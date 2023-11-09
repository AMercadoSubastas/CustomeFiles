<?php 
require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado); ?>
<?php
$remate= $_GET['getClientId'];

if(isset($_GET['getClientId'])){  
	$res = mysqli_query("select * from remates where ncomp='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
	$res1 ="SELECT SUM( `totneto105` ) , SUM( `totiva105` ) , SUM( `totneto105` + `totiva105` ) as total_105 , SUM( `totneto21` ) , SUM( `totiva21` ) , 	SUM( `totneto21` + `totiva21` )  as total_21
FROM `cabfac` 
WHERE `codrem` = '$remate'" ;
	$query_remate = mysqli_query($amercado, $res1) or die(mysqli_error($amercado));
	$totalRows_remate = mysqli_num_rows($query_remate); 
	$totneto105 = mysqli_result($query_remate,0,0);
	$totneto105 =  number_format ($totneto105 , 2 , "," , ".");
 	$iva105 = mysqli_result($query_remate,0,1);
	$total_105 = mysqli_result($query_remate,0,2);
	$totneto21 = mysqli_result($query_remate,0,3);
	$iva21 = mysqli_result($query_remate,0,4);
	$iva21 = number_format ($iva21 , 2 , "," , ".");
	$total_21 = mysqli_result($query_remate,0,5);
	$total_remate = $total_105 +$total_21;
	$iva105 = number_format ($iva105 , 2 , "," , ".");
	$total_105 = number_format ($total_105 , 2 , "," , ".");
	$totneto21 = number_format ($totneto21 , 2 , "," , ".");
	$total_21 = number_format ($total_21 , 2 , "," , ".");
	$total_remate = number_format ($total_remate , 2 , "," , ".");
	if($inf = mysqli_fetch_array($res)){
    	$fecha_remate = substr($inf["fecest"],8,2).'-'.substr($inf["fecest"],5,2).'-'.substr($inf["fecest"],0,4);
        echo "formObj.lugar_remate.value = '".$inf["direccion"]."';\n";    
    	echo "formObj.fecha_remate.value = '".$fecha_remate."';\n";    
		echo "formObj.importe_total.value = '".$total_remate."';\n"; 
		echo "formObj.neto105.value = '".$totneto105."';\n";  
		echo "formObj.iva105.value = '".$iva105."';\n";  
    	echo "formObj.total105.value = '".$total_105."';\n"; 
		echo "formObj.total105_1.value = '".$total_105."';\n";
		echo "formObj.neto21.value = '".$totneto21."';\n"; 
    	echo "formObj.iva21.value = '".$iva21."';\n"; 
		echo "formObj.total21.value = '".$total_21."';\n"; 
		echo "formObj.total21_1.value = '".$total_21."';\n"; 
	}else{
    	echo "formObj.lugar_remate.value = '';\n";    
    	echo "formObj.fecha_remate.value = '';\n";    
    	echo "formObj.importe_total.value = '';\n";
  	}    
}
?> 
