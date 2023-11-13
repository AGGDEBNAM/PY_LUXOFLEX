<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manejo de Base de Datos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Manejo de Base de Datos</h1>

    <!-- Formulario para insertar datos -->
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

    <!-- Mostrar registros existentes -->
    <h2>Contactos Existentes</h2>
    <?php
        include_once 'conexion.php';

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
