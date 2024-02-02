<?php
session_start();
require "errors.php";
require "db_connection.php";

$query = "SELECT * FROM movie";
$stmt = $conn->prepare($query);


if (!$stmt) {
    echo "Error preparing the query: " . $conn->error;
} else {
    $stmt->execute();
    $result = $stmt->get_result();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_id = $_POST["movie_id"];
    $_SESSION["movie_id"] = $movie_id;
    echo $movie_id;
    header("Location: bookmovie.php");
}
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" />
    <title>Cinema Paradiso</title>
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

        <div class="movie-selection">
            <ul class="tabs">
                <li data-tab-target="#now" class="active tab">Now Showing</li>
                <li data-tab-target="#soon" class="tab">Coming Soon</li>
            </ul>
            <div class="movie-list">
                <div id="now" data-tab-content class="active">
                    <table class="movie-list-container">
                        <?php
                            $count = 0; // Initialize counter
                            
                            while ($count < 5 && ($row = $result->fetch_assoc()) !== null) {
                                echo "<td>";
                                echo "<form method='POST' action=''>";
                                echo "<div id='movie-wrapper'>";
                                echo "<input type='hidden' name='movie_id' value='" . $row['MovieID'] . "'/>";
                                echo "<img class='movie-image' src='data:image/jpeg;base64," . base64_encode($row['MovieImage']) . "' />";
                                echo "<div class='movie-title'> " . $row['MovieName'] . " </div>";
                                echo "<br>";
                                echo "<div class='movie-genre'>  Genre: " . $row['MovieGenre'] . " </div>";
                                echo "<div class='movie-duration'> Duration: " . $row['MovieDuration'] . " minutes </div>";    
                                echo "<br>";                
                                echo "<input type='submit' class='buy-ticket' value='Book Now'  />";
                                echo "</div>";
                                echo "</form>";
                                echo "</td>";
                        
                                $count++; // Increment counter
                            }
                        ?>
                    </table>
                </div>
        </div>
        <div class="movie-list">
            <div id="soon" data-tab-content class="active">
                <table class="movie-list-container">
                <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<td>";
                        echo "<form method='POST' action=''>";
                        echo "<div id='movie-wrapper'>";
                        echo "<input type='hidden' name='movie_id' value='" . $row['MovieID'] . "'/>";
                        echo "<img class='movie-image' src='data:image/jpeg;base64," . base64_encode($row['MovieImage']) . "' />";
                        echo "<div class='movie-title'> " . $row['MovieName'] . " </div>";
                        echo "<br>";
                        echo "<div class='movie-genre'>  Genre: " . $row['MovieGenre'] . " </div>";
                        echo "<div class='movie-duration'> Duration: " . $row['MovieDuration'] . " minutes </div>";    
                        echo "<br>";                
                        echo "</div>";
                        echo "</form>";
                        echo "</td>";
                    }
                ?>
</table>
    </div></div></div>
    <footer>
        <div><a href="admin.php">Admin Page</a></div>
        <ul class="more-information">
            <li><a href="index.php">| Home | </a></li>
            <li><a href="locations.html">Locations | </a></li>
            <li><a href="booking.php">Bookings | </a></li>
        </ul>
    </footer>
                
</body>
<script src="script.js"></script>

</html>