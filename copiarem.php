<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');

setlocale (LC_TIME,"es_ES.UTF-8");

require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];
$fechahoy = date("Y-m-d");
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$row_remates = mysqli_fetch_assoc($remates);
$direc = $row_remates["direccion"];
$fechareal = $row_remates["fecreal"];
$hora = $row_remates["horareal"];
$hora = $row_remates["horareal"];
$horareal = substr($hora,0,5);
$tipoind = $row_remates["tipoind"];
$sello = $row_remates["sello"];
$plazo_sap = $row_remates["plazoSAP"];
$obs =  $row_remates["observacion"];
$obs = $obs."(Viene del Remate ".$colname_remate.")";
$cod_loc = $row_remates["codloc"];
$cod_prov = $row_remates["codprov"];
$cod_pais = $row_remates["codpais"];
$fecha         = substr($fechareal,8,2)."-".substr($fechareal,5,2)."-".substr($fechareal,0,4);
$serie_rem = 4;
$tcomp_rem = 4;
$codcli = $row_remates["codcli"];

//LEO LA SERIE DEL REMATE PARA VER EL PROXIMO NUMERO Y ACTUALIZARLO
$query_series = sprintf("SELECT * FROM series WHERE codnum = %s ", $serie_rem);
$series = mysqli_query($amercado, $query_series) or die(mysqli_error($amercado));
$row_series = mysqli_fetch_assoc($series);
$nuevo_remate = $row_series["nroact"] ;
$prox_remate = $nuevo_remate + 1;
// ACTUALIZO LA SERIE
$update_series = sprintf("UPDATE series SET nroact = %d WHERE codnum = %s ", $prox_remate, $serie_rem);
$up_series = mysqli_query($amercado, $update_series) or die("ERROR GRABANDO SERIE");
// GRABO EL NUEVO REMATE
$insert_remate = sprintf("INSERT INTO remates (direccion, fecest, fecreal, tcomp, serie, ncomp, horareal, tipoind, observacion, sello, plazoSAP, codpais, codprov, codloc, codcli) VALUES ('$direc','$fechahoy','$fechareal',%s,%s,%s,'$horareal',%s,'$obs',%s,'$plazo_sap',%s,%s,%s,%s)",  $tcomp_rem, $serie_rem, $nuevo_remate,  $tipoind, $sello,  $cod_pais, $cod_prov, $cod_loc, $codcli);
//echo "INSERT_REMATE = ".$insert_remate." - ";
$ins_remates = mysqli_query($amercado, $insert_remate) or die("ERROR GRABANDO NUEVO REMATE ".$nuevo_remate." - ");
//LEO LA TABLA DIR_REMATES PARA VER SI TENGO DIRECCION DE EXPOSICION
$query_dir_remates = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 1);
$dir_remates = mysqli_query($amercado, $query_dir_remates) or die(mysqli_error($amercado));
$row_dir_remates = mysqli_fetch_assoc($dir_remates);
$dir_direccion = $row_dir_remates["direccion"];
$dir_codprov   = $row_dir_remates["codprov"];
$dir_codloc    = $row_dir_remates["codloc"];

// LEO LA SEGUNDA DIRECCION
$query_dir_remates2 = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 2);
$dir_remates2 = mysqli_query($amercado, $query_dir_remates2) or die(mysqli_error($amercado));
$row_dir_remates2 = mysqli_fetch_assoc($dir_remates2);
$dir_direccion2 = $row_dir_remates2["direccion"];
$dir_codprov2   = $row_dir_remates2["codprov"];
$dir_codloc2    = $row_dir_remates2["codloc"];

//=======================================================================
// LEO LA TERCER DIRECCION
$query_dir_remates3 = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 3);
$dir_remates3 = mysqli_query($amercado, $query_dir_remates3) or die(mysqli_error($amercado));
$row_dir_remates3 = mysqli_fetch_assoc($dir_remates3);
$dir_direccion3 = $row_dir_remates3["direccion"];
$dir_codprov3   = $row_dir_remates3["codprov"];
$dir_codloc3    = $row_dir_remates3["codloc"];

//=======================================================================

//=======================================================================
// LEO LA CUARTA DIRECCION
$query_dir_remates4 = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 4);
$dir_remates4 = mysqli_query($amercado, $query_dir_remates4) or die(mysqli_error($amercado));
$row_dir_remates4 = mysqli_fetch_assoc($dir_remates4);
$dir_direccion4 = $row_dir_remates4["direccion"];
$dir_codprov4   = $row_dir_remates4["codprov"];
$dir_codloc4    = $row_dir_remates4["codloc"];

//=======================================================================

//=======================================================================
// LEO LA QUINTA DIRECCION
$query_dir_remates5 = sprintf("SELECT * FROM dir_remates WHERE codrem = %s AND secuencia = %d", $colname_remate, 5);
$dir_remates5 = mysqli_query($amercado, $query_dir_remates5) or die(mysqli_error($amercado));
$row_dir_remates5 = mysqli_fetch_assoc($dir_remates5);
$dir_direccion5 = $row_dir_remates5["direccion"];
$dir_codprov5   = $row_dir_remates5["codprov"];
$dir_codloc5    = $row_dir_remates5["codloc"];

//=======================================================================

if (mysqli_num_rows($dir_remates) > 0 ) {
	$hay_exhib = 1;
	// LEO LA PROVINCIA Y LA LOCALIDAD DE LA DIRECCION DE EXHIBICION
	$query_provincia_ex = sprintf("SELECT * FROM provincias WHERE codnum = %s", $dir_codprov);
	$provincia_ex = mysqli_query($amercado, $query_provincia_ex) or die(mysqli_error($amercado));
	$row_provincia_ex = mysqli_fetch_assoc($provincia_ex);
	$provin_ex = $row_provincia_ex["descripcion"];

	$query_localidad_ex = sprintf("SELECT * FROM localidades WHERE codnum = %s", $dir_codloc);
	$localidad_ex = mysqli_query($amercado, $query_localidad_ex) or die(mysqli_error($amercado));
	$row_localidad_ex = mysqli_fetch_assoc($localidad_ex);
	$localid_ex = $row_localidad_ex["descripcion"];
    
    // GRABO LA NUEVA DIR_EXPO EN EL NUEVO REMATE
    $insert_dirremate = sprintf("INSERT INTO dir_remates (codrem, secuencia, direccion, codpais, codprov, codloc) VALUES (%s,%s,'$dir_direccion',%s,%s,%s)", $nuevo_remate, 1, 1, $dir_codprov, $dir_codloc);
    $ins_dirremates = mysqli_query($amercado, $insert_dirremate) or die("ERROR GRABANDO NUEVO DIR REMATE 1 : ".$nuevo_remate." - ");
}
else 
	$hay_exhib = 0;
	
if (mysqli_num_rows($dir_remates2) > 0 ) {
	$hay_exhib = 2;
	// LEO LA PROVINCIA Y LA LOCALIDAD DE LA DIRECCION DE EXHIBICION
	$query_provincia_ex2 = sprintf("SELECT * FROM provincias WHERE codnum = %s", $dir_codprov2);
	$provincia_ex2 = mysqli_query($amercado, $query_provincia_ex2) or die(mysqli_error($amercado));
	$row_provincia_ex2 = mysqli_fetch_assoc($provincia_ex2);
	$provin_ex2 = $row_provincia_ex2["descripcion"];

	$query_localidad_ex2 = sprintf("SELECT * FROM localidades WHERE codnum = %s", $dir_codloc2);
	$localidad_ex2 = mysqli_query($amercado, $query_localidad_ex2) or die(mysqli_error($amercado));
	$row_localidad_ex2 = mysqli_fetch_assoc($localidad_ex2);
	$localid_ex2 = $row_localidad_ex2["descripcion"];
   // GRABO LA NUEVA DIR_EXPO EN EL NUEVO REMATE
    $insert_dirremate2 = sprintf("INSERT INTO dir_remates (codrem, secuencia, direccion, codpais, codprov, codloc) VALUES (%s,%s,'$dir_direccion2',%s,%s,%s)", $nuevo_remate, 2,  1, $dir_codprov2, $dir_codloc2);
    $ins_dirremates2 = mysqli_query($amercado, $insert_dirremate2) or die("ERROR GRABANDO NUEVO DIR REMATE 2 ".$nuevo_remate." - ");
}


//================================================================================
if (mysqli_num_rows($dir_remates3) > 0 ) {
	$hay_exhib = 3;
	// LEO LA PROVINCIA Y LA LOCALIDAD DE LA DIRECCION DE EXHIBICION
	$query_provincia_ex3 = sprintf("SELECT * FROM provincias WHERE codnum = %s", $dir_codprov3);
	$provincia_ex3 = mysqli_query($amercado, $query_provincia_ex3) or die(mysqli_error($amercado));
	$row_provincia_ex3 = mysqli_fetch_assoc($provincia_ex3);
	$provin_ex3 = $row_provincia_ex3["descripcion"];

	$query_localidad_ex3 = sprintf("SELECT * FROM localidades WHERE codnum = %s", $dir_codloc3);
	$localidad_ex3 = mysqli_query($amercado, $query_localidad_ex3) or die(mysqli_error($amercado));
	$row_localidad_ex3 = mysqli_fetch_assoc($localidad_ex3);
	$localid_ex3 = $row_localidad_ex3["descripcion"];
    // GRABO LA NUEVA DIR_EXPO EN EL NUEVO REMATE
    $insert_dirremate3 = sprintf("INSERT INTO dir_remates (codrem, secuencia, direccion, codpais, codprov, codloc) VALUES (%s,%s,'$dir_direccion3',%s,%s,%s)", $nuevo_remate, 3,  1, $dir_codprov3, $dir_codloc3);
    $ins_dirremates3 = mysqli_query($amercado, $insert_dirremate3) or die("ERROR GRABANDO NUEVO DIR REMATE 3".$nuevo_remate." - ");
}

//================================================================================

//================================================================================
if (mysqli_num_rows($dir_remates4) > 0 ) {
	$hay_exhib = 4;
	// LEO LA PROVINCIA Y LA LOCALIDAD DE LA DIRECCION DE EXHIBICION
	$query_provincia_ex4 = sprintf("SELECT * FROM provincias WHERE codnum = %s", $dir_codprov4);
	$provincia_ex4 = mysqli_query($amercado, $query_provincia_ex4) or die(mysqli_error($amercado));
	$row_provincia_ex4 = mysqli_fetch_assoc($provincia_ex4);
	$provin_ex4 = $row_provincia_ex4["descripcion"];

	$query_localidad_ex4 = sprintf("SELECT * FROM localidades WHERE codnum = %s", $dir_codloc4);
	$localidad_ex4 = mysqli_query($amercado, $query_localidad_ex4) or die(mysqli_error($amercado));
	$row_localidad_ex4 = mysqli_fetch_assoc($localidad_ex4);
	$localid_ex4 = $row_localidad_ex4["descripcion"];
    // GRABO LA NUEVA DIR_EXPO EN EL NUEVO REMATE
    $insert_dirremate4 = sprintf("INSERT INTO dir_remates (codrem, secuencia, direccion, codpais, codprov, codloc) VALUES (%s,%s,'$dir_direccion4',%s,%s,%s)", $nuevo_remate, 4, 1, $dir_codprov4, $dir_codloc4);
    $ins_dirremates4 = mysqli_query($amercado, $insert_dirremate4) or die("ERROR GRABANDO NUEVO DIR REMATE 4".$nuevo_remate." - ");
}

//================================================================================

//================================================================================
if (mysqli_num_rows($dir_remates5) > 0 ) {
	$hay_exhib = 5;
	// LEO LA PROVINCIA Y LA LOCALIDAD DE LA DIRECCION DE EXHIBICION
	$query_provincia_ex5 = sprintf("SELECT * FROM provincias WHERE codnum = %s", $dir_codprov5);
	$provincia_ex5 = mysqli_query($amercado, $query_provincia_ex5) or die(mysqli_error($amercado));
	$row_provincia_ex5 = mysqli_fetch_assoc($provincia_ex5);
	$provin_ex5 = $row_provincia_ex5["descripcion"];

	$query_localidad_ex5 = sprintf("SELECT * FROM localidades WHERE codnum = %s", $dir_codloc5);
	$localidad_ex5 = mysqli_query($amercado, $query_localidad_ex5) or die(mysqli_error($amercado));
	$row_localidad_ex5 = mysqli_fetch_assoc($localidad_ex5);
	$localid_ex5 = $row_localidad_ex5["descripcion"];
    // GRABO LA NUEVA DIR_EXPO EN EL NUEVO REMATE
    $insert_dirremate5 = sprintf("INSERT INTO dir_remates (codrem, secuencia, direccion, codpais, codprov, codloc) VALUES (%s,%s,'$dir_direccion5',%s,%s,%s)", $nuevo_remate, 5,   1, $dir_codprov5, $dir_codloc5);
    $ins_dirremates5 = mysqli_query($amercado, $insert_dirremate5) or die("ERROR GRABANDO NUEVO DIR REMATE 5".$nuevo_remate." - ");

}
echo "                           SE GENERO EL NUEVO REMATE NRO : ".$nuevo_remate."    ";
mysqli_close($amercado);
?>