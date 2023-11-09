<?php require_once('Connections/amercado.php'); 
include_once "src/userfn.php";
?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "remate")) {

	$rematenum = GetSQLValueString($_POST['remate'], "int");
	$paisnum = GetSQLValueString($_POST['country'], "int");
	$provincianum = GetSQLValueString($_POST['city'], "int");
	$localidadnum = GetSQLValueString($_POST['university'], "int");
	$sello = $_POST['sello'];
	if (empty($sello)){
		$sello = 0 ;
	
	} else {
		$sello = 1;
	}
	$fechareal = $_POST['fecreal1'];
	$fecha_real ="'".substr($fechareal,6,4)."-".substr($fechareal,3,2)."-".substr($fechareal,0,2)."'";
	
  	$insertSQL = sprintf("INSERT INTO remates (tcomp, serie, ncomp, codcli, direccion, codpais, codprov, codloc, fecest, fecreal, imptot, cantlotes, horaest, horareal, observacion, tipoind , sello) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s , %s)",
                       	GetSQLValueString($_POST['tcomp'], "int"),
                       	GetSQLValueString($_POST['serie'], "int"),
		   				GetSQLValueString($_POST['remate'], "int"),
                       	GetSQLValueString($_POST['codcli'], "int"),
                       	GetSQLValueString($_POST['direccion'], "text"),
                       	GetSQLValueString($_POST['country'], "int"),
						GetSQLValueString($_POST['city'], "int"),
						GetSQLValueString($_POST['university'], "int"),
						$fecha_real,
						$fecha_real,
                       	GetSQLValueString($_POST['imptot'], "double"),
                       	GetSQLValueString($_POST['cantlotes'], "int"),
                       	GetSQLValueString($_POST['horareal'], "date"),
                        GetSQLValueString($_POST['horareal'], "date"),
                       	GetSQLValueString($_POST['observacion'], "text"),
                       	GetSQLValueString($_POST['tipoind'], "int"),
						$sello );


  	$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  	$remate = GetSQLValueString($_POST['remate'], "int");

  	$actualiza = "UPDATE series SET nroact='$remate' WHERE codnum='4'";
  	$actualiza_serie = mysqli_query($amercado, $actualiza) or die(mysqli_error($amercado));
  	if ($nuevo_remate == 1)
		$remate += 1;
  
  	if (isset($_SERVER['QUERY_STRING'])) {
    		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    		$insertGoTo .= $_SERVER['QUERY_STRING'];
  	}
	

}

mysqli_select_db($amercado, $database_amercado);
$query_tipo_industria = "SELECT * FROM tipoindustria";
$tipo_industria = mysqli_query($amercado, $query_tipo_industria) or die(mysqli_error($amercado));
$row_tipo_industria = mysqli_fetch_assoc($tipo_industria);
$totalRows_tipo_industria = mysqli_num_rows($tipo_industria);
?>
<?php 
$tcompdescrip= "REMATE";
$seriedescrip = "SERIE DE REMATE";
?>
<?php
mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT codnum, descripcion FROM tipcomp";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE tipoent = 1 AND activo = '1' ORDER BY razsoc ASC";
$cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

$colname_lotes = "-1";
if (isset($_POST['codrem'])) {
  	$colname_lotes = addslashes($_POST['codrem']);
}
mysqli_select_db($amercado, $database_amercado);
$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s", $colname_lotes);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);

$query_paises = "SELECT * FROM paises";
$paises = mysqli_query($amercado, $query_paises) or die(mysqli_error($amercado));
$row_paises = mysqli_fetch_assoc($paises);
$totalRows_paises = mysqli_num_rows($paises);

$query_serie = "SELECT * FROM series WHERE  codnum ='4'";
$serie = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));
$totalRows_serie = mysqli_num_rows($serie);

while ($row_serie = mysqli_fetch_array($serie)) {

	$remate = $row_serie['nroact']+1;

};

$ver_lotes = "SELECT lotes.codintlote, lotes.descor  FROM  lotes WHERE  (lotes.codrem = '$remate')";
$lotes2 = mysqli_query($amercado, $ver_lotes) or die(mysqli_error($amercado));
$row_lotes2 = mysqli_fetch_assoc($lotes2);
$totalRows_lotes2 = mysqli_num_rows($lotes2);

$cant_lotes = mysqli_num_rows($lotes2);

?>


<script language="javascript" src="cal2.js">
</script>
<script language="javascript" src="cal_conf2.js"></script>

<script type="text/javascript" src="../js/ajax.js"></script>    

<script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script> 
<script type="text/javascript">
DHTMLSuite.include("chainedSelect");
</script>
	
<script languaje="javascript">

function pasaValor(form)
{ 

	var valor = form1.remate.value;  // Nuemro de remate
	var tipoindus = form1.tipoind.value; // Tipo de industria
	var codcliente = form1.codcli.value; // Codigo de cliente
	var dire    = form1.direccion.value; // Direccion del remate
	var codigopais = form1.country.value; // Codigo del pais
	var codigoprov = form1.city.value; // Codigo de provincia
	var codigolocalidad = form1.university.value; // Codigo de Localidad
	var fecreal1 = form1.fecreal1.value; // Fecha real 
	var fecreal = form1.fecreal.value; // Fecha real 
	var horareal = form1.horareal.value; // Hora real
	var monedaelegida = form1.sello.value; // moneda ///
	var observaciones = form1.observacion.value;// Observaciones

	formulario2.carga.value = valor;
	formulario2.tipoindustria.value = tipoindus;
	formulario2.codigocliente.value = codcliente;
	formulario2.direccionremate.value = dire;
	formulario2.codigopais.value = codigopais;
	formulario2.codigoprovincia.value = codigoprov;
	formulario2.codigolocalidad.value = codigolocalidad ;
	formulario2.fechareal.value = fecreal ;
	formulario2.fechareal1.value = fecreal1 ;
	formulario2.horareal.value = horareal;
	formulario2.monedaelegida.value = monedaelegida ;
	formulario2.observacion.value = observaciones ;
	formulario2.submit()

}

function enviar_form() {
	formulario2.submit()

}

function VerificarFormulario(form)
{
	if(form.tipoind.value==""){
		alert("Falta el Tipo de Industria");
		form.tipoind.focus();
		return false;
	}
}

</script>
<script languaje="javascript">
function cambia_fecha1(form)

{ 
	var fecha2 = remate.fecreal1.value;
	var ano = fecha2.substring(6,10);
	var mes = fecha2.substring(3,5);
	var dia = fecha2.substring(0,2);
	var hora = remate.horareal.value;
	var fecha3 = ano+"-"+mes+"-"+dia+" "+hora;
	remate.fecreal.value = fecha3;

}
</script>
<script language="javascript" type="text/javascript">  
//Validacion de campos de texto no vacios 
//  
  
//*********************************************************************************  
// Funcion que valida que un campo contenga un string y no solamente un " "  
// Es tipico que al validar un string se diga  
//    if(campo == "") ? alert(Error)  
// Si el campo contiene " " entonces la validacion anterior no funciona  
//*********************************************************************************  
  
//busca caracteres que no sean espacio en blanco en una cadena  
function vacio(q) {  
        for ( i = 0; i < q.length; i++ ) {  
                if ( q.charAt(i) != " " ) {  
                        return true  
                }  
        }  
        return false  
}  
  
//valida que el campo no este vacio y no tenga solo espacios en blanco  
function ValidaCampos(F) {  
         
        if(vacio(F.tipoind.value) == false || vacio(F.codcli.value) == false || vacio(F.direccionremate.value) == false
	|| vacio(F.country.value) == false || vacio(F.city.value) == false   || vacio(F.university.value) == false 
        || vacio(F.fecreal1.value) == false || vacio(F.horareal.value) == false) {  
		
      		alert("Faltan campos obligatorios.")  
			$nuevo_remate = 0;
        	return false  
        } else {  
        	alert(" EL REMATE HA SIDO CORRECTAMENTE GRABADO")  
			$nuevo_remate = 1;
            //cambiar la linea siguiente por return true para que ejecute la accion del formulario  
            return true  
        }  
}  
</script>  
<?php 
/*
$remate1="";
$tipoindustria1="";
$codigocliente1="";
$direccionremate1="";
$codigopais1="";
$codigoprovincia1="";
$codigolocalidad1="";
$fechaestablecida ="";
$fechareal1 ="";
$fechareal ="";
$horaestablecida="";
$horareal="";
$monedaelegida1="";
$observacion1="";
$horareal1 = "";
*/
if(isset($_GET['carga']))
	$remate1=$_GET['carga']; 
if(isset($_GET['tipoindustria']))
	$tipoindustria1=$_GET['tipoindustria']; 
if(isset($_GET['codigocliente']))
	$codigocliente1=$_GET['codigocliente']; 
if(isset($_GET['direccionremate']))
	$direccionremate1=$_GET['direccionremate']; 
if(isset($_GET['codigopais']))
	$codigopais1=$_GET['codigopais'];
if(isset($_GET['codigoprovincia'])) 
	$codigoprovincia1=$_GET['codigoprovincia']; 
if(isset($_GET['codigolocalidad']))
	$codigolocalidad1=$_GET['codigolocalidad']; 
if(isset($_GET['fechaestablecida']))
	$fechaestablecida =$_GET['fechaestablecida']; 
if(isset($_GET['fechareal1']))
	$fechareal1 =$_GET['fechareal1']; 
if(isset($_GET['fechareal']))
	$fechareal =$_GET['fechareal']; 
if(isset($_GET['horaestablecida']))
	$horaestablecida=$_GET['horaestablecida']; 
if(isset($_GET['horareal']))
	$horareal=$_GET['horareal']; 
if(isset($_GET['monedaelegida']))
	$monedaelegida1=$_GET['monedaelegida']; 
if(isset($_GET['observacion']))
	$observacion1=$_GET['observacion']; 
if(isset($horareal))
	$horareal1 = $horareal;

if (isset($remate1) && !is_null($remate1)) {
 	$remate = $remate1 ;
}
if (isset($direccionremate1) && !is_null($direccionremate1)) {
 	$direccionremate = $direccionremate1 ;
}

?>
</head>
<link href="v_estilo_lotes.css" rel="stylesheet" type="text/css">
<body>
<?php if (isset($codigocliente1) && !is_null($codigocliente1)) {
	$resultado= mysqli_query("SELECT razsoc FROM entidades WHERE codnum = $codigocliente1");

	while ($registro = mysqli_fetch_row($resultado)){

       		foreach($registro  as $cliente){
			$nombre_cli = $cliente ;
 		}
	} 
}


if (isset($tipoindustria1) && !is_null($tipoindustria1)) {
	$resultado2= mysqli_query("SELECT nomre FROM tipoindustria WHERE codnum = $tipoindustria1");

	while ($registro2 = mysqli_fetch_row($resultado2)){

       		foreach($registro2  as $ind){
       			$industria = $ind ;

		}
	} 
}

if (isset($codigopais1) && !is_null($codigopais1)) {
	$resultado3= mysqli_query("SELECT descripcion FROM paises WHERE codnum = $codigopais1");
	$codpais = $codigopais1;
	while ($registro3 = mysqli_fetch_row($resultado3)){

       		foreach($registro3  as $pais){
       			$nombre_pais = $pais ;

 		}
	} 

	$resultado4= mysqli_query("SELECT descripcion FROM provincias WHERE ( codnum = $codigoprovincia1 AND codpais = $codigopais1)");
	$codprov = $codigoprovincia1;
	while ($registro4= mysqli_fetch_row($resultado4)){

       		foreach($registro4  as $provincia){
       			$nombre_provincia = $provincia ;

 		}
	} 

	$resultado5= mysqli_query("SELECT descripcion FROM localidades WHERE ( codnum = $codigolocalidad1 AND codpais = $codigopais1 AND codprov = $codigoprovincia1)");
	$codloc = $codigolocalidad1;
	while ($registro5 = mysqli_fetch_row($resultado5)){

       		foreach($registro5  as $localidad){
       			$nombre_localidad = $localidad ;

 		}
	} 
}


?>
 
<form id="form1" name="remate" method="POST" action="<?php echo $editFormAction; ?>" onSubmit="return ValidaCampos(form1);">
  <table width="640" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="30" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_mat.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td><table width="640" border="0" cellpadding="2" cellspacing="4" bgcolor="#000000">
          <tr class="ewTableAltRow">
            <td colspan="4" bgcolor="#ECE9D8">
		<table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#000066">
              <tr>
                <td bgcolor="#000066" ><span class="Estilo1"><b>&nbsp;<span class="Estilo2">Tipo</span></b></span></td>
                <td bgcolor="#000066"><input name="tipo_comprobante" class="ewTable" value="<?php echo $tcompdescrip ?>" readonly>
		<input name="tcomp"  type="hidden"class="ewTable" value="4" readonly></td>
                <td><span class="Estilo1"><b>&nbsp;<span class="Estilo2">Num Serie</span></b></span></td>
                <td><td bgcolor="#000066"><input name="tipo_comprobante" class="ewTable" value="<?php echo $seriedescrip ?>" readonly>             <input name="serie"  type="hidden"class="ewTable" value="4" readonly>
				</td>
                <td><span class="Estilo1" >&nbsp;<span class="Estilo2"><b>Comprobante</b></span></span> </td>
                <td><input name="remate" type="text" class="ewTable"  value="<?php echo $remate ?>" readonly/></td>
              </tr>
            </table></td>
          </tr>
		   <tr class="ewTableAltRow">  <?php if (isset($codigocliente1) && !is_null($codigocliente1)) { 
		  ?>
		  <input name="tipoind" type="hidden" class="ewTable" id="tipoind" size="40"  value="<?php echo $tipoindustria1 ?>"/>
		   <td width="89" class="ewTableHeader">Tipo de industria</td>
            <td colspan="3" width="157" class="ewTableHeader">
			<input name="industria" type="text" class="ewTable" name="industria" size="40"  value="<?php echo $industria ?>"  readonly=""/></td>
			
		   <?php } else { ?> 
		   
		   
		   
            <td width="89" class="ewTableHeader">&nbsp;Industria</td>
            <td colspan="3" class="ewTableHeader"><select name="tipoind" class="ewTable">
              <option value="">Tipo de industria</option>
              <?php
do {  
?>
              <option value="<?php echo $row_tipo_industria['codnum']?>"><?php echo $row_tipo_industria['nomre']?></option>
              <?php
} while ($row_tipo_industria = mysqli_fetch_assoc($tipo_industria));
  $rows = mysqli_num_rows($tipo_industria);
  if($rows > 0) {
      mysqli_data_seek($tipo_industria, 0);
	  $row_tipo_industria = mysqli_fetch_assoc($tipo_industria);
  }
?>
            
            </select>
            </td> <?php } ?>
           </tr>
		  
          <tr class="ewTableAltRow"><?php if (isset($codigocliente1) && !is_null($codigocliente1)) { 
		  ?>
		  <input name="codcli" type="hidden" class="ewTable" id="codcli" size="40"  value="<?php echo $codigocliente1 ?>"/>
		   <td width="89" class="ewTableHeader">&nbsp;Cliente</td>
            <td width="157" class="ewTableHeader">
		 <input name="cliente" type="text" class="ewTable" name="cliente" size="40"  value="<?php echo $nombre_cli ?>" readonly="" />
			
		   <?php } else { ?>
            <td width="89" class="ewTableHeader">&nbsp;Cliente</td>
            <td width="157" class="ewTableHeader"><select name="codcli" class="ewTable" id="codcli">
                <option value="">Seleccion cliente</option>
                <?php
do {  
?>
                <option value="<?php echo $row_cliente['codnum']?>"><?php echo $row_cliente['razsoc']?></option>
                <?php
} while ($row_cliente = mysqli_fetch_assoc($cliente));
  $rows = mysqli_num_rows($cliente);
  if($rows > 0) {
      mysqli_data_seek($cliente, 0);
	  $row_cliente = mysqli_fetch_assoc($cliente);
  }
?>
			  
              </select></td> <?php } ?>
            <td width="185" class="ewTableHeader"><span class="ewTableHeader">&nbsp;Direcci&oacute;n del remate</span></td>
            <td width="185" class="ewTableHeader"><input name="direccion" type="text" class="ewTable" id="direccionremate" size="40" /></td>
          </tr>
          
          <tr>
            <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/fondo_blanco.gif">
              <tr bgcolor="#000000">
                <td><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#000000">
                  <tr>
                    <td height="25" colspan="2" background="images/fecha.jpg"><div  class="Estilo1" align="center"><strong>Ubicaci&oacute;n</strong></div></td>
                    </tr>
                  <?php if (isset($codigopais1) && !is_null($codigopais1)) { 
			  ?><tr>
	<input name="country" type="hidden" class="ewTable" id="country" size="12"  value="<?php echo $codigopais1 ?>"/>
	<td width="89" class="ewTableHeader"><span class="Estilo2">Pa&iacute;s</span></td>
    <td  width="157" class="ewTableHeader">
	<input name="pais" type="text" class="ewTable" name="pais" size="17"  value="<?php echo $nombre_pais ?>"  readonly=""/>
	</td></tr>	
	<tr>
	<input name="city" type="hidden" class="ewTable" id="city" size="12"  value="<?php echo $codigoprovincia1 ?>"/>
	<td width="89" class="ewTableHeader"><span class="Estilo2">Provincia</span></td>
    <td  width="157" class="ewTableHeader">
	<input name="provincia" type="text" class="ewTable" name="provincia" size="17"  value="<?php echo $nombre_provincia ?>"  readonly=""/>
	</td></tr>		
	<tr>
	<input name="university" type="hidden" class="ewTable" id="university" size="40"  value="<?php echo $codigolocalidad1 ?>"/>
	<td width="89" class="ewTableHeader"><span class="Estilo2">Localidad</span></td>
    <td  width="157" class="ewTableHeader">
	<input name="localidad" type="text" class="ewTable" name="localidad" size="17"  value="<?php echo $nombre_localidad ?>"  readonly=""/>
	</td></tr>			
		   <?php } else { ?>
				  <tr>
                    <td class="ewTableHeader">&nbsp;<span class="Estilo2">Pa&iacute;s</span></td>
                    <td class="ewTableHeader"> <select id="country" class="ewTable">
     <option value="" >Seleccione país</option>
     <?php
do {  
?>
     <option  class="Estilo2" value="<?php echo $row_paises['codnum']?>"><?php echo $row_paises['descripcion']?></option>
     <?php
} while ($row_paises = mysqli_fetch_assoc($paises));
  $rows = mysqli_num_rows($paises);
  if($rows > 0) {
      mysqli_data_seek($paises, 0);
	  $row_paises = mysqli_fetch_assoc($paises);
  }
?> 
   </select> </td>
                  </tr>
                  <tr>
                    <td class="ewTableHeader"><span class="Estilo2">Provincia</span></td>
                    <td class="ewTableHeader"><select id="city" class="ewTable"></select>
                     </td>
                  </tr>
                  <tr>
                    <td class="ewTableHeader"><span class="Estilo2">Localidad</span></td>
                    <td class="ewTableHeader"><select id="university" class="ewTable"></select></td>
					
					
                  </tr> <?php } ?>
                </table></td>
                <td valign="top" bgcolor="#000000"><div align="center">
                  <table width="95%" border="0" cellpadding="0" cellspacing="1" bgcolor="#000000">
                    <tr bgcolor="#000000">
                      <td height="25" colspan="4" background="images/fecha.jpg"><div  class="Estilo1" align="center"><strong>&nbsp;Fechas</strong></div></td>
                    </tr>
                    
					<tr>

				    </tr>
                    <tr>
<?php if (isset($fechareal1) && !is_null($fechareal1)) { ?>					  
					  
<td width="22%" class="ewTableHeader"><span class="ewTableHeader">F. Real</span> </td>			   
<td width="21%" class="ewTableHeader"><input name="fecreal1" type="text" class="ewTable" id="fecreal1" size="11" value="<?php echo $fechareal1 ?>" readonly/></td><input name="fecreal" type="hidden" class="ewTable"  size="50" value="<?php echo $fechareal ?>"/>				
					<?php } else { ?>	 
<td class="ewTableHeader"><span class="ewTableHeader">F. Real</span></td>
<td valign="middle" class="ewTableHeader"><input name="fecreal1" type="text" class="ewTable" id="fecreal1" size="11" /><a href="javascript:showCal('Calendar2')"><img src="images/ew_calendar.gif" width="15" height="15" border="0"></a></td>
<input name="fecreal" type="hidden" class="ewTable"  size="50" />
<?php } ?>
<?php if (isset($horareal1) && !is_null($horareal1)) { ?>					  
                      <td class="ewTableHeader"><span class="Estilo2">Hora real.</span> </td>
                      <td class="ewTableHeader"><input name="horareal" type="text" class="ewTable" id="horareal" size="8" value="<?php echo $horareal ?>" readonly/></td>
                    <?php } else { ?>
					<td class="ewTableHeader"><span class="Estilo2">Hora real.</span> </td>
                      <td class="ewTableHeader"><input name="horareal" type="text" class="ewTable" id="horareal" size="8" onChange="cambia_fecha1(this.form)"/></td>
					<?php } ?>
				    </tr>
                  </table>
                </div></td>
                <td valign="top" bgcolor="#000000"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#000000">
                  <tr bgcolor="#000000">
                    <td height="25" colspan="4" background="images/fecha.jpg"><div  class="Estilo1" align="center"><strong>Sello</strong></div></td>
                  </tr>
                  <tr bgcolor="#000000"> 
			
				  <td class="ewTableHeader"><span class="Estilo2">Sello de confirmacion</span> </td>
                      <td class="ewTableHeader"><input name="sello" type="checkbox"     />
				  </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td  height="16"   background="images/separador.gif" ></td>
    </tr>
    <tr>
      <td><table width="640" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">
        <tr>
          <td width="50" height="29" valign="middle" align="center"  background="images/fonod_lote.jpg"><img src="images/lote_titulo.gif" width="38" height="20"></td>
          <td background="images/fonod_lote.jpg" valign="middle" align="center"><img src="images/descripcion_titulo.gif" width="100" height="20"></td>
          
          <td background="images/fonod_lote.jpg" colspan="2">&nbsp;</td>
        </tr>
        <tr>
		<?php $lotes2= mysqli_query($amercado, $ver_lotes);

$precio_base= 0;
$precio_final= 0;

while ($registro = mysqli_fetch_row($lotes2)){
//   $precio_base= $precio_base + $registro[3];
//   $precio_final= $precio_final + $registro[4];  
?>
		 <tr>
           <td width="50" bgcolor="#7982D1"><input name="secuencia" type="text" class="ewTable" id="secuencia" size="5" value="<?php echo $registro[0] ?>"/></td><td bgcolor="#7982D1" width="350"><input name="descor" type="text" class="ewTable" id="descor" size="78" value="<?php echo $registro[1] ?>"/></td><td>&nbsp;</td>
        </tr>
		<?php } ?>
           <tr>
          <td width="50" bgcolor="#7982D1"><input name="secuencia" type="text" class="ewTable" id="secuencia" size="5" /></td>
          <td bgcolor="#7982D1"><input name="descor2" type="text" class="ewTable" id="descor2" size="78"  /></td>
          <td align="center" bgcolor="#7982D1" colspan="2" ><img src="images/agregarlote.gif" class="ewAddOption" onClick="pasaValor(this.form)"></td>
        </tr> 
      </table></td>
    </tr>
	  <tr>
      <td height="16"   background="images/separador.gif"></td>
    </tr>
	  <tr>
	    <td><table width="640" border="0" cellpadding="1" cellspacing="1" bgcolor="#000066">
          <tr>
            <td height="25" colspan="3" align="center" valign="middle" background="images/fecha.jpg" ><strong>&nbsp;<span class="Estilo1">Observaciones</span></strong></td>
          </tr>
          <tr><?php if (isset($observacion1) && !is_null($observacion1))  
				  {  //echo $observacion1 ; ?>
            <td colspan="3"><textarea name="observacion" cols="70" rows="5" class="ewTable" id="observacion" ><?php echo $observacion1 ?></textarea></td>
            <?php } else { ?>
		  <td colspan="3"><textarea name="observacion" cols="70" rows="5" class="ewTable" id="observacion"></textarea></td>
		    <?php } ?>
		 </tr>
          <tr>
            <td width="20%" ><img src="images/total_lotes.jpg" width="131" height="33" /></td>
            <td width="22%" ><input name="cantlotes" type="text" class="ewTable" id="cantlotes" value="<?php echo $cant_lotes ?>"/></td>
            <td width="58%" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0099FF">
              <tr>
                <td height="25" colspan="2" background="images/fecha.jpg" align="center" ><strong><span class="Estilo1">Importes</span></strong></td>
              </tr>
			  <tr>
                <td class="ewTableHeader">&nbsp;<span class="Estilo2">Precio Base</span></td>
                <td><input name="preciobase" type="text" class="ewTable" value="<?php echo $precio_base ?>"/></td>
              </tr>
              <tr>
                <td class="ewTableHeader">&nbsp;<span class="Estilo2">Precio Obtenido</td>
                <td><input name="imptot" type="text" class="ewTable" id="imptot" value="<?php echo $precio_final ?>"/></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          
        </table></td>
    </tr>
	<tr>
      <td>
      <table width="640" border="0" cellspacing="1" cellpadding="1">
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        <td align="center"><input type="submit" name="Submit2" value="Grabar"  /></td>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      </table>
     <input type="hidden" name="MM_insert" value="remate">  
      
      </td>
</form>
		  <form name="formulario2" action="carga_lotes.php">
		  <input type="hidden"  name="carga" >
		  <input type="hidden"  name="tipoindustria" >
		  <input type="hidden"  name="codigocliente" >
		  <input type="hidden"  name="direccionremate" >
		  <input type="hidden"  name="codigopais" >
		  <input type="hidden" name="codigoprovincia" >
		  <input type="hidden" name="codigolocalidad" >
		  <input type="hidden"  name="fechaestablecida" >
		  <input type="hidden"  name="fechaestablecida1" >
		  <input type="hidden"  name="fechareal" >
		  <input type="hidden"  name="fechareal1" >
		  <input type="hidden"  name="horaestablecida" >
		  <input type="hidden"  name="horareal" >
		  <input type="hidden"  name="monedaelegida" >
		   <input type="hidden"  name="observacion" >
          <td></td>
		  </form>
    </tr>
 </table>
	  
<script type="text/javascript"> 

chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('country','city','includes/getCities.php'); 
chainedSelects.addChain('city','university','includes/getLocalidad.php'); 
chainedSelects.init(); 

</script> 

</body>
</html>
<?php
mysql_free_result($tipo_industria);
?>