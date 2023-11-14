<?php
require_once "db_connection.php";
require_once "errors.php";

function create_movie(object $conn)
{

    // Rename the array to $movie_data for clarity
    $movie_data = [
        [
            'foodname' => 'Lost Horizon',
            'cuisine' => 'Scary',
            'food_description' => 'A vast desert landscape...',
            'price' => '12.99',
            'image_data' => file_get_contents("movie1.png")
        ],
        [
            'foodname' => 'Echoes of the Past',
            'cuisine' => 'Love',
            'food_description' => 'A bustling modern city street...',
            'price' => '9.99',
            'image_data' => file_get_contents("batman.png")
        ],
    ];

    try {
        $stmt = $conn->prepare("INSERT INTO movie (MovieName, MovieGenre, MovieDuration, MovieImage) VALUES (?, ?, ?, ?)");

        foreach ($movie_data as $movie_item) {
            // Bind parameters
            $stmt->bind_param("ssss", $movie_item['foodname'], $movie_item['cuisine'], $movie_item['food_description'], $movie_item['image_data']);

            // Execute the prepared statement
            $stmt->execute();
            $movieId = $conn->insert_id;
            createSeats($conn, $movieId); ///create seat for each movie
        }

        // Close the prepared statement
        $stmt->close();
        echo "Movies inserted successfully!";
    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
}

function InsertMovie(object $conn, string $movieTitle, string $movieGenre, string $movieDuration, string $movieImage, string $moviePrice)
{
    try {
        $stmt = $conn->prepare("INSERT INTO MOVIE (MovieName, MovieGenre, MovieDuration, MovieImage, MoviePrice) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $movieTitle, $movieGenre, $movieDuration, $movieImage, $moviePrice);
        $stmt->execute();
        $movieId = $conn->insert_id;
        //createSeats($conn, $movieId); ///create seat for each movie
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
        createSeats($conn, $slotId, $slotMovie); ///create seat for each movie
        $stmt->close();
        echo "Movie Slot created successful";
    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
}

function createSeats(object $conn, int $slotId, int $movieId)
{

    require "db_connection.php";
    $rows = ['A', 'B', 'C', 'D', 'E', 'F']; // Example row values
    $isBooked = 0;
    $stmt = $conn->prepare("INSERT INTO Seats (slotID, MovieID, SeatNumber, IsBooked) VALUES (?,?,?,?)");
    foreach ($rows as $row) {
        for ($j = 1; $j <= 10; $j++) {
            $seat = $row . $j;
            // echo "Debug: slotId=$slotId, movieId=$movieId, seat=$seat\n";
            try {
                $stmt->bind_param("iisi", $slotId, $movieId, $seat, $isBooked);
                $stmt->execute();
            } catch (PDOException $e) {
                die("Query Failed: " . $e->getMessage());
            }
        }
    }
    // $stmt->close();
}


?>