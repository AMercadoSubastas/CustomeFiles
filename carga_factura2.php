<?php require_once('Connections/amercado.php'); ?>
<?php
mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

// $nivel = 9 ;
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
<?php include "header.php" ;
echo $nivel;
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

	<script type="text/javascript" src="AJAX/ajax.js"></script>
	<script type="text/javascript">
	
	var ajax = new sack();
	var currentClientID=false;
	
	var ajax1 = new sack();
	var currentLoteID=false;
//	alert(currentlote);
	//alert(currentlote);
	function getClientData()
	{
		var clientId = document.getElementById('remate_num').value.replace(/[^0-9]/g,'');
			alert (clientId);
		if( clientId!=currentClientID){
		alert()
			currentClientID = clientId
			ajax.requestFile = 'getFact.php?getClientId='+clientId;	// Specifying which file to get
			ajax.onCompletion = showClientData;	// Specify function that will be executed after file has been found
			ajax.runAJAX();		// Execute AJAX function			
		}
		
		
	}
	function getLoteData()
	{
		var loteId = document.getElementById('lote').value ;
		alert ("LOT" +document.getElementById('lote').value);
	   alert("LoteID "+ loteId);
		
		if( loteId!=currentLoteID){
	    alert(currentLoteID);
		currentLoteID = loteId;
		alert("Current "+currentLoteID);
			ajax1.requestFile = 'getlote.php?getClientId='+loteId;	// Specifying which file to get
			ajax1.onCompletion = showLoteData;	// Specify function that will be executed after file has been found
			ajax1.runAJAX();		// Execute AJAX function			
		}
		
	}
	function showClientData()
	{
		var formObj = document.forms['factura'];	
		eval(ajax.response);
	}
	function showLoteData()
	{
		var formObj1 = document.forms['factura'];	
		
		eval(ajax1.response);
	}
	
	function initFormEvents()
	{
		document.getElementById('remate_num').onblur = getClientData;
		document.getElementById('remate_num').focus();
		//alert("CLIENTE DATA"+getClientData);
		document.getElementById('lote').onblur = getLoteData;
		document.getElementById('lote').focus();
		alert (document.getElementById('lote'));
		//alert("LOTE DATA"+getLoteData);
	}
	
	
	window.onload = initFormEvents;
	</script>

 
 



<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
</head>

<body>

<form id="factura" name="factura" method="post" action="">
  <table width="640" border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#003366">
    <tr>
      <td colspan="3" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_facturas.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="3" valign="top" bgcolor="#003366"><table width="100%" border="0" cellspacing="1" cellpadding="1">
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
              <td width="48%" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Contado</span></td>
              <td width="52%" bgcolor="#0094FF"><input name="GrupoOpciones1" type="radio" value="opci&oacute;n" checked="checked" /></td>
            </tr>
            <tr>
              <td bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Pendiente de pago</span></td>
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
      <td colspan="3"  background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
	  
	   
	  <table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">
        <tr>
          <td width="39" rowspan="2" class="ewTableHeader">
              <div align="center">Lote</div></td>
          <td width="369" rowspan="2" class="ewTableHeader">
              <div align="center">Descripci&oacute;n</div></td>
          <td width="48" rowspan="2" class="ewTableHeader"><div align="center">Com</div></td>
          <td width="60" rowspan="2" class="ewTableHeader">
              <div align="center">Importe</div></td>
		 <td height="24" colspan="3" class="ewTableHeader"><div align="center">Impuestos</div></td>	  
        </tr>
        <tr>
          <td height="15" class="ewTableHeader"> <div align="center">10.5 </div></td>
          <td class="ewTableHeader"><div align="center">21</div></td>
          <td class="ewTableHeader">RG 33337 </td>
        </tr>
        
		<tr>
          <td bgcolor="#0094FF" rowspan="15"><iframe id="tabiframe"  frameborder="0"  scrolling="no" src="lotes.html" width="39px" height="343px"></iframe></td>
          <td bgcolor="#0094FF"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="40" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision" type="hidden" id="comision" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="40" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision1" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision1" type="hidden" id="comision1" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe1" type="text" id="importe1" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="40" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision2" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision2" type="hidden" id="comision2" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		  <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="40" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision3" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision3" type="hidden" id="comision3" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		  <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="40" /></td>
           <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision4" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision4" type="hidden" id="comision4" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		  <tr>
         
          <td bgcolor="#0094FF"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="40" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision5" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision5" type="hidden" id="comision5" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="40" /></td>
           <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision6" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision6" type="hidden" id="comision6" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe6" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="40" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision7" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision7" type="hidden" id="comision7" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="40" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision8" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision8" type="hidden" id="comision6" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="40" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision9" type="text" id="comision" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision9" type="hidden" id="comision6" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="40" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision10" type="text" id="comision10" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision10" type="hidden" id="comision10" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
        
          <td bgcolor="#0094FF"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="40" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision11" type="text" id="comision11" size="4" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision11" type="hidden" id="comision11" size="4" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
        
          <td bgcolor="#0094FF"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="40" /></td>
          <td bgcolor="#0094FF"><input name="comision12" type="text" id="comision12" size="4" /></td>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
		 <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="40" /></td>
          <td bgcolor="#0094FF"><input name="comision13" type="text" id="comision13" size="4" /></td>
          <td bgcolor="#0094FF"><input name="importe2" type="text" id="importe2" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr> <tr>
          
          <td bgcolor="#0094FF"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="40" /></td>
          <td bgcolor="#0094FF"><input name="comision14" type="text" id="comision14" size="4" /></td>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="8" /></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox" value="checkbox" />
		    </div></td>
		  <td bgcolor="#0094FF" width="29"><div align="center">
		    <input type="checkbox" name="checkbox2" value="checkbox" />
		    </div></td>
          <td bgcolor="#0094FF" width="49"><div align="center">
            <input type="checkbox" name="checkbox3" value="checkbox" />
          </div></td>
        </tr>
      </table> 
      </td>
    </tr>
    <tr>
      <td width="71" height="20" valign="top" class="ewTableHeader">&nbsp;Leyenda</td>
      <td width="280" rowspan="3" valign="top"><textarea name="textarea" cols="55" rows="4"></textarea></td>
      <td width="281" rowspan="3" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#003366"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
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
      <td colspan="3" bgcolor="#ECE9D8">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
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
