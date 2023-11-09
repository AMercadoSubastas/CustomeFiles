<?php require_once('Connections/amercado.php'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
<?php require_once('Connections/amercado.php'); 
  include_once "ewcfg50.php" ?>
<?php include_once "ewmysql50.php" ?>
<?php include_once "phpfn50.php" ?>
<?php include_once  "userfn50.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php $comprobante = $_POST['comprobante'];
$serie = $_POST['serie'];
$factnum = $_POST['factnum'];
$fecha_fact = $_POST['fecha_fact'];
$remate = $_POST['remate'];
$remate_lote = $_POST['remate'];
//echo "Remate".$remate."<br>";
?>
<?php
mysqli_select_db($amercado, $database_amercado);
$query_tipo_com = "SELECT * FROM tipcomp WHERE codnum ='$comprobante'";
$tipo_com = mysqli_query($amercado, $query_tipo_com) or die(mysqli_error($amercado));
$row_tipo_com = mysqli_fetch_assoc($tipo_com);
$totalRows_tipo_com = mysqli_num_rows($tipo_com);
?>
<?php $query_series = "SELECT * FROM series WHERE codnum ='$serie'";
$series = mysqli_query($amercado, $query_series,) or die(mysqli_error($amercado));
$row_series = mysqli_fetch_assoc($series);
$totalRows_series = mysqli_num_rows($series);

//$query_num_remates = "SELECT * FROM remates codnum ='$remate'";
//$num_remates = mysqli_query($amercado, $query_num_remates) or die(mysqli_error($amercado));
//$row_num_remates = mysqli_fetch_assoc($num_remates);
//$totalRows_num_remates = mysqli_num_rows($num_remates);
// echo $comprobante."<br>" ;
// echo $serie."<br>" ;
// echo $factnum."<br>" ;
// echo $fecha_fact."<br>" ;
// echo $remate."<br>" ;

?>

<script language="javascript">
function ver_check (form)
{ 
var cant_lotes = factura.numlote.length ;

//alert (cant_lotes) ;
var contador = 0 ;
num_lotes = new Array();
//var num_lotes[cant_lotes] ;
for (contador = 0 ; contador < cant_lotes; contador++)
{
   //alert("hola");
 
if (factura.numlote[contador].checked==true) {
  // alert(factura.numlote[contador].value) ;
    num_lotes[contador] = factura.numlote[contador].value;
	 alert ("Segundo Alert "+num_lotes[contador]);
 }
}


}
</script>

<?php
mysqli_select_db($amercado, $database_amercado);
$query_remate = "SELECT * FROM remates WHERE ncomp='$remate'";
$remate = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$row_remate = mysqli_fetch_assoc($remate);
$totalRows_remate = mysqli_num_rows($remate);
//echo "Total REmate".$totalRows_remate."<br>";
//echo "Remate ".$remate."<br>";
mysqli_select_db($amercado, $database_amercado);
$query_verlotes = "SELECT * FROM lotes WHERE codrem='$remate_lote'";
$verlotes = mysqli_query($amercado, $query_verlotes) or die(mysqli_error($amercado));
$row_verlotes = mysqli_fetch_assoc($verlotes);
$totalRows_verlotes = mysqli_num_rows($verlotes);
//echo $totalRows_verlotes;
?>



</head>

<body>
<form id="factura" name="factura" method="post" action="">
  <table width="640" border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#003366">
    <tr>
      <td width="633" colspan="2" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_lotes_fac.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top" bgcolor="#003366"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="21%" height="20" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Tipo comprobante </span></td>
          <td width="23%"><input name="remito" value="<?php echo $row_tipo_com['descripcion']; ?>" readonly=""/>      </td>
          <td width="11%">&nbsp;</td>
          <td width="16%" class="ewTableHeader">&nbsp;Serie</td>
          <td width="29%"><input name="serie"  value="<?php echo $row_series['descripcion']; ?>" size="25" readonly=""/>         </td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num Factura </td>
          <td><input name="num_factura" type="num_factura" class="phpmakerlist" id="ncomp" value="<?php echo $factnum ?>" readonly=""/></td>
          <td>&nbsp;</td>
          <td class="ewTableHeader">&nbsp;Fecha fact </td>
          <td><input name="fecha_factura" type="text" id="fecha_factura" size="12" value="<?php echo $fecha_fact ?>"readonly=""/>
         <a href="javascript:showCal('Calendar4')"></a></td>
        </tr>
        
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num. remate </td>
          <td><input name="remate_num" type="text" id="remate_num" readonly="" value="<?php echo $row_remate['ncomp']; ?>"/></td>
          <td>&nbsp;</td>
          <<td height="20" class="ewTableHeader">Fecha de remate</td>
          <td colspan="2"><input name="fecha_remate" type="text" size="12" value="<?php echo $row_remate['fecreal']; ?>" readonly=""/></td>
        </tr>
        <tr>
          <td height="9" class="ewTableHeader">Lugar del remate </td>
          <td colspan="4"><input name="lugar_remate" type="text" id="lugar_remate" value="<?php echo $row_remate['direccion']; ?>" size="30" /></td>
        </tr>
        
        
      </table></td>
    </tr>
    <tr>
      <td colspan="2"  background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">
        <tr>
          <td width="10%" height="24" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center">Lote</div></td>
          <td width="74%" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center">Descripci&oacute;n</div></td>
          <td width="16%" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center">Seleccionar</div></td>	  
        </tr>
       
          <?php do { ?>
            <tr> <td bgcolor="#0094FF"><input name="lotes" type="text" id="lotes" value="<?php echo $row_verlotes['codintlote']; ?>" size="5" /></td>
            <td bgcolor="#0094FF"><input name="lote" type="text" class="phpmaker" id="lote" value="<?php echo $row_verlotes['descor']; ?>" size="75" /></td>
            <td bgcolor="#0094FF" align="center">
			    <input type="checkbox" name="numlote" value="<?php echo $row_verlotes['codnum']; ?>"  />
             </td></tr>
            <?php } while ($row_verlotes = mysqli_fetch_assoc($verlotes)); ?>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#ECE9D8">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
         
          <td><div align="center">
            <input type="submit" name="Submit2" value="Borrar"  />
          </div></td>
          <td><div align="center">
            <input type="submit" name="Submit3" value="Enviar" onclick="ver_check(this.form)"/>
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>

</body>
</html>
<?php
mysql_free_result($tipo_com);

mysql_free_result($remate);

mysql_free_result($verlotes);

mysql_free_result($series);


?>
