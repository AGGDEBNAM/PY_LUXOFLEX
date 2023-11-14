<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>

    <?php
    $servername = "localhost";
    $database = "luxoflex";

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recuperar credenciales del formulario
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];

        // Intentar crear conexión
        try {
            $conn = new mysqli($servername, $usuario, $contrasena, $database);

            // Verificar la conexión
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            // Si no hubo problemas hasta aquí, el inicio de sesión es exitoso
            echo "Inicio de sesión exitoso para $usuario";

            // Cerrar conexión
            $conn->close();
        } catch (mysqli_sql_exception $e) {
            // Manejar excepción en caso de error de conexión
            // Credenciales inválidas
            echo "Inicio de sesión fallido. Usuario o contraseña incorrectos.";
        }
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required><br>

        <input type="submit" value="Iniciar Sesión">
    </form>
</body>
</html>
