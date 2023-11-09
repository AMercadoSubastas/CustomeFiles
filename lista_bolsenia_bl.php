<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
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
<? 
 require_once('Connections/amercado.php');  
 require_once "vendor/autoload.php";
 require_once "src/constants.php";
 require_once "src/config.php";
 require_once "src/phpfn.php";
 require_once "src/userfn.php";  ?>
<?php
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
//header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache"); // HTTP/1.0
?>
<?php include "header.php" ;
echo $nivel;
?>
</head>

<body>
<form id="form1" name="form1" method="post" action="rp_bolsenia_bl.php">
  <table width="300" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/fondo_titulos.jpg" align="center"><img src="images/bolsenia_bl.gif" width="224" height="30" /></td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="150">Cantidad de Boletas a emitir : </td>
      <td width="50"><input type="text" name="remate_num" /></td>
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
