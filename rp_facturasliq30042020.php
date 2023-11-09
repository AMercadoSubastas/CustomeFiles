<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');

setlocale (LC_TIME,"spanish");
//$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","S�bado");
//$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

//Connect to your database
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];

// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$row_remates = mysqli_fetch_assoc($remates);
$direc = $row_remates["direccion"];
$fechareal = $row_remates["fecreal"];
$hora = $row_remates["horareal"];
$hora = substr($hora,0,5);
$sello = $row_remates["sello"];
$plazo_sap = $row_remates["plazoSAP"];
$obs =  $row_remates["observacion"];
$cod_loc = $row_remates["codloc"];
$cod_prov = $row_remates["codprov"];
$fecha         = substr($fechareal,8,2)."-".substr($fechareal,5,2)."-".substr($fechareal,0,4);

$query_provincia = sprintf("SELECT * FROM provincias WHERE codnum = %s", $cod_prov);
$provincia = mysqli_query($amercado, $query_provincia) or die(mysqli_error($amercado));
$row_provincia = mysqli_fetch_assoc($provincia);
$provin = $row_provincia["descripcion"];

$query_localidad = sprintf("SELECT * FROM localidades WHERE codnum = %s", $cod_loc);
$localidad = mysqli_query($amercado, $query_localidad) or die(mysqli_error($amercado));
$row_localidad = mysqli_fetch_assoc($localidad);
$localid = $row_localidad["descripcion"];
//LEO LA TABLA DIR_REMATES PARA VER SI TENGO DIRECCION DE EXPOSICION
$query_dir_remates = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 1);
$dir_remates = mysqli_query($amercado, $query_dir_remates) or die(mysqli_error($amercado));
$row_dir_remates = mysqli_fetch_assoc($dir_remates);
$dir_direccion = $row_dir_remates["direccion"];
$dir_codprov   = $row_dir_remates["codprov"];
$dir_codloc    = $row_dir_remates["codloc"];

// LEO LA SEGUNDA DIRECCION
$query_dir_remates2 = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 2);
$dir_remates2 = mysqli_query($amercado, $query_dir_remates2) or die(mysqli_error($amercado));
$row_dir_remates2 = mysqli_fetch_assoc($dir_remates2);
$dir_direccion2 = $row_dir_remates2["direccion"];
$dir_codprov2   = $row_dir_remates2["codprov"];
$dir_codloc2    = $row_dir_remates2["codloc"];

if (mysqli_num_rows($dir_remates) > 0 ) {
	$hay_exhib = 1;
	// LEO LA PROVINCIA Y LA LOCALIDAD DE LA DIRECCION DE EXHIBICION
	$query_provincia_ex = sprintf("SELECT * FROM provincias WHERE codnum = %s", $dir_codprov);
	$provincia_ex = mysqli_query($amercado, $query_provincia_ex) or die(mysqli_error($amercado));
	$row_provincia_ex = mysqli_fetch_assoc($provincia_ex);
	$provin_ex = $row_provincia_ex["descripcion"];

	$query_localidad_ex = sprintf("SELECT * FROM localidades WHERE codnum = %s", $dir_codloc);
	$localidad_ex = mysqli_query($amercado, $query_localidad_ex) or die(mysqli_error($amercado));
	$row_localidad_ex = mysqli_fetch_assoc($localidad_ex);
	$localid_ex = $row_localidad_ex["descripcion"];
}
else 
	$hay_exhib = 0;
	
if (mysqli_num_rows($dir_remates2) > 0 ) {
	$hay_exhib = 2;
	// LEO LA PROVINCIA Y LA LOCALIDAD DE LA DIRECCION DE EXHIBICION
	$query_provincia_ex2 = sprintf("SELECT * FROM provincias WHERE codnum = %s", $dir_codprov2);
	$provincia_ex2 = mysqli_query($amercado, $query_provincia_ex2) or die(mysqli_error($amercado));
	$row_provincia_ex2 = mysqli_fetch_assoc($provincia_ex2);
	$provin_ex2 = $row_provincia_ex2["descripcion"];

	$query_localidad_ex2 = sprintf("SELECT * FROM localidades WHERE codnum = %s", $dir_codloc2);
	$localidad_ex2 = mysqli_query($amercado, $query_localidad_ex2) or die(mysqli_error($amercado));
	$row_localidad_ex2 = mysqli_fetch_assoc($localidad_ex2);
	$localid_ex2 = $row_localidad_ex2["descripcion"];
}
	

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE codrem = %s ORDER BY tcomp  , ncomp", $colname_remate);
$cabfac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));
$totalRows_cabfac = mysqli_num_rows($cabfac);
$renglones = 0;


$j = 0; // Me dar� el total de renglones
$hojas_impresas = 0;
//Create a new PDF file
$pdf=new FPDF();
$pdf->SetAutoPageBreak(0, 5) ;

$pdf->AddPage();
$hojas_impresas = $hojas_impresas + 1;
$Y_Fields_Name_position = 36;
$Y_Table_Position = 46;


//=========================== CABECERA =============================================
$pdf->Image('images/logo_adrianC.jpg',1,8,65);
//Arial bold 14
$pdf->SetFont('Arial','B',8);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->SetY(7);
$pdf->SetX(60);
$pdf->Cell(80,10,'FACTURAS POR REMATE / LIQUIDACION',0,0,'C');

$pdf->SetFont('Arial','B',8);
$pdf->SetY(6);
$pdf->SetX(135);
$pdf->Cell(80,10,'Av. Alicia Moreau de Justo 740, Piso 4, Of. 19 - CABA',0,0,'L');
$pdf->SetY(10);
$pdf->SetX(135);
$pdf->Cell(80,10,'Tel.: (+54) 11 4343-9893 / www.adrianmercado.com.ar',0,0,'L');

$pdf->Line(2,18,208,18);

$pdf->Ln(35);

$pdf->SetFont('Arial','B',12);
$pdf->SetY(24);
$pdf->SetX(10);
$pdf->Cell(80,10,'REMATE Nro.: '.$colname_remate,0,0,'L');

$pdf->SetFillColor(232,232,232);
//=============================================================================
//Bold Font for Field Name
$pdf->SetFont('Arial','B',11);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(4);
$pdf->Cell(35,10,'NRO.',1,0,'C',1);
$pdf->SetX(39);
$pdf->Cell(30,10,'IMPORTE',1,0,'C',1);
$pdf->SetX(69);
$pdf->Cell(111,10,'DETALLE',1,0,'C',1);
$pdf->SetX(176);
$pdf->Cell(30,10,'AFECTA LIQ?',1,0,'C',1);



$pdf->Ln();

//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
// luego una nueva pagina y la cabecera
$j = 0;
$afecta = "";

while($row_cabfac = mysqli_fetch_array($cabfac)) {
	
	$tipo_cf = $row_cabfac["tcomp"];
	$serie_cf = $row_cabfac["serie"];
	$ncomp_cf = $row_cabfac["ncomp"];
	$nro_cf = $row_cabfac["nrodoc"];
	$total_cf = $row_cabfac["totbruto"];
	$fecha_cf = $row_cabfac["fecreg"];
	$en_liquid = $row_cabfac["en_liquid"];
	if ($en_liquid==1) {
		$afecta = "AFECTA";
	}
	else {
		$afecta = "NO AFECTA";
	}
	if ($tipo_cf == 57 || $tipo_cf == 58 || $tipo_cf == 59 || $tipo_cf == 60) {
		$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp= %d AND serie = %d AND ncomp = %d ORDER BY ncomp", $tipo_cf,$serie_cf,$ncomp_cf);
		$detfac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
		$row_detfac = mysqli_fetch_assoc($detfac);
	
		$texto_df = $row_detfac["descrip"];
	}
	else {
		if ($tipo_cf == 52 || $tipo_cf == 54) 
			$texto_df = "FACTURA POR CONCEPTOS";
		else
			$texto_df = "FACTURA POR LOTES";
	} 
	$pdf->SetFillColor(255,255,255);
	$pdf->SetFont('Arial','B',11);
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(4);
	$pdf->Cell(35,10,$nro_cf,1,0,'C',1);
	$pdf->SetX(39);
	$pdf->Cell(30,10,number_format($total_cf, 2, ',','.'),1,0,'R',1);
	$pdf->SetX(69);
	$pdf->Cell(111,10,$texto_df,1,0,'C',1);
	$pdf->SetX(176);
	$pdf->Cell(30,10,$afecta,1,0,'C',1);


	
	$pdf->SetFont('Arial','B',11);
	$Y_Table_Position += 8;
	if ($Y_Table_Position >= 246) {
		$pdf->AddPage();
		$hojas_impresas = $hojas_impresas + 1;
		$Y_Fields_Name_position = 36;
		$Y_Table_Position = 46;


		//=========================== CABECERA =============================================
		$pdf->Image('images/logo_adrianC.jpg',1,8,65);
		//Arial bold 14
		$pdf->SetFont('Arial','B',8);
		//Movernos a la derecha
		$pdf->Cell(55);
		//T�tulo
		$pdf->SetY(7);
		$pdf->SetX(60);
		$pdf->Cell(80,10,'FACTURAS POR REMATE / LIQUIDACION',0,0,'C');

		$pdf->SetFont('Arial','B',8);
		$pdf->SetY(6);
		$pdf->SetX(135);
		$pdf->Cell(80,10,'Av. Alicia Moreau de Justo 740, Piso 4, Of. 19 - CABA',0,0,'L');
		$pdf->SetY(10);
		$pdf->SetX(135);
		$pdf->Cell(80,10,'Tel.: (+54) 11 4343-9893 / www.adrianmercado.com.ar',0,0,'L');

		$pdf->Line(2,18,208,18);

		$pdf->Ln(35);

		$pdf->SetFont('Arial','B',12);
		$pdf->SetY(24);
		$pdf->SetX(10);
		$pdf->Cell(80,10,'REMATE Nro.: '.$colname_remate,0,0,'L');

		$pdf->SetFillColor(232,232,232);
		//=============================================================================
		//Bold Font for Field Name
		$pdf->SetFont('Arial','B',11);
		$pdf->SetY($Y_Fields_Name_position);
		$pdf->SetX(4);
		$pdf->Cell(35,10,'NRO.',1,0,'C',1);
		$pdf->SetX(35);
		$pdf->Cell(30,10,'IMPORTE',1,0,'C',1);
		$pdf->SetX(65);
		$pdf->Cell(111,10,'DETALLE',1,0,'C',1);
		$pdf->SetX(176);
		$pdf->Cell(30,10,'AFECTA LIQ?',1,0,'C',1);


		$pdf->Ln();

		
	}
}
	
mysqli_close($amercado);
$pdf->Output();
?>