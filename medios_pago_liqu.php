<?php require_once('Connections/amercado.php'); 

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
//echo $totalRows_Cheques;

$query_sucursales = "SELECT * FROM sucbancos";
$sucursales = mysqli_query($amercado, $query_sucursales) or die(mysqli_error($amercado));
$row_sucursales = mysqli_fetch_assoc($sucursales);
$totalRows_sucursales = mysqli_num_rows($sucursales);





$datos = $_COOKIE['factura'];
//echo $datos."<br>";
//list($tcomp, $serie  ,$remate_num , $tot_general) = explode("@", $datos);
$datos1 = explode("@", $datos);
//echo count($datos1);
for ($index =0 ; $index <count($datos1) ; $index++)
{
  	 
   if ($index = 1) 
     {
	 $tcomp1 =$datos1[1] ;
	// echo $tcomp1."<br><br>";
	// $tcomp =explode("tcomp",$tcomp1);
	$tcomp = explode("tcomp", $tcomp1);
	$tip_comp = $tcomp[1];
	//echo "tipo comprobante".$tip_comp."<br>";
	 }
	 if ($index = 2) 
     {
	$serie1 =   $datos1[2];
	$serie = explode("serie", $serie1);
	// echo $serie."<br>";
	 $serie = $serie[1];
	// echo "Numero Serie".$serie."<br>";
	 }
	 if ($index = 3) 
     {
	 $remate_num1 =   $datos1[3];
	 $remate_num = explode("remate_num", $remate_num1);
	 $num_remate =  $remate_num[1];
	// echo "Num reamte".$num_remate."<br>";
	 }  	
	 if ($index = 4) 
     {
	 $monto = $datos1[4];
	 $tot_general = explode("tot_general", $monto);
	 $tot_gen =  $tot_general[1];
	//  echo "Total Generale".$tot_gen."<br>";
	 }   
//print ("$index. $datos1[$index].<br>\n");


}
$tcomp = $tcompu[0] ;
$serie = $serie[0] ;
$remate = $remate_num[0];
$tot_general = $tot_general[0];
//echo $tcomp."<br>";
// echo "Num reamte".$num_remate."<br>";
// echo "Total Generale".$tot_gen."<br>";
//echo $tot_general."<br>";
$sql_deposito = "SELECT   bancos.nombre , codchq , importe , fechapago , `codrem`" 
        . " FROM cartvalores , bancos  "
        . " WHERE tcomp = 9 "
        . " AND serie = 7 "
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serie"
        . " AND ncomprel IS NULL "
        . " AND codrem = $num_remate"
        . " AND bancos.codnum = cartvalores.codban";
		
		$sql = "SELECT bancos.nombre , codchq , importe , fechapago , codrem"
        . " FROM cartvalores , bancos "
        . " WHERE tcomp =8"
        . " AND serie =6"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel IS NULL "
        . " AND codrem =$num_remate"
        . " AND bancos.codnum = cartvalores.codban"
        . ' ';
		
		$sql_dolares = "SELECT importe "
        . " FROM cartvalores "
        . " WHERE tcomp =13"
        . " AND serie =9"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel IS NULL "
        . " AND codrem =$num_remate"
        . ' ';
		
		$sql_pesos = "SELECT importe "
        . " FROM cartvalores "
        . " WHERE tcomp =12"
        . " AND serie =8"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel IS NULL "
        . " AND codrem =$num_remate"
        . ' ';
		$precio2 =0 ;
$total_dolares = mysqli_query($amercado, $sql_dolares) or die(mysqli_error($amercado));	
$total_dol = mysqli_query($amercado, $sql_dolares) or die(mysqli_error($amercado));	
$totalRows_dolares = mysqli_num_rows($total_dolares);

$tot_pesos = mysqli_query($amercado, $sql_pesos) or die(mysqli_error($amercado));	

$total_deposito = mysqli_query($amercado, $sql_deposito) or die(mysqli_error($amercado));	
$tot_dep = mysqli_query($amercado, $sql_deposito) or die(mysqli_error($amercado));
//$row_deposito  = mysqli_fetch_assoc($total_deposito);
$totalRows_deposito = mysqli_num_rows($total_deposito);
//echo "CAntidad de Depositos". $totalRows_deposito."<br>";

//echo "<table >";
$monto_total_cheques = 0 ;
//$mont_total_cheques = 0 ;
$precio1 =0 ;
$total_cheques = mysqli_query($amercado, $sql) or die(mysqli_error($amercado));	
$total_cheq = mysqli_query($amercado, $sql) or die(mysqli_error($amercado));	
$totalRows_cheques = mysqli_num_rows($total_cheques);
$depositos = 0 ;
while ($registro = mysqli_fetch_array($tot_dep)){
	$depositos = $depositos + $registro['2'];
}   
$cheques = 0 ;
while ($registro1 = mysqli_fetch_array($total_cheq)){
	$cheques = $cheques + $registro1['2'];
}  
$dola = 0 ;
while ($registro2 = mysqli_fetch_array($total_dol)){
	$dola = $dola + $registro2['0'];
}
$efectivo = 0;
while ($registro3 = mysqli_fetch_array($tot_pesos)){
	$efectivo = $efectivo + $registro3['0'];
}
//	echo "Cheque".$cheques."<br>";
//	echo "Dolares".$dola."<br>";
//	echo "Depositos".$depositos."<br>"; ;
	$total_general = $cheques+$dola+$efectivo+$depositos;
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
.Estilo1 {color: #ECE9D8}
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
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
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingreso_depositos.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"></div></td>
            <td width="150" bgcolor="#00CCFF"><img src="images/deposito.gif" width="130" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/fecha_deposito.gif" width="130" height="16" /></td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="cheques" name="deposito_form" method="post" action="carga_deposito.php">
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
              <td ></td>
              <td width="150"><input name="deposito" type="text" class="seleccion" /></td>
              <td width="97"><div align="right">
                  <input name="fecha_deposito" type="text" class="seleccion" id="fecha_deposito" size="11" />
              </div></td>
              <td width="24"><a href="javascript:showCal('Calendar5')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
              <td><input name="importe_deposito" type="text" class="seleccion" size="10" />              </td>
            </tr>
            <tr>
              <td ><input name="codrem2"    type="hidden"  value="<?php echo $num_remate ?>"/></td>
              <td><input name="fechaing2"  type="hidden"  value="<?php echo $fechaing ?>"/></td>
              <td><input name="serie2"     type="hidden"  value="7"/></td>
              <input name="ncomp2"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel2"  type="hidden"  value="<?php echo $serie ?>" />
              <input name="tcomprel2"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado2"    type="hidden"  value="P"/>
              <input name="tcomp2"     type="hidden"  value="9"/>
              <td colspan="3"><input name="Submit222" type="submit" class="seleccion" value="Ingresar dep&oacute;sitos" />              </td>
            </tr>
          </form>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingreso_dolares.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/cantidad_dol.gif" width="150" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/cotizacion.gif" width="150" height="16" /></div></td>
            <td colspan="3" bgcolor="#00CCFF">&nbsp;</td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="form1" name="moneda" method="post" action="carga_moneda.php">
            <tr>
              <td width="150"><input name="cant_moneda" type="text" id="cant_moneda2" /></td>
              <td width="150"><input name="cotizacion" type="text" size="6"  onchange="calculo_cotiz(this.form)"/></td>
              <td colspan="3">&nbsp;</td>
              <td><div align="center">
                  <input name="tot_cotizacion" type="text" size="15" />
              </div></td>
            </tr>
            <tr>
              <td width="150">&nbsp;</td>
              <td width="150">&nbsp;</td>
              <td width="150">&nbsp;</td>
              <input name="codrem3"    type="hidden"  value="<?php echo $num_remate ?>"/>
              <input name="fechaing3"  type="hidden"  value="<?php echo $fechaing ?>"/>
              <input name="serie3"     type="hidden"  value="9"/>
              <input name="ncomp3"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel3"  type="hidden"  value="<?php echo $serie ?>" />
              <input name="tcomprel3"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado3"    type="hidden"  value="P"/>
              <input name="tcomp3"     type="hidden"  value="13"/>
              <td colspan="3"><div align="center">
                  <input name="Submit2" type="submit" class="seleccion" value="Ingresar monedas extranjeras" />
              </div></td>
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
          <form id="ing_pesos" name="ing_pesos" method="post" action="carga_pesos.php">
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
			   <input name="codrem4"    type="hidden"  value="<?php echo $num_remate ?>"/>
              <input name="fechaing4"  type="hidden"  value="<?php echo $fechaing ?>"/>
              <input name="serie4"     type="hidden"  value="8"/>
              <input name="ncomp4"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel4"  type="hidden"  value="2" />
              <input name="tcomprel4"  type="hidden"  value="3" />
              <input name="estado3"    type="hidden"  value="P"/>
              <input name="tcomp4"     type="hidden"  value="12"/>
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
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingresoefectivo.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/pesos.gif" width="150" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"></div></td>
            <td width="150" bgcolor="#00CCFF">&nbsp;</td>
            <td colspan="2" bgcolor="#00CCFF">&nbsp;</td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="ing_pesos" name="ing_pesos" method="post" action="carga_pesos.php">
            <tr>
              <td width="150"><input name="cant_efectivo2" type="text" id="cant_efectivo2"  onchange="pesos(this.form)" /></td>
              <td width="150">&nbsp;</td>
              <td width="150">&nbsp;</td>
              <td width="97">&nbsp;</td>
              <td width="24">&nbsp;</td>
              <td><input name="importe_pesos2" type="text" id="importe_pesos2" size="15" />
              </td>
            </tr>
            <tr>
              <td width="150">&nbsp;</td>
              <td width="150">&nbsp;</td>
              <td width="150">&nbsp;</td>
              <input name="codrem42"    type="hidden"  value="<?php echo $num_remate ?>"/>
              <input name="fechaing42"  type="hidden"  value="<?php echo $fechaing ?>"/>
              <input name="serie42"     type="hidden"  value="8"/>
              <input name="ncomp42"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel42"  type="hidden"  value="<?php echo $serie ?>" />
              <input name="tcomprel42"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado32"    type="hidden"  value="P"/>
              <input name="tcomp42"     type="hidden"  value="12"/>
              <td colspan="3"><div align="center">
                  <input name="enviar2" type="submit" class="seleccion" value="Ingresar pesos" />
              </div></td>
            </tr>
          </form>
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
            <td colspan="2" bgcolor="#00CCFF"><img src="images/fecha_dep_marro.gif" width="150" height="16" /></td>
            <td width="123" bgcolor="#999966"><img src="images/importes_marron.gif" width="70" height="16" /></td>
          </tr>
          <form id="cheques" name="cheque_list" method="post" >
		<?php while ($registro = mysqli_fetch_array($total_deposito)){
   $monto_total_cheques = $monto_total_cheques + $registro['2'];
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
            <td colspan="3" bgcolor="#993333" ><div align="center"><img src="images/moneda_extranj.gif" width="400" height="24" /></div></td>
          </tr>
          <tr>
            <td width="639" bgcolor="#999966"><div align="left"><img src="images/concepto.gif" width="200" height="16" /></div>
              <div align="center"></div></td>
            <td colspan="2" bgcolor="#999966"><div align="right"><img src="images/importes_marron.gif" width="70" height="16" /></div></td>
            </tr>
          <form id="form1" name="moneda_ext" method="post" action="lista_efectivo">
		  <?php while ($registro2 = mysqli_fetch_array($total_dolares)){
   $precio2= $precio2 + $registro2['0'];
 //  echo "Dolares".$registro2['0'];
?>
            <tr>
              <td bgcolor="#993333"><input name="descripcion_dolares" type="text" class="seleccion" value="Total de Moneda Extranjera" size="30"/></td>
              <td width="101" colspan="2"   bgcolor="#993333"  align="right">
               <input align="right" name="importe_dol" type="text" class="seleccion"  size="13" value="<?php echo $precio2 ?>" /> 
                  <?php } ?>               </td>
              </tr>
            <tr>
              <td bgcolor="#993333"><input name="descripcion_pesos" type="text" class="seleccion" value="Total de Pesos" size="30"/></td><td width="101" colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos" type="text" class="seleccion"  size="13" value="<?php echo $efectivo ?>" /> </td>
              </tr>
          </form>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="292" bgcolor="#ECE9D8"><div align="right"></div></td>
        <td width="349" align="center" valign="middle"><div align="right">
          <img src="images/total_general_v.gif" width="136" height="26" />
          <input name="textfield" type="text" class="seleccion" value="<?php echo $total_general ?>"/>
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
  <tr>
    <td colspan="2"><script type="text/javascript"> 
//alert()
chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('banco','sucursal','includes/getsucural.php'); 
chainedSelects.addChain('banco1','sucursal1','includes/getsucural.php'); 
//chainedSelects.addChain('city','university','includes/getLocalidad.php'); 
chainedSelects.init(); 

</script> 
</body>
</html>
<?php
mysql_free_result($bancos);
mysql_free_result($Cheques);
mysql_free_result($sucursales);
//mysql_free_result($sucursal);
?>
