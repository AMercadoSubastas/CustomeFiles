<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado); ?>

<?php

$remate= $_GET['getFacturaId'];
$serie= $_GET['getSerieId'];
//$observacion = Los cheques ;
if(isset($_GET['getFacturaId']) && isset($_GET['getSerieId'])){  

	$consulta=mysqli_query($amercado, "select * from cabfac where (ncomp='".$_GET['getFacturaId']."' AND serie ='".$_GET['getSerieId']."')") or die(mysqli_error($amercado));
	$resultado=mysqli_query($amercado, $consulta) or die (mysqli_error($amercado));
	if (mysqli_num_rows($resultado)>0)
	{
		$item="LA FACTURA YA EXISTE";
		echo "formObj.num_factura.value = '".$item."';\n";
		
	} 






  //$res = mysqli_query("select * from ajax_client where clientID='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
  //$res = mysqli_query("select * from cabfac where ncomp='".$_GET['getFacturaId']."' AND serie ='".$_GET['getSerieId']."'") or die(mysqli_error($amercado));
  //$f1=fopen("remate.txt","w+");
  # escribimos  al final del fichero preexistente 
  //fputs($f1,$remate);
  #cerramos el fichero
  //fclose($f1); 
/*
  if($inf = mysqli_fetch_array($res)){
    
    
  }else{
    echo "formObj.firstname.value = '';\n";    
    echo "formObj.lastname.value = '';\n";    
    
  } 
  */   
}
?> 
