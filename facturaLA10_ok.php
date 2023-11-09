<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Documento sin t&iacute;tulo</title>
<?php
$factura = $_GET['factura'];
?>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {
	color: #990000;
	font-size: x-large;
}
-->
</style>
</head>

<body>
<table width="400" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td class="ewGroupAggregate Estilo1" ><div align="center">Factura N&ordm; &nbsp;&nbsp;<?php echo $factura?></div></td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1" >&nbsp;</td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1"><div align="center">Ingresada</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <tr bgcolor="FFFFFF">
   <td height="33" colspan="4" align="center" bgcolor="#0099CC"><a href="v_afcautxlotA10.php"><img src="images/salir_but.gif" width="55" height="23" border="0" /></a></td>
   </tr>
  </tr>
</table>
</body>
</html>
