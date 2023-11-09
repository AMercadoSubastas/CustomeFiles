<?php 
setcookie("concepto","");
require_once('Connections/amercado.php'); 
include_once "src/userfn.php";
mysqli_select_db($amercado, $database_amercado);
$query_comprobante = "SELECT * FROM series  WHERE series.codnum =1";
$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
$row_comprobante = mysqli_fetch_assoc($comprobante);
$totalRows_comprobante = mysqli_num_rows($comprobante);

$num_comp = ($row_comprobante['nroact'])+1 ; 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$renglones = 0;
$primera_vez = 1;
if (isset($_POST['descripcion']) && GetSQLValueString($_POST['descripcion'], "text")!="NULL") {
	// DESDE ACA ===================================================================================
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
		mysqli_select_db($amercado, $database_amercado);
		$actualiza1 = sprintf("UPDATE `series` SET `nroact` = %s WHERE `series`.`codnum` = %s", GetSQLValueString($_POST['num_factura'], "int"), GetSQLValueString($_POST['serie'], "int")) ;				 
		$resultado=mysqli_query($amercado,	$actualiza1);	
	}
	// HASTA ACA ===================================================================================
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem , descrip, neto,  concafac) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['importe'], "double"),
					   GetSQLValueString($_POST['secuencia'], "int"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 1;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion1']) && GetSQLValueString($_POST['descripcion1'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia1'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia1'], "int"),
                       GetSQLValueString($_POST['descripcion1'], "text"),
                       GetSQLValueString($_POST['importe1'], "double"),
                       GetSQLValueString($_POST['comision1'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 2;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion2']) && GetSQLValueString($_POST['descripcion2'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia2'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia2'], "int"),
                       GetSQLValueString($_POST['descripcion2'], "text"),
                       GetSQLValueString($_POST['importe2'], "double"),
                       GetSQLValueString($_POST['comision2'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 3;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion3']) && GetSQLValueString($_POST['descripcion3'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, tieneresol) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia3'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia3'], "int"),
                       GetSQLValueString($_POST['descripcion3'], "text"),
                       GetSQLValueString($_POST['importe3'], "double"),
                       GetSQLValueString($_POST['comision3'], "double"),
                       GetSQLValueString(isset($_POST['tieneresol']) ? "true" : "", "defined","1","0"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 4;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion4']) && GetSQLValueString($_POST['descripcion4'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto,comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia4'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia4'], "int"),
                       GetSQLValueString($_POST['descripcion4'], "text"),
					   GetSQLValueString($_POST['importe4'], "double"),
                       GetSQLValueString($_POST['comision4'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 5;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion5']) && GetSQLValueString($_POST['descripcion5'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia5'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia5'], "int"),
                       GetSQLValueString($_POST['descripcion5'], "text"),
                       GetSQLValueString($_POST['importe5'], "double"),
                       GetSQLValueString($_POST['comision5'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 6;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion6']) && GetSQLValueString($_POST['descripcion6'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia6'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia6'], "int"),
                       GetSQLValueString($_POST['descripcion6'], "text"),
                       GetSQLValueString($_POST['importe6'], "double"),
                       GetSQLValueString($_POST['comision6'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 7;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion7']) && GetSQLValueString($_POST['descripcion7'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia7'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia7'], "int"),
                       GetSQLValueString($_POST['descripcion7'], "text"),
                       GetSQLValueString($_POST['importe7'], "double"),
                       GetSQLValueString($_POST['comision7'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 8;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion8']) && GetSQLValueString($_POST['descripcion8'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia8'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia8'], "int"),
                       GetSQLValueString($_POST['descripcion8'], "text"),
                       GetSQLValueString($_POST['importe8'], "double"),
                       GetSQLValueString($_POST['comision8'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 9;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion9']) && GetSQLValueString($_POST['descripcion9'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia9'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia9'], "int"),
                       GetSQLValueString($_POST['descripcion9'], "text"),
                       GetSQLValueString($_POST['importe9'], "double"),
                       GetSQLValueString($_POST['comision9'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 10;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion10']) && GetSQLValueString($_POST['descripcion10'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia10'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia10'], "int"),
                       GetSQLValueString($_POST['descripcion10'], "text"),
                       GetSQLValueString($_POST['importe10'], "double"),
                       GetSQLValueString($_POST['comision10'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 11;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion11']) && GetSQLValueString($_POST['descripcion11'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia11'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia11'], "int"),
                       GetSQLValueString($_POST['descripcion11'], "text"),
                       GetSQLValueString($_POST['importe11'], "double"),
                       GetSQLValueString($_POST['comision11'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 12;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion12']) && GetSQLValueString($_POST['descripcion12'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia12'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia12'], "int"),
                       GetSQLValueString($_POST['descripcion12'], "text"),
                       GetSQLValueString($_POST['importe12'], "double"),
                       GetSQLValueString($_POST['comision12'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 13;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion13']) && GetSQLValueString($_POST['descripcion13'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia13'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia13'], "int"),
                       GetSQLValueString($_POST['descripcion13'], "text"),
                       GetSQLValueString($_POST['importe13'], "double"),
                       GetSQLValueString($_POST['comision13'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 14;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['descripcion14']) && GetSQLValueString($_POST['descripcion14'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia14'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia14'], "int"),
                       GetSQLValueString($_POST['descripcion14'], "text"),
                       GetSQLValueString($_POST['importe14'], "double"),
                       GetSQLValueString($_POST['comision14'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 15;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	}
}

// Crea la mascara 
if (isset($_POST['descripcion']) && GetSQLValueString($_POST['descripcion'], "text")!="NULL") {

	$tcomp = $_POST['tcomp'];
  	$serie = $_POST['serie'];
  	$num_fac = $_POST['num_factura'];
  	$query_mascara = "SELECT * FROM series  WHERE  series.codnum='$serie'";
  	$mascara = mysqli_query($amercado, $query_mascara) or die(mysqli_error($amercado));
  	$row_mascara = mysqli_fetch_assoc($mascara);
  	$totalRows_mascara = mysqli_num_rows($mascara);
 	$mascara  = $row_mascara['mascara'];
	if ($num_fac <10) {
   		$mascara=$mascara."-"."0000000".$num_fac ;
   	}

	if ($num_fac >9 && $num_fac <99) {
  		$mascara=$mascara."-"."000000".$num_fac;
   	}

	if ($num_fac >99 && $num_fac <999) {
		$mascara=$mascara."-"."00000".$num_fac;
  	}
  
	if ($num_fac >999 && $num_fac <9999) {
		$mascara=$mascara."-"."0000".$num_fac;
  	}
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
$fecha_factura1 =$_POST['fecha_factura1'] ;
		$fecha_factura1 = substr($fecha_factura1,6,4)."-".substr($fecha_factura1,3,2)."-".substr($fecha_factura1,0,2);
		//echo "   FECHA_FACTURA1 -    ".$fecha_factura1."    -    ";
  		$insertSQL = sprintf("INSERT INTO cabfac (tcomp, serie, ncomp, fecval, fecdoc, fecreg, cliente, fecvenc, estado, emitido, codrem, totbruto, totiva21, totneto21, nrengs, nrodoc) VALUES (%s, %s, %s, '$fecha_factura1','$fecha_factura1', '$fecha_factura1', %s, '$fecha_factura1', %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['codnum'], "int"),
                  	   GetSQLValueString($_POST['GrupoOpciones1'], "text"), 
					   GetSQLValueString("0", "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['tot_general'], "double"),
                       GetSQLValueString($_POST['totiva21'], "double"),
                       GetSQLValueString($_POST['totneto21'], "double"),
					   GetSQLValueString($renglones, "int"),
					   GetSQLValueString($mascara, "text"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
		// DESDE ACA =======================================================================================
		if (isset($_POST['leyenda']) && GetSQLValueString($_POST['leyenda'], "text")!="NULL") {
 			$insertSQLfactley = sprintf("INSERT INTO factley (tcomp, serie, ncomp, leyendafc, codrem) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['leyenda'], "text"),
                       GetSQLValueString($_POST['remate_num'], "int"));
                       

  			mysqli_select_db($amercado, $database_amercado);
    		$Result1 = mysqli_query($amercado, $insertSQLfactley) or die(mysqli_error($amercado));

		}
		mysqli_select_db($amercado, $database_amercado);
		$query_medios_pago = sprintf("SELECT * FROM cartvalores   WHERE cartvalores.tcomprel = %s  AND cartvalores.serierel = %s  AND cartvalores.ncomprel= %s",
                       GetSQLValueString($_POST['serie'], "int"),
					   GetSQLValueString($_POST['tcomp'], "int"),
					   GetSQLValueString($_POST['num_factura'], "int"));

		$medios_pago = mysqli_query($amercado, $query_medios_pago) or die(mysqli_error($amercado));
		$row_medios_pago = mysqli_fetch_assoc($medios_pago);
		$totalRows_medios_pago = mysqli_num_rows($medios_pago);
		if ($totalRows_medios_pago==0 && strcmp(GetSQLValueString($_POST['GrupoOpciones1'], "text"),"'S'")==0) {
   			// Levanto ultimo Comprobant e y sumo 1
   			mysqli_select_db($amercado, $database_amercado);
   			$query_comprobante = "SELECT * FROM series  WHERE series.codnum =8";
   			$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
   			$row_comprobante = mysqli_fetch_assoc($comprobante);
   			$totalRows_comprobante = mysqli_num_rows($comprobante);
    		$num_comp = ($row_comprobante['nroact'])+1 ; 
  			$strSQL = sprintf("INSERT INTO cartvalores (tcomp, serie, ncomp,  codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel )
	VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
	                   GetSQLValueString("12", "int"),
                       GetSQLValueString("8", "int"),
                       GetSQLValueString("$num_comp", "int"),
                       GetSQLValueString("1", "int"),
					   GetSQLValueString($_POST['tot_general'], "double"),
                       GetSQLValueString($_POST['fecha_factura'], "date"),
                       GetSQLValueString($_POST['fecha_factura'], "date"),
                       GetSQLValueString($_POST['serie'], "int"),
					   GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString("S", "text"),
					   GetSQLValueString("1", "int"),
					   GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"));
		
			// 4. Ejecuto la consulta.	
			$result = mysqli_query($amercado, $strSQL);				         
			$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='8'" ;	
			$resultado=mysqli_query($amercado,	$actualiza);
			$total_fc = GetSQLValueString($_POST['tot_general'], "text");
		} 

		if (!empty($_POST['imprime'])) { 
			$facnum = GetSQLValueString($_POST['num_factura'], "int");
			$tipcomp = GetSQLValueString($_POST['tcomp'], "int");
			$numserie = GetSQLValueString($_POST['serie'], "int");
			$insertGoTo = "rp_facnc.php?ftcomp=$tipcomp&&fserie=$numserie&&fncomp=$facnum";
			if (isset($_SERVER['QUERY_STRING'])) {
    			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
	    		$insertGoTo .= $_SERVER['QUERY_STRING'];
			}
			header(sprintf("Location: %s", "rp_facnc.php?ftcomp=$tipcomp&&fserie=$numserie&&fncomp=$facnum")); 
		} else { 
		  	$facnum = GetSQLValueString($_POST['num_factura'], "int");
		 	$insertGoTo = "factura_ok.php?factura=$facnum";
		  	if (isset($_SERVER['QUERY_STRING'])) {
    			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    			$insertGoTo .= $_SERVER['QUERY_STRING'];
  			}
  			header(sprintf("Location: %s", "factura_ok.php?factura=$facnum")); 
		}
	}
}
setcookie("concepto","");
mysqli_select_db($amercado, $database_amercado);
$query_facturas_a = "SELECT * FROM series  WHERE series.tipcomp=1";
$facturas_a = mysqli_query($amercado, $query_facturas_a) or die(mysqli_error($amercado));
$row_facturas_a = mysqli_fetch_assoc($facturas_a);
$totalRows_facturas_a = mysqli_num_rows($facturas_a);
$facturanum1 = ($row_facturas_a['nroact'])+1;
/// Agrega Mascara 
$mascara1      = $row_facturas_a['mascara']; // 
$tcomp = $row_facturas_a['tipcomp'];
mysqli_select_db($amercado, $database_amercado);
$query_facturas_b = "SELECT * FROM series  WHERE series.tipcomp=23";
$facturas_b = mysqli_query($amercado, $query_facturas_b) or die(mysqli_error($amercado));
$row_facturas_b = mysqli_fetch_assoc($facturas_b);
$totalRows_facturas_b = mysqli_num_rows($facturas_b);
$facturanum2 = ($row_facturas_b['nroact'])+1;
/// DESDE ACA LA MASCARA
$mascara2    = $row_facturas_b['mascara'];
if ($mascara1='') {
 	$mascara = $mascara2 ;
	if ($facturanum2 <10) {
		$mascara=$mascara."-"."0000000".$facturanum2 ;
	}

	if ($facturanum2 >9 && $facturanum2 <99) {
		$mascara=$mascara."-"."000000".$facturanum2;
	}

	if ($facturanum2 >99 && $facturanum2 <999) {
		$mascara=$mascara."-"."00000".$facturanum2;
	}
	if ($facturanum2 >999 && $facturanum2 <9999) {
		$mascara=$mascara."-"."0000".$facturanum2;
	}
} else {
	$mascara = $mascara1 ;
 	if ($facturanum1 <10) {
		$mascara=$mascara."-"."0000000".$facturanum1 ;
	}

	if ($facturanum1 >9 && $facturanum1 <99) {
		$mascara=$mascara."-"."000000".$facturanum1;
	}

	if ($facturanum1 >99 && $facturanum1 <999) {
		$mascara=$mascara."-"."00000".$facturanum1;
	}
	if ($facturanum1 >999 && $facturanum1 <9999) {
		$mascara=$mascara."-"."0000".$facturanum1;
	}
}
// HASTA ACA LA MASCARA 

mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades";
$cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

$colname_serie = "1";
if (isset($_POST['tcomp'])) {
  	$colname_serie = addslashes($_POST['tcomp']);
}
mysqli_select_db($amercado, $database_amercado);
$query_serie = sprintf("SELECT * FROM serxtc WHERE tcomp = %s ORDER BY serie ASC", $colname_serie);
$serie = mysqli_query($amercado, $query_serie) or die(mysqli_error($amercado));
$row_serie = mysqli_fetch_assoc($serie);
$totalRows_serie = mysqli_num_rows($serie);

$nivel = 9 ;
$query_num_remates = "SELECT * FROM remates";
$num_remates = mysqli_query($amercado, $query_num_remates) or die(mysqli_error($amercado));
$row_num_remates = mysqli_fetch_assoc($num_remates);
$totalRows_num_remates = mysqli_num_rows($num_remates);

$query_impuesto = "SELECT * FROM impuestos";
$impuesto = mysqli_query($amercado, $query_impuesto) or die(mysqli_error($amercado));
$row_impuesto = mysqli_fetch_assoc($impuesto);
$totalRows_impuesto = mysqli_num_rows($impuesto);
$iva_21_desc = mysqli_result($impuesto,0,2)."<br>";
$iva_21_porcen = mysqli_result($impuesto,0,1);
$iva_15_desc = mysqli_result($impuesto,1,2)."<br>";
$iva_15_porcen = mysqli_result($impuesto,1,1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php require_once('Connections/amercado.php');  ?>
<?php //include_once "ewcfg50.php" ?>
<?php //include_once "ewmysql50.php" ?>
<?php //include_once "phpfn50.php" ?>
<?php //include_once  "userfn50.php" ?>
<?php //include_once "usuariosinfo.php" ?>
<?php
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
//header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache"); // HTTP/1.0
?>
<?php //include "header.php" ;
//echo $nivel;
?>
<script src="cjl_cookie.js" type="text/javascript"></script>
<script language="javascript">
function forma_pago(form)
{
	var total      = factura.tot_general.value;  // Total de la boleta
  	var fac_numero = factura.num_factura.value ; // Nuemro de la Factura 
  	var tipo_comprobante = factura.tcomp.value ;  // Tipo de comprobante 
  	var serie_num     = factura.serie.value ;  // Numero de Serie 
  	var fecha_factura = factura.fecha_factura.value ; //  Fecha de Factura
  	var remate_num    = factura.remate_num.value ; // Numero de Remate
  	var chequedo = factura.pago_contado.value ;
  	factura.leyenda.value = "";
  	var error ="";
  	if (tipo_comprobante=="" || total=="" || serie_num=="" || fecha_factura1=="") {
    	if (tipo_comprobante=="") {
        	error = "      Tipo de comprobante\n"; 
        }
      	if (serie_num=="") {
        	error = error+"      Serie\n"; 
        }
	
	 	if (total=="") {
        	error = error+"      Total general\n"; 
        }		 
		
		if (fecha_factura1=="") {
        	error = error+"      Fecha de Nota de Debito\n"; 
        }		 
		   
  		alert ("Faltan los siguientes datos :\n"+error);
  
  	} else {

  		// escribimos el mensaje de alerta
		strAlerta = "Total Factura " + total + "\n" + "Numero Factura " + fac_numero + "\n" + "Tipo de comprobante " + tipo_comprobante + "\n" + "Serie Numero " + serie_num + "\n" + "Fecha NDeb " + fecha_factura1 + "\n" + "Numero de remate " + remate_num + "\n";

		// lanzamos la acci�n

   		var f = document.forms[0] ;
   		var ckUtil = new CJL_CookieUtil("concepto", 30);
 		function setFieldFromCookie(fieldId)
   		{
	  		var cookieVal = ckUtil.getSubValue(fieldId);
	  		if( cookieVal )
	  		{
	     		f[fieldId].value = cookieVal;
	  		}
   		}
   
   		function saveFieldToCookie(fieldId)
   		{
	  		var fieldVal = f[fieldId].value;	  
	 	  	ckUtil.setSubValue(fieldId, fieldVal);	  	  
   		}
   
   		if( ckUtil.cookieExists() )
  		{
      
   			setFieldFromCookie("tcomp");
   			setFieldFromCookie("serie");
   			setFieldFromCookie("num_factura");
   			setFieldFromCookie("tot_general");
      
    	} else   
   		{
      		saveFieldToCookie("tcomp");
      		saveFieldToCookie("serie");
      		saveFieldToCookie("num_factura");
	  		saveFieldToCookie("tot_general");	  
	  		if( ckUtil.cookieExists() )
	  		{
	  		}
	  		else
	  		{
	  		} 
   		}
		window.open("medios_pago_con.php","nueva","fullscreen,scrollbars");
	}
}
</script>
<script language="javascript">
function sin_lotes(form)
{
	alert("Debe ingresar al menos un lote para facturar");
}
</script> 
<script language="javascript">
function OcultarCapa(capa)
{

	document.all.cheques_tercero.style.visibility='visible' // Si utilizamos IE
	document.all.medios_p.style.visibility='hidden'

	//document.all(capa).style.visibility='visible' 
}
</script>
<script language="javascript">
 function cambia_fecha(form)
{ 
	var fecha = factura.fecha_factura1.value;
	var ano = fecha.substring(6,10);
	var mes = fecha.substring(3,5);
	var dia = fecha.substring(0,2);

	var fecha1 = ano+"-"+mes+"-"+dia ;

}

function pasaValor(form)
{ 
	var comprobante = factura.tcomp.value;  // Nuemro de remate
	var serie       = factura.serie.value; // Tipo de industria
	var factnum     = factura.num_factura.value; // Codigo de cliente
	var fecha_fact  = factura.fecha_factura.value; // Direccion del remate
	var remate      = factura.remate_num.value; // Direccion del remate
}
</script>

<script language="javascript">
function  valor_prueba(form) {

    if (factura.impuesto[0].checked==true) {
    	var impuesto = (factura.impuesto[0].value)/100;

    }

    if (factura.impuesto[1].checked==true) {
        var impuesto = (factura.impuesto[1].value)/100;
    }

   	if (factura.impuesto1[0].checked==true) {
    	var impuesto1 = (factura.impuesto1[0].value)/100;
    }

   	if (factura.impuesto1[1].checked==true) {
   		var impuesto1 = (factura.impuesto1[1].value)/100;
   	}
    
    if (factura.impuesto2[0].checked==true) {
    	var impuesto2 = (factura.impuesto2[0].value)/100;
    }

    if (factura.impuesto2[1].checked==true) {
    	var impuesto2 = (factura.impuesto2[1].value)/100;
    }
    
	if (factura.impuesto3[0].checked==true) {
		var impuesto3 = (factura.impuesto3[0].value)/100;
	}

	if (factura.impuesto3[1].checked==true) {
		var impuesto3 = (factura.impuesto3[1].value)/100;
	} 

	var total = factura.importe.value;
	var total_articulo = impuesto+('+')+total;
} 

</script>
<script language="javascript">
function validarFormulario(form)
{
    var series = factura.serie.value  // serie 
	var imp21  = factura.iva21.value/100; // impuesto 21 %	
	var monto  = factura.importe.value; // Monto  primer concepto
	var monto1 = factura.importe1.value; // Monto segundo concepto
	var monto2 = factura.importe2.value; // Monto tercer concepto
	var monto3 = factura.importe3.value; // Monto cuarto concepto
	var monto4 = factura.importe4.value;  // Monto Quinto concepto
	var monto5 = factura.importe5.value; // Monto Sexto concepto
	var monto6  = factura.importe6.value; // Monto Septimo concepto
	var monto7  = factura.importe7.value; // Monto Octavo concepto
	var monto8  = factura.importe8.value; // Monto Noveno concepto
	var monto9  = factura.importe9.value; // Monto D�cimo concepto
	var monto10 = factura.importe10.value;  // Monto Onceavo concepto
	var monto11 = factura.importe11.value; // Monto Doceavo concepto
	var monto12 = factura.importe12.value; // Monto Treceavo concepto
	var monto13 = factura.importe13.value; // Monto Catorceavo concepto
	var monto14 = factura.importe14.value; // Monto Quinceavo concepto
	
    var tot_mon =  0 ;
	var tot_mon_1 =  0 ;
	var tot_mon_2 =  0 ;
	var tot_mon_3 =  0 ;
	var tot_mon_4 =  0 ;
	var tot_mon_5 =  0 ;
	var tot_mon_6 =  0 ;
	var tot_mon_7 =  0 ;
	var tot_mon_8 =  0 ;
	var tot_mon_9 =  0 ;
	var tot_mon_10 =  0 ;
	var tot_mon_11 =  0 ;
	var tot_mon_12 =  0 ;
	var tot_mon_13 =  0 ;
	var tot_mon_14 =  0 ;
	
   	var imp_21 =   0 ;
	var imp_21_1 =  0 ;
	var imp_21_2 =  0 ;
	var imp_21_3 =  0 ;
	var imp_21_4 =  0 ;
	var imp_21_5 =  0 ;
	var imp_21_6 =  0 ;
	var imp_21_7 =  0 ;
	var imp_21_8 =  0 ;
	var imp_21_9 =  0 ;
	var imp_21_10 = 0 ;
	var imp_21_11 = 0 ;
	var imp_21_12 = 0 ;
	var imp_21_13 = 0 ;
	var imp_21_14 = 0 ;
	
	var tot_monto =  0 ;
	var imp_tot_21  = 0;
	var tot_general  =  0;
	// PRIMER LOTE
	if (monto.length!=0) {
		var tot_mon = eval(monto);              // Monto de Primer Lote
      	var	imp_tot_21 = eval(monto*imp21);        // Impuesto Monto 1 al 21 %
		var	tot_general = eval(monto+('+')+imp_tot_21);	// Total general
	    
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
		 
	} 
		
	// SEGUNDO LOTE
	if (monto1.length!=0) {
		imp_21_1 = eval(monto1*imp21);        // Impuesto Monto 1 al 21 %
		tot_mon21_1 = eval(monto1+('+')+imp_21_1);	// Total general
		tot_mon = eval(tot_mon+('+')+monto1);
		imp_tot_21 = eval(imp_tot_21+('+')+imp_21_1);
		tot_general = eval(tot_general+('+')+tot_mon21_1)
			
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
					 
	} 
	
	// TERCER LOTE
	if (monto2.length!=0) {
		imp_21_2 = eval(monto2*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_2 = eval(monto2+('+')+imp_21_2);	// Total general
		tot_mon = eval(tot_mon+('+')+monto2);
		tot_mon21_2 = eval(monto2+('+')+imp_21_2);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_2);
	    tot_general = eval(tot_general+('+')+tot_mon21_2) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
	} 
		
	// CUARTO LOTE
	if (monto3.length!=0) {
		imp_21_3 = eval(monto3*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_3 = eval(monto3+('+')+imp_21_3);	// Total general
		tot_mon = eval(tot_mon+('+')+monto3);
		tot_mon21_3 = eval(monto3+('+')+imp_21_3);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_3);
	    tot_general = eval(tot_general+('+')+tot_mon21_3) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
			
	} 
		
	// QUINTO LOTE
	if (monto4.length!=0) {
		imp_21_4 = eval(monto4*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_4 = eval(monto4+('+')+imp_21_4);	// Total general
		tot_mon = eval(tot_mon+('+')+monto4);
		tot_mon21_4 = eval(monto4+('+')+imp_21_4);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_4);
	    tot_general = eval(tot_general+('+')+tot_mon21_4) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
			
	} 
	
	// SEXTO LOTE
	if (monto5.length!=0) {
		imp_21_5 = eval(monto5*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_5 = eval(monto5+('+')+imp_21_5);	// Total general
		tot_mon = eval(tot_mon+('+')+monto5);
		tot_mon21_5 = eval(monto5+('+')+imp_21_5);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_5);
	    tot_general = eval(tot_general+('+')+tot_mon21_5) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
			
	} 
	
	// SEPTIMO LOTE
	if (monto6.length!=0) {
		imp_21_6 = eval(monto6*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_6 = eval(monto6+('+')+imp_21_6);	// Total general
		tot_mon = eval(tot_mon+('+')+monto6);
		tot_mon21_6 = eval(monto6+('+')+imp_21_6);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_6);
	    tot_general = eval(tot_general+('+')+tot_mon21_6) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
			
	} 
	
	// OCTAVO LOTE
	if (monto7.length!=0) {
		imp_21_7 = eval(monto7*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_7 = eval(monto7+('+')+imp_21_7);	// Total general
		tot_mon = eval(tot_mon+('+')+monto7);
		tot_mon21_7 = eval(monto7+('+')+imp_21_7);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_7);
	    tot_general = eval(tot_general+('+')+tot_mon21_7) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
		
	} 
	
	// NOVENO LOTE
	if (monto8.length!=0) {
		imp_21_8 = eval(monto8*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_8 = eval(monto8+('+')+imp_21_8);	// Total general
		tot_mon = eval(tot_mon+('+')+monto8);
		tot_mon21_8 = eval(monto8+('+')+imp_21_8);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_8);
	    tot_general = eval(tot_general+('+')+tot_mon21_8) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
		
	} 
	
	// DECIMO LOTE
	if (monto9.length!=0) {
		imp_21_9 = eval(monto9*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_9 = eval(monto9+('+')+imp_21_9);	// Total general
		tot_mon = eval(tot_mon+('+')+monto9);
		tot_mon21_9 = eval(monto9+('+')+imp_21_9);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_9);
	    tot_general = eval(tot_general+('+')+tot_mon21_9) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
	} 
	
	//  LOTE ONCE
	if (monto10.length!=0) {
		imp_21_10 = eval(monto10*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_10 = eval(monto10+('+')+imp_21_10);	// Total general
		tot_mon = eval(tot_mon+('+')+monto10);
		tot_mon21_10 = eval(monto10+('+')+imp_21_10);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_10);
	    tot_general = eval(tot_general+('+')+tot_mon21_10) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
					 
	} 
	
	//  LOTE DOCE
	if (monto11.length!=0) {
		         imp_21_11 = eval(monto11*imp21);              // Impuesto Monto 1 al 21 %
		         tot_mon21_11 = eval(monto11+('+')+imp_21_11);	// Total general
		         tot_mon = eval(tot_mon+('+')+monto11);
		         tot_mon21_11 = eval(monto11+('+')+imp_21_11);	// Total general
	             imp_tot_21 = eval(imp_tot_21+('+')+imp_21_11);
	             tot_general = eval(tot_general+('+')+tot_mon21_11) 
		
		         factura.totneto21.value = tot_mon.toFixed(2) ;	
		         factura.totiva21.value = imp_tot_21.toFixed(2) ;
		         factura.tot_general.value = tot_general.toFixed(2) ;
	} 
	
	//  LOTE TRECE
	if (monto12.length!=0) {
		   imp_21_12 = eval(monto12*imp21);              // Impuesto Monto 1 al 21 %
		   tot_mon21_12 = eval(monto11+('+')+imp_21_12);	// Total general
		   tot_mon = eval(tot_mon+('+')+monto11);
		   tot_mon21_12 = eval(monto12+('+')+imp_21_12);	// Total general
	       imp_tot_21 = eval(imp_tot_21+('+')+imp_21_12);
	       tot_general = eval(tot_general+('+')+tot_mon21_12) 
		
		   factura.totneto21.value = tot_mon.toFixed(2) ;	
		   factura.totiva21.value = imp_tot_21.toFixed(2) ;
		   factura.tot_general.value = tot_general.toFixed(2) ;
	
	} 
	
	//  LOTE CATORCE
	if (monto13.length!=0) {
		imp_21_13 = eval(monto13*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_13 = eval(monto11+('+')+imp_21_13);	// Total general
		tot_mon = eval(tot_mon+('+')+monto11);
		tot_mon21_13 = eval(monto13+('+')+imp_21_13);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_13);
	    tot_general = eval(tot_general+('+')+tot_mon21_13) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
	}
			
	//  LOTE QUINCE
	if (monto14.length!=0) {
	   imp_21_14 = eval(monto14*imp21);              // Impuesto Monto 1 al 21 %
	   tot_mon21_14 = eval(monto11+('+')+imp_21_14);	// Total general
	   tot_mon = eval(tot_mon+('+')+monto11);
	   tot_mon21_14 = eval(monto14+('+')+imp_21_14);	// Total general
	   imp_tot_21 = eval(imp_tot_21+('+')+imp_21_14);
	   tot_general = eval(tot_general+('+')+tot_mon21_14) 
		
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
	}
	
	if  (factura.GrupoOpciones1[0].checked == true && factura.pago_contado.checked == true )
	{
			  
		factura.leyenda.value = "Se emite una nota de credito por la cantidad de $ "+tot_general.toFixed(2);
	} 
    if ( series == '11' ) {
		// Oculto
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
		// Visible
		factura.totneto21_1.value = tot_general.toFixed(2);
		factura.totiva21_1.value = "-----";
		factura.tot_general_1.value = tot_general.toFixed(2) ;
	} else {
		// Oculto   
		factura.totneto21.value = tot_mon.toFixed(2) ;	
		factura.totiva21.value = imp_tot_21.toFixed(2) ;
		factura.tot_general.value = tot_general.toFixed(2) ;
		// Visible
		factura.totneto21_1.value = tot_mon.toFixed(2) ;	
		factura.totiva21_1.value = imp_tot_21.toFixed(2) ;
		factura.tot_general_1.value = tot_general.toFixed(2) ;
	}
}	
</script> 
<script language="javascript">
function agregarOpciones(form)
{
	var selec = form.tipos.options;

    if (selec[0].selected == true)
    {
		var seleccionar = new Option("<-- esperando selecci�n","","","");
    }

    if (selec[1].selected == true)
    {
		factura.serie.value = 1;
		factura.serie_texto.value = "NOTA DE DEBITO A0001";
		factura.tcomp.value = 21;
		//factura.num_factura.value = <?php //echo $facturanum1 ?>;
    }

    if (selec[2].selected == true)
    {
	
		factura.serie.value = 11;
		factura.serie_texto.value = "NOTA DE DEBITO B0002";
		factura.tcomp.value = 29;
		//factura.num_factura.value = <?php // echo $facturanum2 ?>;
    }
}
</script>
</script>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<script type="text/javascript" src="../js/ajax.js"></script>    
<script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script> 
<script type="text/javascript">
DHTMLSuite.include("chainedSelect");
</script>
<!-- Desde Aca !--> 
	<script type="text/javascript" src="AJAX/ajax.js"></script>
	<script type="text/javascript">
	// Cliente
	var ajax = new sack();
	var currentClientID=false;
	// Primer Lote
	
	function getClientData()
	{
		var clientId = document.getElementById('remate_num').value.replace(/[^0-9]/g,'');
		if( clientId!=currentClientID){
			currentClientID = clientId
			ajax.requestFile = 'getFact.php?getClientId='+clientId;	// Specifying which file to get
			ajax.onCompletion = showClientData;	// Specify function that will be executed after file has been found
			ajax.runAJAX();		// Execute AJAX function			
		}
	}
		
	function showClientData()
	{
		var formObj = document.forms['factura'];	
		eval(ajax.response);
	}
	
					
	function initFormEvents()
	{
	    var fecha1 = new Date();
		var dia = fecha1.getDate();
		var mes = (fecha1.getMonth()+1);
		var ano = fecha1.getYear();
		var fecha11 = dia+'/'+mes+'/'+ano;
		var fecha = ano+'-'+mes+'-'+dia;
		document.getElementById('fecha_factura').value = fecha ;
		document.getElementById('fecha_factura1').value = fecha11 ;
		document.getElementById('remate_num').onblur = getClientData;
		document.getElementById('remate_num').focus();
	}

	window.onload = initFormEvents;
</script>
<script language="javascript">
function pendiente(form)
{
	if (factura.GrupoOpciones1[0].checked ==false)
   	{
		factura.leyenda.value="Detalle de medio de pago segun recibo";
		factura.pago_contado.disabled= true;

   	} else {
		factura.leyenda.value="";
   		factura.pago_contado.disabled= false;
	}
}

</script>
<!-- Hasta Aca  !-->
<script language="javascript">
<!--
function neto(form)
{ importe = factura.importes.value; 
   document.write(importe);
  //alert (importe);

}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='El importe debe contener un n�mero.\n';
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
<?php //echo $sess ; ?>
<?php //echo $sess1 ; ?>
<form id="factura" name="factura" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="640" border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
    <tr>
      <td colspan="3" background="images/fondo_titulos.jpg"><div align="center"><img src="images/not_deb_aut.gif" width="364" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="3" valign="top" bgcolor="#003366"><table width="100%" border="0" cellspacing="1" cellpadding="1">
         <tr>
		 <td height="20" class="ewTableHeader">&nbsp;Tipo de Comprobante </td>
          <td width="24%"><select name="tipos" onChange="agregarOpciones(this.form)">
                                      <option value="">[seleccione una opci&oacute;n]</option>
                                      <option value="18">NOTA DEBITO A0001</option>
                                      <option value="27">NOTA DEBITO B0001</option>
                           </select>
		  <input name="tcomp" type="hidden"  size="25" >
		         </td>
          <td width="7%">&nbsp;</td>
          <td width="13%" class="ewTableHeader">&nbsp;Serie</td>
          <td width="34%"><input name="serie_texto" type="text"  size="25" />
		  <input name="serie" type="hidden"  size="25"/>
            </td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num Factura </td>
          <td><input name="num_factura" type="num_factura" class="phpmakerlist"  /></td>
          <td>&nbsp;</td>
          <td class="ewTableHeader">&nbsp;Fecha fact </td>
          <td><input name="fecha_factura1" type="text" id="fecha_factura1"  />
         <a href="javascript:showCal('Calendar4')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
        </tr>
        
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num. remate </td>
          <td><input name="remate_num" type="text" id="remate_num" /></td>
          <td>&nbsp;</td>
          <td colspan="2" rowspan="4" valign="top" bgcolor="#0094FF" ><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#003366">
            <tr>
              <td colspan="2" bgcolor="#0094FF" align="center"><img src="images/cond_pago.gif" width="150" height="30" /></td>
            </tr>
            <tr>
              <td width="48%" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Contado</span></td>
              <td width="52%" bgcolor="#0094FF"><input name="GrupoOpciones1" type="radio" value="S" checked="checked"onclick="pendiente(this.form)"  /></td>
            </tr>
            <tr>
              <td bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Pendiente de pago</span></td>
              <td bgcolor="#0094FF"><input type="radio" name="GrupoOpciones1" value="P" onClick="pendiente(this.form)"/></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td height="9" class="ewTableHeader">Lugar del remate </td>
          <td><input name="lugar_remate" type="text" id="lugar_remate" /></td>
          <td>&nbsp;</td>
        </tr>
		<tr>
         <td height="20" class="ewTableHeader">Fecha de remate</td>
          <td><input name="fecha_remate" type="text" size="12" /></td>
          <td><input name="fecha_factura" type="hidden" size="12" /></td>
          </tr>
        <tr>
          <td height="10" class="ewTableHeader"> Cliente </td>
          <td><select name="codnum" id="codnum">
            <option value="">Cliente</option>
		
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
          </select></td>
          <td>&nbsp;</td>
        </tr>
        
      </table></td>
    </tr>
    <tr>
      <td colspan="3"  background="images/separador3.gif"></td>
    </tr>
    <tr>
      <td colspan="3" valign="top">
	  <table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">
        <tr>
          <td width="39" class="ewTableHeader">
              <div align="center">Concepto</div></td>
          <td width="369" class="ewTableHeader">
              <div align="center">Descripci&oacute;n</div></td>
          
          <td width="75" class="ewTableHeader">
              <div align="center">Importe</div></td>
        </tr>
       
        
		<tr>
          <td bgcolor="#0094FF"><input name="lote" type="text" id="lote" size="5" value="1" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="75" />		  </td><input name="secuencia" type="hidden" class="phpmaker"  size="75"  value="1"/>
         
          <td width="75" bgcolor="#0094FF"><input name="importe" type="text" id="importe"  onchange="validarFormulario(this.form)" size="10"   /></td>
          </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote1" type="text" id="lote1" size="5" value="2" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="75" /></td>
         
    <td width="75" bgcolor="#0094FF"><input name="importe1" type="text" id="importe1" onchange="validarFormulario(this.form)" size="10"  /></td>
	<input name="secuencia1" type="hidden" class="phpmaker" id="secuencia1" size="65" value="2" />
	    </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote2" type="text" id="lote2" size="5" value="3" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="75" /></td>
        
          <td width="75" bgcolor="#0094FF"><input name="importe2" type="text" id="importe2" onchange="validarFormulario(this.form)" size="10" /></td>
		  <input name="secuencia2" type="hidden" class="phpmaker" id="secuencia2" size="65" value="3"/> 
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote3" type="text" id="lote3" size="5" value="4" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe3" type="text" id="importe3" onchange="validarFormulario(this.form)" size="10" /></td>
		  <input name="secuencia3" type="hidden" class="phpmaker" id="secuencia3" size="65" value="4"/>
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote4" type="text" id="lote4" size="5" value="5" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="75" /></td>
           
          <td width="75" bgcolor="#0094FF"><input name="importe4" type="text" id="importe4" onchange="validarFormulario(this.form)" size="10" /></td>
		   <input name="secuencia4" type="hidden" class="phpmaker" id="secuencia4" size="65" value="5"/>
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote5" type="text" id="lote5" size="5" value="6" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="75" /></td>
         
          <td width="75" bgcolor="#0094FF"><input name="importe5" type="text" id="importe5" onchange="validarFormulario(this.form)" size="10" /></td>
		  <input name="secuencia5" type="hidden" class="phpmaker" id="secuencia5" size="65" value="6"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote6" type="text" id="lote6" size="5" value="7" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="75" /></td>
          
		   <td width="75" bgcolor="#0094FF"><input name="importe6" type="text" id="importe6" onchange="validarFormulario(this.form)" size="10" /></td>
           <input name="secuencia6" type="hidden" class="phpmaker" id="secuencia6" size="65" value="7"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote7" type="text" id="lote7" size="5" value="8" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe7" type="text" id="importe7" onchange="validarFormulario(this.form)" size="10" /></td>
		 <input name="secuencia7" type="hidden" class="phpmaker" id="secuencia7" size="65" value="8"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote8" type="text" id="lote8" size="5" value="9" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe8" type="text" id="importe8" onchange="validarFormulario(this.form)" size="10" /></td>
		  <input name="secuencia8" type="hidden" class="phpmaker" id="secuencia8" size="65" value="9" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote9" type="text" id="lote9" size="5" value="10" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe9" type="text" id="importe9" onchange="validarFormulario(this.form)" size="10" /></td>
		  <input name="secuencia9" type="hidden" class="phpmaker" id="secuencia9" size="65" value="10"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote10" type="text" id="lote10" size="5" value="11" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="75" /></td>
         
          <td width="75" bgcolor="#0094FF"><input name="importe10" type="text" id="importe10" onchange="validarFormulario(this.form)" size="10" /></td>
		  <input name="secuencia10" type="hidden" class="phpmaker" id="secuencia10" size="65" value="11"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote11" type="text" id="lote11" size="5" value="12" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="75" /></td>
          
          <td width="75" bgcolor="#0094FF"><input name="importe11" type="text" id="importe11" onchange="validarFormulario(this.form)" size="10" /></td>
		  <input name="secuencia11" type="hidden" class="phpmaker" id="secuencia11" size="65" value="12"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote12" type="text" id="lote12" size="5" value="13" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="75" /></td>
         
          <td width="75" bgcolor="#0094FF"><input name="importe12" type="text" id="importe12" onchange="validarFormulario(this.form)" size="10" /></td>
		  <input name="secuencia12" type="hidden" class="phpmaker" id="secuencia12" size="65" value="13"/>
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote13" type="text" id="lote13" size="5" value="14" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="75" /></td>
        
          <td width="75" bgcolor="#0094FF"><input name="importe13" type="text" id="importe13" onchange="validarFormulario(this.form)" size="10" /></td>
		 <input name="secuencia13" type="hidden" class="phpmaker" id="secuencia13" size="65" value="14"/>
		</tr> <tr>
          <td bgcolor="#0094FF"><input name="lote14" type="text" id="lote14" size="5" value="15" readonly=""/></td>
          <td bgcolor="#0094FF"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="75" /></td>
       
          <td width="75" bgcolor="#0094FF"><input name="importe14" type="text" id="importe14" onchange="validarFormulario(this.form)" size="10" /></td>
		 <input name="secuencia14" type="hidden" class="phpmaker" id="secuencia14" size="65" value="15"/>
		</tr>
      </table>      </td>
    </tr>
    <tr>
      <td width="280" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF"><tr>
      <td width="71" height="20" valign="top" class="ewTableHeader">&nbsp;Leyenda</td>
      <td width="280"  valign="top"><textarea name="leyenda" cols="55" rows="4"></textarea></td>
    </tr>
	<tr><td class="ewTableHeader">&nbsp;Imprimir </td><td><input type="checkbox" name="imprime" value="1" />
</td>
	</tr>
	 </table></td>
      <td width="281" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        
        <tr>
          <td colspan="2"><div align="center"><img src="images/con_pago.gif" width="140" height="28" /></div></td>
          </tr>
        <tr>
          <td>&nbsp;<span class="ewTableHeader">Efectivo</span></td>
		  
          <td><input name="pago_contado" type="checkbox" onClick="forma_pago(this.form)" value="si" checked="checked"/></td>
        </tr>
      </table>    </tr>
    <tr>
      <td height="20" valign="top"><input name="iva21" type="hidden" size="12" value="<?php echo $iva_21_porcen     ?>"/></td>
    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
  
    <tr>
      <td colspan="3" bgcolor="#003366"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        <tr>
         
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php echo $iva_21_porcen ?> %</div></td>
          
          
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php echo $iva_21_porcen ?> %</div></td>
          
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Total </div></td>
        </tr>
         <tr>
          
          <td align="center"><input name="totneto21" type="hidden" id="totneto21" size="12" /></td>
          
         
          <td align="center"><input name="totiva21"  type="hidden"  id="totiva21" size="12" /></td>
          
          <td align="center"><input name="tot_general" type="hidden"  id="tot_general" size="15" /></td>
        </tr>
        <tr>
          
          <td align="center"><input name="totneto21_1" type="text" id="totneto21_1" size="12" /></td>
          
         
          <td align="center"><input name="totiva21_1"  type="text" id="totiva21_1" size="12" /></td>
          
          <td align="center"><input name="tot_general_1" type="text" id="tot_general_1" size="15" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
         
          <td colspan="3"  align="center">
            <input type="submit" name="Submit3" value="Grabar" />
           </td>
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
<?php
mysql_free_result($tipo_comprobante);
mysql_free_result($cliente);
mysql_free_result($num_remates);
?>