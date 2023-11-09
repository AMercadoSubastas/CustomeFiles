<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado); ?>

<?php

$remate= $_GET['getClientId'];
//$observacion = Los cheques ;
if(isset($_GET['getClientId'])){  
  //$res = mysqli_query("select * from ajax_client where clientID='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
  $res = mysqli_query("select * from remates where ncomp='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
  $f1=fopen("remate.txt","w+");
  # escribimos  al final del fichero preexistente 
  fputs($f1,$remate);
  #cerramos el fichero
  fclose($f1); 

  if($inf = mysqli_fetch_array($res)){
    echo "formObj.lugar_remate.value = '".$inf["direccion"]."';\n";    
    echo "formObj.fecha_remate.value = '".$inf["fecest"]."';\n";    
  //  echo "formObj.observacion.value = '".$observaciones."';\n";  
    
  }else{
    echo "formObj.firstname.value = '';\n";    
    echo "formObj.lastname.value = '';\n";    
    
  }    
}
?> 
