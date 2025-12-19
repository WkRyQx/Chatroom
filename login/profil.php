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

$velemenyek_szama = 0;
$velemeny_szamlalo = "SELECT COUNT(*) as count FROM velemeny WHERE id = ?";
if ($stmt = $conn->prepare($velemeny_szamlalo)) {
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $row = $result->fetch_assoc();
        $velemenyek_szama = isset($row['count']) ? (int)$row['count'] : 0;
    }
    $stmt->close();
} else {

}
$reakciok_szama = 0;
$reakcio_szamlalo = "SELECT COUNT(*) as count2 FROM reagalas_velemenyre WHERE id = ?";
if ($stmt = $conn->prepare($reakcio_szamlalo)) {
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $row = $result->fetch_assoc();
        $reakciok_szama = isset($row['count2']) ? (int)$row['count2'] : 0;
    }
    $stmt->close();
} else {

}



?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
        <link rel="shortcut icon" href="logo.png" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
        <link rel="stylesheet" href="style.css">
        <title>Poolchat</title>
    </head>
    <body>
        <div class="profil-container">
            <form method="get" action="home.php">
                <button type="submit" class="visszaBtn"><i class="fa-solid fa-arrow-left"></i></button>
            </form>
            <h1>Profil:</h1>
            <div class="theme-toggle">
                <button id="light-mode" class="theme-btn active">
                    <i class="fas fa-sun"></i> Light Mode
                </button>
                <button id="dark-mode" class="theme-btn">
                    <i class="fas fa-moon"></i> Dark Mode
                </button>
            </div>
                    <ul>
                        <li>Név: <?php echo( htmlspecialchars($_SESSION['user_name'])); ?></li>
                        <li>Email: <?php echo( htmlspecialchars($_SESSION['user_email'])); ?></li>
                        <li>Neme: <?php echo( htmlspecialchars($_SESSION['user_gender'])); ?></li>
                        <li>Életkor: <?php echo( htmlspecialchars($_SESSION['user_age'])); ?></li>
                        <li>Iskola: <?php echo( htmlspecialchars($_SESSION['user_school'])); ?></li>
                        <li>Jelszo: *********</li>
                    </ul>
                <form method="Post" action="kijelentkezes.php">
                    <div class="lo-reg-btn">
                        <button type="submit" class="btn white-btn ">Kijelentkezés</button>
                    </div>
                </form>
            <div>
                <p>Vélemények száma: <?php echo( htmlspecialchars($velemenyek_szama ?? 0)); ?></p>
            </div>

            <div>
                <p>Reakciók/Likek száma: <?php   echo(htmlspecialchars($reakciok_szama ?? 0)); ?></p>
            </div>




        </div>











    <script src="main.js"></script>
    </body>
</html>
