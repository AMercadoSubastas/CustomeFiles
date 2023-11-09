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
<?php require_once('Connections/amercado.php');  
 include_once "ewcfg50.php" ;
 include_once "ewmysql50.php" ;
 include_once "phpfn50.php" ; 
 include_once  "userfn50.php" ;
 include_once "usuariosinfo.php" ; ?>
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
<form id="form1" name="form1" method="post" action="rp_remate_vendido.php">
  <table width="300" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" align="center" background="images/fondo_titulos.jpg" class="ewGroupAggregate">
	  REMATE VENDIDO</td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="150">Remate N&uacute;mero : </td>
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
