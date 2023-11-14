<?php
function conectarBaseDeDatos($servername, $username, $password, $database)
{
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    return $conn;
}

function eliminarUsuario($conn, $usuario_a_eliminar)
{
    $usuario_a_eliminar = $conn->real_escape_string($usuario_a_eliminar);
    $sql = "DROP USER IF EXISTS $usuario_a_eliminar";

    try {
        if ($conn->query($sql) === TRUE) {
            echo "Usuario eliminado con éxito: $usuario_a_eliminar<br>";
        } else {
            throw new Exception("Error al eliminar el usuario: " . $conn->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        // Imprime información adicional sobre el error
        echo "Query ejecutada: " . $sql . "<br>";
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "luxoflex";

$conn = conectarBaseDeDatos($servername, $username, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $usuario_a_eliminar = $_POST['eliminar'];
    eliminarUsuario($conn, $usuario_a_eliminar);

    // Redirigir para evitar reenvío del formulario
    header("Location: " . $_SERVER['PHP_SELF']);
    exit(); // Asegura que el script se detenga después de redirigir
}

$sql = "SELECT User, Host, Select_priv, Insert_priv, Update_priv, Delete_priv, Create_priv, Drop_priv, Grant_priv, References_priv FROM mysql.user";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listar y Eliminar Usuarios</title>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<?php if ($result->num_rows > 0): ?>
    <h2>Usuarios en MySQL:</h2>
    <table>
        <tr>
            <th>Usuario</th>
            <th>Host</th>
            <th>Permisos</th>
            <th>Database</th>
            <th>Eliminar</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['User'] ?></td>
                <td><?= $row['Host'] ?></td>
                <td>
                    SELECT: <?= $row['Select_priv'] ?>
                    INSERT: <?= $row['Insert_priv'] ?>
                    UPDATE: <?= $row['Update_priv'] ?>
                    DELETE: <?= $row['Delete_priv'] ?><br>
                    CREATE: <?= $row['Create_priv'] ?>
                    DROP: <?= $row['Drop_priv'] ?>
                    GRANT: <?= $row['Grant_priv'] ?>
                    REFERENCES: <?= $row['References_priv'] ?>
                </td>
                <td><?= $database ?></td>
                <td>
                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                        <input type="hidden" name="eliminar" value="<?= $row['User'] . '@' . $row['Host'] ?>">
                        <input type="submit" value="Eliminar">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>
<?php else: ?>
    <p>No se encontraron usuarios.</p>
<?php endif; ?>

<?php $conn->close(); ?>

</body>
</html>