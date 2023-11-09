<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />

<?php 
 require_once('Connections/amercado.php');  
 /*
 include_once "ewcfg50.php" ;
 include_once "ewmysql50.php" ;
 include_once "phpfn50.php" ; 
 include_once  "userfn50.php" ;
 include_once "usuariosinfo.php" ; 
 include "header.php" ;
*/
//echo $nivel;
?>
</head>

<body>
<form id="form1" name="form1" method="get" action="modifremates.php">
  <table width="300" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/fondo_titulos.jpg" align="center"><img src="images/modificar_remate.gif" width="250" height="30" /></td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="150">Remate N&uacute;mero : </td>
      <td width="50"><input type="text" name="carga1" /></td>
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
