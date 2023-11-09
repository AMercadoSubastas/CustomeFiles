<?php require_once('Connections/amercado.php'); ?>
<?php


$tcomp_nombre =  $_POST['tcomp_nombre_a'];
$tcomp_a =  $_POST['tcomp_a'];
$serie_nombre = $_POST['serie_nombre_a'];
$serie_a = $_POST['serie_a'];
$liquidacion = $_POST['liquidacion_a'];
$cliente = $_POST['cliente_a'];
$remate_num  =  $_POST['remate_num_a'];
$fecha_remate = $_POST['fecha_remate_a'];
$lugar_remate = $_POST['lugar_remate_a'];
$importe_total = $_POST['importe_total_a'];
$neto105 = $_POST['neto105_a'];
$iva105  = $_POST['iva105_a'];
$total105 =   $_POST['total105_a'];
$neto21 =    $_POST['neto21_a'];
$iva21   = $_POST['iva21_a'];
$total21  =  $_POST['total21_a'];
$acuenta  = $_POST['acuenta_a'];
$gastos_autor =  $_POST['gastos_autor_a'];
$otros_gastos =  $_POST['otros_gastos_a'];
$total_resta =   $_POST['total_resta_a'];
$total_gene =  $_POST['total_gene_a'];
echo $tcomp_a ;
//echo "Medios".$medios;

mysqli_select_db($amercado, $database_amercado);
$query_bancos = "SELECT * FROM bancos";
$bancos = mysqli_query($amercado, $query_bancos) or die(mysqli_error($amercado));
$row_bancos = mysqli_fetch_assoc($bancos);
$totalRows_bancos = mysqli_num_rows($bancos);

//echo $totalRows_bancos;
$query_Cheques = "SELECT * FROM cartvalores";
$Cheques = mysqli_query($amercado, $query_Cheques) or die(mysqli_error($amercado));
$row_Cheques = mysqli_fetch_assoc($Cheques);
$totalRows_Cheques = mysqli_num_rows($Cheques);
//echo $Cheques;
//echo $totalRows_Cheques;

$query_sucursales = "SELECT * FROM sucbancos";
$sucursales = mysqli_query($amercado, $query_sucursales) or die(mysqli_error($amercado));
$row_sucursales = mysqli_fetch_assoc($sucursales);
$totalRows_sucursales = mysqli_num_rows($sucursales);






$sql22 =	"SELECT bancos.nombre , sucbancos.nombre , codchq , importe , fechapago , codrem FROM cartvalores , bancos , sucbancos WHERE tcomp = 8 AND serie = 6  AND codrem = 5 AND bancos.codnum = cartvalores.codban  AND sucbancos.codnum = cartvalores.codsuc AND sucbancos.codbanco = bancos.codnum ";
	//	echo "aca va el sql22  ";
	//	echo ($sql22);
$total_cheques = mysqli_query($amercado, $sql22) or die(mysqli_error($amercado));	
$total_cheq = mysqli_query($amercado, $sql22) or die(mysqli_error($amercado));	
//echo $total_cheques; 
$totalRows_cheques = mysqli_num_rows($total_cheques);
echo "Numeros de cheques".$totalRows_cheques;



//echo "
echo "<table >";
";
$monto_total_cheques = 0 ;
//$mont_total_cheques = 0 ;
$precio1 =0 ;

//  $depositos = 0 ;
// while ($registro = mysqli_fetch_array($tot_dep)){
//  $depositos = $depositos + $registro['3'];
 //   }   
$cheques = 0 ;
 while ($registro1 = mysqli_fetch_array($total_cheq)){
  $cheques = $cheques + $registro1['3'];
    }  

?>
<form method="POST" name="cheque_tercero" action="<?php echo $editFormAction; ?>">
      <input name="tcomp_nombre_a" type="text" value="<?php echo $tcomp_nombre?>">
      <input name="tcomp_a" type="text" value="<?php echo $tcomp_a ?>">
      <input name="serie_nombre_a" type="text" value="<?php echo $serie_nombre ?>">
      <input name="serie_a" type="text" value="<?php echo $serie_a ?>">
      <input name="liquidacion_a" type="text" value="<?php echo $liquidacion ?>">
      <input name="cliente_a" type="text" value="<?php echo $cliente ?>">
      <input name="remate_num_a" type="text" value="<?php echo $remate_num ?>">
      <input name="fecha_remate_a" type="text" value="<?php echo $fecha_remate ?>">
      <input name="lugar_remate_a" type="text" value="<?php echo $lugar_remate ?>">
      <input name="importe_total_a" type="text" value="<?php echo $importe_total ?>">
      <input name="neto105_a"  type="text" value="<?php echo $neto105 ?>">
      <input name="iva105_a" type="text" value="<?php echo $iva105  ?>">
      <input name="total105_a" type="text" value="<?php echo $total105 ?>">
      <input name="neto21_a" type="text" value="<?php echo $neto21 ?>">
      <input name="iva21_a" type="text" value="<?php echo $iva21 ?>">
      <input name="total21_a" type="text" value="<?php echo $total21 ?>">
      <input name="acuenta_a" type="text" value="<?php echo $acuenta ?>">
      <input name="gastos_autor_a" type="text" value="<?php echo $gastos_autor  ?>">
      <input name="otros_gastos_a" type="text" value="<?php echo $otros_gastos ?>">
      <input name="total_resta_a" type="text" value="<?php echo $total_resta ?>">
      <input name="total_gene_a" type="text" value="<?php echo $total_gene ?>">
      <input type="hidden" name="MM_update" value="cheque_tercero">
</form>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />



<script language="javascript">
function hola(form) {
var cantcheques = deposito_list.idcheque.length
alert(cantcheques);

var monto_total = new Array(cantcheques) 
var i = 0 ;
var pesos_total = 0
for (i ; i < cantcheques ; i++)
 { 
 // var monto = monto+'i' ;
 // alert (monto) ;
  //document.write(i) ;
 //  alert(i)
 // alert()
   if(deposito_list.idcheque[i].checked == true)
	 {
	   var monto = "deposito_list.monto"+eval(i)+".value" ;
	   var monto_cheque = eval(monto) ;
	  monto_total[i] = monto_cheque;
	  pesos_total = eval(pesos_total+('+')+monto_cheque) ;
	  	  
	
	 }  
	 
	 

}	   
  // total_deposito
deposito_list.total_deposito.value = pesos_total
//alert (pesos_total);
//}


}

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
//alert ("hola")
var cantidad = moneda.cant_moneda.value ;
//alert (cantidad)
var cotiz = moneda.cotizacion.value ;
//alert (cotiz)
var total = eval(cantidad*cotiz);
//alert (total)
moneda.tot_cotizacion.value = total
//alert (total);
}

</script>
<script language="javascript">
function pesos(form)
{

var pesos_ing = ing_pesos.cant_efectivo.value ;
ing_pesos.importe_pesos.value = pesos_ing ;

}

//var ver1 = deposito_list.total_deposito.value ;

//alert();

</script> 
<style type="text/css">
<!--
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
}
.Estilo2 {
	color: #FFFF66;
	font-weight: bold;
}
</style>
</head>

<body >
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
     <form name="valor_contado" > <tr>
        <td colspan="2" >&nbsp;</td>
		  
        <td colspan="2" >&nbsp;</td>
     </tr>
	 </form>
      
      <tr>
        <td colspan="3"></td>
      </tr>
      
      
      <tr>
        <td width="43">&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="6" bgcolor="#993333" ><div align="center"><img src="images/cheques_terc_marron.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><span class="Estilo2">Sel</span></td>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/sucursal_marron.gif" width="64" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><img src="images/num_chq_marron.gif" width="135" height="16" /></td>
            <td bgcolor="#999966"><img src="images/fecha_vencimiento.gif" width="135" height="16" /></td>
            <td width="123" bgcolor="#999966"><div align="center"><img src="images/importes_marron.gif" width="70" height="16" /></div></td>
          </tr>
<form  name='deposito_list' method='post' action=''>
<?php $i=0;
while ($registro1 = mysqli_fetch_array($total_cheques)){
   $precio1= $precio1 + $registro1['3'];
   
echo "<tr>";
echo '<td width=\"150\" bgcolor=\'993333\'><input type=\'checkbox\' name=\'idcheque[]\' value=\"checkbox\" onclick="hola(this.form)"/></td>' ;
echo "<td width=\"150\" bgcolor=\'993333\'><input name=\'banco$i\' type=\'text\' class=\"seleccion\" value='$registro1[0]'/></td>" ;
echo "<td width=\"150\" bgcolor=\"993333\"><input name=\"sucrusal$i\" type=\"text\" class=\"seleccion\" value='$registro1[1]'/></td>" ;
echo "<td width=\"150\" bgcolor=\"993333\"><input name=\"vencimiento$i\" type=\"text\" class=\"seleccion\" value='$registro1[2]'/> </td>" ;
echo "<td bgcolor=\"993333\"><input name=\"fecha_venc$i\" type=\"text\" class=\"seleccion\"  value='$registro1[4] '/></td>" ;
echo "<td bgcolor=\"993333\"><input name=\"monto$i\" type=\"text\" class=\"seleccion\"  value='$registro1[3]'>' />              </td>" ;    
echo "</tr>";
$i=$i+1;
		} ?>
            <tr>
              <td bgcolor="#993333">&nbsp;</td>
              <td bgcolor="#993333">&nbsp;</td>
              <td bgcolor="#993333">&nbsp;</td>
              <td colspan="2" bgcolor="#993333"><div align="right"><img src="images/total_cheques_marr.gif" width="136" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_deposito" type="text" class="seleccion" size="15" /></td>
            </tr>
          </form>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="210" bgcolor="#ECE9D8"><div align="right"></div></td>
        <td width="344" align="center" valign="middle"><div align="right">
          
        </div></td>
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
  </table>
</body>
</html>
<?php
mysql_free_result($bancos);
mysql_free_result($Cheques);
mysql_free_result($sucursales);
//mysql_free_result($sucursal);
?>
