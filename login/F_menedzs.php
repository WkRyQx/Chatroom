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


// Ellenőrizzük, hogy a felhasználó admin-e
function isAdmin() {
    return isset($_SESSION['user_szerepkor']) && $_SESSION['user_szerepkor'] == 1;
}


//Felhasznalo lista ajax
if (isset($_GET['ajax_users'])) {
    $q = trim($_GET['q'] ?? '');

    if ($q !== '') {
        $stmt = $conn->prepare("
            SELECT id, email, neve, neme, eletkor, iskola, szerepkor_id AS szerepkor
            FROM felhasznalo
            WHERE LOWER(neve) LIKE ?
            ORDER BY neve ASC
            LIMIT 100
        ");
        $like = '%' . mb_strtolower($q, 'UTF-8') . '%';
        $stmt->bind_param('s', $like);
    } else {
        $stmt = $conn->prepare("
            SELECT id, email, neve, neme, eletkor, iskola, szerepkor_id AS szerepkor
            FROM felhasznalo
            ORDER BY neve ASC
            LIMIT 100
        ");
    }

    $stmt->execute();
    $res = $stmt->get_result();

    $out = [];
    $can_manage = isAdmin() ? 1 : 0;

    while ($row = $res->fetch_assoc()) {
        $row['can_manage'] = $can_manage;
        $out[] = $row;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($out);
    exit;
}

// Egy felhasználó lekérése AJAX-szal (szerkesztéshez)
if (isset($_GET['ajax_get_user']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT id, email, neve, neme, eletkor, iskola, szerepkor_id FROM felhasznalo WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    if ($row) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($row);
    } else {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Nem található a felhasználó.']);
    }
    exit;
}


//Felhasznalo torles
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_felhasznalo'])) {

    if (!isAdmin()) {
        $error = "Nincs jogosultságod törölni.";
    } else {
        $felhasznalo_id = (int)$_POST['felhasznalo_id'];

        $stmt = $conn->prepare("DELETE FROM felhasznalo WHERE id = ?");
        $stmt->bind_param("i", $felhasznalo_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "A felhasználó törölve lett.";
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            $error = "Törlés sikertelen.";
        }
        $stmt->close();
    }
}


//felhasznalo szerkesztes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['szerkesztes_felhasznalo'])) {

    if (!isAdmin()) {
        $error = "Nincs jogosultságod szerkeszteni.";
    } else {

        $felhasznalo_id = (int)($_POST['felhasznalo_id'] ?? 0);
        $email = trim($_POST['email'] ?? '');
        $nev = trim($_POST['neve'] ?? '');
        $neme = trim($_POST['neme'] ?? '');
        $eletkor = (int)($_POST['eletkor'] ?? 0);
        $iskola = trim($_POST['iskola'] ?? '');
        $szerepkor_id = (int)($_POST['szerepkor_id'] ?? 2);

            $stmt = $conn->prepare("
                UPDATE felhasznalo
                SET email = ?, neve = ?, neme = ?, eletkor = ?, iskola = ?, szerepkor_id = ?
                WHERE id = ?
            ");

            if (!$stmt) {
                $error = "SQL hiba: " . $conn->error;
            } else {
                $stmt->bind_param(
                    "sssissi",
                    $email,
                    $nev,
                    $neme,
                    $eletkor,
                    $iskola,
                    $szerepkor_id,
                    $felhasznalo_id
                );

                if ($stmt->execute()) {
                    $_SESSION['success'] = "A felhasználó sikeresen szerkesztve.";
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                } else {
                    $error = "Szerkesztés sikertelen: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }

$user = null;

if (isAdmin()) {
    if (isset($_GET['edit_id'])) {
        $id = (int)$_GET['edit_id'];

        $stmt = $conn->prepare("SELECT * FROM felhasznalo WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['felhasznalo_id']) && !isset($_POST['szerkesztes_felhasznalo'])) {
        $id = (int)$_POST['felhasznalo_id'];

        $stmt = $conn->prepare("SELECT * FROM felhasznalo WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
    }
}


?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <title>Felhasznaló Menedzsment</title>
</head>
<body>
    <form method="get" action="home_admin.php">
        <button type="submit" class="visszaBtn"><i class="fa-solid fa-arrow-left"></i></button>
    </form>


    <div style="display: flex; gap: 20px;">
    <div style="flex: 1;">

        <input type="search" id="search" placeholder="Keresés név alapján">
        <div id="user-list"></div>
    </div>
    <div style="flex: 1;">
    <form method="post" id="loadUserForm">
        <label>A szerkeszteni kivánt felhasználó id:</label>
        <div style="display: flex; gap: 10px;">
            <input type="text" name="felhasznalo_id" id="felhasznalo_id_input">
            <button type="button" onclick="loadUserForEdit()">Betöltés</button>
        </div>
    </form>

    <form method="post">
        <label for="email">Email:</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">

        <label for="neve">Név:</label>
        <input type="text" name="neve" value="<?php echo htmlspecialchars($user['neve'] ?? ''); ?>">

        <label for="neme">Neme:</label>
        <input type="text" name="neme" value="<?php echo htmlspecialchars($user['neme'] ?? ''); ?>">

        <label for="eletkor">Életkor:</label>
        <input type="number" name="eletkor" value="<?php echo $user['eletkor'] ?? ''; ?>">

        <label for="iskola">Iskola:</label>
        <input type="text" name="iskola" value="<?php echo htmlspecialchars($user['iskola'] ?? ''); ?>">

        <label for="szerepkor_id">Szerepkör ID:</label>
        <input type="number" name="szerepkor_id" value="<?php echo $user['szerepkor_id'] ?? '2'; ?>">

        <input type="hidden" name="felhasznalo_id" value="<?php echo $user['id'] ?? ''; ?>">
        <button type="submit" name="szerkesztes_felhasznalo" value="1">Mentés</button>
    </form>
    </div>
</div>

<script src="main.js"></script>
</body>
</html>