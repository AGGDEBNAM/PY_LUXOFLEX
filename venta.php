<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["etiquetas"])) {
        header("Location: etiquetas.php");
        exit();
    }

    if (isset($_POST["logout"])) {
        session_unset();
        session_destroy();
        header("Location: inicio.php");
        exit();
    }

    if (isset($_POST["perfil"])) {
        header("Location: perfil.php");
        exit();
    }
    if (isset($_POST["venta"])) {
        header("Location: venta.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Generar una venta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="venta.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300&display=swap');
    </style>

</head>

<?php
if (isset($_POST["insert_venta"])) {
    try {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "luxoflex";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            throw new Exception("Error de conexión: " . $conn->connect_error);
        }

        $id_etiqueta_tmp = isset($_POST['id_etiqueta']) ? $_POST['id_etiqueta'] : "";
        $id_domicilio_tmp = isset($_POST['id_domicilio']) ? $_POST['id_domicilio'] : "";

        $nombreUsuario = $_SESSION['usuario'];
        $currt_user = "SELECT id_contacto FROM contacto WHERE user_name = '$nombreUsuario'";
        $result_id_contacto = $conn->query($currt_user);

        if ($result_id_contacto->num_rows > 0) {
            $row_id_contacto = $result_id_contacto->fetch_assoc();
            $id_contacto = $row_id_contacto['id_contacto'];
            $cantidad = $_POST['cantidad'];
            $comentarios = $_POST['comentarios'];

            $sql_venta = $conn->prepare("CALL insertarVenta(?, ?, ?, ?, ?)");

            if ($sql_venta) {
                $sql_venta->bind_param("iisii", $cantidad, $id_etiqueta_tmp, $comentarios, $id_domicilio_tmp, $id_contacto);

                if ($sql_venta->execute()) {
                    echo "Datos guardados correctamente";
                } else {
                    throw new Exception("Error al guardar los datos: " . $sql_venta->error);
                }

                $sql_venta->close();
            } else {
                throw new Exception("Error en la preparación de la consulta SQL: " . $conn->error);
            }
        } else {
            throw new Exception("No se encontró el id_contacto para el usuario.");
        }

        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


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
        <h2>Generar una venta de una etiqueta registrada</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "luxoflex";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        
        ?>

        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="card-venta">
                <label for="id_etiqueta">Selecciona una etiqueta:</label>
                <select id="id_etiqueta" name="id_etiqueta" required>
                    <?php
                    $nombreUsuario = $_SESSION['usuario'];
                    $currt_user = "SELECT id_contacto FROM contacto WHERE user_name = '$nombreUsuario'";
                    $result_id_contacto = $conn->query($currt_user);
                    if ($result_id_contacto->num_rows > 0) {
                        $row_id_contacto = $result_id_contacto->fetch_assoc();
                        $id_contacto = $row_id_contacto['id_contacto'];
                    } else {
                        throw new Exception("No se encontró el id_contacto para el usuario.");
                    } 
                    $sql_etiquetas = "SELECT id_etiqueta FROM etiqueta WHERE id_contacto = $id_contacto";
                    $result_etiquetas = $conn->query($sql_etiquetas);

                    while ($row_etiqueta = $result_etiquetas->fetch_assoc()) {
                        echo "<option value='{$row_etiqueta['id_etiqueta']}'>{$row_etiqueta['id_etiqueta']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="card-venta">
                <label for="id_domicilio">Selecciona un domicilio:</label>
                <select id="id_domicilio" name="id_domicilio" required>
                    <?php
                    $nombreUsuario = $_SESSION['usuario'];
                    $currt_user = "SELECT id_contacto FROM contacto WHERE user_name = '$nombreUsuario'";
                    $result_id_contacto = $conn->query($currt_user);
                    if ($result_id_contacto->num_rows > 0) {
                        $row_id_contacto = $result_id_contacto->fetch_assoc();
                        $id_contacto = $row_id_contacto['id_contacto'];
                    } else {
                        throw new Exception("No se encontró el id_contacto para el usuario.");
                    } 
                    $sql_domicilios = "SELECT id_domicilio FROM domicilio WHERE id_contacto = $id_contacto";
                    $result_domicilios = $conn->query($sql_domicilios);

                    while ($row_domicilio = $result_domicilios->fetch_assoc()) {
                        echo "<option value='{$row_domicilio['id_domicilio']}'>{$row_domicilio['id_domicilio']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="card-venta">
                <label for="cantidad">Cantidad</label>
                <input type="number" id="cantidad" name="cantidad" required>
            </div>
            <div class="card-venta">
                <label for="comentarios">Comentarios</label>
                <input type="text" id="comentarios" name="comentarios" required>
            </div>
            <div class="card-venta">
                <button type="submit" name="insert_venta" class="button">Finalizar Venta</button>
            </div>
        </form>

        <?php
        $conn->close();
        ?>
    </div>
</body>

</html>