<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>

</head>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<?php
require_once('Connections/amercado.php');  
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM `concafact` ORDER BY `nroconc` asc";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<body>
<form  name="libros" method="post" action="rp_lista_gastosxconc.php">
  <table width="500" height="200" border="1" align="left" cellpadding="1" cellspacing="1">
       <tr>
      <td width="150" height="16">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
        <tr>
      <td width="150">Desde  : </td>
      <td width="50"><input name="fecha_desde" type="text" size="11" /></td>
      <td width="50"><a href="javascript:showCal('Calendar14')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
    <tr>
      <td width="150">Hasta :</td>
      <td width="50"><input name="fecha_hasta" type="text" size="11" /></td>
      <td width="50"><a href="javascript:showCal('Calendar15')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
       <tr>
		<td width="150"><strong>Concepto N&uacute;mero: </strong></td>
      <td height="10" class="ewTableHeader"> </td>
          <td width="82"><select name="concepto_num" id="concepto_num">
            <option value="">Concepto</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_Recordset1['nroconc']?>"><?php echo $row_Recordset1['nroconc']?><?php echo " - "?><?php echo utf8_encode($row_Recordset1['descrip'])?></option>
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
      <td width="150">&nbsp;</td>
      <td width="50"><input type="submit" name="Submit" value="Enviar" /></td>
      <td width="50">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
