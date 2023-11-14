<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);


require "db_connection.php";

$query = "SELECT * FROM Movie";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if ($stmt->execute()) {
    // Fetch data using $result
} else {
    echo "Error executing the query: " . $stmt->error;
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
                <a href="index.html"><img src="images/home/logo.png" class="logo" alt="images" style="width: 300px;"></a>
                <ul class="navbar-list">
                    <li><a href="index.php">Movies</a></li>
                    <li><a href="locations.html">Locations</a></li>    
                    <li><a href="menu.html">Menu</a></li>    
                    <li><a href="account-login.php">Login</a></li>    
                    <li><a href="cart.php"><img src="images/home/cart.png" alt="images" style="width: 40px; height: 40px;"></a></li>    
                </ul>
            </nav>

        </div>

        <div class="movie-selection">


            <div class="movie-list">
                <table class="movie-list-container">

                    <?php foreach ($result as $movie) {
                        echo "<td>";
                        echo "<form method='POST' action=''>";
                        echo "<div id='movie-wrapper'>";
                        echo "<input type='hidden' name='movie_id' value='" . $movie['MovieID'] . "'/>";
                        echo "<img class='movie-image' src='data:image/jpeg;base64," . base64_encode($movie['MovieImage']) . "' />";
                        echo "<div> Movie Title: " . $movie['MovieName'] . " </div>";
                        echo "<div> Movie Genre: " . $movie['MovieGenre'] . " </div>";
                        echo "<div> Duration: " . $movie['MovieDuration'] . " </div>";
                        echo "<input type='submit' class='buy-ticket' value='Book Now'  />";
                        echo "</div>";
                        echo "</form>";
                        echo "</td>";
                    } ?>

                </table>
            </div>
        </div>
    </div>
    <footer>
        <div><a href="admin.php">Admin Page</a></div>
        <ul class="more-information">
            <li>| Home |</li>
            <li>Booking Information |</li>
            <li>Account  |</li>
            <li>Contact Us |</li>
            <li>F.A.Q |</li>
        </ul>
    </footer>
                
</body>
<script src="script.js"></script>

</html>

