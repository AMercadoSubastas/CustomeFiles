<?php 
error_reporting(E_ALL);
ini_set('display_errors','Yes');
require_once('Connections/amercado.php'); 
include_once "src/userfn.php";
$total_general = 0.00;
$cheques = 0.00;
$cheques_a_terceros = 0.00;
$dola = 0.00;
$efectivo = 0.00;
$depositos = 0.00;
$depositos_terceros = 0.00;
$monto_sus = 0.00;
$monto_ganancias = 0.00;
$monto_ing_brutos = 0.00;
$comp_ing_brutos = "";
$monto_iva = 0.00;
$tot_faltante =  0.00;
$tot_gen = 0.00;
$cliente  = $_GET['cliente'];
$fecha_ing = date('Y-m-d'); 
$fecha_ing11 = date('Y-m-d'); 

//echo "CLIENTE = ".$cliente."  ";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$codpais = 1;
$moneda = 1 ; 
// AGREGO LOS CHEQUES
if ((isset($_POST["MM_insert1"])) && ($_POST["MM_insert1"] == "cheque")) {
    // Busco el ultimo nro de la serie para los cheques
	mysqli_select_db($amercado, $database_amercado);
	$query_comprobante = "SELECT * FROM series  WHERE series.codnum =6";
	$comprobante1 = mysqli_query($amercado, $query_comprobante) or die("ERROR LEYENDO SERIES PARA CHEQUES");
	$row_comprobante = mysqli_fetch_assoc($comprobante1);
	$totalRows_comprobante = mysqli_num_rows($comprobante1);

    $banco          = $_POST['banco'];
	$query_sucursal = "SELECT * FROM sucbancos WHERE codbanco = '$banco'";
	$suc = mysqli_query($amercado, $query_sucursal) or die("ERROR LEYENDO SUCURSALES");
	$row_sucursal = mysqli_fetch_assoc($suc);
	$sucursal = $row_sucursal['codnum'];
	
	$numcheque    = $_POST['numcheque'];
	$vence_cheque = $_POST['venc']; 
	$importe      = $_POST['importe']; 
	$vencimiento  = substr($vence_cheque,6,4)."-".substr($vence_cheque,3,2)."-".substr($vence_cheque,0,2); // TRansformacion del cheque
	$fecha_ingreso = date('Y-m-d'); 
	$estado = "P" ;
	$tcomp =  $_POST['tcomp'];
	$serie = $_POST['serie'];
	$serierel = $_POST['serierel'];
	$tcomprel = $_POST['tcomprel'];
	$numfac = $_POST['numfac']; 
	$codrem = $_POST['codrem'];
	echo $codrem ;
	$num_comp = ($row_comprobante['nroact'])+1 ;
	$strSQL = "INSERT INTO cartvalores	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda , ncomprel , codrem )	VALUES ('$tcomp','$serie','$num_comp','$banco','$sucursal','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'P' ,'$moneda', '$numfac', '$codrem' )";
	$result = mysqli_query($amercado, $strSQL);				         
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='6'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);

}
//============================================================================================================
// INSERTO LOS CHEQUES A TERCEROS 
if ((isset($_POST["MM_insert11"])) && ($_POST["MM_insert11"] == "cheque11")) {

	mysqli_select_db($amercado, $database_amercado);
	$query_comprobante = "SELECT * FROM series  WHERE series.codnum = 44";
	$comprobante2 = mysqli_query($amercado, $query_comprobante) or die("ERROR LEYENDO SERIE 44");
	$row_comprobante = mysqli_fetch_assoc($comprobante2);
	$totalRows_comprobante = mysqli_num_rows($comprobante2);
	$banco11        = $_POST['banco11'];               // codigo Banco
	$query_sucursal = "SELECT * FROM sucbancos WHERE codbanco = '$banco11'";
	$suc = mysqli_query($amercado, $query_sucursal) or die("ERROR LEYENDO SUC BANCOS 75");
	$row_sucursal = mysqli_fetch_assoc($suc);
	$sucursal11 = $row_sucursal['codnum'];
	// Codigo sucursal
	$numcheque11    = $_POST['numcheque11'];       // Numero de Cheque
	$vence_cheque11 = $_POST['venc11']; // Fecha Vencimiento formato YYYY-MM-DD ;
	$importe11      = $_POST['importe11'];           //  Importe de cheque 
	$vencimiento11  = substr($vence_cheque11,6,4)."-".substr($vence_cheque11,3,2)."-".substr($vence_cheque11,0,2); 
	$fecha_ingreso11 = date('Y-m-d'); 
	$estado11 = "P" ;
	$tcomp11 =  $_POST['tcomp11'];
	$serie11 = $_POST['serie11'];
	$serierel11 = $_POST['serierel11'];
	$tcomprel11 = $_POST['tcomprel11'];
	$numfac11 = $_POST['numfac11']; //En realidad es el recibo
	$codrem11 = $_POST['codrem11'];
	echo $codrem11 ;
	$num_comp11 = ($row_comprobante['nroact']) + 1 ;
	$strSQL = "INSERT INTO cartvalores	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda , ncomprel , codrem )	VALUES ('$tcomp11','$serie11','$num_comp11','$banco11','$sucursal11','$numcheque11' ,'$codpais','$importe11','$vencimiento11', '$fecha_ingreso11' ,'$serierel11','$tcomprel11' ,'P' ,'$moneda', '$numfac11', '$codrem11' )";
	$result = mysqli_query($amercado, $strSQL);				         
		 
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp11' WHERE `series`.`codnum` ='44'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);

}
//============================================================================================================
if ((isset($_POST["MM_insert2"])) && ($_POST["MM_insert2"] == "transferencia")) {
	// INGRESO DE TRANSFERENCIAS BANCARIAS 
	mysqli_select_db($amercado, $database_amercado);
	$query_comprobante = "SELECT * FROM series  WHERE series.codnum =7";
	$comprobante3 = mysqli_query($amercado, $query_comprobante) or die("ERROR LEYENDO TRF 105");
	$row_comprobante = mysqli_fetch_assoc($comprobante3);
	$totalRows_comprobante = mysqli_num_rows($comprobante3);
	$codpais = 1;

	$moneda = 1 ; // tipo de moneda

	$banco        = $_POST['banco1'];               // codigo Banco
	$query_sucursal = "SELECT * FROM sucbancos WHERE codbanco = '$banco'";
	$suc = mysqli_query($amercado, $query_sucursal) or die("ERROR LEYENDO SUC BANCOS 114");
	$row_sucursal = mysqli_fetch_assoc($suc);
	$sucursal = $row_sucursal['codnum'];
	// Codigo sucursal
	$codpais = 1;

	$moneda = 1 ; // tipo de moneda

	$numcheque    = $_POST['deposito'];       // Numero de Cheque
	$vence_cheque = $_POST['fecha_deposito']; // Fecha Vencimiento formato YYYY-MM-DD ;
	$codrem = $_POST['codrem'];
	//echo $codrem ;
	$vencimiento  = substr($vence_cheque,6,4)."-".substr($vence_cheque,3,2)."-".substr($vence_cheque,0,2); 
	$fecha_ingreso = date('Y-m-d'); 
	$estado = "P" ;
	$tcomp2 =  $_POST['tcomp2'];
	$serie2 = $_POST['serie2'];
	$serierel2 = $_POST['serierel2'];
	$tcomprel2 = $_POST['tcomprel2'];
	$numfac2 = $_POST['numfac2'];
	$importe_transf     = $_POST['importe_deposito'];           //  Importe de cheque 
	$vencimiento  = substr($vence_cheque,6,4)."-".substr($vence_cheque,3,2)."-".substr($vence_cheque,0,2);
	$fecha_ingreso = date('Y-m-d'); 
	$estado = "P" ;
	$num_comp = ($row_comprobante['nroact'])+1 ;

	$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda , ncomprel ,codrem )
	VALUES ('9','7','$num_comp','$banco','$sucursal','$numcheque' ,'$codpais','$importe_transf','$vencimiento', '$fecha_ingreso' ,'$serierel2','$tcomprel2' ,'P' ,'$moneda', '$numfac2' , '$codrem')";
	$result = mysqli_query($amercado, $strSQL);				         
		 
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='6'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);
}

//=====================================================================================
if ((isset($_POST["MM_insert21"])) && ($_POST["MM_insert21"] == "transferencia_terceros")) {
	// INGRESO DE TRANSFERENCIAS BANCARIAS A TERCEROS (NUEVO)
	mysqli_select_db($amercado, $database_amercado);
	$query_comprobante_terceros = "SELECT * FROM series  WHERE series.codnum =39";
	$comprobante_terceros = mysqli_query($amercado, $query_comprobante_terceros) or die("ERROR LEYENDO SERIE 39");
	$row_comprobante_terceros = mysqli_fetch_assoc($comprobante_terceros);
	$totalRows_comprobante_terceros = mysqli_num_rows($comprobante_terceros);
	$codpais = 1;

	$moneda = 1 ; // tipo de moneda
    // Codigo sucursal
	$banco_terceros        = $_POST['banco1_terceros'];               // codigo Banco
	$query_sucursal_terceros = "SELECT * FROM sucbancos WHERE codbanco = '$banco_terceros'";
	$suc_terceros = mysqli_query($amercado, $query_sucursal_terceros) or die("ERROR LEYENDO SUCURSAL EN 163");
	$row_sucursal_terceros = mysqli_fetch_assoc($suc_terceros);
	$sucursal_terceros = $row_sucursal_terceros['codnum'];
	
	$codpais = 1;

	$moneda = 1 ; // tipo de moneda

	$numcheque_terceros    = $_POST['deposito_terceros'];       // Numero de Cheque
	$vence_cheque_terceros = $_POST['fecha_deposito_terceros']; // Fecha Vencimiento formato YYYY-MM-DD ;
	$codrem = $_POST['codrem'];
	//echo $codrem ;
	$vencimiento_terceros  = substr($vence_cheque_terceros,6,4)."-".substr($vence_cheque_terceros,3,2)."-".substr($vence_cheque_terceros,0,2); // TRansformacion del cheque
	$fecha_ingreso_terceros = date('Y-m-d'); 
	$estado = "P" ;
	$tcomp21 =  $_POST['tcomp21'];
	$serie21 = $_POST['serie21'];
	$serierel21 = $_POST['serierel21'];
	$tcomprel21 = $_POST['tcomprel21'];
	$numfac21 = $_POST['numfac21'];
	$importe_transf_terceros     = $_POST['importe_deposito_terceros'];           //  Importe de cheque 
	$vencimiento_terceros  = substr($vence_cheque_terceros,6,4)."-".substr($vence_cheque_terceros,3,2)."-".substr($vence_cheque_terceros,0,2); 
	$fecha_ingreso_terceros = date('Y-m-d'); 
	$estado = "P" ;
	$num_comp_terceros = ($row_comprobante_terceros['nroact'])+1 ;

	$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda , ncomprel ,codrem )
	VALUES ('95','39','$num_comp_terceros','$banco_terceros','$sucursal_terceros','$numcheque_terceros' ,'$codpais','$importe_transf_terceros','$vencimiento_terceros', '$fecha_ingreso_terceros' ,'$serierel21','$tcomprel21' ,'P' ,'$moneda', '$numfac21' , '$codrem')";
	$result = mysqli_query($amercado, $strSQL);				         
		 
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp_terceros' WHERE `series`.`codnum` ='39'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);
}
//=====================================================================================

if ((isset($_POST["MM_insert3"])) && ($_POST["MM_insert3"] == "efectivo")) {
	
	mysqli_select_db($amercado, $database_amercado);
	$query_comprobante = "SELECT * FROM series  WHERE series.codnum = 8";
	$comprobante4 = mysqli_query($amercado, $query_comprobante) or die("ERROR LEYENDO SERIE DE EFECTIVO");
	$row_comprobante = mysqli_fetch_assoc($comprobante4);
	$totalRows_comprobante = mysqli_num_rows($comprobante4);
	$codpais = 1;

	$moneda = 1 ; // tipo de moneda

	$fecha_ingreso = date('Y-m-d'); 
	$estado = "P" ;
	$tcomp =  $_POST['tcomp4'];
	$codrem = $_POST['codrem'];
	$serie = $_POST['serie4'];
	$serierel4 = $_POST['serierel4'];
	$tcomprel4 = $_POST['tcomprel4'];
	$numfac4 = $_POST['ncomprel4'];
	$importe_pesos = $_POST['importe_pesos'];
	$num_comp = ($row_comprobante['nroact'])+1 ; 
	//  Importe de cheque 
	$vencimiento  = date('Y-m-d');; 
	$fecha_ingreso = date('Y-m-d'); 
	$estado = "P" ;
	$num_comp4 = ($row_comprobante['nroact'])+1 ;
	
	$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp,  codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda , ncomprel )
	VALUES ('12','8','$num_comp4','1','$importe_pesos','$fecha_ingreso', '$fecha_ingreso' ,'$serierel4','$tcomprel4' ,'S' ,'$moneda', '$numfac4' )";
	$result = mysqli_query($amercado, $strSQL);				         
		 
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='6'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);

}

if ((isset($_POST["MM_insert4"])) && ($_POST["MM_insert4"] == "retenciones")) {
	//  RETENCIONES

	mysqli_select_db($amercado, $database_amercado);
	$query_comprobante = "SELECT * FROM series  WHERE series.codnum =34";
	$comprobante5 = mysqli_query($amercado, $query_comprobante) or die("ERROR LEYENDO SERIES 241");
	$row_comprobante = mysqli_fetch_assoc($comprobante5);
	$totalRows_comprobante = mysqli_num_rows($comprobante5);
	$codpais = 1;
 	
	
	// variables del formulario tipo de pago
	$moneda = 1 ; // tipo de moneda
	$fecha_ingreso = $_POST['fecharet'];
	$fec_ing  = substr($fecha_ingreso,6,4)."-".substr($fecha_ingreso,3,2)."-".substr($fecha_ingreso,0,2); // TRansformacion del cheque
	//echo "FEC_ING: ".$fec_ing."  ";
	$estado = "P" ;
	$tcomp =  $_POST['t_ret'];
	$serie = $_POST['serie5'];
	$serierel5 = $_POST['serierel5'];
	$tcomprel5 = $_POST['tcomprel5'];
	$numfac5 = $_POST['ncomprel5'];
	$importe_pesos2 = $_POST['importeret'];
	$comp_ing_brutos = $_POST['numret'];

	$num_comp4 = ($row_comprobante['nroact'])+1 ; 
	$vencimiento  = date('Y-m-d');; // TRansformacion del cheque
	//$fecha_ingreso = date('Y-m-d'); 
	$estado = "P" ;
	$num_comp4 = ($row_comprobante['nroact'])+1 ;
	
	$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp,  codpais , importe , codchq  ,fechapago , fechaingr ,  serierel , tcomprel , estado , moneda , ncomprel )
	VALUES ($tcomp,$serie,'$num_comp4','1','$importe_pesos2','$comp_ing_brutos','$fec_ing', '$fec_ing' ,'3','2' ,'S' ,'$moneda', '$numfac5')";
	$result = mysqli_query($amercado, $strSQL);				         
		 
	$actualiza = "UPDATE `series` SET `nroact` = '$num_comp4' WHERE `series`.`codnum` ='34'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);

}

if ((isset($_POST["MM_insert41"])) && ($_POST["MM_insert41"] == "remates")) {
	//  REMATES ASOCIADOS

    
    $nremate = $_POST['n_rem'];
	mysqli_select_db($amercado, $database_amercado);
	$query_rem_asoc = "SELECT * FROM remates  WHERE tcomp = 4 and ncomp = $nremate";
	$rem_asoc = mysqli_query($amercado, $query_rem_asoc) or die("ERROR LEYENDO REM ASOC 284");
	$row_rem_asoc = mysqli_fetch_assoc($rem_asoc);
	$fecha_rem = $row_rem_asoc["fecreal"];
    
	$codpais = 1;
 	
	
	// variables del formulario tipo de pago
	$moneda = 1 ; // tipo de moneda
	
	$estado = "P" ;
	$tcomp =  4; //$_POST['t_rem'];
    
	$serie = 4;
    
	$serierel5 = $_POST['serierel51'];
	$tcomprel5 = $_POST['tcomprel51'];
	$numfac5 = $_POST['ncomprel51'];
	$importe_pesos21 = $_POST['importerem'];
	$comp_rem = $nremate;

	$num_comp41 = $nremate; 
	$vencimiento  = date('Y-m-d');; // TRansformacion del cheque
	//$fecha_ingreso = date('Y-m-d'); 
	$estado = "P" ;
	
	
	$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp,  codpais , importe , codchq  ,fechapago , fechaingr ,  serierel , tcomprel , estado , moneda , ncomprel )
	VALUES ($tcomp,$serie,'$num_comp41','1','$importe_pesos21','$comp_rem','$fecha_rem', '$fecha_rem' ,'3','2' ,'S' ,'$moneda', '$numfac5')";
	$result = mysqli_query($amercado, $strSQL);				         
		 
	
}

if ((isset($_POST["MM_insert8"])) && ($_POST["MM_insert8"] == "facturas")) {

	$codpais = 1;

	
	// variables del formulario tipo de pago
	$moneda = 1 ; // tipo de moneda
	$banco        = ""; //$_POST['banco1'];               // codigo Banco
	$numcheque    = $_POST['nrodoc3'];       // Numero de Cheque
	$vencimiento = $_POST['fecha_factura']; // Fecha Vencimiento formato YYYY-MM-DD ;
	$tot_facturas      = $_POST['importe_factura'];           //  Importe de la factura
    echo "IMPORTE = ".$tot_facturas."   ";
	
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

	$strSQL = "INSERT INTO cartvalores (tcomp, serie, ncomp, codban, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel , seriesal ,tcompsal , ncompsal ) VALUES ('$tcomp','$serie','$num_comp','$banco','$numcheque' ,'$codpais','$tot_facturas','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'S' ,'$moneda','$codrem' , '$numfac' ,'$serierel','$tcomprel' ,'$numfac' )";
	
	$result = mysqli_query($amercado, $strSQL);				         

}

?>
<?php require_once('Connections/amercado.php'); 

mysqli_select_db($amercado, $database_amercado);
$query_bancos = "SELECT * FROM bancos";
$bancos = mysqli_query($amercado, $query_bancos) or die("ERROR LEYENDO BANCOS (524)");
$row_bancos = mysqli_fetch_assoc($bancos);
$totalRows_bancos = mysqli_num_rows($bancos);

mysqli_select_db($amercado, $database_amercado);
$query_tipcomp_ret = "SELECT * FROM tipcomp WHERE codafip IS NOT null AND codafip != 0 AND activo = 1";
$tipcomp_ret = mysqli_query($amercado, $query_tipcomp_ret) or die("ERROR LEYENDO RETENCIONES (530)");
$row_tipcomp_ret = mysqli_fetch_assoc($tipcomp_ret);
$totalRows_tipcomp_ret = mysqli_num_rows($tipcomp_ret);

mysqli_select_db($amercado, $database_amercado);
$query_cabfac = "SELECT * FROM cabfac WHERE cliente = $cliente AND tcomp IN (115,116,117,125,126,127,133,134) AND estado ='P'";
$cabfac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC (536)");
$row_cabfac = mysqli_fetch_assoc($cabfac);
$totalRows_cabfac = mysqli_num_rows($cabfac);




mysqli_select_db($amercado, $database_amercado);
$query_rem = "SELECT * FROM remates WHERE codcli = $cliente ORDER BY ncomp DESC";
$rem = mysqli_query($amercado, $query_rem) or die("ERROR LEYENDO  REMATES");
$row_ncomp_rem = mysqli_fetch_assoc($rem);
$totalRows_rem = mysqli_num_rows($rem);

$query_Cheques = "SELECT * FROM cartvalores";
$Cheques = mysqli_query($amercado, $query_Cheques) or die("ERROR LEYENDO CARTVALORES 550");
$row_Cheques = mysqli_fetch_assoc($Cheques);
$totalRows_Cheques = mysqli_num_rows($Cheques);

$query_sucursales = "SELECT * FROM sucbancos";
$sucursales = mysqli_query($amercado, $query_sucursales) or die("ERROR LEYENDO SUCBANCOS 555");
$row_sucursales = mysqli_fetch_assoc($sucursales);
$totalRows_sucursales = mysqli_num_rows($sucursales);

$tcomprel = $_GET['ftcomp'];
$serierel = $_GET['fserie'];
$ncomprel = $_GET['fncomp'];
$tot_gen = $_GET['total_general'];
$remate  = $_GET['remate'];
$codrem  = $_GET['remate'];
$codrem11  = $_GET['remate'];

$sql_deposito = "SELECT   bancos.nombre , codchq , importe , fechapago " 
        . " FROM cartvalores , bancos  "
        . " WHERE tcomp = 9 "
        . " AND serie = 7 "
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . " AND bancos.codnum = cartvalores.codban";
		
$sql_deposito_terceros = "SELECT   bancos.nombre , codchq , importe , fechapago " 
        . " FROM cartvalores , bancos  "
        . " WHERE tcomp = 95 "
        . " AND serie = 39 "
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . " AND bancos.codnum = cartvalores.codban";

$sql_rem = "SELECT   codchq , importe  " 
        . " FROM cartvalores   "
        . " WHERE tcomp = 4" //$tremate "
        //. " AND ncomp = $nremate "
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel";
				
		$sql = "SELECT bancos.nombre , codchq , importe , fechapago "
        . " FROM cartvalores , bancos "
        . " WHERE tcomp =8"
        . " AND serie =6"
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . " AND bancos.codnum = cartvalores.codban"
        . ' ';
		
        $sql11 = "SELECT bancos.nombre , codchq , importe , fechapago "
        . " FROM cartvalores , bancos "
        . " WHERE tcomp =100"
        . " AND serie =44"
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . " AND bancos.codnum = cartvalores.codban"
        . ' ';

		$sql_dolares = "SELECT  importe "
        . " FROM cartvalores "
        . " WHERE tcomp =13"
        . " AND serie =9"
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . ' ';
		
		$sql_pesos = "SELECT importe "
        . " FROM cartvalores "
        . " WHERE tcomp =12"
        . " AND serie =8"
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
		. ' ';
		
		$sql_iva = "SELECT codchq  , fechapago , importe"
       . " FROM cartvalores "
        . " WHERE tcomp =40"
        . " AND serie =22"
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . ' ';
	
		$sql_ing_brutos = "SELECT codchq  , fechapago , importe, tcomp"
        . " FROM cartvalores "
        . " WHERE ((tcomp BETWEEN 66 AND 85) OR tcomp = 90)"
        . " AND serie = 34"
        . " AND tcomprel = 2"
        . " AND serierel = 3"
        . " AND ncomprel = $ncomprel"
        . ' ';
		
		$sql_ganancias = "SELECT codchq  , fechapago , importe"
        . " FROM cartvalores "
        . " WHERE tcomp =42"
        . " AND serie =24"
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . ' ';
		
		$sql_suss = "SELECT codchq  , fechapago , importe"
        . " FROM cartvalores "
        . " WHERE tcomp =43"
        . " AND serie =25"
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . ' '; 

$tot_facturas = 0;
$sql_factura = "SELECT importe, codchq "
        . " FROM cartvalores "
        . " WHERE tcomp in (51,52,53,54)"
        . " AND tcomprel = $tcomprel"
        . " AND serierel = $serierel"
        . " AND ncomprel = $ncomprel"
        . ' ';
$total_facturas = mysqli_query($amercado, $sql_factura) or die("ERROR LEYENDO FACTURAS 675");
	while ($facturas = mysqli_fetch_array($total_facturas)){
   			$tot_facturas =  $tot_facturas + $facturas['importe'];
	}

$precio2 =0 ;
$total_dolares = mysqli_query($amercado, $sql_dolares) or die("ERROR LEYENDO DOLARES 681");	
$total_dol = mysqli_query($amercado, $sql_dolares) or die("ERROR LEYENDO DOLARES 682");	
$totalRows_dolares = mysqli_num_rows($total_dolares);

// Retencion de IVA
$total_iva = mysqli_query($amercado, $sql_iva) or die("ERROR LEYENDO RET IVA 686");
$totalRows_total_iva = mysqli_num_rows($total_iva);
//$fecha_iva = substr($fecha_iva,8,2)."-".substr($fecha_iva,5,2)."-".substr($fecha_iva,0,4);

// Retenciones
$total_ing_brutos = mysqli_query($amercado, $sql_ing_brutos) or die("ERROR LEYENDO RET IB 691");	
$totalRows_total_ing_brutos = mysqli_num_rows($total_ing_brutos);
//$fecha_ing_brutos = substr($fecha_ing_brutos,8,2)."-".substr($fecha_ing_brutos,5,2)."-".substr($fecha_ing_brutos,0,4);

// Retencion de GANANCIAS
$total_ganancias = mysqli_query($amercado, $sql_ganancias) or die("ERROR LEYENDO RET GAN 696");	
$totalRows_total_ganancias = mysqli_num_rows($total_ganancias);

// Retencion de SUSS
$total_suss = mysqli_query($amercado, $sql_suss) or die("ERROR LEYENDO RET SUSS 700");	
$totalRows_total_suss = mysqli_num_rows($total_suss);
//$fecha_suss = substr($fecha_sus,8,2)."-".substr($fecha_sus,5,2)."-".substr($fecha_sus,0,4);

$tot_pesos = mysqli_query($amercado, $sql_pesos) or die("ERROR LEYENDO PESOS 704");	

$total_deposito = mysqli_query($amercado, $sql_deposito) or die("ERROR LEYENDO DEPOSITOS 706");	
$tot_dep = mysqli_query($amercado, $sql_deposito) or die("ERROR LEYENDO DEPOSITOS 707");
$totalRows_deposito = mysqli_num_rows($total_deposito);

$total_deposito_terceros = mysqli_query($amercado, $sql_deposito_terceros) or die("ERROR LEYENDO DEP TERC 710");	
$tot_dep_terceros = mysqli_query($amercado, $sql_deposito_terceros) or die("ERROR LEYENDO DEP TERC 711");
$totalRows_deposito_terceros = mysqli_num_rows($total_deposito_terceros);

$monto_total_cheques = 0 ;
$monto_total_cheques_terceros = 0;

$precio1 =0 ;
$total_cheques = mysqli_query($amercado, $sql) or die("ERROR LEYENDO CHEQUES 718");	
$total_cheq = mysqli_query($amercado, $sql) or die("ERROR LEYENDO CHEQUES 719");	
$totalRows_cheques = mysqli_num_rows($total_cheques);

$monto_total_cheques = 0 ;
$monto_total_cheques_a_terceros = 0;

$precio11 =0 ;
$total_cheques11 = mysqli_query($amercado, $sql11) or die("ERROR LEYENDO CHEQUES TERC 726");	
$total_cheq11 = mysqli_query($amercado, $sql11) or die("ERROR LEYENDO CHEQUES TERC 727");	
$totalRows_cheques11 = mysqli_num_rows($total_cheques11);

$total_rem = mysqli_query($amercado, $sql_rem) or die("ERROR LEYENDO REMATES 730");	
$total_r = mysqli_query($amercado, $sql_rem) or die("ERROR LEYENDO REMATES 731");	

$depositos = 0 ;
while ($registro = mysqli_fetch_array($tot_dep)){
	$depositos = $depositos + $registro['2'];
}   
$depositos_terceros = 0;
while ($registro_terceros = mysqli_fetch_array($tot_dep_terceros)){
	$depositos_terceros = $depositos_terceros + $registro_terceros['2'];
}   

$cheques = 0 ;
while ($registro1 = mysqli_fetch_array($total_cheq)){
	$cheques = $cheques + $registro1['2'];
}  

$total_remates = 0 ;
while ($registro411 = mysqli_fetch_array($total_rem)){
	$total_remates = $total_remates + $registro411['1'];
}  


$cheques11 = 0 ;
while ($registro11 = mysqli_fetch_array($total_cheq11)){
	$cheques11 = $cheques11 + $registro11['2'];
} 

$dola = 0 ;
while ($registro2 = mysqli_fetch_array($total_dol)){
	$dola = $dola + $registro2['0'];
}
$efectivo = 0;
while ($registro3 = mysqli_fetch_array($tot_pesos)){
	$efectivo = $efectivo + $registro3['0'];
}
$monto_sus = 0;
while ($registro4 = mysqli_fetch_array($total_suss)){
	$monto_sus = $monto_sus + $registro4['2'];
}	
$monto_ganancias = 0;
while ($registro5 = mysqli_fetch_array($total_ganancias)){
	$monto_ganancias = $monto_ganancias + $registro5['2'];
}	
$monto_ing_brutos = 0;
while ($registro6 = mysqli_fetch_array($total_ing_brutos)){
	$monto_ing_brutos = $monto_ing_brutos + $registro6['2'];
}	
$monto_iva = 0;
while ($registro7 = mysqli_fetch_array($total_iva)){
	$monto_iva = $monto_iva + $registro7['2'];
}				
$total_general = $cheques+$cheques11+$dola+$efectivo+$depositos+$depositos_terceros+$monto_sus+$monto_ganancias+$monto_ing_brutos+$monto_iva+$total_remates+$tot_facturas;
$tot_faltante =  $tot_gen - $total_general;
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

</script>
<script language="javascript">
function pesos(form)
{

	var pesos_ing = ing_pesos.cant_efectivo.value ;
	ing_pesos.importe_pesos.value = pesos_ing ;

}

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
    <td colspan="2" background="images/fondo_titulos.jpg"><img src="images/ing_medios_pago.gif" width="315" height="30" /> </td>
  </tr>
  <tr>
    <td width="320">&nbsp;</td>
    <td width="379">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> <?php $tot_reten = 0; ?>
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
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingres_cheques_terceros.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"></div></td>
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
               <td >&nbsp;</td>
                <td width="150"><input name="numcheque" type="text" class="seleccion" id="numcheque" /></td>
                <td width="97"><div align="right">
                    <input name="venc" type="text" class="seleccion" id="venc" size="11" />
                </div></td>
                <td width="24"><a href="javascript:showCal('Calendar3')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
                <td><input name="importe" type="text" class="seleccion" id="importe" size="10" />                </td>
              </tr>

            <tr>
			    <input name="codrem"  type="hidden"  value="<?php echo  $remate ?>"/>
	            
                <input name="serie"     type="hidden"  value="6"/>
	            <input name="numfac"     type="hidden"  value="<?php echo $ncomprel ?>"/>	 
	            <input name="serierel"  type="hidden"  value="<?php echo $serierel ?>" />
	            <input name="tcomprel"  type="hidden"  value="<?php echo $tcomprel ?>" />
              <td width="150">&nbsp;</td>
              <td width="150"><input name="estado"    type="hidden"  value="P"/></td>
              <td width="150"><input name="tcomp"     type="hidden"  value="8"/></td>
			   <input type="hidden" name="MM_insert1" value="cheque">
              <td colspan="3"><div align="center">
			      <input name="Submit" type="submit" class="seleccion" value="Ingresar cheques" />
                         </div></td>
            </tr>
          </form>
        </table></td>
      </tr>
      
        
        <tr>
        <td width="1">&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingres_cheques_a_terceros.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"></div></td>
            <td width="150" bgcolor="#00CCFF"><img src="images/num_cheque.gif" width="124" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/vencimientoi.gif" width="130" height="16" /></td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="cheques11" name="cheque11_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
               <td width="140"><select name="banco11" class="seleccion" id="banco11">
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
               <td >&nbsp;</td>
                <td width="150"><input name="numcheque11" type="text" class="seleccion" id="numcheque11" /></td>
                <td width="97"><div align="right">
                    <input name="venc11" type="text" class="seleccion" id="venc11" size="11" />
                </div></td>
                <td width="24"><a href="javascript:showCal('Calendar31')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
                <td><input name="importe11" type="text" class="seleccion" id="importe11" size="10" />                </td>
              </tr>

            <tr>
			    <input name="codrem11"  type="hidden"  value="<?php echo  $codrem11 ?>"/>
	            
                <input name="serie11"     type="hidden"  value="44"/>
	            <input name="numfac11"     type="hidden"  value="<?php echo $ncomprel ?>"/>	 
	            <input name="serierel11"  type="hidden"  value="<?php echo $serierel ?>" />
	            <input name="tcomprel11"  type="hidden"  value="<?php echo $tcomprel ?>" />
              <td width="150">&nbsp;</td>
              <td width="150"><input name="estado11"    type="hidden"  value="P"/></td>
              <td width="150"><input name="tcomp11"     type="hidden"  value="100"/></td>
			   <input type="hidden" name="MM_insert11" value="cheque11">
              <td colspan="3"><div align="center">
			      <input name="Submit" type="submit" class="seleccion" value="Ingresar cheques a 3ros" />
                         </div></td>
            </tr>
          </form>
        </table></td>
      </tr>
        
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingreso_depositos.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"></div></td>
            <td width="150" bgcolor="#00CCFF"><img src="images/deposito.gif" width="130" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/fecha_deposito.gif" width="130" height="16" /></td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="deposito_form" name="deposito_form" method="post" action="<?php echo $editFormAction; ?>">
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
              <td >&nbsp;</td>
              <td width="150"><input name="deposito" type="text" class="seleccion" /></td>
              <td width="97"><div align="right">
                  <input name="fecha_deposito" type="text" class="seleccion" id="fecha_deposito" size="11" />
              </div></td>
              <td width="24"><a href="javascript:showCal('Calendar5')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
              <td><input name="importe_deposito" type="text" class="seleccion" size="10" />              </td>
            </tr>
            <tr><input name="numfac2"    type="hidden"  value="<?php echo $ncomprel ?>"/>
            
              <td><input name="serie2"    type="hidden"  value="7"/></td>
              <input name="ncomp2"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel2"  type="hidden"  value="<?php echo $serierel ?>" />
              <input name="tcomprel2"  type="hidden"  value="<?php echo $tcomprel ?>" />
              <input name="estado2"    type="hidden"  value="P"/>
              <input name="tcomp2"     type="hidden"  value="9"/>
			  <input name="codrem"  type="text"  value="<?php echo  $remate  ?>"/>
			   <input type="hidden" name="MM_insert2" value="transferencia">
              <td colspan="3"><input name="Submit222" type="submit" class="seleccion" value="Ingresar dep&oacute;sitos" /></td>
            </tr>
          </form>
        </table></td>
      </tr>
		
		  <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingreso_depositos_terceros.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"></div></td>
            <td width="150" bgcolor="#00CCFF"><img src="images/deposito.gif" width="130" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/fecha_deposito.gif" width="130" height="16" /></td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="deposito_terceros_form" name="deposito_terceros_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
              <td width="140"><select name="banco1_terceros" class="seleccion" id="select">
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
              <td >&nbsp;</td>
              <td width="150"><input name="deposito_terceros" type="text" class="seleccion" /></td>
              <td width="97"><div align="right">
                  <input name="fecha_deposito_terceros" type="text" class="seleccion" id="fecha_deposito_terceros" size="11" />
              </div></td>
              <td width="24"><a href="javascript:showCal('Calendar51')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
              <td><input name="importe_deposito_terceros" type="text" class="seleccion" size="10" />              </td>
            </tr>
            <tr><input name="numfac21"    type="hidden"  value="<?php echo $ncomprel ?>"/>
            
              <td><input name="serie21"    type="hidden"  value="7"/></td>
              
              <input name="serierel21"  type="hidden"  value="<?php echo $serierel ?>" />
              <input name="tcomprel21"  type="hidden"  value="<?php echo $tcomprel ?>" />
              <input name="estado21"    type="hidden"  value="P"/>
              <input name="tcomp21"     type="hidden"  value="9"/>
			  <input name="codrem"  type="text"  value="<?php echo  $remate  ?>"/>
			   <input type="hidden" name="MM_insert21" value="transferencia_terceros">
              <td colspan="3"><input name="Submit2221" type="submit" class="seleccion" value="Ingresar dep&oacute;sitos 3ros" />              </td>
            </tr>
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
			  <input name="ncomprel4"    type="hidden"  value="<?php echo $ncomprel ?>"/>
			  <input name="fechaing4"  type="hidden"  value="<?php echo date("Y-m-d"); ?>"/>
              <input name="serie4"     type="hidden"  value="12"/>
              
              <input name="serierel4"  type="hidden"  value="<?php echo $serierel ?>" />
              <input name="tcomprel4"  type="hidden"  value="<?php echo $tcomprel ?>" />
              <input name="estado3"    type="hidden"  value="P"/>
              <input name="tcomp4"     type="hidden"  value="8"/>
              <input name="codrem"  type="text"  value="<?php echo  $remate  ?>"/>
			  	   <input type="hidden" name="MM_insert3" value="efectivo">
              <td colspan="3"><div align="center">
                <input name="enviar" type="submit" class="seleccion" value="Ingresar pesos" />
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
			<td width="120" bgcolor="#00CCFF" class="Estilo1"><div align="center"></div></td>
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
               <td >&nbsp;</td>
                <td width="150"><input name="numret" type="text" class="seleccion" id="numret" /></td>
                <td width="97"><div align="right">
                    <input name="fecharet" type="text" class="seleccion" id="fecharet" size="11" />
                </div></td>
                <td width="24"><a href="javascript:showCal('Calendar9')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
                <td><input name="importeret" type="text" class="seleccion" id="importeret" size="10" />                </td>
              </tr>
	   
			   
            <tr>
			    <input name="codrem5"  type="hidden"  value="<?php echo  $remate ?>"/>
	            
                <input name="serie5"     type="hidden"  value="34"/>
	            <input name="ncomprel5"     type="hidden"  value="<?php echo $ncomprel ?>"/>	 
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
      </tr> <?php /* } */?>
        
        <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td height="24" colspan="8" bgcolor="#0094FF" ><div align="center" class="Estilo1">Remates asociados</div></td>
          </tr>
          <tr>
            			
            <td width="120" bgcolor="#00CCFF" class="Estilo1"><div align="center">Id. Remate </div></td>
            <td colspan="2" bgcolor="#00CCFF"><div align="center" class="Estilo1">Importe</div></td>
            <td width="114" bgcolor="#00CCFF">&nbsp;</td>
          </tr>
		  <?php if ($totalRows_rem > 0)  { ?>
           <form id="rem_form" name="rem_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
               
               <td width="140"><select name="n_rem" class="seleccion" id="n_rem">
                 <option value="" class="phpmaker">Seleccione Id de Remate</option>
                 <?php
                   do {  
                        ?>
                 <option  class="phpmaker" value="<?php echo $row_ncomp_rem['ncomp']?>"><?php echo $row_ncomp_rem['ncomp']?></option>
                 <?php
                        } while ($row_ncomp_rem = mysqli_fetch_assoc($rem));
                             $rows = mysqli_num_rows($rem);
                              if($rows > 0) {
                                 mysqli_data_seek($rem, 0);
	                            $row_ncomp_rem = mysqli_fetch_assoc($rem);
                               }
                           ?>
               </select></td>
                
                
                <td><input name="importerem" type="text" class="seleccion" id="importerem" size="10" /> </td>
              </tr>
	   
			   
            <tr>
			    <input name="codrem51"  type="hidden"  value="<?php echo  $remate ?>"/>
	            
                <input name="serie51"     type="hidden"  value="34"/>
	            <input name="ncomprel51"     type="hidden"  value="<?php echo $ncomprel ?>"/>	 
	            <input name="serierel51"  type="hidden"  value="<?php echo $serierel ?>" />
	            <input name="tcomprel51"  type="hidden"  value="<?php echo $tcomprel ?>" />
              <td width="150">&nbsp;</td>
              <td width="150"><input name="estado51"    type="hidden"  value="P"/></td>
              <td width="150"><input name="tcomp51"     type="hidden"  value="<?php echo $tcomprel ?>"/></td>
			   <input type="hidden" name="MM_insert41" value="remates">
              <td colspan="3"><div align="center">
			      <input name="Submit41" type="submit" class="seleccion" value="Ingresar Remates"/>
                         </div></td>
            </tr>
          </form>
			<?php
			 }  
                        ?>
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
			
			<?php if ($totalRows_cabfac > 0) { ?>
          <form id="facturas_form" name="facturas_form" method="post" action="<?php echo $editFormAction; ?>">
            <tr>
              <td width="140"><select name="cabfac1" class="seleccion" id="cabfac1" >
                  <option value="" class="phpmaker">Seleccione Factura</option>
                  <?php
                   do {  
                        ?>
                  <option  class="phpmaker" value="<?php echo $row_cabfac['nrodoc']?>"><?php echo $row_cabfac['totbruto']." - "?><?php echo $row_cabfac['nrodoc']?></option>
                  <?php
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
			<input type="hidden" name="MM_insert8" value="facturas">
          </form>
			<?php
			 }  
               ?>
			
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
            <td colspan="6" bgcolor="#993333" ><div align="center"><img src="images/depositos_banc.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"></div></td>
            <td width="150" bgcolor="#999966"><img src="images/numn_dep_marro.gif" width="150" height="16" /></td>
            <td colspan="2" bgcolor="#999966"><img src="images/fecha_dep_marro.gif" width="150" height="16" /></td>
            <td width="123" bgcolor="#999966"><img src="images/importes_marron.gif" width="70" height="16" /></td>
          </tr>
          <form id="cheques" name="cheque_list" method="post" >
		<?php $reg_ley = "";  while ($registro = mysqli_fetch_array($total_deposito)){
   					$monto_total_cheques = $monto_total_cheques + $registro['2'];
   					$reg_ley = $reg_ley." - ".$registro[0].", Nº".$registro[1].", $".$registro[2]."/"; 
           ?>
		  
            <tr>
              <td width="150" bgcolor="#993333"><input name="list_banco_cheque" type="text" class="seleccion" id="list_banco_cheque" value="<?php echo $registro[0] ?>"/></td>
              <td bgcolor="#993333" ></td>
              <td width="150" bgcolor="#993333"><input name="list_num_cheque" type="text" class="seleccion" id="numcheque2" value="<?php echo $registro[1] ?>"/></td>
              <td width="97" colspan="2" bgcolor="#993333"><div align="right">
                  <input name="list_fecha_venc" type="text" class="seleccion" id="venc2" size="11" value="<?php echo $registro[3] ?>"/>
              </div></td>
              <td bgcolor="#993333"><input name="list_importe_cheque" type="text" class="seleccion" id="importe2" size="15" value="<?php echo $registro[2] ?>"/>              </td>
            </tr>
			<?php } ?>
            <tr>
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td colspan="3" bgcolor="#993333"><div align="right"><img src="images/total_depositos_mar.gif" width="240" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_cheques" type="text" class="seleccion" id="importe_cheque" size="15" value="<?php echo $monto_total_cheques ?>" /></td>
            </tr>
          </form>
        </table></td>
      </tr>
		
		<tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="6" bgcolor="#993333" ><div align="center"><img src="images/depositos_banc_terceros.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"></div></td>
            <td width="150" bgcolor="#999966"><img src="images/numn_dep_marro.gif" width="150" height="16" /></td>
            <td colspan="2" bgcolor="#999966"><img src="images/fecha_dep_marro.gif" width="150" height="16" /></td>
            <td width="123" bgcolor="#999966"><img src="images/importes_marron.gif" width="70" height="16" /></td>
          </tr>
          <form id="cheques" name="cheque_list" method="post" >
		<?php $reg_ley_terceros = "";  while ($registro_terceros = mysqli_fetch_array($total_deposito_terceros)){
   					$monto_total_cheques_terceros = $monto_total_cheques_terceros + $registro_terceros['2'];
   					$reg_ley_terceros = $reg_ley_terceros." - ".$registro_terceros[0].", Nº".$registro_terceros[1].", $".$registro_terceros[2]."/"; 
           ?>
		  
            <tr>
              <td width="150" bgcolor="#993333"><input name="list_banco_cheque_terceros" type="text" class="seleccion" id="list_banco_cheque_terceros" value="<?php echo $registro_terceros[0] ?>"/></td>
              <td bgcolor="#993333" ></td>
              <td width="150" bgcolor="#993333"><input name="list_num_cheque_terceros" type="text" class="seleccion" id="numcheque21" value="<?php echo $registro_terceros[1] ?>"/></td>
              <td width="97" colspan="2" bgcolor="#993333"><div align="right">
                  <input name="list_fecha_venc_terceros" type="text" class="seleccion" id="venc21" size="11" value="<?php echo $registro_terceros[3] ?>"/>
              </div></td>
              <td bgcolor="#993333"><input name="list_importe_cheque_terceros" type="text" class="seleccion" id="importe21" size="15" value="<?php echo $registro_terceros[2] ?>"/>              </td>
            </tr>
			<?php } ?>
            <tr>
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td colspan="3" bgcolor="#993333"><div align="right"><img src="images/total_depositos_mar_terceros.gif" width="240" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_cheques_terceros" type="text" class="seleccion" id="importe_cheque_terceros" size="15" value="<?php echo $monto_total_cheques_terceros ?>" /></td>
            </tr>
          </form>
        </table></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="5" bgcolor="#993333" ><div align="center"><img src="images/cheques_terc_marron.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"></div></td>
            <td width="150" bgcolor="#999966"><img src="images/num_chq_marron.gif" width="135" height="16" /></td>
            <td bgcolor="#999966"><img src="images/fecha_vencimiento.gif" width="135" height="16" /></td>
            <td width="123" bgcolor="#999966"><div align="center"><img src="images/importes_marron.gif" width="70" height="16" /></div></td>
          </tr>
	
          <form id="form1" name="deposito_list" method="post" action="">
	<?php $reg_leyenda = ""; while ($registro1 = mysqli_fetch_array($total_cheques)){
   $precio1= $precio1 + $registro1['2'];
   $reg_leyenda = $reg_leyenda."-".$registro1[0].",Nº".$registro1[1]." $".$registro1[2]."/"; 

?>
<tr>
<td width="150" bgcolor="#993333"><input name="textfield52" type="text" class="seleccion" value="<?php echo $registro1[0] ?>"/></td>
<td width="150" bgcolor="#993333"></td>
<td width="150" bgcolor="#993333"><input name="textfield2222" type="text" class="seleccion" value="<?php echo $registro1[1] ?>"></td>
<td bgcolor="#993333"><input name="fecha_venc2" type="text" class="seleccion" id="fecha_venc" size="13" value="<?php echo $registro1[3] ?>"/></td>
<td bgcolor="#993333"><input name="textfield422" type="text" class="seleccion" size="13" value="<?php echo $registro1[2] ?>" />              </td>      
        </tr>
		<?php } ?>
            <tr>
              <td bgcolor="#993333">&nbsp;</td>
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
            <td colspan="5" bgcolor="#993333" ><div align="center"><img src="images/cheques_a_terc_marron.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"></div></td>
            <td width="150" bgcolor="#999966"><img src="images/num_chq_marron.gif" width="135" height="16" /></td>
            <td bgcolor="#999966"><img src="images/fecha_vencimiento.gif" width="135" height="16" /></td>
            <td width="123" bgcolor="#999966"><div align="center"><img src="images/importes_marron.gif" width="70" height="16" /></div></td>
          </tr>
	
          <form id="form11" name="cheques11_list" method="post" action="">
	<?php $reg_leyenda11 = ""; while ($registro11 = mysqli_fetch_array($total_cheques11)){
   $precio11 = $precio11 + $registro11['2'];
   $reg_leyenda11 = $reg_leyenda11."-".$registro11[0].",Nº".$registro11[1]." $".$registro11[2]."/"; 

?>
<tr>
<td width="150" bgcolor="#993333"><input name="textfield52" type="text" class="seleccion" value="<?php echo $registro11[0] ?>"/></td>
<td width="150" bgcolor="#993333"></td>
<td width="150" bgcolor="#993333"><input name="textfield2222" type="text" class="seleccion" value="<?php echo $registro11[1] ?>"></td>
<td bgcolor="#993333"><input name="fecha_venc2" type="text" class="seleccion" id="fecha_venc" size="13" value="<?php echo $registro11[3] ?>"/></td>
<td bgcolor="#993333"><input name="textfield422" type="text" class="seleccion" size="13" value="<?php echo $registro11[2] ?>" />              </td>      
        </tr>
		<?php } ?>
            <tr>
              <td bgcolor="#993333">&nbsp;</td>
              <td bgcolor="#993333">&nbsp;</td>
              <td colspan="2" bgcolor="#993333"><div align="right"><img src="images/total_cheques_marr.gif" width="136" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_deposito" type="text" class="seleccion" size="15"  value="<?php echo $precio11 ?>" onChange="deposito(this.form)"/></td>
            </tr></form>
          
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
            <td colspan="2" bgcolor="#999966"><div align="right"><img src="images/importes_marron.gif" width="70" height="16" />
			</tr>
           
		  <?php while ($registro2 = mysqli_fetch_array($total_dolares)){
   $precio2= $precio2 + $registro2['0'];
  
    } 
	$tot_efectivo  =($efectivo + $precio2) ;
?>
            
            <tr>
              <td bgcolor="#993333"><input name="descripcion_pesos" type="text" class="seleccion" value="Total de Pesos" size="30"/></td><td width="101" colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos" type="text" class="seleccion"  size="13" value="<?php echo $efectivo ?>" /> </td>
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
          <?php while ($registro2 = mysqli_fetch_array($total_dolares)){
                $precio2= $precio2 + $registro2['0'];
        } 
	       $tot_efectivo  =($efectivo + $precio2) ;
?>  <?php 	if ($totalRows_total_ing_brutos!=0) {  
              	$total_ing_brutos1 = mysqli_query($amercado, $sql_ing_brutos) or die("ERROR LEYENDO RET IB 1530");
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
				$total_ganancias1 = mysqli_query($amercado, $sql_ganancias) or die("ERROR LEYENDO RET GAN 1541");
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
		     $total_iva1 = mysqli_query($amercado, $sql_iva) or die("ERROR LEYENDO RET IVA 1553");
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
			 $total_suss1 = mysqli_query($amercado, $sql_suss) or die("ERROR LEYENDO RET SUSS 1566");
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
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="6" bgcolor="#993333" ><div align="center"><img src="images/remates_ingresados.png" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"></div></td>
            <td width="150" bgcolor="#999966"><div align="center"></div></td>
            <td width="150" bgcolor="#999966"><img src="images/num_rem_marro.png" width="150" height="16" /></td>
            <td colspan="2" bgcolor="#999966"></td>
            <td width="123" bgcolor="#999966"><img src="images/importes_marron.gif" width="70" height="16" /></td>
          </tr>
          <form id="rem_list" name="rem_list" method="post" >
		<?php $reg_ley = ""; $monto_total_rem=0; while ($registro41 = mysqli_fetch_array($total_r)){ 
   					$monto_total_rem = $monto_total_rem + $registro41['1'];
   					$reg_ley = $reg_ley." - ".", Nº".$registro41[0].", $".$registro41[1]."/"; 
           ?>
		  
            <tr>
              <td width="150" bgcolor="#993333"></td>
              <td bgcolor="#993333" ></td>
              <td width="150" bgcolor="#993333"><input name="list_num_rem" type="text" class="seleccion" id="list_num_rem" value="<?php echo $registro41[0] ?>"/></td>
              <td width="97" colspan="2" bgcolor="#993333"><div align="right">
                  
              </div></td>
            <td bgcolor="#993333"><input name="list_importe_rem" type="text" class="seleccion" id="list_importe_rem" size="15" value="<?php echo $registro41[1] ?>"/>  </td>
            </tr>
			<?php } ?>
            <tr>
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td width="150" bgcolor="#993333">&nbsp;</td>
              <td colspan="3" bgcolor="#993333"><div align="right"><img src="images/total_remates.png" width="240" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_rem" type="text" class="seleccion" id="total_rem" size="15" value="<?php echo $monto_total_rem ?>" /></td>
            </tr>
          </form>
        </table></td>
      </tr>
  
        <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="3" bgcolor="#993333" ><div align="center"><img src="images/facturas_asociadas.png" width="400" height="24" /></div></td>
          </tr>   <tr> 
            <td width="639" bgcolor="#999966"><div align="left"><img src="images/factura_verde.png" width="200" height="16" /> </div>
              <div align="center"></div></td>
            <td colspan="2" bgcolor="#999966"><div align="right"><img src="images/importes_marron.gif" width="70" height="16" /></div></td> 
			</tr>
           
		     <tr>
              <td bgcolor="#993333">&nbsp;</td>
              <td width="101" colspan="2"   bgcolor="#993333"  align="right">&nbsp;</td>
              </tr>
            <tr>
              <td bgcolor="#993333"><input name="descripcion_facturas" type="text" class="seleccion" value="<?php echo $row_cabfac['nrodoc'] ?>" size="30"/></td><td width="101" colspan="2"  bgcolor="#993333"  align="right"><input name="importe_facturas" type="text" class="seleccion"  size="13" value="<?php echo $tot_facturas ?>" /> </td>
              </tr>
            <tr>
              <td bgcolor="#993333"><div align="right"><img src="images/total_facturas_marr.gif" width="136" height="26" /></div></td>
              <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_facturas1" type="text" class="seleccion"  size="13" value="<?php echo $tot_facturas ?>" /></td>
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
        <td colspan="2" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
          <tr>
            <td width="560" align="right"><img src="images/total_general_v.gif" width="136" height="26" /></td>
            <td align="right"><input name="tot_gen" type="text" class="seleccion" value="<?php echo $total_general ?>" /></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="292" bgcolor="#ECE9D8">&nbsp;</td>
        <td width="349" align="center" valign="middle"><div align="right"></div></td>
      </tr> 
	
      <tr>
        <td><?php $leyenda = "";
			  if ($tot_efectivo!=0 || $precio1!=0 || $monto_total_cheques!=0) {
	          	$leyenda = "Se abona con :"; 
			 }
			 if ($tot_efectivo!=0) {
	         	$leyenda = $leyenda." Efectivo : $".$tot_efectivo." -";
			 }
			 if ($monto_total_cheques!=0) { //  $precio1 = 0 ;
			   	$leyenda = $leyenda." Depósito : ".$reg_ley." -";
			  	$leyenda = $leyenda." Total de depósitos $".$monto_total_cheques."-";
			 }
			 //  $monto_total_cheques = 0 ;
			 if ($precio1!=0) {
	         	$leyenda = $leyenda."Cheques".$reg_leyenda;
			  	$leyenda = $leyenda."Total de cheques:$".$precio1;
                                $leyenda = $leyenda."Retira contra acreditacion de cheques.";
			 }
			 if ($monto_sus!=0) {
	         	$leyenda = $leyenda."Ret. SUSS $".$monto_sus." Comp N".$comp_sus;
			 
			 }
			 if ($monto_ganancias!=0) {
	         	$leyenda = $leyenda."Ret. Ganacias $".$monto_ganancias." Comp N".$comp_ganancias;
			  	
			 }
			 if ($monto_iva!=0) {
	         	$leyenda = $leyenda."Ret. IVA $".$monto_iva."Comp N".$comp_iva."\n";
			  	
			 }
			 if ($monto_ing_brutos!=0) {
	         	$leyenda = $leyenda." Ret. Ing. Brutos $".$monto_ing_brutos." Comp N".$comp_ing_brutos;
			  
			 }

	            
	  ?>	  </td>
        <td colspan="2" rowspan="2" bgcolor="#ECE9D8"><div align="center"><img src="images/leyenda.gif" width="230" height="26" />          </div>          <form id="leyenda" name="leyenda" method="POST" action="<?php echo $editFormAction; ?>">
            <div align="center">
              <textarea name="leyendafc" cols="120" rows="10" class="seleccion"></textarea>
              <input name="tcomp" type="hidden" class="seleccion" value="2" />
              <input name="serie" type="hidden" class="seleccion" value="3" />
              <input name="numfac"    type="hidden"  value="<?php echo $ncomprel ?>"/>
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
        <td colspan="2"><a href="muestra_recibo.php?tcomprel=<?php echo $tcomprel ?>&serierel=<?php echo $serierel ?>&ncomprel=<?php echo $ncomprel ?>"><img src="images/salir_but.gif" width="72" height="20"  border="0"/></a></td>
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
</body>
</html>