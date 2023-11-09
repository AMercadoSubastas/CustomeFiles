<?php
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];
$f_desde = $fecha_desde;
$f_hasta = $fecha_hasta;
$fecha_desde = "'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");

// Inicio el pdf con los datos de cabecera
	$pdf=new FPDF('P','mm','A4');
	$pdf->AddPage();
	$pdf->SetMargins(0.5, 0.5 , 0.5);
  	$pdf->SetFont('Arial','B',11);
  	$pdf->SetY(5);
  	$pdf->Cell(10);
  	$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
  	$pdf->Cell(120);
  	$pagina = $pdf->PageNo();
  	$pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
  	$pdf->SetY(10);
  	$pdf->Cell(150);
  	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  	$pdf->SetY(15);
  	$pdf->Cell(50);
  	$pdf->Cell(20,10,'  Facturas por Remate - Liquidacion '.$f_desde.' hasta '.$f_hasta,0,0,'L');
  	$pdf->SetFont('Arial','B',9);
  	$pdf->SetY(25);
  	$pdf->Cell(5);
  	$pdf->Cell(15,8,' Fecha ',1,0,'L');
  	$pdf->Cell(30,8,'    Cliente ',1,0,'L');
  	$pdf->Cell(25,8,' Factura ',1,0,'L');
	$pdf->Cell(20,8,' Neto al 10,5',1,0,'L');
	$pdf->Cell(20,8,' Neto al 21',1,0,'L');
	$pdf->Cell(20,8,'   Comision',1,0,'L');
	$pdf->Cell(20,8,'       IVA  ',1,0,'L');
	$pdf->Cell(15,8,' Remate   ',1,0,'L');
	$pdf->Cell(15,8,' Liquid',1,0,'L');
	$pdf->Cell(15,8,' Afecta? ',1,0,'L');
  	$pdf->SetY(33);
  	$pdf->Cell(115);
  	$pdf->SetFont('Arial','B',8);  
  	$valor_y = 45;
  
	$i = 60;
	$j = 100;
	$cant_reng  = 0;
	$tipoenti=1;
	
	// Leo las facturas
	$query_cabfac = sprintf("SELECT * FROM cabfac WHERE  fecval between  %s and %s and tcomp in (51,52,53,54,57,58,59,60,86,89,92,93,94,103,104,105) ORDER BY codrem, tcomp, ncomp", $fecha_desde, $fecha_hasta);
	$cabfac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABECERA DE FACTURAS");


	$cant_reng = 0;
	$cant_cli = 0;
	$cant_cabfac = 0;
	$total_facturas = 0;
	$total_recibos = 0;
	$total_cli = 0.0;
	$total = 0.0;
	while($row_cabfac = mysqli_fetch_array($cabfac)) {
		$cant_cabfac++;
		$cliente      = $row_cabfac["cliente"];
        $tcomp_fc = $row_cabfac["tcomp"];
        $ncomp_fc = $row_cabfac["ncomp"];
		// lOS QUE NO TIENEN REMATE ASOCIADO SON DE INMOBILIARIA
		if ($row_cabfac["codrem"] == 0 || $row_cabfac["codrem"] == "")
			continue;
		// LEO LA ENTIDAD
		$query_entidades = sprintf("SELECT * FROM entidades WHERE codnum = %s", $cliente);
		$entidades = mysqli_query($amercado, $query_entidades) or die ("ERROR LEYENDO ENTIDAD");
		$row_entidades = mysqli_fetch_assoc($entidades);
		$razsoc       = substr($row_entidades["razsoc"],0,14);
        
        // LEO EL DETALLE PARA OBTENER EL NRO DE LIQUIDACION
		$query_detf = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND ncomp = %s", $tcomp_fc, $ncomp_fc);
		$detf = mysqli_query($amercado, $query_detf) or die ("ERROR LEYENDO ENTIDAD");
		$row_detf = mysqli_fetch_assoc($detf);
		
		if ($row_cabfac["tcomp"] == 57 || $row_cabfac["tcomp"] == 58 || $row_cabfac["tcomp"] == 93 || $row_cabfac["tcomp"] == 105 )
			$signo = -1;
		else
			$signo = 1;
			
			if ($cant_reng > 26) {
					$pdf->AddPage();
					$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(50);
					$pdf->Cell(20,10,'  Facturas por Remate - Liquidacion '.$f_desde.' hasta '.$f_hasta,0,0,'L');
					$pdf->SetFont('Arial','B',9);
					$pdf->SetY(25);
					$pdf->Cell(5);
					$pdf->Cell(15,8,' Fecha ',1,0,'L');
					$pdf->Cell(30,8,'         Cliente        ',1,0,'L');
					$pdf->Cell(25,8,'   Factura ',1,0,'L');
					$pdf->Cell(20,8,' Neto al 10,5',1,0,'R');
					$pdf->Cell(20,8,' Neto al 21',1,0,'R');
					$pdf->Cell(20,8,'   Comision',1,0,'R');
					$pdf->Cell(20,8,'       IVA  ',1,0,'R');
					$pdf->Cell(15,8,' Remate   ',1,0,'L');
					$pdf->Cell(15,8,' Liquid',1,0,'L');
					$pdf->Cell(15,8,' Afecta? ',1,0,'L');
					$pdf->SetY(33);
					$pdf->SetFont('Arial','B',8);  
					$valor_y = 45;
					$cant_reng  = 0;
					$i = 60;
					$j = 100;
					
			}
			$totneto105 = $row_cabfac["totneto105"] * $signo;
			$totneto21  = $row_cabfac["totneto21"] * $signo;
			$totcomis   = $row_cabfac["totcomis"] * $signo;
			$totiva     = ($row_cabfac["totiva105"] + $row_cabfac["totiva21"]) * $signo;
			$totneto105 = number_format($totneto105, 2);
			$totcomis   = number_format($totcomis, 2);
			$totneto21  = number_format($totneto21, 2);
			$totiva     = number_format($totiva, 2);
			$pdf->SetY($valor_y);
			$pdf->Cell(5);
			$pdf->Cell(15,8,$row_cabfac["fecreg"],0,0,'L');
			$pdf->Cell(30,8,$razsoc,0,0,'L');
			$pdf->Cell(25,8,$row_cabfac["nrodoc"],0,0,'L');
			$pdf->Cell(20,8,$totneto105,0,0,'R');
			$pdf->Cell(20,8,$totneto21,0,0,'R');
			$pdf->Cell(20,8,$totcomis,0,0,'R');
			$pdf->Cell(20,8,$totiva,0,0,'R');
			$pdf->Cell(15,8,$row_cabfac["codrem"],0,0,'L');
			$pdf->Cell(15,8,$row_detf["ncompsal"],0,0,'L');
			if ($row_cabfac["en_liquid"] == 0) 
				$pdf->Cell(15,8,'No afecta ',0,0,'L');
			else
				$pdf->Cell(15,8,' Afecta ',0,0,'L');
			
			$valor_y += 8;
			$cant_reng  += 1;
		}
	
mysqli_close($amercado);
$pdf->Output();
?>  
