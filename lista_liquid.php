<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
require_once('Connections/amercado.php');  
//echo "EN LISTA_LIQUID ANTES DE LEER REMATES  ";
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM remates  ORDER BY ncomp desc";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("ERROR LEYENDO REMATES");
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
//echo $totalRows_Recordset1;
?>
</head>
<body>
<form id="form1" name="form1" method="post" action="rp_emiliq.php">
  <table width="884" height="188" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/nada.gif" align="center"><img src="images/nada.gif" width="225" height="30" /></td>
    </tr>
    <tr>
      <td width="264">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="264">Remate Nro. : </td>
      
          <td><select name="remate_num" id="remate_num">
            <option value="">Remate</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_Recordset1['ncomp']?>"><?php echo $row_Recordset1['ncomp']?><?php echo " - "?><?php echo utf8_decode($row_Recordset1['direccion'])?></option>
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
      <td width="298" >Tipo de liquidacion  : </td>
      <td width="422" ><select name="tliq" >
	    <option value="0">[ELIJA TIPO DE COMPROBANTE]</option>
        <option value="3">LIQUIDACION A</option>
        <option value="31">LIQUIDACION B</option>
      </select></td>
    </tr>
    <tr>
      <td>Nro. de Liquidacion :</td>
      <td><input name="liquidacion" type="text" id="liquidacion"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td></td>
      <td><input type="submit" name="Submit" value="Enviar"/></td>
    </tr>
  </table>
</form>
</body>
</html>