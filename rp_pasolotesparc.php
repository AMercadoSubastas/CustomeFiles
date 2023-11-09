<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');
header('Content-Type: text/html; charset=utf-8');
setlocale (LC_TIME,"spanish");

//Connect to your database
require_once('Connections/amercado.php');
$plotes[] = Array();
mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_GET['remate_num'];

// ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL CSV =========================
$csv_end = "";  
$csv_sep = "|";  
$csv_file = "\LOTES WEB\LOTES".$colname_remate.".txt";  
$csv=""; 
$j = 0;
foreach ($_GET['lotes'] as $todas){

	$plotes[$j] = $todas;
	$j++;
}	

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

//echo "======COLNAME_REMATE =  ".$colname_remate."   ===  "." PRIMER LOTE =  "."'".$plotes[0]."'"."    ";
$paso = 0;
for ($i=0;$i<$j;$i++) {
	$lote = "'".$plotes[$i]."'";
	$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s ", $colname_remate, $lote);
	//echo "======= QUERY LOTES ===  ".$query_lotes."    ======";
	$lotes = mysqli_query($amercado, $query_lotes) or die("NO PUEDO LEER LOS LOTES");
	//$totalRows_lotes = mysqli_num_rows($lotes);
	$row_lotes = mysqli_fetch_assoc($lotes);
	
	$texto = "";    
	
	$subasta_id = $colname_remate;
	$code = $row_lotes["codintnum"];
	$sublote = $row_lotes["codintsublote"];
	$titulo = "LOTE ".$code.$sublote ;
	$descripcion_completa = $row_lotes["descripcion"];
	$descripcion_completa = utf8_decode($descripcion_completa);
	$descripcion_breve = $row_lotes["descor"];
	$descripcion_breve = utf8_decode($descripcion_breve);
	$mapa_id = 0;
	$galeria_id = 0;
	$orden = $i+1;

	// Imprimo los renglones DESDE ACA ARMO EL TXT
	$csv.="".$csv_sep.$subasta_id.$csv_sep.$titulo.$csv_sep.$descripcion_breve.$csv_sep.$descripcion_completa.$csv_sep.$mapa_id.$csv_sep.$galeria_id.$csv_sep.$orden.$csv_end;
	$paso = 1;
}

mysqli_close($amercado);
if ($paso==1) {
	// ACA GRABO EL ARCHIVO TXT ====================================================
	if (!$handle = fopen($csv_file, "w")) {  
		echo "No se puede abrir el archivo";  
		exit;  
	}  
	if (fwrite($handle, utf8_decode($csv)) === FALSE) {  
		echo "No se puede grabar el archivo";  
		exit;  
	}  
	fclose($handle);  
	echo "<BR>"."<BR>"."<BR>"."<BR>"."<BR>"."<BR>"."<BR>";
	echo "<BR>"."======= SE GENERO CORRECTAMENTE EL ARCHIVO DE LOTES DEL REMATE ".$colname_remate."   ".$csv_file."======="."<BR>";
	$archivo = "LOTES".$colname_remate.".txt";
	$pagina = "http://192.168.1.133/subastas11W/pasolotesweb2.php?archivo=";
	$pagina .= $archivo;
	header("Location:".$pagina);

}
else {
	echo "<BR>"."<BR>"."<BR>"."<BR>"."<BR>"."<BR>"."<BR>";
	echo "<BR>"."======= NO SE SELECCIONARON LOTES PARA EL REMATE ".$colname_remate."  ======="."<BR>";  
}
?>