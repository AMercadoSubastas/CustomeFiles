<?php
require_once('funcion_mysqli_result.php'); 
require_once('Connections/amercado.php');
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM `remates` ORDER BY `ncomp` desc";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>

</head>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<body>
<form  name="libros" class="container" method="post" action="rp_lotes_xls.php">
  <table width="392" height="179" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
    </tr>
    <tr>
      <td width="181" height="16">&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    
    <tr>
      <td width="234">Remate N&uacute;mero : </td>
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
      
    </tr>    <tr>
    <td width="234">&nbsp;</td>
      <td width="420"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
