<?php
require('fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Informe de Etiquetas', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    function LabelsReport($labelsData) {
        // Títulos de las columnas
        $header = array('Tipo de Forma', 'Medidas', 'Aplicación', 'Material', 'Diseño', 'Fecha de Actualización');

        // Anchuras de las columnas (ajustadas)
        $w = array(30, 30, 30, 30, 30, 50);

        // Imprimir títulos de columnas
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        }
        $this->Ln();

        // Imprimir filas de datos
        foreach ($labelsData as $label) {
            $this->Cell($w[0], 6, $label['tipo_forma'], 1);
            $this->Cell($w[1], 6, $label['medida_ancho'] . 'x' . $label['medida_alto'] . 'x' . $label['medida_circunferencia'], 1);
            $this->Cell($w[2], 6, $label['material_aplicacion'], 1);
            $this->Cell($w[3], 6, $label['material_etiqueta'], 1);
            $this->Cell($w[4], 6, $label['disenio'], 1);
            $this->Cell($w[5], 6, $label['fecha_actualizacion'], 1);
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

// Consulta para obtener datos de etiquetas
$query = "SELECT tipo_forma, medida_ancho, medida_alto, medida_circunferencia, material_aplicacion, material_etiqueta, disenio, CURRENT_TIMESTAMP AS fecha_actualizacion FROM etiqueta";
$result = $mysqli->query($query);

// Verificar si hay errores en la consulta
if (!$result) {
    die("Error en la consulta SQL: " . $mysqli->error);
}

// Almacenar datos en un array para el informe
$labelsData = array();
while ($row = $result->fetch_assoc()) {
    $labelsData[] = $row;
}

// Crear instancia de PDF
$pdf = new PDF();
$pdf->AddPage();

// Generar informe utilizando el método personalizado
$pdf->LabelsReport($labelsData);

// Salida del PDF
$pdf->Output();
?>
