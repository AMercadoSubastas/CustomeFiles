<?php
define('FPDF_FONTPATH','fpdf17/font/');
require('fpdf17/fpdf.php');
require('numeros_a_letras.php');

//Conecto con la  base de datos
require_once('Connections/amercado.php');
mysqli_select_db($amercado, $database_amercado);

$remate_num  = "";//$_POST['remate_num'];
$ptcomp      = $_POST['tliq'];
$liquidacion = $_POST['liquidacion'];

// LEO EL REMATE PARA SACAR EL CLIENTE
//$query_remate1 = sprintf("SELECT * FROM remates WHERE ncomp = %s", $remate_num);
//$remates1 = mysqli_query($amercado, $query_remate1) or die("ERROR LEYENDO EL REMATE");
//$row_remates1 = mysqli_fetch_assoc($remates1);
$codcli = 15124; //$row_remates1["codcli"];

// LEO EL CLIENTE PARA SACAR LA CONDICION DE IVA
$query_entidad1 = sprintf("SELECT * FROM entidades WHERE codnum = %s", $codcli);
$tipo_iva_ent = mysqli_query($amercado, $query_entidad1) or die("ERROR LEYENDO EL CLIENTE");
$row_iva_ent = mysqli_fetch_assoc($tipo_iva_ent);
$iva = $row_iva_ent["tipoiva"];

$str_dep0  = "";$str_dep1  = "";$str_dep2  = "";$str_dep3  = "";$str_dep4  = "";
$str_dep5  = "";$str_dep6  = "";$str_dep7  = "";$str_dep8  = "";$str_dep9  = "";
$str_dep10 = "";$str_dep11 = "";$str_dep12 = "";$str_dep13 = "";$str_dep14 = "";
$str_dep15 = "";$str_dep16 = "";

if ($iva == 1) {
	$pserie = 2;
} else {
	$pserie = 13;
}
// Leo la liquidacion
$query_liquid = sprintf("SELECT * FROM liquidacion WHERE tcomp = %s  AND ncomp = %s" , $ptcomp, $liquidacion);

$liquid = mysqli_query($amercado, $query_liquid) or die("ERROR LEYENDO LA LIQUIDACION ".$query_liquid." - ");
$row_liquid = mysqli_fetch_assoc($liquid);

$fecharem     = $row_liquid["fecharem"];
$fecharem     = substr($fecharem,8,2)."-".substr($fecharem,5,2)."-".substr($fecharem,0,4);
$fechaliq     = $row_liquid["fechaliq"];
$fechaliq     = substr($fechaliq,8,2)."-".substr($fechaliq,5,2)."-".substr($fechaliq,0,4);

$cliente      = $row_liquid["cliente"];
$totremate    = $row_liquid["totremate"];
$tot_neto21   = $row_liquid["totneto1"];
$tot_iva21    = $row_liquid["totiva21"];
$subtot1      = $row_liquid["subtot1"];
$tot_neto105  = $row_liquid["totneto2"];
$tot_iva105   = $row_liquid["totiva105"];
$subtot2      = $row_liquid["subtot2"];
$totacuenta   = $row_liquid["totacuenta"];
$totgastos    = $row_liquid["totgastos"];
$totvarios    = $row_liquid["totvarios"];
$totvar       = $totvarios;
$saldoafav    = $row_liquid["saldoafav"];
$saldoafav1   = $row_liquid["saldoafav"];
$remate       = $row_liquid["codrem"];
$rubro        = $row_liquid["rubro"];
/*
// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %d", $remate_num);
$remates = mysqli_query($amercado, $query_remate) or die("ERROR LEYENDO EL REMATE 121:  ".$query_remate." - ");
$row_remates = mysqli_fetch_assoc($remates);
$remate_ncomp = $row_remates["ncomp"];
$remate_direc = $row_remates["direccion"];
$remate_localid = $row_remates["codloc"];
$remate_prov = $row_remates["codprov"];
$remate_fecha = $row_remates["fecreal"];
$remate_fecha  = substr($remate_fecha,8,2)."-".substr($remate_fecha,5,2)."-".substr($remate_fecha,0,4);

// Leo las facturas por conceptos relacionadas con el remate
$query_fcconc = sprintf("SELECT * FROM cabfac WHERE  codrem = %d AND tcomp IN ( 52, 54, 92) AND en_liquid = %d " , $remate_num, 1);
$fcconc = mysqli_query($amercado, $query_fcconc) or die("ERROR LEYENDO LAS FC DE CONCEPTOS DEL REMATE: ".$query_fcconc." - ");
$row_fcconc = mysqli_fetch_assoc($fcconc);
$totalRows_fcconc = mysqli_num_rows($fcconc);

// Leo las facturas por lotes relacionadas con el remate
$query_fclot = sprintf("SELECT * FROM cabfac WHERE  codrem = %d AND tcomp IN (51, 53, 57,58,59,60,89,105) AND en_liquid = %d " , $remate_num, 1);
$fclot = mysqli_query($amercado, $query_fclot) or die("ERROR LEYENDO LAS FC DE LOTES DEL REMATE: ".$query_fclot." - ");
$row_fclot = mysqli_fetch_assoc($fclot);
$totalRows_fclot = mysqli_num_rows($fclot);

// Leo los medios de pago
$query_cartvalores2 = sprintf("SELECT * FROM cartvalores WHERE  codrem = %d  AND tcompsal = %d and ncompsal= %d " , $remate_num, $ptcomp, $liquidacion);
$cartval2 = mysqli_query($amercado, $query_cartvalores2) or die("ERROR LEYENDO LOS MEDIOS DE PAGO: ".$query_cartvalores2." - ");
$row_cartval2 = mysqli_fetch_assoc($cartval2);
$totalRows_cartval2 = mysqli_num_rows($cartval2);
*/
// Leo el cliente
$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %d", $cliente);
$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO EL CLIENTE: ".$query_entidades." - ");
$row_entidades = mysqli_fetch_assoc($enti);
$nom_cliente   = $row_entidades["razsoc"];
$calle_cliente = $row_entidades["calle"];
$nro_cliente   = $row_entidades["numero"];
$codp_cliente  = $row_entidades["codpost"];
$loc_cliente   = $row_entidades["codloc"]; 
$cuit_cliente  = $row_entidades["cuit"];
$tipoiva_cliente = $row_entidades["tipoiva"];

// Leo la localidad del cliente
$query_localidades = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_cliente);
$localidad = mysqli_query($amercado, $query_localidades) or die("ERROR LEYENDO LOCALIDAD DEL CLIENTE: ".$query_localidades." - ");
$row_localidades = mysqli_fetch_assoc($localidad);
$nomloc = $row_localidades["descripcion"];
/*
// Leo la localidad del remate
$query_localidades_rem = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $remate_localid);
$localidad_rem = mysqli_query($amercado, $query_localidades_rem) or die("ERROR LEYENDO LOCALIDAD DEL REMATE: ".$query_localidades_rem." - ");
$row_local_rem = mysqli_fetch_assoc($localidad_rem);
$loc_rem = $row_local_rem["descripcion"];

// Leo la provincia del remate
$query_provincia_rem = sprintf("SELECT * FROM provincias WHERE  codnum = %s", $remate_prov);
$provincia_rem = mysqli_query($amercado, $query_provincia_rem) or die("ERROR LEYENDO PROVINCIA DEL REMATE: ".$query_provincia_rem." - ");
$row_prov_rem = mysqli_fetch_assoc($provincia_rem);
$prov_rem = $row_prov_rem["descripcion"];
*/
// LEO EL TIPO DE IVA DEL CLIENTE
$query_tiposiva = sprintf("SELECT * FROM tipoiva WHERE  codnum = %s", $tipoiva_cliente);
$tiposiva = mysqli_query($amercado, $query_tiposiva) or die("ERROR LEYENDO TIPO DE IVA DEL CLIENTE: ".$query_tiposiva." - ");
$row_tiposiva = mysqli_fetch_assoc($tiposiva);
$tipoiva_descor = $row_tiposiva["descrip"];
/*
// LEO EL RUBRO
$query_rubro = sprintf("SELECT * FROM rubros WHERE  codnum = %s", $rubro);
$rubro1 = mysqli_query($amercado, $query_rubro) or die("ERROR LEYENDO EL RUBRO: ".$query_rubro." - ");
$row_rubro = mysqli_fetch_assoc($rubro1);
$nom_rubro = $row_rubro["descripcion"];
*/
// Formateo los totales 
$tot_venta1  = $tot_neto21 + $tot_neto105 ;
$totremate   = number_format($totremate, 2, ',','.');
$tot_neto21  = number_format($tot_neto21, 2, ',','.');
$tot_iva21   = number_format($tot_iva21, 2, ',','.');
$subtot1     = number_format($subtot1, 2, ',','.');
$tot_neto105 = number_format($tot_neto105, 2, ',','.');
$tot_iva105  = number_format($tot_iva105, 2, ',','.');
$subtot2     = number_format($subtot2, 2, ',','.');
$totgastos   = number_format($totgastos, 2, ',','.');
$totacuenta  = number_format($totacuenta, 2, ',','.');
$totvarios   = number_format($totvarios, 2, ',','.');
$saldoafav   = number_format($saldoafav, 2, ',','.');


	//Creando el archivo pdf
	$pdf=new FPDF();
	
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(1 , 2) ;
	// Imprimo la cabecera
    
    $pdf->SetFont('Arial','B',10);
    
    //Fecha de la liquidacion
	$pdf->SetY(20);
	$pdf->Cell(130);
    $pdf->Cell(40,10,$fechaliq,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	
	// Datos del Cliente
	// NOMBRE DEL CLIENTE y RUBRO
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
   	$pdf->SetX(150);
	$pdf->Cell(70,10," ",0,0,'L');
	
	// DOMICILIO Y LOCALIDAD DEL CLIENTE
	$pdf->SetY(60);
	$pdf->Cell(16);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.')',0,0,'L');
	$pdf->SetX(150);
	$pdf->Cell(70,10,$nomloc,0,0,'L');

	// Datos del Remate
	// FECHA, DIRECCION Y NRO DE CBTE DEL REMATE
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(67);
	$pdf->SetY(66);
	$pdf->Cell(30);
	$pdf->Cell(65,10,' ',0,0,'L');
	$pdf->SetX(140);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(34,10,' ',0,0,'L');
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(10);
	$pdf->SetY(70);
	$pdf->Cell(10,10,'   ',0,0,'L');
	$pdf->Cell(140);
	$pdf->SetX(165);

	// CUIT Y TIPO DE IVA DEL CLIENTE
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(74);
	$pdf->SetX(38);
	$pdf->Cell(10,10,$cuit_cliente,0,0,'L');
	$pdf->SetY(74);
	$pdf->SetX(145);
	$pdf->Cell(145,10,$tipoiva_descor,0,0,'L');
	
	// DATOS EN EL CUERPO DE LA LIQUIDACION
	$pdf->SetY(85);
	$pdf->Cell(180);
	$pdf->Cell(10,10,$totremate,0,0,'R');
	$pdf->SetY(92);
	$pdf->Cell(140,10,$tot_neto21,0,0,'R');
	$pdf->SetY(98);
	$pdf->Cell(10);
	$pdf->Cell(10,10,'21 % ',0,0,'L');
	$pdf->Cell(140);
	$pdf->SetY(100);
	$pdf->Cell(140,10,$tot_iva21,0,0,'R');
	$pdf->SetY(104);
	$pdf->Cell(140,10,$subtot1,0,0,'R');
	$pdf->SetY(120);
	$pdf->Cell(140,10,$tot_neto105,0,0,'R');
	$pdf->SetY(126);
 	$pdf->Cell(10);
	$pdf->Cell(20,10,'10,5 %',0,0,'L');
	$pdf->SetY(127);
	$pdf->Cell(140,10,$tot_iva105,0,0,'R');
	$pdf->SetY(133);
	$pdf->Cell(140,10,$subtot2,0,0,'R');
	$pdf->SetY(158);
	$pdf->Cell(140,10,$totacuenta,0,0,'R');
	$pdf->SetY(165);
	$pdf->Cell(140,10,$totgastos,0,0,'R');
	$pdf->SetY(139);
	if ($totvar != 0) {
        $pdf->Cell(20,10,'Neto no gravado ',0,0,'L');
	    $pdf->Cell(120,10,$totvarios,0,0,'R');
    }
    else {
        $pdf->SetY(172);
        $pdf->Cell(140,10,$totvarios,0,0,'R');
    }
	$pdf->SetY(191);
	$pdf->Cell(189,10,$saldoafav,0,0,'R');
	$numero = $saldoafav1;
	$letras = "";
	if ($numero!=0) {
        if ($numero >= 0) {
		  $letras = numtoletras($numero); //convertir_a_letras($numero);
        }
        else {
            $numneg = $numero * -1;
           $letras = numtoletras($numneg); //convertir_a_letras($numero); 
            $letras = "MENOS ".$letras;
        }
	}
	$pdf->SetY(206);
	$pdf->Cell(10,10,$letras,0,0,'L');
	$pdf->SetY(212);
    $pdf->Cell(30,10,$saldoafav,0,0,'R');
	
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(1 , 2) ;
	// Imprimo la cabecera
    
    $pdf->SetFont('Arial','B',10);
    
    //Fecha de la liquidacion
	$pdf->SetY(20);
	$pdf->Cell(130);
    $pdf->Cell(40,10,$fechaliq,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	
	// Datos del Cliente
	// NOMBRE DEL CLIENTE y RUBRO
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
    $pdf->SetX(150);
	$pdf->Cell(70,10," ",0,0,'L');
	
	// DOMICILIO Y LOCALIDAD DEL CLIENTE
	$pdf->SetY(60);
	$pdf->Cell(16);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.')',0,0,'L');
	$pdf->SetX(150);
	$pdf->Cell(70,10,$nomloc,0,0,'L');

	// Datos del Remate
	// FECHA, DIRECCION Y NRO DE CBTE DEL REMATE
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(67);
	$pdf->SetY(66);
	$pdf->Cell(30);
	$pdf->Cell(65,10,' ',0,0,'L');
	$pdf->SetX(142);
	$pdf->SetFont('Arial','B',11);
	$pdf->Cell(34,10,' ',0,0,'L');
	$pdf->SetFont('Arial','B',9);
	$pdf->SetX(10);
	$pdf->SetY(70);
	$pdf->Cell(10,10,'   ',0,0,'L');
	$pdf->Cell(140);
	$pdf->SetX(165);
	
	// CUIT Y TIPO DE IVA DEL CLIENTE
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(74);
	$pdf->SetX(38);
	$pdf->Cell(10,10,$cuit_cliente,0,0,'L');
	$pdf->SetY(74);
	$pdf->SetX(145);
	$pdf->Cell(145,10,$tipoiva_descor,0,0,'L');
	
	// DATOS EN EL CUERPO DE LA LIQUIDACION
	$pdf->SetY(85);
	$pdf->Cell(180);
	$pdf->Cell(10,10,$totremate,0,0,'R');
	$pdf->SetY(92);
	$pdf->Cell(140,10,$tot_neto21,0,0,'R');
	$pdf->SetY(98);
	$pdf->Cell(10);
	$pdf->Cell(10,10,'21 % ',0,0,'L');
	$pdf->Cell(140);
	$pdf->SetY(98);
	$pdf->Cell(140,10,$tot_iva21,0,0,'R');
	$pdf->SetY(104);
	$pdf->Cell(140,10,$subtot1,0,0,'R');
	$pdf->SetY(120);
	$pdf->Cell(140,10,$tot_neto105,0,0,'R');
	$pdf->SetY(126);
 	$pdf->Cell(10);
	$pdf->Cell(20,10,'10,5 %',0,0,'L');
	$pdf->SetY(127);
	$pdf->Cell(140,10,$tot_iva105,0,0,'R');
	$pdf->SetY(133);
	$pdf->Cell(140,10,$subtot2,0,0,'R');
	$pdf->SetY(158);
	$pdf->Cell(140,10,$totacuenta,0,0,'R');
	$pdf->SetY(165);
	$pdf->Cell(140,10,$totgastos,0,0,'R');
	$pdf->SetY(139);
    if ($totvar != 0) {
        $pdf->Cell(20,10,'Neto no gravado ',0,0,'L');
	    $pdf->Cell(120,10,$totvarios,0,0,'R');
    }
    else {
        $pdf->SetY(172);
        $pdf->Cell(140,10,$totvarios,0,0,'R');
    }
	$pdf->SetY(191);
	$pdf->Cell(189,10,$saldoafav,0,0,'R');
	$numero = $saldoafav1;
	if ($numero!=0) {
		 if ($numero >= 0) {
		  $letras = numtoletras($numero); //convertir_a_letras($numero);
        }
        else {
            $numneg = $numero * -1;
            $letras = numtoletras($numneg); //convertir_a_letras($numero); 
            $letras = "MENOS ".$letras;
        }
	}
	$pdf->SetY(205);
	$pdf->Cell(10,10,$letras,0,0,'L');
	$pdf->SetY(210);
    $pdf->Cell(30,10,$saldoafav,0,0,'R');
	
    //Salto de línea
	$Y_Fields_Name_position = 90;
	//Table position, under Fields Name
	$Y_Table_Position = 100;

	
	//Aca van los datos de las columnas 
	$pdf->SetFont('Arial','B',12);
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(5);
	// Código interno de Lote
	$df_codintlote = "";
	$pdf->MultiCell(10,10,$df_codintlote,0,'L');
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(15);

	
	// Medios de Pago
	mysqli_select_db($amercado, $database_amercado);
	$query_cartvalores = "SELECT * FROM cartvalores WHERE  codrem = '$remate_num' AND ncompsal = '$liquidacion' ORDER BY tcomp ASC" ;
    $cartval = mysqli_query($amercado, $query_cartvalores) or die("ERROR LEYENDO MEDIOS DE PAGO DE LA LIQUIDACION: ".$query_cartvalores." - ");
	$totalRows_cartval = mysqli_num_rows($cartval);
	$efectivo ="";	$cheques = "";	$depositos ="";	$retenciones = ""; $facturas_asoc = "";
	$str_chk ="";	$str_dep ="";	$str_efe ="";	$str_ret =""; $str_facturas_asoc = ""; $str_fact = "";
	$vez_chk = 0;	$vez_dep = 0;	$vez_efe = 0;	$vez_ret = 0; $vez_fac = 0;
	$str_chk_im = "";	$str_chk_im = "";	$str_chk_im1 = "";	$str_chk_im2 = "";
	$str_chk_im3 = "";	$str_chk_im4 = "";	$str_chk_im5 = "";	$str_chk_im6 = "";
	$str_chk_im7 = "";	$str_chk_im8 = "";	$str_chk_im9 = "";	$str_chk_im10 = "";
	$str_chk_im11 = "";	$str_chk_im12 = "";	$str_chk_im13 = "";	$str_chk_im14 = "";
	$str_chk_im15 = "";	$str_chk_im16 = "";	$str_chk_im17 = "";	$str_chk_im18 = "";
	$str_chk_im19 = "";	$str_chk_im20 = "";	$str_chk_im21 = "";	$str_chk_im22 = "";
	$str_chk_im23 = "";	$str_chk_im24 = "";	$str_chk_im25 = "";	$str_chk_im26 = "";
	$str_chk_im27 = "";	$str_chk_im28 = "";	$str_chk_im29 = "";	$str_chk_im30 = "";
	$str_chk_im31 = "";	$str_chk_im32 = "";	$str_chk_im33 = "";	$str_chk_im34 = "";
	$str_chk_im35 = "";	$str_chk_im36 = "";	$str_chk_im37 = "";	$str_chk_im38 = "";
	$str_chk_im39 = "";	$str_chk_im40 = "";	$str_chk_im41 = "";	$str_chk_im42 = "";
	$str_chk_im43 = "";	$str_chk_im44 = "";	$str_chk_im45 = "";	$str_chk_im46 = "";
	$str_chk_im47 = "";	$str_chk_im48 = "";
	while($row_cartval = mysqli_fetch_array($cartval)) {
	
        $tipo_comp = $row_cartval["tcomp"];
        $serie = $row_cartval["serie"];
        $codban = $row_cartval["codban"];
        $codchq = $row_cartval["codchq"];
        $importe = $row_cartval["importe"];
        $codigo = $row_cartval["codnum"];
        if ($codban!="") {
            $query_banco = "SELECT * FROM bancos WHERE  codnum = '$codban'";
            $banco = mysqli_query($amercado, $query_banco);
            $row_banco = mysqli_fetch_assoc($banco);
            $banco_nom  = $row_banco["nombre"];
        }
	
	// Filtro los cheques 
	if  ($tipo_comp=='8'  || $tipo_comp=='14') {
		// Entro la primera vez
	    if ($vez_chk==0) {
	    	$cheques = $importe+$cheques;
	    	$str_chk = $banco_nom." Num:".$codchq." $".$importe;
		 	$str_chk_im = $banco_nom." Num:".$codchq." $".$importe;
		 	$vez_chk= 1;
	    } else {
	    	$str_chk = $banco_nom." Num:".$codchq." $".$importe." / ".$str_chk;
		  	$largo  = strlen($str_chk);
			$str_chk_im = substr($str_chk,0,120);
			$str_chk_im1 = substr($str_chk,120,120);
			$str_chk_im2 = substr($str_chk,240,120);
			$str_chk_im3 = substr($str_chk,360,120);
			$str_chk_im4 = substr($str_chk,480,120);
			$str_chk_im5 = substr($str_chk,600,120);
			$str_chk_im6 = substr($str_chk,720,120);
			$str_chk_im7 = substr($str_chk,840,120);
			$str_chk_im8 = substr($str_chk,960,120);
			$str_chk_im9 = substr($str_chk,1080,120);
			$str_chk_im10 = substr($str_chk,1200,120);
			$str_chk_im11 = substr($str_chk,1320,120);
			$str_chk_im12 = substr($str_chk,1440,120);
			$str_chk_im13 = substr($str_chk,1560,120);
			$str_chk_im14 = substr($str_chk,1680,120);
			$str_chk_im15 = substr($str_chk,1800,120);
			$str_chk_im16 = substr($str_chk,1920,120);
			$str_chk_im17 = substr($str_chk,2040,120);
			$str_chk_im18 = substr($str_chk,2160,120);
			$str_chk_im19 = substr($str_chk,2280,120);
			$str_chk_im20 = substr($str_chk,2400,120);
			$str_chk_im21 = substr($str_chk,2520,120);
			$str_chk_im22 = substr($str_chk,2640,120);
			$str_chk_im23 = substr($str_chk,2760,120);
			$str_chk_im24 = substr($str_chk,2880,120);
			$str_chk_im25 = substr($str_chk,3000,120);
			$str_chk_im26 = substr($str_chk,3120,120);
			$str_chk_im27 = substr($str_chk,3240,120);
			$str_chk_im28 = substr($str_chk,3360,120);
			$str_chk_im29 = substr($str_chk,3480,120);
			$str_chk_im30 = substr($str_chk,3600,120);
			$str_chk_im31 = substr($str_chk,3720,120);
			$str_chk_im32 = substr($str_chk,3840,120);
			$str_chk_im33 = substr($str_chk,3960,120);
			$str_chk_im34 = substr($str_chk,4080,120);
			$str_chk_im35 = substr($str_chk,4200,120);
			$str_chk_im36 = substr($str_chk,4320,120);
			$str_chk_im37 = substr($str_chk,4440,120);
			$str_chk_im38 = substr($str_chk,4560,120);
			$str_chk_im39 = substr($str_chk,4680,120);
			$str_chk_im40 = substr($str_chk,4800,120);
			$str_chk_im41 = substr($str_chk,4920,120);
			$str_chk_im42 = substr($str_chk,5040,120);
			$str_chk_im43 = substr($str_chk,5160,120);
			$str_chk_im44 = substr($str_chk,5280,120);
			$str_chk_im45 = substr($str_chk,5400,120);
			$str_chk_im46 = substr($str_chk,5520,120);
			$str_chk_im47 = substr($str_chk,5640,120);
			$str_chk_im48 = substr($str_chk,5760,120);
	      }
	}
	// Filtro los Depositos
  	if  ($tipo_comp=='9'  || $tipo_comp=='39') {
		// Entro la primera vez
	    if ($vez_dep==0) {
			$depositos= $depositos+$importe;
			$str_dep = $banco_nom." Cuenta:".$codchq." $".$importe;
		 	$str_dep0 = substr($str_dep,0,120);
		 	$vez_dep= 1;
	    } else {
	  	    $str_dep = $banco_nom." Cuenta:".$codchq." $".$importe." / ".$str_dep;
			$largo1 = strlen($str_chk);
		   	$str_dep0  = substr($str_dep,0,120);
		   	$str_dep1  = substr($str_dep,120,120);
			$str_dep2  = substr($str_dep,240,120);
			$str_dep3  = substr($str_dep,360,120);
			$str_dep4  = substr($str_dep,480,120);
			$str_dep5  = substr($str_dep,600,120);
			$str_dep6  = substr($str_dep,720,120);
			$str_dep7  = substr($str_dep,840,120);
			$str_dep8  = substr($str_dep,960,120);
			$str_dep9  = substr($str_dep,1080,120);
			$str_dep10 = substr($str_dep,1200,120);
			$str_dep11 = substr($str_dep,1320,120);
			$str_dep12 = substr($str_dep,1440,120);
			$str_dep13 = substr($str_dep,1560,120);
			$str_dep14 = substr($str_dep,1680,120);
			$str_dep15 = substr($str_dep,1800,120);
			$str_dep16 = substr($str_dep,1920,120);
	    }
	}
	
	// Efectivo
	if  ($tipo_comp=='12'  || $tipo_comp=='38') {
		// Entro la primera vez
	    $str_efe = $importe + $str_efe;
        $importe = number_format($importe, 2, ',','.') ;
		$vez_efe= 1;
	}
        
    // Facturas asociadas
	if  ($tipo_comp=='51'  || $tipo_comp=='52'   || $tipo_comp=='53'  || $tipo_comp=='54'  || $tipo_comp=='89'   || $tipo_comp=='92') {
		// Entro la primera vez
	
	    $str_facturas_asoc = $importe + $str_facturas_asoc;
        $str_fact .= $codchq." - ";
        $importe = number_format($importe, 2, ',','.') ;
		$vez_fact= 1;
	}
	
	// Retenciones
	if  ($tipo_comp=='40'  || $tipo_comp=='41' || $tipo_comp=='42') {
		if ($tipo_comp=='40') {
		  	$ret_tipo = "Retencion IVA";
		  
		}
		if ($tipo_comp=='41') {
		  	$ret_tipo = "Retencion Ing. Brutos";
		  
		}
		if ($tipo_comp=='42') {
		  	$ret_tipo = "Retencion Ganancias";
		  
		}
		// Entro la primera vez
        if ($vez_ret==0) {
            $importe = number_format($importe, 2, ',','.') ;
            $cheques = $importe+$cheques;
            $str_ret = $ret_tipo." $".$importe;
            $vez_ret= 1;
        } else {

            $str_ret = $ret_tipo." $".$importe." / ".$str_ret;;
        }
	}
	
}
 

/// Detalle de Lotes
$query_lotes = "SELECT * FROM lotes WHERE codrem = '$remate_num' ORDER BY codrem, secuencia";
$lotes = mysqli_query($amercado, $query_lotes) or die("ERROR LEYENDO LOTES: ".$query_lotes." - ");
$totalRows_lotes = mysqli_num_rows($lotes);
$renglones = 0;
while($row_lotes = mysqli_fetch_array($lotes)) 
{
	$code = "";
	$name = "";
	$name2 = "";
	$code = $row_lotes["codintlote"];
	$name = substr($row_lotes["descripcion"],0,53);
	$name2 = substr($row_lotes["descripcion"],53,100);
	$renglones = $renglones + 1;
	if (strcmp($name2, "")!=0) {
		$renglones = $renglones + 1;
	}
}
$hojas = ceil($renglones / 18);

$query_lotes = "SELECT * FROM detfac WHERE codrem = '$remate' AND (tcomp='51' OR tcomp='53' OR tcomp='57' OR tcomp='58'   OR tcomp='89') AND tcomsal = $ptcomp AND ncompsal= '$liquidacion'  ORDER BY ncomp ASC";
$lotes = mysqli_query($amercado, $query_lotes) or die("ERROR LEYENDO DETFAC 1RA VEZ: ".$query_lotes." - ");

$totalRows_lotes = mysqli_num_rows($lotes);
$j = 0; // Me dará el total de renglones

$pdf->AddPage();
	
//Fields Name position
$Y_Fields_Name_position = 40;
	
//Table position, under Fields Name
$Y_Table_Position = 47;

$pdf->Image('images/logo_liquidacon.jpg',10,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',15);
//Movernos a la derecha
$pdf->Cell(70);
//Título
$pdf->Cell(70,10,'DETALLE DE PAGOS',0,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->SetFont('Arial','B',10);

$pdf->Ln(35);
$y = 25;

$pdf->SetY($Y_Table_Position);
$pdf->SetY($y);
$pdf->setX(25);
if 	($str_chk!="") {
    
    $pdf->Cell(20,7,"Cheques",0,0,'L',0);
    $pdf->SetFont('Arial','B',8);
    $y = $y+4;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im1,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im2,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im3,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im4,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im5,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im6,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im7,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im8,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im9,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im10,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im11,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im12,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im13,0,0,'L',0);
    // DESDE ACA LAS NUEVAS LINEAS
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im14,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im15,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im16,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im17,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im18,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im19,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im20,0,0,'L',0);
    // HASTA ACA 
    // DESDE ACA mas  LINEAS
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im21,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im22,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im23,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im24,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im25,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im26,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im27,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im28,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im29,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im30,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im31,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im32,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im33,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im34,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im35,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im36,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im37,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im38,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im39,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im40,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im41,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im42,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im43,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im44,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im45,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im46,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im47,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im48,0,0,'L',0);
    // HASTA ACA 
    $y = $y+4;


}
if 	($str_dep!="") {
	$y = $y + 12;
	$pdf->SetY($y);
	$pdf->setX(25);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,7,"Depositos y Transferencias",0,0,'L',0);
	$y = $y+4;
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY($y);

 
	$pdf->Cell(20,7,$str_dep0 ,0,0,'L',0);
	$y = $y+4 ;
	$pdf->SetY($y);
	$pdf->Cell(20,7,$str_dep1,0,0,'L',0);
    $y = $y+4 ;
	$pdf->SetY($y);
	$pdf->Cell(20,7,$str_dep2,0,0,'L',0);
	$y = $y+4 ;
	$pdf->SetY($y);
	$pdf->Cell(20,7,$str_dep3,0,0,'L',0);
	$y = $y+4 ;
	$pdf->SetY($y);
	$pdf->Cell(20,7,$str_dep4,0,0,'L',0);
	$y = $y+4 ;
	$pdf->SetY($y);
	$pdf->Cell(20,7,$str_dep5,0,0,'L',0);
	$y = $y+4 ;
	$pdf->SetY($y);
	$pdf->Cell(20,7,$str_dep6,0,0,'L',0);
	$y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_dep7,0,0,'L',0);
	
	if 	($str_dep8!="") {
		$y = $y+4 ;
	   	$pdf->SetY($y);
	  	$pdf->Cell(20,7,$str_dep8,0,0,'L',0);
	}
	if 	($str_dep9!="") {
		$y = $y+4 ;
	   	$pdf->SetY($y);
	  	$pdf->Cell(20,7,$str_dep9,0,0,'L',0);
	}
	if 	($str_dep10!="") {
		$y = $y+4 ;
		$pdf->SetY($y);
		$pdf->Cell(20,7,$str_dep10,0,0,'L',0);
	}
	if 	($str_dep11!="") {	
		$y = $y+4 ;
	   	$pdf->SetY($y);
	  	$pdf->Cell(20,7,$str_dep11,0,0,'L',0);
	}
	if 	($str_dep12!="") {
		$y = $y+4 ;
	   	$pdf->SetY($y);
	  	$pdf->Cell(20,7,$str_dep12,0,0,'L',0);
	}
	if 	($str_dep13!="") {
		$y = $y+4 ;
	   	$pdf->SetY($y);
	  	$pdf->Cell(20,7,$str_dep13,0,0,'L',0);
	}
	if 	($str_dep14!="") {
		$y = $y+4 ;
	   	$pdf->SetY($y);
	  	$pdf->Cell(20,7,$str_dep14,0,0,'L',0);
	}
	if 	($str_dep15!="") {
		$y = $y+4 ;
	   	$pdf->SetY($y);
	  	$pdf->Cell(20,7,$str_dep15,0,0,'L',0);
	}
	if 	($str_dep16!="") {
		$y = $y+4 ;
	   	$pdf->SetY($y);
	  	$pdf->Cell(20,7,$str_dep16,0,0,'L',0);
	}
	$y = $y+4 ;

}	


if 	($str_efe!="") {
    $pdf->SetY($y);
    $pdf->setX(25);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,7,"Efectivo",0,0,'L',0);
    $y = $y+4;
    $pdf->SetFont('Arial','B',8);
    $pdf->SetY($y);
    $pdf->Cell(20,7,"   Se abona en efectivo $ ".$str_efe,0,0,'L',0);
    $y = $y+4 ;
}	
if ($str_facturas_asoc != "") {
    $pdf->SetY($y);
    $pdf->setX(25);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(80,7,"Facturas asociadas:  ".$str_fact,0,0,'L',0);
    $y = $y+4;
    $pdf->SetFont('Arial','B',8);
    $pdf->SetY($y);
    $pdf->Cell(80,7,"   Se cancela contra facturas asociadas $ ".$str_facturas_asoc,0,0,'L',0);
    $y = $y+4 ;
    
}
if 	($str_ret!="") {
    $pdf->SetY($y);
    $pdf->setX(25);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,7,"Retenciones",0,0,'L',0);
    $y = $y+4;
    $pdf->SetFont('Arial','B',8);
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_ret,0,0,'L',0);
    $y = $y+4 ;
}	
 

$pdf->AddPage();
	
//Fields Name position
$Y_Fields_Name_position = 40;
	
//Table position, under Fields Name
$Y_Table_Position = 47;
	
$pdf->Image('images/logo_liquidacon.jpg',10,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',15);
//Movernos a la derecha
$pdf->Cell(70);
//Título
$pdf->Cell(70,10,'DETALLE DE PAGOS',0,0,'C');
$pdf->SetFont('Arial','B',8);
	
$pdf->SetFont('Arial','B',10);

$pdf->Ln(35);
$y = 25;

$pdf->SetY($Y_Table_Position);
$pdf->SetY($y);
$pdf->setX(25);
if 	($str_chk!="") {
    
    $pdf->Cell(20,7,"Cheques",0,0,'L',0);
    $pdf->SetFont('Arial','B',8);
    $y = $y+4;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im1,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im2,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im3,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im4,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im5,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im6,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im7,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im8,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im9,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im10,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im11,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im12,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im13,0,0,'L',0);

    // DESDE ACA LAS NUEVAS LINEAS
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im14,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im15,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im16,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im17,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im18,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im19,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im20,0,0,'L',0);
    // HASTA ACA 
    // DESDE ACA mas  LINEAS
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im21,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im22,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im23,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im24,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im25,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im26,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im27,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im28,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im29,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im30,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im31,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im32,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im33,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im34,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im35,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im36,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im37,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im38,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im39,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im40,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im41,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im42,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im43,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im44,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im45,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im46,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im47,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_chk_im48,0,0,'L',0);
    // HASTA ACA 
    $y = $y+4 ;
}
if 	($str_dep!="") {
    $y = $y + 16;
    $pdf->SetY($y);
    $pdf->setX(25);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,7,"Depositos y Transferencias",0,0,'L',0);
    $y = $y+4;
    $pdf->SetFont('Arial','B',8);
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_dep0,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_dep1,0,0,'L',0);
        $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_dep2,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_dep3,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_dep4,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_dep5,0,0,'L',0);
    $y = $y+4 ;
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_dep6,0,0,'L',0);
    if 	($str_dep7!="") {
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep7,0,0,'L',0);
    }
    if 	($str_dep8!="") {
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep8,0,0,'L',0);
    }
    if 	($str_dep9!="") {
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep9,0,0,'L',0);
    }
    if 	($str_dep10!="") {
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep10,0,0,'L',0);
    }
    if 	($str_dep11!="") {	
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep11,0,0,'L',0);
    }
    if 	($str_dep12!="") {
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep12,0,0,'L',0);
    }
    if 	($str_dep13!="") {
        $y = $y+4 ;
            $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep13,0,0,'L',0);
    }
    if 	($str_dep14!="") {
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep14,0,0,'L',0);
    }
    if 	($str_dep15!="") {
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep15,0,0,'L',0);
    }
    if 	($str_dep16!="") {
        $y = $y+4 ;
        $pdf->SetY($y);
        $pdf->Cell(20,7,$str_dep16,0,0,'L',0);
    }
    $y = $y+4 ;

}	

if 	($str_efe!="") {
    $pdf->SetY($y);
    $pdf->setX(25);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,7,"Efectivo",0,0,'L',0);
    $y = $y+4;
    $pdf->SetFont('Arial','B',8);
    $pdf->SetY($y);
    $pdf->Cell(20,7,"   Se abona en efectivo $ ".$str_efe,0,0,'L',0);
    $y = $y+4 ;
}	
if ($str_facturas_asoc != "") {
    $pdf->SetY($y);
    $pdf->setX(25);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(80,7,"Facturas asociadas:  ".$str_fact,0,0,'L',0);
    $y = $y+4;
    $pdf->SetFont('Arial','B',8);
    $pdf->SetY($y);
    $pdf->Cell(80,7,"   Se cancela contra facturas asociadas $ ".$str_facturas_asoc,0,0,'L',0);
    $y = $y+4 ;
    
}
if 	($str_ret!="") {
    $pdf->SetY($y);
    $pdf->setX(25);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,7,"Retenciones",0,0,'L',0);
    $y = $y+4;
    $pdf->SetFont('Arial','B',8);
    $pdf->SetY($y);
    $pdf->Cell(20,7,$str_ret,0,0,'L',0);
    $y = $y+4 ;
}	

$pdf->AddPage();
	
//Fields Name position
$Y_Fields_Name_position = 40;
	
//Table position, under Fields Name
$Y_Table_Position = 47;
	
$pdf->Image('images/logo_liquidacon.jpg',10,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',15);
$pdf->Cell(70);
//Título
$pdf->Cell(70,10,'DETALLE DE FACTURA',0,0,'C');
	
$pdf->SetFont('Arial','B',10);

$pdf->Ln(35);

$pdf->SetFillColor(232,232,232);
//Bold Font for Field Name
$pdf->SetFont('Arial','B',11);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(5);
$pdf->Cell(20,7,'REMATE',1,0,'C',1);
$pdf->SetX(25);
$pdf->Cell(32,7,'COMPROBANTE',1,0,'C',1);
$pdf->SetX(57);
$pdf->Cell(14,7,'LOTE',1,0,'C',1);
$pdf->SetX(71);
$pdf->Cell(110,7,'DESCRIPCION',1,0,'C',1);
$pdf->SetX(181);
$pdf->Cell(20,7,'IMPORTE',1,0,'C',1);
$pdf->Ln();
	
//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
// luego una nueva pagina y la cabecera
$j = 0;
$neto ="";
$ncom ="";
$tot_venta = 0;
while($row_lotes = mysqli_fetch_array($lotes)) 
{

	$remate = $row_lotes["codrem"];
	$factura = $row_lotes["ncomp"];   
	$tcomp = $row_lotes["tcomp"];
	$serie = $row_lotes["serie"];
	$codlote = $row_lotes["codlote"];
	$descrip = utf8_decode($row_lotes["descrip"]);
	$descrip = substr($descrip,0,63);
	$neto = $row_lotes["neto"];
	if ($row_lotes["tcomp"] == 57 || $row_lotes["tcomp"] == 58)
		$neto = $neto * -1;
	$tot_venta = $tot_venta + $neto;
	$importe =  number_format($neto, 2, ',','.');

    //======================================================================================
	$codlote = $row_lotes["codlote"];
	// Nuevo
	$query_lotes1 = "SELECT * FROM lotes WHERE codrem = '$remate' AND secuencia = '$codlote' ORDER BY codrem, secuencia";
  	$lotes1 = mysqli_query($amercado, $query_lotes1) or die("ERROR LEYENDO LOTES");
	$row_lotes1 = mysqli_fetch_assoc($lotes1);
    $codintlote = $row_lotes1["codintlote"];
    //======================================================================================

	$query_mascara = "SELECT mascara FROM series WHERE codnum ='$serie' AND tipcomp ='$tcomp'";
    $mascara = mysqli_query($amercado, $query_mascara) or die("ERROR LEYENDO SERIES");
    $row_mascara = mysqli_fetch_assoc($mascara);
    //  $factura = 99;
	$mascara = $row_mascara["mascara"];
	if ($tcomp == 51 || $tcomp == 57 || $tcomp == 59)
		$mascara = "A0004";
	else
		if ($tcomp == 53 || $tcomp == 58 || $tcomp == 60)
			$mascara = "B0004";
		else 
			if ($tcomp == 89 )
				$mascara = "A00005";
                else 
			         if ($tcomp == 1 || $tcomp == 5 || $tcomp == 18 || $tcomp == 21  )
				        $mascara = "A0001";
                    else 
			         if ($tcomp == 6 || $tcomp == 7 || $tcomp == 19 || $tcomp == 22  )
				        $mascara = "A0002";
                        else 
                            if ($tcomp == 23 || $tcomp == 25 || $tcomp == 27 || $tcomp == 29  )
				                $mascara = "B0001";
                            else 
                                if ($tcomp == 24 || $tcomp == 26 || $tcomp == 28 || $tcomp == 30  )
				                    $mascara = "B0002";
	if ($tcomp == 51 || $tcomp == 53 || $tcomp == 89 || $tcomp == 1 || $tcomp == 6 || $tcomp == 18 || $tcomp == 19 || $tcomp == 23  || $tcomp == 246 || $tcomp == 27 || $tcomp == 28  )
		$tc = "Fc-";
	if ($tcomp == 57 || $tcomp == 58 || $tcomp == 5 || $tcomp == 7 || $tcomp == 25 || $tcomp == 26 )
		$tc = "Nc-";
	if ($tcomp == 59 || $tcomp == 60 || $tcomp == 21 || $tcomp == 22 || $tcomp == 29 || $tcomp == 30 )
		$tc = "Nd-";
    // Busco La mascara para las boletas 
	if ($factura<=9) {
	   $num_fac = $mascara."-0000000".$factura;
	 }
	if ($factura<=99 && $factura>9) {
	   $num_fac = $mascara."-000000".$factura;
	 }
	 if ($factura<=999 && $factura>99) {
	   $num_fac = $mascara."-00000".$factura;
	 }
	 if ($factura<=9999 && $factura>999) {
	   $num_fac = $mascara."-0000".$factura;
	 }
	 if ($factura<=99999 && $factura>9999) {
	   $num_fac = $mascara."-000".$factura;
	 }
     $pdf->SetFont('Arial','',9);

    $pdf->SetY($Y_Table_Position);
    $pdf->SetX(5);
    $pdf->Cell(20,6,$remate,1,'C');
    $pdf->SetX(25);
    $pdf->Cell(32,6,$tc.$num_fac,1,'C');
    $pdf->SetX(57);
    $pdf->Cell(14,6,$codintlote,1,'C');
    $pdf->SetX(71);
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(110,6,$descrip,1,'L');
    $pdf->SetFont('Arial','',9);
    $pdf->SetX(181);
    $pdf->Cell(20,6,$importe,1,'R');
    $pdf->SetX(159);
	$pdf->SetY($Y_Table_Position);

		
	$j = $j +1;
	$Y_Table_Position = $Y_Table_Position + 6;
	if ($j >=37 || $Y_Table_Position >= 260) {
		// ACA VA EL PIE
		//Posición: a 1,5 cm del final
   		$pdf->SetY(-35);
   		$pdf->SetFont('Arial','I',8);
   		//Número de página
   		
		$pdf->AddPage();
		$Y_Fields_Name_position = 40;

        //Table position, under Fields Name
        $Y_Table_Position = 47;
        $pdf->Image('images/logo_liquidacon.jpg',10,8,50);
        $pdf->SetFont('Arial','B',15);
        $pdf->Cell(70);
        //Título
        $pdf->Cell(70,10,'DETALLE DE FACTURA',0,0,'C');
        $pdf->SetFont('Arial','B',8);
        $pdf->SetFont('Arial','B',10);
        $pdf->Ln(35);
        $pdf->SetFillColor(232,232,232);
        //Bold Font for Field Name
        $pdf->SetFont('Arial','B',11);
        $pdf->SetY($Y_Fields_Name_position);
        $pdf->SetX(5);
        $pdf->Cell(20,7,'REMATE',1,0,'C',1);
        $pdf->SetX(25);
        $pdf->Cell(32,7,'COMPROBANTE',1,0,'C',1);
        $pdf->SetX(57);
        $pdf->Cell(14,7,'LOTE',1,0,'C',1);
        $pdf->SetX(71);
        $pdf->Cell(110,7,'DESCRIPCION',1,0,'C',1);
        $pdf->SetX(181);
        $pdf->Cell(20,7,'IMPORTE',1,0,'C',1);
        $pdf->Ln();
        $j = 0;
        //Fields Name position
        $Y_Fields_Name_position = 40;
        //Table position, under Fields Name
        $Y_Table_Position = 47;
	}
}
$pdf->SetY($Y_Table_Position+2);
$pdf->SetX(100);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,7,'VENTA TOTAL ',1,0,'C',1);
$tot_venta1 =  number_format($tot_venta1 , 2, ',','.');
$pdf->Cell(49,7,$tot_venta1,1,0,'R',1);

$pdf->AddPage();

$query_lotes3 = "SELECT * FROM lotes WHERE codrem = '$remate' ORDER BY codrem, secuencia";
$lotes3 = mysqli_query($amercado, $query_lotes3) or die("ERROR LEYENDO LOTES 1377: ".$query_lotes3." - ");
$totalRows_lotes3 = mysqli_num_rows($lotes3);
$renglones = 0;
while($row_lotes3 = mysqli_fetch_array($lotes3)) 
{
	$code = "";
	$codintlote = "";
	$name = "";
	$name2 = "";
	$code = $row_lotes3["secuencia"];
	$codintlote = $row_lotes3["codintlote"];
	$name = substr(utf8_decode($row_lotes3["descripcion"]),0,53);
	$name2 = substr(utf8_decode($row_lotes3["descripcion"]),53,100);
	$renglones = $renglones + 1;
	if (strcmp($name2, "")!=0) {
		$renglones = $renglones + 1;
	}
}
$hojas = ceil($renglones / 18);

$query_lotes = "SELECT * FROM detfac WHERE codrem = '$remate' AND (tcomp='51' OR tcomp='53' OR tcomp='57' OR tcomp='58' OR tcomp='89')  AND  tcomsal = $ptcomp AND ncompsal='$liquidacion' ORDER BY ncomp ASC";
$lotes = mysqli_query($amercado, $query_lotes) or die("ERROR LEYENDO DETFAC 2DA VEZ: ".$query_lotes." - ");
$totalRows_lotes = mysqli_num_rows($lotes);
	
//Fields Name position
$Y_Fields_Name_position = 40;
//Table position, under Fields Name
$Y_Table_Position = 47;

$pdf->Image('images/logo_liquidacon.jpg',10,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',15);
//Movernos a la derecha
$pdf->Cell(70);
//Título
$pdf->Cell(70,10,'DETALLE DE FACTURA',0,0,'C');
$pdf->SetFont('Arial','B',10);

$pdf->Ln(35);
$pdf->SetFillColor(232,232,232);

$pdf->SetFont('Arial','B',11);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(5);
$pdf->Cell(20,7,'REMATE',1,0,'C',1);
$pdf->SetX(25);
$pdf->Cell(32,7,'COMPROBANTE',1,0,'C',1);
$pdf->SetX(57);
$pdf->Cell(14,7,'LOTE',1,0,'C',1);
$pdf->SetX(71);
$pdf->Cell(110,7,'DESCRIPCION',1,0,'C',1);
$pdf->SetX(181);
$pdf->Cell(20,7,'IMPORTE',1,0,'C',1);
$pdf->Ln();

//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
// luego una nueva pagina y la cabecera
$j = 0;
$neto ="";
$ncom ="";
$tot_venta = 0;
while($row_lotes = mysqli_fetch_array($lotes)) 
{
	$remate = $row_lotes["codrem"];
	$factura = $row_lotes["ncomp"];   
	$tcomp = $row_lotes["tcomp"];
	$serie = $row_lotes["serie"];


	$codlote = $row_lotes["codlote"];
	// Nuevo
	$query_lotes1 = "SELECT * FROM lotes WHERE codrem = '$remate' AND secuencia = '$codlote' ORDER BY codrem, secuencia";
  	$lotes1 = mysqli_query($amercado, $query_lotes1) or die("ERROR LEYENDO LOTES 1449: ".$query_lotes1." - ");
	$row_lotes1 = mysqli_fetch_assoc($lotes1);
   	$codintlote = $row_lotes1["codintlote"];
 
	// Nuevo
	$descrip = $row_lotes["descrip"];
	$descrip = substr($descrip,0,63);
	$neto = $row_lotes["neto"];
	if ($row_lotes["tcomp"] == 57 || $row_lotes["tcomp"] == 58)
		$neto = $neto * -1;
	
	$tot_venta = $tot_venta + $neto;
	$importe =  number_format($neto, 2, ',','.');
	$query_mascara = "SELECT mascara FROM series WHERE codnum ='$serie' AND tipcomp ='$tcomp'";
    	$mascara = mysqli_query($amercado, $query_mascara) or die("ERROR LEYENDO SERIES 1463: ".$query_mascara." - ");
    	$row_mascara1 = mysqli_fetch_assoc($mascara);
 
	$mascara1 = $row_mascara1["mascara"];
	// Busco La mascara para las facturas 
	if ($tcomp == 51 || $tcomp == 57 || $tcomp == 59)
		$mascara1 = "A0004";
	else
		if ($tcomp == 53 || $tcomp == 58 || $tcomp == 60)
				$mascara = "B0004";
			else 
				if ($tcomp == 89 )
					$mascara = "A00005";
	             else 
			         if ($tcomp == 1 || $tcomp == 5 || $tcomp == 18 || $tcomp == 21  )
				        $mascara = "A0001";
                    else 
			         if ($tcomp == 6 || $tcomp == 7 || $tcomp == 19 || $tcomp == 22  )
				        $mascara = "A0002";
                        else 
                            if ($tcomp == 23 || $tcomp == 25 || $tcomp == 27 || $tcomp == 29  )
				                $mascara = "B0001";
                            else 
                                if ($tcomp == 24 || $tcomp == 26 || $tcomp == 28 || $tcomp == 30  )
				                    $mascara = "B0002";
	if ($tcomp == 51 || $tcomp == 53 || $tcomp == 89 || $tcomp == 1 || $tcomp == 6 || $tcomp == 18 || $tcomp == 19 || $tcomp == 23  || $tcomp == 246 || $tcomp == 27 || $tcomp == 28  )
		$tc = "Fc-";
	if ($tcomp == 57 || $tcomp == 58 || $tcomp == 5 || $tcomp == 7 || $tcomp == 25 || $tcomp == 26 )
		$tc = "Nc-";
	if ($tcomp == 59 || $tcomp == 60 || $tcomp == 21 || $tcomp == 22 || $tcomp == 29 || $tcomp == 30 )
		$tc = "Nd-";
	if ($factura<=9) {
	 	$num_fac1 = $mascara1."-0000000".$factura;
	}
	
	if ($factura<=99 && $factura>9) {
	 	$num_fac1 = $mascara1."-000000".$factura;
	}
	if ($factura<=999 && $factura>99) {
		$num_fac1 = $mascara1."-00000".$factura;
	}
	if ($factura<=9999 && $factura>999) {
		$num_fac1 = $mascara1."-0000".$factura;
	}
	if ($factura<=99999 && $factura>9999) {
		$num_fac1 = $mascara1."-000".$factura;
	}

	$pdf->SetFont('Arial','',9);

	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(5);
	$pdf->Cell(20,6,$remate,1,'C');
	$pdf->SetX(25);
	$pdf->Cell(32,6,$tc.$num_fac1,1,'C');
	$pdf->SetX(57);
	$pdf->Cell(14,6,$codintlote,1,'C');
	$pdf->SetX(71);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(110,6,$descrip,1,'L');
	$pdf->SetFont('Arial','',9);
	$pdf->SetX(181);
	$pdf->Cell(20,6,$importe,1,'R');
	$pdf->SetX(159);
	$pdf->SetY($Y_Table_Position);
		
	$j = $j +1;
	$Y_Table_Position = $Y_Table_Position + 6;
	if ($j >=37 || $Y_Table_Position >= 260) {
		// ACA VA EL PIE
		//Posición: a 1,5 cm del final
   		$pdf->SetY(-35);
   		$pdf->SetFont('Arial','I',8);
   		
		$pdf->AddPage();
		
		$Y_Fields_Name_position = 40;
	
		//Table position, under Fields Name
		$Y_Table_Position = 47;
		$pdf->Image('images/logo_liquidacon.jpg',10,8,50);
		//Arial bold 15
		$pdf->SetFont('Arial','B',15);
		//Movernos a la derecha
		$pdf->Cell(70);
		//Título
		$pdf->Cell(70,10,'DETALLE DE FACTURA',0,0,'C');
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFont('Arial','B',10);
		$pdf->Ln(35);
		$pdf->SetFillColor(232,232,232);
		//Bold Font for Field Name
		$pdf->SetFont('Arial','B',11);
		$pdf->SetY($Y_Fields_Name_position);
		$pdf->SetX(5);
		$pdf->Cell(20,7,'REMATE',1,0,'C',1);
		$pdf->SetX(25);
		$pdf->Cell(32,7,'COMPROBANTE',1,0,'C',1);
		$pdf->SetX(55);
		$pdf->Cell(14,7,'LOTE',1,0,'C',1);
		$pdf->SetX(69);
		$pdf->Cell(110,7,'DESCRIPCION',1,0,'C',1);
		$pdf->SetX(179);
		$pdf->Cell(20,7,'IMPORTE',1,0,'C',1);
		$pdf->Ln();
		$j = 0;
		//Fields Name position
		$Y_Fields_Name_position = 40;
		//Table position, under Fields Name
		$Y_Table_Position = 47;
	}
}
$pdf->SetY($Y_Table_Position+2);
$pdf->SetX(100);
$pdf->SetFont('Arial','',12);
$pdf->Cell(50,7,'VENTA TOTAL ',1,0,'C',1);
$pdf->Cell(49,7,$tot_venta1,1,0,'R',1);
mysqli_close($amercado);
$pdf->Output();
?>