<?php
require('fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Informe de Ventas', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function SalesReport($salesData) {
        // Títulos de las columnas
        $header = array('Cliente', 'Fecha de Venta', 'Cantidad', 'Diseño');

        // Anchuras de las columnas
        $w = array(40, 40, 30, 80);

        // Imprimir títulos de columnas
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        }
        $this->Ln();

        // Imprimir filas de datos
        foreach ($salesData as $sale) {
            $this->Cell($w[0], 6, $sale['user_name'], 1);
            $this->Cell($w[1], 6, $sale['fecha'], 1);
            $this->Cell($w[2], 6, $sale['cantidad'], 1);
            $this->Cell($w[3], 6, $sale['disenio'], 1);
            $this->Ln();
        }
    }
}

// Conexión a la base de datos (asumiendo MySQLi)
$mysqli = new mysqli("localhost", "root", "", "luxoflex");

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}

// Consulta para obtener datos de ventas
$query = "SELECT c.user_name, v.fecha, v.cantidad, e.disenio 
          FROM venta v
          JOIN contacto c ON v.id_contacto = c.id_contacto
          JOIN etiqueta e ON v.id_etiqueta = e.id_etiqueta";
$result = $mysqli->query($query);

// Verificar si hay errores en la consulta
if (!$result) {
    die("Error en la consulta SQL: " . $mysqli->error);
}

// Almacenar datos en un array para el informe
$salesData = array();
while ($row = $result->fetch_assoc()) {
    $salesData[] = $row;
}

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AddPage();

// Generar informe utilizando el método personalizado
$pdf->SalesReport($salesData);

// Salida del PDF
$pdf->Output();
?>
