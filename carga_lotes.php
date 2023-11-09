<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "src/userfn.php";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "f")) {
//$codintlote = GetSQLValueString($_POST['codintlote'], "text");
//print_r($_POST);
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
					   GetSQLValueString($_POST['fecha_real'], "date"),
					    GetSQLValueString($_POST['hora_real'], "date"),
                       GetSQLValueString($codintnum, "int"),
					   GetSQLValueString($codintsublote, "text"),
                       GetSQLValueString($_POST['descripcion'], "text"));
   mysqli_select_db($amercado, $database_amercado);
  $Result2 = mysqli_query($amercado, $insertWEB) or die(mysqli_error($amercado));
  $carga1= GetSQLValueString($_POST['codrem'], "int");
  $insertGoTo = "cargaremates.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
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
var cargarem = cargaremates.prueba.value
alert(document.cargaremates.prueba.value);
alert(cargaremates.prueba.value);
document.cargareamates.submit()

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
$monedaelegida =$_GET['monedaelegida']; 
$observacion =$_GET['observacion']; 
//echo $fechareal;
//echo $horareal;
//echo $horaestablecida."<br>";
//echo $horareal."<br>";





mysqli_select_db($amercado, $database_amercado);
$query_ruibros = "SELECT * FROM rubros";
$ruibros = mysqli_query($amercado, $query_ruibros) or die(mysqli_error($amercado));
$row_ruibros = mysqli_fetch_assoc($ruibros);
$totalRows_ruibros = mysqli_num_rows($ruibros);

mysqli_select_db($amercado, $database_amercado);
$query_comprador = "SELECT * FROM entidades  WHERE entidades.tipoent = ' 2'";
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
          <td align="right" valign="middle" bgcolor="#7982D1" class="ewTableHeader">Remate N 
            <div align="left"></div></td>
          <td bgcolor="#7982D1" class="ewTableHeader" align="left"><input name="codrem" type="text" id="codrem" value="<?php echo $carga1 ?>" size="5"/></td>
          <td bgcolor="#7982D1" class="ewTableHeader" align="center">Lote N. </td>
          <td bgcolor="#7982D1" class="ewTableHeader" align="center"><div align="left">
            <input name="codintnum" type="text" id="codintlote" size="6" maxlength="6" />
          </div></td>
          <td bgcolor="#7982D1" class="ewTableHeader" align="center">Letra</td>
          <td bgcolor="#7982D1" class="ewTableHeader" align="center"><input name="codintsublote" type="text" id="sublote" size="3" /></td>
        </tr><input name="tipoindustria" type="hidden" value="<?php echo $tipoindustria ?>"  />
        <tr>
          <td colspan="6" align="center" bgcolor="#7982D1" class="ewTableRow">Descripci&oacute;n para factura </td>
		  </tr><input name="fecha_real" type="hidden"  size="4" value="<?php echo $fechareal ?>" />
         <tr><input name="hora_real" type="hidden"  size="4" value="<?php echo $horareal ?>" />
          <td bgcolor="#7982D1"colspan="6" ><input name="secuencia" type="hidden" id="secuencia" size="4" value="<?php echo $secuencia ?>" /><input name="descor" type="text" class="ewTable" id="descor" size="100" />            <input name="preciofinal" type="hidden" id="preciofinal" size="15" /></td>
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
    <tr>
      <td colspan="2" valign="top"></td>
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

<form name="cargaremates" action="cargaremates.php" >
          <input type="hidden"  name="carga" value="<?php echo $carga1 ?>" >
		  <input type="hidden"   name="prueba" value="HOla prueba"  />
		  <input type="hidden"   name="tipoindustria"  value="<?php echo $tipoindustria ?>">
		  <input type="hidden"   name="codigocliente" value="<?php echo $codigocliente ?>">
		  <input type="hidden"   name="direccionremate" value="<?php echo $direccionremate ?>">
		  <input type="hidden"   name="codigopais" value="<?php echo $codigopais ?>">
		  <input type="hidden"  name="codigoprovincia" value="<?php echo $codigoprovincia ?>">
		  <input type="hidden"  name="codigolocalidad" value="<?php echo $codigolocalidad ?>">
		  <input type="hidden"   name="fechaestablecida" value="<?php echo  $fechaestablecida ?>">
		   <input type="hidden"   name="fechaestablecida1" value="<?php echo  $fechaestablecida1 ?>">
		  <input type="hidden"   name="fechareal" value="<?php echo $fechareal ?>">
		  <input type="hidden"   name="fechareal1" value="<?php echo $fechareal1 ?>">
		  <input type="hidden"   name="horaestablecida" value="<?php echo $horaestablecida ?>">
		  <input type="hidden"   name="horareal" value="<?php echo $horareal ?>">
		  <input type="hidden"   name="monedaelegida" value="<?php echo $monedaelegida ?>">
		  <input type="hidden"   name="observacion" value="<?php echo $observacion ?>">
</form>
</body>
</html>
<?php
//mysql_free_result($codrubro);
mysql_free_result($ruibros);
mysql_free_result($comprador);
mysql_free_result($cantidad_lotes);
?>
