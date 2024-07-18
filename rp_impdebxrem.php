<?php
ob_start();
set_time_limit(0); // Para evitar el timeout3
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');
define('COD_IMP_DEB','9');
mysqli_select_db($amercado, $database_amercado);
// Leo los parámetros del formulario anterior
$fechahoy = date("d-m-Y");
$codremate = $_POST['remate_num'];

//SACO EL PORCENTAJE DE LA TABLA IMPUESTOS
$query_impdeb = sprintf("SELECT * FROM impuestos WHERE  codnum = %s ",COD_IMP_DEB);
$impdeb = mysqli_query($amercado, $query_impdeb) or die("NO PUEDO LEER EL IMPUESTO"); 
$row_impdeb = mysqli_fetch_assoc($impdeb);
$impchq = $row_impdeb["porcen"];
//Primero leo la cantidad de remates que se generaron a partir del original
$query_remasoc = "SELECT * FROM REMATES WHERE ncomp = $codremate";


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
$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
$pdf->SetY(10);
$pdf->Cell(150);
$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
$pdf->SetY(15);
$pdf->Cell(35);
$pdf->Cell(20,10,utf8_decode('  Impuesto a los depósitos bancarios de la Subasta: ').$codremate,0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(10);
$pdf->Cell(32,16,' Facturas ',1,0,'C');
$pdf->Cell(32,16,' Importe ',1,0,'C');
$pdf->Cell(32,16,' Recibos ',1,0,'C');
$pdf->Cell(32,16,utf8_decode(' Depósitos a 3ros.'),1,0,'C');
$pdf->Cell(32,16,' Retenciones ',1,0,'C');
$pdf->Cell(32,16,' Impuesto ',1,0,'C');
$pdf->SetY(33);
$pdf->Cell(115);
$pdf->SetFont('Arial','B',8);  
$valor_y = 45;
$cant_reng  = 0;
$total_remate = 0.00;
$nrodoc_rc = "";
$consol_gral_retenciones = 0.00;
$consol_gral_terceros    = 0.00;
$consol_gral_remate_imp  = 0.00;
$consol_total_remate     = 0.00;
$tot_efe = 0.00;
	
	$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = $codremate");
	$result_remate = mysqli_query($amercado, $query_remate) or die("NO PUEDO LEER REMATES");  
	$tot_gral_retenciones  = 0.0;
	$tot_gral_terceros  = 0.0;
	$tot_gral_rem = 0.00;	
	$tot_gral_imp = 0.00;
    $tot_gral_efe = 0.00;
    $rec_ant = 0;
	while($row_remate = mysqli_fetch_array($result_remate)) {
		//$total_remate = 0.00;
		$total_retenciones = 0.00;
		$total_dep_terceros = 0.00;
		$remate = $row_remate["ncomp"];
		//ACA LEO LAS FACTURAS, NCRED, NDEB RELACIONADAS CON EL REMATE,POR LOTES en_liquid=1
		$query_cabfac = sprintf("SELECT * FROM cabfac WHERE  codrem = $remate AND (((tcomp = 115 OR tcomp = 116 OR tcomp = 117 OR tcomp = 103 OR tcomp = 142) AND estado = 'S') OR (tcomp IN (119,120,121, 122,123,124,135, 137,138,144) AND en_liquid = 1)) ORDER BY tcomp,ncomp");
		$cabfac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));
		while($row_cabfac = mysqli_fetch_array($cabfac)) {
			$tcomp     = $row_cabfac["tcomp"];
			$ncomp_fc  = $row_cabfac["ncomp"];
			$estado    = $row_cabfac["estado"];
			$nrodoc_fc = $row_cabfac["nrodoc"];
			if ($estado == "P" || $estado == "C") 
				continue;
			switch ($tcomp) {
				case 115:
				case 116:
				case 117:
				case 122:
				case 123:
                case 124:
				case 138:
				case 142:
					$tot_cbte = ($row_cabfac["totneto21"] * 1.21) + ($row_cabfac["totneto105"] + $row_cabfac["totiva105"]);
					break;	
				case 103:
					$tot_cbte = ($row_cabfac["totneto21"] + $row_cabfac["totiva21"]) + ($row_cabfac["totneto105"] + $row_cabfac["totiva105"]);
					break;				
				case 119:
				case 120:
                case 121:
				case 135:
				case 144:
				case 137:
               
					$tot_cbte = (($row_cabfac["totneto21"]*1.21) + ($row_cabfac["totneto105"]*1.105)) * -1;
					break;
			}
			// LEO EL RECIBO DE LA FACTURA
			$tcomprel_cabfac = $row_cabfac["tcomp"];
			$serierel_cabfac = $row_cabfac["serie"];
			$ncomprel_cabfac = $row_cabfac["ncomp"];
			$totbruto_cabfac = $row_cabfac["totbruto"];
			$query_detrecibo = sprintf("SELECT * FROM detrecibo WHERE  tcomprel = $tcomprel_cabfac AND serierel = $serierel_cabfac AND ncomprel = $ncomprel_cabfac ");
			$detrecibo = mysqli_query($amercado, $query_detrecibo) or die("NO PUEDO LEER EL RECIBO"); 
			$row_detrecibo = mysqli_fetch_assoc($detrecibo);
			$ncomp_recibo = $row_detrecibo["ncomp"];
            
			// LEO CARTVALORES PARA ESE RECIBO
			if ($ncomp_recibo != null) {
                    //continue;
				if ($ncomp_recibo < 10)
                	$nrodoc_rc = "X0001-0000000".$ncomp_recibo;
				if ($ncomp_recibo < 100 && $ncomp_recibo > 9)
					$nrodoc_rc = "X0001-000000".$ncomp_recibo;
				if ($ncomp_recibo < 1000 && $ncomp_recibo > 99)
					$nrodoc_rc = "X0001-00000".$ncomp_recibo;
				if ($ncomp_recibo < 10000 && $ncomp_recibo > 999)
					$nrodoc_rc = "X0001-0000".$ncomp_recibo;
                $query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE  ncomprel = $ncomp_recibo ");
                $cartvalores = mysqli_query($amercado, $query_cartvalores) or die("NO PUEDO LEER CARTVALORES"); 
                $tot_efe = 0;
                $totalRows_cartvalores = mysqli_num_rows($cartvalores);
                $tcomp_medpag_ant = 0;
                while($row_medios = mysqli_fetch_array($cartvalores))	{
                    $tcomp_medpag      = $row_medios["tcomp"];
                    $serie_medpag      = $row_medios["serie"];
                    $ncomp_medpag      = $row_medios["ncomp"];
                    switch($tcomp_medpag) {
                        case 8:
                        case 9:
                        case 39:
                            break;
                        case 12:
                            //$tot_efe += $row_medios["importe"];
                            break;
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
						case 106:
                            if ($rec_ant != $ncomp_recibo) {
                                $total_retenciones       += $row_medios["importe"];
                                $tot_gral_retenciones  	 += $row_medios["importe"];
                            }
                            break;
                        case 95:
                        case 100:
                            $total_dep_terceros      += $row_medios["importe"];
                            $tot_gral_terceros  	 += $row_medios["importe"];
                            break;
                    }
                    $tcomp_medpag_ant = $tcomp_medpag;
                }
                
            }
            $rec_ant = $ncomp_recibo;
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
                $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
                $pdf->SetY(10);
                $pdf->Cell(150);
                $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
                $pdf->SetY(15);
                $pdf->Cell(35);
                $pdf->Cell(20,10,utf8_decode('  Impuesto a los depósitos bancarios de la Subasta: ').$codremate,0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->SetY(25);
                $pdf->Cell(10);
                $pdf->Cell(32,16,' Facturas ',1,0,'C');
                $pdf->Cell(32,16,' Importe ',1,0,'C');
                $pdf->Cell(32,16,' Recibos ',1,0,'C');
                $pdf->Cell(32,16,utf8_decode(' Depósitos a 3ros.'),1,0,'C');
                $pdf->Cell(32,16,' Retenciones ',1,0,'C');
                $pdf->Cell(32,16,' Impuesto ',1,0,'C');
                $pdf->SetY(33);
                $pdf->Cell(115);
                $pdf->SetFont('Arial','B',8);  
                $valor_y = 45;
            }
            // Imprimo los comprobantes involucrados.
            $cant_reng +=1; 
            $pdf->SetY($valor_y);
            $pdf->Cell(5);
            $tot_cbte_fc = $tot_cbte;
            $total_retenciones_fc = $total_retenciones;
            $total_dep_terceros_fc = $total_dep_terceros;
            $tot_cbte_fc   = number_format($tot_cbte_fc, 2, ',','.');
            $total_retenciones_fc   = number_format($total_retenciones_fc, 2, ',','.');
            $total_dep_terceros_fc   = number_format($total_dep_terceros_fc, 2, ',','.');
            $pdf->Cell(32,8,$nrodoc_fc ,0,0,'R');
            $pdf->Cell(32,8,$tot_cbte_fc ,0,0,'R');
            $pdf->Cell(32,8,$nrodoc_rc ,0,0,'R');
            $pdf->Cell(32,8,$total_dep_terceros_fc ,0,0,'R');
            $pdf->Cell(32,8,$total_retenciones_fc ,0,0,'R');
            $valor_y = $valor_y + 6;	
            $total_remate += $tot_cbte;
            $tot_gral_rem += $tot_cbte;
            $tot_gral_efe += $tot_efe;
            $tot_cbte = 0.00;
            $total_retenciones  = 0.00;
            $total_dep_terceros = 0.00;
            $tot_efe            = 0.00;
			$nrodoc_rc          = "";
        }
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
			$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetY(15);
			$pdf->Cell(35);
			$pdf->Cell(20,10,utf8_decode('  Impuesto a los depósitos bancarios de la Subasta: ').$codremate,0,0,'L');
            $pdf->SetFont('Arial','B',9);
            $pdf->SetY(25);
            $pdf->Cell(10);
            $pdf->Cell(32,16,' Facturas ',1,0,'C');
            $pdf->Cell(32,16,' Importe ',1,0,'C'); 
            $pdf->Cell(32,16,' Recibos ',1,0,'C');
            $pdf->Cell(32,16,utf8_decode(' Depósitos a 3ros.'),1,0,'C');
            $pdf->Cell(32,16,' Retenciones ',1,0,'C');
            $pdf->Cell(32,16,' Impuesto ',1,0,'C');
            $pdf->SetY(33);
            $pdf->Cell(115);
            $pdf->SetFont('Arial','B',8);  
			$valor_y = 45;
        }
        if ($total_remate != 0.0) {
			$cant_reng +=1; 
			$pdf->SetY($valor_y);
			$pdf->Cell(15);
			$pdf->Cell(160,8,'=========================================================================================================',0,0,'L');	
			$valor_y = $valor_y + 6;

			$cant_reng +=1;
			$pdf->SetY($valor_y);
			$pdf->Cell(17);
			$pdf->Cell(25,8," Subasta Nro. ".$remate."  ",0,0,'R');
			$tot_remate_imp   = ($total_remate - $tot_gral_retenciones - $tot_gral_terceros) * $impchq;
			$tot_gral_imp += $tot_remate_imp;
            $consol_gral_retenciones += $tot_gral_retenciones;
            $consol_gral_terceros    += $tot_gral_terceros;
            $consol_gral_remate_imp  += $tot_remate_imp;
            $consol_total_remate     += $total_remate;
			$tot_gral_retenciones   = number_format($tot_gral_retenciones, 2, ',','.');
			$tot_gral_terceros      = number_format($tot_gral_terceros, 2, ',','.');
			$tot_remate_imp         = number_format($tot_remate_imp, 2, ',','.');
			$total_remate           = number_format($total_remate, 2, ',','.');
			$pdf->Cell(35,8,$total_remate,0,0,'R');
			$pdf->Cell(35,8,$tot_gral_terceros,0,0,'R');
    		$pdf->Cell(35,8,$tot_gral_retenciones,0,0,'R');
			$pdf->Cell(35,8,$tot_remate_imp,0,0,'R');
			$valor_y = $valor_y + 6;
            $cant_reng +=1; 
			$pdf->SetY($valor_y);
			$pdf->Cell(15);
			$pdf->Cell(160,8,'=========================================================================================================',0,0,'L');	
			$valor_y = $valor_y + 6;
        }
        $total_remate = 0.00;
        $tot_remate_imp = 0.00;
    }

$valor_y = $valor_y + 6;
$cant_reng +=1;
$pdf->SetY($valor_y);
$pdf->Cell(17);
$pdf->Cell(25,8," ID Consolidado ".$codremate."  ",0,0,'R');
$consol_gral_retenciones   = number_format($consol_gral_retenciones, 2, ',','.');
$consol_gral_terceros      = number_format($consol_gral_terceros, 2, ',','.');
$consol_gral_remate_imp    = number_format($consol_gral_remate_imp, 2, ',','.');
$consol_total_remate       = number_format($consol_total_remate, 2, ',','.');
$pdf->Cell(35,8,$consol_total_remate,0,0,'R');
$pdf->Cell(35,8,$consol_gral_terceros,0,0,'R');
$pdf->Cell(35,8,$consol_gral_retenciones,0,0,'R');
$pdf->Cell(35,8,$consol_gral_remate_imp,0,0,'R');
$valor_y = $valor_y + 6;
$cant_reng +=1; 
$pdf->SetY($valor_y);
$pdf->Cell(15);
$pdf->Cell(160,8,'=========================================================================================================',0,0,'L');	
$valor_y = $valor_y + 6;
mysqli_close($amercado);
ob_end_clean();
$pdf->Output();
?>