<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");
// Leo los renglones

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

// Leo la cabecera

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN %s AND %s ORDER BY fecreg, nrodoc", $fecha_desde, $fecha_hasta);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));

// Inicio el pdf con los datos de cabecera
  $pdf=new FPDF('L','mm','Legal');
  
  $pdf->AddPage();
  $pdf->SetMargins(0.5, 0.5 , 0.5);
  $pdf->SetFont('Arial','B',11);
  $pdf->SetY(5);
  $pdf->Cell(10);
  $pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
  $pdf->Cell(200);
  $pagina = $pdf->PageNo();
  $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
  $pdf->SetY(10);
  $pdf->Cell(230);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(130);
  $pdf->Cell(20,10,' Listado de comprobantes por usuario ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  $pdf->Cell(20,16,'    Fecha',1,0,'L');
  $pdf->Cell(30,16,' Nro.Factura',1,0,'L');
  $pdf->Cell(40,16,'      Raz�n Social',1,0,'L');
  $pdf->Cell(22,16,'     CUIT',1,0,'L');
  $pdf->Cell(23,16,'    Conceptos ',1,0,'L');
  $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
  $pdf->Cell(26,16,'    Conceptos ',1,0,'L');
  $pdf->Cell(26,16,'    Conceptos ',1,0,'L');
  $pdf->Cell(24,16,' IVA Debito',1,0,'L');
  $pdf->Cell(24,16,' IVA Debito',1,0,'L');
  $pdf->Cell(26,16,' Usuario  ',1,0,'L');
  $pdf->Cell(26,16,'Total Facturado',1,0,'L');
  $pdf->SetY(34);
  $pdf->Cell(114);
  $pdf->Cell(23,8,'    Exentos ',0,0,'L');
  $pdf->Cell(26,8,'             ',0,0,'L');
  $pdf->Cell(26,8,' Gravados 21% ',0,0,'L');
  $pdf->Cell(26,8,' Gravados 10,5%',0,0,'L');
  $pdf->Cell(24,8,' Fiscal 21%',0,0,'L');
  $pdf->Cell(24,8,' Fiscal 10,5%',0,0,'L');
  $pdf->Cell(26,8,'         ',0,0,'L');
  
  $valor_y = 45;
  
// Datos de los renglones
$i = 0;
$acum_tot_neto21  = 0;
$acum_tot_exento  = 0;
$acum_tot_neto105 = 0;
$acum_tot_iva21   = 0;
$acum_tot_iva105  = 0;
//$acum_tot_resol   = 0;
$acum_total       = 0;
$acum_totcomis    = 0;
$pri_vez = 0;
$lo_imprimi = 0;
$tcompant = $ncompant = 0;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	if ($tcomp == $tcompant && $ncomp == $ncompant)
		continue;
	$tcompant   = $tcomp;
	$ncompant   = $ncomp;
	if($tcomp == 59 && $ncomp == 80) {
		$pri_vez = 1;
		//echo " PRIMERA VEZ QUE PASO   -  ";
		
	}
	if ($tcomp == 59 && $ncomp == 80 && $lo_imprimi == 1) {
				//echo " LE HAGO EL CONTINUE 1 ";
				continue;
	}
	if ($tcomp !=  51 && $tcomp !=  52 && $tcomp !=  53 && $tcomp !=  54 && $tcomp != 55 && 
		$tcomp != 56 && $tcomp != 57 && $tcomp != 58 && $tcomp != 59 && $tcomp != 60 && 
		$tcomp != 61 && $tcomp != 62 &&	$tcomp != 63 && $tcomp != 64  && $tcomp != 89  && $tcomp != 92   && $tcomp != 93  && $tcomp != 94 )
		continue;
	if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93) {
		$tc = "NC-";
		$signo = -1;
	}
	elseif ($tcomp == 59 ||  $tcomp == 60 || $tcomp == 63 || $tcomp == 64 || $tcomp == 94 ){
		$tc = "ND-";
		$signo = 1;
	}
	else {
		$tc = "FC-";
		$signo = 1;
	}
	if ($i <= 22) {
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		if (($tcomp == 57 || $tcomp == 58 || $tcomp == 59 || $tcomp = 60 ) && ($row_cabecerafac["totiva21"] == 0.00 &&  $row_cabecerafac["totiva105"] == 0.00)) {
			$tot_neto21   = 0.00;
			$tot_neto105  = 0.00;
			$tot_exento   = $row_cabecerafac["totneto21"] ;
		}
		else {
			$tot_neto21   = $row_cabecerafac["totneto21"] ;
			$tot_neto105  = $row_cabecerafac["totneto105"];
			$tot_exento   = 0.00;
		}
		//$tot_neto21   = $row_cabecerafac["totneto21"] ;
		//$tot_neto105  = $row_cabecerafac["totneto105"];
		$tot_comision = $row_cabecerafac["totcomis"];
		$tot_iva21    = $row_cabecerafac["totiva21"];
		$tot_iva105   = $row_cabecerafac["totiva105"];
		//$tot_resol    = $row_cabecerafac["totimp"];
		$cod_usu      = $row_cabecerafac["usuario"];
		$total        = $row_cabecerafac["totbruto"];
		$nroorig      = $row_cabecerafac["nrodoc"];
		// ACA LEO EL CBTESANUL PARA VERIFICAR SI ESTA ANULADA
		$query_cbtesanul = sprintf("SELECT * FROM cbtesanul WHERE  tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
  		$cbtesanul = mysqli_query($amercado, $query_cbtesanul) or die(mysqli_error($amercado));
		//$row_cbtesanul = mysqli_fetch_assoc($cbtesanul);
		$estado = "P";
		if (mysqli_num_rows($cbtesanul) > 0) 
			$estado		  = "A";
		
		// Acumulo subtotales
		if ($estado != "A") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93 ) {
				// resto Notas de Cr�dito
				$acum_tot_exento  = $acum_tot_exento  - $tot_exento;
				$acum_tot_neto21  = $acum_tot_neto21  - $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 - $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   - $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  - $tot_iva105;
				//$acum_tot_resol   = $acum_tot_resol   - $tot_resol;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
			}
			else {
				// Sumo Facturas y Notas de D�bito
				$acum_tot_exento  = $acum_tot_exento  + $tot_exento;
				$acum_tot_neto21  = $acum_tot_neto21  + $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 + $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   + $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  + $tot_iva105;
				//$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
				$acum_total       = $acum_total       + $total;
				$acum_totcomis    = $acum_totcomis    + $tot_comision;
					
			}
	
			$tot_exento   = number_format($tot_exento*$signo, 2, ',','.');
			$tot_neto21   = number_format($tot_neto21*$signo, 2, ',','.');
			$tot_neto105  = number_format($tot_neto105*$signo, 2, ',','.');
			$tot_iva21    = number_format($tot_iva21*$signo, 2, ',','.');
			$tot_iva105   = number_format($tot_iva105*$signo, 2, ',','.');
			//$tot_resol    = number_format($tot_resol*$signo, 2, ',','.');
			$tot_comision = number_format($tot_comision*$signo, 2, ',','.');
			$total        = number_format($total*$signo, 2, ',','.');
			
			// Leo el usuario
			$query_usuario = sprintf("SELECT * FROM usuarios WHERE  codnum = %s", $cod_usu);
  			$usu = mysqli_query($amercado, $query_usuario) or die(mysqli_error($amercado));
  			$row_usu = mysqli_fetch_assoc($usu);
			$nom_usu   = substr($row_usu["usuario"], 0, 10);
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 17);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  			if ($tcomp == 59 && $ncomp == 80 && $lo_imprimi == 1) {
				//echo " LE HAGO EL CONTINUE  2";
				continue;
			}
			
			if ($tcomp == 59 && $ncomp == 80) {
				//echo " -  lo_imprimi = 1  ";
				$lo_imprimi = 1;
				continue;
			}
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
			$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(28,6,$nroorig,0,0,'L');
  			$pdf->Cell(40,6,$nom_cliente,0,0,'L');
  			$pdf->Cell(22,6,$cuit_cliente,0,0,'L');
  			$pdf->Cell(23,6,$tot_exento,0,0,'R');
			$pdf->Cell(26,6,$tot_comision,0,0,'R');
  			$pdf->Cell(26,6,$tot_neto21,0,0,'R');
  			$pdf->Cell(26,6,$tot_neto105,0,0,'R');
  			$pdf->Cell(24,6,$tot_iva21,0,0,'R');
  			$pdf->Cell(24,6,$tot_iva105,0,0,'R');
			$pdf->Cell(24,6,$nom_usu,0,0,'L');
  			$pdf->Cell(26,6,$total,0,0,'R');
			$tot_exento   = 0.00;
			$tot_neto21   = 0.00;
			$tot_neto105  = 0.00;
			$tot_iva21    = 0.00;
			$tot_iva105   = 0.00;
			//$tot_resol    = 0.00;
			$tot_comision = 0.00;
			$total        = 0.00;
		
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
		else {
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
  	    	$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(28,6,$nroorig,0,0,'L');
  			$pdf->Cell(40,6,"ANULADA",0,0,'L');
  			$pdf->Cell(22,6," ",0,0,'L');
  			$pdf->Cell(23,6,"0,00",0,0,'R');
			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(24,6,"0,00",0,0,'R');
  			$pdf->Cell(24,6,"0,00",0,0,'R');
			$pdf->Cell(24,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
	}
	else {
		// Imprimo subtotales de la hoja, uso otras variables porque el number_format
		// me jode los acumulados
		$f_acum_tot_exento  = number_format($acum_tot_exento, 2, ',','.');
		$f_acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
		$f_acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
		$f_acum_tot_iva21   = number_format($acum_tot_iva21, 2, ',','.');
		$f_acum_tot_iva105  = number_format($acum_tot_iva105, 2, ',','.');
		//$f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
		$f_acum_total       = number_format($acum_total, 2, ',','.');
		$f_acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
		
		$pdf->SetY($valor_y);
		$pdf->Cell(112);
		$pdf->Cell(26,6,$f_acum_tot_exento,0,0,'R');
		$pdf->Cell(26,6,$f_acum_totcomis,0,0,'R');
		$pdf->Cell(26,6,$f_acum_tot_neto21,0,0,'R');
  		$pdf->Cell(26,6,$f_acum_tot_neto105,0,0,'R');
  		$pdf->Cell(24,6,$f_acum_tot_iva21,0,0,'R');
  		$pdf->Cell(24,6,$f_acum_tot_iva105,0,0,'R');
		//$pdf->Cell(24,6,$f_acum_tot_resol,0,0,'R');
  		$pdf->Cell(26,6,$f_acum_total,0,0,'R');
		
		// Voy a otra hoja e imprimo los titulos 
		$pdf->AddPage();
		$pdf->SetMargins(0.5, 0.5 , 0.5);
  		$pdf->SetFont('Arial','B',11);
		$pdf->SetY(5);
		$pdf->Cell(10);
		$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
		$pdf->Cell(200);
		$pagina = $pdf->PageNo();
		$pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
		$pdf->SetY(10);
		$pdf->Cell(230);
		$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
		$pdf->SetY(15);
		$pdf->Cell(130);
		$pdf->Cell(20,10,' Listado de comprobantes por usuario ',0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(3);
		$pdf->Cell(20,16,'    Fecha',1,0,'L');
		$pdf->Cell(30,16,' Nro.Factura',1,0,'L');
		$pdf->Cell(40,16,'       Raz�n Social',1,0,'L');
		$pdf->Cell(22,16,'     CUIT',1,0,'L');
		$pdf->Cell(23,16,'    Conceptos ',1,0,'L');
		$pdf->Cell(26,16,'   Comisiones ',1,0,'L');
		$pdf->Cell(26,16,'    Conceptos ',1,0,'L');
		$pdf->Cell(26,16,'    Conceptos ',1,0,'L');
		$pdf->Cell(24,16,' IVA Debito',1,0,'L');
		$pdf->Cell(24,16,' IVA Debito',1,0,'L');
		$pdf->Cell(24,16,' Usuario',1,0,'L');
		$pdf->Cell(26,16,'Total Facturado',1,0,'L');
		$pdf->SetY(34);
		$pdf->Cell(114);
		$pdf->Cell(23,8,'    Exentos ',0,0,'C');
		$pdf->Cell(26,8,'             ',0,0,'C');
		$pdf->Cell(26,8,' Gravados 21% ',0,0,'C');
		$pdf->Cell(26,8,' Gravados 10,5%',0,0,'C');
		$pdf->Cell(24,8,' Fiscal 21%',0,0,'C');
		$pdf->Cell(24,8,' Fiscal 10,5%',0,0,'C');
		$pdf->Cell(24,8,'         ',0,0,'C');
  
		$valor_y = 45;
		// reinicio los contadores
		
		$i = 0;
		// ACA TENGO QUE IMPRIMIR EL REGISTRO QUE ACABO DE LEER PORQUE SI NO LO PIERDO CUANDO VUELVO AL WHILE
		
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		if (($tcomp == 57 || $tcomp == 58 || $tcomp == 59 || $tcomp = 60) && ($row_cabecerafac["totiva21"] == 0.00 &&  $row_cabecerafac["totiva105"] == 0.00)) {
			$tot_neto21   = 0.00;
			$tot_neto105  = 0.00;
			$tot_exento   = $row_cabecerafac["totneto21"] ;
		}
		else {
			$tot_neto21   = $row_cabecerafac["totneto21"] ;
			$tot_neto105  = $row_cabecerafac["totneto105"];
			$tot_exento   = 0.00;
		}
		
		
		//$tot_neto21   = $row_cabecerafac["totneto21"] ;
		//$tot_neto105  = $row_cabecerafac["totneto105"];
		$tot_comision = $row_cabecerafac["totcomis"];
		$tot_iva21    = $row_cabecerafac["totiva21"];
		$tot_iva105   = $row_cabecerafac["totiva105"];
		//$tot_resol    = $row_cabecerafac["totimp"];
		$total        = $row_cabecerafac["totbruto"];
		$nroorig      = $row_cabecerafac["nrodoc"];
		// LE AGREGO LA CLAVE PORQUE ME PARECE QUE LA PIERDE
		$tcomp       = $row_cabecerafac["tcomp"];
		$serie       = $row_cabecerafac["serie"];
		$ncomp       = $row_cabecerafac["ncomp"];
		// ACA LEO EL CBTESANUL PARA VERIFICAR SI ESTA ANULADA
		$query_cbtesanul = sprintf("SELECT * FROM cbtesanul WHERE  tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
  		$cbtesanul = mysqli_query($amercado, $query_cbtesanul) or die(mysqli_error($amercado));
		//$row_cbtesanul = mysqli_fetch_assoc($cbtesanul);
		$estado = "P";
		if (mysqli_num_rows($cbtesanul) > 0) 
			$estado		  = "A";
		
		// Acumulo subtotales
		if ($estado != "A") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93) {
				// resto Notas de Cr�dito
				$acum_tot_exento  = $acum_tot_exento  - $tot_exento;
				$acum_tot_neto21  = $acum_tot_neto21  - $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 - $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   - $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  - $tot_iva105;
				//$acum_tot_resol   = $acum_tot_resol   - $tot_resol;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
			}
			else {
				// Sumo Facturas y Notas de D�bito
				$acum_tot_exento  = $acum_tot_exento  + $tot_exento;
				$acum_tot_neto21  = $acum_tot_neto21  + $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 + $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   + $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  + $tot_iva105;
				//$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
				$acum_total       = $acum_total       + $total;
				$acum_totcomis    = $acum_totcomis    + $tot_comision;
					
			}
	
	
			$tot_exento   = number_format($tot_exento*$signo, 2, ',','.');
			$tot_neto21   = number_format($tot_neto21*$signo, 2, ',','.');
			$tot_neto105  = number_format($tot_neto105*$signo, 2, ',','.');
			$tot_iva21    = number_format($tot_iva21*$signo, 2, ',','.');
			$tot_iva105   = number_format($tot_iva105*$signo, 2, ',','.');
			//$tot_resol    = number_format($tot_resol*$signo, 2, ',','.');
			$tot_comision = number_format($tot_comision*$signo, 2, ',','.');
			$total        = number_format($total*$signo, 2, ',','.');
	
			// Leo el usuario
			$query_usuario = sprintf("SELECT * FROM usuarios WHERE  codnum = %s", $cod_usu);
  			$usu = mysqli_query($amercado, $query_usuario) or die(mysqli_error($amercado));
  			$row_usu = mysqli_fetch_assoc($usu);
			$nom_usu   = substr($row_usu["usuario"], 0, 10);
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 17);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  	
			
			if ($tcomp == 59 && $ncomp == 80 && $lo_imprimi == 1) {
				continue;
			}
			
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
			$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(28,6,$nroorig,0,0,'L');
  			$pdf->Cell(40,6,$nom_cliente,0,0,'L');
  			$pdf->Cell(22,6,$cuit_cliente,0,0,'L');
  			$pdf->Cell(23,6,$tot_exento,0,0,'R');
			$pdf->Cell(26,6,$tot_comision,0,0,'R');
  			$pdf->Cell(26,6,$tot_neto21,0,0,'R');
  			$pdf->Cell(26,6,$tot_neto105,0,0,'R');
  			$pdf->Cell(24,6,$tot_iva21,0,0,'R');
  			$pdf->Cell(24,6,$tot_iva105,0,0,'R');
			$pdf->Cell(24,6,$nom_usu,0,0,'L');
  			$pdf->Cell(26,6,$total,0,0,'R');
			$tot_exento   = 0.00;
			$tot_neto21   = 0.00;
			$tot_neto105  = 0.00;
			$tot_iva21    = 0.00;
			$tot_iva105   = 0.00;
			//$tot_resol    = 0.00;
			$tot_comision = 0.00;
			$total        = 0.00;
		
			
			$valor_y = $valor_y + 6;
		}
		// desde aca el else
		else {
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
  	    	$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(26,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,"ANULADA",0,0,'L');
  			$pdf->Cell(22,6," ",0,0,'L');
  			$pdf->Cell(23,6,"0,00",0,0,'R');
			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(24,6,"0,00",0,0,'R');
  			$pdf->Cell(24,6,"0,00",0,0,'R');
			$pdf->Cell(24,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
		// hasta aca el else
		// HASTA ACA ========================================================================================
	}
}
// Imprimo subtotales de la hoja la �ltima vez
$acum_tot_exento  = number_format($acum_tot_exento, 2, ',','.');
$acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
$acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
$acum_tot_iva21   = number_format($acum_tot_iva21, 2, ',','.');
$acum_tot_iva105  = number_format($acum_tot_iva105, 2, ',','.');
//$acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
$acum_total       = number_format($acum_total, 2, ',','.');
$acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
		
$pdf->SetY($valor_y);
$pdf->Cell(112);
$pdf->Cell(26,6,$acum_tot_exento,0,0,'R');
$pdf->Cell(26,6,$acum_totcomis,0,0,'R');
$pdf->Cell(26,6,$acum_tot_neto21,0,0,'R');
$pdf->Cell(26,6,$acum_tot_neto105,0,0,'R');
$pdf->Cell(24,6,$acum_tot_iva21,0,0,'R');
$pdf->Cell(24,6,$acum_tot_iva105,0,0,'R');
$pdf->Cell(24,6,"               ",0,0,'R');
$pdf->Cell(26,6,$acum_total,0,0,'R');
		
mysqli_close($amercado);
$pdf->Output();
?>  
