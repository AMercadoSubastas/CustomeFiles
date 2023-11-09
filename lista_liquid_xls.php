<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php 
 require_once('Connections/amercado.php');  
  ?>
<?php
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
//header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache"); // HTTP/1.0
?>

</head>

<body>
<form id="form1" name="form1" method="get" action="rp_liquid_xls.php">
  <table width="526" height="188" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/nada.gif" align="center"><img src="images/nada.gif" width="225" height="30" /></td>
    </tr>
    <tr>
      <td width="258">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="258" height="35">Remate a Liquidar   : </td>
      <td width="261"><input name="ftcomp" type="text" id="ftcomp" /></td>
    </tr>
    <tr>
      <td height="31">Numero de Liquidacion :</td>
      <td><input name="liquidacion" type="text" id="ftcomp2" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="258"></td>
      <td width="261"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
