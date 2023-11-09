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
    
mysqli_select_db($amercado, $database_amercado);
//$query_Recordset1 = "SELECT * FROM `remates` ORDER BY `ncomp` desc";
$query_Recordset2 = sprintf("SELECT * FROM `tipcomp` WHERE `codnum` in (51,52,57,59) ORDER BY `codnum`");
$Recordset2 = mysqli_query($amercado, $query_Recordset2) or die("ERROR LEYENDO TIPOS DE CBTES");
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);
	
//$usename = $_SESSION[EW_SESSION_USER_PROFILE_USER_NAME];
//$usuario = CurrentUserName();
    $usuario = 1;
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
$usu = 1;
echo $usu;
 ?>
<script language="javascript">
function factura_lotes_A(form)
{
 

	
	//alert(cheque_tercero.cliente_a.value);
	//alert(cheque_tercero.remate_num_a.value);
	form1.submit();
 
}
    </script>
    <script language="javascript">
 
function factura_conc_A(form)
{
 

	
	//alert(cheque_tercero.cliente_a.value);
	//alert(cheque_tercero.remate_num_a.value);
	form4.submit();
 
}
    </script>
<script language="javascript">
 
function nota_credito_A(form)
{
 

	
	//alert(cheque_tercero.cliente_a.value);
	//alert(cheque_tercero.remate_num_a.value);
	form2.submit();
 
}
    </script>
    <script language="javascript">
 
function nota_debito_A(form)
{
 

	
	//alert(cheque_tercero.cliente_a.value);
	//alert(cheque_tercero.remate_num_a.value);
	form3.submit();
 
}
    </script>
<body>
<form id="form1" name="form1" method="post" action="WafcautxlotA.php?usu=<?php echo $usu?>">
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
          <td width="40%" bgcolor="#FFFFFF"><span class="ewTableHeader">&nbsp; &nbsp;</span></td>
          
          <td width="60%"><img src="images/factura_lotes_A.png" width="210" height="70" onclick="factura_lotes_A(this.form)" /></td>
        </tr>
      <tr>
          <tr>
          <td width="40%" bgcolor="#FFFFFF"><span class="ewTableHeader">&nbsp; &nbsp;</span></td>
          
          <td width="60%"><img src="images/factura_conc_A.png" width="210" height="70" onclick="factura_conc_A(this.form)" /></td>
        </tr>
      <tr>
          <td width="40%" bgcolor="#FFFFFF"><span class="ewTableHeader">&nbsp; &nbsp;</span></td>
          
          <td width="60%"><img src="images/nota_credito_A.png" width="210" height="70" onclick="nota_credito_A(this.form)" /></td>
        </tr>
      <tr>
          <td width="40%" bgcolor="#FFFFFF"><span class="ewTableHeader">&nbsp; &nbsp;</span></td>
          
          <td width="60%"><img src="images/nota_debito_A.png" width="210" height="70" onclick="nota_debito_A(this.form)" /></td>
        </tr>
    <tr>
      <td width="234">&nbsp;</td>
		
      <td width="420">&nbsp;</td>
    </tr>
    <tr>
      <td width="234">&nbsp;</td>
      
    </tr>
  </table>
</form>
    
    <form id="form2" name="form2" method="post" action="WancA4.php?usu=<?php echo $usu?>">
    </form>
    <form id="form3" name="form3" method="post" action="WandA4.php?usu=<?php echo $usu?>">
    </form>
    <form id="form4" name="form4" method="post" action="WafcautxconcA.php?usu=<?php echo $usu?>">
    </form>
</body>
</html>
