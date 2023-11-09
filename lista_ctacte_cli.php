<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>Documento sin t&iacute;tulo</title>



<?php	
 require_once('Connections/amercado.php');  

 require_once "vendor/autoload.php";
 require_once "src/constants.php";
 require_once "src/config.php";
 require_once "src/phpfn.php";
 require_once "src/userfn.php"; 
 ?>
 <?php

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE tipoent='1' and activo = '1' ORDER BY razsoc  ASC";
$cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

?>

</head>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<body>
<form  name="libros" method="post" action="rp_ctacte_cli.php">
  
    
    <tr>
      <td width="392" height="20">&nbsp;</td>
      
    </tr>
 <table width="392" height="179" border="0" align="left" cellpadding="1" cellspacing="1">   
         <tr>
          <td height="10" class="ewTableHeader"> Cliente </td>
          <td><select name="codnum" id="codnum" >
            <option value="">Cliente</option>
		
            <?php
do {  
?>
            <option value="<?php echo $row_cliente['codnum']?>"><?php echo substr(utf8_encode($row_cliente['razsoc']),0,30)?></option>
            <?php
} while ($row_cliente = mysqli_fetch_assoc($cliente));
  	$rows = mysqli_num_rows($cliente);
  if($rows > 0) {
      mysqli_data_seek($cliente, 0);
	  $row_cliente = mysqli_fetch_assoc($cliente);
	 		
  }
?>
          </select></td>
          <td></td>
        </tr>
 
      

    <tr>
      <td width="181">&nbsp;</td>
      <td width="132"><input type="submit" name="Submit" value="Enviar" /></td>
      <td width="69">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
