<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "luxoflex";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}

$nombreUsuario = $_SESSION['usuario'];
$currt_user = "SELECT id_contacto FROM contacto WHERE user_name = '$nombreUsuario'";
$result_id_contacto = $conn->query($currt_user);
$row_id_contacto = $result_id_contacto->fetch_assoc();
$id_contacto = $row_id_contacto['id_contacto'];

$sql_etiquetas = "SELECT * FROM etiqueta WHERE id_contacto = $id_contacto";
$result_etiquetas = $conn->query($sql_etiquetas);

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

    if (isset($_POST["insert_etiqueta"])) {
        if (isset($_FILES["disenio"])) {
            $imagen = $_FILES["disenio"];
            $directorioDestino = "imagenes/";
            $ruta = $directorioDestino . basename($imagen["name"]);
            move_uploaded_file($imagen["tmp_name"], $ruta);
        }

        chmod("imagenes/", 0777);

        $tipo_forma = $_POST['tipo_forma'];
        $medida_ancho = $_POST['medida_ancho'];
        $medida_alto = $_POST['medida_alto'];
        $medida_circunferencia = $_POST['medida_circunferencia'];
        $material_etiqueta = $_POST['material_etiqueta'];
        $laminado = $_POST['laminado'];
        $material_aplicacion = $_POST['material_aplicacion'];
        $cantidad_de_colores = $_POST['cantidad_de_colores'];
        $colores = $_POST['colores'];

        $sql_etiqueta = "INSERT INTO `etiqueta` (`tipo_forma`, `medida_ancho`, `medida_alto`, `medida_circunferencia`, `material_etiqueta`, `laminado`, `material_aplicacion`, `cantidad_de_colores`, `colores`, `disenio`, `id_contacto`)
                VALUES ('$tipo_forma', '$medida_ancho', '$medida_alto', '$medida_circunferencia', '$material_etiqueta','$laminado', '$material_aplicacion', '$cantidad_de_colores', '$colores', '$ruta', '$id_contacto')";

        if ($conn->query($sql_etiqueta) === TRUE) {
            header("Location: etiquetas.php");
            exit();
        } else {
            echo "Error al guardar los datos: " . $conn->error;
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }
    }

    if (isset($_POST["edit_etiqueta"])) {
        $edit_id = $_POST["edit_id"];
        header("Location: editar_etiqueta.php?id=$edit_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUXO FLEX</title>
    <link rel="stylesheet" type="text/css" href="Etiquetas.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <h2>Pedir una cotización</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="card-etiqueta">
                <label for="tipo_forma">Forma de la etiqueta</label>
                <select name="tipo_forma" id="tipo_forma">
                    <option value="cuadrada">Cuadrada</option>
                    <option value="circular">Circular</option>
                    <option value="especial">Especial</option>
                </select>
            </div>
            <div class="card-etiqueta">
                <label for="medida_ancho">Medida del ancho</label>
                <input type="text" name="medida_ancho" id="medida_ancho" required>
            </div>
            <div class="card-etiqueta">
                <label for="medida_alto">Medida del alto</label>
                <input type="text" name="medida_alto" id="medida_alto" required>
            </div>
            <div class="card-etiqueta">
                <label for="medida_circunferencia">Medida de la circunferencia</label>
                <input type="text" name="medida_circunferencia" id="medida_circunferencia" required>
            </div>
            <div class="card-etiqueta">
                <label for="material_etiqueta">Material de la etiqueta</label>
                <select name="material_etiqueta" id="material_etiqueta">
                    <option value="couche">Couche</option>
                    <option value="bopp blanco">Bopp blanco</option>
                    <option value="bopp transparente">Bopp transparente</option>
                    <option value="bopp metalizado">Bopp metalizado</option>
                    <option value="holografico">Holográfico</option>
                    <option value="transferencia termica">Transferencia térmica</option>
                </select>
            </div>
            <div class="card-etiqueta">
                <label for="laminado">Laminado</label>
                <select name="laminado" id="laminado">
                    <option value="mate">Laminado mate</option>
                    <option value="brillante">Laminado brillante</option>
                </select>
            </div>
            <div class="card-etiqueta">
                <label for="material_aplicacion">Forma de aplicar</label>
                <select name="material_aplicacion" id="material_aplicacion">
                    <option value="manual">Manual</option>
                    <option value="automatizada">Automatizada</option>
                </select>
            </div>
            <div class="card-etiqueta">
                <label for="cantidad_de_colores">Cantidad de tintas/colores</label>
                <select name="cantidad_de_colores" id="cantidad_de_colores">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                </select>
            </div>
            <div class="card-etiqueta">
                <label for="colores">Colores a utilizar</label>
                <input type="text" name="colores" id="colores" required>
            </div>
            <div class="card-etiqueta-file">
                <label for="disenio">Seleccionar Archivo</label>
                <input type="file" name="disenio" id="disenio" accept="image/*" />
            </div>
            <div class="card-etiqueta">
                <button type="submit" name="insert_etiqueta" class="button">Insertar Etiqueta</button>
            </div>
        </form>

        <h2>Etiquetas Registradas</h2>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST["delete_etiqueta"])) {
                $delete_id = $_POST["delete_id"];

                $sql_etiqueta = "SELECT COUNT(*) as count FROM venta WHERE id_etiqueta = $delete_id";
                $result = $conn->query($sql_etiqueta);

                if ($result === false) {
                    throw new Exception("Error en la consulta SQL de verificación de venta: " . $conn->error);
                }

                $row = $result->fetch_assoc();
                $etiqueta_count = $row["count"];

                if ($etiqueta_count > 0) {
                    echo "<p>No se puede eliminar la etiqueta porque tiene ventas asociadas. Antes de eliminar se debe completar la venta.</p>";
                } else {
                    $delete_query = "DELETE FROM etiqueta WHERE id_etiqueta = $delete_id";

                    if ($conn->query($delete_query) === false) {
                        throw new Exception("Error en la consulta SQL de eliminación: " . $conn->error);
                    }

                    header("Location: {$_SERVER['PHP_SELF']}");
                    exit();
                }
            }
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo Forma</th>
                    <th>Ancho</th>
                    <th>Alto</th>
                    <th>Circunferencia</th>
                    <th>Material</th>
                    <th>Laminado</th>
                    <th>Material Aplicacion</th>
                    <th>Cantidad Colores</th>
                    <th>Colores</th>
                    <th>Diseño</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_etiquetas->num_rows > 0) {
                    while ($row_etiqueta = $result_etiquetas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row_etiqueta['id_etiqueta']}</td>";
                        echo "<td>{$row_etiqueta['tipo_forma']}</td>";
                        echo "<td>{$row_etiqueta['medida_ancho']}</td>";
                        echo "<td>{$row_etiqueta['medida_alto']}</td>";
                        echo "<td>{$row_etiqueta['medida_circunferencia']}</td>";
                        echo "<td>{$row_etiqueta['material_etiqueta']}</td>";
                        echo "<td>{$row_etiqueta['laminado']}</td>";
                        echo "<td>{$row_etiqueta['material_aplicacion']}</td>";
                        echo "<td>{$row_etiqueta['cantidad_de_colores']}</td>";
                        echo "<td>{$row_etiqueta['colores']}</td>";
                        echo "<td><img src='{$row_etiqueta['disenio']}' alt='Diseño' width='100'></td>";

                        echo "<td>";
                        echo "<div class='button-container'>";
                        echo "<form method='post' action='{$_SERVER['PHP_SELF']}'>";
                        echo "<input type='hidden' name='edit_id' value='{$row_etiqueta['id_etiqueta']}'>";
                        echo "<button type='submit' name='edit_etiqueta' title='Editar'><i class='fas fa-edit'></i></button>";
                        echo "</form>";

                        echo "<form method='post' action='{$_SERVER['PHP_SELF']}'>";
                        echo "<input type='hidden' name='delete_id' value='{$row_etiqueta['id_etiqueta']}'>";
                        echo "<button type='submit' name='delete_etiqueta' title='Eliminar'><i class='fas fa-trash'></i></button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No hay etiquetas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>