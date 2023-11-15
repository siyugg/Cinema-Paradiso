<?php
if (session_status() == PHP_SESSION_NONE) {
    // Only start the session if it's not already started
    session_start();
}

// Function to get quantity for an item
function getQuantity($itemID) {
    return isset($_SESSION['quantity'][$itemID]) ? $_SESSION['quantity'][$itemID] : 1;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $host = 'localhost';
    $username = 'f32ee';
    $password = 'password';
    $database = 'project';

    // Connect to the database
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert order details into the database
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        $itemName = $_SESSION['cart'][$i]['MenuName'];  // Update this based on your item structure
        $quantity = getQuantity($itemName);
        $price = $_SESSION['cart'][$i]['MenuPrice'];
        $image = $_SESSION['cart'][$i]['MenuImage'];  // Update this based on your item structure
        $description = $_SESSION['cart'][$i]['MenuDescription'];
        $email = $_POST['email'];

        // Use a prepared statement to prevent SQL injection
        $insertOrderQuery = $conn->prepare("INSERT INTO checkout (email, item_name, quantity, price, image, description) VALUES (?, ?, ?, ?, ?, ?)");

        // Check if the prepared statement was created successfully
        if ($insertOrderQuery === false) {
            die('Error preparing the statement.');
        }

        // Bind parameters to the placeholders
        $insertOrderQuery->bind_param("ssdsss", $email, $itemName, $quantity, $price, $image, $description);

        // Execute the prepared statement
        $insertOrderQuery->execute();

        // Check if the execution was successful
        if ($insertOrderQuery->affected_rows <= 0) {
            die('Error inserting data into the database.');
        }

        // Close the prepared statement for each iteration
        $insertOrderQuery->close();
    }

    // Close the database connection
    $conn->close();

    // Clear the cart after placing the order
    unset($_SESSION['cart']);
    unset($_SESSION['quantity']);

    // Redirect to a thank you page or any other page as needed
    header('Location: booking.php');
    exit();
}
?>
