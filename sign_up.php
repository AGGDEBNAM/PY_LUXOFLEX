<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["login"])) {
        header("Location: login.php");
        exit();
    }

    if (isset($_POST["signup"])) {
        header("Location: sign_up.php");
        exit();
    }

    // Variables de formulario
    $nombre_usuario = $_POST['in_username'];
    $contrasena = $_POST['in_password'];
    $email = $_POST['in_email'];
    $telefono = $_POST['in_tel'];
    $empresa = $_POST['in_empresa'];

    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "luxoflex";
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Verificar si el nombre de usuario ya existe
    $check_query = "SELECT * FROM `contacto` WHERE `user_name` = '$nombre_usuario'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "El nombre de usuario ya existe. Por favor, elige otro.";
        header("Location: sign_up.php");
        exit();
    } else {
        // Insertar el nuevo usuario
        $insert_query = "INSERT INTO `contacto` (`user_name`, `password`, `email`, `telefono`, `empresa`) 
                         VALUES ('$nombre_usuario', '$contrasena', '$email', '$telefono', '$empresa')";

        if ($conn->query($insert_query) === TRUE) {
            $_SESSION['success_message'] = "Usuario creado con éxito";

            // Crear usuario en la base de datos MySQL y asignar privilegios
            $sql_create_user = "CREATE USER '$nombre_usuario'@'localhost' IDENTIFIED BY '$contrasena';";
            $sql_grant_privileges = "GRANT INSERT, UPDATE, DELETE ON *.* TO '$nombre_usuario'@'localhost';";
            $sql_flush_privileges = "FLUSH PRIVILEGES;";

            if ($conn->multi_query($sql_create_user . $sql_grant_privileges . $sql_flush_privileges) === TRUE) {
                // Éxito
            } else {
                // Error
            }

            header("Location: inicio.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Error al crear el usuario: " . $conn->error;
            header("Location: sign_up.php");
            exit();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sign Up Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="sign_up.css">
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
        <h2>Sign Up</h2>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='message'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['success_message'])) {
            echo "<p class='success'>" . $_SESSION['success_message'] . "</p>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<p class='message'>" . $_SESSION['error_message'] . "</p>";
            unset($_SESSION['error_message']);
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="user-box">
                <input type="text" name="in_username" maxlength="20" required>
                <span></span>
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="in_password" maxlength="20" required>
                <label>Password</label>
            </div>
            <div class="user-box">
                <input type="email" name="in_email" maxlength="30" required>
                <label>E-mail</label>
            </div>
            <div class="user-box">
                <input type="tel" name="in_tel" maxlength="20" required>
                <label>Tel</label>
            </div>
            <div class="user-box">
                <input type="text" name="in_empresa" maxlength="25" required>
                <label>Empresa</label>
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