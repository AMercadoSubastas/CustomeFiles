<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "src/userfn.php";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "f")) {
 $codintnum  = $_POST['codintnum'];
$codintsublote =  $_POST['codintsublote'];
$codintlote = $codintnum.$codintsublote ;
  $insertSQL = sprintf("INSERT INTO lotes (codrem, codrubro, preciobase, preciofinal, comiscobr, comispag, comprador, descripcion, descor, observ, secuencia,codintlote , codintnum ,codintsublote ) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s , %s , %s , %s)",
                       GetSQLValueString($_POST['codrem'], "int"),
                       GetSQLValueString($_POST['codrubro'], "int"),
					   GetSQLValueString($_POST['preciobase'], "double"),
                       GetSQLValueString($_POST['preciofinal'], "double"),
                       GetSQLValueString($_POST['comiscobr'], "double"),
                       GetSQLValueString($_POST['comispag'], "double"),
                       GetSQLValueString($_POST['comprador'], "int"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['descor'], "text"),
                       GetSQLValueString($_POST['observ'], "text"),
                       GetSQLValueString($_POST['secuencia'], "int"),
                       GetSQLValueString($codintlote, "text"),
					   GetSQLValueString($codintnum, "int"),
					   GetSQLValueString($codintsublote, "text"));
  mysqli_select_db($amercado, $database_amercado);
  $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

$insertWEB = sprintf("INSERT INTO remateweb  (Remate , FechaRemate , HoraRemate, Lote, Sublote , Descrip ) VALUES (%s, %s, %s, %s , %s, %s)",
                       GetSQLValueString($_POST['codrem'], "int"),
					   GetSQLValueString($_POST['fechareal'], "date")  , 
					   GetSQLValueString($_POST['horareal'], "text"),
					   GetSQLValueString($codintnum, "int"),
					   GetSQLValueString($codintsublote, "text"),
                       GetSQLValueString($_POST['descripcion'], "text"));
   mysqli_select_db($amercado, $database_amercado);
  $Result2 = mysqli_query($amercado, $insertWEB) or die(mysqli_error($amercado));
$carga1 = $_POST['codrem'];

  $insertGoTo = "modifremates.php?carga1=$carga1";
 // if (isset($_SERVER['QUERY_STRING'])) {
//    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
 //   $insertGoTo .= $_SERVER['QUERY_STRING'];
 // }
   header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<script language="javascript">
function pasaValor(form)

{ 
//alert ("Hola");
//var cargarem = cargaremates.prueba.value
//alert(document.cargaremates.prueba.value);
//alert(cargaremates.prueba.value);
//document.modremates.submit()

}
</script>
<link href="amercado_rick.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<?php require_once('Connections/amercado.php'); 

$carga1 =$_GET['carga']; 
$tipoindustria =$_GET['tipoindustria']; 
$codigocliente =$_GET['codigocliente']; 
$direccionremate =$_GET['direccionremate']; 
$codigopais =$_GET['codigopais']; 
$codigoprovincia  =$_GET['codigoprovincia']; 
$codigolocalidad =$_GET['codigolocalidad']; 
$fechaestablecida =$_GET['fechaestablecida']; 
$fechaestablecida1 =$_GET['fechaestablecida1']; 
$fechareal =$_GET['fechareal']; 
$fechareal1 =$_GET['fechareal1']; 
$horaestablecida =$_GET['horaestablecida']; 
$horareal =$_GET['horareal']; 
$monedaelegida =1; 
$observacion =$_GET['observacion']; 

//echo "Fecha Est 1".$fechaestablecida1."<br>";
//echo "Fecha Est ".$fechaestablecida."<br>";
//echo "Fecha Real 1".$fechareal."<br>";
//echo "Fecha real ".$fechareal1."<br>";

//echo $horareal."<br>";
$fechareal1 = substr($fechareal1,6,4)."-".substr($fechareal1,3,2)."-".substr($fechareal1,0,2);
//echo "Fecha real ".$fechareal1."<br>";



mysqli_select_db($amercado, $database_amercado);
$query_ruibros = "SELECT * FROM rubros";
$ruibros = mysqli_query($amercado, $query_ruibros) or die(mysqli_error($amercado));
$row_ruibros = mysqli_fetch_assoc($ruibros);
$totalRows_ruibros = mysqli_num_rows($ruibros);

mysqli_select_db($amercado, $database_amercado);
$query_comprador = "SELECT * FROM entidades ";
$comprador = mysqli_query($amercado, $query_comprador) or die(mysqli_error($amercado));
$row_comprador = mysqli_fetch_assoc($comprador);
$totalRows_comprador = mysqli_num_rows($comprador);

mysqli_select_db($amercado, $database_amercado);
$query_cantidad_lotes = "SELECT * FROM lotes WHERE lotes.codrem = '$carga1'";
$cantidad_lotes = mysqli_query($amercado, $query_cantidad_lotes) or die(mysqli_error($amercado));
$row_cantidad_lotes = mysqli_fetch_assoc($cantidad_lotes);
$totalRows_cantidad_lotes = mysqli_num_rows($cantidad_lotes);

//echo "Cant Lotes".$totalRows_cantidad_lotes;
$secuencia = $totalRows_cantidad_lotes + 1;
?>
<?php //echo $carga1."<br>";
//echo $tipoindustria."<br>";
//echo $codigocliente."<br>";
//echo $direccionremate."<br>";
//echo $codigopais."<br>";
//echo $codigoprovincia."<br>";
//echo $codigolocalidad."<br>";
//echo $fechaestablecida."<br>";
//echo $fechareal."<br>";
//echo $horaestablecida."<br>";
//echo $horareal."<br>";
//echo $monedaelegida."<br>";
//echo $observacion."<br>";
//echo $carga1;
?>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" id="formulario1" name="f"   method="POST">
  <table width="640" border="0" align="center" cellpadding="1" cellspacing="1">
   <tr>
      <td height="30" background="images/fondo_titulos.jpg" colspan="2"><div align="center"><img src="images/carga_lotes.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" cellpadding="1" cellspacing="1">
        <tr>
          <td width="80" align="right" valign="middle" bgcolor="#7982D1" class="ewTableHeader">Remate N</td>
          <td bgcolor="#7982D1" valign="middle" class="ewTableHeader" align="left"><input name="codrem" type="text" id="codrem" value="<?php echo $carga1 ?>" size="5"/></td>
		  <input name="fechareal" type="hidden" id="fechareal" value="<?php echo $fechareal1 ?>" />
		  <input name="horareal"  type="hidden" id="horareal" value="<?php echo $horareal ?>" />
          <td bgcolor="#7982D1" class="ewTableHeader" align="right">Lote N. </td>
          <td bgcolor="#7982D1" class="ewTableHeader" align="left">
            <input name="codintnum" type="text" id="codintlote" size="8" maxlength="8" /></td>
		   <td bgcolor="#7982D1" class="ewTableHeader" align="right">Sublote</td>
          <td bgcolor="#7982D1" class="ewTableHeader"  align="left">
            <input name="codintsublote" type="text" id="codintsublote" size="8" maxlength="8" />
         </td>
        </tr><input name="tipoindustria" type="hidden" value="<?php echo $tipoindustria ?>"  />
        <tr>
          <td bgcolor="#7982D1" class="ewTableHeader" align="center" colspan="6">Descripci&oacute;n para factura </span></td>
		  </tr>
        <tr>
          <td colspan="6" bgcolor="#7982D1" ><input name="secuencia" type="hidden" id="secuencia" size="4" value="<?php echo $secuencia ?>" /><input name="descor" type="text" class="ewTable" id="descor" size="120" /></td>
		  </tr></table>	  </td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td height="20" align="center" class="ewTableHeader">&nbsp;<strong>Descripci&oacute;n para cat&aacute;logo </strong></td>
          </tr>
        <tr>
          <td><textarea name="descripcion" cols="120" rows="3" class="ewTable" id="descripcion"></textarea></td>
          </tr>
        <tr>
          <td height="20" align="center" class="ewTableHeader"><strong>Observaciones</strong></td>
        </tr>
        <tr><input name="comispag" type="hidden" class="ewTable" id="comispag" value="10"/>
        <input name="comiscobr" type="hidden" class="ewTable" id="comiscobr"  value="10"/>
          <td><textarea name="observ" cols="120" rows="3" class="ewTable" id="observ"></textarea></td>
        </tr>

      </table></td>
    </tr>
    
            
          </table></td>
          <td width="50%" colspan="2" valign="top" bgcolor="#009999"></td>
          </tr>
        <tr>
          <td colspan="4" background="images/separador2.gif">&nbsp;</td>
          </tr>
        
      </table></td>
    </tr>
    <tr>
      <td width="312">&nbsp; </td>
      <td width="321"><div align="center">
	  <input type="submit" name="cmdGuardaryNuevo" value="Guardar">
        </div></td>
    </tr>
  </table>
  
  <input type="hidden" name="MM_insert" value="f">
</form>

<form name="modremates" action="modifremates.php"  method="post">
          
		  <input type="hidden"  name="remate_num" value="<?php echo $carga1 ?>" >
		
		 
</form>
</body>
</html>
<?php
//mysql_free_result($codrubro);
mysql_free_result($ruibros);
mysql_free_result($comprador);
mysql_free_result($cantidad_lotes);
?>
