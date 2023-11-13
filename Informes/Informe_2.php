<?php
require('fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',12);
        $this->Cell(0,10,'Informe de Etiquetas',0,1,'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Página '.$this->PageNo(),0,0,'C');
    }

    function LabelsReport($labelsData) {
        // Títulos de las columnas
        $header = array('Tipo de Forma', 'Medidas', 'Aplicación', 'Material', 'Precio Unitario', 'Fecha de Actualización');

        // Anchuras de las columnas
        $w = array(40, 30, 40, 30, 30, 50);

        // Imprimir títulos de columnas
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        }
        $this->Ln();

        // Imprimir filas de datos
        foreach ($labelsData as $label) {
            foreach ($label as $data) {
                $this->Cell($w[key($label)], 6, $data, 1);
                next($label);
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

// Consulta para obtener datos de etiquetas
$query = "SELECT * FROM etiqueta";
$result = $mysqli->query($query);

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
