<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');

setlocale (LC_TIME,"spanish");

//Connect to your database
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];
$fecha_tope = "2020-08-13";

$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$row_remates = mysqli_fetch_assoc($remates);
$direc = $row_remates["direccion"];
$fecha = $row_remates["fecreal"];
$fechareal = $row_remates["fecreal"];
$sello = $row_remates["sello"];
$plazo_sap = $row_remates["plazoSAP"];
$cod_loc = $row_remates["codloc"];
$cod_prov = $row_remates["codprov"];
$obs =  $row_remates["observacion"];
$hora = $row_remates["horareal"];
$hora = substr($hora,0,5);
$fecha= substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);

$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
//$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);
$renglones = 0;
while($row_lotes = mysqli_fetch_array($lotes)) 
{
 	// desde aca 
	$code    = "";
	$texto   = "";
	$texto2  = "";
	$texto3  = "";
	$texto4  = "";
	$texto5  = "";
	$texto6  = "";
	$texto7  = "";
	$texto8  = "";
	$texto9	 = "";
	$texto10 = "";
	
	$code = $row_lotes["codintlote"];
	$texto = $row_lotes["descor"];
	if ($fechareal  < $fecha_tope )
		$tamano = 45; // tama�o m�ximo renglon
	else
		$tamano = 55; // tama�o m�ximo renglon
    $contador = 0; 
    $contador1 = 0; 
    $contador2 = 0; 
    $contador3 = 0; 
    $contador4 = 0; 
    $contador5 = 0; 
    $contador6 = 0; 
    $contador7 = 0; 
	$contador8 = 0;
	$contador9 = 0;
	$contador10 = 0;
    //$texto = strtoupper($texto);
    $texto_orig= $texto ;
    $largo_orig = strlen($texto_orig);

	// Cortamos la cadena por los espacios 
    $arrayTexto =explode(' ',$texto); 
	$texto = ''; 

	// Reconstruimos el primer renglon
	while(isset($arrayTexto[$contador]) && $tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
    	$texto .= ' '.$arrayTexto[$contador]; 
    	$contador++; 
	} 

	$largo_primer_renglon = strlen($texto)."<br>"; 
 
	// Aca empieza un renglon
	$texto1 = substr($texto_orig,strlen($texto)) ;
	$arrayTexto1 =explode(' ',$texto1); 
	$texto1 = ''; 
	// Reconstruimos el segundo renglon
	while(isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1])&& strlen($arrayTexto1[$contador1])!=0){ 
    	$texto1 .= ' '.$arrayTexto1[$contador1]; 
    	$contador1++; 
	}
	$largo_segundo_renglon = strlen($texto1);
	// Aca termina

	$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
	$arrayTexto2 =explode(' ',$texto2); 
	$texto2 = ''; 
	// Reconstruimos el segundo renglon
	while(isset($arrayTexto2[$contador2]) && $tamano >= strlen($texto2) + strlen($arrayTexto2[$contador2])&& strlen($arrayTexto2[$contador2])!=0){ 
    	$texto2 .= ' '.$arrayTexto2[$contador2]; 
    	$contador2++; 
	}
	$largo_tercer_renglon = strlen($texto2);
	$largo_tercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);
	$texto3 = substr($texto_orig,$largo_tercer) ;
	$arrayTexto3 =explode(' ',$texto3); 
	$texto3 = ''; 
	// Reconstruimos el cuarto renglon
	while(isset($arrayTexto3[$contador3]) && $tamano >= strlen($texto3) + strlen($arrayTexto3[$contador3])&& strlen($arrayTexto3[$contador3])!=0){ 
    	$texto3 .= ' '.$arrayTexto3[$contador3]; 
    	$contador3++; 
	}
	$largo_cuarto_renglon = strlen($texto3);
	$largo_cuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon);
	$texto4 = substr($texto_orig,$largo_cuarto) ;
	$arrayTexto4 =explode(' ',$texto4); 
	$texto4 = ''; 
	while(isset($arrayTexto4[$contador4]) && $tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& strlen($arrayTexto4[$contador4])!=0){ 
    	$texto4 .= ' '.$arrayTexto4[$contador4]; 
    	$contador4++; 
	}
	$largo_quinto_renglon = strlen($texto4);
	$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+	$largo_quinto_renglon);
	$texto5 = substr($texto_orig,$largo_quinto) ;
	$arrayTexto5 =explode(' ',$texto5); 
	$texto5 = ''; 
	while(isset($arrayTexto5[$contador5]) && $tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5])&& strlen($arrayTexto5[$contador5])!=0){ 
    	$texto5 .= ' '.$arrayTexto5[$contador5]; 
    	$contador5++; 
	}
	$largo_sexto_renglon = strlen($texto5);
	$largo_sexto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);

	// ============================================================================================
	$texto6 = substr($texto_orig,$largo_sexto) ;
	$arrayTexto6 =explode(' ',$texto6); 
	$texto6 = ''; 
	while(isset($arrayTexto6[$contador6]) && $tamano >= strlen($texto6) + strlen($arrayTexto6[$contador6])&& strlen($arrayTexto6[$contador6])!=0){ 
    	$texto6 .= ' '.$arrayTexto6[$contador6]; 
    	$contador6++; 
	}
	$largo_septimo_renglon = strlen($texto6);
	$largo_septimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon);

	// =============================================================================================
	$texto7 = substr($texto_orig,$largo_septimo) ;
	$arrayTexto7 =explode(' ',$texto7); 
	$texto7 = ''; 
	while(isset($arrayTexto7[$contador7]) && $tamano >= strlen($texto7) + strlen($arrayTexto7[$contador7])&& strlen($arrayTexto7[$contador7])!=0){ 
    	$texto7 .= ' '.$arrayTexto7[$contador7]; 
    	$contador7++; 
	}
	$largo_octavo_renglon = strlen($texto7);
	$largo_octavo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon);

	// ============================================================================================
	$texto8 = substr($texto_orig,$largo_octavo) ;
	$arrayTexto8 =explode(' ',$texto8); 
	$texto8 = ''; 
	while(isset($arrayTexto8[$contador8]) && $tamano >= strlen($texto8) + strlen($arrayTexto8[$contador8])&& strlen($arrayTexto8[$contador8])!=0){ 
    	$texto8 .= ' '.$arrayTexto8[$contador8]; 
    	$contador8++; 
	}
	$largo_noveno_renglon = strlen($texto8);
	$largo_noveno = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon);

	// ===========================================================================================

	$texto9 = substr($texto_orig,$largo_noveno) ;
	$arrayTexto9 =explode(' ',$texto9); 
	$texto9 = ''; 
	while(isset($arrayTexto9[$contador9]) && $tamano >= strlen($texto9) + strlen($arrayTexto9[$contador9])&& strlen($arrayTexto9[$contador9])!=0){ 
    	$texto9 .= ' '.$arrayTexto9[$contador9]; 
    	$contador9++; 
	}
	$largo_decimo_renglon = strlen($texto9);
	$largo_decimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon);

	// ==============================================================================================

	$texto10 = substr($texto_orig,$largo_decimo) ;
	$arrayTexto10 =explode(' ',$texto10); 
	$texto10 = ''; 
	while(isset($arrayTexto10[$contador10]) && $tamano >= strlen($texto10) + strlen($arrayTexto10[$contador10])&& strlen($arrayTexto10[$contador10])!=0){ 
    	$texto10 .= ' '.$arrayTexto10[$contador10]; 
    	$contador10++; 
	}
	$largo_undecimo_renglon = strlen($texto10);
	$largo_und11 = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon);

	// ============================================================================================


	//$renglones = $renglones + 1;
	if (strcmp($texto, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto1, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto2, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto3, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto4, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto5, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto6, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto7, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto8, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto9, '')!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto10, '')!=0) {
		$renglones = $renglones + 1;
	}
}


$hojas = floor($renglones / 35);
if ($renglones % 35 != 0)
	$hojas++;
//echo "  RENGLONES = ".$renglones;
//echo "  HOJAS =".floor($renglones / 30);
//echo "   HOJAS = ".$hojas;
if ($hojas == 0)
	$hojas = 1;
if ($colname_remate == 2445 || $colname_remate == 2457 || $colname_remate == 2446)
	$hojas = $hojas - 1;

if ($colname_remate == 2419 || $colname_remate == 2478 || $colname_remate == 2426)
	$hojas = $hojas - 1;
if ($colname_remate == 2348)
	$hojas = $hojas + 1;
	//echo $renglones;
	//echo $hojas;
$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
//$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);

// Leo el remate


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

$j = 0; // Me dar� el total de renglones
$hojas_impresas = 0;
//Create a new PDF file
$pdf=new FPDF();
$pdf->SetAutoPageBreak(0, 5) ;

$pdf->AddPage();
$hojas_impresas = $hojas_impresas + 1;

if ($colname_remate == 2219)
	$hay_exhib = 0;

if ($hay_exhib == 0 && $sello=='1') {
	//Fields Name position
	$Y_Fields_Name_position = 47;

	//Table position, under Fields Name
	$Y_Table_Position = 57;
}
if ($hay_exhib == 0 && $sello!='1') {
	//Fields Name position
	$Y_Fields_Name_position = 42;

	//Table position, under Fields Name
	$Y_Table_Position = 52;
}

if ($hay_exhib == 1 && $sello=='1') {
	//Fields Name position
	$Y_Fields_Name_position = 52;

	//Table position, under Fields Name
	$Y_Table_Position = 62;
}
if ($hay_exhib == 1 && $sello !='1') {
	//Fields Name position
	$Y_Fields_Name_position = 47;

	//Table position, under Fields Name
	$Y_Table_Position = 57;
}
if ($hay_exhib == 2 && $sello=='1') {
	//Fields Name position
	$Y_Fields_Name_position = 57;

	//Table position, under Fields Name
	$Y_Table_Position = 67;
}

if ($hay_exhib == 2 && $sello!='1') {
	//Fields Name position
	$Y_Fields_Name_position = 52;

	//Table position, under Fields Name
	$Y_Table_Position = 62;
}

//=========================== CABECERA =============================================
$pdf->Image('images/logo_adrianC.jpg',1,8,65);
//Arial bold 14
$pdf->SetFont('Arial','B',12);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->SetY(7);
$pdf->SetX(60);
$pdf->Cell(80,10,'CAT�LOGO PARA VENDEDOR',0,0,'C');

$pdf->SetFont('Arial','B',8);
$pdf->SetY(6);
$pdf->SetX(132);
$pdf->Cell(80,10,'                    Olga Cossettini 731, Piso 3 - CABA',0,0,'L');
$pdf->SetY(10);
$pdf->SetX(132);
$pdf->Cell(80,10,'Tel.:(+54) 11 3984-7400 / www.grupoadrianmercado.com',0,0,'L');

$pdf->Line(2,18,208,18);
//========================== HASTA LA LINEA ==========================================

$pdf->SetFont('Arial','B',10);
$pdf->SetY(17);
$pdf->SetX(5);
$pdf->Cell(19,10, 'Por c/o de: ',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,10, substr($obs,0,90),0,0,'L');
//=============================================================================
if ( strcmp(substr($hora,3,2) , "00") == 0)
	$hora = substr($hora,0,2);
$pdf->SetY(22);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,10,'Fecha y Hora: ',0,0,'L');
$pdf->SetFont('Arial','',10);
$dia = ucwords(strftime("%A", strtotime($fecha)));
$mes = ucwords(strftime("%B", strtotime($fecha)));
$dianum = strftime("%d", strtotime($fecha));
if ($dianum[0]==0)
	$dianum = $dianum[1];

$pdf->Cell(20,10, '    '.$dia.' '.$dianum.' de '.$mes.' de '.strftime("%Y", strtotime($fecha)).' - '.$hora.' hs.',0,0,'L');//$pdf->Cell(20,10, '      '.$fecha_hoy.' - '.$hora.' hs.',0,0,'L');
//=============================================================================
if ($hay_exhib == 0) {
	$pdf->SetY(27);
	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',10);
	if ($colname_remate == 2272 || $colname_remate == 2271)
		$pdf->Cell(10,10,'Subasta: ',0,0,'L');
	else
		$pdf->Cell(20,10,'Subasta / Exhibici�n: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(16);
	$pdf->Cell(120,10,$direc.", ".$localid.", ".$provin,0,0,'L');
	$pdf->SetX(90);
}
//=============================================================================
if ($hay_exhib == 1) {
	$pdf->SetY(27);
	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,10,'Subasta: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(6);
	$pdf->Cell(120,10,$direc.", ".$localid.", ".$provin,0,0,'L');
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(32);
	$pdf->SetX(5);
	$pdf->Cell(15,10,'Exhibici�n: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(5);
	$pdf->Cell(120,10,$dir_direccion.", ".$localid_ex.", ".$provin_ex,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(37);
}
if ($hay_exhib == 2) {
	$pdf->SetY(27);
	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,10,'Subasta: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(6);
	$pdf->Cell(120,10,$direc.", ".$localid.", ".$provin,0,0,'L');
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(32);
	$pdf->SetX(5);
	$pdf->Cell(20,10,'Exhibici�n 1/2: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(6);
	$pdf->Cell(120,10,$dir_direccion.", ".$localid_ex.", ".$provin_ex,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(37);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(37);
	$pdf->SetX(5);
	$pdf->Cell(20,10,'Exhibici�n 2/2: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(6);
	$pdf->Cell(120,10,$dir_direccion2.", ".$localid_ex2.", ".$provin_ex2,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(42);
}


if ($sello=='1') {
			$pdf->SetX(10);
			if ($hay_exhib == 0)
				$pdf->SetY(37);
			if ($hay_exhib == 1)
				$pdf->SetY(42);
			if ($hay_exhib == 2)
				$pdf->SetY(47);
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(25);
			$pdf->Cell(140,7,'Venta sujeta a aprobaci�n por la empresa vendedora '.$plazo_sap,1,0,'C');

}

$pdf->Ln(35);

$pdf->SetFillColor(232,232,232);
//Bold Font for Field Name
$pdf->SetFont('Arial','B',11);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(1);
$pdf->Cell(14,10,'LOTE',1,0,'C',1);
$pdf->SetX(15);
$pdf->Cell(115,10,'DESCRIPCION',1,0,'C',1);
$pdf->SetX(130);
$pdf->Cell(27,10,'PRE BASE',1,0,'C',1);
$pdf->SetX(157);
$pdf->Cell(38,10,'PRE OBT',1,0,'C',1);
$pdf->SetX(195);
$pdf->Cell(10,10,'OBS',1,0,'C',1);
$pdf->Ln();
	
//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
// luego una nueva pagina y la cabecera
$j = 0;
while($row_lotes = mysqli_fetch_array($lotes)) 
{
	$texto = "";
	$texto2 = "";
	$texto3 = "";
	$texto4 = "";
	$texto5 = "";
	$texto6 = "";	
	$texto7 = "";	
	$texto8 = "";	
	$texto9 = "";	
	$texto10 = "";	
	
	$code = $row_lotes["codintlote"];
	$texto = $row_lotes["descor"];
	if ($fechareal  < $fecha_tope )
	     $tamano = 45; // tama�o m�ximo renglon
	else
		$tamano = 55; // tama�o m�ximo renglon
         $contador = 0; 
         $contador1 = 0; 
         $contador2 = 0; 
         $contador3 = 0; 
         $contador4 = 0; 
         $contador5 = 0; 
         $contador6 = 0; 
         $contador7 = 0; 
	 $contador8 = 0; 
         $contador9 = 0; 
         $contador10 = 0; 

        //$texto = strtoupper($texto);
        $texto_orig= $texto ;
        $largo_orig = strlen($texto_orig);

// Cortamos la cadena por los espacios 

      $arrayTexto =explode(' ',$texto); 
	  $texto = ''; 

// Reconstruimos el primer renglon
while(isset($arrayTexto[$contador]) && $tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
    $texto .= ' '.$arrayTexto[$contador]; 
    $contador++; 
} 

$largo_primer_renglon = strlen($texto) ;
// =====================================================================================
$texto1 = substr($texto_orig,strlen($texto)) ;

$arrayTexto1 =explode(' ',$texto1); 
$texto1 = ''; 
// Reconstruimos el segundo renglon
while(isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1])&& strlen($arrayTexto1[$contador1])!=0){ 
    $texto1 .= ' '.$arrayTexto1[$contador1]; 
    $contador1++; 
}
$largo_segundo_renglon = strlen($texto1);
// =====================================================================================
$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
$arrayTexto2 =explode(' ',$texto2); 
$texto2 = ''; 
// Reconstruimos el segundo renglon
while(isset($arrayTexto2[$contador2]) && $tamano >= strlen($texto2) + strlen($arrayTexto2[$contador2])&& strlen($arrayTexto2[$contador2])!=0){ 
    $texto2 .= ' '.$arrayTexto2[$contador2]; 
    $contador2++; 
}
$largo_tercer_renglon = strlen($texto2);
$largo_tercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);
// =====================================================================================
$texto3 = substr($texto_orig,$largo_tercer) ;
$arrayTexto3 =explode(' ',$texto3); 
$texto3 = ''; 
// Reconstruimos el cuarto renglon
while(isset($arrayTexto3[$contador3]) && $tamano >= strlen($texto3) + strlen($arrayTexto3[$contador3])&& strlen($arrayTexto3[$contador3])!=0){ 
    $texto3 .= ' '.$arrayTexto3[$contador3]; 
    $contador3++; 
}
$largo_cuarto_renglon = strlen($texto3);
$largo_cuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon);
// =====================================================================================
$texto4 = substr($texto_orig,$largo_cuarto) ;
$arrayTexto4 =explode(' ',$texto4); 
$texto4 = ''; 
while(isset($arrayTexto4[$contador4]) && $tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& strlen($arrayTexto4[$contador4])!=0){ 
    $texto4 .= ' '.$arrayTexto4[$contador4]; 
    $contador4++; 
}
$largo_quinto_renglon = strlen($texto4);
$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);
// =====================================================================================
$texto5 = substr($texto_orig,$largo_quinto) ;
$arrayTexto5 =explode(' ',$texto5); 
$texto5 = ''; 
while(isset($arrayTexto5[$contador5]) && $tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5]) && strlen($arrayTexto5[$contador5])!=0){ 
    $texto5 .= ' '.$arrayTexto5[$contador5]; 
    $contador5++; 
}
$largo_sexto_renglon = strlen($texto5);
$largo_sexto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);

$texto6 = substr($texto_orig,$largo_sexto) ;
$arrayTexto6 =explode(' ',$texto6); 
$texto6 = ''; 

// Reconstruimos el septimo renglon ===============================================================================
while(isset($arrayTexto6[$contador6]) && $tamano >= strlen($texto6) + strlen($arrayTexto6[$contador6]) && strlen($arrayTexto6[$contador6])!=0){ 
    $texto6 .= ' '.$arrayTexto6[$contador6]; 
    $contador6++; 
}
$largo_septimo_renglon = strlen($texto6);
$largo_septimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon);

$texto7 = substr($texto_orig,$largo_septimo) ;
$arrayTexto7 =explode(' ',$texto7); 
$texto7 = ''; 

// Reconstruimos el octavo renglon ==================================================================================
while(isset($arrayTexto7[$contador7]) && $tamano >= strlen($texto7) + strlen($arrayTexto7[$contador7]) && strlen($arrayTexto7[$contador7])!=0){ 
    $texto7 .= ' '.$arrayTexto7[$contador7]; 
    $contador7++; 
}
$largo_octavo_renglon = strlen($texto7);
$largo_octavo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon);

$texto8 = substr($texto_orig,$largo_octavo) ;
$arrayTexto8 =explode(' ',$texto8); 
$texto8 = ''; 

// Reconstruimos el noveno renglon ====================================================================================
while(isset($arrayTexto8[$contador8]) && $tamano >= strlen($texto8) + strlen($arrayTexto8[$contador8]) && strlen($arrayTexto8[$contador8])!=0){ 
    $texto8 .= ' '.$arrayTexto8[$contador8]; 
    $contador8++; 
}
$largo_noveno_renglon = strlen($texto8);
$largo_noveno = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon);

$texto9 = substr($texto_orig,$largo_noveno) ;
$arrayTexto9 =explode(' ',$texto9); 
$texto9 = ''; 

// Reconstruimos el decimo renglon =====================================================================================
while(isset($arrayTexto9[$contador9]) && $tamano >= strlen($texto9) + strlen($arrayTexto9[$contador9]) && strlen($arrayTexto9[$contador9])!=0){ 
    $texto9 .= ' '.$arrayTexto9[$contador9]; 
    $contador9++; 
}
$largo_decimo_renglon = strlen($texto9);
$largo_decimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon);


	$pdf->SetFont('Arial','B',11);
	$pdf->SetY($Y_Table_Position);
	
	if (strcmp($texto9,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(6);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(5);
			else
				$pdf->SetX(4);
		//$pdf->SetX(1);
		$pdf->Cell(10,6,$code,0,'C');

		$pdf->SetX(1);
		$pdf->Cell(10,60,'',1);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto,0,'L');
		
		$pdf->SetX(19);
		$pdf->Cell(140,60,'',1);
		
		$pdf->SetX(159);
		$pdf->Cell(44,60,'',1);
		$pdf->SetY($Y_Table_Position+6);

		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto2,0,'L');

		$pdf->SetY($Y_Table_Position+18);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto3,0,'L');
	
	   	$pdf->SetY($Y_Table_Position+24);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto4,0,'L');
	
		$pdf->SetY($Y_Table_Position+30);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto5,0,'L');

		$pdf->SetY($Y_Table_Position+36);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto6,0,'L');
		
		$pdf->SetY($Y_Table_Position+42);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto7,0,'L');

		$pdf->SetY($Y_Table_Position+48);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto8,0,'L');

		$pdf->SetY($Y_Table_Position+54);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto9,0,'L');




		$Y_Table_Position = $Y_Table_Position + 54;

	}
	elseif (strcmp($texto8,"")!=0) {


		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(6);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(5);
			else
				$pdf->SetX(4);
		//$pdf->SetX(1);
		$pdf->Cell(10,6,$code,0,'C');

		$pdf->SetX(1);
		$pdf->Cell(10,54,'',1);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto,0,'L');
		
		$pdf->SetX(19);
		$pdf->Cell(140,54,'',1);
		
		$pdf->SetX(159);
		$pdf->Cell(44,54,'',1);
		$pdf->SetY($Y_Table_Position+6);

		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto2,0,'L');

		$pdf->SetY($Y_Table_Position+18);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto3,0,'L');
	
	   	$pdf->SetY($Y_Table_Position+24);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto4,0,'L');
	
		$pdf->SetY($Y_Table_Position+30);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto5,0,'L');

		$pdf->SetY($Y_Table_Position+36);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto6,0,'L');
		
		$pdf->SetY($Y_Table_Position+42);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto7,0,'L');

		$pdf->SetY($Y_Table_Position+48);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto8,0,'L');


		$Y_Table_Position = $Y_Table_Position + 48;
	}

	elseif (strcmp($texto7,"")!=0) {


		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(6);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(5);
			else
				$pdf->SetX(4);
		//$pdf->SetX(1);
		$pdf->Cell(10,6,$code,0,'C');

		$pdf->SetX(1);
		$pdf->Cell(10,48,'',1);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto,0,'L');
		
		$pdf->SetX(19);
		$pdf->Cell(140,48,'',1);
		
		$pdf->SetX(159);
		$pdf->Cell(44,48,'',1);
		$pdf->SetY($Y_Table_Position+6);

		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto2,0,'L');

		$pdf->SetY($Y_Table_Position+18);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto3,0,'L');
	
	   	$pdf->SetY($Y_Table_Position+24);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto4,0,'L');
	
		$pdf->SetY($Y_Table_Position+30);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto5,0,'L');

		$pdf->SetY($Y_Table_Position+36);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto6,0,'L');
		
		$pdf->SetY($Y_Table_Position+42);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto7,0,'L');

		$Y_Table_Position = $Y_Table_Position + 42;
	}

	elseif (strcmp($texto6,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(6);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(5);
			else
				$pdf->SetX(4);
		//$pdf->SetX(1);
		$pdf->Cell(10,6,$code,0,'C');

		$pdf->SetX(1);
		$pdf->Cell(10,42,'',1);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto,0,'L');
		
		$pdf->SetX(19);
		$pdf->Cell(140,42,'',1);
		
		$pdf->SetX(159);
		$pdf->Cell(44,42,'',1);
		$pdf->SetY($Y_Table_Position+6);

		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto2,0,'L');

		$pdf->SetY($Y_Table_Position+18);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto3,0,'L');
	
	   	$pdf->SetY($Y_Table_Position+24);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto4,0,'L');
	
		$pdf->SetY($Y_Table_Position+30);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto5,0,'L');

		$pdf->SetY($Y_Table_Position+36);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto6,0,'L');
		
		$Y_Table_Position = $Y_Table_Position + 36;
	}
	elseif (strcmp($texto5, "")!=0) {
		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(6);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(5);
			else
				$pdf->SetX(4);
		//$pdf->SetX(1);
		$pdf->Cell(10,6,$code,0,'C');

		$pdf->SetX(1);
		$pdf->Cell(10,36,'',1);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto,0,'L');
		
		$pdf->SetX(19);
		$pdf->Cell(140,36,'',1);
		
		$pdf->SetX(159);
		$pdf->Cell(44,36,'',1);
		$pdf->SetY($Y_Table_Position+6);

		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto2,0,'L');

		$pdf->SetY($Y_Table_Position+18);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto3,0,'L');
	
	   	$pdf->SetY($Y_Table_Position+24);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto4,0,'L');
	
		$pdf->SetY($Y_Table_Position+30);
		$pdf->SetX(19);
		$pdf->Cell(140,6,$texto5,0,'L');

		$Y_Table_Position = $Y_Table_Position + 30;
	}
	elseif (strcmp($texto4, "")!=0) {
			$pdf->SetY($Y_Table_Position);
			
			$pdf->SetX(1);
			$pdf->Cell(14,30,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,30,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,30,'',1);
			$pdf->SetX(157);
			$pdf->Cell(38,30,'',1);
			$pdf->SetX(195);
			$pdf->Cell(10,30,'',1);
			//$pdf->SetX(1);
			if (strlen ( $code ) == 1)
				$pdf->SetX(6);
			else
				if (strlen ( $code ) == 2)
					$pdf->SetX(5);
				else
					$pdf->SetX(4);
			$pdf->Cell(14,6,$code,0,'C');
	
			$pdf->SetX(1);
			$pdf->Cell(14,30,'',1);
			$pdf->SetX(19);
			$pdf->Cell(140,6,$texto,0,'L');
	
			$pdf->SetX(19);
			$pdf->Cell(140,30,'',1);
	
			$pdf->SetX(159);
		
			$pdf->SetY($Y_Table_Position+6);
		
			$pdf->SetX(19);
			$pdf->Cell(140,6,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+12);
			$pdf->SetX(19);
			$pdf->Cell(140,6,$texto2,0,'L');
			
			$pdf->SetY($Y_Table_Position+18);
			$pdf->SetX(19);
			$pdf->Cell(140,6,$texto3,0,'L');
	
	   		$pdf->SetY($Y_Table_Position+24);
			$pdf->SetX(19);
			$pdf->Cell(140,6,$texto4,0,'L');
	
			
			$Y_Table_Position = $Y_Table_Position + 24;
		}
		elseif (strcmp($texto3, "")!=0) {
			$pdf->SetY($Y_Table_Position);
			 $pdf->SetX(1);
			$pdf->Cell(14,24,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,24,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,24,'',1);
			$pdf->SetX(157);
			$pdf->Cell(38,24,'',1);
			$pdf->SetX(195);
			$pdf->Cell(10,24,'',1);
			
			//$pdf->SetX(5);
			if (strlen ( $code ) == 1)
				$pdf->SetX(6);
			else
				if (strlen ( $code ) == 2)
					$pdf->SetX(5);
				else
					$pdf->SetX(4);
			$pdf->Cell(10,6,$code,0,'C');
	
			$pdf->SetX(5);
		
			$pdf->SetX(15);
			$pdf->Cell(140,6,$texto,0,'L');
		
			$pdf->SetX(15);
	
			$pdf->SetX(159);

			$pdf->SetY($Y_Table_Position+6);
	
			$pdf->SetX(15);
			$pdf->Cell(140,6,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+12);
			$pdf->SetX(15);
			$pdf->Cell(140,6,$texto2,0,'L');
		
			$pdf->SetY($Y_Table_Position+18);
			$pdf->SetX(15);
			$pdf->Cell(140,6,$texto3,0,'L');
			
			
			$Y_Table_Position = $Y_Table_Position + 18;
		}
		elseif (strcmp($texto2, "")!=0) {
		    $pdf->SetX(1);
			$pdf->Cell(14,18,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,18,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,18,'',1);
			$pdf->SetX(157);
			$pdf->Cell(38,18,'',1);
			$pdf->SetX(195);
			$pdf->Cell(10,18,'',1);
			$pdf->SetX(1);
		
		
			$pdf->SetY($Y_Table_Position);
			//$pdf->SetX(1);
			if (strlen ( $code ) == 1)
				$pdf->SetX(6);
			else
				if (strlen ( $code ) == 2)
					$pdf->SetX(5);
				else
					$pdf->SetX(4);
			$pdf->Cell(14,6,$code,0,'C');

			$pdf->SetX(1);

			$pdf->SetX(15);
			$pdf->Cell(140,6,$texto,0,'L');
		
			$pdf->SetX(159);

			$pdf->SetY($Y_Table_Position+6);
	
			$pdf->SetX(15);
			$pdf->Cell(140,6,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+12);
			$pdf->SetX(15);
			$pdf->Cell(140,6,$texto2,0,'L');
		
			$Y_Table_Position = $Y_Table_Position + 12;
		}
		elseif (strcmp($texto1, "")!=0) {
			$pdf->SetY($Y_Table_Position);

			$pdf->SetX(1);
			$pdf->Cell(14,12,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,12,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,12,'',1);
			$pdf->SetX(157);
			$pdf->Cell(38,12,'',1);
			$pdf->SetX(195);
			$pdf->Cell(10,12,'',1);
			//$pdf->SetX(1);
			if (strlen ( $code ) == 1)
				$pdf->SetX(6);
			else
				if (strlen ( $code ) == 2)
					$pdf->SetX(5);
				else
					$pdf->SetX(4);
			$pdf->Cell(14,6,$code,0,'C');

			$pdf->SetX(1);
			$pdf->Cell(14,12,'',1);
			$pdf->SetX(15);
			$pdf->Cell(15,6,$texto,0,'L');
		
			$pdf->SetY($Y_Table_Position+6);

			$pdf->SetX(15);
			$pdf->Cell(15,6,$texto1,0,'L');
								
			$Y_Table_Position = $Y_Table_Position + 6;
		} else  {
			$pdf->SetY($Y_Table_Position);
			$pdf->SetX(1);
			$pdf->Cell(14,6,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,6,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,6,'',1);
			$pdf->SetX(157);
			$pdf->Cell(38,6,'',1);
			$pdf->SetX(195);
			$pdf->Cell(10,6,'',1);
			//$pdf->SetX(1);
			if (strlen ( $code ) == 1)
				$pdf->SetX(6);
			else
				if (strlen ( $code ) == 2)
					$pdf->SetX(5);
				else
					$pdf->SetX(4);
			$pdf->Cell(10,6,$code,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(15,6,$texto,0,'L');
	
		
		}
	$pdf->SetY($Y_Table_Position);
	
		
	$j = $j +1;
	$Y_Table_Position = $Y_Table_Position + 6;
	if ($j >=35 || $Y_Table_Position >= 270) {
		// ACA VA EL PIE
		//Posici�n: a 1,5 cm del final
   		$pdf->SetY(-12);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		//N�mero de p�gina
   		$pdf->Cell(0,10,'P�gina '.$pdf->PageNo().'/'.$hojas,0,0,'C');
		if ($hojas <= $hojas_impresas )
			break;
		
		if ($colname_remate != 1812 && $colname_remate != 1742  ) {
		$pdf->AddPage();
		$hojas_impresas = $hojas_impresas + 1;	
		// LUEGO LA CABECERA DE NUEVO

	
if ($hay_exhib == 0 && $sello=='1') {
	//Fields Name position
	$Y_Fields_Name_position = 47;

	//Table position, under Fields Name
	$Y_Table_Position = 57;
}
if ($hay_exhib == 0 && $sello!='1') {
	//Fields Name position
	$Y_Fields_Name_position = 42;

	//Table position, under Fields Name
	$Y_Table_Position = 52;
}

if ($hay_exhib == 1 && $sello=='1') {
	//Fields Name position
	$Y_Fields_Name_position = 52;

	//Table position, under Fields Name
	$Y_Table_Position = 62;
}
if ($hay_exhib == 1 && $sello !='1') {
	//Fields Name position
	$Y_Fields_Name_position = 47;

	//Table position, under Fields Name
	$Y_Table_Position = 57;
}
if ($hay_exhib == 2 && $sello=='1') {
	//Fields Name position
	$Y_Fields_Name_position = 57;

	//Table position, under Fields Name
	$Y_Table_Position = 67;
}

if ($hay_exhib == 2 && $sello!='1') {
	//Fields Name position
	$Y_Fields_Name_position = 52;

	//Table position, under Fields Name
	$Y_Table_Position = 62;
}

//=========================== CABECERA =============================================
$pdf->Image('images/logo_adrianC.jpg',1,8,65);
//Arial bold 14
$pdf->SetFont('Arial','B',12);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->SetY(7);
$pdf->SetX(60);
$pdf->Cell(80,10,'CAT�LOGO PARA VENDEDOR',0,0,'C');

$pdf->SetFont('Arial','B',8);
$pdf->SetY(6);
$pdf->SetX(132);
$pdf->Cell(80,10,'                    Olga Cossettini 731, Piso 3 - CABA',0,0,'L');
$pdf->SetY(10);
$pdf->SetX(132);
$pdf->Cell(80,10,'Tel.:(+54) 11 3984-7400 / www.grupoadrianmercado.com',0,0,'L');

$pdf->Line(2,18,208,18);
//========================== HASTA LA LINEA ==========================================

$pdf->SetFont('Arial','B',10);
$pdf->SetY(17);
$pdf->SetX(5);
$pdf->Cell(19,10, 'Por c/o de: ',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,10, substr($obs,0,90),0,0,'L');
//=============================================================================
if ( strcmp(substr($hora,3,2) , "00") == 0)
	$hora = substr($hora,0,2);
$pdf->SetY(22);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,10,'Fecha y Hora: ',0,0,'L');
$pdf->SetFont('Arial','',10);
$dia = ucwords(strftime("%A", strtotime($fecha)));
$mes = ucwords(strftime("%B", strtotime($fecha)));
$dianum = strftime("%d", strtotime($fecha));
if ($dianum[0]==0)
	$dianum = $dianum[1];

$pdf->Cell(20,10, '    '.$dia.' '.$dianum.' de '.$mes.' de '.strftime("%Y", strtotime($fecha)).' - '.$hora.' hs.',0,0,'L');
//$pdf->Cell(20,10, '      '.$fecha_hoy.' - '.$hora.' hs.',0,0,'L');
//=============================================================================
if ($hay_exhib == 0) {
	$pdf->SetY(27);
	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',10);
	if ($colname_remate == 2272 || $colname_remate == 2271)
		$pdf->Cell(10,10,'Subasta: ',0,0,'L');
	else
		$pdf->Cell(20,10,'Subasta / Exhibici�n: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(16);
	$pdf->Cell(120,10,$direc.", ".$localid.", ".$provin,0,0,'L');
	$pdf->SetX(90);
}
//=============================================================================
if ($hay_exhib == 1) {
	$pdf->SetY(27);
	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,10,'Subasta: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(6);
	$pdf->Cell(120,10,$direc.", ".$localid.", ".$provin,0,0,'L');
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(32);
	$pdf->SetX(5);
	$pdf->Cell(15,10,'Exhibici�n: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(5);
	$pdf->Cell(120,10,$dir_direccion.", ".$localid_ex.", ".$provin_ex,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(37);
}
if ($hay_exhib == 2) {
	$pdf->SetY(27);
	$pdf->SetX(5);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,10,'Subasta: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(6);
	$pdf->Cell(120,10,$direc.", ".$localid.", ".$provin,0,0,'L');
	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(32);
	$pdf->SetX(5);
	$pdf->Cell(20,10,'Exhibici�n 1/2: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(6);
	$pdf->Cell(120,10,$dir_direccion.", ".$localid_ex.", ".$provin_ex,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(37);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(37);
	$pdf->SetX(5);
	$pdf->Cell(20,10,'Exhibici�n 2/2: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(6);
	$pdf->Cell(120,10,$dir_direccion2.", ".$localid_ex2.", ".$provin_ex2,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(42);
}


if ($sello=='1') {
			$pdf->SetX(10);
			if ($hay_exhib == 0)
				$pdf->SetY(37);
			if ($hay_exhib == 1)
				$pdf->SetY(42);
			if ($hay_exhib == 2)
				$pdf->SetY(47);
			$pdf->SetFont('Arial','B',12);
$pdf->Cell(25);
			$pdf->Cell(140,7,'Venta sujeta a aprobaci�n por la empresa vendedora '.$plazo_sap,1,0,'C');
}

	    $pdf->Ln(35);


		$pdf->SetFillColor(232,232,232);
		//Bold Font for Field Name
		$pdf->SetFont('Arial','B',11);
		$pdf->SetY($Y_Fields_Name_position);
		$pdf->SetX(1);
		$pdf->Cell(14,10,'LOTE',1,0,'C',1);
		$pdf->SetX(15);
		$pdf->Cell(115,10,'DESCRIPCION',1,0,'C',1);
		$pdf->SetX(130);
		$pdf->Cell(27,10,'PRE BASE',1,0,'C',1);
		$pdf->SetX(157);
		$pdf->Cell(38,10,'PRE OBT',1,0,'C',1);
		$pdf->SetX(195);
		$pdf->Cell(10,10,'OBS',1,0,'C',1);
		$pdf->Ln();
		$j = 0;
	}
	
}
}
// ACA VA EL PIE AGAIN
if ($pdf->PageNo() <= $hojas) {
		//Posici�n: a 1,5 cm del final
   		$pdf->SetY(-12);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		//N�mero de p�gina
   		$pdf->Cell(0,10,'P�gina '.$pdf->PageNo().'/'.$hojas,0,0,'C');
}
mysqli_close($amercado);
$pdf->Output();
?>  
