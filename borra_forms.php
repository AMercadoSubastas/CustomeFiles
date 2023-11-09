<?php include_once "ewcfg50.php" ;
 include_once "ewmysql50.php" ;
 include_once "phpfn50.php" ;
 include_once  "userfn50.php" ;
include_once "usuariosinfo.php"; 

include "header.php" ; ?>
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
//echo "Tipo ".$tcomp."<br>";
//echo "Serie ".$serie."<br>";
//echo "Numero ".$ncomp."<br>";
//echo "Motivo ".$motivo."<br>";
//echo "Fecha ".$fechaanul."<br>";
	if ($tcomp=='3' OR $tcomp=='31') {
		echo "<br>"."NO SE PUEDEN ELIMINAR LIQUIDACIONES POR ESTE PROCESO"."<br>";
	} else { 
		//echo "Tipo".$tcomp."<br>";
		//echo "Serie".$serie."<br>";
		//echo "Ncomp".$ncomp."<br>";
		//$sql ="SELECT * FROM cabfac WHERE tcomp = '$tcomp' AND serie = '$serie' AND ncomp ='$ncomp'";
		//$query_fac1 = "SELECT * FROM cabfac WHERE tcomp='1' AND serie='1' AND ncomp='1'" ;
		//$query_fac1 = "SELECT * FROM cabfac WHERE (tcomp='$tcomp' AND serie='$serie' AND ncomp='$ncomp')" ;
		//$fac= mysqli_query($amercado, $sql ) ;
		$query_fac = "SELECT * FROM cabfac  WHERE (cabfac.tcomp='$tcomp'  AND cabfac.serie='$serie'  AND cabfac.ncomp='$ncomp')";
		$fac = mysqli_query($amercado, $query_fac ) or die(mysqli_error($amercado));
		//$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
		$num_reg = mysqli_num_rows($fac);
		//$num_reg = mysqli_num_rows($fac);
		//echo "Es una factura";
		if($num_reg==1) {

			// Actualizo la tabla de Cabecera de Factura
			$actualiza_fac = "DELETE FROM `cabfac`  WHERE (tcomp= '$tcomp' AND serie='$serie' AND ncomp='$ncomp')";
			mysqli_query($amercado, $actualiza_fac) ;

			// Modifico  lotes en tabla de lotes
			$selecciona_detalle= "SELECT * FROM detfac WHERE (tcomp='$tcomp' AND serie='$serie' AND ncomp='$ncomp')" ;
			$detfac = mysqli_query($amercado, $selecciona_detalle ) ;
			$num_reg_det = mysqli_num_rows($detfac);
			//echo $num_reg_det;
			while ( $myrow = mysqli_fetch_array($detfac)) {
				// echo "Remate ".$myrow['codrem']."<br>";
				$remate = $myrow['codrem'];
				$lote = $myrow['codlote'];
				// echo "Lote ".$myrow['codlote']."<br>";
				$actualiza_lotes = "UPDATE `lotes` SET `estado` = 0 , preciofinal = NULL WHERE (codrem= '$remate' AND secuencia ='$lote')";
				mysqli_query($amercado, $actualiza_lotes) ;
				// echo "Actualizado,";
			}
			// Borro el detalle de la factura 

			$borra_detalle ="DELETE FROM detfac  WHERE (tcomp= '$tcomp' AND serie='$serie' AND ncomp='$ncomp')";
			mysqli_query($amercado, $borra_detalle) ;
	
			// Borro el los medios de pago de la factura
			$borra_valores ="DELETE FROM cartvalores   WHERE (tcomprel = '$tcomp' AND serierel='$serie' AND ncomprel ='$ncomp')";
			mysqli_query($amercado, $borra_valores) ;
		
			// Borro la leyenda de la factura
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

			 <?php } 
			  }


?>

</body>
</html>
