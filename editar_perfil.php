<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "luxoflex";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombreUsuario = $_SESSION['usuario'];
$sql = "SELECT id_contacto, user_name, email, telefono, empresa FROM contacto WHERE user_name = '$nombreUsuario'";
$result = $conn->query($sql);
$perfil = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger y validar los datos aquí
    $email = $_POST["email"];
    $telefono = $_POST["telefono"];
    $empresa = $_POST["empresa"];

    // Actualizar información del perfil
    $sqlUpdate = "UPDATE contacto SET email = ?, telefono = ?, empresa = ? WHERE user_name = ?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("ssss", $email, $telefono, $empresa, $nombreUsuario);
    $stmt->execute();
    $stmt->close();

    // Redireccionar de nuevo a perfil.php
    header("Location: perfil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LUXO FLEX</title>
        <link rel="stylesheet" type="text/css" href="etiquetas.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap');
        </style>
    </head>
</head>
<body>
<header>
        <nav>
            <div class="logo">
                <a href="inicio_users.php">
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
                    <button type="submit" name="venta" class="button">cotizaciones</button>
                    <button type="submit" name="etiquetas" class="button">etiquetas</button>
                    <button type="submit" name="perfil" class="button">perfil</button>
                    <button type="submit" name="logout" class="button">logout</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="card-perfil">
        <h2>Editar Perfil</h2>
        <form action="editar_perfil.php" method="post">
            <div class="card-domicilio">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $perfil['email']; ?>" required>
            </div>
            <div class="card-domicilio">
                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" value="<?php echo $perfil['telefono']; ?>" required>
            </div>
            <div class="card-domicilio">
                <label for="empresa">Nombre de la empresa:</label>
                <input type="text" name="empresa" value="<?php echo $perfil['empresa']; ?>" required>
            </div>
            <button type="submit" class="button">Actualizar Perfil</button>
        </form>
    </div>
</body>
</html>
