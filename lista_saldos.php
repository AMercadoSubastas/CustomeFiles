<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php 
 require_once('Connections/amercado.php');  
 require_once "vendor/autoload.php";
 require_once "src/constants.php";
 require_once "src/config.php";
 require_once "src/phpfn.php";
 require_once "src/userfn.php";   
 
mysqli_select_db($amercado, $database_amercado);
//$query_Recordset1 = "SELECT * FROM `remates` ORDER BY `ncomp` desc";
$query_Recordset1 = sprintf("SELECT * FROM `tiposaldos` WHERE `activo` = 1");
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("ERROR LEYENDO TIPOS DE SALDOS");
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

</head>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<body>
<form  name="libros" method="post" action="rp_saldos.php">
  <table width="392" height="179" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="3" background="images/nada.gif" align="center"><img src="images/nada.gif" width="310" height="30" /></td>
    </tr>
    <tr>
      <td width="181" height="16">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
      <td width="181" height="39">Mes desde  : </td>

      <td width="132"><input name="mes_desde" type="text" size="11" /></td>
      
    </tr>
      <tr>
      <td width="181" height="39">A�o desde  : </td>

      <td width="132"><input name="anio_desde" type="text" size="11" /></td>
      
    </tr>
    <tr>
      <td width="181">Mes hasta</td>
      <td width="132"><input name="mes_hasta" type="text" size="11" /></td>
      
    </tr>
      
    <tr>
      <td width="181">A�o hasta</td>
      <td width="132"><input name="anio_hasta" type="text" size="11" /></td>
      
    </tr>
      <tr>
      <td width="234">Tipo de saldo: </td>
      <td height="10" class="ewTableHeader"> </td>
          <td><select name="saldo_num" id="saldo_num">
            <option value="">Tipo</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_Recordset1['codnum']?>"><?php echo $row_Recordset1['codnum']?><?php echo " - "?><?php echo $row_Recordset1['nombre']?></option>
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
      <td width="48%" height="29" >&nbsp;<span class="ewTableHeader">Salida pdf</span></td>
      <td width="52%" ><input name="GrupoOpciones1" type="radio"  value=1   checked="checked" /></td>
    </tr>
    <tr>
      <td height="27" >&nbsp;<span class="ewTableHeader">Salida txt</span></td>
      <td ><input name="GrupoOpciones1" type="radio" value=0  /></td>
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
