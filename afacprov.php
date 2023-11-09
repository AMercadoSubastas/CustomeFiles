<?php 
error_reporting(E_ALL);
ini_set('display_errors','Yes');
//setlocale (LC_ALL,"es_ES.UTF-8");
include_once "funcion_mysqli_result.php";
require_once('Connections/amercado.php'); 
//Conecto con la  base de datos
mysqli_select_db($amercado, $database_amercado);

$cod_usuario = 1; //$_SESSION['id'];
/*
echo "USUARIO ".$cod_usuario."  ";
*/
$cbtes= array();
$prov = array();
$tipo = array();
$totneto105 = 0.00;
$totiva105 = 0.00;
$totneto21 = 0.00;
$totiva21 = 0.00;



mysqli_select_db($amercado, $database_amercado);
	$query_cf_prov = sprintf("SELECT * FROM cabfac WHERE  fecreg > %s AND tcomp BETWEEN %d AND %d", '2017-01-01', 32, 37);
	$cf_prov = mysqli_query($amercado, $query_cf_prov) or die("ERROR");
	$k = 0;
	while($row_cf_prov = mysqli_fetch_array($cf_prov, MYSQLI_BOTH)) {
		$cbtes[$k] = $row_cf_prov['nrodoc']; 
		$prov[$k] = $row_cf_prov['cliente']; 
		$tipo[$k] = $row_cf_prov['tcomp']; 
		$k++;
	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$renglones = 0;
$primera_vez = 1;
$siga = 0;
// VERIFICAR QUE LA FACTURA NO EXISTA PARA ESTE PROVEEDOR
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
	mysqli_select_db($amercado, $database_amercado);
	$query_cf_prov = sprintf("SELECT * FROM cabfac WHERE nrodoc=%s AND cliente = %s AND tcomp = %s", GetSQLValueString($_POST['ncomp'], "text"),GetSQLValueString($_POST['codnum'], "int"),GetSQLValueString($_POST['tcomp'], "int"));
	$cf_prov = mysqli_query($amercado, $query_cf_prov) or die("ERROR");
	$k = 0;
	while($row_cf_prov = mysqli_fetch_array($cf_prov, MYSQLI_BOTH)) {
		$k++;
	}
	if ($k==0) {
		$siga = 1; 
    }
	else {
		$facnum = GetSQLValueString($_POST['ncomp'], "text");
		$tipocomp = GetSQLValueString($_POST['serie_texto'], "text");
        $siga = 0;
 		$insertGoTo = "facturap_repe.php?factura=$facnum&tcomp=$tipocomp";
  		if (isset($_SERVER['QUERY_STRING'])) {
    		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    		$insertGoTo .= $_SERVER['QUERY_STRING'];
  		}
  		header(sprintf("Location: %s", "facturap_repe.php?factura=$facnum&tcomp=$tipocomp")); 
	}
    // ACA VALIDO QUE CARGUEN EL TIPO DE CBTE QUE CORRESPONDA DE ACUERDO A LA CAT DEL PROVEEDOR
    $prove = GetSQLValueString($_POST['codnum'], "int");
    mysqli_select_db($amercado, $database_amercado);
    $query_pro = sprintf("SELECT * FROM entidades WHERE codnum=%s ", $prove);
    $pro = mysqli_query($amercado, $query_pro) or die("ERROR");
    $row_pro = mysqli_fetch_assoc($pro);
    $cond_iva = $row_pro["tipoiva"];
    $tipo_cbte = GetSQLValueString($_POST['tcomp'], "int");
	echo "tipo_cbte = ".$tipo_cbte." - ";
	echo "cond_iva = ".$cond_iva." - ";
    if ($cond_iva != 1 && ($tipo_cbte == 32 || $tipo_cbte == 34 || $tipo_cbte == 36 || $tipo_cbte == 110)) {
        $siga = 0;
        echo "le pifiaste en el tipo de factura o en la cond de iva del proveedor";
    }
    if ($cond_iva == 1 && ($tipo_cbte == 33 || $tipo_cbte == 35 || $tipo_cbte == 37)) {
        $siga = 0;
        echo "le pifiaste en el tipo de factura  o en la cond de iva del proveedor";
    }
}
//echo "si llegaste hasta aca y siga == 0 y la factura no está repetida, hay un moco importante... SIGA = ".$siga." - ";
//$siga = 1; // se lo fuerzo para que grabe la factura repetida, falta que este valor venga de facturap_repe.php
if ($siga) {
if (isset($_POST['descripcion']) && (GetSQLValueString($_POST['descripcion'], "text")!="NULL")) {
	// DESDE ACA ===================================================================================
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
        mysqli_select_db($amercado, $database_amercado);
        $query_comprobante = sprintf("SELECT * FROM series  WHERE `series`.`codnum` = %s",GetSQLValueString($_POST['serie'], "int"));
        $comprobante = mysqli_query($amercado, $query_comprobante) or die("ERROR LEYENDO SERIES");
        $row_comprobante = mysqli_fetch_assoc($comprobante);
        $totalRows_comprobante = mysqli_num_rows($comprobante);
        $num_comp = ($row_comprobante['nroact'])+1 ;
		mysqli_select_db($amercado, $database_amercado);
		$actualiza1 = sprintf("UPDATE `series` SET `nroact` = %s WHERE `series`.`codnum` = %s", 
			$num_comp, //GetSQLValueString($_POST['num_factura'], "int"), 
			GetSQLValueString($_POST['serie'], "int")) ;				 
		$resultado=mysqli_query($amercado, 	$actualiza1);	

	}
	// HASTA ACA ===================================================================================
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
		$tcomp = $_POST['tcomp'] ;
 		$serie = $_POST['serie'] ;
 		if ($tcomp==36 && $serie== 20 ) {
  			$importe = $_POST['importe'];

  		} else {
  			$importe = $_POST['importe']; 
  		}
  		$porciva = $_POST['tipoiva'];
		$iva = ($importe * $porciva) / 100.0; 
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng,  iva, porciva, descrip, neto,  concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia'], "int"),
                       GetSQLValueString($iva, "double"),
					   GetSQLValueString($_POST['tipoiva'], "double"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($importe, "double"),
					   GetSQLValueString($_POST['concepto'], "int"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 1;
        //echo "grabo el renglón 1 ";
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");

	}
}
if (isset($_POST['descripcion1']) && GetSQLValueString($_POST['descripcion1'], "text")!="NULL") {

 	$tcomp = $_POST['tcomp'] ;
 	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
  		$importe1 = $_POST['importe1'];
  	} else {
  		$importe1 = $_POST['importe1']; 
  	}
	
	$porciva1 = $_POST['tipoiva1'];
	$iva1 = ($importe1 * $porciva1) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s,  %s, %s, %s, %s,  %s, %s,  %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia1'], "int"),
					   GetSQLValueString($iva1, "double"),
					   GetSQLValueString($_POST['tipoiva1'], "double"),
                       GetSQLValueString($_POST['descripcion1'], "text"),
                       GetSQLValueString($importe1, "double"),
                       GetSQLValueString($_POST['concepto1'], "double"),
				       $cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 2;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
 
	}
}
if (isset($_POST['descripcion2']) && GetSQLValueString($_POST['descripcion2'], "text")!="NULL") {
	$tcomp = $_POST['tcomp'] ;
 	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
  		$importe2 = $_POST['importe2'];
  	} else {
  		$importe2 = $_POST['importe2']; 
  	}
	
	$porciva2 = $_POST['tipoiva2'];
	$iva2 = ($importe2 * $porciva2) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia2'], "int"),
					   GetSQLValueString($iva2, "double"),
					   GetSQLValueString($_POST['tipoiva2'], "double"),
                       GetSQLValueString($_POST['descripcion2'], "text"),
                       GetSQLValueString( $importe2 , "double"),
                       GetSQLValueString($_POST['concepto2'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 3;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
 
	}
}
if (isset($_POST['descripcion3']) && GetSQLValueString($_POST['descripcion3'], "text")!="NULL") {
  	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe3 = $_POST['importe3'];
  	} else {
  		$importe3 = $_POST['importe3']; 
  	}
	
	$porciva3 = $_POST['tipoiva3'];
	$iva3 = ($importe3 * $porciva3) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s,  %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia3'], "int"),
					   GetSQLValueString($iva3, "double"),
					   GetSQLValueString($_POST['tipoiva3'], "double"),
                       GetSQLValueString($_POST['descripcion3'], "text"),
                       GetSQLValueString($importe3, "double"),
                       GetSQLValueString($_POST['concepto3'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 4;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
 
	}
}
if (isset($_POST['descripcion4']) && GetSQLValueString($_POST['descripcion4'], "text")!="NULL") {
 	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe4 = $_POST['importe4'];
  	} else {
  		$importe4 = $_POST['importe4']; 
  	}
	
	$porciva4 = $_POST['tipoiva4'];
	$iva4 = ($importe4 * $porciva4) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia4'], "int"),
					   GetSQLValueString($iva4, "double"),
					   GetSQLValueString($_POST['tipoiva4'], "double"),
                       GetSQLValueString($_POST['descripcion4'], "text"),
					   GetSQLValueString( $importe4 , "double"),
                       GetSQLValueString($_POST['concepto4'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 5;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
	}
}
if (isset($_POST['descripcion5']) && GetSQLValueString($_POST['descripcion5'], "text")!="NULL") {
 	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe5 = $_POST['importe5'];
  	} else {
  		$importe5 = $_POST['importe5']; 
  	}
	
	$porciva5 = $_POST['tipoiva5'];
	$iva5 = ($importe5 * $porciva5) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia5'], "int"),
					   GetSQLValueString($iva5, "double"),
					   GetSQLValueString($_POST['tipoiva5'], "double"),
                       GetSQLValueString($_POST['descripcion5'], "text"),
                       GetSQLValueString($importe5, "double"),
                       GetSQLValueString($_POST['concepto5'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 6;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");

	}
}
if (isset($_POST['descripcion6']) && GetSQLValueString($_POST['descripcion6'], "text")!="NULL") {
	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe6 = $_POST['importe6'];
  	} else {
  		$importe6 = $_POST['importe6']; 
  	}
	
	$porciva6 = $_POST['tipoiva6'];
	$iva6 = ($importe6 * $porciva6) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia6'], "int"),
					   GetSQLValueString($iva6, "double"),
					   GetSQLValueString($_POST['tipoiva6'], "double"),
                       GetSQLValueString($_POST['descripcion6'], "text"),
                       GetSQLValueString($importe6, "double"),
                       GetSQLValueString($_POST['concepto6'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 7;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
	}
}
if (isset($_POST['descripcion7']) && GetSQLValueString($_POST['descripcion7'], "text")!="NULL") {
	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe7 = $_POST['importe7'];
  	} else {
  		$importe7 = $_POST['importe7']; 
  	}
	
	$porciva7 = $_POST['tipoiva7'];
	$iva7 = ($importe7 * $porciva7) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia7'], "int"),
					   GetSQLValueString($iva7, "double"),
					   GetSQLValueString($_POST['tipoiva7'], "double"),
                       GetSQLValueString($_POST['descripcion7'], "text"),
                       GetSQLValueString($importe7, "double"),
                       GetSQLValueString($_POST['concepto7'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 8;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
	}
}

if (isset($_POST['descripcion8']) && GetSQLValueString($_POST['descripcion8'], "text")!="NULL") {
  	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe8 = $_POST['importe8'];
  	} else {
  		$importe8 = $_POST['importe8']; 
  	}
	
	$porciva8 = $_POST['tipoiva8'];
	$iva8 = ($importe8 * $porciva8) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
   		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia8'], "int"),
					   GetSQLValueString($iva8, "double"),
					   GetSQLValueString($_POST['tipoiva8'], "double"),
                       GetSQLValueString($_POST['descripcion8'], "text"),
                       GetSQLValueString($importe8 , "double"),
                       GetSQLValueString($_POST['concepto8'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 9;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
	}
}
if (isset($_POST['descripcion9']) && GetSQLValueString($_POST['descripcion9'], "text")!="NULL") {
  	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe9 = $_POST['importe9'];
  	} else {
    	$importe9 = $_POST['importe9']; 
  	}
	
	$porciva9 = $_POST['tipoiva9'];
	$iva9 = ($importe9 * $porciva9) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
   		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia9'], "int"),
					   GetSQLValueString($iva9, "double"),
					   GetSQLValueString($_POST['tipoiva9'], "double"),
                       GetSQLValueString($_POST['descripcion9'], "text"),
                       GetSQLValueString($importe9 , "double"),
                       GetSQLValueString($_POST['concepto9'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 10;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
  
	}
}
if (isset($_POST['descripcion10']) && GetSQLValueString($_POST['descripcion10'], "text")!="NULL") {
 	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe10 = $_POST['importe10'];
  	} else {
    	$importe10 = $_POST['importe10']; 
  	}
	
	$porciva10 = $_POST['tipoiva10'];
	$iva10 = ($importe10 * $porciva10) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia10'], "int"),
					   GetSQLValueString($iva10, "double"),
					   GetSQLValueString($_POST['tipoiva10'], "double"),
                       GetSQLValueString($_POST['descripcion10'], "text"),
                       GetSQLValueString( $importe10 , "double"),
                       GetSQLValueString($_POST['concepto10'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 11;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
 
	}
}
if (isset($_POST['descripcion11']) && GetSQLValueString($_POST['descripcion11'], "text")!="NULL") {
 	$tcomp = $_POST['tcomp'] ;
  	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe11 = $_POST['importe11'];
  	} else {
    	$importe11 = $_POST['importe11']; 
  	}
	
	$porciva11 = $_POST['tipoiva11'];
	$iva11 = ($importe11 * $porciva11) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia11'], "int"),
					   GetSQLValueString($iva11, "double"),
					   GetSQLValueString($_POST['tipoiva11'], "double"),
                       GetSQLValueString($_POST['descripcion11'], "text"),
                       GetSQLValueString($importe11, "double"),
                       GetSQLValueString($_POST['concepto11'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 12;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
	}
}
if (isset($_POST['descripcion12']) && GetSQLValueString($_POST['descripcion12'], "text")!="NULL") {
 	$tcomp = $_POST['tcomp'] ;
 	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe12 = $_POST['importe12'];
  	} else {
    	$importe12 = $_POST['importe12']; 
  	}
	
	$porciva12 = $_POST['tipoiva12'];
	$iva12 = ($importe12 * $porciva12) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
   		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia12'], "int"),
					   GetSQLValueString($iva12, "double"),
					   GetSQLValueString($_POST['tipoiva12'], "double"),
                       GetSQLValueString($_POST['descripcion12'], "text"),
                       GetSQLValueString($importe12 , "double"),
                       GetSQLValueString($_POST['concepto12'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 13;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
	}
}
if (isset($_POST['descripcion13']) && GetSQLValueString($_POST['descripcion13'], "text")!="NULL") {
 	$tcomp = $_POST['tcomp'] ;
 	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe13 = $_POST['importe13'];
  	} else {
    	$importe13 = $_POST['importe13']; 
  	}
	
	$porciva13 = $_POST['tipoiva13'];
	$iva13 = ($importe13 * $porciva13) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
   		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia13'], "int"),
					   GetSQLValueString($iva13, "double"),
					   GetSQLValueString($_POST['tipoiva13'], "double"),
                       GetSQLValueString($_POST['descripcion13'], "text"),
                       GetSQLValueString($importe13 , "double"),
                       GetSQLValueString($_POST['concepto13'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 14;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
	}
}
if (isset($_POST['descripcion14']) && GetSQLValueString($_POST['descripcion14'], "text")!="NULL") {
  	$tcomp = $_POST['tcomp'] ;
 	$serie = $_POST['serie'] ;
 	if ($tcomp==36 && $serie== 20 ) {
    	$importe14 = $_POST['importe14'];
  	} else {
    	$importe14 = $_POST['importe14']; 
  	}
	
	$porciva14 = $_POST['tipoiva14'];
	$iva14 = ($importe14 * $porciva14) / 100.0; 
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, iva, porciva, descrip, neto, concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia14'], "int"),
					   GetSQLValueString($iva14, "double"),
					   GetSQLValueString($_POST['tipoiva14'], "double"),
                       GetSQLValueString($_POST['descripcion14'], "text"),
                       GetSQLValueString($importe14 , "double"),
                       GetSQLValueString($_POST['concepto14'], "double"),
						$cod_usuario);

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 15;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");
	}
}

if (isset($_POST['descripcion']) && GetSQLValueString($_POST['descripcion'], "text")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "proveedor")) {

        if (isset($_POST['totneto105']) && GetSQLValueString($_POST['totneto105'], "double")!="NULL")
			$totneto105 = $_POST['totneto105'] ;
	  	if (isset($_POST['totiva105']) && GetSQLValueString($_POST['totiva105'], "double")!="NULL")
			$totiva105  = $_POST['totiva105'] ;
    	if (isset($_POST['totiva27']) && GetSQLValueString($_POST['totiva27'], "double")!="NULL") {
	    	$totiva105  += $_POST['totiva27'] ;
		}
		if (isset($_POST['totiva025']) && GetSQLValueString($_POST['totiva025'], "double")!="NULL") {
			$totiva105  += $_POST['totiva025'] ;
		}
		if (isset($_POST['totiva05']) && GetSQLValueString($_POST['totiva05'], "double")!="NULL") {
			$totiva105  += $_POST['totiva05'] ;
		}

                 
	    $tcomp = $_POST['tcomp'] ;
        $serie = $_POST['serie'] ;
			  
        if ($tcomp==33 && $serie== 17 ) {
        
			$tot_general = $_POST['tot_general'] ;
			$tot_neto = $_POST['tot_general'] ;
			$totiva21 = $_POST['totiva21'];
			$totneto21 =  $_POST['totneto'] ;
		} else {
			$tot_neto = $_POST['totneto'] ;
			$tot_general = $_POST['tot_general'] ;
			$totiva21 =   $_POST['totiva21'];
			$totneto21 =  $_POST['totneto'] ;
		}
	
		$fecha_prov = $_POST['fecha_f2'];
		$fecha_prov = substr($fecha_prov,6,4)."-".substr($fecha_prov,3,2)."-".substr($fecha_prov,0,2);
        $fecha_prov2 = $_POST['fecha_f3'];
		$fecha_prov2 = substr($fecha_prov2,6,4)."-".substr($fecha_prov2,3,2)."-".substr($fecha_prov2,0,2);
		$hoy = date("Y-m-d H:i:s");
		$insertSQL = sprintf("INSERT INTO cabfac (tcomp, serie, ncomp, fecval, fecdoc, fecreg, cliente, fecvenc, estado, emitido, totneto , totbruto, totiva105, totiva21,   totneto105, totneto21, nrengs, concepto ,nrodoc, usuario, usuarioultmod) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,  %s ,%s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_comp, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($fecha_prov , "date"),
                       GetSQLValueString($fecha_prov2 , "date"),
                       GetSQLValueString($fecha_prov2 , "date"),
                       GetSQLValueString($_POST['codnum'], "int"),
                       GetSQLValueString($fecha_prov2, "date"),
				       GetSQLValueString($_POST['GrupoOpciones1'], "text"), 
					   GetSQLValueString("0", "int"),
					   GetSQLValueString($tot_neto , "double"),
                       GetSQLValueString($tot_general , "double"),
                       GetSQLValueString($totiva105, "double"),
                       GetSQLValueString($totiva21 , "double"),
                       GetSQLValueString( $totneto105 , "double"),
                       GetSQLValueString($totneto21, "double"),
					   GetSQLValueString($renglones, "int"),
					   GetSQLValueString("1", "int"),
                       GetSQLValueString($_POST['ncomp'], "text"),
						$cod_usuario,
						$cod_usuario);
        //echo "grabo la cabecera ";
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $insertSQL) or die("ERROR");

		

	 	$facnum = GetSQLValueString($_POST['ncomp'], "text");
		$tipocomp = GetSQLValueString($_POST['serie_texto'], "text");
        echo "voy a factura ok ";
 		$insertGoTo = "facturap_ok.php?factura=$facnum&tcomp=$tipocomp";
  		if (isset($_SERVER['QUERY_STRING'])) {
    		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    		$insertGoTo .= $_SERVER['QUERY_STRING'];
  		}
  		header(sprintf("Location: %s", "facturap_ok.php?factura=$facnum&tcomp=$tipocomp")); 

	}
}

} // if ($siga)...

mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esprovedor= '1' ";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die("ERROR LEYENDO TIPCOMP");
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE tipoent='3' and activo = '1' ORDER BY razsoc  ASC";
$cliente = mysqli_query($amercado, $query_cliente) or die("ERROR LEYENDO ENTIDADES");
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

if (isset($_POST['tcomp'])) {
  $colname_serie = addslashes($_POST['tcomp']);
}

mysqli_select_db($amercado, $database_amercado);
$query_conceptos_a_facturar = "SELECT * FROM concafact ORDER BY nroconc  ASC";
$conceptos_a_facturar = mysqli_query($amercado, $query_conceptos_a_facturar) or die("ERROR");
$row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
$totalRows_conceptos_a_facturar = mysqli_num_rows($conceptos_a_facturar);

$query_impuesto = "SELECT * FROM impuestos WHERE rangos = 0 ORDER BY porcen DESC";
$impuesto = mysqli_query($amercado, $query_impuesto) or die("ERROR");
$row_impuesto = mysqli_fetch_assoc($impuesto);
$totalRows_impuesto = mysqli_num_rows($impuesto);

mysqli_select_db($amercado, $database_amercado);
$query_facturas_a = "SELECT * FROM series  WHERE series.tipcomp=32  AND series.codnum=16";
$facturas_a = mysqli_query($amercado, $query_facturas_a) or die("ERROR");
$row_facturas_a = mysqli_fetch_assoc($facturas_a);
$totalRows_facturas_a = mysqli_num_rows($facturas_a);
$facturanum1 = ($row_facturas_a['nroact'])+1;
$series1 = 16 ;

$query_facturas_b = "SELECT * FROM series  WHERE series.tipcomp=33  AND series.codnum=17";
$facturas_b = mysqli_query($amercado, $query_facturas_b) or die("ERROR");
$row_facturas_b = mysqli_fetch_assoc($facturas_b);
$totalRows_facturas_b = mysqli_num_rows($facturas_b);
$facturanum2= ($row_facturas_b['nroact'])+1;
$series2= 17;

$query_facturas_c = "SELECT * FROM series  WHERE series.tipcomp=34  AND series.codnum=18";
$facturas_c = mysqli_query($amercado, $query_facturas_c) or die("ERROR");
$row_facturas_c = mysqli_fetch_assoc($facturas_c);
$totalRows_facturas_c = mysqli_num_rows($facturas_c);
$facturanum3= ($row_facturas_c['nroact'])+1;
$series3= 18;

$query_facturas_d = "SELECT * FROM series  WHERE series.tipcomp=35  AND series.codnum=19";
$facturas_d = mysqli_query($amercado, $query_facturas_d) or die("ERROR");
$row_facturas_d = mysqli_fetch_assoc($facturas_d);
$totalRows_facturas_d = mysqli_num_rows($facturas_d);
$facturanum4= ($row_facturas_d['nroact'])+1;
$series4= 19;

$query_facturas_e = "SELECT * FROM series  WHERE series.tipcomp=36  AND series.codnum=20";
$facturas_e = mysqli_query($amercado, $query_facturas_e) or die("ERROR");
$row_facturas_e = mysqli_fetch_assoc($facturas_e);
$totalRows_facturas_e = mysqli_num_rows($facturas_e);
$facturanum5= ($row_facturas_e['nroact'])+1;
$series5= 20;

$query_facturas_e = "SELECT * FROM series  WHERE series.tipcomp=37  AND series.codnum=21";
$facturas_e = mysqli_query($amercado, $query_facturas_e) or die("ERROR");
$row_facturas_e = mysqli_fetch_assoc($facturas_e);
$totalRows_facturas_e = mysqli_num_rows($facturas_e);
$facturanum6= ($row_facturas_e['nroact'])+1;
$series6= 21;

$query_facturas_f = "SELECT * FROM series  WHERE series.tipcomp=65  AND series.codnum=33";
$facturas_f = mysqli_query($amercado, $query_facturas_f) or die("ERROR");
$row_facturas_f = mysqli_fetch_assoc($facturas_f);
$totalRows_facturas_f = mysqli_num_rows($facturas_f);
$facturanum7= ($row_facturas_f['nroact'])+1;
$series7= 33;

$query_facturas_g = "SELECT * FROM series  WHERE series.tipcomp=107  AND series.codnum=47";
$facturas_g = mysqli_query($amercado, $query_facturas_g) or die("ERROR");
$row_facturas_g = mysqli_fetch_assoc($facturas_g);
$totalRows_facturas_g = mysqli_num_rows($facturas_g);
$facturanum8= ($row_facturas_g['nroact'])+1;
$series8= 47;

$query_facturas_h = "SELECT * FROM series  WHERE series.tipcomp=110  AND series.codnum=48";
$facturas_h = mysqli_query($amercado, $query_facturas_h) or die("ERROR");
$row_facturas_h = mysqli_fetch_assoc($facturas_h);
$totalRows_facturas_h = mysqli_num_rows($facturas_h);
$facturanum9= ($row_facturas_h['nroact'])+1;
$series9= 48;


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<?php
 require_once('Connections/amercado.php');  ?>
<script language="javascript" src="cal2.js">
</script>
<script language="javascript" src="cal_conf2.js"></script>

<script language="javascript">
function agregarOpciones(form)
{
	var selec = form.tipos.options;
    if (selec[0].selected == true)
    {
	    var seleccionar = new Option("<-- esperando selecciÃ³n","","","");
     
    }

    if (selec[1].selected == true)
    {
	  proveedor.serie.value = 16;
	  proveedor.serie_texto.value ="FACTURA PROVEEDOR A";
	  proveedor.tcomp.value = 32;
	  proveedor.num_factura.value = <?php echo $facturanum1 ?>;
      var serie = 16;
    }

    if (selec[2].selected == true)
    {
      proveedor.serie.value = 17;
	  proveedor.serie_texto.value ="FACTURA PROVEEDOR C";
	  proveedor.tcomp.value = 33;
	  var serie = 17 ;
	  proveedor.num_factura.value = <?php echo $facturanum2 ?>;
	}
	
	if (selec[3].selected == true)
    {
      proveedor.serie.value = 33;
	  proveedor.serie_texto.value ="SERIE FACTURA M";
	  proveedor.tcomp.value = 65;
	  var serie = 33 ;
	  proveedor.num_factura.value = <?php echo $facturanum7 ?>;
 
    }
	if (selec[4].selected == true)
    {
      proveedor.serie.value = 18;
	  proveedor.serie_texto.value ="SERIE NOTA DEBITO A";
	  proveedor.tcomp.value = 34;
	  proveedor.num_factura.value = <?php echo $facturanum3 ?>;
	  var serie = 18;
 
	}
	if (selec[5].selected == true)
    {
      proveedor.serie.value = 19;
	  proveedor.serie_texto.value ="SERIE NOTA DEBITO C";
	  proveedor.tcomp.value = 35;
	  var serie =19
	  proveedor.num_factura.value = <?php echo $facturanum4 ?>;
    }
	
	if (selec[6].selected == true)
    {
      proveedor.serie.value = 36;
	  proveedor.serie_texto.value ="SERIE NOTA DEBITO M";
	  proveedor.tcomp.value = 88;
	  var serie =36
	  proveedor.num_factura.value = <?php echo $facturanum4 ?>;
    }
	
  	if (selec[7].selected == true)
    {
      proveedor.serie.value = 20;
	  proveedor.serie_texto.value ="SERIE NOTA CREDITO A";
	  proveedor.tcomp.value = 36;
	  proveedor.num_factura.value = <?php echo $facturanum5 ?>;
      var serie =20
    }
	
	if (selec[8].selected == true)
    {
      proveedor.serie.value = 21;
	  proveedor.serie_texto.value ="SERIE NOTA CREDITO C";
	  proveedor.tcomp.value = 37;
	  var serie = 21 ;
	  proveedor.num_factura.value = <?php echo $facturanum6 ?>;
 
    }
	
	if (selec[9].selected == true)
    {
      proveedor.serie.value = 35;
	  proveedor.serie_texto.value ="SERIE NOTA CREDITO M";
	  proveedor.tcomp.value = 87;
	  var serie = 35 ;
	  proveedor.num_factura.value = <?php echo $facturanum7 ?>;
 
    }
    
    if (selec[10].selected == true)
    {
      proveedor.serie.value = 47;
	  proveedor.serie_texto.value ="SERIE FACT PROV EXT";
	  proveedor.tcomp.value = 107;
	  var serie = 47 ;
	  proveedor.num_factura.value = <?php echo $facturanum8 ?>;
 
    }
    
    if (selec[11].selected == true)
    {
      proveedor.serie.value = 48;
	  proveedor.serie_texto.value ="SERIE LIQU. DE GASTOS";
	  proveedor.tcomp.value = 110;
	  var serie = 48 ;
	  proveedor.num_factura.value = <?php echo $facturanum9 ?>;
 
    }
	
}
</script>
<script language="javascript">
// Concepto 1
function getprov(form) {

	var seleccion =  form.concepto.options;
	var cantidad  =  form.concepto.options.length;
	var cant = (cantidad+1) ;
	var contador = 0;
	strAlerta = "seleccion " + seleccion + "\n" + "cantidad " + cantidad + "\n" + "cant " + cant + "\n" + "contador " + contador + "\n" + "seleccion[0].selected" + seleccion[0].selected + "\n" + "seleccion[contador].selected" + "\n" + seleccion[contador].selected;
	//alert(strAlerta);
	for ( contador ; contador < cant ; contador++) {
   		if (seleccion[0].selected == true) { 
	  
	  		alert("Debe seleccionar una opcion");
	  		form.descripcion.value = "" ;
	  		form.descripcion.disabled = true ;
      		form.importe.disabled = true ;
	  		break ;	
    	}

    	if (seleccion[contador].selected) { 
	    	var opcion = new String (seleccion[contador].text);
	  		var opcion = opcion.substring(2,50);
	  		proveedor.descripcion.value = opcion+" ";
      		proveedor.descripcion.focus();

	   	}
		strAlerta = "seleccion " + seleccion + "\n" + "cantidad " + cantidad + "\n" + "cant " + cant + "\n" + "contador " + contador + "\n" + "seleccion[0].selected" + seleccion[0].selected + "\n" + "seleccion[contador].selected" + "\n" + seleccion[contador].selected;
		//alert(strAlerta);
	}
	strAlerta2 = "seleccion " + seleccion + "\n" + "cantidad " + cantidad + "\n" + "cant " + cant + "\n" + "contador " + contador + "\n" + "seleccion[0].selected" + seleccion[0].selected + "\n" + "seleccion[contador].selected" + "\n" + seleccion[contador].selected;
	//alert(strAlerta2);
}  
</script> 
<script language="javascript">
// Concepto 2
function getprov1(form) {

	var seleccion1 = form.concepto1.options;
	var cantidad1 =  form.concepto1.options.length;
	var cant1 = (cantidad1+1) ;
	var contador1 = 0;
  
	for (contador1; contador1<cantidad1 ; contador1++) {
   		if (seleccion1[0].selected == true)  { 
	  		alert("Debe seleccionar una opcion")
	  		proveedor.descripcion1.value = "" ;
	  		proveedor.descripcion1.disabled = true ;
      		proveedor.importe1.disabled = true ;
	  		break ;	
    	}

    	if (seleccion1[contador1].selected == true)  { 
	  		var opcion1= new String (seleccion1[contador1].text);
	  		var opcion1 = opcion1.substring(2,50);
	  		proveedor.descripcion1.value = opcion1+" " ;
      		proveedor.descripcion1.focus() ;
   		}
   	}
}   
</script> 
<script language="javascript">
// TERCER CONCEPTO
function getprov2(form) {

	var seleccion2 = form.concepto2.options;
	var cantidad2  =  form.concepto2.options.length;
	var cant2      = (cantidad2+1) ;
	var contador2  = 0;
  
	for (contador2; contador2<cantidad2 ; contador2++) {
   		if (seleccion2[0].selected == true) { 
	  		alert("Debe seleccionar una opcion");
	  		proveedor.descripcion2.value = "" ;
	  		proveedor.descripcion2.disabled = true ;
      		proveedor.importe2.disabled = true ;
	  		break ;	
    	}

    	if (seleccion2[contador2].selected == true)  { 
	  		var opcion2= new String (seleccion2[contador2].text);
	  		var opcion2 = opcion2.substring(2,50);
	  		proveedor.descripcion2.value = opcion2+" " ;
      		proveedor.descripcion2.focus() ;
		}
   	}
}   
</script>
<script language="javascript">
 
function getprov3(form) {
	var seleccion3 = form.concepto3.options;
	var cantidad3 =  form.concepto3.options.length
	var cant3 = (cantidad3+1) ;
	var contador3 = 0;
  
	for (contador3; contador3<cantidad3 ; contador3++) {
		if (seleccion3[0].selected == true){ 
	  		alert("Debe seleccionar una opcion")
	  		proveedor.descripcion3.value = "" ;
	  		proveedor.descripcion3.disabled = true ;
      		proveedor.importe3.disabled = true ;
	  		break ;	
    	}

    	if (seleccion3[contador3].selected == true) { 
			var opcion3= new String (seleccion3[contador3].text);
	  		var opcion3 = opcion3.substring(2,50)
	  		proveedor.descripcion3.value = opcion3+" " ;
      		proveedor.descripcion3.focus() ;
		}
   	}
}    
</script>
<script language="javascript">

function getprov4(form) {
	var seleccion4 = form.concepto4.options;
	var cantidad4 =  form.concepto4.options.length
	var cant4 = (cantidad4+1) ;
	var contador4 = 0;
  
	for (contador4; contador4<cantidad4 ; contador4++) {
   		if (seleccion4[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion4.value = "" ;
	  		proveedor.descripcion4.disabled = true ;
      		proveedor.importe4.disabled = true ;
	  		break ;	
    	}

    	if (seleccion4[contador4].selected == true) { 
	  	  	var opcion4= new String (seleccion4[contador4].text);
	  		var opcion4 = opcion4.substring(2,50)
	  		proveedor.descripcion4.value = opcion4+" " ;
      		proveedor.descripcion4.focus() ;
	  	}
	}	
}  
</script>
<script language="javascript">
 
function getprov5(form) {
	var seleccion5 = form.concepto5.options;
	var cantidad5 =  form.concepto5.options.length
	var cant5 = (cantidad5+1) ;
	var contador5 = 0;
  
	for (contador5; contador5<cantidad5 ; contador5++) {
		if (seleccion5[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion5.value = "" ;
	  		proveedor.descripcion5.disabled = true ;
      		proveedor.importe5.disabled = true ;
	  		break ;	
    	}

    	if (seleccion5[contador5].selected == true)  { 
	    	var opcion5= new String (seleccion5[contador5].text);
	  		var opcion5 = opcion5.substring(2,50)
	  		proveedor.descripcion5.value = opcion5+" " ;
      		proveedor.descripcion5.focus() ;
		}
   	}
}   
</script>
<script language="javascript">
 
function getprov6(form) {
	var seleccion6 = form.concepto6.options;
	var cantidad6 =  form.concepto6.options.length
	var cant6 = (cantidad6+1) ;
	var contador6 = 0;
  
	for (contador6; contador6<cantidad6 ; contador6++){ 
   		if (seleccion6[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion6.value = "" ;
	  		proveedor.descripcion6.disabled = true ;
      		proveedor.importe6.disabled = true ;
	  		break ;	
    	}

    	if (seleccion6[contador6].selected == true) { 
	    	var opcion6= new String (seleccion6[contador6].text);
	  		var opcion6 = opcion6.substring(2,60)
	  		proveedor.descripcion6.value = opcion6+" " ;
      		proveedor.descripcion6.focus() ;
	   }
   	}
}   
</script>
<script language="javascript">

function getprov7(form) {
	var seleccion7 = form.concepto7.options;
	var cantidad7 =  form.concepto7.options.length
	var cant7 = (cantidad7+1) ;
	var contador7 = 0;
  
	for (contador7; contador7<cantidad7 ; contador7++) {   
		if (seleccion7[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion7.value = "" ;
	  		proveedor.descripcion7.disabled = true ;
      		proveedor.importe7.disabled = true ;
	  		break ;	
    	}

    	if (seleccion7[contador7].selected == true) { 
	    	var opcion7= new String (seleccion7[contador7].text);
	  		var opcion7 = opcion7.substring(2,70)
	  		proveedor.descripcion7.value = opcion7+" " ;
      		proveedor.descripcion7.focus() ;
   		}
   	}
}   
</script>
<script language="javascript">

function getprov8(form) {
	var seleccion8 = form.concepto8.options;
	var cantidad8 =  form.concepto8.options.length
	var cant8 = (cantidad8+1) ;
	var contador8 = 0;
  
	for (contador8; contador8<cantidad8 ; contador8++) { 
   		if (seleccion8[0].selected == true) { 
	  	  	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion8.value = "" ;
	  		proveedor.descripcion8.disabled = true ;
      		proveedor.importe8.disabled = true ;
	  		break ;	
    	}

    	if (seleccion8[contador8].selected == true) { 
	  	  	var opcion8= new String (seleccion8[contador8].text);
	  		var opcion8 = opcion8.substring(2,80)
	  		proveedor.descripcion8.value = opcion8+" " ;
      		proveedor.descripcion8.focus() ;
	   }
	}
}   
</script>
<script language="javascript">

function getprov9(form) {
	var seleccion9 = form.concepto9.options;
	var cantidad9 =  form.concepto9.options.length
	var cant9 = (cantidad9+1) ;
	var contador9 = 0;
  	
	for (contador9; contador9<cantidad9 ; contador9++) {
   		if (seleccion9[0].selected == true) { 
	  	  	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion9.value = "" ;
	  		proveedor.descripcion9.disabled = true ;
      		proveedor.importe9.disabled = true ;
	  		break ;	
    	}

    	if (seleccion9[contador9].selected == true) { 
	  	  	var opcion9= new String (seleccion9[contador9].text);
	  		var opcion9 = opcion9.substring(2,90)
	  		proveedor.descripcion9.value = opcion9+" " ;
      		proveedor.descripcion9.focus() ;
	
   		}
   	}
}   
</script>
<script language="javascript">

function getprov10(form) {
	var seleccion10 = form.concepto10.options;
	var cantidad10 =  form.concepto10.options.length
	var cant10 = (cantidad10+1) ;
	var contador10 = 0;
  
	for (contador10; contador10<cantidad10 ; contador10++) {
   		if (seleccion10[0].selected == true) { 
	  		alert("Debe seleccionar una opcion")
	  		proveedor.descripcion10.value = "" ;
	  		proveedor.descripcion10.disabled = true ;
      		proveedor.importe10.disabled = true ;
	  		break ;	
    	}

    	if (seleccion10[contador10].selected == true)    { 
	    	var opcion10= new String (seleccion10[contador10].text);
	  		var opcion10 = opcion10.substring(2,100)
	  		proveedor.descripcion10.value = opcion10+" " ;
      		proveedor.descripcion10.focus() ;
	   	}
   	}
}   
</script>
<script language="javascript">
 
function getprov11(form) {
	var seleccion11 = form.concepto11.options;
	var cantidad11 =  form.concepto11.options.length
	var cant11 = (cantidad11+1) ;
	var contador11 = 0;
  
	for (contador11; contador11<cantidad11 ; contador11++) { 
   		if (seleccion11[0].selected == true) { 
	  		alert("Debe seleccionar una opcion")
	  		proveedor.descripcion11.value = "" ;
	  		proveedor.descripcion11.disabled = true ;
      		proveedor.importe11.disabled = true ;
	  		break ;	
    	}

    	if (seleccion11[contador11].selected == true) { 
	  		var opcion11= new String (seleccion11[contador11].text);
	  		var opcion11 = opcion11.substring(2,50)
	  		proveedor.descripcion11.value = opcion11+" " ;
      		proveedor.descripcion11.focus() ;
		}
   	}
}   
</script>
<script language="javascript">

function getprov12(form) {
	var seleccion12 = form.concepto12.options;
	var cantidad12 =  form.concepto12.options.length
	var cant12 = (cantidad12+1) ;
	var contador12 = 0;
  
	for (contador12; contador12<cantidad12 ; contador12++) {
   		if (seleccion12[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion12.value = "" ;
	  		proveedor.descripcion12.disabled = true ;
      		proveedor.importe12.disabled = true ;
	  		break ;	
    	}

    	if (seleccion12[contador12].selected == true)  { 
	  		var opcion12= new String (seleccion12[contador12].text);
	  		var opcion12 = opcion12.substring(2,50)
	  		proveedor.descripcion12.value = opcion12+" " ;
      		proveedor.descripcion12.focus() ;
	   	}
   	}
}
</script>
<script language="javascript">
   
function getprov13(form) {
	var seleccion13 = form.concepto13.options;
	var cantidad13 =  form.concepto13.options.length
	var cant13 = (cantidad13+1) ;
	var contador13 = 0;
  
	for (contador13; contador13<cantidad13 ; contador13++) { //alert();
   		if (seleccion13[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion13.value = "" ;
	  		proveedor.descripcion13.disabled = true ;
      		proveedor.importe13.disabled = true ;
	  		break ;	
    	}
    	if (seleccion13[contador13].selected == true) { 
	  	  	var opcion13= new String (seleccion13[contador13].text);
	  		var opcion13 = opcion13.substring(2,50)
	  		proveedor.descripcion13.value = opcion13+" " ;
      		proveedor.descripcion13.focus() ;
	   	}
   	}
}  

</script>
<script language="javascript">
 
function getprov14(form) {
	var seleccion14 = form.concepto14.options;
	var cantidad14 =  form.concepto14.options.length
	var cant14 = (cantidad14+1) ;
	var contador14 = 0;
  	
	for (contador14; contador14<cantidad14 ; contador14++) { //alert();
   		if (seleccion14[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		proveedor.descripcion14.value = "" ;
	  		proveedor.descripcion14.disabled = true ;
      		proveedor.importe14.disabled = true ;
	  		break ;	
    	}
    	if (seleccion14[contador14].selected == true) { 
	  	  	var opcion14= new String (seleccion14[contador14].text);
	  		var opcion14 = opcion14.substring(2,140)
	  		proveedor.descripcion14.value = opcion14+" " ;
      		proveedor.descripcion14.focus() ;
	  	}
   	}
}   	
</script>
<script language="javascript">
function ValidoRepe(form) {
	alert("HOLA DENTRO DE LA FUNCION VIENDO QUE LE PIFIASTE AL CBTE");

	var cbte_ing = form.ncomp.value;
	var prov_ing = form.codnum.value;
	var tcomp_ing = form.tcomp.value;
	var i = 0;
	
	for(i=0;i< $k; i++) {
		if ($cbte[i] == cbte_ing && $prov[i] == prov_ing && $tipo[i] == tcomp_ing) {
			alert "EL COMPROBANTE YA HA SIDO INGRESADO CON ANTERIORIDAD";
		}
	}
	
}
</script>
<script language="javascript">
function ValidoForm(form)
{
		// DESDE ACA
	var fac_numero = form.num_factura_prov.value ; // Numero de la Factura 
	var tipo_comprobante = form.tcomp.value ;  // Tipo de comprobante 
	var serie_num     = form.serie.value ;  // Numero de Serie 
	var fecha_factura = form.fecha_f2.value ; //  Fecha de Factura
	var proveedor    = form.codnum.value ; // Numero de Proveedor	
  	var error ="";
  	
	 //alert ("imp ="+tipoiva);
	 //alert ("conc ="+concepto);
	
	
	
	if (tipo_comprobante=="" || serie_num=="" || fac_numero==""  || proveedor=="" || fecha_factura=="") {
      	if (tipo_comprobante=="") {
         	error = "      Tipo de comprobante\n"; 
        }
      	
	    if (serie_num=="") {
        	error = error+"      Serie\n"; 
        }
	 	if (fac_numero=="") {
        	error = error+"      Numero de Factura\n"; 
        }	
	 	if (proveedor=="") {
        	error = error+"      Proveedor\n"; 
        }
		if (fecha_factura=="") {
        	error = error+"      Fecha de Factura\n"; 
        }		 
		alert ("Faltan los siguientes datos :\n"+error);
	} 
	//HASTA ACA
}
</script>
<script language="javascript">
function validarFormulario(form)
{
	
 	// VERIFICAR NOMBRES DE CAMPOS ======================================
	
	var fac_numero = form.ncomp.value ; // Numero de la Factura 
	
	var tipo_comprobante = form.tcomp.value ;  // Tipo de comprobante 
	var serie_num     = form.serie.value ;  // Numero de Serie 
	var fecha_factura = form.fecha_f2.value ; //  Fecha de Factura
	var proveedor    = form.codnum.value ; // Numero de Proveedor

	// ==================================================================
	
	//var series = form.serie.value;  // serie 
	var concepto = form.concepto.value;
	
	//var imp21  = proveedor.concepto.value/100; 
	var tipoiva  = form.tipoiva.value; 
	
	var tipoiva1  = form.tipoiva1.value; 
	var tipoiva2  = form.tipoiva2.value; 
	var tipoiva3  = form.tipoiva3.value; 
	var tipoiva4  = form.tipoiva4.value; 
	var tipoiva5  = form.tipoiva5.value; 
	var tipoiva6  = form.tipoiva6.value; 
	
	var tipoiva7  = form.tipoiva7.value; 
	var tipoiva8  = form.tipoiva8.value; 
	var tipoiva9  = form.tipoiva9.value; 
	var tipoiva10  = form.tipoiva10.value; 
	var tipoiva11  = form.tipoiva11.value; 
	var tipoiva12  = form.tipoiva12.value; 
	var tipoiva13  = form.tipoiva13.value; 
	var tipoiva14  = form.tipoiva14.value; 
	
	var monto  = form.importe.value; // Monto  primer concepto
	var monto1 = form.importe1.value; // Monto segundo concepto
	var monto2 = form.importe2.value; // Monto tercer concepto
	var monto3 = form.importe3.value; // Monto cuarto concepto
	var monto4 = form.importe4.value;  // Monto Quinto concepto
	var monto5 = form.importe5.value; // Monto Sexto concepto
	var monto6  = form.importe6.value; // Monto Septimo concepto
	
	var monto7  = form.importe7.value; // Monto Octavo concepto
	var monto8  = form.importe8.value; // Monto Noveno concepto
	var monto9  = form.importe9.value; // Monto DÃ©cimo concepto
	var monto10 = form.importe10.value;  // Monto Onceavo concepto
	var monto11 = form.importe11.value; // Monto Doceavo concepto
	var monto12 = form.importe12.value; // Monto Treceavo concepto
	var monto13 = form.importe13.value; // Monto Catorceavo concepto
	var monto14 = form.importe14.value; // Monto Quinceavo concepto
	
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
	
   
	
	// DESDE ACA
		
  	var error ="";
  	
	// alert ("imp ="+tipoiva);
	 //alert ("conc ="+concepto);
	
	
	
	if (tipo_comprobante=="" || serie_num=="" || fac_numero=="" || monto == "" || proveedor=="" || fecha_factura=="") {
      	if (tipo_comprobante=="") {
         	error = "      Tipo de comprobante\n"; 
        }
      	if (monto == "") {
	  		error = error+"     Importe\n";
	  	}
	    if (serie_num=="") {
        	error = error+"      Serie\n"; 
        }
	 	if (fac_numero=="") {
        	error = error+"      Numero de Factura\n"; 
        }	
	 	if (proveedor=="") {
        	error = error+"      Proveedor\n"; 
        }
		if (fecha_factura=="") {
        	error = error+"      Fecha de Factura\n"; 
        }		 
		alert ("Faltan los siguientes datos :\n"+error);
	} 
	//HASTA ACA
	
	var tot_monto =  0 ;
	var imp_tot_21  = 0;
	var imp_tot_27  = 0;
	var imp_tot_105  = 0;
	var imp_tot_025 = 0;
	var imp_tot_05 = 0;
	var imp_tot_ex  = 0;
	var tot_general  =  0;
	var tot_mon_105 = 0;
	var tot_mon_025 = 0;
	var tot_mon_05 = 0;
	var tot_mon_21 = 0;
	var tot_mon_27 = 0;
	var tot_mon_ex = 0;
	
	var tot_monto_1 =  0 ;
	var imp_tot_21_1  = 0;
	var imp_tot_27_1  = 0;
	var imp_tot_105_1  = 0;
	var imp_tot_025_1 = 0;
	var imp_tot_05_1 = 0;
	var imp_tot_ex_1  = 0;

	var tot_monto_2 =  0 ;
	var imp_tot_21_2  = 0;
	var imp_tot_27_2  = 0;
	var imp_tot_105_2  = 0;
	var imp_tot_025_2 = 0;
	var imp_tot_05_2 = 0;
	var imp_tot_ex_2  = 0;
	
	var tot_monto_3 =  0 ;
	var imp_tot_21_3  = 0;
	var imp_tot_27_3  = 0;
	var imp_tot_105_3  = 0;
	var imp_tot_025_3 = 0;
	var imp_tot_05_3 = 0;
	var imp_tot_ex_3  = 0;

	var tot_monto_4 =  0 ;
	var imp_tot_21_4  = 0;
	var imp_tot_27_4  = 0;
	var imp_tot_105_4  = 0;
	var imp_tot_025_4 = 0;
	var imp_tot_05_4 = 0;
	var imp_tot_ex_4  = 0;

	var tot_monto_5 =  0 ;
	var imp_tot_21_5  = 0;
	var imp_tot_27_5  = 0;
	var imp_tot_105_5  = 0;
	var imp_tot_025_5 = 0;
	var imp_tot_05_5 = 0;
	var imp_tot_ex_5  = 0;

	var tot_monto_6 =  0 ;
	var imp_tot_21_6  = 0;
	var imp_tot_27_6  = 0;
	var imp_tot_105_6  = 0;
	var imp_tot_025_6 = 0;
	var imp_tot_05_6 = 0;
	var imp_tot_ex_6  = 0;


	var tot_monto_7 =  0 ;
	var imp_tot_21_7  = 0;
	var imp_tot_27_7  = 0;
	var imp_tot_105_7  = 0;
	var imp_tot_025_7 = 0;
	var imp_tot_05_7 = 0;
	var imp_tot_ex_7  = 0;
	
	var tot_monto_8 =  0 ;
	var imp_tot_21_8  = 0;
	var imp_tot_27_8  = 0;
	var imp_tot_105_8  = 0;
	var imp_tot_025_8 = 0;
	var imp_tot_05_8 = 0;
	var imp_tot_ex_8  = 0;	
	
	var tot_monto_9 =  0 ;
	var imp_tot_21_9  = 0;
	var imp_tot_27_9  = 0;
	var imp_tot_105_9  = 0;
	var imp_tot_025_9 = 0;
	var imp_tot_05_9 = 0;
	var imp_tot_ex_9  = 0;	

	var tot_monto_10 =  0 ;
	var imp_tot_21_10  = 0;
	var imp_tot_27_10  = 0;
	var imp_tot_105_10  = 0;
	var imp_tot_025_10 = 0;
	var imp_tot_05_10 = 0;
	var imp_tot_ex_10  = 0;	

	var tot_monto_11 =  0 ;
	var imp_tot_21_11  = 0;
	var imp_tot_27_11  = 0;
	var imp_tot_105_11  = 0;
	var imp_tot_025_11 = 0;
	var imp_tot_05_11 = 0;
	var imp_tot_ex_11  = 0;	

	var tot_monto_12 =  0 ;
	var imp_tot_21_12  = 0;
	var imp_tot_27_12  = 0;
	var imp_tot_105_12  = 0;
	var imp_tot_025_12 = 0;
	var imp_tot_05_12 = 0;
	var imp_tot_ex_12  = 0;	

	var tot_monto_13 =  0 ;
	var imp_tot_21_13  = 0;
	var imp_tot_27_13  = 0;
	var imp_tot_105_13  = 0;
	var imp_tot_025_13 = 0;
	var imp_tot_05_13 = 0;
	var imp_tot_ex_13  = 0;	
	
	var tot_monto_14 =  0 ;
	var imp_tot_21_14  = 0;
	var imp_tot_27_14  = 0;
	var imp_tot_105_14  = 0;
	var imp_tot_025_14 = 0;
	var imp_tot_05_14 = 0;
	var imp_tot_ex_14  = 0;	
	
	// PRIMER LOTE
	//alert("TIPOIVA = :\n"+tipoiva);
	//alert("TIPOIVA1 = :\n"+tipoiva1);
	//alert("TIPOIVA2 = :\n"+tipoiva2);
	//alert("TIPOIVA3 = :\n"+tipoiva3);

	if (monto.length!=0.0) {
		
			//alert("TIENE MONTO, IVA = "+tipoiva);
		if (tipoiva == 0) {
	
			//alert("IVA = 0");
				
				tot_mon = eval(monto);
				tot_mon_ex = eval(monto);             
      			imp_tot_ex = 0;        
	  			tot_general = eval(monto+('+')+imp_tot_ex);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(monto);              
      			imp_tot_025 = eval(monto*tipoiva/100);
	  			tot_general = eval(monto+('+')+imp_tot_025);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(monto);              
      			imp_tot_05 = eval(monto*tipoiva/100);
	  			tot_general = eval(monto+('+')+imp_tot_05);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva == 10.5) {

				//alert("IVA = 10,5");
				
				tot_mon = eval(monto);             
      			imp_tot_105 = eval(monto * tipoiva / 100.0);
	  			tot_general = eval(monto+('+')+imp_tot_105);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(monto);              
      			imp_tot_21 = eval(monto  * tipoiva / 100);     
	  			tot_general = eval(monto+('+')+imp_tot_21);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(monto);              // Monto de Primer Lote
      			imp_tot_27 = eval(monto*tipoiva/100); 
	  			tot_general = eval(monto+('+')+imp_tot_27);	// Total general
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		 
	} 


	
	// SEGUNDO LOTE
	if (monto1.length!=0) {
		if (tipoiva1 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto1);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto1);        
	  			tot_general = eval(tot_general+('+')+monto1);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva1 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto1);              
      			imp_tot_025_1 = eval(monto1*tipoiva1/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_1);
	  			tot_general = eval(tot_general+('+')+monto1+('+')+imp_tot_025_1);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva1 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto1);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto1);          
      			imp_tot_05_1 = eval(monto1*tipoiva1/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_1);
	  			tot_general = eval(tot_general+('+')+monto1+('+')+imp_tot_05_1);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva1 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto1);
				tot_mon_105 = eval(tot_mon_105+('+')+monto1);             
      			imp_tot_105_1 = eval(monto1 * tipoiva1 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_1);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_1+('+')+monto1);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva1 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto1); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto1);              
      			imp_tot_21_1 = eval(monto1  * tipoiva1 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_1);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_1+('+')+monto1);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva1 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto1); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto1);    
      			imp_tot_27_1 = eval(monto1  * tipoiva1 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_1);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_1+('+')+monto1);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
	}
	
	// TERCER LOTE
	if (monto2.length!=0) {
		//alert("IVA2 = "+tipoiva2);
			if (tipoiva2 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto2);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto2);        
	  			tot_general = eval(tot_general+('+')+monto2);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva2 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto2);              
      			imp_tot_025_2 = eval(monto2*tipoiva2/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_2);
	  			tot_general = eval(tot_general+('+')+monto2+('+')+imp_tot_025_2);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva2 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto2);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto2);          
      			imp_tot_05_2 = eval(monto2*tipoiva2/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_2);
	  			tot_general = eval(tot_general+('+')+monto2+('+')+imp_tot_05_2);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva2 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto2);
				tot_mon_105 = eval(tot_mon_105+('+')+monto2);             
      			imp_tot_105_2 = eval(monto2 * tipoiva2 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_2);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_2+('+')+monto2);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva2 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto2); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto2);              
      			imp_tot_21_2 = eval(monto2  * tipoiva2 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_2);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_2+('+')+monto2);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva2 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto2); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto2);    
      			imp_tot_27_2 = eval(monto2  * tipoiva2 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_2);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_2+('+')+monto2);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
	} 
		
	// CUARTO LOTE
	if (monto3.length!=0) {
		//alert("IVA3 = "+tipoiva3);
			if (tipoiva3 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto3);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto3);        
	  			tot_general = eval(tot_general+('+')+monto3);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva3 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto3);              
      			imp_tot_025_3 = eval(monto3*tipoiva3/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_3);
	  			tot_general = eval(tot_general+('+')+monto3+('+')+imp_tot_025_3);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva3 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto3);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto3);          
      			imp_tot_05_3 = eval(monto3*tipoiva3/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_3);
	  			tot_general = eval(tot_general+('+')+monto3+('+')+imp_tot_05_3);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva3 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto3);
				tot_mon_105 = eval(tot_mon_105+('+')+monto3);             
      			imp_tot_105_3 = eval(monto3 * tipoiva3 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_3);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_3+('+')+monto3);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva3 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto3); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto3);              
      			imp_tot_21_3 = eval(monto3  * tipoiva3 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_3);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_3+('+')+monto3);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva3 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto3); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto3);    
      			imp_tot_27_3 = eval(monto3  * tipoiva3 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_3);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_3+('+')+monto3);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}			 
	} 
	
	// QUINTO LOTE
	if (monto4.length!=0) {
				//alert("IVA3 = "+tipoiva3);
			if (tipoiva4 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto4);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto4);        
	  			tot_general = eval(tot_general+('+')+monto4);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva4 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto4);              
      			imp_tot_025_4 = eval(monto4*tipoiva4/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_4);
	  			tot_general = eval(tot_general+('+')+monto4+('+')+imp_tot_025_4);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva4 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto4);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto4);          
      			imp_tot_05_4 = eval(monto4*tipoiva4/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_4);
	  			tot_general = eval(tot_general+('+')+monto4+('+')+imp_tot_05_4);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva4 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto4);
				tot_mon_105 = eval(tot_mon_105+('+')+monto4);             
      			imp_tot_105_4 = eval(monto4 * tipoiva4 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_4);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_4+('+')+monto4);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva4 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto4); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto4);              
      			imp_tot_21_4 = eval(monto4  * tipoiva4 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_4);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_4+('+')+monto4);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva4 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto4); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto4);    
      			imp_tot_27_4 = eval(monto4  * tipoiva4 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_4);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_4+('+')+monto4);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}	
	} 
	
	// SEXTO LOTE
	if (monto5.length!=0) {
					//alert("IVA3 = "+tipoiva3);
			if (tipoiva5 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto5);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto5);        
	  			tot_general = eval(tot_general+('+')+monto5);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva5 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto5);              
      			imp_tot_025_5 = eval(monto5*tipoiva5/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_5);
	  			tot_general = eval(tot_general+('+')+monto5+('+')+imp_tot_025_5);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva5 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto5);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto5);          
      			imp_tot_05_5 = eval(monto5*tipoiva5/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_5);
	  			tot_general = eval(tot_general+('+')+monto5+('+')+imp_tot_05_5);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva5 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto5);
				tot_mon_105 = eval(tot_mon_105+('+')+monto5);             
      			imp_tot_105_5 = eval(monto5 * tipoiva5 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_5);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_5+('+')+monto5);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva5 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto5); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto5);              
      			imp_tot_21_5 = eval(monto5  * tipoiva5 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_5);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_5+('+')+monto5);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva5 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto5); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto5);    
      			imp_tot_27_5 = eval(monto5  * tipoiva5 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_5);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_5+('+')+monto5);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
			
	} 
	
	// SEPTIMO LOTE
	if (monto6.length!=0) {
					//alert("IVA3 = "+tipoiva3);
			if (tipoiva6 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto6);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto6);        
	  			tot_general = eval(tot_general+('+')+monto6);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva6 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto6);              
      			imp_tot_025_6 = eval(monto6*tipoiva6/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_6);
	  			tot_general = eval(tot_general+('+')+monto6+('+')+imp_tot_025_6);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva6 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto6);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto6);          
      			imp_tot_05_6 = eval(monto6*tipoiva6/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_6);
	  			tot_general = eval(tot_general+('+')+monto6+('+')+imp_tot_05_6);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva6 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto6);
				tot_mon_105 = eval(tot_mon_105+('+')+monto6);             
      			imp_tot_105_6 = eval(monto6 * tipoiva6 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_6);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_6+('+')+monto6);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva6 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto6); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto6);              
      			imp_tot_21_6 = eval(monto6  * tipoiva6 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_6);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_6+('+')+monto6);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva6 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto6); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto6);    
      			imp_tot_27_6 = eval(monto6  * tipoiva6 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_6);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_6+('+')+monto6);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
			
	} 
	
	// OCTAVO LOTE
	if (monto7.length!=0) {
					//alert("IVA3 = "+tipoiva3);
			if (tipoiva7 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto7);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto7);        
	  			tot_general = eval(tot_general+('+')+monto7);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva7 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto7);              
      			imp_tot_025_7 = eval(monto7*tipoiva7/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_7);
	  			tot_general = eval(tot_general+('+')+monto7+('+')+imp_tot_025_7);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva7 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto7);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto7);          
      			imp_tot_05_7 = eval(monto7*tipoiva7/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_7);
	  			tot_general = eval(tot_general+('+')+monto7+('+')+imp_tot_05_7);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva7 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto7);
				tot_mon_105 = eval(tot_mon_105+('+')+monto7);             
      			imp_tot_105_7 = eval(monto7 * tipoiva7 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_7);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_7+('+')+monto7);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva7 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto7); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto7);              
      			imp_tot_21_7 = eval(monto7  * tipoiva7 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_7);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_7+('+')+monto7);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva7 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto7); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto7);    
      			imp_tot_27_7 = eval(monto7  * tipoiva7 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_7);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_7+('+')+monto7);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		
	} 
	
	// NOVENO LOTE
	if (monto8.length!=0) {
						//alert("IVA3 = "+tipoiva3);
			if (tipoiva8 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto8);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto8);        
	  			tot_general = eval(tot_general+('+')+monto8);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva8 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto8);              
      			imp_tot_025_8 = eval(monto8*tipoiva8/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_8);
	  			tot_general = eval(tot_general+('+')+monto8+('+')+imp_tot_025_8);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva8 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto8);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto8);          
      			imp_tot_05_8 = eval(monto8*tipoiva8/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_8);
	  			tot_general = eval(tot_general+('+')+monto8+('+')+imp_tot_05_8);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva8 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto8);
				tot_mon_105 = eval(tot_mon_105+('+')+monto8);             
      			imp_tot_105_8 = eval(monto8 * tipoiva8 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_8);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_8+('+')+monto8);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva8 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto8); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto8);              
      			imp_tot_21_8 = eval(monto8  * tipoiva8 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_8);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_8+('+')+monto8);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva8 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto8); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto8);    
      			imp_tot_27_8 = eval(monto8  * tipoiva8 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_8);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_8+('+')+monto8);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		
	} 
	// DECIMO LOTE
	if (monto9.length!=0) {
						//alert("IVA3 = "+tipoiva3);
			if (tipoiva9 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto9);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto9);        
	  			tot_general = eval(tot_general+('+')+monto9);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva9 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto9);              
      			imp_tot_025_9 = eval(monto9*tipoiva9/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_9);
	  			tot_general = eval(tot_general+('+')+monto9+('+')+imp_tot_025_9);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva9 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto9);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto9);          
      			imp_tot_05_9 = eval(monto9*tipoiva9/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_9);
	  			tot_general = eval(tot_general+('+')+monto9+('+')+imp_tot_05_9);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva9 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto9);
				tot_mon_105 = eval(tot_mon_105+('+')+monto9);             
      			imp_tot_105_9 = eval(monto9 * tipoiva9 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_9);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_9+('+')+monto9);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva9 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto9); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto9);              
      			imp_tot_21_9 = eval(monto9  * tipoiva9 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_9);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_9+('+')+monto9);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva9 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto9); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto9);    
      			imp_tot_27_9 = eval(monto9  * tipoiva9 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_9);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_9+('+')+monto9);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		
	} 

	// UNDECIMO LOTE
	if (monto10.length!=0) {
						//alert("IVA3 = "+tipoiva3);
			if (tipoiva10 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto10);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto10);        
	  			tot_general = eval(tot_general+('+')+monto10);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva10 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto10);              
      			imp_tot_025_10 = eval(monto10*tipoiva10/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_10);
	  			tot_general = eval(tot_general+('+')+monto10+('+')+imp_tot_025_10);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva10 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto10);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto10);          
      			imp_tot_05_10 = eval(monto10*tipoiva10/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_10);
	  			tot_general = eval(tot_general+('+')+monto10+('+')+imp_tot_05_10);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva10 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto10);
				tot_mon_105 = eval(tot_mon_105+('+')+monto10);             
      			imp_tot_105_10 = eval(monto10 * tipoiva10 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_10);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_10+('+')+monto10);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva10 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto10); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto10);              
      			imp_tot_21_10 = eval(monto10  * tipoiva10 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_10);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_10+('+')+monto10);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva10 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto10); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto10);    
      			imp_tot_27_10 = eval(monto10  * tipoiva10 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_10);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_10+('+')+monto10);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		
	} 
	
	// DECIMO SEGUNDO LOTE
	
	if (monto11.length!=0) {
						//alert("IVA3 = "+tipoiva3);
			if (tipoiva11 == 0) {
	
			//alert("IVA = 0");
	
				tot_mon = eval(tot_mon+('+')+monto11);             
      			imp_tot_ex = eval(imp_tot_ex+('+')+monto11);        
	  			tot_general = eval(tot_general+('+')+monto11);
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
			//form.totneto.value = 0.00 ;	
			//form.tot_general.value = 0.00 ;
		}
		if (tipoiva11 == 2.5) {

				//alert("IVA = 2.5");
				
				tot_mon = eval(tot_mon+('+')+monto11);              
      			imp_tot_025_11 = eval(monto11*tipoiva11/100);
				imp_tot_025 = eval(imp_tot_025+('+')+imp_tot_025_11);
	  			tot_general = eval(tot_general+('+')+monto11+('+')+imp_tot_025_11);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva025.value = imp_tot_025.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva025.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva10 == 5.0) { 

				//alert("IVA = 5");
				
				tot_mon = eval(tot_mon+('+')+monto11);    
				tot_mon_05 = eval(tot_mon_05+('+')+monto11);          
      			imp_tot_05_11 = eval(monto11*tipoiva11/100);
				imp_tot_05   = eval(imp_tot_05+('+')+imp_tot_05_11);
	  			tot_general = eval(tot_general+('+')+monto11+('+')+imp_tot_05_11);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva05.value = imp_tot_05.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva05.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva11 == 10.5) {

				//alert("IVA = 10,5");
				tot_mon     = eval(tot_mon+('+')+monto11);
				tot_mon_105 = eval(tot_mon_105+('+')+monto11);             
      			imp_tot_105_11 = eval(monto11 * tipoiva11 / 100.0);
				imp_tot_105   = eval(imp_tot_105+('+')+imp_tot_105_11);
	  			tot_general = eval(tot_general+('+')+imp_tot_105_11+('+')+monto11);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva105.value = imp_tot_105.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva105.value = 0.00 ;
				//form.tot_general.value = 0.00 ;
		
		}
		if (tipoiva11 == 21.0) {

				//alert("IVA = "+tipoiva);
			
				tot_mon = eval(tot_mon+('+')+monto11); 
				tot_mon_21 = eval(tot_mon_21+('+')+monto11);              
      			imp_tot_21_11 = eval(monto11  * tipoiva11 / 100);
				imp_tot_21   = eval(imp_tot_21+('+')+imp_tot_21_11);    
	  			tot_general = eval(tot_general+('+')+imp_tot_21_11+('+')+monto11);	
	    	
			
			//alert("tot_mon ="+tot_mon);
			//alert("imp_tot_21 ="+imp_tot_21);
			//alert("tot_general ="+tot_general);
			
			
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva21.value = imp_tot_21.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
				
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva21.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		if (tipoiva11 == 27.0) {

				//alert("IVA = 27");
				
				tot_mon = eval(tot_mon+('+')+monto11); 
				tot_mon_27 = eval(tot_mon_27+('+')+monto11);    
      			imp_tot_27_11 = eval(monto11  * tipoiva11 / 100);
				imp_tot_27   = eval(imp_tot_27+('+')+imp_tot_27_11);    
	  			tot_general = eval(tot_general+('+')+imp_tot_27_11+('+')+monto11);	
	    
				form.totneto.value = tot_mon.toFixed(2) ;	
				form.totiva27.value = imp_tot_27.toFixed(2) ;
				form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		else {
				//form.totneto.value = 0.00 ;	
				//form.totiva27.value = 0.00 ;
				//form.tot_general.value = tot_general.toFixed(2) ;
		
		}
		
	} 
	
	
	/*
	// DECIMO LOTE
	if (monto9.length!=0) {
		imp_21_9 = eval(monto9*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_9 = eval(monto9+('+')+imp_21_9);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto9);
		tot_mon21_9 = eval(monto9+('+')+imp_21_9);	// Total general
	    	imp_tot_21 = eval(imp_tot_21+('+')+imp_21_9);
	   
	    	tot_general = eval(tot_general+('+')+tot_mon21_9) 
		
		proveedor.totneto21.value = tot_mon.toFixed(2) ;	
		proveedor.totiva21.value = imp_tot_21.toFixed(2) ;
		proveedor.tot_general.value = tot_general.toFixed(2) ;
						 
	} 
	//  LOTE ONCE
	if (monto10.length!=0) {
		imp_21_10 = eval(monto10*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_10 = eval(monto10+('+')+imp_21_10);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto10);
		tot_mon21_10 = eval(monto10+('+')+imp_21_10);	// Total general
	    	imp_tot_21 = eval(imp_tot_21+('+')+imp_21_10);
	   
	    	tot_general = eval(tot_general+('+')+tot_mon21_10) 
		
		proveedor.totneto21.value = tot_mon.toFixed(2) ;	
		proveedor.totiva21.value = imp_tot_21.toFixed(2) ;
		proveedor.tot_general.value = tot_general.toFixed(2) ;
					 
	} 
	//  LOTE DOCE
	if (monto11.length!=0) {
	         imp_21_11 = eval(monto11*imp21);              // Impuesto Monto 1 al 21 %
	         tot_mon21_11 = eval(monto11+('+')+imp_21_11);	// Total general
		
	         tot_mon = eval(tot_mon+('+')+monto11);
	         tot_mon21_11 = eval(monto11+('+')+imp_21_11);	// Total general
	         imp_tot_21 = eval(imp_tot_21+('+')+imp_21_11);
	   
	         tot_general = eval(tot_general+('+')+tot_mon21_11) 
		
	         proveedor.totneto21.value = tot_mon.toFixed(2) ;	
	         proveedor.totiva21.value = imp_tot_21.toFixed(2) ;
	         proveedor.tot_general.value = tot_general.toFixed(2) ;
	} 
	//  LOTE TRECE
	if (monto12.length!=0) {
	   	imp_21_12 = eval(monto12*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_12 = eval(monto11+('+')+imp_21_12);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto11);
		tot_mon21_12 = eval(monto12+('+')+imp_21_12);	// Total general
	       	imp_tot_21 = eval(imp_tot_21+('+')+imp_21_12);
	   
	       	tot_general = eval(tot_general+('+')+tot_mon21_12) 
		
		proveedor.totneto21.value = tot_mon.toFixed(2) ;	
		proveedor.totiva21.value = imp_tot_21.toFixed(2) ;
		proveedor.tot_general.value = tot_general.toFixed(2) ;
	
	} 
	//  LOTE CATORCE
	if (monto13.length!=0) {
		imp_21_13 = eval(monto13*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_13 = eval(monto11+('+')+imp_21_13);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto11);
		tot_mon21_13 = eval(monto13+('+')+imp_21_13);	// Total general
	    	imp_tot_21 = eval(imp_tot_21+('+')+imp_21_13);
	   
	    	tot_general = eval(tot_general+('+')+tot_mon21_13) 
		
		proveedor.totneto21.value = tot_mon.toFixed(2) ;	
		proveedor.totiva21.value = imp_tot_21.toFixed(2) ;
		proveedor.tot_general.value = tot_general.toFixed(2) ;
	}
			
	//  LOTE QUINCE
	if (monto14.length!=0) {
		imp_21_14 = eval(monto14*imp21);              // Impuesto Monto 1 al 21 %
		tot_mon21_14 = eval(monto11+('+')+imp_21_14);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto11);
		tot_mon21_14 = eval(monto14+('+')+imp_21_14);	// Total general
	       	imp_tot_21 = eval(imp_tot_21+('+')+imp_21_14);
	   
	       	tot_general = eval(tot_general+('+')+tot_mon21_14) 
		
		//   proveedor.totneto21.value = tot_mon.toFixed(2) ;	
		//   proveedor.totiva21.value = imp_tot_21.toFixed(2) ;
		//   proveedor.tot_general.value = tot_general.toFixed(2) ;
	}
	// alert ("S1+"+series);
	
		
	//var serie = proveedor.serie.value;
	//alert(proveedor.serie.value);
	//	alert("Serie"+series);
     	// alert ("s2"+series);
      	if ( series == '11' ) {
		// Oculto
		// alert()
		proveedor.totneto21.value = tot_mon.toFixed(2) ;	
		proveedor.totiva21.value = imp_tot_21.toFixed(2) ;
		proveedor.tot_general.value = tot_general.toFixed(2) ;
		// Visible
		if  (proveedor.GrupoOpciones1[0].checked == true && proveedor.pago_contado.checked == true )
		{
			  
			 proveedor.leyenda.value = "Se abona con efectivo la cantidad de $ "+tot_general.toFixed(2);
			 
			 
		} 
		//	tot_mon21 = tot_mon21*1.21
		
		proveedor.totneto21_1.value = tot_general.toFixed(2);
		proveedor.totiva21_1.value = "-----";
		proveedor.tot_general_1.value = tot_general.toFixed(2) ;
		//   alert(proveedor.tot_general_1.value);
			   
	} else {
		// Oculto   
		//  alert("Hola")
		proveedor.totneto21.value = tot_mon.toFixed(2) ;	
		proveedor.totiva21.value = imp_tot_21.toFixed(2) ;
		proveedor.tot_general.value = tot_general.toFixed(2) ;
		// Visible
		
		proveedor.totneto21_1.value = tot_mon.toFixed(2) ;	
		proveedor.totiva21_1.value = imp_tot_21.toFixed(2) ;
		proveedor.tot_general_1.value = tot_general.toFixed(2) ;
		if  (proveedor.GrupoOpciones1[0].checked == true && proveedor.pago_contado.checked == true )
		{
			  
			proveedor.leyenda.value = "Se abona con efectivo la cantidad de $ "+tot_general.toFixed(2);
			 
			 
		} 
	}
	*/	   
}	
</script> 
<script language="javascript">
function pendiente(form)
{
	if (proveedor.GrupoOpciones1[0].checked ==false)
    {

    } else {

 	}
}

</script>
<script type="text/javascript" src="AJAX/ajax.js"></script>
<script type="text/javascript">
var ajax = new Array();
function getCityList(sel)
{
	var concepto = sel.options[sel.selectedIndex].value;
	document.getElementById('concepto').options.length = 0;	// Empty city select box
	if(concepto.length>0){
		var index = ajax.length;
		ajax[index] = new sack();
		alert("HOLA"+concepto);
		ajax[index].requestFile = 'includes/getproveedor.php?concepto='+concepto; 		                                                                         // Specifying which file to get
		
		ajax.onCompletion = showClientData;	// Specify function that will be executed after file has been found
		ajax.runAJAX();		// Execute AJAX function
	}
}

function showClientData()
{
		var formObj = document.forms['descripcion'];	
		eval(ajax.response);
}
</script>
<script language="javascript">

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
        if (isNaN(val)) errors+='El importe debe contener un nÃºmero.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('ERROR \n'+errors);
  document.MM_returnValue = (errors == '');
}
function validar() { 
var prove = proveedor.concepto.value;
 if (prove==20) {
// alert(prove);
 proveedor.totneto.value=proveedor.importe.value;
   } 
}

function validar1() { 
var prove1 = proveedor.concepto1.value;
 if (prove1 ==20) {
// alert(prove1);
 proveedor.totneto.value=proveedor.importe1.value;
   } 
}

function validar2() { 
var prove2 = proveedor.concepto2.value;
 if (prove2 ==20) {
// alert(prove1);
 proveedor.totneto.value=proveedor.importe2.value;
   } 
}
function validar3() { 
var prove3 = proveedor.concepto3.value;
 if (prove3 ==20) {
// alert(prove1);
 proveedor.totneto.value=proveedor.importe3.value;
   } 
}

function validar4() { 
  var prove4 = proveedor.concepto4.value;
  if (prove4==20) {
    proveedor.totneto.value=proveedor.importe4.value;
  }
 }

function validar5() { 
  var prove5 = proveedor.concepto5.value;
  if (prove5==20) {
    proveedor.totneto.value=proveedor.importe5.value;
  }
 } 

function validar6() { 
  var prove6 = proveedor.concepto6.value;
  if (prove6==20) {
    proveedor.totneto.value=proveedor.importe6.value;
  }
  }

function validar7() { 
  var prove7 = proveedor.concepto7.value;
  if (prove7==20) {
    proveedor.totneto.value=proveedor.importe7.value;
  } 
  }

function validar8() { 
  var prove8 = proveedor.concepto8.value;
  if (prove8==20) {
    proveedor.totneto.value=proveedor.importe8.value;
  } 
  }
       
function validar9() { 
  var prove9 = proveedor.concepto9.value;
  if (prove9==20) {
    proveedor.totneto.value=proveedor.importe9.value;
  }   }
  /*
function validar10() { 
  var prove10 = proveedor.concepto10.value;
  if (prove10==20) {
    proveedor.totneto.value=proveedor.importe10.value;
  }  } 
 
function validar11() { 
  var prove11 = proveedor.concepto11.value;
  if (prove11==20) {
    proveedor.totneto.value=proveedor.importe11.value;
  }  }
function validar12() { 
  var prove12 = proveedor.concepto12.value;
  if (prove12==20) {
    proveedor.totneto.value=proveedor.importe12.value;
  }   }   
function validar13() { 
 var prove13 = proveedor.concepto13.value;
 if (prove13==20) {
 proveedor.totneto.value=proveedor.importe13.value;
} }
 
function validar14() { 
 var prove14 = proveedor.concepto14.value;
 if (prove14 ==20) {
proveedor.totneto.value=proveedor.importe14.value;
 }
} 
/*/
//-->
</script>
<script language="javascript">
function ValidoFecha(form)
{
    //alert (" 1 ");
    var fecha_cbte = form.fecha_f2.value;
    //alert (" 2 ");
    var fecha_tope = form.fecha_tope.value;
    //alert (" 3 ");
    var mes_tope = "04";
    //alert (" 4 ");
    var dia_tope = "30";
    //alert (" 5 ");
    var anio_tope = "2023";
    //alert (" 6 ");
    var anio = fecha_cbte.substring(6,10);
    //alert (" 7 ");
    var mes = fecha_cbte.substring(3,5);
    //alert (" 8 ");
    var dia = fecha_cbte.substring(0,2);

    //alert ("anio = "+anio);
    //alert ("mes = "+mes);
    //alert ("dia = "+dia);
    if (anio  < anio_tope) {
        alert ("La fecha de carga del comprobante debe ser posterior a "+fecha_tope);
        form.fecha_f2.value = "";
        form.fecha_f2.focus();
    } else if (anio  === anio_tope) {
                if (mes < mes_tope) {
                    alert ("La fecha de carga del comprobante debe ser posterior a "+fecha_tope);
                    form.fecha_f2.value = "";
                    form.fecha_f2.focus();
                } else if (mes === mes_tope) {
                            if (dia < dia_tope) {
                                alert ("La fecha de carga del comprobante debe ser posterior a "+fecha_tope);
                                form.fecha_f2.value = "";
                                form.fecha_f2.focus();
                            }
                }
            }
}   
    
</script>
<script language="javascript">
function validoNroFac(form)
{
	
	
}
</script>
<script language="javascript">
function ArmarNrodoc(form)
{
	//--> DESDE ACA
	var tipo_fac    = form.tcomp.value ; 
	var pto_vta     = form.pto_vta_prov.value ; 
	var nro_fac_pro = form.nro_fac_prov.value ;
	var error ="";
	var nrodoc = "";
  		//alert ("tipo_fac = "+tipo_fac);
	 	//alert ("pto_vta = "+pto_vta);
		//alert ("nro_fac_pro = "+nro_fac_pro);
	

	
	if (tipo_fac=="" || pto_vta=="" || nro_fac_pro=="") {
      	if (tipo_fac=="") {
         	error = "      Tipo de Factura\n"; 
			form.tcomp.focus();
			
			
        }
      	
	    if (pto_vta=="") {
        	error = error+"     Punto de Venta\n"; 
			form.pto_vta_prov.focus();
			
        }
	 	if (nro_fac_pro=="") {
        	error = error+"      Numero de Factura\n"; 
			form.nro_fac_prov.focus();
        }	
	 	
		alert ("Faltan los siguientes datos :\n"+error);
		return;
	} 
	else {
		
		//Primero veo lo de la letra
		if (tipo_fac == 32 || tipo_fac == 34 || tipo_fac == 36 || tipo_fac == 110) {
			nrodoc = nrodoc+"A";
		}
		else if(tipo_fac == 33 || tipo_fac == 35 || tipo_fac == 37)  {
			nrodoc = nrodoc+"C";	
			}
			else {
				nrodoc = nrodoc+"M";
			}
		// Valido si es numero
		if (!/^([0-9])*$/.test(pto_vta)) {
	    	alert("El valor " + pto_vta + " no es un nÃºmero");
			form.ncomp.value = "";
			form.pto_vta_pro.focus();
			return;
		}
		if (!/^([0-9])*$/.test(nro_fac_pro)) {
	    	alert("El valor " + nro_fac_pro + " no es un nÃºmero");
			form.ncomp.value = "";
			form.nro_fac_pro.focus();
			return;
		}
		// Luego los rangos
		if (pto_vta > 99999 || pto_vta < 1) {
			alert ("El punto de venta debe estar entre 1 y 99999");
			form.ncomp.value = "";
			form.pto_vta_pro.focus();
			return;
		}
		if (nro_fac_pro > 99999999 || nro_fac_pro < 1) {
			alert ("El Nro de Cbte debe estar entre 1 y 99999999");
			form.ncomp.value = "";
			form.nro_fac_prov.focus();
			return;
		}
		//Luego el pto de vta
		if (pto_vta > 999)
			nrodoc = nrodoc+pto_vta;
		else if 	(pto_vta > 99) {
				nrodoc = nrodoc+"0";
				nrodoc = nrodoc + pto_vta;	
			}
			else if (pto_vta > 9) {
					nrodoc = nrodoc+"00";
					nrodoc = nrodoc+pto_vta;	
				}
				else {
					nrodoc = nrodoc+"000";
					nrodoc = nrodoc+pto_vta;	
				}
		// El guion
		nrodoc = nrodoc + "-";
		//Finalmente el nro			 
		if (nro_fac_pro > 9999999)
			nrodoc = nrodoc+nro_fac_pro;
		else if 	(nro_fac_pro > 999999) {
				nrodoc = nrodoc+"0";
				nrodoc = nrodoc+nro_fac_pro;	
			}
			else if (nro_fac_pro > 99999) {
					nrodoc = nrodoc+"00";
					nrodoc = nrodoc+nro_fac_pro;	
				}
				else if (nro_fac_pro > 9999) {
						nrodoc = nrodoc+"000";
						nrodoc = nrodoc+nro_fac_pro;	
					}
					else if (nro_fac_pro > 999) {
							nrodoc = nrodoc+"0000";
							nrodoc = nrodoc+nro_fac_pro;	
						}
						else if (nro_fac_pro > 99) {
								nrodoc = nrodoc+"00000";
								nrodoc = nrodoc+nro_fac_pro;	
							}
							else if (nro_fac_pro > 9) {
									nrodoc = nrodoc+"000000";
									nrodoc = nrodoc+nro_fac_pro;	
								}
								else {
									nrodoc = nrodoc+"0000000";
									nrodoc = nrodoc+nro_fac_pro;	
								}

	//alert ("nrodoc ="+nrodoc);
	form.ncomp.value = nrodoc;
	}

}
</script>

</head>
<body>

<form id="proveedor" name="proveedor" method="POST" class="form-horizontal ewForm ewAddForm" action="<?php echo $editFormAction; ?>">
  <table height="848" border="2" align="left" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    <td colspan="2"  height="30" border="0" cellpadding="1" cellspacing="1" background="images/fonod_lote.jpg"  bgcolor="#FFFFFF"></td>
      <td colspan="2"  height="30" border="0" cellpadding="1" cellspacing="1" background="images/fonod_lote.jpg"  bgcolor="#FFFFFF"></td>
    <td>
    <tr>
<td colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="22%" height="20" bgcolor="#FFFFFF"><span class="ewTableHeader">Tipo de comprobante </span></td>
          <td width="24%"><select name="tipos" onchange="agregarOpciones(this.form)" >
                                      <option value="">[seleccione una opci&oacute;n]</option>
                                      <option value="16">FACT PROVEEDOR A</option>
                                      <option value="17">FACT PROVEEDOR C</option>
									  <option value="33">FACT PROVEEDOR M</option>
                                      <option value="18">NOTA DEB PROVEEDOR A</option>
                                      <option value="19">NOTA DEB PROVEEDOR C</option>
			  							<option value="36">NOTA DEB PROVEEDOR M</option>
									  <option value="20">NOTA CRED PROVEEDOR A</option>
                                      <option value="21">NOTA CRED PROVEEDOR C</option>
			  						<option value="35">NOTA CRED PROVEEDOR M</option>
                                      <option value="47">FACT PROVEEDOR EXT</option>
                                      <option value="100">LIQUID. GASTOS COMUNES</option>
                                      
                           </select>
		         </td>
          <td width="7%"></td>
          <td width="13%" class="ewTableHeader">Serie</td>
          <td width="34%"><input name="serie_texto" type="text"  size="20" />
		  <input name="serie" type="hidden"   size="20"/>
		  <input name="tcomp" type="hidden"   size="20"/>
		  <input name="num_factura"  type="hidden"   />
          <input name="fecha_tope"  type="hidden"  value="30-09-2022" />
		
            </td>
        </tr>
        <tr>
        <td height="20" class="ewTableHeader">Pto. de Vta. de Factura </td>
          <td><input name="pto_vta_prov" type="text" size="4" class="phpmakerlist" id="pto_vta_prov" /></td>
          <td height="20" class="ewTableHeader">Nro. de Factura </td>
          <td><input name="nro_fac_prov" type="text" size="8"  class="phpmakerlist" id="nro_fac_prov" /></td>
         </tr> 
         <tr>
          <td height="20" class="ewTableHeader">Nro. Completo de Factura </td>
          <td><input name="ncomp" type="text" size="16" id="ncomp" onclick="ArmarNrodoc(this.form)"  /></td>
          
          <td width="30">Fecha Registración </td>
          <td width="30"><input name="fecha_f2" id="fecha_f2" required="required" onchange="ValidoFecha(this.form)" type="text" size="11" /></td>
             
            <td width="30">Fecha Cbte </td>
          <td width="30"><input name="fecha_f3" id="fecha_f3" required="required"  type="text" size="11" /></td>
          
        </tr>
        
        <tr>
          <td height="20" ></td>
          <td></td>
          <td></td>
          <td colspan="2" rowspan="4" valign="top" bgcolor="#FFFFFF" ><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFFFFF">
            </tr>
              <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="images/cond_pago.gif" width="150" height="30" /></td>
            </tr>
            <tr>
              <td width="48%" bgcolor="#FFFFFF">&nbsp;<span class="ewTableHeader">Cancelada</span></td>
              <td width="52%" bgcolor="#FFFFFF"><input name="GrupoOpciones1" type="radio" value="S" checked="checked"onclick="pendiente(this.form)"  /></td>
            </tr>
            <tr>
              <td bgcolor="#FFFFFF">&nbsp;<span class="ewTableHeader">Pendiente de pago</span></td>
              <td bgcolor="#FFFFFF"><input name="GrupoOpciones1" type="radio" value="P" onclick="pendiente(this.form)"  /></td>
            </tr>
          </table></td>
          </tr>
        
		<tr>
         
          <td></td>
          <td><input name="fecha_factura" type="hidden" size="12" /></td>
        </tr>
        <tr>
          <td height="10" class="ewTableHeader"> Proveedor </td>
          <td><select name="codnum" id="codnum" onchange="ValidoRepe(this.form)">
            <option value="">Proveedor</option>
		
            <?php
do {  
?>
            <option value="<?php echo $row_cliente['codnum']?>"><?php echo substr($row_cliente['razsoc'],0,30)?></option>
            <?php
} while ($row_cliente = mysqli_fetch_assoc($cliente));
  	$rows = mysqli_num_rows($cliente);
  if($rows > 0) {
      mysqli_data_seek($cliente, 0);
	  $row_cliente = mysqli_fetch_assoc($cliente);
	 		
  }
?>
          </select></td>
          <td></td>
        </tr>
        
      </table></td>
    </tr>
    <tr>
     
    </tr>
    <tr>
      <td colspan="3">
	  
	   
	  		<table width="100%" border="0" cellpadding="1" cellspacing="1" background="images/fonod_lote.jpg"  bgcolor="#FFFFFF">
        <tr>
          <td width="40" height="24" class="ewTableHeader">
              <div align="center">Concepto</div></td>
              <td width="30" class="ewTableHeader">
              <div align="center">% de IVA</div></td>
          <td width="300" class="ewTableHeader">
              <div align="center">Descripci&oacute;n</div></td>
          
          <td width="75" class="ewTableHeader">
              <div align="center">Importe</div></td>
		 </tr>
        
        
		<tr>
          <td bgcolor="#FFFFFF"><select name="concepto" onchange="getprov(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);?>
<input name="imp" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><select name="tipoiva" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="55" />		  </td><input name="secuencia" type="hidden" class="phpmaker"  size="55"  value="1"/>
       
         
          <td width="75" bgcolor="#FFFFFF">
          <input name="importe" type="text" id="importe" onchange="validarFormulario(this.form)" size="10"/>
          </td>
          </tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto1" onchange="getprov1(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
 

          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva1" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu1" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="55" /></td>
     <input name="secuencia1" type="hidden" class="phpmaker" id="secuencia1" size="55" value="2" />
    <input name="imp1" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>    
    <td width="75" bgcolor="#FFFFFF"><input name="importe1" type="text" id="importe1"  onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe1','','NisNum');return document.MM_returnValue" size="10"  /></td>
	
	    </tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto2" onchange="getprov2(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva2" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu2" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="55" /></td>
        
          <td width="75" bgcolor="#FFFFFF"><input name="importe2" type="text" id="importe2" onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe2','','NisNum');return document.MM_returnValue" size="10"  /></td>
		  <input name="secuencia2" type="hidden" class="phpmaker" id="secuencia2" size="65" value="3"/>
          <input name="imp2" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/> 
		</tr>
		  <tr>
          <td bgcolor="#FFFFFF"><select name="concepto3" onchange="getprov3(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva3" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu3" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="55" /></td>
          
          <td width="75" bgcolor="#FFFFFF"><input name="importe3" type="text" id="importe3" onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe3','','NisNum');return document.MM_returnValue" size="10"  /></td>
		  <input name="secuencia3" type="hidden" class="phpmaker" id="secuencia3" size="65" value="4"/>
          <input name="imp3" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr>
		  <tr>
          <td bgcolor="#FFFFFF"><select name="concepto4" onchange="getprov4(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva4" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu4" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="55" /></td>
           
          <td width="75" bgcolor="#FFFFFF"><input name="importe4" type="text" id="importe4" onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe4','','NisNum');return document.MM_returnValue" size="10"  /></td>
		   <input name="secuencia4" type="hidden" class="phpmaker" id="secuencia4" size="65" value="5"/>
           <input name="imp4" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr>
		  <tr>
 <td bgcolor="#FFFFFF"><select name="concepto5" onchange="getprov5(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva5" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu5" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="55" /></td>
           
          <td width="75" bgcolor="#FFFFFF"><input name="importe5" type="text" id="importe5" onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe5','','NisNum');return document.MM_returnValue" size="10"  /></td>
		   <input name="secuencia5" type="hidden" class="phpmaker" id="secuencia5" size="65" value="6"/>
           <input name="imp5" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		
		</tr>
		 <tr>
 <td bgcolor="#FFFFFF"><select name="concepto6" onchange="getprov6(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva6" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu6" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="55" /></td>
           
          <td width="75" bgcolor="#FFFFFF"><input name="importe6" type="text" id="importe6" onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe6','','NisNum');return document.MM_returnValue" size="10"  /></td>
		   <input name="secuencia6" type="hidden" class="phpmaker" id="secuencia6" size="65" value="7"/>
           <input name="imp6" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr>
		 <tr>
         <td bgcolor="#FFFFFF"><select name="concepto7" onchange="getprov7(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva7" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu7" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="55" /></td>
           
          <td width="75" bgcolor="#FFFFFF"><input name="importe7" type="text" id="importe7" onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe7','','NisNum');return document.MM_returnValue" size="10"  /></td>
		   <input name="secuencia7" type="hidden" class="phpmaker" id="secuencia7" size="65" value="8"/>
           <input name="imp7" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr>
		 <tr>
             <td bgcolor="#FFFFFF"><select name="concepto8" onchange="getprov8(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva8" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu8" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="55" /></td>
           
          <td width="75" bgcolor="#FFFFFF"><input name="importe8" type="text" id="importe8" onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe8','','NisNum');return document.MM_returnValue" size="10"  /></td>
		   <input name="secuencia8" type="hidden" class="phpmaker" id="secuencia8" size="65" value="9"/>
           <input name="imp8" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>	
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto9" onchange="getprov9(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva9" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu9" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="55" /></td>
           
          <td width="75" bgcolor="#FFFFFF"><input name="importe9" type="text" id="importe9" onchange="validarFormulario(this.form)"; onblur="MM_validateForm('importe9','','NisNum');return document.MM_returnValue" size="10"  /></td>
		   <input name="secuencia9" type="hidden" class="phpmaker" id="secuencia9" size="65" value="10"/>
           <input name="imp9" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>	
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto10" onchange="getprov10(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva10" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu10" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="55" /></td>
         
          <td width="75" bgcolor="#FFFFFF"><input name="importe10" type="text" id="importe10" onblur="MM_validateForm('importe10','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <input name="secuencia10" type="hidden" class="phpmaker" id="secuencia10" size="65" value="11"/>
          <input name="imp10" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto11" onchange="getprov11(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva11" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu11" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="55" /></td>
          
          <td width="75" bgcolor="#FFFFFF"><input name="importe11" type="text" id="importe11" onblur="MM_validateForm('importe11','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <input name="secuencia11" type="hidden" class="phpmaker" id="secuencia11" size="65" value="12"/>
          <input name="imp11" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto12" onchange="getprov12(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva12" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu12" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="55" /></td>
         
          <td width="75" bgcolor="#FFFFFF"><input name="importe12" type="text" id="importe12" onblur="MM_validateForm('importe12','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <input name="secuencia12" type="hidden" class="phpmaker" id="secuencia12" size="65" value="13"/>
          <input name="imp12" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto13" onchange="getprov13(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva13" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu13" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="55" /></td>
        
          <td width="75" bgcolor="#FFFFFF"><input name="importe13" type="text" id="importe13" onblur="MM_validateForm('importe13','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <input name="secuencia13" type="hidden" class="phpmaker" id="secuencia13" size="65" value="14"/>
         <input name="imp13" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr> <tr>
          <td bgcolor="#FFFFFF"><select name="concepto14" onchange="getprov14(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc']?>"><?php echo $row_conceptos_a_facturar['nroconc']?>&nbsp;<?php echo $row_conceptos_a_facturar['descrip']?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
               <td bgcolor="#FFFFFF"><select name="tipoiva14" onchange="getprov(this.form)">
		  <option value="">[% de IVA]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_impuesto['porcen'];?>"><?php echo $row_impuesto['porcen'];?></option>
            <?php
} while ($row_impuesto = mysqli_fetch_assoc($impuesto));
  $rows = mysqli_num_rows($impuesto);
  if($rows > 0) {
      mysqli_data_seek($impuesto, 0);
	  $row_impuesto = mysqli_fetch_assoc($impuesto);?>
<input name="impu14" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_impuesto['porcen'];?>"/>
  
  <?php
  }
?>
          </select></td>
          <td bgcolor="#FFFFFF"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="55" /></td>
       
          <td width="75" bgcolor="#FFFFFF"><input name="importe14" type="text" id="importe14" onblur="MM_validateForm('importe14','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <input name="secuencia14" type="hidden" class="phpmaker" id="secuencia14" size="65" value="15"/>
         <input name="imp14" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
		</tr>
      </table>      </td>
    </tr>
    
     
    <tr>
      	<td colspan="3" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    	<td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Importe Neto </div></td>
        <td width="12%"><input name="totneto" type="text" id="totneto" size="15" style="text-align:right"/></td>
        <td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center"></div></td>
  	</tr>
    
        <tr>
    	<td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva 2,5 %</div></td>
        <td><input name="totiva025"  type="text" id="totiva025" size="15" style="text-align:right"/></td>
        <td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center"></div></td>
  	</tr>
 <tr>
    	<td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva 5 %</div></td>
        <td><input name="totiva05"  type="text" id="totiva05" size="15" style="text-align:right"/></td>
        <td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center"></div></td>
  	</tr>
 
    <tr>
    	<td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php echo "10.5 %" ?></div></td>
        <td><input name="totiva105"  type="text" id="totiva105" size="15" style="text-align:right"/></td>
        <td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center"></div></td>
  	</tr>
    <tr>
    	<td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva 21% </div></td>
        <td><input name="totiva21"  type="text" id="totiva21" size="15" style="text-align:right"/></td>
        <td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center"></div></td>
  	</tr>
    <tr>
    	<td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva 27 %</div></td>
        <td><input name="totiva27"  type="text" id="totiva27" size="15" style="text-align:right"/></td>
        <td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center"></div></td>
  	</tr>
    <tr>
    	<td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Otros tributos</div></td>
        <td><input name="totreten"  type="text" id="totreten" size="15" style="text-align:right"/></td>
        <td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center"></div></td>
  	</tr>
    <tr>
    	<td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Total </div></td>
		<td><input name="tot_general" type="text" id="tot_general" size="15"  style="text-align:right"/></td>
        <td width="44%" height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center"></div></td>
    </tr>
 </table>
 </td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
         
          <td colspan="3"  align="center">
            <input type="submit" name="Submit3" value="Grabar" onclick="ValidoForm(this.form)" />
           </td>
        </tr>
      </table></td>
    </tr>
  </table>
    <input type="hidden" name="MM_insert" value="proveedor">
</body>
</form>
</html>
