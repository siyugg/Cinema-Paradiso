<?php

require "errors.php";
require "db_connection.php";
session_start();

// Define variables to hold the form input and results
$email = '';
$result = array();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve bookings based on the provided email
    $email = $_POST['email'];

    $viewBookingsQuery = $conn->prepare("SELECT item_name, quantity, price, description FROM checkout WHERE email = ?");
    $viewBookingsQuery->bind_param("s", $email);
    $viewBookingsQuery->execute();

    $viewBookingsQuery->bind_result($item_name, $quantity, $price, $description);

    // Fetch results
    while ($viewBookingsQuery->fetch()) {
        $result[] = array('item_name' => $item_name, 'quantity' => $quantity, 'price' => $price, 'description' => $description);
    }

    $viewBookingsQuery->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Paradiso Bookings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <div class="navbar">
            <nav>
                <a href="index.php" class="logo"><img src="images/logo.png" alt="images" style="width: 240px; height: 150px;"></a>
                <ul class="navbar-list">
                    <li><a href="index.php">Movies</a></li>
                    <li><a href="locations.html">Locations</a></li>    
                    <li><a href="menu.php">Menu</a></li>    
                    <li><a href="booking.php">Bookings</a></li>    
                    <li><a href="cart.php"><img src="images/cart.png" alt="images" style="width: 40px; height: 40px;"></a></li>    
                </ul>
            </nav>
        </div>

        <div class="verify">
            <h2>Enter your email to view bookings:</h2>
            <form action="booking.php" method="post">
                <label for="email" style=" color:#fff padding:10px">Email:</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required>
                <input type="submit" value="View Bookings">
            </form>

            <?php if (is_array($result) && count($result) > 0): ?>
            <h2>Order Summary for <?php echo $email; ?></h2>
            <table class="booking">
                <tr>
                    <td>Item</td>
                    <td>Description</td>
                    <td>Quantity</td>
                    <td>Price</td>
                    
                </tr>
                <?php foreach ($result as $row): ?>
                <tr>
                    <td><?php echo $row['item_name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>$<?php echo $row['price']; ?></td>
                    
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <footer>
    <ul class="more-information">
            <li><a href="index.php">| Home | </a></li>
            <li><a href="locations.html">Locations | </a></li>
            <li><a href="booking.php">Bookings  | </a></li>
        </ul>
    </footer>
</body>
</html>
