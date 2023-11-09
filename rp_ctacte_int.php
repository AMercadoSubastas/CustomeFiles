<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior

$cliente    = $_POST['codnum'];

//$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
//$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");

// Leo las cabeceras de facturas
$pe = "P";
$ese = "S";
mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE codnum = $cliente ";
$cliente_q = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente_q);

$razsoc = $row_cliente["razsoc"];
$fechatope = "20200101";

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE cliente = %s AND fecreg > $fechatope ORDER BY  fecreg, cliente, nrodoc", $cliente);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));

// Inicio el pdf con los datos de cabecera
$pdf=new FPDF('P','mm','A4');

//$pdf->AddFont('Arial','',10);
$pdf->AddPage();
$pdf->SetMargins(0.5, 0.5 , 0.5);
$pdf->SetFont('Arial','B',10);
$pdf->SetY(5);
$pdf->Cell(10);
$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');

//$pdf->SetY(10);
$pdf->Cell(140);
$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
$pdf->SetY(15);
$pdf->Cell(70);
$pdf->Cell(20,10,' Res�men de saldos a favor de '.$razsoc,0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(10);
$pdf->Cell(25,8,'  Fecha  ',1,0,'L');
$pdf->Cell(30,8,'   Comprobante',1,0,'L');
$pdf->Cell(40,8,'      D�bitos     ',1,0,'L');
$pdf->Cell(40,8,'      Cr�ditos    ',1,0,'L');
$pdf->Cell(40,8,'       Saldo    ',1,0,'L');
$pdf->Cell(15,8,'   ID   ',1,0,'L');
  
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

	if($row_cabecerafac["estado"] == "A")
		continue;

	$tcomp      = $row_cabecerafac["tcomp"];
	$ncomp      = $row_cabecerafac["ncomp"];
	$totneto105 = $row_cabecerafac["totneto105"];
	$totbruto   = $row_cabecerafac["totbruto"];
	$totneto21  = $row_cabecerafac["totneto"];
	$totcomis   = $row_cabecerafac["totcomis"];
	$nrodoc     = $row_cabecerafac["nrodoc"];
	$estado     = $row_cabecerafac["estado"];
	$fecha      = $row_cabecerafac["fecreg"];
    $codrem      = $row_cabecerafac["codrem"];
	
	// Veo si son cbtes de ventas
	$query_tipcomp= "SELECT * FROM tipcomp WHERE codnum = $tcomp";
	$tipcomp = mysqli_query($amercado, $query_tipcomp) or die(mysqli_error($amercado));
	$row_Recordset3 = mysqli_fetch_assoc($tipcomp);
	if ($tcomp != 98 && $tcomp != 99 )
		continue;
    if ($tcomp == 98 && $estado == "S")
		continue;

	if ($tcomp ==  99 ) {
		$tc = "DEV-";
		$signo = -1;
	}
	
	else 
		if ($tcomp == 98) {
			$tc = "SAF-";
			$signo = 1;
		} 	
	
	$cliente = $row_cabecerafac["cliente"];
	$total   = $row_cabecerafac["totbruto"] * $signo;
	if ($tc == "DEV-" ) {
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
					
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->SetX(10);
  			$pdf->Cell(25,6,$fecha,0,0,'L');
  			$pdf->Cell(30,6,$tc.$nrodoc,0,0,'L');
			$pdf->Cell(40,6,$debito,0,0,'R');
  			$pdf->Cell(40,6,$credito,0,0,'R');
			$pdf->Cell(40,6,$df_acum_total,0,0,'R');
            $pdf->Cell(15,6,$codrem,0,0,'R');
  					
			$valor_y = $valor_y + 6;
			$linea++;
	}
	else {
		$linea = 0;
        $pdf->SetY(260);
        //$pdf->Cell(143);
        $pdf->Cell(100);
        $pagina = $pdf->PageNo();
        $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
		$pdf->AddPage();
		$pdf->SetMargins(0.5, 0.5 , 0.5);
		$pdf->SetFont('Arial','',11);
		$pdf->SetY(5);
		$pdf->Cell(10);
		$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
		
		//$pdf->SetY(10);
		$pdf->Cell(140);
		$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
		$pdf->SetY(15);
		$pdf->Cell(70);
		$pdf->Cell(20,10,' Res�men de saldos a favor de '.$razsoc,0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(10);
        $pdf->Cell(25,8,'  Fecha  ',1,0,'L');
        $pdf->Cell(30,8,'   Comprobante',1,0,'L');
        $pdf->Cell(40,8,'      D�bitos     ',1,0,'L');
        $pdf->Cell(40,8,'      Cr�ditos    ',1,0,'L');
        $pdf->Cell(40,8,'       Saldo    ',1,0,'L');
        $pdf->Cell(15,8,'   ID   ',1,0,'L');
		  
		$valor_y = 45;
		
		$pdf->SetY($valor_y);
		$pdf->Cell(1);
		$pdf->SetX(10);
		$pdf->Cell(25,6,$fecha,0,0,'L');
		$pdf->Cell(30,6,$tc.$nrodoc,0,0,'L');
        $pdf->Cell(40,6,$debito,0,0,'R');
        $pdf->Cell(40,6,$credito,0,0,'R');
        $pdf->Cell(40,6,$df_acum_total,0,0,'R');
        $pdf->Cell(15,6,$codrem,0,0,'R');

		$valor_y = $valor_y + 6;
		$linea++;
	}
	if ($estado == 'S') {
		
        // ACA DEBERIA LEER CARTVALORES PARA VER CUANTO SE DESCONTO
        $query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE  tcomp = 98 AND ncomp = %s", $ncomp);
        $cartvalores = mysqli_query($amercado, $query_cartvalores) or die("ERROR LEYENDO CARTVALORES");
        $row_cartvalores = mysqli_fetch_array($cartvalores);
        $netocbterel = $row_cartvalores["importe"];
        $debito = $netocbterel * -1;
        $credito = 0.00;
        $codrem = $row_cartvalores["codrem"];
        $fecha_rem = $row_cartvalores["fechapago"];
        $acum_cli     = $acum_cli + $debito;
        $acum_total   = $acum_cli;
        $debito       = number_format($debito, 2, ',','.');
        $credito       = number_format($credito, 2, ',','.');
        $df_acum_total   = number_format($acum_total, 2, ',','.');
        //$fecha_rem = $row_detrecibo["fechahora"];
        $fecha_rem ="'".substr($fecha_rem,8,2)."-".substr($fecha_rem,5,2)."-".substr($fecha_rem,0,4)."'";
        $tc_recibo = $row_cartvalores["ncomprel"];
        if ($linea < 48) {
            $pdf->SetY($valor_y);
            $pdf->Cell(1);
            $pdf->SetX(10);
            $pdf->Cell(25,6,$fecha,0,0,'L');
            $pdf->Cell(30,6,"Recibo:".$tc_recibo,0,0,'L');
            $pdf->Cell(40,6,$debito,0,0,'R');
            $pdf->Cell(40,6,$credito,0,0,'R');
            $pdf->Cell(40,6,$df_acum_total,0,0,'R');
            $pdf->Cell(15,6,$codrem,0,0,'R');

            $valor_y = $valor_y + 6;
            $linea++;
        }
        else {
            $linea = 0;
            $pdf->SetY(260);
            //$pdf->Cell(143);
            $pdf->Cell(100);
            $pagina = $pdf->PageNo();
            $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
            //$valor_y = $valor_y + 6;
            //$linea++;
            $pdf->AddPage();
            $pdf->SetMargins(0.5, 0.5 , 0.5);
            $pdf->SetFont('Arial','',11);
            $pdf->SetY(5);
            $pdf->Cell(10);
            $pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
            
            $pdf->Cell(180);
            $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
            $pdf->SetY(15);
            $pdf->Cell(70);
            $pdf->Cell(20,10,' Res�men de saldos a favor de '.$razsoc,0,0,'L');
            $pdf->SetFont('Arial','B',9);
            $pdf->SetY(25);
            $pdf->Cell(10);
            $pdf->Cell(25,8,'  Fecha  ',1,0,'L');
            $pdf->Cell(30,8,'   Comprobante',1,0,'L');
            $pdf->Cell(40,8,'      D�bitos     ',1,0,'L');
            $pdf->Cell(40,8,'      Cr�ditos    ',1,0,'L');
            $pdf->Cell(40,8,'       Saldo    ',1,0,'L');
            $pdf->Cell(15,8,'   ID   ',1,0,'L');

            $valor_y = 45;

            $pdf->SetY($valor_y);
            $pdf->Cell(1);
            $pdf->SetX(10);
            $pdf->Cell(25,6,$fecha,0,0,'L');
            $pdf->Cell(30,6,"Recibo:".$tc_recibo,0,0,'L');
            $pdf->Cell(40,6,$debito,0,0,'R');
            $pdf->Cell(40,6,$credito,0,0,'R');
            $pdf->Cell(40,6,$df_acum_total,0,0,'R');
            $pdf->Cell(15,6,$codrem,0,0,'R');
            
            
        }
	}	
}

// Imprimo subtotales de la hoja la �ltima vez
//echo " -  4 total  -  ";
$acum_total       = number_format($acum_total, 2, ',','.');
		
$pdf->SetY($valor_y);
$pdf->Cell(132);
$pdf->Cell(26,6,"SALDO:  ",0,0,'R');

$pdf->Cell(26,6,$acum_total,0,0,'R');
$pdf->SetY(260);
//$pdf->Cell(143);
$pdf->Cell(100);
$pagina = $pdf->PageNo();
$pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
mysqli_close($amercado);
$pdf->Output();
?>  