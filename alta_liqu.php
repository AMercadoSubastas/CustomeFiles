<?php
require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado); 

$query_Recordset1 = "SELECT * FROM `remates` ORDER BY `ncomp` desc";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("ERROR");
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
//echo $totalRows_Recordset1;
$cod_usuario = $_SESSION['id'];
echo "USUARIO ".$cod_usuario."  ";
$usu = $cod_usuario;
echo $usu;
 ?>
<body>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<form id="libros" name="libros"  action="aliquidacion.php?usu=<?php echo $usu?>&fecha_desde=<?php echo $fecha_desde?>&fecha_hasta=<?php echo $fecha_hasta?>" method="post">
  <table width="597" height="173" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/nada.jpg" align="center"><img src="images/nada.gif" width="183" height="30" /></td>
    </tr>
    <tr>
      <td width="348">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="348">Remate N&uacute;mero : </td>
      <td width="242"><select name="remate" id="remate">
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
      <td width="348">&nbsp;</td>
      <td width="242">&nbsp;</td>
    </tr>
    <tr>
      <td width="348">&nbsp;</td>
      <td width="242"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
