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
    <body>
    <div>
        <a href="F_menedzs.php">Felhasznaló Menedzsment</a>
    </div>
    <div>
        <a href="ujjitasok_admin.php">ujjitasok</a>
    </div>
    <div>
        <a href="profil.php">profil</a>
    </div>

    <h1>admin</h1>

    <script src="main.js"></script>
    </body>
</html>
