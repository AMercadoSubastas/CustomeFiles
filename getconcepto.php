<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado);
$concepto = $_GET['getconceptoId'];

if(isset($concepto)){  
	$f1=fopen("datos.txt","w+");
  	# escribimos  al final del fichero preexistente 
  	fputs($f1,$concepto);
  	#cerramos el fichero
  	fclose($f1); 
	$res = mysqli_query("SELECT * FROM concafact WHERE ( nroconc'".$concepto."')")  or die(mysqli_error($amercado));
  	if($inf = mysqli_fetch_array($res)){
    	$porcentaje = $inf["porcentaje"];
     	if ($porcentaje!=0) {  
       		$porc = $inf["porcentaje"];
       		$desc = $inf["descrip"];
       		echo "formObj1.descripcion.value = '".$desc."';\n";  
      		echo "formObj1.tipo_imp.value = '".$porc."';\n"; 
	   	} else {
	     	$porc = $inf["porcentaje"];;
   			$desc = $inf["descrip"];
       		echo "formObj1.descripcion.value = '".$desc."';\n";  
      		echo "formObj1.tipo_imp.value = '".$porc."';\n"; 
   		}    
    
	}else{
		echo "formObj1.descripcion.value  = '';\n";    
    	echo "formObj1.porcentaje.value = '';\n";    
    }    
}
?> 
