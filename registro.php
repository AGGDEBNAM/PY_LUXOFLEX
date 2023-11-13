<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
</head>
<body>

<h2>Crear Usuario</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="nombre_usuario">Nombre de Usuario:</label>
    <input type="text" name="nombre_usuario" required><br>

    <label for="contrasena">Contraseña:</label>
    <input type="password" name="contrasena" required><br>

    <input type="submit" value="Crear Usuario">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si se ha enviado el formulario, procesa los datos

    // Recuperar los datos del formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    // Conectar a la base de datos (ajusta las credenciales según tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "luxoflex";

    $conn = new mysqli($servername, $username, $password, $database);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Crear el usuario y otorgar privilegios
    $sql = "CREATE USER '$nombre_usuario'@'localhost' IDENTIFIED BY '$contrasena';";
    $sql .= "GRANT INSERT, UPDATE, DELETE ON *.* TO '$nombre_usuario'@'localhost';";
    $sql .= "FLUSH PRIVILEGES;";
    if ($conn->multi_query($sql) === TRUE) {
        echo "Usuario creado con éxito";

        // Redirigir a la misma página para evitar la reenviación del formulario
        header("Location: " . $_SERVER['PHP_SELF']);
        exit(); // Asegura que el script se detenga después de redirigir
    } else {
        echo "Error al crear el usuario: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>

</body>
</html>

