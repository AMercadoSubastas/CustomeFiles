<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors','Yes');
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');
setlocale (LC_ALL,"spanish");
mysqli_select_db($amercado, $database_amercado);

// Leo los parámetros del formulario anterior

$cliente    = $_POST['cuit'];

$fechahoy = date("d-m-Y");

// Leo las cabeceras de facturas
$pe = "P";
$ese = "S";
mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE cuit = '$cliente'";
$cliente_q = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente_q);
$cliente = $row_cliente["codnum"];

$razsoc = $row_cliente["razsoc"];
$fechatope = "20171231";

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE cliente = %s AND fecreg > $fechatope ORDER BY  fecreg, cliente, nrodoc", $cliente);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));

// Inicio el pdf con los datos de cabecera
$pdf=new FPDF('P','mm','Legal');

//$pdf->AddFont('Arial','',10);
$pdf->AddPage();
$pdf->SetMargins(0.5, 0.5 , 0.5);
$pdf->SetFont('Arial','B',10);
$pdf->SetY(5);
$pdf->Cell(10);
$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
$pdf->Cell(200);
$pagina = $pdf->PageNo();
$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
$pdf->SetY(10);
$pdf->Cell(230);
$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
$pdf->SetY(15);
$pdf->Cell(50);
$pdf->Cell(20,10,utf8_decode(' Resúmen de cuenta corriente de ').$razsoc,0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(10);
$pdf->Cell(25,8,'  Fecha   ',1,0,'L');
$pdf->Cell(25,8,'  Comprobante',1,0,'L');
$pdf->Cell(40,8,utf8_decode('     Débitos     '),1,0,'L');
$pdf->Cell(40,8,utf8_decode('    Créditos    '),1,0,'L');
$pdf->Cell(40,8,'    Saldo    ',1,0,'L');
$pdf->Cell(18,8,'ID Subasta   ',1,0,'L');
  
$valor_y = 45;
  
// Datos de los renglones
$i = 0;
$signo = 1;
$tc = "";
$acum_cli    = 0.00;
$total       = 0.00;
$acum_total  = 0.00;

$acum_cli = 0.00;
$linea = 0;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	

	
	$tcomp      = $row_cabecerafac["tcomp"];
	$ncomp      = $row_cabecerafac["ncomp"];
	$totneto105 = $row_cabecerafac["totneto105"];
	$totbruto   = $row_cabecerafac["totbruto"];
	$totneto21  = $row_cabecerafac["totneto21"];
	$totcomis   = $row_cabecerafac["totcomis"];
	$nrodoc     = $row_cabecerafac["nrodoc"];
	$estado     = $row_cabecerafac["estado"];
	$fecha      = $row_cabecerafac["fecreg"];
    $codrem      = $row_cabecerafac["codrem"];
	$CAE         = $row_cabecerafac["CAE"];
	
	// Veo si son cbtes de ventas
	$query_tipcomp= "SELECT * FROM tipcomp WHERE codnum = $tcomp";
	$tipcomp = mysqli_query($amercado, $query_tipcomp) or die(mysqli_error($amercado));
	$row_Recordset3 = mysqli_fetch_assoc($tipcomp);
	

	if ($tcomp ==  119 ||  $tcomp ==  120 || $tcomp == 121 || $tcomp == 135 || $tcomp == 137 || $tcomp == 144) {
		$tc = "NC-";
		$signo = -1;
	}
	
	else 
		if ($tcomp == 115 || $tcomp == 116 || $tcomp == 117 || $tcomp == 125 || $tcomp == 126 || $tcomp == 127 || $tcomp == 133 || $tcomp == 134 || $tcomp == 136  || $tcomp == 142 || $tcomp == 103 || $tcomp == 143) {
			$tc = "FC-";
			$signo = 1;
		} else 
			if ($tcomp == 122 || $tcomp == 123 || $tcomp == 124 || $tcomp == 138) {
					$tc = "ND-";
					$signo = 1;
				} else
				if ($tcomp == 98) {
					$tc = "SGCC-";
					$signo = 1;
				} else
				if ($tcomp == 99) {
					$tc = "DCC-";
					$signo = 1;
				}
				else
					continue;
	
	$cliente = $row_cabecerafac["cliente"];
	$total   = $row_cabecerafac["totbruto"] * $signo;
	if ($tc == "FC-" ||	$tc == "ND-" ||	$tc == "SGCC-" ||	$tc == "DCC-") {
		$debito = $total;
		$credito = 0.00;
	}
	else {
		$debito = 0.00;
		$credito = $total;
	}
		// Acumulo subtotales
	
	$acum_cli     = $acum_cli + $total;
	$acum_total   = $acum_cli;
	$debito       = number_format($debito, 2, ',','.');
	$credito       = number_format($credito, 2, ',','.');
	$df_acum_total   = number_format($acum_total, 2, ',','.');
	$fecha ="'".substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4)."'";
	if ($linea < 48) {
			//$tot_cli[$j] = number_format($tot_cli[$j], 2, ',','.');
			//$porc_cli[$j] = number_format($porc_cli[$j], 2, ',','.');
			
			
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->SetX(10);
  			$pdf->Cell(25,6,$fecha,0,0,'L');
  			$pdf->Cell(25,6,$tc.$nrodoc,0,0,'L');
			$pdf->Cell(40,6,$debito,0,0,'R');
  			$pdf->Cell(40,6,$credito,0,0,'R');
			$pdf->Cell(40,6,$df_acum_total,0,0,'R');
            $pdf->Cell(15,6,$codrem,0,0,'C');
  					
			$valor_y = $valor_y + 6;
			$linea++;
	}
	else {
		$linea = 0;
		$pdf->AddPage();
		$pdf->SetMargins(0.5, 0.5 , 0.5);
		$pdf->SetFont('Arial','',11);
		$pdf->SetY(5);
		$pdf->Cell(10);
		$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
		$pdf->Cell(200);
		$pagina = $pdf->PageNo();
		$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
		$pdf->SetY(10);
		$pdf->Cell(230);
		$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
		$pdf->SetY(15);
		$pdf->Cell(50);
		$pdf->Cell(20,10,utf8_decode(' Resúmen de cuenta corriente de ').$razsoc,0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(10);
		$pdf->Cell(25,8,'  Fecha   ',1,0,'L');
		$pdf->Cell(25,8,'  Comprobante',1,0,'L');
		$pdf->Cell(40,8,utf8_decode('     Débitos     '),1,0,'L');
		$pdf->Cell(40,8,utf8_decode('    Créditos    '),1,0,'L');
		$pdf->Cell(40,8,'    Saldo    ',1,0,'L');
        $pdf->Cell(18,8,'Id Subasta   ',1,0,'L');
		  
		$valor_y = 45;
		
		$pdf->SetY($valor_y);
		$pdf->Cell(1);
		$pdf->SetX(10);
		$pdf->Cell(25,6,$fecha,0,0,'L');
		$pdf->Cell(25,6,$tc.$nrodoc,0,0,'L');
		$pdf->Cell(40,6,$debito,0,0,'R');
        $pdf->Cell(40,6,$credito,0,0,'R');
        $pdf->Cell(40,6,$df_acum_total,0,0,'R');
        $pdf->Cell(15,6,$codrem,0,0,'C');

		$valor_y = $valor_y + 6;
		$linea++;
	}
	if ($estado == 'S' && ($tc != "SGCC-" && $tc != "DCC-")) {
		//VOY A BUSCAR EL RECIBO DE PAGO
		$query_detrecibo = sprintf("SELECT * FROM detrecibo WHERE  tcomprel = %s AND ncomprel = %s", $tcomp, $ncomp);
		$detrecibo = mysqli_query($amercado, $query_detrecibo) or die("ERROR LEYENDO DETRECIBO ".$query_detrecibo." - ");
		while ($row_detrecibo = mysqli_fetch_array($detrecibo)) {
		if ($row_detrecibo["tcomprel"] == 119 || $row_detrecibo["tcomprel"] == 120 || $row_detrecibo["tcomprel"] == 121 || $row_detrecibo["tcomprel"] == 135) 
			$signo_recibo = -1;
		else
			$signo_recibo = -1;
		//$netocbterel = $row_detrecibo["netocbterel"] * $signo_recibo;
        $ncomp_recibo = $row_detrecibo["ncomp"];
		$tcomp_recibo = $row_detrecibo["tcomp"];
        // if ($netocbterel == 0)
        //    continue;
		$debito = 0.00;
		
        $query_cabrecibo = sprintf("SELECT * FROM cabrecibo WHERE tcomp = %s and ncomp = %s", $tcomp_recibo, $ncomp_recibo);
        $cabrecibo = mysqli_query($amercado, $query_cabrecibo) or die("ERROR LEYENDO CABRECIBO ".$query_cabrecibo." - ");
        $row_cabrecibo = mysqli_fetch_array($cabrecibo);
		if ($row_detrecibo["nreng"] > 1)
			$netocbterel = 0.00;
		else
			$netocbterel = $row_cabrecibo["imptot"] * $signo_recibo;
		$credito = $netocbterel;
        $fecha_rem = $row_cabrecibo["fecha"];
		$acum_cli     = $acum_cli + $netocbterel;
		$acum_total   = $acum_cli;
		$debito       = number_format($debito, 2, ',','.');
		$credito       = number_format($credito, 2, ',','.');
		$df_acum_total   = number_format($acum_total, 2, ',','.');
		//$fecha_rem = $row_detrecibo["fechahora"];
		$fecha_rem ="'".substr($fecha_rem,8,2)."-".substr($fecha_rem,5,2)."-".substr($fecha_rem,0,4)."'";
		$tc_recibo = $row_detrecibo["ncomp"];
		if ($linea < 48) {
			$pdf->SetY($valor_y);
			$pdf->Cell(1);
			$pdf->SetX(10);
			$pdf->Cell(25,6,$fecha_rem,0,0,'L');
			$pdf->Cell(25,6,"Recibo:".$tc_recibo,0,0,'L');
			$pdf->Cell(40,6,$debito,0,0,'R');
  			$pdf->Cell(40,6,$credito,0,0,'R');
			$pdf->Cell(40,6,$df_acum_total,0,0,'R');
            $pdf->Cell(15,6,$codrem,0,0,'C');

			$valor_y = $valor_y + 6;
			$linea++;
		}
		else {
			$linea = 0;
			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
			$pdf->Cell(200);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(230);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetY(15);
			$pdf->Cell(70);
			$pdf->Cell(20,10,utf8_decode(' Resúmen de cuenta corriente de ').$razsoc,0,0,'L');
			$pdf->SetFont('Arial','B',9);
			$pdf->SetY(25);
			$pdf->Cell(10);
			$pdf->Cell(25,8,'  Fecha   ',1,0,'L');
			$pdf->Cell(25,8,'  Comprobante',1,0,'L');
			$pdf->Cell(40,8,utf8_decode('     Débitos     '),1,0,'L');
            $pdf->Cell(40,8,utf8_decode('    Créditos    '),1,0,'L');
            $pdf->Cell(40,8,'    Saldo    ',1,0,'L');
            $pdf->Cell(18,8,'Id Subasta   ',1,0,'L');

			$valor_y = 45;

			$pdf->SetY($valor_y);
			$pdf->Cell(1);
			$pdf->SetX(10);
			$pdf->Cell(25,6,$fecha_rem,0,0,'L');
			$pdf->Cell(25,6,"Recibo:".$tc_recibo,0,0,'L');
			$pdf->Cell(40,6,$debito,0,0,'R');
  			$pdf->Cell(40,6,$credito,0,0,'R');
			$pdf->Cell(40,6,$df_acum_total,0,0,'R');
            $pdf->Cell(15,6,$codrem,0,0,'C');

			$valor_y = $valor_y + 6;
			$linea++;
		}
		}
	}	

}

// Imprimo subtotales de la hoja la última vez

$acum_total       = number_format($acum_total, 2, ',','.');

		
$pdf->SetY($valor_y);
$pdf->Cell(128);
$pdf->Cell(26,6,"SALDO:  ",0,0,'R');

$pdf->Cell(26,6,$acum_total,0,0,'R');
		
mysqli_close($amercado);
$pdf->Output();
?>