<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado); ?>

<?php
$factura = $_GET['clientId'];
?>
<script language="javascript">
alert();

</script>
<?php echo $factura;
if(isset($_GET['clientId'])){  
  $res = mysqli_query("select * from remates where ncomp='".$_GET['clientId']."'") or die(mysqli_error($amercado));
  if($inf = mysqli_fetch_array($res)){
    echo "formObj.lugar_remate.value = '".$inf["direccion"]."';\n";    
    echo "formObj.fecha_remate.value = '".$inf["fecest"]."';\n";    
    
    
  }else{
    echo "formObj.lugar_remate.value = '';\n";    
    echo "formObj.fecha_remate.value = '';\n";    
        
  }    
}
?> 
