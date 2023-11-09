<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Documento sin t&iacute;tulo</title>
<?php 
include_once "src/userfn.php";
include_once "src/phpfn.php";

include_once "ewcfg11.php";
require_once('Connections/amercado.php'); 

mysqli_select_db($amercado, $database_amercado);
//LEVANTO LOS DATOS DEL FORM ANTERIOR
$codremate = $_POST["remate_num"];
if (isset($_POST['codusu']))
	$usuario = $_POST['codusu'];
else if (isset($_GET['codusu']))
	$usuario = $_GET['codusu'];
	else 
		$usuario = 1;
//echo "USUARIO ".$usuario."  ";
$cod_usuario = $usuario;
//echo "COD_USUARIO = ".$cod_usuario."  ";

mysqli_select_db($amercado, $database_amercado);

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$sigo_y_grabo = 1;
$todo_ok = 1;
$importe = 0;
$importe1 = 0;
$importe2 = 0;
$importe3 = 0;
$importe4 = 0;
$importe5 = 0;
$importe6 = 0;
$importe7 = 0;
$importe8 = 0;
$importe9 = 0;
$importe10 = 0;
$importe11 = 0;
$importe12 = 0;
$importe13 = 0;
$importe14 = 0;



//if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
	
if ($sigo_y_grabo == 1 && $todo_ok ==1) {
	//echo "CAE2 = ".$CAE." CAEFchVto2 = ".$CAEFchVto." Resultado2 = ".$Resultado."   -  ";
	$renglones = 0;
	$primera_vez = 1;
	if (isset($_POST['lote']) && GetSQLValueString($_POST['lote'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$importe = str_replace(".","" ,$_POST['importe']);
			$importe = str_replace(",","." ,$importe);
			str_replace(GetSQLValueString($_POST['importe'], "double"),",",".");
			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s,usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision'], "int"),
				$importe,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote'], "text")); 
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 1");
		}
	}
	if (isset($_POST['lote1']) && GetSQLValueString($_POST['lote1'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$importe1 = str_replace(".","" ,$_POST['importe1']);
			$importe1 = str_replace(",","." ,$importe1);
  			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s, preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision1'], "int"),
				$importe1,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote1'], "text")); 
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 2");
		}
	}
	if (isset($_POST['lote2']) && GetSQLValueString($_POST['lote2'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe2 = str_replace(".","" ,$_POST['importe2']);
			$importe2 = str_replace(",","." ,$importe2);
  			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision2'], "int"),
				$importe2,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote2'], "text")); 
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 3");
		}
	}
	if (isset($_POST['lote3']) && GetSQLValueString($_POST['lote3'], "int")!="NULL") {
					
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  				$importe3 = str_replace(".","" ,$_POST['importe3']);
				$importe3 = str_replace(",","." ,$importe3);
  				$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision3'], "int"),
				$importe3,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote3'], "text")); 
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 4");
		}
	}
	if (isset($_POST['lote4']) && GetSQLValueString($_POST['lote4'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe4 = str_replace(".","" ,$_POST['importe4']);
			$importe4 = str_replace(",","." ,$importe4);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision4'], "int"),
				$importe4,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote4'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 5");
		}
	}
	if (isset($_POST['lote5']) && GetSQLValueString($_POST['lote5'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe5 = str_replace(".","" ,$_POST['importe5']);
			$importe5 = str_replace(",","." ,$importe5);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s, preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision5'], "int"),
				$importe5,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote5'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 6");
		}
	}
	if (isset($_POST['lote6']) && GetSQLValueString($_POST['lote6'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe6 = str_replace(".","" ,$_POST['importe6']);
			$importe6 = str_replace(",","." ,$importe6);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s, preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision6'], "int"),
				$importe6,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote6'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 7");
		}
	}
	if (isset($_POST['lote7']) && GetSQLValueString($_POST['lote7'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe7 = str_replace(".","" ,$_POST['importe7']);
			$importe7 = str_replace(",","." ,$importe7);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s, preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision7'], "int"),
				$importe7,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote7'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 8");
		}
	}
	if (isset($_POST['lote8']) && GetSQLValueString($_POST['lote8'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe8 = str_replace(".","" ,$_POST['importe8']);
			$importe8 = str_replace(",","." ,$importe8);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s, preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision8'], "int"),
				$importe8,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote8'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 9");
		}
	}
	if (isset($_POST['lote9']) && GetSQLValueString($_POST['lote9'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe9 = str_replace(".","" ,$_POST['importe9']);
			$importe9 = str_replace(",","." ,$importe9);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s, preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision9'], "int"),
				$importe9,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote9'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 10");
		}
	}
	if (isset($_POST['lote10']) && GetSQLValueString($_POST['lote10'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe10 = str_replace(".","" ,$_POST['importe10']);
			$importe10 = str_replace(",","." ,$importe10);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision10'], "int"),
				$importe10,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote10'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 11");
		}
	}
	if (isset($_POST['lote11']) && GetSQLValueString($_POST['lote11'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe11 = str_replace(".","" ,$_POST['importe11']);
			$importe11 = str_replace(",","." ,$importe11);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision11'], "int"),
				$importe11,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote11'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 12");
		}
	}
	if (isset($_POST['lote12']) && GetSQLValueString($_POST['lote12'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe12 = str_replace(".","" ,$_POST['importe12']);
			$importe12 = str_replace(",","." ,$importe12);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision12'], "int"),
				$importe12,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote12'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 13");
		}
	}
	if (isset($_POST['lote13']) && GetSQLValueString($_POST['lote13'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe13 = str_replace(".","" ,$_POST['importe13']);
			$importe13 = str_replace(",","." ,$importe13);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision13'], "int"),
				$importe13,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote13'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 14");
		}
	}
	if (isset($_POST['lote14']) && GetSQLValueString($_POST['lote14'], "int")!="NULL") {
		
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$importe14 = str_replace(".","" ,$_POST['importe14']);
			$importe14 = str_replace(",","." ,$importe14);
   			$updateSQL = sprintf("UPDATE lotes SET comiscobr = %s,  preciobase =  %s, usuarioultmod = %s WHERE codrem = %s AND codintlote = %s",
  				GetSQLValueString($_POST['comision14'], "int"),
				$importe14,
				isset($cod_usuario)	? $cod_usuario: 1,
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['lote14'], "text"));
  			$Result1 = mysqli_query($amercado, $updateSQL) or die("ERROR REGRABANDO LOTE 15");
		}
	}
}  // ESTA ES LA NUEVA LLAVE 09082010

//mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
if ($tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante)) {
	$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
	$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);
}
//mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE (tipoent='2' OR tipoent='1') AND activo = '1' AND tipoiva = '1'  ORDER BY razsoc ASC";
if($cliente = mysqli_query($amercado, $query_cliente)) {
	$row_cliente = mysqli_fetch_assoc($cliente);
	$totalRows_cliente = mysqli_num_rows($cliente);
}
$colname_serie = "29";
if (isset($_POST['tcomp'])) {
  $colname_serie = addslashes($_POST['tcomp']);
}

$nivel = 9 ;
	
mysqli_select_db( $database_amercado, $amercado);
$query_Recordset1 = sprintf("SELECT * FROM `remates` WHERE  `ncomp` = $codremate");
//echo "QUERY = ".$query_Recordset1."   ";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("ERROR LEYENDO EL REMATE");
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
$lug_remate = utf8_decode($row_Recordset1["direccion"]);
$fec_remate = utf8_decode($row_Recordset1["fecreal"]);
echo "lugar: ".$lug_remate."  ";
echo "fecha: ".$fec_remate."  ";    
//LEO TABLA LOTES
$query_lotes_rem = sprintf("SELECT * FROM lotes WHERE codrem = $codremate AND estado = 0");
$lotes_rem = mysqli_query($amercado, $query_lotes_rem) or die("ERROR LEYENDO LOTES");
$row_lotes_rem = mysqli_fetch_assoc($lotes_rem);
$totalRows_lotes_rem = mysqli_num_rows($lotes_rem);
//echo " QUERY LOTES = ".$lotes_rem." TOTAL = ".$totalRows_lotes_rem."  ";

$query_impuesto = "SELECT * FROM impuestos";
$impuesto = mysqli_query($amercado, $query_impuesto) or die("ERROR LEYENDO TABLA IMPUESTOS");
$row_impuesto = mysqli_fetch_assoc($impuesto);
$totalRows_impuesto = mysqli_num_rows($impuesto);
$iva_21_desc = mysqli_result($impuesto,0,2)."<br>";
$iva_21_porcen = mysqli_result($impuesto,0,1);
$iva_15_desc = mysqli_result($impuesto,1,2)."<br>";
$iva_15_porcen = mysqli_result($impuesto,1,1);
?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<meta http-equiv="Content-Language" content="es"/>
<?php 
require_once('Connections/amercado.php');  ?>
<script src="cleave.min.js"></script>
<script src="cleave-phone.{country}.js"></script>
<script type="text/javascript" src="AJAX/ajax.js"></script>
<script language="JavaScript">
function pregunta(form){
    if (confirm('¿Estas seguro de grabar?')){
       document.form.submit();
    }
	
}
</script>
<script language="javascript">
	// Lote 1
function getprov(form) {
	var seleccion =  form.lote.options;
	var cantidad  =  form.lote.options.length;
	
	var cant = (cantidad+1) ;
	var contador = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote.value;
	if (total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote.value = "" ;
		form.descripcion.value = "" ;
        form.comision.value = "" ;
        form.tasa.value = "" ;
        form.descripcion.value = "" ;
        form.importe.value = "" ;
		return 0;
	}
	for ( contador ; contador < cant ; contador++) {
   		if (seleccion[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion.value = "" ;
			form.comision.value = "" ;
			form.tasa.value = "" ;
	  		form.descripcion.disabled = true ;
      		form.importe.disabled = true ;
	  		break ;	
    	}
    	if (seleccion[contador].selected) { 
	    	var opcion = new String (seleccion[contador].text);
			let largo_lote = opcion.indexOf("-");
						
			let posicion = opcion.indexOf("|");
			if (posicion !== -1)
    			opcion = opcion.substr(largo_lote + 1,posicion - 2);
	  		form.descripcion.value = opcion+" ";
			//form.lote.value = opcion+" ";
			var opcion2 = new String (seleccion[contador].text);
	  		var opcion2 = opcion2.substring(posicion + 2,posicion + 4);
	  		form.comision.value = opcion2+" ";
			var opcion3 = new String (seleccion[contador].text);
	  		var opcion3 = opcion3.substring(posicion + 7,posicion + 10);
	  		form.tasa.value = opcion3+" ";
			var opcion4 = new String (seleccion[contador].text);
	  		var opcion4 = opcion4.substring(posicion + 13,100);
	  		form.importe.value = opcion4;
			form.importe.focus();
			
						
	   	}
	}
	
}  
</script> 
<script language="javascript">
function formateo_nro(form) {
	form.importe.value = formato_numero(form.importe.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro1(form) {
	form.importe1.value = formato_numero(form.importe1.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro2(form) {
	form.importe2.value = formato_numero(form.importe2.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro3(form) {
	form.importe3.value = formato_numero(form.importe3.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro4(form) {
	form.importe4.value = formato_numero(form.importe4.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro5(form) {
	form.importe5.value = formato_numero(form.importe5.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro6(form) {
	form.importe6.value = formato_numero(form.importe6.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro7(form) {
	form.importe7.value = formato_numero(form.importe7.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro8(form) {
	form.importe8.value = formato_numero(form.importe8.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro9(form) {
	form.importe9.value = formato_numero(form.importe9.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro10(form) {
	form.importe10.value = formato_numero(form.importe10.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro11(form) {
	form.importe11.value = formato_numero(form.importe11.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro12(form) {
	form.importe12.value = formato_numero(form.importe12.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro13(form) {
	form.importe13.value = formato_numero(form.importe13.value,2,",",".");
}
</script> 
<script language="javascript">
function formateo_nro14(form) {
	form.importe14.value = formato_numero(form.importe14.value,2,",",".");
}
</script> 
<script language="javascript">
	// Lote 2
function getprov1(form) {
	var seleccion1 =  form.lote1.options;
	var cantidad1  =  form.lote1.options.length;
	var cant1 = (cantidad1+1) ;
	var contador1 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote1.value;
	if (total === form.lote.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote1.value = "" ;
		form.descripcion1.value = "" ;
        form.comision1.value = "" ;
        form.tasa1.value = "" ;
        form.descripcion1.value = "" ;
        form.importe1.value = "" ;
		return 0;
	}
	for ( contador1 ; contador1 < cant1 ; contador1++) {
   		if (seleccion1[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion1.value = "" ;
			form.comision1.value = "" ;
			form.tasa1.value = "" ;
	  		form.descripcion1.disabled = true ;
      		form.importe1.disabled = true ;
	  		break ;	
    	}
    	if (seleccion1[contador1].selected) { 
	    	var opcion1 = new String (seleccion1[contador1].text);
			let largo_lote1 = opcion1.indexOf("-");
	  		let posicion1 = opcion1.indexOf("|");
			if (posicion1 !== -1)
				opcion1 = opcion1.substring(largo_lote1 + 1,posicion1 - 2);
	  		form.descripcion1.value = opcion1+" ";
			var opcion2_1 = new String (seleccion1[contador1].text);
	  		opcion2_1 = opcion2_1.substring(posicion1 + 2, posicion1 + 4);
	  		form.comision1.value = opcion2_1+" ";
			var opcion3_1 = new String (seleccion1[contador1].text);
	  		opcion3_1 = opcion3_1.substring(posicion1 + 7,posicion1 + 10);
	  		form.tasa1.value = opcion3_1+" ";
			var opcion4_1 = new String (seleccion1[contador1].text);
	  		opcion4_1 = opcion4_1.substring(posicion1 + 13,100);
	  		form.importe1.value = opcion4_1;
      		form.importe1.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 3
function getprov2(form) {
	var seleccion2 =  form.lote2.options;
	var cantidad2  =  form.lote2.options.length;
	var cant2 = (cantidad2+1) ;
	var contador2 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote2.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote2.value = "" ;
		form.descripcion2.value = "" ;
        form.comision2.value = "" ;
        form.tasa2.value = "" ;
        form.descripcion2.value = "" ;
        form.importe2.value = "" ;
		return 0;
	}
	for ( contador2 ; contador2 < cant2 ; contador2++) {
   		if (seleccion2[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion2.value = "" ;
			form.comision2.value = "" ;
			form.tasa2.value = "" ;
	  		form.descripcion2.disabled = true ;
      		form.importe2.disabled = true ;
	  		break ;	
    	}
    	if (seleccion2[contador2].selected) { 
	    	var opcion2 = new String (seleccion2[contador2].text);
			let largo_lote2 = opcion2.indexOf("-");
			let posicion2 = opcion2.indexOf("|");
				if (posicion2 !== -1)
	  		var opcion2 = opcion2.substring(largo_lote2 + 1,posicion2 - 2);
	  		form.descripcion2.value = opcion2+" ";
			var opcion2_2 = new String (seleccion2[contador2].text);
	  		opcion2_2 = opcion2_2.substring(posicion2 + 2, posicion2 + 4);
	  		form.comision2.value = opcion2_2+" ";
			var opcion3_2 = new String (seleccion2[contador2].text);
	  		opcion3_2 = opcion3_2.substring(posicion2 + 7,posicion2 + 10);
	  		form.tasa2.value = opcion3_2+" ";
			var opcion4_2 = new String (seleccion2[contador2].text);
	  		opcion4_2 = opcion4_2.substring(posicion2 + 13,100);
	  		form.importe2.value = opcion4_2;
      		form.importe2.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 4
function getprov3(form) {
	var seleccion3 =  form.lote3.options;
	var cantidad3  =  form.lote3.options.length;
	var cant3 = (cantidad3+1) ;
	var contador3 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote3.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote3.value = "" ;
		form.descripcion3.value = "" ;
        form.comision3.value = "" ;
        form.tasa3.value = "" ;
        form.descripcion3.value = "" ;
        form.importe3.value = "" ;
		return 0;
	}
	for ( contador3 ; contador3 < cant3 ; contador3++) {
   		if (seleccion3[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion3.value = "" ;
			form.comision3.value = "" ;
			form.tasa3.value = "" ;
	  		form.descripcion3.disabled = true ;
      		form.importe3.disabled = true ;
	  		break ;	
    	}
    	if (seleccion3[contador3].selected) { 
	    	var opcion3 = new String (seleccion3[contador3].text);
			let largo_lote3 = opcion3.indexOf("-");
			let posicion3 = opcion3.indexOf("|");
				if (posicion3 !== -1)
	  		var opcion3 = opcion3.substring(largo_lote3 + 1,posicion3 - 2);
	  		form.descripcion3.value = opcion3+" ";
			var opcion3_2 = new String (seleccion3[contador3].text);
	  		opcion3_2 = opcion3_2.substring(posicion3 + 2, posicion3 + 4);
	  		form.comision3.value = opcion3_2+" ";
			var opcion3_3 = new String (seleccion3[contador3].text);
	  		opcion3_3 = opcion3_3.substring(posicion3 + 7,posicion3 + 10);
	  		form.tasa3.value = opcion3_3+" ";
			var opcion3_4 = new String (seleccion3[contador3].text);
	  		opcion3_4 = opcion3_4.substring(posicion3 + 13,100);
	  		form.importe3.value = opcion3_4;
      		form.importe3.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 5
function getprov4(form) {
	var seleccion4 =  form.lote4.options;
	var cantidad4  =  form.lote4.options.length;
	var cant4 = (cantidad4+1) ;
	var contador4 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote4.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote4.value = "" ;
		form.descripcion4.value = "" ;
        form.comision4.value = "" ;
        form.tasa4.value = "" ;
        form.descripcion4.value = "" ;
        form.importe4.value = "" ;
		return 0;
	}
	for ( contador4 ; contador4 < cant4 ; contador4++) {
   		if (seleccion4[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion4.value = "" ;
			form.comision4.value = "" ;
			form.tasa4.value = "" ;
	  		form.descripcion4.disabled = true ;
      		form.importe4.disabled = true ;
	  		break ;	
    	}
    	if (seleccion4[contador4].selected) { 
	    	var opcion4 = new String (seleccion4[contador4].text);
			let largo_lote4 = opcion4.indexOf("-");
			let posicion4 = opcion4.indexOf("|");
				if (posicion4 !== -1)
	  		var opcion4 = opcion4.substring(largo_lote4 + 1,posicion4 - 2);
	  		form.descripcion4.value = opcion4+" ";
			var opcion4_2 = new String (seleccion4[contador4].text);
	  		opcion4_2 = opcion4_2.substring(posicion4 + 2, posicion4 + 4);
	  		form.comision4.value = opcion4_2+" ";
			var opcion4_3 = new String (seleccion4[contador4].text);
	  		opcion4_3 = opcion4_3.substring(posicion4 + 7,posicion4 + 10);
	  		form.tasa4.value = opcion4_3+" ";
			var opcion4_4 = new String (seleccion4[contador4].text);
	  		opcion4_4 = opcion4_4.substring(posicion4 + 13,100);
	  		form.importe4.value = opcion4_4;
      		form.importe4.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 6
function getprov5(form) {
	var seleccion5 =  form.lote5.options;
	var cantidad5  =  form.lote5.options.length;
	var cant5 = (cantidad5+1) ;
	var contador5 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote5.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote5.value = "" ;
		form.descripcion5.value = "" ;
        form.comision5.value = "" ;
        form.tasa5.value = "" ;
        form.descripcion5.value = "" ;
        form.importe5.value = "" ;
		return 0;
	}
	for ( contador5 ; contador5 < cant5 ; contador5++) {
   		if (seleccion5[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion5.value = "" ;
			form.comision5.value = "" ;
			form.tasa5.value = "" ;
	  		form.descripcion5.disabled = true ;
      		form.importe5.disabled = true ;
	  		break ;	
    	}
    	if (seleccion5[contador5].selected) { 
	    	var opcion5 = new String (seleccion5[contador5].text);
			let largo_lote5 = opcion5.indexOf("-");
			let posicion5 = opcion5.indexOf("|");
				if (posicion5 !== -1)
	  		var opcion5 = opcion5.substring(largo_lote5 + 1,posicion5 - 2);
	  		form.descripcion5.value = opcion5+" ";
			var opcion5_2 = new String (seleccion5[contador5].text);
	  		opcion5_2 = opcion5_2.substring(posicion5 + 2, posicion5 + 4);
	  		form.comision5.value = opcion5_2+" ";
			var opcion5_3 = new String (seleccion5[contador5].text);
	  		opcion5_3 = opcion5_3.substring(posicion5 + 7,posicion5 + 10);
	  		form.tasa5.value = opcion5_3+" ";
			var opcion5_4 = new String (seleccion5[contador5].text);
	  		opcion5_4 = opcion5_4.substring(posicion5 + 13,100);
	  		form.importe5.value = opcion5_4;
      		form.importe5.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 7
function getprov6(form) {
	var seleccion6 =  form.lote6.options;
	var cantidad6  =  form.lote6.options.length;
	var cant6 = (cantidad6+1) ;
	var contador6 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote6.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote6.value = "" ;
		form.descripcion6.value = "" ;
        form.comision6.value = "" ;
        form.tasa6.value = "" ;
        form.descripcion6.value = "" ;
        form.importe6.value = "" ;
		return 0;
	}
	for ( contador6 ; contador6 < cant6 ; contador6++) {
   		if (seleccion6[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion6.value = "" ;
			form.comision6.value = "" ;
			form.tasa6.value = "" ;
	  		form.descripcion6.disabled = true ;
      		form.importe6.disabled = true ;
	  		break ;	
    	}
    	if (seleccion6[contador6].selected) { 
	    	var opcion6 = new String (seleccion6[contador6].text);
			let largo_lote6 = opcion6.indexOf("-");
			let posicion6 = opcion6.indexOf("|");
				if (posicion6 !== -1)
	  		var opcion6 = opcion6.substring(largo_lote6 + 1,posicion6 - 2);
	  		form.descripcion6.value = opcion6+" ";
			var opcion6_2 = new String (seleccion6[contador6].text);
	  		opcion6_2 = opcion6_2.substring(posicion6 + 2, posicion6 + 4);
	  		form.comision6.value = opcion6_2+" ";
			var opcion6_3 = new String (seleccion6[contador6].text);
	  		opcion6_3 = opcion6_3.substring(posicion6 + 7,posicion6 + 10);
	  		form.tasa6.value = opcion6_3+" ";
			var opcion6_4 = new String (seleccion6[contador6].text);
	  		opcion6_4 = opcion6_4.substring(posicion6 + 13,100);
	  		form.importe6.value = opcion6_4;
      		form.importe6.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 8
function getprov7(form) {
	var seleccion7 =  form.lote7.options;
	var cantidad7  =  form.lote7.options.length;
	var cant7 = (cantidad7+1) ;
	var contador7 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote7.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote7.value = "" ;
		form.descripcion7.value = "" ;
        form.comision7.value = "" ;
        form.tasa7.value = "" ;
        form.descripcion7.value = "" ;
        form.importe7.value = "" ;
		return 0;
	}
	for ( contador7 ; contador7 < cant7 ; contador7++) {
   		if (seleccion7[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion7.value = "" ;
			form.comision7.value = "" ;
			form.tasa7.value = "" ;
	  		form.descripcion7.disabled = true ;
      		form.importe7.disabled = true ;
	  		break ;	
    	}
    	if (seleccion7[contador7].selected) { 
	    	var opcion7 = new String (seleccion7[contador7].text);
			let largo_lote7 = opcion7.indexOf("-");
			let posicion7 = opcion7.indexOf("|");
				if (posicion7 !== -1)
	  		var opcion7 = opcion7.substring(largo_lote7 + 1,posicion7 - 2);
	  		form.descripcion7.value = opcion7+" ";
			var opcion7_2 = new String (seleccion7[contador7].text);
	  		opcion7_2 = opcion7_2.substring(posicion7 + 2, posicion7 + 4);
	  		form.comision7.value = opcion7_2+" ";
			var opcion7_3 = new String (seleccion7[contador7].text);
	  		opcion7_3 = opcion7_3.substring(posicion7 + 7,posicion7 + 10);
	  		form.tasa7.value = opcion7_3+" ";
			var opcion7_4 = new String (seleccion7[contador7].text);
	  		opcion7_4 = opcion7_4.substring(posicion7 + 13,100);
	  		form.importe7.value = opcion7_4;
      		form.importe7.focus();
	   	}
	}
}  
</script> 
</script> 
<script language="javascript">
	// Lote 9
function getprov8(form) {
	var seleccion8 =  form.lote8.options;
	var cantidad8  =  form.lote8.options.length;
	var cant8 = (cantidad8+1) ;
	var contador8 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote8.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote8.value = "" ;
		form.descripcion8.value = "" ;
        form.comision8.value = "" ;
        form.tasa8.value = "" ;
        form.descripcion8.value = "" ;
        form.importe8.value = "" ;
		return 0;
	}
	for ( contador8 ; contador8 < cant8 ; contador8++) {
   		if (seleccion8[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion8.value = "" ;
			form.comision8.value = "" ;
			form.tasa8.value = "" ;
	  		form.descripcion8.disabled = true ;
      		form.importe8.disabled = true ;
	  		break ;	
    	}
    	if (seleccion8[contador8].selected) { 
	    	var opcion8 = new String (seleccion8[contador8].text);
			let largo_lote8 = opcion8.indexOf("-");
			let posicion8 = opcion8.indexOf("|");
				if (posicion8 !== -1)
	  		var opcion8 = opcion8.substring(largo_lote8 + 1,posicion8 - 2);
	  		form.descripcion8.value = opcion8+" ";
			var opcion8_2 = new String (seleccion8[contador8].text);
	  		opcion8_2 = opcion8_2.substring(posicion8 + 2, posicion8 + 4);
	  		form.comision8.value = opcion8_2+" ";
			var opcion8_3 = new String (seleccion8[contador8].text);
	  		opcion8_3 = opcion8_3.substring(posicion8 + 7,posicion8 + 10);
	  		form.tasa8.value = opcion8_3+" ";
			var opcion8_4 = new String (seleccion8[contador8].text);
	  		opcion8_4 = opcion8_4.substring(posicion8 + 13,100);
	  		form.importe8.value = opcion8_4;
      		form.importe8.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 10
function getprov9(form) {
	var seleccion9 =  form.lote9.options;
	var cantidad9  =  form.lote9.options.length;
	var cant9 = (cantidad9+1) ;
	var contador9 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote9.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote9.value = "" ;
		form.descripcion9.value = "" ;
        form.comision9.value = "" ;
        form.tasa9.value = "" ;
        form.descripcion9.value = "" ;
        form.importe9.value = "" ;
		return 0;
	}
	for ( contador9 ; contador9 < cant9 ; contador9++) {
   		if (seleccion9[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion9.value = "" ;
			form.comision9.value = "" ;
			form.tasa9.value = "" ;
	  		form.descripcion9.disabled = true ;
      		form.importe9.disabled = true ;
	  		break ;	
    	}
    	if (seleccion9[contador9].selected) { 
	    	var opcion9 = new String (seleccion9[contador9].text);
			let largo_lote9 = opcion9.indexOf("-");
			let posicion9 = opcion9.indexOf("|");
				if (posicion9 !== -1)
	  		var opcion9 = opcion9.substring(largo_lote9 + 1,posicion9 - 2);
	  		form.descripcion9.value = opcion9+" ";
			var opcion9_2 = new String (seleccion9[contador9].text);
	  		opcion9_2 = opcion9_2.substring(posicion9 + 2, posicion9 + 4);
	  		form.comision9.value = opcion9_2+" ";
			var opcion9_3 = new String (seleccion9[contador9].text);
	  		opcion9_3 = opcion9_3.substring(posicion9 + 7,posicion9 + 10);
	  		form.tasa9.value = opcion9_3+" ";
			var opcion9_4 = new String (seleccion9[contador9].text);
	  		opcion9_4 = opcion9_4.substring(posicion9 + 13,100);
	  		form.importe9.value = opcion9_4;
      		form.importe9.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 11
function getprov10(form) {
	var seleccion10 =  form.lote10.options;
	var cantidad10  =  form.lote10.options.length;
	var cant10 = (cantidad10+1) ;
	var contador10 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote10.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote10value = "" ;
		form.descripcion10.value = "" ;
        form.comision10.value = "" ;
        form.tasa10.value = "" ;
        form.descripcion10.value = "" ;
        form.importe10.value = "" ;
		return 0;
	}
	for ( contador10 ; contador10 < cant10 ; contador10++) {
   		if (seleccion10[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion10.value = "" ;
			form.comision10.value = "" ;
			form.tasa10.value = "" ;
	  		form.descripcion10.disabled = true ;
      		form.importe10.disabled = true ;
	  		break ;	
    	}
    	if (seleccion10[contador10].selected) { 
	    	var opcion10 = new String (seleccion10[contador10].text);
			let largo_lote10 = opcion10.indexOf("-");
			let posicion10 = opcion10.indexOf("|");
				if (posicion10 !== -1)
	  		var opcion10 = opcion10.substring(largo_lote10 + 1,posicion10 - 2);
	  		form.descripcion10.value = opcion10+" ";
			var opcion10_2 = new String (seleccion10[contador10].text);
	  		opcion10_2 = opcion10_2.substring(posicion10 + 2, posicion10 + 4);
	  		form.comision10.value = opcion10_2+" ";
			var opcion10_3 = new String (seleccion10[contador10].text);
	  		opcion10_3 = opcion10_3.substring(posicion10 + 7,posicion10 + 10);
	  		form.tasa10.value = opcion10_3+" ";
			var opcion10_4 = new String (seleccion10[contador10].text);
	  		opcion10_4 = opcion10_4.substring(posicion10 + 13,100);
	  		form.importe10.value = opcion10_4;
      		form.importe10.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 12
function getprov11(form) {
	var seleccion11 =  form.lote11.options;
	var cantidad11  =  form.lote11.options.length;
	var cant11 = (cantidad11+1) ;
	var contador11 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote11.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value)  {
		alert("EL lote ya ha sido seleccionado");
		form.lote11.value = "" ;
		form.descripcion11.value = "" ;
        form.comision11.value = "" ;
        form.tasa11.value = "" ;
        form.descripcion11.value = "" ;
        form.importe11.value = "" ;
		return 0;
	}
	for ( contador11 ; contador11 < cant11 ; contador11++) {
   		if (seleccion11[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion11.value = "" ;
			form.comision11.value = "" ;
			form.tasa11.value = "" ;
	  		form.descripcion11.disabled = true ;
      		form.importe11.disabled = true ;
	  		break ;	
    	}
    	if (seleccion11[contador11].selected) { 
	    	var opcion11 = new String (seleccion11[contador11].text);
			let largo_lote11 = opcion11.indexOf("-");
			let posicion11 = opcion11.indexOf("|");
				if (posicion11 !== -1)
	  		var opcion11 = opcion11.substring(largo_lote11 + 1,posicion11 - 2);
	  		form.descripcion11.value = opcion11+" ";
			var opcion11_2 = new String (seleccion11[contador11].text);
	  		opcion11_2 = opcion11_2.substring(posicion11 + 2, posicion11 + 4);
	  		form.comision11.value = opcion11_2+" ";
			var opcion11_3 = new String (seleccion11[contador11].text);
	  		opcion11_3 = opcion11_3.substring(posicion11 + 7,posicion11 + 10);
	  		form.tasa11.value = opcion11_3+" ";
			var opcion11_4 = new String (seleccion11[contador11].text);
	  		opcion11_4 = opcion11_4.substring(posicion11 + 13,100);
	  		form.importe11.value = opcion11_4;
      		form.importe11.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
function formato_numero(numero, decimales, separador_decimal, separador_miles){ 
    numero=parseFloat(numero);
    if(isNaN(numero)){
        return "";
    }
    if(decimales!==undefined){
        // Redondeamos
        numero=numero.toFixed(decimales);
    }
    // Convertimos el punto en separador_decimal
    numero=numero.toString().replace(".", separador_decimal!==undefined ? separador_decimal : ",");
    if(separador_miles){
        // Añadimos los separadores de miles
        var miles=new RegExp("(-?[0-9]+)([0-9]{3})");
        while(miles.test(numero)) {
            numero=numero.replace(miles, "$1" + separador_miles + "$2");
        }
    }
    return numero;
}
</script> 
<script language="javascript">
	// Lote 13
function getprov12(form) {
	var seleccion12 =  form.lote12.options;
	var cantidad12  =  form.lote12.options.length;
	var cant12 = (cantidad12+1) ;
	var contador12 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote12.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value ||  total === form.lote13.value || total === form.lote14.value)  {
		alert("EL lote ya ha sido seleccionado");
		form.lote12.value = "" ;
		form.descripcion12.value = "" ;
        form.comision12.value = "" ;
        form.tasa12.value = "" ;
        form.descripcion12.value = "" ;
        form.importe12.value = "" ;
		return 0;
	}
	for ( contador12 ; contador12 < cant12 ; contador12++) {
   		if (seleccion12[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion12.value = "" ;
			form.comision12.value = "" ;
			form.tasa12.value = "" ;
	  		form.descripcion12.disabled = true ;
      		form.importe12.disabled = true ;
	  		break ;	
    	}
    	if (seleccion12[contador12].selected) { 
	    	var opcion12 = new String (seleccion12[contador12].text);
			let largo_lote12 = opcion12.indexOf("-");
			let posicion12 = opcion12.indexOf("|");
				if (posicion12 !== -1)
	  		var opcion12 = opcion12.substring(largo_lote12 + 1,posicion12 - 2);
	  		form.descripcion12.value = opcion12+" ";
			var opcion12_2 = new String (seleccion12[contador12].text);
	  		opcion12_2 = opcion12_2.substring(posicion12 + 2, posicion12 + 4);
	  		form.comision12.value = opcion12_2+" ";
			var opcion12_3 = new String (seleccion12[contador12].text);
	  		opcion12_3 = opcion12_3.substring(posicion12 + 7,posicion12 + 10);
	  		form.tasa12.value = opcion12_3+" ";
			var opcion12_4 = new String (seleccion12[contador12].text);
	  		opcion12_4 = opcion12_4.substring(posicion12 + 13,100);
	  		form.importe12.value = opcion12_4;
      		form.importe12.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 14
function getprov13(form) {
	var seleccion13 =  form.lote13.options;
	var cantidad13  =  form.lote13.options.length;
	var cant13 = (cantidad13+1) ;
	var contador13 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote13.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote14.value) {
		alert("EL lote ya ha sido seleccionado");
		form.lote13.value = "" ;
		form.descripcion13.value = "" ;
        form.comision13.value = "" ;
        form.tasa13.value = "" ;
        form.descripcion13.value = "" ;
        form.importe13.value = "" ;
		return 0;
	}
	for ( contador13 ; contador13 < cant13 ; contador13++) {
   		if (seleccion13[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion13.value = "" ;
			form.comision13.value = "" ;
			form.tasa13.value = "" ;
	  		form.descripcion13.disabled = true ;
      		form.importe13.disabled = true ;
	  		break ;	
    	}
    	if (seleccion13[contador13].selected) { 
	    	var opcion13 = new String (seleccion13[contador13].text);
			let largo_lote13 = opcion13.indexOf("-");
			let posicion13 = opcion13.indexOf("|");
				if (posicion13 !== -1)
	  		var opcion13 = opcion13.substring(largo_lote13 + 1,posicion13 - 2);
	  		form.descripcion13.value = opcion13+" ";
			var opcion13_2 = new String (seleccion13[contador13].text);
	  		opcion13_2 = opcion13_2.substring(posicion13 + 2, posicion13 + 4);
	  		form.comision13.value = opcion13_2+" ";
			var opcion13_3 = new String (seleccion13[contador13].text);
	  		opcion13_3 = opcion13_3.substring(posicion13 + 7,posicion13 + 10);
	  		form.tasa13.value = opcion13_3+" ";
			var opcion13_4 = new String (seleccion13[contador13].text);
	  		opcion13_4 = opcion13_4.substring(posicion13 + 13,100);
	  		form.importe13.value = opcion13_4;
      		form.importe13.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 15
function getprov14(form) {
	var seleccion14 =  form.lote14.options;
	var cantidad14  =  form.lote14.options.length;
	var cant14 = (cantidad14+1) ;
	var contador14 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote14.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value) {
		alert("EL lote ya ha sido seleccionado");
		form.lote14.value = "" ;
		form.descripcion14.value = "" ;
        form.comision14.value = "" ;
        form.tasa14.value = "" ;
        form.descripcion14.value = "" ;
        form.importe14.value = "" ;
		return 0;
	}
	for ( contador14 ; contador14 < cant14 ; contador14++) {
   		if (seleccion14[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion14.value = "" ;
			form.comision14.value = "" ;
			form.tasa14.value = "" ;
	  		form.descripcion14.disabled = true ;
      		form.importe14.disabled = true ;
	  		break ;	
    	}
    	if (seleccion14[contador14].selected) { 
	    	var opcion14 = new String (seleccion14[contador14].text);
			let largo_lote14 = opcion14.indexOf("-");
			let posicion14 = opcion14.indexOf("|");
				if (posicion14 !== -1)
	  		var opcion14 = opcion14.substring(largo_lote14 + 1,posicion14 - 2);
	  		form.descripcion14.value = opcion14+" ";
			var opcion14_2 = new String (seleccion14[contador14].text);
	  		opcion14_2 = opcion14_2.substring(posicion14 + 2, posicion14 + 4);
	  		form.comision14.value = opcion14_2+" ";
			var opcion14_3 = new String (seleccion14[contador14].text);
	  		opcion14_3 = opcion14_3.substring(posicion14 + 7,posicion14 + 10);
	  		form.tasa14.value = opcion14_3+" ";
			var opcion14_4 = new String (seleccion14[contador14].text);
	  		opcion14_4 = opcion14_4.substring(posicion14 + 13,100);
	  		form.importe14.value = opcion14_4;
      		form.importe14.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
function sin_lotes(form)
{
	alert("Debe ingresar al menos un lote para grabar");
}
</script> 
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript">
function checkDuplicates() {
  var selects = document.getElementsByTagName("select"),
      i,
      current,
      selected = {};
  for(i = 0; i < selects.length; i++){
    current = selects[i].selectedIndex;
    if (selected[current]) {
      alert("El lote ya ha sido seleccionado.");
      return false;
    } else
      selected[current] = true;
  }
  return true;
}
</script>
<script language="javascript">
	<!--

	function neto(form)
	{ 
		importe = factura.importes.value; 
   		document.write(importe);
	}

	function MM_findObj(n, d) { //v4.01
		//alert("en MM_findObj    ");
  		var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    	d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  		if(!x && d.getElementById) x=d.getElementById(n); return x;
	}

	function MM_validateForm() { //v4.0
		//alert("en MM_validateForm    ");
  		var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  		for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    	if (val) { nm=val.name; if ((val=val.value)!="") {
      	if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      	} else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='El importe debe contener un número.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
        min=test.substring(8,p); max=test.substring(p+1);
        if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    	} } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  		} if (errors) alert('ERROR \n'+errors);
  		document.MM_returnValue = (errors == '');
	}
	//-->

</script>

<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />
</head>
<body>

<form id="factura" name="factura" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="719" border="1" align="left" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
   
    <tr>
      <td colspan="3" valign="top" bgcolor="#82BADD"><table width="100%" border="1" cellspacing="1" cellpadding="1">
        
        
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Nro Remate </td>
			<td><input name="remate_num" type="text" id="remate_num" size="25"  value= <?php echo $codremate; ?> /></td>
          
          <td colspan="2" rowspan="4" valign="top" bgcolor="#82BADD" ></td>
          </tr>
        <tr>
          <td height="20" class="ewTableHeader">Lugar del Remate </td>
          <td><input name="lugar_remate" type="text" id="lugar_remate" size="25" value= <?php echo $lug_remate; ?>/></td>
        </tr>
		<tr>
         <td height="20" class="ewTableHeader">Fecha del Remate</td>
          <td><input name="fecha_remate" type="text" size="10" value= <?php echo $fec_remate; ?>/></td>
          <td>&nbsp;</td>
          </tr>
        
     </table></td>
    </tr>
    <tr>
      <td colspan="3"  background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
	   
	  <table width="100%" border="1" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
        <tr>
          <td width="73" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg" > <div align="center">LOTE</div></td>
          <td width="308" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">DESCRIPCION</div></td>
          <td width="86" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">COM</div></td>
          <td width="58" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">% TASA</div></td>
			<td width="68" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">IMPORTE</div></td>
		   
        </tr>
        <tr>
			</tr>
		<tr>
          <td size="5" bgcolor="#82BADD"><select name="lote" id="lote" onchange="getprov(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="45" />  </td>
        
		<td bgcolor="#82BADD"><input name="comision" type="text" id="comision" size="5" /></td> 
		
		
		<td bgcolor="#82BADD"><input name="tasa" type="text" id="tasa" size="5" /></td> 
		  		
         <td bgcolor="#82BADD"><input name="importe" type="text" id="importe" onBlur= "formateo_nro(this.form)" size="10" />
		</td>
        
		<input name="secuencia0" type="hidden" class="phpmaker" id="secuencia0" size="5" />
        </tr>
		<tr>
          <td width="73" bgcolor="#82BADD"><select name="lote1" id="lote1" onchange="getprov1(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);
        
        }
      ?>
         </select></td>
          <td bgcolor="#82BADD"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="45" /></td>
          <td bgcolor="#82BADD"><input name="comision1" type="text" id="comision1" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa1" type="text" id="tasa1" size="5" /></td> 
    		<td bgcolor="#82BADD"><input name="importe1" type="text" id="importe1" onBlur= "formateo_nro1(this.form)"size="10"  /></td>
	  
      <input name="secuencia1" type="hidden" class="phpmaker" id="secuencia1" size="65" />
	    </tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote2" id="lote2" onchange="getprov2(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);
        
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="45" /></td>
          <td bgcolor="#82BADD"><input name="comision2" type="text" id="comision2" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa2" type="text" id="tasa2" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe2" type="text" id="importe2" onBlur= "formateo_nro2(this.form)" size="10" /></td>
		  <input name="secuencia2" type="hidden" class="phpmaker" id="secuencia2" size="65" /> 
		</tr>
		  <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote3" id="lote3" onchange="getprov3(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision3" type="text" id="comision3" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa3" type="text" id="tasa3" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe3" type="text" id="importe3" onBlur= "formateo_nro3(this.form)"size="10" /></td>
		  <input name="secuencia3" type="hidden" class="phpmaker" id="secuencia3" size="65" />
		</tr>
		  <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote4" id="lote4" onchange="getprov4(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="45" /></td>
            <td bgcolor="#82BADD"><input name="comision4" type="text" id="comision4" size="5" /></td> 
			   <td bgcolor="#82BADD"><input name="tasa4" type="text" id="tasa4" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe4" type="text" id="importe4" onBlur= "formateo_nro4(this.form)"size="10" /></td>
		         
          <input name="secuencia4" type="hidden" class="phpmaker" id="secuencia4" size="65" />
		</tr>
		  <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote5" id="lote5" onchange="getprov5(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision5" type="text" id="comision5" size="5" /></td> 
			 <td bgcolor="#82BADD"><input name="tasa5" type="text" id="tasa5" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe5" type="text" id="importe5" onBlur= "formateo_nro5(this.form)"size="10" /></td>
		  <input name="secuencia5" type="hidden" class="phpmaker" id="secuencia5" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote6" id="lote6" onchange="getprov6(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision6" type="text" id="comision6" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa6" type="text" id="tasan6" size="5" /></td> 
		   <td bgcolor="#82BADD"><input name="importe6" type="text" id="importe6" onBlur= "formateo_nro6(this.form)" size="10" /></td>
           
           <input name="secuencia6" type="hidden" class="phpmaker" id="secuencia6" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote7" id="lote7" onchange="getprov7(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision7" type="text" id="comision7" size="5" /></td> 
			 <td bgcolor="#82BADD"><input name="tasa7" type="text" id="tasa7" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe7" type="text" id="importe7" onBlur= "formateo_nro7(this.form)"size="10" /></td>
		
           <input name="secuencia7" type="hidden" class="phpmaker" id="secuencia7" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote8" id="lote8" onchange="getprov8(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="45" /></td>
          <td bgcolor="#82BADD"><input name="comision8" type="text" id="comision8" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa8" type="text" id="tasa8" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe8" type="text" id="importe8" onBlur= "formateo_nro8(this.form)"size="10" /></td>
		  
          <input name="secuencia8" type="hidden" class="phpmaker" id="secuencia8" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote9" id="lote9" onchange="getprov9(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>

          <td bgcolor="#82BADD"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision9" type="text" id="comision9" size="5" /></td> 
			 <td bgcolor="#82BADD"><input name="tasa9" type="text" id="tasa9" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe9" type="text" id="importe9" onBlur= "formateo_nro9(this.form)"size="10" /></td>
		 
           <input name="secuencia9" type="hidden" class="phpmaker" id="secuencia9" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote10" id="lote10" onchange="getprov10(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="45" /></td>
         <td bgcolor="#82BADD"><input name="comision10" type="text" id="comision10" size="5" /></td> 
			<td bgcolor="#82BADD"><input name="tasa10" type="text" id="tasa10" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe10" type="text" id="importe10" onBlur= "formateo_nro10(this.form)"size="10" /></td>
		
          <input name="secuencia10" type="hidden" class="phpmaker" id="secuencia10" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote11" id="lote11" onchange="getprov11(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision11" type="text" id="comision11" size="5" /></td> 
			 <td bgcolor="#82BADD"><input name="tasa11" type="text" id="tasa11" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe11" type="text" id="importe11" onBlur= "formateo_nro11(this.form)"size="10" /></td>
	    <input name="secuencia11" type="hidden" class="phpmaker" id="secuencia11" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote12" id="lote12" onchange="getprov12(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision12" type="text" id="comision12" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa12" type="text" id="tasa12" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe12" type="text" id="importe12" onBlur= "formateo_nro12(this.form)" size="10" /></td>
	      <input name="secuencia12" type="hidden" class="phpmaker" id="secuencia12" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote13" id="lote13" onchange="getprov13(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision13" type="text" id="comision13" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa13" type="text" id="tasa13" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe13" type="text" id="importe13" onBlur= "formateo_nro13(this.form)"size="10" /></td>
	       <input name="secuencia13" type="hidden" class="phpmaker" id="secuencia13" size="65" />
		</tr> <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote14" id="lote14" onchange="getprov14(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "-"?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciofinal']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="45" /></td>
        <td bgcolor="#82BADD"><input name="comision14" type="text" id="comision14" size="5" /></td>
		 <td bgcolor="#82BADD"><input name="tasa14" type="text" id="tasa14" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe14" type="text" id="importe14" onBlur= "formateo_nro14(this.form)"size="10" /></td>
		         
          <input name="secuencia14" type="hidden" class="phpmaker" id="secuencia14" size="65" />
		</tr>
      </table>      </td>
    </tr>
    <tr bgcolor="#82BADD"><td width="413" bgcolor="#82BADD"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#82BADD"><tr>
      
	</tr>
	 </table></td>
      <td width="426" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
        <tr>
          <td width="48%">&nbsp;<span class="ewTableHeader">                    </span></td>
          <td width="52%"></td>
        </tr>
        <tr>
          
        </tr>
      </table></tr>
     
    <tr>
      <td colspan="3" bgcolor="#82BADD">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#82BADD"><table width="100%" border="1" cellspacing="1" cellpadding="1">
        <tr>
          <td><div align="center">
            
          </div></td>
          <td><div align="center">
            
            <input type="hidden" value="Quehago" id="pageOperation" name="pageOperation" />
			<input type="submit" value="Grabar cambios"  id="evento_eliminar" name="evento_eliminar" />
			  
			<input type="reset" value="Limpiar Formulario">
          </div></td>
          <td><div align="center">
            
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="factura">
</form>
 <script type="text/javascript"> 

chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('tcomp','serie','includes/getserxtc.php'); 
//chainedSelects.addChain('ncomp','datos','includes/getremate.php'); 

chainedSelects.init(); 
</script>
</body>
</html>