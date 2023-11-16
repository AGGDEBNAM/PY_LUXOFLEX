<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luxoflex";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión a la base de datos ha fallado: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $empresa = $_POST['empresa'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $num_cotizacion = $_POST['num_cotizacion'];

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Manejo de Base de Datos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="contacto.css">
</head>

<body>
    <h1>Manejo de Base de Datos</h1>

    <h2>Insertar Nuevo Contacto</h2>
    <form action="insertar.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>

        <label for="empresa">Empresa:</label>
        <input type="text" name="empresa"><br>

        <label for="email">Email:</label>
        <input type="email" name="email"><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono"><br>

        <label for="num_cotizacion">Número de Cotización:</label>
        <input type="text" name="num_cotizacion"><br>

        <input type="submit" value="Insertar">
    </form>

    <h2>Contactos Existentes</h2>
    <?php
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La conexión a la base de datos ha fallado: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM contacto";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Número de Cotización</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['nombre'] . "</td>
                    <td>" . $row['empresa'] . "</td>
                    <td>" . $row['email'] . "</td>
                    <td>" . $row['telefono'] . "</td>
                    <td>" . $row['num_cotizacion'] . "</td>
                </tr>";
        }

        echo "</table>";
    } else {
        echo "No hay contactos registrados.";
    }

    $conn->close();
    ?>
</body>

</html>