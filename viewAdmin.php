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

$servername = "localhost";
$username = "root";
$password = "";
$database = "luxoflex";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$search_id = isset($_POST['search_id']) ? $_POST['search_id'] : '';

$sql = "SELECT * FROM vista_ventas";
if ($search_id) {
    $sql .= " WHERE id_etiqueta LIKE '%" . $conn->real_escape_string($search_id) . "%'";
}
$result = $conn->query($sql);

$sql2 = "SELECT * FROM vista_etiquetas";
if ($search_id) {
    $sql2 .= " WHERE id_etiqueta LIKE '%" . $conn->real_escape_string($search_id) . "%'";
}
$result_etiquetas = $conn->query($sql2);

$sql3 = "SELECT * FROM vista_detalle_etiquetas";
if ($search_id) {
    $sql3 .= " WHERE id_etiqueta LIKE '%" . $conn->real_escape_string($search_id) . "%'";
}
$result_etiquetas_detalle = $conn->query($sql3);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista ventas</title>
    <link rel="stylesheet" href="ViewAdmin.css?v=1.0">
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

        <div class="card-bar">
            <div class="search-bar">
                <label for="search_id">Busca La Etiqueta:</label>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="searchForm">
                    <input type="number" name="search_id" id="search_id" placeholder="Buscar por identificador" value="<?php echo htmlspecialchars($search_id); ?>" autocomplete="off">
                </form>
            </div>
        </div>

        <h2>Vista de Ventas</h2>

        <table>
            <thead>
                <tr>
                    <th class="ventas-col-id">Identificador</th>
                    <th class="ventas-col-fecha">Fecha</th>
                    <th class="ventas-col-direccion">Dirección</th>
                    <th class="ventas-col-codigo">Código Postal</th>
                    <th class="ventas-col-diseno">Diseño de la Etiqueta</th>
                    <th class="ventas-col-usuario">Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id_etiqueta']}</td>";
                        echo "<td>{$row['fecha']}</td>";
                        echo "<td>{$row['direccion']}</td>";
                        echo "<td>{$row['codigo_postal']}</td>";
                        echo "<td>{$row['diseño_etiqueta']}</td>";
                        echo "<td>{$row['usuario']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No se encontraron ventas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Vista de Etiquetas</h2>
        <table>
            <thead>
                <tr>
                    <th class="etiquetas-col-id">Identificador</th>
                    <th class="etiquetas-col-alto">Cantidad</th>
                    <th class="etiquetas-col-alto">Medida Alto</th>
                    <th class="etiquetas-col-ancho">Medida Ancho</th>
                    <th class="etiquetas-col-circunferencia">Medida Circunferencia</th>
                    <th class="etiquetas-col-diseno">Diseño</th>
                    <th class="etiquetas-col-usuario">Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_etiquetas->num_rows > 0) {
                    while ($row = $result_etiquetas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id_etiqueta']}</td>";
                        echo "<td>{$row['total_cantidad']}</td>";
                        echo "<td>{$row['medida_alto']}</td>";
                        echo "<td>{$row['medida_ancho']}</td>";
                        echo "<td>{$row['medida_circunferencia']}</td>";
                        echo "<td><img src='{$row['disenio']}?v=" . time() . "' alt='Diseño' width='100'></td>";
                        echo "<td>{$row['user_name']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No se encontraron etiquetas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Vista Detallada Etiquetas</h2>
        <table>
            <thead>
                <tr>
                    <th class="etiquetas-full-id">Identificador</th>
                    <th class="etiquetas-full-forma">Tipo Forma</th>
                    <th class="etiquetas-full-mat">Material de Etiqueta</th>
                    <th class="etiquetas-full-laminado">Laminado</th>
                    <th class="etiquetas-full-mat-aplicacion">Material de Aplicacion</th>
                    <th class="etiquetas-full-colores">Colores</th>
                    <th class="etiquetas-full-usuario">Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_etiquetas_detalle->num_rows > 0) {
                    while ($row = $result_etiquetas_detalle->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id_etiqueta']}</td>";
                        echo "<td>{$row['tipo_forma']}</td>";
                        echo "<td>{$row['material_etiqueta']}</td>";
                        echo "<td>{$row['laminado']}</td>";
                        echo "<td>{$row['material_aplicacion']}</td>";
                        echo "<td>{$row['colores']}</td>";
                        echo "<td>{$row['user_name']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No se encontraron etiquetas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="inicio.js"></script>
    <script>
        let debounceTimeout;

        document.getElementById('search_id').addEventListener('input', function() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function() {
                document.getElementById('searchForm').submit();
            }, 300); // Espera 300 ms antes de enviar el formulario
        });
    </script>

    <?php
    $conn->close();
    ?>

</body>

</html>