<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>

<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering


require_once('Connections/amercado.php');  
mysqli_select_db($amercado, $database_amercado);
//$query_Recordset1 = "SELECT * FROM `remates` ORDER BY `ncomp` desc";
$query_Recordset1 = sprintf("SELECT * FROM `remates` WHERE `fecest` >= NOW() ORDER BY `ncomp` desc");
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("ERROR LEYENDO REMATES");
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
	
//$usename = $_SESSION[EW_SESSION_USER_PROFILE_USER_NAME];
$usuario = CurrentUserName();
echo "USUARIO ".$usuario."  ";
// LEO EL USUARIO
mysqli_select_db($amercado, $database_amercado);
//$cod_usuario = 1;
$usuario = "\"".$usuario."\"";
$query_usuarios = sprintf("SELECT * FROM usuarios WHERE usuario = %s",$usuario);
//echo "QUERY = ".$query_usuarios."   ";
$res_usuarios = mysqli_query($amercado, $query_usuarios) or  die("ERROR LEYENDO USUARIOS");
$row_usuarios = mysqli_fetch_assoc($res_usuarios);
$usu = $row_usuarios['codnum'];
echo $usu;
 ?>

<body>
<form id="form1" name="form1" method="post" action="WafcautxlotA5.php?<?php echo $usu?>">
  <table width="706" height="152" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/nada.gif" align="center"><img src="images/nada.gif" width="222" height="30" /></td>
    </tr>
	  <td><input name="codusu" id="codusu" type="text"  size="12" value="<?php echo $usu ?>"/></td>
    <tr>
      <td width="234">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="234">Remate a facturar: </td>
      <td height="10" class="ewTableHeader"> </td>
          <td><select name="remate_num" id="remate_num">
            <option value="">Remate</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_Recordset1['ncomp']?>"><?php echo $row_Recordset1['ncomp']?><?php echo " - "?><?php echo $row_Recordset1['direccion']?></option>
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
