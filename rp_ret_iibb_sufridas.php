<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES DE CLIENTES
define('FC_CLI_MAN_A','6');
define('FC_CLI_AUT_A','1');
define('FC_CLI_MAN_B','24');
define('FC_CLI_AUT_B','23');
define('NC_CLI_MAN_A','7');
define('NC_CLI_AUT_A','5');
define('NC_CLI_MAN_B','26');
define('NC_CLI_AUT_B','25');
define('ND_CLI_MAN_A','22');
define('ND_CLI_AUT_A','21');
define('ND_CLI_MAN_B','30');
define('ND_CLI_AUT_B','29');
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES DE PROVEEDORES
define('FC_PRO_A','32');
define('FC_PRO_B','33');
define('NC_PRO_A','36');
define('NC_PRO_B','37');
define('ND_PRO_A','34');
define('ND_PRO_B','35');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$fecha_desde = "'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";


$fechahoy = date("d-m-Y");


// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die(mysqli_error($amercado));
$row_Recordset2 = mysqli_fetch_assoc($impuestos);
$totalRows_Recordset2 = mysqli_num_rows($impuestos);
$porc_iva105 = (mysqli_result($impuestos,1, 1)/100); 
$porc_iva21  = (mysqli_result($impuestos,0, 1)/100);


// Leo la cabecera

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = 1 AND fecreg BETWEEN %s AND %s ORDER BY fecreg", $fecha_desde, $fecha_hasta);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));

// Inicio el pdf con los datos de cabecera
  $pdf=new FPDF('P','mm','A4');
  
  $pdf->AddPage();
  //$pdf->SetAutoPageBreak(1 , 2) ;
  $pdf->SetMargins(0.5, 0.5 , 0.5);
  $pdf->SetFont('Arial','B',11);
  $pdf->SetY(5);
  $pdf->Cell(10);
  $pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
  $pdf->Cell(120);
  $pagina = $pdf->PageNo();
  $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
  $pdf->SetY(10);
  $pdf->Cell(150);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(70);
  $pdf->Cell(20,10,' Percepciones y Retenciones Sufridas IIBB',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(10);
  $pdf->Cell(50,16,' Denominaci�n ',1,0,'L');
  $pdf->Cell(35,16,' Comprobante ',1,0,'L');
  $pdf->Cell(35,16,' C.U.I.T. ',1,0,'L');
  $pdf->Cell(30,16,' I.I.B.B. ',1,0,'L');
  $pdf->Cell(35,16,' Importe ',1,0,'L');
  $pdf->SetY(33);
  $pdf->Cell(130);
  $pdf->Cell(35,8,'  Fecha',0,0,'L');
    
  $valor_y = 45;
  
$i = 60;
$j = 100;
$total = 0;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	$fcomp      = $row_cabecerafac["fecreg"];
	$nrodoc     = $row_cabecerafac["nrodoc"];
	$tc = "FC-";
	$signo = 1;
	
	if ($tcomp !=  1 && $tcomp !=  2) 
		continue;

	$cliente      = $row_cabecerafac["cliente"];
	
	// Leo el cliente
  	$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  	$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  	$row_entidades = mysqli_fetch_assoc($enti);
  	
	$cuit         = $row_entidades["cuit"];
	$razsoc       = $row_entidades["razsoc"];
		
	// Leo cartvalores
	$query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE  tcomp = 41 AND ((tcomprel = %d AND serierel = %d AND ncomprel = %d) OR (tcompsal = %d AND seriesal = %d AND ncompsal = %d))", $tcomp, $serie, $ncomp, $tcomp, $serie, $ncomp);
  	$cartvalores       = mysqli_query($amercado, $query_cartvalores) or die(mysqli_error($amercado));
  	$row_cartvalores   = mysqli_fetch_assoc($cartvalores);
		
	if (mysqli_num_rows($cartvalores)==0)
		continue;
					
	$importe   = $row_cartvalores["importe"];
	$total     = $total + $importe;
	$importe   = number_format($importe, 2, ',','.');
				
	// Acumulo subtotales
	$pdf->SetY($valor_y);
  	$pdf->Cell(10);
  	$pdf->Cell(50,8,$razsoc,0,0,'L');
  	$pdf->Cell(35,8,$nrodoc,0,0,'L');
  	$pdf->Cell(35,8,$cuit,0,0,'L');
  	$pdf->Cell(30,8,$fcomp,0,0,'L');
  	$pdf->Cell(35,8,$importe,0,0,'R');


		
	$valor_y = $valor_y + 6;
}
$pdf->SetY($valor_y);
$pdf->Cell(130);
$total   = number_format($total, 2, ',','.');
$pdf->Cell(30,8,'TOTAL PERCIBIDO: ',0,0,'R');
$pdf->Cell(35,8,$total,0,0,'R');
mysqli_close($amercado);

$pdf->Output();
?>  
