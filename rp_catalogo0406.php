<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');

//Connect to your database
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];


$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);

$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));

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
		$texto11 = "";
		$texto12 = "";
		$texto13 = "";
		$texto14 = "";
	

		$sublote = $row_lotes["codintsublote"];
		$code = $code.$sublote ;
		$texto = $row_lotes["descripcion"];
	    $tamano = 53; // tama�o m�ximo renglon
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
		$contador11 = 0;
		$contador12 = 0;
		$contador13 = 0;
		$contador14 = 0;





        $texto = strtoupper($texto);
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
		; 
		$largo_primer_renglon = strlen($texto)."<br>"; 

		// Aca empieza un renglon
		$texto1 = substr($texto_orig,strlen($texto)) ;
		$arrayTexto1 =explode(' ',$texto1); 
		$texto1 = ''; 
		// Reconstruimos el segundo renglon
		while( isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1]) && strlen($arrayTexto1[$contador1])!=0){
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


		// ===========================================================================================
		$texto4 = substr($texto_orig,$largo_cuarto) ;
		$arrayTexto4 =explode(' ',$texto4); 
		$texto4 = ''; 
		while(isset($arrayTexto4[$contador4]) && $tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& strlen($arrayTexto4[$contador4])!=0){ 
    		$texto4 .= ' '.$arrayTexto4[$contador4]; 
    		$contador4++; 
		}
		$largo_quinto_renglon = strlen($texto4);
		$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);

		//==============================================================================================
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
		$largo_undecimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon);

		// ============================================================================================

		$texto11 = substr($texto_orig,$largo_undecimo) ;
		$arrayTexto11 =explode(' ',$texto11); 
		$texto11 = ''; 
		while(isset($arrayTexto11[$contador11]) && $tamano >= strlen($texto11) + strlen($arrayTexto11[$contador11])&& strlen($arrayTexto11[$contador11])!=0){ 
    		$texto11 .= ' '.$arrayTexto11[$contador11]; 
    		$contador11++; 
		}
		$largo_duodecimo_renglon = strlen($texto11);
		$largo_duodecimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon);

		// ============================================================================================

		$texto12 = substr($texto_orig,$largo_duodecimo) ;
		$arrayTexto12 =explode(' ',$texto12); 
		$texto12 = ''; 
		while(isset($arrayTexto12[$contador12]) && $tamano >= strlen($texto12) + strlen($arrayTexto12[$contador12])&& strlen($arrayTexto12[$contador12])!=0){ 
    		$texto12 .= ' '.$arrayTexto12[$contador12]; 
    		$contador12++; 
		}
		$largo_decimotercer_renglon = strlen($texto12);
		$largo_decimotercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon);

		// ============================================================================================

		$texto13 = substr($texto_orig,$largo_decimotercer) ;
		$arrayTexto13 =explode(' ',$texto13); 
		$texto13 = ''; 
		while(isset($arrayTexto13[$contador13]) && $tamano >= strlen($texto13) + strlen($arrayTexto13[$contador13])&& strlen($arrayTexto13[$contador13])!=0){ 
    		$texto13 .= ' '.$arrayTexto13[$contador13]; 
    		$contador13++; 
		}
		$largo_decimocuarto_renglon = strlen($texto13);
		$largo_decimocuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon);


		// ============================================================================================

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
	
		if (strcmp($texto6, "")!=0) {
			$renglones = $renglones + 1;
		}
		if (strcmp($texto7, "")!=0) {
			$renglones = $renglones + 1;
		}
		if (strcmp($texto8, "")!=0) {
			$renglones = $renglones + 1;
		}
		if (strcmp($texto9, "")!=0) {
			$renglones = $renglones + 1;
		}
		if (strcmp($texto10, "")!=0) {
			$renglones = $renglones + 1;
		}
		if (strcmp($texto11, "")!=0) {
			$renglones = $renglones + 1;
		}
		if (strcmp($texto12, "")!=0) {
			$renglones = $renglones + 1;
		}
		if (strcmp($texto13, "")!=0) {
			$renglones = $renglones + 1;
		}
}

$hojas = floor($renglones / 22);
if ($hojas == 0)
	$hojas = 1;

$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
$totalRows_lotes = mysqli_num_rows($lotes);

// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$row_remates = mysqli_fetch_assoc($remates);
$direc = $row_remates["direccion"];
$fecha = $row_remates["fecreal"];
$hora = $row_remates["horareal"];
$hora = substr($hora,0,5);
$sello = $row_remates["sello"];
$cod_loc = $row_remates["codloc"];
$cod_prov = $row_remates["codprov"];
$fecha         = substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);

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
$j = 0; // Me dar� el total de renglones

//Create a new PDF file
$pdf=new FPDF();
$pdf->SetAutoPageBreak(0, 5) ;

$pdf->AddPage();

	
//Fields Name position
$Y_Fields_Name_position = 50;
	
//Table position, under Fields Name
$Y_Table_Position = 60;
	
//$pdf->AddPage();
$pdf->Image('amercado.jpg',1,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',14);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->Image('catalogo_remate.jpg',68,8,50);
//$pdf->Cell(70,10,'CATALOGO DE REMATE',1,0,'C');
$pdf->SetFont('Arial','B',8);
//$pdf->Ln(1);
//$pdf->SetY(25);
//$pdf->Cell(1);
//$pdf->SetX(5);
$pdf->SetY(9);
$pdf->SetX(137);
$pdf->Cell(80,10,'Av. A. Moreau de Justo 740, Piso 4, Of. 19 � C.A.B.A.',0,0,'L');
	
$pdf->SetY(12);
//$pdf->Cell(1);
$pdf->SetX(137);
$pdf->Cell(80,10,'Tel/Fax: (+54) 11 4343-9893 ',0,0,'L');
	


$pdf->SetY(22);


$pdf->SetX(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,10,'FECHA          :  ',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10);
$pdf->Cell(25,10,$fecha.'    HORA : '.$hora,0,0,'L');
	
$pdf->SetY(27);
$pdf->SetX(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,10,'SUBASTA     :  ',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10);
$pdf->Cell(120,10,$direc." , ".$localid." , ".$provin,0,0,'L');
$pdf->SetX(90);

	

if ($hay_exhib) {
	$pdf->SetFont('Arial','B',14);
	$pdf->SetY(32);
	$pdf->SetX(1);
	$pdf->Cell(25,10,'EXHIBICION :  ',0,0,'L');
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(10);
	$pdf->Cell(120,10,$dir_direccion." , ".$localid_ex." , ".$provin_ex,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(32);
	
}
else 
	$pdf->SetY(27);

if ($sello=='1') {
	$pdf->SetX(120);
	$pdf->SetY(38);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(55);
	$pdf->Cell(70,10,'SUJETO A APROBACION',1,0,'C');
}

$pdf->Ln(35);

$pdf->SetFillColor(232,232,232);
//Bold Font for Field Name
$pdf->SetFont('Arial','B',14);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(4);
$pdf->Cell(16,10,'LOTE',1,0,'C',1);
$pdf->SetX(20);
$pdf->Cell(150,10,'DESCRIPCION',1,0,'C',1);
$pdf->SetX(170);
$pdf->Cell(34,10,'OBS.',1,0,'C',1);
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
	$texto11 = "";	
	$texto12 = "";	
	$texto13 = "";
			
    $code = $row_lotes["codintnum"];
	$sublote = $row_lotes["codintsublote"];
	$code = $code.$sublote ;
	$texto = $row_lotes["descripcion"];
	     $tamano = 53; // tama�o m�ximo renglon
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
		 $contador11 = 0; 
		 $contador12 = 0; 
		 $contador13 = 0; 

        $texto = strtoupper($texto);

        $texto_orig= $texto ;

        $largo_orig = strlen($texto_orig);

// Cortamos la cadena por los espacios 

      	$arrayTexto =explode(' ',$texto); 
		$texto = ''; 

// Reconstruimos el primer renglon =============================================================================
while(isset($arrayTexto[$contador]) && $tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
    $texto .= ' '.$arrayTexto[$contador]; 
    $contador++; 
} 

$largo_primer_renglon = strlen($texto)."<br>"; 

$texto1 = substr($texto_orig,strlen($texto)) ;
$arrayTexto1 =explode(' ',$texto1); 
$texto1 = ''; 

// Reconstruimos el segundo renglon ============================================================================
while(isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1])&& strlen($arrayTexto1[$contador1])!=0){ 
    $texto1 .= ' '.$arrayTexto1[$contador1]; 
    $contador1++; 
}
$largo_segundo_renglon = strlen($texto1);

$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
$arrayTexto2 =explode(' ',$texto2); 
$texto2 = ''; 

// Reconstruimos el tercer renglon ==============================================================================
while(isset($arrayTexto2[$contador2]) && $tamano >= strlen($texto2) + strlen($arrayTexto2[$contador2])&& strlen($arrayTexto2[$contador2])!=0){ 
    $texto2 .= ' '.$arrayTexto2[$contador2]; 
    $contador2++; 
}
$largo_tercer_renglon = strlen($texto2);
$largo_tercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);

$texto3 = substr($texto_orig,$largo_tercer) ;
$arrayTexto3 =explode(' ',$texto3); 
$texto3 = ''; 

// Reconstruimos el cuarto renglon =============================================================================
while(isset($arrayTexto3[$contador3]) && $tamano >= strlen($texto3) + strlen($arrayTexto3[$contador3])&& strlen($arrayTexto3[$contador3])!=0){ 
    $texto3 .= ' '.$arrayTexto3[$contador3]; 
    $contador3++; 
}
$largo_cuarto_renglon = strlen($texto3);
$largo_cuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon);

$texto4 = substr($texto_orig,$largo_cuarto) ;
$arrayTexto4 =explode(' ',$texto4); 
$texto4 = ''; 

// Reconstruimos el quinto renglon ==============================================================================
while(isset($arrayTexto4[$contador4]) && $tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& strlen($arrayTexto4[$contador4])!=0){ 
    $texto4 .= ' '.$arrayTexto4[$contador4]; 
    $contador4++; 
}
$largo_quinto_renglon = strlen($texto4);
$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);

$texto5 = substr($texto_orig,$largo_quinto) ;
$arrayTexto5 =explode(' ',$texto5); 
$texto5 = ''; 

// Reconstruimos el sexto renglon ================================================================================
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

// Reconstruimos el undecimo renglon =====================================================================================
while(isset($arrayTexto10[$contador10]) && $tamano >= strlen($texto10) + strlen($arrayTexto10[$contador10]) && strlen($arrayTexto10[$contador10])!=0){ 
    $texto10 .= ' '.$arrayTexto10[$contador10]; 
    $contador10++; 
}
$largo_undecimo_renglon = strlen($texto10);
$largo_undecimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon);

// Reconstruimos el duodecimo renglon =====================================================================================
while(isset($arrayTexto11[$contador11]) && $tamano >= strlen($texto11) + strlen($arrayTexto11[$contador11]) && strlen($arrayTexto11[$contador11])!=0){ 
    $texto11 .= ' '.$arrayTexto11[$contador11]; 
    $contador11++; 
}
$largo_duodecimo_renglon = strlen($texto11);
$largo_duodecimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon);

// Reconstruimos el decimotercer renglon =====================================================================================
while(isset($arrayTexto12[$contador12]) && $tamano >= strlen($texto12) + strlen($arrayTexto12[$contador12]) && strlen($arrayTexto12[$contador12])!=0){ 
    $texto12 .= ' '.$arrayTexto12[$contador12]; 
    $contador12++; 
}
$largo_decimotercer_renglon = strlen($texto12);
$largo_decimotercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon);



	$pdf->SetFont('Arial','B',12);
	$pdf->SetY($Y_Table_Position);

if ((strcmp($texto13,"")!=0 && $Y_Table_Position >= 179) || (strcmp($texto12,"")!=0 && $Y_Table_Position >= 186) ||
    (strcmp($texto11,"")!=0 && $Y_Table_Position >= 193) || (strcmp($texto10,"")!=0 && $Y_Table_Position >= 200) ||
    (strcmp($texto9, "")!=0 && $Y_Table_Position >= 207) || (strcmp($texto8, "")!=0 && $Y_Table_Position >= 214) ||
    (strcmp($texto7, "")!=0 && $Y_Table_Position >= 221) || (strcmp($texto6, "")!=0 && $Y_Table_Position >= 228) ||
    (strcmp($texto5, "")!=0 && $Y_Table_Position >= 235) || (strcmp($texto4, "")!=0 && $Y_Table_Position >= 242) ||
    (strcmp($texto3, "")!=0 && $Y_Table_Position >= 249) || (strcmp($texto2, "")!=0 && $Y_Table_Position >= 256) ||
    (strcmp($texto1, "")!=0 && $Y_Table_Position >= 263) || (strcmp($texto,  "")!=0 && $Y_Table_Position >= 270)) {


// ACA VA EL PIE
		//Posici�n: a 1,5 cm del final
   		$pdf->SetY(-15);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		//N�mero de p�gina
   		$pdf->Cell(0,10,'P�gina '.$pdf->PageNo().'/'.$hojas,0,0,'C');
		$pdf->AddPage();
		// LUEGO LA CABECERA DE NUEVO
		//Fields Name position
$Y_Fields_Name_position = 50;
	
//Table position, under Fields Name
$Y_Table_Position = 60;
	
//$pdf->AddPage();
$pdf->Image('amercado.jpg',1,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',14);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->Image('catalogo_remate.jpg',68,8,50);
//$pdf->Cell(70,10,'CATALOGO DE REMATE',1,0,'C');
$pdf->SetFont('Arial','B',8);
//$pdf->Ln(1);
//$pdf->SetY(25);
//$pdf->Cell(1);
//$pdf->SetX(5);
$pdf->SetY(9);
$pdf->SetX(137);
$pdf->Cell(80,10,'Av. A. Moreau de Justo 740, Piso 4, Of. 19 � C.A.B.A.',0,0,'L');
	
$pdf->SetY(12);
//$pdf->Cell(1);
$pdf->SetX(137);
$pdf->Cell(80,10,'Tel/Fax: (+54) 11 4343-9893 ',0,0,'L');
	
$pdf->SetY(22);
$pdf->SetX(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,10,'FECHA          :  ',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10);
$pdf->Cell(25,10,$fecha.'    HORA : '.$hora,0,0,'L');
	
$pdf->SetY(27);
$pdf->SetX(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,10,'SUBASTA     :  ',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10);
$pdf->Cell(120,10,$direc." , ".$localid." , ".$provin,0,0,'L');
$pdf->SetX(90);

	

if ($hay_exhib) {
	$pdf->SetFont('Arial','B',14);
	$pdf->SetY(32);
	$pdf->SetX(1);
	$pdf->Cell(25,10,'EXHIBICION :  ',0,0,'L');
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(10);
	$pdf->Cell(120,10,$dir_direccion." , ".$localid_ex." , ".$provin_ex,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(32);
	
}
else 
	$pdf->SetY(27);

if ($sello=='1') {
			$pdf->SetX(120);
			$pdf->SetY(38);
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(55);
			$pdf->Cell(70,10,'SUJETO A APROBACION',1,0,'C');
		}

	    $pdf->Ln(35);


		$pdf->SetFillColor(232,232,232);
		//Bold Font for Field Name
		$pdf->SetFont('Arial','B',14);
		$pdf->SetY($Y_Fields_Name_position);
		$pdf->SetX(4);
		$pdf->Cell(16,10,'LOTE',1,0,'C',1);
		$pdf->SetX(20);
		$pdf->Cell(150,10,'DESCRIPCION',1,0,'C',1);
		$pdf->SetX(170);
		$pdf->Cell(34,10,'OBS.',1,0,'C',1);
		$pdf->Ln();
		$j = 0;
		//Fields Name position
		$Y_Fields_Name_position = 50;
	
		//Table position, under Fields Name
		$Y_Table_Position = 60;
	
		$pdf->SetFont('Arial','B',12);
		$pdf->SetY($Y_Table_Position);


} //else {
	if (strcmp($texto13,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');
		
		$pdf->SetX(4);
		$pdf->Cell(16,98,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,98,'',1);
			
		$pdf->SetX(170);
		$pdf->Cell(34,98,'',1);
		$pdf->SetY($Y_Table_Position+7);
	
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+42);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+49);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+56);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+63);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+70);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+77);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+84);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+91);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto13,0,'L');

		$Y_Table_Position = $Y_Table_Position + 91;

	}
	elseif (strcmp($texto12,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');
		
		$pdf->SetX(4);
		$pdf->Cell(16,91,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,91,'',1);
			
		$pdf->SetX(170);
		$pdf->Cell(34,91,'',1);
		$pdf->SetY($Y_Table_Position+7);
	
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+42);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+49);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+56);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+63);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+70);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+77);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+84);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto12,0,'L');

		$Y_Table_Position = $Y_Table_Position + 84;

	}
	elseif (strcmp($texto11,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');
		
		$pdf->SetX(4);
		$pdf->Cell(16,84,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,84,'',1);
			
		$pdf->SetX(170);
		$pdf->Cell(34,84,'',1);
		$pdf->SetY($Y_Table_Position+7);
	
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+42);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+49);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+56);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+63);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+70);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+77);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto11,0,'L');

		$Y_Table_Position = $Y_Table_Position + 77;

	}
	elseif (strcmp($texto10,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');
		
		$pdf->SetX(4);
		$pdf->Cell(16,77,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,77,'',1);
			
		$pdf->SetX(170);
		$pdf->Cell(34,77,'',1);
		$pdf->SetY($Y_Table_Position+7);
	
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+42);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+49);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+56);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+63);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+70);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto10,0,'L');

		$Y_Table_Position = $Y_Table_Position + 70;

	}
	elseif (strcmp($texto9,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');
		
		$pdf->SetX(4);
		$pdf->Cell(16,70,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,70,'',1);
			
		$pdf->SetX(170);
		$pdf->Cell(34,70,'',1);
		$pdf->SetY($Y_Table_Position+7);
	
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+42);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+49);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+56);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+63);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto9,0,'L');

		$Y_Table_Position = $Y_Table_Position + 63;

	}
	elseif (strcmp($texto8,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');

		$pdf->SetX(4);
		$pdf->Cell(16,63,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
		
		$pdf->SetX(20);
		$pdf->Cell(150,63,'',1);
		
		$pdf->SetX(170);
		$pdf->Cell(34,63,'',1);
		$pdf->SetY($Y_Table_Position+7);
	
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+42);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+49);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+56);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto8,0,'L');

		$Y_Table_Position = $Y_Table_Position + 56;
	}

	elseif (strcmp($texto7,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');
		
		$pdf->SetX(4);
		$pdf->Cell(16,56,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,56,'',1);
		
		$pdf->SetX(170);
		$pdf->Cell(34,56,'',1);
		$pdf->SetY($Y_Table_Position+7);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+42);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+49);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto7,0,'L');
		
		$Y_Table_Position = $Y_Table_Position + 49;
	}

	elseif (strcmp($texto6,"")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');
	
		$pdf->SetX(4);
		$pdf->Cell(16,49,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
		
		$pdf->SetX(20);
		$pdf->Cell(150,49,'',1);
		
		$pdf->SetX(170);
		$pdf->Cell(34,49,'',1);
		$pdf->SetY($Y_Table_Position+7);
	
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+21);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+28);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+42);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto6,0,'L');


		$Y_Table_Position = $Y_Table_Position + 42;
	}
	elseif (strcmp($texto5, "")!=0) {

		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(4);
		$pdf->Cell(16,7,$code,0,'C');
	
		$pdf->SetX(4);
		$pdf->Cell(16,42,'',1);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,42,'',1);
		
		$pdf->SetX(170);
		$pdf->Cell(34,42,'',1);
		$pdf->SetY($Y_Table_Position+7);

		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto2,0,'L');

		$pdf->SetY($Y_Table_Position+21);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto3,0,'L');
	
	    	$pdf->SetY($Y_Table_Position+28);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto4,0,'L');
	
		$pdf->SetY($Y_Table_Position+35);
		$pdf->SetX(20);
		$pdf->Cell(150,7,$texto5,0,'L');

		$Y_Table_Position = $Y_Table_Position + 35;
	}
	elseif (strcmp($texto4, "")!=0) {

			$pdf->SetY($Y_Table_Position);
			$pdf->SetX(4);
			$pdf->Cell(16,7,$code,0,'C');
			$pdf->SetX(4);
			$pdf->Cell(16,35,'',1);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto,0,'L');
		
			$pdf->SetX(20);
			$pdf->Cell(150,35,'',1);
					
			$pdf->SetX(170);
			$pdf->Cell(34,35,'',1);
			$pdf->SetY($Y_Table_Position+7);
		
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+14);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto2,0,'L');
			
			$pdf->SetY($Y_Table_Position+21);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto3,0,'L');
			
	    		$pdf->SetY($Y_Table_Position+28);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto4,0,'L');
		
			
			$Y_Table_Position = $Y_Table_Position + 28;
		}
		elseif (strcmp($texto3, "")!=0) {

			$pdf->SetY($Y_Table_Position);
			$pdf->SetX(4);
			$pdf->Cell(16,7,$code,0,'C');
		
			$pdf->SetX(4);
			$pdf->Cell(16,28,'',1);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto,0,'L');
				
			$pdf->SetX(20);
			$pdf->Cell(150,28,'',1);
		
			$pdf->SetX(170);
			$pdf->Cell(34,28,'',1);
			$pdf->SetY($Y_Table_Position+7);
	
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+14);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto2,0,'L');
		
			$pdf->SetY($Y_Table_Position+21);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto3,0,'L');
			
			
			$Y_Table_Position = $Y_Table_Position + 21;
		}
		elseif (strcmp($texto2, "")!=0) {

			$pdf->SetY($Y_Table_Position);
			$pdf->SetX(4);
			$pdf->Cell(16,7,$code,0,'C');
		
			$pdf->SetX(4);
			$pdf->Cell(16,21,'',1);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto,0,'L');
				
			$pdf->SetX(20);
			$pdf->Cell(150,21,'',1);
				
			$pdf->SetX(170);
			$pdf->Cell(34,21,'',1);
			$pdf->SetY($Y_Table_Position+7);
		
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+14);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto2,0,'L');
						
			$Y_Table_Position = $Y_Table_Position + 14;
		}
		elseif (strcmp($texto1, "")!=0) {

			$pdf->SetY($Y_Table_Position);
			$pdf->SetX(4);
			$pdf->Cell(16,7,$code,0,'C');
		
			$pdf->SetX(4);
			$pdf->Cell(16,14,'',1);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto,0,'L');
					
			$pdf->SetX(20);
			$pdf->Cell(150,14,'',1);
				
			$pdf->SetX(170);
			$pdf->Cell(34,14,'',1);
			$pdf->SetY($Y_Table_Position+7);
		
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto1,0,'L');
								
			$Y_Table_Position = $Y_Table_Position + 7;
		} else  {

			$pdf->SetY($Y_Table_Position);
			$pdf->SetX(4);
			$pdf->Cell(16,7,$code,0,'C');
		
			$pdf->SetX(4);
			$pdf->Cell(16,7,'',1);
			$pdf->SetX(20);
			$pdf->Cell(150,7,$texto,0,'L');
		
			$pdf->SetX(20);
			$pdf->Cell(150,7,'',1);
		
			$pdf->SetX(170);
			$pdf->Cell(34,7,'',1);
			
		}
		$pdf->SetY($Y_Table_Position);

	//}
	
	$j = $j +1;
	$Y_Table_Position = $Y_Table_Position + 7;
	if ($j >=25 || $Y_Table_Position >= 270) {
		// ACA VA EL PIE
		//Posici�n: a 1,5 cm del final
   		$pdf->SetY(-15);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		//N�mero de p�gina
   		$pdf->Cell(0,10,'P�gina '.$pdf->PageNo().'/'.$hojas,0,0,'C');
		$pdf->AddPage();
		// LUEGO LA CABECERA DE NUEVO
		//Fields Name position
$Y_Fields_Name_position = 50;
	
//Table position, under Fields Name
$Y_Table_Position = 60;
	
//$pdf->AddPage();
$pdf->Image('amercado.jpg',1,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',14);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->Image('catalogo_remate.jpg',68,8,50);
//$pdf->Cell(70,10,'CATALOGO DE REMATE',1,0,'C');
$pdf->SetFont('Arial','B',8);
//$pdf->Ln(1);
//$pdf->SetY(25);
//$pdf->Cell(1);
//$pdf->SetX(5);
$pdf->SetY(9);
$pdf->SetX(137);
$pdf->Cell(80,10,'Av. A. Moreau de Justo 740, Piso 4, Of. 19 � C.A.B.A.',0,0,'L');
	
$pdf->SetY(12);
//$pdf->Cell(1);
$pdf->SetX(137);
$pdf->Cell(80,10,'Tel/Fax: (+54) 11 4343-9893 ',0,0,'L');
	
$pdf->SetY(22);
$pdf->SetX(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,10,'FECHA          :  ',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10);
$pdf->Cell(25,10,$fecha.'    HORA : '.$hora,0,0,'L');
	
$pdf->SetY(27);
$pdf->SetX(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,10,'SUBASTA     :  ',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10);
$pdf->Cell(120,10,$direc." , ".$localid." , ".$provin,0,0,'L');
$pdf->SetX(90);

	

if ($hay_exhib) {
	$pdf->SetFont('Arial','B',14);
	$pdf->SetY(32);
	$pdf->SetX(1);
	$pdf->Cell(25,10,'EXHIBICION :  ',0,0,'L');
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(10);
	$pdf->Cell(120,10,$dir_direccion." , ".$localid_ex." , ".$provin_ex,0,0,'L');
	$pdf->SetX(90);
	$pdf->SetY(32);
	
}
else 
	$pdf->SetY(27);


		if ($sello=='1') {
			$pdf->SetX(120);
			$pdf->SetY(38);
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(55);
			$pdf->Cell(70,10,'SUJETO A APROBACION',1,0,'C');
		}

	    $pdf->Ln(35);


		$pdf->SetFillColor(232,232,232);
		//Bold Font for Field Name
		$pdf->SetFont('Arial','B',14);
		$pdf->SetY($Y_Fields_Name_position);
		$pdf->SetX(4);
		$pdf->Cell(16,10,'LOTE',1,0,'C',1);
		$pdf->SetX(20);
		$pdf->Cell(150,10,'DESCRIPCION',1,0,'C',1);
		$pdf->SetX(170);
		$pdf->Cell(34,10,'OBS.',1,0,'C',1);
		$pdf->Ln();
		$j = 0;
		//Fields Name position
		$Y_Fields_Name_position = 50;
	
		//Table position, under Fields Name
		$Y_Table_Position = 60;
		
	}
	
}
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
