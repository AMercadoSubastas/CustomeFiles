<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado);
$lote = $_GET['getloteId'];
//$lote = "1A";
$f1=fopen("remate.txt","r");
$remate =fgets($f1,5);
fclose($f1);

//echo $remate;
//echo $lote;
//$lote ='1A';
//mysqli_select_db($amercado, $database_amercado);
//$query_texto = "SELECT * FROM lotes WHERE ( codrem='4' AND codintlote='1a')";
//$texto = mysqli_query($amercado, $query_texto) or die(mysqli_error($amercado));
//$row_texto = mysqli_fetch_assoc($texto);
//$totalRows_texto = mysqli_num_rows($texto);


//$remate= $_GET['getloteId'];
if(isset($lote)){  
  //$res = mysqli_query("select * from ajax_client where clientID='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
  $res = mysqli_query("SELECT * FROM lotes WHERE ( codrem='".$remate."' AND codintlote='".$lote."')")  or die(mysqli_error($amercado));
 // $f1=fopen("remate.txt","w+");
  # escribimos  al final del fichero preexistente 
//  fputs($f1,$remate);
  #cerramos el fichero
//  fclose($f1); 
  //$hola ="VER OPCIN";
 // $chau ="12";
  if($inf = mysqli_fetch_array($res)){
     $estado = $inf["estado"];
     if ($estado==0) {  
       $hola = $inf["descor"];
       $chau = $inf["comiscobr"];
       $secu = $inf["secuencia"];
       echo "formObj1.descripcion.value = '".$hola."';\n";  
  //  echo "formObj.descripcion.value = '".$inf["descor"]."';\n";    
      echo "formObj1.comision.value = '".$chau."';\n";    
      echo "formObj1.secuencia0.value = '".$secu."';\n"; 
	   } else {
   //  $fact = mysqli_query("SELECT * FROM detfac WHERE ( codrem='".$remate."' AND codintlote='".$lote."')")  or die(mysqli_error($amercado));
	// $factura = mysqli_fetch_assoc($fact);
	
	 
    $item = "EL LOTE YA ESTA FACTURADO ";
   echo "formObj1.descripcion.value = '".$item."';\n";  
   
   
   }    
    
  }else{
    echo "formObj1.descripcion.value  = '';\n";    
    echo "formObj1.comision.value = '';\n";    
    echo "formObj1.secuencia0.value = '';\n";
  }    
}
?> 
