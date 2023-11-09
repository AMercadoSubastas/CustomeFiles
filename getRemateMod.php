<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado);


$remate = $_GET['getRemateId'];
//$remate = 5;
?>

<?php //echo $remate;
if(isset($_GET['getRemateId'])){  
//if(isset($remate)){  
	//echo "ENTRO AL IF";
  $res = mysqli_query("select * from remates where ncomp='$remate'") or die(mysqli_error($amercado));
  if($inf = mysqli_fetch_array($res)){
  	//echo "HOLA";
  	//echo $inf["direccion"];
 //   echo "formObj.codcli.value      = '".$inf["codcli"]."';\n";    
    echo "formObj.direccion.value   = '".$inf["direccion"]."';\n"; 
	echo "formObj.country.value     = '".$inf["codpais"]."';\n"; 
	echo "formObj.city.value        = '".$inf["codprov"]."';\n"; 
	echo "formObj.university.value  = '".$inf["codloc"]."';\n"; 
	echo "formObj.fecest.value      = '".$inf["fecest"]."';\n"; 
	echo "formObj.fecreal.value     = '".$inf["fecreal"]."';\n";    
	echo "formObj.imptot.value      = '".$inf["imptot"]."';\n";    
	echo "formObj.cantlotes.value   = '".$inf["cantlotes"]."';\n";    
	echo "formObj.horaest.value     = '".$inf["horaest"]."';\n";    
	echo "formObj.horareal.value    = '".$inf["horareal"]."';\n";    	
	echo "formObj.observacion.value = '".$inf["observacion"]."';\n";    
	echo "formObj.tipoind.value     = '".$inf["tipoind"]."';\n";    
    

  }else{
  	//echo "CHAU";
    echo "formObj.codcli.value      = '';\n";    
    echo "formObj.direccion.value   = '';\n"; 
	echo "formObj.country.value     = '';\n"; 
	echo "formObj.city.value        = '';\n"; 
	echo "formObj.university.value  = '';\n"; 
	echo "formObj.fecest.value      = '';\n"; 
	echo "formObj.fecreal.value     = '';\n";    
	echo "formObj.imptot.value      = '';\n";    
	echo "formObj.cantlotes.value   = '';\n";    
	echo "formObj.horaest.value     = '';\n";    
	echo "formObj.horareal.value    = '';\n";    	
	echo "formObj.observacion.value = '';\n";    
	echo "formObj.tipoind.value     = '';\n";    
        
  }  
 }
 else {
  echo "NUEVO ECHO";  
}

?> 
