<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

require "db_connection.php";

$query = "SELECT * FROM menu";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo "Error preparing the query: " . $conn->error;
} else {
    $stmt->execute();
    $result = $stmt->get_result();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy']) && isset($_POST['quantity'])) {
    $itemName = urldecode($_POST['buy']);
    $quantity = (int)$_POST['quantity'];

    if ($quantity < 1) {
        echo "Invalid quantity";
    } else {
        // Find the item in the result set
        $itemDetails = null;
        while ($row = $result->fetch_assoc()) {
            if ($row['MenuName'] === $itemName) {
                $itemDetails = array(
                    'MenuID' => $row['MenuID'],
                    'MenuDescription' => $row['MenuDescription'],
                    'MenuPrice' => $row['MenuPrice'],
                    'MenuImage' => $row['MenuImage'],
                    'MenuName' => $row['MenuName']
                );
                break; // Stop searching once found
            }
        }

        if ($itemDetails !== null) {
            // Add the item to the cart
            for ($i = 0; $i < $quantity; $i++) {
                $_SESSION['cart'][] = $itemDetails;
            }

            // Redirect to avoid resubmission on refresh
            header('location: ' . $_SERVER['PHP_SELF'] . '?' . SID);
            exit();
        } else {
            echo "Item not found in the database.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="script.js" defer></script>
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
        <h1>Menu</h1>
        <ul class="tabs">
            <li data-tab-target="#alacarte" class="active tab">A la carte</li>
            <li data-tab-target="#combo" class="tab">Combo Meals</li>
        </ul>

        <div class="tab-content">
            <div id="alacarte" data-tab-content class="active">
                <h2>A la Carte</h2>

                <table class="menu">
                    <tr>
                        <td>
                            <h3><strong>Menu</strong></h3>
                        </td>
                        <td>
                            <h3><strong>Description</strong></h3>
                        </td>
                        <td>
                            <h3><strong>Price</strong></h3>
                        </td>
                        <td>
                            <h3><strong>Quantity</strong></h3>
                        </td>
                    </tr>
                    <?php
                    $count = 0; // Initialize counter

                  while ($count < 4 && ($row = $result->fetch_assoc()) !== null) {
                  echo "<tr>";
                  echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['MenuImage']) . "' style='width: 200px; height: 200px;' /></td>";
                  echo "<td>" . $row['MenuDescription'] . "</td>";
                  echo "<td>$" . number_format($row['MenuPrice'], 2) . "</td>";
                  echo "<td>";
                  echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
                  echo "<input type='hidden' name='buy' value='" . urlencode($row['MenuName']) . "'>";
                  echo "<input type='number' name='quantity' value='1' min='1' max='10'>";
                  echo "<input type='submit' value='Add to Cart'>";
                  echo "</form>";
                  echo "</td>";
                  echo "</tr>";

                  $count++; // Increment counter
                  }
                 ?>




                </table>
            </div>
            <div id="combo" data-tab-content>
                <h2>Combo Meals</h2>
                <table class="menu">
                    <tr>
                        <td>
                            <h3><strong>Menu</strong></h3>
                        </td>
                        <td>
                            <h3><strong>Description</strong></h3>
                        </td>
                        <td>
                            <h3><strong>Price</strong></h3>
                        </td>
                        <td>
                            <h3><strong>Quantity</strong></h3>
                        </td>
                    </tr>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['MenuImage']) . "' style='width: 200px; height: 200px;' /></td>";
                    echo "<td>" . $row['MenuDescription'] . "</td>";
                    echo "<td>$" . number_format($row['MenuPrice'], 2) . "</td>";
                    echo "<td>";
                    echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
                    echo "<input type='hidden' name='buy' value='" . urlencode($row['MenuName']) . "'>";
                    echo "<input type='number' name='quantity' value='1' min='1' max='10'>";
                    echo "<input type='submit' value='Add to Cart'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                     }
                    ?>

                </table>
            </div>
        </div>
    </div>

    <footer>
        <ul class="more-information">
            <li><a href="index.php">| Home | </a></li>
            <li><a href="locations.html">Locations | </a></li>
            <li><a href="booking.php">Bookings | </a></li>
        </ul>
    </footer>
</body>

</html>
