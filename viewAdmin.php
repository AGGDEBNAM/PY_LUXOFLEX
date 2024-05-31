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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">

<header class="bg-white shadow-md dark:bg-gray-900">
    <nav class="container mx-auto p-4 flex items-center justify-between">
        <div class="flex items-center">
            <a href="inicio_admins.php">
                <img src="img_py/LUXFLEX.PNG" alt="LUXO FLEX" class="h-10" />
            </a>
            <ul class="ml-10 flex space-x-4">
                <li><a href="/pricing" class="text-gray-500 dark:text-gray-400">CONTACTO</a></li>
                <li><a href="/about" class="text-gray-500 dark:text-gray-400">ABOUT</a></li>
            </ul>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="flex space-x-2">
            <button type="submit" name="admin_users" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Admin Users</button>
            <button type="submit" name="logout" class="px-4 py-2 bg-red-500 text-white rounded-lg">Log out</button>
        </form>
    </nav>
</header>

<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-semibold mb-4">Vista de Ventas</h2>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Fecha</th>
                    <th class="px-6 py-3">Usuario</th>
                    <th class="px-6 py-3">Diseño de la Etiqueta</th>
                    <th class="px-6 py-3">Dirección</th>
                    <th class="px-6 py-3">Código Postal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>";
                        echo "<td class='px-6 py-4'>{$row['fecha']}</td>";
                        echo "<td class='px-6 py-4'>{$row['usuario']}</td>";
                        echo "<td class='px-6 py-4'>{$row['diseño_etiqueta']}</td>";
                        echo "<td class='px-6 py-4'>{$row['direccion']}</td>";
                        echo "<td class='px-6 py-4'>{$row['codigo_postal']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='px-6 py-4 text-center'>No se encontraron ventas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <h2 class="text-2xl font-semibold mt-8 mb-4">Vista de Etiquetas</h2>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Medida Alto</th>
                    <th class="px-6 py-3">Medida Ancho</th>
                    <th class="px-6 py-3">Diseño</th>
                    <th class="px-6 py-3">Usuario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_etiquetas->num_rows > 0) {
                    while ($row = $result_etiquetas->fetch_assoc()) {
                        echo "<tr class='bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600'>";
                        echo "<td class='px-6 py-4'>{$row['medida_alto']}</td>";
                        echo "<td class='px-6 py-4'>{$row['medida_ancho']}</td>";
                        echo "<td class='px-6 py-4'>{$row['disenio']}</td>";
                        echo "<td class='px-6 py-4'>{$row['user_name']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='px-6 py-4 text-center'>No se encontraron etiquetas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$conn->close();
?>

</body>
</html>
