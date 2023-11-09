<?php 
error_reporting(E_ALL);
ini_set('display_errors','Yes');
require_once('Connections/amercado.php'); 
include_once "funcion_mysqli_result.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
}
.Estilo2 {font-size: 14px;
background:  #0099FF;
color:#000000; }

.Estilo3 {font-size: 16px;
background:  #FFFFFF;
color:#000000; }

.Estilo4 {font-size: 16px;
background:  #0099FF;
color:#000000; }
.facturas
{
text-align:center;
font-size: 19px;
background:  #00CCFF;
color:#000000; 
 
 
}

.Estilo1 {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
</style>

<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<script language="javascript">
function cambia_fecha(form)
{ 
	var fecha = recibo_cab.fecha_recibo1.value;
	var ano = fecha.substring(6,10);
	var mes = fecha.substring(3,5);
	var dia = fecha.substring(0,2);
	var fecha1 = ano+"-"+mes+"-"+dia ;
	recibo_cab.fecha_recibo.value = fecha1;
}
</script>
</head>

<body>
<?php
$tcomprel = $_GET['tcomprel'];
$serierel = $_GET['serierel'];
$ncomprel = $_GET['ncomprel'];
$tcomprel = 2;
$serierel = 3;

mysqli_select_db($amercado, $database_amercado);
$query_recibo = "SELECT fecha , cliente, imptot  FROM cabrecibo WHERE tcomp ='2' AND serie='3' AND ncomp='$ncomprel' AND emitido='0'";
$sel_recibo =mysqli_query($amercado, $query_recibo) or die(mysqli_error($amercado));
$row_recibonum = mysqli_fetch_assoc($sel_recibo);
$fecha = $row_recibonum['fecha'];
$cliente = $row_recibonum['cliente'];
$imptot = $row_recibonum['imptot'];

$query_cliente = "SELECT entidades.razsoc , entidades.calle , entidades.numero ,  entidades.codloc , entidades.codprov FROM entidades WHERE codnum='$cliente'";
$selec_cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($selec_cliente);

$localidad = $row_cliente['codloc'];
$provincia = $row_cliente['codprov'];
$query_localidad = "SELECT localidades.descripcion FROM localidades WHERE localidades.codnum='$localidad' AND localidades.codprov='$provincia'";
$localidad = mysqli_query($amercado, $query_localidad) or die(mysqli_error($amercado));
$row_loc = mysqli_fetch_assoc($localidad);

$query_prov = "SELECT provincias.descripcion FROM provincias WHERE provincias.codnum='$provincia'";
$provincia = mysqli_query($amercado, $query_prov) or die(mysqli_error($amercado));
$row_prov = mysqli_fetch_assoc($provincia);

$query_det_recibo = "SELECT detrecibo.nrodoc , detrecibo.netocbterel FROM detrecibo WHERE ncomp ='$ncomprel'";
$selec_det_recibo = mysqli_query($amercado, $query_det_recibo) or die(mysqli_error($amercado));

$monto_recibo = 0;
$factura_num = " - ";
 while ($monto = mysqli_fetch_array($selec_det_recibo)){
  $monto_recibo = $monto_recibo + $monto['1'];
  $factura_num =$factura_num.$monto['0']." - ";
    }
// Medios de Pago 	

// CHEQUES
$query_cheques = "SELECT codban,codsuc,codchq,importe FROM cartvalores WHERE ncomprel ='$ncomprel' AND tcomprel ='2' AND serierel='3' AND tcomp='8' AND serie='6'";
$selec_cheques = mysqli_query($amercado, $query_cheques) or die(mysqli_error($amercado));
    
// CHEQUES A TERCEROS
$query_cheques11 = "SELECT codban,codsuc,codchq,importe FROM cartvalores WHERE ncomprel ='$ncomprel' AND tcomprel ='2' AND serierel='3' AND tcomp='100' AND serie='44'";
$selec_cheques11 = mysqli_query($amercado, $query_cheques11) or die(mysqli_error($amercado));


// DEPOSITOS
$query_depositos = "SELECT codban,codsuc,codchq,importe FROM cartvalores WHERE ncomprel ='$ncomprel' AND tcomprel ='2' AND serierel='3' AND tcomp='9' AND serie='7'";
$selec_depositos = mysqli_query($amercado, $query_depositos) or die(mysqli_error($amercado));

// EFECTIVO
$query_efectivo = "SELECT importe FROM cartvalores WHERE ncomprel ='$ncomprel' AND tcomprel ='2' AND serierel='3' AND tcomp='12' AND serie='8'";
$selec_efectivo = mysqli_query($amercado, $query_efectivo) or die(mysqli_error($amercado));

// RETENCION GANANCIAS
$query_ganancias = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$ncomprel' AND tcomprel ='2' AND serierel='3' AND tcomp='42' AND serie='24'";
$selec_ganancias = mysqli_query($amercado, $query_ganancias) or die(mysqli_error($amercado));

// RETENCION ING BRUTOS
$query_ing_brutos = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$ncomprel' AND tcomprel ='2' AND serierel='3' AND tcomp='41' AND serie='23'";
$selec_ing_brutos = mysqli_query($amercado, $query_ing_brutos) or die(mysqli_error($amercado));

// RETENCION IVA
$query_iva = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$ncomprel' AND tcomprel ='2' AND serierel='3' AND tcomp='40' AND serie='22'";
$selec_iva = mysqli_query($amercado, $query_iva) or die(mysqli_error($amercado));

// RETENCION SUSS
$query_suss = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$ncomprel' AND tcomprel ='2' AND serierel='3' AND tcomp='43' AND serie='25'";
$selec_suss = mysqli_query($amercado, $query_suss) or die(mysqli_error($amercado));

$total_cheques = 0;
$total_cheques11 = 0;
$total_deposito = 0;
$total_efectivo = 0;
$total_iva = 0;
$total_ganancias = 0;
$total_suss = 0;
$total_ing_brutos = 0;

?>
 <table width="640" border="0" cellspacing="1" cellpadding="1"  bgcolor="#ECE9D8" >
<form id="recibo_cab" name="recibo" method="POST" action="<?php echo $editFormAction; ?>"> 
   
    <tr  bgcolor="#00CCFF">
      <td class="Estilo2" >&nbsp;&nbsp;&nbsp;Fecha</td>
      <td class="Estilo2"><input name="fecha_recibo1" type="text" class="Estilo3" size="12"  value="<?php echo $fecha ;?>" />
      </td>
      <td class="Estilo2" >&nbsp;&nbsp;&nbsp;Recibo</td>
      <td class="Estilo2"><input name="recibo"  class="Estilo3" type="text" size="12"  value="<?php echo $ncomprel ;?>" />
      </td>
    </tr>
    <input type="hidden" name="cliente"  value="<?php echo $cliente ?>" />
	<input type="hidden" name="tcomp"  value="2" />
	<input type="hidden" name="serie"  value="3" />
	
     <tr bgcolor="#003366">
      <td class="Estilo2" colspan="2">&nbsp;&nbsp;&nbsp;Nombre</td>
      <td colspan="2"><input type="text" name="nombre"  value="<?php echo $row_cliente['razsoc'] ?>"/></td>
    </tr>
    <tr bgcolor="#003366">
      <td class="Estilo2" colspan="2">&nbsp;&nbsp;&nbsp;Direccion</td>
      <td colspan="2"><input name="direccion" type="text" value="<?php echo $row_cliente['calle']."  ".$row_cliente['numero'] ?>" size="50"/></td>
    </tr>
     <tr bgcolor="#003366">
      <td class="Estilo2" colspan="2">&nbsp;&nbsp;&nbsp;Localidad</td>
      <td colspan="2"><input type="text" name="localidad" value="<?php echo $row_loc['descripcion'] ?>" /></td>
    </tr>
    <tr bgcolor="#003366">
      <td class="Estilo2" colspan="2">&nbsp;&nbsp;&nbsp;Provincia</td>
      <td colspan="2"><input type="text" class="seleccion" name="provincia" value="<?php echo $row_prov['descripcion'] ?>" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
	  <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" class="facturas">Factura Seleccionadas </td>
    </tr>
    <tr>
      <td colspan="4"><table width="100%" height="222" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">

        <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF" colspan="4"><textarea name="renglon1" cols="120" readonly="readonly" rows="5" class="seleccion"><?php echo $factura_num ?></textarea ></td>
      </tr> 
	      <tr bgcolor="FFFFFF">
		  <td width="25%" bgcolor="#0099FF" >&nbsp;</td>
		  <td width="25%" bgcolor="#0099FF">&nbsp;</td>
       <td width="25%" bgcolor="#0099FF"  class="Estilo4">&nbsp;&nbsp;&nbsp;Total</td>
       <td width="25%" bgcolor="#0099FF" ><input type="text" class="Estilo3" name="total_general" value="<?php echo $monto_recibo ?>"  readonly=""/></td>
      </tr>
 

 <tr bgcolor="FFFFFF">
   <td bgcolor="#ECE9D8" colspan="4">&nbsp;</td>
 </tr>
<tr>
      <td colspan="4" class="facturas">Medios de Pago </td>
    </tr>
 <tr bgcolor="FFFFFF">

   <td bgcolor="#ECE9D8" colspan="4"> <?php 
   $cheques_texto =1;
   $depositos_texto =1;
   
	if (mysqli_num_rows($selec_cheques)!=0) {	   
    	while ($cheques = mysqli_fetch_array($selec_cheques)){
     		$codban =$cheques['0'];
     		$codsuc =$cheques['1'];
     		$codchq =$cheques['2'];
	 		if ( $cheques_texto==1) {
         		echo "CHEQUES"."<BR>";
	    		$cheques_texto = $cheques_texto+1;
       		}
	   		$importe_cheque =$cheques['3'];
	   		mysqli_select_db($amercado, $database_amercado);
	   		$query_de_bancos = "SELECT *  FROM bancos WHERE codnum ='$codban'";
       		$selecciona_bancos = mysqli_query($amercado, $query_de_bancos) or die(mysqli_error($amercado));
	   		$row_bancos = mysqli_fetch_assoc($selecciona_bancos);
	   		$nombre_bancos =  $row_bancos['nombre'];
	   		$total_cheques=$total_cheques+$importe_cheque;
      		echo $nombre_bancos." - ".$codchq." - $".$importe_cheque." ," ;
	 	} 
		echo "  Total cheques $:".$total_cheques."<br><br>";
	}	
    if (mysqli_num_rows($selec_cheques11)!=0) {	   
    	while ($cheques11 = mysqli_fetch_array($selec_cheques11)){
     		$codban11 =$cheques11['0'];
     		$codsuc11 =$cheques11['1'];
     		$codchq11 =$cheques11['2'];
	 		if ( $cheques_texto11==1) {
         		echo "CHEQUES A TERCEROS"."<BR>";
	    		$cheques_texto11 = $cheques_texto11+1;
       		}
	   		$importe_cheque11 =$cheques11['3'];
	   		mysqli_select_db($amercado, $database_amercado);
	   		$query_de_bancos = "SELECT *  FROM bancos WHERE codnum ='$codban11'";
       		$selecciona_bancos = mysqli_query($amercado, $query_de_bancos) or die(mysqli_error($amercado));
	   		$row_bancos = mysqli_fetch_assoc($selecciona_bancos);
	   		$nombre_bancos =  $row_bancos['nombre'];
	   		$total_cheques11=$total_cheques11+$importe_cheque11;
      		echo $nombre_bancos." - ".$codchq11." - $".$importe_cheque11." ," ;
	 	} 
		echo "  Total cheques a 3ros $:".$total_cheques11."<br><br>";
	}	
	if (mysqli_num_rows($selec_depositos)!=0) {	
		$depositos_texto =1 ;
    	while ($deposito = mysqli_fetch_array($selec_depositos)){	
        	$codban =$deposito['0'];
        	$codsuc =$deposito['1'];
        	$codchq =$deposito['2'];
	    	if ( $depositos_texto==1) {
            	echo "DEPOSITOS"."<BR>";
	         	$depositos_texto =  2;
           	}
	    	$importe_deposito =$deposito['3'];
	     	mysqli_select_db($amercado, $database_amercado);
	     	$query_de_bancos = "SELECT *  FROM bancos WHERE codnum ='$codban'";
	     	$selecciona_bancos = mysqli_query($amercado, $query_de_bancos) or die(mysqli_error($amercado));
	     	$row_bancos = mysqli_fetch_assoc($selecciona_bancos);
	     	$nombre_bancos =  $row_bancos['nombre'];
	     	$total_deposito=$total_deposito+$importe_deposito;
		 	echo $nombre_bancos." - ".$codchq." - $".$importe_deposito." ," ;
	  	} 
	  	echo "Total deposito : $".$total_deposito."<br><br>";
	}
	if (mysqli_num_rows($selec_efectivo)!=0) {		  
		$efectivo_texto=1 ;
	  	while ($efectivo = mysqli_fetch_array($selec_efectivo)){	
	 		if ( $efectivo_texto==1) {
     			echo "EFECTIVO"."<BR>";
	    		$efectivo_texto =  2;
       		}
	   		$importe_efectivo =$efectivo ['0'];
	   		$total_efectivo=$total_efectivo+$importe_efectivo;
	  	} 
	  	echo "Total efectivo : $".$total_efectivo."<br><br>";
	}
	if ((mysqli_num_rows($selec_ganancias)!=0) or (mysqli_num_rows($selec_ing_brutos)!=0)	or (mysqli_num_rows($selec_suss)!=0) or (mysqli_num_rows($selec_iva)!=0)) {   
		echo "RETENCIONES"."<BR>";
	  	if ((mysqli_num_rows($selec_ganancias)!=0)) {
	  		$total_ganancia=0;
	  		$ganancia_texto= 1;
	  		while ($ganancia = mysqli_fetch_array($selec_ganancias)){	
   				$codban =$ganancia['0'];
   				$importe_ganancia =$ganancia['1'];
	 			if ( $ganancia_texto==1) {
     				echo "Retencion de ganancias"."<BR>";
	    			$ganancia_texto =  2;
       			}
	   			$total_ganancia=$total_ganancia+$importe_ganancia;
      			echo $codban." - $".$importe_ganancia." ," ;
	  		} 
	  		echo "Total Retencion ganancias : $".$total_ganancia."<br><br>";
	  	}
	  	if ((mysqli_num_rows($selec_ing_brutos)!=0)) {
	  		$total_ing_brutos =0;
	  		$ing_brutos_texto= 1;
	  		while ($ing_brutos = mysqli_fetch_array($selec_ing_brutos)){	
     			$codban =$ing_brutos['0'];
     			$importe_ing_brutos =$ing_brutos['1'];
	 			if ( $ing_brutos_texto==1) {
     				echo "Retencion de Ingresos Brutos"."<BR>";
	    			$ing_brutos_texto =  2;
       			}
	   			$total_ing_brutos = $total_ing_brutos+$importe_ing_brutos;
      			echo $codban." - $".$importe_ing_brutos." ," ;
	  		} 
	  		echo "Total Retencion Ingresos Brutos: $".$total_ing_brutos."<br><br>";
	  	}
	  	if ((mysqli_num_rows($selec_iva)!=0)) {
	  		$total_iva=0;
	  		$iva_texto= 1;
	  		while ($iva = mysqli_fetch_array($selec_iva)){	
   				$codban =$iva['0'];
   				$importe_iva =$iva['1'];
	 			if ( $iva_texto==1) {
     				echo "Retencion de IVA"."<BR>";
	    			$iva_texto =  2;
       			}
	   			$total_iva=$total_iva+$importe_iva;
      			echo $codban." - $".$importe_iva." ," ;
	  		} 
	  		echo "Total Retencion de IVA : $".$total_iva."<br><br>";
	  	}
	  	if ((mysqli_num_rows($selec_suss)!=0)) {
	  		$total_suss=0;
	  		$suss_texto= 1;
	  		while ($suss = mysqli_fetch_array($selec_suss)){	
   				$codban =$suss['0'];
   				$importe_suss =$suss['1'];
	 			if ( $suss_texto==1) {
     				echo "Retencion de SUSS"."<BR>";
	    			$suss_texto =  2;
       			}
		   		$total_suss=$total_suss+$importe_suss;
      			echo $codban." - $".$importe_suss." ," ;
	  		} 
	  		echo "Total Retencion SUSS : $".$total_suss."<br><br>";
	  	}
	}
	?></td>
 </tr>
 <tr bgcolor="FFFFFF">
   <td colspan="4" align="center" bgcolor="#0099CC"><a href="rp_recibo.php?ncomp=<?php echo $ncomprel ?>"><img src="images/seguir.gif" width="51" height="19" border="0" /></a></td>
   </tr>
   <tr bgcolor="FFFFFF">
   <td height="33" colspan="4" align="center" bgcolor="#0099CC"><a href="VRecibocobuna"><img src="images/salir_but.gif" width="55" height="23" border="0" /></a></td>
   </tr>
      </table></td>
    </tr>
	<input type="hidden" name="MM_insert" value="recibo">
   </form>
</table>

</body>
</html>
