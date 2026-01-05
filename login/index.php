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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'register') {

        $email = trim($_POST["email"] ?? "");
        $jelszo = $_POST["jelszo"] ?? "";
        $nev = trim($_POST["nev"] ?? "");
        $neme = $_POST["neme"] ?? "";
        $eletkor = isset($_POST["eletkor"]) ? (int)$_POST["eletkor"] : null;
        $iskola = $_POST["iskola"] ?? "";

        if ($nev === "") {
            $error = ("A név megadása kötelező.");
        } else {
            $hash = password_hash($jelszo, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO felhasznalo (email, jelszo, neve, neme, eletkor, iskola, mikor_keszult , szerepkor_id) VALUES (?, ?, ?, ?, ?, ?, NOW(), 2)");
            if ($stmt === false) {
                $error = ("Hiba előkészítéskor: " . $conn->error);
            } else {
                $stmt->bind_param('ssssis', $email, $hash, $nev,  $neme, $eletkor, $iskola);
                if ($stmt->execute()) {
                    $success = ("Sikeres regisztráció! Most már bejelentkezhetsz.");
                } else {
                    $error = ("Hiba a regisztrációnál: " . $stmt->error);
                }
                $stmt->close();
            }
        }

    } elseif ($action === 'login') {
        $email = trim($_POST["emaillog"] ?? "");
        $jelszo = $_POST["jelszolog"] ?? "";

        if ($email === "" || $jelszo === "") {
            $error = ("Add meg az emailt és a jelszót.");
        } else {
            $stmt = $conn->prepare("SELECT id, email, jelszo, neve, neme, eletkor, iskola , szerepkor_id FROM felhasznalo WHERE email = ?");
            if ($stmt === false) {
                $error = ("Hiba előkészítéskor: " . $conn->error);
            } else {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows === 1) {
                    $stmt->bind_result($id, $db_email, $hash, $nev, $neme, $eletkor, $iskola , $szerepkor);
                    $stmt->fetch();

                    if (password_verify($jelszo, $hash)) {
                        session_regenerate_id(true);
                        $_SESSION['user_id'] = $id;
                        $_SESSION['user_name'] = $nev;
                        $_SESSION['user_email'] = $db_email;
                        $_SESSION['user_password'] = $hash;
                        $_SESSION['user_gender'] = $neme;
                        $_SESSION['user_age'] = $eletkor;
                        $_SESSION['user_school'] = $iskola;
                        $_SESSION['user_szerepkor'] = $szerepkor;

                        if($_SESSION['user_szerepkor'] == 2)
                            header("Location: home.php");
                        else{
                            header("Location: home_admin.php");
                        }
                        exit;
                    } else {
                        $error = ("Hibás email vagy jelszó.");
                    }
                } else {
                    $error = ("Hibás email vagy jelszó.");
                }

                $stmt->close();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Poolchat</title>
</head>
<body>

<?php if ($success): ?>
    <div class="msg success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="msg error"><?= $error ?></div>
<?php endif; ?>

 <div class="wrapper">
    <div class="form-box"> 

        <div class="login-container" id="login">
            <div class="top">
                <div class="lo-reg-btn">
                    <button class="btn white-btn loginBtn" onclick="login()">Bejelentkezés</button>
                    <button class="btn white-btn registerBtn" onclick="register()">Regisztráció</button>
                </div>
                <header>Bejelentkezés</header>
            </div>
            <form method="post">
            <div class="input-box">
                 <input type="hidden" name="action" value="login">
                <input type="email" class="input-field"  placeholder="Email" name="emaillog" required>
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Jelszó" id="show1" name="jelszolog" required>
                <i class="fa-solid fa-lock"></i>
                <i class="fa-solid fa-eye" id="toggle1"></i>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" value="Bejelentkezés" onclick="handleLogin()" >
            </div>
            </form>
            <div class="two-col">

                <div class="two">
                    <label><a href="#" class="btn white-btn" class="passBtn" onclick="Jelszo()" >Elfelejtetted a jelszavad?</a></label>
                </div>
            </div>
        </div>


        <div class="register-container" id="register">
            <div class="top">
                <div class="lo-reg-btn">
                    <button class="btn white-btn loginBtn" onclick="login()">Bejelentkezés</button>
                    <button class="btn white-btn registerBtn" onclick="register()">Regisztráció</button>
                </div>
                <header>Regisztráció</header>
            </div>
            <form method="post">
            <input type="hidden" name="action" value="register">
            <div class="input-box">
                <input type="email" class="input-field" placeholder="Email" name="email" required>
                <i class="fa-solid fa-envelope left-icon"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Jelszó" id="show2"  name="jelszo" required>
                <i class="fa-solid fa-lock left-icon"></i>
                <i class="fa-solid fa-eye" id="toggle2"></i>
            </div>
            <div class="input-box">
                <input type="text" class="input-field"  placeholder="Felhasználónév"  name="nev" required>
                <i class="fa-solid fa-user left-icon"></i>
            </div>
            <div class="input-box">
                <input type="number" class="input-field" id="myNumber" placeholder="Életkor" min="14" max="20"  name="eletkor" required>
                <i class="fa-solid fa-child left-icon"></i>
            </div>
            <div class="input-box">
                <div class="dropdown">
                    <input type="text" class="textBox"  placeholder="Válassz nemet"  name="neme" required readonly>
                    <i class="fa-solid fa-restroom"></i>
                    <div class="option">
                        <div onclick="show('Férfi')">
                            Férfi
                        </div>
                        <div onclick="show('Nő')">
                            Nő
                        </div>  
                    </div>
                </div>
            </div>
            <div class="input-box">
                <div class="dropdown">
                    <input type="text" class="textBox"  placeholder="Válassz iskolát"  name="iskola" required readonly>
                    <i class="fa-solid fa-school"></i>
                    <div class="option">
                        <div onclick="show('Boros Sámuel Technikum')">
                            Boros Sámuel Technikum
                        </div>
                        <div onclick="show('Pollák Antal Technikum')">
                            Pollák Antal Technikum
                        </div>
                        <div onclick="show('Zsoldos Ferenc Technikum')">
                            Zsoldos Ferenc Technikum
                        </div>
                        <div onclick="show('Horváth Mihály Technikum')">
                            Horváth Mihály Technikum
                        </div>
                    </div>
                </div>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" value="Regisztráció" onclick=" handleRegister()">
            </div>
        </div>
        </form>


        <div class="password-container" id="pass">
            <header>Jelszó visszaállitás</header>
            <div class="input-box">
                <input type="email" class="input-field" placeholder="Email" required>
                <i class="fa-solid fa-envelope left-icon"></i>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" value="Küldés">
            </div>
            <div class="two-col">
                <div class="two">
                    <label class="one"><a href="#" onclick="login()">Bejelentkezés</a></label>
                </div>
            </div>
        </div>
   
   
<script src="main.js"></script>

</body>
</html>

<?php
$conn->close();
?>