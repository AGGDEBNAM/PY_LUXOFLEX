<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}
if ($_SESSION['usuario'] !== 'administrador') {
    header("Location: inicio_users.php");
    exit();
}

if (isset($_POST["ver_users"])) {
    header("Location: Ver_Usuarios.php");
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

function conectarBaseDeDatos($servername, $username, $password, $database)
{
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    return $conn;
}

function eliminarUsuario($conn, $usuario_a_eliminar)
{
    $usuario_a_eliminar = $conn->real_escape_string($usuario_a_eliminar);

    $sql_drop_user = "DROP USER IF EXISTS '$usuario_a_eliminar'@'localhost'";

    try {
        if ($conn->query($sql_drop_user) === TRUE) {
            echo "Usuario eliminado con éxito: $usuario_a_eliminar<br>";

            $sql_delete_venta = "DELETE v FROM venta v
                                JOIN contacto c ON v.id_contacto = c.id_contacto
                                WHERE c.user_name = '$usuario_a_eliminar'";
            $sql_delete_etiqueta = "DELETE e FROM etiqueta e
            JOIN contacto c ON e.id_contacto = c.id_contacto
            WHERE c.user_name = '$usuario_a_eliminar'";
            $sql_delete_domicilio = "DELETE d FROM domicilio d
            JOIN contacto c ON d.id_contacto = c.id_contacto
            WHERE c.user_name = '$usuario_a_eliminar'";
            $sql_delete_contacto = "DELETE FROM contacto WHERE user_name = '$usuario_a_eliminar'";

            if (
                $conn->query($sql_delete_venta) === TRUE &&
                $conn->query($sql_delete_etiqueta) === TRUE &&
                $conn->query($sql_delete_domicilio) === TRUE &&
                $conn->query($sql_delete_contacto) === TRUE
            ) {
                echo "Datos asociados eliminados con éxito para el usuario: $usuario_a_eliminar<br>";
            } else {
                throw new Exception("Error al eliminar los datos asociados: " . $conn->error);
            }
        } else {
            throw new Exception("Error al eliminar el usuario: " . $conn->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "Query ejecutada: " . $sql_drop_user . "<br>";
    }
}

function eliminarVenta($conn, $num_cotizacion)
{
    $num_cotizacion = $conn->real_escape_string($num_cotizacion);

    $sql_delete_venta = "DELETE FROM venta WHERE num_cotizacion = '$num_cotizacion'";

    try {
        if ($conn->query($sql_delete_venta) === TRUE) {
            echo "Venta eliminada con éxito: $num_cotizacion<br>";
        } else {
            throw new Exception("Error al eliminar la venta: " . $conn->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
        echo "Query ejecutada: " . $sql_delete_venta . "<br>";
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "luxoflex";

try {
    $conn = conectarBaseDeDatos($servername, $username, $password, $database);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['eliminar'])) {
            $usuario_a_eliminar = $_POST['eliminar'];
            eliminarUsuario($conn, $usuario_a_eliminar);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }

        if (isset($_POST['eliminar_venta'])) {
            $num_cotizacion = $_POST['eliminar_venta'];
            eliminarVenta($conn, $num_cotizacion);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    $search_values = [
        'num_cotizacion' => '',
        'cantidad' => '',
        'id_etiqueta' => '',
        'comentarios' => '',
        'direccion' => '',
        'username' => '',
        'fecha' => ''
    ];

    foreach ($search_values as $key => $value) {
        if (isset($_POST[$key])) {
            $search_values[$key] = $conn->real_escape_string($_POST[$key]);
        }
    }

    $usuarios_a_excluir = ['root', 'pma', 'administrador', 'Usuario'];

    $sql = "SELECT User, Host, Select_priv, Insert_priv, Update_priv, Delete_priv, Create_priv, Drop_priv, Grant_priv, References_priv
            FROM mysql.user
            WHERE User NOT IN ('" . implode("','", $usuarios_a_excluir) . "')";

    $result = $conn->query($sql);

    $sql_ventas = "SELECT v.num_cotizacion, v.cantidad, v.id_etiqueta, v.comentarios, d.direccion, c.user_name AS username, v.fecha 
                    FROM venta v 
                    JOIN domicilio d ON v.id_domicilio = d.id_domicilio 
                    JOIN contacto c ON v.id_contacto = c.id_contacto";
    $conditions = [];
    foreach ($search_values as $key => $value) {
        if (!empty($value)) {
            if ($key == 'username') {
                $conditions[] = "c.user_name LIKE '%$value%'";
            } elseif ($key == 'direccion') {
                $conditions[] = "d.direccion LIKE '%$value%'";
            } else {
                $conditions[] = "v.$key LIKE '%$value%'";
            }
        }
    }
    if (count($conditions) > 0) {
        $sql_ventas .= " WHERE " . implode(" AND ", $conditions);
    }
    $result_ventas = $conn->query($sql_ventas);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listar y Eliminar Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Ver_Usuarios.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap');

        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body>

    <header>
        <nav>
            <div class="logo">
                <a href="inicio_admins.php">
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
                    <button type="submit" name="ver_users" class="button">Ver Users</button>
                    <button type="submit" name="admin_users" class="button">Admin Users</button>
                    <button type="submit" name="logout" class="button">Log out</button>
                </form>
            </div>
        </nav>
    </header>
    <div class="card-perfil">

        <h1>Usuarios en MySQL:</h1>

        <?php if ($result->num_rows > 0) : ?>
            <table>
                <tr>
                    <th>Usuario</th>
                    <th>Host</th>
                    <th>Permisos</th>
                    <th>Database</th>
                    <th>Eliminar</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()) : ?>
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
                            <div class='button-container'>
                                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                                    <input type="hidden" name="eliminar" value="<?= $row['User'] ?>">
                                    <button type='submit' name='delete_etiqueta' title='Eliminar'><i class='fas fa-trash'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>
    </div>
<?php else : ?>
    <p>No se encontraron usuarios.</p>
<?php endif; ?>

<div class="card-perfil">

    <h1>Ventas en MySQL:</h1>

    <div class="card-bar">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="searchForm">
            <table>
                <thead>
                    <tr>
                        <th class="num_cotizacion"><input autocomplete="off" type="text" name="num_cotizacion" placeholder="Buscar Número de Cotización" value="<?php echo htmlspecialchars($search_values['num_cotizacion']); ?>"></th>
                        <th class="cantidad"><input autocomplete="off" type="text" name="cantidad" placeholder="Buscar Cantidad" value="<?php echo htmlspecialchars($search_values['cantidad']); ?>"></th>
                        <th class="id_etiquetas"><input autocomplete="off" type="text" name="id_etiqueta" placeholder="Buscar ID de Etiqueta" value="<?php echo htmlspecialchars($search_values['id_etiqueta']); ?>"></th>
                        <th class="comentarios"><input autocomplete="off" type="text" name="comentarios" placeholder="Buscar Comentarios" value="<?php echo htmlspecialchars($search_values['comentarios']); ?>"></th>
                        <th class="domicilio"><input autocomplete="off" type="text" name="direccion" placeholder="Buscar Dirección" value="<?php echo htmlspecialchars($search_values['direccion']); ?>"></th>
                        <th class="contacto"><input autocomplete="off" type="text" name="username" placeholder="Buscar Usuario" value="<?php echo htmlspecialchars($search_values['username']); ?>"></th>
                        <th class="fecha"><input autocomplete="off" type="text" name="fecha" placeholder="Buscar Fecha" value="<?php echo htmlspecialchars($search_values['fecha']); ?>"></th>
                        <th class="buscar">
                            <button type="submit">Buscar</button>
                            <button type="button" onclick="limpiarCampos()">Clean</button>
                        </th>
                    </tr>
                </thead>
            </table>
        </form>
    </div>

    <?php if ($result_ventas->num_rows > 0) : ?>
        <table>
            <thead>
                <tr>
                    <th>Número de Cotización</th>
                    <th>Cantidad</th>
                    <th>ID de Etiqueta</th>
                    <th>Comentarios</th>
                    <th>ID de Domicilio</th>
                    <th>ID de Contacto</th>
                    <th>Fecha</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_ventas = $result_ventas->fetch_assoc()) : ?>
                    <tr>
                        <td><?= $row_ventas['num_cotizacion'] ?></td>
                        <td><?= $row_ventas['cantidad'] ?></td>
                        <td><?= $row_ventas['id_etiqueta'] ?></td>
                        <td><?= $row_ventas['comentarios'] ?></td>
                        <td><?= $row_ventas['direccion'] ?></td>
                        <td><?= $row_ventas['username'] ?></td>
                        <td><?= $row_ventas['fecha'] ?></td>
                        <td>
                            <div class='button-container'>
                                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                                    <input type="hidden" name="eliminar_venta" value="<?= $row_ventas['num_cotizacion'] ?>">
                                    <button type='submit' name='delete_etiqueta' title='Eliminar'><i class='fas fa-trash'></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Número de Cotización</th>
                    <th>Cantidad</th>
                    <th>ID de Etiqueta</th>
                    <th>Comentarios</th>
                    <th>ID de Domicilio</th>
                    <th>ID de Contacto</th>
                    <th>Fecha</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="8">No se encontraron resultados.</td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="inicio.js"></script>
<script>
    function limpiarCampos() {
        document.querySelectorAll('#searchForm input').forEach(input => {
            input.value = '';
        });
        document.getElementById('searchForm').submit();
    }
</script>

<?php $conn->close(); ?>

</body>

</html>