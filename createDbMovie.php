<?php
require_once "db_connection.php";
require_once "errors.php";

function InsertMovie(object $conn, string $movieTitle, string $movieGenre, string $movieDuration, string $movieImage, string $moviePrice)
{
    try {
        $stmt = $conn->prepare("INSERT INTO MOVIE (MovieName, MovieGenre, MovieDuration, MovieImage, MoviePrice) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $movieTitle, $movieGenre, $movieDuration, $movieImage, $moviePrice);
        $stmt->execute();
        $movieId = $conn->insert_id;
        $stmt->close();
        echo "Movies inserted successfully!";
    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
}

function CreateSlot(object $conn, int $slotMovie, string $slotDate, string $slotLocation, string $slotTime)
{
    try {
        $stmt = $conn->prepare("INSERT INTO schedule (MovieID, slotDate, slotLocation, slotTime) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $slotMovie, $slotDate, $slotLocation, $slotTime);
        $stmt->execute();
        $slotId = $conn->insert_id;
        //query slotid here
        createSeats($conn, $slotId, $slotMovie); //create seat for each movie
        $stmt->close();
        echo "Movie Slot created successful";
    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
}

function createSeats(object $conn, int $slotId, int $movieId){
    require "db_connection.php";
    $rows = ['A', 'B', 'C', 'D', 'E', 'F']; // Example row values
    $isBooked = 0;
    $stmt = $conn->prepare("INSERT INTO Seats (slotID, MovieID, SeatNumber, IsBooked) VALUES (?,?,?,?)");
    foreach ($rows as $row) {
        for ($j = 1; $j <= 10; $j++) {
            $seat = $row . $j;
            try {
                $stmt->bind_param("iisi", $slotId, $movieId, $seat, $isBooked);
                $stmt->execute();
            } catch (PDOException $e) {
                die("Query Failed: " . $e->getMessage());
            }
        }
    }
}

?>