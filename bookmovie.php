<?php
require "errors.php";
require "db_connection.php";

session_start();
$movie_id = $_SESSION["movie_id"];
unset($_SESSION['cart']);

// Get movie information
if (isset($_SESSION['movie_id'])) {

    // Prepare and execute the SQL query to retrieve movie information
    $queryMovie = "SELECT * FROM movie WHERE MovieID = ?";
    $stmtMovie = $conn->prepare($queryMovie);
    $stmtMovie->bind_param("i", $movie_id);
    $stmtMovie->execute();
    $resultMovie = $stmtMovie->get_result();

    $movie = $resultMovie->fetch_assoc();
    
    } else {
    echo "Movie ID is not set in the session.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedDate'])){
    $_SESSION['selected_date'] = $_POST['selectedDate'];
    $selected_date = $_SESSION['selected_date'];
    echo "the selected date is:";
    echo $selected_date;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedSlot'])) {
    list($slotID, $movie_id) = explode('|', $_POST['selectedSlot']);
    
    $_SESSION["selectedSlotID"] = $slotID;
    $_SESSION["movie_id"] = $movie_id;
    header("Location: seatingplan.php");
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
        <a href="index.php" class="logo"><img src="images/logo.png" alt="images" style="width: 240px; height: 150px;"></a>
            <ul class="navbar-list">
                <li><a href="index.php">Movies</a></li>
                <li><a href="locations.html">Locations</a></li>    
                <li><a href="menu.html">Menu</a></li>    
                <li><a href="booking.php">Bookings</a></li>    
                <li><a href="cart.php"><img src="images/cart.png" alt="images" style="width: 40px; height: 40px;"></a></li>    
            </ul>
        </nav>
    </div>

    <div id="movie-info-wrapper">
        <div id="movie-info">
            <?php 
                echo "<img src='data:image/jpeg;base64," . base64_encode($movie['MovieImage']) . "' alt='Movie Poster' class='slot-movie-image'><br>";
                echo "<div class='movie-des-wrapper'>"; 
                echo "<h1 class='movie-title'> Movie: " . $movie['MovieName'] . "<h1>";
                echo "<p class='movie-description'>Synopsis: " . $movie['MovieDescription'] . "<p><br>";
                echo "<p class='movie-others'> Duration: " . $movie['MovieDuration'] . " minutes <p>";
                echo "<p class='movie-others'> Genre:" . $movie['MovieGenre'] . "<br><p>";
                echo "</div>";  
            ?>
        </div>
            
        <div class="movie-schedule">
            <h3>Movie Showtimes</h3>
            <table class="schedule-table" border="1">
            <tr>
                <td></td>
                <td>
                    <form method="POST" action="bookmovie.php" id="selectDateForm"> 
                        <select name="selectedDate" class="select-date-slot" id="selectedDate">
                            <?php
                            /* Get movie schedule */ 
                            $querySchedule = "SELECT DISTINCT slotDate FROM schedule where MovieID = ?";
                            $stmtSchedule = $conn->prepare($querySchedule);
                            $stmtSchedule->bind_param("i", $movie_id);
                            $stmtSchedule->execute();
                            $resultSchedule = $stmtSchedule->get_result();
                            while ($row = $resultSchedule->fetch_assoc()) {
                                $date = $row['slotDate'];
                                $_SESSION['selected_date'] = $date;
                                echo '<option value="'.$date. '">' . $date. '</option>';
                            }
                            ?>
                        </select>
                        <button type="submit" class="select-date-button">Check for Slots</button>

                    </form>
                </td>
            </tr>
            <form method="POST" action="" id="selectSlotForm">
            <?php
                if (isset($_SESSION['selected_date'])){
                    $querySchedule = "SELECT * FROM schedule WHERE MovieID = ? AND slotDate=?";
                    $stmtSchedule = $conn->prepare($querySchedule);
                    $stmtSchedule->bind_param("is", $movie_id, $selected_date);
                    $stmtSchedule->execute();
                    $resultSchedule = $stmtSchedule->get_result();

                    $data = array();
                    while ($row = $resultSchedule->fetch_assoc()) {
                        $data[] = $row;
                    }
                    //Iterate through locations
                    $locations = array("Yishun", "Jurong East", "Orchard", "Paya Lebar", "Vivocity");
                    foreach ($locations as $location) {
                        echo '<tr>';
                        echo '<td>' . $location . '</td>';

                        $slotID = "";
                        echo '<td>';
                        foreach ($data as $row) {
                            if ($row['slotLocation'] == $location && $row['slotDate'] == $selected_date) {
                                $time = $row['slotTime'];
                                $slotID = $row['slotID'];

                                echo '<button type="submit" name="selectedSlot" value="' . $slotID . '|' . $movie_id . '" class="select-slot-button">' . $time . '</button>';
                            }
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                }
            ?>
            </form>
        </table>
        </div>
    </div>

    <footer>
        <div><a href="admin.php">Admin Page</a></div>
            <ul class="more-information">
                <li><a href="index.php">| Home | </a></li>
                <li><a href="locations.html">Locations | </a></li>
                <li><a href="booking.php">Bookings  | </a></li>
            </ul>
    </footer>
</body>
<script src="script.js"></script>

</html>




