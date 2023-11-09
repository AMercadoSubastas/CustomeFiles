<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "funcion_mysqli_result.php";
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "cheques")) {
	$codpais = 1;
	// LEO EL ULTIMO DE LA SERIE
	mysqli_select_db($amercado, $database_amercado);
	$numero = 10;
	$query_serie = "SELECT * FROM series WHERE  codnum ='$numero'";
	$serie       = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));
	$row_serie = mysqli_fetch_array($serie);
	// variables del formulario tipo de pago
	$moneda = 1 ; // tipo de moneda
	$banco        = $_POST['banco'];               // codigo Banco
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
 	$num_comp = ($row_serie['nroact'])+1 ; 


	$strSQL = "INSERT INTO cartvalores (tcomp, serie, ncomp, codban,  codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , tcompsal , ncompsal , seriesal ) VALUES ('$tcomp','$serie','$num_comp','$banco','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'S' ,'1','$codrem' , '$numfac' , '$tcomprel' , '$numfac' , '$serierel')";
	
	
	// 4. Ejecuto la consulta.	
	$result = mysqli_query($amercado, $strSQL);				         
			 
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='10'" ;				 
	$resultado=mysqli_query($amercado, $actualiza);		

}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "depositos")) {

	$codpais = 1;
    mysqli_select_db($amercado, $database_amercado);
	// LEO EL ULTIMO DE LA SERIE
	$numero = 15;
	$query_serie = "SELECT * FROM series WHERE  codnum ='$numero'";
	$serie       = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));
	$row_serie = mysqli_fetch_array($serie, $amercado);
	// variables del formulario tipo de pago
	$moneda = 1 ; // tipo de moneda
	$banco        = $_POST['banco1'];               // codigo Banco
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
	$num_comp = ($row_serie['nroact'])+1 ; 

	$strSQL = "INSERT INTO cartvalores (tcomp, serie, ncomp, codban, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , seriesal ,tcompsal , ncompsal ) VALUES ('$tcomp','$serie','$num_comp','$banco','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'S' ,'$moneda','$codrem' , '$numfac' ,'$serierel','$tcomprel' , '$numfac' )";
	
	// 4. Ejecuto la consulta.	
	$result = mysqli_query($amercado, $strSQL);				         
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='15'" ;				 
	$resultado=mysqli_query($amercado, $actualiza);				              
	//$resultado =mysqli_query($amercado, $insetar_banco);

}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "pesos")) {

	$codpais = 1;
    mysqli_select_db($amercado, $database_amercado);
	// LEO EL ULTIMO DE LA SERIE
	$numero = 8;
	$query_serie = "SELECT * FROM series WHERE  codnum ='$numero'";
	$serie       = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));
	$row_serie = mysqli_fetch_array($serie);
	// variables del formulario tipo de pago

	$moneda = 1 ; // tipo de moneda
	if (isset($_POST['cotizacion']))
		$cotizacion   = $_POST['cotizacion']; 
	if (isset($_POST['banco1']))
		$banco        = $_POST['banco1'];               // codigo Banco
	if (isset($_POST['deposito']))
		$numcheque    = $_POST['deposito'];        // Numero de Cheque
	if (isset($_POST['fecha_deposito']))
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
	$num_comp = ($row_serie['nroact'])+1 ; 
	$strSQL = "INSERT INTO cartvalores (tcomp, serie, ncomp,  codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , seriesal ,tcompsal , ncompsal  )	VALUES ('$tcomp','$serie','$num_comp' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'S' ,'$moneda','$codrem' , '$numfac' ,'$serierel','$tcomprel' , '$numfac')";	
	
	// 4. Ejecuto la consulta.	
	$result = mysqli_query($amercado, $strSQL);				         
			 
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='8'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);

}

if ((isset($_POST["MM_insert4"])) && ($_POST["MM_insert4"] == "retenciones")) {
	// RUTINA DE RETENCIONES

	mysqli_select_db($amercado, $database_amercado);
	$query_comprobante = "SELECT * FROM series  WHERE series.codnum =34";
	$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
	$row_comprobante = mysqli_fetch_assoc($comprobante);
	$totalRows_comprobante = mysqli_num_rows($comprobante);
	$codpais = 1;
 	
	
	// variables del formulario tipo de pago
	$moneda = 1 ; // tipo de moneda
	$fecha_ingreso = $_POST['fecharet'];
	$fec_ing  = substr($fecha_ingreso,6,4)."-".substr($fecha_ingreso,3,2)."-".substr($fecha_ingreso,0,2); // TRansformacion del cheque
	echo "FEC_ING: ".$fec_ing."  ";
	$estado = "P" ;
	$tcomp =  $_POST['t_ret'];
	$serie = $_POST['serie5'];
	$serierel5 = 2;//$_POST['serierel5'];
	$tcomprel5 = 3; //$_POST['tcomprel5'];
	$numfac5 = $_POST['ncomprel5'];
	$importe_pesos2 = $_POST['importeret'];
	$comp_ing_brutos = $_POST['numret'];
    $codrem = $_POST['codrem5'];
	$num_comp4 = ($row_comprobante['nroact'])+1 ; 
	$vencimiento  = date('Y-m-d');; 
	$fecha_ingreso = date('Y-m-d'); 
	$estado = "P" ;
	$num_comp4 = ($row_comprobante['nroact'])+1 ;
	
	$strSQL = "INSERT INTO cartvalores (tcomp, serie, ncomp,  codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , seriesal ,tcompsal , ncompsal ) VALUES ('$tcomp','$serie','$num_comp4','$comp_ing_brutos' ,'$codpais','$importe_pesos2','$vencimiento', '$fecha_ingreso' ,'$serierel5','$tcomprel5' ,'S' ,'$moneda','$codrem' , '$numfac5' ,'$serierel5','$tcomprel5' , '$numfac5' )";
	$result = mysqli_query($amercado, $strSQL);				         
		 
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp4' WHERE `series`.`codnum` ='34'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);

}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "facturas")) {

	$codpais = 1;
    mysqli_select_db($amercado, $database_amercado);
	
	// variables del formulario tipo de pago
	$moneda = 1 ; // tipo de moneda
	$banco        = ""; //$_POST['banco1'];               // codigo Banco
	$numcheque    = $_POST['nrodoc3'];       // Numero de Cheque
	$vencimiento = $_POST['fecha_factura']; // Fecha Vencimiento formato YYYY-MM-DD ;
	$importe      = $_POST['importe_factura'];           //  Importe de cheque 
    echo "IMPORTE = ".$importe."   ";
	//$vencimiento  = substr($vence_cheque,6,4)."-".substr($vence_cheque,3,2)."-".substr($vence_cheque,0,2); // TRansformacion del cheque
	$fecha_ingreso = date('Y-m-d'); 
	$estado = "S" ;
	$tcomp =  $_POST['tcomp3'];
	$codrem = $_POST['codrem3'];
	$serie = $_POST['serie3'];
	$serierel = $_POST['serierel3'];
	$tcomprel = $_POST['tcomprel3'];
	$numfac = $_POST['numfac3'];
	$total_general = $_POST['tot_general'];
	$num_comp = $_POST['ncomp3']; 

	$strSQL = "INSERT INTO cartvalores (tcomp, serie, ncomp, codban, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , seriesal ,tcompsal , ncompsal ) VALUES ('$tcomp','$serie','$num_comp','$banco','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'S' ,'$moneda','$codrem' , '$numfac' ,'$serierel','$tcomprel' ,'$numfac' )";
	
	// 4. Ejecuto la consulta.	
	$result = mysqli_query($amercado, $strSQL);				         
	//$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='15'" ;				 
	//$resultado=mysqli_query($amercado,	$actualiza);				              
	//$resultado =mysqli_query($amercado, $insertar_factura);

}

// HASTA ACA ===========================================================================================
?>
<?php require_once('Connections/amercado.php'); 

$num_fact =  $_GET['liquidacion'];
$num_remate = $_GET['remate'];
$tot_gen = $_GET['tot_general'];
$serierel = $_GET['serie'];;
$tip_comp = $_GET['tipocomp'];
$cliente_rem = $_GET['cliente'];

mysqli_select_db($amercado, $database_amercado);
$query_tipcomp_ret = "SELECT * FROM tipcomp WHERE codafip IS NOT null and codnum in (66,68)";
$tipcomp_ret = mysqli_query($amercado, $query_tipcomp_ret) or die("ERROR LEYENDO RETENCIONES");
$row_tipcomp_ret = mysqli_fetch_assoc($tipcomp_ret);
$totalRows_tipcomp_ret = mysqli_num_rows($tipcomp_ret);

$tot_reten = 0;
$monto_ganancias = 0.00;
$monto_iva = 0.00;
echo " 1 ";
mysqli_select_db($amercado, $database_amercado);
$query_bancos = "SELECT * FROM bancos";
$bancos = mysqli_query($amercado, $query_bancos) or die(mysqli_error($amercado));
$row_bancos = mysqli_fetch_assoc($bancos);
$totalRows_bancos = mysqli_num_rows($bancos);

mysqli_select_db($amercado, $database_amercado);
$query_cabfac = "SELECT * FROM cabfac WHERE cliente = $cliente_rem AND tcomp IN (115,116,117,125,126,127,133,134) AND estado ='P'";
$cabfac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));
$row_cabfac = mysqli_fetch_assoc($cabfac);
$totalRows_cabfac = mysqli_num_rows($cabfac);

//echo " 2 ";
$query_Cheques = "SELECT * FROM cartvalores";
$Cheques = mysqli_query($amercado, $query_Cheques) or die(mysqli_error($amercado));
$row_Cheques = mysqli_fetch_assoc($Cheques);
$totalRows_Cheques = mysqli_num_rows($Cheques);
if (isset($tcomp[0]))
	$tcomp = $tcomp[0] ;
if (isset($serie[0]))
	$serie = $serie[0] ;
if (isset($remate_num[0]))
	$remate = $remate_num[0];
if (isset($tot_general[0]))
	$tot_general = $tot_general[0];
echo " 3 ";
$sql_deposito = "SELECT   bancos.nombre , codchq , importe , fechapago , codrem" 
        . " FROM cartvalores , bancos  "
       	. " WHERE tcomp = 39"
        . " AND serie = 15" 
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem = $num_remate"
        . " AND bancos.codnum = cartvalores.codban"
        . ' ';;
		$total_deposito = mysqli_query($amercado, $sql_deposito) or die(mysqli_error($amercado));
echo " 4 ";		
$sql_deposito1 = "SELECT   bancos.nombre  ,codchq , importe , fechapago , codrem" 
        . " FROM cartvalores , bancos  "
       . " WHERE tcomp = 39"
        . " AND serie = 15" 
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem = $num_remate"
        . " AND bancos.codnum = cartvalores.codban"
        . ' ';;
		$total_deposito1 = mysqli_query($amercado, $sql_deposito1) or die(mysqli_error($amercado));
		$monto_total_depositos = 0;	
		while ($depositos = mysqli_fetch_array($total_deposito1)){
   			$monto_total_depositos = $monto_total_depositos + $depositos['2'];
		}
echo " 5 ";
$sql_chk = "SELECT bancos.nombre ,codchq , importe , fechapago , codrem"
        . " FROM cartvalores , bancos "
        . " WHERE tcomp =14"
        . " AND serie =10"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
        . " AND bancos.codnum = cartvalores.codban"
        . ' ';
	   $total_cheques = mysqli_query($amercado, $sql_chk) or die(mysqli_error($amercado));
echo " 6 ";	   
$sql_chk1 = "SELECT bancos.nombre  ,codchq , importe , fechapago , codrem"
      . " FROM cartvalores , bancos "
      . " WHERE tcomp =14"
      . " AND serie =10"
      . " AND tcomprel =$tip_comp"
      . " AND serierel =$serierel"
      . " AND ncomprel = $num_fact"
      . " AND codrem =$num_remate"
      . " AND bancos.codnum = cartvalores.codban"
      . ' ';
	   $total_cheques1 = mysqli_query($amercado, $sql_chk1) or die(mysqli_error($amercado));
	
	  	$monto_total_cheques1 = 0;	
		while ($cheques = mysqli_fetch_array($total_cheques1)){
   			$monto_total_cheques1  = $monto_total_cheques1 + $cheques['2'];
			
		}
	echo " 7 ";		
$tot_efectivo = 0;
$sql_pesos = "SELECT importe "
        . " FROM cartvalores "
        . " WHERE tcomp =12"
        . " AND serie =8"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
		. ' ';
		$total_efectivo = mysqli_query($amercado, $sql_pesos) or die(mysqli_error($amercado));
		while ($efectivo = mysqli_fetch_array($total_efectivo)){
   			$tot_efectivo =  $tot_efectivo + $efectivo['importe'];
   		}

echo " 8 ";		

		$sql_iva = "SELECT codchq  , fechapago , importe"
       . " FROM cartvalores "
        . " WHERE tcomp =40"
        . " AND serie =22"
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . ' ';
	
		$sql_ing_brutos = "SELECT codchq  , fechapago , importe, tcomp"
        . " FROM cartvalores "
        . " WHERE ((tcomp BETWEEN 66 AND 85) OR tcomp = 90)"
        . " AND serie = 34"
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . ' ';
		
		$sql_ganancias = "SELECT codchq  , fechapago , importe"
        . " FROM cartvalores "
        . " WHERE tcomp =42"
        . " AND serie =24"
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . ' ';
		
		$sql_suss = "SELECT codchq  , fechapago , importe"
        . " FROM cartvalores "
        . " WHERE tcomp =43"
        . " AND serie =25"
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . ' '; 

$tot_facturas = 0;
$sql_factura = "SELECT importe, codchq "
        . " FROM cartvalores "
        . " WHERE tcomp in (115,116,117,125,126,127,133,134)"
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serierel"
        . " AND ncomprel = $num_fact"
        . " AND codrem = $num_remate"
		. ' ';
$total_facturas = mysqli_query($amercado, $sql_factura) or die(mysqli_error($amercado));
	while ($facturas = mysqli_fetch_array($total_facturas)){
   			$tot_facturas =  $tot_facturas + $facturas['importe'];
	}

echo " 9 ";
// Retencion de IVA
$total_iva = mysqli_query($amercado, $sql_iva) or die(mysqli_error($amercado));
$totalRows_total_iva = mysqli_num_rows($total_iva);
//$fecha_iva = substr($fecha_iva,8,2)."-".substr($fecha_iva,5,2)."-".substr($fecha_iva,0,4);

// Retenciones
$total_ing_brutos = mysqli_query($amercado, $sql_ing_brutos) or die(mysqli_error($amercado));	
$totalRows_total_ing_brutos = mysqli_num_rows($total_ing_brutos);
//$fecha_ing_brutos = substr($fecha_ing_brutos,8,2)."-".substr($fecha_ing_brutos,5,2)."-".substr($fecha_ing_brutos,0,4);

// Retencion de GANANCIAS
$total_ganancias = mysqli_query($amercado, $sql_ganancias) or die(mysqli_error($amercado));	
$totalRows_total_ganancias = mysqli_num_rows($total_ganancias);

// Retencion de SUSS
$total_suss = mysqli_query($amercado, $sql_suss) or die(mysqli_error($amercado));	
$totalRows_total_suss = mysqli_num_rows($total_suss);

$total_general = $tot_efectivo + $monto_total_cheques1 + $monto_total_depositos + $tot_facturas;
		$tot_faltante = $tot_gen - $total_general;


//$fecha_suss = substr($fecha_sus,8,2)."-".substr($fecha_sus,5,2)."-".substr($fecha_sus,0,4);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
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
	var cantidad = moneda.cant_moneda.value ;
	var cotiz = moneda.cotizacion.value ;
	var total = eval(cantidad*cotiz);
	moneda.tot_cotizacion.value = total
}

</script>
<script language="javascript">
function pesos(form)
{

	var pesos_ing = ing_pesos.cant_efectivo.value ;
	ing_pesos.importe_pesos.value = pesos_ing ;

}
</script> 
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
        <td colspan="2"><img src="images/num_cheque.gif" width="124" height="16" />
          <table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingres_cheques_propios.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="221" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
			<td width="150" bgcolor="#00CCFF"><img src="images/num_cheque.gif" width="124" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/vencimientoi.gif" width="130" height="16" /></td>
            <td width="219" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="cheques" name="cheque_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
               <td width="221"><select name="banco" class="seleccion" id="banco">
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
              	<td width="150"><input name="numcheque" type="text" class="seleccion" id="numcheque" /></td>
              
                <td width="196"><div align="right">
                    <input name="venc" type="text" class="seleccion" id="venc" size="11" />
                </div></td>
                <td width="84"><a href="javascript:showCal('Calendar3')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
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
              <td width="221">&nbsp;</td>
              
              <td width="150"><input name="estado"    type="hidden"  value="P"/></td>
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
            
            <td width="150" bgcolor="#00CCFF"><img src="images/num_cuenta.gif" width="130" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/fecha_deposito.gif" width="130" height="16" /></td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
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
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingreso_facturas.gif" width="402" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/factura.gif" width="150" height="16" /></div></td>
            
            
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="facturas_form" name="facturas_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
              <td width="140"><select name="cabfac1" class="seleccion" id="cabfac1" onchange="elijofacturas(this.form)">
                  <option value="" class="phpmaker">Seleccione Factura</option>
                  <?php
                   do {  
                       if(isset($row_cabfac['nrodoc'])) {?>
                  <option  class="phpmaker" value="<?php echo $row_cabfac['nrodoc']?>"><?php echo $row_cabfac['totbruto']." - "?><?php echo $row_cabfac['nrodoc']?></option>
                  <?php
														}
                        } while ($row_cabfac = mysqli_fetch_assoc($cabfac));
                             $rows = mysqli_num_rows($cabfac);
                              if($rows > 0) {
                                 mysqli_data_seek($cabfac, 0);
	                            $row_cabfac = mysqli_fetch_assoc($cabfac);
                               }
                           ?>
              </select></td>
              
              
              
              <td><input name="importe_factura" type="text" class="seleccion" size="10"  />    </td>
            </tr>
            <tr><input name="numfac3"    type="hidden"  value="<?php echo $num_fact ?>"/>
              <td ><input name="codrem3"    type="hidden"  value="<?php echo $num_remate ?>"/></td>
              <td><input name="fecha_factura"  type="hidden"  value="<?php echo $row_cabfac['fecreg'] ?>"/></td>
              <td><input name="serie3"     type="hidden"  value="<?php echo $row_cabfac['serie'] ?>"/></td>
              <input name="ncomp3"     type="hidden"  value="<?php echo $row_cabfac['ncomp']?>"/>
              <input name="serierel3"  type="hidden" value="<?php echo $serierel ?>" />
              <input name="tcomprel3"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado3"    type="hidden"  value="S"/>
              <input name="tcomp3"     type="hidden"  value="<?php echo $row_cabfac['tcomp']?>"/>
              <input name="nrodoc3"     type="hidden"  value="<?php echo $row_cabfac['nrodoc']?>"/>
			  <input name="tot_general" type="hidden"  value="<?php echo $tot_gen ?>" />
              <td colspan="3"><input name="Submit223" type="submit" class="seleccion" value="Ingresar Factura asociada" />    </td>
            </tr>
			<input type="hidden" name="MM_insert" value="facturas">
          </form>
        </table></td>
      </tr>

        
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingresoefectivo.gif" width="500" height="24" /></div></td>
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
        <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td height="24" colspan="8" bgcolor="#0094FF" ><div align="center" class="Estilo1">Retenciones (Ingresar Nro original de la retencion) </div></td>
          </tr>
        <tr>
            <td width="180" bgcolor="#00CCFF"><div align="center" class="Estilo1">Tipo</div>              <div align="center"></div></td>
			
            <td width="120" bgcolor="#00CCFF" class="Estilo1"><div align="center">Numero Ret </div></td>
            <td colspan="2" bgcolor="#00CCFF" class="Estilo1"><div align="center">Fecha</div></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
            <td width="114" bgcolor="#00CCFF">&nbsp;</td>
          </tr>
		  
           <form id="ret_form" name="ret_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
               <td width="140"><select name="t_ret" class="seleccion" id="t_ret">
                 <option value="" class="phpmaker">Seleccione Tipo Ret</option>
                 <?php
                   do {  
                        ?>
                 <option  class="phpmaker" value="<?php echo $row_tipcomp_ret['codnum']?>"><?php echo $row_tipcomp_ret['descripcion']?></option>
                 <?php
                        } while ($row_tipcomp_ret = mysqli_fetch_assoc($tipcomp_ret));
                             $rows = mysqli_num_rows($tipcomp_ret);
                              if($rows > 0) {
                                 mysqli_data_seek($tipcomp_ret, 0);
	                            $row_tipcomp_ret = mysqli_fetch_assoc($tipcomp_ret);
                               }
                           ?>
               </select></td>
               
                <td width="150"><input name="numret" type="text" class="seleccion" id="numret" /></td>
                <td width="97"><div align="right">
                    <input name="fecharet" type="text" class="seleccion" id="fecharet" size="11" />
                </div></td>
                <td width="24"><a href="javascript:showCal('Calendar9')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
                <td><input name="importeret" type="text" class="seleccion" id="importeret" size="10" />                </td>
              </tr>
	   
			   
            <tr>
			    <input name="codrem5"  type="hidden"  value="<?php echo  $remate ?>"/>
	            <input name="fechaing5"  type="hidden"  value="<?php echo $fecharet ?>"/>
                <input name="serie5"     type="hidden"  value="34"/>
	            <input name="ncomprel5"     type="hidden"  value="<?php echo $num_fact ?>"/>	 
	            <input name="serierel5"  type="hidden"  value="<?php echo $serierel ?>" />
	            <input name="tcomprel5"  type="hidden"  value="<?php echo $tcomprel ?>" />
              <td width="150">&nbsp;</td>
              <td width="150"><input name="estado5"    type="hidden"  value="P"/></td>
              <td width="150"><input name="tcomp5"     type="hidden"  value="<?php echo $tcomprel ?>"/></td>
			   <input type="hidden" name="MM_insert4" value="retenciones">
              <td colspan="3"><div align="center">
			      <input name="Submit" type="submit" class="seleccion" value="Ingresar retenciones" />
                         </div></td>
            </tr>
          </form>
        </table></td>
      </tr>
  	   <tr>
        <td>&nbsp;</td>
        <td colspan="2"><div align="center"></div></td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="5" bgcolor="#993333" ><div align="center"><img src="images/cheques_propios_marron.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            
            <td width="150" bgcolor="#999966"><img src="images/num_chq_marron.gif" width="135" height="16" /></td>
            <td bgcolor="#999966"><img src="images/fecha_vencimiento.gif" width="135" height="16" /></td>
            <td width="123" bgcolor="#999966"><div align="center"><img src="images/importes_marron.gif" width="70" height="16" /></div></td>
          </tr>
	
          <form id="form1" name="deposito_list" method="post" action="">
	<?php	$precio1=0;
			$reg_leyenda = "";
			while ($registro1 = mysqli_fetch_array($total_cheques)){
   				$precio1= $precio1 + $registro1['2'];
   				$reg_leyenda = $reg_leyenda." -".$registro1[0].", N�".$registro1[1]."  $".$registro1[2]." "; 

	?>
<tr>
<td width="150" bgcolor="#993333"><input name="textfield52" type="text" class="seleccion" value="<?php echo $registro1[0] ?>"/></td>
<td width="150" bgcolor="#993333"><input name="textfield232" type="text" class="seleccion" value="<?php echo $registro1[1] ?>"/></td>
<td width="150" bgcolor="#993333"><input name="textfield2222" type="text" class="seleccion" value="<?php echo $registro1[3] ?>"></td>
<td bgcolor="#993333"><input name="fecha_venc2" type="text" class="seleccion" id="fecha_venc" size="13" value="<?php echo $registro1[2] ?>"/></td>
    
        </tr>
		<?php } ?>
            <tr>
             
              <td bgcolor="#993333">&nbsp;</td>
              <td colspan="2" bgcolor="#993333"><div align="right"><img src="images/total_cheques_marr.gif" width="136" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_deposito" type="text" class="seleccion" size="15"  value="<?php echo $precio1 ?>" onChange="deposito(this.form)"/></td>
            </tr></form>
          
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="4" bgcolor="#993333" ><div align="center"><img src="images/depositos_bancarios_porpios.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            
            <td width="150" bgcolor="#999966"><img src="images/numn_de_cuenta.gif" width="150" height="16" /></td>
            <td  bgcolor="#999966"><img src="images/fecha_dep_marro.gif" width="150" height="16" /></td>
            <td width="123" bgcolor="#999966"><img src="images/importes_marron.gif" width="70" height="16" /></td>
          </tr>
          <form id="cheques" name="cheque_list" method="post" >
		<?php  $monto_total_cheques = 0;
				while ($registro = mysqli_fetch_array($total_deposito)){
   					$monto_total_cheques = $monto_total_cheques + $registro['2'];
   					//$reg_ley = $reg_ley." - ".$registro[0]." , N�".$registro[1].", $".$registro[2]."  "; 
           ?>
		  
            <tr>
              <td width="150" bgcolor="#993333"><input name="list_banco_cheque" type="text" class="seleccion" id="list_banco_cheque" value="<?php echo $registro[0] ?>"/></td>
              <td bgcolor="#993333" ><input name="list_suc_cheque" type="text" class="seleccion" value="<?php echo $registro[1] ?>"/></td>
              <td width="150" bgcolor="#993333"><input name="list_num_cheque" type="text" class="seleccion" id="numcheque2" value="<?php echo $registro[3] ?>"/></td>
             
              <td bgcolor="#993333"><input name="list_importe_cheque" type="text" class="seleccion" id="importe2" size="15" value="<?php echo $registro[2] ?>"/>              </td>
            </tr>
			<?php } ?>
            <tr>
              
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td colspan="2" bgcolor="#993333"><div align="right"><img src="images/total_depositos_mar.gif" width="240" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_cheques" type="text" class="seleccion" id="importe_cheque" size="15" value="<?php echo $monto_total_cheques ?>" /></td>
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
        <td colspan="2" bgcolor="#ECE9D8"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="5" bgcolor="#993333" ><div align="center"><img src="images/retensiones.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="304" bgcolor="#999966"><div align="left"><img src="images/concepto.gif" width="200" height="16" /></div>                </td>
            <td width="156" bgcolor="#999966"><img src="images/comprobante_marron.gif" width="112" height="16" /></td>
            <td width="100" bgcolor="#999966"><img src="images/fecha_maa.gif" width="100" height="16" /></td>
            <td colspan="2" bgcolor="#999966"><div align="right"><img src="images/importes_marron.gif" width="70" height="16" /></div></td> 
          </tr>
            <?php 	if ($totalRows_total_ing_brutos!=0) {  
              	$total_ing_brutos1 = mysqli_query($amercado, $sql_ing_brutos) or die(mysqli_error($amercado));
		  		while ($ing_brutos = mysqli_fetch_array($total_ing_brutos1)){
			   		$tot_reten=$tot_reten+$ing_brutos['2'];
 	?>
          <tr>
            <td bgcolor="#993333"><input  type="text" class="seleccion" value="<?php echo $ing_brutos['3'] ?>" size="45"/></td>
            <td bgcolor="#993333"><input type="text" class="seleccion" name="textfield" value="<?php echo $ing_brutos['0'] ?>" /></td>
            <td width="100" bgcolor="#993333"><input  name="textfield2" class="seleccion" type="text" size="13" value="<?php echo $ing_brutos['1'] ?>"/></td>
            <td  colspan="2"   bgcolor="#993333"  align="right"><input align="right" name="importe_dol2" type="text" class="seleccion"  size="13" value="<?php echo $ing_brutos['2'] ?>" />            </td>
          </tr> <?php } } 
		    if ($totalRows_total_ganancias!=0) { 
				$total_ganancias1 = mysqli_query($amercado, $sql_ganancias) or die(mysqli_error($amercado));
			 		while ($ganancias = mysqli_fetch_array($total_ganancias1)){
			  			$tot_reten=$tot_reten+$ganancias['2']  ;
			  			//echo "Hola adentro de Ganancias 1";
			  ?>  
          <tr>
            <td bgcolor="#993333"><input name="descripcion_pesos22" type="text" class="seleccion" value="Retenci&oacute;n Ganacias" size="45"/></td>
            <td bgcolor="#993333"><input type="text" class="seleccion" value="<?php echo $ganancias['0'] ?>" /></td>
            <td width="100" bgcolor="#993333"><input class="seleccion" name="textfield22" type="text" size="13" value="<?php echo $ganancias['1'] ?>"/></td> 
            <td  colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos2" type="text" class="seleccion"  size="13" value="<?php echo $ganancias['2'] ?>" />            </td>
          </tr> <?php }} 
		   if ($totalRows_total_iva!=0) {  
		     $total_iva1 = mysqli_query($amercado, $sql_iva) or die(mysqli_error($amercado));
		  // echo "Hola adentro de Total IVA 0";
		   while ($iva = mysqli_fetch_array($total_iva1)){
		  //  echo "Hola adentro de Total IVA 1";
			  $tot_reten=$tot_reten+$iva['2'] ?>  
          <tr>
            <td bgcolor="#993333"><input name="descripcion_pesos2" type="text" class="seleccion" value="Impuesto Valor Agregado" size="45"/></td>
            <td bgcolor="#993333"><input name="textfield4" type="text" class="seleccion" value="<?php echo $iva['0'] ?>"/></td>
            <td width="100" bgcolor="#993333"><input name="textfield23" class="seleccion" type="text" size="13" value="<?php echo $iva['1'] ?>"/></td>
            <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos22" type="text" class="seleccion"  size="13" value="<?php echo $iva['2'] ?>" /></td>
          </tr><?php }} 
		    if ($totalRows_total_suss!=0) { 
			//echo "adentro SUSS"; 
			 $total_suss1 = mysqli_query($amercado, $sql_suss) or die(mysqli_error($amercado));
		  while ($suss = mysqli_fetch_array($total_suss1)){
		   //  echo "Dentro del SUSS";
			  $tot_reten=$tot_reten+$suss['2']
		  
		   ?>
          <tr>
            <td bgcolor="#993333"><input name="descripcion_pesos2" type="text" class="seleccion" value="Retencion del SUSS" size="45"/></td>
            <td bgcolor="#993333"><input type="text" name="textfield5" class="seleccion" value="<?php echo $suss['0'] ?>" /></td>
            <td width="100" bgcolor="#993333"><input name="textfield24" class="seleccion" type="text" size="11" value="<?php echo $suss['1'] ?>"/></td>
            <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos23" type="text" class="seleccion"  size="13" value="<?php echo $suss['2'] ?>" /></td>
          </tr><?php }} ?>
          <tr>
            <td colspan="3" bgcolor="#993333"><div align="right"><img src="images/total_reten.gif" width="136" height="26" /></div></td>
            <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos2" type="text" class="seleccion"  size="13" value="<?php echo $tot_reten ?>" /></td>
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
        <td><?php 
	         $leyenda = "";
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
			
			 if ($precio1!=0) {
	         	$leyenda = $leyenda." Cheques\n".$reg_leyenda."\n";
			  	$leyenda = $leyenda." Total de cheques  :$".$precio1."\n";
                                $leyenda = $leyenda." Retira contra acreditacion de cheques.\n";
			 }

	            
	  ?>	  
          </td>
        <td colspan="2" rowspan="2" bgcolor="#ECE9D8"><div align="center"><img src="images/leyenda.gif" width="230" height="26" />          </div>          <form id="leyenda" name="leyenda" method="POST" action="<?php echo $editFormAction; ?>">
            <div align="center">
         <textarea name="leyendafc" cols="120" rows="10" class="seleccion"><?php echo $leyenda ?></textarea>
         <input name="tcomp" type="hidden" class="seleccion" value="<?php echo $tip_comp ?>" />
         <input name="serie" type="hidden" class="seleccion" value="<?php echo $serierel ?>" />
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


</script> 
</body>
</html>
