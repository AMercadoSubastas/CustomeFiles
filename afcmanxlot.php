<?php 
require_once('Connections/amercado.php'); 
include_once "src/userfn.php";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


$renglones = 0;
$primera_vez = 1;
if (isset($_POST['lote']) && GetSQLValueString($_POST['lote'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
		mysqli_select_db($amercado, $database_amercado);
		$actualiza1 = sprintf("UPDATE `series` SET `nroact` = %s WHERE `series`.`codnum` = %s", GetSQLValueString($_POST['num_factura'], "int"), GetSQLValueString($_POST['serie'], "int")) ;				 
		$resultado=mysqli_query($amercado,	$actualiza1);	

	}
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       GetSQLValueString($_POST['num_factura'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia0'], "int"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['importe'], "double"),
					   GetSQLValueString($_POST['impuesto'], "double"),
                       GetSQLValueString($_POST['comision'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 1;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia0'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote1']) && GetSQLValueString($_POST['lote1'], "int")!="NULL") {

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       	GetSQLValueString($_POST['tcomp'], "int"),
                       	GetSQLValueString($_POST['serie'], "int"),
                       	GetSQLValueString($_POST['num_factura'], "int"),
                       	GetSQLValueString($_POST['remate_num'], "int"),
                       	GetSQLValueString($_POST['secuencia1'], "int"),
                       	GetSQLValueString($_POST['descripcion1'], "text"),
                       	GetSQLValueString($_POST['importe1'], "double"),
			GetSQLValueString($_POST['impuesto1'], "double"),
                       	GetSQLValueString($_POST['comision1'], "double"));


  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 2;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe1'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia1'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote2']) && GetSQLValueString($_POST['lote2'], "int")!="NULL") {

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                        GetSQLValueString($_POST['tcomp'], "int"),
                        GetSQLValueString($_POST['serie'], "int"),
                        GetSQLValueString($_POST['num_factura'], "int"),
                        GetSQLValueString($_POST['remate_num'], "int"),
                        GetSQLValueString($_POST['secuencia2'], "int"),
                        GetSQLValueString($_POST['descripcion2'], "text"),
                        GetSQLValueString($_POST['importe2'], "double"),
						GetSQLValueString($_POST['impuesto2'], "double"),
                        GetSQLValueString($_POST['comision2'], "double"));


  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 3;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe2'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia2'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote3']) && GetSQLValueString($_POST['lote3'], "int")!="NULL") {

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       	GetSQLValueString($_POST['tcomp'], "int"),
                       	GetSQLValueString($_POST['serie'], "int"),
                       	GetSQLValueString($_POST['num_factura'], "int"),
                       	GetSQLValueString($_POST['remate_num'], "int"),
                       	GetSQLValueString($_POST['secuencia3'], "int"),
                       	GetSQLValueString($_POST['descripcion3'], "text"),
                       	GetSQLValueString($_POST['importe3'], "double"),
		       			GetSQLValueString($_POST['impuesto3'], "double"),
                       	GetSQLValueString($_POST['comision3'], "double"));


  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 4;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe3'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia3'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote4']) && GetSQLValueString($_POST['lote4'], "int")!="NULL") {

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia4'], "int"),
                GetSQLValueString($_POST['descripcion4'], "text"),
				GetSQLValueString($_POST['importe4'], "double"),
		       	GetSQLValueString($_POST['impuesto4'], "double"),
                GetSQLValueString($_POST['comision4'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 5;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe4'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia4'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote5']) && GetSQLValueString($_POST['lote5'], "int")!="NULL") {

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
               GetSQLValueString($_POST['tcomp'], "int"),
               GetSQLValueString($_POST['serie'], "int"),
               GetSQLValueString($_POST['num_factura'], "int"),
               GetSQLValueString($_POST['remate_num'], "int"),
               GetSQLValueString($_POST['secuencia5'], "int"),
               GetSQLValueString($_POST['descripcion5'], "text"),
               GetSQLValueString($_POST['importe5'], "double"),
		       GetSQLValueString($_POST['impuesto5'], "double"),
               GetSQLValueString($_POST['comision5'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 6;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe5'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia5'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

	if (isset($_POST['lote6']) && GetSQLValueString($_POST['lote6'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia6'], "int"),
                GetSQLValueString($_POST['descripcion6'], "text"),
                GetSQLValueString($_POST['importe6'], "double"),
				GetSQLValueString($_POST['impuesto6'], "double"),
                GetSQLValueString($_POST['comision6'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 7;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe6'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia6'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote7']) && GetSQLValueString($_POST['lote7'], "int")!="NULL") {

	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
              	GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia7'], "int"),
                GetSQLValueString($_POST['descripcion7'], "text"),
                GetSQLValueString($_POST['importe7'], "double"),
		       	GetSQLValueString($_POST['impuesto7'], "double"),
                GetSQLValueString($_POST['comision7'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 8;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe7'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia7'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote8']) && GetSQLValueString($_POST['lote8'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia8'], "int"),
                GetSQLValueString($_POST['descripcion8'], "text"),
                GetSQLValueString($_POST['importe8'], "double"),
		       	GetSQLValueString($_POST['impuesto8'], "double"),
                GetSQLValueString($_POST['comision8'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 9;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe8'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia8'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote9']) && GetSQLValueString($_POST['lote9'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia9'], "int"),
                GetSQLValueString($_POST['descripcion9'], "text"),
                GetSQLValueString($_POST['importe9'], "double"),
		       	GetSQLValueString($_POST['impuesto9'], "double"),
                GetSQLValueString($_POST['comision9'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 10;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe9'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia9'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote10']) && GetSQLValueString($_POST['lote10'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia10'], "int"),
                GetSQLValueString($_POST['descripcion10'], "text"),
                GetSQLValueString($_POST['importe10'], "double"),
		        GetSQLValueString($_POST['impuesto10'], "double"),
                GetSQLValueString($_POST['comision10'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 11;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe10'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia10'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote11']) && GetSQLValueString($_POST['lote11'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia11'], "int"),
                GetSQLValueString($_POST['descripcion11'], "text"),
                GetSQLValueString($_POST['importe11'], "double"),
		       	GetSQLValueString($_POST['impuesto11'], "double"),
                GetSQLValueString($_POST['comision11'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 12;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe11'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia11'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote12']) && GetSQLValueString($_POST['lote12'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia12'], "int"),
                GetSQLValueString($_POST['descripcion12'], "text"),
                GetSQLValueString($_POST['importe12'], "double"),
		       	GetSQLValueString($_POST['impuesto12'], "double"),
                GetSQLValueString($_POST['comision12'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 13;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe12'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia12'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote13']) && GetSQLValueString($_POST['lote13'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia13'], "int"),
                GetSQLValueString($_POST['descripcion13'], "text"),
                GetSQLValueString($_POST['importe13'], "double"),
		       	GetSQLValueString($_POST['impuesto13'], "double"),
                GetSQLValueString($_POST['comision13'], "double"));

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 14;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe13'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia13'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote14']) && GetSQLValueString($_POST['lote14'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  		$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($_POST['tcomp'], "int"),
                GetSQLValueString($_POST['serie'], "int"),
                GetSQLValueString($_POST['num_factura'], "int"),
                GetSQLValueString($_POST['remate_num'], "int"),
                GetSQLValueString($_POST['secuencia14'], "int"),
                GetSQLValueString($_POST['descripcion14'], "text"),
                GetSQLValueString($_POST['importe14'], "double"),
		       	GetSQLValueString($_POST['impuesto14'], "double"),
                GetSQLValueString($_POST['comision14'], "double"));
					   

  		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 15;
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   		$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe14'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia14'], "int"));
  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}

if (isset($_POST['lote']) && GetSQLValueString($_POST['lote'], "int")!="NULL") {
  	$tcomp = $_POST['tcomp'];
  	$serie = $_POST['serie'];
  	$num_fac = $_POST['num_factura'];
  	$query_mascara = "SELECT * FROM series  WHERE series.tipcomp='$tcomp'  AND series.codnum='$serie'";
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
  		$insertSQL = sprintf("INSERT INTO cabfac (tcomp, serie, ncomp, fecval, fecdoc, fecreg, cliente, fecvenc, estado, emitido, codrem, totbruto, totiva105, totiva21, totimp, totcomis, totneto105, totneto21, nrengs, nrodoc , tieneresol) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
		
                       	GetSQLValueString($_POST['tcomp'], "int"),
                       	GetSQLValueString($_POST['serie'], "int"),
                       	GetSQLValueString($_POST['num_factura'], "int"),
                       	GetSQLValueString($_POST['fecha_factura'], "date"),
                       	GetSQLValueString($_POST['fecha_factura'], "date"),
                       	GetSQLValueString($_POST['fecha_factura'], "date"),
                       	GetSQLValueString($_POST['codnum'], "int"),
                       	GetSQLValueString($_POST['fecha_factura'], "date"),
		       			GetSQLValueString($_POST['GrupoOpciones1'], "text"),
		       			GetSQLValueString("0", "int"),
                       	GetSQLValueString($_POST['remate_num'], "int"),
                       	GetSQLValueString($_POST['tot_general'], "double"),
                       	GetSQLValueString($_POST['totiva105'], "double"),
                       	GetSQLValueString($_POST['totiva21'], "double"),
                       	GetSQLValueString($_POST['totimp'], "double"),
                       	GetSQLValueString($_POST['totcomis'], "double"),
                       	GetSQLValueString($_POST['totneto105'], "double"),
                       	GetSQLValueString($_POST['totneto21'], "double"),
	   	       			GetSQLValueString($renglones, "int"),
		       			GetSQLValueString($mascara, "text"),
                       	GetSQLValueString(isset($_POST['tieneresol']) ? "true" : "", "defined","1","0"));

  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  		if ($hay_lotes_pendientes == 1) {
  		 	$updateSQL = sprintf("UPDATE remates SET estado = %s , imptot = %s WHERE ncomp = %s" ,
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['tot_general'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
 		}
 		else {
 			$updateSQL = sprintf("UPDATE remates SET estado = %s , imptot = %s WHERE ncomp = %s" ,
  				GetSQLValueString("2", "int"),
				GetSQLValueString($_POST['tot_general'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
 		}
	}
	// Medios de Pago  en Efectivo
	$tcomprel = $_POST['tcomp'];
	$serierel = $_POST['serie'];
	$factnum = $_POST['num_factura'];


 	mysqli_select_db($amercado, $database_amercado);
	$query_medios_pago = "SELECT * FROM cartvalores WHERE tcomprel = '$tcomprel' AND serierel = '$serierel'  AND ncomprel= '$factnum'";

	$medios_pago = mysqli_query($amercado, $query_medios_pago) or die(mysqli_error($amercado));
	$totalRows_medios_pago = mysqli_num_rows($medios_pago);


	if ($totalRows_medios_pago==0 && strcmp(GetSQLValueString($_POST['GrupoOpciones1'], "text"),"'S'")==0) 
	{
		
   		// Levanto ultimo Comprobante y sumo 1
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
                    GetSQLValueString("P", "text"),
					GetSQLValueString("1", "int"),
					GetSQLValueString($_POST['remate_num'], "int"),
                    GetSQLValueString($_POST['num_factura'], "int"));
		
		// 4. Ejecuto la consulta.	
		$result = mysqli_query($amercado, $strSQL);				         
			
		$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='8'" ;	
		$resultado=mysqli_query($amercado,	$actualiza);
		$total_fc = GetSQLValueString($_POST['tot_general'], "text");

	} 
	// Inserto la leyenda
	if (GetSQLValueString($_POST['leyenda'], "text")!="NULL") {

 		$insertSQLfactley = sprintf("INSERT INTO factley (tcomp, serie, ncomp, leyendafc, codrem) VALUES (%s, %s, %s, %s, %s)",
               	GetSQLValueString($_POST['tcomp'], "int"),
               	GetSQLValueString($_POST['serie'], "int"),
               	GetSQLValueString($_POST['num_factura'], "int"),
		       	GetSQLValueString($_POST['leyenda'], "text"),
		       	GetSQLValueString($_POST['remate_num'], "int"));

  		mysqli_select_db($amercado, $database_amercado);
  		$Result1 = mysqli_query($amercado, $insertSQLfactley) or die(mysqli_error($amercado));

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
 			//$insertGoTo = "factura_ok.php?factura=$facnum";
  			if (isset($_SERVER['QUERY_STRING'])) {
   				$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    			$insertGoTo .= $_SERVER['QUERY_STRING'];
  		}
 		header(sprintf("Location: %s", "factura_ok.php?factura=$facnum")); 
	}
}


setcookie("factura","");
mysqli_select_db($amercado, $database_amercado);
$query_facturas_a = "SELECT * FROM series  WHERE series.tipcomp=6  AND series.codnum=5";
$facturas_a = mysqli_query($amercado, $query_facturas_a) or die(mysqli_error($amercado));
$row_facturas_a = mysqli_fetch_assoc($facturas_a);
$totalRows_facturas_a = mysqli_num_rows($facturas_a);
$facturanum1 = ($row_facturas_a['nroact'])+1;
$tcomp = $row_facturas_a['tipcomp'];

$query_facturas_b = "SELECT * FROM series  WHERE  series.tipcomp=24 AND  series.codnum=12";
$facturas_b = mysqli_query($amercado, $query_facturas_b) or die(mysqli_error($amercado));
$row_facturas_b = mysqli_fetch_assoc($facturas_b);
$totalRows_facturas_b = mysqli_num_rows($facturas_b);
$facturanum2= ($row_facturas_b['nroact'])+1;

?>
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
		factura.serie.value = 5;
		factura.serie_texto.value = "SERIE DE FACT. MAN A0002";
		factura.tcomp.value = 6;
		//factura.num_factura.value = <?php echo $facturanum1 ?>;
    }

    if (selec[2].selected == true)
    {
	
		factura.serie.value = 12;
		factura.serie_texto.value = "SERIE DE FACT. MAN B0002";
		factura.tcomp.value = 24;
		//factura.num_factura.value = <?php echo $facturanum2 ?>;
    }
}
</script>


<?php mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE tipoent='1' AND activo = '1' ORDER BY razsoc ASC";
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php require_once('Connections/amercado.php');  ?>

<?php
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
//header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache"); // HTTP/1.0
?>
<script src="cjl_cookie.js" type="text/javascript"></script>
<script language="javascript">
function forma_pago(form) {
  	var total      = factura.tot_general.value;  // Total de la boleta
  	var fac_numero = factura.num_factura.value ; // Nuemro de la Factura 
  	var tipo_comprobante = factura.tcomp.value ;  // Tipo de comprobante 
  	var serie_num     = factura.serie.value ;  // Numero de Serie 
  	var fecha_factura = factura.fecha_factura.value ; //  Fecha de Factura
  	var remate_num    = factura.remate_num.value ; // Numero de Remate
  	var chequedo = factura.pago_contado.value ;
  	factura.leyenda.value = "";
  	var error ="";
  	// DESDE ACA VALIDACION DE IMPUESTOS//
 
  	var lote  = factura.lote.value ;
  	var lote1 = factura.lote1.value ;
  	var lote2 = factura.lote2.value ;
  	var lote3 = factura.lote3.value ;
  	var lote4 = factura.lote4.value ;
  	var lote5 = factura.lote5.value ;
  	var lote6 = factura.lote6.value ;
  	var lote7 = factura.lote7.value ;
  	var lote8 = factura.lote8.value ;
  	var lote9 = factura.lote9.value ;
  	var lote10 = factura.lote10.value ;
  	var lote11 = factura.lote11.value ;
  	var lote12 = factura.lote12.value ;
  	var lote13 = factura.lote13.value ;
  	var lote14 = factura.lote14.value ;
  	var impuesto =   factura.impuesto[0].checked;//
  	var impuesto_0 = factura.impuesto[1].checked;//
  	var impuesto1 =   factura.impuesto1[0].checked;//
  	var impuesto1_0 = factura.impuesto1[1].checked;//
  	var impuesto2 =   factura.impuesto2[0].checked;//
  	var impuesto2_0 = factura.impuesto2[1].checked;//
  	var impuesto3 =   factura.impuesto3[0].checked;//
  	var impuesto3_0 = factura.impuesto3[1].checked;//
  	var impuesto4 =   factura.impuesto4[0].checked;//
  	var impuesto4_0 = factura.impuesto4[1].checked;//
  
  	var impuesto5 =   factura.impuesto5[0].checked;//
  	var impuesto5_0 = factura.impuesto5[1].checked;//
  	var impuesto6 =   factura.impuesto6[0].checked;//
  	var impuesto6_0 = factura.impuesto6[1].checked;//
  	var impuesto7 =   factura.impuesto7[0].checked;//
  	var impuesto7_0 = factura.impuesto7[1].checked;//
  	var impuesto8 =   factura.impuesto8[0].checked;//
  	var impuesto8_0 = factura.impuesto8[1].checked;//
  	var impuesto9 =   factura.impuesto9[0].checked;//
  	var impuesto9_0 = factura.impuesto9[1].checked;//
  
  	var impuesto10 =   factura.impuesto10[0].checked;//
  	var impuesto10_0 = factura.impuesto10[1].checked;//
  	var impuesto11 =   factura.impuesto11[0].checked;//
  	var impuesto11_0 = factura.impuesto11[1].checked;//
  	var impuesto12 =   factura.impuesto12[0].checked;//
  	var impuesto12_0 = factura.impuesto12[1].checked;//
  	var impuesto13 =   factura.impuesto13[0].checked;//
  	var impuesto13_0 = factura.impuesto13[1].checked;//
  	var impuesto14 =   factura.impuesto14[0].checked;//
  	var impuesto14_0 = factura.impuesto14[1].checked;//
 
  	var imp = 1;
  
   //alert("Estoy en forma_pago");
  
  	if (impuesto==false && impuesto_0==false && lote !="" ) {
		//  alert("Primer Lote sin marcar");
	  	var imp = 0; 
  	}
	//  alert(impuesto1);
 	//  alert(impuesto1_0);
   	if (impuesto1==false && impuesto1_0==false && lote1 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
  
   	if (impuesto2==false && impuesto2_0==false && lote2 !="" ) {
	  	//alert("Segundo Lote sin marcar");
	 	var imp = 0; 
  	}
  
   	if (impuesto3==false && impuesto3_0==false && lote3 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
  
   	if (impuesto4==false && impuesto4_0==false && lote4 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
  
   	if (impuesto5==false && impuesto5_0==false && lote5 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
  
   	if (impuesto6==false && impuesto6_0==false && lote6 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
  
   	if (impuesto7==false && impuesto7_0==false && lote7 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
  
   	if (impuesto8==false && impuesto8_0==false && lote8 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
  
   	if (impuesto9==false && impuesto9_0==false && lote9 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
   	
	if (impuesto10==false && impuesto10_0==false && lote10 !="" ) {
	 	// alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
   	
	if (impuesto11==false && impuesto11_0==false && lote11 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
   	
	if (impuesto12==false && impuesto12_0==false && lote12 !="" ) {
		//  alert("Segundo Lote sin marcar");
	  	var imp = 0; 
  	}
   	
	if (impuesto13==false && impuesto13_0==false && lote13 !="" ) {
		//  alert("Lote 13 sin marcar");
	  	var imp = 0; 
  	}
   	
	if (impuesto14==false && impuesto14_0==false && lote14 !="" ) {
		// alert("Lote 14 sin marcar");
	  	var imp = 0; 
  	}
	
	//alert("Estoy en forma_pago 2 ");
	
	if (tipo_comprobante=="" || total=="" || serie_num=="" || remate_num=="" || imp==0 || fecha_factura=="") {
		if (tipo_comprobante=="") {
      		error = "      Tipo de comprobante\n"; 
        }
		if (serie_num=="") {
        	error = error+"      Serie\n"; 
        }
	 	if (remate_num=="") {
        	error = error+"      Numero de remate\n"; 
        }	
	 	if (total=="") {
        	error = error+"      Total general\n"; 
        }
	 	if (imp==0) {
        	error = error+"      Impuesto\n"; 
        }	
		if (fecha_factura=="") {
        	error = error+"      Fecha de Factura\n"; 
        }	 
		  
  		alert ("Faltan los siguientes datos :\n"+error);
  	} else {
  		// escribimos el mensaje de alerta
		strAlerta = "Total Factura " + total + "\n" + "Numero Factura " + fac_numero + "\n" + "Tipo de comprobante " + tipo_comprobante + "\n" + "Serie Numero " + serie_num + "\n" + "Fecha Factura " + fecha_factura + "\n" + "Numero de remate " + remate_num + "\n";

		// lanzamos la acci�n
		// alert("Dentro coocke")
		
		//alert("Estoy en forma_pago 3 ");
		 
   		var f = document.forms[0] ;
		//alert("Estoy en forma_pago 3.1 ");
   		var ckUtil = new CJL_CookieUtil("factura", 30);
 		//alert("Estoy en forma_pago 3.2 ");
		function setFieldFromCookie(fieldId) {
			//alert("Estoy en forma_pago 3.3 ");
	  		var cookieVal = ckUtil.getSubValue(fieldId);
			//alert("Estoy en forma_pago 3.4 ");
	  		if( cookieVal )	{
				//alert("Estoy en forma_pago 3.5 ");
	     		f[fieldId].value = cookieVal;
				//alert("Estoy en forma_pago 3.6 ");
		  	}
			//alert("Estoy en forma_pago 3.7 ");
   		}
   
  		//alert("Estoy en forma_pago 4 "); 
   		
		function saveFieldToCookie(fieldId)	{
	  		var fieldVal = f[fieldId].value;	  
	 	  	ckUtil.setSubValue(fieldId, fieldVal);	  	  
   		}

		//alert("Estoy en forma_pago 5 ");  
   		
		if( ckUtil.cookieExists() )	{
      
   			setFieldFromCookie("tcomp");
   			setFieldFromCookie("serie");
   			setFieldFromCookie("remate_num");
   			setFieldFromCookie("num_factura");
   			setFieldFromCookie("tot_general");
      
    	} else 	{
      		saveFieldToCookie("tcomp");
      		saveFieldToCookie("serie");
      		saveFieldToCookie("remate_num");
	  		saveFieldToCookie("num_factura");
	  		saveFieldToCookie("tot_general");	  
	  		 
   		}
		//alert("Voy a medios_pago.php");
		window.open("medios_pago.php","nueva","fullscreen,scrollbars");
	}
	//alert("Estoy en forma_pago 6");
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
	factura.fecha_factura.value = fecha1;

}

function pasaValor(form)
{ 

	var comprobante = factura.tcomp.value;  // Tipo de cbte
	var serie       = factura.serie.value; // Serie
	var factnum     = factura.num_factura.value; // Nro de cbte
	var fecha_fact  = factura.fecha_factura.value; // Fecha
	var remate      = factura.remate_num.value; // Nro del remate

 }
</script>
</script>
<script language="javascript">
function  valor_prueba(form) {

    if (factura.impuesto[0].checked==true) {
          var impuesto = (factura.impuesto[0].value)/100;
       //   alert("Impuesto 0: "+impuesto);
       }

    if (factura.impuesto[1].checked==true) {
         var impuesto = (factura.impuesto[1].value)/100;
    //     alert("Impuesto 1: "+impuesto);
        }
    //   alert("Impuesto : "+impuesto);

   if (factura.impuesto1[0].checked==true) {
         var impuesto1 = (factura.impuesto1[0].value)/100;
        }

   if (factura.impuesto1[1].checked==true) {
         var impuesto1 = (factura.impuesto1[1].value)/100;
        }
        //alert("Impuesto1 : "+impuesto1);

    if (factura.impuesto2[0].checked==true) {
         var impuesto2 = (factura.impuesto2[0].value)/100;
         }

    if (factura.impuesto2[1].checked==true) {
         var impuesto2 = (factura.impuesto2[1].value)/100;
       }
      //   alert("Impuesto2 : "+impuesto2);  

if (factura.impuesto3[0].checked==true) {
var impuesto3 = (factura.impuesto3[0].value)/100;
}

if (factura.impuesto3[1].checked==true) {
var impuesto3 = (factura.impuesto3[1].value)/100;
} 
//alert("Impuesto3 : "+impuesto3); 
//form.casilla1[i].checked = false;
var total = factura.importe.value;
var total_articulo = impuesto+('+')+total;
//alert(total_articulo);

} 

</script>
<script language="javascript">
function validarFormulario(form)
{
		
	var monto  = factura.importe.value; // Monto  primer lote
	var comi   = factura.comision.value; // Comision  primer lote
	var imp105 = (factura.impuesto[0].value)/100; // Impuesto 10,5 %
	var imp21  = (factura.impuesto[1].value)/100; // impuesto 21 %
	//alert(monto);
	//alert(comi);
	//alert(imp105);
	//alert(imp21);
	var serie = factura.serie.value;
	
	//alert("Serie"+serie);
	var monto1 = factura.importe1.value; // Monto segundo lote
	var comi1  = factura.comision1.value;// Comision  segundo lote
	var imp105_1 = (factura.impuesto1[0].value)/100; // Impuesto 10,5 %
	var imp21_1  = (factura.impuesto1[1].value)/100; // impuesto 21 %
	
	var monto2 = factura.importe2.value; // Monto tercer lote
	var comi2  = factura.comision2.value; // Comision  tercer lote
	var imp105_2 = (factura.impuesto2[0].value)/100; // Impuesto 10,5 %
	var imp21_2  = (factura.impuesto2[1].value)/100; // impuesto 21 %
	
	var monto3 = factura.importe3.value; // Monto cuarto lote
	var comi3  = factura.comision3.value; // Comision  cuarto lote
	var imp105_3 = (factura.impuesto3[0].value)/100; // Impuesto 10,5 %
	var imp21_3  = (factura.impuesto3[1].value)/100; // impuesto 21 %
	
	var monto4 = factura.importe4.value; // Monto quinto lote
	var comi4  = factura.comision4.value; // Comision  cuarto lote
	var imp105_4 = (factura.impuesto4[0].value)/100; // Impuesto 10,5 %
	var imp21_4  = (factura.impuesto4[1].value)/100; // impuesto 21 %
	
	//var monto4 = factura.importe4.value;  // Monto Quinto lote
	//var comi4  = factura.comision4.value; // Comision  Quinto lote
	//var imp105_4 = (factura.impuesto4[0].value)/100; // Impuesto 10,5 %
	//var imp21_4  = (factura.impuesto4[1].value)/100; // impuesto 21 %
	
	var monto5 = factura.importe5.value; // Monto Sexto lote
	var comi5   = factura.comision5.value;  // Comision  Sexto lote
	var imp105_5 = (factura.impuesto5[0].value)/100; // Impuesto 10,5 %
	var imp21_5  = (factura.impuesto5[1].value)/100; // impuesto 21 %
	
	var monto6  = factura.importe6.value; // Monto Septimo lote
	var comi6   = factura.comision6.value; // Comision  Septimo lote
	var imp105_6 = (factura.impuesto6[0].value)/100; // Impuesto 10,5 %
	var imp21_6  = (factura.impuesto6[1].value)/100; // impuesto 21 %
	
	var monto7  = factura.importe7.value; // Monto Octavo lote
	var comi7   = factura.comision7.value; // Comision  Octavo lote
	var imp105_7 = (factura.impuesto7[0].value)/100; // Impuesto 10,5 %
	var imp21_7  = (factura.impuesto7[1].value)/100; // impuesto 21 %
	
	var monto8  = factura.importe8.value; // Monto Noveno lote
	var comi8   = factura.comision8.value; // Comision  Noveno lote
	var imp105_8 = (factura.impuesto8[0].value)/100; // Impuesto 10,5 %
	var imp21_8  = (factura.impuesto8[1].value)/100; // impuesto 21 %
	
	var monto9  = factura.importe9.value; // Monto D�cimo lote
	var comi9   = factura.comision9.value; // Comision  D�cimo lote
	var imp105_9 = (factura.impuesto9[0].value)/100; // Impuesto 10,5 %
	var imp21_9  = (factura.impuesto9[1].value)/100; // impuesto 21 %
	
	var monto10 = factura.importe10.value;  // Monto Onceavo lote
	var comi10  = factura.comision10.value; // Comision  Onceavo lote
	var imp105_10 = (factura.impuesto10[0].value)/100; // Impuesto 10,5 %
	var imp21_10  = (factura.impuesto10[1].value)/100; // impuesto 21 %
		
	var monto11 = factura.importe11.value; // Monto Doceavo lote
	var comi11  = factura.comision11.value; // Comision  Doceavo lote
	var imp105_11 = (factura.impuesto11[0].value)/100; // Impuesto 10,5 %
	var imp21_11  = (factura.impuesto11[1].value)/100; // impuesto 21 %
	
	var monto12 = factura.importe12.value; // Monto Treceavo lote
	var comi12  = factura.comision12.value; // Comision  Treceavo lote
	var imp105_12 = (factura.impuesto12[0].value)/100; // Impuesto 10,5 %
	var imp21_12  = (factura.impuesto12[1].value)/100; // impuesto 21 %
	
	var monto13 = factura.importe13.value; // Monto Catorceavo lote
	var comi13  = factura.comision13.value; // Comision  Catorceavo lote
	var imp105_13 = (factura.impuesto13[0].value)/100; // Impuesto 10,5 %
	var imp21_13  = (factura.impuesto13[1].value)/100; // impuesto 21 %
	
	var monto14 = factura.importe14.value; // Monto Quinceavo lote
	var comi14  = factura.comision14.value; // Comision  Quinceavo lote
	var imp105_14 = (factura.impuesto14[0].value)/100; // Impuesto 10,5 %
	var imp21_14  = (factura.impuesto14[1].value)/100; // impuesto 21 %
	
    var  tot_mon = 0 ;
    var tot_comi = 0 ;
	var neto105 = 0;
	var neto21 = 0 ;
	var imp_tot105 = 0 ;
	var imp_tot21 = 0 ;
	var tot_mon105 = 0 ;
	var tot_mon21  = 0;
	var totresol  = 0;

         if (factura.impuesto[0].checked==true) {
             if (monto.length!=0 ) {
			 	 tot_mon = eval(monto);
	             tot_mon = tot_mon.toFixed(2);
				 tot_comi = (comi*monto)/100;
				 tot_mon105 = eval(monto);
				 tot_mon105 = tot_mon105.toFixed(2);
	           //  alert(tot_mon105);
				 //imp_tot105 = eval(monto+('+')+((comi*monto)/100))*imp105 ;
				 //alert("Impuesto 10,5% "+imp_tot105);
				 imp_tot105 = eval(monto*imp105) ;
				 //alert("Impuesto 10,5% "+imp_tot105);
				 imp_tot21 = eval(((comi*monto)/100)*imp21);
	       	 }  
	       }
	
	      if (factura.impuesto[1].checked==true) {
	         if (monto.length!=0) {
	             tot_mon = eval(monto);
	             tot_mon = tot_mon.toFixed(2);
				 tot_comi = (comi*monto)/100;
				 tot_mon21 = eval(monto); 
				 // eval(monto+('+')+((comi*monto)/100));
	             tot_mon21 = tot_mon21.toFixed(2);
	             imp_tot21 = eval(monto+('+')+((comi*monto)/100))*imp21;
			//	alert("Impuesto 21% "+imp_tot21);
				 
	       	  }  
	       }
		
		   // SEGUNDO LOTE TODO OK
		 if (factura.impuesto1[0].checked==true) { 
			 if (monto1.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto1);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi1 = (comi1*monto1)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto1); 
					   //tot_comi1+('+')+monto1);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi1);
					   //imp_tot105_1 = eval(monto1+('+')+tot_comi1)*imp105_1 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_1);
					   imp_tot105_1 = eval(monto1)*imp105_1 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_1);
					   imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_1);
					   
	              } 
	         }
			 
		 if (factura.impuesto1[1].checked==true) {	 
			if (monto1.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto1);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi1 = (comi1*monto1)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto1); 
					   // tot_comi1+('+')+monto1);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi1);
				       imp_tot21_1 = eval(monto1+('+')+tot_comi1)*imp21_1 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_1);
	             } 
			}	 
			
			// TERCER LOTE TODO OK
			if (factura.impuesto2[0].checked==true) { 
				if (monto2.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto2);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi2 = (comi2*monto2)/100;
					   tot_mon105 =eval(tot_mon105+('+')+monto2); 
					   // tot_comi2+('+')+monto2);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi2);
					   //imp_tot105_2 =  eval(monto2+('+')+tot_comi2)*imp105_2 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_2);
					   imp_tot105_2 =  eval(monto2)*imp105_2 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_2);
					   imp_tot21_2 = eval(tot_comi2)*imp21_2 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_2);
	              } 
	         }
			 
		 	if (factura.impuesto2[1].checked==true) {	 
				if (monto2.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto2);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi2 = (comi2*monto2)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto2); 
					   // tot_comi2+('+')+monto2);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi2);
				       imp_tot21_2 = eval(monto2+('+')+tot_comi2)*imp21_2 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_2);
	             } 
			}	
			
			
			// CUARTO LOTE TODO OK
			if (factura.impuesto3[0].checked==true) { 
				if (monto3.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto3);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi3 = (comi3*monto3)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto3);
					    // tot_comi3+('+')+monto3);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi3);
					   //imp_tot105_3 = eval(monto3+('+')+tot_comi3)*imp105_3 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_3);
					   imp_tot105_3 = eval(monto3)*imp105_3 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_3);
					   imp_tot21_3 = eval(tot_comi3)*imp21_3;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_3);
	              } 
	         }
			 
		 	if (factura.impuesto3[1].checked==true) {	 
				if (monto3.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto3);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi3 = (comi3*monto3)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto3); 
					   // tot_comi3+('+')+monto3);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi3);
				       imp_tot21_3 = eval(monto3+('+')+tot_comi3)*imp21_3 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_3);
	             } 
			}	
				 
	        // QUINTO LOTE TODO OK
			if (factura.impuesto4[0].checked==true) { 
				if (monto4.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto4);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi4 = (comi4*monto4)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto4); 
					   // tot_comi4+('+')+monto4);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi4);
					   //imp_tot105_4 = eval(monto4+('+')+tot_comi4)*imp105_4 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_4);
					   imp_tot105_4 = eval(monto4)*imp105_4 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_4);
					   imp_tot21_4 = eval(tot_comi4)*imp21_4 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_4);
	              } 
	         }
			 
		 	if (factura.impuesto4[1].checked==true) {	 
				if (monto4.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto4);
	                   tot_mon = tot_mon.toFixed(2);
				       tot_comi4 = (comi4*monto4)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto4); 
					   // tot_comi4+('+')+monto4);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi4);
				       imp_tot21_4 = eval(monto4+('+')+tot_comi4)*imp21_4 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_4);
	             } 
			}	
			
			
            // SEXTO LOTE TODO OK
			if (factura.impuesto5[0].checked==true) { 
				if (monto5.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto5);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi5 = (comi5*monto5)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto5); 
					   // tot_comi5+('+')+monto5);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi5);
					   //imp_tot105_5 = eval(monto5+('+')+tot_comi5)*imp105_5 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_5);
					   imp_tot105_5 = eval(monto5)*imp105_5 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_5);
					   imp_tot21_5 = eval(tot_comi5)*imp21_5 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_5);
	              } 
	         }
			 
		 	if (factura.impuesto5[1].checked==true) {	 
				if (monto5.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto5);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi5 = (comi5*monto5)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto5);
					    //tot_comi5+('+')+monto5);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi5);
				       imp_tot21_5 = eval(monto5+('+')+tot_comi5)*imp21_5 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_5);
	             } 
			}	
			
			
	        // SEPTIMO LOTE TODO OK
			if (factura.impuesto6[0].checked==true) { 
				if (monto6.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto6);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi6 = (comi6*monto6)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto6);
					    //tot_comi6+('+')+monto6);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi6);
					   //imp_tot105_6 = eval(monto6+('+')+tot_comi6)*imp105_6 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_6);
					   imp_tot105_6 = eval(monto6)*imp105_6 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_6);
					   imp_tot21_6 = eval(tot_comi6)*imp21_6 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_6);
	              } 
	         }
			 
		 	if (factura.impuesto6[1].checked==true) {	 
				if (monto6.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto6);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi6 = (comi6*monto6)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto6);
					    //tot_comi6+('+')+monto6);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi6);
				       imp_tot21_6 = eval(monto6+('+')+tot_comi6)*imp21_6 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_6);
	             } 
			}	
 
            // OCTAVO LOTE TODO OK
			if (factura.impuesto7[0].checked==true) { 
				if (monto7.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto7);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi7 = (comi7*monto7)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto7); 
					   // tot_comi7+('+')+monto7);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi7);
					   //imp_tot105_7 = eval(monto7+('+')+tot_comi7)*imp105_7 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_7);
					   imp_tot105_7 = eval(monto7)*imp105_7 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_7);
					   imp_tot21_7 = eval(tot_comi7)*imp21_7 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_7);
	              } 
	         }
			 
		 	if (factura.impuesto7[1].checked==true) {	 
				if (monto7.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto7);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi7 = (comi7*monto7)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto7);
					   //tot_comi7+('+')+monto7);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi7);
				       imp_tot21_7 = eval(monto7+('+')+tot_comi7)*imp21_7 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_7);
	             } 
			}	
			
			// NOVENO LOTE TODO OK
			if (factura.impuesto8[0].checked==true) { 
				if (monto8.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto8);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi8 = (comi8*monto8)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto8);
					    //tot_comi8+('+')+monto8);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi8);
					   //imp_tot105_8 = eval(monto8+('+')+tot_comi8)*imp105_8 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_8);
					   imp_tot105_8 = eval(monto8)*imp105_8 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_8);
					   imp_tot21_8 = eval(tot_comi8)*imp21_8 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_8);
	              } 
	         }
			 
		 	if (factura.impuesto8[1].checked==true) {	 
				if (monto8.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto8);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi8 = (comi8*monto8)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto8);
					   // tot_comi8+('+')+monto8);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi8);
				       imp_tot21_8 = eval(monto8+('+')+tot_comi8)*imp21_8 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_8);
	             } 
			}	
				  
			// DECIMO LOTE TODO OK
			if (factura.impuesto9[0].checked==true) { 
				if (monto9.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto9);
	                   tot_mon = tot_mon.toFixed(2);
				       tot_comi9 = (comi9*monto9)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto9); 
					   //tot_comi9+('+')+monto9);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi9);
					   //imp_tot105_9 = eval(monto9+('+')+tot_comi9)*imp105_9 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_9);
					   imp_tot105_9 = eval(monto9)*imp105_9 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_9);
					   imp_tot21_9 = eval(tot_comi9)*imp21_9 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_9);
	              } 
	         }
			 
		 	if (factura.impuesto9[1].checked==true) {	 
				if (monto9.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto9);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi9 = (comi9*monto9)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto9);
					   //tot_comi9+('+')+monto9);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi9);
				       imp_tot21_9 = eval(monto9+('+')+tot_comi9)*imp21_9 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_9);
	             } 
			}	
					
			// DECIMO PRIMER LOTE TODO OK
			if (factura.impuesto10[0].checked==true) { 
				if (monto10.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto10);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi10 = (comi10*monto10)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto10);
					    //tot_comi10+('+')+monto10);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi10);
					   //imp_tot105_10 = eval(monto10+('+')+tot_comi10)*imp105_10 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_10);
					   imp_tot105_10 = eval(monto10)*imp105_10 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_10);
					   imp_tot21_10 = eval(tot_comi10)*imp21_10 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_10);
	              } 
	         }
			 
		 	if (factura.impuesto10[1].checked==true) {	 
				if (monto10.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto10);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi10 = (comi10*monto10)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto10);
					   //tot_comi10+('+')+monto10);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi10);
				       imp_tot21_10 = eval(monto10+('+')+tot_comi10)*imp21_10 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_10);
	             } 
			}	
	        // DECIMO SEGUNDO LOTE TODO OK
			if (factura.impuesto11[0].checked==true) { 
				if (monto11.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto11);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi11 = (comi11*monto11)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto11);
					   //tot_comi11+('+')+monto11);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi11);
					   //imp_tot105_11 = eval(monto11+('+')+tot_comi11)*imp105_11 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_11);
					   imp_tot105_11 = eval(monto11)*imp105_11 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_11);
					   imp_tot21_11 = eval(tot_comi11)*imp21_11 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_11);
	              } 
	         }
			 
		 	if (factura.impuesto11[1].checked==true) {	 
				if (monto11.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto11);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi11 = (comi11*monto11)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto11); 
					   //tot_comi11+('+')+monto11);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi11);
				       imp_tot21_11 = eval(monto11+('+')+tot_comi11)*imp21_11 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_11);
	             } 
			}	
	       
		    // DECIMO TERCER LOTE TODO OK
			if (factura.impuesto12[0].checked==true) { 
				if (monto12.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto12);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi12 = (comi12*monto12)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto12);
					   //tot_comi12+('+')+monto12);
	                   tot_comi12 = (comi12*monto12)/100;
	                   tot_comi = eval(tot_comi+('+')+tot_comi12);
					   //imp_tot105_12 = eval(monto12+('+')+tot_comi12)*imp105_12 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_12);
					   imp_tot105_12 = eval(monto12)*imp105_12 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_12);
					   imp_tot21_12 = eval(tot_comi12)*imp21_12 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_12);
	              } 
	         }
			 
		 	if (factura.impuesto12[1].checked==true) {	 
				if (monto12.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto12);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi12 = (comi12*monto12)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto12);
					   //tot_comi12+('+')+monto12);
	                   tot_comi12 = (comi12*monto12)/100;
	                   tot_comi = eval(tot_comi+('+')+tot_comi12);
				       imp_tot21_12 = eval(monto12+('+')+tot_comi12)*imp21_12 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_12);
	             } 
			}
				
	        // DECIMO CUARTO LOTE TODO OK
			if (factura.impuesto13[0].checked==true) { 
				if (monto13.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto13);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi13 = (comi13*monto13)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto13);
					   //tot_comi13+('+')+monto13);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi13);
					   //imp_tot105_13 = eval(monto13+('+')+tot_comi13)*imp105_13 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_13);
					   imp_tot105_13 = eval(monto13)*imp105_13 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_13);
					   imp_tot21_13 = eval(tot_comi13)*imp21_13 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_13);
	              } 
	         }
			 
		 	if (factura.impuesto13[1].checked==true) {	 
				if (monto13.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto13);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi13 = (comi13*monto13)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto13); 
					   //tot_comi13+('+')+monto13);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi13);
				       imp_tot21_13 = eval(monto13+('+')+tot_comi13)*imp21_13 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_13);
	             } 
			}	
	        // DECIMO QUINTO LOTE TODO OK
			if (factura.impuesto14[0].checked==true) { 
				if (monto14.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto14);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi14 = (comi14*monto14)/100;
					   tot_mon105 = eval(tot_mon105+('+')+monto14);
					    //tot_comi14+('+')+monto14);
	                   tot_mon105 = tot_mon105.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi14);
					   //imp_tot105_14 = eval(monto14+('+')+tot_comi14)*imp105_14 ;
			 		   //imp_tot105 = eval(imp_tot105+('+')+imp_tot105_14);
					   imp_tot105_14 = eval(monto14)*imp105_14 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_14);
					   imp_tot21_14 = eval(tot_comi14)*imp21_14 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_14);
	              } 
	         }
			 
		 	if (factura.impuesto14[1].checked==true) {	 
				if (monto14.length!=0) {
	                   tot_mon = eval(tot_mon+('+')+monto14);
	                   tot_mon = tot_mon.toFixed(2);
					   tot_comi14 = (comi14*monto14)/100;
					   tot_mon21 = eval(tot_mon21+('+')+monto14); 
					   //tot_comi14+('+')+monto14);
	                   tot_mon21 = tot_mon21.toFixed(2);
	                   tot_comi = eval(tot_comi+('+')+tot_comi14);
				       imp_tot21_14 = eval(monto14+('+')+tot_comi14)*imp21_14 ;
					   imp_tot21 = eval(imp_tot21+('+')+imp_tot21_14);
	             } 
			}	
			
		  	//alert ("Total vendido"+tot_mon);
			tot_comi=tot_comi.toFixed(2);
			//alert ("Comision "+tot_comi);
			
			tot_neto = eval(tot_mon+('+')+tot_comi+('+')+imp_tot21+('+')+imp_tot105);
			tot_neto = tot_neto.toFixed(2);
		    var	imp_tot105 = imp_tot105.toFixed(2);
			var imp_tot21 = imp_tot21.toFixed(2);

			var serie_num = eval(factura.serie.value);
			var largo = factura.serie.value.length ;

			if (serie==12) {
			   // Oculto
			factura.totiva105.value = imp_tot105;
			factura.totiva21.value = imp_tot21;
			factura.totcomis.value = tot_comi ;
			factura.tot_general.value = tot_neto;
		    factura.totneto105.value = tot_mon105;
			factura.totneto21.value = tot_mon21;
			 // Visible
		//	 alert(factura.totneto21.value );
			factura.totneto105_1.value = eval(tot_mon105+('+')+imp_tot105);
			tot_mon21 = tot_mon21*1.21
			totcomis = tot_comi*1.21
			factura.totneto21_1.value = tot_mon21.toFixed(2);
			factura.totiva21_1.value = '  --------   ';
			factura.totiva105_1.value = '  --------   ';
			factura.totcomis_1.value = totcomis.toFixed(2);
			factura.tot_general_1.value = tot_neto;
		//   alert(factura.tot_general_1.value);
			   
			   } else {
			  // Oculto   
			factura.totiva105.value = imp_tot105;
			factura.totiva21.value = imp_tot21;
			factura.totcomis.value = tot_comi ;
			factura.tot_general.value = tot_neto;
		    factura.totneto105.value = tot_mon105;
			factura.totneto21.value = tot_mon21;
			/// Visible
			factura.totiva105_1.value = imp_tot105;
			factura.totiva21_1.value = imp_tot21;
			factura.totcomis_1.value = tot_comi ;
			factura.tot_general_1.value = tot_neto;
		    factura.totneto105_1.value = tot_mon105;
			factura.totneto21_1.value = tot_mon21;
			//    alert(factura.tot_general.value);
			   }
			  
			 if  (factura.GrupoOpciones1[0].checked == true && factura.pago_contado.checked == true )
			 {
			 
			 factura.leyenda.value = "Se abona con efectivo la cantidad de $ "+tot_neto;
			 
			 
			 }
	
 
	}
</script>

<script language="javascript">

function resol(form)
{
			var imp_tot105 = factura.totneto105.value;
			var imp_tot21  = factura.totneto21.value;
			porc105 = 0.015;
			//alert 
			porc21  = 0.03;
			if (factura.tieneresol.checked==true) {
			  // alert(imp_tot105)
				tot_resol = eval((imp_tot105+('*')+porc105)+('+')+(imp_tot21+('*')+porc21));
				tot_resol=tot_resol.toFixed(2);
				factura.totimp.value = tot_resol;
				factura.tot_general.value = eval(tot_neto+('+')+tot_resol);
			}
			else {
				factura.totimp.value = 0;
				factura.tot_general.value = tot_neto;
			}
			//alert("Resol :"+tot_resol+"<br>");
}


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
	var ajax1 = new sack();
	var currentLoteID=false;
	// Segundo Lote
	var ajax2 = new sack();
	var currentLoteID1=false;
	// Tercer Lote
	var ajax3 = new sack();
	var currentLoteID2=false;
	// Cuarto Lote
	var ajax4 = new sack();
	var currentLoteID3=false;
	// Quinto Lote
	var ajax5 = new sack();
	var currentLoteID4=false;
	// Sexto Lote
	var ajax6 = new sack();
	var currentLoteID5=false;
	// Septimo Lote
	var ajax7 = new sack();
	var currentLoteID6=false;
	// Octavo Lote
	var ajax8 = new sack();
	var currentLoteID7=false;
	// Novenmo Lote
	var ajax9 = new sack();
	var currentLoteID8=false;
	// Decimo Lote
	var ajax10 = new sack();
	var currentLoteID9=false;
	// Lote Once
	var ajax11 = new sack();
	var currentLoteID10=false;
	// Lote Doce
	var ajax12 = new sack();
	var currentLoteID11=false;
	// Lote Trece
	var ajax13 = new sack();
	var currentLoteID12=false;
	// Lote Catorce
	var ajax14 = new sack();
	var currentLoteID13=false;
	// Lote Quince
	var ajax15 = new sack();
	var currentLoteID14=false;
	
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
	
	function getLoteData() // Primer lote
	{
		var loteId = document.getElementById('lote').value ;
		var RemateId = document.getElementById('remate_num').value;
	
		if( loteId!=currentLoteID){
			currentLoteID = loteId;
			ajax1.requestFile = 'getlote.php?getloteId='+loteId+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax1.onCompletion = showLoteData;	// Specify function that will be executed after file has been found
			ajax1.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData1()  /// Segundo Lote
	{
		var loteId1 = document.getElementById('lote1').value ;
		var RemateId = document.getElementById('remate_num').value;
				
		if( loteId1!=currentLoteID1){
	   		currentLoteID1 = loteId1;
			ajax2.requestFile = 'getlote1.php?getloteId1='+loteId1+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax2.onCompletion = showLoteData1;	// Specify function that will be executed after file has been found
			ajax2.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData2()  /// Tercer lote
	{
		var loteId2 = document.getElementById('lote2').value ;
		var RemateId = document.getElementById('remate_num').value;
		
		if( loteId2!=currentLoteID2){
			currentLoteID2 = loteId2;
			ajax3.requestFile = 'getlote2.php?getloteId2='+loteId2+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax3.onCompletion = showLoteData2;	// Specify function that will be executed after file has been found
			ajax3.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData3()  /// Cuarto lote
	{
		var loteId3 = document.getElementById('lote3').value ;
		var RemateId = document.getElementById('remate_num').value;
		
		if( loteId3!=currentLoteID3){
			currentLoteID3 = loteId3;
			ajax4.requestFile = 'getlote3.php?getloteId3='+loteId3+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax4.onCompletion = showLoteData3;	// Specify function that will be executed after file has been found
			ajax4.runAJAX();		// Execute AJAX function			
		}
	}

	function getLoteData4()  /// Quinto lote
	{
		var loteId4 = document.getElementById('lote4').value ;
		var RemateId = document.getElementById('remate_num').value;
		
		if( loteId4!=currentLoteID4){
			currentLoteID4 = loteId4;
			ajax5.requestFile = 'getlote4.php?getloteId4='+loteId4+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax5.onCompletion = showLoteData4;	// Specify function that will be executed after file has been found
			ajax5.runAJAX();		// Execute AJAX function			
		}	
	}	

	function getLoteData5()  /// Sexto lote
	{
		var loteId5 = document.getElementById('lote5').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId5!=currentLoteID5){
			currentLoteID5= loteId5;
			ajax6.requestFile = 'getlote5.php?getloteId5='+loteId5+'&getremate_num='+RemateId;	// Specifying which file to get
	    	ajax6.onCompletion = showLoteData5;	// Specify function that will be executed after file has been found
			ajax6.runAJAX();		// Execute AJAX function			
		}	
	}
		
	function getLoteData6()  /// Septimo lote
	{
		var loteId6 = document.getElementById('lote6').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId6!=currentLoteID6){
			currentLoteID6= loteId6;
			ajax7.requestFile = 'getlote6.php?getloteId6='+loteId6+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax7.onCompletion = showLoteData6;	// Specify function that will be executed after file has been found
			ajax7.runAJAX();		// Execute AJAX function			
		}	
	}	

	function getLoteData7()  /// Octavo lote
	{
		var loteId7 = document.getElementById('lote7').value ;
		var RemateId = document.getElementById('remate_num').value;
 		if( loteId7!=currentLoteID7){
 			currentLoteID7= loteId7;
 			ajax8.requestFile = 'getlote7.php?getloteId7='+loteId7+'&getremate_num='+RemateId;	// Specifying which file to get
 			ajax8.onCompletion = showLoteData7;	// Specify function that will be executed after file has been found
 			ajax8.runAJAX();		// Execute AJAX function			
 		}	
 	}
 
 	function getLoteData8()  /// Noveno lote
	{
		var loteId8 = document.getElementById('lote8').value ;
		var RemateId = document.getElementById('remate_num').value;
 		if( loteId8!=currentLoteID8){
 			currentLoteID8= loteId8;
 			ajax9.requestFile = 'getlote8.php?getloteId8='+loteId8+'&getremate_num='+RemateId;	// Specifying which file to get
 			ajax9.onCompletion = showLoteData8;	// Specify function that will be executed after file has been found
 			ajax9.runAJAX();		// Execute AJAX function			
 		}	
 	}	
  
  	function getLoteData9()  /// Decimo lote
	{
		var loteId9 = document.getElementById('lote9').value ;
		var RemateId = document.getElementById('remate_num').value;
 		if( loteId9!=currentLoteID9){
 			currentLoteID9= loteId9;
 			ajax10.requestFile = 'getlote9.php?getloteId9='+loteId9+'&getremate_num='+RemateId;	// Specifying which file to get
 			ajax10.onCompletion = showLoteData9;	// Specify function that will be executed after file has been found
 			ajax10.runAJAX();		// Execute AJAX function			
 		}	
 	}
 
	function getLoteData10()  ///  Lote Once
	{
		var loteId10 = document.getElementById('lote10').value ;
		var RemateId = document.getElementById('remate_num').value;
 		if( loteId10!=currentLoteID10){
 			currentLoteID10= loteId10;
 			ajax11.requestFile = 'getlote10.php?getloteId10='+loteId10+'&getremate_num='+RemateId;	// Specifying which file to get
 			ajax11.onCompletion = showLoteData10;	// Specify function that will be executed after file has been found
 			ajax11.runAJAX();		// Execute AJAX function			
 		}	
 	}	
 
	function getLoteData11()  /// Lote Doce
	{
		var loteId11 = document.getElementById('lote11').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId11!=currentLoteID11){
			currentLoteID11= loteId11;
  			ajax12.requestFile = 'getlote11.php?getloteId11='+loteId11+'&getremate_num='+RemateId;	// Specifying which file to get
  			ajax12.onCompletion = showLoteData11;	// Specify function that will be executed after file has been found
  			ajax12.runAJAX();		// Execute AJAX function			
		}	
	}	

	function getLoteData12()  /// Lote trece
	{
		var loteId12 = document.getElementById('lote12').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId12!=currentLoteID12){
			currentLoteID12= loteId12;
  			ajax13.requestFile = 'getlote12.php?getloteId12='+loteId12+'&getremate_num='+RemateId;	// Specifying which file to get
  			ajax13.onCompletion = showLoteData12;	// Specify function that will be executed after file has been found
 			ajax13.runAJAX();		// Execute AJAX function			
		}	
	}	
		
	function getLoteData13()  /// Lote catorce
	{
		var loteId13 = document.getElementById('lote13').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId13!=currentLoteID13){
			currentLoteID13= loteId13;
  			ajax14.requestFile = 'getlote13.php?getloteId13='+loteId13+'&getremate_num='+RemateId;	// Specifying which file to get
  			ajax14.onCompletion = showLoteData13;	// Specify function that will be executed after file has been found
 			ajax14.runAJAX();		// Execute AJAX function			
		}	
	}	

	function getLoteData14()  /// Lote Quince
	{
		var loteId14 = document.getElementById('lote14').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId14!=currentLoteID14){
			currentLoteID14= loteId14;
  			ajax15.requestFile = 'getlote14.php?getloteId14='+loteId14+'&getremate_num='+RemateId;	// Specifying which file to get
  			ajax15.onCompletion = showLoteData14;	// Specify function that will be executed after file has been found
  			ajax15.runAJAX();		// Execute AJAX function			
		}	
	}			
		
	function showClientData()
	{
		var formObj = document.forms['factura'];	
		eval(ajax.response);
	}
	
	function showLoteData() // Primer lote
	{
		var formObj1 = document.forms['factura'];	
		eval(ajax1.response);
	}
	
	function showLoteData1() // Segundo  Lote
	{
		var formObj2 = document.forms['factura'];	
		eval(ajax2.response);
	}
	
	function showLoteData2() // Tercer lote
	{
		var formObj3 = document.forms['factura'];	
		eval(ajax3.response);
	}
	
	function showLoteData3() // Cuarto lote
	{
		var formObj4 = document.forms['factura'];	
		eval(ajax4.response);
	}
	
	function showLoteData4() // Quinto lote
	{
		var formObj5 = document.forms['factura'];	
		eval(ajax5.response);
	}
	
	function showLoteData5() // Sexto lote
	{
    	var formObj6 = document.forms['factura'];	
		eval(ajax6.response);
	}

	function showLoteData6() // Septimo lote
	{
    	var formObj7 = document.forms['factura'];	
		eval(ajax7.response);
	}

	function showLoteData7() // Octavo lote
	{
		var formObj8 = document.forms['factura'];	
	 	eval(ajax8.response);
	}

	function showLoteData8() // Noveno lote
	{
		var formObj9 = document.forms['factura'];	
	 	eval(ajax9.response);
	}

	function showLoteData9() // Decimo lote
	{
		var formObj10 = document.forms['factura'];	
	 	eval(ajax10.response);
	}
		
	function showLoteData10() // Once lote
	{
	 	var formObj11 = document.forms['factura'];	
	 	eval(ajax11.response);
	}
		
	function showLoteData11() // Docelote
	{
		var formObj12 = document.forms['factura'];	
	 	eval(ajax12.response);
	}	

	function showLoteData12() // Trece lote
	{
		var formObj13 = document.forms['factura'];	
	 	eval(ajax13.response);
	}	
			
	function showLoteData13() // Catorce lote
	{
		var formObj14 = document.forms['factura'];	
	 	eval(ajax14.response);
	}

	function showLoteData14() // Quince lote
	{
		var formObj15 = document.forms['factura'];	
	 	eval(ajax15.response);
	}
					
	function initFormEvents()
	{
	    var fecha1 = new Date();
		var dia = fecha1.getDate();
		var mes = (fecha1.getMonth()+1);
		var ano = fecha1.getYear();
		var fecha = ano+'-'+mes+'-'+dia ;
		var fecha11 = dia+'-'+mes+'-'+ano ;
		document.getElementById('fecha_factura').value = fecha ;
		document.getElementById('remate_num').onblur = getClientData;
		document.getElementById('remate_num').focus();
		document.getElementById('lote').onblur = getLoteData;
		document.getElementById('lote').focus();
		document.getElementById('lote1').onblur = getLoteData1;
		document.getElementById('lote1').focus();
		document.getElementById('lote2').onblur = getLoteData2;
		document.getElementById('lote2').focus();
		document.getElementById('lote3').onblur = getLoteData3;
		document.getElementById('lote3').focus();
		document.getElementById('lote4').onblur = getLoteData4;
		document.getElementById('lote4').focus();
		document.getElementById('lote5').onblur = getLoteData5;
		document.getElementById('lote5').focus();
		document.getElementById('lote6').onblur = getLoteData6;
		document.getElementById('lote6').focus();
		document.getElementById('lote7').onblur = getLoteData7;
		document.getElementById('lote7').focus();
		document.getElementById('lote8').onblur = getLoteData8;
		document.getElementById('lote8').focus();
		document.getElementById('lote9').onblur = getLoteData9;
		document.getElementById('lote9').focus();
		document.getElementById('lote10').onblur = getLoteData10;
		document.getElementById('lote10').focus();
	    document.getElementById('lote11').onblur = getLoteData11;
		document.getElementById('lote11').focus();
		document.getElementById('lote12').onblur = getLoteData12;
		document.getElementById('lote12').focus();
		document.getElementById('lote13').onblur = getLoteData13;
	    document.getElementById('lote13').focus();
		document.getElementById('lote14').onblur = getLoteData14;
		document.getElementById('lote14').focus();
	}
		
	window.onload = initFormEvents;
	</script>
<script language="javascript">
function pendiente(form)
{
	if (factura.GrupoOpciones1[0].checked ==false)
    {
		//factura.pago_contado
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

<form id="factura" name="factura" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="640" border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
    <tr>
      <td colspan="3" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_man_lotes.gif" width="358" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="3" valign="top" bgcolor="#003366"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="22%" height="20" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Tipo comprobante </span></td>
          <td width="24%"><select name="tipos" onChange="agregarOpciones(this.form)">
                                      <option value="">[seleccione una opci&oacute;n]</option>
                                      <option value="6">FACT MANUAL A</option>
                                      <option value="24">FACT MANUAL B</option>
                           </select>
		  <input name="tcomp" id="tcomp"  type="hidden" />
		         </td>
          <td width="7%">&nbsp;</td>
          <td width="13%" class="ewTableHeader">&nbsp;Serie</td>
          <td width="34%"><input name="serie_texto" type="text"  size="25" />
		  <input name="serie" type="hidden"  size="25"/>
            </td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num Factura </td>
          <td><input name="num_factura" type="num_factura" class="phpmakerlist" id="ncomp"  width="25" /></td>
          <td>&nbsp;</td>
          <td class="ewTableHeader">&nbsp;Fecha fact </td>
		  <input name="fecha_factura" type="hidden" id="fecha_factura"  />
          <td><input name="fecha_factura1" type="text" id="fecha_factura1"   onblur="cambia_fecha(this.form)" />
         <a href="javascript:showCal('Calendar4')"><img src="calendar/img.gif" width="22" height="14"  border="0"/></a></td>
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
          <td>&nbsp;</td>
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
      <td colspan="3"  background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
	  
	   
	  <table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">
        <tr>
          <td width="39" rowspan="2" class="ewTableHeader">
              <div align="center">Lote</div></td>
          <td width="369" rowspan="2" class="ewTableHeader">
              <div align="center">Descripci&oacute;n</div></td>
          <td width="44" rowspan="2" class="ewTableHeader"><div align="center">Com</div></td>
          <td width="60" rowspan="2" class="ewTableHeader">
              <div align="center">Importe</div></td>
		 <td height="24" colspan="3" class="ewTableHeader"><div align="center">Impuestos</div></td>	  
        </tr>
        <tr>
          <td height="15" class="ewTableHeader"> <div align="center"><?php echo $iva_15_porcen     ?></div></td>
          <td class="ewTableHeader"><div align="center"><?php echo $iva_21_porcen     ?></div></td>
        </tr>
        
		<tr>
          <td bgcolor="#0094FF"><input name="lote" type="text" id="lote" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="65" />		  </td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision" type="text" id="comision" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision" type="text" id="comision" size="3"  readonly="" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" onBlur="MM_validateForm('importe','','NisNum');return document.MM_returnValue" size="10"   /></td>
          <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)"/></td>
		<input name="secuencia0" type="hidden" class="phpmaker" id="secuencia0" size="65" />
          
		 </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote1" type="text" id="lote1" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision1" type="text" id="comision1" size="3" /></td> <?php } else { ?>
	<td bgcolor="#0094FF"><input name="comision1" type="text" id="comision1" size="3" readonly /></td> <?php } ?>
    <td bgcolor="#0094FF"><input name="importe1" type="text" id="importe1" onBlur="MM_validateForm('importe1','','NisNum');return document.MM_returnValue" size="10"  /></td>
	<td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto1" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)"/></td>
	<td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto1" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)" /></td>
    
      <input name="secuencia1" type="hidden" class="phpmaker" id="secuencia1" size="65" />
	    </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote2" type="text" id="lote2" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision2" type="text" id="comision2" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision2" type="text" id="comision2" size="3" readonly /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe2" type="text" id="importe2" onBlur="MM_validateForm('importe2','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto2" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto2" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia2" type="hidden" class="phpmaker" id="secuencia2" size="65" /> 
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote3" type="text" id="lote3" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision3" type="text" id="comision3" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision3" type="text" id="comision3" size="3"  readonly=""/></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe3" type="text" id="importe3" onBlur="MM_validateForm('importe3','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto3" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto3" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
           <input name="secuencia3" type="hidden" class="phpmaker" id="secuencia3" size="65" />
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote4" type="text" id="lote4" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="65" /></td>
           <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision4" type="text" id="comision4" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision4" type="text" id="comision4" size="3" readonly /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe4" type="text" id="importe4" onBlur="MM_validateForm('importe4','','NisNum');return document.MM_returnValue" size="10" /></td>
		   <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto4" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto4" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
          <input name="secuencia4" type="hidden" class="phpmaker" id="secuencia4" size="65" />
		</tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote5" type="text" id="lote5" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision5" type="text" id="comision5" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision5" type="text" id="comision5" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe5" type="text" id="importe5" onBlur="MM_validateForm('importe5','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto5" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto5" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
          <input name="secuencia5" type="hidden" class="phpmaker" id="secuencia5" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote6" type="text" id="lote6" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="65" /></td>
          <?php if($nivel=='9') { ?>   <td bgcolor="#0094FF"><input name="comision6" type="text" id="comision6" size="3" /></td> <?php } else { ?>
           <td bgcolor="#0094FF"><input name="comision6" type="text" id="importe62" size="3" /></td> <?php } ?>
		   <td bgcolor="#0094FF"><input name="importe6" type="text" id="importe6" onBlur="MM_validateForm('importe6','','NisNum');return document.MM_returnValue" size="10" /></td>
           <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto6" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto6" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia6" type="hidden" class="phpmaker" id="secuencia6" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote7" type="text" id="lote7" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision7" type="text" id="comision7" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision7" type="text" id="comision7" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe7" type="text" id="importe7" onBlur="MM_validateForm('importe7','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto7" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto7" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia7" type="hidden" class="phpmaker" id="secuencia7" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote8" type="text" id="lote8" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision8" type="text" id="comision8" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision8" type="text" id="comision8" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe8" type="text" id="importe8" onBlur="MM_validateForm('importe8','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto8" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto8" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia8" type="hidden" class="phpmaker" id="secuencia8" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote9" type="text" id="lote9" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision9" type="text" id="comision9" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision9" type="text" id="comision9" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe9" type="text" id="importe9" onBlur="MM_validateForm('importe9','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto9" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto9" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia9" type="hidden" class="phpmaker" id="secuencia9" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote10" type="text" id="lote10" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision10" type="text" id="comision10" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision10" type="text" id="comision10" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe10" type="text" id="importe10" onBlur="MM_validateForm('importe10','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto10" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto10" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
          <input name="secuencia10" type="hidden" class="phpmaker" id="secuencia10" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote11" type="text" id="lote11" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision11" type="text" id="comision11" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision11" type="text" id="comision11" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe11" type="text" id="importe11" onBlur="MM_validateForm('importe11','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto11" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto11" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
           <input name="secuencia11" type="hidden" class="phpmaker" id="secuencia11" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote12" type="text" id="lote12" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision12" type="text" id="comision12" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision12" type="text" id="comision12" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe12" type="text" id="importe12" onBlur="MM_validateForm('importe12','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto12" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto12" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
           <input name="secuencia12" type="hidden" class="phpmaker" id="secuencia12" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote13" type="text" id="lote13" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision13" type="text" id="comision13" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision13" type="text" id="comision13" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe13" type="text" id="importe13" onBlur="MM_validateForm('importe13','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto13" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto13" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
                     <input name="secuencia13" type="hidden" class="phpmaker" id="secuencia13" size="65" />
		</tr> <tr>
          <td bgcolor="#0094FF"><input name="lote14" type="text" id="lote14" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="65" /></td>
       <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision14" type="text" id="comision14" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision14" type="text" id="comision14" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe14" type="text" id="importe14" onBlur="MM_validateForm('importe14','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto14" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto14" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia14" type="hidden" class="phpmaker" id="secuencia14" size="65" />
		</tr>
      </table>      </td>
    </tr>
    <tr bgcolor="#0094FF"><td bgcolor="#0094FF"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF"><tr>
      <td width="71" height="20" valign="top" class="ewTableHeader">&nbsp;Leyenda</td>
      <td width="280"  valign="top"><textarea name="leyenda" cols="55" rows="4"></textarea></td>
    </tr>
	<tr><td class="ewTableHeader">&nbsp;Imprimir </td><td><input type="checkbox" name="imprime" value="1" />
</td>
	</tr>
	 </table></td>
      <td width="281" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        <tr>
          <td width="48%">&nbsp;<span class="ewTableHeader">Resolucion</span></td>
          <td width="52%"><input name="tieneresol" type="checkbox" id="tieneresol" value="si" onClick="resol(this.form)"   /></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center"><img src="images/con_pago.gif" width="140" height="28" /></div></td>
          </tr>
        <tr>
          <td>&nbsp;<span class="ewTableHeader">Efectivo</span></td>
		  
          <td><input name="pago_contado" type="checkbox" onClick="forma_pago(this.form)" value="si" checked="checked"/></td>
        </tr>
      </table></tr>
  
  
    <tr>
      <td colspan="3" bgcolor="#003366"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        <tr>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php  echo $iva_15_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php  echo $iva_21_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Comision </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php  echo $iva_15_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php  echo $iva_21_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">R. G. 3337 </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Total </div></td>
        </tr>
        
        <tr>
          <td><input name="totneto105"  type="hidden"  size="12" /></td>
          <td><input name="totneto21"   type="hidden"  size="12" /></td>
          <td><input name="totcomis"    type="hidden"  size="12" /></td>
          <td><input name="totiva105"   type="hidden"  size="12" /></td>
          <td><input name="totiva21"    type="hidden"  size="12" /></td>
          <td><input name="totimp"      type="hidden"  size="10" /></td>
          <td><input name="tot_general" type="hidden"  size="15" /></td>
        </tr>
		 <tr>
          <td><input name="totneto105_1"  type="text"  size="12" /></td>
          <td><input name="totneto21_1"   type="text"  size="12" /></td>
          <td><input name="totcomis_1"    type="text"  size="12" /></td>
          <td><input name="totiva105_1"   type="text"  size="12" /></td>
          <td><input name="totiva21_1"    type="text"  size="12" /></td>
          <td><input name="totimp_1"      type="text"  size="10" /></td>
          <td><input name="tot_general_1" type="text"  size="15" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td><div align="center">
            
          </div></td>
          <td><div align="center">
            <input type="submit" name="Submit3" value="Grabar" />
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
<?php
mysql_free_result($tipo_comprobante);
mysql_free_result($cliente);
mysql_free_result($num_remates);
?>
