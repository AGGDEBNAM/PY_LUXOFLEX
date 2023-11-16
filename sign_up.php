<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "luxoflex";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["login"])) {
        header("Location: login.php");
        exit();
    }


    if (isset($_POST["signup"])) {
        header("Location: sign_up.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sing Up Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="sign_up.css">
</head>

<body>

    <header>
        <nav>
            <div class="logo">
                <a href="inicio.php">
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
        <h2>Sign Up</h2>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $conn = new mysqli($servername, $username, $password, $database);

            $nombre_usuario = $_POST['in_username'];
            $contrasena = $_POST['in_password'];

            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nombre = $_POST['in_username'];
                $empresa = "Alen";
                $email = $_POST['in_email'];
                $telefono = $_POST['in_tel'];
                $num_cotizacion = "1";

                $sql = "INSERT INTO contacto (nombre, empresa, email, telefono, num_cotizacion) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("ssssi", $nombre, $empresa, $email, $telefono, $num_cotizacion);

                    if ($stmt->execute()) {
                        echo "Nuevo contacto insertado con éxito.";
                    } else {
                        echo "Error al insertar el contacto: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error al preparar la consulta: " . $conn->error;
                }
            }

            $sql = "CREATE USER '$nombre_usuario'@'localhost' IDENTIFIED BY '$contrasena';";
            $sql .= "GRANT INSERT, UPDATE, DELETE ON *.* TO '$nombre_usuario'@'localhost';";
            $sql .= "FLUSH PRIVILEGES;";
            if ($conn->multi_query($sql) === TRUE) {
                echo "Usuario creado con éxito";

                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Error al crear el usuario: " . $conn->error;
            }

            $conn->close();
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="user-box">
                <input type="text" name="in_username" required>
                <span></span>
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="in_password" required>
                <label>Password</label>
            </div>
            <div class="user-box">
                <input type="email" name="in_email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="user-box">
                <input type="tel" name="in_tel" required>
                <label>Phone</label>
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