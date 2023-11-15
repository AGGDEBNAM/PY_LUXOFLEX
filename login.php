<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST["login"])) {
        header("Location: login.php");
        exit();
    }

    
    if (isset($_POST["signup"])) {
        header("Location: registro.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>

    <header>
        <nav>
            <div class="logo">
                <a href="/">
                    <img src="LUXFLEX.PNG" alt="LUXO FLEX" />
                </a>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="/pricing">Pricing</a></li>
                    <li><a href="/about">About</a></li>
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
        $servername = "localhost";
        $database = "luxoflex";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $usuario = $_POST['username'];
            $contrasena = $_POST['password'];

            try {
                $conn = new mysqli($servername, $usuario, $contrasena, $database);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                echo "<p class='success'>Inicio de sesión exitoso para $usuario</p>";

                $conn->close();
            } catch (mysqli_sql_exception $e) {
                echo "<p class='message'>Inicio de sesión fallido.<br>Usuario o contraseña incorrectos.</p>";
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="user-box">
                <input type="text" name="username" required>
                <span></span>
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <button type="submit" class="btn">
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