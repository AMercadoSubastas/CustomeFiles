<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php require_once('Connections/amercado.php'); 

mysqli_select_db($amercado, $database_amercado);
$query_comprobante = "SELECT * FROM series  WHERE series.codnum =7";
$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
$row_comprobante = mysqli_fetch_assoc($comprobante);
$totalRows_comprobante = mysqli_num_rows($comprobante);
?>

</head>

<body>
<?php // Para modificar

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
$estado = "P" ;
$tcomp =  $_POST['tcomp2'];
$codrem = $_POST['codrem2'];
$serie = $_POST['serie2'];
$serierel = $_POST['serierel2'];
$tcomprel = $_POST['tcomprel2'];
$numfac = $_POST['numfac2'];
   // Fecha de ingreso del cheque ;
echo $vencimiento."<br>";              // TRansformacion del cheque



//echo $banco."<br>";
echo $sucursal."<br>";
echo $numcheque."<br>";
echo $vence_cheque ."<br>";
echo $importe."<br>";
$num_comp = ($row_comprobante['nroact'])+1 ; 

echo "Moneda (moneda):".$moneda."<br>";
echo "Banco (codban):".$banco."<br>";
echo "Sucursal (codsuc):".$sucursal."<br>";
echo "Numero Cheque :".$numcheque."<br>";
echo "Vence cheque :".$vencimiento."<br>";
//echo "Cliente :".$cliente."<br>";
echo "Tipo de comprobante (tcomp):".$tcomp."<br>";
echo "Serie de comprobante (serie):".$serie."<br>";
echo "codigo de pais (codpais):".$codpais."<br>";
echo "Fecho de ingreso (fechaingr):".$fecha_ingreso."<br>";
echo "Nuemro de comprobante (ncomp)".$num_comp."<br>";
echo "C�digo de Remate (codrem) :".$codrem."<br>";
echo "Serie (Factura) (serierel):".$serierel."<br>";
echo "Tipo de comprobante (factura) (tcomprel):".$tcomprel."<br>";
echo "Fecho de ingreso (fechaingr):".$fecha_ingreso."<br>";


$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel )
	VALUES ('$tcomp','$serie','$num_comp','$banco','$sucursal','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'P' ,'$moneda','$codrem' , '$numfac' )";
	
	
	// 4. Ejecuto la consulta.	
$result = mysqli_query($amercado, $strSQL);				         

			 
$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='7'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);				              
$resultado =mysqli_query($amercado, $insetar_banco);

#comprobamos el resultado de la insercion


echo "<script language='javascript' >";
echo "window.location.href='medios_pago_con.php'";
echo "</script>";

?>






</body>
</html>
<?php
mysql_free_result($comprobante);
?>
