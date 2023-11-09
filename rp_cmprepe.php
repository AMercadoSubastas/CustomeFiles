<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior

$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];


$anio = "";
$mes = "";
$anio = substr($fecha_desde,6,4);
$mes = substr($fecha_desde,3,2);
$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";
$fechahoy = date("d-m-Y");

// Leo la cabecera

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = 32 AND fecreg BETWEEN %s AND %s ORDER BY ncomp ", $fecha_desde, $fecha_hasta);
//echo "QUERY = ".$query_cabfac."  ";
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABECERA DE FACTURAS");

$pri_vez = 1;
$i = 0;
$pdf=new FPDF();

$titulo = 18;
$linea = 24;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
    $tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
    $nrodoc     = $row_cabecerafac["nrodoc"];
    $fecreg     = $row_cabecerafac["fecreg"];
    if ($pri_vez == 1) {
        $ncomp_ant = $ncomp;
        $nrodoc_ant = $nrodoc;
        $fecreg_ant = $fecreg;
        $pri_vez = 0;
        //echo "PRIMERA VEZ";
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(1 , 2) ;
        $pdf->SetFont('Arial','B',12);
        $pdf->SetY($titulo);
        $pdf->Cell(18);
        $pdf->Cell(70,10,"CBTES REPETIDOS",0,0,'C');
        $i = 0;
        $linea = 24;
        //echo "  CAMBIO PAGINA 
    }
    else {
        if ($ncomp_ant == $ncomp) {
            if ($i > 40) {
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(1 , 2) ;
                $pdf->SetFont('Arial','B',12);
                $pdf->SetY($titulo);
                $pdf->Cell(18);
                $pdf->Cell(70,10,"CBTES REPETIDOS",0,0,'C');
                $i = 0;
                $linea = 24;
                //echo "  CAMBIO PAGINA  ";
            }
            $pdf->SetY($linea);
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(18);
            //echo "  IMPRIMO CBTE ".$ncomp." ".$ncomp_ant." - ";
            $pdf->Cell(80,10,"NCOMP = ".$ncomp_ant." NRODOC = ".$nrodoc_ant."  FECHA = ".$fecreg_ant." ",0,0,'L');
            $linea += 6;
            $pdf->SetY($linea);
            $pdf->Cell(18);
            $pdf->Cell(80,10,"NCOMP = ".$ncomp." NRODOC = ".$nrodoc."  FECHA = ".$fecreg." ",0,0,'L');
            $linea += 6;
            $i++;
            $i++;
            
        }
    }
    //echo "paso por un cbte, NCOMP_ANT = ".$ncomp_ant."  NCOMP = ".$ncomp." - ";
    $ncomp_ant = $ncomp;
    $nrodoc_ant = $nrodoc;
    $fecreg_ant = $fecreg;
}
//mysqli_close($amercado);
$pdf->Output();
?>