<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>

<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php 
/*
 require_once('Connections/amercado.php');  
 include_once "ewcfg11.php" ;
 include_once "ewmysql11.php" ;
 include_once "phpfn11.php" ; 
 include_once "userfn11.php" ;
 include_once "usuariosinfo.php" ; */?>
 <?php

require_once('Connections/amercado.php');  
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM `entidades` ORDER BY `razsoc` asc";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("ERROR LEYENDO ENTIDADES");
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
//echo $totalRows_Recordset1;
 ?>
<body>
<form  name="libros" method="post" action="rp_facturas_enti.php">
  <table width="590" height="268" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="3" background="images/nada.gif" align="center"><img src="images/nada.gif" width="310" height="30" /></td>
    </tr>
    <tr>
      <td width="318" height="16">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
      <td width="318" height="39">Desde  : </td>

      <td width="158"><input name="fecha_desde" value=<?php echo "01-01-2020"?> type="text" size="11" /></td>
      <td width="104"><a href="javascript:showCal('Calendar14')"><img src="calendar_old/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
    <tr>
      <td width="318">Hasta</td>
      <td width="158"><input name="fecha_hasta" value=<?php $dia=date("d")."-".date("m")."-".date("Y"); echo $dia?> type="text" size="11" /></td>
      <td width="104"><a href="javascript:showCal('Calendar15')"><img src="calendar_old/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
    <tr>
         
          <td></td>
          <td><input name="fecha_factura" type="hidden" size="12" /></td>
        </tr>
        <tr>
          <td height="30" class="ewTableHeader"> Entidad </td>
          <td><select name="enti" id="enti">
            <option value="">Entidad</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_Recordset1['codnum']?>"><?php echo utf8_encode($row_Recordset1['razsoc'])?><?php echo " - "?><?php echo $row_Recordset1['codnum']?><?php echo " - "?><?php echo $row_Recordset1['tipoent']?></option>
            <?php
				} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  				$rows = mysqli_num_rows($Recordset1);
  				if($rows > 0) {
      				mysqli_data_seek($Recordset1, 0);
	  				$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  				}
			?>
          </select></td>
          <td></td>
        </tr>
    <tr>
      <td width="318">(1-Cliente, 3-Proveedor)</td>
      </tr>
		
		<tr>
        <td></td>
      <td width="158"><input type="submit" name="Submit" value="Enviar" /></td>
      <td width="104">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
