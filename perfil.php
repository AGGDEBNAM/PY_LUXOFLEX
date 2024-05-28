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

    if (isset($_POST["insert_domicilio"])) {
        try {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "luxoflex";

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                throw new Exception("Error de conexión: " . $conn->connect_error);
            }

            $pais = $_POST["pais"];
            $ciudad = $_POST["ciudad"];
            $estado = $_POST["estado"];
            $direccion = $_POST["direccion"];
            $codigo_postal = $_POST["codigo_postal"];
            $rfc = $_POST["rfc"];

            $nombreUsuario = $_SESSION['usuario'];
            $currt_user = "SELECT id_contacto, user_name, email, telefono, empresa FROM contacto WHERE user_name = '$nombreUsuario'";
            $result_user_name = $conn->query($currt_user);

            if ($result_user_name === false) {
                throw new Exception("Error en la consulta SQL: " . $conn->error);
            }

            $row_user_name = $result_user_name->fetch_assoc();

            if ($row_user_name === null) {
                // throw new Exception("No se encontraron datos para el usuario.");
                echo "<p>No se encontraron datos para el usuario. Redirigiendo a la página de inicio...</p>";
                header("Refresh: 3; url=inicio.php");
                exit();
            }

            $id_contacto = $row_user_name['id_contacto'];

            $insert_query = "INSERT INTO domicilio (pais, ciudad, Estado, direccion, codigo_postal, rfc, id_contacto) 
                            VALUES ('$pais', '$ciudad', '$estado', '$direccion', $codigo_postal, '$rfc', $id_contacto)";

            if ($conn->query($insert_query) === false) {
                throw new Exception("Error en la consulta SQL: " . $conn->error);
            }

            header("Location: perfil.php");
            exit();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "luxoflex";

try {
    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    $nombreUsuario = $_SESSION['usuario'];

    $currt_user = "SELECT id_contacto, user_name, email, telefono, empresa FROM contacto WHERE user_name = '$nombreUsuario'";

    $result_user_name = $conn->query($currt_user);

    if ($result_user_name === false) {
        throw new Exception("Error en la consulta SQL: " . $conn->error);
    }

    $row_user_name = $result_user_name->fetch_assoc();

    if ($row_user_name === null) {
        // throw new Exception("No se encontraron datos para el usuario.");
        echo "<p>No se encontraron datos para el usuario. Redirigiendo a la página de inicio...</p>";
        header("Refresh: 3; url=inicio.php");
        exit();
    }

    $user_name = $row_user_name['user_name'];
    $email = $row_user_name['email'];
    $telefono = $row_user_name['telefono'];
    $empresa = $row_user_name['empresa'];

    $id_contacto = $row_user_name['id_contacto'];
    $get_domicilios_query = "SELECT id_domicilio, pais, ciudad, Estado, direccion, codigo_postal, rfc
                            FROM domicilio
                            WHERE id_contacto = $id_contacto";

    $result_domicilios = $conn->query($get_domicilios_query);

    if ($result_domicilios === false) {
        throw new Exception("Error en la consulta SQL: " . $conn->error);
    }

?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LUXO FLEX</title>
        <link rel="stylesheet" type="text/css" href="perfil.css" />

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

            <h1>informacion del perfil.</h1>
            <?php
            echo "<p><strong>Nombre de usuario:</strong> $user_name</p>";
            echo "<p><strong>Email:</strong> $email</p>";
            echo "<p><strong>Teléfono:</strong> $telefono</p>";
            echo "<p><strong>Nombre de la empresa:</strong> $empresa</p>";
            ?>
            <div class="card-domicilio">
                <a href="editar_perfil.php" class="button">Editar Perfil</a>
            </div>
            <h2>Inserte datos de sus domicilios.</h2>

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="card-domicilio">
                    <label for="pais">País:</label>
                    <input type="text" name="pais" required>
                </div>
                <div class="card-domicilio">
                    <label for="ciudad">Ciudad:</label>
                    <input type="text" name="ciudad" required>
                </div>
                <div class="card-domicilio">
                    <label for="estado">Estado:</label>
                    <input type="text" name="estado" required>
                </div>
                <div class="card-domicilio">
                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" required>
                </div>
                <div class="card-domicilio">
                    <label for="codigo_postal">Código Postal:</label>
                    <input type="text" name="codigo_postal" required>
                </div>
                <div class="card-domicilio">
                    <label for="rfc">RFC:</label>
                    <input type="text" name="rfc" required>
                </div>
                <div class="card-domicilio">
                    <button type="submit" name="insert_domicilio" class="button">Insertar Domicilio</button>
                </div>
            </form>

            <h2>Domicilios Registrados</h2>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["edit_domicilio"])) {
                    $edit_id = $_POST["edit_id"];
                    header("Location: editar_domicilio.php?id=$edit_id");
                    exit();
                } elseif (isset($_POST["delete_domicilio"])) {
                    $delete_id = $_POST["delete_id"];

                    $check_venta_query = "SELECT COUNT(*) as count FROM venta WHERE id_domicilio = $delete_id";
                    $result = $conn->query($check_venta_query);

                    if ($result === false) {
                        throw new Exception("Error en la consulta SQL de verificación de venta: " . $conn->error);
                    }

                    $row = $result->fetch_assoc();
                    $venta_count = $row["count"];

                    if ($venta_count > 0) {
                        echo "<p>No se puede eliminar el domicilio porque tiene ventas asociadas. Antes de eliminar se debe completar la venta.</p>";
                    } else {
                        $delete_query = "DELETE FROM domicilio WHERE id_domicilio = $delete_id";

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
                        <th>País</th>
                        <th>Ciudad</th>
                        <th>Estado</th>
                        <th>Dirección</th>
                        <th>Código Postal</th>
                        <th>RFC</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_domicilios->num_rows > 0) {
                        while ($row_domicilio = $result_domicilios->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row_domicilio['id_domicilio']}</td>";
                            echo "<td>{$row_domicilio['pais']}</td>";
                            echo "<td>{$row_domicilio['ciudad']}</td>";
                            echo "<td>{$row_domicilio['Estado']}</td>";
                            echo "<td>{$row_domicilio['direccion']}</td>";
                            echo "<td>{$row_domicilio['codigo_postal']}</td>";
                            echo "<td>{$row_domicilio['rfc']}</td>";

                            echo "<td>";
                            echo "<form method='post' action='{$_SERVER['PHP_SELF']}'>";
                            echo "<input type='hidden' name='edit_id' value='{$row_domicilio['id_domicilio']}'>";
                            echo "<button type='submit' name='edit_domicilio'>Editar</button>";
                            echo "</form>";

                            echo "<form method='post' action='{$_SERVER['PHP_SELF']}'>";
                            echo "<input type='hidden' name='delete_id' value='{$row_domicilio['id_domicilio']}'>";
                            echo "<button type='submit' name='delete_domicilio'>Eliminar</button>";
                            echo "</form>";
                            echo "</td>";

                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No hay domicilios registrados.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
    ?>

    </body>

    </html>