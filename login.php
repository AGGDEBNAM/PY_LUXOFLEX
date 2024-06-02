<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="Login.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap');
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="inicio.php">
                    <img src="img_py/LUXFLEX.PNG" alt="LUXO FLEX" />
                </a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="/pricing">CONTACTO</a></li>
                    <li><a href="/about">ABOUT</a></li>
                </ul>
            </div>
            <div class="cta-buttons">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <button type="submit" name="login" class="button">Log in</button>
                    <button type="submit" name="signup" class="button">Sign up</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="login-box">
        <h2>Login</h2>

        <?php
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["log-in"])) {
                $usuario = isset($_POST['user_name']) ? $_POST['user_name'] : "";
                $contrasena = isset($_POST['password']) ? $_POST['password'] : "";

                $servername = "localhost";
                $username_db = "root";
                $password_db = "";
                $database = "luxoflex";

                try {
                    $conn = new mysqli($servername, $username_db, $password_db, $database);

                    if ($conn->connect_error) {
                        die("Error de conexión: " . $conn->connect_error);
                    }

                    $stmt = $conn->prepare("SELECT password FROM contacto WHERE user_name = ?");
                    $stmt->bind_param("s", $usuario);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($hashed_password);
                    $stmt->fetch();

                    // Verificar la contraseña encriptada con SHA-256
                    if ($stmt->num_rows == 1 && hash('sha256', $contrasena) === $hashed_password) {
                        $_SESSION['usuario'] = $usuario;

                        if ($usuario === "administrador") {
                            header("Location: viewAdmin.php");
                            exit();
                        } else {
                            header("Location: inicio_users.php");
                            exit();
                        }
                    } else {
                        echo "<p class='message'>Inicio de sesión fallido.<br>Usuario o contraseña incorrectos.</p>";
                    }

                    $stmt->close();
                    $conn->close();
                } catch (mysqli_sql_exception $e) {
                    echo "<p class='message'>Error de conexión.</p>";
                }
            }

            if (isset($_POST["signup"])) {
                header("Location: sign_up.php");
                exit();
            }
            if (isset($_POST["login"])) {
                header("Location: login.php");
                exit();
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="user-box">
                <input type="text" name="user_name" maxlength="20" required>
                <span></span>
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" maxlength="20" required>
                <label>Password</label>
            </div>
            <button type="submit" name="log-in" class="btn">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Submit
            </button>
        </form>
    </div>
</body>
</html>
