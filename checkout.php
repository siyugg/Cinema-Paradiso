<?php

if (session_status() == PHP_SESSION_NONE) {
    // Only start the session if it's not already started
    session_start();
}
require "errors.php";
require "db_connection.php";

$seats = $_SESSION["seats"];
$movie_id = $_SESSION['movie_id'];
$slot_id = $_SESSION['selectedSlotID'];
$movie_price = $_SESSION['moviePrice'];

/* Query for movie name and price */
$queryCartToMovie = "SELECT MovieName, MoviePrice FROM movie WHERE MovieID=?";
$fromCart1 = $conn->prepare($queryCartToMovie);
$fromCart1->bind_param("i", $movie_id);
$fromCart1->execute();
$resultFromMovie = $fromCart1->get_result();
$getMovieInfo = $resultFromMovie->fetch_assoc();

/* Query for selected schedule */
$queryCartToSchedule = "SELECT * FROM schedule WHERE slotID=?";
$fromCart2 = $conn->prepare($queryCartToSchedule);
$fromCart2->bind_param("i", $slot_id);
$fromCart2->execute();
$resultFromSchedule = $fromCart2->get_result();
$getScheduleInfo = $resultFromSchedule->fetch_assoc();


// Function to get quantity for an item
function getQuantity($itemID) {
    return isset($_SESSION['quantity'][$itemID]) ? $_SESSION['quantity'][$itemID] : 1;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert order details into the database
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        $itemName = $_SESSION['cart'][$i]['MenuName'];
        $quantity = getQuantity($itemName);
        $price = $_SESSION['cart'][$i]['MenuPrice'];
        $description = $_SESSION['cart'][$i]['MenuDescription'];
        $email = $_POST['email'];

        $insertOrderQuery = $conn->prepare("INSERT INTO checkout (email, item_name, quantity, price, description) VALUES (?, ?, ?, ?, ?)");

        // Check if the prepared statement was created successfully
        if ($insertOrderQuery === false) {
            die('Error preparing the statement.');
        }

        $insertOrderQuery->bind_param("ssdss", $email, $itemName, $quantity, $price, $description);
        $insertOrderQuery->execute();

        // Check if the execution was successful
        if ($insertOrderQuery->affected_rows <= 0) {
            die('Error inserting data into the database.');
        }
        $insertOrderQuery->close();
    }

    // Update seat availability
    $updateSeats = "UPDATE seats SET IsBooked = 1 WHERE SeatNumber = ? AND slotID = ?";
    $stmtUpdateSeats = $conn->prepare($updateSeats);
    if (!$stmtUpdateSeats) {
        die("Query preparation failed: " . $conn->error);
    }

    foreach ($seats as $seat) {
        echo $seat;
        if (!$stmtUpdateSeats->bind_param("si", $seat, $slot_id)) {
            die("Binding parameters failed: " . $stmtUpdateSeats->error);
        }

        if (!$stmtUpdateSeats->execute()) {
            die("Query execution failed: " . $stmtUpdateSeats->error);
        }
    }

    $stmtUpdateSeats->close();
    
    $movieName = $getMovieInfo['MovieName'];
    $seatCount = count($seats);
    $moviePrice = $movie_price * $seatCount;
    $email = $_POST['email'];

    $seatString = implode(" ", $seats);
    $movieDescription = "Date: " . $getScheduleInfo['slotDate'] . 
                            " | Location: " . $getScheduleInfo['slotLocation'] .
                            " | Time: " . $getScheduleInfo['slotTime'] . 
                            " | Seat Number: ". $seatString ;
                        
    $queryInsertMovie = "INSERT INTO checkout (email, item_name, quantity, price, description) VALUES (?,?,?,?,?)";
    $insertMovieQuery = $conn->prepare($queryInsertMovie);
    $insertMovieQuery->bind_param("ssdss", $email, $movieName, $seatCount, $moviePrice, $movieDescription);
    $insertMovieQuery->execute();
    $insertMovieQuery->close();
    
    $conn->close();

    // Clear the cart after placing the order
    unset($_SESSION['cart']);
    unset($_SESSION['quantity']);
    unset($_SESSION['seats']);
    unset($_SESSION['movie_id']);
    unset($_SESSION['selectedSlotID']);

    header('Location: booking.php');
    exit();
}
?>
