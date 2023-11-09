<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>

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
<?php //include "header.php" ;
//echo $nivel;
?>
</head>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<body>
<form  name="libros" method="post" action="rp_ivacompras.php">
  <table width="438" height="188" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="3" background="images/nada.gif" align="center"><img src="images/nada.gif" width="310" height="30" /></td>
    </tr>
    <tr>
      <td width="266" height="16">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
      <td width="266">Desde  : </td>
      <td width="119"><input name="fecha_desde" type="text" size="11" /></td>
      <td width="43"><a href="javascript:showCal('Calendar14')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
    <tr>
      <td width="266">Hasta</td>
      <td width="119"><input name="fecha_hasta" type="text" size="11" /></td>
      <td width="43"><a href="javascript:showCal('Calendar15')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
    <tr>
      <td width="48%" height="29" >&nbsp;<span class="ewTableHeader">Salida pdf</span></td>
      <td width="52%" ><input name="GrupoOpciones1" type="radio" value=1   checked="checked"  /></td>
    </tr>
    <tr>
      <td height="27" >&nbsp;<span class="ewTableHeader">Salida txt</span></td>
      <td ><input name="GrupoOpciones1" type="radio" value=0  /></td>
    </tr>
   
    <tr>
      <td width="266">&nbsp;</td>
      <td width="119"><input type="submit" name="Submit" value="Enviar" /></td>
      <td width="43">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
