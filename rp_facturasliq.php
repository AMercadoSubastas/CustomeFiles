<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');
error_reporting(E_ALL);
ini_set('display_errors','Yes');
//setlocale (LC_TIME,"spanish");

//Connect to your database
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];
$f_desde = $_POST['fecha_desde'];
$f_hasta = $_POST['fecha_hasta'];
$f_desde         = "'".substr($f_desde,6,4)."-".substr($f_desde,3,2)."-".substr($f_desde,0,2)."'";
$f_hasta         = "'".substr($f_hasta,6,4)."-".substr($f_hasta,3,2)."-".substr($f_hasta,0,2)."'";
//echo "fdesde = ".$f_desde." fhasta = ".$f_hasta."  ";
// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s ", $colname_remate);
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
if(isset($row_dir_remates2["direccion"])) {
	$dir_direccion2 = $row_dir_remates2["direccion"];
	$dir_codprov2   = $row_dir_remates2["codprov"];
	$dir_codloc2    = $row_dir_remates2["codloc"];
}
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
$query_cabf = sprintf("SELECT * FROM cabfac WHERE codrem = %s AND tcomp NOT IN (98,99) AND fecreg between $f_desde AND $f_hasta ORDER BY tcomp  , ncomp", $colname_remate);
$cabf = mysqli_query($amercado, $query_cabf) or die("ERROR LEYENDO CABFAC 86");	
$rows_cabf = mysqli_fetch_array($cabf);
$usuario = 1; //$rows_cabf["usuario"];

$query_usu = sprintf("SELECT * FROM usuarios WHERE codnum = %s ", $usuario);
$usu = mysqli_query($amercado, $query_usu) or die ("ERROR LEYENDO USUARIOS");
$rows_usu = mysqli_fetch_array($usu);
$nombre_usu = $rows_usu["nombre"];

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE codrem = %s AND tcomp NOT IN (98,99) ORDER BY tcomp  , ncomp", $colname_remate);
$cabfac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
$totalRows_cabfac = mysqli_num_rows($cabfac);
$renglones = 0;


$j = 0; // Me dara el total de renglones
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
//Titulo
$pdf->SetY(7);
$pdf->SetX(60);
$pdf->Cell(80,10,'FACTURAS POR REMATE / LIQUIDACION',0,0,'C');

$pdf->SetFont('Arial','B',8);
$pdf->SetY(6);
$pdf->SetX(133);
$pdf->Cell(80,10,'Av. Alicia Moreau de Justo 1080, Piso 4, Of. 198 - CABA',0,0,'L');
$pdf->SetY(10);
$pdf->SetX(133);
$pdf->Cell(80,10,'Tel.: (+54) 11 3984-7400 / www.adrianmercado.com.ar',0,0,'L');


$pdf->Line(2,18,208,18);

$pdf->Ln(35);

$pdf->SetFont('Arial','B',12);
$pdf->SetY(24);
$pdf->SetX(10);
$pdf->Cell(80,10,'REMATE Nro.: '.$colname_remate,0,0,'L');
$pdf->SetX(113);
$pdf->Cell(80,10,'Usuario Cobranzas: '.$nombre_usu,0,0,'L');


$pdf->SetFillColor(232,232,232);
//=============================================================================
//Bold Font for Field Name
$pdf->SetFont('Arial','B',10);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(3);
$pdf->Cell(32,10,'NRO.',1,0,'C',1);
$pdf->SetX(35);
$pdf->Cell(22,10,'FECHA',1,0,'C',1);
$pdf->SetX(57);
$pdf->Cell(28,10,'NETO 21%',1,0,'C',1);
$pdf->SetX(85);
$pdf->Cell(28,10,'NETO 10,5%',1,0,'C',1);
$pdf->SetX(113);
$pdf->Cell(80,10,'DETALLE',1,0,'C',1);
$pdf->SetX(193);
$pdf->Cell(16,10,'AF LIQ?',1,0,'C',1);



$pdf->Ln();

//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
// luego una nueva pagina y la cabecera
$j = 0;
$afecta = "";
$acum_21 = 0.00;
$acum_105 = 0.00;
while($row_cabfac = mysqli_fetch_array($cabfac)) {
	
	$tipo_cf = $row_cabfac["tcomp"];
	$serie_cf = $row_cabfac["serie"];
	$ncomp_cf = $row_cabfac["ncomp"];
	$nro_cf = $row_cabfac["nrodoc"];
	if ($tipo_cf == 119 || $tipo_cf == 120) {
		// LEO EL DETALLE DE LAS N CRED PARA VER SI DESCONTARON TASA ADM
		$query_df = "SELECT * FROM detfac WHERE tcomp = $tipo_cf AND ncomp = $ncomp_cf AND descrip LIKE '%TASA%'";
		//echo "QUERY =  ".$query_df."  _  ";
		$df = mysqli_query($amercado, $query_df) or die(mysqli_error($amercado));
		if($row_df = mysqli_fetch_array($df)) {
			$total_21 = $row_cabfac["totneto21"] ;//- $row_df["neto"];
            $total_105 = $row_cabfac["totneto105"];
            $fecha_cf = $row_cabfac["fecreg"];
            $en_liquid = $row_cabfac["en_liquid"];
		}
		else {
			$total_21 = $row_cabfac["totneto21"];
            $total_105 = $row_cabfac["totneto105"];
            $fecha_cf = $row_cabfac["fecreg"];
            $en_liquid = $row_cabfac["en_liquid"];
		}
	}
	else {
      //$total_cf = $row_cabfac["totbruto"];
      $total_21 = $row_cabfac["totneto21"];
      $total_105 = $row_cabfac["totneto105"];
      $fecha_cf = $row_cabfac["fecreg"];
      $en_liquid = $row_cabfac["en_liquid"];
	}
	if ($en_liquid==1) {
		$afecta = "SI";
	}
	else {
		$afecta = "NO";
	}
	if ($en_liquid==1) {
		if ($tipo_cf == 119 || $tipo_cf == 120 || $tipo_cf == 121) {
			$acum_21 -= $total_21;
			$acum_105 -= $total_105;
		}
        else
            if ($tipo_cf == 125 || $tipo_cf == 126 || $tipo_cf == 127) {
                //NO ACUMULO, EN REALIDAD SON CONCEPTOS
            }
            else {
                $acum_21 += $total_21;
                $acum_105 += $total_105;

            }
	
	}
	
	if ($tipo_cf == 119 || $tipo_cf == 120  || $tipo_cf == 122 || $tipo_cf == 123 || $tipo_cf == 124) {
		$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp= %d AND serie = %d AND ncomp = %d ORDER BY ncomp", $tipo_cf,$serie_cf,$ncomp_cf);
		$detfac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
		$row_detfac = mysqli_fetch_assoc($detfac);
	
		$texto_df = substr($row_detfac["descrip"],0,48);
	}
	else {
		if ($tipo_cf == 125 || $tipo_cf == 126) 
			$texto_df = "FACTURA POR CONCEPTOS";
		else
			if ($tipo_cf == 117)
				$texto_df = "FACTURA DE CREDITO POR LOTES";
			else
                if ($tipo_cf == 127)
				    $texto_df = "FACTURA DE CREDITO POR CONCEPTOS";
                else
                    if ($tipo_cf == 121)
				        $texto_df = "N CRED DE CREDITO ";
                    else
                        if ($tipo_cf == 94)
				            $texto_df = "N DEB DE CREDITO ";
                        else
                            if ($tipo_cf == 103)
				                $texto_df = "FACTURA DE EXPORTACION ";
                            else
                                if ($tipo_cf == 104)
				                    $texto_df = "FACTURA DE GASTOS ";
                                else
                                    $texto_df = "FACTURA POR LOTES";
	} 
	$pdf->SetFillColor(255,255,255);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(3);
	/*
	$pdf->Cell(32,10,'NRO.',1,0,'C',1);
$pdf->SetX(35);
$pdf->Cell(22,10,'FECHA',1,0,'C',1);
$pdf->SetX(57);
$pdf->Cell(28,10,'NETO 21%',1,0,'C',1);
$pdf->SetX(85);
$pdf->Cell(28,10,'NETO 10,5%',1,0,'C',1);
$pdf->SetX(113);
$pdf->Cell(80,10,'DETALLE',1,0,'C',1);
$pdf->SetX(193);
$pdf->Cell(16,10,'AF LIQ?',1,0,'C',1);

	*/
	$pdf->Cell(32,10,$nro_cf,1,0,'C',1);
	$pdf->SetX(35);
	$pdf->Cell(22,10,$fecha_cf,1,0,'R',1);
	$pdf->SetX(57);
	$pdf->Cell(28,10,number_format($total_21, 2, ',','.'),1,0,'R',1);
	$pdf->SetX(85);
	$pdf->Cell(28,10,number_format($total_105, 2, ',','.'),1,0,'R',1);
	$pdf->SetX(113);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(80,10,$texto_df,1,0,'C',1);
	$pdf->SetX(193);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(16,10,$afecta,1,0,'C',1);


	
	$pdf->SetFont('Arial','B',11);
	$Y_Table_Position += 8;
	if ($Y_Table_Position >= 262) {
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
		//Titulo
		$pdf->SetY(7);
		$pdf->SetX(60);
		$pdf->Cell(80,10,'FACTURAS POR REMATE / LIQUIDACION',0,0,'C');

		$pdf->SetFont('Arial','B',8);
		$pdf->SetY(6);
		$pdf->SetX(133);
		$pdf->Cell(80,10,'Av. Alicia Moreau de Justo 1080, Piso 4, Of. 198 - CABA',0,0,'L');
		$pdf->SetY(10);
		$pdf->SetX(133);
		$pdf->Cell(80,10,'Tel.: (+54) 11 3984-7400 / www.adrianmercado.com.ar',0,0,'L');

		$pdf->Line(2,18,208,18);

		$pdf->Ln(35);

		$pdf->SetFont('Arial','B',12);
		$pdf->SetY(24);
		$pdf->SetX(10);
		$pdf->Cell(80,10,'REMATE Nro.: '.$colname_remate,0,0,'L');
        $pdf->SetX(113);
        $pdf->Cell(80,10,'Usuario Cobranzas: '.$nombre_usu,0,0,'L');
		$pdf->SetFillColor(232,232,232);
		//=============================================================================
		//Bold Font for Field Name
		$pdf->SetFont('Arial','B',10);
		$pdf->SetY($Y_Fields_Name_position);
		$pdf->SetX(3);
		$pdf->Cell(32,10,'NRO.',1,0,'C',1);
		$pdf->SetX(35);
		$pdf->Cell(22,10,'FECHA',1,0,'C',1);
		$pdf->SetX(57);
		$pdf->Cell(28,10,'NETO 21%',1,0,'C',1);
		$pdf->SetX(85);
		$pdf->Cell(28,10,'NETO 10,5%',1,0,'C',1);
		$pdf->SetX(113);
		$pdf->Cell(80,10,'DETALLE',1,0,'C',1);
		$pdf->SetX(193);
		$pdf->Cell(16,10,'AF LIQ?',1,0,'C',1);

		$pdf->Ln();

		
	}
}


// VAN LOS TOTALES
$pdf->SetFont('Arial','B',10);
$pdf->SetY($Y_Table_Position + 1);
$pdf->SetX(3);
$pdf->Cell(54,10,'TOTALES',1,0,'C',1);
$pdf->SetX(57);
$pdf->Cell(28,10,number_format($acum_21, 2, ',','.'),1,0,'R',1);
$pdf->SetX(85);
$pdf->Cell(28,10,number_format($acum_105, 2, ',','.'),1,0,'R',1);

mysqli_close($amercado);
$pdf->Output();
?>