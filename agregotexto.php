<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout


setlocale (LC_TIME,"es_ES.UTF-8");


//Connect to your database
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];

// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$row_remates = mysqli_fetch_assoc($remates);


$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
$totalRows_lotes = mysqli_num_rows($lotes);
$renglones = 0;
$texto = "";
while($row_lotes = mysqli_fetch_array($lotes)) {
	if ($row_lotes["secuencia"] < 4)
		continue;
	$texto     = $row_lotes["descripcion"];
	$valor_de_reemplazo =  " (USADO CON DETERIOROS Y FALTANTES, EN EL ESTADO QUE SE ENCUENTRA Y EXHIBE).-";
	$texto = str_replace ( ".-" , $valor_de_reemplazo , $texto );

	echo "TEXTO =  ".$texto."    ";
	$codintlote = $row_lotes["codintlote"];
	echo "CODINTLOTE =  ".$codintlote."    ";
    $grabo_lotes_mod = sprintf("UPDATE lotes SET 'descripcion' = $texto WHERE 'codrem' = %s AND codintlote = $codintlote", $colname_remate);
	$lotes_mod =  mysqli_query($amercado,$grabo_lotes_mod);
	$texto = "";
	$codintlote = "";
}
mysqli_close($amercado);

?>