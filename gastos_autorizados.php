<?php require_once('Connections/amercado.php'); ?>
<?php 
$precio1=0;

$remate = $_GET['remate_num_b'];
function strsplt($thetext) 
{ 

	$num=1; 

	$arr=array(); 
	$x=floor(strlen($thetext)); 
	while ($i<=$x) 
	{ 
		$y=substr($thetext,$j,$num); 
		if ($y) 
		{ 
			array_push($arr,$y); 
		} 
		$i++; 
		$j=$j+$num; 
	} 
	return $arr; 
} 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "deposito_list")) {
	echo "entra";
	$fecha = date("Y-m-d");

	$cod_1 = GetSQLValueString($_POST['cod_num'], "text")."<br><br>";
	$lar_num = strlen($cod_1)-10;
	strsplt($cod_1);
	$cod_1 = substr($cod_1,1,($lar_num));

	echo $arr[0]."<br>";
	echo $arr[1]."<br>";
	echo "ANTES Cod Num  :".$cod_1."<br>";
	echo "ANTES largo :".$lar_num."<br><br>";
	$codigos = array($cod_1);
	echo array_count_values($codigos); 

	$total_cheques = GetSQLValueString($_POST['total_deposito'], "int");
	$numero_remate = GetSQLValueString($_POST['remate'], "int");
	$numero_cliente = GetSQLValueString($_POST['cliente'], "int");
	$cod_numero = GetSQLValueString($_POST['cod_num'], "text");

	echo "Numero de Remate ".$numero_remate."<br>";

	// Desde ACA
	$updateSQL = sprintf("UPDATE cabfac SET estado='S'  WHERE tcomp='18' AND  serie='1' AND codnum IN (%s)",
                           $cod_1);

  	mysqli_select_db($amercado, $database_amercado);
  	$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));

  
	echo "<form name='cheque_tercero' action='aliquidacion.php'?remate=<?php echo $remate ?>";
	echo "<input name='remate' type='text' value='$remate' />";

	echo "</form>";
	echo "<script language='javascript'>";

	echo "cheque_tercero.submit()";
	echo "</script>";

}
?>
<?php
$tipo = $_GET['tcomp_b'];
$serie = $_GET['serie_b'];
$liquidacion = $_GET['liquidacion_b'];
$remate = $_GET['remate_num_b'];
?>
<?php
$maxRows_tipo_comprobante = 100;
$pageNum_tipo_comprobante = 0;
if (isset($_GET['pageNum_tipo_comprobante'])) {
  	$pageNum_tipo_comprobante = $_GET['pageNum_tipo_comprobante'];
}
$startRow_tipo_comprobante = $pageNum_tipo_comprobante * $maxRows_tipo_comprobante;

mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$query_limit_tipo_comprobante = sprintf("%s LIMIT %d, %d", $query_tipo_comprobante, $startRow_tipo_comprobante, $maxRows_tipo_comprobante);
$tipo_comprobante = mysqli_query($amercado, $query_limit_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);

if (isset($_GET['totalRows_tipo_comprobante'])) {
  	$totalRows_tipo_comprobante = $_GET['totalRows_tipo_comprobante'];
} else {
  	$all_tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante);
  	$totalRows_tipo_comprobante = mysqli_num_rows($all_tipo_comprobante);
}
$totalPages_tipo_comprobante = ceil($totalRows_tipo_comprobante/$maxRows_tipo_comprobante)-1;

//==============================================================================================
if ($tipo == 3) {
	mysqli_select_db($amercado, $database_amercado);
	$query_gastos_autorizados = "SELECT cabfac.codnum, cabfac.ncomp, cabfac.fecreg, cabfac.totbruto, cabfac.tcomp FROM cabfac WHERE (cabfac.tcomp=18 OR cabfac.tcomp=19) AND cabfac.codrem='$remate' AND cabfac.estado='P'";
	$total_gastos_autorizados = mysqli_query($amercado, $query_gastos_autorizados) or die(mysqli_error($amercado));	
	$cant_gastos = mysqli_num_rows($total_gastos_autorizados);
	
}
else {
	mysqli_select_db($amercado, $database_amercado);
	$query_gastos_autorizados = "SELECT cabfac.codnum, cabfac.ncomp, cabfac.fecreg, cabfac.totbruto, cabfac.tcomp FROM cabfac WHERE (cabfac.tcomp=27 OR cabfac.tcomp=28) AND cabfac.codrem='$remate' AND cabfac.estado='P'";
	$total_gastos_autorizados = mysqli_query($amercado, $query_gastos_autorizados) or die(mysqli_error($amercado));	
	$cant_gastos = mysqli_num_rows($total_gastos_autorizados);

}
if ($cant_gastos == 0){ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>

<form name='cheque_tercero' action="aliquidacion.php?remate=<?php echo $remate ?>">
<input name='remate' type='hidden' value="<?php echo $remate ?>" />
</form>
<script language="javascript">
alert("No hay Gastos autorizados para ingresar")
cheque_tercero.submit()
</script>
<?php }
?>
<?php
 require_once('Connections/amercado.php');  
 include_once "userfn11.php" ;
?>
<script language="javascript">
function carga_factura(form) {
	var cantfact = deposito_list.idfactura.length // Cantidad de Facturas marcadas
	if (cantfact==null) {
   		if(deposito_list.idfactura.checked == true) { 
	   		var monto  = deposito_list.monto0.value ;
	   		var monto_factura = eval(monto) ;
	 
			deposito_list.total_deposito.value = monto_factura ;
    		deposito_list.cod_num.value = deposito_list.idfactura.value ;;
		}  

	} else { 

		var monto_total = new Array(cantfact) 
		var i = 0 ;
		var pesos_total = 0
		var lista = ""; 
		for (i ; i < cantfact ; i++) { 
   			if(deposito_list.idfactura[i].checked == true) { 
	   			var monto = "deposito_list.monto"+eval(i)+".value" ;
	   			var lista  = lista + deposito_list.idfactura[i].value+",";
	   			var monto = "deposito_list.monto"+eval(i)+".value" ;
	   			var monto_cheque = eval(monto) ;
	   			monto_total[i] = monto_cheque;
	   			pesos_total = eval(pesos_total+('+')+monto_cheque) ;
	   			alert (monto);
	
			}  
		}	 
		cant_cheques = eval(lista.length-1) ;
		cheques_can = lista.substr(0,cant_cheques);
		deposito_list.total_deposito.value = pesos_total ;
		deposito_list.cod_num.value = cheques_can;
		alert(deposito_list.cod_num.value); 

	}
}

</script>
<script language="javascript">
function cambia_fecha(form) { 
	var fecha = factura.fecha_factura1.value;
	var ano = fecha.substring(6,10);
	var mes = fecha.substring(3,5);
	var dia = fecha.substring(0,2);
	var fecha1 = ano+"-"+mes+"-"+dia ;

}
</script>

<!-- Hasta Aca  !-->

<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="640" border="0" cellspacing="1" cellpadding="1">
  <tr>
    <td colspan="2" background="images/fondo_titulos.jpg"><img src="images/ing_medios_pago.gif" width="315" height="30" /></td>
  </tr>
  <tr>
    <td width="320">&nbsp;</td>
    <td width="379">&nbsp;</td>
  </tr>
  
  <tr>
    <td colspan="2"><table width="90%" border="0" align="center" cellpadding="2" cellspacing="1" bordercolor="#ECE9D8" bgcolor="#ECE9D8">
     <tr>
        <td colspan="2" >&nbsp;</td>
		  
        <td colspan="2" >&nbsp;</td>
     </tr>
	 
      
      <tr>
        <td colspan="3"></td>
      </tr>
      
      
      <tr>
        <td width="43">&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="6" bgcolor="#993333" ><div align="center"><img src="images/cheques_gast_autor.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><span class="Estilo2">Sel</span></td>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/factura_marron.gif" width="100" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/fecha_maa.gif" width="100" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><img src="images/mointo.gif" width="100" height="16" /></td>
           
            
          </tr>
<form  name='deposito_list' method='post' action='<?php echo $editFormAction; ?>'>
<?php 
$i=0;
while ($registro1 = mysqli_fetch_array($total_gastos_autorizados)){
	$precio1= $precio1 + $registro1['3'];
   	$ano = substr($registro1[2],0,4);
   	$mes = substr($registro1[2],5,2);
   	$dia = substr($registro1[2],8,2);
   	$fecha = $dia."-".$mes."-".$ano;
	echo "<tr>"; ?>

<td width="150" bgcolor='993333'><input type='checkbox' name='idfactura' value='<?php echo $registro1[0] ?>' onClick="carga_factura(this.form)"/></td> <?php echo "<td width=\"150\" bgcolor=\'993333\'><input name=\'factnum$i\' type=\'text\' class=\"seleccion\" value='$registro1[1]'/></td>" ;
echo "<td width=\"150\" bgcolor=\"993333\"><input name=\"fecha_esp$i\" type=\"text\" class=\"seleccion\" value='$fecha'/></td>" ;
echo "<td width=\"150\" bgcolor=\"993333\"><input name=\"monto$i\" type=\"text\" class=\"seleccion\" value='$registro1[3]'/> </td>" ;
echo "<input name=\"fecha$i\" type=\"text\" class=\"seleccion\" value='$registro1[2]'/>";
echo "</tr>";
$i=$i+1;
		} ?>
            <tr><input name="cod_num" type="text" class="seleccion" size="20" />
			<input name="liquidacion" type="text" class="seleccion" size="15" value='<?php echo $liquidacion ?>' />
			<input name="cliente" type="hidden" class="seleccion" size="15" value='<?php echo $cliente ?>' />
			<input name="remate" type="text" class="seleccion" size="15" value='<?php echo $remate ?>' />
              <td bgcolor="#993333">&nbsp;</td>
              <td bgcolor="#993333">&nbsp;</td>
              
              <td  bgcolor="#993333" align="right"><img src="images/total_facturas_marr.gif" width="136" height="26" /></td>
              <td bgcolor="#993333"><input name="total_deposito" type="text" class="seleccion" size="15" /></td>
			     </tr>
         <tr><td colspan="4" class="ewFooterRow">  <input type="hidden"   name="MM_update" value="deposito_list">
		  <input type="submit"  name="Submit" value="Enviar" />
		  </td></tr>
		  </form>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
       <td colspan="2"><a href="aliquidacion.php?remate=<?php echo $remate ?>"><img src="images/salir_but.gif" width="72" height="20"  border="0" onClick="salir()"/></a></td>
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
  </table>
</body>
</html>
<?php
mysql_free_result($tipo_comprobante);
?>