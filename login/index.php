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
 <div class="wrapper">
    <div class="form-box"> 


        <div class="login-container" id="login">
            <div class="top">
                <div class="lo-reg-btn">
                    <button class="btn white-btn" id="loginBtn" onclick="login()">Sign In</button>
                    <button class="btn white-btn" id="registerBtn" onclick="register()">Sign Up</button>
                </div>
                <header>Bejelentkezés</header>
            </div>
            <div class="input-box">
                <input type="email" class="input-field" placeholder="Email" required>
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Jelszó" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="input-box">
                <select class="input-field" required>
                    <option value="">Válassz iskolát</option>
                    <option value="boros">Boros Sámuel Technikum</option>
                    <option value="pollak">Pollák Antal Technikum</option>
                    <option value="zsoldos">Zsoldos Ferenc Technikum</option>
                    <option value="horvath">Horváth Mihály Gimnázium</option>
                </select>
                <i class="fa-solid fa-school"></i>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" value="Bejelentkezés">
            </div>
            <div class="two-col">
                <div class="one">
                    <input type="checkbox" id="login-check">
                    <label for="login-check"> Emlékezz rám</label>
                </div>
                <div class="two">
                    <label><a href="#" class="btn white-btn" id="passBtn" onclick="Jelszo()" >Elfelejtetted a jelszavad?</a></label>
                </div>
            </div>
        </div>


        <div class="register-container" id="register">
            <div class="top">
                <div class="lo-reg-btn">
                    <button class="btn white-btn" id="loginBtn" onclick="login()">Sign In</button>
                    <button class="btn" id="registerBtn" onclick="register()">Sign Up</button>
                </div>
                <header>Regisztráció</header>
            </div>
            <div class="input-box">
                <input type="email" class="input-field" placeholder="Email" required>
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="input-box">
                <input type="password" class="input-field" placeholder="Jelszó" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            <div class="input-box">
                <input type="text" class="input-field" placeholder="Felhasználónév" required>
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="input-box">
                <input type="number" class="input-field" id="myNumber" placeholder="Életkor" min="14" max="20" required>
                <i class="fa-solid fa-child"></i>
            </div>
            <div class="input-box">
                <select class="input-field" required>
                    <option value="">Válassz nemet</option>
                    <option value="ferfi">Férfi</option>
                    <option value="no">Nő</option>
                </select>
                <i class="fa-solid fa-restroom"></i>
            </div>
            <div class="input-box">
                <select class="input-field" required>
                    <option value="">Válassz iskolát</option>
                    <option value="boros">Boros Sámuel Technikum</option>
                    <option value="pollak">Pollák Antal Technikum</option>
                    <option value="zsoldos">Zsoldos Ferenc Technikum</option>
                    <option value="horvath">Horváth Mihály Gimnázium</option>
                </select>
                <i class="fa-solid fa-school"></i>
            </div>
            <div class="input-box">
                <input type="submit" class="submit" value="Regisztráció">
            </div>
        </div>


        <div class="password-container" id="pass">
            <header>Jelszó visszaállitás</header>
            <div class="input-box">
                <input type="email" class="input-field" placeholder="Email" required>
                <i class="fa-solid fa-envelope"></i>
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


    </div>
</div>

<script src="main.js"></script>

</body>
</html>