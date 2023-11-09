<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES
define('FC_CLI_CON_A','52');
define('FC_CLI_LOT_A','51');
define('FC_CLI_CON_B','54');
define('FC_CLI_LOT_B','53');
define('FC_CLI_A6','55');
define('FC_CLI_B6','56');
define('NC_CLI_A4','57');
define('NC_CLI_B4','58');
define('NC_CLI_A6','61');
define('NC_CLI_B6','62');
define('ND_CLI_A4','59');
define('ND_CLI_B4','60');
define('ND_CLI_A6','63');
define('ND_CLI_B6','64');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$fecha_desde =substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2);
$fecha_hasta = substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2);

//echo "FECHA_DESDE = ".$fecha_desde."    ";
$fechahoy = date("d-m-Y");
// Leo los renglones
//echo "FECHA_HOY = ".$fechahoy."    ";
// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die(mysqli_error($amercado));
$row_Recordset2 = mysqli_fetch_assoc($impuestos);
$totalRows_Recordset2 = mysqli_num_rows($impuestos);
$impuestos->data_seek(1);
$row = $impuestos->fetch_array();
// Calcular los porcentajes de impuestos
$porc_iva105 = $row[1]/ 100 ."<br>";
$impuestos->data_seek(0);
$row = $impuestos->fetch_array();
$porc_iva21 = $row[1] / 100;

// Leo la cabecera

//$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (51,52) ORDER BY ncomp ASC");
$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (51,52) ORDER BY ncomp ASC");
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
//echo "FECHA_DESDE = ".$fecha_desde."  FECHA_HASTA = ".$fecha_hasta."   ";

// Inicio el pdf con los datos de cabecera
  $pdf=new FPDF('P','mm','A4');
  
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' FACTURAS A0004-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "FC-A0004-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (53,54) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' FACTURAS B0004-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "FC-B0004-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}

//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (55) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' FACTURAS A0006-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "FC-A0006-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}

//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (56) ORDER BY ncomp ASC");
//echo "QUERY :  ".$query_cabfac."   ";
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' FACTURAS B0006-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "FC-B0006-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (57) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' N CRED A0004-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "NC-A0004-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (58) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' N CRED B0004-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "NC-B0004-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (61) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' N CRED A0006-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "NC-A0006-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (62) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' N CRED B0006-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "NC-B0006-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (59) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' N DEB A0004-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "ND-A0004-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (60) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' N DEB B0004-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "ND-B0004-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (63) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' N DEB A0006-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "ND-A0006-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND	tcomp IN (64) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' N DEB B0006-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "ND-B0006-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (89,92) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' FC DE CRED A00005-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "FCCRED-A00005-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (104) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' FC  A00005-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "FC-A00005-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}
//=========================================================================
	
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN $fecha_desde AND $fecha_hasta AND tcomp IN (103) ORDER BY ncomp ASC");
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
	
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
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(80);
  $pdf->Cell(20,10,' Comprobantes faltantes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  
  $pdf->Cell(38,8,' FCEXP  A00008-',1,0,'L');

  
  $valor_y = 42;

// Datos de los renglones
$i = 0;
$j = 0;
$serie_doc  = "FCEXP-A00008-";
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	
	if ($i == 0) {
		//$nroorig_ant = $row_cabecerafac["nrodoc"];		
		//$tcomp_ant = $row_cabecerafac["tcomp"];		
		$ncomp_ant = $row_cabecerafac["ncomp"];	
		$i++;
		continue;
	}
	if ($i>0){
		
		$ncomp_ant = $ncomp_ant + 1;
		//echo "I = ".$i." NCOMP = ".$ncomp."NCOMP_ant + 1 = ".$ncomp_ant."////"; 
		while ($ncomp > $ncomp_ant) {
				//$nroorig      = $row_cabecerafac["nrodoc"];		
				// Imprimo los renglones faltantes
				$pdf->SetY($valor_y);
  				$pdf->Cell(5);
  				
				$pdf->Cell(26,6,$serie_doc.$ncomp_ant,0,0,'L');
				$ncomp_ant = $ncomp_ant + 1;
				$valor_y = $valor_y + 6;
				$i++;
				$j++;
				//continue;
		}
		//$ncomp_ant = $ncomp_ant + 1;
		$i++;
	}
	

		
		
}

mysqli_close($amercado);
$pdf->Output();
?>  
