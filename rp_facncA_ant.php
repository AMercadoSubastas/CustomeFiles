<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

	// Leo los par�metros del formulario anterior
	$ptcomp = $_GET['ftcomp'];
	$pserie = $_GET['fserie'];
	$pncomp = $_GET['fncomp'];
	//echo " TCOMP =   ".$ptcomp."  SERIE =  ".$pserie."  NCOMP =  ".$pncomp."  -  ";
	$totalFilas = 0;
	// Cambia el estado de la factura 
	$actualizaemitido ="UPDATE cabfac SET emitido='1' WHERE tcomp = '$ptcomp' AND ncomp='$pncomp'";
	$Result1 = mysqli_query($amercado, $actualizaemitido) or die(mysqli_error($amercado));
	//echo " 1  - ";
	// Leo los renglones
	$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s ORDER BY codlote" , $ptcomp, $pserie, $pncomp);
	$detallefac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
	$totalRows_detallefac = mysqli_num_rows($detallefac);
	//echo " 2  - ";
	// Traigo impuestos
	$query_impuestos= "SELECT * FROM impuestos";
	$impuestos = mysqli_query($amercado, $query_impuestos) or die(mysqli_error($amercado));
	$row_Recordset2 = mysqli_fetch_assoc($impuestos);
	$totalRows_Recordset2 = mysqli_num_rows($impuestos);
	$porc_iva105 = (mysqli_result($impuestos,1, 1)/100); 
	$porc_iva21 = (mysqli_result($impuestos,0, 1)/100);
	//echo " 3  - ";
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
	//echo " 4  - ";
	// Leo la leyenda asociada a la factura y corto la descripcion en 8 renglones de 100 caracteres c/u
	$query_leyenda   = sprintf("SELECT * FROM factley WHERE tcomp = %s AND serie = %s AND ncomp = %s", $ptcomp, $pserie, $pncomp);
	$cabeleyenda     = mysqli_query($amercado, $query_leyenda) or die(mysqli_error($amercado));
	$row_cabeleyenda = mysqli_fetch_assoc($cabeleyenda);

	$leyenda1      = substr($row_cabeleyenda["leyendafc"],0,120);
	$leyenda2	   = substr($row_cabeleyenda["leyendafc"],120,120);
	$leyenda3	   = substr($row_cabeleyenda["leyendafc"],240,120);

	// Leo el remate
	if ($remate!="" && $remate != 0) {
		//echo " 5  - ";
   		$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $remate);
	   	$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
   		$row_remates = mysqli_fetch_assoc($remates);
		$remate_ncomp = $row_remates["ncomp"];
		$remate_direc = $row_remates["direccion"];
		$remate_fecha = $row_remates["fecreal"];
		$loc_remate   = $row_remates["codloc"];
		$prov_remate  = $row_remates["codprov"];
		$remate_fecha = substr($remate_fecha,8,2)."-".substr($remate_fecha,5,2)."-".substr($remate_fecha,0,4);
		//echo " 6  - ";
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
	//echo " 7  - ";
   		// Leo la localidad del Remate
		$query_localidades_rem = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_remate);
		$localidad_rem = mysqli_query($amercado, $query_localidades_rem) or die(mysqli_error($amercado));
		$row_localidades_rem = mysqli_fetch_assoc($localidad_rem);
		$nomlocrem = $row_localidades_rem["descripcion"];
	//echo " 8  - ";
		// Leo la Provincia del Remate
		$query_provincia_rem = sprintf("SELECT * FROM provincias WHERE  codnum = %s",$prov_remate);
		$provincia_rem = mysqli_query($amercado, $query_provincia_rem) or die(mysqli_error($amercado));
		$row_provincia_rem = mysqli_fetch_assoc($provincia_rem);
		$nomprovrem = $row_provincia_rem["descripcion"];
	
		
	} 
	 //echo " 9  - "; 
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
	//echo " 10  - ";
	// Leo la localidad
	$query_localidades = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_cliente);
	$localidad = mysqli_query($amercado, $query_localidades) or die(mysqli_error($amercado));
	$row_localidades = mysqli_fetch_assoc($localidad);
	$nomloc = $row_localidades["descripcion"];
	//echo " 11  - ";
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
	if ($remate!="" && $remate!=0 && $ptcomp != 57 && $ptcomp != 59 && $ptcomp != 61 && $ptcomp != 63 ) {
		//echo " 12  - ";
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
			//echo " 13  - ";
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
			else { //if ($lote_num!="" ){
				$codintlote    = $row_lotes['codintlote'];
				$df_codintlote = $df_codintlote.$codintlote."\n";
			}
			$df_descrip1   = $df_descrip1.$descrip1."\n";
			$df_descrip2   = $df_descrip2.$descrip2."\n";
	
		}
	} else {
		//echo " 14  - ";
		$query_detfac1 = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s ORDER BY codlote" , $ptcomp, $pserie, $pncomp);
		$detallefac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));

		$totalRows_detallefac1 = mysqli_num_rows($detallefac1);
		while($row_detallefac1 = mysqli_fetch_array($detallefac1)) {

	    	$df_lote       	= $row_detallefac1["concafac"];
			$neto          	= $row_detallefac1["neto"];
			$neto          	= $row_detallefac1["neto"];
			$importe  		= number_format($row_detallefac1["neto"], 2, ',','.');
			$df_neto 		= number_format($row_detallefac1["neto"], 2, ',','.');
			$df_importe    	= $df_importe.$importe."\n";
			$df_codintlote 	= $df_codintlote.$df_lote."\n";
			$descrip1   	= substr($row_detallefac1['descrip'],0,60);
			$df_descrip1   	= $df_descrip1.$descrip1."\n";
		}
	}
	mysqli_close($amercado);

	// =========================================== FACTURA ORIGINAL  ==================================================================
	//Creo el PDF file
	// Imprimo la cabecera
   	// Linea de arriba

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
	$pdf->Line(175,95,175,246); 
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
	if ($ptcomp == 51 || $ptcomp == 52 || $ptcomp == 55)
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
	
	if ($ptcomp == 55 || $ptcomp == 61) {
		$pdf->SetY(15);
		$pdf->Cell(130);
		$pdf->Image('images/logo_adrian.jpg',10,8);
		$pdf->SetY(18);
		$pdf->SetX(15);
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(150,10,'    Matriculado CUCICBA: Mercado Leandro Nicol�s');
		$pdf->SetY(21);
		$pdf->SetX(15);
		$pdf->Cell(150,10,'         Matr�cula N� 5604, Tomo 1, Folio 209');
		
	}
	else {
		$pdf->SetY(15);
		$pdf->Cell(130);
		$pdf->Image('images/logo_adrian.jpg',10,8);
	}
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(25);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'                ADRIAN MERCADO S.A.');
	$pdf->SetY(29);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'Av. Alicia Moreau de Justo 740 Piso 4 Of. 19');
	$pdf->SetY(33);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'CABA  (C1107AAP) Tel/Fax: (011)  4343-9893');
	$pdf->SetY(37);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'       IVA  RESPONSABLE  INSCRIPTO');
	
	$pdf->Image('images/tipo_a.gif',100,8);
	
	
	// Imprimo la cabecera
    // Nota Debito
    if ($ptcomp==59 || $ptcomp==63) {
		$pdf->SetY(6);
		$nota = 'NOTA DE';
		$debito= ' DEBITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota.$debito,0,0,'L');
		//$pdf->SetLineWidth(5);
		//$pdf->Line(145,10,170,10);
		//$pdf->SetX(174);
		//$pdf->Cell(70,10,$debito,0,0,'L');
	}
	if ($ptcomp==57 || $ptcomp==61 ) {
		$pdf->SetY(6);
		$nota = 'NOTA DE ';
		$debito= 'CREDITO';
		$pdf->SetFont('Arial','B',14);
		$pdf->SetX(125);
		$pdf->Cell(70,10,$nota.$debito,0,0,'L');
		//$pdf->SetLineWidth(5);
		//$pdf->Line(145,10,170,10);
		//$pdf->SetX(174);
		//$pdf->Cell(70,10,$debito,0,0,'L');

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
	if ($remate!="" && $remate != 0) {
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
	if ($pserie == 29) 
		$pdf->Cell(20,10,'       LOTE                                                          DESCRIPCION                                                                                NETO',0,0,'L');
	else 
		$pdf->Cell(20,10,'                                                                     DESCRIPCION                                                                                NETO',0,0,'L');

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


	$pdf->SetY(-38); // $pdf->SetY(-25);
   	//Arial italic 8
   	$pdf->SetFont('Arial','I',8);
   	if ($pserie==29 || $pserie == 31) {	
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
	$pdf->Output();
?>  
