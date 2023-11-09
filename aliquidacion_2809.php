<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "src/userfn.php";
//echo " ========================= ENTRE EN ALIQUIDACION ========================================  ";
//sleep(20);
//echo " ========================= ENTRE EN ALIQUIDACION 2 ========================================  ";
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM rubros";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$tipo_de_iva = 1;
$total_remate = 0.0;
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "liquidacion")) {
	// SI YA GRABE ENTRO ACA ===============================================
	$fecha_liquidacion = GetSQLValueString($_POST['fecha_liq'], "date");
	$fecha_liquidacion = substr($fecha_liquidacion,7,4)."-".substr($fecha_liquidacion,4,2)."-".substr($fecha_liquidacion,1,2);
	$tcomp = $_POST['tcomp'];
	$serie = $_POST['serie'];
	$liquidacion = $_POST['liquidacion'];

	if ($liquidacion<100) {
		$doc ="000000".$liquidacion;
	}
	if ($liquidacion<1000 and $liquidacion>99) {
		$doc ="00000".$liquidacion;
	}
	if ($liquidacion<10000 and $liquidacion>999) {
		$doc ="0000".$liquidacion;
	}
	if ($tcomp==3 and $serie==2) {
		$nrodoc = 'A0001-'.$doc;

	} 
	else {
		$nrodoc = 'B0001-'.$doc;
	}


	$insertSQL = sprintf("INSERT INTO liquidacion (tcomp, serie, cliente, codrem, fecharem, totremate, totneto1, totiva21, subtot1, totneto2, totiva105, subtot2, totacuenta, totgastos, totvarios, saldoafav , ncomp ,  fechaliq , codpais , codprov , codloc , rubro ,nrodoc ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s , '$fecha_liquidacion' , %s, %s, %s,%s ,'$nrodoc')",
                       	GetSQLValueString($_POST['tcomp'], "int"),
                       	GetSQLValueString($_POST['serie'], "int"),
                       	GetSQLValueString($_POST['num_cliente'], "int"),
                       	GetSQLValueString($_POST['remate_num'], "int"),
                       	GetSQLValueString($_POST['fecha_remate'], "date"),
                       	GetSQLValueString($_POST['importe_total'], "double"),
                       	GetSQLValueString($_POST['neto21'], "double"),
                       	GetSQLValueString($_POST['iva21'], "double"),
                       	GetSQLValueString($_POST['total21'], "double"),
                       	GetSQLValueString($_POST['neto105'], "double"),
                       	GetSQLValueString($_POST['iva105'], "double"),
                       	GetSQLValueString($_POST['total105'], "double"),
                       	GetSQLValueString($_POST['acuenta'], "double"),
                       	GetSQLValueString($_POST['gastos_autor'], "double"),
                       	GetSQLValueString($_POST['otros_gastos'], "double"),
                       	GetSQLValueString($_POST['total_general'], "double"),
						GetSQLValueString($_POST['liquidacion'], "int"),
						GetSQLValueString($_POST['codpais'], "int"),
						GetSQLValueString($_POST['codprov'], "int"),
						GetSQLValueString($_POST['codloc'], "int"),
						GetSQLValueString($_POST['rubro'], "int"));
					   
					   

	mysqli_select_db($amercado, $database_amercado);
	$numero_serie = GetSQLValueString($_POST['serie'], "int");
	$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	$liquid_num = GetSQLValueString($_POST['liquidacion'], "int");
	$actualiza = "UPDATE series SET nroact='$liquid_num' WHERE codnum='$numero_serie'";
   
	$actualiza_serie = mysqli_query($amercado, $actualiza) or die(mysqli_error($amercado));
	$actualizafac = sprintf("UPDATE cabfac SET tcompsal=%s , seriesal=%s  , ncompsal = %s  WHERE codrem=%s",
                      	GetSQLValueString($_POST['tcomp'], "int"),
                      	GetSQLValueString($_POST['serie'], "int"),
						GetSQLValueString($_POST['liquidacion'], "int"),
						GetSQLValueString($_POST['remate_num'], "int"));
	$actualiza_fac = mysqli_query($amercado, $actualizafac) or die(mysqli_error($amercado));	

	// Actualizo detalle factura HAY QUE AGREGAR NCRED Y NDEB
	$actualizadet = sprintf("UPDATE detfac  SET tcomsal=%s , seriesal=%s  , ncompsal = %s  WHERE (codrem=%s AND concafac IS NULL)",
                       	GetSQLValueString($_POST['tcomp'], "int"),
                       	GetSQLValueString($_POST['serie'], "int"),
						GetSQLValueString($_POST['liquidacion'], "int"),
						GetSQLValueString($_POST['remate_num'], "int"));
	$actualiza_det = mysqli_query($amercado, $actualizadet) or die(mysqli_error($amercado));
	// fin actualizacion

	$insertGoTo = "liquidacion_ok.php";
	if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    	$insertGoTo .= $_SERVER['QUERY_STRING'];
	}
	header(sprintf("Location: %s", $insertGoTo)); 
}
mysqli_select_db($amercado, $database_amercado);
if (isset($_POST['remate']))
	$rematenum = $_POST['remate'];
else
	$rematenum = $_GET['remate'];
$clientes = 0;
if(isset($_POST['remate']) || isset($_GET['remate'])){  
	$res = mysqli_query("select * from remates where ncomp='$rematenum'") or die(mysqli_error($amercado));
	$clientes = mysqli_result($res,0,4) ;
	$direccion = mysqli_result($res,0,5) ;
	$codpais = mysqli_result($res,0,6) ;
	$codprov = mysqli_result($res,0,7) ;
	$codloc = mysqli_result($res,0,8) ;
	$fremate = mysqli_result($res,0,10) ;
	$inf = mysqli_fetch_array($res);
	$fecha_remate = substr($fremate,8,2).'-'.substr($fremate,5,2).'-'.substr($fremate,0,4);
	$iva_21 = mysqli_query("select * from impuestos") or die(mysqli_error($amercado));
	$procen_iva = (mysqli_result($iva_21,0,1)/100) ;

	//=================================== DESDE ACA ==========================================
	$query_cliente = "SELECT * FROM entidades  WHERE entidades.codnum ='$clientes'";

	$cliente_t     = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
	$row_cliente   = mysqli_fetch_assoc($cliente_t);
	$totalRows_cliente = mysqli_num_rows($cliente_t);
	$nom_clientes  = mysqli_result($cliente_t,0,1) ;
	$tipo_de_iva   = mysqli_result($cliente_t,0,12);

	if ($tipo_de_iva==1) {
		$tserie       = 2 ;
		$tcomprobante = 3 ;
		$numero       = 2 ; 
	} else {
		$tserie       = 13 ;
		$tcomprobante = 31 ;
		$numero       = 13 ;
	}
	//=================================== HASTA ACA ==========================================	
	//echo "TIPO DE IVA".$tipo_de_iva."<br>";
	
	// ACA SUMO TODAS LAS FACTURAS Y NDEB QUE AFECTAN LA LIQUIDACION
	if ($tipo_de_iva== 1) {
		$res1 ="SELECT SUM( `totneto105` ) , SUM( `totiva105` ) , SUM( `totneto105` + `totiva105` ) as total_105 , SUM( `totneto21` ) , SUM( `totneto21` *'$procen_iva' ) , SUM( `totneto21` *'$procen_iva' + `totneto21`)  as total_21 FROM `cabfac` WHERE (`codrem` = '$rematenum' AND `tcomp`!='52'  AND `tcomp`!='57'  AND `tcomp`!='58'  AND `en_liquid` = 1 )" ;
		$query_remate = mysqli_query($amercado, $res1) or die(mysqli_error($amercado));
		$totalRows_remate = mysqli_num_rows($query_remate); 
	}
	else {
		$res1 ="SELECT SUM( `totneto105` ) , SUM( `totiva105` ) , SUM( `totneto105` + `totiva105` ) as total_105 , SUM( `totneto21` ) , SUM( `totneto21` *'$procen_iva' ) , SUM( `totneto21` *'$procen_iva' + `totneto21`)  as total_21 FROM `cabfac` WHERE (`codrem` = '$rematenum' AND `tcomp`!='54' AND `tcomp`!='57'  AND `tcomp`!='58'  AND `en_liquid` = 1 )" ;
		$query_remate = mysqli_query($amercado, $res1) or die(mysqli_error($amercado));
		$totalRows_remate = mysqli_num_rows($query_remate); 

	}
	// ACA SUMO TODAS LAS NCRED QUE AFECTAN LA LIQUIDACION
	if ($tipo_de_iva== 1) {
		$res2 ="SELECT SUM( `totneto105` ) , SUM( `totiva105` ) , SUM( `totneto105` + `totiva105` ) as total_105_2 , SUM( `totneto21` ) , SUM( `totneto21` *'$procen_iva' ) , SUM( `totneto21` *'$procen_iva' + `totneto21`)  as total_21_2 FROM `cabfac` WHERE (`codrem` = '$rematenum' AND (`tcomp`='57' OR `tcomp`='58') AND `en_liquid` = 1 )" ;
		$query_remate2 = mysqli_query($amercado, $res2) or die(mysqli_error($amercado));
		$totalRows_remate2 = mysqli_num_rows($query_remate2); 
	}
	else {
		$res2 ="SELECT SUM( `totneto105` ) , SUM( `totiva105` ) , SUM( `totneto105` + `totiva105` ) as total_105_2 , SUM( `totneto21` ) , SUM( `totneto21` *'$procen_iva' ) , SUM( `totneto21` *'$procen_iva' + `totneto21`)  as total_21_2 FROM `cabfac` WHERE (`codrem` = '$rematenum' AND (`tcomp`='57' OR`tcomp`='58')  AND `en_liquid` = 1 )" ;
		$query_remate2 = mysqli_query($amercado, $res2) or die(mysqli_error($amercado));
		$totalRows_remate2 = mysqli_num_rows($query_remate2); 

	}
	// ========================================================================
	//echo "NCRED AL 10,5 =   ".mysqli_result($query_remate2,0,0)."   ";
	//echo "NCRED AL 21 =   ".mysqli_result($query_remate2,0,0)."   ";
	
	$totneto105 = mysqli_result($query_remate,0,0) - mysqli_result($query_remate2,0,0);
	//echo "FAC AL 10,5 =   ".mysqli_result($query_remate,0,0)."   ";
	//echo "totneto105 =   ".$totneto105."   ";
	$iva105 = mysqli_result($query_remate,0,1) - mysqli_result($query_remate2,0,1);
	$total_105 = mysqli_result($query_remate,0,2) - mysqli_result($query_remate2,0,2);
	$totneto21 = mysqli_result($query_remate,0,3) - mysqli_result($query_remate2,0,3);
	$iva21 = mysqli_result($query_remate,0,4) - mysqli_result($query_remate2,0,4);

	$total_21 = mysqli_result($query_remate,0,5) - mysqli_result($query_remate2,0,5);
	$totneto21 = round($totneto21,2);
	$iva21 = round($iva21,2);
	$iva105 = round($iva105,2);
	$total_21 = round($total_21,2);

	$total_105 = round($total_105,2);
	$total_remate = round($total_105 +$total_21,2);
}
mysqli_select_db($amercado, $database_amercado);
$query_cheques_total = "SELECT SUM( cartvalores.importe) FROM cartvalores WHERE  cartvalores.estado = 'S' AND cartvalores.codrem ='$rematenum'" ;
$cheques_total       = mysqli_query($amercado, $query_cheques_total) or die(mysqli_error($amercado));
$row_cheques_total   = mysqli_fetch_assoc($cheques_total);

$totcheques1  = number_format ($row_cheques_total['SUM( cartvalores.importe)'] , 2 , "," , ".");
$totcheques   = $row_cheques_total['SUM( cartvalores.importe)'] ;
$totalcheques = round($totcheques,2);

if ($tipo_de_iva== 1) {
	$query_factura_total     = "SELECT SUM( cabfac.totbruto) FROM cabfac WHERE (cabfac.tcomp = 52) AND cabfac.estado != 'A' AND cabfac.codrem ='$rematenum' AND cabfac.en_liquid = 1";
	$factura_total           = mysqli_query($amercado, $query_factura_total) or die(mysqli_error($amercado));
	$row_factura_total 	     = mysqli_fetch_assoc($factura_total);
	$totalRows_factura_total = mysqli_num_rows($factura_total);
	$totfactura1             = number_format ($row_factura_total['SUM( cabfac.totbruto)'] , 2 , "," , ".");
	$totfactura              = $row_factura_total['SUM( cabfac.totbruto)'];
	$totfactura              = round($totfactura,2);
}
else {
	$query_factura_total     = "SELECT SUM( cabfac.totbruto) FROM cabfac WHERE (cabfac.tcomp = 54)  AND cabfac.estado != 'A' AND cabfac.codrem ='$rematenum'  AND cabfac.en_liquid = 1" ;
	$factura_total           = mysqli_query($amercado, $query_factura_total) or die(mysqli_error($amercado));
	$row_factura_total       = mysqli_fetch_assoc($factura_total);
	$totalRows_factura_total = mysqli_num_rows($factura_total);
	$totfactura1             = number_format ($row_factura_total['SUM( cabfac.totbruto)'] , 2 , "," , ".");
	$totfactura              = $row_factura_total['SUM( cabfac.totbruto)'];
	$totfactura              = round($totfactura,2);

}
// ACA LE PONGO UN ECHO 
//echo "Total Factura".$totfactura1."<br>";

$totalgastos   = $totalcheques+$totfactura;
$total_general = round(($total_remate-$totalgastos),2);
$cliente_t = 0;
$query_cliente = "SELECT * FROM entidades  WHERE entidades.codnum ='$clientes'";

$cliente_t     = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente   = mysqli_fetch_assoc($cliente_t);
$totalRows_cliente = mysqli_num_rows($cliente_t);
$nom_clientes  = $row_cliente['razsoc']; //mysqli_result($cliente_t,0,1) ;
$tipo_de_iva   = $row_cliente['tipoiva'];//mysqli_result($cliente_t,0,12);

if ($tipo_de_iva==1) {
	$tserie       = 2 ;
	$tcomprobante = 3 ;
	$numero       = 2 ; 
} else {
	$tserie       = 13 ;
	$tcomprobante = 31 ;
	$numero       = 13 ;
}


$query_serie = "SELECT * FROM series WHERE  codnum ='$numero'";
$serie       = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));

$totalRows_serie = mysqli_num_rows($serie);

while ($row_serie = mysqli_fetch_array($serie)) {
	$liquidacion = 1+$row_serie['nroact'];
}

$query_impuesto     = "SELECT * FROM impuestos";
$impuesto           = mysqli_query($amercado, $query_impuesto) or die(mysqli_error($amercado));
$row_impuesto       = mysqli_fetch_assoc($impuesto);
$totalRows_impuesto = mysqli_num_rows($impuesto);
$iva_21_desc        = mysqli_result($impuesto,0,2);
$iva_21_porcen      = mysqli_result($impuesto,0,1);


$iva_15_desc        = mysqli_result($impuesto,1,2);
$iva_15_porcen      = mysqli_result($impuesto,1,1);

//include_once "ewcfg50.php" ;
//include_once "ewmysql50.php" ;
//include_once "phpfn50.php" ;
//include_once  "userfn50.php" ;
//include_once "usuariosinfo.php"; 

//include "header.php" ;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../js/ajax.js"></script>    

<script type="text/javascript" src="../js/dhtmlSuite-common.js"></script>
<script type="text/javascript" src="../js/dhtmlSuite-dynamicContent.js"></script>
<script type="text/javascript" src="../js/dhtmlSuite-windowWidget.js"></script>
<script type="text/javascript" src="../js/dhtmlSuite-dragDropSimple.js"></script>
<script type="text/javascript" src="../js/dhtmlSuite-resize.js"></script> 
<SCRIPT type="text/javascript">
var DHTML_SUITE_THEME_FOLDER = 'themes/';
</SCRIPT> 
<SCRIPT type="text/javascript">
var DHTML_SUITE_THEME = 'zune';
</SCRIPT> 
<script type="text/javascript" src="AJAX/ajax.js"></script>
</script>
<form name="cheque_tercero" action="cheques_terceros_1.php" method="post">
      <input name="tcomp_nombre_a" type="hidden">
      <input name="tcomp_a" type="hidden">
      <input name="serie_nombre_a" type="hidden">
      <input name="serie_a" type="hidden">
      <input name="liquidacion_a" type="hidden">
      <input name="cliente_a" type="hidden">
      <input name="remate_num_a" type="hidden">
      <input name="fecha_remate_a" type="hidden">
      <input name="lugar_remate_a" type="hidden">
      <input name="importe_total_a" type="hidden">
      <input name="neto105_a"  type="hidden">
      <input name="iva105_a" type="hidden">
      <input name="total105_a" type="hidden">
      <input name="neto21_a" type="hidden">
      <input name="iva21_a" type="hidden">
      <input name="total21_a" type="hidden">
      <input name="acuenta_a" type="hidden">
      <input name="gastos_autor_a" type="hidden">
      <input name="otros_gastos_a" type="hidden">
      <input name="total_resta_a" type="hidden">
      <input name="total_gene_a" type="hidden">
</form>
 
 <form name="gastos_autorizados" action="gastos_autorizados.php">
      <input name="tcomp_b" type="hidden">
      <input name="serie_b" type="hidden">
      <input name="liquidacion_b" type="hidden">
      <input name="remate_num_b" type="hidden">
      <input name="acuenta_b" type="hidden">
      <input name="gastos_autor_b" type="hidden">
      <input name="otros_gastos_b" type="hidden">
      <input name="total_resta_b" type="hidden">
      <input name="total_gene_b" type="hidden">
 </form>
 

 
 <script language="javascript">
 
function manda_cheque_terceros(form)
{
 

	cheque_tercero.tcomp_nombre_a.value = liquidacion.tcomp_nombre.value ;
	cheque_tercero.tcomp_a.value = liquidacion.tcomp.value ;
	cheque_tercero.serie_nombre_a.value = liquidacion.serie_nombre.value ;
	cheque_tercero.serie_a.value = liquidacion.serie.value ;
	cheque_tercero.liquidacion_a.value = liquidacion.liquidacion.value ;
	cheque_tercero.cliente_a.value = liquidacion.cliente.value ;
	cheque_tercero.fecha_remate_a.value = liquidacion.fecha_remate.value ;

	cheque_tercero.remate_num_a.value = liquidacion.remate_num.value ;
	cheque_tercero.lugar_remate_a.value = liquidacion.lugar_remate.value ;
	cheque_tercero.importe_total_a.value = liquidacion.importe_total.value ;
	cheque_tercero.neto105_a.value = liquidacion.neto105.value ;
	cheque_tercero.iva105_a.value = liquidacion.iva105.value ;
	cheque_tercero.neto21_a.value = liquidacion.neto21.value ; 
	cheque_tercero.iva21_a.value = liquidacion.iva21.value ;
	cheque_tercero.total105_a.value = liquidacion.total105.value ;
	cheque_tercero.total21_a.value = liquidacion.total21.value ;
	cheque_tercero.acuenta_a.value = liquidacion.acuenta.value ;
	cheque_tercero.gastos_autor_a.value = liquidacion.gastos_autor.value ;
	cheque_tercero.otros_gastos_a.value = liquidacion.otros_gastos.value ;
	cheque_tercero.total_resta_a.value = liquidacion.total_resta.value ;
	cheque_tercero.total_gene_a.value = liquidacion.total_general.value ;
	//alert(cheque_tercero.cliente_a.value);
	//alert(cheque_tercero.remate_num_a.value);
	cheque_tercero.submit();
 
}

function manda_gastos_autorizados(form)
{

	gastos_autorizados.tcomp_b.value = liquidacion.tcomp.value ;
	gastos_autorizados.serie_b.value = liquidacion.serie.value ;
	gastos_autorizados.liquidacion_b.value = liquidacion.liquidacion.value ;
	gastos_autorizados.remate_num_b.value = liquidacion.remate_num.value ;
	gastos_autorizados.submit();
 
} 

function otros_medios1(form)
{

	otros_medios.tot_general.value = liquidacion.total_general.value ;
	otros_medios.liquidacion.value = liquidacion.liquidacion.value ;
	otros_medios.remate.value = liquidacion.remate_num.value ;
	otros_medios.submit();
 
}
</script>
<SCRIPT type="text/javascript">
var windowModel = new DHTMLSuite.windowModel();
windowModel.createWindowModelFromMarkUp('myWindow');
var colorWindow = new DHTMLSuite.windowWidget();
colorWindow.setLayoutThemeWindows();
colorWindow.addWindowModel(windowModel);
colorWindow.init();
</SCRIPT> 
<script type="text/javascript">

var ajax = new sack();
var currentClientID=false;
	
function getClientData()
{
	var clientId = document.getElementById('remate_num').value.replace(/[^0-9]/g,'');
	
	if( clientId!=currentClientID){
		currentClientID = clientId
		ajax.onCompletion = showClientData;	// Specify function that will be executed after file has been found
		ajax.runAJAX();		// Execute AJAX function			
	}
}
	
function showClientData()
{
	var formObj = document.forms['liquidacion'];	
	eval(ajax.response);
}
function initFormEvents()
{
	
	var document.liquidacion.otros_gastos.value = 0;
	//alert (document.liquidacion.otros_gastos.value)
		
}		
window.onload = initFormEvents;	

//</script>			
<script language="javascript">


<!--
function gastos (form)
{

	var gastos1 = liquidacion.acuenta.value ;
	//alert (gastos1);
	var gastos2 = liquidacion.gastos_autor.value ;
	//alert (gastos2);
	var total1 = eval(gastos1+('+')+gastos2);
	var totgen =liquidacion.importe_total.value
	//alert (totgen);
	var gastos3 = liquidacion.otros_gastos.value ;
	//alert (gastos3);
	var total = eval(total1+('+')+gastos3);
    // impuesto+('+')+total;
	liquidacion.total_resta.value = total ;
	var total_general = totgen - total  ;
	liquidacion.total_gene.value = total_general ;
 
	//var tot_gen =

}

function otros (form)
{

	var imp_total = liquidacion.importe_total.value;
	var acuenta = liquidacion.acuenta.value;
	var gastos = liquidacion.gastos_autor.value;
	var otros = liquidacion.otros_gastos.value ;
	var total = eval(acuenta+('+')+gastos+('+')+otros); 
	var total_gene = eval(imp_total+('-')+total);

 	liquidacion.total_resta.value = total;
 	liquidacion.total_general.value = total_gene;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
</script> 
<SCRIPT LANGUAGE="JavaScript"> 
//function cerrar(){  
//	window.close(); 
//	window.open("http://localhost/subastas11man/a_liquid.php","",""); 
//} 

//-->
</script>
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
#cheques_tercero {
	position:absolute;
	width:582px;
	height:115px;
	z-index:4;
	visibility: hidden;
	background-color: #00CCFF;
}
#liquidacion1 {
	position:absolute;
	width:640px;
	height:700px;
	z-index:1;
	visibility: visible;
	background-color: #00CCFF;
}
-->
</style>
<script language="javascript" src="cal2.js">
</script>
<script language="javascript" src="cal_conf2.js"></script>
</head>
<body>
<form id="liquidacion" name="liquidacion" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="640" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td background="images/fondo_titulos.jpg"><div align="center"><img src="images/liquid_remate.gif" width="400" height="30" /></div></td>
    </tr>
    <tr>
      <td background="images/fondo_titulos.jpg">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#006699"><table width="640" border="0" cellspacing="1" cellpadding="1">
<?php if ($tipo_de_iva==1) { ?>
       <tr>
          <td height="20" bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Tipo de comprobante</span> </td>
          <td ><input name="tcomp_nombre" type="text" class="phpmaker" value="LIQUIDACION A" />
          <input name="tcomp" type="hidden" value="3" />          </td>
          <td height="20" bgcolor="#0094FF" class="ewTableHeader">&nbsp;Serie</td>
          <td><input name="serie_nombre" type="text" class="phpmaker" value="SERIE DE LIQUIDACION A" /></td><input name="serie" type="hidden" value="2" />
        </tr> <?php } else { ?>
         <tr>
          <td height="20" bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Tipo de comprobante</span> </td>
          <td ><input name="tcomp_nombre" type="text" class="phpmaker" value="LIQUIDACION B" />
          <input name="tcomp" type="hidden" value="31" />          </td>
          <td height="20" bgcolor="#0094FF" class="ewTableHeader">&nbsp;Serie</td>
          <td><input name="serie_nombre" type="text" class="phpmaker" value="SERIE DE LIQUIDACION B" /></td><input name="serie" type="hidden" value="13" />
        </tr> 
        
      <?php } ?>
        <tr>
          <td height="20" bgcolor="#0094FF" class="ewTableHeader">&nbsp;Num de liquidacion </td>
          <td><input name="liquidacion" type="text"  value="<?php echo $liquidacion ?>" readonly=""/>          </td>
          <td bgcolor="#0094FF" class="ewTableHeader">&nbsp;Fecha</td>
          <td><input name="fecha_liq" type="text" size="11" maxlength="11" /><a href="javascript:showCal('Calendar8')"><img src="images/ew_calendar.gif" width="15" height="15" border="0"></td>
        </tr><input name="codpais" type="hidden" size="11" maxlength="11" value="<?php echo $codpais ?>"/>
		<input name="codprov" type="hidden" size="11" maxlength="11" value="<?php echo $codprov ?>"/>
		<input name="codloc" type="hidden" size="11" maxlength="11" value="<?php echo $codloc ?>"/>
        <tr>
          <td height="20" bgcolor="#0094FF" class="ewTableHeader">&nbsp;Cliente</td>
          <td><input name="cliente" type="text"  value="<?php echo $nom_clientes ?>" readonly=""/>          </td>
          <td bgcolor="#0094FF" class="ewTableHeader" >&nbsp;Rubro</td>
          <td bgcolor="#0094FF" class="ewTableHeader" ><select name="rubro">
          <option value="" >[Seleccione un Rubro]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_Recordset1['codnum']?>"><?php echo $row_Recordset1['descripcion']?></option>
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
      <input name="num_cliente" type="hidden"  value="<?php echo $clientes ?>" readonly=""/>
          <input name="fecha_remate" type="hidden"  value="<?php echo $fremate ?>" readonly=""/>
         
        
        <tr>
          <td height="20" bgcolor="#0094FF" class="ewTableHeader">&nbsp;Num de Remate</td>
          <td><input name="remate_num" type="text" class="phpmaker" id="remate_num"  value='<?php echo $rematenum ?>' onchange="copia_valor(this.form)"/></td>
          <td height="20" bgcolor="#0094FF" class="ewTableHeader">&nbsp;Fecha Remate </td>
          <td><input name="fecha_remate1" type="text" class="phpmaker" size="11" value ="<?php echo  $fecha_remate ?>"/></td>
        </tr>
        <tr>
          <td height="20" bgcolor="#0094FF" class="ewTableHeader">&nbsp;Lugar de Remate </td>
          <td colspan="3"><input name="lugar_remate" type="text" size="60" value="<?php echo $direccion  ?>"  /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td background="images/separador3.gif"></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#006699">
        <tr>
          <td colspan="3" bgcolor="#006699"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#006699">
          <?php if ($tipo_de_iva==1) { ?> 
            <tr>
              <td width="31%" bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Importe Total del Remate </span></td>
              <td width="69%"><input name="importe_total" type="text" class="phpmaker" value="<?php echo $total_remate ?>"/></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="31%" bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Neto Gravado&nbsp;<?php echo $iva_15_porcen ?>&nbsp;%</span> </td>
          <td width="27%"><input name="neto105" class="phpmaker" type="text" id="neto105" value="<?php echo $totneto105 ?>"/></td>
          <td width="42%" >&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#0094FF" ><span class="ewTableHeader">&nbsp;<?php echo $iva_15_desc ?>&nbsp;% </span></td>
          <td  ><input  name="iva105" type="text" class="phpmaker" value="<?php echo $iva105 ?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Total <?php echo $iva_15_porcen ?>&nbsp;%</span></td>
          <td><input name="total105" type="text" class="phpmaker" value="<?php echo $total_105 ?>" /></td>
          <td><input name="total105_1" type="text" class="phpmaker" value="<?php echo $total_105 ?>"/></td>
        </tr>
        <tr>
          <td width="31%" bgcolor="#0094FF" class="ewTableHeader"><span class="Estilo1">&nbsp;Neto Gravado&nbsp;<?php echo $iva_21_porcen ?>&nbsp;%</td>
          <td width="27%"><input name="neto21" type="text" class="phpmaker" value="<?php echo $totneto21 ?>"/></td>
          <td width="42%">&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;<?php echo $iva_21_desc ?>&nbsp;% </span></td>
          <td><input name="iva21" type="text" class="phpmaker" value="<?php echo $iva21 ?>"/></td>
          <td bgcolor="#006699" class="ewLayout">&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Total <?php echo $iva_21_porcen ?>&nbsp;%</span></td>
          <td><input name="total21" type="text" class="phpmaker" value="<?php echo $total_21 ?>"/></td>
          <td><input name="total21_1" type="text" class="phpmaker" value="<?php echo $total_21 ?>"/></td>
        </tr> <?php } else { 
		$total_remate = $totneto105+$totneto21;
		$iva105 = 0 ;
		$iva21 = 0;
		$total_105 = $totneto105;
		$total_21  = $totneto21;
		$total_general = $total_remate - $totalgastos ;
		?>
		
        <tr>
              <td width="31%" bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Importe Total del Remate </span></td>
              <td width="69%"><input name="importe_total" type="text" class="phpmaker" value="<?php echo $total_remate ?>"/></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="31%" bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Neto Gravado&nbsp;<?php echo $iva_15_porcen ?>&nbsp;%</span> </td>
          <td width="27%"><input name="neto105" type="text" class="phpmaker" id="neto105" value="<?php echo $totneto105 ?>"/></td>
          <td width="42%" >&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#0094FF" ><span class="ewTableHeader">&nbsp;<?php echo $iva_15_desc ?>&nbsp;% </span></td>
          <td  ><input  name="iva105" type="text" value="<?php echo $iva105 ?>" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Total <?php echo $iva_15_porcen ?>&nbsp;%</span></td>
          <td><input name="total105" type="text" class="phpmaker" value="<?php echo $total_105 ?>" /></td>
          <td><input name="total105_1" type="text" class="phpmaker" value="<?php echo $total_105 ?>"/></td>
        </tr>
        <tr>
          <td width="31%" bgcolor="#0094FF" class="ewTableHeader"><span class="Estilo1">&nbsp;Neto Gravado&nbsp;<?php echo $iva_21_porcen ?>&nbsp;%</td>
          <td width="27%"><input name="neto21" type="text" class="phpmaker" value="<?php echo $totneto21 ?>"/></td>
          <td width="42%">&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;<?php echo $iva_21_desc ?>&nbsp;% </span></td>
          <td><input name="iva21" type="text" class="phpmaker" value="<?php echo $iva21 ?>"/></td>
          <td bgcolor="#006699" class="ewLayout">&nbsp;</td>
        </tr>
        <tr>
          <td bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Total <?php echo $iva_21_porcen ?>&nbsp;%</span></td>
          <td><input name="total21" type="text" class="phpmaker" value="<?php echo $total_21 ?>"/></td>
          <td><input name="total21_1" type="text" class="phpmaker" value="<?php echo $total_21 ?>"/></td>
        </tr> 
		
	<?php } ?>
        <tr>
          <td background="images/separador3.gif" colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td width="31%" bgcolor="#0094FF"><span class="ewTableHeader">&nbsp;Entrega a cuenta &nbsp;</span></td>
          <td width="27%"><input name="acuenta" type="text" class="phpmaker" value="<?php echo $totalcheques ?>" /></td>
          <td width="42%"><img src="images/cheques_tercero_azul.gif" width="210" height="20" onclick="manda_cheque_terceros(this.form)" /></td>
        </tr>
        <tr>
          <td bgcolor="#0094FF"><span class="Estilo1">&nbsp;</span><span class="ewTableHeader">Gastos autorizados </span></td>
          <td><input name="gastos_autor" type="text" class="phpmaker"  value="<?php echo $totfactura ?>" /></td>
          <td><img src="images/gastos_autorizados.gif" width="154" height="20" onclick="manda_gastos_autorizados(this.form)" /></td>
        </tr>
        <tr>
          <td bgcolor="#0094FF"><span class="Estilo1">&nbsp;</span><span class="ewTableHeader">Otros conceptos </span></td>
          <td><input name="otros_gastos" type="text" class="phpmaker" onchange="otros(this.form)"value="0" /></td>
          <td><input name="total_resta" type="text" class="phpmaker" value="<?php echo $totalgastos ?>" /></td>
        </tr>
        <tr>
          <td background="images/separador3.gif" colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td  bgcolor="#0094FF"><span class="Estilo1"><span class="ewTableHeader">Saldo a favor del cliente </span></td><td><input name="total_general" type="text" class="phpmaker" value="<?php echo $total_general ?>" /></td>
          <td><a href="otros_gastos_liq.php?tot_general=<?php echo $total_general ?>&&liquidacion=<?php echo $liquidacion ?>&&remate=<?php echo $rematenum ?>&&serie=<?php echo $tserie ?>&&tipocomp=<?php echo $tcomprobante ?>" target="_blank"><img src="images/medios_pago1.gif" width="103" height="19" border="0" ></a></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td bgcolor="#0099CC"><div align="center">
            <input name="Submit" type="submit" bgcolor="#0099CC" class="phpmaker" value="Grabar" />
            <td height="33" colspan="4" align="center" bgcolor="#0099CC"><a href="a_liquid.php"><img src="images/salir_but.gif" width="55" height="33" border="0" /></a></td>
          </div></td>
          </tr>
      </table></td>
   <?php //</tr>?>
  <?php //</table>?>
  <input type="hidden" name="MM_insert" value="liquidacion">
</form>
</body>
<script language="javascript"> 
//cerrar(); 
</script> 
</html>
<?php
mysql_free_result($Recordset1);
?>

