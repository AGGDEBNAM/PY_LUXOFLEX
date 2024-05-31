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
    $id_etiqueta = $_GET['id'];

    $sql = "SELECT * FROM etiqueta WHERE id_etiqueta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_etiqueta);
    $stmt->execute();
    $result = $stmt->get_result();
    $etiqueta = $result->fetch_assoc();
    $stmt->close();

    if (!$etiqueta) {
        header("Location: etiquetas.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_etiqueta"])) {
    // Recoger y validar los datos aquí
    $tipo_forma = $_POST['tipo_forma'];
    $medida_ancho = $_POST['medida_ancho'];
    $medida_alto = $_POST['medida_alto'];
    $medida_circunferencia = $_POST['medida_circunferencia'];
    $material_etiqueta = $_POST['material_etiqueta'];
    $laminado = $_POST['laminado'];
    $material_aplicacion = $_POST['material_aplicacion'];
    $cantidad_de_colores = $_POST['cantidad_de_colores'];
    $colores = $_POST['colores'];
    $ruta = $etiqueta['disenio'];

    // Manejar la carga del archivo de diseño, si es necesario
    if (isset($_FILES["disenio"]) && $_FILES["disenio"]["error"] == 0) {
        $imagen = $_FILES["disenio"];
        $directorioDestino = "imagenes/";
        $ruta = $directorioDestino . basename($imagen["name"]);
        move_uploaded_file($imagen["tmp_name"], $ruta);
    }

    // Actualizar información de la etiqueta
    $sql = "UPDATE etiqueta SET tipo_forma = ?, medida_ancho = ?, medida_alto = ?, medida_circunferencia = ?, material_etiqueta = ?, laminado = ?, material_aplicacion = ?, cantidad_de_colores = ?, colores = ?, disenio = ? WHERE id_etiqueta = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssi", $tipo_forma, $medida_ancho, $medida_alto, $medida_circunferencia, $material_etiqueta, $laminado, $material_aplicacion, $cantidad_de_colores, $colores, $ruta, $id_etiqueta);
    $stmt->execute();
    $stmt->close();

    // Redireccionar de nuevo a etiquetas.php
    header("Location: etiquetas.php");
    exit();
}

// Manejar otras acciones del formulario
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUXO FLEX</title>
    <link rel="stylesheet" type="text/css" href="Etiquetas.css">
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
        <h2>Editar Etiqueta</h2>
        <form action="editar_etiqueta.php?id=<?php echo $id_etiqueta; ?>" method="post" enctype="multipart/form-data">
            <!-- Forma de la etiqueta -->
            <div class="card-etiqueta">
                <label for="tipo_forma">Forma de la etiqueta:</label>
                <select name="tipo_forma" id="tipo_forma">
                    <option value="cuadrada" <?php echo $etiqueta['tipo_forma'] == 'cuadrada' ? 'selected' : ''; ?>>Cuadrada</option>
                    <option value="circular" <?php echo $etiqueta['tipo_forma'] == 'circular' ? 'selected' : ''; ?>>Circular</option>
                    <option value="especial" <?php echo $etiqueta['tipo_forma'] == 'especial' ? 'selected' : ''; ?>>Especial</option>
                </select>
            </div>

            <!-- Medida Ancho -->
            <div class="card-etiqueta">
                <label for="medida_ancho">Medida del ancho:</label>
                <input type="text" name="medida_ancho" id="medida_ancho" value="<?php echo $etiqueta['medida_ancho']; ?>" required>
            </div>

            <!-- Medida Alto -->
            <div class="card-etiqueta">
                <label for="medida_alto">Medida del alto:</label>
                <input type="text" name="medida_alto" id="medida_alto" value="<?php echo $etiqueta['medida_alto']; ?>" required>
            </div>

            <!-- Medida Circunferencia -->
            <div class="card-etiqueta">
                <label for="medida_circunferencia">Medida de la circunferencia:</label>
                <input type="text" name="medida_circunferencia" id="medida_circunferencia" value="<?php echo $etiqueta['medida_circunferencia']; ?>" required>
            </div>

            <!-- Material de la Etiqueta -->
            <div class="card-etiqueta">
                <label for="material_etiqueta">Material de la etiqueta:</label>
                <select name="material_etiqueta" id="material_etiqueta">
                    <!-- Las opciones deben ser ajustadas según los materiales disponibles -->
                    <option value="couche" <?php echo $etiqueta['material_etiqueta'] == 'couche' ? 'selected' : ''; ?>>Couche</option>
                    <option value="bopp blanco" <?php echo $etiqueta['material_etiqueta'] == 'bopp blanco' ? 'selected' : ''; ?>>Bopp blanco</option>
                    <!-- Añadir más opciones según sea necesario -->
                </select>
            </div>

            <!-- Laminado -->
            <div class="card-etiqueta">
                <label for="laminado">Laminado:</label>
                <select name="laminado" id="laminado">
                    <option value="mate" <?php echo $etiqueta['laminado'] == 'mate' ? 'selected' : ''; ?>>Mate</option>
                    <option value="brillante" <?php echo $etiqueta['laminado'] == 'brillante' ? 'selected' : ''; ?>>Brillante</option>
                </select>
            </div>

            <!-- Material Aplicación -->
            <div class="card-etiqueta">
                <label for="material_aplicacion">Forma de aplicar:</label>
                <select name="material_aplicacion" id="material_aplicacion">
                    <option value="manual" <?php echo $etiqueta['material_aplicacion'] == 'manual' ? 'selected' : ''; ?>>Manual</option>
                    <option value="automatizada" <?php echo $etiqueta['material_aplicacion'] == 'automatizada' ? 'selected' : ''; ?>>Automatizada</option>
                </select>
            </div>

            <!-- Cantidad de Colores -->
            <div class="card-etiqueta">
                <label for="cantidad_de_colores">Cantidad de tintas/colores:</label>
                <input type="number" name="cantidad_de_colores" id="cantidad_de_colores" value="<?php echo $etiqueta['cantidad_de_colores']; ?>" required>
            </div>

            <!-- Colores a Utilizar -->
            <div class="card-etiqueta">
                <label for="colores">Colores a utilizar:</label>
                <input type="text" name="colores" id="colores" value="<?php echo $etiqueta['colores']; ?>" required>
            </div>

            <!-- Diseño (Archivo) -->
            <div class="card-etiqueta-file">
                <label for="disenio">Seleccionar Archivo (opcional):</label>
                <input type="file" name="disenio" id="disenio" accept="image/*">
            </div>
            
            <div class="card-etiqueta">
            <button type="submit" name="update_etiqueta" class="button">Actualizar Etiqueta</button>
            </div>

        </form>
    </div>
</body>
</html>
