<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "src/userfn.php";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

// DESDE ACA =======================================================================================

if (isset($_POST['tcomp']) && GetSQLValueString($_POST['tcomp'], "int")=="44") {
	if (isset($_POST['leyendafc']) && GetSQLValueString($_POST['leyendafc'], "text")!="NULL") {
 		$insertSQLfactley = sprintf("INSERT INTO factley (tcomp, serie, ncomp, leyendafc, codrem) VALUES (%s, %s, %s, %s, %s)",
                	GetSQLValueString($_POST['tcomp'], "int"),
                   	26, // GetSQLValueString($_POST['serie'], "int"),
					GetSQLValueString($_POST['numfac'], "int"),
					GetSQLValueString($_POST['leyendafc'], "text"),
                    GetSQLValueString($_POST['codrem'], "int"));
                       

  		mysqli_select_db($amercado, $database_amercado);
  	
  		$Result1 = mysqli_query($amercado, $insertSQLfactley) or die(mysqli_error($amercado));

	}
}

if (isset($_POST['tcomp']) && GetSQLValueString($_POST['tcomp'], "int")=="45") {
	if (isset($_POST['leyendafc']) && GetSQLValueString($_POST['leyendafc'], "text")!="NULL") {
 		$insertSQLfactley = sprintf("INSERT INTO factley (tcomp, serie, ncomp, leyendafc, codrem) VALUES (%s, %s, %s, %s, %s)",
                  	GetSQLValueString($_POST['tcomp'], "int"),
                  	27, // GetSQLValueString($_POST['serie'], "int"),
					GetSQLValueString($_POST['numfac'], "int"),
					GetSQLValueString($_POST['leyendafc'], "text"),
                    GetSQLValueString($_POST['codrem'], "int"));
                       

  		mysqli_select_db($amercado, $database_amercado);
  	
  		$Result1 = mysqli_query($amercado, $insertSQLfactley) or die(mysqli_error($amercado));

	}
}


// HASTA ACA ===========================================================================================
?><?php require_once('Connections/amercado.php'); 

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

$datos = $_COOKIE['concepto'];
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
	$tcomp =explode("tcomp",$tcomp1);
	$tcomp = explode("tcomp", $tcomp1);
	$tip_comp = $tcomp[1];
//	echo "Tipo comprobante".$tip_comp."<br>";
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
	 $remate_num =   $datos1[3];
	 $remate_num = explode("remate_numa", $remate_num);
	 $num_remate =  $remate_num[1];
	//echo "Num remate".$num_remate."<br>";
	 }  
	  if ($index = 4) 
     {
	 $monto = $datos1[4];
	  $num_fact = explode("num_factura", $monto);
	 $num_fact =  $num_fact[1];
 //echo "Numero factura".$num_fact."<br>";
	 }   	
	 if ($index = 5) 
     {
	 $monto = $datos1[5];
	 $tot_general = explode("tot_general", $monto);
	 $tot_gen =  $tot_general[1];
 // echo "Total Generale".$tot_gen."<br>";
	 }     
//print ("$index. $datos1[$index].<br>\n");


}
$tcomp = $tcomp[0] ;
$serie = $serie[0] ;
$remate = $remate_num[0];
$tot_general = $tot_general[0];
//echo $tcomp."<br>";
//echo "Num reamte".$num_remate."<br>";
//echo "Total Generale".$tot_gen."<br>";
//echo $tot_general."<br>";

$sql_deposito = "SELECT   bancos.nombre , codchq , importe , fechapago , `codrem`" 
        . " FROM cartvalores , bancos"
        . " WHERE tcomp = 9 "
        . " AND serie = 7 "
        . " AND tcomprel = $tip_comp"
        . " AND serierel = $serie"
        . " AND ncomprel = $num_fact"
        . " AND codrem = $num_remate"
        . " AND bancos.codnum = cartvalores.codban";
		
		$sql = "SELECT bancos.nombre , codchq , importe , fechapago , codrem"
        . " FROM cartvalores , bancos"
        . " WHERE tcomp =8"
        . " AND serie =6"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
        . " AND bancos.codnum = cartvalores.codban"
        . ' ';
		
		$sql_dolares = "SELECT importe "
        . " FROM cartvalores "
        . " WHERE tcomp =13"
        . " AND serie =9"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
        . ' ';
		
		$sql_pesos = "SELECT importe "
        . " FROM cartvalores "
        . " WHERE tcomp =12"
        . " AND serie =8"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
		. ' ';
		
		$sql_iva = "SELECT * "
       . " FROM cartvalores "
        . " WHERE tcomp =40"
        . " AND serie =22"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
        . ' ';
	
		$sql_ing_brutos = "SELECT *"
        . " FROM cartvalores "
        . " WHERE tcomp =41"
        . " AND serie =23"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
        . ' ';
		
		$sql_ganancias = "SELECT * "
        . " FROM cartvalores "
        . " WHERE tcomp =42"
        . " AND serie =24"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
        . ' ';
		
		$sql_suss = "SELECT * "
        . " FROM cartvalores "
        . " WHERE tcomp =43"
        . " AND serie =25"
        . " AND tcomprel =$tip_comp"
        . " AND serierel =$serie"
        . " AND ncomprel = $num_fact"
        . " AND codrem =$num_remate"
        . ' '; 
		$precio2 =0 ;
$total_dolares = mysqli_query($amercado, $sql_dolares) or die(mysqli_error($amercado));	
$total_dol = mysqli_query($amercado, $sql_dolares) or die(mysqli_error($amercado));	
$totalRows_dolares = mysqli_num_rows($total_dolares);

/// Retension de IVA
$total_iva = mysqli_query($amercado, $sql_iva) or die(mysqli_error($amercado));
$totalRows_total_iva = mysqli_num_rows($total_iva);
$row_iva = mysqli_fetch_assoc($total_iva);
$monto_iva = $row_iva["importe"] ;
$fecha_iva = $row_iva["fechapago"] ;
$comp_iva = $row_iva["codchq"] ;
//echo "Total filas IVA".$totalRows_total_iva."<br>";
//echo "monto IVA".$monto_iva."<br>";
//echo "Fecha IVA".$fecha_iva."<br>";
//echo "Comprobante IVA".$comp_iva."<br>";
$fecha_iva = substr($fecha_iva,8,2)."-".substr($fecha_iva,5,2)."-".substr($fecha_iva,0,4);

// Retension de ING Brutos
$total_ing_brutos = mysqli_query($amercado, $sql_ing_brutos) or die(mysqli_error($amercado));	
$totalRows_total_ing_brutos = mysqli_num_rows($total_ing_brutos);
//echo $totalRows_total_ing_brutos;
$row_ing_brutos = mysqli_fetch_assoc($total_ing_brutos);
$monto_ing_brutos = $row_ing_brutos["importe"] ;
$fecha_ing_brutos = $row_ing_brutos["fechapago"] ;
$comp_ing_brutos = $row_ing_brutos["codchq"] ;
//echo "Total filas Ing Brutos".$totalRows_total_ing_brutos."<br>";
//echo "monto Ing Brutos".$monto_ing_brutos."<br>";
//echo "Fecha Ing Brutos".$fecha_ing_brutos."<br>";
///echo "Comprobante Ing Brutos".$comp_ing_brutos."<br>";
$fecha_ing_brutos = substr($fecha_ing_brutos,8,2)."-".substr($fecha_ing_brutos,5,2)."-".substr($fecha_ing_brutos,0,4);
// Retension de GANANCIAS
$total_ganancias = mysqli_query($amercado, $sql_ganancias) or die(mysqli_error($amercado));	
$totalRows_total_ganancias = mysqli_num_rows($total_ganancias);

$row_ganancias = mysqli_fetch_assoc($total_ganancias);
$monto_ganancias = $row_ganancias["importe"] ;
$fecha_ganancias = $row_ganancias["fechapago"] ;
$comp_ganancias = $row_ganancias["codchq"] ;
$fecha_ganancias = substr($fecha_ganancias,8,2)."-".substr($fecha_ganancias,5,2)."-".substr($fecha_ganancias,0,4);
//echo "Total Ganancia filas".$totalRows_total_ganancias."<br>";
//echo "monto Ganancia".$monto_ganancias."<br>";
//echo "Fecha Ganancia".$fecha_ganancias."<br>";
//echo "Comprobante componente".$comp_ganancias."<br>";
// Retension de SUSS
$total_suss = mysqli_query($amercado, $sql_suss) or die(mysqli_error($amercado));	
$totalRows_total_suss = mysqli_num_rows($total_suss);
$row_sus = mysqli_fetch_assoc($total_suss);
$monto_sus = $row_sus["importe"];
$fecha_sus = $row_sus["fechapago"];
$comp_sus = $row_sus["codchq"];
$fecha_suss = substr($fecha_sus,8,2)."-".substr($fecha_sus,5,2)."-".substr($fecha_sus,0,4);
//echo $totalRows_total_ing_brutos."<br>";
//echo $monto_sus."<br>";
//echo $fecha_sus."<br>";
$tot_reten = $monto_sus + $monto_ganancias +  $monto_ing_brutos  + $monto_iva ;
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
	$total_general = $cheques+$dola+$efectivo+$depositos+$monto_sus+$monto_ganancias+$monto_ing_brutos+$monto_iva;
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
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
}
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
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
            <td colspan="6" bgcolor="#0094FF" ><div align="center"><img src="images/ingres_cheques_terceros.gif" width="500" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#00CCFF"><div align="center"><img src="images/banco.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#00CCFF"><div align="center"></div></td>
            <td width="150" bgcolor="#00CCFF"><img src="images/num_cheque.gif" width="124" height="16" /></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/vencimientoi.gif" width="130" height="16" /></td>
            <td width="123" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
          </tr>
          <form id="cheques" name="cheque_form" method="post" action="carga_cheque_conB_i.php">
            <tr>
               <td width="140"><select name="banco" class="seleccion" id="banco">
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
               <td >                </td>
                <td width="150"><input name="numcheque" type="text" class="seleccion" id="numcheque" /></td>
                <td width="97"><div align="right">
                    <input name="venc" type="text" class="seleccion" id="venc" size="11" />
                </div></td>
                <td width="24"><a href="javascript:showCal('Calendar3')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
                <td><input name="importe" type="text" class="seleccion" id="importe" size="10" />                </td>
              </tr>

            <tr>
	           <input name="numfac"    type="hidden"  value="<?php echo $num_fact ?>"/>
	            <input name="codrem"    type="hidden"  value="<?php echo $num_remate ?>"/>
                <input name="fechaing"  type="hidden"  value="<?php echo $fechaing ?>"/>
                <input name="serie"     type="hidden"  value="6"/>
	            <input name="ncomp"     type="hidden"  value="<?php echo $comprobante ?>"/>	 
	            <input name="serierel"  type="hidden"  value="<?php echo $serie ?>" />
	            <input name="tcomprel"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <td width="150">&nbsp;</td>
              <td width="150"><input name="estado"    type="hidden"  value="P"/></td>
              <td width="150"><input name="tcomp"     type="hidden"  value="8"/></td>
              <td colspan="3"><div align="center">
			      <input name="Submit" type="submit" class="seleccion" value="Ingresar cheques" />
                         </div></td>
            </tr>
          </form>
        </table></td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
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
          <form id="cheques" name="deposito_form" method="post" action="carga_deposito_conB_i.php">
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
            <tr><input name="numfac2"    type="hidden"  value="<?php echo $num_fact ?>"/>
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
          <form id="form1" name="moneda" method="post" action="carga_monedaB_i.php">
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
			  <input name="numfac3"    type="hidden"  value="<?php echo $num_fact ?>"/>
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
          <form id="ing_pesos" name="ing_pesos" method="post" action="carga_pesos_conB_i.php">
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
			  <input name="numfac4"    type="hidden"  value="<?php echo $num_fact ?>"/>
			   <input name="codrem4"    type="hidden"  value="<?php echo $num_remate ?>"/>
              <input name="fechaing4"  type="hidden"  value="<?php echo $fechaing ?>"/>
              <input name="serie4"     type="hidden"  value="8"/>
              <input name="ncomp4"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel4"  type="hidden"  value="<?php echo $serie ?>" />
              <input name="tcomprel4"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado3"    type="hidden"  value="P"/>
              <input name="tcomp4"     type="hidden"  value="12"/>
              <td colspan="3"><div align="center">
                <input name="enviar" type="submit" class="seleccion" value="Ingresar pesos" />
              </div></td>
            </tr>
          </form>
        </table></td>
      </tr>
  <?php /*  if (($totalRows_total_ing_brutos==0) && ($totalRows_total_ing_brutos==0) && ($totalRows_total_ing_brutos==0) && ($totalRows_total_ing_brutos==0)) { */?>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
          <tr>
            <td height="24" colspan="7" bgcolor="#0094FF" ><div align="center" class="Estilo1">Retenciones</div></td>
          </tr>
          <tr>
            <td width="180" bgcolor="#00CCFF"><div align="center" class="Estilo1">Tipo</div>              <div align="center"></div></td>
            <td width="120" bgcolor="#00CCFF" class="Estilo1"><div align="center">Numero </div></td>
            <td colspan="2" bgcolor="#00CCFF" class="Estilo1"><div align="center">Fecha</div></td>
            <td colspan="2" bgcolor="#00CCFF"><img src="images/importes.gif" width="124" height="16" /></td>
            <td width="114" bgcolor="#00CCFF">&nbsp;</td>
          </tr>
		   <?php if ($totalRows_total_ing_brutos==0) {   ?>
          <form id="ing_brutos" name="ing_brutos" method="post" action="carga_retencionB_i.php">
          
		    <tr>
              <td><input name="ingresos_brutos" type="text" id="ing_brutos" size="30" value="Ingresos Brutos"/></td>
              <td width="120"><input name="comp_ing_brutos" type="text" size="20" /></td>
              <td width="136"><input name="fecha_depo_ing" type="text"  size="11" /></td>
              <td width="24"><a href="javascript:showCal('Calendar9')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
              <td width="123"><input name="importe_ing_brutos" type="text" id="importe_pesos2" size="15" /></td>
              <td colspan="2"><input name="enviar2" type="submit" class="seleccion" value="Ingresar Ing Brutos" /></td>
              </tr>
			  <input name="numfac"    type="hidden"  value="<?php echo $num_fact ?>"/>
			   <input name="codrem"    type="hidden"  value="<?php echo $num_remate ?>"/>
              
              <input name="serie"     type="hidden"  value="23"/>
              <input name="ncomp"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel"  type="hidden"  value="<?php echo $serie ?>" />
              <input name="tcomprel"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado"    type="hidden"  value="P"/>
              <input name="tcomp"     type="hidden"  value="41"/>
			</form>  <?php } ?>
			<?php if ($totalRows_total_ganancias==0) {   ?>
			<form id="ing_ganancias" name="ing_ganancias" method="post" action="carga_retencionB_i.php"> 
			<tr>
              <td><input name="ganancias" type="text" id="ganancias" size="30" value="Ganancias"/></td>
              <td width="120"><input name="comp_ganacias" type="text" id="comp_ganacias" size="20" /></td>
              <td><input name="fecha_depo_gan" type="text"  size="11" /></td>
              <td><a href="javascript:showCal('Calendar10')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
              <td width="123"><input name="importe_ganancias" type="text"  size="15" /></td>
              <td colspan="2"><input name="enviar22" type="submit" class="seleccion" value="Ingresar Ganancias" /></td>
              </tr>
			  <input name="numfac"    type="hidden"  value="<?php echo $num_fact ?>"/>
			   <input name="codrem"    type="hidden"  value="<?php echo $num_remate ?>"/>
              
              <input name="serie"     type="hidden"  value="24"/>
              <input name="ncomp"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel"  type="hidden"  value="<?php echo $serie ?>" />
              <input name="tcomprel"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado"    type="hidden"  value="P"/>
              <input name="tcomp"     type="hidden"  value="42"/>
			  </form> <?php } ?>
			  <?php if ($totalRows_total_iva==0) {   ?>
			  <form  name="ing_iva" method="post" action="carga_retencionB_i.php"> 
			<tr>
              <td><input name="iva" type="text" id="iva" size="30" value="Impuesto Valor Agregado"/></td>
              <td width="120"><input name="comp_iva" type="text" id="comp_iva" size="20" /></td>
              <td><input name="fecha_depo_iva" type="text"  size="11" /></td>
              <td><a href="javascript:showCal('Calendar11')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
              <td width="123"><input name="importe_iva" type="text" id="importe_iva" size="15" /></td>
			  <input name="numfac"    type="hidden"  value="<?php echo $num_fact ?>"/>
			   <input name="codrem"    type="hidden"  value="<?php echo $num_remate ?>"/>
              
              <input name="serie"     type="hidden"  value="22"/>
              <input name="ncomp"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel"  type="hidden"  value="<?php echo $serie ?>" />
              <input name="tcomprel"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado"    type="hidden"  value="P"/>
              <input name="tcomp"     type="hidden"  value="40"/>
              <td colspan="2"><input name="enviar222" type="submit" class="seleccion" value="Ingresar IVA" /></td>
              </tr>
			  </form> <?php } ?>
			  <?php if ($totalRows_total_suss==0) {   ?>
			  <form  name="ing_suss" method="post" action="carga_retencionB_i.php"> 
            <tr>
              <td><input name="suss" type="text" id="suss" size="30" value="S.U.S.S"/></td>
              <td width="120"><input name="comp_sus" type="text" id="comp_sus" size="20" /></td>
			  <td><input name="fecha_depo_suss" type="text"  size="11" /></td>
			  <td><a href="javascript:showCal('Calendar12')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
			  <input name="numfac"    type="hidden"  value="<?php echo $num_fact ?>"/>
			   <input name="codrem"    type="hidden"  value="<?php echo $num_remate ?>"/>
              
              <input name="serie"     type="hidden"  value="25"/>
              <input name="ncomp"     type="hidden"  value="<?php echo $comprobante ?>"/>
              <input name="serierel"  type="hidden"  value="<?php echo $serie ?>" />
              <input name="tcomprel"  type="hidden"  value="<?php echo $tip_comp ?>" />
              <input name="estado"    type="hidden"  value="P"/>
              <input name="tcomp"     type="hidden"  value="43"/>
              <td><input name="importe_sus" type="text" id="importe_sus" size="15" /></td>
              <td colspan="2"><input name="enviar2222" type="submit" class="seleccion" value="Ingresar S.U.S.S." /></td>
              </tr>
          </form> <?php } ?>
        </table></td>
      </tr> <?php /* } */?>
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
            <td colspan="2" bgcolor="#999966"><img src="images/fecha_dep_marro.gif" width="150" height="16" /></td>
            <td width="123" bgcolor="#999966"><img src="images/importes_marron.gif" width="70" height="16" /></td>
          </tr>
          <form id="cheques" name="cheque_list" method="post" >
		<?php $reg_ley = ""; 
		while ($registro = mysqli_fetch_array($total_deposito)){
   			$monto_total_cheques = $monto_total_cheques + $registro['2'];
   			$reg_ley = $reg_ley." - ".$registro[0]." , N�".$registro[1].", $".$registro[2]."  "; 
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
            <td colspan="5" bgcolor="#993333" ><div align="center"><img src="images/cheques_terc_marron.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="150" bgcolor="#999966"><div align="center"><img src="images/banco_marron.gif" width="48" height="16" /></div></td>
            <td width="150" bgcolor="#999966"><div align="center"></div></td>
            <td width="150" bgcolor="#999966"><img src="images/num_chq_marron.gif" width="135" height="16" /></td>
            <td bgcolor="#999966"><img src="images/fecha_vencimiento.gif" width="135" height="16" /></td>
            <td width="123" bgcolor="#999966"><div align="center"><img src="images/importes_marron.gif" width="70" height="16" /></div></td>
          </tr>
	
          <form id="form1" name="deposito_list" method="post" action="">
	<?php $reg_leyenda = "";
	while ($registro1 = mysqli_fetch_array($total_cheques)){
   		$precio1= $precio1 + $registro1['2'];
   		$reg_leyenda = $reg_leyenda." -".$registro1[0].", N�".$registro1[1]."  $".$registro1[2]." "; 

//$i = $i + 1;
?>
<tr>
<td width="150" bgcolor="#993333"><input name="textfield52" type="text" class="seleccion" value="<?php echo $registro1[0] ?>"/></td>
<td width="150" bgcolor="#993333"></td>
<td width="150" bgcolor="#993333"><input name="textfield2222" type="text" class="seleccion" value="<?php echo $registro1[1] ?>"></td>
<td bgcolor="#993333"><input name="fecha_venc2" type="text" class="seleccion" id="fecha_venc" size="13" value="<?php echo $registro1[3] ?>"/></td>
<td bgcolor="#993333"><input name="textfield422" type="text" class="seleccion" size="13" value="<?php echo $registro1[2] ?>" />              </td>      
        </tr>
		<?php } ?>
            <tr>
              <td bgcolor="#993333">&nbsp;</td>
              <td bgcolor="#993333">&nbsp;</td>
              <td colspan="2" bgcolor="#993333"><div align="right"><img src="images/total_cheques_marr.gif" width="136" height="26" /></div></td>
              <td bgcolor="#993333"><input name="total_deposito" type="text" class="seleccion" size="15"  value="<?php echo $precio1 ?>" onChange="deposito(this.form)"/></td>
            </tr></form>
          
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="3" bgcolor="#993333" ><div align="center"><img src="images/moneda_extranj.gif" width="400" height="24" /></div></td>
          </tr>   <tr> 
            <td width="639" bgcolor="#999966"><div align="left"><img src="images/concepto.gif" width="200" height="16" /></div>
              <div align="center"></div></td>
            <td colspan="2" bgcolor="#999966"><div align="right"><img src="images/importes_marron.gif" width="70" height="16" /></div></td> 
			</tr>
           
		  <?php while ($registro2 = mysqli_fetch_array($total_dolares)){
   $precio2= $precio2 + $registro2['0'];
    } 
	$tot_efectivo  =($efectivo + $precio2) ;
?>
            <tr>
              <td bgcolor="#993333"><input name="descripcion_dolares" type="text" class="seleccion" value="Total de Moneda Extranjera" size="30"/></td>
              <td width="101" colspan="2"   bgcolor="#993333"  align="right">
               <input align="right" name="importe_dol" type="text" class="seleccion"  size="13" value="<?php echo $precio2 ?>" />               </td>
              </tr>
            <tr>
              <td bgcolor="#993333"><input name="descripcion_pesos" type="text" class="seleccion" value="Total de Pesos" size="30"/></td><td width="101" colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos" type="text" class="seleccion"  size="13" value="<?php echo $efectivo ?>" /> </td>
              </tr>
            <tr>
              <td bgcolor="#993333"><div align="right"><img src="images/total_efectivo.gif" width="136" height="26" /></div></td>
              <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos" type="text" class="seleccion"  size="13" value="<?php echo $tot_efectivo ?>" /></td>
            </tr>
           
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td bgcolor="#ECE9D8">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td colspan="2" bgcolor="#ECE9D8"><table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#FFFFCC">
          <tr>
            <td colspan="5" bgcolor="#993333" ><div align="center"><img src="images/retensiones.gif" width="367" height="24" /></div></td>
          </tr>
          <tr>
            <td width="304" bgcolor="#999966"><div align="left"><img src="images/concepto.gif" width="200" height="16" /></div>
                <div align="center"></div></td>
            <td width="156" bgcolor="#999966"><img src="images/comprobante_marron.gif" width="112" height="16" /></td>
            <td width="100" bgcolor="#999966"><img src="images/fecha_maa.gif" width="100" height="16" /></td>
            <td colspan="2" bgcolor="#999966"><div align="right"><img src="images/importes_marron.gif" width="70" height="16" /></div></td>
          </tr>
          <?php while ($registro2 = mysqli_fetch_array($total_dolares)){
   $precio2= $precio2 + $registro2['0'];
    } 
	$tot_efectivo  =($efectivo + $precio2) ;
?>  <?php if ($totalRows_total_ing_brutos!=0) {   ?>
          <tr>
            <td bgcolor="#993333"><input  type="text" class="seleccion" value="Retencion de Ingresos Brutos" size="45"/></td>
            <td bgcolor="#993333"><input type="text" class="seleccion" name="textfield" value="<?php echo $comp_ing_brutos ?>" /></td>
            <td width="100" bgcolor="#993333"><input  name="textfield2" class="seleccion" type="text" size="11" value="<?php echo $fecha_ing_brutos ?>"/></td>
            <td width="145" colspan="2"   bgcolor="#993333"  align="right"><input align="right" name="importe_dol2" type="text" class="seleccion"  size="13" value="<?php echo $monto_ing_brutos ?>" />            </td>
          </tr> <?php } 
		   if ($totalRows_total_ganancias!=0) {   ?>  
          <tr>
            <td bgcolor="#993333"><input name="descripcion_pesos2" type="text" class="seleccion" value="Retenci&oacute;n Ganacias" size="45"/></td>
            <td bgcolor="#993333"><input type="text" value="<?php echo $comp_ganancias ?>" /></td>
            <td width="100" bgcolor="#993333"><input name="textfield22" type="text" size="11" value="<?php echo $fecha_ganancias ?>"/></td> 
            <td width="145" colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos2" type="text" class="seleccion"  size="13" value="<?php echo $monto_ganancias ?>" />            </td>
          </tr> <?php } 
		   if ($totalRows_total_iva!=0) {   ?>  
          <tr>
            <td bgcolor="#993333"><input name="descripcion_pesos2" type="text" class="seleccion" value="Impuesto Valor Agregado" size="45"/></td>
            <td bgcolor="#993333"><input type="text" name="textfield4" value="<?php echo $comp_iva ?>"/></td>
            <td width="100" bgcolor="#993333"><input name="textfield23" type="text" size="11" value="<?php echo $fecha_iva?>"/></td>
            <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos22" type="text" class="seleccion"  size="13" value="<?php echo $monto_iva ?>" /></td>
          </tr><?php }  if ($totalRows_total_suss!=0) {   ?>
          <tr>
            <td bgcolor="#993333"><input name="descripcion_pesos2" type="text" class="seleccion" value="Retencion del SUSS" size="45"/></td>
            <td bgcolor="#993333"><input type="text" name="textfield5" value="<?php echo $comp_sus ?>" /></td>
            <td width="100" bgcolor="#993333"><input name="textfield24" type="text" size="11" value="<?php echo $fecha_sus ?>"/></td>
            <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos23" type="text" class="seleccion"  size="13" value="<?php echo $monto_sus ?>" /></td>
          </tr><?php } ?>
          <tr>
            <td colspan="3" bgcolor="#993333"><div align="right"><img src="images/total_reten.gif" width="136" height="26" /></div></td>
            <td colspan="2"  bgcolor="#993333"  align="right"><input name="importe_pesos2" type="text" class="seleccion"  size="13" value="<?php echo $tot_reten ?>" /></td>
          </tr>
        </table></td>
        </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td bgcolor="#ECE9D8">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td width="292" bgcolor="#ECE9D8">&nbsp;</td>
        <td width="349" align="center" valign="middle"><div align="right">
          <img src="images/total_general_v.gif" width="136" height="26" />
          <input name="tot_gen" type="text" class="seleccion" value="<?php echo $total_general ?>" />
        </div></td>
      </tr> 
	
      <tr>
        <td><?php // ($efectivo + $precio2);
			 //  echo "Tot".$tot_efectivo."<br>" ;
			 // $tot_efectivo = 0 ;
			 $leyenda = "";
			 if ($tot_efectivo!=0 || $precio1!=0 || $monto_total_cheques!=0) {
	          	$leyenda = "Se abono con : \n"; 
			 }
			 if ($tot_efectivo!=0) {
	         	$leyenda = $leyenda." Efectivo : $".$tot_efectivo." \n";
			 }
			 if ($monto_total_cheques!=0) { //  $precio1 = 0 ;
			   	$leyenda = $leyenda." Dep�sito : ".$reg_ley."\n";
			  	$leyenda = $leyenda." Total de dep�sitos  $".$monto_total_cheques."\n";
			 }
			 //  $monto_total_cheques = 0 ;
			 if ($precio1!=0) {
	         	$leyenda = $leyenda." Cheques\n".$reg_leyenda."\n";
			  	$leyenda = $leyenda." Total de cheques  :$".$precio1."\n";
                                $leyenda = $leyenda." Retira contra acreditacion de cheques.\n";
			 }
			 if ($monto_sus!=0) {
	         	$leyenda = $leyenda." Ret. SUSS $".$monto_sus." Comp N".$comp_sus."\n";
			  //	$leyenda = $leyenda." Total de cheques  :$".$precio1."\n";
               //                 $leyenda = $leyenda." Retira contra acreditacion de cheques.\n";
			 }
			 if ($monto_ganancias!=0) {
	         	$leyenda = $leyenda." Ret. Ganacias $".$monto_ganancias." Comp N".$comp_ganancias."\n";
			  	
			 }
			 if ($monto_iva!=0) {
	         	$leyenda = $leyenda." Ret. SUSS $".$monto_iva." Comp N".$comp_iva."\n";
			  	
			 }
			 if ($monto_ing_brutos!=0) {
	         	$leyenda = $leyenda." Ret. SUSS $".$monto_ing_brutos." Comp N".$comp_ing_brutos."\n";
			  
			 }
			// echo $leyenda ;
			 //   if ($precio1!=0) {
			 //  $leyenda = $leyenda
	            
	  ?>
	  </td>
        <td colspan="2" rowspan="2" bgcolor="#ECE9D8"><div align="center"><img src="images/leyenda.gif" width="230" height="26" />          </div>          <form id="leyenda" name="leyenda" method="POST" action="<?php echo $editFormAction; ?>">
            <div align="center">
              <textarea name="leyendafc" cols="120" rows="10" class="seleccion"><?php echo $leyenda ?></textarea>
              <input name="tcomp" type="hidden" class="seleccion" value="<?php echo $tip_comp ?>" />
              <input name="serie" type="hidden" class="seleccion" value="<?php echo $serie ?>" />
              <input name="codrem" type="hidden" class="seleccion" value="<?php echo $num_remate ?>" />
              <input name="numfac"    type="hidden"  value="<?php echo $num_fact ?>"/>
              <input name="Submit3" type="submit" class="seleccion" value="Insertar Leyenda" />
              <input type="hidden" name="MM_insert" value="leyenda">
            </div>
          </form></td>
        </tr>
      <tr>
	  
	  
	  
        <td rowspan="2">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="2" bgcolor="#ECE9D8" align="center">&nbsp;</td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
        <td bgcolor="#ECE9D8">&nbsp;</td>
        <td align="center" valign="middle">&nbsp;</td>
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
