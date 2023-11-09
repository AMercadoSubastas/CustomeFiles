<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');

//Connect to your database
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];
//$colname_remate = 1;
$fecha_tope = "2020-08-13";

// Leo el remate
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
$hora = $row_remates["horareal"];
$hora = substr($hora,0,5);
$fecha         = substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);

$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
//$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);
$renglones = 0;
$precio_obtenido = ""; //0;
$neto_facturado = ""; //0;
$nro_recibo = "";
while($row_lotes = mysqli_fetch_array($lotes)) {
	// desde aca 
	$code = "";
	$texto = "";
	$texto2 = "";
	$texto3 = "";
	$texto4 = "";
	$texto5 = "";
	
	$code = $row_lotes["codintlote"];
	$texto = $row_lotes["descor"];
	if ($fechareal  < $fecha_tope )
		$tamano = 39; // tama�o m�ximo renglon
	else
		$tamano = 48; // tama�o m�ximo renglon
    $contador = 0; 
    $contador1 = 0; 
    $contador2 = 0; 
    $contador3 = 0; 
    $contador4 = 0; 
    $contador5 = 0; 
    $contador6 = 0; 
    $contador7 = 0; 

    //$texto = strtoupper($texto);
    $texto_orig= $texto ;
    $largo_orig = strlen($texto_orig);
    $arrayTexto =explode(' ',$texto); 
	$texto = ''; 

	// Reconstruimos el primer renglon
	while(isset($arrayTexto[$contador]) && $tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
    	$texto .= ' '.$arrayTexto[$contador]; 
    	$contador++; 
	} 
	$largo_primer_renglon = strlen($texto)."<br>"; 
	$texto1 = substr($texto_orig,strlen($texto)) ;
	$arrayTexto1 =explode(' ',$texto1); 
	$texto1 = ''; 
	
	// Reconstruimos el segundo renglon
	while(isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1])&& strlen($arrayTexto1[$contador1])!=0){ 
    	$texto1 .= ' '.$arrayTexto1[$contador1]; 
	    $contador1++; 
	}
	$largo_segundo_renglon = strlen($texto1);
	$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
	$arrayTexto2 =explode(' ',$texto2); 
	$texto2 = ''; 

	// Reconstruimos el tercer renglon
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
	$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);
	$texto5 = substr($texto_orig,$largo_quinto) ;
	$arrayTexto5 =explode(' ',$texto5); 
	$texto5 = ''; 
	while(isset($arrayTexto5[$contador5]) && $tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5])&& strlen($arrayTexto5[$contador5])!=0){ 
    	$texto5 .= ' '.$arrayTexto5[$contador5]; 
	    $contador5++; 
	}
	$largo_sexto_renglon = strlen($texto5);
	$largo_sext6 = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);
	$renglones = $renglones + 1;

	if (strcmp($texto1, "")!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto2, "")!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto3, "")!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto4, "")!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto5, "")!=0) {
		$renglones = $renglones + 1;
	}
}

$hojas = floor($renglones / 32);
if ($renglones % 32!= 0)
	$hojas++;
if ($hojas == 0)
	$hojas = 1;
if ( $colname_remate == 2795 || $colname_remate == 2793)
	$hojas = $hojas + 1;

if ($colname_remate == 2357 || $colname_remate == 2368 || $colname_remate == 2386)  // || $colname_remate == 2348)
	$hojas = $hojas - 1;

if ( $colname_remate == 2387 || $colname_remate == 2377 || $colname_remate == 2328 || $colname_remate == 2094 || $colname_remate == 2051)
	$hojas = $hojas + 1;
	
$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
$totalRows_lotes = mysqli_num_rows($lotes);



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

if ($colname_remate == 2219)
	$hay_exhib = 0;


//Create a new PDF file
$pdf=new FPDF();

$pdf->SetAutoPageBreak(0, 5) ;
$pdf->AddPage();

$hojas_impresas = $hojas_impresas + 1;

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

	
$pdf->Image('images/logo_adrian.jpg',3,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',12);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->Image('carpeta_fac.jpg',68,8,50);
$pdf->SetFont('Arial','B',8);
$pdf->SetY(9);
$pdf->SetX(137);
$pdf->Cell(80,10,'Av. A. Moreau de Justo 1080, Piso 4, Of. 198 C.A.B.A.',0,0,'L');
	
$pdf->SetY(12);
$pdf->SetX(137);
$pdf->Cell(80,10,'Tel/Fax: (+54) 11 3984-7400 ',0,0,'L');
	
$pdf->SetFont('Arial','B',11);

$pdf->SetY(22);
$pdf->SetX(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(25,10,'FECHA          :  ',0,0,'L');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10);
$pdf->Cell(25,10,$fecha.'    HORA : '.$hora,0,0,'L');
	
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
$pdf->SetFont('Arial','B',9);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(5);
$pdf->Cell(12,10,'LOTE',1,0,'C',1);
$pdf->SetX(17);
$pdf->Cell(106,10,'DESCRIPCION',1,0,'C',1);
$pdf->SetX(123);
$pdf->Cell(9,10,'SE�A',1,0,'C',1);
$pdf->SetX(132);
$pdf->Cell(30,10,'PRE. OBT.',1,0,'C',1);
$pdf->SetX(162);
$pdf->Cell(30,10,'IMP. FACT.',1,0,'C',1);
$pdf->SetX(192);
$pdf->Cell(15,10,'OBS',1,0,'C',1);
$pdf->Ln();
	
//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
// luego una nueva pagina y la cabecera
$j = 0;
$precio_obt_total = 0.00;
$neto_facturado_total = 0.00;
$total_cobrado = 0.00;
while($row_lotes = mysqli_fetch_array($lotes)) 
{
	$texto = "";
	$texto2 = "";
	$texto3 = "";
	$texto4 = "";
	$texto5 = "";
	
	$code = $row_lotes["codintlote"];
	$texto = $row_lotes["descor"];
	$secuencia = $row_lotes["secuencia"];
	$precio_obt = $row_lotes["preciofinal"];
	$lote_facturado = $row_lotes["estado"];
	//echo "CODLOTE = ".$code."  PRECIOFINAL = ".$precio_obt."  ESTADO = ".$lote_facturado." === ";
	if ($lote_facturado == 1) {
        // LEO EL RENGLON DE LA FACTURA
        $query_detfac = sprintf("SELECT * FROM detfac WHERE codrem = %s AND codlote = %s", $colname_remate, $secuencia);
        $detfac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
        $totalRows_detfac = mysqli_num_rows($detfac);
        /*
		if ($totalRows_detfac > 1) {
			// DEBO FIJARME SI TIENE NOTA DE CREDITO
			
        }
	
		else 	*/ 
		if ($totalRows_detfac > 0) {
			// ASUMO QUE SE FACTURO UNA VEZ
			while ($row_detfac = mysqli_fetch_array($detfac)) {
				$neto_facturado = $row_detfac["neto"];
				$tc_cabfac = $row_detfac["tcomp"];
				$nc_cabfac = $row_detfac["ncomp"];
				// LEO DETRECIBO PARA VER SI SE COBRO
				$query_detrecibo = sprintf("SELECT * FROM detrecibo WHERE tcomprel = %s AND ncomprel = %s", $tc_cabfac, $nc_cabfac);
        		$detrecibo = mysqli_query($amercado, $query_detrecibo) or die(mysqli_error($amercado));
				if ($row_detrecibo = mysqli_fetch_array($detrecibo)) {
					$nro_recibo = $row_detrecibo["ncomp"];
					$total_cobrado += $row_detfac["neto"];
				}
				else
					$nro_recibo = "P";
				
			}
				
		}
	}
	else {
		$neto_facturado = 0;
		$nro_recibo = "";
		$precio_obt = "";
	}
    if ($fechareal  < $fecha_tope )
		$tamano = 39; // tama�o m�ximo renglon
	else
		$tamano = 48; // tama�o m�ximo renglon
    $contador = 0; 
    $contador1 = 0; 
    $contador2 = 0; 
    $contador3 = 0; 
    $contador4 = 0; 
    $contador5 = 0; 
    $contador6 = 0; 
    $contador7 = 0; 

	$precio_obt_total += $precio_obt;
	$neto_facturado_total += (int) $neto_facturado;
	if ($precio_obt != "")
		$precio_obt  = number_format($precio_obt, 2, ',','.');
	//if (isset($neto_facturado) && $neto_facturado > 0 )
	//	$neto_facturado  = number_format($neto_facturado, 2, ',','.');
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
	$texto1 = substr($texto_orig,strlen($texto)) ;
	$arrayTexto1 =explode(' ',$texto1); 
	$texto1 = ''; 

	// Reconstruimos el segundo renglon
	while(isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1])&& strlen($arrayTexto1[$contador1])!=0){ 
    	$texto1 .= ' '.$arrayTexto1[$contador1]; 
	    $contador1++; 
	}
	$largo_segundo_renglon = strlen($texto1);
	$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
	$arrayTexto2 =explode(' ',$texto2); 
	$texto2 = ''; 

	// Reconstruimos el tercer renglon
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
	$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);
	$texto5 = substr($texto_orig,$largo_quinto) ;
	$arrayTexto5 =explode(' ',$texto5); 
	$texto5 = ''; 
	while(isset($arrayTexto5[$contador5]) && $tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5]) && strlen($arrayTexto5[$contador5])!=0){ 
    	$texto5 .= ' '.$arrayTexto5[$contador5]; 
	    $contador5++; 
	}
	$largo_sexto_renglon = strlen($texto5);
	$largo_sext6 = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);


	$pdf->SetFont('Arial','B',12);
	$pdf->SetY($Y_Table_Position);
	if ($texto5!="") {
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(123);
	    $pdf->Cell(9,42,'',1);
		$pdf->SetX(132);
	    $pdf->Cell(30,42,$precio_obt,1,0,'R');
		$pdf->SetX(162);
	    $pdf->Cell(30,42,$neto_facturado,1,0,'R');
		$pdf->SetX(192);
	    $pdf->Cell(15,42,$nro_recibo,1,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,7,$code,0,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,42,'',1);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto,0,'L');
		$pdf->SetX(123);
	    $pdf->Cell(9,42,'',1);
		$pdf->SetX(17);
		$pdf->Cell(106,42,'',1);
		$pdf->SetX(162);
		$pdf->Cell(30,42,'',1);
		$pdf->SetY($Y_Table_Position+7);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto1,0,'L');
		$pdf->SetY($Y_Table_Position+14);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto3,0,'L');
	    $pdf->SetY($Y_Table_Position+28);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto5,0,'L');
		$Y_Table_Position = $Y_Table_Position + 35;
	}
	elseif (strcmp($texto4, "")!=0) {
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(123);
		$pdf->Cell(9,35,'',1);
		$pdf->SetX(132);
		$pdf->Cell(30,35,$precio_obt,1,0,'R');
		$pdf->SetX(162);
	    $pdf->Cell(30,35,$neto_facturado,1,0,'R');
		$pdf->SetX(192);
	    $pdf->Cell(15,35,$nro_recibo,1,0,'C');
		$pdf->SetX(5);
		$pdf->SetX(5);
		$pdf->Cell(12,7,$code,0,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(9,35,'',1);
		$pdf->SetX(17);
		$pdf->Cell(140,7,$texto,0,'L');
		$pdf->SetX(17);
		$pdf->Cell(140,35,'',1);
		$pdf->SetX(123);
		$pdf->Cell(9,35,'',1);
		$pdf->SetY($Y_Table_Position+7);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto1,0,'L');
		$pdf->SetY($Y_Table_Position+14);
		$pdf->SetX(17);
		$pdf->Cell(140,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto4,0,'L');
		$Y_Table_Position = $Y_Table_Position + 28;
	}
	elseif (strcmp($texto3, "")!=0) {
		$pdf->SetX(123);
		$pdf->Cell(9,28,'',1);
		$pdf->SetX(132);
		$pdf->Cell(30,28,$precio_obt,1,0,'R');
		$pdf->SetX(162);
	    $pdf->Cell(30,28,$neto_facturado,1,0,'R');
		$pdf->SetX(192);
	    $pdf->Cell(15,28,$nro_recibo,1,0,'C');
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(5);
		$pdf->Cell(12,7,$code,0,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,28,'',1);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto,0,'L');
		$pdf->SetX(17);
		$pdf->Cell(106,28,'',1);
		$pdf->SetY($Y_Table_Position+7);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto1,0,'L');
		$pdf->SetY($Y_Table_Position+14);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto3,0,'L');
		$Y_Table_Position = $Y_Table_Position + 21;
	}
	elseif (strcmp($texto2, "")!=0) {
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(123);
		$pdf->Cell(9,21,'',1);
		$pdf->SetX(132);
		$pdf->Cell(30,21,$precio_obt,1,0,'R');
		$pdf->SetX(162);
	    $pdf->Cell(30,21,$neto_facturado,1,0,'R');
		$pdf->SetX(192);
	    $pdf->Cell(15,21,$nro_recibo,1,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,7,$code,0,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,21,'',1);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto,0,'L');
		$pdf->SetX(17);
		$pdf->Cell(106,21,'',1);
		$pdf->SetY($Y_Table_Position+7);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto1,0,'L');
		$pdf->SetY($Y_Table_Position+14);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto2,0,'L');
		$Y_Table_Position = $Y_Table_Position + 14;
	}
	elseif (strcmp($texto1, "")!=0) {
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(123);
		$pdf->Cell(9,14,'',1);
		$pdf->SetX(132);
		$pdf->Cell(30,14,$precio_obt,1,0,'R');
		$pdf->SetX(162);
	    $pdf->Cell(30,14,$neto_facturado,1,0,'R');
		$pdf->SetX(192);
	    $pdf->Cell(15,14,$nro_recibo,1,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,7,$code,0,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,14,'',1);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto,0,'L');
		$pdf->Cell(9,14,'',1);
		$pdf->SetX(17);
		$pdf->Cell(106,14,'',1);
		$pdf->SetY($Y_Table_Position+7);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto1,0,'L');
		$Y_Table_Position = $Y_Table_Position + 7;
	} else  {
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(123);
		$pdf->Cell(9,7,'',1);
		$pdf->SetX(132);
		$pdf->Cell(30,7,$precio_obt,1,0,'R');
		$pdf->SetX(162);
	    $pdf->Cell(30,7,$neto_facturado,1,0,'R');
		$pdf->SetX(192);
	    $pdf->Cell(15,7,$nro_recibo,1,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,7,$code,0,0,'C');
		$pdf->SetX(5);
		$pdf->Cell(12,7,'',1);
		$pdf->SetX(17);
		$pdf->Cell(106,7,$texto,0,'L');
		$pdf->SetX(17);
		$pdf->Cell(106,7,'',1);
	}	
	$pdf->SetY($Y_Table_Position);

	
	$j = $j +1;
	$Y_Table_Position = $Y_Table_Position + 7;
	if ($j >=25 || $Y_Table_Position >= 260) {
		// ACA VA EL PIE
		//Posici�n: a 1,5 cm del final
		$pdf->SetY(-15);
		//Arial italic 8
		$pdf->SetFont('Arial','I',8);
		//N�mero de p�gina
		$pdf->Cell(0,10,'P�gina '.$pdf->PageNo().'/'.$hojas,0,0,'C');
		//if ($colname_remate != 1809 && $pdf->PageNo() < 3)
		
		if ($hojas <= $hojas_impresas )
			break;
		
		$pdf->AddPage();
		
		$hojas_impresas = $hojas_impresas + 1;
		
		// LUEGO LA CABECERA DE NUEVO
		//Fields Name position
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

		$pdf->Image('images/logo_adrian.jpg',3,8,50);
		//Arial bold 15
		$pdf->SetFont('Arial','B',12);
		//Movernos a la derecha
		$pdf->Cell(55);
		//T�tulo
	
		$pdf->Image('carpeta_fac.jpg',68,8,50);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetY(9);
		$pdf->SetX(137);
		$pdf->Cell(80,10,'Av. A. Moreau de Justo 1080, Piso 4, Of. 198 C.A.B.A.',0,0,'L');

		$pdf->SetY(12);
		$pdf->SetX(137);
		$pdf->Cell(80,10,'Tel/Fax: (+54) 11 3984-7400 ',0,0,'L');
		$pdf->SetFont('Arial','B',11);
		$pdf->SetY(22);
		$pdf->SetX(5);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(25,10,'FECHA          :  ',0,0,'L');
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(10);
		$pdf->Cell(25,10,$fecha.'    HORA : '.$hora,0,0,'L');
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
      $pdf->SetFont('Arial','B',9);
      $pdf->SetY($Y_Fields_Name_position);
      $pdf->SetX(5);
      $pdf->Cell(13,10,'LOTE',1,0,'C',1);
      $pdf->SetX(17);
      $pdf->Cell(108,10,'DESCRIPCION',1,0,'C',1);
      $pdf->SetX(123);
      $pdf->Cell(9,10,'SE�A',1,0,'C',1);
      $pdf->SetX(132);
      $pdf->Cell(30,10,'PRE. OBT.',1,0,'C',1);
      $pdf->SetX(162);
      $pdf->Cell(30,10,'IMP. FACT.',1,0,'C',1);
      $pdf->SetX(192);
      $pdf->Cell(15,10,'OBS',1,0,'C',1);
      $pdf->Ln();
      $j = 0;
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
	
	}

}
$Y_Table_Position = $Y_Table_Position + 7;
$diferencia = $precio_obt_total - $neto_facturado_total;
$precio_obt_total  = number_format($precio_obt_total, 2, ',','.');
$neto_facturado_total  = number_format($neto_facturado_total, 2, ',','.');
$diferencia  = number_format($diferencia, 2, ',','.');
$total_cobrado  = number_format($total_cobrado, 2, ',','.');

$pdf->SetY($Y_Table_Position);
$pdf->SetX(102);
$pdf->Cell(30,10,'TOTALES',1,0,'C',1);
$pdf->SetX(132);
$pdf->Cell(30,10,$precio_obt_total,1,0,'C',1);
$pdf->SetX(162);
$pdf->Cell(30,10,$neto_facturado_total,1,0,'C',1);
$Y_Table_Position = $Y_Table_Position + 10;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(102);
$pdf->Cell(30,10,'DIFERENCIA',1,0,'C',1);
$pdf->SetX(132);
$pdf->Cell(30,10,$diferencia,1,0,'C',1);
$Y_Table_Position = $Y_Table_Position + 10;
$pdf->SetY($Y_Table_Position);
$pdf->SetX(102);
$pdf->Cell(30,10,'COBRADO',1,0,'C',1);
$pdf->SetX(132);
$pdf->Cell(30,10,$total_cobrado,1,0,'C',1);

// ACA VA EL PIE AGAIN
//Posici�n: a 1,5 cm del final
$pdf->SetY(-15);
//Arial italic 8
$pdf->SetFont('Arial','I',8);
//N�mero de p�gina
$pdf->Cell(0,10,'P�gina '.$pdf->PageNo().'/'.$hojas,0,0,'C');

mysqli_close($amercado);
$pdf->Output();
?>  
