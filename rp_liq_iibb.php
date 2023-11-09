<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES
define('FC_CLI_MAN_A','32');
define('FC_CLI_AUT_A','33');
define('FC_CLI_MAN_B','34');
define('FC_CLI_AUT_B','35');
define('NC_CLI_MAN_A','32');
define('NC_CLI_AUT_A','33');
define('NC_CLI_MAN_B','34');
define('NC_CLI_AUT_B','35');
define('ND_CLI_MAN_A','32');
define('ND_CLI_AUT_A','33');
define('ND_CLI_MAN_B','34');
define('ND_CLI_AUT_B','35');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior

$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];
$f_desde = $_POST['fecha_desde'];
$f_hasta = $_POST['fecha_hasta'];

$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
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

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN %s AND %s ORDER BY fecreg, nrodoc", $fecha_desde, $fecha_hasta);

$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));

// Inicio el pdf con los datos de cabecera
  $pdf=new FPDF('L','mm','A4');
  
  $pdf->AddPage();
  $pdf->SetMargins(0.5, 0.5 , 0.5);
  $pdf->SetFont('Arial','B',11);
  $pdf->SetY(5);
  $pdf->Cell(10);
  $pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
  $pdf->Cell(200);
  $pagina = $pdf->PageNo();
  $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
  $pdf->SetY(10);
  $pdf->Cell(230);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(70);
  $pdf->Cell(20,10,' Liquidaci�n de Ingresos Brutos desde  '.$f_desde.' hasta '.$f_hasta,0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(10);
  $pdf->Cell(25,14,' Jurisdicci�n ',1,0,'L');
  $pdf->Cell(45,14,' Provincia ',1,0,'L');
  $pdf->Cell(40,14,' Total Facturado ',1,0,'L');
  $pdf->SetY(33);
  $pdf->Cell(10);
  $pdf->Cell(25,8,'     AFIP ',0,0,'L');
    
  $valor_y = 45;
  
// Datos de los renglones
$i = 0;

$acum_tot_neto21  = 0;
$acum_tot_neto105 = 0;
for ($i = 0; $i < 30 ; $i++) {
	$acum_total[$i] = 0;
}
$j = 1;
$query_provincias = sprintf("SELECT * FROM provincias");
  		$provin = mysqli_query($amercado, $query_provincias) or die(mysqli_error($amercado));

		while($row_provin = mysqli_fetch_array($provin)) {
	  		$nom_provincia[$j] = $row_provin["descripcion"];
			$cod_provincia[$j] = $row_provin["codnum"];
			$j++;
		} 
		$k = 0;
		$l = 0;
//iniciamos el array
	for ($i=0;$i<=30;$i++) 
		$acum_total[$i] = 0.00;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	
	if (!isset($row_cabecerafac["cliente"]) || $row_cabecerafac["estado"]=="A")
		continue;
	$tcomp      = $row_cabecerafac["tcomp"];
	$l++;
	if ($tcomp !=  1 && $tcomp !=  5 && $tcomp !=  6 && $tcomp !=  7 && $tcomp != 18 && $tcomp != 19 &&
	    $tcomp != 21 && $tcomp != 22 && $tcomp != 23 && $tcomp != 24 && $tcomp != 25 && $tcomp != 26 &&
		$tcomp != 27 && $tcomp != 28 && $tcomp != 29 && $tcomp != 30 && $tcomp != 44 && $tcomp != 45 &&
		$tcomp != 46 && $tcomp != 47 && $tcomp != 48 && $tcomp != 49)
		continue;
	if ($tcomp ==  5 ||  $tcomp ==  7 || $tcomp == 25 || $tcomp == 26 || $tcomp == 46 || $tcomp == 47 ) {
		$tc = "NC-";
		$signo = -1;
	}
	elseif ($tcomp == 21 ||  $tcomp == 22 || $tcomp == 29 || $tcomp == 30  || $tcomp == 48 || $tcomp ==49 ){
		$tc = "ND-";
		$signo = 1;
	}
	else {
		$tc = "FC-";
		$signo = 1;
	}
	$k++;
		
		if (isset($row_cabecerafac["codrem"])) {
			// Si tengo nro de Remate lo saco por la direccion del mismo
			$codrem = $row_cabecerafac["codrem"];
			$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $codrem);
			$remate = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
			$row_remate = mysqli_fetch_assoc($remate);
			$codprov = $row_remate["codprov"];
		}
		else {
			// Si no, Leo el cliente
  			$cliente      = $row_cabecerafac["cliente"];
			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$codprov      = $row_entidades["codprov"];
		}
		$tot_neto21   = $row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"];
		$tot_neto105  = $row_cabecerafac["totneto105"];
		$total        = $row_cabecerafac["totbruto"];
		
		// Leo la provincia
		$query_provincias = sprintf("SELECT * FROM provincias WHERE  codnum = %d and codpais = %d", $codprov, 1);
  		$provin = mysqli_query($amercado, $query_provincias) or die(mysqli_error($amercado));
  		$row_provin = mysqli_fetch_assoc($provin);
	
  		$nom_provincia[$codprov] = $row_provin["descripcion"];
		$cod_provincia[$codprov] = $row_provin["codnum"];
		if (!isset($codprov))
			continue;
		// Acumulo subtotales
		if ($tcomp ==  5 ||  $tcomp ==  7 || $tcomp == 25 || $tcomp == 26 || $tcomp == 46 || $tcomp == 47 ) {
			// resto Notas de Cr�dito
			
			$acum_total[$codprov]       = $acum_total[$codprov] - $tot_neto21   - $tot_neto105;
		}
		else {
			// Sumo Facturas y Notas de D�bito
			
			$acum_total[$codprov]       = $acum_total[$codprov] + $tot_neto21 + $tot_neto105;
		}
		
}
$total_prov = 24;
$totgral = 0;
for ($i = 1 ; $i < $total_prov ; $i++ ) {
	$totgral      += $acum_total[$i];
	$total        = number_format($acum_total[$i], 2, ',','.');
	
	if ($total==0)
		continue;	
		
	$pdf->SetY($valor_y);
  	$pdf->Cell(10);
  		
	$pdf->Cell(25,6,$cod_provincia[$i],0,0,'L');
	$pdf->Cell(45,6,$nom_provincia[$i],0,0,'L');
	$pdf->Cell(40,6,$total,0,0,'R');
	$total = 0;
	$valor_y = $valor_y + 6;
}
$valor_y = $valor_y + 6;
$pdf->SetY($valor_y);
$pdf->Cell(10);
$pdf->Cell(65,6,"TOTAL GENERAL; ",0,0,'L');
$total_general        = number_format($totgral, 2, ',','.');
$pdf->Cell(45,6,$total_general,0,0,'R');
		
mysqli_close($amercado);
$pdf->Output();
?>  
