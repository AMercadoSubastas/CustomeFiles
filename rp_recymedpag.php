<?php
set_time_limit(0); // Para evitar el timeout
require_once('fpdf17/fpdf.php');
require_once('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');
//header('Content-Type: text/html; charset=UTF-8');


mysqli_select_db($amercado, $database_amercado);

// Leo los parametros del formulario anterior
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
  	$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
  	$pdf->Cell(120);
  	$pagina = $pdf->PageNo();
  	$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
  	$pdf->SetY(10);
  	$pdf->Cell(150);
  	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  	$pdf->SetY(18);
  	$pdf->Cell(30);
  	$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');
  	$pdf->SetFont('Arial','B',9);
  	$pdf->SetY(25);
  	$pdf->Cell(10);
  	$pdf->Cell(30,8,' Recibo ',1,0,'L');
  	$pdf->Cell(40,8,' Importe ',1,0,'L');
  	$pdf->Cell(30,8,'    Factura ',1,0,'L');
	$pdf->Cell(30,8,' Fecha ',1,0,'L');
	$pdf->Cell(40,8,' Importe ',1,0,'L');
  	$pdf->SetY(33);
  	$pdf->Cell(115);
  	$pdf->SetFont('Arial','B',8);  
  	$valor_y = 33;
  
	$i = 60;
	$j = 100;
	$cant_reng  = 0;

	$tipoenti=1;
	
	// Leo los clientes
	$query_entidades = sprintf("SELECT * FROM entidades WHERE  tipoent = %s ORDER BY tipoent, codnum", $tipoenti);
	$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));

	$ni_idea = 0.0;
	$cant_reng = 0;
	$cant_cli = 0;
	$cant_cabfac = 0;
	$cant_cabrecibos = 0;
	$cant_cartvalores = 0;
	$total_facturas = 0.0;
	$total_recibos = 0.0;
	$total_cli = 0.0;
	$total = 0.0;
	$tot_gral_chq_terceros = 0.0;
	$tot_gral_depositos    = 0.0;
	$tot_gral_efvo         = 0.0; 
	$tot_gral_dolares      = 0.0;
	$tot_gral_retiva       = 0.0;
	$tot_gral_retibrutos   = 0.0;
	$tot_gral_retganan     = 0.0; 
	$tot_gral_retsuss      = 0.0;
	$tot_gral_retenciones  = 0.0;
    $tot_gral_otrosmedios  = 0.0;
	$tot_gral_saldoafav    = 0.0;
	$tot_gral_devsaldoafav = 0.0;
	while($row_entidades = mysqli_fetch_array($enti)) {
			$cant_cli++;
			$cliente      = $row_entidades["codnum"];
			$razsoc       = $row_entidades["razsoc"];

		
			// PRIMERO LEO LOS RECIBOS POR CLIENTE
			$query_cabrecibos = sprintf("SELECT * FROM cabrecibo WHERE  cliente = $cliente AND fecha BETWEEN %s AND %s ORDER BY cliente, fecha", $fecha_desde, $fecha_hasta);
			$cabrecibos = mysqli_query($amercado, $query_cabrecibos) or die(mysqli_error($amercado));  
			
			$totalRows_cabrecibos = mysqli_num_rows($cabrecibos);
			if ($totalRows_cabrecibos == 0)
					continue;
	
			$tot_chq_terceros = 0.0;
			$tot_depositos    = 0.0;
			$tot_efvo         = 0.0; 
			$tot_dolares      = 0.0;
			$tot_retiva       = 0.0;
			$tot_retibrutos   = 0.0;
			$tot_retganan     = 0.0; 
			$tot_retsuss      = 0.0;
            $tot_otrosmedios  = 0.0;
			$tot_saldoafav    = 0.0;
			$tot_devsaldoafav = 0.0;
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
	
            //$valor_y += 6;
            $pdf->SetY($valor_y);
            $pdf->Cell(10);
            $pdf->Cell(60,8,substr($razsoc,0,35),0,0,'L');
            $valor_y += 6;
            $cant_reng +=1;
	
			while($row_cabrecibos = mysqli_fetch_array($cabrecibos))	{	
		
	
					$cant_cabrecibos++;
	
					$tcomprec      = $row_cabrecibos["tcomp"];
					$serierec      = $row_cabrecibos["serie"];
					$ncomprec      = $row_cabrecibos["ncomp"];
					$totbrutorec   = $row_cabrecibos["imptot"];
					
					//======================================================================
					if ($cant_reng > 34) {
						$cant_reng  = 0;
						$pdf->AddPage();
						$pdf->SetMargins(0.5, 0.5 , 0.5);
						$pdf->SetFont('Arial','B',11);
						$pdf->SetY(5);
						$pdf->Cell(10);
						$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
						$pdf->Cell(120);
						$pagina = $pdf->PageNo();
						$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
						$pdf->SetY(10);
						$pdf->Cell(150);
						$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
						$pdf->SetY(18);
						$pdf->Cell(30);
						$pdf->Cell(20,6,' Acumulado por Medio de Pago de los Recibos ',0,0,'L');
						$pdf->SetFont('Arial','B',9);
						$pdf->SetY(25);
						$pdf->Cell(10);
						$pdf->Cell(30,8,' Recibo ',1,0,'L');
						$pdf->Cell(40,8,' Importe ',1,0,'L');
						$pdf->Cell(30,8,'    Factura ',1,0,'L');
						$pdf->Cell(30,8,' Fecha ',1,0,'L');
						$pdf->Cell(40,8,' Importe ',1,0,'L');
						$pdf->SetY(33);
						$pdf->Cell(115);
						$pdf->SetFont('Arial','B',8);  
						$valor_y = 33;

					}

					//======================================================================
	
					$pdf->SetY($valor_y);
			  		$pdf->Cell(10);
			  		$pdf->Cell(30,8,$ncomprec,0,0,'L');
					$tbrutorec = number_format($totbrutorec, 2, ',','.');
					$pdf->Cell(40,8,$tbrutorec,0,0,'R');
					$total_recibos += $totbrutorec;
					//$valor_y += 6;
					//$cant_reng +=1;	
					//LEO DETRECIBO PARA VER LAS FACTURAS RELACIONADAS
					$query_detrecibos = sprintf("SELECT * FROM detrecibo WHERE  tcomp = $tcomprec AND ncomp = $ncomprec");
					$detrecibos = mysqli_query($amercado, $query_detrecibos) or die(mysqli_error($amercado));
					while($row_detrecibos = mysqli_fetch_array($detrecibos)) {
						$nrodocfac = $row_detrecibos["nrodoc"];
						$tcomprel  = $row_detrecibos["tcomprel"];
						$ncomprel  = $row_detrecibos["ncomprel"];
					
						//LEO LA FACTURA PARA SABER EL BRUTO
						$query_cabfac2 = sprintf("SELECT * FROM cabfac WHERE tcomp = '$tcomprel' AND ncomp = '$ncomprel' AND nrodoc = '$nrodocfac' ");
						$cabecerafac2 = mysqli_query($amercado, $query_cabfac2) or die("ERROR LEYENDO LA FACTURA RELACIONADA AL RECIBO"); 
						$row_cabecerafac2 = mysqli_fetch_assoc($cabecerafac2);
						$importe_factura = $row_cabecerafac2["totbruto"];
						$fecha_factura = $row_cabecerafac2["fecreg"];
						$fechafac =  substr($fecha_factura,8,2)."-".substr($fecha_factura,5,2)."-".substr($fecha_factura,0,4);
						if ($cant_reng > 34) {
							$cant_reng  = 0;
							$pdf->AddPage();
							$pdf->SetMargins(0.5, 0.5 , 0.5);
							$pdf->SetFont('Arial','B',11);
							$pdf->SetY(5);
							$pdf->Cell(10);
							$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
							$pdf->Cell(120);
							$pagina = $pdf->PageNo();
							$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
							$pdf->SetY(10);
							$pdf->Cell(150);
							$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
							$pdf->SetY(18);
							$pdf->Cell(30);
							$pdf->Cell(20,6,' Acumulado por Medio de Pago de los Recibos ',0,0,'L');
							$pdf->SetFont('Arial','B',9);
							$pdf->SetY(25);
							$pdf->Cell(10);
							$pdf->Cell(30,8,' Recibo ',1,0,'L');
							$pdf->Cell(40,8,' Importe ',1,0,'L');
							$pdf->Cell(30,8,'    Factura ',1,0,'L');
							$pdf->Cell(30,8,' Fecha ',1,0,'L');
							$pdf->Cell(40,8,' Importe ',1,0,'L');
							$pdf->SetY(33);
							$pdf->Cell(115);
							$pdf->SetFont('Arial','B',8);  
							$valor_y = 33;

						}
						
						$pdf->SetY($valor_y);
						$pdf->Cell(80);
						$total_facturas += $importe_factura;
						$imp_factura = number_format($importe_factura, 2, ',','.');
						
						$pdf->Cell(30,8,$nrodocfac,0,0,'C');
						$pdf->Cell(30,8,$fechafac,0,0,'C');
						$pdf->Cell(40,8,$imp_factura,0,0,'R');
						$valor_y += 6;
						$cant_reng +=1;	
					}
					// LEO CARTVALORES PARA EL RECIBO RELACIONADO

					$query_medios = "SELECT * FROM cartvalores WHERE tcomprel = $tcomprec AND serierel = $serierec AND ncomprel = $ncomprec";
					$medios = mysqli_query($amercado, $query_medios) or die(mysqli_error($amercado));
			
					//$totalRows_medios = mysqli_num_rows($medios);

					while($row_medios = mysqli_fetch_array($medios))	{

							$tcomp_medpag      = $row_medios["tcomp"];
							$serie_medpag      = $row_medios["serie"];
							$ncomp_medpag      = $row_medios["ncomp"];
			
							$cant_cartvalores++;

							switch($tcomp_medpag) {
								case 8:
									$tot_chq_terceros += $row_medios["importe"];
									$tot_gral_chq_terceros += $row_medios["importe"];
									break;
								case 9:
									$tot_depositos    += $row_medios["importe"];
									$tot_gral_depositos    += $row_medios["importe"];
									break;
								case 12:
									$tot_efvo         += $row_medios["importe"];
									$tot_gral_efvo         += $row_medios["importe"];
									break;
								case 13:
									$tot_dolares      += $row_medios["importe"];
									$tot_gral_dolares      += $row_medios["importe"];
									break;
								case 40:
								case 68:
									$tot_retiva       += $row_medios["importe"];
									$tot_gral_retiva       += $row_medios["importe"];
									$tot_gral_retenciones +=  $row_medios["importe"];
									break;
								case 41:
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
                                case 96:
									$tot_retibrutos   += $row_medios["importe"];
									$tot_gral_retibrutos   += $row_medios["importe"];
									$tot_gral_retenciones +=  $row_medios["importe"];
									break;
								case 42:
								case 66:
									$tot_retganan     += $row_medios["importe"];
									$tot_gral_retganan     += $row_medios["importe"];
									$tot_gral_retenciones +=  $row_medios["importe"];
									break;
								case 43:
								case 67:
									$tot_retsuss      += $row_medios["importe"];
									$tot_gral_retsuss      += $row_medios["importe"];
									$tot_gral_retenciones +=  $row_medios["importe"];
									break;
								case 98:
									$tot_saldoafav -= $row_medios["importe"];
									$tot_gral_saldoafav -= $row_medios["importe"];
									break;
								case 91:
									$tot_devsaldoafav += $row_medios["importe"];
									$tot_gral_devsaldoafav += $row_medios["importe"];
									break;
								default:
									$tot_otrosmedios += $row_medios["importe"];
                                    $tot_gral_otrosmedios += $row_medios["importe"];
									break;
			
							}
					}

			}
	/*
			if ($cant_reng > 34) {
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
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,' Acumulado por Medio de Pago de los Recibos ',0,0,'L');
					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_chq_terceros != 0.0 || $tot_depositos != 0.0 || $tot_efvo != 0.0 ||
		    		 $tot_dolares != 0.0 || $tot_retiva != 0.0 || $tot_retibrutos != 0.0 ||
		 		     $tot_retganan != 0.0 || $tot_retsuss != 0.0 || $tot_otrosmedios != 0.0) {

					$valor_y += 6;
					$pdf->SetY($valor_y);
		  			$pdf->Cell(10);
					$pdf->Cell(60,8,substr($razsoc,0,35),0,0,'L');
					$valor_y += 6;
					$cant_reng +=1;
			}
            */
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;

			}
			if ($tot_chq_terceros != 0.0) {
					$cant_reng +=1; 
					$pdf->SetY($valor_y);
		  			$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Cheques     : ',0,0,'L');
					$total_cli += $tot_chq_terceros;
					$total     += $tot_chq_terceros;
					$tot_chq_terceros   = number_format($tot_chq_terceros, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_chq_terceros,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_chq_terceros = 0;
			}

			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_depositos != 0.0) { 
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,utf8_decode(' Total Depósitos   : '),0,0,'L');
					$total_cli += $tot_depositos;
					$total     += $tot_depositos;
					$tot_depositos      = number_format($tot_depositos, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_depositos,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_depositos = 0;
			}	
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_efvo != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Efectivo    : ',0,0,'L');
					$total_cli += $tot_efvo;
					$total     += $tot_efvo;
					$tot_efvo  = number_format($tot_efvo, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_efvo,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_efvo = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_dolares != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,utf8_decode(' Total Dólares     : '),0,0,'L');
					$total_cli += $tot_dolares;
					$total     += $tot_dolares;
					$tot_dolares        = number_format($tot_dolares, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_dolares,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_dolares = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_retiva != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Ret. IVA    : ',0,0,'L');
					$total_cli += $tot_retiva;
					$total     += $tot_retiva;
					$tot_retiva         = number_format($tot_retiva, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_retiva,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_retiva = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;

			}
			if ($tot_retibrutos != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Ret. IIBB   : ',0,0,'L');
					$total_cli += $tot_retibrutos;
					$total     += $tot_retibrutos;
					$tot_retibrutos     = number_format($tot_retibrutos, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_retibrutos,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_retibrutos = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_retganan != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Ret. Ganan. : ',0,0,'L');
					$total_cli += $tot_retganan;
					$total     += $tot_retganan;
					$tot_retganan       = number_format($tot_retganan, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_retganan,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_retganan = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_retsuss != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Ret. SUSS   : ',0,0,'L');
					$total_cli += $tot_retsuss;
					$total     += $tot_retsuss;
					$tot_retsuss        = number_format($tot_retsuss, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_retsuss,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_retsuss = 0;	
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_saldoafav != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Saldo a favor   : ',0,0,'L');
					$total_cli -= $tot_saldoafav;
					$total     -= $tot_saldoafav;
					$tot_saldoafav        = number_format($tot_saldoafav, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_saldoafav,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_saldoafav = 0;	
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_devsaldoafav != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Saldo a favor   : ',0,0,'L');
					$total_cli += $tot_devsaldoafav;
					$total     += $tot_devsaldoafav;
					$tot_devsaldoafav        = number_format($tot_devsaldoafav, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_devsaldoafav,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_devsaldoafav = 0;	
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
		    if ($tot_otrosmedios != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Otros Medios   : ',0,0,'L');
					$total_cli += $tot_otrosmedios;
					$total     += $tot_otrosmedios;
					$tot_otrosmedios        = number_format($tot_otrosmedios, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_otrosmedios,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_otrosmedios = 0;	
			}
			
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,' Acumulado por Medio de Pago de los Recibos ',0,0,'L');
					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;

			}
			if ($tot_chq_terceros != 0.0) {
					$cant_reng +=1; 
					$pdf->SetY($valor_y);
		  			$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Cheques     : ',0,0,'L');
					$total_cli += $tot_chq_terceros;
					$total     += $tot_chq_terceros;
					$tot_chq_terceros   = number_format($tot_chq_terceros, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_chq_terceros,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_chq_terceros = 0;
			}

			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_depositos != 0.0) { 
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,utf8_decode(' Total Depósitos   : '),0,0,'L');
					$total_cli += $tot_depositos;
					$total     += $tot_depositos;
					$tot_depositos      = number_format($tot_depositos, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_depositos,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_depositos = 0;
			}	
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_efvo != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Efectivo    : ',0,0,'L');
					$total_cli += $tot_efvo;
					$total     += $tot_efvo;
					$tot_efvo           = number_format($tot_efvo, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_efvo,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_efvo = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_dolares != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,utf8_decode(' Total Dólares     : '),0,0,'L');
					$total_cli += $tot_dolares;
					$total     += $tot_dolares;
					$tot_dolares        = number_format($tot_dolares, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_dolares,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_dolares = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_retiva != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Ret. IVA    : ',0,0,'L');
					$total_cli += $tot_retiva;
					$total     += $tot_retiva;
					$tot_retiva         = number_format($tot_retiva, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_retiva,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_retiva = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;

			}
			if ($tot_retibrutos != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Ret. IIBB   : ',0,0,'L');
					$total_cli += $tot_retibrutos;
					$total     += $tot_retibrutos;
					$tot_retibrutos     = number_format($tot_retibrutos, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_retibrutos,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_retibrutos = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_retganan != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Ret. Ganan. : ',0,0,'L');
					$total_cli += $tot_retganan;
					$total     += $tot_retganan;
					$tot_retganan       = number_format($tot_retganan, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_retganan,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_retganan = 0;
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_retsuss != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Ret. SUSS   : ',0,0,'L');
					$total_cli += $tot_retsuss;
					$total     += $tot_retsuss;
					$tot_retsuss        = number_format($tot_retsuss, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_retsuss,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_retsuss = 0;	
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
		
			if ($tot_saldoafav != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Saldo a favor   : ',0,0,'L');
					$total_cli -= $tot_saldoafav;
					$total     -= $tot_saldoafav;
					$tot_saldoafav        = number_format($tot_saldoafav, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_saldoafav,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_saldoafav = 0;	
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			if ($tot_devsaldoafav != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Saldo a favor   : ',0,0,'L');
					$total_cli += $tot_devsaldoafav;
					$total     += $tot_devsaldoafav;
					$tot_devsaldoafav        = number_format($tot_devsaldoafav, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_devsaldoafav,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_devsaldoafav = 0;	
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}

            if ($tot_otrosmedios != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Otros Medios  : ',0,0,'L');
					$total_cli += $tot_otrosmedios;
					$total     += $tot_otrosmedios;
					$tot_otrosmedios        = number_format($tot_otrosmedios, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_otrosmedios,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_otrosmedios = 0;	
			}
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
	
			}
			//=================================================================================
			if ($total_cli == 0)
				continue;
			$pdf->SetY($valor_y);
			$pdf->Cell(10);
		  	$pdf->Cell(30,8,'Total Cliente ( '.$cliente.' ):',0,0,'L');
			$total_cli        = number_format($total_cli, 2, ',','.');
			$pdf->Cell(25,8,$total_cli,0,0,'R');
			$cant_reng +=1;
			if ($cant_reng > 34) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(18);
					$pdf->Cell(30);
					$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
		  		 	$pdf->Cell(30,8,' Recibo ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->Cell(30,8,'    Factura ',1,0,'L');
					$pdf->Cell(30,8,' Fecha ',1,0,'L');
					$pdf->Cell(40,8,' Importe ',1,0,'L');
					$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 33;
			}
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(10);
			$pdf->Cell(160,8,'======================================================================================================',0,0,'L');	
			$valor_y = $valor_y + 6;
			$total_cli = 0;
			$cant_reng +=1;

	}

			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
			$pdf->Cell(120);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetY(18);
			$pdf->Cell(30);
			$pdf->Cell(20,6,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

			$pdf->SetFont('Arial','B',9);
	  		$pdf->SetY(25);
	  		$pdf->Cell(10);

			$valor_y = 33;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$total_facturas   = number_format($total_facturas, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL FACTURAS: ',0,0,'R');
			$pdf->Cell(35,8,$total_facturas,0,0,'R');
			// =============================================================
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$total_recibos   = number_format($total_recibos, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL RECIBOS: ',0,0,'R');
			$pdf->Cell(35,8,$total_recibos,0,0,'R');
			// =============================================================
			// ACUMULO LOS MEDIOS DE PAGO
			$tot_gral_medpag = 0.0;
			$tot_gral_medpag = $tot_gral_chq_terceros + $tot_gral_efvo + $tot_gral_retenciones + $tot_gral_depositos + $tot_gral_dolares + $tot_gral_otrosmedios;
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_gral_chq_terceros   = number_format($tot_gral_chq_terceros, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL CHEQUES: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_chq_terceros,0,0,'R');
			
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_gral_depositos   = number_format($tot_gral_depositos, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL DEPOSITOS: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_depositos,0,0,'R');
			
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_gral_efvo   = number_format($tot_gral_efvo, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL EFECTIVO: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_efvo,0,0,'R');
			
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_gral_dolares   = number_format($tot_gral_dolares, 2, ',','.');
			$ni_idea   = number_format($ni_idea, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL DOLARES: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_dolares,0,0,'R');		
			//$pdf->Cell(35,8,$ni_idea,0,0,'R');		

			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			//$tot_gral_retenciones = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss;
			$tot_gral_retenciones   = number_format($tot_gral_retenciones, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL RETENCIONES: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_retenciones,0,0,'R');

			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			//$tot_gral_retenciones = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss;
			$tot_gral_saldoafav   = number_format($tot_gral_saldoafav, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL SALDO A FAVOR: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_saldoafav,0,0,'R');

            $valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			//$tot_gral_retenciones = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss;
			$tot_gral_devsaldoafav   = number_format($tot_gral_devsaldoafav, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL DEV SALDO A FAVOR: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_devsaldoafav,0,0,'R');
            
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			//$tot_gral_retenciones = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss;
			$tot_gral_otrosmedios   = number_format($tot_gral_otrosmedios, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL OTROS MEDIOS: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_otrosmedios,0,0,'R');

			$cant_reng = 0;
			
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			//$tot_gral_retenciones = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss;
			$tot_gral_medpag   = number_format($tot_gral_medpag, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL MEDIOS DE PAGO: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_medpag,0,0,'R');
			$cant_reng = 0;
	
		


mysqli_close($amercado);

$pdf->Output();
?>  
