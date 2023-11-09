<?php require_once('Connections/amercado.php'); 


$num_remate  = $_GET['remate'];
$tot_gen = $_GET['tot_gen'];
$serie  = $_GET['serie'] ;
$tip_comp = $_GET['tipo_comp'];
echo "Numero Remate ".$num_remate."<br>";
echo "Total general ".$tot_gen."<br>";
echo "Numero Serie ".$serie."<br>";
echo "Tipo componente ".$tip_comp."<br>";

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "cheque_form")) {
  $insertSQL = sprintf("INSERT INTO cartvalores (tcomp, serie, ncomp, codban, codsuc, importe, fechaemis, fechapago, tcomprel, serierel, estado, moneda, codrem) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['ncomp'], "int"),
                       GetSQLValueString($_POST['banco'], "int"),
                       GetSQLValueString($_POST['sucursal'], "int"),
                       GetSQLValueString($_POST['importe'], "double"),
                       GetSQLValueString($_POST['fechaing'], "date"),
                       GetSQLValueString($_POST['fechapago'], "date"),
                       GetSQLValueString($_POST['tcomprel'], "int"),
                       GetSQLValueString($_POST['serierel'], "int"),
                       GetSQLValueString($_POST['estado'], "text"),
                       GetSQLValueString($_POST['moneda'], "int"),
                       GetSQLValueString($_POST['codrem'], "int"));

  mysqli_select_db($amercado, $database_amercado);
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>
<?php require_once('Connections/amercado.php'); 
mysqli_select_db($amercado, $database_amercado);
$query_bancos = "SELECT * FROM bancos";
$bancos = mysqli_query($amercado, $query_bancos) or die(mysqli_error($amercado));
$row_bancos = mysqli_fetch_assoc($bancos);
$totalRows_bancos = mysqli_num_rows($bancos);

$query_sucursales = "SELECT * FROM sucbancos";
$sucursales = mysqli_query($amercado, $query_sucursales) or die(mysqli_error($amercado));
$row_sucursales = mysqli_fetch_assoc($sucursales);
$totalRows_sucursales = mysqli_num_rows($sucursales);

//mysqli_select_db($amercado, $database_amercado);
$query_serie = "SELECT * FROM series WHERE  codnum ='8'";
$serie = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));
//$row_serie = mysqli_fetch_array($serie);
$totalRows_serie = mysqli_num_rows($serie);
//echo $totalRows_serie;
while ($row_serie = mysqli_fetch_array($serie)) {
//echo $row_serie[5];
//echo $row_serie[nroact];
$comprobante = 1+$row_serie['nroact'];
echo $comprobante ;
};





$fechaing = date("Y-m-d");
//echo $fechaing;

// Comienza Cookie
//$datos = $_COOKIE['factura'];

//$datos1 = explode("@", $datos);
//echo count($datos1);
//for ($index =0 ; $index <count($datos1) ; $index++)
//{
  	 
//   if ($index = 1) 
 //    {
//	 $tcomp1 =$datos1[1] ;
	// echo $tcomp1."<br><br>";
	// $tcomp =explode("tcomp",$tcomp1);
//	$tcomp = explode("tcomp", $tcomp1);
//	$tip_comp = $tcomp[1];
//	echo "tipo comprobante".$tip_comp."<br>";
//	 }
//	 if ($index = 2) 
//     {
//	$serie1 =   $datos1[2];
//	$serie = explode("serie", $serie1);
	// echo $serie."<br>";
//	 $serie = $serie[1];
//	echo "Numero Serie".$serie."<br>";
//	 }
//	 if ($index = 3) 
//     {
//	 $remate_num1 =   $datos1[3];
//	 $remate_num = explode("remate_num", $remate_num1);
//	 $num_remate =  $remate_num[1];
//	 echo "Num reamte".$num_remate."<br>";
//	 }  	
//	 if ($index = 4) 
//     {
//	 $monto = $datos1[4];
//	 $tot_general = explode("tot_general", $monto);
//	 $tot_gen =  $tot_general[1];
//	  echo "Total Generale".$tot_gen."<br>";
//	 }   
//print ("$index. $datos1[$index].<br>\n");


//}

?>
<script language="javascript" src="cal2.js"></script>
<script language="javascript" src="cal_conf2.js"></script>
<script type="text/javascript" src="../js/ajax.js"></script>    
<script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
<script tpe="text/javascript">
DHTMLSuite.include("chainedSelect");
</script>


<script language="javascript">
function cambia_fecha(form)
{
var fecha  = cheque_form.venc.value ;
//alert (fecha);

var ano = fecha.substring(6,10);
var mes = fecha.substring(3,5);
var dia = fecha.substring(0,2);

var fecha3 = ano+"-"+mes+"-"+dia;
//alert (fecha3);
cheque_form.fechapago.value = fecha3 ;


}

</script>
<body>
<table width="440" border="0" align="center" cellpadding="1" cellspacing="1" bgcolor="#003366">
 <form id="cheques" name="cheque_form" method="POST" action="<?php echo $editFormAction; ?>">

  <tr> 
    <td colspan="3" bgcolor="#0094FF"  align="left"><img src="images/carga_cheques.gif" width="148" height="24" /> </td>
  <tr>
    <td width="200" bgcolor="#00CCFF" align="left"><img src="images/banco.gif" width="48" height="24" /></div></td>
    <td colspan="2" bgcolor="#00CCFF">
      <div align="left">
        <select id="banco" name="banco">
          <option value="">Seleccione Banco</option>
          <?php
                   do {  
                        ?>
          <option value="<?php echo $row_bancos['codnum']?>"><?php echo $row_bancos['nombre']?></option>
          <?php
                        } while ($row_bancos = mysqli_fetch_assoc($bancos));
                             $rows = mysqli_num_rows($bancos);
                              if($rows > 0) {
                                 mysqli_data_seek($bancos, 0);
	                            $row_bancos = mysqli_fetch_assoc($bancos);
                               }
                           ?>
        </select>
      </div></td>
  </tr>
  
 
    <tr>
      <td width="200" bgcolor="#00CCFF" align="left">
      <img src="images/sucursal.gif" width="64" height="24" /></td>
      <td width="141"><input name="moneda" type="hidden"  size="1" value="1" />
        <span class="ewTableHeader">
        <select name="sucursal" id="sucursal" >
        </select>
      </span></td>
      <td width="89"><?php echo $reamte ; ?></td>
    </tr>
    <tr>
      <td width="200" bgcolor="#00CCFF"><img src="images/num_cheque.gif" width="124" height="24" /></td>
      <td width="141"><input name="codchk" type="text"  size="15" /></td>
      <td width="89">&nbsp;</td>
    </tr>
    <tr>
      <td bgcolor="#00CCFF"><img src="images/vencimientoi.gif" width="130" height="24" /></td>
      <td width="141" align="left"><input name="venc" type="text" id="venc" size="11"  />
      <label><a href="javascript:showCal('Calendar3')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></label></td>
    </tr>
    <tr>
      <td bgcolor="#00CCFF"><div align="left"><img src="images/importes.gif" width="124" height="24" /></div></td>
      <td><input name="importe" type="text" id="importe" size="10" onchange="cambia_fecha(this.form)"/></td>
      <td width="89"><input name="estado" type="hidden"  size="1"  value="P"/></td>
    </tr>
    <tr>
      <td bgcolor="#003366"><input name="tcomp" type="hidden"  size="1"  value="8"/>;</td>
      <td><input name="codrem" type="hidden"  size="1"  value="<?php echo $num_remate ?>"/>
      <input name="fechaing" type="hidden"  size="11"  value="<?php echo $fechaing ?>"/>
      <input name="serie" type="hidden"  size="1"  value="6"/>
	  <input name="ncomp" type="hidden"  size="1"  value="<?php echo $comprobante ?>"/>	  </td>
	  
      <td width="89"><input name="serierel" type="hidden" value="<?php echo $serie ?>" /><input name="tcomprel" type="hidden" value="<?php echo $tip_comp ?>" /></td>
     </tr>
    <tr>
      <td bgcolor="#003366"><input name="fechapago" type="hidden" /></td>
      <td colspan="2"><input type="submit" name="Submit" value="Ingresar cheques" /></td>
    </tr>
    <input type="hidden" name="MM_insert" value="cheque_form">
  </form>
</table>
<script type="text/javascript"> 
//alert()
chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('banco','sucursal','includes/getsucural.php'); 
//chainedSelects.addChain('city','university','includes/getLocalidad.php'); 
chainedSelects.init(); 

</script> 
</body>
</html>
