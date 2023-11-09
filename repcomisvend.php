<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>

 <?php
 require_once('Connections/amercado.php');  
 
 
 		$hostname_repuestos = "localhost";
		$database_repuestos = "repuestos";
		$username_repuestos = "root";
		$password_repuestos = "";

				// LEO LA TABLA SERIES
		$repuestos = mysqli_connect($hostname_repuestos, $username_repuestos, $password_repuestos) or trigger_error(mysqli_error($amercado),E_USER_ERROR);

//Connect to your database
//require_once('Connections/repuestos.php');

mysqli_select_db($repuestos, $database_repuestos);

$query_Recordset1 = "SELECT * FROM `vendedores`  ORDER BY `apynom` asc";
$Recordset1 = mysqli_query($repuestos, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

?>
</head>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<body>
<form id="form1" name="form1" method="post" action="rp_comisvend.php">
  <table width="707" height="264" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="phpimages/nada.gif" align="center"><img src="phpimages/nada.gif" width="222" height="30" /></td>
    </tr>
    <tr>
      <td width="126">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
     
       <tr>  
      <td width="181" height="48">Desde  : </td>

      <td width="132"><input name="fecha_desde" type="text" size="11" /></td>
      <td width="69"><a href="javascript:showCal('Calendar18')"><img src="calendar_old/img.gif" width="32" height="23"  border="0"/></a></td>
    </tr>
    <tr>
      <td width="181" height="44">Hasta</td>
      <td width="132"><input name="fecha_hasta" type="text" size="11" /></td>
      <td width="69"><a href="javascript:showCal('Calendar19')"><img src="calendar_old/img.gif" width="32" height="23"  border="0"/></a></td>
    </tr>
    <tr>
      <td width="126">&nbsp;</td>
      <td width="365">&nbsp;</td>
    </tr>
    <tr>
      <td width="126">&nbsp;</td>
      <td width="365"> <input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
