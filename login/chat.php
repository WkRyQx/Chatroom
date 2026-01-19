<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";        
$dbname = "poolchat";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Hiba a kapcsolódáskor: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <link rel="shortcut icon" href="logo.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
        <link rel="stylesheet" href="main.css">
        <title>Poolchat</title>
    </head>
            <form method="get" action="home.php">
                <button type="submit" class="visszaBtn"><i class="fa-solid fa-arrow-left"></i></button>
            </form>



    <script src="main.js"></script>
    </body>
</html>
