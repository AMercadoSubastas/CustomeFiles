
<?php 

 require_once('Connections/amercado.php'); 
 include_once "src/userfn.php";
 mysqli_select_db($amercado, $database_amercado);
 /*
 include_once "ewcfg50.php" ;
 include_once "ewmysql50.php" ;
 include_once "phpfn50.php" ; 
 include_once  "userfn50.php" ;
 include_once "usuariosinfo.php" ; 
 include "header.php" ;
*/
	if (isset($_GET['carga1'])) {
		$remate =  $_GET['carga1'];
  		$res = mysqli_query("select * from remates where ncomp='$remate'") or die(mysqli_error($amercado));
  		if($inf = mysqli_fetch_array($res)){
    		$direccion = $inf['direccion'] ; 
			$codpais =   $inf['codpais'] ; 
    		$tipo_ind =	$inf['tipoind'];
			$codprov  = $inf['codprov'] ; 
			$codloc  = $inf['codloc'] ; 
			$fecest  = $inf['fecreal'] ; 
			$fecreal = $inf['fecreal'];    
			$num_cliente = $inf['codcli']; 
			$hora_real = substr($inf['horareal'],0,5) ;
			$num_pais = $inf['codpais'];
			$num_prov = $inf['codprov'];
			$num_loc = $inf['codloc'];
			$sello = $inf['sello'];
			$paises= mysqli_query("SELECT descripcion FROM paises WHERE (  codnum = '$num_pais')");

			while ($registrop= mysqli_fetch_row($paises)){

       			foreach($registrop  as $paises_nom){
       				$nombre_pais = $paises_nom ;

 				}
			} 
	
			$resultado4= mysqli_query("SELECT descripcion FROM provincias WHERE ( codnum = $num_prov AND codpais = '$num_pais')");

			while ($registro4= mysqli_fetch_row($resultado4)){

       			foreach($registro4  as $provincia){
       				$nombre_provincia = $provincia ;
					//  echo $nombre_provincia."<br>";
 				}
			} 

			$resultado5= mysqli_query("SELECT descripcion FROM localidades WHERE ( codnum = $num_loc AND codpais = '1' AND codprov = $num_prov)");
			// PONER UN IF ACA POR SI EL CAMPO ES NULO
			while ($registro5 = mysqli_fetch_row($resultado5)){

       			foreach($registro5  as $localidad){
       				$nombre_localidad = $localidad ;
					//  echo $nombre_localidad."<br>";
 				}
			} 

     		$hora_est = substr($inf['horaest'],0,5) ;   
	             
			$fecha_remate = substr($fecreal,8,2)."-".substr($fecreal,5,2)."-".substr($fecreal,0,4) ;

  		} 

	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["select_lote"]))) {
	$fechareal = $_POST['fecreal1'];
	$fecha_real ="'".substr($fechareal,6,4)."-".substr($fechareal,3,2)."-".substr($fechareal,0,2)."'";
	
	$ids="";
	$j = 0;
	foreach($_POST['select_lote'] as $id) { 
		$j++;
		$ids.=((int)$id).",";
	}
	$ids=substr($ids,0,-1); 
	echo "$IDS = (".$ids.") ";
	
	// Aca modifico los lotes seleccionados y les pongo el nuevo nro de remate
	mysqli_select_db($amercado, $database_amercado);
	$query_serie_new = "SELECT * FROM series WHERE  codnum = '4'";
	$serie_new = mysqli_query($amercado, $query_serie_new) or die(mysqli_error($amercado));
	$row_serie_new = mysqli_fetch_array($serie_new);
	$totalRows_serie_new = mysqli_num_rows($serie_new);
	$sello1 = $_POST['sello'];
	$remate_new = 1 + $row_serie_new[nroact] ;
	// Grabo la nueva cabecera con los mismos datos del remate
	$insert_rematenewSQL = sprintf("INSERT INTO remates (tcomp, serie, ncomp, codcli, direccion, codpais, codprov, codloc, fecest, fecreal, imptot, cantlotes, horaest, horareal, observacion, tipoind,sello) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       	GetSQLValueString($_POST['tcomp'], "int"),
                       	GetSQLValueString($_POST['serie'], "int"),
			$remate_new,
                       	GetSQLValueString($_POST['codcli'], "int"),
                       	GetSQLValueString($_POST['direccion'], "text"),
                       	GetSQLValueString($_POST['country'], "int"),
                       	GetSQLValueString($_POST['city'], "int"),
                       	GetSQLValueString($_POST['university'], "int"),
                       	$fecha_real,
			$fecha_real,
                       	GetSQLValueString($_POST['imptot'], "double"),
                       	$j,
                       	GetSQLValueString($_POST['horareal'], "date"),
                       	GetSQLValueString($_POST['horareal'], "date"),
                       	GetSQLValueString($_POST['observacion'], "text"),
                       	GetSQLValueString($_POST['tipoind'], "int"),
			GetSQLValueString($_POST['sello'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$Result_rematenew = mysqli_query($amercado, $insert_rematenewSQL) or die(mysqli_error($amercado));
			// Ahora hago el update de los lotes seleccionados con el nuevo remate

	$updatelotesSQL = sprintf("UPDATE  lotes SET codrem = %s WHERE codrem = '$remate' AND secuencia IN ($ids)", 
								$remate_new);
	$Result_lotesnew   = mysqli_query($amercado, $updatelotesSQL) or die(mysqli_error($amercado));
	// Actualizo el ultimo numero de la serie de remates
	$actualiza = "UPDATE series SET nroact='$remate_new' WHERE codnum='4'";
  	$actualiza_serie = mysqli_query($amercado, $actualiza) or die(mysqli_error($amercado));
	
	// Regrabo el remate actual
	
	$updateremateSQL = sprintf("UPDATE  remates SET tcomp = %s, serie = %s, ncomp = %s, codcli = %s, direccion = %s, codpais = %s, codprov = %s, codloc = %s, fecest = %s, fecreal = %s, imptot = %s, cantlotes = %s, horaest = %s, horareal = %s, observacion = %s, tipoind = %s, sello = %s WHERE ncomp = '$remate'",
                       	GetSQLValueString($_POST['tcomp'], "int"),
                       	GetSQLValueString($_POST['serie'], "int"),
			GetSQLValueString($_POST['remate'], "int"),
					   //$remate,
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
		   	GetSQLValueString($_POST['sello'], "int"));

  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateremateSQL) or die(mysqli_error($amercado));

}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "remate_form1")) { 
	if (isset($_POST['sello']))
   		$sello1 = $_POST['sello'];
   	if ($sello1=='on') {
	   	$sello = 1 ;
     } else {
		$sello = 0 ;
	}
	$fechareal = $_POST['fecreal1'];
	$fecha_real ="'".substr($fechareal,6,4)."-".substr($fechareal,3,2)."-".substr($fechareal,0,2)."'";   

 	$updateremateSQL = sprintf("UPDATE  remates SET tcomp = %s, serie = %s, ncomp = %s, codcli = %s, direccion = %s, fecest = %s, fecreal = %s, imptot = %s, cantlotes = %s, horaest = %s, horareal = %s, observacion = %s, tipoind = %s, sello = %s WHERE ncomp = '$remate'",
        	GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
		GetSQLValueString($_POST['remate'], "int"),
		GetSQLValueString($_POST['codcli'], "int"),
                GetSQLValueString($_POST['direccion'], "text"),
		$fecha_real,
		$fecha_real,
                GetSQLValueString($_POST['imptot'], "double"),
                GetSQLValueString($_POST['cantlotes'], "int"),
                GetSQLValueString($_POST['horareal'], "date"),
                GetSQLValueString($_POST['horareal'], "date"),
                GetSQLValueString($_POST['observacion'], "text"),
                GetSQLValueString($_POST['tipoind'], "int"),
		$sello );


  	mysqli_select_db($amercado, $database_amercado);
  	$Result1 = mysqli_query($amercado, $updateremateSQL) or die(mysqli_error($amercado));
  	$remate = GetSQLValueString($_POST['remate'], "int");
  	$insertGoTo = "index.php";
  	if (isset($_SERVER['QUERY_STRING'])) {
    		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    		$insertGoTo .= $_SERVER['QUERY_STRING'];
  	}
  	?>
 	<script language="javascript">
  	window.location('index.php');
  	</script>
  	<?php //esta es la llave que cierra el if del MM_insert
}

mysqli_select_db($amercado, $database_amercado);
$query_tipo_industria = "SELECT * FROM tipoindustria";
$tipo_industria = mysqli_query($amercado, $query_tipo_industria) or die(mysqli_error($amercado));
$row_tipo_industria = mysqli_fetch_assoc($tipo_industria);
$totalRows_tipo_industria = mysqli_num_rows($tipo_industria);
?>
<?php $tcompdescrip= "REMATE";
$seriedescrip = "SERIE DE REMATE";
// ver lotes

mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT codnum, descripcion FROM tipcomp";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades  WHERE activo = '1' ORDER BY razsoc ASC";
$cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);


$colname_lotes = "-1";
if (isset($_POST['codrem'])) {
  	$colname_lotes = addslashes($_POST['codrem']);
}
mysqli_select_db($amercado, $database_amercado);
$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum", $colname_lotes);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);


$query_paises = "SELECT * FROM paises";
$paises = mysqli_query($amercado, $query_paises) or die(mysqli_error($amercado));
$row_paises = mysqli_fetch_assoc($paises);
$totalRows_paises = mysqli_num_rows($paises);

$query_serie = "SELECT * FROM series WHERE  codnum = '4'";
$serie = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));

$totalRows_serie = mysqli_num_rows($serie);

while ($row_serie = mysqli_fetch_array($serie)) {

}


$maxRows_ver_lotes = 500;
$pageNum_ver_lotes = 0;

$startRow_ver_lotes = $pageNum_ver_lotes * $maxRows_ver_lotes;

mysqli_select_db($amercado, $database_amercado);
$query_ver_lotes = "SELECT lotes.secuencia, lotes.codintlote , lotes.descor FROM lotes  WHERE  lotes.codrem='$remate' ORDER BY codintnum , codintsublote"  ;

$ver_lotes = mysqli_query($amercado, $query_ver_lotes) or die(mysqli_error($amercado));

$total_lotes =  mysqli_num_rows($ver_lotes);
if (isset($_POST['totalRows_ver_lotes']))
	if (isset($startRow_ver_lotes)) {
  		$totalRows_ver_lotes = $_POST['totalRows_ver_lotes'];
	} else {
  		$all_ver_lotes = mysqli_query($amercado, $query_ver_lotes);
  		$totalRows_ver_lotes = mysqli_num_rows($all_ver_lotes);
	}

if (isset($totalRows_ver_lotes))
	$totalPages_ver_lotes = ceil($totalRows_ver_lotes/$maxRows_ver_lotes)-1;

//session_start(); // Initialize session data
//ob_start(); // Turn on output buffering
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
	var valor = remate_form1.remate.value;  // Nuemro de remate
	var tipoindus = remate_form1.tipoind.value; // Tipo de industria
	var codcliente = remate_form1.codcli.value; // Codigo de cliente
	var dire    = remate_form1.direccion.value; // Direccion del remate
	var fechaesta1 = remate_form1.fecreal1.value;  // Fecha establecida
	var fechaesta = remate_form1.fecreal.value;  // VAlor Mysql
	var fecreal1 = remate_form1.fecreal1.value; // Fecha real 
	var fecreal = remate_form1.fecreal.value; // Fecha real 
	var horaest = remate_form1.horareal.value; // Hora establecida 
	var horareal = remate_form1.horareal.value; // Hora real
	var sello = remate_form1.sello.value; // moneda 
	var observaciones = remate_form1.observacion.value;// Observaciones

	formulario2.carga.value = valor;
	formulario2.tipoindustria.value = tipoindus;
	formulario2.codigocliente.value = codcliente;
	formulario2.direccionremate.value = dire;
	formulario2.fechareal.value = fecreal ;
	formulario2.fechareal1.value = fecreal1 ;
	formulario2.horareal.value = horareal;
	formulario2.sello.value = sello ;
	formulario2.observacion.value = observaciones ;
	formulario2.submit();
}

function enviar_form() {
//alert("enviar FORM2");
	formulario2.submit();


}
</script>

<script language="javascript">

function cambia_fecha(form)

{ 
	//alert("cambia fecha");
	var fecha = remate_form.fecest1.value;
	var ano = fecha.substring(6,10);
	var mes = fecha.substring(3,5);
	var dia = fecha.substring(0,2);

	var hora = remate_form.horaest.value;
	//alert (ano);
	//alert (mes);
	//alert (dia);
	var fecha1 = ano+"-"+mes+"-"+dia+" "+hora;
	//alert (fecha1);
	//alert(fecha + hora) ;
	remate_form.fecest.value = fecha1;

}

function cambia_fecha1(form)

{ 
	//alert("cambia fecha1");
	var fecha2 = remate_form.fecreal1.value;
	var ano = fecha2.substring(6,10);
	var mes = fecha2.substring(3,5);
	var dia = fecha2.substring(0,2);

	var hora = remate_form.horareal.value;
	//alert (ano);
	//alert (mes);
	//alert (dia);
	var fecha3 = ano+"-"+mes+"-"+dia+" "+hora;
	//alert (fecha3);
	//alert(hora) ;
	remate_form.fecreal.value = fecha3;

}
</script>
<!-- DESDE ACA ============================================================= !--> 
<script type="text/javascript" src="AJAX/ajax.js"></script>
<script type="text/javascript">
// Remate
var ajax = new sack();
var currentRemateID=false;
	
function getRemateData()
{
	var remateId = document.getElementById('remate').value.replace(/[^0-9]/g,'');
		alert ("remateID"+remateId);
	if( remateId!=currentRemateID){
		alert ("Dento IF");
		currentRemateID = remateId;
		//alert("mando el valor remateId"+remateId)
		//ajax.requestFile = 'getRemateMod.php?getRemateId='+remateId; // Specifying which file to get
		ajax.onCompletion = showRemateData;	// Specify function that will be executed after file has been found
		ajax.runAJAX();		// Execute AJAX function			
	}
}
function showRemateData()
{
	var formObj = document.forms['remate_form1'];	
	//alert("mando el valor formObj"+formObj);
	alert(ajax.response);
	eval(ajax.response);
}
function initFormEvents()
{	
	//alert("inicializo eventos");
	document.getElementById('remate_form1').onblur = getRemateData;
	document.getElementById('remate_form1').focus();
}
window.onload = initFormEvents;
</script>

<!-- HASTA ACA ==============================================================!--> 


<?php 
if (isset($_GET['carga']))
	$remate1=$_GET['carga']; 
if (isset($_GET['tipoindustria']))
	$tipoindustria1=$_GET['tipoindustria']; 
if (isset($_GET['codigocliente']))
	$codigocliente1=$_GET['codigocliente']; 
if (isset($_GET['direccionremate']))
	$direccionremate1=$_GET['direccionremate']; 
if (isset($_GET['codigopais']))
	$codigopais1=$_GET['codigopais']; 
if (isset($_GET['codigoprovincia']))
	$codigoprovincia1=$_GET['codigoprovincia']; 
if (isset($_GET['codigolocalidad']))
	$codigolocalidad1=$_GET['codigolocalidad']; 
if (isset($_GET['fechareal1']))
	$fechaestablecida1 =$_GET['fechareal1']; 
if (isset($_GET['fechareal']))
	$fechaestablecida =$_GET['fechareal']; 
if (isset($_GET['fechareal1']))
	$fechareal1 =$_GET['fechareal1']; 
if (isset($_GET['fechareal']))
	$fechareal =$_GET['fechareal']; 
if (isset($_GET['horareal']))
	$horaestablecida=$_GET['horareal']; 
if (isset($_GET['horareal']))
	$horareal=$_GET['horareal']; 
if (isset($_GET['sello']))
	$sello1=$_GET['sello'];
if (isset($_GET['observacion']))
	$observacion1=$_GET['observacion']; 
//echo "Remate ".$remate1; 

//echo "HORA ESTAB".$horaestablecida."<br>";
//echo "Hora real". $horareal."<br>";

if (isset($horaestablecida))
	$horaestablecida1 = $horaestablecida;
if (isset($horareal))
	$horareal1 = $horareal;
if (isset($remate1) && !is_null($remate1)) {
	$remate = $remate1 ;
}
if (isset($direccionremate1) && !is_null($direccionremate1)) {
 	$direccionremate = $direccionremate1 ;
}

?>



</head>

<link href="estilo_lotes.css" rel="stylesheet" type="text/css">
<body>
<?php /*
 if (!is_null($codigocliente1)) {
	$resultado= mysqli_query("SELECT razsoc FROM entidades WHERE codnum = $codigocliente1");

	while ($registro = mysqli_fetch_row($resultado)){

		foreach($registro  as $cliente){
       		$nombre_cli = $cliente ;
 		}
	} 
} 

/*if (!is_null($monedaelegida1)) {
	$resultado1= mysqli_query("SELECT descor FROM monedas WHERE codnum = $monedaelegida1");

	while ($registro1 = mysqli_fetch_row($resultado1)){

       	foreach($registro1  as $mon){
       		$moneda = $mon ;
			//   echo $moneda."<br>";
 		}
	} 
} 
/*
if (!is_null($tipo_ind)) {
    echo "Tipo ind  ".$tipo_ind ;
	$resultado2= mysqli_query("SELECT nomre FROM tipoindustria WHERE codnum = $tipo_ind");

	while ($registro2 = mysqli_fetch_row($resultado2)){

       	foreach($registro2  as $ind){
		    
       		$industria = $ind ;
			echo "Tipo ind TXT  ".$industria;
	   		// echo $industria."<br>";
 		}
	} 
}

if (!is_null($codigopais1)) {
	$resultado3= mysqli_query("SELECT descripcion FROM paises WHERE codnum = $codigopais1");

	while ($registro3 = mysqli_fetch_row($resultado3)){

       	foreach($registro3  as $pais){
       		$nombre_pais = $pais ;
			// echo $nombre_pais ;
 		}
} 



$resultado5= mysqli_query("SELECT descripcion FROM localidades WHERE ( codnum = $codigolocalidad1 AND codpais = $codigopais1 AND codprov = $codigoprovincia1)");
// PONER UN IF ACA POR SI EL CAMPO ES NULO
while ($registro5 = mysqli_fetch_row($resultado5)){

       	foreach($registro5  as $localidad){
       		$nombre_localidad = $localidad ;
			//   echo $nombre_localidad ;
 		}
} 

}/*/

?>
<?php //
//if(!isset($_POST['cmdGuardar']) && !isset($_POST['cmdGuardaryNuevo'])){ ?>
<form id="form12" name="remate_form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="640" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td height="30" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_mat.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td><table width="640" border="0" cellpadding="2" cellspacing="4" bgcolor="#000000">
          <tr class="ewTableAltRow">
            <td colspan="4" bgcolor="#ECE9D8"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#000066">
              <tr>
                <td bgcolor="#000066" ><span class="Estilo1"><b>&nbsp;<span class="Estilo2">Tipo</span></b></span></td>
                <td bgcolor="#000066"><input name="tipo_comprobante" class="ewTable" value="<?php echo $tcompdescrip ?>" readonly>
				<input name="tcomp"  type="hidden"class="ewTable" value="4" readonly></td>
                <td><span class="Estilo1"><b>&nbsp;<span class="Estilo2">Num Serie</span></b></span></td>
                <td><td bgcolor="#000066"><input name="tipo_comprobante" class="ewTable" value="<?php echo $seriedescrip ?>" readonly>             <input name="serie"  type="hidden"class="ewTable" value="4" readonly>
				</td>
                <td><span class="Estilo1" >&nbsp;<span class="Estilo2"><b>Comprobante</b></span></span> </td>
                <td><input name="remate" type="text" class="ewTable" value="<?php echo $remate ?>"/></td>
              </tr>
            </table></td>
          </tr>
		   <tr class="ewTableAltRow">  <?php if (isset($codigocliente1) && !is_null($codigocliente1)) { 
		  // echo $codigocliente1;
		 // echo $nombre_cli ;
		  
		  ?>
		  <input name="tipoind" type="hidden" class="ewTable" id="tipoind" size="40"  value="<?php echo $tipoindustria1 ?>"/>
		   <td width="89" class="ewTableHeader">Tipo de industria</td>
            <td colspan="3" width="157" class="ewTableHeader">
			<input name="industria" type="text" class="ewTable" name="industria" size="40"  value="<?php echo $industria ?>"  readonly="" />
			
		   <?php } else { ?> 
            <td width="89" class="ewTableHeader">&nbsp;Industria</td>
            <td colspan="3" class="ewTableHeader"><select name="tipoind" class="ewTable">
              <option value="">Tipo de industria</option>
              <?php
do {  
          if ($row_tipo_industria['codnum']== $tipo_ind) { ?>
              <option value="<?php echo $row_tipo_industria['codnum']?>"  selected="selected"><?php echo $row_tipo_industria['nomre']?></option>
			  
        <?php } else {  ?>
			  <option value="<?php echo $row_tipo_industria['codnum']?>"><?php echo $row_tipo_industria['nomre']?></option>
			  
			<?php }
              
} while ($row_tipo_industria = mysqli_fetch_assoc($tipo_industria));
  $rows = mysqli_num_rows($tipo_industria);
  if($rows > 0) {
      mysqli_data_seek($tipo_industria, 0);
	  $row_tipo_industria = mysqli_fetch_assoc($tipo_industria);
  }
?>
            
            </select>            </td> <?php } ?>
           </tr>
		  
          <tr class="ewTableAltRow"><?php if (isset($codigocliente1) && !is_null($codigocliente1)) { 
		  // echo $codigocliente1;
		  // echo $nombre_cli ;
		  
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
do { if( $row_cliente['codnum']==$num_cliente ) {
?>
                <option value="<?php echo $row_cliente['codnum']?>" selected="selected" ><?php echo $row_cliente['razsoc']?></option> <?php } else { ?>
				
				<option value="<?php echo $row_cliente['codnum']?>" ><?php echo $row_cliente['razsoc']?></option>
				
				<?php }
               
} while ($row_cliente = mysqli_fetch_assoc($cliente));
  $rows = mysqli_num_rows($cliente);
  if($rows > 0) {
      	mysqli_data_seek($cliente, 0);
	$row_cliente = mysqli_fetch_assoc($cliente);
  }
?>
  
              </select></td> <?php } ?>
            <td width="58" class="ewTableHeader"><span class="ewTableHeader">&nbsp;Direcci&oacute;n</span></td>
            <td class="ewTableHeader" >
              <input name="direccion" type="text" class="ewTable" id="direccion"  value="<?php if (isset($direccion)) echo $direccion ?>" size="40"/>
            </td>
          </tr>
          
          <tr>
            <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/fondo_blanco.gif">
              <tr>
                <td bgcolor="#000000"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#000000">
                  <tr>
                    <td height="25" colspan="2" background="images/fecha.jpg"><div  class="Estilo1" align="center"><strong>Ubicaci&oacute;n</strong></div></td>
                    </tr>
        <tr><input name="country" type="hidden" class="ewTable" name="country" size="17"  value="<?php echo $row_cliente['codpais'];  ?>"  />
	<input name="city" type="hidden" class="ewTable" name="text" size="17"  value="<?php echo $codprov ;  ?>"  />
	<input name="university" type="hidden" class="ewTable" name="text" size="17"  value="<?php echo $codloc;  ?>"  />
	<td width="89" class="ewTableHeader"><span class="Estilo2">Pa&iacute;s</span></td>
    <td  width="157" class="ewTableHeader">
	<input name="pais" type="text" class="ewTable" name="pais" size="17"  value="<?php echo $nombre_pais  ?>"  readonly=""/>	</td></tr>	
	<tr>
	
	<td width="89" class="ewTableHeader"><span class="Estilo2">Provincia</span></td>
    <td  width="157" class="ewTableHeader">
	<input name="provincia" type="text" class="ewTable" name="provincia" size="17"  value="<?php echo $nombre_provincia ?>"  readonly=""/>	</td></tr>		
	<tr>
	
	<td width="89" class="ewTableHeader"><span class="Estilo2">Localidad</span></td>
    <td  width="157" class="ewTableHeader">
	<input name="localidad" type="text" class="ewTable" name="localidad" size="17"  value="<?php echo $nombre_localidad ?>"  readonly=""/>	</td></tr>			
		  
    
                </table></td>
                <td valign="top" bgcolor="#000000">
                  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#000000">
                    <tr>
                      <td height="25" colspan="4" background="images/fecha.jpg"><div  class="Estilo1" align="center"><strong>&nbsp;Fechas</strong></div></td>
                    </tr>
                    
					<tr>

<?php if (isset($fechareal1) && !is_null($fechareal1)) { ?>					  
					  
<td width="22%" class="ewTableHeader"><span class="ewTableHeader">F. Real</span> </td>			   
<td width="21%" class="ewTableHeader"><input name="fecreal1" type="text" class="ewTable" id="fecreal1" size="11" value="<?php echo $fecha_remate ?>" readonly/></td><input name="fecreal" type="hidden" class="ewTable"  size="50" />				
					<?php } else { ?>	 
<td class="ewTableHeader"><span class="ewTableHeader">F. Real</span></td>
<td valign="middle" class="ewTableHeader"><input name="fecreal1" type="text" class="ewTable" id="fecreal1" size="11" value="<?php echo $fecha_remate ?>"/><a href="javascript:showCal('Calendar7')"><img src="images/ew_calendar.gif" width="15" height="15" border="0"></a></td>
<input name="fecreal" type="hidden" class="ewTable"  size="50" />
<?php } ?>
<?php if (isset($horareal1) && !is_null($horareal1)) { ?>					  
                      <td class="ewTableHeader"><span class="Estilo2">Hora real.</span> </td>
                      <td class="ewTableHeader"><input name="horareal" type="text" class="ewTable" id="horareal" size="6" value="<?php echo $hora_real ?>" readonly/></td>
                    <?php } else { ?>
					<td class="ewTableHeader"><span class="Estilo2">Hora real.</span> </td>
                      <td class="ewTableHeader"><input name="horareal" type="text" class="ewTable" id="horareal" size="6" value="<?php echo $hora_real ?>" onChange="cambia_fecha1(this.form)"/></td>
					<?php } ?>
				    </tr>
                  </table>
                </td>
                <td valign="top" bgcolor="#000000"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#000000">
                  <tr bgcolor="#000000">
                    <td height="25" colspan="4" background="images/fecha.jpg"><div  class="Estilo1" align="center"><strong>Sello</strong></div></td>
                  </tr>
                  <tr bgcolor="#000000"> 
			
				  <td class="ewTableHeader"><span class="Estilo2">Sello de confirmacion</span></td>
                  <?php if (isset($sello) && $sello==1) { 
				//  echo "SELLO".$sello;
				  ?>
                      <td class="ewTableHeader"><input name="sello" type="checkbox" class="ewTable" id="sello"  value="1" checked /> 
					  
					  </td><?php } else { 
					//  echo "SELLO 2 ".$sello;
					  ?><td class="ewTableHeader"><input name="sello" type="checkbox"  />
					  
					  </td><?php } ?> 
             
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
	  <?php $precio_base = 0;
 $precio_final = 0;

 ?>
        <tr><td width="50" background="images/fonod_lote.jpg" align="center">L </td>
          <td width="50" height="29" valign="middle" align="center"  background="images/fonod_lote.jpg"><img src="images/lote_titulo.gif" width="38" height="20"></td>
          <td background="images/fonod_lote.jpg" valign="middle" align="center"><img src="images/descripcion_titulo.gif" width="100" height="20"></td>
         
		  <td background="images/fonod_lote.jpg"><img src="images/editar_txt.gif" width="37" height="20"></td>
        </tr> 
	<?php $j = 0; 
	 while ($row_ver_lotes = mysqli_fetch_row($ver_lotes)){
	  $j++;
	  //$precio_base= $precio_base + $row_ver_lotes[3];
      //$precio_final= $precio_final + $row_ver_lotes[4];  
//	 echo $row_ver_lotes;
 //$precio_base= $precio_base + $row_ver_lotes[3];
// $precio_final= $precio_final + $row_ver_lotes[4];   ?>
			<tr><td bgcolor="#7982D1"><input name="select_lote[]" type="checkbox"  value="<?php echo $row_ver_lotes[0]; ?>" ></td>
          <td width="50" bgcolor="#7982D1"><input name="secuencia" type="text" class="ewTable" id="secuencia" size="5" value="<?php echo $row_ver_lotes[1]; ?>"/></td>
          <td bgcolor="#7982D1" width="350"><input name='descor' type='text' class='ewTable' id='descor' size='88' value='<?php echo $row_ver_lotes[2]; ?>' /></td>
          </td> <td bgcolor="#7982D1"><a href="modif_lotes_m.php?carga=<?php echo $remate ?>&secuencia=<?php echo $row_ver_lotes[0]; ?>&tipoindustria=<?php echo $tipo_ind ?>&codigocliente <?php echo $num_cliente ?>&fecharemate=<?php echo $fecha_remate; ?>&horareal=<?php echo $hora_real; ?>"><img src="images/editar_dib.gif" width="19" height="20" border="0"></a></td>
        </tr>
		<?php } ?>
		
		 <tr>
		  <td bgcolor="#7982D1" colspan="2">&nbsp;</td>
          
		  <td align="left" bgcolor="#7982D1" colspan="2"><img src="images/agregarlote.gif" class="ewTable" onClick="pasaValor(this.form)"></td>
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
            <td width="22%" ><input name="cantlotes" type="text" class="ewTable" id="cantlotes" value="<?php echo $total_lotes  ?>"/></td>
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
        <td><table >

  
	
	</table></td>
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
     <input type="hidden" name="MM_insert" value="remate_form1">  
      
      </td>
</form>
		  <form name="formulario2" action="carga_lotes_m.php">
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
		  <input type="hidden"  name="observacion" >
		  <input type="hidden"  name="sello" >
          <td></td>
		  </form>
    </tr>
	 </table>
	  
   <script type="text/javascript"> 

chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('country','city','includes/getCities1.php'); 
chainedSelects.addChain('city','university','includes/getLocalidad1.php'); 
chainedSelects.init(); 

</script> 

</body>
</html><?php
mysql_free_result($tipo_industria);
//mysql_free_result($ver_lotes);
?>