<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "src/userfn.php";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  	$updateSQL = sprintf("DELETE FROM cartvalores  WHERE codnum=%s",
                       GetSQLValueString($_POST['codnum'], "int"));

  	mysqli_select_db($amercado, $database_amercado);
  	$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
  	$updateGoTo = "medios_pago.php";
  	if (isset($_SERVER['QUERY_STRING'])) {
    	$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    	$updateGoTo .= $_SERVER['QUERY_STRING'];
  	}
  	header(sprintf("Location: %s", $updateGoTo));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {
	color: #FFFF99;
	font-size: 18px;
}
-->
</style>
<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado);
$codnum = $_GET['codnum'];
if(isset($_GET['retencion']))
	$retencion = $_GET['retencion'];
else
	$retencion = "";
echo $codnum."<br>" ;
echo $retencion;
$sql_deposito = "SELECT   bancos.nombre , codchq , importe , fechapago , codrem, cartvalores.codnum" 
        . " FROM cartvalores , bancos  "
        . " WHERE cartvalores.codnum ='$codnum' AND cartvalores.codban = bancos.codnum";

//$registro =	mysqli_query($amercado, $sql_deposito) ;	
$total_deposito = mysqli_query($amercado, $sql_deposito) or die(mysqli_error($amercado));
	
		$registro = mysqli_fetch_row($total_deposito) ;
?>
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="500" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
    <tr>
      <td colspan="2" bgcolor="#336666" class="ewGroupAggregate Estilo1" ><div align="center">Borrar transaccion </div></td>
    </tr>
    
    <tr>
      <td width="221" bgcolor="#993333" class="Estilo1">Banco</td>
      <td width="272" bgcolor="#993333"><input name="banco" type="text" class="phpmaker"  readonly="" value="<?php echo $registro [0]  ; ?>"/></td>
    </tr>
    <tr>
      
    </tr>
    <tr>
      <td bgcolor="#993333" class="Estilo1">Numero de transaccion</td>
      <td bgcolor="#993333"><input name="tranmsaccio" type="text" class="phpmaker" id="tranmsaccio" readonly="" value="<?php echo $registro [1] ?>"/></td>
    </tr>
    <tr>
      <td bgcolor="#993333" class="Estilo1">Fecha de transaccion </td>
      <td bgcolor="#993333"><input name="fecha" type="text" class="phpmaker" id="tranmsaccio" readonly="" value="<?php echo $registro [3] ?>"/></td>
    </tr>
    <tr>
      <td bgcolor="#993333" class="Estilo1">Importe</td>
      <td bgcolor="#993333"><input name="importe" type="text" class="phpmaker" id="tranmsaccio"  value="<?php echo $registro [2] ?>"/></td>
    </tr><input name="codnum" type="hidden" class="phpmaker" id="tranmsaccio"  value="<?php echo $codnum ?>"/>
    <tr>
      <td bgcolor="#993333" class="Estilo1">&nbsp;</td>
      <td bgcolor="#993333"><input type="submit" name="Submit" value="Borrar" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1">
</form>
</body>

</html>
