<?php
set_time_limit(0); // Para evitar el timeout3
require('fpdf17/fpdf.php');


require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];
$f_desde = $fecha_desde;
$f_hasta = $fecha_hasta;
$fecha_desde = "'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");

//Luego lo sacar� de la tabla impuesto
$impchq = 0.012;

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
  	$pdf->Cell(25);
  	$pdf->Cell(20,10,'  Impuesto a los dep�sitos bancarios por Remate desde: '.$fecha_desde.' hasta: '.$fecha_hasta,0,0,'L');
  	$pdf->SetFont('Arial','B',9);
  	$pdf->SetY(25);
  	$pdf->Cell(40);
  	$pdf->Cell(40,16,' Remate ',1,0,'L');
	$pdf->Cell(40,16,' Importe ',1,0,'L');
	$pdf->Cell(40,16,' Impuesto ',1,0,'L');
  	$pdf->SetY(33);
  	$pdf->Cell(115);
  	$pdf->SetFont('Arial','B',8);  
  	$valor_y = 45;
  
	$i = 60;
	$j = 100;
	$cant_reng  = 0;
		
	$query_remate = sprintf("SELECT * FROM remates WHERE fecreal BETWEEN %s AND %s ORDER BY ncomp", $fecha_desde, $fecha_hasta);
	//echo "QUERY_REMATE =   ".$query_remate."  ";
	$result_remate = mysqli_query($amercado, $query_remate) or die("NO PUEDO LEER REMATES");  
		
	$tot_gral_retenciones  = 0.0;
	$tot_gral_rem = 0.00;	
	$tot_gral_imp = 0.00;
	while($row_remate = mysqli_fetch_array($result_remate)) {
		$total_remate = 0.00;
		$total_retenciones = 0.00;
		$remate = $row_remate["ncomp"];
		//ACA LEO LAS FACTURAS, NCRED, NDEB RELACIONADAS CON EL REMATE,POR LOTES en_liquid=1
		$query_cabfac = sprintf("SELECT * FROM cabfac WHERE  codrem = $remate AND (((tcomp = 51 OR tcomp = 53) AND estado = 'S') OR (tcomp IN (57,58,59,60,89) AND en_liquid = 1)) ORDER BY tcomp,ncomp");
		$cabfac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));
				
		while($row_cabfac = mysqli_fetch_array($cabfac)) {
			$tcomp    = $row_cabfac["tcomp"];
			$ncomp_fc = $row_cabfac["ncomp"];
			$estado   = $row_cabfac["estado"];
			if ($estado == "P") 
				continue;
			switch ($tcomp) {
				case 51:
				case 53:
				case 89:
				case 59:
				case 60:
					$tot_cbte = ($row_cabfac["totneto21"]*1.21) + ($row_cabfac["totneto105"]*1.105);
					break;
				case 57:
				case 58:
					$tot_cbte = (($row_cabfac["totneto21"]*1.21) + ($row_cabfac["totneto105"]*1.105)) * -1;
					break;
			}
			//$total_remate += $tot_cbte;
			// LEO EL RECIBO DE LA FACTURA
			$tcomprel_cabfac = $row_cabfac["tcomp"];
			$serierel_cabfac = $row_cabfac["serie"];
			$ncomprel_cabfac = $row_cabfac["ncomp"];
			$totbruto_cabfac = $row_cabfac["totbruto"];
			$query_detrecibo = sprintf("SELECT * FROM detrecibo WHERE  tcomprel = $tcomprel_cabfac AND serierel = $serierel_cabfac AND ncomprel = $ncomprel_cabfac ");
			$detrecibo = mysqli_query($amercado, $query_detrecibo) or die("NO PUEDO LEER EL RECIBO"); 
			$row_detrecibo = mysqli_fetch_assoc($detrecibo);
			$ncomp_recibo = $row_detrecibo["ncomp"];
			//echo "NCOM_RECIBO =  ".$ncomp_recibo."  ";
			// LEO CARTVALORES PARA ESE RECIBO
			if ($ncomp_recibo == null)
				continue;
			$query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE  ncomprel = $ncomp_recibo ");
			$cartvalores = mysqli_query($amercado, $query_cartvalores) or die("NO PUEDO LEER CARTVALORES"); 

			$totalRows_cartvalores = mysqli_num_rows($cartvalores);
			//if ($totalRows_cartvalores == 0)
			//	continue;

			while($row_medios = mysqli_fetch_array($cartvalores))	{

				$tcomp_medpag      = $row_medios["tcomp"];
				$serie_medpag      = $row_medios["serie"];
				$ncomp_medpag      = $row_medios["ncomp"];

				
				switch($tcomp_medpag) {
					case 8:
					case 9:
					case 39:
					case 12:
					case 13:
					case 14:
						break;
					case 66:
					case 67:
					case 68:
					case 69:
					case 70:
					case 71:
					case 72:
					case 73:
					case 74:
					case 75:
					case 76:
					case 77:
					case 78:
					case 79:
					case 80:
					case 81:
					case 82:
					case 83:
					case 84:
					case 85:
					case 90:
						$total_retenciones       += $row_medios["importe"];
						$tot_gral_retenciones  	 += $row_medios["importe"];
						break;

				}
		}
		
	
		if ($cant_reng > 28) {
			$cant_reng  = 0;
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
			$pdf->Cell(25);
			$pdf->Cell(20,10,'  Impuesto a los dep�sitos bancarios por Remate desde: '.$fecha_desde.' hasta: '.$fecha_hasta,0,0,'L');
			$pdf->SetFont('Arial','B',9);
			$pdf->SetY(25);
			$pdf->Cell(40);
  			$pdf->Cell(40,16,' Remate ',1,0,'L');
			$pdf->Cell(40,16,' Importe ',1,0,'L');
			$pdf->Cell(40,16,' Impuesto ',1,0,'L');
			$pdf->SetY(33);
			$pdf->Cell(115);
			$pdf->SetFont('Arial','B',8);  
			$valor_y = 45;

		}
		//$cant_reng +=1; 
		//$pdf->SetY($valor_y);
		//$pdf->Cell(20);
		//$pdf->Cell(65,8,"CBTE: ".$tcomp."-".$ncomp_fc." NETO:  ".$tot_cbte."  BRUTO: ".$totbruto_cabfac." RET:  ".$total_retenciones."   " ,0,0,'L');
		//$valor_y = $valor_y + 6;	
		$total_remate += $tot_cbte;
		$total_remate -= $total_retenciones;
		$tot_gral_rem += $tot_cbte;
		$tot_gral_rem -= $total_retenciones;
		$tot_cbte = 0.00;
		$tot_retenciones = 0.00;
	}
	
	if ($cant_reng > 28) {
			$cant_reng  = 0;
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
			$pdf->Cell(25);
			$pdf->Cell(20,10,'  Impuesto a los dep�sitos bancarios por Remate desde: '.$fecha_desde.' hasta: '.$fecha_hasta,0,0,'L');
			$pdf->SetFont('Arial','B',9);
			$pdf->SetY(25);
			$pdf->Cell(40);
			$pdf->Cell(40,16,' Remate ',1,0,'L');
			$pdf->Cell(40,16,' Importe ',1,0,'L');
			$pdf->Cell(40,16,' Impuesto ',1,0,'L');
			$pdf->SetY(33);
			$pdf->Cell(115);
			$pdf->SetFont('Arial','B',8);  
			$valor_y = 45;

	}

	if ($total_remate != 0.0) {
			$cant_reng +=1; 
			$pdf->SetY($valor_y);
			$pdf->Cell(50);
			$pdf->Cell(25,8," Nro. ".$remate."  ",0,0,'R');
			//$pdf->Cell(30,8,' Total Valores    : ',0,0,'L');
			$tot_remate_imp   = $total_remate * $impchq;
			$tot_gral_imp += $tot_remate_imp;
			$tot_remate_imp   = number_format($tot_remate_imp, 2, ',','.');
			$total_remate     = number_format($total_remate, 2, ',','.');
			$pdf->Cell(20);
			$pdf->Cell(25,8,$total_remate,0,0,'R');
			$pdf->Cell(25,8,$tot_remate_imp,0,0,'R');
			$valor_y = $valor_y + 6;
			
	}
	$total_remate = 0.00;
	$tot_remate_imp = 0.00;
	
		
	
}
	if ($cant_reng > 28) {
		$cant_reng  = 0;
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
			$pdf->Cell(25);
			$pdf->Cell(20,10,'  Impuesto a los dep�sitos bancarios por Remate desde: '.$fecha_desde.' hasta: '.$fecha_hasta,0,0,'L');
			$pdf->SetFont('Arial','B',9);
			$pdf->SetY(25);
			$pdf->Cell(40);
			$pdf->Cell(40,16,' Remate ',1,0,'L');
			$pdf->Cell(40,16,' Importe ',1,0,'L');
			$pdf->Cell(40,16,' Impuesto ',1,0,'L');
			$pdf->SetY(33);
			$pdf->Cell(115);
			$pdf->SetFont('Arial','B',8);  
			$valor_y = 45;
	}
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
	$pdf->Cell(10);
	$pdf->Cell(160,8,'===============================================================================================================',0,0,'L');	
	$valor_y = $valor_y + 6;
	
	$cant_reng +=1;

	

	if ($cant_reng > 28) {
			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(120);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetY(15);
			$pdf->Cell(25);
			$pdf->Cell(20,10,'  Impuesto a los dep�sitos bancarios por Remate desde: '.$fecha_desde.' hasta: '.$fecha_hasta,0,0,'L');
			$pdf->SetFont('Arial','B',9);
			$pdf->SetY(25);
			$pdf->Cell(40);
			$pdf->Cell(40,16,' Remate ',1,0,'L');
			$pdf->Cell(40,16,' Importe ',1,0,'L');
			$pdf->Cell(40,16,' Impuesto ',1,0,'L');
	  		$pdf->SetY(33);
	  		$pdf->Cell(115);
	  		$pdf->SetFont('Arial','B',8);  
	  		$valor_y = 45;
			$pdf->Cell(20);
			$tot_rem = $tot_gral_rem;
			$tot_gral_rem   = number_format($tot_gral_rem, 2, ',','.');
			$tot_gral_imp   = number_format($tot_gral_imp, 2, ',','.');
			$pdf->Cell(55,8,'TOTALES: ',0,0,'R');
			$pdf->Cell(40,8,$tot_gral_rem,0,0,'R');
			$pdf->Cell(35,8,$tot_gral_imp,0,0,'R');
			$cant_reng = 0;
	}
	else {
	
			// =============================================================
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(20);
			$tot_rem = $tot_gral_rem;
			$tot_gral_rem   = number_format($tot_gral_rem, 2, ',','.');
			$tot_gral_imp   = number_format($tot_gral_imp, 2, ',','.');
			$pdf->Cell(55,8,'TOTALES: ',0,0,'R');
			$pdf->Cell(40,8,$tot_gral_rem,0,0,'R');
			$pdf->Cell(35,8,$tot_gral_imp,0,0,'R');
			$valor_y = $valor_y + 6;
			$cant_reng +=1;
			// =============================================================
			
	}

mysqli_close($amercado);
$pdf->Output();
?>