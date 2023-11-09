<?php require_once('Connections/amercado.php'); ?>
<?php
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

// DESDE ACA =======================================================================================
if (GetSQLValueString($_POST['leyendafc'], "text")!="NULL") {
 	$insertSQLfactley = sprintf("INSERT INTO factley (tcomp, serie, ncomp, leyendafc, codrem) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['numfac'], "int"),
					   GetSQLValueString($_POST['leyendafc'], "text"),
                       GetSQLValueString($_POST['codrem'], "int"));
                       

  	mysqli_select_db($amercado, $database_amercado);
  	
  	$Result1 = mysqli_query($amercado, $insertSQLfactley) or die(mysqli_error($amercado));

}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "cheques")) {

//echo "Adentro de los cheques" ;
$codpais = 1;

// variables del formulario tipo de pago

$moneda = 1 ; // tipo de moneda

$banco        = $_POST['banco'];               // codigo Banco
$sucursal     = $_POST['sucursal'];         // Codigo sucursal
$numcheque    = $_POST['numcheque'];       // Numero de Cheque
$vence_cheque = $_POST['venc']; // Fecha Vencimiento formato YYYY-MM-DD ;
$importe      = $_POST['importe'];           //  Importe de cheque 
$vencimiento  = substr($vence_cheque,6,4)."-".substr($vence_cheque,3,2)."-".substr($vence_cheque,0,2); // TRansformacion del cheque
$fecha_ingreso = date('Y-m-d'); 
$estado = "P" ;
$tcomp =  $_POST['tcomp'];
$codrem = $_POST['codrem'];
$serie = $_POST['serie'];
$serierel = $_POST['serierel'];
$tcomprel = $_POST['tcomprel'];
$numfac = $_POST['numfac'];
$total_general = $_POST['tot_general'];
   // Fecha de ingreso del cheque ;
//echo $vencimiento."<br>";              // TRansformacion del cheque



//echo $banco."<br>";
//echo $sucursal."<br>";
//echo $numcheque."<br>";
//echo $vence_cheque ."<br>";
//echo $importe."<br>";
$num_comp = ($row_comprobante['nroact'])+1 ; 

//echo "Moneda (moneda):".$moneda."<br>";
//echo "Banco (codban):".$banco."<br>";
//echo "Sucursal (codsuc):".$sucursal."<br>";
//echo "Numero Cheque :".$numcheque."<br>";
//echo "Vence cheque :".$vencimiento."<br>";
//echo "Cliente :".$cliente."<br>";
//echo "Tipo de comprobante (tcomp):".$tcomp."<br>";
//echo "Serie de comprobante (serie):".$serie."<br>";
//echo "codigo de pais (codpais):".$codpais."<br>";
//echo "Fecho de ingreso (fechaingr):".$fecha_ingreso."<br>";
//echo "Nuemro de comprobante (ncomp)".$num_comp."<br>";
//echo "C�digo de Remate (codrem) :".$codrem."<br>";
//echo "Serie (Factura) (serierel):".$serierel."<br>";
//echo "Tipo de comprobante (factura) (tcomprel):".$tcomprel."<br>";
//echo "Fecho de ingreso (fechaingr):".$fecha_ingreso."<br>";
$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , tcompsal , ncompsal , seriesal )
	VALUES ('$tcomp','$serie','$num_comp','$banco','$sucursal','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'S' ,'1','$codrem' , '$numfac' , '$tcomprel' , '$numfac' , '$serierel')";
	
	
	// 4. Ejecuto la consulta.	
$result = mysqli_query($amercado, $strSQL);				         

			 
$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='10'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);		

}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "depositos")) {

$codpais = 1;

// variables del formulario tipo de pago

$moneda = 1 ; // tipo de moneda

$banco        = $_POST['banco1'];               // codigo Banco
$sucursal     = $_POST['sucursal1'];         // Codigo sucursal
$numcheque    = $_POST['deposito'];       // Numero de Cheque
$vence_cheque = $_POST['fecha_deposito']; // Fecha Vencimiento formato YYYY-MM-DD ;
$importe      = $_POST['importe_deposito'];           //  Importe de cheque 
$vencimiento  = substr($vence_cheque,6,4)."-".substr($vence_cheque,3,2)."-".substr($vence_cheque,0,2); // TRansformacion del cheque
$fecha_ingreso = date('Y-m-d'); 
$estado = "S" ;
$tcomp =  $_POST['tcomp2'];
$codrem = $_POST['codrem2'];
$serie = $_POST['serie2'];
$serierel = $_POST['serierel2'];
$tcomprel = $_POST['tcomprel2'];
$numfac = $_POST['numfac2'];
$total_general = $_POST['tot_general'];
   // Fecha de ingreso del cheque ;
//echo $vencimiento."<br>";              // TRansformacion del cheque



//echo $banco."<br>";
//echo $sucursal."<br>";
//echo $numcheque."<br>";
//echo $vence_cheque ."<br>";
//echo $importe."<br>";
$num_comp = ($row_comprobante['nroact'])+1 ; 

//echo "Moneda (moneda):".$moneda."<br>";
//echo "Banco (codban):".$banco."<br>";
//echo "Sucursal (codsuc):".$sucursal."<br>";
//echo "Numero Cheque :".$numcheque."<br>";
//echo "Vence cheque :".$vencimiento."<br>";
//echo "Cliente :".$cliente."<br>";
//echo "Tipo de comprobante (tcomp):".$tcomp."<br>";
//echo "Serie de comprobante (serie):".$serie."<br>";
//echo "codigo de pais (codpais):".$codpais."<br>";
//echo "Fecho de ingreso (fechaingr):".$fecha_ingreso."<br>";
//echo "Nuemro de comprobante (ncomp)".$num_comp."<br>";
//echo "C�digo de Remate (codrem) :".$codrem."<br>";
//echo "Serie (Factura) (serierel):".$serierel."<br>";
//echo "Tipo de comprobante (factura) (tcomprel):".$tcomprel."<br>";
//echo "Fecho de ingreso (fechaingr):".$fecha_ingreso."<br>";


$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , seriesal ,tcompsal , ncompsal )
	VALUES ('$tcomp','$serie','$num_comp','$banco','$sucursal','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'S' ,'$moneda','$codrem' , '$numfac' ,'$serierel','$tcomprel' , '$numfac' )";
	
	
	// 4. Ejecuto la consulta.	
$result = mysqli_query($amercado, $strSQL);				         

			 
$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='15'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);				              
$resultado =mysqli_query($amercado, $insetar_banco);


}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "pesos")) {
//echo "Dentro de Pesos";

$codpais = 1;

// variables del formulario tipo de pago

$moneda = 1 ; // tipo de moneda
$cotizacion   = $_POST['cotizacion']; 
$banco        = $_POST['banco1'];               // codigo Banco
$sucursal     = $_POST['sucursal1'];         // Codigo sucursal
$numcheque    = $_POST['deposito'];       // Numero de Cheque
$vence_cheque = $_POST['fecha_deposito']; // Fecha Vencimiento formato YYYY-MM-DD ;
$importe      = $_POST['importe_pesos'];           //  Importe de cheque 
$vencimiento  = substr($vence_cheque,6,4)."-".substr($vence_cheque,3,2)."-".substr($vence_cheque,0,2); // TRansformacion del cheque
$fecha_ingreso = date('Y-m-d'); 
$estado = "$" ;
$tcomp =  $_POST['tcomp4'];
$codrem = $_POST['codrem4'];
$serie = $_POST['serie4'];
$serierel = $_POST['serierel4'];
$tcomprel = $_POST['tcomprel4'];
$numfac = $_POST['numfac4'];
$total_general = $_POST['tot_general'];

   // Fecha de ingreso del cheque ;
// echo $vencimiento."<br>";              // TRansformacion del cheque



//echo $banco."<br>";
//echo $sucursal."<br>";
//echo $numcheque."<br>";
//echo $vence_cheque ."<br>";
//echo $importe."<br>";
$num_comp = ($row_comprobante['nroact'])+1 ; 
//echo "Cotizacion (moneda):".$cotizacion."<br>";
//echo "Moneda (moneda):".$moneda."<br>";
//echo "Banco (codban):".$banco."<br>";
//echo "Sucursal (codsuc):".$sucursal."<br>";
//echo "Numero Cheque :".$numcheque."<br>";
//echo "Vence cheque :".$vencimiento."<br>";
//echo "Cliente :".$cliente."<br>";
//echo "Tipo de comprobante (tcomp):".$tcomp."<br>";
//echo "Serie de comprobante (serie):".$serie."<br>";
//echo "codigo de pais (codpais):".$codpais."<br>";
//echo "Fecho de ingreso (fechaingr):".$fecha_ingreso."<br>";
//echo "Nuemro de comprobante (ncomp)".$num_comp."<br>";
//echo "C�digo de Remate (codrem) :".$codrem."<br>";
//echo "Serie (Factura) (serierel):".$serierel."<br>";
//echo "Tipo de comprobante (factura) (tcomprel):".$tcomprel."<br>";
//echo "Fecho de ingreso (fechaingr):".$fecha_ingreso."<br>";
//echo "Total Generla".$total_general."<br>";
//$strSQL = "INSERT INTO cartvalores
//	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr  , tcomrel  , serierel , ncomprell , estado , moneda ,codrem )
//	VALUES ('$tcomp','$serie','$num_comp','$banco','$sucursal','$numcheque','$codpais','$importe','$vencimiento', '$fecha_ingreso' //, '$tcomprel','$serierel','$num_comp' ,'P' ,'$moneda','$codrem')";
$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp,  codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , seriesal ,tcompsal , ncompsal  )
	VALUES ('$tcomp','$serie','$num_comp' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'S' ,'$moneda','$codrem' , '$numfac' ,'$serierel','$tcomprel' , '$numfac')";	
	// 4. Ejecuto la consulta.	
$result = mysqli_query($amercado, $strSQL);				         
			 
$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='8'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);

}
// HASTA ACA ===========================================================================================
?><?php require_once('Connections/amercado.php'); 
//$num_fact =  $_GET['liquidacion_c'];
//$num_remate = $_GET['remate_num_c'];
//$num_fact = 5;
//$num_remate= 1;
//$tot_gen =-10000;
//echo "Liquidacion".$num_fact ."<br>";
//echo "Remate".$num_remate."<br>";
$num_fact =  $_GET['liquidacion'];
echo $num_fact;
$num_remate = $_GET['remate'];
echo $num_remate;
$tot_gen = $_GET['tot_general'];
echo $tot_gen;
$serierel = $_GET['serie'];;
echo $serierel;
$tip_comp = $_GET['tipocomp'];
echo $tip_comp;
$file = "otros_gastos_liq";
//echo "Serie REL ".$serierel."<br>";
//echo "Tip Comp ".$tip_comp."<br>";
//echo "Num de Remate ".$num_remate."<br>";
//echo "Num Liqui ".$num_fact."<br>";
//echo "Tot Gen ".$tot_gen."<br>";
//echo "Tip Comp".$tip_comp."<br>";

//$serierel = 2;

//echo "Medios".$medios;
mysqli_select_db($amercado, $database_amercado);
$query_bancos = "SELECT * FROM bancos";
$bancos = mysqli_query($amercado, $query_bancos) or die(mysqli_error($amercado));
$row_bancos = mysqli_fetch_assoc($bancos);
$totalRows_bancos = mysqli_num_rows($bancos);

//echo $totalRows_bancos;
$query_Cheques = "SELECT * FROM cartvalores";
$Cheques = mysqli_query($amercado, $query_Cheques) or die(mysqli_error($amercado));
$row_Cheques = mysqli_fetch_assoc($Cheques);
$totalRows_Cheques = mysqli_num_rows($Cheques);
//echo $totalRows_Cheques;

$query_sucursales = "SELECT * FROM sucbancos";
$sucursales = mysqli_query($amercado, $query_sucursales) or die(mysqli_error($amercado));
$row_sucursales = mysqli_fetch_assoc($sucursales);
$totalRows_sucursales = mysqli_num_rows($sucursales);
/*
$datos = $_COOKIE['factura']; 
//echo $datos."<br>";
//list($tcomp, $serie  ,$remate_num , $tot_general) = explode("@", $datos);
$datos1 = explode("@", $datos);
//echo count($datos1);
for ($index =0 ; $index <count($datos1) ; $index++)
{
  	 
   if ($index = 1) 
     {
	 $tcomp1 =$datos1[1] ;
	// echo $tcomp1."<br><br>";
	// $tcomp =explode("tcomp",$tcomp1);
	$tcomp = explode("tcomp", $tcomp1);
	$tip_comp = $tcomp[1];
	//echo "Tipo comprobante".$tip_comp."<br>";
	 }
	 if ($index = 2) 
     {
	$serie1 =   $datos1[2];
	$serie = explode("serie", $serie1);
	// echo $serie."<br>";
	 $serie = $serie[1];
	// echo "Numero Serie".$serie."<br>";
	 }
	 if ($index = 3) 
     {
	 $remate_num1 =   $datos1[3];
	 $remate_num = explode("remate_num", $remate_num1);
	 $num_remate =  $remate_num[1];
	//echo "Num remate".$num_remate."<br>";
	 }  
	  if ($index = 4) 
     {
	 $monto = $datos1[4];
	  $num_fact = explode("num_factura", $monto);
	 $num_fact =  $num_fact[1];
	// echo "Numero factura".$num_fact."<br>";
	 }   	
	 if ($index = 5) 
     {
	 $monto = $datos1[5];
	 $tot_general = explode("tot_general", $monto);
	 $tot_gen =  $tot_general[1];
//	  echo "Total Generale".$tot_gen."<br>";
	 }     
//print ("$index. $datos1[$index].<br>\n");


} */

$tcomp = $tcompu[0] ;
$serie = $serie[0] ;
$remate = $remate_num[0];
$tot_general = $tot_general[0];
//echo $tcomp."<br>";
// echo "Num reamte".$num_remate."<br>";
// echo "Total Generale".$tot_gen."<br>";
//echo $tot_general."<br>";/*

/// Depositis Bancarios
$sql_deposito = "SELECT   bancos.nombre , sucbancos.nombre ,codchq , importe , fechapago , codrem , cartvalores.codnum" 
        . " FROM cartvalores , bancos , sucbancos "
       . " WHERE tcomp = 39"
        . " AND serie = 15" 
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem = $num_remate"
        . " AND bancos.codnum = cartvalores.codban"
        . " AND sucbancos.codnum = cartvalores.codsuc"
        . " AND sucbancos.codbanco = bancos.codnum"
		 . ' ';;
		$total_deposito = mysqli_query($amercado, $sql_deposito) or die(mysqli_error($amercado));
/*		
$sql_deposito1 = "SELECT   bancos.nombre , sucbancos.nombre ,codchq , importe , fechapago , codrem" 
        . " FROM cartvalores , bancos , sucbancos "
       . " WHERE tcomp = 39"
        . " AND serie = 15" 
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem = $num_remate"
        . " AND bancos.codnum = cartvalores.codban"
        . " AND sucbancos.codnum = cartvalores.codsuc"
        . " AND sucbancos.codbanco = bancos.codnum"
		 . ' ';;
		$total_deposito1 = mysqli_query($amercado, $sql_deposito1) or die(mysqli_error($amercado));
		$monto_total_depositos = 0;	
		 while ($depositos = mysqli_fetch_array($total_deposito1)){
   $monto_total_depositos = $monto_total_depositos + $depositos['3'];
			
			}
			/*/
		//	echo $monto_total_depositos."<br>";
	//	echo "Total deposito".$total_deposito."<br>";
/// Cheques Bancarios		
		$sql_chk = "SELECT bancos.nombre , sucbancos.nombre ,codchq , importe , fechapago , codrem , cartvalores.codnum"
        . " FROM cartvalores , bancos , sucbancos"
        . " WHERE tcomp =14"
        . " AND serie =10"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
        . " AND bancos.codnum = cartvalores.codban"
        . " AND sucbancos.codnum = cartvalores.codsuc"
        . " AND sucbancos.codbanco = bancos.codnum"
        . ' ';
	   $total_cheques = mysqli_query($amercado, $sql_chk) or die(mysqli_error($amercado));
  
	   $sql_chk1 = "SELECT bancos.nombre , sucbancos.nombre ,codchq , importe , fechapago , codrem ,  cartvalores.codnum"
        . " FROM cartvalores , bancos , sucbancos"
       . " WHERE tcomp =14"
        . " AND serie =10"
       . " AND tcomprel =$tip_comp"
        . " AND serierel =$serierel"
       . " AND ncomprel = $num_fact"
      . " AND codrem =$num_remate"
      . " AND bancos.codnum = cartvalores.codban"
        . " AND sucbancos.codnum = cartvalores.codsuc"
       . " AND sucbancos.codbanco = bancos.codnum"
       . ' ';
	   $total_cheques1 = mysqli_query($amercado, $sql_chk1) or die(mysqli_error($amercado));
	   
	  // echo "Total Cqueques".$total_cheques;
	  $monto_total_cheques1 = 0;	
		 while ($cheques = mysqli_fetch_array($total_cheques1)){
   $monto_total_cheques1  = $monto_total_cheques1 + $cheques['3'];
			
			}
			
	//	echo "Total Cheques".$monto_total_cheques."<br>";
	
	// Deposito Pesos		
		$sql_pesos = "SELECT importe "
        . " FROM cartvalores "
        . " WHERE tcomp =12"
        . " AND serie =8"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
		. ' ';
		//$cash = mysqli_query($amercado, $sql_pesos) or die(mysqli_error($amercado));
	//	$row_efectivo = mysqli_fetch_assoc($total_efectivo);
	$total_efectivo = mysqli_query($amercado, $sql_pesos) or die(mysqli_error($amercado));
		while ($efectivo = mysqli_fetch_array($total_efectivo)){
   $tot_efectivo =  $tot_efectivo + $efectivo['importe'];
  
    
}
//echo "Total efectivo".$tot_efectivo;
$total_general = $tot_efectivo + $monto_total_cheques1 + $monto_total_depositos;
//echo "Faltante :".$total_general;
$tot_faltante = $tot_gen-$total_general;
?>
 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="cal2.js">
</script>
<script language="javascript" src="cal_conf2.js"></script>
<script language="javascript" src="jscookies.js"></script>
<script type="text/javascript" src="../js/ajax.js"></script>    
<script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script> 
<script tpe="text/javascript">
DHTMLSuite.include("chainedSelect");

</script>




<script language="javascript">

//Modeless window script- By DynamicDrive.com
//for full source code and terms of use
//visit http://www.dynamicdrive.com

function modelesswin(url,mwidth,mheight){
if (document.all&&window.print) //if ie5
eval('window.showModelessDialog(url,"","help:0;resizable:1;dialogWidth:'+mwidth+'px;dialogHeight:'+mheight+'px")')
else
eval('window.open(url,"","width='+mwidth+'px,height='+mheight+'px,resizable=1,scrollbars=1")')
}

function calculo_cotiz(form)
{
//alert ("hola")
var cantidad = moneda.cant_moneda.value ;
//alert (cantidad)
var cotiz = moneda.cotizacion.value ;
//alert (cotiz)
var total = eval(cantidad*cotiz);
//alert (total)
moneda.tot_cotizacion.value = total
//alert (total);
}

</script>
<script language="javascript">
function pesos(form)
{

var pesos_ing = ing_pesos.cant_efectivo.value ;
ing_pesos.importe_pesos.value = pesos_ing ;

}

//var ver1 = deposito_list.total_deposito.value ;

//alert();

</script> 
<style type="text/css">
<!--
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
}
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
</style>
</head>

<body >

<table width="640" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td colspan="2" background="images/fondo_titulos.jpg"><img src="images/medios_propios.gif" width="314" height="30" /></td>
  </tr>
  <tr>
    <td width="320">&nbsp;</td>
    <td width="379">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><table width="90%" border="0" align="center" cellpadding="2" cellspacing="1" bordercolor="#ECE9D8" bgcolor="#ECE9D8">
     <form name="valor_contado" > <tr>
        <td colspan="2" ><label>&nbsp;<span class="ewMultiPagePager">Contado&nbsp;</span>&nbsp;&nbsp;&nbsp;</label>
       <input name="contado" type="text" value="<?php echo $tot_gen ?>"  /></td>
		  
        <td colspan="2" ><label>&nbsp;<span class="ewMultiPagePager">Monto faltante;</span>&nbsp;&nbsp;&nbsp;</label>
       <input name="faltante" type="text" value="<?php echo $tot_faltante ?>"  />   </td>
     </tr>
	 </form>
      
      <tr>
        <td colspan="3"></td>
      </tr>
      
      
      <tr>
        <td width="1">&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingres_cheques_propios.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/sucursal.gif" width="64" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><img src="images/num_cheque.gif" width="124" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/vencimientoi.gif" width="130" height="16" /></td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="cheques" name="cheque_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
               <td width="140"><select name="banco" class="seleccion" id="banco">
                 <option value="" class="phpmaker">Seleccione Banco</option>
                 <?php
                   do {  
                        ?>
                 <option  class="phpmaker" value="<?php echo $row_bancos['codnum']?>"><?php echo $row_bancos['nombre']?></option>
                 <?php
                        } while ($row_bancos = mysqli_fetch_assoc($bancos));
                             $rows = mysqli_num_rows($bancos);
                              if($rows > 0) {
                                 mysqli_data_seek($bancos, 0);
	                            $row_bancos = mysqli_fetch_assoc($bancos);
                               }
                           ?>
               </select></td>
               <td ><select class="seleccion" name="sucursal" id="sucursal" >
                  </select>                </td>
                <td width="150"><input name="numcheque" type="text" class="seleccion" id="numcheque" /></td>
                <td width="97"><div align="right">
                    <input name="venc" type="text" class="seleccion" id="venc" size="11" />
                </div></td>
                <td width="24"><a href="javascript:showCal('Calendar3')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
                <td><input name="importe" type="text" class="seleccion" id="importe" size="10" />                </td>
              </tr>

            <tr>
	           <input name="numfac"    type="hidden"  value="<?php echo $num_fact ?>"/>
	            <input name="codrem"    type="hidden" value="<?php echo $num_remate ?>"/>
                <input name="fechaing"  type="hidden"  value="<?php echo $fechaing ?>"/>
                <input name="serie"     type="hidden"  value="10"/>
	            <input name="ncomp"     type="hidden" value="<?php echo $comprobante ?>"/>	 
	            <input name="serierel"  type="hidden" value="<?php echo $serierel ?>" />
	            <input name="tcomprel"  type="hidden"  value="<?php echo $tip_comp ?>" />
				 <input name="tot_general"  type="hidden"  value="<?php echo $tot_gen ?>" />
              <td width="150">&nbsp;</td>
              <td width="150"><input name="estado"    type="hidden"  value="S"/></td>
              <td width="150"><input name="tcomp"     type="hidden"  value="14"/></td>
              <td colspan="3"><div align="center">
			      <input name="Submit" type="submit" class="seleccion" value="Ingresar cheques" />
                         </div></td>
            </tr>
			     <input type="hidden" name="MM_insert" value="cheques">
          </form>
        </table></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingreso_depositos_propios.gif" width="402" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/sucursal.gif" width="64" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><img src="images/num_cuenta.gif" width="130" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/fecha_deposito.gif" width="130" height="16" /></td>
            <td width="123" bgcolor="#00CCFF">&nbsp;</td>
          </tr>
          <form id="cheques" name="deposito_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
              <td width="140"><select name="banco1" class="seleccion" id="select">
                  <option value="" class="phpmaker">Seleccione Banco</option>
                  <?php
                   do {  
                        ?>
                  <option  class="phpmaker" value="<?php echo $row_bancos['codnum']?>"><?php echo $row_bancos['nombre']?></option>
                  <?php
                        } while ($row_bancos = mysqli_fetch_assoc($bancos));
                             $rows = mysqli_num_rows($bancos);
                              if($rows > 0) {
                                 mysqli_data_seek($bancos, 0);
	                            $row_bancos = mysqli_fetch_assoc($bancos);
                               }
                           ?>
              </select></td>
              <td ><select class="seleccion" name="sucursal1" id="select2" >
              </select></td>
              <td width="150"><input name="deposito" type="text" class="seleccion" /></td>
              <td width="97"><div align="right">
                  <input name="fecha_deposito" type="text" class="seleccion" id="fecha_deposito" size="11" />
              </div></td>
              <td width="24"><a href="javascript:showCal('Calendar5')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
              <td><input name="importe_deposito" type="text" class="seleccion" size="10" />              </td>
            </tr>
            <tr><input name="numfac2"    type="hidden"  value="<?php echo $num_fact ?>"/>
              <td ><input name="codrem2"    type="hidden"  value="<?php echo $num_remate ?>"/></td>
              <td><input name="fechaing2"  type="hidden"  value="<?php echo $fechaing ?>"/></td>
              <td><input name="serie2"     type="hidden"  value="15"/></td>
              <input name="ncomp2"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel2"  type="hidden" value="<?php echo $serierel ?>" />
              <input name="tcomprel2"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado2"    type="hidden"  value="S"/>
              <input name="tcomp2"     type="hidden"  value="39"/>
			  <input name="tot_general" type="hidden"  value="<?php echo $tot_gen ?>" />
              <td colspan="3"><input name="Submit222" type="submit" class="seleccion" value="Ingresar dep&oacute;sitos" />              </td>
            </tr>
			<input type="hidden" name="MM_insert" value="depositos">
          </form>
        </table></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center">
              <input name="textfield232" type="text" class="seleccion" value="<?php echo $registro1[1] ?>"/>
              <img src="images/ingresoefectivo.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/pesos.gif" width="150" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"></div></td>
            <td width="150" bgcolor="#00CCFF">&nbsp;</td>
            <td colspan="2" bgcolor="#00CCFF">&nbsp;</td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="ing_pesos" name="ing_pesos" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
              <td width="150"><input name="cant_efectivo" type="text" id="cant_efectivo"  onchange="pesos(this.form)" /></td>
              <td width="150">&nbsp;</td>
              <td width="150">&nbsp;</td>
              <td width="97">&nbsp;</td>
              <td width="24">&nbsp;</td>
              <td><input name="importe_pesos" type="text" id="importe_pesos" size="15" />              </td>
            </tr>
            <tr>
              <td width="150">&nbsp;</td>
              <td width="150">&nbsp;</td>
              <td width="150">&nbsp;</td>
			  <input name="numfac4"    type="hidden"  value="<?php echo $num_fact ?>"/>
			   <input name="codrem4"   type="hidden"   value="<?php echo $num_remate ?>"/>
              <input name="fechaing4"  type="hidden"  value="<?php echo $fechaing ?>"/>
              <input name="serie4"     type="hidden"  value="8"/>
              <input name="ncomp4"     type="hidden"   value="<?php echo $comprobante ?>"/>
              <input name="serierel4"  type="hidden"   value="<?php echo $serierel ?>" />
              <input name="tcomprel4"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado3"    type="hidden"  value="S"/>
              <input name="tcomp4"     type="hidden"   value="12"/>
			  <input name="tot_general" type="hidden"  value="<?php echo $tot_gen ?>" />
              <td colspan="3"><div align="center">
                <input name="enviar" type="submit" class="seleccion" value="Ingresar pesos" />
				<input type="hidden" name="MM_insert" value="pesos">
              </div></td>
            </tr>
          </form>
        </table></td>
      </tr>
  <?php /*  if (($totalRows_total_ing_brutos==0) && ($totalRows_total_ing_brutos==0) && ($totalRows_total_ing_brutos==0) && ($totalRows_total_ing_brutos==0)) { */?>
       <?php /* } */?>
	   <tr>
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"></div></td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
        
		  <tr>
            <td colspan="7" bgcolor="#993333" ><div align="center"><img src="images/cheques_propios_marron.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/sucursal_marron.gif" width="64" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><img src="images/num_chq_marron.gif" width="135" height="16" /></td>
            <td bgcolor="#999966"><img src="images/fecha_vencimiento.gif" width="135" height="16" /></td>
            <td width="123" bgcolor="#999966"><div align="center"><img src="images/importes_marron.gif" width="70" height="16" /></div></td>
            <td bgcolor="#999966">B</td>
            <td bgcolor="#999966">E</td>
          </tr>
	
          <form id="form1" name="deposito_list" method="post" action="">
	<?php while ($registro1 = mysqli_fetch_array($total_cheques)){
   $precio1= $precio1 + $registro1['3'];
   $reg_leyenda = $reg_leyenda." -".$registro1[0].", N�".$registro1[2]."  $".$registro1[3]." "; 

//$i = $i + 1;
?>
<tr>
<td width="150" bgcolor="#993333"><input name="textfield52" type="text" class="seleccion" value="<?php echo $registro1[0] ?>"/></td>
<td width="150" bgcolor="#993333">&nbsp;</td>
<td width="150" bgcolor="#993333"><input name="textfield2222" type="text" class="seleccion" value="<?php echo $registro1[2] ?>"></td>
<td bgcolor="#993333"><input name="fecha_venc2" type="text" class="seleccion" id="fecha_venc" size="13" value="<?php echo $registro1[4] ?>"/></td>
<td bgcolor="#993333"><input name="textfield422" type="text" class="seleccion" size="13" value="<?php echo $registro1[3] ?>" />              </td>      
        <td bgcolor="#993333"><a href="modif_medio_borrar.php?file=<?php echo $file ?>&codnum=<?php echo $registro1[6] ?>&ncomp=<?php echo $num_fact ?>&serierel=<?php echo $serierel ?>&tcomprel=<?php echo $tip_comp ?>&remate=<?php echo $num_remate ?>&tot_gen=<?php echo $tot_gen ?>"><img src="images/b_drop.png" width="16" height="16" border="0" /></a></td>
        <td bgcolor="#993333"><a href="modif_medio_editar.php?file=<?php echo $file ?>&codnum=<?php echo $registro1[6] ?>&ncomp=<?php echo $num_fact ?>&serierel=<?php echo $serierel ?>&tcomprel=<?php echo $tip_comp ?>&remate=<?php echo $num_remate ?>&tot_gen=<?php echo $tot_gen ?>"><img src="images/b_edit.png" width="16" height="16" border="0" /></a></td>
</tr>
		<?php } ?>
            <tr>
              <td bgcolor="#993333">&nbsp;</td>
              <td bgcolor="#993333">&nbsp;</td>
              <td colspan="2" bgcolor="#993333"><div align="right"><img src="images/total_cheques_marr.gif" width="136" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_deposito" type="text" class="seleccion" size="15"  value="<?php echo $precio1 ?>" onChange="deposito(this.form)"/></td>
              <td bgcolor="#993333">&nbsp;</td>
              <td bgcolor="#993333">&nbsp;</td>
            </tr></form>
          
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="8" bgcolor="#993333" ><div align="center"><img src="images/depositos_bancarios_porpios.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/sucursal_marron.gif" width="64" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><img src="images/numn_de_cuenta.gif" width="150" height="16" /></td>
            <td colspan="2" bgcolor="#999966"><img src="images/fecha_dep_marro.gif" width="150" height="16" /></td>
            <td width="123" bgcolor="#999966"><img src="images/importes_marron.gif" width="70" height="16" /></td>
            <td bgcolor="#999966">B</td>
            <td bgcolor="#999966">E</td>
          </tr>
          <form id="cheques" name="cheque_list" method="post" >
		<?php while ($registro = mysqli_fetch_array($total_deposito)){
   $monto_total_cheques = $monto_total_cheques + $registro['3'];
   $reg_ley = $reg_ley." - ".$registro[0]." , N�".$registro[2].", $".$registro[3]."  "; 
           ?>
		  
            <tr>
              <td width="150" bgcolor="#993333"><input name="list_banco_cheque" type="text" class="seleccion" id="list_banco_cheque" value="<?php echo $registro[0] ?>"/></td>
              <td bgcolor="#993333" ><input name="list_suc_cheque" type="text" class="seleccion" value="<?php echo $registro[1] ?>"/></td>
              <td width="150" bgcolor="#993333"><input name="list_num_cheque" type="text" class="seleccion" id="numcheque2" value="<?php echo $registro[2] ?>"/></td>
              <td width="97" colspan="2" bgcolor="#993333"><div align="right">
                  <input name="list_fecha_venc" type="text" class="seleccion" id="venc2" size="11" value="<?php echo $registro[4] ?>"/>
              </div></td>
              <td bgcolor="#993333"><input name="list_importe_cheque" type="text" class="seleccion" id="importe2" size="15" value="<?php echo $registro[3] ?>"/>              </td>
              <td bgcolor="#993333"><a href="modif_medio_borrar.php?file=<?php echo $file ?>&codnum=<?php echo $registro[6] ?>&ncomp=<?php echo $num_fact ?>&serierel=<?php echo $serierel ?>&tcomprel=<?php echo $tip_comp ?>&remate=<?php echo $num_remate ?>&tot_gen=<?php echo $tot_gen ?>"><img src="images/b_drop.png" width="16" height="16" border="0" /></a></td>
              <td bgcolor="#993333"><a href="modif_medio_editar.php?file=<?php echo $file ?>&codnum=<?php echo $registro[6] ?>&ncomp=<?php echo $num_fact ?>&serierel=<?php echo $serierel ?>&tcomprel=<?php echo $tip_comp ?>&remate=<?php echo $num_remate ?>&tot_gen=<?php echo $tot_gen ?>"><img src="images/b_edit.png" width="16" height="16" border="0" /></a></td>
            </tr>
			<?php } ?>
            <tr>
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td colspan="3" bgcolor="#993333"><div align="right"><img src="images/total_depositos_mar.gif" width="240" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_cheques" type="text" class="seleccion" id="importe_cheque" size="15" value="<?php echo $monto_total_cheques ?>" /></td>
              <td bgcolor="#993333">&nbsp;</td>
              <td bgcolor="#993333">&nbsp;</td>
            </tr>
          </form>
        </table></td>
      </tr>
      
      
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="3" bgcolor="#993333" ><div align="center"><img src="images/moneda_extranj.gif" width="400" height="24" /></div></td>
          </tr>   <tr> 
            <td width="639" bgcolor="#999966"><div align="left"><img src="images/concepto.gif" width="200" height="16" /></div>
              <div align="center"></div></td>
            <td colspan="2" bgcolor="#999966"><div align="right"><img src="images/importes_marron.gif" width="70" height="16" /></div></td> 
			</tr>
           
		  <?php //$tot_efectivo  =($efectivo + $precio2) ;
?>
            <tr>
              <td bgcolor="#993333">&nbsp;</td>
              <td width="101" colspan="2"   bgcolor="#993333"  align="right">&nbsp;</td>
              </tr>
            <tr>
              <td bgcolor="#993333"><input name="descripcion_pesos" type="text" class="seleccion" value="Total de Pesos" size="30"/></td><td width="101" colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos" type="text" class="seleccion"  size="13" value="<?php echo $tot_efectivo ?>" /> </td>
              </tr>
            <tr>
              <td bgcolor="#993333"><div align="right"><img src="images/total_efectivo.gif" width="136" height="26" /></div></td>
              <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos" type="text" class="seleccion"  size="13" value="<?php echo $tot_efectivo ?>" /></td>
            </tr>
           
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td bgcolor="#ECE9D8">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td width="292" bgcolor="#ECE9D8">&nbsp;</td>
        <td width="349" align="center" valign="middle"><div align="right">
          <img src="images/total_general_v.gif" width="136" height="26" />
          <input name="tot_gen" type="text" class="seleccion" value="<?php echo $total_general ?>" />
        </div></td>
      </tr> 
	
      <tr>
        <td><?php // ($efectivo + $precio2);
			 //  echo "Tot".$tot_efectivo."<br>" ;
			 // $tot_efectivo = 0 ;
			 if ($tot_efectivo!=0 || $precio1!=0 || $monto_total_cheques!=0) {
	          	$leyenda = "Se abona con : \n"; 
			 }
			 if ($tot_efectivo!=0) {
	         	$leyenda = $leyenda." Efectivo : $".$tot_efectivo." \n";
			 }
			 if ($monto_total_cheques!=0) { //  $precio1 = 0 ;
			   	$leyenda = $leyenda." Dep�sito : ".$reg_ley."\n";
			  	$leyenda = $leyenda." Total de dep�sitos  $".$monto_total_cheques."\n";
			 }
			 //  $monto_total_cheques = 0 ;
			 if ($precio1!=0) {
	         	$leyenda = $leyenda." Cheques\n".$reg_leyenda."\n";
			  	$leyenda = $leyenda." Total de cheques  :$".$precio1."\n";
                                $leyenda = $leyenda." Retira contra acreditacion de cheques.\n";
			 }
			 if ($monto_sus!=0) {
	         	$leyenda = $leyenda." Ret. SUSS $".$monto_sus." Comp N".$comp_sus."\n";
			  //	$leyenda = $leyenda." Total de cheques  :$".$precio1."\n";
               //                 $leyenda = $leyenda." Retira contra acreditacion de cheques.\n";
			 }
			 if ($monto_ganancias!=0) {
	         	$leyenda = $leyenda." Ret. Ganacias $".$monto_ganancias." Comp N".$comp_ganancias."\n";
			  	
			 }
			 if ($monto_iva!=0) {
	         	$leyenda = $leyenda." Ret. SUSS $".$monto_iva." Comp N".$comp_iva."\n";
			  	
			 }
			 if ($monto_ing_brutos!=0) {
	         	$leyenda = $leyenda." Ret. SUSS $".$monto_ing_brutos." Comp N".$comp_ing_brutos."\n";
			  
			 }
			// echo $leyenda ;
			 //   if ($precio1!=0) {
			 //  $leyenda = $leyenda
	            
	  ?>	  </td>
        <td colspan="2" rowspan="2" bgcolor="#ECE9D8"><div align="center"><img src="images/leyenda.gif" width="230" height="26" />          </div>          <form id="leyenda" name="leyenda" method="POST" action="<?php echo $editFormAction; ?>">
            <div align="center">
              <textarea name="leyendafc" cols="120" rows="10" class="seleccion"><?php echo $leyenda ?></textarea>
              <input name="tcomp" type="hidden" class="seleccion" value="<?php echo $tip_comp ?>" />
              <input name="serie" type="hidden" class="seleccion" value="<?php echo $serie ?>" />
              <input name="codrem" type="hidden" class="seleccion" value="<?php echo $num_remate ?>" />
              <input name="numfac"    type="hidden"  value="<?php echo $num_fact ?>"/>
              <input name="Submit3" type="submit" class="seleccion" value="Insertar Leyenda" />
              <input type="hidden" name="MM_insert" value="leyenda">
            </div>
          </form></td>
        </tr>
      <tr>
	  
	  
	  
        <td rowspan="2">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="2" bgcolor="#ECE9D8" align="center">&nbsp;</td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td bgcolor="#ECE9D8">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><a href="javascript:close()"><img src="images/salir_but.gif" width="72" height="20"  border="0"/></a></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><script type="text/javascript"> 
//alert()
chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('banco','sucursal','includes/getsucural.php'); 
chainedSelects.addChain('banco1','sucursal1','includes/getsucural.php'); 
//chainedSelects.addChain('city','university','includes/getLocalidad.php'); 
chainedSelects.init(); 

</script> 
</body>
</html>
<?php
mysql_free_result($bancos);
mysql_free_result($Cheques);
mysql_free_result($sucursales);
//mysql_free_result($sucursal);
?>
