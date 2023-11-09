<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "funcion_mysqli_result.php";

mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM entidades  WHERE entidades.tipoent=1  ORDER BY entidades.razsoc ASC";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {color: #ECE9D8}
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
}
</style>
 
?>

</head>

<body>
<form id="form1" name="form1" method="post" action="carga_recibo.php">
  <table width="300" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/fondo_titulos.jpg" align="center"><img src="images/emi_rec.gif" /></td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="150">Nombre del Cliente : </td>
      <td width="50"><select name="cliente" class="ewMultiPagePager">
        <?php
do {  
?>
        <option value="<?php echo $row_Recordset1['codnum']?>"><?php echo $row_Recordset1['razsoc']?></option>
        <?php
} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  $rows = mysqli_num_rows($Recordset1);
  if($rows > 0) {
      mysqli_data_seek($Recordset1, 0);
	  $row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td width="50">&nbsp;</td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td width="50"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>