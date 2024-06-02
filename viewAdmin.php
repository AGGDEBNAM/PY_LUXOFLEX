<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

if (isset($_POST["admin_users"])) {
    header("Location: viewAdmin.php");
    exit();
}

if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
    header("Location: inicio.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "luxoflex";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM vista_ventas";
$result = $conn->query($sql);

$sql2 = "SELECT * FROM vista_etiquetas";
$result_etiquetas = $conn->query($sql2);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista ventas</title>
    <link rel="stylesheet" href="viewAdmin.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap');
    </style>
</head>

<body>

    <header>
        <nav>
            <div class="logo">
                <a href="inicio_admis.php">
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
                    <button type="submit" name="login" class="button">Admin Users</button>
                    <button type="submit" name="logout" class="button">Log out</button>
                </form>
            </div>
        </nav>
    </header>
    <div class="card-perfil">
        <h2>Vista de Ventas</h2>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Diseño de la Etiqueta</th>
                    <th>Dirección</th>
                    <th>Código Postal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['fecha']}</td>";
                        echo "<td>{$row['usuario']}</td>";
                        echo "<td>{$row['diseño_etiqueta']}</td>";
                        echo "<td>{$row['direccion']}</td>";
                        echo "<td>{$row['codigo_postal']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No se encontraron ventas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Vista de Etiquetas</h2>

        <table>
            <thead>
                <tr>
                    <th>Medida Alto</th>
                    <th>Medida Ancho</th>
                    <th>Diseño</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_etiquetas->num_rows > 0) {
                    while ($row = $result_etiquetas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['medida_alto']}</td>";
                        echo "<td>{$row['medida_ancho']}</td>";
                        echo "<td>{$row['disenio']}</td>";
                        echo "<td>{$row['user_name']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No se encontraron etiquetas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
    $conn->close();
    ?>

</body>

</html>