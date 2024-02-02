<?php
session_start();
require "db_connection.php";

$seats = isset($_SESSION["seats"]) ? $_SESSION["seats"] : array();
$slot_id = isset($_SESSION['selectedSlotID']) ? $_SESSION['selectedSlotID'] : null;
$movie_id = isset($_SESSION['movie_id']) ? $_SESSION['movie_id'] : null;

// Initialize the cart session variable if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle emptying the cart
if (isset($_GET['empty'])) {
    unset($_SESSION['cart']);
    unset($_SESSION['seats']);
    unset($_SESSION['selectedSlotID']);
    unset($_SESSION['movie_id']);
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
        </div>
        <div class="show-cart">
            <h2> Cart </h2></br>
            <form action="checkout.php" method="post" id="checkoutCart">
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
                    if (isset($_GET['empty'])) {
                        // Remove the movie row from the table
                        echo "<tr>";
                        echo "<td colspan='4'>Cart is empty</td>";
                        echo "</tr>";
                    } else {
                        $total = 0;

                        foreach ($_SESSION['cart'] as $item) {
                            echo "<tr>";
                            echo "<td><img src='data:image/jpeg;base64," . base64_encode($item['MenuImage']) . "' style='width: 200px; height: 200px;'></td>";
                            echo "<td style='display: none;'>" . $item['MenuName'] . "</td>"; 
                            echo "<td>" . $item['MenuDescription'] . "</td>";
                            echo "<td>$" . number_format($item['MenuPrice'], 2) . "</td>";
                            echo "<td>" . getQuantity($item['MenuID']) . "</td>";
                            echo "</tr>";
                            $total += $item['MenuPrice'] * getQuantity($item['MenuID']);
                        }
                        
                        $getMovieInfo = null;
                        $getScheduleInfo = null;
                        echo "<tr>";
                        if ($movie_id !== null && $slot_id !== null) {
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

                            echo "<td>" . $getMovieInfo['MovieName'] . "</td>";
                            echo "<td>Date: " . $getScheduleInfo['slotDate'] . 
                                " | Location: " . $getScheduleInfo['slotLocation'] .
                                " | Time: " . $getScheduleInfo['slotTime'] . 
                                " | Seat Number: ";
                            foreach ($seats as $seat) {
                                echo $seat;
                                echo " ";
                            }
                            echo "</td>";
                            echo "<td>$" . $getMovieInfo['MoviePrice'] . "</td>";
                            echo "<td>" . count($seats) . "</td>";
                        }
                        $total += $getMovieInfo['MoviePrice'] * count($seats);
                        echo "</tr>";
  
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
                </div>
            </form>
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('checkoutCart').addEventListener('submit', function () {
                    alert('Checkout Success!');
                });
            });
            </script>
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
