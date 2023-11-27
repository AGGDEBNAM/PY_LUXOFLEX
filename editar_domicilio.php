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

if (isset($_GET['id'])) {
    $id_domicilio = $_GET['id'];

    $sql = "SELECT * FROM domicilio WHERE id_domicilio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_domicilio);
    $stmt->execute();
    $result = $stmt->get_result();
    $domicilio = $result->fetch_assoc();
    $stmt->close();

    if (!$domicilio) {
        header("Location: perfil.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_domicilio"])) {
    $pais = $_POST["pais"];
    $ciudad = $_POST["ciudad"];
    $estado = $_POST["estado"];
    $direccion = $_POST["direccion"];
    $codigo_postal = $_POST["codigo_postal"];
    $rfc = $_POST["rfc"];

    $sql = "UPDATE domicilio SET pais = ?, ciudad = ?, Estado = ?, direccion = ?, codigo_postal = ?, rfc = ? WHERE id_domicilio = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $pais, $ciudad, $estado, $direccion, $codigo_postal, $rfc, $id_domicilio);
    $stmt->execute();
    $stmt->close();

    header("Location: perfil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LUXO FLEX</title>
        <link rel="stylesheet" type="text/css" href="etiquetas.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap');
        </style>
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
        <h2>Editar Domicilio</h2>
        <form action="editar_domicilio.php?id=<?php echo $id_domicilio; ?>" method="post">
            <!-- Campos del formulario -->
            <!-- País -->
            <div class="card-domicilio">
                <label for="pais">País:</label>
                <input type="text" name="pais" required value="<?php echo $domicilio['pais']; ?>">
            </div>
            <!-- Ciudad -->
            <div class="card-domicilio">
                <label for="ciudad">Ciudad:</label>
                <input type="text" name="ciudad" required value="<?php echo $domicilio['ciudad']; ?>">
            </div>
            <!-- Estado -->
            <div class="card-domicilio">
                <label for="estado">Estado:</label>
                <input type="text" name="estado" required value="<?php echo $domicilio['Estado']; ?>">
            </div>
            <!-- Dirección -->
            <div class="card-domicilio">
                <label for="direccion">Dirección:</label>
                <input type="text" name="direccion" required value="<?php echo $domicilio['direccion']; ?>">
            </div>
            <!-- Código Postal -->
            <div class="card-domicilio">
                <label for="codigo_postal">Código Postal:</label>
                <input type="text" name="codigo_postal" required value="<?php echo $domicilio['codigo_postal']; ?>">
            </div>
            <!-- RFC -->
            <div class="card-domicilio">
                <label for="rfc">RFC:</label>
                <input type="text" name="rfc" required value="<?php echo $domicilio['rfc']; ?>">
            </div>
            <button type="submit" name="update_domicilio" class="button">Actualizar Domicilio</button>
        </form>
    </div>
</body>
</html>
