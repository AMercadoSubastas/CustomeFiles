<?php define('FPDF_FONTPATH','fpdf153/font/');
require('fpdf153/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$ptcomp =6;
$pserie =5;
$pncomp =5 ;


// Cambia el estado de la factura 
$actualizaemitido ="UPDATE cabfac SET emitido='1' WHERE ncomp='$pncomp'";

  $Result1 = mysqli_query($amercado, $actualizaemitido) or die(mysqli_error("ERROR"));
// Leo los renglones
 // Verificado 
$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s" , $ptcomp, $pserie, $pncomp);
$detallefac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
//$row_detallefac = mysqli_fetch_assoc($detallefac);
$totalRows_detallefac = mysqli_num_rows($detallefac);

// TRaigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die(mysqli_error($amercado));
$row_Recordset2 = mysqli_fetch_assoc($impuestos);
$totalRows_Recordset2 = mysqli_num_rows($impuestos);
$impuestos->data_seek(1);
    $row = $impuestos->fetch_array();
// Calcular los porcentajes de impuestos
    $porc_iva105 = $row[1]/ 100 ."<br>";
    $impuestos->data_seek(0);
    $row = $impuestos->fetch_array();
    $porc_iva21 = $row[1] / 100;


// Leo la cabecera

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $ptcomp, $pserie, $pncomp);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));
$row_cabecerafac = mysqli_fetch_assoc($cabecerafac);

$fecha        = $row_cabecerafac["fecdoc"];
$fecharem       = $row_cabecerafac["fecdoc"];
$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
$cliente      = $row_cabecerafac["cliente"];
$tot_neto21   = $row_cabecerafac["totneto21"];
$tot_neto105  = $row_cabecerafac["totneto105"];
$tot_comision = $row_cabecerafac["totcomis"];
$tot_iva21    = $row_cabecerafac["totiva21"];
$tot_iva105   = $row_cabecerafac["totiva105"];
$tot_resol    = $row_cabecerafac["totimp"];
$total        = $row_cabecerafac["totbruto"];
$remate       = $row_cabecerafac["codrem"];
$estado       = $row_cabecerafac["estado"];
$total_let        = $row_cabecerafac["totbruto"];

//if ($estado=='S') {
//$mensaje = "RECIBO + FACTURA";


//} else {


//$mensaje = "FACTURA";
//}

// Leo la leyenda asociada a la factura y corto la descripcion en 5 renglones de 100 caracteres c/u
$query_leyenda = sprintf("SELECT * FROM factley WHERE tcomp = %s AND serie = %s AND ncomp = %s", $ptcomp, $pserie, $pncomp);
$cabeleyenda = mysqli_query($amercado, $query_leyenda) or die(mysqli_error($amercado));
$row_cabeleyenda = mysqli_fetch_assoc($cabeleyenda);
$leyenda1      = substr($row_cabeleyenda["leyendafc"],0,100);
$leyenda2	   = substr($row_cabeleyenda["leyendafc"],100,200);
$leyenda3	   = substr($row_cabeleyenda["leyendafc"],200,300);
$leyenda4	   = substr($row_cabeleyenda["leyendafc"],300,400);
$leyenda5	   = substr($row_cabeleyenda["leyendafc"],400,500);
echo $leyenda1;
// Leo el remate
if ($remate!="") {
   $query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $remate);
   $remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
   $row_remates = mysqli_fetch_assoc($remates);
   $remate_ncomp = $row_remates["ncomp"];
   $remate_direc = $row_remates["direccion"];
   $remate_fecha = $row_remates["fecreal"];
   $loc_remate = $row_remates["codloc"];
   $prov_remate = $row_remates["codprov"];
   $remate_fecha = substr($remate_fecha,8,2)."-".substr($remate_fecha,5,2)."-".substr($remate_fecha,0,4);
 
echo $remate_ncomp."<br>";
echo $remate_direc."<br>";
echo $remate_fecha."<br>";
echo $loc_remate."<br>";
echo $remate_ncomp."<br>";
echo $prov_remate."<br>";
echo $remate_fecha."<br>";
echo $remate_ncomp."<br>";


// Leo la localidad REmate
  $query_localidades_rem = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_remate);
  $localidad_rem = mysqli_query($amercado, $query_localidades_rem) or die(mysqli_error($amercado));
  $row_localidades_rem = mysqli_fetch_assoc($localidad_rem);
  $nomlocrem = $row_localidades_rem["descripcion"];
  echo $nomlocrem."<br>";
// Leo la Provincia del REmate
  $query_provincia_rem = sprintf("SELECT * FROM provincias WHERE  codnum = %s",$prov_remate);
  $provincia_rem = mysqli_query($amercado, $query_provincia_rem) or die(mysqli_error($amercado));
  $row_provincia_rem = mysqli_fetch_assoc($provincia_rem);
  $nomprovrem = $row_provincia_rem["descripcion"];
  echo $nomprovrem."<br>";
  } 
  
  
// Leo el cliente
  $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  $enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  $row_entidades = mysqli_fetch_assoc($enti);
  $nom_cliente   = $row_entidades["razsoc"];
  $calle_cliente = $row_entidades["calle"];
  $nro_cliente   = $row_entidades["numero"];
  $codp_cliente  = $row_entidades["codpost"];
  $loc_cliente   = $row_entidades["codloc"]; 
  $cuit_cliente  = $row_entidades["cuit"];
  $tel_cliente   = $row_entidades["tellinea"];
  $tipo_iva   =    $row_entidades["tipoiva"];
// Leo la localidad

  $query_localidades = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_cliente);
  $localidad = mysqli_query($amercado, $query_localidades) or die(mysqli_error($amercado));
  $row_localidades = mysqli_fetch_assoc($localidad);
  $nomloc = $row_localidades["descripcion"];
// TIPO DE IVA 
$sql_iva = sprintf("SELECT * FROM tipoiva WHERE  codnum = %s", $tipo_iva);
$tipo_de_iva = mysqli_query($amercado, $sql_iva ) or die(mysqli_error($amercado));
$row_tip_iva = mysqli_fetch_assoc($tipo_de_iva);
$tip_iva_cliente = $row_tip_iva["descrip"];

//Inicializo los datos de las columnas de lotes
	$df_codintlote = "";
	$df_descrip1   = "";
	$df_descrip2   = "";
	$df_neto       = "";
	$df_importe    = "";

// Datos de los renglones

if ($remate!="" ) {
while($row_detallefac = mysqli_fetch_array($detallefac))
{
	
	$df_lote     = $row_detallefac["codlote"];
	$neto          = $row_detallefac["neto"];
	$importe  = number_format($row_detallefac["neto"], 2, ',','.');
	$df_neto = number_format($row_detallefac["neto"], 2, ',','.');
	echo "Codigo Lote".$df_lote."<br>";
	echo $neto."<br>";
	echo $importe."<br>";
	echo $df_neto."<br>";
   // $df_neto       = $df_neto.$neto."\n";
	$df_importe    = $df_importe.$importe."\n";
	$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s AND secuencia = %s" , $remate, $df_lote);
	$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
	$row_lotes = mysqli_fetch_assoc($lotes);
	$totalRows_lotes = mysqli_num_rows($lotes);
	
	$codintlote    = $row_lotes['codintlote'];
	$descrip1      = substr($row_detallefac['descrip'],0,60);
	$descrip2      = substr($row_detallefac['descrip'],61,120);
	//$neto          = $row_detallefac['neto'];
	//$importe       = $row_detallefac['neto'];
	
	$df_codintlote = $df_codintlote.$codintlote."\n";
	$df_descrip1   = $df_descrip1.$descrip1."\n";
	$df_descrip2   = $df_descrip2.$descrip2."\n";
	//$df_neto       = $df_neto.$neto."\n";

		
}
} else {
$query_detfac1 = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s" , $ptcomp, $pserie, $pncomp);
$detallefac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
//$row_detallefac = mysqli_fetch_assoc($detallefac);
$totalRows_detallefac1 = mysqli_num_rows($detallefac1);
while($row_detallefac1 = mysqli_fetch_array($detallefac1))
{

    $df_lote     = $row_detallefac1["concafac"];
	$neto          = $row_detallefac1["neto"];
	$neto          = $row_detallefac1["neto"];
	$importe  = number_format($row_detallefac1["neto"], 2, ',','.');
	$df_neto = number_format($row_detallefac1["neto"], 2, ',','.');
   // $df_neto       = $df_neto.$neto."\n";
	$df_importe    = $df_importe.$importe."\n";
	//$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s AND secuencia = %s" , $remate, $df_lote);
	//$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
	//$row_lotes = mysqli_fetch_assoc($lotes);
	//$totalRows_lotes = mysqli_num_rows($lotes);
	$df_codintlote = $df_codintlote.$df_lote."\n";
	//$codintlote    = $row_lotes['codintlote'];
	$descrip1   = substr($row_detallefac1['descrip'],0,60);
	$df_descrip1   = $df_descrip1.$descrip1."\n";
	//$descrip2      = substr($row_detallefac['descrip'],61,120);
	//$neto          = $row_detallefac['neto'];
	//$importe       = $row_detallefac['neto'];
	
//	$df_codintlote = $df_codintlote.$codintlote."\n";
//	$df_descrip1   = $df_descrip1.$descrip1."\n";
//	$df_descrip2   = $df_descrip2.$descrip2."\n";
  //echo $descrip1;


}

}
mysqli_close($amercado);
?>
