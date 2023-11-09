<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');
setlocale (LC_ALL,"spanish");
mysqli_select_db($amercado, $database_amercado);

// Leo los parámetros del formulario anterior

$cliente    = $_POST['codnum'];

$fechahoy = date("d-m-Y");

// Leo las cabeceras de facturas
$pe = "P";
$ese = "S";
mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE codnum = $cliente ";
$cliente_q = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente_q);

$razsoc = $row_cliente["razsoc"];
$fechatope = "20071231";

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE cliente = %s  ORDER BY  fecreg, cliente, nrodoc", $cliente);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));

// Inicio el pdf con los datos de cabecera
$pdf=new FPDF('P','mm','Legal');

//$pdf->AddFont('Arial','',10);
$pdf->AddPage();
$pdf->SetMargins(0.5, 0.5 , 0.5);
$pdf->SetFont('Arial','B',10);
$pdf->SetY(5);
$pdf->Cell(10);
$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
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

	//if($row_cabecerafac["estado"] == "A")
	//	continue;

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
	
	// Veo si son cbtes de ventas
	$query_tipcomp= "SELECT * FROM tipcomp WHERE codnum = $tcomp";
	$tipcomp = mysqli_query($amercado, $query_tipcomp) or die(mysqli_error($amercado));
	$row_Recordset3 = mysqli_fetch_assoc($tipcomp);
	if ($tcomp != 32 && $tcomp != 33 && $tcomp != 34 && $tcomp != 35 && $tcomp != 36)
		continue;

	if ($tcomp ==  36 ||  $tcomp ==  37 ) {
		$tc = "NC-";
		$signo = -1;
	}
	
	else 
		if ($tcomp == 32 || $tcomp == 33 ) {
			$tc = "FC-";
			$signo = 1;
		} else 
			if ($tcomp == 34 || $tcomp == 35 ) {
					$tc = "ND-";
					$signo = 1;
				}	
	
	$cliente = $row_cabecerafac["cliente"];
	$total   = $row_cabecerafac["totbruto"] * $signo;
	if ($tc == "FC-" ||	$tc == "ND-") {
		$credito = $total;
		$debito = 0.00;
	}
	else {
		$credito = 0.00;
		$debito = $total;
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
		$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
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
        
		  
		$valor_y = 45;
		
		$pdf->SetY($valor_y);
		$pdf->Cell(1);
		$pdf->SetX(10);
		$pdf->Cell(25,6,$fecha,0,0,'L');
		$pdf->Cell(25,6,$tc.$nrodoc,0,0,'L');
		$pdf->Cell(40,6,$debito,0,0,'R');
        $pdf->Cell(40,6,$credito,0,0,'R');
        $pdf->Cell(40,6,$df_acum_total,0,0,'R');
       

		$valor_y = $valor_y + 6;
		$linea++;
	}
	

}

// Imprimo subtotales de la hoja la última vez
//echo " -  4 total  -  ";
$acum_total       = number_format($acum_total, 2, ',','.');

		
$pdf->SetY($valor_y);
$pdf->Cell(128);
$pdf->Cell(26,6,"SALDO:  ",0,0,'R');

$pdf->Cell(26,6,$acum_total,0,0,'R');
		
mysqli_close($amercado);
$pdf->Output();
?>  
