<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado); ?>

<?php

$remate= $_GET['getremate'];
$remate = 4;
//if(isset($_GET['getremate'])){  

$query_remate ="SELECT SUM( `totneto105` ) , SUM( `totiva105` ) , SUM( `totneto105` + `totiva105` ) as total_105 , SUM( `totneto21` ) , SUM( `totiva21` ) , SUM( `totneto21` + `totiva21` )  as total_21
FROM `cabfac` 
WHERE `codrem` = '$remate'" ;
$query_remate = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$totalRows_remate = mysqli_num_rows($query_remate);
echo $totalRows_remate."<br>";
echo mysqli_result($query_remate,0,0)."<br>";
echo mysqli_result($query_remate,0,1)."<br>";
echo mysqli_result($query_remate,0,2)."<br>";
echo mysqli_result($query_remate,0,3)."<br>";
echo mysqli_result($query_remate,0,4)."<br>";
echo mysqli_result($query_remate,0,5)."<br>";
  //$res = mysqli_query("select * from ajax_client where clientID='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
//  $res = mysqli_query("select * from remates where ncomp='".$_GET['getClientId']."'") or die(mysqli_error($amercado));
//  if($inf = mysqli_fetch_array($query_remate)){
    $inf = mysqli_fetch_array($query_remate) ;
	echo "Total Neto".$inf["totneto105"]."<br>";
  if($inf = mysqli_fetch_array($query_remate)){
    echo "Total neto 105".$inf["totneto105"];
    echo "formObj.firstname.value = '".$inf["totneto105"]."';\n";    
    echo "formObj.lastname.value = '".$inf["totiva105"]."';\n";    
    echo "formObj.address.value = '".$inf["totneto21"]."';\n";    
    echo "formObj.zipCode.value = '".$inf["totiva21"]."';\n";    
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
//}
?> 
