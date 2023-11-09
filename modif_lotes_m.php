<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "src/userfn.php";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "f")) {
$codintnum  = $_POST['codintnum'];
$codintsublote =  $_POST['codintsublote'];
$codintlote = $codintnum.$codintsublote ;
  $updateSQL = sprintf("UPDATE lotes SET codrem=%s, codrubro=%s, preciobase=%s, preciofinal=%s, comiscobr=%s, comispag=%s, comprador=%s, descripcion=%s, descor=%s, observ=%s, secuencia=%s, codintnum=%s , codintsublote=%s , codintlote=%s WHERE codnum=%s",
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
                       GetSQLValueString($codintnum , "int"),
					   GetSQLValueString($codintsublote, "text"),
					   GetSQLValueString($codintlote , "text"),
                       GetSQLValueString($_POST['codnum'], "int"));
					   $carga1 = $_POST['codrem'];
  mysqli_select_db($amercado, $database_amercado);
  $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
					   
	$id_remate =	GetSQLValueString($_POST['idremate'], "int") ;	
	
	$rematenum = GetSQLValueString($_POST['codrem'], "int")."<br>" ;
//	echo GetSQLValueString($_POST['codintlote'], "text")."<br>" ;
//	echo GetSQLValueString($_POST['descripcion'], "text")."<br>" ;
				
						
$actualizaweb = sprintf("UPDATE remateweb SET   Lote=%s, Sublote=%s , Descrip=%s WHERE IdRemate ='$id_remate'",
                       GetSQLValueString($codintnum , "int"),
					   GetSQLValueString($codintsublote, "text"),
                       GetSQLValueString($_POST['descripcion'], "text"));			   

  mysqli_select_db($amercado, $database_amercado);
  $Result2 = mysqli_query($amercado, $actualizaweb) or die(mysqli_error($amercado));					   
					   

  mysqli_select_db($amercado, $database_amercado);
  $Result1 = mysqli_query($amercado, $actualizaweb) or die(mysqli_error($amercado));
  $carga1 = GetSQLValueString($_POST['codrem'], "int");               
  $updateGoTo = "modifremates.php?carga1=$carga1";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>

<link href="amercado_rick.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {color: #FFFFFF}
-->
</style>
<?php require_once('Connections/amercado.php'); 
if (isset($_GET['secuencia']))
	$secuencia = $_GET['secuencia'];
if (isset($_GET['carga']))
	$carga1 =$_GET['carga']; 
$tipoindustria =$_GET['tipoindustria']; 
if (isset($_GET['codigocliente']))
	$codigocliente =$_GET['codigocliente']; 
if (isset($_GET['direccionremate']))
	$direccionremate =$_GET['direccionremate']; 
if (isset($_GET['codigopais']))
	$codigopais =$_GET['codigopais']; 
if (isset($_GET['codigoprovincia']))
	$codigoprovincia  =$_GET['codigoprovincia']; 
if (isset($_GET['codigolocalidad']))
	$codigolocalidad =$_GET['codigolocalidad']; 
if (isset($_GET['fechaestablecida']))
	$fechaestablecida =$_GET['fechaestablecida']; 
if (isset($_GET['fechaestablecida1']))
	$fechaestablecida1 =$_GET['fechaestablecida1']; 
if (isset($_GET['fechareal']))
	$fechareal =$_GET['fechareal']; 
if (isset($_GET['fechareal1']))
	$fechareal1 =$_GET['fechareal1']; 
if (isset($_GET['horaestablecida']))
	$horaestablecida =$_GET['horaestablecida']; 
if (isset($_GET['horareal']))
	$horareal =$_GET['horareal']; 
if (isset($_GET['monedaelegida']))
	$monedaelegida =$_GET['monedaelegida']; 
if (isset($_GET['observacion']))
	$observacion =$_GET['observacion']; 
//echo $carga1;
//echo $secuencia;
//echo $horaestablecida."<br>";
//echo $horareal."<br>";





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
$query_cantidad_lotes = "SELECT * FROM lotes WHERE lotes.codrem = '$carga1' AND lotes.secuencia = '$secuencia'";
$cantidad_lotes = mysqli_query($amercado, $query_cantidad_lotes) or die(mysqli_error($amercado));
$row_cantidad_lotes = mysqli_fetch_assoc($cantidad_lotes);
$totalRows_cantidad_lotes = mysqli_num_rows($cantidad_lotes);
$codigo_rubro = $row_cantidad_lotes['codrubro'];

$lote_web = $row_cantidad_lotes['codintnum'];
$sublote_web =  $row_cantidad_lotes['codintsublote'];
//echo "LOTE WEB".$lote_web."<br>";
//echo "Remate".$carga1."<br>";;
mysqli_select_db($amercado, $database_amercado);
$query_cantidad_lotes = "SELECT * FROM remateweb WHERE remateweb.Remate = '$carga1' AND remateweb.Lote = '$lote_web' AND remateweb.Sublote  = '$sublote_web'";
$cantidad_lotes_web = mysqli_query($amercado, $query_cantidad_lotes) or die(mysqli_error($amercado));
$row_lotes_web = mysqli_fetch_assoc($cantidad_lotes_web);

$ID_Rem = $row_lotes_web['IdRemate'];
//echo "IDE REMATE=".$ID_Rem;
//echo "iD REM ".$ID_Rem."<br>";;
//echo "Cant Lotes".$totalRows_cantidad_lotes;
//$secuencia = $totalRows_cantidad_lotes + 1;
?>

</head>

<body>
<form action="<?php echo $editFormAction; ?>" id="formulario1" name="f"   method="POST">
  <table width="600" border="0" align="center" cellpadding="1" cellspacing="1">
   <tr>
      <td height="30" background="images/fondo_titulos.jpg" colspan="2"><div align="center"><img src="images/carga_lotes.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%">
        <tr>
          <td colspan="2" align="right" valign="middle" bgcolor="#7982D1" class="ewTableHeader">Remate N 
            <div align="left"><input name="codnum" type="hidden" id="codrem" value="<?php echo $row_cantidad_lotes['codnum']; ?>" size="5"/></div></td><input name="idRemate1" type="hidden" id="idRemate" value="<?php echo $ID_Rem ; ?>" size="5"/>
          <td bgcolor="#7982D1" class="ewTableHeader" align="left"><input name="codrem" type="text" id="codrem" value="<?php echo $carga1 ?>" size="5"/></td>
           <td bgcolor="#7982D1" class="ewTableHeader" align="right">Lote N. </td>
          <td bgcolor="#7982D1" class="ewTableHeader" align="left">
            <input name="codintnum" type="text" id="codintlote" size="8" maxlength="8" value="<?php echo $row_cantidad_lotes['codintnum']; ?>"/></td>
		   <td bgcolor="#7982D1" class="ewTableHeader" align="right">Sublote</td>
          <td bgcolor="#7982D1" class="ewTableHeader"  align="left">
            <input name="codintsublote" type="text" id="codintsublote" size="8" maxlength="8" value="<?php echo $row_cantidad_lotes['codintsublote']; ?>"/>         </td>
        </tr><input name="tipoindustria" type="hidden" value="<?php echo $tipoindustria ?>"  />
        <tr><input name="idremate" type="hidden" value="<?php echo $ID_Rem ?>"  />
          <td bgcolor="#7982D1" class="ewTableHeader" align="center" colspan="7">Descripci&oacute;n para factura </td>
		  <input name="comispag" type="hidden" class="ewTable" id="comispag" value="10"/>
        <input name="comiscobr" type="hidden" class="ewTable" id="comiscobr"  value="10"/>
        </tr><input name="fecha_real" type="hidden"  size="4" value="<?php echo $fechareal ?>" />
        <tr><input name="hora_real" type="hidden"  size="4" value="<?php echo $horareal ?>" />
          <td width="50" bgcolor="#7982D1"colspan="7" ><input name="secuencia" type="hidden" id="secuencia" size="4" value="<?php echo $secuencia ?>" /><input name="descor" type="text" class="ewTable" id="descor" value="<?php echo $row_cantidad_lotes['descor']; ?>" size="100" /></td>
		  </tr></table>	  </td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td height="20" align="center" class="ewTableHeader">&nbsp;<strong>Descripci&oacute;n para cat&aacute;logo </strong></td>
          </tr>
        <tr>
          <td><textarea name="descripcion" cols="100" rows="3" class="ewTable" id="descripcion"><?php echo $row_cantidad_lotes['descripcion']; ?></textarea></td>
          </tr>
		   <tr>
          <td height="20" align="center" class="ewTableHeader"><label>Precio Obenido&nbsp;&nbsp;&nbsp;<input type="text" name="preciofinal" class="ewTable" value="<?php echo $row_cantidad_lotes['preciofinal']; ?>" </label></td>
        </tr>
        
        <tr>
          <td height="20" align="center" class="ewTableHeader"><strong>Observaciones</strong></td>
        </tr>
        <tr>
          <td><textarea name="observ" cols="100" rows="3" class="ewTable" id="observ"><?php echo $row_cantidad_lotes['observ']; ?></textarea></td>
        </tr>

      </table></td>
    </tr>
   
    <tr>
      <td width="312">&nbsp; </td>
      <td width="321"><div align="center">
	  <input type="submit" name="cmdGuardaryNuevo" value="Guardar">
        </div></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  
  
  <input type="hidden" name="MM_update" value="f">
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
