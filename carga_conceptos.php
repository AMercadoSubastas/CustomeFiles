<?php 
setcookie("concepto","");
require_once('Connections/amercado.php'); 
//setcookie('factura',"");
mysqli_select_db($amercado, $database_amercado);
$query_comprobante = "SELECT * FROM series  WHERE series.codnum =1";
$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
$row_comprobante = mysqli_fetch_assoc($comprobante);
$totalRows_comprobante = mysqli_num_rows($comprobante);

$num_comp = ($row_comprobante['nroact'])+1 ; 

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = addslashes($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$renglones = 0;
$primera_vez = 1;
if (GetSQLValueString($_POST['descripcion'], "text")!="NULL") {
// DESDE ACA ===================================================================================
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
	mysqli_select_db($amercado, $database_amercado);
	$actualiza1 = sprintf("UPDATE `series` SET `nroact` = %s WHERE `series`.`codnum` = %s", GetSQLValueString($_POST['num_factura'], "int"), GetSQLValueString($_POST['serie'], "int")) ;				 
	$resultado=mysqli_query($amercado,	$actualiza1);	

}
// HASTA ACA ===================================================================================
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
echo "insert detfac 1";
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem , descrip, neto,  concafac) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['importe'], "double"),
					   GetSQLValueString($_POST['secuencia'], "int"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 1;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion1'], "text")!="NULL") {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia1'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia1'], "int"),
                       GetSQLValueString($_POST['descripcion1'], "text"),
                       GetSQLValueString($_POST['importe1'], "double"),
                       GetSQLValueString($_POST['comision1'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 2;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion2'], "text")!="NULL") {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia2'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia2'], "int"),
                       GetSQLValueString($_POST['descripcion2'], "text"),
                       GetSQLValueString($_POST['importe2'], "double"),
                       GetSQLValueString($_POST['comision2'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 3;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion3'], "text")!="NULL") {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, tieneresol) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia3'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia3'], "int"),
                       GetSQLValueString($_POST['descripcion3'], "text"),
                       GetSQLValueString($_POST['importe3'], "double"),
                       GetSQLValueString($_POST['comision3'], "double"),
                       GetSQLValueString(isset($_POST['tieneresol']) ? "true" : "", "defined","1","0"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 4;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion4'], "text")!="NULL") {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia4'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia4'], "int"),
                       GetSQLValueString($_POST['descripcion4'], "text"),
                       GetSQLValueString($_POST['comision4'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 5;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion5'], "text")!="NULL") {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia5'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia5'], "int"),
                       GetSQLValueString($_POST['descripcion5'], "text"),
                       GetSQLValueString($_POST['importe5'], "double"),
                       GetSQLValueString($_POST['comision5'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 6;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
}
}
if (GetSQLValueString($_POST['descripcion6'], "text")!="NULL") {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia6'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia6'], "int"),
                       GetSQLValueString($_POST['descripcion6'], "text"),
                       GetSQLValueString($_POST['importe6'], "double"),
                       GetSQLValueString($_POST['comision6'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 7;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
 
}
}
if (GetSQLValueString($_POST['descripcion7'], "text")!="NULL") {

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia7'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia7'], "int"),
                       GetSQLValueString($_POST['descripcion7'], "text"),
                       GetSQLValueString($_POST['importe7'], "double"),
                       GetSQLValueString($_POST['comision7'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 8;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
 
}
}
if (GetSQLValueString($_POST['descripcion8'], "text")!="NULL") {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia8'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia8'], "int"),
                       GetSQLValueString($_POST['descripcion8'], "text"),
                       GetSQLValueString($_POST['importe8'], "double"),
                       GetSQLValueString($_POST['comision8'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 9;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion9'], "text")!="NULL") {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia9'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia9'], "int"),
                       GetSQLValueString($_POST['descripcion9'], "text"),
                       GetSQLValueString($_POST['importe9'], "double"),
                       GetSQLValueString($_POST['comision9'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 10;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion10'], "text")!="NULL") {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia10'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia10'], "int"),
                       GetSQLValueString($_POST['descripcion10'], "text"),
                       GetSQLValueString($_POST['importe10'], "double"),
                       GetSQLValueString($_POST['comision10'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 11;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion11'], "text")!="NULL") {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia11'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia11'], "int"),
                       GetSQLValueString($_POST['descripcion11'], "text"),
                       GetSQLValueString($_POST['importe11'], "double"),
                       GetSQLValueString($_POST['comision11'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 12;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion12'], "text")!="NULL") {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia12'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia12'], "int"),
                       GetSQLValueString($_POST['descripcion12'], "text"),
                       GetSQLValueString($_POST['importe12'], "double"),
                       GetSQLValueString($_POST['comision12'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 13;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion13'], "text")!="NULL") {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia13'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia13'], "int"),
                       GetSQLValueString($_POST['descripcion13'], "text"),
                       GetSQLValueString($_POST['importe13'], "double"),
                       GetSQLValueString($_POST['comision13'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 14;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}
if (GetSQLValueString($_POST['descripcion14'], "text")!="NULL") {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia14'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia14'], "int"),
                       GetSQLValueString($_POST['descripcion14'], "text"),
                       GetSQLValueString($_POST['importe14'], "double"),
                       GetSQLValueString($_POST['comision14'], "double"));

  mysqli_select_db($amercado, $database_amercado);
  $renglones = 15;
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

}
}

if (GetSQLValueString($_POST['descripcion'], "text")!="NULL") {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  $insertSQL = sprintf("INSERT INTO cabfac (tcomp, serie, ncomp, fecval, fecdoc, fecreg, cliente, fecvenc, estado, emitido, codrem, totbruto, totiva105, totiva21, totimp, totcomis, totneto105, totneto21, nrengs, tieneresol) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['fecha_factura'], "date"),
                       GetSQLValueString($_POST['fecha_factura'], "date"),
                       GetSQLValueString($_POST['fecha_factura'], "date"),
                       GetSQLValueString($_POST['codnum'], "int"),
                       GetSQLValueString($_POST['fecha_factura'], "date"),
					   GetSQLValueString("P", "text"),
					   GetSQLValueString("0", "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['tot_general'], "double"),
                       GetSQLValueString($_POST['totiva105'], "double"),
                       GetSQLValueString($_POST['totiva21'], "double"),
                       GetSQLValueString($_POST['totimp'], "double"),
                       GetSQLValueString($_POST['totcomis'], "double"),
                       GetSQLValueString($_POST['totneto105'], "double"),
                       GetSQLValueString($_POST['totneto21'], "double"),
					   GetSQLValueString($renglones, "int"),
                       GetSQLValueString(isset($_POST['tieneresol']) ? "true" : "", "defined","1","0"));

  mysqli_select_db($amercado, $database_amercado);
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

// DESDE ACA =======================================================================================
if (GetSQLValueString($_POST['leyenda'], "text")!="NULL") {
 	$insertSQLfactley = sprintf("INSERT INTO factley (tcomp, serie, ncomp, leyendafc, codrem) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['leyenda'], "text"),
                       GetSQLValueString($_POST['remate_num'], "int"));
                       

  	mysqli_select_db($amercado, $database_amercado);
  	
  	$Result1 = mysqli_query($amercado, $insertSQLfactley) or die(mysqli_error($amercado));

}
// HASTA ACA ===========================================================================================  

}
}


mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades";
$cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

$colname_serie = "1";
if (isset($_POST['tcomp'])) {
  $colname_serie = addslashes($_POST['tcomp']);
}
mysqli_select_db($amercado, $database_amercado);
$query_serie = sprintf("SELECT * FROM serxtc WHERE tcomp = %s ORDER BY serie ASC", $colname_serie);
$serie = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));
$row_serie = mysqli_fetch_assoc($serie);
$totalRows_serie = mysqli_num_rows($serie);

$nivel = 9 ;
$query_num_remates = "SELECT * FROM remates";
$num_remates = mysqli_query($amercado, $query_num_remates) or die(mysqli_error($amercado));
$row_num_remates = mysqli_fetch_assoc($num_remates);
$totalRows_num_remates = mysqli_num_rows($num_remates);


$query_impuesto = "SELECT * FROM impuestos";
$impuesto = mysqli_query($amercado, $query_impuesto) or die(mysqli_error($amercado));
$row_impuesto = mysqli_fetch_assoc($impuesto);
$totalRows_impuesto = mysqli_num_rows($impuesto);
$iva_21_desc = mysqli_result($impuesto,0,2)."<br>";
	$iva_21_porcen = mysqli_result($impuesto,0,1);

	$iva_15_desc = mysqli_result($impuesto,1,2)."<br>";
	$iva_15_porcen = mysqli_result($impuesto,1,1);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php 
 require_once('Connections/amercado.php');  ?>
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
<script src="cjl_cookie.js" type="text/javascript"></script>
<script language="javascript">
 function forma_pago(form)
 {
  var total      = factura.tot_general.value;  // Total de la boleta
  var fac_numero = factura.num_factura.value ; // Nuemro de la Factura 
  var tipo_comprobante = factura.tcomp.value ;  // Tipo de comprobante 
  var serie_num     = factura.serie.value ;  // Numero de Serie 
  var fecha_factura = factura.fecha_factura.value ; //  Fecha de Factura
  var remate_num    = factura.remate_num.value ; // Numero de Remate
  var chequedo = factura.pago_contado.value ;
  factura.leyenda.value = "";
  var error ="";
  if (tipo_comprobante=="" || total=="" || serie_num=="" ) {
      if (tipo_comprobante=="") {
         error = "      Tipo de comprobante\n"; 
        }
      if (serie_num=="") {
          error = error+"      Serie\n"; 
         }
	
	 if (total=="") {
          error = error+"      Total general\n"; 
         }		 
		  
  alert ("Faltan los siguientes datos :\n"+error);
  
  } else {
/// var elformulario.factura3.value = fac_numero ; 
 // alert(fac_numero) ;
//alert (chequedo);
 // function OcultarCapa(capa){
//if(document.layers)document.layers[capa].visibility='hide' // Si utilizamos NS
//document.all.cheques_tercero.style.visibility='hidden' // Si utilizamos IE
//document.all.medios_p.style.visibility='visible'
//}


  // escribimos el mensaje de alerta
strAlerta = "Total Factura " + total + "\n" + "Numero Factura " + fac_numero + "\n" + "Tipo de comprobante " + tipo_comprobante + "\n" + "Serie Numero " + serie_num + "\n" + "Fecha Factura " + fecha_factura + "\n" + "Numero de remate " + remate_num + "\n";

// lanzamos la acci�n
alert(strAlerta);

// alert("Dentro coocke")
   var f = document.forms[0] ;
   var ckUtil = new CJL_CookieUtil("concepto", 30);
 function setFieldFromCookie(fieldId)
   {
	  var cookieVal = ckUtil.getSubValue(fieldId);
	  if( cookieVal )
	  {
	     f[fieldId].value = cookieVal;
		// alert (f[fieldId].value);
	  }
   }
   
   
   function saveFieldToCookie(fieldId)
   {
	  var fieldVal = f[fieldId].value;	  
	 	  ckUtil.setSubValue(fieldId, fieldVal);	  	  
   }
   
   if( ckUtil.cookieExists() )
  {
      

   setFieldFromCookie("tcomp");
   setFieldFromCookie("serie");
   setFieldFromCookie("num_factura");
   setFieldFromCookie("tot_general");
      
    } else   
   {
      saveFieldToCookie("tcomp");
      saveFieldToCookie("serie");
      saveFieldToCookie("num_factura");
	  saveFieldToCookie("tot_general");	  
	  if( ckUtil.cookieExists() )
	  {
	   //  alert("Los datos fueron guardados en una cookie.\n");
	//     f.id_delete.disabled = false;
	  }
	  else
	  {
	//    alert("No hay cookie guardada proemro agregue datos.\n" )
		//      "First enter data into one or more of the fields");
	  
	  } 
   }
   


//window.open("medios_pago.php","nueva","toolbar=no,directories=0,menubar=no,width=1024,height=500");
//'getFact.php?getClientId='+clientId
//var tcomp = 12;
window.open("medios_pago_con.php","nueva","fullscreen,scrollbars");
//fullscreen,scrollbars
}

 }
 </script>

<script language="javascript">
function sin_lotes(form)
{
	alert("Debe ingresar al menos un lote para facturar");
}
</script> 
<script language="javascript">
function OcultarCapa(capa)
{

document.all.cheques_tercero.style.visibility='visible' // Si utilizamos IE
document.all.medios_p.style.visibility='hidden'

//document.all(capa).style.visibility='visible' 
}
</script>
<script language="javascript">
 function cambia_fecha(form)

{ 
var fecha = factura.fecha_factura1.value;
alert(fecha);
var ano = fecha.substring(6,10);
var mes = fecha.substring(3,5);
var dia = fecha.substring(0,2);

//var hora = factura.fecha_factura2.value;
//alert (ano);
//alert (mes);
//alert (dia);
var fecha1 = ano+"-"+mes+"-"+dia ;
//alert (fecha1);
//alert(fecha + hora) ;
//factura.fecha_factura.value = fecha1;

}

function pasaValor(form)

{ 
//alert (form1.remate.value);
var comprobante = factura.tcomp.value;  // Nuemro de remate
var serie       = factura.serie.value; // Tipo de industria
var factnum     = factura.num_factura.value; // Codigo de cliente
var fecha_fact  = factura.fecha_factura.value; // Direccion del remate
var remate      = factura.remate_num.value; // Direccion del remate


 }
</script>

<script language="javascript">
function  valor_prueba(form) {

    if (factura.impuesto[0].checked==true) {
          var impuesto = (factura.impuesto[0].value)/100;
       //   alert("Impuesto 0: "+impuesto);
       }

    if (factura.impuesto[1].checked==true) {
         var impuesto = (factura.impuesto[1].value)/100;
    //     alert("Impuesto 1: "+impuesto);
        }
    //   alert("Impuesto : "+impuesto);

   if (factura.impuesto1[0].checked==true) {
         var impuesto1 = (factura.impuesto1[0].value)/100;
        }

   if (factura.impuesto1[1].checked==true) {
         var impuesto1 = (factura.impuesto1[1].value)/100;
        }
        //alert("Impuesto1 : "+impuesto1);

    if (factura.impuesto2[0].checked==true) {
         var impuesto2 = (factura.impuesto2[0].value)/100;
         }

    if (factura.impuesto2[1].checked==true) {
         var impuesto2 = (factura.impuesto2[1].value)/100;
       }
      //   alert("Impuesto2 : "+impuesto2);  

if (factura.impuesto3[0].checked==true) {
var impuesto3 = (factura.impuesto3[0].value)/100;
}

if (factura.impuesto3[1].checked==true) {
var impuesto3 = (factura.impuesto3[1].value)/100;
} 
//alert("Impuesto3 : "+impuesto3); 
//form.casilla1[i].checked = false;
var total = factura.importe.value;
var total_articulo = impuesto+('+')+total;
//alert(total_articulo);

} 

</script>
<script language="javascript">
function validarFormulario(form)
{
		
	var monto  = factura.importe.value; // Monto  primer lote
	var comi   = 0; // Comision  primer lote
	var imp105 = (factura.impuesto[0].value)/100; // Impuesto 10,5 %
	var imp21  = (factura.impuesto[1].value)/100; // impuesto 21 %
	
	var monto1 = factura.importe1.value; // Monto segundo lote
	var comi1  = 0;// Comision  segundo lote
	var imp105_1 = (factura.impuesto1[0].value)/100; // Impuesto 10,5 %
	var imp21_1  = (factura.impuesto1[1].value)/100; // impuesto 21 %
	
	var monto2 = factura.importe2.value; // Monto tercer lote
	var comi2  = 0 ; // Comision  tercer lote
	var imp105_2 = (factura.impuesto2[0].value)/100; // Impuesto 10,5 %
	var imp21_2  = (factura.impuesto2[1].value)/100; // impuesto 21 %
	
	var monto3 = factura.importe3.value; // Monto cuarto lote
	var comi3  = 0 ; // Comision  cuarto lote
	var imp105_3 = (factura.impuesto3[0].value)/100; // Impuesto 10,5 %
	var imp21_3  = (factura.impuesto3[1].value)/100; // impuesto 21 %
	
	var monto4 = factura.importe4.value;  // Monto Quinto lote
	var comi4  = 0 ; // Comision  Quinto lote
	var imp105_4 = (factura.impuesto4[0].value)/100; // Impuesto 10,5 %
	var imp21_4  = (factura.impuesto4[1].value)/100; // impuesto 21 %
	
	var monto5 = factura.importe5.value; // Monto Sexto lote
	var comi5   = 0;  // Comision  Sexto lote
	var imp105_5 = (factura.impuesto5[0].value)/100; // Impuesto 10,5 %
	var imp21_5  = (factura.impuesto5[1].value)/100; // impuesto 21 %
	
	var monto6  = factura.importe6.value; // Monto Septimo lote
	var comi6   = 0 ; // Comision  Septimo lote
	var imp105_6 = (factura.impuesto6[0].value)/100; // Impuesto 10,5 %
	var imp21_6  = (factura.impuesto6[1].value)/100; // impuesto 21 %
	
	var monto7  = factura.importe7.value; // Monto Octavo lote
	var comi7   = 0; // Comision  Octavo lote
	var imp105_7 = (factura.impuesto7[0].value)/100; // Impuesto 10,5 %
	var imp21_7  = (factura.impuesto7[1].value)/100; // impuesto 21 %
	
	var monto8  = factura.importe8.value; // Monto Noveno lote
	var comi8   = 0 ; // Comision  Noveno lote
	var imp105_8 = (factura.impuesto8[0].value)/100; // Impuesto 10,5 %
	var imp21_8  = (factura.impuesto8[1].value)/100; // impuesto 21 %
	
	var monto9  = factura.importe9.value; // Monto D�cimo lote
	var comi9   = 0 ; // Comision  D�cimo lote
	var imp105_9 = (factura.impuesto9[0].value)/100; // Impuesto 10,5 %
	var imp21_9  = (factura.impuesto9[1].value)/100; // impuesto 21 %
	
	var monto10 = factura.importe10.value;  // Monto Onceavo lote
	var comi10  = 0 ; // Comision  Onceavo lote
	var imp105_10 = (factura.impuesto10[0].value)/100; // Impuesto 10,5 %
	var imp21_10  = (factura.impuesto10[1].value)/100; // impuesto 21 %
		
	var monto11 = factura.importe11.value; // Monto Doceavo lote
	var comi11  = 0 ; // Comision  Doceavo lote
	var imp105_11 = (factura.impuesto11[0].value)/100; // Impuesto 10,5 %
	var imp21_11  = (factura.impuesto11[1].value)/100; // impuesto 21 %
	
	var monto12 = factura.importe12.value; // Monto Treceavo lote
	var comi12  = 0 ; // Comision  Treceavo lote
	var imp105_12 = (factura.impuesto12[0].value)/100; // Impuesto 10,5 %
	var imp21_12  = (factura.impuesto12[1].value)/100; // impuesto 21 %
	
	var monto13 = factura.importe13.value; // Monto Catorceavo lote
	var comi13  = 0 ; // Comision  Catorceavo lote
	var imp105_13 = (factura.impuesto13[0].value)/100; // Impuesto 10,5 %
	var imp21_13  = (factura.impuesto13[1].value)/100; // impuesto 21 %
	
	var monto14 = factura.importe14.value; // Monto Quinceavo lote
	var comi14  = 0 ; // Comision  Quinceavo lote
	var imp105_14 = (factura.impuesto14[0].value)/100; // Impuesto 10,5 %
	var imp21_14  = (factura.impuesto14[1].value)/100; // impuesto 21 %
	
    var tot_mon = 0 ;
    var tot_comi = 0 ;
	var neto105 = 0;
	var neto21 = 0 ;
	var imp_tot105 = 0 ;
	var imp_tot21 = 0 ;
	var tot_mon105 = 0 ;
	var tot_mon21  = 0;
	var totresol  = 0;
	     //// Primer LOTE TODO OK
		 if (factura.impuesto[0].checked==true) {
             if (monto.length!=0 ) {
			 	 tot_mon = eval(monto);
	             tot_mon = tot_mon.toFixed(2);
				 tot_mon105 = eval(monto);
				 tot_mon105 = tot_mon105.toFixed(2);
	          	 imp_tot105 = eval(monto*imp105) ;
				
	       	 }  
	       }
	
	      if (factura.impuesto[1].checked==true) {
	         if (monto.length!=0) {
	             tot_mon = eval(monto);
	             tot_mon = tot_mon.toFixed(2);
				 tot_mon21 = eval(monto); 
				 tot_mon21 = tot_mon21.toFixed(2);
	             imp_tot21 = eval(monto*imp21);
			      	  }  
	       }
		
      if (factura.impuesto1[0].checked==true) { 
	     if (monto1.length!=0) {
	                    tot_mon = eval(tot_mon+('+')+monto1);
	                    tot_mon = tot_mon.toFixed(2);
	    			    tot_mon105 = eval(tot_mon105+('+')+monto1); 
	   				    tot_mon105 = tot_mon105.toFixed(2);
	                    imp_tot105_1 = eval(monto1)*imp105_1 ;
	    		 		imp_tot105 = eval(imp_tot105+('+')+imp_tot105_1);
					              } 
          }
			 
	 if (factura.impuesto1[1].checked==true) {	 
     		if (monto1.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto1);
	                   tot_mon = tot_mon.toFixed(2); 
					   tot_mon21 = eval(tot_mon21+('+')+monto1); 
	                   tot_mon21 = tot_mon21.toFixed(2);
				       imp_tot21_1 = eval(monto1)*imp21_1 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_1);
	             } 
			}	 
			
	// TERCER LOTE TODO OK
			if (factura.impuesto2[0].checked==true) { 
				if (monto2.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto2);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 =eval(tot_mon105+('+')+monto2); 
					   tot_mon105 = tot_mon105.toFixed(2);
	             	   imp_tot105_2 =  eval(monto2)*imp105_2 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_2);
	              } 
	         }
			 
		 	if (factura.impuesto2[1].checked==true) {	 
				if (monto2.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto2);
	                   tot_mon = tot_mon.toFixed(2);
				       tot_mon21 = eval(tot_mon21+('+')+monto2); 
			           tot_mon21 = tot_mon21.toFixed(2);
	          	       imp_tot21_2 = eval(monto2)*imp21_2 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_2);
	             } 
			}			
			
			// CUARTO LOTE TODO OK
			if (factura.impuesto3[0].checked==true) { 
				if (monto3.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto3);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto3);
	                   tot_mon105 = tot_mon105.toFixed(2);
					   imp_tot105_3 = eval(monto3*imp105_3) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_3);
	              } 
	         }
			 
		 	if (factura.impuesto3[1].checked==true) {	 
				if (monto3.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto3);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto3); 
	                   tot_mon21 = tot_mon21.toFixed(2);
				       imp_tot21_3 = eval(monto3*imp21_3) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_3);
	             } 
			}	
			
			// QUINTO LOTE TODO OK
			if (factura.impuesto4[0].checked==true) { 
				if (monto4.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto4);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto4); 
					   tot_mon105 = tot_mon105.toFixed(2);
	                   imp_tot105_4 = eval(monto4*imp105_4);
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_4);
	              } 
	         }
			 
		 	if (factura.impuesto4[1].checked==true) {	 
				if (monto4.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto4);
	                   tot_mon = tot_mon.toFixed(2);
				       tot_mon21 = eval(tot_mon21+('+')+monto4); 
				       tot_mon21 = tot_mon21.toFixed(2);
	                   imp_tot21_4 = eval(monto4*imp21_4) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_4);
	             } 
			}	
			
			
            // SEXTO LOTE TODO OK
			if (factura.impuesto5[0].checked==true) { 
				if (monto5.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto5);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto5); 
					   tot_mon105 = tot_mon105.toFixed(2);
	                   imp_tot105_5 = eval(monto5*imp105_5) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_5);
	              } 
	         }
			 
		 	if (factura.impuesto5[1].checked==true) {	 
				if (monto5.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto5);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto5);
					   tot_mon21 = tot_mon21.toFixed(2);
				       imp_tot21_5 = eval(monto5*imp21_5) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_5);
	             } 
			}	
			
			  // SEPTIMO LOTE TODO OK
			if (factura.impuesto6[0].checked==true) { 
				if (monto6.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto6);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto6);			
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   imp_tot105_6 = eval(monto6*imp105_6) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_6);
	              } 
	         }
			 
		 	if (factura.impuesto6[1].checked==true) {	 
				if (monto6.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto6);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto6);
				       tot_mon21 = tot_mon21.toFixed(2);
	         	       imp_tot21_6 = eval(monto6*imp21_6) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_6);
	             } 
			}
			
			 // OCTAVO LOTE TODO OK
			if (factura.impuesto7[0].checked==true) { 
				if (monto7.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto7);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto7); 
					   tot_mon105 = tot_mon105.toFixed(2);
	               	   imp_tot105_7 = eval(monto7*imp105_7) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_7);
	              } 
	         }
			 
		 	if (factura.impuesto7[1].checked==true) {	 
				if (monto7.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto7);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto7);
					   tot_mon21 = tot_mon21.toFixed(2);
	    		       imp_tot21_7 = eval(monto7*imp21_7);
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_7);
	             } 
			}	
			// NOVENO LOTE TODO OK
			if (factura.impuesto8[0].checked==true) { 
				if (monto8.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto8);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto8);
					   tot_mon105 = tot_mon105.toFixed(2);
	              	   imp_tot105_8 = eval(monto8*imp105_8) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_8);
	              } 
	         }
			 
		 	if (factura.impuesto8[1].checked==true) {	 
				if (monto8.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto8);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto8);
					   tot_mon21 = tot_mon21.toFixed(2);
	                   imp_tot21_8 = eval(monto8*imp21_8) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_8);
	             } 
			}	
			// DECIMO LOTE TODO OK
			if (factura.impuesto9[0].checked==true) { 
				if (monto9.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto9);
	                   tot_mon = tot_mon.toFixed(2);
				       tot_mon105 = eval(tot_mon105+('+')+monto9); 
					   tot_mon105 = tot_mon105.toFixed(2);
	                   imp_tot105_9 = eval(monto9*imp105_9);
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_9);
	              } 
	         }
			 
		 	if (factura.impuesto9[1].checked==true) {	 
				if (monto9.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto9);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto9);
					   tot_mon21 = tot_mon21.toFixed(2);
	                   imp_tot21_9 = eval(monto9*imp21_9) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_9);
	             } 
			}	
			
			
			// DECIMO PRIMER LOTE TODO OK
			if (factura.impuesto10[0].checked==true) { 
				if (monto10.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto10);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto10);
					   tot_mon105 = tot_mon105.toFixed(2);
	            	   imp_tot105_10 = eval(monto10*imp105_10) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_10);
	              } 
	         }
			 
		 	if (factura.impuesto10[1].checked==true) {	 
				if (monto10.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto10);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto10);
					   tot_mon21 = tot_mon21.toFixed(2);
	                   imp_tot21_10 = eval(monto10*imp21_10) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_10);
	             } 
			}	
	        // DECIMO SEGUNDO LOTE TODO OK
			if (factura.impuesto11[0].checked==true) { 
				if (monto11.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto11);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto11);
					   tot_mon105 = tot_mon105.toFixed(2);
	                   imp_tot105_11 = eval(monto11*imp105_11) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_11);
	              } 
	         }
			 
		 	if (factura.impuesto11[1].checked==true) {	 
				if (monto11.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto11);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto11); 
					   tot_mon21 = tot_mon21.toFixed(2);
	                   imp_tot21_11 = eval(monto11*imp21_11) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_11);
	             } 
			}	
	       
		    // DECIMO TERCER LOTE TODO OK
			if (factura.impuesto12[0].checked==true) { 
				if (monto12.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto12);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto12);
					   tot_mon105 = tot_mon105.toFixed(2);
					   imp_tot105_12 = eval(monto12*imp105_12) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_12);
	              } 
	         }
			 
		 	if (factura.impuesto12[1].checked==true) {	 
				if (monto12.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto12);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto12);
				       imp_tot21_12 = eval(monto12*imp21_12) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_12);
	             } 
			}
				
	        // DECIMO CUARTO LOTE TODO OK
			if (factura.impuesto13[0].checked==true) { 
				if (monto13.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto13);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto13);
				       tot_mon105 = tot_mon105.toFixed(2);
	        		   imp_tot105_13 = eval(monto13*imp105_13) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_13);
	              } 
	         }
			 
		 	if (factura.impuesto13[1].checked==true) {	 
				if (monto13.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto13);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto13); 
					   tot_mon21 = tot_mon21.toFixed(2);
	                   imp_tot21_13 = eval(monto13*imp21_13) ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_13);
	             } 
			}	
	        // DECIMO QUINTO LOTE TODO OK
			if (factura.impuesto14[0].checked==true) { 
				if (monto14.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto14);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon105 = eval(tot_mon105+('+')+monto14);
					   tot_mon105 = tot_mon105.toFixed(2);
	                   imp_tot105_14 = eval(monto14*imp105_14) ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_14);
	              } 
	         }
			 
		 	if (factura.impuesto14[1].checked==true) {	 
				if (monto14.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto14);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_mon21 = eval(tot_mon21+('+')+monto14); 
					   tot_mon21 = tot_mon21.toFixed(2);
	                   imp_tot21_14 = eval(monto14*imp21_14 );
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_14);
	             } 
			}	

		  	//alert ("Total vendido"+tot_mon);
			tot_comi=tot_comi.toFixed(2);
			//alert ("Comision "+tot_comi);
			
			tot_neto = eval(tot_mon+('+')+tot_comi+('+')+imp_tot21+('+')+imp_tot105);
			tot_neto = tot_neto.toFixed(2);
		    var	imp_tot105 = imp_tot105.toFixed(2);
			var imp_tot21 = imp_tot21.toFixed(2);
			//imp_tot21 = imp_tot21.toFixed(2);
		//	tot_comi = tot_comi.toFixed(2);
		//	tot_neto = tot_neto.toFixed(2);
		//	tot_mon105 = tot_mon105.toFixed(2);
		//	tot_mon21 = tot_mon21.toFixed(2);
			//alert ("Total Neto"+tot_neto);
			//imp_tot105=imp_tot105.toFixed(2);
			//imp_tot21=imp_tot21.toFixed(2);
			//tot_mon105=tot_mon105.toFixed(2);
			//tot_mon21=tot_mon21.toFixed(2);
			//alert ("Total IVA21"+imp_tot21);
			//alert ("Total IVA10.5"+imp_tot105);
			factura.totiva105.value = imp_tot105;
			factura.totiva21.value = imp_tot21;
		//	factura.totcomis.value = tot_comi ;
			factura.tot_general.value = tot_neto;
			
			factura.totneto105.value = tot_mon105;
			factura.totneto21.value = tot_mon21;
			//factura.fecha_factura.value = "2007-12-06";
			
 //   if (factura.impuesto[1].checked==true) {
//	    var impuesto = (factura.impuesto[0].value)/100;
//             if (monto.length!=0) {
//	             tot_mon = eval(monto);
//	             tot_mon_105 = tot_mon.toFixed(2);
//	             tot_comi = (comi*monto)/100;
//				 impuesto_105 = impuesto*tot_mon105;
//				 impuesto_105 = impuesto_105.toFixed(2);
//        	 }  
//     }
  //   alert("Monto Total :"+tot_mon+"<br>"+Comision :"+tot_comi+"<br>"+Impuesto 10.5"+impuesto_105+"<br>")
 
	}
</script>

<script language="javascript">

function resol(form)
{
			var imp_tot105 = factura.totneto105.value;
			var imp_tot21  = factura.totneto21.value;
			porc105 = 0.015;
			//alert 
			porc21  = 0.03;
			if (factura.tieneresol.checked==true) {
			  // alert(imp_tot105)
				tot_resol = eval((imp_tot105+('*')+porc105)+('+')+(imp_tot21+('*')+porc21));
				tot_resol=tot_resol.toFixed(2);
				factura.totimp.value = tot_resol;
				factura.tot_general.value = eval(tot_neto+('+')+tot_resol);
			}
			else {
				factura.totimp.value = 0;
				factura.tot_general.value = tot_neto;
			}
			//alert("Resol :"+tot_resol+"<br>");
}


</script>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>

<script type="text/javascript" src="../js/ajax.js"></script>    

<script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script> 
<script type="text/javascript">
DHTMLSuite.include("chainedSelect");
</script>
<!-- Desde Aca !--> 
	<script type="text/javascript" src="AJAX/ajax.js"></script>
	<script type="text/javascript">
	// Cliente
	var ajax = new sack();
	var currentClientID=false;
	// Primer Lote
	
	//	alert(currentlote);
	//alert(currentLoteID2);
	function getClientData()
	{
		var clientId = document.getElementById('remate_num').value.replace(/[^0-9]/g,'');
		//	alert (clientId);
		if( clientId!=currentClientID){
		//alert()
			currentClientID = clientId
			ajax.requestFile = 'getFact.php?getClientId='+clientId;	// Specifying which file to get
			ajax.onCompletion = showClientData;	// Specify function that will be executed after file has been found
			ajax.runAJAX();		// Execute AJAX function			
		}
		
		
	}
	
		
	function showClientData()
	{
		var formObj = document.forms['factura'];	
		eval(ajax.response);
	}
	
					
	function initFormEvents()
	{
	    var fecha1 = new Date();
		var dia = fecha1.getDate();
	//alert (dia);
		var mes = (fecha1.getMonth()+1);
	//	alert(mes);
		var ano = fecha1.getYear();
		var fecha11 = dia+'/'+mes+'/'+ano;
		var fecha = ano+'-'+mes+'-'+dia;
		document.getElementById('leyenda').value = " Pago contado";
		document.getElementById('fecha_factura').value = fecha ;
		document.getElementById('fecha_factura1').value = fecha11 ;
		document.getElementById('remate_num').onblur = getClientData;
		document.getElementById('remate_num').focus();
		//alert("CLIENTE DATA"+getClientData);
		//alert (document.getElementById('lote'));
		//alert("LOTE DATA"+getLoteData);
	}
	
	
	window.onload = initFormEvents;
	</script>
<script language="javascript">
function pendiente(form)
{
if (factura.GrupoOpciones1[0].checked ==false)

    {
//factura.pago_contado
factura.leyenda.value="Detalle de medio de pago segun recibo";
factura.pago_contado.disabled= true;

     // alert("Hola")
   // form.intereses[0].disabled = false;
 //   form.intereses[1].disabled = false;
 //   form.intereses[2].disabled = false;

    } else {
	
	 factura.leyenda.value="";
     factura.pago_contado.disabled= false;

 }

   

    }

</script>
<!-- Hasta Aca  !-->
<script language="javascript">
<!--
function neto(form)
{ importe = factura.importes.value; 
   document.write(importe);
  //alert (importe);

}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='El importe debe contener un n�mero.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('ERROR \n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>

<link href="estilo_factura.css" rel="stylesheet" type="text/css" />

</head>

<body>


<?php echo $sess1 ; ?>
<form id="factura" name="factura" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="640" border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#003366">
    <tr>
      <td colspan="3" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_conceptos.gif" width="224" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="3" valign="top" bgcolor="#003366"><table width="100%" border="0" cellspacing="1" cellpadding="1">
         <tr>
          <td width="22%" height="20" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Tipo comprobante </span></td>
          <td width="24%"><input name="" value="FACTURA A0001" />
		  <input name="tcomp" id="tcomp" value="1" type="hidden" />
		         </td>
          <td width="7%">&nbsp;</td>
          <td width="13%" class="ewTableHeader">&nbsp;Serie</td>
          <td width="34%"><input value="SERIE DE FACT A00001" size="25" />
		  <input name="serie" id="serie" value="1" type="hidden" />
            </td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num Factura </td>
          <td><input name="num_factura" type="num_factura" class="phpmakerlist" id="ncomp" value="<? echo $num_comp ?>"/></td>
          <td>&nbsp;</td>
          <td class="ewTableHeader">&nbsp;Fecha fact </td>
          <td><input name="fecha_factura1" type="text" id="fecha_factura1"  />
         <a href="javascript:showCal('Calendar4')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
        </tr>
        
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num. remate </td>
          <td><input name="remate_num" type="text" id="remate_num" /></td>
          <td>&nbsp;</td>
          <td colspan="2" rowspan="4" valign="top" bgcolor="#0094FF" ><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#003366">
            <tr>
              <td colspan="2" bgcolor="#0094FF" align="center"><img src="images/cond_pago.gif" width="150" height="30" /></td>
            </tr>
            <tr>
              <td width="48%" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Contado</span></td>
              <td width="52%" bgcolor="#0094FF"><input name="GrupoOpciones1" type="radio" value="opcion" checked="checked"onclick="pendiente(this.form)"  /></td>
            </tr>
            <tr>
              <td bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Pendiente de pago</span></td>
              <td bgcolor="#0094FF"><input type="radio" name="GrupoOpciones1" value="opcion" onclick="pendiente(this.form)"/></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td height="9" class="ewTableHeader">Lugar del remate </td>
          <td><input name="lugar_remate" type="text" id="lugar_remate" /></td>
          <td>&nbsp;</td>
        </tr>
		<tr>
         <td height="20" class="ewTableHeader">Fecha de remate</td>
          <td><input name="fecha_remate" type="text" size="12" value="<? echo $fecha_est ?>"/></td>
          <td><input name="fecha_factura" type="hidden" size="12" /></td>
          </tr>
        <tr>
          <td height="10" class="ewTableHeader"> Cliente </td>
          <td><select name="codnum" id="codnum">
            <option value="value">Cliente</option>
		
            <?php
do {  
?>
            <option value="<?php echo $row_cliente['codnum']?>"><?php echo $row_cliente['razsoc']?></option>
            <?php
} while ($row_cliente = mysqli_fetch_assoc($cliente));
  $rows = mysqli_num_rows($cliente);
  if($rows > 0) {
      mysqli_data_seek($cliente, 0);
	  $row_cliente = mysqli_fetch_assoc($cliente);
  }
?>
          </select></td>
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
              <div align="center">Concepto</div></td>
          <td width="369" rowspan="2" class="ewTableHeader">
              <div align="center">Descripci&oacute;n</div></td>
          
          <td width="75" rowspan="2" class="ewTableHeader">
              <div align="center">Importe</div></td>
		 <td height="24" colspan="3" class="ewTableHeader"><div align="center">Impuestos</div></td>	  
        </tr>
        <tr>
          <td height="15" class="ewTableHeader"> <div align="center"><?php  echo $iva_15_porcen     ?></div></td>
          <td class="ewTableHeader"><div align="center"><?php  echo $iva_21_porcen     ?></div></td>
        </tr>
        
		<tr>
          <td bgcolor="#0094FF"><input name="lote" type="text" id="lote" size="5" value="1" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="75" />		  </td><input name="secuencia" type="hidden" class="phpmaker"  size="75"  value="1"/>
         
          <td width="75" bgcolor="#0094FF"><input name="importe" type="text" id="importe" onblur="MM_validateForm('importe','','NisNum');return document.MM_returnValue" size="10"   /></td>
          <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto" value="<?php  echo $iva_21_porcen     ?>" onclick="validarFormulario(this.form)"/></td>
		  
          
		 </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote1" type="text" id="lote1" size="5" value="2" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="75" /></td>
         
    <td width="75" bgcolor="#0094FF"><input name="importe1" type="text" id="importe1" onblur="MM_validateForm('importe1','','NisNum');return document.MM_returnValue" size="10"  /></td>
	<td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto1" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)"/></td>
	<td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto1" value="<?php  echo $iva_21_porcen     ?>" onclick="validarFormulario(this.form)" /></td>
    
      <input name="secuencia1" type="hidden" class="phpmaker" id="secuencia1" size="65" value="2" />
	    </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote2" type="text" id="lote2" size="5" value="3" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="75" /></td>
        
          <td width="75" bgcolor="#0094FF"><input name="importe2" type="text" id="importe2" onblur="MM_validateForm('importe2','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto2" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto2" value="<?php  echo $iva_21_porcen     ?>" onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia2" type="hidden" class="phpmaker" id="secuencia2" size="65" value="3"/> 
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote3" type="text" id="lote3" size="5" value="4" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe3" type="text" id="importe3" onblur="MM_validateForm('importe3','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto3" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto3" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
           <input name="secuencia3" type="hidden" class="phpmaker" id="secuencia3" size="65" value="4"/>
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote4" type="text" id="lote4" size="5" value="5" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="75" /></td>
           
          <td width="75" bgcolor="#0094FF"><input name="importe4" type="text" id="importe4" onblur="MM_validateForm('importe4','','NisNum');return document.MM_returnValue" size="10" /></td>
		   <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto4" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto4" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
          <input name="secuencia4" type="hidden" class="phpmaker" id="secuencia4" size="65" value="5"/>
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote5" type="text" id="lote5" size="5" value="6" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="75" /></td>
         
          <td width="75" bgcolor="#0094FF"><input name="importe5" type="text" id="importe5" onblur="MM_validateForm('importe5','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto5" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto5" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
          <input name="secuencia5" type="hidden" class="phpmaker" id="secuencia5" size="65" value="6"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote6" type="text" id="lote6" size="5" value="7" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="75" /></td>
          
		   <td width="75" bgcolor="#0094FF"><input name="importe6" type="text" id="importe6" onblur="MM_validateForm('importe6','','NisNum');return document.MM_returnValue" size="10" /></td>
           <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto6" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto6" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia6" type="hidden" class="phpmaker" id="secuencia6" size="65" value="7"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote7" type="text" id="lote7" size="5" value="8" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe7" type="text" id="importe7" onblur="MM_validateForm('importe7','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto7" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto7" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia7" type="hidden" class="phpmaker" id="secuencia7" size="65" value="8"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote8" type="text" id="lote8" size="5" value="9" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe8" type="text" id="importe8" onblur="MM_validateForm('importe8','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto8" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto8" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia8" type="hidden" class="phpmaker" id="secuencia8" size="65" value="9" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote9" type="text" id="lote9" size="5" value="10" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe9" type="text" id="importe9" onblur="MM_validateForm('importe9','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto9" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto9" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia9" type="hidden" class="phpmaker" id="secuencia9" size="65" value="10"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote10" type="text" id="lote10" size="5" value="11" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="75" /></td>
         
          <td width="75" bgcolor="#0094FF"><input name="importe10" type="text" id="importe10" onblur="MM_validateForm('importe10','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto10" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto10" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
          <input name="secuencia10" type="hidden" class="phpmaker" id="secuencia10" size="65" value="11"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote11" type="text" id="lote11" size="5" value="12" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe11" type="text" id="importe11" onblur="MM_validateForm('importe11','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto11" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto11" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
           <input name="secuencia11" type="hidden" class="phpmaker" id="secuencia11" size="65" value="12"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote12" type="text" id="lote12" size="5" value="13" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="75" /></td>
         
          <td width="75" bgcolor="#0094FF"><input name="importe12" type="text" id="importe12" onblur="MM_validateForm('importe12','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto12" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto12" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
           <input name="secuencia12" type="hidden" class="phpmaker" id="secuencia12" size="65" value="13"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote13" type="text" id="lote13" size="5" value="14" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="75" /></td>
        
          <td width="75" bgcolor="#0094FF"><input name="importe13" type="text" id="importe13" onblur="MM_validateForm('importe13','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto13" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto13" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
                     <input name="secuencia13" type="hidden" class="phpmaker" id="secuencia13" size="65" value="14"/>
		</tr> <tr>
          <td bgcolor="#0094FF"><input name="lote14" type="text" id="lote14" size="5" value="15" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="75" /></td>
       
          <td width="75" bgcolor="#0094FF"><input name="importe14" type="text" id="importe14" onblur="MM_validateForm('importe14','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto14" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto14" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia14" type="hidden" class="phpmaker" id="secuencia14" size="65" value="15"/>
		</tr>
      </table>      </td>
    </tr>
    <tr>
      <td width="280" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF"><tr>
      <td width="71" height="20" valign="top" class="ewTableHeader">&nbsp;Leyenda</td>
      <td width="280"  valign="top"><textarea name="leyenda" cols="55" rows="4"></textarea></td>
    </tr>
	<tr><td class="ewTableHeader">&nbsp;Imprimir </td><td><input type="checkbox" name="checkbox" value="0" />
</td>
	</tr>
	 </table></td>
      <td width="281" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        <tr>
          <td width="48%">&nbsp;<span class="ewTableHeader">Resolucion</span></td>
          <td width="52%"><input name="tieneresol" type="checkbox" id="tieneresol" value="si" onclick="resol(this.form)"   /></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center"><img src="images/con_pago.gif" width="140" height="28" /></div></td>
          </tr>
        <tr>
          <td>&nbsp;<span class="ewTableHeader">Efectivo</span></td>
		  
          <td><input name="pago_contado" type="checkbox" onclick="forma_pago(this.form)" value="si" checked="checked"/></td>
        </tr>
      </table>    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
  
    <tr>
      <td colspan="3" bgcolor="#003366"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        <tr>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php  echo $iva_15_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php  echo $iva_21_porcen ?> %</div></td>
          
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php  echo $iva_15_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php  echo $iva_21_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">R. G. 3337 </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Total </div></td>
        </tr>
        
        <tr>
          <td><input name="totneto105" type="text" id="totneto105" size="12" /></td>
          <td><input name="totneto21" type="text" id="totneto21" size="12" /></td>
          
          <td><input name="totiva105" type="text" id="totiva105" size="12" /></td>
          <td><input name="totiva21"  type="text" id="totiva21" size="12" /></td>
          <td><input name="totimp" type="text" id="totimp" size="10" /></td>
          <td><input name="tot_general" type="text" id="tot_general" size="15" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
         
          <td colspan="3"  align="center">
            <input type="submit" name="Submit3" value="Grabar" />
           </td>
        </tr>
      </table></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="factura">
</form>
 <script type="text/javascript"> 

chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('tcomp','serie','includes/getserxtc.php'); 
//chainedSelects.addChain('ncomp','datos','includes/getremate.php'); 

chainedSelects.init(); 

</script>
 

</body>
</html>
<?php
mysql_free_result($tipo_comprobante);

mysql_free_result($cliente);

mysql_free_result($num_remates);

//mysql_free_result($serie);
?>
