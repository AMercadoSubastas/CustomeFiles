<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php

require_once('Connections/amercado.php');  
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM `remates` ORDER BY `ncomp` desc";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
//echo $totalRows_Recordset1;
 ?>
<body>
<form id="form1" name="form1"  action="aliquidacion.php" method="post">
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
