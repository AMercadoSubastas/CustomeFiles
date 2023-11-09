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
$triplicado = $_POST['GrupoOpciones1'];
$gsadmin    = $_POST['GrupoOpciones2'];
$senia      = $_POST['GrupoOpciones3'];
$muestro_lotes  = $_POST['GrupoOpciones4'];

//echo "REMATE:   ".$colname_remate."   TRIPLICADO =  ".$triplicado."  GASTOS : ".$gsadmin." ===== ";


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

// LEO LA TERCER DIRECCION
$query_dir_remates3 = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 3);
$dir_remates3 = mysqli_query($amercado, $query_dir_remates3) or die(mysqli_error($amercado));
$row_dir_remates3 = mysqli_fetch_assoc($dir_remates3);
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

if (mysqli_num_rows($dir_remates3) > 0 ) {
	$hay_exhib = 3;
}


$query_lotes = sprintf("SELECT * FROM lotes WHERE 'codrem' = %s ORDER BY 'codintnum', 'codintsublote'", $colname_remate);
//echo "REMATE =  ".$colname_remate."   LOTE DESDE =  ".$lote_desde."    LOTE HASTA;  ".$lote_hasta."   ";
//echo "query_lotes =  ".$query_lotes." =======";
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
$totalRows_lotes = mysqli_num_rows($lotes);
$renglones = 0;
while($row_lotes = mysqli_fetch_array($lotes)) {
  	// desde aca 
	$code    = "";
	$texto   = ""; $texto2  = ""; $texto3  = ""; $texto4  = "";	$texto5  = ""; $texto6  = "";
	$texto7  = ""; $texto8  = ""; $texto9  = ""; $texto10 = "";	$texto11 = ""; $texto12 = "";
	$texto13 = ""; $texto14 = ""; $texto15 = ""; $texto16 = ""; $texto17 = ""; $texto18 = "";
	$texto19 = ""; $texto20 = ""; $texto21 = ""; $texto22 = ""; 
	
	$sublote   = $row_lotes["codintsublote"];
	$code      = $code.$sublote ;
	$texto     = $row_lotes["descripcion"];
	$tamano    = 78; // 55; // tama�o m�ximo renglon
    $contador  = 0; $contador1 = 0; $contador2 = 0; $contador3 = 0; $contador4 = 0; $contador5 = 0; 
    $contador6 = 0; $contador7 = 0; $contador8 = 0; $contador9 = 0;	$contador10 = 0; $contador11 = 0;
	$contador12 = 0; $contador13 = 0; $contador14 = 0; $contador15 = 0; $contador16 = 0;
	$contador17 = 0; $contador18 = 0; $contador19 = 0; $contador20 = 0; $contador21 = 0; $contador22 = 0;

    $texto = strtoupper($texto);
    $texto_orig= $texto ;
    $largo_orig = strlen($texto_orig);
	/*
	if ($row_lotes["codintlote"] == 30)
		echo " TEXTO:  ".$texto_orig." ===LARGO ORIG: ".$largo_orig."     ";
	*/
	// Cortamos la cadena por los espacios 
   	$arrayTexto =explode(' ',$texto); 
	$texto = ""; 

	// Reconstruimos el primer renglon
	while(isset($arrayTexto[$contador]) && $tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
   		$texto .= ' '.$arrayTexto[$contador]; 
   		$contador++; 
	} 
	$largo_primer_renglon = strlen($texto)."<br>"; 
	// Aca empieza un renglon
	$texto1 = substr($texto_orig,strlen($texto)) ;
	$arrayTexto1 =explode(' ',$texto1); 
	$texto1 = ""; 
	// Reconstruimos el segundo renglon
	while(isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1]) && strlen($arrayTexto1[$contador1])!=0){
		$texto1 .= ' '.$arrayTexto1[$contador1]; 
		$contador1++; 
	}
	$largo_segundo_renglon = strlen($texto1);

	$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
	$arrayTexto2 =explode(' ',$texto2); 
	$texto2 = ""; 

	// Reconstruimos el tercer renglon
	while(isset($arrayTexto2[$contador2]) && $tamano >= strlen($texto2) + strlen($arrayTexto2[$contador2])&& strlen($arrayTexto2[$contador2])!=0){ 
		$texto2 .= ' '.$arrayTexto2[$contador2]; 
		$contador2++; 
	}
	$largo_tercer_renglon = strlen($texto2);
	$largo_tercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);
	$texto3 = substr($texto_orig,$largo_tercer) ;
	$arrayTexto3 =explode(' ',$texto3); 
	$texto3 = ""; 

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
	$texto4 = ""; 
	while(isset($arrayTexto4[$contador4]) && $tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& strlen($arrayTexto4[$contador4])!=0){ 
		$texto4 .= ' '.$arrayTexto4[$contador4]; 
		$contador4++; 
	}
	$largo_quinto_renglon = strlen($texto4);
	$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);

	//==============================================================================================
	$texto5 = substr($texto_orig,$largo_quinto) ;
	$arrayTexto5 =explode(' ',$texto5); 
	$texto5 = ""; 
	while(isset($arrayTexto5[$contador5]) && $tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5])&& strlen($arrayTexto5[$contador5])!=0){ 
		$texto5 .= ' '.$arrayTexto5[$contador5]; 
		$contador5++; 
	}
	$largo_sexto_renglon = strlen($texto5);
	$largo_sexto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);

	// ============================================================================================
	$texto6 = substr($texto_orig,$largo_sexto) ;
	$arrayTexto6 =explode(' ',$texto6); 
	$texto6 = ""; 
	while(isset($arrayTexto6[$contador6]) && $tamano >= strlen($texto6) + strlen($arrayTexto6[$contador6])&& strlen($arrayTexto6[$contador6])!=0){ 
		$texto6 .= ' '.$arrayTexto6[$contador6]; 
		$contador6++; 
	}
	$largo_septimo_renglon = strlen($texto6);
	$largo_septimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon);

	// =============================================================================================
	$texto7 = substr($texto_orig,$largo_septimo) ;
	$arrayTexto7 =explode(' ',$texto7); 
	$texto7 = ""; 
	while(isset($arrayTexto7[$contador7]) && $tamano >= strlen($texto7) + strlen($arrayTexto7[$contador7])&& strlen($arrayTexto7[$contador7])!=0){ 
		$texto7 .= ' '.$arrayTexto7[$contador7]; 
		$contador7++; 
	}
	$largo_octavo_renglon = strlen($texto7);
	$largo_octavo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon);

	// ============================================================================================
	$texto8 = substr($texto_orig,$largo_octavo) ;
	$arrayTexto8 =explode(' ',$texto8); 
	$texto8 = ""; 
	while(isset($arrayTexto8[$contador8]) && $tamano >= strlen($texto8) + strlen($arrayTexto8[$contador8])&& strlen($arrayTexto8[$contador8])!=0){ 
		$texto8 .= ' '.$arrayTexto8[$contador8]; 
		$contador8++; 
	}
	$largo_noveno_renglon = strlen($texto8);
	$largo_noveno = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon);

	// ===========================================================================================

	$texto9 = substr($texto_orig,$largo_noveno) ;
	$arrayTexto9 =explode(' ',$texto9); 
	$texto9 = ""; 
	while(isset($arrayTexto9[$contador9]) && $tamano >= strlen($texto9) + strlen($arrayTexto9[$contador9])&& strlen($arrayTexto9[$contador9])!=0){ 
		$texto9 .= ' '.$arrayTexto9[$contador9]; 
		$contador9++; 
	}
	$largo_decimo_renglon = strlen($texto9);
	$largo_decimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon);

	// ==============================================================================================

	$texto10 = substr($texto_orig,$largo_decimo) ;
	$arrayTexto10 =explode(' ',$texto10); 
	$texto10 = ""; 
	while(isset($arrayTexto10[$contador10]) && $tamano >= strlen($texto10) + strlen($arrayTexto10[$contador10])&& strlen($arrayTexto10[$contador10])!=0){ 
		$texto10 .= ' '.$arrayTexto10[$contador10]; 
		$contador10++; 
	}
	$largo_undecimo_renglon = strlen($texto10);
	$largo_undecimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon);

	// ============================================================================================

	$texto11 = substr($texto_orig,$largo_undecimo) ;
	$arrayTexto11 =explode(' ',$texto11); 
	$texto11 = ""; 
	while(isset($arrayTexto11[$contador11]) && $tamano >= strlen($texto11) + strlen($arrayTexto11[$contador11])&& strlen($arrayTexto11[$contador11])!=0){ 
		$texto11 .= ' '.$arrayTexto11[$contador11]; 
		$contador11++; 
	}
	$largo_duodecimo_renglon = strlen($texto11);
	$largo_duodecimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon);

	// ============================================================================================

	$texto12 = substr($texto_orig,$largo_duodecimo) ;
	$arrayTexto12 =explode(' ',$texto12); 
	$texto12 =""; 
	while(isset($arrayTexto12[$contador12]) && $tamano >= strlen($texto12) + strlen($arrayTexto12[$contador12])&& strlen($arrayTexto12[$contador12])!=0){ 
		$texto12 .= ' '.$arrayTexto12[$contador12]; 
		$contador12++; 
	}
	$largo_decimotercer_renglon = strlen($texto12);
	$largo_decimotercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon);

	// ============================================================================================

	$texto13 = substr($texto_orig,$largo_decimotercer) ;
	$arrayTexto13 =explode(' ',$texto13); 
	$texto13 = ""; 
	while(isset($arrayTexto13[$contador13]) && $tamano >= strlen($texto13) + strlen($arrayTexto13[$contador13])&& strlen($arrayTexto13[$contador13])!=0){ 
		$texto13 .= ' '.$arrayTexto13[$contador13]; 
		$contador13++; 
	}
	$largo_decimocuarto_renglon = strlen($texto13);
	$largo_decimocuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon);

	// ============================================================================================
	$texto14 = substr($texto_orig,$largo_decimocuarto) ;
	$arrayTexto14 =explode(' ',$texto14); 
	$texto14 = ""; 
	while(isset($arrayTexto14[$contador14]) && $tamano >= strlen($texto14) + strlen($arrayTexto14[$contador14])&& strlen($arrayTexto14[$contador14])!=0){ 
		$texto14 .= ' '.$arrayTexto14[$contador14]; 
		$contador14++; 
	}
	$largo_decimoquinto_renglon = strlen($texto14);
	$largo_decimoquinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon);

	// ============================================================================================
	$texto15 = substr($texto_orig,$largo_decimoquinto) ;
	$arrayTexto15 =explode(' ',$texto15); 
	$texto15 = ""; 
	while(isset($arrayTexto15[$contador15]) && $tamano >= strlen($texto15) + strlen($arrayTexto15[$contador15])&& strlen($arrayTexto15[$contador15])!=0){ 
		$texto15 .= ' '.$arrayTexto15[$contador15]; 
		$contador15++; 
	}
	$largo_decimosexto_renglon = strlen($texto15);
	$largo_decimosexto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon);

	// ============================================================================================
	$texto16 = substr($texto_orig,$largo_decimosexto) ;
	$arrayTexto16 =explode(' ',$texto16); 
	$texto16 = ""; 
	while(isset($arrayTexto16[$contador16]) && $tamano >= strlen($texto16) + strlen($arrayTexto16[$contador16])&& strlen($arrayTexto16[$contador16])!=0){ 
		$texto16 .= ' '.$arrayTexto16[$contador16]; 
		$contador16++; 
	}
	$largo_decimoseptimo_renglon = strlen($texto16);
	$largo_decimoseptimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon);

	// ============================================================================================

	$texto17 = substr($texto_orig,$largo_decimoseptimo) ;
	$arrayTexto17 =explode(' ',$texto17); 
	$texto17 = ""; 
	while(isset($arrayTexto17[$contador17]) && $tamano >= strlen($texto17) + strlen($arrayTexto17[$contador17])&& strlen($arrayTexto17[$contador17])!=0){ 
		$texto17 .= ' '.$arrayTexto17[$contador17]; 
		$contador17++; 
	}
	$largo_decimooctavo_renglon = strlen($texto17);
	$largo_decimooctavo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon);

	// ============================================================================================

	$texto18 = substr($texto_orig,$largo_decimooctavo) ;
	$arrayTexto18 =explode(' ',$texto18); 
	$texto18 = ""; 
	while(isset($arrayTexto18[$contador18]) && $tamano >= strlen($texto18) + strlen($arrayTexto18[$contador18])&& strlen($arrayTexto18[$contador18])!=0){ 
		$texto18 .= ' '.$arrayTexto18[$contador18]; 
		$contador18++; 
	}
	$largo_decimonoveno_renglon = strlen($texto18);
	$largo_decimonoveno = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon+$largo_decimonoveno_renglon);

	// ============================================================================================

	$texto19 = substr($texto_orig,$largo_decimonoveno) ;
	$arrayTexto19 =explode(' ',$texto19); 
	$texto19 = ""; 
	while(isset($arrayTexto19[$contador19]) && $tamano >= strlen($texto19) + strlen($arrayTexto19[$contador19])&& strlen($arrayTexto19[$contador19])!=0){ 
		$texto19 .= ' '.$arrayTexto19[$contador19]; 
		$contador19++; 
	}
	$largo_vigesimo_renglon = strlen($texto19);
	$largo_vigesimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon+$largo_decimonoveno_renglon+$largo_vigesimo_renglon);

	// ============================================================================================

	$texto20 = substr($texto_orig,$largo_vigesimo) ;
	$arrayTexto20 =explode(' ',$texto20); 
	$texto20 = ""; 
	while(isset($arrayTexto20[$contador20]) && $tamano >= strlen($texto20) + strlen($arrayTexto20[$contador20])&& strlen($arrayTexto20[$contador20])!=0){ 
		$texto20 .= ' '.$arrayTexto20[$contador20]; 
		$contador20++; 
	}
	$largo_vigesimoprimer_renglon = strlen($texto20);
	$largo_vigesimoprimer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon+$largo_decimonoveno_renglon+$largo_vigesimo_renglon+$largo_vigesimoprimer_renglon);

	// ============================================================================================
	
	$texto21 = substr($texto_orig,$largo_vigesimoprimer) ;
	$arrayTexto21 =explode(' ',$texto21); 
	$texto21 = ""; 
	while(isset($arrayTexto21[$contador21]) && $tamano >= strlen($texto21) + strlen($arrayTexto21[$contador21])&& strlen($arrayTexto21[$contador21])!=0){ 
		$texto21 .= ' '.$arrayTexto21[$contador21]; 
		$contador21++; 
	}
	$largo_vigesimosegundo_renglon = strlen($texto21);
	$largo_vigesimosegundo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon+$largo_decimonoveno_renglon+$largo_vigesimo_renglon+$largo_vigesimoprimer_renglon+$largo_vigesimosegundo_renglon);

	// ============================================================================================
	
	$texto22 = substr($texto_orig,$largo_vigesimosegundo) ;
	$arrayTexto22 =explode(' ',$texto22); 
	$texto22 = ""; 
	while(isset($arrayTexto22[$contador22]) && $tamano >= strlen($texto22) + strlen($arrayTexto22[$contador22])&& strlen($arrayTexto22[$contador22])!=0){ 
		$texto22 .= ' '.$arrayTexto22[$contador22]; 
		$contador22++; 
	}
	
		
	
	
	
	
}
//$hojas = CantHojas($colname_remate);

//echo "    HOJAS = ".$hojas."   RENGLONES = ".$renglones."     ";
$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s  ORDER BY codintnum,codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
$totalRows_lotes = mysqli_num_rows($lotes);

$pdf=new FPDF();
$pdf->SetAutoPageBreak(0, 5) ;


$j = 0;
while($row_lotes = mysqli_fetch_array($lotes)) {
if ($triplicado==0)
	$k = 2;
else
	$k = 3;
for ($i=0;$i < $k; $i++) {	 // FOR PARA ORIGINAL, DUPLICADO Y/O TRIPLICADO
	$j = 0; // Me dar� el total de renglones
	$hojas_impresas = 0;
	//Create a new PDF file

	$pdf->AddPage();
	$hojas_impresas = $hojas_impresas + 1;

if ($hay_exhib == 0) {
	//Fields Name position
	$Y_Fields_Name_position = 50;

	//Table position, under Fields Name
	$Y_Table_Position = 45;
}

if ($hay_exhib == 1 ) {
	//Fields Name position
	$Y_Fields_Name_position = 50;

	//Table position, under Fields Name
	$Y_Table_Position = 45;
}
if ($hay_exhib == 2 ) {
	//Fields Name position
	$Y_Fields_Name_position = 50;

	//Table position, under Fields Name
	$Y_Table_Position = 50;
}
if ($hay_exhib == 3 ) {
	//Fields Name position
	$Y_Fields_Name_position = 50;

	//Table position, under Fields Name
	$Y_Table_Position = 45;
}


/*
//=========================== CABECERA =============================================
$pdf->Image('phpimages/logo_boletase.png',40,10,120);

$pdf->SetFont('Arial','B',10);
$pdf->SetY(26);
$pdf->SetX(8);
$pdf->Cell(24,10, 'Por c/o de:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(180,10, ' '.substr($obs,0,90),0,0,'L');
//=============================================================================
if ( strcmp(substr($hora,3,2) , "00") == 0)
	$hora = substr($hora,0,2);
$dia = ucwords(strftime("%A", strtotime($fecha)));
$dianum = strftime("%d", strtotime($fecha));
if ($dianum[0]==0)
	$dianum = $dianum[1];
$mes = ucwords(strftime("%B", strtotime($fecha)));
$pdf->SetY(30);
$pdf->SetX(8);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(24,10, 'Fecha y Hora:',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(180,10, ' '.$dia.' '.$dianum.' de '.$mes.' de '.strftime("%Y", strtotime($fecha)).' - '.$hora.' hs.',0,0,'L');
*/
//=========================== CABECERA =============================================
$pdf->Image('images/logo_adrianC.jpg',1,8,65);
//Arial bold 14
$pdf->SetFont('Arial','B',14);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->SetY(7);
$pdf->SetX(80);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(50,10,'BOLETA DE SE�A',1,0,'C');

$pdf->SetFont('Arial','B',8);
$pdf->SetY(6);
$pdf->SetX(132);
$pdf->Cell(80,10,'                Olga Cossettini 731, Piso 3 - CABA',0,0,'L');
$pdf->SetY(10);
$pdf->SetX(132);
$pdf->Cell(80,10,'Tel.: (+54) 11 3984-7400 / www.grupoadrianmercado.com',0,0,'L');

$pdf->Line(2,18,208,18);
//========================== HASTA LA LINEA ==========================================

$pdf->SetFont('Arial','B',10);
$pdf->SetY(17);
$pdf->SetX(9);
$pdf->Cell(19,10, 'Por c/o de: ',0,0,'L');
$pdf->SetFont('Arial','',10);
$pdf->Cell(25,10, substr($obs,0,90),0,0,'L');
//=============================================================================
if ( strcmp(substr($hora,3,2) , "00") == 0)
	$hora = substr($hora,0,2);
$pdf->SetY(22);
$pdf->SetX(9);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(20,10,'Fecha y Hora: ',0,0,'L');
$pdf->SetFont('Arial','',10);
$dia = ucwords(strftime("%A", strtotime($fecha)));

$dianum = strftime("%d", strtotime($fecha));
if ($dianum[0]==0)
	$dianum = $dianum[1];
$mes = ucwords(strftime("%B", strtotime($fecha)));
$pdf->Cell(20,10, '    '.$dia.' '.$dianum.' de '.$mes.' de '.strftime("%Y", strtotime($fecha)).' - '.$hora.' hs.',0,0,'L');
//$pdf->Cell(20,10, '      '.$fecha_hoy.' - '.$hora.' hs.',0,0,'L');
//=============================================================================

//=============================================================================
if ($hay_exhib == 0) {
	$pdf->SetY(27);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,10,'Subasta:',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,' '.$direc.", ".$localid.", ".$provin,0,0,'L');
	$pdf->SetY(32);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,10,'Exhibici�n: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,'      '.$direc.", ".$localid.", ".$provin,0,0,'L');
}
//=============================================================================
if ($hay_exhib == 1) {
	$pdf->SetY(27);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,10,'Subasta:',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,' '.$direc.", ".$localid.", ".$provin,0,0,'L');
	
	$pdf->SetY(32);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(18,10,'Exhibici�n: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,'     '.$dir_direccion.", ".$localid_ex.", ".$provin_ex,0,0,'L');
}
if ($hay_exhib == 2) {
	$pdf->SetY(27);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,10,'Subasta:',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,' '.$direc.", ".$localid.", ".$provin,0,0,'L');
	
	$pdf->SetY(32);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(18,10,'Exhibici�n 1/2: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,'         '.$dir_direccion.", ".$localid_ex.", ".$provin_ex,0,0,'L');
	$pdf->SetY(37);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(18,10,'Exhibici�n 2/2: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,'         '.$dir_direccion2.", ".$localid_ex2.", ".$provin_ex2,0,0,'L');
	
}
if ($hay_exhib == 3) {
	$pdf->SetY(27);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,10,'Subasta:',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,' '.$direc.", ".$localid.", ".$provin,0,0,'L');
	$pdf->SetY(32);
	$pdf->SetX(9);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,10,'Exhibici�n: ',0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(180,10,'      '.'Ver en nuestro sitio web',0,0,'L');
}


if ($sello=='1') {
			$pdf->SetX(10);
			$pdf->SetFont('Arial','B',12);
			$pdf->SetY(140);
			$pdf->Cell(25);
			$pdf->Cell(140,7,'Venta sujeta a aprobaci�n por la empresa vendedora '.$plazo_sap,1,0,'C');
}

$pdf->Ln(55);


//=============================================================================
//Bold Font for Field Name

//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
// luego una nueva pagina y la cabecera

	$texto = "";    $texto2 = "";  $texto3 = "";  $texto4 = "";  $texto5 = "";  $texto6 = ""; 
	$texto7 = "";	$texto8 = "";  $texto9 = "";  $texto10 = ""; $texto11 = ""; $texto12 = ""; 
	$texto13 = "";  $texto14 = ""; $texto15 = ""; $texto16 = ""; $texto17 = ""; $texto18 = "";
	$texto19 = ""; $texto20 = ""; $texto21 = ""; $texto22 = "";
		
	$code = $row_lotes["codintnum"];
	$sublote = $row_lotes["codintsublote"];
	$code = $code.$sublote ;
	$texto = $row_lotes["descripcion"];
	$tamano = 83; //55; // tama�o m�ximo renglon
	$contador = 0; $contador1 = 0; $contador2 = 0; $contador3 = 0; $contador4 = 0; $contador5 = 0; 
	$contador6 = 0; $contador7 = 0; $contador8 = 0; $contador9 = 0; $contador10 = 0; $contador11 = 0; 
	$contador12 = 0; $contador13 = 0; $contador14 = 0; $contador15 = 0; $contador16 = 0; $contador17 = 0; 
	$contador18 = 0; $contador19 = 0; $contador20 = 0; $contador21 = 0; $contador22 = 0; 

	$texto = strtoupper($texto);
	$texto_orig= $texto ;
	$largo_orig = strlen($texto_orig);
/*
	if ($row_lotes["codintlote"] == 30)
		echo " TEXTO:  ".$texto_orig." ===LARGO ORIG: ".$largo_orig."     ";
*/	
	
	// Cortamos la cadena por los espacios 

	$arrayTexto =explode(' ',$texto); 
	$texto = ''; 

	// Reconstruimos el primer renglon ==============================================================
	while(isset($arrayTexto[$contador]) && ($tamano-8) >= strlen($texto) + strlen($arrayTexto[$contador])){ 
		$texto .= ' '.$arrayTexto[$contador]; 
		$contador++; 
	} 

	$largo_primer_renglon = strlen($texto)."<br>"; 
	$texto1 = substr($texto_orig,strlen($texto)) ;
	$arrayTexto1 =explode(' ',$texto1); 
	$texto1 = ''; 

	// Reconstruimos el segundo renglon ============================================================
	while(isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1])&& strlen($arrayTexto1[$contador1])!=0){ 
		$texto1 .= ' '.$arrayTexto1[$contador1]; 
		$contador1++; 
	}
	$largo_segundo_renglon = strlen($texto1);

	$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
	$arrayTexto2 =explode(' ',$texto2); 
	$texto2 = ''; 

	// Reconstruimos el tercer renglon ============================================================
	while(isset($arrayTexto2[$contador2]) && $tamano >= strlen($texto2) + strlen($arrayTexto2[$contador2])&& strlen($arrayTexto2[$contador2])!=0){ 
		$texto2 .= ' '.$arrayTexto2[$contador2]; 
		$contador2++; 
	}
	$largo_tercer_renglon = strlen($texto2);
	$largo_tercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);

	$texto3 = substr($texto_orig,$largo_tercer) ;
	$arrayTexto3 =explode(' ',$texto3); 
	$texto3 = ''; 

	// Reconstruimos el cuarto renglon ===========================================================
	while(isset($arrayTexto3[$contador3]) && $tamano >= strlen($texto3) + strlen($arrayTexto3[$contador3])&& strlen($arrayTexto3[$contador3])!=0){ 
		$texto3 .= ' '.$arrayTexto3[$contador3]; 
		$contador3++; 
	}
	$largo_cuarto_renglon = strlen($texto3);
	$largo_cuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon);
	$texto4 = substr($texto_orig,$largo_cuarto) ;
	$arrayTexto4 =explode(' ',$texto4); 
	$texto4 = ''; 

	// Reconstruimos el quinto renglon ==========================================================
	while(isset($arrayTexto4[$contador4]) && $tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& 	strlen($arrayTexto4[$contador4])!=0){ 
		$texto4 .= ' '.$arrayTexto4[$contador4]; 
		$contador4++; 
	}
	$largo_quinto_renglon = strlen($texto4);
	$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);

	$texto5 = substr($texto_orig,$largo_quinto) ;
	$arrayTexto5 =explode(' ',$texto5); 
	$texto5 = ''; 

	// Reconstruimos el sexto renglon ============================================================
	while(isset($arrayTexto5[$contador5]) && $tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5]) && strlen($arrayTexto5[$contador5])!=0){ 
		$texto5 .= ' '.$arrayTexto5[$contador5]; 
		$contador5++; 
	}
	$largo_sexto_renglon = strlen($texto5);
	$largo_sexto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);

	$texto6 = substr($texto_orig,$largo_sexto) ;
	$arrayTexto6 =explode(' ',$texto6); 
	$texto6 = ''; 

	// Reconstruimos el septimo renglon =====================================================
	while(isset($arrayTexto6[$contador6]) && $tamano >= strlen($texto6) + strlen($arrayTexto6[$contador6]) && strlen($arrayTexto6[$contador6])!=0){ 
		$texto6 .= ' '.$arrayTexto6[$contador6]; 
		$contador6++; 
	}
	$largo_septimo_renglon = strlen($texto6);
	$largo_septimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon);

	$texto7 = substr($texto_orig,$largo_septimo) ;
	$arrayTexto7 =explode(' ',$texto7); 
	$texto7 = ''; 

	// Reconstruimos el octavo renglon ============================================================
	while(isset($arrayTexto7[$contador7]) && $tamano >= strlen($texto7) + strlen($arrayTexto7[$contador7]) && strlen($arrayTexto7[$contador7])!=0){ 
		$texto7 .= ' '.$arrayTexto7[$contador7]; 
		$contador7++; 
	}
	$largo_octavo_renglon = strlen($texto7);
	$largo_octavo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon);

	$texto8 = substr($texto_orig,$largo_octavo) ;
	$arrayTexto8 =explode(' ',$texto8); 
	$texto8 = ''; 

	// Reconstruimos el noveno renglon ============================================================
	while(isset($arrayTexto8[$contador8]) && $tamano >= strlen($texto8) + strlen($arrayTexto8[$contador8]) && strlen($arrayTexto8[$contador8])!=0){ 
		$texto8 .= ' '.$arrayTexto8[$contador8]; 
		$contador8++; 
	}
	$largo_noveno_renglon = strlen($texto8);
	$largo_noveno = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon);

	$texto9 = substr($texto_orig,$largo_noveno) ;
	$arrayTexto9 =explode(' ',$texto9); 
	$texto9 = ''; 

	// Reconstruimos el decimo renglon =====================================================
	while(isset($arrayTexto9[$contador9]) && $tamano >= strlen($texto9) + strlen($arrayTexto9[$contador9]) && strlen($arrayTexto9[$contador9])!=0){ 
		$texto9 .= ' '.$arrayTexto9[$contador9]; 
		$contador9++; 
	}
	$largo_decimo_renglon = strlen($texto9);
	$largo_decimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon);

	$texto10 = substr($texto_orig,$largo_decimo) ;
	$arrayTexto10 =explode(' ',$texto10); 
	$texto10 = ''; 

	// Reconstruimos el undecimo renglon ====================================================
	while(isset($arrayTexto10[$contador10]) && $tamano >= strlen($texto10) + strlen($arrayTexto10[$contador10]) && strlen($arrayTexto10[$contador10])!=0){ 
		$texto10 .= ' '.$arrayTexto10[$contador10]; 
		$contador10++; 
	}
	$largo_undecimo_renglon = strlen($texto10);
	$largo_undecimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon);

	$texto11 = substr($texto_orig,$largo_undecimo) ;
	$arrayTexto11 =explode(' ',$texto11); 
	$texto11 = ''; 

	// Reconstruimos el duodecimo renglon =================================================
	while(isset($arrayTexto11[$contador11]) && $tamano >= strlen($texto11) + strlen($arrayTexto11[$contador11]) && strlen($arrayTexto11[$contador11])!=0){ 
		$texto11 .= ' '.$arrayTexto11[$contador11]; 
		$contador11++; 
	}
	$largo_duodecimo_renglon = strlen($texto11);
	$largo_duodecimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon);
	
	$texto12 = substr($texto_orig,$largo_duodecimo) ;
	$arrayTexto12 =explode(' ',$texto12); 
	$texto12 = ''; 
	
	// Reconstruimos el decimosegundo renglon =============================================
	while(isset($arrayTexto12[$contador12]) && $tamano >= strlen($texto12) + strlen($arrayTexto12[$contador12]) && strlen($arrayTexto12[$contador12])!=0){ 
		$texto12 .= ' '.$arrayTexto12[$contador12]; 
		$contador12++; 
	}
	$largo_decimotercer_renglon = strlen($texto12);
	$largo_decimotercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon);
	
	$texto13 = substr($texto_orig,$largo_decimotercer) ;
	$arrayTexto13 =explode(' ',$texto13); 
	$texto13 = ''; 
	
	// Reconstruimos el decimotercer renglon ==========================================
	while(isset($arrayTexto13[$contador13]) && $tamano >= strlen($texto13) + strlen($arrayTexto13[$contador13]) && strlen($arrayTexto13[$contador13])!=0){ 
		$texto13 .= ' '.$arrayTexto13[$contador13]; 
		$contador13++; 
	}
	$largo_decimocuarto_renglon = strlen($texto13);
	$largo_decimocuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon);
	
	$texto14 = substr($texto_orig,$largo_decimocuarto) ;
	$arrayTexto14 =explode(' ',$texto14); 
	$texto14 = ''; 
	
	// Reconstruimos el decimocuarto renglon ============================================
	while(isset($arrayTexto14[$contador14]) && $tamano >= strlen($texto14) + strlen($arrayTexto14[$contador14]) && strlen($arrayTexto14[$contador14])!=0){ 
		$texto14 .= ' '.$arrayTexto14[$contador14]; 
		$contador14++; 
	}
	$largo_decimoquinto_renglon = strlen($texto14);
	$largo_decimoquinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon);
	
	$texto15 = substr($texto_orig,$largo_decimoquinto) ;
	$arrayTexto15 =explode(' ',$texto15); 
	$texto15 = ''; 
	
	// ============================================================================================
	$texto15 = substr($texto_orig,$largo_decimoquinto) ;
	$arrayTexto15 =explode(' ',$texto15); 
	$texto15 = ""; 
	while(isset($arrayTexto15[$contador15]) && $tamano >= strlen($texto15) + strlen($arrayTexto15[$contador15])&& strlen($arrayTexto15[$contador15])!=0){ 
		$texto15 .= ' '.$arrayTexto15[$contador15]; 
		$contador15++; 
	}
	$largo_decimosexto_renglon = strlen($texto15);
	$largo_decimosexto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon);

	// ============================================================================================
	$texto16 = substr($texto_orig,$largo_decimosexto) ;
	$arrayTexto16 =explode(' ',$texto16); 
	$texto16 = ""; 
	while(isset($arrayTexto16[$contador16]) && $tamano >= strlen($texto16) + strlen($arrayTexto16[$contador16])&& strlen($arrayTexto16[$contador16])!=0){ 
		$texto16 .= ' '.$arrayTexto16[$contador16]; 
		$contador16++; 
	}
	$largo_decimoseptimo_renglon = strlen($texto16);
	$largo_decimoseptimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon);

	// ============================================================================================

	$texto17 = substr($texto_orig,$largo_decimoseptimo) ;
	$arrayTexto17 =explode(' ',$texto17); 
	$texto17 = ""; 
	while(isset($arrayTexto17[$contador17]) && $tamano >= strlen($texto17) + strlen($arrayTexto17[$contador17])&& strlen($arrayTexto17[$contador17])!=0){ 
		$texto17 .= ' '.$arrayTexto17[$contador17]; 
		$contador17++; 
	}
	$largo_decimooctavo_renglon = strlen($texto17);
	$largo_decimooctavo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon);

	// ============================================================================================

	$texto18 = substr($texto_orig,$largo_decimooctavo) ;
	$arrayTexto18 =explode(' ',$texto18); 
	$texto18 = ""; 
	while(isset($arrayTexto18[$contador18]) && $tamano >= strlen($texto18) + strlen($arrayTexto18[$contador18])&& strlen($arrayTexto18[$contador18])!=0){ 
		$texto18 .= ' '.$arrayTexto18[$contador18]; 
		$contador18++; 
	}
	$largo_decimonoveno_renglon = strlen($texto18);
	$largo_decimonoveno = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon+$largo_decimonoveno_renglon);

	// ============================================================================================

	$texto19 = substr($texto_orig,$largo_decimonoveno) ;
	$arrayTexto19 =explode(' ',$texto19); 
	$texto19 = ""; 
	while(isset($arrayTexto19[$contador19]) && $tamano >= strlen($texto19) + strlen($arrayTexto19[$contador19])&& strlen($arrayTexto19[$contador19])!=0){ 
		$texto19 .= ' '.$arrayTexto19[$contador19]; 
		$contador19++; 
	}
	$largo_vigesimo_renglon = strlen($texto19);
	$largo_vigesimo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon+$largo_decimonoveno_renglon+$largo_vigesimo_renglon);

	// ============================================================================================

	$texto20 = substr($texto_orig,$largo_vigesimo) ;
	$arrayTexto20 =explode(' ',$texto20); 
	$texto20 = ""; 
	while(isset($arrayTexto20[$contador20]) && $tamano >= strlen($texto20) + strlen($arrayTexto20[$contador20])&& strlen($arrayTexto20[$contador20])!=0){ 
		$texto20 .= ' '.$arrayTexto20[$contador20]; 
		$contador20++; 
	}
	$largo_vigesimoprimer_renglon = strlen($texto20);
	$largo_vigesimoprimer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon+$largo_decimonoveno_renglon+$largo_vigesimo_renglon+$largo_vigesimoprimer_renglon);

	// ============================================================================================
	
	$texto21 = substr($texto_orig,$largo_vigesimoprimer) ;
	$arrayTexto21 =explode(' ',$texto21); 
	$texto21 = ""; 
	while(isset($arrayTexto21[$contador21]) && $tamano >= strlen($texto21) + strlen($arrayTexto21[$contador21])&& strlen($arrayTexto21[$contador21])!=0){ 
		$texto21 .= ' '.$arrayTexto21[$contador21]; 
		$contador21++; 
	}
	$largo_vigesimosegundo_renglon = strlen($texto21);
	$largo_vigesimosegundo = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon+$largo_septimo_renglon+$largo_octavo_renglon+$largo_noveno_renglon+$largo_decimo_renglon+$largo_undecimo_renglon+$largo_duodecimo_renglon+$largo_decimotercer_renglon+$largo_decimocuarto_renglon+$largo_decimoquinto_renglon+$largo_decimosexto_renglon+$largo_decimoseptimo_renglon+$largo_decimooctavo_renglon+$largo_decimonoveno_renglon+$largo_vigesimo_renglon+$largo_vigesimoprimer_renglon+$largo_vigesimosegundo_renglon);

	// ============================================================================================
	
	$texto22 = substr($texto_orig,$largo_vigesimosegundo) ;
	$arrayTexto22 =explode(' ',$texto22); 
	$texto22 = ""; 
	while(isset($arrayTexto22[$contador22]) && $tamano >= strlen($texto22) + strlen($arrayTexto22[$contador22])&& strlen($arrayTexto22[$contador22])!=0){ 
		$texto22 .= ' '.$arrayTexto22[$contador22]; 
		$contador22++; 
	}
	
	$pdf->SetFont('Arial','B',11);
	$pdf->SetY($Y_Table_Position);
	
	if ($muestro_lotes == 1) {
	
	if (strcmp($texto22,"")!=0) {
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,138,'',0);
			
		$pdf->SetX(170);
		$pdf->Cell(34,138,'',0);
		$pdf->SetY($Y_Table_Position+6);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+18);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+24);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+30);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+36);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+42);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+48);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+54);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+60);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+66);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+72);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+78);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+84);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto14,0,'L');
		$pdf->SetY($Y_Table_Position+90);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto15,0,'L');
		$pdf->SetY($Y_Table_Position+96);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto16,0,'L');
		$pdf->SetY($Y_Table_Position+102);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto17,0,'L');
		$pdf->SetY($Y_Table_Position+108);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto18,0,'L');
		$pdf->SetY($Y_Table_Position+114);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto19,0,'L');
		$pdf->SetY($Y_Table_Position+120);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto20,0,'L');
		$pdf->SetY($Y_Table_Position+126);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto21,0,'L');
		$pdf->SetY($Y_Table_Position+132);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto22,0,'L');
		
		$Y_Table_Position = $Y_Table_Position + 132;
	
	}
	//=====================================================
	
	elseif (strcmp($texto21,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(9);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(8);
			else
				$pdf->SetX(7);
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE       ",0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_",0,'C');
			
		$pdf->SetX(4);
		$pdf->Cell(16,132,'',0);
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,132,'',0);
			
		$pdf->SetX(170);
		$pdf->Cell(34,132,'',0);
		$pdf->SetY($Y_Table_Position+6);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+18);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+24);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+30);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+36);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+42);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+48);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+54);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+60);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+66);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+72);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+78);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+84);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto14,0,'L');
		$pdf->SetY($Y_Table_Position+90);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto15,0,'L');
		$pdf->SetY($Y_Table_Position+96);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto16,0,'L');
		$pdf->SetY($Y_Table_Position+102);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto17,0,'L');
		$pdf->SetY($Y_Table_Position+108);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto18,0,'L');
		$pdf->SetY($Y_Table_Position+114);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto19,0,'L');
		$pdf->SetY($Y_Table_Position+120);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto20,0,'L');
		$pdf->SetY($Y_Table_Position+126);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto21,0,'L');
		
		$Y_Table_Position = $Y_Table_Position + 126;
	
	}
	//=====================================================
	elseif (strcmp($texto20,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(9);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(8);
			else
				$pdf->SetX(7);
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE       ",0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_",0,'C');
			
		$pdf->SetX(4);
		$pdf->Cell(16,126,'',0);
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,126,'',0);
			
		$pdf->SetX(170);
		$pdf->Cell(34,126,'',0);
		$pdf->SetY($Y_Table_Position+6);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+18);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+24);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+30);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+36);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+42);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+48);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+54);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+60);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+66);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+72);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+78);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+84);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto14,0,'L');
		$pdf->SetY($Y_Table_Position+90);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto15,0,'L');
		$pdf->SetY($Y_Table_Position+96);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto16,0,'L');
		$pdf->SetY($Y_Table_Position+102);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto17,0,'L');
		$pdf->SetY($Y_Table_Position+108);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto18,0,'L');
		$pdf->SetY($Y_Table_Position+114);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto19,0,'L');
		$pdf->SetY($Y_Table_Position+120);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto20,0,'L');
		
		$Y_Table_Position = $Y_Table_Position + 120;
	
	}
	//=====================================================
	elseif (strcmp($texto19,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(9);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(8);
			else
				$pdf->SetX(7);
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE       ",0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_",0,'C');
			
		$pdf->SetX(4);
		$pdf->Cell(16,120,'',0);
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,120,'',0);
			
		$pdf->SetX(170);
		$pdf->Cell(34,120,'',0);
		$pdf->SetY($Y_Table_Position+6);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+18);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+24);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+30);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+36);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+42);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+48);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+54);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+60);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+66);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+72);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+78);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+84);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto14,0,'L');
		$pdf->SetY($Y_Table_Position+90);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto15,0,'L');
		$pdf->SetY($Y_Table_Position+96);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto16,0,'L');
		$pdf->SetY($Y_Table_Position+102);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto17,0,'L');
		$pdf->SetY($Y_Table_Position+108);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto18,0,'L');
		$pdf->SetY($Y_Table_Position+114);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto19,0,'L');
		
		$Y_Table_Position = $Y_Table_Position + 114;
	
	}
	//=====================================================
	elseif (strcmp($texto18,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(9);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(8);
			else
				$pdf->SetX(7);
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE       ",0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_",0,'C');
			
		$pdf->SetX(4);
		$pdf->Cell(16,114,'',0);
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,114,'',0);
			
		$pdf->SetX(170);
		$pdf->Cell(34,114,'',0);
		$pdf->SetY($Y_Table_Position+6);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+18);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+24);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+30);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+36);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+42);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+48);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+54);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+60);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+66);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+72);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+78);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+84);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto14,0,'L');
		$pdf->SetY($Y_Table_Position+90);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto15,0,'L');
		$pdf->SetY($Y_Table_Position+96);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto16,0,'L');
		$pdf->SetY($Y_Table_Position+102);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto17,0,'L');
		$pdf->SetY($Y_Table_Position+108);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto18,0,'L');
		
		$Y_Table_Position = $Y_Table_Position + 108;
	
	}
	//=====================================================

	elseif (strcmp($texto17,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		if (strlen ( $code ) == 1)
			$pdf->SetX(9);
		else
			if (strlen ( $code ) == 2)
				$pdf->SetX(8);
			else
				$pdf->SetX(7);
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE       ",0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_",0,'C');
			
		$pdf->SetX(4);
		$pdf->Cell(16,108,'',0);
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto,0,'L');
			
		$pdf->SetX(20);
		$pdf->Cell(150,108,'',0);
			
		$pdf->SetX(170);
		$pdf->Cell(34,108,'',0);
		$pdf->SetY($Y_Table_Position+6);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+12);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+18);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+24);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+30);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+36);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+42);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+48);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+54);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+60);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+66);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+72);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+78);
	
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+84);
		
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto14,0,'L');
		$pdf->SetY($Y_Table_Position+90);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto15,0,'L');
		$pdf->SetY($Y_Table_Position+96);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto16,0,'L');
		$pdf->SetY($Y_Table_Position+102);

		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto17,0,'L');
		
		$Y_Table_Position = $Y_Table_Position + 102;
	
	}
	//=====================================================
	elseif (strcmp($texto16,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(8);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
		//$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
				
		$pdf->Cell(150,6,$texto,0,'L');
			
		$pdf->SetY($Y_Table_Position+5);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+45);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+50);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+55);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+60);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+65);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+70);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto14,0,'L');
		$pdf->SetY($Y_Table_Position+75);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto15,0,'L');
		$pdf->SetY($Y_Table_Position+80);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto16,0,'L');
			
		$Y_Table_Position = $Y_Table_Position + 80;
	
	}
	//=====================================================
	elseif (strcmp($texto15,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);

		$pdf->SetX(8);
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
			
		$pdf->SetY($Y_Table_Position+5);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+45);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+50);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+55);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+60);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+65);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+70);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto14,0,'L');
		$pdf->SetY($Y_Table_Position+75);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto15,0,'L');
			
		$Y_Table_Position = $Y_Table_Position + 75;
	
	}
	//=====================================================
	elseif (strcmp($texto14,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(8);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
		
		$pdf->SetY($Y_Table_Position+5);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+45);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+50);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+55);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+60);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+65);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto13,0,'L');
		$pdf->SetY($Y_Table_Position+70);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto14,0,'L');
		$Y_Table_Position = $Y_Table_Position + 70;
	
	}
	//=====================================================
	elseif (strcmp($texto13,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(8);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
		
		$pdf->SetY($Y_Table_Position+5);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
			
		$pdf->SetY($Y_Table_Position+10);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+45);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+50);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+55);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+60);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto12,0,'L');
		$pdf->SetY($Y_Table_Position+65);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto13,0,'L');
		$Y_Table_Position = $Y_Table_Position + 65;
	
	}
	elseif (strcmp($texto12,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(8);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
			
				
		$pdf->SetY($Y_Table_Position+5);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+45);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+50);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+55);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto11,0,'L');
		$pdf->SetY($Y_Table_Position+60);
			
		$pdf->SetX(20);
		$pdf->Cell(150,6,$texto12,0,'L');
		$Y_Table_Position = $Y_Table_Position + 60;
	}
	elseif (strcmp($texto11,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(8);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
	
		$pdf->SetY($Y_Table_Position+5);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
			
		$pdf->SetY($Y_Table_Position+10);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+45);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+50);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto10,0,'L');
		$pdf->SetY($Y_Table_Position+55);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto11,0,'L');
		$Y_Table_Position = $Y_Table_Position + 55;
	
	}
	elseif (strcmp($texto10,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
			
		$pdf->SetY($Y_Table_Position+5);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+45);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto9,0,'L');
		$pdf->SetY($Y_Table_Position+50);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto10,0,'L');
		$Y_Table_Position = $Y_Table_Position + 50;
	}
	elseif (strcmp($texto9,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
			
		$pdf->SetY($Y_Table_Position+5);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$pdf->SetY($Y_Table_Position+45);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto9,0,'L');
		$Y_Table_Position = $Y_Table_Position + 45;
	
	}
	elseif (strcmp($texto8,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
	
		$pdf->SetY($Y_Table_Position+5);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
			
		$pdf->SetY($Y_Table_Position+10);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
		$pdf->SetY($Y_Table_Position+40);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto8,0,'L');
		$Y_Table_Position = $Y_Table_Position + 40;
	}
	
	elseif (strcmp($texto7,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
			
		$pdf->SetY($Y_Table_Position+5);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
			
		$pdf->SetY($Y_Table_Position+10);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
		$pdf->SetY($Y_Table_Position+35);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto7,0,'L');
			
		$Y_Table_Position = $Y_Table_Position + 35;
	}
	
	elseif (strcmp($texto6,"")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
		
		$pdf->SetY($Y_Table_Position+5);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		$pdf->SetY($Y_Table_Position+15);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		$pdf->SetY($Y_Table_Position+20);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		$pdf->SetY($Y_Table_Position+25);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$pdf->SetY($Y_Table_Position+30);
	
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto6,0,'L');
	
	
		$Y_Table_Position = $Y_Table_Position + 30;
	}
	elseif (strcmp($texto5, "")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
		
		
		$pdf->SetY($Y_Table_Position+5);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
	
		$pdf->SetY($Y_Table_Position+15);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
		
		$pdf->SetY($Y_Table_Position+20);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
		
		$pdf->SetY($Y_Table_Position+25);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto5,0,'L');
		$Y_Table_Position = $Y_Table_Position + 25;
	}
	elseif (strcmp($texto4, "")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
		
		$pdf->SetY($Y_Table_Position+5);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
			
		$pdf->SetY($Y_Table_Position+10);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
				
		$pdf->SetY($Y_Table_Position+15);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
				
		$pdf->SetY($Y_Table_Position+20);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto4,0,'L');
			
				
		$Y_Table_Position = $Y_Table_Position + 20;
	}
	elseif (strcmp($texto3, "")!=0) {
	
		$pdf->SetY($Y_Table_Position);

		$pdf->SetX(9);
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
			
		$pdf->SetY($Y_Table_Position+5);
		
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+10);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
		
		$pdf->SetY($Y_Table_Position+15);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto3,0,'L');
				
				
		$Y_Table_Position = $Y_Table_Position + 15;
	}
	elseif (strcmp($texto2, "")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
			
		$pdf->SetY($Y_Table_Position+5);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
			
		$pdf->SetY($Y_Table_Position+10);
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto2,0,'L');
							
		$Y_Table_Position = $Y_Table_Position + 10;
	}
	elseif (strcmp($texto1, "")!=0) {
	
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(9);
		
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
		
		$pdf->SetY($Y_Table_Position+5);
			
		$pdf->SetX(8);
		$pdf->Cell(150,6,$texto1,0,'L');
									
		$Y_Table_Position = $Y_Table_Position + 5;
	} else  {
	
		$pdf->SetY($Y_Table_Position);
		
		$pdf->SetX(9);
		if ($code == 0)
			$pdf->Cell(16,6,"LOTE     ".$texto,0,'C');
		else
			$pdf->Cell(16,6,"LOTE ".$code."_ ".$texto,0,'C');
				
						
	}
	}
	$pdf->SetY($Y_Table_Position);
	$pdf->SetY(130);
		
	$pdf->SetX(70);
	$pdf->SetFont('Arial','U',16);
	//$pdf->Cell(16,6,"BOLETA DE SE�A",0,'C');
	$pdf->SetX(8);
	$pdf->SetY(160);
	$pdf->SetFont('Arial','I',11);
	 
	$pdf->Cell(16,6,"IMPORTE: $.........................................................             SE�A 20%: $........................................................",0,'C');

	$pdf->SetX(8);
	$pdf->SetY(170);
	if ($senia==0) {
		$pdf->Cell(16,6,"SE�A 32.10%: $..................................................             COM. 12.10%: $...................................................",0,'C');
		$pdf->SetX(10);
		$pdf->SetY(175);
		$pdf->SetFont('Arial','I',8);
		$pdf->Cell(16,6,"(Total)",0,'L');
		$pdf->SetFont('Arial','I',11);
	}
	else {
		if ($colname_remate==2070)
			$pdf->Cell(16,6,"SE�A 25%: $.....................................................               COM. 5%:  $......................................................",0,'C');
		else
			$pdf->Cell(16,6,"SE�A 30%: $.....................................................             COM. 10%: $......................................................",0,'C');

		$pdf->SetX(10);
		$pdf->SetY(175);
		$pdf->SetFont('Arial','I',8);
		$pdf->Cell(16,6,"(Total)",0,'L');
		$pdf->SetFont('Arial','I',11);
	}
	$pdf->SetX(8);
	$pdf->SetY(180);
	if($gsadmin == 1)
		$pdf->Cell(16,6,"GASTOS ADMIN.: $............................................             COMPRADOR: ......................................................",0,'C');
	else
		$pdf->Cell(16,6,"COMPRADOR: ......................................................",0,'C');
	
	$pdf->SetX(8);
	$pdf->SetY(190);
	
	$pdf->Cell(16,6,"BANCO: .......................................    CHEQUE N�: ......................................  PESOS: ...................................",0,'C');
	$pdf->SetX(8);
	$pdf->SetY(195);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(16,6,"(20%)",0,'C');
	$pdf->SetFont('Arial','I',11);
	$pdf->SetX(8);
	$pdf->SetY(200);
	$pdf->Cell(16,6,"BANCO: .......................................    CHEQUE N�: ......................................  PESOS: ...................................",0,'C');
	$pdf->SetX(8);
	$pdf->SetY(205);
	$pdf->SetFont('Arial','I',8);
	if ($senia == 0)
		$pdf->Cell(16,6,"(12.10%)",0,'C');
	else
		$pdf->Cell(16,6,"(10%)",0,'C');
	$pdf->SetFont('Arial','I',11);
	$pdf->SetX(8);
	$pdf->SetY(210);
	$pdf->Cell(16,6,"EFECTIVO: $...................................................",0,'C');
	
	$pdf->SetX(8);
	$pdf->SetY(222);
	$pdf->Line(10,222,193,222);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(16,6,"NOTIFICO QUE RECIB� CONFORME CONDICIONES GENERALES DE SUBASTA.",0,'C');
	$pdf->SetX(8);
	$pdf->SetY(243);
	$pdf->SetFont('Arial','I',10);
	$pdf->Cell(16,6,"FIRMA: .............................................................................             DNI:...........................................................................",0,'C');
	$pdf->SetX(8);
	$pdf->SetY(270);
	$pdf->Cell(16,6,"ACLARACION: ..................................................................            TEL.: ...........................................................................",0,'C');
	$pdf->SetX(98);
	$pdf->SetY(280);
	if ($i == 0)
		$pdf->Cell(16,6,"                                                                                ORIGINAL",0,'C');
	if ($i == 1)
		$pdf->Cell(16,6,"                                                                               DUPLICADO",0,'C');
	if ($i == 2)
		$pdf->Cell(16,6,"                                                                              TRIPLICADO",0,'C');

	$pdf->SetX(38);
	$pdf->SetY(285);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(16,6,"                                  Control Interno: C�d. RPG 07-02.2 / Versi�n: 0 / Revisi�n: 0 / Confecciona: Subastas / Aprueba: Direcci�n",0,'C');
	
	
	
	


	$texto = ""; $texto2 = ""; $texto3 = ""; $texto4 = ""; $texto5 = ""; $texto6 = "";	
	$texto7 = ""; $texto8 = "";	$texto9 = ""; $texto10 = ""; $texto11 = "";	$texto12 = "";	
	$texto13 = ""; $texto14 = ""; $texto15 = ""; $texto16 = "";	$texto17 = ""; $texto18 = ""; 
	$texto19 = ""; $texto20 = ""; $texto21 = ""; $texto22 = ""; 
		
	}
}

mysqli_close($amercado);
$pdf->Output();
?>