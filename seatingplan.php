<?php
require "errors.php";
require "db_connection.php";
session_start();

$movie_id = $_SESSION["movie_id"];
$slot_id = $_SESSION["selectedSlotID"];

if (isset($_SESSION['movie_id'])) {
    // Prepare and execute the SQL query to retrieve movie information
    $sql = "SELECT * FROM movie WHERE MovieID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $movie = $result->fetch_assoc();
} else {
    echo "Movie ID is not set in the session.";
}

/* To display the selected slot information */
try {
    $querySelectedSchedule = "SELECT * FROM schedule WHERE MovieID = ? AND slotID = ?";
    $stmtSelectedSchedule = $conn->prepare($querySelectedSchedule);
    $stmtSelectedSchedule->bind_param("ii", $movie_id, $slot_id);
    $stmtSelectedSchedule->execute();
    $resultSchedule = $stmtSelectedSchedule->get_result();
    $selectedSchedule = $resultSchedule->fetch_assoc();
} catch (PDOException $e) {
    die("Query Failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seats = array();
 
        foreach ($_POST as $key => $seat) {
            echo "Key: " . $key . "";
            echo '<br>';
            $seats[] = $key;
        }
        $movie_price = $movie['moviePrice'];
        $_SESSION['moviePrice'] = $movie_price;
        $_SESSION['seats'] = $seats;
        header("Location: cart.php");
        exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <title>Document</title>
</head>

<body>
    <div id="navbar">
        <nav>
            <a href="index.php"><img src="images/logo.png" class="logo" alt="images" style="width: 300px;"></a>
            <ul class="navbar-list">
                <li><a href="index.php">Movies</a></li>
                <li><a href="locations.html">Locations</a></li>    
                <li><a href="menu.php">Menu</a></li>    
                <li><a href="account-login.php">Login</a></li>    
                <li><a href="cart.php"><img src="images/cart.png" alt="images" style="width: 40px; height: 40px;"></a></li>    
            </ul>
        </nav>
    </div>

    <div id="movie-info-wrapper">
        <div id="movie-info">
            <?php 
                echo "<img src='data:image/jpeg;base64," . base64_encode($movie['MovieImage']) . "' alt='Movie Poster' class='seats-movie-image'><br>";
                echo "<div class='movie-des-wrapper'>"; 
                echo "<h1 class='movie-title'> Movie Title: " . $movie['MovieName'] . "<h1><br>";
                echo "<p class='movie-description'>Movie Duration: " . $movie['MovieDuration'] . "<p><br>";
                echo "<p class='movie-description'>Movie Genre:" . $movie['MovieGenre'] . "<br><p>";
                echo "</div>";  
            ?>
        </div>
        <div id="displaySchedule">
            <?php 
                echo "<h3>Select seat for " . $selectedSchedule['slotDate'] . " at " . $selectedSchedule['slotLocation'] ."</h3>";
                echo "<h3>Time: " . $selectedSchedule['slotTime'] . "</h3>";
            ?>
        </div>
        <div id="main-content">
            <div id="movie-screen">
                <div id="screen">Screen</div>
                <div id="movie">
                    <form method="POST" action="" id="selectedCheckBoxForm">
                        <div id="movieseats">
                            <?php
                            $querySeats = "SELECT * FROM Seats WHERE MovieID = ? AND slotID = ?";
                            $stmtSeats = $conn->prepare($querySeats);
                            $stmtSeats->bind_param("ii", $movie_id, $slot_id);
                            $stmtSeats->execute();
                            $resultSeats = $stmtSeats->get_result();

                            foreach ($resultSeats as $seat) {
                                if ($seat["IsBooked"] == 0) {  // If the seat is not booked
                                    echo "<input type='checkbox' id=" . $seat["SeatNumber"] . " class='seat' name=" . $seat["SeatNumber"] . ">";
                                    echo "<label for=" . $seat["SeatNumber"] . " class='seat-label seat'>" . $seat["SeatNumber"] . " </label>";
                                } else {  // If the seat is booked
                                    echo "<input type='checkbox' id=" . $seat["SeatNumber"] . " class='seat booked' name=" . $seat["SeatNumber"] . " disabled>";
                                    echo "<label for=" . $seat["SeatNumber"] . " class='seat-label booked-label'>" . $seat["SeatNumber"] . " </label>";
                                }
                            } ?>
                        </div>
                </div>
            </div>
            <div id="checkout-container">
                <h3 class="selected-seats">Selected Seats</h3>
                <div id="print-seats">
                    <div id="checkout-seats">
                     
                    </div>
                </div>
                <button type="submit" value='Checkout' id="checkout-button">Add to Cart</button>
            </div>
            </form>
        </div>
    </div>
</body>
<script src="script.js"></script>

</html>