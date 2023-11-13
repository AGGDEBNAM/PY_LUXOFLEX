<?php
include_once 'conexion.php';

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$empresa = $_POST['empresa'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$num_cotizacion = $_POST['num_cotizacion'];

// Preparar la consulta SQL
$sql = "INSERT INTO contacto (nombre, empresa, email, telefono, num_cotizacion) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nombre, $empresa, $email, $telefono, $num_cotizacion);

// Ejecutar la consulta
if ($stmt->execute()) {
    echo "Nuevo contacto insertado con éxito.";
} else {
    echo "Error al insertar el contacto: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
