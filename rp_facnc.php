<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');
header('Content-Type: text/html; charset=utf-8');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$ptcomp = $_GET['ftcomp'];
$pserie = $_GET['fserie'];
$pncomp = $_GET['fncomp'];
$totalFilas = 0;
// Cambia el estado de la factura 
$actualizaemitido ="UPDATE cabfac SET emitido='1' WHERE tcomp = '$ptcomp' AND ncomp='$pncomp'";
$Result1 = mysqli_query($amercado, $actualizaemitido) or die(mysqli_error($amercado));
// Leo los renglones

$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s ORDER BY codlote" , $ptcomp, $pserie, $pncomp);
$detallefac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
$totalRows_detallefac = mysqli_num_rows($detallefac);

// Traigo impuestos
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

// Leo la cabecera de factura
$query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $ptcomp, $pserie, $pncomp);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));
$row_cabecerafac = mysqli_fetch_assoc($cabecerafac);
 
$fecha        = $row_cabecerafac["fecdoc"];
$fecharem     = $row_cabecerafac["fecdoc"];
$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
$cliente      = $row_cabecerafac["cliente"];
$tot_neto21   = $row_cabecerafac["totneto21"];
$tot_neto105  = $row_cabecerafac["totneto105"];
$tot_comision = $row_cabecerafac["totcomis"];
$tot_iva21    = $row_cabecerafac["totiva21"];
$tot_iva105   = $row_cabecerafac["totiva105"];
$tot_resol    = $row_cabecerafac["totimp"];
$total        = $row_cabecerafac["totbruto"];
$totalletras  = $row_cabecerafac["totbruto"];
$remate       = $row_cabecerafac["codrem"];
$estado       = $row_cabecerafac["estado"];
$nro_doc      = $row_cabecerafac["nrodoc"];
$CAE          = $row_cabecerafac["CAE"];
$CAEFchVto    = $row_cabecerafac["CAEFchVto"];

// Leo la leyenda asociada a la factura y corto la descripcion en 8 renglones de 100 caracteres c/u
$query_leyenda   = sprintf("SELECT * FROM factley WHERE tcomp = %s AND serie = %s AND ncomp = %s", $ptcomp, $pserie, $pncomp);
$cabeleyenda     = mysqli_query($amercado, $query_leyenda) or die(mysqli_error($amercado));
$row_cabeleyenda = mysqli_fetch_assoc($cabeleyenda);

$leyenda1      = substr($row_cabeleyenda["leyendafc"],0,120);
$leyenda2	   = substr($row_cabeleyenda["leyendafc"],120,120);
$leyenda3	   = substr($row_cabeleyenda["leyendafc"],240,120);
$leyenda4	   = substr($row_cabeleyenda["leyendafc"],360,120);
$leyenda5	   = substr($row_cabeleyenda["leyendafc"],480,120);
$leyenda6	   = substr($row_cabeleyenda["leyendafc"],600,120);
$leyenda7	   = substr($row_cabeleyenda["leyendafc"],720,120);
$leyenda8	   = substr($row_cabeleyenda["leyendafc"],840,120);
$leyenda9	   = substr($row_cabeleyenda["leyendafc"],960,120);
$leyenda10	   = substr($row_cabeleyenda["leyendafc"],1080,120);
$leyenda11	   = substr($row_cabeleyenda["leyendafc"],1200,120);
$leyenda12	   = substr($row_cabeleyenda["leyendafc"],1320,120);
$leyenda13	   = substr($row_cabeleyenda["leyendafc"],1440,120);
$leyenda14	   = substr($row_cabeleyenda["leyendafc"],1660,120);
$leyenda15	   = substr($row_cabeleyenda["leyendafc"],1780,120);
$leyenda16	   = substr($row_cabeleyenda["leyendafc"],1900,120);
$leyenda17	   = substr($row_cabeleyenda["leyendafc"],2020,120);
$leyenda18	   = substr($row_cabeleyenda["leyendafc"],2140,120);
$leyenda19	   = substr($row_cabeleyenda["leyendafc"],2260,120);
$leyenda20	   = substr($row_cabeleyenda["leyendafc"],2380,120);
$leyenda21	   = substr($row_cabeleyenda["leyendafc"],2500,120);
$leyenda22	   = substr($row_cabeleyenda["leyendafc"],2620,120);
$leyenda23	   = substr($row_cabeleyenda["leyendafc"],2740,120);
$leyenda24	   = substr($row_cabeleyenda["leyendafc"],2860,120);
$leyenda25	   = substr($row_cabeleyenda["leyendafc"],2980,120);
$leyenda26	   = substr($row_cabeleyenda["leyendafc"],3100,120);
$leyenda27	   = substr($row_cabeleyenda["leyendafc"],3220,120);
$leyenda28	   = substr($row_cabeleyenda["leyendafc"],3340,120);

// Leo el remate
if ($remate!="") {
   	$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $remate);
   	$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
   	$row_remates = mysqli_fetch_assoc($remates);
	$remate_ncomp = $row_remates["ncomp"];
	$remate_direc = $row_remates["direccion"];
	$remate_fecha = $row_remates["fecreal"];
	$loc_remate   = $row_remates["codloc"];
	$prov_remate  = $row_remates["codprov"];
	$remate_fecha = substr($remate_fecha,8,2)."-".substr($remate_fecha,5,2)."-".substr($remate_fecha,0,4);

	$totalFilas = 0;
	//Leo si hay direccion de exposicion
   	$query_remate_expo = sprintf("SELECT * FROM dir_remates WHERE codrem = %s", $remate);
   	$remates_expo = mysqli_query($amercado, $query_remate_expo) or die(mysqli_error($amercado));
	$totalFilas    =    mysqli_num_rows($remates_expo);
	if ($totalFilas != 0) {
   		$row_remates_expo = mysqli_fetch_assoc($remates_expo);
		$remate_ncomp = $row_remates_expo["codrem"];
		$remate_direc = $row_remates_expo["direccion"];
		$loc_remate   = $row_remates_expo["codloc"];
		$prov_remate  = $row_remates_expo["codprov"];
	}
	
   	// Leo la localidad del Remate
  	$query_localidades_rem = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_remate);
  	$localidad_rem = mysqli_query($amercado, $query_localidades_rem) or die(mysqli_error($amercado));
  	$row_localidades_rem = mysqli_fetch_assoc($localidad_rem);
  	$nomlocrem = $row_localidades_rem["descripcion"];

  	// Leo la Provincia del Remate
  	$query_provincia_rem = sprintf("SELECT * FROM provincias WHERE  codnum = %s",$prov_remate);
  	$provincia_rem = mysqli_query($amercado, $query_provincia_rem) or die(mysqli_error($amercado));
  	$row_provincia_rem = mysqli_fetch_assoc($provincia_rem);
  	$nomprovrem = $row_provincia_rem["descripcion"];

	// LEO EL ULTIMO NRO DE REMITO
	if ($ptcomp == 1 || $ptcomp == 6 || $ptcomp == 23  || $ptcomp == 24) {
		$serie_remito = 28;
		$query_remito = sprintf("SELECT * FROM series WHERE  codnum = %s",$serie_remito);
  		$remito = mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
  		$row_remito = mysqli_fetch_assoc($remito);
  		$ultimo = $row_remito["nroact"];
		$remitonum = $ultimo + 1;
		$query_act_remito = sprintf("UPDATE series SET nroact = %s WHERE  codnum = %s",$remitonum, $serie_remito);
		$act_remito = mysqli_query($amercado, $query_act_remito) or die(mysqli_error($amercado));
	}
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
		while($row_detallefac = mysqli_fetch_array($detallefac)) {
	  		$lote_num =  $row_detallefac["codlote"];
			if ($lote_num=="" ) {
				$df_lote    =  $row_detallefac["concafac"];
			}
			if ($lote_num!="" ){
				$df_lote     = $row_detallefac["codlote"];
			}
			$neto          = $row_detallefac["neto"];
			$importe  = number_format($row_detallefac["neto"], 2, ',','.');
			$df_neto  = number_format($row_detallefac["neto"], 2, ',','.');
			$df_importe    = $df_importe.$importe."\n";
			$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s AND secuencia = %s" , $remate, $df_lote);
			$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
			$row_lotes = mysqli_fetch_assoc($lotes);
			$totalRows_lotes = mysqli_num_rows($lotes);
	
			$codintlote    = $row_lotes['codintlote'];
			$descrip1      = substr($row_detallefac['descrip'],0,62);
			$descrip2      = substr($row_detallefac['descrip'],62,62);
			if ($lote_num=="" ) {
		 		$codintlote    = $row_detallefac['concafac']; // antes decian $row_lotes['concafac'];
		 		$df_codintlote = $row_detallefac['concafac']; // antes decian $row_lotes['concafac'];
			}
			if ($lote_num!="" ){
				$codintlote    = $row_lotes['codintlote'];
			}
			$df_codintlote = $df_codintlote.$codintlote."\n";
			$df_descrip1   = $df_descrip1.$descrip1."\n";
			$df_descrip2   = $df_descrip2.$descrip2."\n";
	
		}
	} else {
		$query_detfac1 = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s ORDER BY codlote" , $ptcomp, $pserie, $pncomp);
		$detallefac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));

		$totalRows_detallefac1 = mysqli_num_rows($detallefac1);
		while($row_detallefac1 = mysqli_fetch_array($detallefac1)) {

	    	$df_lote       = $row_detallefac1["concafac"];
			$neto          = $row_detallefac1["neto"];
			$neto          = $row_detallefac1["neto"];
			$importe  = number_format($row_detallefac1["neto"], 2, ',','.');
			$df_neto = number_format($row_detallefac1["neto"], 2, ',','.');
			$df_importe    = $df_importe.$importe."\n";
			$df_codintlote = $df_codintlote.$df_lote."\n";
			$descrip1   = substr($row_detallefac1['descrip'],0,60);
			$df_descrip1   = $df_descrip1.$descrip1."\n";
		}
	}
	mysqli_close($amercado);

// FACTURA ORIGINAL 
//Creo el PDF file
	// Imprimo la cabecera
   	// Linea de arriba
if ($estado=='P') {
  	$pdf=new FPDF();
  	
  	$pdf->AddPage();
  	$pdf->SetAutoPageBreak(1 , 2) ;

   	$pdf->SetLineWidth(.2);
   	$pdf->Line(9,7.5,200,7.5);
	$pdf->Line(9,7.5,9,280);
	$pdf->Line(9,280,200,280);
	$pdf->Line(200,7.5,200,280);
	$pdf->Line(9,50,200,50);
	$pdf->Line(9,100,200,100);
	$pdf->Line(9,200,200,200);
	$pdf->Line(9,270,200,270);
	$pdf->Line(9,76,200,76);
	$pdf->Line(107,7.5,107,50);
   	$pdf->SetFont('Arial','B',14);
	$pdf->SetY(8);
	$pdf->SetX(150);
	$pdf->Cell(160,10,'FACTURA');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(15);
	$pdf->SetX(130);
   	$pdf->Cell(160,10,'Fecha :');
	$pdf->SetY(23);
	$pdf->SetX(130);
   	$pdf->Cell(150,10,'N� :');
	$pdf->Cell(150,10,'Fecha :');
	$pdf->SetY(32);
	$pdf->SetX(120);
   	$pdf->Cell(150,10,'C.U.I.T : 30-71018343-7');
	$pdf->SetY(37);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Ing. Brutos C.M : 901-265134-1');
	$pdf->SetY(42);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Fecha de Inicio a Actividades : 01/07/2007');
	$pdf->SetY(48);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Se�or/es:');
	$pdf->SetY(54);
	$pdf->SetX(10);
	$pdf->Cell(10,10,'Domicilio:');
	$pdf->SetX(125);
	$pdf->Cell(10,10,'Localidad:');
	$pdf->SetY(60);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'IVA:');
	$pdf->SetY(60);
	$pdf->SetX(125);
	$pdf->Cell(150,10,'CUIT:'.$cuit_cliente);
	$pdf->SetY(66);
	$pdf->SetY(66);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Telefono:'.$tel_cliente);
   	//Movernos a la derecha
    
   	//Fecha
	$pdf->SetY(15);
	$pdf->Cell(130);
	$pdf->Image('images/logo_adrian.jpg',10,8);
	$pdf->Image('images/equis.jpg',100,8);


	// Imprimo la cabecera
   	// Nota Debito
   	if ($ptcomp==21 || $ptcomp==22 || $ptcomp==29  || $ptcomp==30 || $ptcomp==48 || $ptcomp==49) {
		$pdf->SetY(6);
		$nota = 'NOTA ';
		$debito= 'DEBITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota,0,0,'L');
		$pdf->SetLineWidth(5);
		$pdf->Line(145,10,170,10);
		$pdf->SetX(174);
		$pdf->Cell(70,10,$debito,0,0,'L');

	}
	if ($ptcomp==5 || $ptcomp==7 || $ptcomp==25  || $ptcomp==26 || $ptcomp==46 || $ptcomp==47) {
		$pdf->SetY(6);
		$nota = 'NOTA ';
		$debito= 'CREDITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota,0,0,'L');
		$pdf->SetLineWidth(5);
		$pdf->Line(145,10,170,10);
		$pdf->SetX(174);
		$pdf->Cell(70,10,$debito,0,0,'L');

	}
 	$pdf->SetFont('Arial','B',10);
   	//Movernos a la derecha
	$pdf->SetY(17); // $pdf->SetY(15);
	$pdf->Cell(130);
   	$pdf->Cell(40,10,$fecha,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	
	// Datos del Cliente
	$pdf->SetY(53); // $pdf->SetY(50);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente);
	//$pdf->Cell(70,10,$nom_cliente." ".$ptcomp,0,0,'L');
	$pdf->SetY(58); // $pdf->SetY(55);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') '.$nomloc,0,0,'L');
	
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(65); // $pdf->SetY(62);
	$pdf->Cell(18);
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
	$pdf->Cell(130);
	$pdf->Cell(10,10,$cuit_cliente,0,0,'L');
	$pdf->SetY(71); // $pdf->SetY(68);
	$pdf->SetX(40);
	$pdf->Cell(30);
	$pdf->Cell(20,10,'CONTADO',0,0,'L');
	$pdf->Cell(116);
	$pdf->SetX(170);
	$pdf->Cell(30,10,$tel_cliente,0,0,'L');
	$pdf->SetX(15);
	$pdf->SetY(85); // $pdf->SetY(82);
	
	// Si el Remate no es nulo 
	if ($remate_ncomp!="") {
		$pdf->Cell(30,10,$remate_direc." ,".$nomlocrem." ,".$nomprovrem,0,0,'L');
		$pdf->Cell(140);
		$pdf->Cell(20,10,$remate_fecha,0,0,'L');
	}	
   	//Salto de l�nea

	//Posici�n de los t�tulos de los renglones, en este caso no va
	$Y_Fields_Name_position = 90;
	//Posici�n del primer rengl�n 
	$Y_Table_Position = 102; // $Y_Table_Position = 100;
	//Los t�tulos de las columnas no los debo poner
	//Aca van los datos de las columnas

	$j = $Y_Table_Position;
	$pdf->SetY($Y_Table_Position);

	$pdf->SetFont('Arial','B',11);
	$pdf->SetY($j);
	$pdf->SetX(5);

	// C�digo interno de Lote
	$pdf->MultiCell(12,9,$df_codintlote,0,'L');
	$pdf->SetY($j);
	$pdf->SetX(15);

	// Descripci�n del lote en uno o dos renglones
	if (isset($df_descrip2)) {
		$pdf->SetX(25);
		$pdf->MultiCell(150,9,$df_descrip1,0,'L');
		$pdf->SetY($j+4);
		$pdf->SetX(25);
		$pdf->MultiCell(150,9,$df_descrip2,0,'L');
		$pdf->SetY($j);
		$pdf->SetX(155);
		$pdf->MultiCell(44,9,$df_importe,0,'R');
	}
	else{
	    	$pdf->SetY($j);
		$pdf->MultiCell(150,9,$df_descrip1,0,'L');
		$pdf->SetX(155);
		$pdf->MultiCell(44,9,$df_importe,0,'R');
	}
	// ACA VA EL PIE
	//Posici�n: a -56mm cm del final
 	$pdf->SetY(-60); // $pdf->SetY(-60);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,4,$leyenda1,0,0,'L');
 	$pdf->SetY(-56); //$pdf->SetY(-56);
   	$pdf->Cell(100,4,$leyenda2,0,0,'L');
 	$pdf->SetY(-52); // $pdf->SetY(-52);
   	$pdf->Cell(100,4,$leyenda3,0,0,'L');
	$pdf->SetY(-48); // $pdf->SetY(-48);
   	$pdf->Cell(100,4,$leyenda4,0,0,'L');
	$pdf->SetY(-44); // $pdf->SetY(-44);
   	$pdf->Cell(100,4,$leyenda5,0,0,'L');
	$pdf->SetY(-40); // $pdf->SetY(-40);
   	$pdf->Cell(100,4,$leyenda6,0,0,'L');
	$pdf->SetY(-36); //$pdf->SetY(-36);
	$pdf->Cell(100,4,$leyenda7,0,0,'L');
	$pdf->SetY(-32); // $pdf->SetY(-32);
	$pdf->Cell(100,4,$leyenda8,0,0,'L');
	$pdf->SetY(-30); // $pdf->SetY(-25);
   	//Arial italic 8
   	$pdf->SetFont('Arial','I',8);
  	if ($pserie==1 OR $pserie==5 OR $pserie==26) {	
		$total = $tot_neto21 + $tot_neto105 + $tot_comision+$tot_iva21+$tot_iva105;
		$tot_neto21   = number_format($tot_neto21, 2, ',','.');
		$tot_neto105  = number_format($tot_neto105, 2, ',','.');
		$tot_comision = number_format($tot_comision, 2, ',','.');
		$tot_iva21    = number_format($tot_iva21, 2, ',','.');
		$tot_iva105   = number_format($tot_iva105, 2, ',','.');
		$tot_resol    = number_format($tot_resol, 2, ',','.');
		$total        = number_format($total, 2, ',','.');
		//Porcentajes
		// tipo de comprobante 
		// FActura A
   		$pdf->Cell(0,8,'              21 %                              10,5 %                           10%                                21 %                             10,5 %',0,0,'L');
   		$pdf->SetY(-22); // $pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);
		
		// ACA VAN LOS CAMPOS UNO A UNO EN EL CASILLERO QUE LES CORRESPONDE
		$pdf->SetX(10);
		$pdf->Cell(15,8,$tot_neto21,0,0,'R');
		$pdf->SetX(40);
		$pdf->Cell(15,8,$tot_neto105,0,0,'R');
		$pdf->SetX(70);
		$pdf->Cell(15,8,$tot_comision,0,0,'R');
		$pdf->SetX(100);
		$pdf->Cell(15,8,$tot_iva21,0,0,'R');
		$pdf->SetX(130);
		$pdf->Cell(15,8,$tot_iva105,0,0,'R');
		$pdf->SetX(160);
		$pdf->Cell(15,8,$tot_resol,0,0,'R');
		$pdf->SetX(190);
		$pdf->Cell(15,8,$total,0,0,'R');
	}
	else {
		$totalneto21  = $tot_neto21*1.21; // sacar el cable !!!!
		$totalneto105 = $tot_neto105+$tot_iva105;
		$tot_comision = $tot_comision*1.21; // sacar el cable !!!!
		$total = $totalneto21 + $totalneto105 + $tot_comision ;
		$totalneto21  = number_format($totalneto21, 2, ',','.');
		$totalneto105 = number_format($totalneto105, 2, ',','.');
		$tot_neto21   = number_format($tot_neto21, 2, ',','.');
		$tot_neto105  = number_format($tot_neto105, 2, ',','.');
		$tot_comision = number_format($tot_comision, 2, ',','.');
		$tot_iva21    = number_format($tot_iva21, 2, ',','.');
		$tot_iva105   = number_format($tot_iva105, 2, ',','.');
		$tot_resol    = number_format($tot_resol, 2, ',','.');
		$total        = number_format($total, 2, ',','.');
		
		$pdf->Cell(0,8,'                               21 %                                                         10,5 %                                                     10%                                                    ',0,0,'L');
   	 	$pdf->SetY(-22); // $pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);
		
		// ACA VAN LOS CAMPOS UNO A UNO EN EL CASILLERO QUE LES CORRESPONDE
		$pdf->SetX(20);
		$pdf->Cell(15,8,$totalneto21,0,0,'R');
		$pdf->SetX(80);
		$pdf->Cell(15,8,$totalneto105,0,0,'R');
		$pdf->SetX(135);
		$pdf->Cell(15,8,$tot_comision,0,0,'R');
		$pdf->SetX(190);
		$pdf->Cell(15,8,$total,0,0,'R');
	
	}
	
	// FACTURA DUPLICADO
	//Creo el PDF file
	
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(1 , 2) ;
	// Imprimo la cabecera
	// Nota Debito
    if ($ptcomp==21 || $ptcomp==22 || $ptcomp==29  || $ptcomp==30 || $ptcomp==48 || $ptcomp==49) {
		$pdf->SetY(6);
		$nota = 'NOTA ';
		$debito= 'DEBITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota,0,0,'L');
		$pdf->SetLineWidth(5);
		$pdf->Line(145,10,170,10);
		$pdf->SetX(174);
		$pdf->Cell(70,10,$debito,0,0,'L');

	}
	if ($ptcomp==5 || $ptcomp==7 || $ptcomp==25  || $ptcomp==26 || $ptcomp==46 || $ptcomp==47) {
		$pdf->SetY(6);
		$nota = 'NOTA ';
		$debito= 'CREDITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota,0,0,'L');
		$pdf->SetLineWidth(5);
		$pdf->Line(145,10,170,10);
		$pdf->SetX(174);
		$pdf->Cell(70,10,$debito,0,0,'L');

	}
    $pdf->SetFont('Arial','B',10);
    //Movernos a la derecha
    //Fecha
	$pdf->SetY(17); // $pdf->SetY(15);
	$pdf->Cell(130);
	$pdf->Cell(40,10,$fecha,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	
	// Datos del Cliente
	$pdf->SetY(53); // $pdf->SetY(50);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente);
	//$pdf->Cell(70,10,$nom_cliente." ".$ptcomp,0,0,'L');
	$pdf->SetY(58); // $pdf->SetY(55);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') '.$nomloc,0,0,'L');
	
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	//$pdf->Ln(9);
	$pdf->SetY(65); // $pdf->SetY(62);
	$pdf->Cell(18);
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
	$pdf->Cell(130);
	$pdf->Cell(10,10,$cuit_cliente,0,0,'L');
	//$pdf->Ln(10);
	$pdf->SetY(71); // $pdf->SetY(68);
	$pdf->SetX(40);
	$pdf->Cell(30);
	$pdf->Cell(20,10,'CONTADO',0,0,'L');
	$pdf->Cell(116);
	$pdf->SetX(170);
	$pdf->Cell(30,10,$tel_cliente,0,0,'L');
	$pdf->SetX(15);
	$pdf->SetY(85); // $pdf->SetY(82);

	if($remate!="") {
		$pdf->Cell(30,10,$remate_direc." ,".$nomlocrem." ,".$nomprovrem,0,0,'L');
		$pdf->Cell(140);
		$pdf->Cell(20,10,$remate_fecha,0,0,'L');
	}
   	//Salto de l�nea
	//Posici�n de los t�tulos de los renglones, en este caso no va
	$Y_Fields_Name_position = 90;
	//Posici�n del primer rengl�n 
	$Y_Table_Position = 102; //$Y_Table_Position = 100;
	//Los t�tulos de las columnas no los debo poner
	//Aca van los datos de las columnas

	$j = $Y_Table_Position;
	$pdf->SetY($Y_Table_Position);

	$pdf->SetFont('Arial','B',11);
	$pdf->SetY($j);
	$pdf->SetX(5);

	// C�digo interno de Lote
	$pdf->MultiCell(12,9,$df_codintlote,0,'L');
	$pdf->SetY($j);
	$pdf->SetX(15);

	// Descripci�n del lote en uno o dos renglones
	if (isset($df_descrip2)) {
		$pdf->SetX(25);
		$pdf->MultiCell(150,9,$df_descrip1,0,'L');
		$pdf->SetY($j+4);
		$pdf->SetX(25);
		$pdf->MultiCell(150,9,$df_descrip2,0,'L');
		$pdf->SetY($j);
	}
	else{
		$pdf->MultiCell(150,9,$df_descrip1,0,'L');
		$pdf->SetY($j);
	}
	$pdf->SetX(155);
	$pdf->MultiCell(44,9,$df_importe,0,'R');

	// ACA VA EL PIE
	//Posici�n: a 64 mm cm del final
 	$pdf->SetY(-60); // $pdf->SetY(-60);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,4,$leyenda1,0,0,'L');
 	$pdf->SetY(-56); // $pdf->SetY(-56);
   	$pdf->Cell(100,4,$leyenda2,0,0,'L');
 	$pdf->SetY(-52); // $pdf->SetY(-52);
   	$pdf->Cell(100,4,$leyenda3,0,0,'L');
	$pdf->SetY(-48); // $pdf->SetY(-48);
   	$pdf->Cell(100,4,$leyenda4,0,0,'L');
	$pdf->SetY(-44); // $pdf->SetY(-44);
   	$pdf->Cell(100,4,$leyenda5,0,0,'L');
	$pdf->SetY(-40); // $pdf->SetY(-40);
   	$pdf->Cell(100,4,$leyenda6,0,0,'L');
	$pdf->SetY(-36); // $pdf->SetY(-36);
	$pdf->Cell(100,4,$leyenda7,0,0,'L');
	$pdf->SetY(-32); // $pdf->SetY(-32);
	$pdf->Cell(100,4,$leyenda8,0,0,'L');
	$pdf->SetY(-30); // $pdf->SetY(-25);
   	//Arial italic 8
   	$pdf->SetFont('Arial','I',8);
   	if ($pserie==1 OR $pserie==5 OR $pserie==26) {	
   		$pdf->Cell(0,8,'                      21 %                              10,5 %                            10%                          21 %                             10,5 %',0,0,'L');
   		$pdf->SetY(-22); // $pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);
		
		// ACA VAN LOS CAMPOS UNO A UNO EN EL CASILLERO QUE LES CORRESPONDE
		$pdf->SetX(10);
		$pdf->Cell(15,8,$tot_neto21,0,0,'R');
		$pdf->SetX(40);
		$pdf->Cell(15,8,$tot_neto105,0,0,'R');
		$pdf->SetX(70);
		$pdf->Cell(15,8,$tot_comision,0,0,'R');
		$pdf->SetX(100);
		$pdf->Cell(15,8,$tot_iva21,0,0,'R');
		$pdf->SetX(130);
		$pdf->Cell(15,8,$tot_iva105,0,0,'R');
		$pdf->SetX(160);
		$pdf->Cell(15,8,$tot_resol,0,0,'R');
		$pdf->SetX(190);
		$pdf->Cell(15,8,$total,0,0,'R');
	}
	else {
		$pdf->Cell(0,8,'                               21 %                                                         10,5 %                                                     10%                                                    ',0,0,'L');
		$pdf->SetY(-22); // $pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);		

		// ACA VAN LOS CAMPOS UNO A UNO EN EL CASILLERO QUE LES CORRESPONDE
		$pdf->SetX(20);
		$pdf->Cell(15,8,$totalneto21,0,0,'R');
		$pdf->SetX(80);
		$pdf->Cell(15,8,$totalneto105,0,0,'R');
		$pdf->SetX(135);
		$pdf->Cell(15,8,$tot_comision,0,0,'R');
		$pdf->SetX(190);
		$pdf->Cell(15,8,$total,0,0,'R');
	
	}
} else {
	$pdf=new FPDF();
	
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(1 , 2) ;
	
	   	$pdf->SetLineWidth(.2);
   	$pdf->Line(7,7.5,203,7.5);
	$pdf->Line(7,7.5,7,290);
	$pdf->Line(7,290,203,290);
	$pdf->Line(203,7.5,203,290);
	$pdf->Line(7,50,203,50);
	$pdf->Line(7,95,203,95);
	$pdf->Line(7,100,203,100);
	$pdf->Line(25,95,25,246);
	$pdf->Line(175,95,175,246); //
	$pdf->Line(32,260,32,275);
	$pdf->Line(62,260,62,275);
	$pdf->Line(90,260,90,275);
	$pdf->Line(122,260,122,275);
	$pdf->Line(154,260,154,275);
	$pdf->Line(175,260,175,275);
	$pdf->Line(7,246,203,246);
	$pdf->Line(7,260,203,260);
	$pdf->Line(7,267,203,267);
	$pdf->Line(7,275,203,275);
	$pdf->Line(7,76,203,76);
	$pdf->Line(107,7.5,107,50);
   	$pdf->SetFont('Arial','B',14);
	$pdf->SetY(8);
	$pdf->SetX(150);
	$pdf->Cell(160,10,'FACTURA');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(15);
	$pdf->SetX(130);
   	$pdf->Cell(160,10,'Fecha :');
	$pdf->SetY(23);
	$pdf->SetX(130);
   	$pdf->Cell(150,10,'N� :  '.$nro_doc);
	$pdf->Cell(150,10,'Fecha :');
	$pdf->SetY(32);
	$pdf->SetX(120);
   	$pdf->Cell(150,10,'C.U.I.T : 30-71018343-7');
	$pdf->SetY(37);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Ing. Brutos C.M. : 901-265134-1');
	$pdf->SetY(42);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Fecha de Inicio de Actividades : 01/07/2007');
	$pdf->SetY(48);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Se�or/es:');
	$pdf->SetY(54);
	$pdf->SetX(10);
	$pdf->Cell(10,10,'Domicilio:');
	$pdf->SetX(125);
	$pdf->Cell(10,10,'Localidad:');
	$pdf->SetY(60);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'IVA:');
	$pdf->SetY(60);
	$pdf->SetX(125);
	$pdf->Cell(150,10,'CUIT:'.$cuit_cliente);
	$pdf->SetY(66);
	$pdf->SetY(66);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Telefono:  '.$tel_cliente);
   	//Movernos a la derecha
    
   	//Fecha
	$pdf->SetY(15);
	$pdf->Cell(130);
	$pdf->Image('images/logo_adrian.jpg',10,8);
	$pdf->SetY(23);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'Av. Alicia Moreau de Justo 1080 Piso 4 Of. 198');
	$pdf->SetY(26);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'CABA  (C1107AAP) Tel/Fax: (011)  3984-7400');
	$pdf->SetY(32);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'       IVA  RESPONSABLE  INSCRIPTO');
	
	$pdf->Image('images/tipo_a.gif',100,8);


	
	
	// Imprimo la cabecera
    // Nota Debito
    if ($ptcomp==21 || $ptcomp==22 || $ptcomp==29  || $ptcomp==30 || $ptcomp==48 || $ptcomp==49) {
		$pdf->SetY(6);
		$nota = 'NOTA ';
		$debito= 'DEBITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota,0,0,'L');
		$pdf->SetLineWidth(5);
		$pdf->Line(145,10,170,10);
		$pdf->SetX(174);
		$pdf->Cell(70,10,$debito,0,0,'L');
	}
	if ($ptcomp==5 || $ptcomp==7 || $ptcomp==25  || $ptcomp==26 || $ptcomp==46 || $ptcomp==47) {
		$pdf->SetY(6);
		$nota = 'NOTA ';
		$debito= 'CREDITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota,0,0,'L');
		$pdf->SetLineWidth(5);
		$pdf->Line(145,10,170,10);
		$pdf->SetX(174);
		$pdf->Cell(70,10,$debito,0,0,'L');

	}
   	$pdf->SetFont('Arial','B',10);
   	//Movernos a la derecha
   	//Fecha
	$pdf->SetY(15); // $pdf->SetY(15);
	$pdf->Cell(135);
   	$pdf->Cell(40,10,$fecha,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	
	// Datos del Cliente
	$pdf->SetY(48); // $pdf->SetY(48);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
	$pdf->SetY(54); // $pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') '.$nomloc,0,0,'L');
	
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	//$pdf->Ln(9);
	$pdf->SetY(60); // $pdf->SetY(63);
	$pdf->Cell(18);
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
	
	//$pdf->Ln(10);
	$pdf->SetY(68);// $pdf->SetY(68);
	$pdf->SetX(40);
	$pdf->Cell(30);
	$pdf->Cell(20,10,'CONTADO',0,0,'L');
	$pdf->Cell(116);
	$pdf->SetX(170);
	//$pdf->Cell(30,10,$tel_cliente,0,0,'L');
	$pdf->SetX(15);
	$pdf->SetY(85); // $pdf->SetY(82);
	if ($remate!="") {
		$pdf->SetX(15);
		$pdf->SetY(73); // $pdf->SetY(82);
		$pdf->Cell(20,10,'Por el remate efectuado en :',0,0,'L');
		$pdf->SetX(15);
		$pdf->SetY(80); // $pdf->SetY(82);
		$pdf->Cell(30,10,$remate_direc." ,".$nomlocrem." ,".$nomprovrem,0,0,'L');
		$pdf->Cell(130);
		$pdf->SetX(135);
		$pdf->Cell(20,10,'De fecha: '.$remate_fecha,0,0,'L');
	}
   	//Salto de l�nea
	$pdf->SetY(93);
	$pdf->SetX(5);
	$pdf->Cell(20,10,'       LOTE                                                          DESCRIPCION                                                                                NETO',0,0,'L');
	//Posici�n de los t�tulos de los renglones, en este caso no va
	$Y_Fields_Name_position = 90;
	//Posici�n del primer rengl�n 
	$Y_Table_Position = 102;// $Y_Table_Position = 100;

	//Los t�tulos de las columnas no los debo poner
	//Aca van los datos de las columnas
	$j = $Y_Table_Position;
	$pdf->SetY($Y_Table_Position);

	$pdf->SetFont('Arial','B',11);
	$pdf->SetY($j);
	$pdf->SetX(15);

	// C�digo interno de Lote
	$pdf->MultiCell(12,9,$df_codintlote,0,'L');
	$pdf->SetY($j);
	$pdf->SetX(27);

	// Descripci�n del lote en uno o dos renglones
	if (isset($df_descrip2)) {
		$pdf->SetX(27);
		$pdf->MultiCell(150,9,$df_descrip1,0,'L');
		$pdf->SetY($j+4);
		$pdf->SetX(27);
		$pdf->MultiCell(150,9,$df_descrip2,0,'L');
		$pdf->SetY($j);
	}
	else{
		$pdf->MultiCell(150,9,$df_descrip1,0,'L');
		$pdf->SetY($j);
	}
	$pdf->SetX(155);
	$pdf->MultiCell(44,9,$df_importe,0,'R');

	// ACA VA EL PIE
	//Posici�n: a 64 mm cm del final
 	$pdf->SetY(-50); // $pdf->SetY(-60);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,4,$leyenda1,0,0,'L');
 	$pdf->SetY(-46); // $pdf->SetY(-56);
   	$pdf->Cell(100,4,$leyenda2,0,0,'L');
 	$pdf->SetY(-42); // $pdf->SetY(-52);
   	$pdf->Cell(100,4,$leyenda3,0,0,'L');
	//$pdf->SetY(-38); // $pdf->SetY(-48);
   	//$pdf->Cell(100,4,$leyenda4,0,0,'L');
	//$pdf->SetY(-44); // $pdf->SetY(-44);
   	//$pdf->Cell(100,4,$leyenda5,0,0,'L');
	//$pdf->SetY(-40); // $pdf->SetY(-40);
   	//$pdf->Cell(100,4,$leyenda6,0,0,'L');
	//$pdf->SetY(-36);// $pdf->SetY(-36);
	//$pdf->Cell(100,4,$leyenda7,0,0,'L');
	//$pdf->SetY(-32); // $pdf->SetY(-32);
	//$pdf->Cell(100,4,$leyenda8,0,0,'L');

	$pdf->SetY(-38); // $pdf->SetY(-25);
   	//Arial italic 8
   	$pdf->SetFont('Arial','I',8);
   	if ($pserie==1 OR $pserie==5 OR $pserie==26) {	
		$total = $tot_neto21 + $tot_neto105 + $tot_comision+$tot_iva21+$tot_iva105;
		$tot_neto21   = number_format($tot_neto21, 2, ',','.');
		$tot_neto105  = number_format($tot_neto105, 2, ',','.');
		$tot_comision = number_format($tot_comision, 2, ',','.');
		$tot_iva21    = number_format($tot_iva21, 2, ',','.');
		$tot_iva105   = number_format($tot_iva105, 2, ',','.');
		$tot_resol    = number_format($tot_resol, 2, ',','.');
		$total        = number_format($total, 2, ',','.');
		//Porcentajes
		// tipo de comprobante 
		// FActura A
    
   		$pdf->Cell(0,8,' Neto 21 %                         Neto 10,5 %              Comisi�n 10%                   IVA 21 %                         IVA 10,5 %              RG 3337                       Total',0,0,'L');
   		$pdf->SetY(-30); // $pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);

		// ACA VAN LOS CAMPOS UNO A UNO EN EL CASILLERO QUE LES CORRESPONDE
		$pdf->SetX(14);
		$pdf->Cell(15,8,$tot_neto21,0,0,'R');
		$pdf->SetX(40);
		$pdf->Cell(15,8,$tot_neto105,0,0,'R');
		$pdf->SetX(72);
		$pdf->Cell(15,8,$tot_comision,0,0,'R');
		$pdf->SetX(102);
		$pdf->Cell(15,8,$tot_iva21,0,0,'R');
		$pdf->SetX(135);
		$pdf->Cell(15,8,$tot_iva105,0,0,'R');
		$pdf->SetX(155);
		$pdf->Cell(15,8,$tot_resol,0,0,'R');
		$pdf->SetX(184);
		$pdf->Cell(15,8,$total,0,0,'R');
	}
	else {
		$totalneto21  = $tot_neto21*1.21;
		$totalneto105 = $tot_neto105+$tot_iva105;
		$tot_comision = $tot_comision*1.21;
   		$total = $totalneto21 + $totalneto105 + $tot_comision ;
		$totalneto21  = number_format($totalneto21, 2, ',','.');
		$totalneto105 = number_format($totalneto105, 2, ',','.');
		$tot_neto21   = number_format($tot_neto21, 2, ',','.');
		$tot_neto105  = number_format($tot_neto105, 2, ',','.');
		$tot_comision = number_format($tot_comision, 2, ',','.');
		$tot_iva21    = number_format($tot_iva21, 2, ',','.');
		$tot_iva105   = number_format($tot_iva105, 2, ',','.');
		$tot_resol    = number_format($tot_resol, 2, ',','.');
		$total        = number_format($total, 2, ',','.');
		$pdf->Cell(0,8,'                               21 %                                                         10,5 %                                                     10%                                                    ',0,0,'L');
   	 	$pdf->SetY(-32); // $pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);

		// ACA VAN LOS CAMPOS UNO A UNO EN EL CASILLERO QUE LES CORRESPONDE
		$pdf->SetX(20);
		$pdf->Cell(15,8,$totalneto21,0,0,'R');
		$pdf->SetX(80);
		$pdf->Cell(15,8,$totalneto105,0,0,'R');
		$pdf->SetX(135);
		$pdf->Cell(15,8,$tot_comision,0,0,'R');
		$pdf->SetX(190);
		$pdf->Cell(15,8,$total,0,0,'R');
	
	}
	
	$CAEFchVto2 = substr($CAEFchVto,8,2)."-".substr($CAEFchVto,5,2)."-".substr($CAEFchVto,0,4);
	$pdf->SetY(-19); // $pdf->SetY(-20);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(70,10,'CAE N�:  '.$CAE.'   Fecha Vto. CAE: '.$CAEFchVto2,0,0,'L');
} // LUEGO DE DESCOMENTAR EL RESTO, SACAR ESTA LLAVE 26/06/2015
	/*
	
	
	// ============================================= FACTURA DUPLICADO ===================================================================
	//Creo el PDF file
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(1 , 2) ;
	// Imprimo la cabecera
	// Nota Debito
    	if ($ptcomp==21 || $ptcomp==22 || $ptcomp==29  || $ptcomp==30 || $ptcomp==48 || $ptcomp==49) {
		$pdf->SetY(6);
		$nota = 'NOTA ';
		$debito= 'DEBITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota,0,0,'L');
		$pdf->SetLineWidth(5);
		$pdf->Line(145,10,170,10);
		$pdf->SetX(174);
		$pdf->Cell(70,10,$debito,0,0,'L');

	}
	if ($ptcomp==5 || $ptcomp==7 || $ptcomp==25  || $ptcomp==26 || $ptcomp==46 || $ptcomp==47) {
		$pdf->SetY(6);
		$nota = 'NOTA ';
		$debito= 'CREDITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota,0,0,'L');
		$pdf->SetLineWidth(5);
		$pdf->Line(145,10,170,10);
		$pdf->SetX(174);
		$pdf->Cell(70,10,$debito,0,0,'L');

	}
   	$pdf->SetFont('Arial','B',10);
   	//Fecha
	$pdf->SetY(17); // $pdf->SetY(15);
	$pdf->Cell(130);
   	$pdf->Cell(40,10,$fecha,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	
	// Datos del Cliente
	$pdf->SetY(50); // $pdf->SetY(47);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
	$pdf->SetY(56); // $pdf->SetY(53);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') '.$nomloc,0,0,'L');
	
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(65); // $pdf->SetY(62);
	$pdf->Cell(18);
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
	$pdf->Cell(130);
	$pdf->Cell(10,10,$cuit_cliente,0,0,'L');
	$pdf->SetY(71); // $pdf->SetY(68);
	$pdf->SetX(40);
	$pdf->Cell(30);
	$pdf->Cell(20,10,'CONTADO',0,0,'L');
	$pdf->Cell(116);
	$pdf->SetX(170);
	$pdf->Cell(30,10,$tel_cliente,0,0,'L');
	$pdf->SetX(15);
	$pdf->SetY(85); // $pdf->SetY(82);
	if ($remate!="") {
		$pdf->Cell(30,10,$remate_direc." ,".$nomlocrem." ,".$nomprovrem,0,0,'L');
		$pdf->Cell(140);
		$pdf->Cell(20,10,$remate_fecha,0,0,'L');
	}
   	//Salto de l�nea
 	//Posici�n de los t�tulos de los renglones, en este caso no va
	$Y_Fields_Name_position = 90;
	//Posici�n del primer rengl�n 
	$Y_Table_Position = 102; // $Y_Table_Position = 100;
	//Los t�tulos de las columnas no los debo poner
	//Aca van los datos de las columnas

	$j = $Y_Table_Position;
	$pdf->SetY($Y_Table_Position);
	$pdf->SetFont('Arial','B',11);
	$pdf->SetY($j);
	$pdf->SetX(5);

	// C�digo interno de Lote
	$pdf->MultiCell(12,9,$df_codintlote,0,'L');
	$pdf->SetY($j);
	$pdf->SetX(15);

	// Descripci�n del lote en uno o dos renglones
	if (isset($df_descrip2)) {
		$pdf->SetX(25);
		$pdf->MultiCell(150,9,$df_descrip1,0,'L');
		$pdf->SetY($j+4);
		$pdf->SetX(25);
		$pdf->MultiCell(150,9,$df_descrip2,0,'L');
		$pdf->SetY($j);
	}
	else{
		$pdf->MultiCell(150,9,$df_descrip1,0,'L');
		$pdf->SetY($j);
	}
	$pdf->SetX(155);
	$pdf->MultiCell(44,9,$df_importe,0,'R');

	// ACA VA EL PIE
 	//Posici�n: a 64 mm cm del final
 	$pdf->SetY(-60); // $pdf->SetY(-60);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(100,4,$leyenda1,0,0,'L');
 	$pdf->SetY(-56); // $pdf->SetY(-56);
   	$pdf->Cell(100,4,$leyenda2,0,0,'L');
 	$pdf->SetY(-52); // $pdf->SetY(-52);
   	$pdf->Cell(100,4,$leyenda3,0,0,'L');
	$pdf->SetY(-48); // $pdf->SetY(-48);
   	$pdf->Cell(100,4,$leyenda4,0,0,'L');
	$pdf->SetY(-44); // $pdf->SetY(-44);
   	$pdf->Cell(100,4,$leyenda5,0,0,'L');
	$pdf->SetY(-40); // $pdf->SetY(-40);
   	$pdf->Cell(100,4,$leyenda6,0,0,'L');
	$pdf->SetY(-36); // $pdf->SetY(-36);
	$pdf->Cell(100,4,$leyenda7,0,0,'L');
	$pdf->SetY(-32); // $pdf->SetY(-32);
	$pdf->Cell(100,4,$leyenda8,0,0,'L');
	$pdf->SetY(-30); // $pdf->SetY(-25);
   	//Arial italic 8
   	$pdf->SetFont('Arial','I',8);
   	if ($pserie==1 OR $pserie==5 OR $pserie==26) {	

		//Porcentajes
		// tipo de comprobante 
		// FActura A
  		$pdf->Cell(0,8,'        21 %                              10,5 %                            10%                                21 %                             10,5 %',0,0,'L');
    	$pdf->SetY(-22); // $pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);

		// ACA VAN LOS CAMPOS UNO A UNO EN EL CASILLERO QUE LES CORRESPONDE
		$pdf->SetX(10);
		$pdf->Cell(15,8,$tot_neto21,0,0,'R');
		$pdf->SetX(40);
		$pdf->Cell(15,8,$tot_neto105,0,0,'R');
		$pdf->SetX(70);
		$pdf->Cell(15,8,$tot_comision,0,0,'R');
		$pdf->SetX(100);
		$pdf->Cell(15,8,$tot_iva21,0,0,'R');
		$pdf->SetX(130);
		$pdf->Cell(15,8,$tot_iva105,0,0,'R');
		$pdf->SetX(160);
		$pdf->Cell(15,8,$tot_resol,0,0,'R');
		$pdf->SetX(190);
		$pdf->Cell(15,8,$total,0,0,'R');
	}
	else {
	
		$pdf->Cell(0,8,'                               21 %                                                         10,5 %                                                     10%                                                    ',0,0,'L');
   	 	$pdf->SetY(-22); // $pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);

		// ACA VAN LOS CAMPOS UNO A UNO EN EL CASILLERO QUE LES CORRESPONDE
		$pdf->SetX(20);
		$pdf->Cell(15,8,$totalneto21,0,0,'R');
		$pdf->SetX(80);
		$pdf->Cell(15,8,$totalneto105,0,0,'R');
		$pdf->SetX(135);
		$pdf->Cell(15,8,$tot_comision,0,0,'R');
		$pdf->SetX(190);
		$pdf->Cell(15,8,$total,0,0,'R');
	}
	
	$query_mascara ="SELECT * FROM series WHERE codnum = '$pserie'";
	$detallemasc = mysqli_query($amercado, $query_mascara) or die(mysqli_error($amercado));
	$totalRows_detallemasc = mysqli_num_rows($detallemasc);
	$row_detallemasc = mysqli_fetch_assoc($detallemasc);
	$mascara      = $row_detallemasc['mascara'];

	$query_recibos = "SELECT * FROM series  WHERE  series.codnum=3";
	$recibos = mysqli_query($amercado, $query_recibos) or die(mysqli_error($amercado));
	$row_recibos = mysqli_fetch_assoc($recibos);
	$totalRows_recibos = mysqli_num_rows($recibos);
	$recibonum = ($row_recibos['nroact'])+1;

	// Actualiza serie
	$actualiza1 = sprintf("UPDATE `series` SET `nroact` ='$recibonum' WHERE series.codnum=3") ;				 
	$resultado=mysqli_query(	$actualiza1, $amercado);	
	// Leo los renglones

	$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s" , $ptcomp, $pserie, $pncomp);
	$detallefac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
	//$row_detallefac = mysqli_fetch_assoc($detallefac);
	$totalRows_detallefac = mysqli_num_rows($detallefac);

	// Leo la cabecera
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $ptcomp, $pserie, $pncomp);
	$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));
	$row_cabecerafac = mysqli_fetch_assoc($cabecerafac);

	$fecha        = $row_cabecerafac["fecdoc"];
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
	$fac_num       = $row_cabecerafac["ncomp"];

	if ($fac_num<10) {
		$num_fac="0000000".$fac_num;
	}

	if ($fac_num>9 && $fac_num<99) {
		$num_fac="000000".$fac_num;
	}

	if ($fac_num>99 && $fac_num<999) {
		$num_fac="00000".$fac_num;
	}
	if ($fac_num>999 && $fac_num<9999) {
		$num_fac="0000".$fac_num;
	}
	// Leo la leyenda asociada a la factura y corto la descripcion en 5 renglones de 100 caracteres c/u
	$query_leyenda = sprintf("SELECT * FROM factley WHERE tcomp = %s AND serie = %s AND ncomp = %s", $ptcomp, $pserie, $pncomp);
	$cabeleyenda = mysqli_query($amercado, $query_leyenda) or die(mysqli_error($amercado));
	$row_cabeleyenda = mysqli_fetch_assoc($cabeleyenda);
	$leyenda1      = substr($row_cabeleyenda["leyendafc"],0,120);
	$leyenda2	   = substr($row_cabeleyenda["leyendafc"],120,120);
	$leyenda3	   = substr($row_cabeleyenda["leyendafc"],240,120);
	$leyenda4	   = substr($row_cabeleyenda["leyendafc"],360,120);
	$leyenda5	   = substr($row_cabeleyenda["leyendafc"],480,120);
	$leyenda6	   = substr($row_cabeleyenda["leyendafc"],600,120);
	$leyenda7	   = substr($row_cabeleyenda["leyendafc"],720,120);
	$leyenda8	   = substr($row_cabeleyenda["leyendafc"],840,120);

	// FORMAS DE PAGO
	if($remate!="") {
		// Leo el remate
		$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $remate);
		$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
		$row_remates = mysqli_fetch_assoc($remates);
		$remate_ncomp = $row_remates["ncomp"];
		$remate_direc = $row_remates["direccion"];
		$remate_fecha = $row_remates["fecreal"];
		$remate_fecha = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		// Leo el cliente
	}
	//Leo si hay direccion de exposicion
   	$query_remate_expo = sprintf("SELECT * FROM dir_remates WHERE codrem = %s", $remate);
   	$remates_expo = mysqli_query($amercado, $query_remate_expo) or die(mysqli_error($amercado));
	$totalFilas    =    mysqli_num_rows($remates_expo);
	if ($totalFilas != 0) {
   		$row_remates_expo = mysqli_fetch_assoc($remates_expo);
		$remate_ncomp = $row_remates_expo["codrem"];
		$remate_direc = $row_remates_expo["direccion"];
		$loc_remate   = $row_remates_expo["codloc"];
		$prov_remate  = $row_remates_expo["codprov"];
	}
	
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

	// Leo la localidad
	$query_localidades = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_cliente);
	$localidad = mysqli_query($amercado, $query_localidades) or die(mysqli_error($amercado));
	$row_localidades = mysqli_fetch_assoc($localidad);
	$nomloc = $row_localidades["descripcion"];

	//Inicializo los datos de las columnas de lotes

	$df_neto       = "";
	$df_importe    = "";
	
	// DESDE ACA LAS FORMAS DE PAGO
	mysqli_select_db($amercado, $database_amercado);
	$query_formasdepago = "SELECT * FROM cartvalores WHERE cartvalores.ncomprel='$pncomp' AND cartvalores.serierel ='$pserie' AND cartvalores.tcomprel ='$ptcomp'";

	$formasdepago = mysqli_query($amercado, $query_formasdepago) or die(mysqli_error($amercado));
	$row_formasdepago = mysqli_fetch_assoc($formasdepago); // VER SI HAY QUE SACARLO DESPUES =================================
	// Ingreso de CABECERA DEL RECIBO 
	mysqli_query("INSERT cabrecibo (tcomp ,serie,ncomp,cantcbtes, fecha ,cliente,imptot , emitido) VALUES ('2','3','$recibonum','1','$fecharem','$cliente','$total',1)",$amercado);
	
	// Ingreso de DETALLES DEL RECIBO
	mysqli_query("INSERT detrecibo  (tcomp ,serie,ncomp,nreng, tcomprel ,serierel,ncomprel , netocbterel) VALUES ('2','3','$recibonum','1','$ptcomp','$pserie','$pncomp','$total')",$amercado);

	$efectivo    = 0 ;
	$cheques     = 0;
	$depositos   = 0;
	$retenciones = 0;

	do { 
		$tipo_comp =  $row_formasdepago['tcomp']; 
		$serie =   $row_formasdepago['serie']; 
		$importe =$row_formasdepago['importe']; 

		// Cheques de Tercero y propios 
		if ( $serie=='6' OR  $serie=='10') {
			$cheques = $cheques+$importe;
		}
		// Efectivo
		if ($serie=='8' OR $serie=='14') {
			$efectivo = $efectivo+$importe;
		}
		// Depositos 
        	if ($serie=='7' OR  $serie=='15') {
			$depositos = $depositos+$importe;
		}
		// Retenciones
        	if ( $serie=='22' OR   $serie=='23' OR  $serie=='24' OR  $serie=='25'){
	 		$retenciones = $retenciones+$importe;
		}
		
		  
	} while ($row_formasdepago = mysqli_fetch_assoc($formasdepago)); 
	mysql_free_result($formasdepago);

	//Logo

	//Creo el PDF file
	// ORIGINAL
	//$pdf=new FPDF();
	
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(1 , 2) ;
	
	// Imprimo la cabecera
   	// Linea de arriba
   	$pdf->SetLineWidth(.2);
   	$pdf->Line(9,7.5,200,7.5);
	$pdf->Line(9,7.5,9,280);
	$pdf->Line(9,280,200,280);
	$pdf->Line(200,7.5,200,280);
	$pdf->Line(9,50,200,50);
	$pdf->Line(9,100,200,100);
	$pdf->Line(9,200,200,200);
	$pdf->Line(9,270,200,270);
	$pdf->Line(9,76,200,76);
	$pdf->Line(107,7.5,107,50);
   	$pdf->SetFont('Arial','B',14);
	$pdf->SetY(8);
	$pdf->SetX(150);
	$pdf->Cell(160,10,'RECIBO');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(15);
	$pdf->SetX(130);
   	$pdf->Cell(160,10,'Fecha :');
	$pdf->SetY(23);
	$pdf->SetX(130);
   	$pdf->Cell(150,10,'N� :');
	$pdf->Cell(150,10,'Fecha :');
	$pdf->SetY(32);
	$pdf->SetX(120);
   	$pdf->Cell(150,10,'C.U.I.T : 30-71018343-7');
	$pdf->SetY(37);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Ing. Brutos C.M : 901-265134-1');
	$pdf->SetY(42);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Fecha de Inicio a Actividades : 01/07/2007');
	$pdf->SetY(48);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Se�or/es:');
	$pdf->SetY(54);
	$pdf->SetX(10);
	$pdf->Cell(10,10,'Domicilio:');
	$pdf->SetX(125);
	$pdf->Cell(10,10,'Localidad:');
	$pdf->SetY(60);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'IVA:');
	$pdf->SetY(60);
	$pdf->SetX(125);
	$pdf->Cell(150,10,'CUIT:'.$cuit_cliente);
	$pdf->SetY(66);
	$pdf->SetY(66);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Telefono:'.$tel_cliente);
   	//Movernos a la derecha
    
   	//Fecha
	$pdf->SetY(15);
	$pdf->Cell(130);
	$pdf->Image('images/logo_adrian.jpg',10,8);
	$pdf->Image('images/equis.jpg',100,8);

   	//Arial bold 15
   	$pdf->SetFont('Arial','B',15);
   	//Movernos a la derecha
   	$pdf->Cell(80);
   	//T�tulo
   	// $pdf->Cell(30,10,'Title',1,0,'C');
   	//Salto de l�nea
   	$pdf->Ln(20);
	$pdf->SetY(15);
	$pdf->SetX(150);
	// ACA ESTA EL BOLONQUI DE LA FECHA
	$fecharem = "21/11/2014";
	// D�a del mes con 2 d�gitos, y con ceros iniciales, de 01 a 31
 	$dia = date("d");
	// Mes actual en 2 d�gitos y con 0 en caso del 1 al 9, de 1 a 12
	$mes = date("m");
	// A�o actual con 4 d�gitos, ej 2013
 	$anio = date("Y");
	$fecharem = $dia."/".$mes."/".$anio;
	
   	$pdf->Cell(40,10,$fecharem,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	// Nuemro de Remito
	$pdf->SetY(23);
	$pdf->SetX(150);
   	$pdf->Cell(40,10,$recibonum ,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	// Datos del Cliente
	$pdf->SetY(48);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') ',0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->SetX(150);
	$pdf->Cell(70,10,$nomloc,0,0,'L');
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(60);
	$pdf->Cell(18);
	// Poner del Tipo de Impuesto 
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
	$pdf->SetX(130);
	$pdf->SetY(66);
	$pdf->Cell(30);
	$pdf->Cell(116);
	$pdf->SetX(29);
	
	$pdf->SetX(15);
	$pdf->SetY(76);

	$aux = (string) $totalletras;
	$decimal = substr( $aux, strpos( $aux, "." ) );
	
	$entero = ((($totalletras * 100 )- $decimal ) / 100);
	$r = ($totalletras - $entero) * 100;
	if ($entero % 1000  != 0) {
		$letras = convertir_a_letras($totalletras);
	}
	else {
		$totalletras = ($totalletras - $r) / 1000;
		//echo "totalletras  despues de dividir = ".$totalletras."<BR>";
		$letras = convertir_a_letras($totalletras);
		$letras = $letras." mil";
		if ($r != 0) {
			$decimales = convertir_a_letras($r);
			if (strcmp($decimales, "uno centavos")==0)
				$decimales = "un centavo";
			$letras = $letras." con ".$decimales;
		}
	}
	
	$total        = number_format($total, 2, ',','.');
	$pdf->Cell(20,10,'Recib� la cantidad de pesos : '.$letras,0,0,'L');		
	$pdf->SetX(15);
	$pdf->SetY(89);
	$pdf->Cell(20,10,'en concepto de cancelaci�n de facturas N�:  '.$mascara."-".$num_fac ,0,0,'L');	
	$pdf->SetX(15);
	$pdf->SetY(102);
	$pdf->Cell(20,10,'Forma de Pago :',0,0,'L');		
	$pdf->SetX(15);
	$pdf->SetY(122);
	if ($efectivo!=0.0) {
		$efectivo= number_format($efectivo, 2, ',','.');
		$pdf->Cell(20,10,'Efectivo:---------------------------------------------------'.$efectivo,0,0,'L');	
	} else {
		$pdf->Cell(20,10,'Efectivo:',0,0,'L');	
	}
	$pdf->SetY(142);
	if ($cheques!=0) {
		$cheques= number_format($cheques, 2, ',','.');
	
		$pdf->Cell(20,10,'Cheques :---------------------------------------------------'.$cheques,0,0,'L');	
	} else {	
		$pdf->Cell(20,10,'Cheques :',0,0,'L');	
	}
	$pdf->SetX(15);
	$pdf->SetY(162);
	if ($depositos!=0) {
		$depositos= number_format($depositos, 2, ',','.');
		$pdf->Cell(20,10,'Dep�sitos:--------------------------------------------------'.$depositos,0,0,'L');
	} else {
		$pdf->Cell(20,10,'Dep�sitos:',0,0,'L');	
   	}
	$pdf->SetY(182);

	if ($retenciones!=0) {
		$retenciones = number_format($retenciones, 2, ',','.');
		$pdf->Cell(20,10,'Retenciones :-------------------------------------------- '.$retenciones,0,0,'L');	
    } else {	
		$pdf->Cell(20,10,'Retenciones :',0,0,'L');		
	}

	$pdf->SetX(15);
	$pdf->SetY(202);
	$pdf->Cell(20,10,'Detalles de Pago:',0,0,'L');	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(210);
	$pdf->Cell(100,4,$leyenda1,0,0,'L');
	$pdf->SetY(214);
	$pdf->Cell(100,4,$leyenda2,0,0,'L');
	$pdf->SetY(218);
	$pdf->Cell(100,4,$leyenda3,0,0,'L');
	$pdf->SetY(222);
	$pdf->Cell(100,4,$leyenda4,0,0,'L');
	$pdf->SetY(226);
	$pdf->Cell(100,4,$leyenda5,0,0,'L');
	$pdf->SetY(230);
	$pdf->Cell(100,4,$leyenda6,0,0,'L');
	$pdf->SetY(234);
	$pdf->Cell(100,4,$leyenda7,0,0,'L');
	$pdf->SetY(238);
	$pdf->Cell(100,4,$leyenda8,0,0,'L');
	$pdf->SetY(270);
	$pdf->Cell(20,10,'Total: Pesos',0,0,'L');
	$pdf->SetX(50);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,8,$total.'  ',0,0,'L');
	$pdf->SetY(280);
   	$pdf->SetX(95);
	$pdf->Cell(20,10,'ORIGINAL',0,0,'C');
   	//Salto de l�nea
	//Posici�n de los t�tulos de los renglones, en este caso no va
	$Y_Fields_Name_position = 90;
	//Posici�n del primer rengl�n 
	$Y_Table_Position = 100;

	//Los t�tulos de las columnas no los debo poner
	//Aca van los datos de las columnas
	$j = $Y_Table_Position;
	$pdf->SetY($Y_Table_Position);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetY($j);
	$pdf->SetX(5);
	$pdf->SetX(155);
	// ACA VA EL PIE
	//Posici�n: a 5 cm del final
 	// Ricardo	$pdf->SetY(-50);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetY(250);
   	$pdf->SetY(254);
   	$pdf->SetY(258);
	$pdf->SetY(-38);
 	$pdf->SetY(-34);
 	$pdf->SetY(-25);
   	//Arial italic 8
   	$pdf->SetFont('Arial','I',8);
   	$pdf->SetY(-20);
	$pdf->SetFont('Arial','B',10);

	$pdf->AddPage();
	$pdf->SetAutoPageBreak(1 , 2) ;
	// Imprimo la cabecera
   	// Linea de arriba
   	$pdf->Line(9,7.5,200,7.5);
	$pdf->Line(9,7.5,9,280);
	$pdf->Line(9,280,200,280);
	$pdf->Line(200,7.5,200,280);
	$pdf->Line(9,50,200,50);
	$pdf->Line(9,100,200,100);
	$pdf->Line(9,200,200,200);
	$pdf->Line(9,270,200,270);
	$pdf->Line(9,76,200,76);
	$pdf->Line(107,7.5,107,50);
   	$pdf->SetFont('Arial','B',14);
	$pdf->SetY(8);
	$pdf->SetX(150);
	$pdf->Cell(160,10,'RECIBO');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(15);
	$pdf->SetX(130);
   	$pdf->Cell(160,10,'Fecha :');
	$pdf->SetY(23);
	$pdf->SetX(130);
   	$pdf->Cell(150,10,'N� :');
	$pdf->Cell(150,10,'Fecha :');
	$pdf->SetY(32);
	$pdf->SetX(120);
   	$pdf->Cell(150,10,'C.U.I.T : 30-71018343-7');
	$pdf->SetY(37);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Ing. Brutos C.M : 901-265134-1');
	$pdf->SetY(42);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Fecha de Inicio a Actividades : 01/07/2007');
	$pdf->SetY(48);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Se�or/es:');
	$pdf->SetY(54);
	$pdf->SetX(10);
	$pdf->Cell(10,10,'Domicilio:');
	$pdf->SetX(125);
	$pdf->Cell(10,10,'Localidad:');
	$pdf->SetY(60);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'IVA:');
	$pdf->SetY(60);
	$pdf->SetX(125);
	$pdf->Cell(150,10,'CUIT:'.$cuit_cliente);
	$pdf->SetY(66);
	$pdf->SetY(66);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Telefono:'.$tel_cliente);
   	//Movernos a la derecha
    
   	//Fecha
	$pdf->SetY(15);
	$pdf->Cell(130);
	$pdf->Image('images/logo_adrian.jpg',10,8);
	$pdf->Image('images/equis.jpg',100,8);

   	//Arial bold 15
   	$pdf->SetFont('Arial','B',15);
   	//Movernos a la derecha
   	$pdf->Cell(80);
   	//T�tulo

   	//Salto de l�nea
   	$pdf->Ln(20);
	$pdf->SetY(15);
	$pdf->SetX(150);
   	$pdf->Cell(40,10,$fecharem,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	// Nuemro de Remito
	$pdf->SetY(23);
	$pdf->SetX(150);
   	$pdf->Cell(40,10,$recibonum ,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	// Datos del Cliente
	$pdf->SetY(48);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') ',0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->SetX(150);
	$pdf->Cell(70,10,$nomloc,0,0,'L');
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(60);
	$pdf->Cell(18);
	// Poner del Tipo de Impuesto 
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
	$pdf->SetX(130);
	$pdf->SetY(66);
	$pdf->Cell(30);
	$pdf->Cell(116);
	$pdf->SetX(29);
	$pdf->SetX(15);
	$pdf->SetY(76);
	$pdf->Cell(20,10,'Recib� la cantidad de pesos : '.$letras,0,0,'L');		
	$pdf->SetX(15);
	$pdf->SetY(89);
	$pdf->Cell(20,10,'en concepto de cancelaci�n de facturas N�:  '.$mascara."-".$num_fac ,0,0,'L');	
	$pdf->SetX(15);
	$pdf->SetY(102);
	$pdf->Cell(20,10,'Forma de Pago :',0,0,'L');		
	$pdf->SetX(15);
	$pdf->SetY(122);
	if ($efectivo!=0.0) {
		//$efectivo= number_format($efectivo, 2, ',','.');
		$pdf->Cell(20,10,'Efectivo:--------------------------------------------------- '.$efectivo,0,0,'L');	
	} else {
		$pdf->Cell(20,10,'Efectivo:',0,0,'L');	
	}
	$pdf->SetY(142);
	if ($cheques!=0) {
		$pdf->Cell(20,10,'Cheques :--------------------------------------------------- '.$cheques,0,0,'L');	
	} else {	
		$pdf->Cell(20,10,'Cheques :',0,0,'L');	
	}
	$pdf->SetX(15);
	$pdf->SetY(162);
	if ($depositos!=0) {
		$pdf->Cell(20,10,'Dep�sitos:-------------------------------------------------- '.$depositos,0,0,'L');
	} else {
		$pdf->Cell(20,10,'Dep�sitos:',0,0,'L');	
   	}
	$pdf->SetY(182);
	if ($retenciones!=0) {
	
		$pdf->Cell(20,10,'Retenciones :-------------------------------------------- '.$retenciones,0,0,'L');	
    	} else {	
		$pdf->Cell(20,10,'Retenciones :',0,0,'L');		
	}
	$pdf->SetX(15);
	$pdf->SetY(202);
	$pdf->Cell(20,10,'Detalles de Pago:',0,0,'L');	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(210);
	$pdf->Cell(100,4,$leyenda1,0,0,'L');
	$pdf->SetY(214);
	$pdf->Cell(100,4,$leyenda2,0,0,'L');
	$pdf->SetY(218);
	$pdf->Cell(100,4,$leyenda3,0,0,'L');
	$pdf->SetY(222);
	$pdf->Cell(100,4,$leyenda4,0,0,'L');
	$pdf->SetY(226);
	$pdf->Cell(100,4,$leyenda5,0,0,'L');
	$pdf->SetY(230);
	$pdf->Cell(100,4,$leyenda6,0,0,'L');
	$pdf->SetY(234);
	$pdf->Cell(100,4,$leyenda7,0,0,'L');
	$pdf->SetY(238);
	$pdf->Cell(100,4,$leyenda8,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(270);
	$pdf->Cell(20,10,'Total: Pesos',0,0,'L');
	$pdf->SetX(50);
	$pdf->Cell(0,8,$total.'  ',0,0,'L');
	$pdf->SetY(280);
   	$pdf->SetX(95);
	$pdf->Cell(20,10,'DUPLICADO',0,0,'C');

	// ACA INTENTAREMOS AGREGAR EL REMITO ============================== 09/03/2015 =========================================================
	// BUSCO EL PROXIMO NRO DE REMITO
	//Creo el PDF file
	// REMITO ORIGINAL
	//$pdf=new FPDF();
	if ($ptcomp == 1 || $ptcomp == 6 || $ptcomp == 23  || $ptcomp == 24) {
		
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(1 , 2) ;
	
		// Imprimo la cabecera
   		// Linea de arriba
   		$pdf->SetLineWidth(.2);
   		$pdf->Line(9,7.5,200,7.5);
		$pdf->Line(9,7.5,9,280);
		$pdf->Line(9,280,200,280);
		$pdf->Line(200,7.5,200,280);
		$pdf->Line(9,50,200,50);
		$pdf->Line(9,90,200,90);
		$pdf->Line(9,100,200,100);
		$pdf->Line(25,90,25,280);
		$pdf->Line(9,76,200,76);
		$pdf->Line(107,7.5,107,50);
   		$pdf->SetFont('Arial','B',14);
		
		$pdf->SetY(8);
		$pdf->SetX(150);
		$pdf->Cell(160,10,'REMITO');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetY(15);
		$pdf->SetX(130);
   		$pdf->Cell(160,10,'Fecha :');
		$pdf->SetY(23);
		$pdf->SetX(130);
   		$pdf->Cell(150,10,'N� :');
		$pdf->Cell(150,10,'Fecha :');
		$pdf->SetY(32);
		$pdf->SetX(120);
   		$pdf->Cell(150,10,'C.U.I.T : 30-71018343-7');
		$pdf->SetY(37);
		$pdf->SetX(120);
		$pdf->Cell(150,10,'Ing. Brutos C.M : 901-265134-1');
		$pdf->SetY(42);
		$pdf->SetX(120);
		$pdf->Cell(150,10,'Fecha de Inicio a Actividades : 01/07/2007');
		$pdf->SetY(48);
		$pdf->SetX(10);
		$pdf->Cell(150,10,'Se�or/es:');
		$pdf->SetY(54);
		$pdf->SetX(10);
		$pdf->Cell(10,10,'Domicilio:');
		$pdf->SetX(125);
		$pdf->Cell(10,10,'Localidad:');
		$pdf->SetY(60);
		$pdf->SetX(10);
		$pdf->Cell(150,10,'IVA:');
		$pdf->SetY(60);
		$pdf->SetX(125);
		$pdf->Cell(150,10,'CUIT:'.$cuit_cliente);
		$pdf->SetY(66);
		$pdf->SetY(66);
		$pdf->SetX(10);
		$pdf->Cell(150,10,'Telefono:'.$tel_cliente);
   		//Movernos a la derecha
    
   		//Fecha
		$pdf->SetY(15);
		$pdf->Cell(130);
	
		$pdf->Image('images/logo_adrian.jpg',10,8);
		$pdf->Image('images/equis.jpg',100,8);

   		//Arial bold 15
   		$pdf->SetFont('Arial','B',15);
   		//Movernos a la derecha
   		$pdf->Cell(80);
   		//T�tulo
   		//Salto de l�nea
   		$pdf->Ln(20);
		$pdf->SetY(15);
		$pdf->SetX(150);
		// ACA ESTA EL BOLONQUI DE LA FECHA
		//$fecharem = "21/11/2014";
		// D�a del mes con 2 d�gitos, y con ceros iniciales, de 01 a 31
 		$dia = date("d");
		// Mes actual en 2 d�gitos y con 0 en caso del 1 al 9, de 1 a 12
		$mes = date("m");
		// A�o actual con 4 d�gitos, ej 2013
 		$anio = date("Y");
		$fecharem = $dia."/".$mes."/".$anio;
   		$pdf->Cell(40,10,$fecharem,0,0,'L');
		$pdf->SetFont('Arial','B',10);
		// Numero de Remito
		$pdf->SetY(23);
		$pdf->SetX(150);
   		$pdf->Cell(40,10,$remitonum ,0,0,'L');
		$pdf->SetFont('Arial','B',10);
		// Datos del Cliente
		$pdf->SetY(48);
		$pdf->Cell(18);
		$pdf->Cell(70,10,$nom_cliente,0,0,'L');
		$pdf->SetY(54);
		$pdf->Cell(18);
		$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') ',0,0,'L');
		$pdf->SetY(54);
		$pdf->Cell(18);
		$pdf->SetX(150);
		$pdf->Cell(70,10,$nomloc,0,0,'L');
		// Datos del Remate
		$pdf->SetFont('Arial','B',10);
		$pdf->SetY(60);
		$pdf->Cell(18);
		// Poner del Tipo de Impuesto 
		$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
		$pdf->SetX(130);
		$pdf->SetY(66);
		$pdf->Cell(30);
		$pdf->Cell(116);
		$pdf->SetX(29);
		$pdf->SetX(15);
		$pdf->SetY(76);
		$pdf->SetX(15);
		$pdf->SetY(78);
		$pdf->Cell(20,10,'Relacionado con facturas N�:  '.$mascara."-".$num_fac ,0,0,'L');	
		$pdf->SetY(92);
		$pdf->SetX(12);
		$pdf->Cell(150,10,' LOTE                                         DESCRIPCION ');
	
		//Posici�n de los t�tulos de los renglones, en este caso no va
		$Y_Fields_Name_position = 90;
		//Posici�n del primer rengl�n 
		$Y_Table_Position = 102; // $Y_Table_Position = 100;

		//Los t�tulos de las columnas no los debo poner
		//Aca van los datos de las columnas

		$j = $Y_Table_Position;
		$pdf->SetY($Y_Table_Position);

		$pdf->SetFont('Arial','B',11);
		$pdf->SetY($j);
		$pdf->SetX(15);

		// C�digo interno de Lote
		$pdf->MultiCell(12,9,$df_codintlote,0,'L');
		$pdf->SetY($j);
		$pdf->SetX(15);

		// Descripci�n del lote en uno o dos renglones
		if (isset($df_descrip2)) {
			$pdf->SetX(25);
			$pdf->MultiCell(150,9,$df_descrip1,0,'L');
			$pdf->SetY($j+4);
			$pdf->SetX(25);
			$pdf->MultiCell(150,9,$df_descrip2,0,'L');
			$pdf->SetY($j);
			$pdf->SetX(155);
		
		}
		else{
	    	$pdf->SetY($j);
			$pdf->MultiCell(150,9,$df_descrip1,0,'L');
			$pdf->SetX(155);
		
		}
		$pdf->SetFont('Arial','B',10);
		$pdf->SetY(280);
   		$pdf->SetX(95);
		$pdf->Cell(20,10,'ORIGINAL',0,0,'C');

   		//Salto de l�nea
		//Posici�n de los t�tulos de los renglones, en este caso no va
		$Y_Fields_Name_position = 90;
		//Posici�n del primer rengl�n 
		$Y_Table_Position = 100;

		//Los t�tulos de las columnas no los debo poner
		//Aca van los datos de las columnas
		$j = $Y_Table_Position;
		$pdf->SetY($Y_Table_Position);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetY($j);
		$pdf->SetX(5);
		$pdf->SetX(155);

		// ACA VA EL PIE
		//Posici�n: a 5 cm del final
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(250);
   		$pdf->SetY(254);
   		$pdf->SetY(258);
		$pdf->SetY(-38);
 		$pdf->SetY(-34);
 		$pdf->SetY(-25);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		$pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);

		// ================================================= DUPLICADO DEL REMITO =========================================
		
		$pdf->AddPage();
		$pdf->SetAutoPageBreak(1 , 2) ;
	
		// Imprimo la cabecera
   		// Linea de arriba
   		$pdf->SetLineWidth(.2);
   		$pdf->Line(9,7.5,200,7.5);
		$pdf->Line(9,7.5,9,280);
		$pdf->Line(9,280,200,280);
		$pdf->Line(200,7.5,200,280);
		$pdf->Line(9,50,200,50);
		$pdf->Line(9,90,200,90);
		$pdf->Line(9,100,200,100);
		$pdf->Line(25,90,25,280);
		$pdf->Line(9,76,200,76);
		$pdf->Line(107,7.5,107,50);
   		$pdf->SetFont('Arial','B',14);
		$pdf->SetY(8);
		$pdf->SetX(150);
		$pdf->Cell(160,10,'REMITO');
		$pdf->SetFont('Arial','B',10);
		$pdf->SetY(15);
		$pdf->SetX(130);
   		$pdf->Cell(160,10,'Fecha :');
		$pdf->SetY(23);
		$pdf->SetX(130);
   		$pdf->Cell(150,10,'N� :');
		$pdf->Cell(150,10,'Fecha :');
		$pdf->SetY(32);
		$pdf->SetX(120);
   		$pdf->Cell(150,10,'C.U.I.T : 30-71018343-7');
		$pdf->SetY(37);
		$pdf->SetX(120);
		$pdf->Cell(150,10,'Ing. Brutos C.M : 901-265134-1');
		$pdf->SetY(42);
		$pdf->SetX(120);
		$pdf->Cell(150,10,'Fecha de Inicio a Actividades : 01/07/2007');
		$pdf->SetY(48);
		$pdf->SetX(10);
		$pdf->Cell(150,10,'Se�or/es:');
		$pdf->SetY(54);
		$pdf->SetX(10);
		$pdf->Cell(10,10,'Domicilio:');
		$pdf->SetX(125);
		$pdf->Cell(10,10,'Localidad:');
		$pdf->SetY(60);
		$pdf->SetX(10);
		$pdf->Cell(150,10,'IVA:');
		$pdf->SetY(60);
		$pdf->SetX(125);
		$pdf->Cell(150,10,'CUIT:'.$cuit_cliente);
		$pdf->SetY(66);
		$pdf->SetY(66);
		$pdf->SetX(10);
		$pdf->Cell(150,10,'Telefono:'.$tel_cliente);
   		//Movernos a la derecha
    
   		//Fecha
		$pdf->SetY(15);
		$pdf->Cell(130);
	
		$pdf->Image('images/logo_adrian.jpg',10,8);
		$pdf->Image('images/equis.jpg',100,8);

   		//Arial bold 15
   		$pdf->SetFont('Arial','B',15);
   		//Movernos a la derecha
   		$pdf->Cell(80);
   		//T�tulo
   		//Salto de l�nea
   		$pdf->Ln(20);
		$pdf->SetY(15);
		$pdf->SetX(150);
		// ACA ESTA EL BOLONQUI DE LA FECHA
		// D�a del mes con 2 d�gitos, y con ceros iniciales, de 01 a 31
 		$dia = date("d");
		// Mes actual en 2 d�gitos y con 0 en caso del 1 al 9, de 1 a 12
		$mes = date("m");
		// A�o actual con 4 d�gitos, ej 2015
 		$anio = date("Y");
		$fecharem = $dia."/".$mes."/".$anio;
		$pdf->Cell(40,10,$fecharem,0,0,'L');
		$pdf->SetFont('Arial','B',10);
		// Numero de Remito
		$pdf->SetY(23);
		$pdf->SetX(150);
   		$pdf->Cell(40,10,$remitonum ,0,0,'L');
		$pdf->SetFont('Arial','B',10);
		// Datos del Cliente
		$pdf->SetY(48);
		$pdf->Cell(18);
		$pdf->Cell(70,10,$nom_cliente,0,0,'L');
		$pdf->SetY(54);
		$pdf->Cell(18);
		$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') ',0,0,'L');
		$pdf->SetY(54);
		$pdf->Cell(18);
		$pdf->SetX(150);
		$pdf->Cell(70,10,$nomloc,0,0,'L');
		// Datos del Remate
		$pdf->SetFont('Arial','B',10);
		$pdf->SetY(60);
		$pdf->Cell(18);
		// Poner el Tipo de Impuesto 
		$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
		$pdf->SetX(130);
		$pdf->SetY(66);
		$pdf->Cell(30);
		$pdf->Cell(116);
		$pdf->SetX(29);
		$pdf->SetX(15);
		$pdf->SetY(76);
		$pdf->SetX(15);
		$pdf->SetY(78);
		$pdf->Cell(20,10,'Relacionado con facturas N�:  '.$mascara."-".$num_fac ,0,0,'L');	
		$pdf->SetY(92);
		$pdf->SetX(12);
		$pdf->Cell(150,10,' LOTE                                         DESCRIPCION ');
	
		//Posici�n de los t�tulos de los renglones, en este caso no va
		$Y_Fields_Name_position = 90;
		//Posici�n del primer rengl�n 
		$Y_Table_Position = 102; // $Y_Table_Position = 100;

		//Los t�tulos de las columnas no los debo poner
		//Aca van los datos de las columnas

		$j = $Y_Table_Position;
		$pdf->SetY($Y_Table_Position);

		$pdf->SetFont('Arial','B',11);
		$pdf->SetY($j);
		$pdf->SetX(15);

		// C�digo interno de Lote
		$pdf->MultiCell(12,9,$df_codintlote,0,'L');
		$pdf->SetY($j);
		$pdf->SetX(15);

		// Descripci�n del lote en uno o dos renglones
		if (isset($df_descrip2)) {
			$pdf->SetX(25);
			$pdf->MultiCell(150,9,$df_descrip1,0,'L');
			$pdf->SetY($j+4);
			$pdf->SetX(25);
			$pdf->MultiCell(150,9,$df_descrip2,0,'L');
			$pdf->SetY($j);
			$pdf->SetX(155);
		}
		else{
	    	$pdf->SetY($j);
			$pdf->MultiCell(150,9,$df_descrip1,0,'L');
			$pdf->SetX(155);
		
		}
		$pdf->SetX(15);
		$pdf->SetY(202);
		$pdf->SetY(270);
		$pdf->SetX(50);
		$pdf->SetFont('Arial','B',10);
		// ACA VA EL PIE
		//Posici�n: a 5 cm del final
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(250);
   		$pdf->SetY(254);
   		$pdf->SetY(258);
		$pdf->SetY(-38);
 		$pdf->SetY(-34);
 		$pdf->SetY(-25);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		$pdf->SetY(-20);
		$pdf->SetFont('Arial','B',10);
		$pdf->SetY(280);
   		$pdf->SetX(95);
		$pdf->Cell(20,10,'DUPLICADO',0,0,'C');
	}
}	
*/
$pdf->Output();
?>  
