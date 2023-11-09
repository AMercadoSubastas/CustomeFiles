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

function pasaValor(form)

{ 
//alert (form1.remate.value);
var comprobante = factura.tcomp.value;  // Nuemro de remate
var serie = factura.serie.value; // Tipo de industria
var factnum = factura.num_factura.value; // Codigo de cliente
var fecha_fact    = factura.fecha_factura.value; // Direccion del remate
var remate    = factura.remate_num.value; // Direccion del remate

//var remate = factura.select.options[factura.select.selectedIndex].value; // Codigo del pais
//formulario.select.options[formulario.select.selectedIndex].value
//alert (comprobante );
//alert (serie );
//alert (factnum );
//alert (fecha_fact );
//alert (remate);
//var codcli = form1.codcli.value;

formulario2.comprobante.value = comprobante;
formulario2.serie.value = serie;
formulario2.factnum.value = factnum;
formulario2.fecha_fact.value = fecha_fact;
formulario2.remate.value = remate;
formulario2.submit()
//formulario2.industria.value = industria;
//formulario2.codcli.value = codcli;

//alert (formulario2.carga.value);
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
<form id="factura" name="factura" method="post" action="">
  <table width="640" border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#003366">
    <tr>
      <td colspan="2" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_facturas.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="2" valign="top" bgcolor="#003366"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="22%" height="20" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Tipo comprobante </span></td>
          <td width="24%"><select name="tcomp" id="tcomp" >
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
          <td width="7%">&nbsp;</td>
          <td width="13%" class="ewTableHeader">&nbsp;Serie</td>
          <td width="34%"><select name="serie" id="serie">
          </select>          </td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num Factura </td>
          <td><input name="num_factura" type="num_factura" class="phpmakerlist" id="ncomp" /></td>
          <td>&nbsp;</td>
          <td class="ewTableHeader">&nbsp;Fecha fact </td>
          <td><input name="fecha_factura" type="text" id="fecha_factura" />
         <a href="javascript:showCal('Calendar4')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
        </tr>
        
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num. remate </td>
          <td><input name="remate_num" type="text" id="remate_num" /></td>
          <td><img src="images/ver_lotes.gif" width="70" height="20" onClick="pasaValor(this.form)" /></td>
          <td colspan="2" rowspan="4" valign="top" bgcolor="#0094FF" ><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#003366">
            <tr>
              <td colspan="2" bgcolor="#0094FF" align="center"><img src="images/cond_pago.gif" width="150" height="30" /></td>
            </tr>
            <tr>
              <td width="48%" bgcolor="#0094FF">&nbsp;&nbsp;<span class="ewTableHeader">Contado</span></td>
              <td width="52%" bgcolor="#0094FF"><input type="radio" name="GrupoOpciones1" value="opci&oacute;n" /></td>
            </tr>
            <tr>
              <td bgcolor="#0094FF">&nbsp;&nbsp;<span class="ewTableHeader">Pendiente de pago</span></td>
              <td bgcolor="#0094FF"><input type="radio" name="GrupoOpciones1" value="opci&oacute;n" /></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td height="9" class="ewTableHeader">Lugar del remate </td>
          <td><input name="lugar_remate" type="text" id="lugar_remate" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="10" class="ewTableHeader"> Cliente </td>
          <td><select name="codnum" id="codnum">
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">Fecha de remate</td>
          <td><input name="fecha_remate" type="text" size="12" value="<?php echo $fecha_est ?>"/></td>
          <td>&nbsp;</td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2"  background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">
        <tr>
          <td width="11%" height="24" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center">Lote</div></td>
          <td width="67%" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center">Descripci&oacute;n</div></td>
          <td width="22%" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center">Importe</div></td>
		 <td width="22%" colspan="3" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center"></div></td>	  
        </tr>
        <tr>
          <td bgcolor="#0094FF"><input name="textfield" type="text" size="5" /></td>
          <td bgcolor="#0094FF"><input name="lote" type="text" class="phpmaker" id="lote" size="55" /></td>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="15" /></td>
		  <td bgcolor="#0094FF" width="33%"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="33%"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="33%"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="textfield2" type="text" size="5" /></td>
          <td bgcolor="#0094FF"><input name="textfield9" type="text" class="phpmaker" size="55" /></td>
          <td bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
		  <td bgcolor="#0094FF" width="33%"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="33%"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="33%"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="textfield3" type="text" size="5" /></td>
          <td bgcolor="#0094FF"><input name="textfield9" type="text" class="phpmaker" size="55" /></td>
          <td bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
		  <td colspan="3" bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="textfield4" type="text" size="5" /></td>
          <td bgcolor="#0094FF"><input name="textfield9" type="text" class="phpmaker" size="55" /></td>
          <td bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
		  <td colspan="3" bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="textfield5" type="text" size="5" /></td>
          <td bgcolor="#0094FF"><input name="textfield9" type="text" class="phpmaker" size="55" /></td>
          <td bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
		  <td colspan="3" bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="textfield6" type="text" size="5" /></td>
          <td bgcolor="#0094FF"><input name="textfield9" type="text" class="phpmaker" size="55" /></td>
          <td bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
		  <td colspan="3" bgcolor="#0094FF"><input name="textfield10" type="text" size="15" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td width="71" height="20" valign="top" class="ewTableHeader">&nbsp;Leyenda</td>
      <td width="562" rowspan="3" valign="top"><textarea name="textarea" cols="75" rows="4"></textarea></td>
    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#003366"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        <tr>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Comision </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">R. G. 3337 </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Total </div></td>
        </tr>
        <tr>
          <td><input name="textfield7" type="text" size="10" /></td>
          <td><input name="textfield73" type="text" size="10" /></td>
          <td><input name="textfield74" type="text" size="10" /></td>
          <td><input name="textfield75" type="text" size="10" /></td>
          <td><input name="textfield76" type="text" size="10" /></td>
          <td><input name="textfield77" type="text" size="10" /></td>
          <td><input name="textfield78" type="text" size="10" /></td>
        </tr>
        <tr>
          <td><input name="textfield79" type="text" size="10" /></td>
          <td><input name="textfield710" type="text" size="10" /></td>
          <td><input name="textfield711" type="text" size="10" /></td>
          <td><input name="textfield712" type="text" size="10" /></td>
          <td><input name="textfield713" type="text" size="10" /></td>
          <td><input name="textfield714" type="text" size="10" /></td>
          <td><input name="textfield715" type="text" size="10" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#ECE9D8">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td><div align="center">
            <input type="submit" name="Submit" value="Salir" />
          </div></td>
          <td><div align="center">
            <input type="submit" name="Submit2" value="Imprimir" />
          </div></td>
          <td><div align="center">
            <input type="submit" name="Submit3" value="Grabar" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>
 <script type="text/javascript"> 

chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('tcomp','serie','includes/getserie.php'); 
//chainedSelects.addChain('ncomp','datos','includes/getremate.php'); 

chainedSelects.init(); 

</script> 
	 <form name="formulario2"  method="post" action="carga_lotes_fact.php">
		  <input type="hidden"  name="comprobante" >
		  <input type="hidden"  name="serie" >
		  <input type="hidden"  name="factnum" >
		  <input type="hidden"  name="fecha_fact" >
		  <input type="hidden"  name="remate" >
		
         
		  </form>
</body>
</html>
<?php
mysql_free_result($tipo_comprobante);

mysql_free_result($num_remates);

//mysql_free_result($serie);
?>

