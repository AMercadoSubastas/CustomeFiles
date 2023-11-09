<?php require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];
//$colname_remate = 1;

$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
//$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);
$renglones = 0;
while($row_lotes = mysqli_fetch_array($lotes)) 
{
  // desde aca 
	$code = "";
	$texto = "";
	$texto2 = "";
	$texto3 = "";
	$texto4 = "";
	$texto5 = "";
	
	$code = $row_lotes["codintlote"];
	$texto = $row_lotes["descor"];
	     $tamano = 53; // tama�o m�ximo renglon
         $contador = 0; 
         $contador1 = 0; 
         $contador2 = 0; 
         $contador3 = 0; 
         $contador4 = 0; 
         $contador5 = 0; 
         $contador6 = 0; 
         $contador7 = 0; 

        $texto = strtoupper($texto);
//echo $texto."<br><br><br>" ;
        $texto_orig= $texto ;
//echo "Texto Original".$texto_orig."<br><br>";
        $largo_orig = strlen($texto_orig);
//echo $largo_orig."<br>";
// Cortamos la cadena por los espacios 

      $arrayTexto = split(' ',$texto); 
$texto = ''; 
}
// Reconstruimos el primer renglon
while($tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
    $texto .= ' '.$arrayTexto[$contador]; 
    $contador++; 
} 

?>
