<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado); ?>

<?php


if(isset($_GET['getClientId'])){  
  //$res = mysqli_query("select * from ajax_client where clientID='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
  $res = mysqli_query("select * from remates where ncomp='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
  if($inf = mysqli_fetch_array($res)){
    echo "formObj.firstname.value = '".$inf["direccion"]."';\n";    
    echo "formObj.lastname.value = '".$inf["fecest"]."';\n";    
    echo "formObj.address.value = '".$inf["address"]."';\n";    
    echo "formObj.zipCode.value = '".$inf["zipCode"]."';\n";    
    echo "formObj.city.value = '".$inf["city"]."';\n";    
    echo "formObj.country.value = '".$inf["country"]."';\n";    
    
  }else{
    echo "formObj.firstname.value = '';\n";    
    echo "formObj.lastname.value = '';\n";    
    echo "formObj.address.value = '';\n";    
    echo "formObj.zipCode.value = '';\n";    
    echo "formObj.city.value = '';\n";    
    echo "formObj.country.value = '';\n";      
  }    
}
?> 
