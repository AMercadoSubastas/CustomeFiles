<?php
require_once('Connections/amercado.php'); 
include_once "funcion_mysqli_result.php";
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');
require('fpdf17/fpdf.php');
require('numeros_a_letras.php');


mysqli_select_db($amercado, $database_amercado);

$fecha_hoy = date("Y-m-d");
$fecha_hoy = "'".$fecha_hoy."'";

// Leo los parámetros del formulario anterior
if (isset($_POST['ncomp']))
    $pncomp = $_POST['ncomp'];
if (isset($_GET['ncomp']))
    $pncomp = $_GET['ncomp'];
$tcomp = 2;
$serie = 3;
$tcompefe = 0;
$lista_chq    = array();
$lista_dep    = array();
// Leo la cabecera
$query_cab_recibo = "SELECT * FROM cabrecibo  WHERE tcomp ='$tcomp' AND serie ='$serie' AND ncomp = '$pncomp'" ;
if ($cab_recibo = mysqli_query($amercado, $query_cab_recibo)) { 
    $row_cab_recibo = mysqli_fetch_assoc($cab_recibo);
    $cliente = $row_cab_recibo["cliente"];
    $fecha = $row_cab_recibo["fecha"];
    $fecha_rem = $fecha;
    $fecha = substr($row_cab_recibo["fecha"],8,2)."/".substr($row_cab_recibo["fecha"],5,2)."/".substr($row_cab_recibo["fecha"],0,4);
    $total = $row_cab_recibo["imptot"];
}

// Leo los renglones
$query_det_recibo = "SELECT detrecibo.nrodoc , detrecibo.netocbterel FROM detrecibo WHERE ncomp ='$pncomp'";
$selec_det_recibo = mysqli_query($amercado, $query_det_recibo) or die("ERROR LEYENDO 32");

$monto_recibo = 0;
$factura_num = " ";

while ($monto = mysqli_fetch_array($selec_det_recibo, MYSQLI_BOTH)) {
	$factura_num =$factura_num.$monto['0'].";";
}
/*
$fact_tcomp = "";
$fact_serie = "";
$fact_ncomp = "";
$fact_nrodoc = "";
*/
$query_det_recibo2 = "SELECT * FROM detrecibo WHERE ncomp ='$pncomp'";
$selec_det_recibo2 = mysqli_query($amercado, $query_det_recibo2) or die("ERROR LEYENDO 48");
$i = 0;

$query_det_recibo2 = "SELECT * FROM detrecibo WHERE ncomp ='$pncomp'";
$selec_det_recibo2 = mysqli_query($amercado, $query_det_recibo2) or die("ERROR LEYENDO 48");
$i = 0;
while ($det_recibo2 = mysqli_fetch_array($selec_det_recibo2)) {
	
	$fact_tcomp[$i] =	$det_recibo2['tcomprel'];
	$fact_serie[$i] =	$det_recibo2['serierel'];
	$fact_ncomp[$i] =	$det_recibo2['ncomprel'];
	$fact_nrodoc[$i] =	$det_recibo2['nrodoc'];
	$i++;
}
$tope_fact = $i;


// VEO QUIEN FUE EL USUARIO QUE GENERO LA FACTURA
$query_cab = "SELECT * FROM cabfac WHERE ncomp ='$fact_ncomp[0]' and tcomp = '$fact_tcomp[0]'";
$selec_cab = mysqli_query($amercado, $query_cab) or die("ERROR LEYENDO 61");
$row_cab = mysqli_fetch_assoc($selec_cab);
$usuario_fac = $row_cab["usuario"];
if ($usuario_fac == 25 || $usuario_fac == 26) {
    $inmob = 0;
}
else {
    $inmob = 0;
}

// Leo el cliente
$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
$enti = mysqli_query($amercado,  $query_entidades) or die("ERROR LEYENDO 63");
$row_entidades = mysqli_fetch_assoc($enti);
$nom_cliente   = $row_entidades["razsoc"];
$calle_cliente = $row_entidades["calle"];
$nro_cliente   = $row_entidades["numero"];
$codp_cliente  = $row_entidades["codpost"];
$loc_cliente   = $row_entidades["codloc"]; 
$cuit_cliente  = $row_entidades["cuit"];
$tel_cliente   = $row_entidades["tellinea"];
$tipo_iva      = $row_entidades["tipoiva"];
$mail          = $row_entidades["mailcont"];

// TIPO DE IVA 
$sql_iva = sprintf("SELECT * FROM tipoiva WHERE  codnum = %s", $tipo_iva);
$tipo_de_iva = mysqli_query($amercado,  $sql_iva ) or die("ERROR LEYENDO 77");
$row_tip_iva = mysqli_fetch_assoc($tipo_de_iva);
$tip_iva_cliente = $row_tip_iva["descrip"];

// Leo la localidad
$query_localidades = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_cliente);
$localidad = mysqli_query($amercado,  $query_localidades) or die("ERROR LEYENDO 84");
$row_localidades = mysqli_fetch_assoc($localidad);
$nomloc = $row_localidades["descripcion"];

$pdf=new FPDF();


for ($k=0;$k<2;$k++) {
	
    // CHEQUES
    $query_cheques = "SELECT codban,codsuc,codchq,importe,fechapago FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='8' AND serie='6'";
    $selec_cheques = mysqli_query($amercado,  $query_cheques) or die("ERROR LEYENDO 103");

    $tot_cheques = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='8' AND serie='6'";
    $result_cheques = mysqli_query($amercado,  $tot_cheques) or die("ERROR LEYENDO 106");
    $cheques_tot = 0 ;

    while($row = mysqli_fetch_array($result_cheques, MYSQLI_BOTH)){
        $cheques_tot0	= $row['0'];
        $cheques_tot = $cheques_tot + $cheques_tot0 ;
    }

    // CHEQUES A TERCEROS
    $query_cheques11 = "SELECT codban,codsuc,codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='100' AND serie='44'";
    $selec_cheques11 = mysqli_query($amercado,  $query_cheques11) or die("ERROR LEYENDO 105");

    $tot_cheques11 = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='100' AND serie='44'";
    $result_cheques11 = mysqli_query($amercado,  $tot_cheques11) or die("ERROR LEYENDO 111");
    $cheques_tot11 = 0 ;

    while($row11 = mysqli_fetch_array($result_cheques11, MYSQLI_BOTH)){
        $cheques_tot011	= $row11['0'];
        $cheques_tot11 = $cheques_tot11 + $cheques_tot011 ;
    }

    // REMATES
    $query_rem = "SELECT ncomp,importe, fechapago FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='4' ";
    $selec_rem = mysqli_query($amercado,  $query_rem) or die("ERROR LEYENDO 107 ");

    $tot_rem = "SELECT importe, ncomp  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='4' ";
    $result_rem = mysqli_query($amercado,  $tot_rem ) or die("ERROR LEYENDO 112");
    $rem_tot = 0 ;
    while($row41 = mysqli_fetch_array($result_rem, MYSQLI_BOTH)){
        $rem_tot0	= $row41['0'];
        $rem_texto = $row41['1'];
        $rem_tot = $rem_tot + $rem_tot0 ;
    }

    // DEPOSITOS
    $query_depositos = "SELECT codban,codsuc,codchq,importe,fechapago FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='9' AND serie='7'";
    $selec_depositos = mysqli_query($amercado,  $query_depositos) or die("ERROR LEYENDO 107 ");

    //echo "DEPOSITOS = ".$selec_depositos."  ";
    
    $tot_dep = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='9' AND serie='7'";
    $result_dep = mysqli_query($amercado,  $tot_dep ) or die("ERROR LEYENDO 112");
    $dep_tot = 0 ;
    while($row1 = mysqli_fetch_array($result_dep, MYSQLI_BOTH)){
        $dep_tot0	= $row1['0'];
        $dep_tot = $dep_tot + $dep_tot0 ;
    }

    // DEPOSITOS A TERCEROS
    $query_depositos_terceros = "SELECT codban,codsuc,codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='95' AND serie='39'";
    $selec_depositos_terceros = mysqli_query($amercado,  $query_depositos_terceros) or die("ERROR LEYENDO 122");

    $tot_dep_terceros = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='95' AND serie='39'";
    $result_dep_terceros = mysqli_query($amercado,  $tot_dep_terceros) or die("ERROR LEYENDO 127");
    $dep_tot_terceros = 0 ;
    while($row1_terceros = mysqli_fetch_array($result_dep_terceros, MYSQLI_BOTH)){
        $dep_tot_terceros0	= $row1_terceros['0'];
        $dep_tot_terceros = $dep_tot_terceros + $dep_tot_terceros0 ;
    }
    $tcompefe = 0;
    // EFECTIVO
    $query_efectivo = "SELECT importe, codchq,tcomp  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND (tcomp='12' OR tcomp='13')";
    $selec_efectivo = mysqli_query($amercado,  $query_efectivo) or die("ERROR LEYENDO 139");
    $efe_tot = 0 ;
    while($row2 = mysqli_fetch_array($selec_efectivo, MYSQLI_BOTH)){
        $efe_tot0	= $row2['0'];
        $efe_tot = $efe_tot + $efe_tot0 ;
        $ndeb = $row2['1'];
        $tcompefe = $row2['2'];

    }
    
    $tcompefe2 = 0;
    // PAGO CON SALDO A FAVOR
    $query_efectivo_2 = "SELECT importe, codchq,tcomp  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND  tcomp='91'";
    $selec_efectivo_2 = mysqli_query($amercado,  $query_efectivo_2) or die("ERROR LEYENDO 187");
    $efe_tot_2 = 0 ;
    while($row2_2 = mysqli_fetch_array($selec_efectivo_2, MYSQLI_BOTH)){
        $efe_tot0_2	= $row2_2['0'];
        $efe_tot_2 = $efe_tot_2 + $efe_tot0_2 ;
        $ndeb2 = $row2_2['1'];
        $tcompefe2 = $row2_2['2'];

    }
    $tcompefe3 = 0;
    // CREDITO A FAVOR
    $query_efectivo_3 = "SELECT importe, codchq,tcomp  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='98'";
    $selec_efectivo_3 = mysqli_query($amercado,  $query_efectivo_3) or die("ERROR LEYENDO 187");
    $efe_tot_3 = 0 ;
    while($row2_3 = mysqli_fetch_array($selec_efectivo_3, MYSQLI_BOTH)){
        $efe_tot0_3	= $row2_3['0'];
        $efe_tot_3 = $efe_tot_3 + $efe_tot0_3 ;
        $ndeb3 = $row2_3['1'];
        $tcompefe3 = $row2_3['2'];

    }
    $tcompefe4 = 0;
    // CANCELA CONTRA FC PROVEEDOR
    $query_efectivo_4 = "SELECT importe, codchq,tcomp  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='97'";
    $selec_efectivo_4 = mysqli_query($amercado,  $query_efectivo_4) or die("ERROR LEYENDO 187");
    $efe_tot_4 = 0 ;
    while($row2_4 = mysqli_fetch_array($selec_efectivo_4, MYSQLI_BOTH)){
        $efe_tot0_4	= $row2_4['0'];
        $efe_tot_4 = $efe_tot_4 + $efe_tot0_4 ;
        $ndeb4 = $row2_4['1'];
        $tcompefe4 = $row2_4['2'];

    }
    
    // NOTAS DE CREDITO ASOCIADAS A LA FACTURA
    $query_ganan = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='42' ";
    $selec_ganancias = mysqli_query($amercado,  $query_ganan) or die("ERROR LEYENDO 156");

    $query_ganan1 = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='42' ";
    $selec_ganan1 = mysqli_query($amercado,  $query_ganan1) or die("ERROR LEYENDO 161");

    $ganancia = 0 ;
    while($row3 = mysqli_fetch_array($selec_ganancias, MYSQLI_BOTH)){
        $ganan	= $row3['1'];
        $ganancia = $ganancia + $ganan ;
    }

    // RETENCION ING BRUTOS PARA CBTES VIEJOS
    $query_ing_brutos = "SELECT codchq,importe, tcomp FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND serie='34'";
    $selec_ing_brutos = mysqli_query($amercado,  $query_ing_brutos) or die("ERROR LEYENDO 173");

    $query_ing_brutos1 = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND serie='34'";
    $selec_ing_brutos1 = mysqli_query($amercado,  $query_ing_brutos1) or die("ERROR LEYENDO 178");

    $t_ing_br = 0 ;
    while($row4 = mysqli_fetch_array($selec_ing_brutos1, MYSQLI_BOTH)){
        $iibb	= $row4['1'];
        $t_ing_br = $t_ing_br + $iibb ;
    }

    // RETENCION IVA PARA CBTES VIEJOS
    $query_iva = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='40' AND serie='22'";
    $selec_iva = mysqli_query($amercado,  $query_iva) or die("ERROR LEYENDO 191");

    $query_iva1 = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='40' AND serie='22'";
    $selec_iva1 = mysqli_query($amercado,  $query_iva1) or die("ERROR LEYENDO 196");

    $tot_iva = 0 ;
    while($row5 = mysqli_fetch_array($selec_iva1, MYSQLI_BOTH)){
        $iva_p	= $row5['1'];
        $tot_iva = $tot_iva + $iva_p ;
    }

    // RETENCION SUSS
    $query_suss = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='43' AND serie='25'";
    $selec_suss = mysqli_query($amercado,  $query_suss) or die("ERROR LEYENDO 208");

    $query_suss1 = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='43' AND serie='25'";
    $selec_suss1 = mysqli_query($amercado,  $query_suss1) or die("ERROR LEYENDO 213");

    $tot_sus = 0 ;
    while($row6 = mysqli_fetch_array($selec_suss1, MYSQLI_BOTH)){
        $sus_p	= $row6['1'];
        $tot_sus = $tot_sus + $sus_p ;
    }
    $ganan = 0;
    $total_retenciones = 0;
    $total_retenciones = $tot_sus + $tot_iva + $t_ing_br + $ganan;
    $total_cheques2 = 0;
    $total_cheques112 = 0;
    $total_deposito2 = 0;
    $total_remate = 0;
    $total_deposito_terceros = 0;
    $total_efectivo = 0;
    $total_efectivo_2 = 0;
    $total_iva = 0;
    $total_ganancias = 0;
    $total_ncred = 0;
    $total_suss = 0;
    $total_ing_brutos = 0;
    
    //Creo el PDF file
		
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
	$pdf->Line(9,140,200,140);
	$pdf->Line(9,270,200,270);
	$pdf->Line(9,76,200,76);
	//$pdf->Line(107,7.5,107,50);
    $pdf->Line(107,22,107,50);
    $pdf->SetFont('Arial','BI',10);
    $pdf->SetY(24);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'        ADRIAN MERCADO SUBASTAS S.A.');
    $pdf->SetFont('Arial','BI',14);
    $pdf->SetY(19);
    $pdf->SetX(120);
    $pdf->Cell(160,10,'Recibo Nro.: ');
    $pdf->SetFont('Arial','BI',12);
    $pdf->SetY(24);
    $pdf->SetX(120);
    $pdf->Cell(100,10,utf8_decode('Fecha de Emisión: '));
    $pdf->SetFont('Arial','BI',10);
    $pdf->SetY(29);
    $pdf->SetX(120);
    $pdf->Cell(150,10,'CUIT: 30-71803361-2');
    $pdf->SetY(33);
    $pdf->SetX(120);
    $pdf->Cell(150,10,'Ingresos Brutos:  30-71803361-2');
    $pdf->SetY(38);
    $pdf->SetX(120);
    $pdf->Cell(150,10,'Fecha de Inicio de Actividades: 22/12/2022');	
	$pdf->SetY(48);
	$pdf->SetX(10);
	$pdf->Cell(100,10,utf8_decode('Apellido y Nombre / Razón Social: '));
	$pdf->SetY(54);
	$pdf->SetX(10);
	$pdf->Cell(10,10,'Domicilio: ');
	$pdf->SetX(125);
	$pdf->SetY(66);
	$pdf->SetX(10);
	$pdf->Cell(100,10,utf8_decode('Condición frente al IVA: '));
	$pdf->SetY(54);
	$pdf->SetX(140);
	if (isset($mail)) {
		$pdf->Cell(20,10,utf8_decode('Teléfono: '));
        $pdf->SetX(157);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(30,10,$tel_cliente);
        $pdf->SetY(60);
        $pdf->SetX(120);
        $pdf->SetFont('Arial','BI',10);
        $pdf->Cell(100,10,'e-mail: ');
        $pdf->SetX(135);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(100,10,$mail);
	}
	else {
 		$pdf->Cell(100,10,utf8_decode('Teléfono: '));
        $pdf->SetX(157);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(30,10,$tel_cliente);
	}
    $pdf->SetY(60);
	$pdf->SetX(10);
    $pdf->SetFont('Arial','BI',10);
	$pdf->Cell(150,10,'CUIT: ');
   	$pdf->SetFont('Arial','',10); 
    $pdf->SetX(21);
    $pdf->Cell(150,10,$cuit_cliente);
	
    //$pdf->SetY(14);
	$pdf->SetX(10);
    if ($inmob == 1) 
	    $pdf->Image('images/Adrian-Mercado-Inmobiliaria_1.png',19,8);
    else
        $pdf->Image('marca_adrianmercado-subasta_version1.png',10,4);
	$pdf->Image('images/equis.jpg',101,7.5);
	$pdf->SetFont('Arial','BI',10);
	$pdf->SetY(24);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'        ADRIAN MERCADO SUBASTAS S.A.');
	$pdf->Line(9,7.5,200,7.5);
    $pdf->SetY(15);
	$pdf->SetX(82);
    $pdf->SetFont('Arial','',8); 
    $pdf->Cell(80,10,utf8_decode('Documento no válido como factura'));
    $pdf->SetFont('Arial','BI',10);
	$pdf->SetY(29);
	$pdf->SetX(14);
	$pdf->Cell(150,10,'               Olga Cossettini 731, Piso 3');
	$pdf->SetY(33);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'CABA  (C1107CDA) Tel/Fax: +54 11 3984-7400');
	$pdf->SetY(39);
	$pdf->SetX(15);
	$pdf->Cell(150,10,'      IVA  RESPONSABLE  INSCRIPTO');
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(80);
    $pdf->Ln(20);
	$pdf->SetY(24);
	$pdf->SetX(158);
    //Fecha
    $pdf->Cell(40,10,$fecha,0,0,'L');
	$pdf->SetFont('Arial','',12);
	// Numero de Recibo
	$pdf->SetY(19);
	$pdf->SetX(152);
    $pdf->Cell(40,10,$pncomp ,0,0,'L');
	$pdf->SetFont('Arial','',10);

	// Datos del Cliente
	$pdf->SetY(48);
	$pdf->SetX(70);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
	$pdf->SetY(54);
	$pdf->SetX(28);
	if (isset($codp_cliente))
		$pdf->Cell(100,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') '.$nomloc,0,0,'L');
	else
		$pdf->Cell(100,10,$calle_cliente.' '.$nro_cliente.' '.$nomloc,0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->SetX(120);
		
	// Datos del Remate
	$pdf->SetFont('Arial','',10);
	$pdf->SetY(66);
	$pdf->SetX(52);
	
	// Condición frente al IVA
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
			
    
	$letras = numtoletras($total); // convertir_a_letras($total);
	$total2  = number_format($total, 2, ',','.');
	
	$total_de_cheques  = number_format($cheques_tot, 2, ',','.');
    $total_de_cheques11  = number_format($cheques_tot11, 2, ',','.');
    $pdf->SetX(15);
	$pdf->SetY(76);
    $pdf->SetFont('Arial','BI',10);
	$pdf->Cell(20,10,'Recibi(mos) la suma de: ',0,0,'L');	
	$pdf->SetY(80);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(20,10,$letras,0,0,'L');	
	$pdf->SetX(15);
	$pdf->SetY(85);
	$pdf->SetFont('Arial','BI',10);
	if ($pncomp == 260001 || $pncomp == 260010)
		$pdf->Cell(20,10,utf8_decode('en concepto de depósito en garantía para habilitación subasta on line  '),0,0,'L');	
	else
		$pdf->Cell(20,10,utf8_decode('en concepto de cancelación de facturas Nº: '),0,0,'L');	
	$pdf->SetFont('Arial','',8);
	$pdf->SetY(90);
	$pdf->Cell(20,8,substr($factura_num, 0,121),0,0,'L');
	$pdf->SetY(93);
	$pdf->Cell(20,8,substr($factura_num, 121,120),0,0,'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(15);
	$pdf->SetY(98);
	$pdf->Cell(20,10,'Forma de Pago :',0,0,'L');		
	$pdf->SetX(15);
	$valorY = 102;
    $pdf->SetY($valorY);
	$efec = $efe_tot;
 
    if ($tcompefe == 12) {
        $pdf->Cell(20,10,'Efectivo:--------------------------------------  $ ',0,0,'L');
        $pdf->SetX(80);
        $efe_totf  = number_format($efe_tot, 2, ',','.');
        $pdf->Cell(20,10,$efe_totf,0,0,'R');	
        $valorY += 5;
        $pdf->SetY($valorY);
    }
    
    if ($tcompefe == 13) {
        $pdf->Cell(20,10,utf8_decode('Depósito en dólares:-T. cbio ($221,57)-- U$S '),0,0,'L');
        $pdf->SetX(85);
        $efe_totf  = number_format($efe_tot, 2, ',','.');
        $pdf->Cell(20,10,$efe_totf,0,0,'R');	
        $valorY += 5;
        $pdf->SetY($valorY);
    }
  
        
    if ($tcompefe2 == 91) {
        $pdf->Cell(45,10,utf8_decode('Pago con crédito a favor:'.$ndeb2.'------------  $ '),0,0,'L');
        $pdf->SetX(80);
        $efe_tot_2f  = number_format($efe_tot_2, 2, ',','.');
        $pdf->Cell(45,10,$efe_tot_2f,0,0,'R');
        $valorY += 5;
        $pdf->SetY($valorY);
    }
    if ($tcompefe3 == 98 ) {
        $pdf->SetX(80);
        $pdf->SetY($valorY);
        $pdf->Cell(50,10,utf8_decode('Nuevo Créd. a Favor: ').$ndeb3.' $ ',0,0,'L');
        $pdf->SetX(80);
        $efe_tot_3f  = number_format($efe_tot_3, 2, ',','.');
        $pdf->Cell(20,10,$efe_tot_3f,0,0,'R');	
        $valorY += 5;
        $pdf->SetY($valorY);
    }
            
    if ($tcompefe4 == 97) {
        $pdf->Cell(20,10,'Cancela contra :    '.$ndeb4.'---------------------------  $ ',0,0,'L');
        $pdf->SetX(120);
        $efe_tot_4f  = number_format($efe_tot_4, 2, ',','.');
        $pdf->Cell(20,10, $efe_tot_4f,0,0,'R');
        $valorY += 5;
        $pdf->SetY($valorY);
    }
        
    
    $valorY += 5;
	$pdf->SetY($valorY);
	if ($cheques_tot!=0) {
		$pdf->Cell(20,7,'Cheques : -----------------------------------  $',0,0,'L');
        $pdf->SetX(80);
        $pdf->Cell(20,7,$total_de_cheques,0,0,'R');
        $valorY += 5;
	} else {
		//$pdf->Cell(20,8,'Cheques :',0,0,'L');	
	}	
    $pdf->SetY($valorY);
	if ($cheques_tot11!=0) {
		$pdf->Cell(20,7,'Cheques entregados a terceros :-  $ ',0,0,'L');
        $pdf->SetX(80);
        $pdf->Cell(20,7,$total_de_cheques11,0,0,'R');	
        $valorY += 5;
	} 	
	$pdf->SetX(15);
	$pdf->SetY($valorY);
	if ($dep_tot!=0) {
    	$dep_tot2  = number_format($dep_tot, 2, ',','.');
		$pdf->Cell(20,7,utf8_decode('Depósitos: ----------------------------------  $ '),0,0,'L');
        $pdf->SetX(80);
        $pdf->Cell(20,7,$dep_tot2,0,0,'R');
        $valorY += 5;
	} 
	$pdf->SetX(15);
	$pdf->SetY($valorY);
	if ($dep_tot_terceros!=0) {
        $dep_tot_terceros_orig = $dep_tot_terceros;
    	$dep_tot_terceros2  = number_format($dep_tot_terceros, 2, ',','.');
		$pdf->Cell(20,7,utf8_decode('Depósitos a terceros: -----------------------  $ '),0,0,'L');
        $pdf->SetX(80);
        $pdf->Cell(20,7,$dep_tot_terceros2,0,0,'R');
        $valorY += 5;
	} 

	if ($total_retenciones!=0) {
    	$total_retenciones2  = number_format($total_retenciones, 2, ',','.');	 
		$pdf->SetY($valorY);
		$pdf->Cell(20,7,'Retenciones :-------------------------------  $ ',0,0,'L');
        $pdf->SetX(80);
        $pdf->Cell(20,7,$total_retenciones2,0,0,'R');
        //$pdf->Cell(20,7,'  U$S  67,21',0,0,'L');
        $valorY += 5;
	} 
    $pdf->SetX(15);
	$pdf->SetY($valorY);
	if ($rem_tot!=0) {
        $rem_tot_orig = $rem_tot;
    	$rem_tot2  = number_format($rem_tot, 2, ',','.');
		$pdf->Cell(20,7,utf8_decode('Compensación por Remate: ------------  $ '),0,0,'L');
        $pdf->SetX(80);
        $pdf->Cell(20,7,$rem_tot2,0,0,'R');
        $valorY += 5;
	} 
	$pdf->SetX(15);
	$pdf->SetY(130);
	$pdf->Cell(20,10,'Detalle de Pago:',0,0,'L');	
    $pos_Y = 134;
	$pdf->SetY($pos_Y);
	$y=134;
	$cheques_texto =1;
    $cheques_texto11 =1;
    $depositos_texto =1;
	$depositos_texto_terceros =1;
    $efectivo_texto = 1;
	$retenciones_texto = 1;
	$str_cheques = "";
    $str_cheques11 = "";
	$pdf->SetFont('Arial','',10);
	if (mysqli_num_rows($selec_cheques)!=0) {	
        $j = 0;
    	while ($cheques = mysqli_fetch_array($selec_cheques, MYSQLI_BOTH)){
     		$codban =$cheques['0'];
     		$codsuc =$cheques['1'];
     		$codchq =substr($cheques['2'],0,10);
            $codfec =$cheques['4'];
            $fechachq = substr($codfec,8,2)."/".substr($codfec,5,2)."/".substr($codfec,0,4);
    	 	if ( $cheques_texto==1) {
	 			$pdf->Cell(20,10,'Cheques:',0,0,'L');	
	    		$cheques_texto = $cheques_texto+1;
       		}
	   		$importe_cheque =$cheques['3'];
	   		$query_de_bancos = "SELECT *  FROM bancos WHERE codnum ='$codban'";
	        $selecciona_bancos = mysqli_query($amercado,  $query_de_bancos) or die("ERROR LEYENDO 481");
	   		$row_bancos = mysqli_fetch_assoc($selecciona_bancos);
	   		$nombre_bancos =  substr($row_bancos['nombre'],0,30);
	   	   	$total_cheques2=$total_cheques2+$importe_cheque;
			$importe_cheque2= number_format($importe_cheque, 2, ',','.');
      		$str_cheques = $nombre_bancos."- Nº ".$codchq."- $ ".$importe_cheque2." - Fecha: ".$fechachq." - ".$str_cheques ;
            $lista_chq[$j] = $nombre_bancos."- Nº ".$codchq." - $ ".$importe_cheque2." - Fecha: ".$fechachq." - " ;         
            $j++;
	 	} 
	    //echo $str_cheques;
		if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117) 
		 	$str_cheques = $str_cheques." Retira contra acreditacion de cheques."; 
		
	 	$total_cheques= number_format($total_cheques2, 2, ',','.');
		$largo_str = strlen($str_cheques);
        //echo "LARGO : ".$largo_str." - ";
        if (isset($lista_chq[0]))
            $ley_chk1 = $lista_chq[0]; //substr($str_cheques,0,64);
        if (isset($lista_chq[1]))
   		   $ley_chk2 = $lista_chq[1]; //substr($str_cheques,64,64);
        if (isset($lista_chq[2]))
   		   $ley_chk3 = $lista_chq[2]; //substr($str_cheques,128,64);
        if (isset($lista_chq[3]))
   		   $ley_chk4 = $lista_chq[3]; //substr($str_cheques,192,64);
        if (isset($lista_chq[4]))
   		   $ley_chk5 = $lista_chq[4]; //substr($str_cheques,256,64);
        if (isset($lista_chq[5]))
   		   $ley_chk6 = $lista_chq[5]; //substr($str_cheques,320,64);
        if (isset($lista_chq[6]))
   		   $ley_chk7 = $lista_chq[6]; //substr($str_cheques,384,64);
        if (isset($lista_chq[7]))
   		   $ley_chk8 = $lista_chq[7]; //substr($str_cheques,980,140);
        if (isset($lista_chq[8]))
   		   $ley_chk9 = $lista_chq[8]; //substr($str_cheques,1120,140);
        if (isset($lista_chq[9]))
   		   $ley_chk10 = $lista_chq[9]; //substr($str_cheques,1260,140);
        if (isset($lista_chq[10]))
   		   $ley_chk11 = $lista_chq[10]; //substr($str_cheques,1400,140);
        if (isset($lista_chq[11]))
   		   $ley_chk12 = $lista_chq[11]; //substr($str_cheques,1540,140);
        if (isset($lista_chq[12]))
   		   $ley_chk13 = $lista_chq[12]; //substr($str_cheques,1680,140);
        if (isset($lista_chq[13]))
   		   $ley_chk14 = $lista_chq[13]; //substr($str_cheques,1820,140);
        if (isset($lista_chq[14]))
   		   $ley_chk15 = $lista_chq[14]; //substr($str_cheques,1960,140);
        if (isset($lista_chq[15]))
   		   $ley_chk16 = $lista_chq[15]; //substr($str_cheques,2100,140);
        if (isset($lista_chq[16]))
		  $ley_chk17 = $lista_chq[16]; //substr($str_cheques,2240,140);
        if (isset($lista_chq[17]))
		  $ley_chk18 = $lista_chq[17]; //substr($str_cheques,2380,140);
        if (isset($lista_chq[18]))
		  $ley_chk19 = $lista_chq[18]; //substr($str_cheques,2520,140);
        if (isset($lista_chq[19]))
		  $ley_chk20 = $lista_chq[19]; //substr($str_cheques,2660,140);
        if (isset($lista_chq[20]))
		  $ley_chk21 = $lista_chq[20]; //substr($str_cheques,2800,140);
        if (isset($lista_chq[21]))
		  $ley_chk22 = $lista_chq[21]; //substr($str_cheques,2940,140);
        if (isset($lista_chq[22]))
		  $ley_chk23 = $lista_chq[22]; //substr($str_cheques,3080,140);
        if (isset($lista_chq[23]))
		  $ley_chk24 = $lista_chq[23]; //substr($str_cheques,3220,140);
        if (isset($lista_chq[24]))
		  $ley_chk25 = $lista_chq[24]; //substr($str_cheques,3360,140);
        if (isset($lista_chq[25]))
		  $ley_chk26 = $lista_chq[25]; //substr($str_cheques,3360,140);
        if (isset($lista_chq[26]))
		  $ley_chk27 = $lista_chq[26]; //substr($str_cheques,3360,140);
        if (isset($lista_chq[27]))
		  $ley_chk28 = $lista_chq[27]; //substr($str_cheques,3360,140);
        if (isset($lista_chq[28]))
		  $ley_chk29 = $lista_chq[28]; //substr($str_cheques,3360,140);
   		$y=$y+4;
        //echo (strlen($str_cheques));
  		if ($j == 1) {
    		$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
    		$y = $y+4 ;
   			$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
	   			$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');		
  		}

  		if ($j == 2) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
    		$y = $y+5 ;
   			$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
	   			$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
   		}
  		if ($j == 3) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   			$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
   			$y = $y+5 ;
   			$pdf->SetY($y);
   			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else		
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		} 
    	if ($j == 4) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$pdf->SetFont('Arial','',10);
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		} 
     	if ($j == 5) {
		  	$pdf->SetY($y);
		  	$pdf->SetFont('Arial','',10);
		  	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
		  	$y = $y+4 ;
		  	$pdf->SetY($y);
		  	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		  	$y = $y+4 ;
		  	$pdf->SetY($y);
		  	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		  	$y = $y+4 ;
		  	$pdf->SetY($y);
		  	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		  	$y = $y+4 ;
		  	$pdf->SetY($y);
		  	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
		  	$pdf->SetFont('Arial','B',10);
		  	$y = $y+5 ;
		  	$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			  	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
     	if ($j == 6) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','',10);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');	
		   	$pdf->SetFont('Arial','',10);
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
		   		$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
     	if ($j == 7) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','',10);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		  	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
		   		$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
    	if ($j == 8) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','',10);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		  	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
		   		$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
   		if ($j == 9) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','',10);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
  		if ($j == 10) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
   		if ($j == 11) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
    	if ($j == 12) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditacion de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
     	if ($j == 13) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 14) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		} 
  		if ($j == 15) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		} 
   		if ($j == 16) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		} 
 	
	   	if ($j == 17) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		} 
		if ($j == 18) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 19) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 20) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk19,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk20,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 21) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk21,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 22) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk22,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 23) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk23,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 24) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',10);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk23,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk24,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 25) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','',10);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk23,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk24,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk25,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
		if ($j == 26) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk23,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk24,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk25,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk26,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
        if ($j == 27) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk23,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk24,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk25,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk26,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk27,0,0,'L');
            
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}
        if ($j == 28) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk23,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk24,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk25,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk26,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk27,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
            $pdf->Cell(20,10,$ley_chk28,0,0,'L');
            
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques: $ ".$total_cheques,0,0,'L');
  		}

 	}
	
 	if (mysqli_num_rows($selec_cheques11)!=0) {	  
	    $y = $y+4 ;
        $pdf->SetY($y);
    	while ($cheques11 = mysqli_fetch_array($selec_cheques11, MYSQLI_BOTH)){
     		$codban =$cheques11['0'];
     		$codsuc =$cheques11['1'];
     		$codchq =$cheques11['2'];
    	 	if ( $cheques_texto11 == 1) {
	 			$pdf->Cell(20,10,'Cheques a 3ros.:',0,0,'L');	
	    		$cheques_texto11 = $cheques_texto11+1;
       		}
	   		$importe_cheque11 =$cheques11['3'];
	   		$query_de_bancos = "SELECT *  FROM bancos WHERE codnum ='$codban'";
	        $selecciona_bancos = mysqli_query($amercado,  $query_de_bancos) or die("ERROR LEYENDO 1838");
	   		$row_bancos = mysqli_fetch_assoc($selecciona_bancos);
	   		$nombre_bancos =  $row_bancos['nombre'];
	   	   	$total_cheques112=$total_cheques112+$importe_cheque11;
			$importe_cheque112= number_format($importe_cheque11, 2, ',','.');
      		$str_cheques11 = $nombre_bancos."- Nº".$codchq."- $".$importe_cheque112." ,".$str_cheques11 ;
	 	} 
	
		if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117) 
		 	$str_cheques11 = $str_cheques11." Retira contra acreditación de cheques."; 
		
	 	$total_cheques11 = number_format($total_cheques112, 2, ',','.');
		$largo_str = strlen($str_cheques11);
        $ley_chk1 = substr($str_cheques11,0,140);
   		$ley_chk2 = substr($str_cheques11,140,140);
   		$ley_chk3 = substr($str_cheques11,280,140);
   		$ley_chk4 = substr($str_cheques11,420,140);
   		$ley_chk5 = substr($str_cheques11,560,140);
   		$ley_chk6 = substr($str_cheques11,700,140);
   		$ley_chk7 = substr($str_cheques11,840,140);
   		$ley_chk8 = substr($str_cheques11,980,140);
   		$ley_chk9 = substr($str_cheques11,1120,140);
   		$ley_chk10 = substr($str_cheques11,1260,140);
   		$ley_chk11 = substr($str_cheques11,1400,140);
   		$ley_chk12 = substr($str_cheques11,1540,140);
   		$ley_chk13 = substr($str_cheques11,1680,140);
   		$ley_chk14 = substr($str_cheques11,1820,140);
   		$ley_chk15 = substr($str_cheques11,1960,140);
   		$ley_chk16 = substr($str_cheques11,2100,140);
		$ley_chk17 = substr($str_cheques11,2240,140);
		$ley_chk18 = substr($str_cheques11,2380,140);
		$ley_chk19 = substr($str_cheques11,2520,140);
		$ley_chk20 = substr($str_cheques11,2660,140);
		$ley_chk21 = substr($str_cheques11,2800,140);
		$ley_chk22 = substr($str_cheques11,2940,140);
		$ley_chk23 = substr($str_cheques11,3080,140);
		$ley_chk24 = substr($str_cheques11,3220,140);
		$ley_chk25 = substr($str_cheques11,3360,140);
   		$y=$y+4;
  
  		if (strlen($str_cheques11) < 141) {
    		$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
    		$y = $y+5 ;
   			$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
	   			$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');		
  		}
  		if ((strlen($str_cheques11) < 281) and (strlen($str_cheques11) >140 )) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','B',7);
   			$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   			$pdf->SetFont('Arial','B',10);
    		$y = $y+5 ;
   			$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
	   			$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
   		}
  		if ((strlen($str_cheques11) < 421) and (strlen($str_cheques11) >280 )) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','B',7);
   			$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   			$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   			$pdf->SetFont('Arial','B',10);
   			$y = $y+5 ;
   			$pdf->SetY($y);
   			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else		
			   	$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		} 
    	if ((strlen($str_cheques11) < 561) and (strlen($str_cheques11) >420 )) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','B',7);
   			$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros. : $ ".$total_cheques11,0,0,'L');
  		} 
     	if ((strlen($str_cheques11) < 701) and (strlen($str_cheques11) >560 )) {
		  	$pdf->SetY($y);
		  	$pdf->SetFont('Arial','B',7);
		  	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
		  	$y = $y+4 ;
		  	$pdf->SetY($y);
		  	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		  	$y = $y+4 ;
		  	$pdf->SetY($y);
		  	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		  	$y = $y+4 ;
		  	$pdf->SetY($y);
		  	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		  	$y = $y+4 ;
		  	$pdf->SetY($y);
		  	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
		  	$pdf->SetFont('Arial','B',10);
		  	$y = $y+5 ;
		  	$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			  	$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
     	if ((strlen($str_cheques11) < 841) and (strlen($str_cheques11) >700 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');	
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
		   		$pdf->Cell(20,10,"Total cheques a 3ros: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
     	if ((strlen($str_cheques11) < 981) and (strlen($str_cheques11) >840 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		  	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
		   		$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
    	if ((strlen($str_cheques11) < 1121) and (strlen($str_cheques11) >980 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		  	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
		   		$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
   		if ((strlen($str_cheques11) < 1261) and (strlen($str_cheques11) >1120 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
  		if ((strlen($str_cheques11) < 1401) and (strlen($str_cheques11) >1260 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
   		if ((strlen($str_cheques11) < 1541) and (strlen($str_cheques11) >1400 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
    	if ((strlen($str_cheques11) < 1681) and (strlen($str_cheques11) >1540 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
     	if ((strlen($str_cheques11) < 1821) and (strlen($str_cheques11) >1680 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 1961) and (strlen($str_cheques11) >1820 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		} 
  		if ((strlen($str_cheques11) < 2101) and (strlen($str_cheques11) >1960 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		} 
   		if ((strlen($str_cheques11) < 2241) and (strlen($str_cheques11) >2100 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		} 
 	
	   	if ((strlen($str_cheques11) < 2381) and (strlen($str_cheques11) >2240 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
			   	$pdf->Cell(20,10,"Total cheques a 3ros: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros: $ ".$total_cheques11,0,0,'L');
  		} 
		if ((strlen($str_cheques11) < 2521) and (strlen($str_cheques11) >2380 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
			   	$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 2661) and (strlen($str_cheques11) >2520 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
			   	$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 2801) and (strlen($str_cheques11) >2660 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)	
			   	$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 2941) and (strlen($str_cheques11) >2800 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
		   	if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
				$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 3081) and (strlen($str_cheques11) >2940 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 3221) and (strlen($str_cheques11) >3080 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros: $ ".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.: $ ".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 3361) and (strlen($str_cheques11) >3220 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk23,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 3501) and (strlen($str_cheques11) >3360 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk23,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk24,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11,0,0,'L');
  		}
		if ((strlen($str_cheques11) < 3641) and (strlen($str_cheques11) >3500 )) {
		   	$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_chk1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk4,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk5,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk6,0,0,'L');   
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk7,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk8,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk9,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk10,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk11,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk12,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk13,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk14,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk15,0,0,'L');
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk16,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk17,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk18,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk19,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk20,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk21,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk22,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk23,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk24,0,0,'L');
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_chk25,0,0,'L');
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
			$pdf->SetFont('Arial','B',10);
			if ($fact_tcomp[0] == 115 || $fact_tcomp[0] == 116 || $fact_tcomp[0] == 117)
			   	$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11." Retira contra acreditación de cheques",0,0,'L');
			else
				$pdf->Cell(20,10,"Total cheques a 3ros.:$".$total_cheques11,0,0,'L');
  		}
 	}

    // Depositos
 	$total_depositos=0;
 	$str_deposito = "";
    $depositos_texto = 1;
 	if (mysqli_num_rows($selec_depositos)!=0) {	
        $q = 0;
    	while ($depositos = mysqli_fetch_array($selec_depositos, MYSQLI_BOTH)){
        
     		$codban =$depositos['0'];
     		$codsuc =$depositos['1'];
     		$codchq =$depositos['2'];
            $codfec = $depositos['4'];
            $fechadep = substr($codfec,8,2)."/".substr($codfec,5,2)."/".substr($codfec,0,4);
               
	 		if ( $depositos_texto==1) {
	 			$y = $y+4 ;
     			$pdf->SetY($y);
	 			$pdf->Cell(20,10,utf8_decode('Depósitos:'),0,0,'L');	
	    		$depositos_texto = $depositos_texto+1;
       		}
	   		$importe_deposito =$depositos['3'];
	   		$query_de_bancos = "SELECT *  FROM bancos WHERE codnum ='$codban'";
	        $selecciona_bancos = mysqli_query($amercado,  $query_de_bancos) or die("ERROR LEYENDO 1889");
	   		$row_bancos = mysqli_fetch_assoc($selecciona_bancos);
	   		$nombre_bancos =  $row_bancos['nombre'];
	   	   	$total_depositos = $total_depositos + $importe_deposito ;
			$importe_deposito2 = number_format($importe_deposito, 2, ',','.');
      		$str_deposito = $nombre_bancos.utf8_decode("- Nº").$codchq."- $ ".$importe_deposito2." Fecha: ".$fechadep." ,".$str_deposito ;
            $lista_dep[$q] = $nombre_bancos.utf8_decode("- Nº").$codchq."- $ ".$importe_deposito2." Fecha: ".$fechadep." ," ;
            $q++;
	 	} 
	 	//$str_deposito = $str_deposito ; 
	 	$total_depositos2 = number_format($total_depositos, 2, ',','.');
		$largo_str = strlen($str_deposito);
        if(isset($lista_dep[0]))
            $ley_dep1 = $lista_dep[0]; //substr($str_deposito,0,140);
        if(isset($lista_dep[1]))
   		   $ley_dep2 = $lista_dep[1]; //substr($str_deposito,140,140);
        if(isset($lista_dep[2]))
   		   $ley_dep3 = $lista_dep[2]; //substr($str_deposito,280,140);
        if(isset($lista_dep[3]))
   		   $ley_dep4 = $lista_dep[3]; //substr($str_deposito,420,140);
        if(isset($lista_dep[4]))
            $ley_dep5 = $lista_dep[4]; //substr($str_deposito,560,140);
        if(isset($lista_dep[5]))
            $ley_dep6 = $lista_dep[5]; //substr($str_deposito,720,140);
        if(isset($lista_dep[6]))
            $ley_dep7 = $lista_dep[6]; //substr($str_deposito,720,140);
        if(isset($lista_dep[7]))
            $ley_dep8 = $lista_dep[7]; //substr($str_deposito,720,140);
        if(isset($lista_dep[8]))
            $ley_dep9 = $lista_dep[8]; //substr($str_deposito,720,140);
        if(isset($lista_dep[9]))
            $ley_dep10 = $lista_dep[9]; //substr($str_deposito,720,140);
        if(isset($lista_dep[10]))
            $ley_dep11 = $lista_dep[10]; //substr($str_deposito,720,140);
        if(isset($lista_dep[11]))
            $ley_dep12 = $lista_dep[11]; //substr($str_deposito,720,140);
        if(isset($lista_dep[12]))
            $ley_dep13 = $lista_dep[12]; //substr($str_deposito,720,140);
   		$y=$y+4;
   
  		if ($q == 1) {
    		$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,utf8_decode("Total depósitos:      $ ").$total_depositos2,0,0,'L');
  		}
  		if ($q == 2) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,utf8_decode("Total depósitos:       $ ").$total_depositos2,0,0,'L');
   		}
  		if ($q == 3) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
   			$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
   			$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,utf8_decode("Total depósitos:       $ ").$total_depositos2,0,0,'L');
  		} 
    	if ($q == 4) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:      $ ").$total_depositos2,0,0,'L');
  		} 
        if ($q == 5) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:       $ ").$total_depositos2,0,0,'L');
  		} 
        if ($q == 6) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep6,0,0,'L');
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:        $ ").$total_depositos2,0,0,'L');
  		} 
        if ($q == 7) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep6,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep7,0,0,'L');
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:        $ ").$total_depositos2,0,0,'L');
  		} 
        if ($q == 8) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep6,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep7,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep8,0,0,'L');
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:       $ ").$total_depositos2,0,0,'L');
  		} 
        if ($q == 9) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep6,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep7,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep8,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep9,0,0,'L');
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:           $ ").$total_depositos2,0,0,'L');
  		} 
        if ($q == 10) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep6,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep7,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep8,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep9,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep10,0,0,'L');
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:            $ ").$total_depositos2,0,0,'L');
  		} 
        if ($q == 11) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep6,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep7,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep8,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep9,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep10,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep11,0,0,'L');
            $pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:            $ ").$total_depositos2,0,0,'L');
  		}
        if ($q == 12) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep6,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep7,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep8,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep9,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep10,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep11,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep12,0,0,'L');
            $pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:            $ ").$total_depositos2,0,0,'L');
  		}
        if ($q >= 13) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4,0,0,'L');	
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep5,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep6,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep7,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep8,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep9,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep10,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep11,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep12,0,0,'L');
            $y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep13,0,0,'L');
            $pdf->SetFont('Arial','B',10);
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,utf8_decode("Total depósitos:            $ ").$total_depositos2,0,0,'L');
  		}
 	}
	//=========================================================================
	// Depositos a terceros
 	$total_depositos_terceros=0;
 	$str_deposito_terceros = "";
 	if (mysqli_num_rows($selec_depositos_terceros)!=0) {	   
    	while ($depositos_terceros = mysqli_fetch_array($selec_depositos_terceros)){

     		$codban_terceros =$depositos_terceros['0'];
     		$codsuc_terceros =$depositos_terceros['1'];
     		$codchq_terceros =$depositos_terceros['2'];
    
	 		if ( $depositos_texto_terceros==1) {
	 			$y = $y+4 ;
     			$pdf->SetY($y);
	 			$pdf->Cell(20,10,utf8_decode('Depósitos a terceros: '),0,0,'L');	
	    		$depositos_texto_terceros = $depositos_texto_terceros+1;
       		}
	   		$importe_deposito_terceros =$depositos_terceros['3'];
	   		// mysqli_select_db($database_amercado);
	   		$query_de_bancos_terceros = "SELECT *  FROM bancos WHERE codnum ='$codban_terceros'";
	        $selecciona_bancos_terceros = mysqli_query($amercado,  $query_de_bancos_terceros) or die("ERROR LEYENDO 1901");
	   		$row_bancos_terceros = mysqli_fetch_assoc($selecciona_bancos_terceros);
	   		$nombre_bancos_terceros =  $row_bancos_terceros['nombre'];
	   	   	$total_depositos_terceros=$total_depositos_terceros+$importe_deposito_terceros ;
			$importe_deposito_terceros2 = number_format($importe_deposito_terceros, 2, ',','.');
      		$str_deposito_terceros = $nombre_bancos_terceros."- Nº".$codchq_terceros."- $".$importe_deposito_terceros2.",".$str_deposito_terceros ;
	 	} 
	 	$str_deposito_terceros = $str_deposito_terceros ; 
	 	$total_depositos_terceros2 = number_format($total_depositos_terceros, 2, ',','.');
		$largo_str_terceros = strlen($str_deposito_terceros);
        $ley_dep1_terceros = substr($str_deposito_terceros,0,140);
   		$ley_dep2_terceros = substr($str_deposito_terceros,140,140);
   		$ley_dep3_terceros = substr($str_deposito_terceros,280,140);
   		$ley_dep4_terceros = substr($str_deposito_terceros,420,140);
   		$y=$y+4;
   
  		if (strlen($str_deposito_terceros) < 141) {
    		$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_dep1_terceros,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
    		$y = $y+5 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,utf8_decode("Total depósitos a terceros:       $ ").$total_depositos_terceros2,0,0,'L');
  		}
  		if ((strlen($str_deposito_terceros) < 281) and (strlen($str_deposito_terceros) >140 )) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_dep1_terceros,0,0,'L');	
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_dep2_terceros,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
    		$y = $y+5 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,"Total dep a terceros:        $ ".$total_depositos_terceros2,0,0,'L');
   		}
  		if ((strlen($str_deposito_terceros) < 421) and (strlen($str_deposito_terceros) >280 )) {
   			$pdf->SetY($y);
   			$pdf->SetFont('Arial','',10);
   			$pdf->Cell(20,10,$ley_dep1_terceros,0,0,'L');	
    		$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_dep2_terceros,0,0,'L');	
   			$y = $y+4 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,$ley_dep3_terceros,0,0,'L');	
   			$pdf->SetFont('Arial','',10);
   			$y = $y+5 ;
   			$pdf->SetY($y);
   			$pdf->Cell(20,10,"Total dep a terceros:         $ ".$total_depositos_terceros2,0,0,'L');
  		} 
    	if ((strlen($str_deposito_terceros) < 561) and (strlen($str_deposito_terceros) >420 )) {
   			$pdf->SetY($y);
		   	$pdf->SetFont('Arial','B',7);
		   	$pdf->Cell(20,10,$ley_dep1_terceros,0,0,'L');	
			$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep2_terceros,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep3_terceros,0,0,'L');	
		   	$y = $y+4 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,$ley_dep4_terceros,0,0,'L');	
		   	$pdf->SetFont('Arial','B',10);
		   	$y = $y+5 ;
		   	$pdf->SetY($y);
		   	$pdf->Cell(20,10,"Total dep a 3ros:       $ ".$total_depositos_terceros2,0,0,'L');
  		} 
 	}
	//=========================================================================
  	if (  mysqli_num_rows($selec_efectivo_2)!=0) {	
  		
        if ($tcompefe2 == 91) {
                $y = $y+4 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,utf8_decode('Pago con Crédito '),0,0,'L');	
                $y = $y+4 ;
                $pdf->SetY($y);

                $pdf->Cell(30,10,utf8_decode('Total pago con Crédito: $  ').$efe_tot_2f,0,0,'L');	
                $efectivo_texto = $efectivo_texto+1;
        }
        
    }
    if (mysqli_num_rows($selec_efectivo_3)!=0) {
        if ($tcompefe3 == 98 ) {
            $y = $y+4 ;
            $pdf->SetY($y);
            $pdf->Cell(30,10,utf8_decode('Nuevo Crédito disponible: $ ').$efe_tot_3f,0,0,'L');	
            $efectivo_texto = $efectivo_texto+1;
        }
    }
    if (mysqli_num_rows($selec_efectivo_4)!=0) {           
        if ($tcompefe4 == 97) {
            $pdf->Cell(20,10,': ',0,0,'L');	
            $y = $y+4 ;
            $pdf->SetY($y);
            $pdf->Cell(20,10,'Total N Cred : $   '.$efe_tot_4f,0,0,'L');	
        }
    }
    if (mysqli_num_rows($selec_efectivo)!=0) {
        if ($tcompefe == 12) {
            $y = $y+4 ;
            $pdf->SetY($y);
            $pdf->Cell(20,10,'Efectivo',0,0,'L');	
            $y = $y+4 ;
            $pdf->SetY($y);
            $pdf->Cell(20,10,'Total efectivo: $ '.$efe_totf,0,0,'L');	
            $efectivo_texto = $efectivo_texto+1;
        }	
        if ($tcompefe == 13) {
            $y = $y+4 ;
            $pdf->SetY($y);
            $pdf->Cell(20,10,utf8_decode('Depósito en dólares Bco Francés 123-060206/2'),0,0,'L');	
            $y = $y+4 ;
            $pdf->SetY($y);
            $pdf->Cell(20,10,'Total U$S :  '.$efe_totf,0,0,'L');	
            $efectivo_texto = $efectivo_texto+1;
        }	
    }
    //===========================================REMATES======================================
 	if (mysqli_num_rows($selec_rem)!=0) {	
  		if ( $rem_texto!="") {
                while ($rem = mysqli_fetch_array($selec_rem, MYSQLI_BOTH)){
					$y = $y+4 ;
					$pdf->SetY($y);
                    $rem_ncomp = $rem["ncomp"];
                    $rem_importe = $rem["importe"];
                    
                    $rem_importe = number_format($rem_importe, 2, ',','.');
					$pdf->Cell(20,10,utf8_decode('Compensación por Remate ').$rem_ncomp.": $ ".$rem_importe,0,0,'L');	
                }
                $y = $y+4 ;
				$pdf->SetY($y);
                $rem_tot2 = number_format($rem_tot, 2, ',','.');
				$pdf->Cell(20,10,utf8_decode('Total compensación por Remates:  $ ').$rem_tot2,0,0,'L');	
					
        }
	}
    //==============================================================================================
 	$ret_iva = "";
	$ret_suss = "";
	$ret_gan = "";
	$ret_ing_br = "";
	if ((mysqli_num_rows($selec_ganancias)!=0) or (mysqli_num_rows($selec_ing_brutos)!=0)	or (mysqli_num_rows($selec_suss)!=0) or (mysqli_num_rows($selec_iva)!=0)) {  
     	$y = $y+4 ;
     	$pdf->SetY($y);
	 	$pdf->Cell(20,10,'Retenciones:',0,0,'L'); 
        //$y = $y+4 ;
	  	if ((mysqli_num_rows($selec_ganancias)!=0)) {
	  		$total_ganancia=0;
	  		$ganancia_texto= 1;
	  		while ($ganancia = mysqli_fetch_array($selec_ganancias, MYSQLI_BOTH)){	
      			$codban =$ganancia['0'];
      			$importe_ganancia =$ganancia['1'];
	 			if ( $ganancia_texto==1) {
	  				//$y = $y+4 ;
      				$pdf->SetY($y);
	  				$pdf->SetFont('Arial','',10);
	  				$pdf->Cell(20,10,'Retención de Ganancias',0,0,'L'); 
	    			$ganancia_texto =  2;
       			}
	   			$total_ganancia=$total_ganancia+$importe_ganancia;
				$importe_ganancia= number_format($importe_ganancia, 2, ',','.');
     			$ret_gan = $codban." - $ ".$importe_ganancia." ,".$ret_gan ;
	  		} 
	  		$y = $y+4 ;
      		$pdf->SetY($y);
	  		$pdf->SetFont('Arial','',10);
	  		$pdf->Cell(20,10,$ret_gan,0,0,'L'); 
	  		$y = $y+4 ;
      		$pdf->SetY($y);
	   		$pdf->SetFont('Arial','',10);
	   		$total_ganancia2 = number_format($total_ganancia, 2, ',','.');
	  		$pdf->Cell(20,10,'Total Retención Ganancias: $ '.$total_ganancia2,0,0,'L');
	 
	  	}
		
        // ESTA ES LA RUTINA QUE IMPRIME LAS RETENCIONES A PARTIR DE OCTUBRE DE 2018
        if ((mysqli_num_rows($selec_ing_brutos)!=0)) {
	  
	  		$total_ing_brutos =0;
	  		$ing_brutos_texto= 1;
	  		while ($ing_brutos = mysqli_fetch_array($selec_ing_brutos, MYSQLI_BOTH)){	

     			$codban =$ing_brutos['0'];
     			$importe_ing_brutos =$ing_brutos['1'];
	 			$tcomp_ret = $ing_brutos['2'];
				if ( $ing_brutos_texto==1) {
      				$pdf->SetY($y);
					$pdf->SetFont('Arial','',10);
	  				$pdf->Cell(20,10,' ',0,0,'L'); 
       				$ing_brutos_texto =  2;
       			}
  				// Aca leo el tipcomp para ver la descripcion de la retención
				$query_tipcomp_ret = "SELECT * FROM tipcomp WHERE codnum = $tcomp_ret";
				$tipcomp_ret = mysqli_query($amercado,  $query_tipcomp_ret) or die("ERROR LEYENDO RETENCIONES 2070");
				$row_tipcomp_ret = mysqli_fetch_assoc($tipcomp_ret);
				$totalRows_tipcomp_ret = mysqli_num_rows($tipcomp_ret);
	  			$descr_ret = $row_tipcomp_ret['descripcion'];
	   			$total_ing_brutos = $total_ing_brutos+$importe_ing_brutos;
		       	$ret_ing_br = $descr_ret." -".$codban." - $ ".$importe_ing_brutos." , ";
	  		    $y = $y+4 ;
      			$pdf->SetY($y);
	  			$pdf->SetFont('Arial','',10);
	  			$pdf->Cell(20,10,$ret_ing_br,0,0,'L');
			}
	  		$y = $y+4 ;
     	 	$pdf->SetY($y);
	  		$pdf->SetFont('Arial','',10);
	  		$total_ing_brutos = number_format($total_ing_brutos, 2, ',','.');
            if ($pncomp == 32645)
	 		    $pdf->Cell(20,10,'Total Retenciones $ '.$total_ing_brutos.' U$S 67,21',0,0,'L');
            else
                $pdf->Cell(20,10,'Total Retenciones $ '.$total_ing_brutos,0,0,'L');
	  	}
	
	   	if ((mysqli_num_rows($selec_iva)!=0)) {
	  		$total_iva =0;
	  		$ing_iva_texto= 1;
	  		while ($iva = mysqli_fetch_array($selec_iva)){	
     			$codban =$iva['0'];
     			$importe_iva =$iva['1'];
		 		if ( $ing_iva_texto ==1) {
      				$pdf->SetY($y);
	  				$pdf->SetFont('Arial','',10);
	  				$pdf->Cell(20,10,'Retención de IVA',0,0,'L'); 
       				$ing_iva_texto =  2;
       			}
  
	   			$total_iva = $total_iva + $importe_iva;
		       	$ret_iva = $codban." - $ ".$importe_iva." ,".$ret_iva ;
	  		} 
	  		$y = $y+4 ;
      		$pdf->SetY($y);
	  		$pdf->SetFont('Arial','',10);
	  		$pdf->Cell(20,10,$ret_iva,0,0,'L');
	  		$y = $y+4 ;
      		$pdf->SetY($y);
	  		$pdf->SetFont('Arial','',10);
	 		$pdf->Cell(20,10,'Total Retención IVA: $ '.$total_iva,0,0,'L');
	   	}
	  	// SUSS  
	 	if ((mysqli_num_rows($selec_suss)!=0)) {
	    	$total_suss =0;
	  		$ing_suss_texto= 1;
	  		while ($sus = mysqli_fetch_array($selec_suss, MYSQLI_BOTH)){	
     			$codban =$sus['0'];
     			$importe_sus =$sus['1'];
		 		if ( $ing_suss_texto ==1) {
	  				//$y = $y+4 ;
      				$pdf->SetY($y);
	  				$pdf->SetFont('Arial','',10);
	  				$pdf->Cell(20,10,'Retención de SUSS',0,0,'L'); 
       				$ing_suss_texto =  2;
       			}
  	   			$total_suss = $total_suss + $importe_sus;
		       	$ret_suss = $codban." - $ ".$importe_sus." ,".$ret_suss ;
	  		} 
	  		$y = $y+4 ;
      		$pdf->SetY($y);
	  		$pdf->SetFont('Arial','',10);
	  		$pdf->Cell(20,10,$ret_suss,0,0,'L');
	  		$y = $y+4 ;
      		$pdf->SetY($y);
	  		$pdf->SetFont('Arial','B',8);
	 		$pdf->Cell(20,10,'Total Retención SUSS $ '.$total_suss,0,0,'L');
	  	}
	}
  
	$pdf->SetY(270);
    if ($pncomp != 32645) {
	   $pdf->Cell(20,10,'Importe total: $ ',0,0,'L');
	   $pdf->SetX(40);
	   $pdf->Cell(0,10,$total2.'  ',0,0,'L');
    }
    else {
        $pdf->Cell(20,10,'Importe total: U$S ',0,0,'L');
	    $pdf->SetX(40);
	    $pdf->Cell(0,10,'   1797,73 ',0,0,'L');
    }
	$pdf->SetY(280);
    $pdf->SetX(95);
    if ($k==0)
        $pdf->Cell(20,10,'ORIGINAL',0,0,'C');
    else
        $pdf->Cell(20,10,'DUPLICADO',0,0,'C');
}
	
// ================ACA LE AGREGO EL REMITO EN LOS CASOS QUE CORRESPONDA ====================
	
for ($j=0;$j < $tope_fact;$j++) {

    if ($fact_tcomp[$j] == 115 || $fact_tcomp[$j] == 116 || $fact_tcomp[$j] == 117 ) {
        for ($k=0;$k<2;$k++) {
        //echo "DENTRO DEL IF".$j."  ".$fact_tcomp[$j]."  ".$fact_serie[$j]."  ".$fact_ncomp[$j]."  ";
        // LEO LA FACTURA
        // Leo la cabecera de factura
        $query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $fact_tcomp[$j],$fact_serie[$j],$fact_ncomp[$j]);
        $cabecerafac = mysqli_query($amercado,  $query_cabfac) or die("ERROR LEYENDO 4087");
        $row_cabecerafac = mysqli_fetch_assoc($cabecerafac);

        $fecharem     = $row_cabecerafac["fecdoc"];
        $cliente      = $row_cabecerafac["cliente"];
        $remate       = $row_cabecerafac["codrem"];
        $nrodoc_rem   = $row_cabecerafac["nrodoc"];
        // Leo los renglones de la factura

        $query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s ORDER BY codlote" , $fact_tcomp[$j], $fact_serie[$j], $fact_ncomp[$j]);
        $detallefac = mysqli_query($amercado,  $query_detfac) or die("ERROR LEYENDO 3592");
        $totalRows_detallefac = mysqli_num_rows($detallefac);

        //Leo el remate
        $query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $remate);
        $remates = mysqli_query($amercado,  $query_remate) or die("ERROR LEYENDO 3597");
        $row_remates = mysqli_fetch_assoc($remates);

        $remate_ncomp = $row_remates["ncomp"];
        $remate_direc = $row_remates["direccion"];
        $remate_fecha = $row_remates["fecreal"];
        $loc_remate   = $row_remates["codloc"];
        $prov_remate  = $row_remates["codprov"];
        $cli_rem      = $row_remates["codcli"];
        $remate_fecha = substr($remate_fecha,8,2)."/".substr($remate_fecha,5,2)."/".substr($remate_fecha,0,4);

        $totalFilas = 0;
        //Leo si hay direccion de exposicion
        $query_remate_expo = sprintf("SELECT * FROM dir_remates WHERE codrem = %s", $remate);
        $remates_expo = mysqli_query($amercado,  $query_remate_expo) or die("ERROR LEYENDO 3610");
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
        $localidad_rem = mysqli_query($amercado,  $query_localidades_rem) or die("ERROR LEYENDO 3622");
        $row_localidades_rem = mysqli_fetch_assoc($localidad_rem);
        $nomlocrem = $row_localidades_rem["descripcion"];

        // Leo la Provincia del Remate
        $query_provincia_rem = sprintf("SELECT * FROM provincias WHERE  codnum = %s",$prov_remate);
        $provincia_rem = mysqli_query($amercado,  $query_provincia_rem) or die("ERROR LEYENDO 3628");
        $row_provincia_rem = mysqli_fetch_assoc($provincia_rem);
        $nomprovrem = $row_provincia_rem["descripcion"];


        // Leo el cliente
        $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
        $enti = mysqli_query($amercado,  $query_entidades) or die("ERROR LEYENDO 3635");
        $row_entidades = mysqli_fetch_assoc($enti);
        $nom_cliente   = $row_entidades["razsoc"];
        $calle_cliente = $row_entidades["calle"];
        $nro_cliente   = $row_entidades["numero"];
        $codp_cliente  = $row_entidades["codpost"];
        $loc_cliente   = $row_entidades["codloc"]; 
        $cuit_cliente  = $row_entidades["cuit"];
        $tel_cliente   = $row_entidades["tellinea"];
        $tipo_iva   =    $row_entidades["tipoiva"];
        // DATOS DE CONTACTO PARA RETIRO DE MERCADERIA
        // Leo el vendedor del remate 
        $query_entirem = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cli_rem);
        $entirem = mysqli_query($amercado,  $query_entirem) or die("ERROR LEYENDO 3917 ".$query_entirem." ");
        $row_entirem = mysqli_fetch_assoc($entirem);
        $tel_retiro   = $row_entirem["telcelu"];
        $cont_retiro  = $row_entirem["cargo"];
        $mail_retiro  = $row_entirem["pagweb"];

        // Leo la localidad
        $query_localidades = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_cliente);
        $localidad = mysqli_query($amercado,  $query_localidades) or die("ERROR LEYENDO LOCALIDADES ".$query_localidades);
        $row_localidades = mysqli_fetch_assoc($localidad);
        $nomloc = $row_localidades["descripcion"];

        // TIPO DE IVA 
        $sql_iva = sprintf("SELECT * FROM tipoiva WHERE  codnum = %s", $tipo_iva);
        $tipo_de_iva = mysqli_query($amercado,  $sql_iva) or die("ERROR LEYENDO TIPOS DE IVA ".$sql_iva." ");
        $row_tip_iva = mysqli_fetch_assoc($tipo_de_iva);
        $tip_iva_cliente = $row_tip_iva["descrip"];

        // LEO LA CABECERA DE REMITO A VER SI EXISTE 
        $sql_remi = sprintf("SELECT * FROM cabremi WHERE  tcomprel = %s and serierel = %s AND ncomprel = %s",  $fact_tcomp[$j], $fact_serie[$j], $fact_ncomp[$j]);
        $remi = mysqli_query($amercado,  $sql_remi )  or die("ERROR LEYENDO".$sql_remi." ");
        $row_remi = mysqli_fetch_assoc($remi);
        $remitonum = $row_remi["ncomp"];
        $tot_remitos = mysqli_num_rows($remi);
        if ($tot_remitos == 0) {
             // LEO ULTIMO NRO DE REMITO Y ACTUALIZO SERIES	
            $serie_remito = 28;
            $query_remito = sprintf("SELECT * FROM series WHERE  codnum = %s",$serie_remito);
            $remito = mysqli_query($amercado,  $query_remito) or die("ERROR LEYENDO ".$query_remito." ");
            $row_remito = mysqli_fetch_assoc($remito);
            $ultimo = $row_remito["nroact"];
            $remitonum = $ultimo + 1;
            $query_act_remito = sprintf("UPDATE series SET nroact = %s WHERE  codnum = %s",$remitonum, $serie_remito);
            $act_remito = mysqli_query($amercado,  $query_act_remito) or die("ERROR LEYENDO SERIES".$query_act_remito." ");
            // Grabo el remito si no existía
            $insert_remito = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi) VALUES ('50', '28', '$remitonum', '1' ,'$nrodoc_rem', '$fact_tcomp[$j]', '$fact_serie[$j]', '$fact_ncomp[$j]', '$fecha_rem')";
            $ins_remi = mysqli_query($amercado, $insert_remito) or die ("ERROR GRABANDO REMITO ".$insert_remito." ");
        }

        
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
        $pdf->SetFont('Arial','BI',14);
        $pdf->SetY(19);
        $pdf->SetX(120);
        $pdf->Cell(160,10,'Remito Nro.: ');
        $pdf->SetFont('Arial','BI',12);
        $pdf->SetY(24);
        $pdf->SetX(120);
        $pdf->Cell(100,10,utf8_decode('Fecha de Emisión: '));
        $pdf->SetFont('Arial','BI',10);
        $pdf->SetY(29);
        $pdf->SetX(120);
        $pdf->Cell(150,10,'CUIT: 30-71803361-2');
        $pdf->SetY(34);
        $pdf->SetX(120);
        $pdf->Cell(150,10,'Ingresos Brutos:  30-71803361-2');
        $pdf->SetY(38);
        $pdf->SetX(120);
        $pdf->Cell(150,10,'Fecha de Inicio de Actividades: 22/12/2022');
        $pdf->SetY(48);
        $pdf->SetX(10);
        $pdf->Cell(100,10,utf8_decode('Apellido y Nombre / Razón Social: '));
        $pdf->SetY(54);
        $pdf->SetX(10);
        $pdf->Cell(10,10,'Domicilio: ');
        $pdf->SetX(125);
        $pdf->SetY(66);
        $pdf->SetX(10);
        $pdf->Cell(100,10,utf8_decode('Condición frente al IVA: '));
        $pdf->SetY(54);
        $pdf->SetX(120);
        if (isset($mail_retiro)) {
            $pdf->Cell(20,10,utf8_decode('Teléfono p/retiro: '));
            $pdf->SetX(150);
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(30,10,$tel_retiro);
            $pdf->SetY(60);
            $pdf->SetX(120);
            $pdf->SetFont('Arial','BI',10);
            $pdf->Cell(50,10,'e-mail p/retiro: ');
            $pdf->SetX(146);
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(100,10,$mail_retiro);
        }
        else {

            $pdf->Cell(100,10,utf8_decode('Teléfono p/retiro: '));
            $pdf->SetX(150);
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(30,10,$tel_retiro);
        }
        $pdf->SetY(60);
        $pdf->SetX(10);
        $pdf->SetFont('Arial','BI',10);
        $pdf->Cell(150,10,'CUIT: ');
        $pdf->SetFont('Arial','',10); 
        $pdf->SetX(21);
        $pdf->Cell(150,10,$cuit_cliente);


        //Fecha
        $pdf->SetY(15);
        $pdf->SetX(10);
        if ($inmob == 1)
            $pdf->Image('images/Adrian-Mercado-Inmobiliaria_1.png',19,8);
        else
            $pdf->Image('marca_adrianmercado-subasta_version1.png',12,6);
        $pdf->Image('images/equis.jpg',101,7.5);
		$pdf->Line(9,7.5,200,7.5);
        $pdf->SetFont('Arial','BI',10);
        $pdf->SetY(29);
        $pdf->SetX(15);
        $pdf->Cell(150,10,'                 Olga Cossettini 731, Piso 3');
        $pdf->SetY(34);
        $pdf->SetX(15);
        $pdf->Cell(150,10,'CABA  (C1107CDA) Tel/Fax: +54 11 3984-7400');
        $pdf->SetY(39);
        $pdf->SetX(15);
        $pdf->Cell(150,10,'          IVA  RESPONSABLE  INSCRIPTO');
        //Arial bold 15
        $pdf->SetFont('Arial','',12);
        //Movernos a la derecha
        $pdf->Cell(80);
        //Título
        //Salto de línea
        $pdf->Ln(20);
        $pdf->SetY(24);
        $pdf->SetX(158);
        // ACA ESTA EL BOLONQUI DE LA FECHA
        //$fecharem = "21/11/2014";
        // Día del mes con 2 dígitos, y con ceros iniciales, de 01 a 31
        $dia = date("d");
        // Mes actual en 2 dígitos y con 0 en caso del 1 al 9, de 1 a 12
        $mes = date("m");
        // Año actual con 4 dígitos, ej 2022
        $anio = date("Y");
        $fecharem = $fecha; //$dia."/".$mes."/".$anio;
        $pdf->Cell(40,10,$fecharem,0,0,'L');
        $pdf->SetFont('Arial','',12);
        // Numero de Remito
        $pdf->SetY(19);
        $pdf->SetX(154);
        $pdf->Cell(40,10,$remitonum ,0,0,'L');
        $pdf->SetFont('Arial','',10);
        // Datos del Cliente
        $pdf->SetY(48);
        $pdf->SetX(70);
        $pdf->Cell(70,10,$nom_cliente,0,0,'L');
        $pdf->SetY(54);
        $pdf->Cell(18);
        if (isset($codp_cliente))
            $pdf->Cell(100,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') '.$nomloc,0,0,'L');
        else
            $pdf->Cell(100,10,$calle_cliente.' '.$nro_cliente.' '.$nomloc,0,0,'L');
        $pdf->SetY(54);
        $pdf->Cell(18);
        $pdf->SetX(100);
        // Datos del Remate
        $pdf->SetFont('Arial','',10);
        $pdf->SetY(66);
        $pdf->SetX(54);
        // Poner del Tipo de Impuesto 
        $pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
        
        $pdf->SetY(66);
        $pdf->SetX(120);
        $pdf->SetFont('Arial','BI',10);
        $pdf->Cell(50,10,'Contacto p/retiro: ');
        $pdf->SetX(152);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(30,10,$cont_retiro);
        $pdf->Cell(116);
        $pdf->SetX(29);
        $pdf->SetX(15);
        $pdf->SetY(76);
        $pdf->SetFont('Arial','BI',10);
        $pdf->SetX(15);
        $pdf->SetY(78);
        $pdf->Cell(20,10,utf8_decode('Relacionado con factura Nº: '),0,0,'L');	
        $pdf->SetFont('Arial','',10);
        $pdf->SetX(60);
        $pdf->Cell(20,10,$nrodoc_rem ,0,0,'L');
        $pdf->SetFont('Arial','BI',10);
        $pdf->SetY(92);
        $pdf->SetX(12);
        $pdf->Cell(150,10,' LOTE                                         DESCRIPCION ');

        // DESDE ACA EL WHILE
        //Inicializo los datos de las columnas de lotes
        $df_codintlote = "";
        $df_descrip1   = "";
        $df_descrip2   = "";
        $df_descrip3   = "";
        $df_neto       = "";
        $df_importe    = "";

        // Datos de los renglones
        if ($remate!="" ) {
            //Posición del primer renglón 
            $Y_Table_Position = 102; 

            $p = $Y_Table_Position;
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
                $lotes = mysqli_query($amercado,  $query_lotes) or die("ERROR LEYENDO ".$query_lotes." ");
                $row_lotes = mysqli_fetch_assoc($lotes);
                $totalRows_lotes = mysqli_num_rows($lotes);

                $codintlote    = $row_lotes['codintlote'];
                $descrip1      = substr($row_lotes['descripcion'],0,120);
                $descrip2      = substr($row_lotes['descripcion'],120,120);
                $descrip3      = substr($row_lotes['descripcion'],240,120);
                if ($lote_num=="" ) {
                    $codintlote    = $row_detallefac['concafac']; // antes decian $row_lotes['concafac'];
                    $df_codintlote = $row_detallefac['concafac']; // antes decian $row_lotes['concafac'];
                }
                if ($lote_num!="" ){
                    $codintlote    = $row_lotes['codintlote'];
                }
                $df_codintlote = $codintlote; //$df_codintlote.$codintlote."\n";
                $df_descrip1   = utf8_decode($descrip1); //$df_descrip1.$descrip1;
                $df_descrip2   = utf8_decode($descrip2); //$df_descrip2.$descrip2;
                $df_descrip3   = utf8_decode($descrip3); //$df_descrip2.$descrip2;


                //Posición de los títulos de los renglones, en este caso no va
                $Y_Fields_Name_position = 90;


                $pdf->SetFont('Arial','',10);
                $pdf->SetY($p);
                $pdf->SetX(13);

                // Código interno de Lote
                $pdf->Cell(12,9,$df_codintlote,0,'L');
                $pdf->SetY($p);
                $pdf->SetX(11);
                $pdf->SetFont('Arial','',8);
                // Descripción del lote en uno, dos o tres renglones
                if (isset($df_descrip3)) {
                    $pdf->SetX(25);
                    $pdf->Cell(110,9,$df_descrip1,0,'L');
                    $pdf->SetY($p+4);
                    $pdf->SetX(25);
                    $pdf->Cell(110,9,$df_descrip2,0,'L');
                    $pdf->SetY($p+8);
                    $pdf->SetX(25);
                    $pdf->Cell(110,9,$df_descrip3,0,'L');
                    $p = $p+12;
                } else
                if (isset($df_descrip2)) {
                    $pdf->SetX(25);
                    $pdf->Cell(110,9,$df_descrip1,0,'L');
                    $pdf->SetY($p+4);
                    $pdf->SetX(25);
                    $pdf->Cell(110,9,$df_descrip2,0,'L');
                    $pdf->SetX(155);
                     $p = $p+8;
                }
                else{
                    $pdf->SetX(25);
                    $pdf->SetY($p);
                    $pdf->Cell(110,9,$df_descrip1,0,'L');
                    //$pdf->SetX(155);
                     $p = $p+4;
                }
            }	
            $pdf->SetFont('Arial','B',10);
            $pdf->SetY(280);
            $pdf->SetX(95);
            if ($k==0)
                 $pdf->Cell(20,10,'ORIGINAL',0,0,'C');
            else
                $pdf->Cell(20,10,'DUPLICADO',0,0,'C');
        }
 		}
        
    } 
    
}  // del for
$pdf->Output();
?>