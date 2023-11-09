<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php require_once('Connections/amercado.php'); 

mysqli_select_db($amercado, $database_amercado);
$query_comprobante = "SELECT * FROM series  WHERE series.codnum =6";
$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
$row_comprobante = mysqli_fetch_assoc($comprobante);
$totalRows_comprobante = mysqli_num_rows($comprobante);
?>
</head>
<body>
<?php 
// Para modificar
$codpais = 1;
// variables del formulario tipo de pago
$moneda = 1 ; // tipo de moneda
$banco        = $_POST['banco'];               // codigo Banco

$query_sucursal = "SELECT * FROM sucbancos WHERE codbanco = '$banco'";
$suc = mysqli_query($amercado, $query_sucursal) or die(mysqli_error($amercado));
$row_sucursal = mysqli_fetch_assoc($suc);
$sucursal = $row_sucursal['codnum'];

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
$num_comp = ($row_comprobante['nroact'])+1 ; 

echo "NROCHEQUE = ".$numcheque."  IMPORTE = ".$importe." TCOMP = ".$tcomp." CODREM = ".$codrem." SERIE = ".$serie." SERIEREL = ".$serierel." TCOMPREL = ".$tcomprel." NUMFAC = ".$numfac."    -   " ;
$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel )
	VALUES ('$tcomp','$serie','$num_comp','$banco','$sucursal','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'P' ,'$moneda','$codrem' , '$numfac' )";
// 4. Ejecuto la consulta.	
$result = mysqli_query($amercado, $strSQL);				         
			 
$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='6'" ;				 
$resultado=mysqli_query($amercado,	$actualiza);				              

#comprobamos el resultado de la insercion
echo "<script language='javascript' >";
echo "window.location.href='medios_pago.php'";
echo "</script>";
?>






</body>
</html>
<?php
mysql_free_result($comprobante);
?>
