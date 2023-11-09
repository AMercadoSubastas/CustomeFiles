<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";
$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error("ERROR EN CONEXION"),E_USER_ERROR); 
mysqli_query($amercado,"SET NAMES latin1;" ) or die(mysqli_error($amercado));

define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');
header('Content-Type: text/html; charset=utf-8');
setlocale (LC_TIME,"spanish");

//Connect to your database
//require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];

// ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL CSV =========================
$csv_end = "";  
$csv_sep = "|";  
$csv_file = "\LOTES WEB\LOTES".$colname_remate.".txt";  
$csv="";  

// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
$remates = mysqli_query($amercado, $query_remate) or die("ERROR LEYENDO REMATES ".$query_remate);
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

$query_provincia = sprintf("SELECT * FROM provincias WHERE codpais = 1 AND codnum = %s", $cod_prov);
$provincia = mysqli_query($amercado, $query_provincia) or die("ERROR LEYENDO PROVINCIAS ".$query_provincia);
$row_provincia = mysqli_fetch_assoc($provincia);
$provin = $row_provincia["descripcion"];

$query_localidad = sprintf("SELECT * FROM localidades WHERE codnum = %s", $cod_loc);
$localidad = mysqli_query($amercado, $query_localidad) or die("ERROR LEYENDO LOCALIDADES ".$query_localidad);
$row_localidad = mysqli_fetch_assoc($localidad);
$localid = $row_localidad["descripcion"];
//LEO LA TABLA DIR_REMATES PARA VER SI TENGO DIRECCION DE EXPOSICION
$query_dir_remates = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 1);
$dir_remates = mysqli_query($amercado, $query_dir_remates) or die("ERROR LEYENDO DIR_REMATES ".$query_dir_remates);
$row_dir_remates = mysqli_fetch_assoc($dir_remates);
$dir_direccion = $row_dir_remates["direccion"];
$dir_codprov   = $row_dir_remates["codprov"];
$dir_codloc    = $row_dir_remates["codloc"];

// LEO LA SEGUNDA DIRECCION
$query_dir_remates2 = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 2);
$dir_remates2 = mysqli_query($amercado, $query_dir_remates2) or die("ERROR LEYENDO DIR_REMATES2  ".$query_dir_remates2);
$row_dir_remates2 = mysqli_fetch_assoc($dir_remates2);
$dir_direccion2 = $row_dir_remates2["direccion"];
$dir_codprov2   = $row_dir_remates2["codprov"];
$dir_codloc2    = $row_dir_remates2["codloc"];


$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado,$query_lotes) or die("ERROR LEYENDO LOTES ".$query_lotes);
$totalRows_lotes = mysqli_num_rows($lotes);
$renglones = 0;
   	
	
$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die("ERROR LEYENDO LOTES ".$query_lotes);
$totalRows_lotes = mysqli_num_rows($lotes);
$j = 1;

while($row_lotes = mysqli_fetch_array($lotes)) {
	$texto = "";    
	
	$subasta_id = $colname_remate;
	$code = $row_lotes["codintnum"];
	$sublote = $row_lotes["codintsublote"];
	$titulo = "LOTE ".$code.$sublote ;
	$descripcion_completa = $row_lotes["descripcion"];
	//$descripcion_completa = utf8_decode($descripcion_completa);
	$descripcion_breve = $row_lotes["descor"];
	//$descripcion_breve = utf8_decode($descripcion_breve);
	$mapa_id = 0;
	$galeria_id = 0;
	$orden = $j;
    $codintlote = $row_lotes["codintlote"];
	$j++;
	// Imprimo los renglones DESDE ACA ARMO EL TXT
	$csv.="".$csv_sep.$subasta_id.$csv_sep.$titulo.$csv_sep.$descripcion_breve.$csv_sep.$descripcion_completa.$csv_sep.$mapa_id.$csv_sep.$galeria_id.$csv_sep.$orden.$csv_sep.$codintlote.$csv_end;

}

mysqli_close($amercado);
// ACA GRABO EL ARCHIVO TXT ====================================================
if (!$handle = fopen($csv_file, "w")) {  
    echo "No se puede abrir el archivo";  
    exit;  
}  
//if (fwrite($handle, utf8_decode($csv)) === FALSE) { 
if (fwrite($handle, $csv) === FALSE) {
    echo "No se puede grabar el archivo";  
    exit;  
}  
fclose($handle);

$ch = curl_init( "https://admin.adrianmercado.com.ar/api/subastas/alta-masiva" );
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    array(
        'file' =>new CurlFile(realpath($csv_file))
    ));
//curl_setopt( $ch, CURLOPT_POSTFIELDS, $csv );

# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
if (curl_errno($ch)) {
   print_r(curl_error($ch));
} else {
   $result = "SE HAN ENVIADO LOS LOTES AL SITIO WEB..."; 
}
curl_close($ch);

print_r($result);
?>
