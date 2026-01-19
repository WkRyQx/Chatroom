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

$success = "";
$error = "";


function isAdmin() {
    return isset($_SESSION['user_szerepkor']) && $_SESSION['user_szerepkor'] == 1;
}

function time_ago_hu($datetimeStr) {
    if (empty($datetimeStr)) return 'Ismeretlen idő';
    try {
        $then = new DateTime($datetimeStr);
    } catch (Exception $e) {
        return 'Ismeretlen idő';
    }
    $now = new DateTime();
    $diffSeconds = $now->getTimestamp() - $then->getTimestamp();
    if ($diffSeconds < 0) return 'most';
    if ($diffSeconds < 60) return $diffSeconds . ' másodperce';
    $diffMinutes = floor($diffSeconds / 60);
    if ($diffMinutes < 60) return $diffMinutes . ' perce';
    $diffHours = floor($diffMinutes / 60);
    if ($diffHours < 24) return $diffHours . ' órája';
    $diffDays = floor($diffHours / 24);
    if ($diffDays < 7) return $diffDays . ' napja';
    $diffWeeks = floor($diffDays / 7);
    if ($diffWeeks < 5) return $diffWeeks . ' hete';
    $diffMonths = floor($diffDays / 30);
    if ($diffMonths < 12) return $diffMonths . ' hónapja';
    $diffYears = floor($diffDays / 365);
    return $diffYears . ' éve';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_velemeny'])) {

    if (!isAdmin()) {
        $error = "Nincs jogosultságod törölni.";
    } else {
        $velemeny_id = (int)$_POST['velemeny_id'];

        $stmt = $conn->prepare("DELETE FROM velemeny WHERE velemeny_id = ?");
        $stmt->bind_param("i", $velemeny_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Vélemény törölve lett.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $error = "Törlés sikertelen.";
        }
        $stmt->close();
    }
}

$ujjitas = trim($_POST["ujjitas"] ?? "");
    
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_SESSION['user_id'])) {
        $error = ("Be kell jelentkezned a mentéshez.");
    } else {

        if ($ujjitas === '') {
            $error = ("Az üzenet nem lehet üres.");
        } else {

            $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
            $insert = $conn->prepare("INSERT INTO velemeny (tartalom, mikor_keszult , id) VALUES (?, NOW() , ?)");
            if ($insert) {
                $insert->bind_param("si", $ujjitas, $user_id);
                try {
                    if ($insert->execute()) {
                        $_SESSION['success'] = ("Ötleted sikeresen mentve!");
                        header("Location: " . $_SERVER['REQUEST_URI']);
                        exit;
                    }else {
                        $error = ("Hiba az ötlet mentése során: " . $insert->error);
                    }
                } catch (Exception $e) {
                    $error = ("Hiba mentés közben: " . $e->getMessage());
                }
                
                $insert->close();
            } else {
                $error = ("Hiba az előkészítés során: " . $conn->error);
            }
        }
    }
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

$reakcio_velemenyre_szama = 0;
$reakcio_szamlalo = "SELECT COUNT(*) as count FROM reagalas_velemenyre WHERE velemeny_id = ?";
if ($stmt = $conn->prepare($reakcio_szamlalo)) {
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        $row = $result->fetch_assoc();
        $reakciok_velemenyre_szama = isset($row['count']) ? (int)$row['count'] : 0;
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
        <link rel="stylesheet" href="main.css">
        <title>Poolchat</title>
    </head>
    <body>
    
        <?php if ($success): ?>
            <div class="msg success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="msg error"><?= $error ?></div>
        <?php endif; ?>

        <form method="get" action="home_admin.php">
            <button type="submit" class="visszaBtn"><i class="fa-solid fa-arrow-left"></i></button>
        </form>
        <h1>Ötletek</h1>
        <div class="ujjitasok-container">
            <div class="velemenyek">
                <?php
                $stmt = $conn->prepare("SELECT v.velemeny_id AS id ,v.tartalom AS tartalom, v.mikor_keszult AS mikor_keszult, f.neve AS username FROM velemeny v LEFT JOIN felhasznalo f ON v.id = f.id ORDER BY v.mikor_keszult ASC");
                $stmt->execute();
                $result = $stmt->get_result();
                        
                while ($row = $result->fetch_assoc()):
                ?>
                    <div class="velemeny-item">
                        <h3>
                            <?= htmlspecialchars($row['username'] ?? 'Ismeretlen') ?>
                            <span class="date"><?= time_ago_hu($row['mikor_keszult']) ?></span>
                        </h3>
                
                        <p><?= nl2br(htmlspecialchars($row['tartalom'])) ?></p>
                
                        <?php if (isAdmin()): ?>
                            <form method="post" onsubmit="return confirm('Biztos törölni szeretnéd?');">
                                <input type="hidden" name="delete_velemeny" value="1">
                                <input type="hidden" name="velemeny_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="torlesBtn">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; $stmt->close(); ?>
                </div>
                        
                        
    <script src="main.js"></script>
    </body>
</html>
