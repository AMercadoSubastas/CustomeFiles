<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado); ?>

<?php
$factura = $_GET['getfacturaId'];
$serie = $_GET['getserieId'];
$tcomp = $_GET['gettcompId'];
?>

<?php //$tcomp = "HOLA";
//echo "formObj01.num_factura.value = '".$tcomp."';\n"; 
//$factura = 2;
//$serie = 5;
//$tcomp =6 ;
 
 
 
//echo $factura;

?> 
<?php // =====================================DESDE ACA VA EN EL AJAX ========================
if(isset($_GET['getfacturaId'])) {
//if(isset($factura)) {
	$todo_ok = 1;
	// Verifico que el n�mero no est� usado ni anulado
	// Lo busco primero en cabfac
	mysqli_select_db($amercado, $database_amercado);
	$query_fc = sprintf("SELECT * FROM cabfac  WHERE  serie ='$serie' AND ncomp = '$factura'");
	$fc = mysqli_query($amercado, $query_fc) or die(mysqli_error($amercado));
	$row_fc = mysqli_fetch_assoc($fc);
	$totalRows_fc = mysqli_num_rows($fc);
	if ($totalRows_fc > 0) {
		$todo_ok = 0;
		$mensaje ="Numero ".$factura." esta usado ".$serie;
		echo "formObj01.num_factura.value = '".$mensaje."';\n"; 
	}
	// Ahora lo busco en cbtesanul
	mysqli_select_db($amercado, $database_amercado);
	$query_fcanul = sprintf("SELECT * FROM cbtesanul  WHERE  serie ='$serie' AND ncomp = '$factura'");
	$fcanul = mysqli_query($amercado, $query_fcanul) or die(mysqli_error($amercado));
	$row_fcanul = mysqli_fetch_assoc($fcanul);
	$totalRows_fcanul = mysqli_num_rows($fcanul);
	if ($totalRows_fcanul > 0) {
	    $mensaje= "El n�mero ".$factura." esta anulado";
		$todo_ok = 0;
	} 
	if ($todo_ok == 1) {

    echo "formObj01.num_factura.value = '".$factura."';\n"; 
    
	 }
	
	}
?>
//if (todo_ok == 1) {
//	if(isset($factura)){  
//        $res = mysqli_query("select * from cabfac WHERE tcomp = '$tcomp' AND serie ='$serie' AND ncomp = '$factura'");
//        if($inf = mysqli_fetch_array($res)){
//        $factura = $inf["ncomp"];
//   $factura = $factura+1;
//        echo "formObj01.num_factura.value = '".$factura."';\n";    
  //  echo "formObj.fecha_remate.value = '".$tcomp."';\n";    
//    echo "formObj.num_factura.value = '".$tcomp."';\n"; 
    
 

//}}
//else {
//	echo "formObj01.num_factura.value = '".$mensaje."';\n";  
	
//}}
// =====================================HASTA ACA VA EN EL AJAX ========================