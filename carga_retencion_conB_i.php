<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php require_once('Connections/amercado.php'); 
include_once "src/userfn.php";
/*
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
*/
mysqli_select_db($amercado, $database_amercado);
$query_comprobante = "SELECT * FROM series  WHERE series.codnum =8";
$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
$row_comprobante = mysqli_fetch_assoc($comprobante);
$totalRows_comprobante = mysqli_num_rows($comprobante);
?>
</head>
<body>
<script language="javascript">
//alert("Hola");
</script>
<?php 

// Para modificar

$codpais = 1;

// variables del formulario tipo de pago

$moneda = 1 ; // tipo de moneda

if (isset($_POST['importe_ing_brutos']) && GetSQLValueString($_POST['importe_ing_brutos'], "double")!="NULL") {
	// Datos para ING BRUTOS         // 
	$numcheque     = $_POST['comp_ing_brutos'];         // Nuemro de Comprobante
	$fecha_comp = $_POST['fecha_depo_ing'];             // Fecha del comprobanta ;
	$importe      = $_POST['importe_ing_brutos']; 
	//echo "Adentro de Ingresos Brutos";
	} elseif   (isset($_POST['importe_ganancias']) && GetSQLValueString($_POST['importe_ganancias'], "double")!="NULL") {    //  Importe del comprobanta 
		// Datos para ING BRUTOS         // 
		$numcheque     = $_POST['comp_ganacias'];         // Nuemro de Comprobante
		$fecha_comp = $_POST['fecha_depo_gan'];             // Fecha del comprobanta ;
		$importe      = $_POST['importe_ganancias'];  
		//echo "Adentro de Ganancias";
		} elseif   (isset($_POST['importe_iva']) && GetSQLValueString($_POST['importe_iva'], "double")!="NULL") {      //  Importe del comprobanta 
			// Datos para ING BRUTOS         // 
			$numcheque     = $_POST['comp_iva'];         // Nuemro de Comprobante
			$fecha_comp = $_POST['fecha_depo_iva'];             // Fecha del comprobanta ;
			$importe      = $_POST['importe_iva'];       //  Importe del comprobanta 
			//echo "Adentro de IVA";
			} elseif   (isset($_POST['importe_sus']) && GetSQLValueString($_POST['importe_sus'], "double")!="NULL") { 
				// Datos para ING BRUTOS         // 
				$numcheque     = $_POST['comp_sus'];         // Nuemro de Comprobante
				$fecha_comp = $_POST['fecha_depo_suss'];             // Fecha del comprobanta ;
				$importe      = $_POST['importe_sus'];       //  Importe del comprobanta 
				//echo "Adentro de SUSS";
}

$vencimiento  = substr($fecha_comp,6,4)."-".substr($fecha_comp,3,2)."-".substr($fecha_comp,0,2); // TRansformacion del cheque
$fecha_ingreso = date('Y-m-d'); 
$estado = "P" ;
$tcomp =  $_POST['tcomp'];
$codrem = $_POST['codrem'];
$sucursal = 0;
$serie = $_POST['serie'];
$serierel = $_POST['serierel'];
$tcomprel = $_POST['tcomprel'];
$numfac = $_POST['numfac'];
 /* 
// Fecha de ingreso del cheque ;
echo $vencimiento."<br>";              // TRansformacion del cheque
//echo $banco."<br>";
echo $sucursal."<br>";
echo $numcheque."<br>";
echo $vence_cheque ."<br>";
echo $importe."<br>";
*/
$num_comp = ($row_comprobante['nroact'])+1 ; 
/*
echo "Cotizacion (moneda):".$cotizacion."<br>";
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
*/
$strSQL = "INSERT INTO cartvalores
	(tcomp, serie, ncomp, codban, codsuc, codchq , codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel )
	VALUES ('$tcomp','$serie','$num_comp','$banco','$sucursal','$numcheque' ,'$codpais','$importe','$vencimiento', '$fecha_ingreso' ,'$serierel','$tcomprel' ,'P' ,'$moneda','$codrem' , '$numfac' )";	
	// 4. Ejecuto la consulta.	
$result = mysqli_query($amercado, $strSQL);				         
			 
$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='$serie'" ;				 
	$resultado=mysqli_query($amercado,	$actualiza);				              
//$resultado =mysqli_query($amercado, $insetar_banco);
// INSERTAR COTIZACION
echo "<script language='javascript' >";
echo "window.location.href='medios_pago_conB_i.php'";
echo "</script>";

?>
</body>
</html>
<?php
mysql_free_result($comprobante);
?>
