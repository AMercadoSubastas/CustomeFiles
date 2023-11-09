<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');
header('Content-Type: text/html; charset=utf-8');
setlocale (LC_TIME,"spanish");

//Connect to your database
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
//$colname_remate = $_POST['remate_num'];

$fecha_hoy = date("Y-m-d");
$fdesde = strtotime ( '-60 day' , strtotime ( $fecha_hoy ) ) ;
$fdesde = date ( 'Y-m-d' , $fdesde );
//echo " FDESDE 1:  ".$fdesde;
$fdesde = "2018-10-05";

//echo "FECHA_HOY = ".$fecha_hoy."  FDESDE = ".$fdesde."   ";


// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp > 2100 AND estado = 0 ORDER BY ncomp");
$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
while ($row_remates = mysqli_fetch_array($remates)) {
	// ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL CSV =========================
	$colname_remate = $row_remates["ncomp"];
	$csv_end = "";  
	$csv_sep = "|";  
	$csv_file = "\LOTES WEB\LOTES".$colname_remate.".txt";  
	$csv="";  
	
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


	$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
	$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
	$totalRows_lotes = mysqli_num_rows($lotes);
	$renglones = 0;


	$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", 			$colname_remate);
	$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
	$totalRows_lotes = mysqli_num_rows($lotes);
	$j = 1;

	while($row_lotes = mysqli_fetch_array($lotes)) {
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
		$orden = $j;
		$j++;
		// Imprimo los renglones DESDE ACA ARMO EL TXT
		$csv.="".$csv_sep.$subasta_id.$csv_sep.$titulo.$csv_sep.$descripcion_breve.$csv_sep.$descripcion_completa.$csv_sep.$mapa_id.$csv_sep.$galeria_id.$csv_sep.$orden.$csv_end;

	}

	mysqli_close($amercado);
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
	$archivo = "LOTES".$colname_remate.".txt";
	$pagina = "http://192.168.0.133/subastas11W/pasolotesweb2.php?archivo=";
	$pagina .= $archivo;
	header("Location:".$pagina);
}

?>