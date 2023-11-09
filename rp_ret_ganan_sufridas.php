<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout

//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES DE PROVEEDORES
define('FC_CLI_A_LOT','51');
define('FC_CLI_A_CON','52');
define('FC_CLI_B_LOT','53');
define('FC_CLI_B_CON','54');
define('NC_CLI_A','57');
define('NC_CLI_B','58');
define('ND_CLI_A','55');
define('ND_CLI_B','56');
define('FC_CLI_A_INM','59');
define('FC_CLI_B_INM','60');
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
//$fecha_desde = "2008-01-01";
//$fecha_hasta = "2008-04-30";

$fechahoy = date("d-m-Y");
// Leo los renglones

// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO IMPUESTOS");
$row_Recordset2 = mysqli_fetch_assoc($impuestos);
$totalRows_Recordset2 = mysqli_num_rows($impuestos);
$porc_iva105 = (mysqli_result($impuestos,1, 1)/100); 
$porc_iva21  = (mysqli_result($impuestos,0, 1)/100);


// Leo la cabecera


$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta ORDER BY fecreg");
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABECERA DE FACTURA");



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
  $pdf->Cell(20,10,' Percepciones y Retenciones Sufridas Ganancias',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(10);
  $pdf->Cell(50,16,' CLIENTE ',1,0,'L');
  $pdf->Cell(35,16,' Comprobante ',1,0,'L');
  $pdf->Cell(35,16,'     CUIT ',1,0,'L');
  $pdf->Cell(30,16,' Ganancias ',1,0,'L');
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
	$estado_fac = $row_cabecerafac["estado"];
	$tc = "FC-";
	$signo = 1;
	
	if ($tcomp !=  51 && $tcomp !=  52) 
		continue;
	if ($estado_fac != "S")
		continue;
	$cliente      = $row_cabecerafac["cliente"];
	
	// Leo el cliente
  	$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  	$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO LA ENTIDAD");
  	$row_entidades = mysqli_fetch_assoc($enti);
  	
	$cuit         = $row_entidades["cuit"];
	$razsoc       = substr($row_entidades["razsoc"],0,22);
	
	// ANTES DEBO LEER DETRECIBO
	//echo "NRODOC =   ".$nrodoc."   ";
	$query_detrecibo = sprintf("SELECT * FROM detrecibo WHERE  tcomprel = %s AND serierel = %s AND ncomprel = %s", $tcomp, $serie, $ncomp);
	//echo "query_detrecibo =   ".$query_detrecibo."   ";
	$detrecibo = mysqli_query($amercado, $query_detrecibo) or die("ERROR LEYENDO DETALLE DE RECIBO");
	$row_detrecibo = mysqli_fetch_assoc($detrecibo);
	$tcomp_detrecibo = $row_detrecibo["tcomp"];
	$serie_detrecibo = $row_detrecibo["serie"];
	$ncomp_detrecibo = $row_detrecibo["ncomp"];
	// Leo cartvalores
	$query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE  tcomp = 42 AND ((tcomprel = %d AND serierel = %d AND ncomprel = %d) )", $tcomp_detrecibo, $serie_detrecibo, $ncomp_detrecibo);
  	$cartvalores       = mysqli_query($amercado, $query_cartvalores) or die("ERROR LEYENDO CARTVALORES");
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
  	$pdf->Cell(35,8,$tc.$nrodoc,0,0,'L');
  	$pdf->Cell(35,8,$cuit,0,0,'L');
	$fecha_cbte = substr($row_cabecerafac["fecreg"],8,2)."-".substr($row_cabecerafac["fecreg"],5,2)."-".substr($row_cabecerafac["fecreg"],0,4);
	$fcomp      = $fecha_cbte;
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
