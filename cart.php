<?php
session_start();

// Initialize the cart session variable if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle emptying the cart
if (isset($_GET['empty'])) {
    unset($_SESSION['cart']);
    header('location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Function to get quantity for an item
function getQuantity($itemID) {
    return isset($_SESSION['quantity'][$itemID]) ? $_SESSION['quantity'][$itemID] : 1;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet">
    <title>Cinema</title>
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
        <form action="checkout.php" method="post">
        <table class="cart">
            <tr>
                <td>
                    <h3><strong> Menu </strong></h3>
                </td>
                <td>
                    <h3><strong> Description </strong></h3>
                </td>
                <td>
                    <h3><strong> Price </strong></h3>
                </td>
                <td>
                    <h3><strong> Quantity </strong></h3>
                </td>
            </tr>
            <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item) {
                    echo "<tr>";
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($item['MenuImage']) . "' style='width: 200px; height: 200px;'></td>";
                    echo "<td style='display: none;'>" . $item['MenuName'] . "</td>"; // Hide the MenuName column
                    echo "<td>" . $item['MenuDescription'] . "</td>";
                    echo "<td>$" . number_format($item['MenuPrice'], 2) . "</td>";
                    echo "<td>" . getQuantity($item['MenuID']) . "</td>";
                    echo "</tr>";
                    $total += $item['MenuPrice'] * getQuantity($item['MenuID']);
                }
                
                ?>
      <tr>
    <th align='right' style='color: white; font-size: 24px; '>Total:</th>
    <th align='right' style='color: white; font-size: 24px; '>$<?php echo number_format($total, 2); ?></th>
</tr>
    
    </table>
    <br><br>
    <div class="checkoutcart">
    <label for="email">Email:</label>
        <input type="email" name="email" required><br>
    <input type="submit" value="Checkout Now"><br><br>
    </form>
    </div>
     

    <div class="empty">
        <a href="index.php"><button >Continue Shopping</button></a> &nbsp
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?empty=1"><button>Empty your cart</button></a><br><br><br><br>
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
