<?php
require('fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',12);
        $this->Cell(0,10,'Informe de Ventas',0,1,'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
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
            foreach ($sale as $data) {
                $this->Cell($w[key($sale)], 6, $data, 1);
                next($sale);
            }
            $this->Ln();
        }
    }
}

// Conexión a la base de datos (asumiendo MySQLi)
$mysqli = new mysqli("localhost", "root", "", "luxoflexavz");

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}

// Consulta para obtener datos de ventas
$query = "SELECT c.nombre AS cliente, v.fecha_venta, v.cantidad, v.disenio 
          FROM venta v
          JOIN contacto c ON v.num_cotizacion = c.num_cotizacion";
$result = $mysqli->query($query);

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

