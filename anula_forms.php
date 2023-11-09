<?php 
//include_once "ewcfg50.php" ;
// include_once "ewmysql50.php" ;
// include_once "phpfn50.php" ;
// include_once  "userfn50.php" ;
//include_once "usuariosinfo.php"; 

//include "header.php" ; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {
	color: #990000;
	font-size: x-large;
}
-->
</style>
<body>
<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado);
$tcomp = $_POST['tcomp'] ;
$serie = $_POST['serie'] ;
$ncomp = $_POST['x_ncomp'] ;
$motivo = $_POST['x_motivo'] ;
$fechaanul = $_POST['fecha'] ;
$fechaanul = substr($fechaanul,6,4)."-".substr($fechaanul,3,2)."-".substr($fechaanul,0,2); 

if ($tcomp=='3' OR $tcomp=='31') {

	//echo "es una Liquidacion" ;
	mysqli_select_db($amercado, $database_amercado);
	$query_liq = "SELECT * FROM liquidacion WHERE (tcomp='$tcomp' AND serie='$serie' AND ncomp='$ncomp')" ;

	$liq= mysqli_query($amercado, $query_liq) ;
	$num_reg = mysqli_num_rows($liq);

	if ($num_reg==1) {

		$inserta_comp ="INSERT INTO cbtesanul (tcomp,serie,ncomp,motivo,fechaanul) VALUES ('$tcomp','$serie','$ncomp', '$motivo','$fechaanul')";
		mysqli_query($amercado, $inserta_comp) ;
		$actualiza_liq = "UPDATE `liquidacion` SET `estado`='A' , `totremate`='0' , `totneto1`='0' , `totiva21`='0' , `subtot1`='0' ,`totneto2`='0' , totiva105='0' , subtot2='0' ,totacuenta ='0' , totgastos='0' , totvarios='0' , saldoafav='0' , codrem ='0' WHERE (tcomp= '$tcomp' AND serie='$serie' AND ncomp='$ncomp')";
		mysqli_query($amercado, $actualiza_liq) ;
		$actualiza_pagos = "UPDATE `cartvalores` SET `estado`='P' WHERE (tcompsal = '$tcomp' AND seriesal ='$serie' AND ncompsal ='$ncomp')";
		mysqli_query($amercado, $actualiza_pagos) ;
?>
<table width="400" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td class="ewGroupAggregate Estilo1" ><div align="center">La Liquidacion  N&ordm; &nbsp;&nbsp;<?php echo $ncomp ?></div></td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1" >&nbsp;</td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1"><div align="center">fue  eliminada correctametne</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php } else {
		$inserta_comp ="INSERT INTO cbtesanul (tcomp,serie,ncomp,motivo,fechaanul) VALUES ('$tcomp','$serie','$ncomp', '$motivo','$fechaanul')";
		mysqli_query($amercado, $inserta_comp) ;

		$query_serie = "SELECT * FROM series WHERE (tipcomp ='$tcomp' AND codnum ='$serie' )" ;
		mysqli_query($amercado, $query_nume_liq) ;
		$serie = mysqli_query($amercado, $query_serie ) or die(mysqli_error($amercado));
		$row_cabecerafac = mysqli_fetch_assoc($serie);
		$mascara = $row_cabecerafac['mascara'];
		if ($ncomp <10) {
			$mascara=$mascara."-"."0000000".$ncomp ;
		}

		if ($ncomp >9 && $ncomp <99) {
			$mascara=$mascara."-"."000000".$ncomp ;
		}

		if ($ncomp >99 && $ncomp <999) {
			$mascara=$mascara."-"."00000".$ncomp ;
		}
		if ($ncomp >999 && $ncomp <9999) {
			$mascara=$mascara."-"."0000".$ncomp ;
		}
		$inserta_liq ="INSERT INTO liquidacion (tcomp,serie,ncomp,estado,fechaliq,nrodoc) VALUES ('$tcomp','$serie','$ncomp', 'A','$fechaanul','$mascara')";
		mysqli_query($amercado, $inserta_liq) ;
?>
<table width="400" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td class="ewGroupAggregate Estilo1" ><div align="center">La Liquidacion  N&ordm; &nbsp;&nbsp;<?php echo $ncomp ?></div></td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1" >&nbsp;</td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1"><div align="center">fue  creada y eliminada</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<?php }

} else { 
	$query_fac = "SELECT * FROM cabfac  WHERE (cabfac.tcomp='$tcomp'  AND cabfac.serie='$serie'  AND cabfac.ncomp='$ncomp')";
	$fac = mysqli_query($amercado, $query_fac ) or die(mysqli_error($amercado));
	$num_reg = mysqli_num_rows($fac);
	if($num_reg==1) {
		// Inserto en la tabla de Comprobantes Anulados

		$inserta_comp ="INSERT INTO cbtesanul (tcomp,serie,ncomp,motivo,fechaanul) VALUES ('$tcomp','$serie','$ncomp', '$motivo','$fechaanul')";
		mysqli_query($amercado, $inserta_comp) ;

		// Actualizo la tabla de Cabecera de Factura
		$actualiza_fac = "UPDATE `cabfac` SET `estado` = 'A',`totneto` = 0 ,`totbruto` = 0 ,`totiva105`=0 ,`totiva21`= 0,`totimp` = 0 , `totcomis`=0 , `totneto105`= 0 , `totneto21`= 0 WHERE (tcomp= '$tcomp' AND serie='$serie' AND ncomp='$ncomp')";
		mysqli_query($amercado, $actualiza_fac) ;

		// Modifico  lotes en tabla de lotes
		$selecciona_detalle= "SELECT * FROM detfac WHERE (tcomp='$tcomp' AND serie='$serie' AND ncomp='$ncomp')" ;
		$detfac = mysqli_query($amercado, $selecciona_detalle ) ;
		$num_reg_det = mysqli_num_rows($detfac);

		while ( $myrow = mysqli_fetch_array($detfac)) {
 			$remate = $myrow['codrem'];
			$lote = $myrow['codlote'];
 			$actualiza_lotes = "UPDATE `lotes` SET `estado` = 0 , preciofinal = NULL WHERE (codrem= '$remate' AND secuencia ='$lote')";
			mysqli_query($amercado, $actualiza_lotes) ;
		}
		// Borro el detalle de la factura  Factura

		$borra_detalle ="DELETE FROM detfac  WHERE (tcomp= '$tcomp' AND serie='$serie' AND ncomp='$ncomp')";
		mysqli_query($amercado, $borra_detalle) ;
		// Borro el los medios de pago de la factura
		$borra_valores ="DELETE FROM cartvalores   WHERE (tcomprel = '$tcomp' AND serierel='$serie' AND ncomprel ='$ncomp')";
		mysqli_query($amercado, $borra_valores) ;

		// Borro la leyende de la factura
		$borra_leyenda ="DELETE FROM factley  WHERE (tcomp= '$tcomp' AND serie='$serie' AND ncomp='$ncomp')";
		mysqli_query($amercado, $borra_leyenda) ;

?>
<table width="400" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td class="ewGroupAggregate Estilo1" ><div align="center">La Factura  N&ordm; &nbsp;&nbsp;<?php echo $ncomp ?></div></td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1" >&nbsp;</td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1"><div align="center">fue eliminada</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<?php } else {
		//echo "Creando Factura"."<br>";

		$selecciona_detalle= "SELECT * FROM detfac WHERE (tcomp='$tcomp' AND serie='$serie' AND ncomp='$ncomp')" ;
		$detfac = mysqli_query($amercado, $selecciona_detalle ) ;
		$num_reg_det = mysqli_num_rows($detfac);
		if ($num_reg_det!=0) {
			while ( $myrow = mysqli_fetch_array($detfac)) {
 				$remate = $myrow['codrem'];
				$lote = $myrow['codlote'];
 				$actualiza_lotes = "UPDATE `lotes` SET `estado` = 0 , preciofinal = NULL WHERE (codrem= '$remate' AND secuencia ='$lote')";
				mysqli_query($amercado, $actualiza_lotes) ;
			}
			// Borro el detalle de la factura  Factura

			$borra_detalle ="DELETE FROM detfac  WHERE (tcomp= '$tcomp' AND serie='$serie' AND ncomp='$ncomp')";
			mysqli_query($amercado, $borra_detalle) ;
			// Borro el los medios de pago de la factura
			$borra_valores ="DELETE FROM cartvalores   WHERE (tcomprel = '$tcomp' AND serierel='$serie' AND ncomprel ='$ncomp')";
			mysqli_query($amercado, $borra_valores) ;

			// Borro la leyende de la factura
			$borra_leyenda ="DELETE FROM factley  WHERE (tcomp= '$tcomp' AND serie='$serie' AND ncomp='$ncomp')";
			mysqli_query($amercado, $borra_leyenda) ;
			// Actualizo la factura
			$ncomp1 = $ncomp - 1 ;
			$actualiza1 = sprintf("UPDATE `series` SET `nroact` = '$ncomp1' WHERE `series`.`codnum` = '$serie'") ;				 
			$resultado=mysqli_query($amercado,	$actualiza1);	?>
<table width="400" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td class="ewGroupAggregate Estilo1" ><div align="center">La Procesos  iniciados en la Factura  N&ordm; &nbsp;&nbsp;<?php echo $ncomp ?></div></td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1" >&nbsp;</td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1"><div align="center">fueron eliminados y se puede reutilizar esa factura</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

<?php } else {

		$inserta_comp ="INSERT INTO cbtesanul (tcomp,serie,ncomp,motivo,fechaanul) VALUES ('$tcomp','$serie','$ncomp', '$motivo','$fechaanul')";
		mysqli_query($amercado, $inserta_comp) ;

		$query_mascara = "SELECT * FROM series  WHERE  series.codnum='$serie'";
  		$mascara = mysqli_query($amercado, $query_mascara) or die(mysqli_error($amercado));
  		$row_mascara = mysqli_fetch_assoc($mascara);
  		$totalRows_mascara = mysqli_num_rows($mascara);
  		$mascara  = $row_mascara['mascara'];
		if ($ncomp <10) {
   			$mascara=$mascara."-"."0000000".$ncomp ;
   		}

		if ($ncomp >9 && $ncomp <99) {
  			$mascara=$mascara."-"."000000".$ncomp;
   		}

		if ($ncomp >99 && $ncomp <999) {
			$mascara=$mascara."-"."00000".$ncomp;
  		}
  
		if ($ncomp >999 && $ncomp <9999) {
			$mascara=$mascara."-"."0000".$ncomp;
  		}
		$inserta_fac ="INSERT INTO cabfac (tcomp,serie,ncomp,estado,fecval , fecdoc , fecreg , fecvenc , nrodoc ) VALUES ('$tcomp','$serie','$ncomp', 'A','$fechaanul','$fechaanul','$fechaanul','$fechaanul','$mascara')";
		mysqli_query($amercado, $inserta_fac) ;
		$actualiza1 = sprintf("UPDATE `series` SET `nroact` = '$ncomp' WHERE `series`.`codnum` = '$serie'") ;				 
		$resultado=mysqli_query($amercado,	$actualiza1);	
?>
<table width="400" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td class="ewGroupAggregate Estilo1" ><div align="center">La Factura  N&ordm; &nbsp;&nbsp;<?php echo $ncomp ?></div></td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1" >&nbsp;</td>
  </tr>
  <tr>
    <td class="ewGroupAggregate Estilo1"><div align="center">fue  creada y eliminada</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<?php 	}
	}
}
?>
</body>
</html>
