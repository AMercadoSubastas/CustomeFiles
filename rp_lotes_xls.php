<?php
set_time_limit(0); // Para evitar el timeout

setlocale (LC_TIME,"es_ES.UTF-8");

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$remate    	 = $_POST['remate_num'];


$fechahoy = date("d-m-Y");
// ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL TXT =========================
$csv_end = "  
";  
$csv_sep = ";";  

$csv_file = "remate_".$remate.".txt";  

$csv="";  



// Leo la cabecera

$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum, codintsublote", $remate);

$lotescsv = mysqli_query($amercado, $query_lotes) or die("NO PUEDO LEER LOS LOTES");


// ACA ARMO EL RENGLON DE TITULOS : ===============================================
$titulo1 = utf8_encode("C�digo de Lote");
$titulo2 = utf8_encode("Descripci�n");
$csv.=$titulo1.$csv_sep.$titulo2.$csv_end; 
  

// Datos de los renglones
$i = 0;

while($row_lotescsv = mysqli_fetch_array($lotescsv))
{	
	$codintlote     = $row_lotescsv["codintlote"];
	$descripcion    = $row_lotescsv["descripcion"];
	$descripcion     = utf8_encode($descripcion);		  	
	// Imprimo los renglones DESDE ACA ARMO EL TXT
				
	$csv.=$codintlote.$csv_sep.$descripcion.$csv_end;
	
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

$file = $csv_file;
header("Content-disposition: attachment; filename=$file");
header("Content-type: application/octet-stream");
readfile($file);

if (!isset($file) || empty($file)) {
 exit();
}
$root = "C:\\LOTES WEB";
$file = basename($file);
$path = $root.$file;
$type = '';
 
if (is_file($path)) {
 $size = filesize($path);
 if (function_exists('mime_content_type')) {
 $type = mime_content_type($path);
 } else if (function_exists('finfo_file')) {
 $info = finfo_open(FILEINFO_MIME);
 $type = finfo_file($info, $path);
 finfo_close($info);
 }
 if ($type == '') {
 $type = "application/force-download";
 }
 // Define los headers
 header("Content-Type: $type");
 header("Content-Disposition: attachment; filename=$file");
 header("Content-Transfer-Encoding: binary");
 header("Content-Length: " . $size);
 // Descargar el archivo
 readfile($path);
} else {
 //die("El archivo no existe.");
}
 

?>  
