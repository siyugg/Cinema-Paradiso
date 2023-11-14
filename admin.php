<?php
require "db_connection.php";
require "createDbMovie.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$queryMovie = "SELECT * FROM Movie";
$stmt = $conn->prepare($queryMovie);
$stmt->execute();
$resultMovie = $stmt->get_result();

$movie = $resultMovie->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $formType = $_POST["form_type"];
    switch ($formType) {
        case "create_movie":
            create_movie($conn);
            echo "test";
            break;
        case "create_new_movie":
            if (isset($_FILES["movieImage"])) {
                if ($_FILES["movieImage"]["error"] == 0) {
                    $movieImage = file_get_contents($_FILES["movieImage"]["tmp_name"]);
                    InsertMovie($conn, $_POST["movieTitle"], $_POST["movieGenre"], $_POST["movieDuration"], $movieImage, $_POST["moviePrice"]);
                } else {
                    echo "File upload error: " . $_FILES["movieImage"]["error"];
                }
            }
            break;
        case "create-new-slot":
            // Corrected syntax error here
            // echo $_POST['slotMovie'] . $_POST['slotDate'] . $_POST['slotLocation'] . $_POST['slotTime'];
            CreateSlot($conn, $_POST["slotMovie"], $_POST["slotDate"], $_POST["slotLocation"], $_POST["slotTime"]);
            break; // Added break statement
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="initialize-form" method="POST">
        <h1 id="wrapper">Intialize Movie </h1>
        <input type="hidden" name="form_type" value="create_movie" id="create_movie">
    </form>
    <div><button type="submit" id="submit-button-create-movie">Click me</button></div>
    <div><br></div>
    <h1>Add New Movie</h1>
    <form id="upload-new-movie-form" method="POST" enctype="multipart/form-data">
        <div id="wrapper">
            <input type="hidden" name="form_type" value="create_new_movie" id="create_new_movie_hidden">
            <label for="movieTitle">Movie Title</label>
            <input type="text" name="movieTitle" id="movieTitle">

            <label for="movieGenre">Movie Genre</label>
            <input type="text" name="movieGenre" id="movieGenre">

            <label for="movieDuration">Movie Duration</label>
            <input type="text" name="movieDuration" id="movieDuration">

            <label for="movieImage">Movie Image</label>
            <input type="file" name="movieImage" id="movieImage">

            <label for="moviePrice">Movie Price</label>
            <input type="text" name="moviePrice" id="moviePrice">

            <input type="submit" value="Upload Movie" id="submit-button-create-new-movie">
        </div>
    </form>

    <h1>Add a Movie Slot</h1>
    <form id="add-movie-slot" method="POST" enctype="multipart/form-data">
        <div id="movie-slot-wrapper">
            <input type="hidden" name="form_type" value="create-new-slot" id="add_new_slot_hidden">
            <label for='slotMovie'>Movie:</label>
            <select id='slotMovie' name='slotMovie'>

            
            <?php foreach ($resultMovie as $movie) {
            
            echo "<option value='" . $movie['MovieID'] . "'> " . $movie['MovieName'] . " </option>";
            
        } ?>
            </select>
            
            <label for="slotDate">Date:</label>
            <input type="date" name="slotDate" id="slotDate">

            <label for="slotLocation">Location:</label>
                <select id="slotLocation" name="slotLocation">
                    <option value="Central">Central</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="North">North</option>
                    <option value="South">South</option>
                </select>
            
            <label for="slotTime">Time:</label>
                <select id="slotTime" name="slotTime">
                    <option value="11:00:00">11:00:00</option>
                    <option value="12:00:00">12:00:00</option>
                    <option value="13:00:00">13:00:00</option>
                    <option value="14:00:00">14:00:00</option>
                    <option value="15:00:00">15:00:00</option>
                    <option value="16:00:00">16:00:00</option>
                    <option value="17:00:00">17:00:00</option>
                    <option value="18:00:00">18:00:00</option>
                    <option value="19:00:00">19:00:00</option>
                    <option value="20:00:00">20:00:00</option>
                    <option value="21:00:00">21:00:00</option>
                    <option value="22:00:00">22:00:00</option>
                    <option value="23:00:00">23:00:00</option>
                </select>

            <input type="submit" value="Add Movie Slot" id="submit-button-add-new-slot">

        </div>
    </form>

    <button><a href="index.php">Back to Home</a></button>

</body>
<script src="script.js"></script>

</html>