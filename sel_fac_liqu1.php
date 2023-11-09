<?php require_once('Connections/amercado.php'); 
include_once "src/userfn.php";
 //include_once "ewcfg50.php" ;
 //include_once "ewmysql50.php" ;
 //include_once "phpfn50.php" ; 
 //include_once  "userfn50.php" ;
 //include_once "usuariosinfo.php" ; 
 //include "header.php" ;
//$cliente = $_POST['cliente'];
//echo $cliente;
$remate =  $_POST['remate'];
mysqli_select_db($amercado, $database_amercado);

$query_cliente = "SELECT entidades.razsoc , entidades.calle , entidades.numero ,  entidades.codloc , entidades.codprov FROM entidades WHERE codnum='$cliente'";
$selec_cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($selec_cliente);

//echo $row_cliente['razsoc']."<br>";
//echo $row_cliente['calle'];
//echo $row_cliente['numero']."<br>";

//echo $row_cliente['codloc']."<br>";
$localidad = $row_cliente['codloc'];
$provincia = $row_cliente['codprov'];
$query_localidad = "SELECT localidades.descripcion FROM localidades WHERE localidades.codnum='$localidad' AND localidades.codprov='$provincia'";
$localidad = mysqli_query($amercado, $query_localidad) or die(mysqli_error($amercado));
$row_loc = mysqli_fetch_assoc($localidad);
//echo $row_loc['descripcion']."<br>";
$query_prov = "SELECT provincias.descripcion FROM provincias WHERE provincias.codnum='$provincia'";
$provincia = mysqli_query($amercado, $query_prov) or die(mysqli_error($amercado));
$row_prov = mysqli_fetch_assoc($provincia);
//echo $row_prov['descripcion']."<br>";
//$provincia = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
//echo $row_cliente['provincia']."<br>";


$query_Recordset1 = "SELECT cabfac.nrodoc , cabfac.fecval, cabfac.totbruto , cabfac.tcomp , cabfac.codnum , cabfac.cliente FROM cabfac WHERE (codrem= '$remate' AND tcomp!='18' AND tcompsal IS NULL AND seriesal IS NULL AND ncompsal IS NULL) ORDER BY cabfac.tcomp , cabfac.serie , cabfac.ncomp";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
//$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
$tip_comp= $row_Recordset1['tcomp'];

mysqli_select_db($amercado, $database_amercado);
$query_tipo_comp = "SELECT tipcomp.descripcion FROM tipcomp  WHERE tipcomp.codnum='$tip_comp'";
$tipo_comp = mysqli_query($amercado, $query_tipo_comp) or die(mysqli_error($amercado));
$row_tipo_comp = mysqli_fetch_assoc($tipo_comp);
$totalRows_tipo_comp = mysqli_num_rows($tipo_comp);




//$totalRows_factura_recibos = mysqli_num_rows($factura_recibos);

?>
<script language="javascript">
function carga_cheque(form) {
var cantcheques = deposito_list.idcheque.length

if (cantcheques==null) {
   
 //  alert("Cant Cheques Null")
//   var cantcheques = 1 ;
 //  }
   //
 if(deposito_list.idcheque.checked == true)
	 { 
	 // alert (deposito_list.idcheque.checked);
	//   alert("ID"+deposito_list.idcheque[i].value);
	 //  var monto = "deposito_list.monto"+eval(0)+".value" ;
	   var monto  = deposito_list.monto0.value ;
	//   var monto = "deposito_list.monto"+eval(0)+".value" ;
	   var monto_cheque = eval(monto) ;
	 //  monto_total[i] = monto_cheque;
	 //  pesos_total = eval(pesos_total+('+')+monto_cheque) ;
	 //  alert("Monto"+monto);	  
	deposito_list.total_deposito.value = monto_cheque ;
    deposito_list.cod_num.value = deposito_list.idcheque.value ;;
	 }  

} else { 
//alert(cantcheques);

var monto_total = new Array(cantcheques) 
var i = 0 ;
var pesos_total = 0
var lista = ""; 
for (i ; i < cantcheques ; i++)
 { 
 // var monto = monto+'i' ;
 // alert (monto) ;
  //document.write(i) ;
 //  alert(i)
 // alert()
   if(deposito_list.idcheque[i].checked == true)
	 { 
	//   alert("ID"+deposito_list.idcheque[i].value);
	   var monto = "deposito_list.monto"+eval(i)+".value" ;
	   var lista  = lista + deposito_list.idcheque[i].value+",";
	   var monto = "deposito_list.monto"+eval(i)+".value" ;
	   var monto_cheque = eval(monto) ;
	   monto_total[i] = monto_cheque;
	   pesos_total = eval(pesos_total+('+')+monto_cheque) ;
	//  alert(lista);	  
	
	 }  
	 
 }	 
cant_cheques = eval(lista.length-1) ;
//alert (cant_cheques);
cheques_can = lista.substr(0,cant_cheques);
//alert ("El String "+cheques_can);
//alert ("largo "+cheques_can.length);
//var pesos_total = pesos_total.Math.floor(2);
deposito_list.total_deposito.value = pesos_total ;
deposito_list.cod_num.value = cheques_can;
}	   
  // total_deposito
//deposito_list.cant_cheques.value =
//cant_cheques = substr(lista,0,eval(lista.length-1)) ;

//alert (pesos_total);
//}


}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
}
.Estilo2 {color: #FFFFFF}
</style>

</head>

<body>
<table width="600" border="0" cellpadding="1" cellspacing="1">
 <tr>
    <td colspan="5" align="center" bgcolor="#316AC5" ><div align="center" class="ewAstSelListItem">LIQUIDACION PARCIAL</div> </td>
  </tr>
  <tr>
    <td colspan="5" align="center" bgcolor="#000066" class="ewAstSelListItem">FACTURAS</td>
  </tr>
 
  
  <form name="deposito_list"  action='aliquidacion_parc.php' method="post">
  
	<tr>
    
    <td>&nbsp;</td>
    <td class="ewMultiPagePager">Numero de Compobante</td>
    <td class="ewMultiPagePager">Cliente</td>
    <td class="ewMultiPagePager">Fecha del Comprobante</td>
    <td class="ewMultiPagePager">Monto del Comprobante</td>
  </tr>
 <? 
   $i=0;
//   $total_general= 0;
  while ($registro1 = mysqli_fetch_array($Recordset1) ){
  $cliente= $registro1['cliente'];
  $query_cliente = "SELECT  *  FROM entidades WHERE codnum='$cliente'";
$selec_cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($selec_cliente);
$cliente1 = $row_cliente['razsoc'];
  echo "<tr>"; ?>
<td width="15" bgcolor='993333'><input type='checkbox' name='idcheque' value='<? echo $registro1['codnum'] ?>' onClick="carga_cheque(this.form)"/></td> <?
echo "<td width=\"250\" bgcolor=\"993333\"><input type=\'text\' class=\"seleccion\" size=\"35\" value='$registro1[0]'/></td>" ;
echo "<td width=\"250\" bgcolor=\"993333\"><input  type=\'text\' class=\"seleccion\" size=\"35\" value='$cliente1 '/></td>" ;
echo "<td bgcolor=\"993333\"><input type=\"text\" class=\"seleccion\"  value='$registro1[1] '/></td>" ;
echo "<td bgcolor=\"993333\"><input name=\"monto$i\" type=\"text\" class=\"seleccion\"  value='$registro1[2]'/></td>" ;    
echo "</tr>";
$i=$i+1;

		} ?>
  <input name="remate" type="hidden" class="seleccion\"  value='<? echo $remate ?>'/>
    <tr>
    <td><input name="cod_num" type="hidden" class="seleccion" size="20" /></td>
    
	<td colspan="3" align="right">Total en Recibo&nbsp;&nbsp;&nbsp;</td>
    <td><input name="total_deposito" type="text" class="seleccion" size="15" /></td>
    </tr>
 <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><input type="hidden"   name="MM_update" value="deposito_list"></td>
    <td> <input type="submit" name="Submit" value="Enviar" /></td>
    
  </tr>
  </form>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    
  </tr>
  

</table>
<br /><br /><br />


</body>
</html>
<?php
mysql_free_result($Recordset1);

mysql_free_result($tipo_comp);

//mysql_free_result($factura_recibos);


?>
