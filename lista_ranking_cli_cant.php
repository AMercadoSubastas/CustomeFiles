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

?>

</head>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<body>
<form  name="libros" method="post" action="rp_ranking_cli_cant.php">
  
    
    <tr>
      <td width="392" height="20">&nbsp;</td>
      
    </tr>
 <table width="392" height="179" border="0" align="left" cellpadding="1" cellspacing="1">   
    <tr>
      <td width="181" height="39">Desde  : </td>

      <td width="132"><input name="fecha_desde" type="text" size="11" /></td>
      <td width="69"><a href="javascript:showCal('Calendar14')"><img src="calendar_old/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
    <tr>
      <td width="181">Hasta  :</td>
      <td width="132"><input name="fecha_hasta" type="text" size="11" /></td>
      <td width="69"><a href="javascript:showCal('Calendar15')"><img src="calendar_old/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
    <tr>
      <td width="181">Cant de Clientes :</td>
      <td width="132"><input name="cant_cli" type="text" size="11" /></td>
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
