<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>

<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering

include_once('src/constants.php');
require_once('Connections/amercado.php');  
mysqli_select_db($amercado, $database_amercado);
//$query_Recordset1 = "SELECT * FROM `remates` ORDER BY `ncomp` desc";
//$query_Recordset1 = sprintf("SELECT * FROM `remates` WHERE `fecest` >= NOW() ORDER BY `ncomp` desc");
//$query_Recordset1 = "SELECT * FROM remates WHERE fecest >= NOW() ORDER BY `ncomp` desc";
$query_Recordset1 = "SELECT * FROM tipcomp WHERE codnum in (32,33,65,34,35,88,36,37,87,107,110)";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("ERROR LEYENDO TIPCOMP");
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
	
$cod_usuario = $_SESSION['id'];
echo "USUARIO ".$cod_usuario."  ";
// LEO EL USUARIO
mysqli_select_db($amercado, $database_amercado);
//$cod_usuario = 1;

?>

<body>
<form id="form1" name="form1" method="post" action="afacprov.php?<?php echo $cod_usuario?>">
  <table width="706" height="152" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/nada.gif" align="center"><img src="images/nada.gif" width="222" height="30" /></td>
    </tr>
	  <td><input name="codusu" id="codusu" type="text"  size="12" value="<?php echo $cod_usuario ?>"/></td>
    <tr>
      <td width="234">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="234">Tipo de Comprobante: </td>
      <td height="10" class="ewTableHeader"> </td>
          <td><select name="tcomp" id="tcomp">
            <option value="">Tipo cbte</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_Recordset1['codnum']?>"><?php echo $row_Recordset1['codnum']?><?php echo " - "?><?php echo $row_Recordset1['descripcion']?></option>
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
      <td width="234">&nbsp;</td>
		
      <td width="420">&nbsp;</td>
    </tr>
    <tr>
      <td width="234">&nbsp;</td>
      <td width="420"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>