<?php require_once('Connections/amercado.php'); ?>
<?php
mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);


$query_num_remates = "SELECT * FROM remates";
$num_remates = mysqli_query($amercado, $query_num_remates) or die(mysqli_error($amercado));
$row_num_remates = mysqli_fetch_assoc($num_remates);
$totalRows_num_remates = mysqli_num_rows($num_remates);

//mysqli_select_db($amercado, $database_amercado);
//$query_serie = "SELECT * FROM series WHERE tipcom=tipcomp.codnum";
//$serie = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));
//$row_serie = mysqli_fetch_assoc($serie);
//$totalRows_serie = mysqli_num_rows($serie);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php require_once('Connections/amercado.php');  ?>
 <?php include_once "ewcfg50.php" ?>
<?php include_once "ewmysql50.php" ?>
<?php include_once "phpfn50.php" ?>
<?php include_once  "userfn50.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
//header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache"); // HTTP/1.0
?>
<?php include "header.php" ?>
<script language="javascript">
 function cambia_fecha(form)

{ 
var fecha = remate.fecest1.value;
var ano = fecha.substring(6,10);
var mes = fecha.substring(3,5);
var dia = fecha.substring(0,2);

var hora = remate.horaest.value;
//alert (ano);
//alert (mes);
//alert (dia);
var fecha1 = ano+"-"+mes+"-"+dia+" "+hora;
//alert (fecha1);
//alert(fecha + hora) ;
remate.fecest.value = fecha1;

}
</script>

<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>

 <script type="text/javascript" src="../js/ajax.js"></script>    

 <script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script> 
 	<script tpe="text/javascript">
 	DHTMLSuite.include("chainedSelect");
 	</script>

 
 



<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="factura" name="factura" method="post" action="selec_factura.php">
  <table width="300" border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#003366">
    <tr>
      <td width="1124" colspan="2" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_facturas.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top" bgcolor="#003366"><table width="300" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td height="20" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Tipo comprobante </span></td>
          <td><select name="tcomp" id="tcomp" >
            <option value="">Tipo comprobante</option>
            <?php
do {  
?>
            <option value="<?php echo $row_tipo_comprobante['codnum']?>"><?php echo $row_tipo_comprobante['descripcion']?></option>
            <?php
} while ($row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante));
  $rows = mysqli_num_rows($tipo_comprobante);
  if($rows > 0) {
      mysqli_data_seek($tipo_comprobante, 0);
	  $row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
  }
?>
          </select>          </td>
          </tr>
        <tr>
          <td height="20" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Serie</span></td>
          <td><select name="serie" id="serie">
          </select></td>
          </tr>
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num Factura </td>
          <td><input name="factura" type="text" class="phpmakerlist" id="ncomp" /></td>
          </tr>
        <tr>
          <td height="20" class="ewTableHeader">Fecha fact </td>
          <td><input name="fecha_factura" type="text" id="fecha_factura" />
         <a href="javascript:showCal('Calendar4')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
        </tr>
        
        
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num. remate </td>
          <td><select name="ncomp" id="ncomp">
            <option value="value">Seleccione remate</option>
            <?php
do {  echo $row_num_remates['direccion']."<br>";
echo $row_num_remates['ncomp']."<br>";
$fecha_est = $row_num_remates['fecest'] ;
						      $fecha_real = $row_num_remates['fecreal'] ;
							  $direccion_remate = $row_num_remates['direccion'] ;
?>              
            <option value="<?php echo $row_num_remates['codnum']?>"><?php echo $row_num_remates['ncomp']?></option>
			               <?php $fecha_est = $row_num_remates['fecest'] ;
						      $fecha_real = $row_num_remates['fecreal'] ;
							  $direccion_remate = $row_num_remates['direccion'] ;
           
} while ($row_num_remates = mysqli_fetch_assoc($num_remates));
  $rows = mysqli_num_rows($num_remates);
  if($rows > 0) {
      mysqli_data_seek($num_remates, 0);
	  $row_num_remates = mysqli_fetch_assoc($num_remates);
  }
?>
          </select></td>
          </tr>
        
        
        <tr>
          <td height="20" colspan="2" bgcolor="#003366" >&nbsp;</td>
          </tr>
      </table></td>
    </tr>
    
    
    
    
    <tr>
      <td colspan="2" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td><div align="center">
            <input type="submit" name="Submit3" value="Seguir" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
 <script type="text/javascript"> 

chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('tcomp','serie','includes/getserie.php'); 
chainedSelects.addChain('ncomp','datos','includes/getremate.php'); 

chainedSelects.init(); 

</script> 
</body>
</html>
<?php
mysql_free_result($tipo_comprobante);

mysql_free_result($num_remates);

//mysql_free_result($serie);
?>
