<?php
	define('FPDF_FONTPATH','fpdf17/font/');
	set_time_limit(0); // Para evitar el timeout

	require('fpdf17/fpdf.php');
	require('numaletras.php');
	//Conecto con la  base de datos
	require_once('Connections/amercado.php');

	mysqli_select_db($amercado, $database_amercado);

	// Leo los parametros del formulario anterior
	$f_desde = $_POST['fecha_desde'];
	$f_hasta = $_POST['fecha_hasta'];

	$fecha_desde = "'".substr($f_desde,6,4)."-".substr($f_desde,3,2)."-".substr($f_desde,0,2)."'";
	$fecha_hasta = "'".substr($f_hasta,6,4)."-".substr($f_hasta,3,2)."-".substr($f_hasta,0,2)."'";
	
	$fechahoy = date("d-m-Y");
	// Leo los renglones


	// Leo la tabla cartvalores para los cbtes de retenciones


	$query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE fechapago BETWEEN $fecha_desde AND $fecha_hasta AND ((tcomp BETWEEN 66 AND 85) OR (tcomp = 90) OR (tcomp = 96) OR (tcomp = 106)) ORDER BY tcomp,fechapago");
	$cartvalores = mysqli_query($amercado, $query_cartvalores) or die("ERROR LEYENDO CARTVALORES");
	$renglon = 0;


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
	$pdf->Cell(30,10,utf8_decode('Página : ').$pagina.' ',0,0,'L');
	$pdf->SetY(10);
	$pdf->Cell(150);
	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
	$pdf->SetX(35);
	$pdf->SetY(20);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(195,10,'Detalle de Retenciones (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');

 
  

  	$pdf->SetY(30);
  	$pdf->Cell(10);
  	$pdf->Cell(20,8,'Fecha',0,0,'C');
    $pdf->Cell(34,8,'Comprobante ',0,0,'C');
    $pdf->Cell(30,8,'CUIT ',0,0,'C');
    $pdf->Cell(50,8,'Cliente ',0,0,'C');
    $pdf->Cell(35,8,'Importe ',0,0,'C');
    $pdf->Cell(25,8,'Remate ',0,0,'C');
    $pdf->SetY(36);
    $pdf->Cell(10);
    $pdf->Line(10, 36, 198, 36);
  
    
  	$valor_y = 40;
  
	$i = 60;
	$j = 100;
	$total = 0;
	$subtotal_ant = 0;
	$tcomp_ant = 0;
	$pdf->SetFont('Arial','',9);
	$tot_66 = 0.00; $tot_67 = 0.00; $tot_68 = 0.00; $tot_69 = 0.00; $tot_70 = 0.00;
	$tot_71 = 0.00; $tot_72 = 0.00; $tot_73 = 0.00; $tot_74 = 0.00; $tot_75 = 0.00;
	$tot_76 = 0.00; $tot_77 = 0.00; $tot_78 = 0.00; $tot_79 = 0.00; $tot_80 = 0.00;
	$tot_81 = 0.00; $tot_82 = 0.00; $tot_83 = 0.00; $tot_84 = 0.00; $tot_85 = 0.00;
	$tot_90 = 0.00; $tot_96 = 0.00; $tot_106 = 0.00;
	while($row_cartvalores = mysqli_fetch_array($cartvalores)) {	
		$tcomp      = $row_cartvalores["tcomp"];
        $ncomprel   = $row_cartvalores["ncomprel"]; // Nro de recibo
        $query_rec = sprintf("SELECT * FROM detrecibo WHERE  ncomp = %s", $ncomprel);
  		$rec = mysqli_query($amercado, $query_rec) or die("ERROR LEYENDO DETRECIBO  ".$ncomprel);
  		$row_rec = mysqli_fetch_assoc($rec);
        $tfactura   = $row_rec["tcomprel"];
  		$factura   = $row_rec["ncomprel"];
        $query_fc = sprintf("SELECT * FROM cabfac WHERE  tcomp = %s and ncomp = %s", $tfactura, $factura);
  		$fc = mysqli_query($amercado, $query_fc) or die("ERROR LEYENDO CABFAC ".$tfactura."  ".$factura."REC NRO ".$ncomprel);
  		$row_fc = mysqli_fetch_assoc($fc);
  		$remate   = $row_fc["codrem"];
        
		if ($renglon > 24){
			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
			$pdf->Cell(120);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,utf8_decode('Página : ').$pagina.' ',0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetX(35);
			$pdf->SetY(20);
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(195,10,'Detalle de Retenciones (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');




			$pdf->SetY(30);
			$pdf->Cell(10);
			$pdf->Cell(20,8,'Fecha',0,0,'C');
			$pdf->Cell(34,8,'Comprobante ',0,0,'C');
			$pdf->Cell(30,8,'CUIT ',0,0,'C');
			$pdf->Cell(50,8,'Cliente ',0,0,'C');
			$pdf->Cell(35,8,'Importe ',0,0,'C');
            $pdf->Cell(25,8,'Remate ',0,0,'C');
			$pdf->SetY(36);
			$pdf->Cell(10);
			$pdf->Line(10, 36, 198, 36);

			$renglon = 0;
			$valor_y = 40;


		}
		if ($tcomp_ant == 0 || $tcomp_ant != $tcomp) {
			//Imprimo el subtitulo
			// Leo en tipcomp el tipo de cbte de AFIP
			$query_tipcomp = sprintf("SELECT * FROM tipcomp WHERE codnum = $tcomp");
			$tipcomp = mysqli_query($amercado, $query_tipcomp) or die("ERROR LEYENDO TIPO DE CBTES");
			$row_tipcomp = mysqli_fetch_assoc($tipcomp);
			$tcompafip = $row_tipcomp["codafip"];
			$tcompdesc = $row_tipcomp["descripcion"];
			if ($tcomp_ant != 0) {
				$query_tipcomp_ant = sprintf("SELECT * FROM tipcomp WHERE codnum = $tcomp_ant");
				$tipcomp_ant = mysqli_query($amercado, $query_tipcomp_ant) or die("ERROR LEYENDO TIPO DE CBTES");
				$row_tipcomp_ant = mysqli_fetch_assoc($tipcomp_ant);
				$tcompafip_ant = $row_tipcomp_ant["codafip"];
				$tcompdesc_ant = $row_tipcomp_ant["descripcion"];
				
  				$pdf->SetY($valor_y);
				$subtotal_ant   = number_format($subtotal_ant, 2, ',','.');
				$pdf->Cell(50);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(140,8,'Total '.$tcompafip_ant.' : '.$subtotal_ant,0,0,'R');	
                $renglon += 1;
				$pdf->Line(10,$valor_y + 6, 190, $valor_y + 6);
                $renglon += 1;
				$subtotal_ant = 0;
				$pdf->SetFont('Arial','',9);
				$valor_y = $valor_y + 6;
				$tcomp_ant = $tcomp;
			}	
			$pdf->SetY($valor_y);
			$pdf->Cell(10);
  			$pdf->SetFont('Arial','B',11);
			$pdf->Cell(80,8,$tcompafip.' '.$tcompdesc,0,0,'L');
			$valor_y = $valor_y + 6;
			$tcomp_ant = $tcomp;
			$pdf->SetFont('Arial','B',9);
		}
	
		// Leo en tipcomp el tipo de cbte de AFIP
		$query_tipcomp = sprintf("SELECT * FROM tipcomp WHERE codnum = $tcomp");
		$tipcomp = mysqli_query($amercado, $query_tipcomp) or die("ERROR LEYENDO TIPO DE CBTES");
		$row_tipcomp = mysqli_fetch_assoc($tipcomp);
		$tcompafip = $row_tipcomp["codafip"];
		$tcompdesc = $row_tipcomp["descripcion"];
	
		$nrodoc     = $row_cartvalores["codchq"];
		$importe    = $row_cartvalores["importe"];
		$ncomprel   = $row_cartvalores["ncomprel"];
		$fcomp      = $row_cartvalores["fechapago"];
		$subtotal_ant = $subtotal_ant + $importe;
		switch($tcomp) {
			case 66:
				$tot_66 += $importe;
				break;
			case 67:
				$tot_67 += $importe;
				break;
			case 68:
				$tot_68 += $importe;
				break;
			case 69:
				$tot_69 += $importe;
				break;
			case 70:
				$tot_70 += $importe;
				break;
			case 71:
				$tot_71 += $importe;
				break;
			case 72:
				$tot_72 += $importe;
				break;
			case 73:
				$tot_73 += $importe;
				break;
			case 74:
				$tot_74 += $importe;
				break;
			case 75:
				$tot_75 += $importe;
				break;
			case 76:
				$tot_76 += $importe;
				break;
			case 77:
				$tot_77 += $importe;
				break;
			case 78:
				$tot_78 += $importe;
				break;
			case 79:
				$tot_79 += $importe;
				break;
			case 80:
				$tot_80 += $importe;
				break;
			case 81:
				$tot_81 += $importe;
				break;
			case 82:
				$tot_82 += $importe;
				break;
			case 83:
				$tot_83 += $importe;
				break;
			case 84:
				$tot_84 += $importe;
				break;
			case 85:
				$tot_85 += $importe;
				break;
			case 90:
				$tot_90 += $importe;
				break;
            case 96:
				$tot_96 += $importe;
				break;
            case 106:
				$tot_106 += $importe;
				break;

		}
	
		//Con esto leo el recibo.
		$query_recibo = sprintf("SELECT * FROM cabrecibo WHERE ncomp = $ncomprel");
		$recibo = mysqli_query($amercado, $query_recibo) or die("ERROR LEYENDO RECIBO");
		$row_recibo = mysqli_fetch_assoc($recibo);
	
		$cliente = $row_recibo["cliente"];
	
		//CON EL CLIENTE LEO ENTIDADES
		$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  		$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO LA ENTIDAD");
  		$row_entidades = mysqli_fetch_assoc($enti);
  	
		$cuit         = $row_entidades["cuit"];
		$razsoc       = substr($row_entidades["razsoc"],0,30);
		
		$total     = $total + $importe;
		$importe   = number_format($importe, 2, ',','.');
				
		// Acumulo subtotales
		$pdf->SetY($valor_y);
  		$pdf->Cell(10);
  		$pdf->SetFont('Arial','',9);
		$fecha_cbte = substr($fcomp,8,2)."-".substr($fcomp,5,2)."-".substr($fcomp,0,4);
		$fcomp      = $fecha_cbte;
  		$pdf->Cell(20,8,$fcomp,0,0,'L');
		$pdf->Cell(36,8,$nrodoc,0,0,'L');
		$pdf->Cell(30,8,$cuit,0,0,'L');
		$pdf->Cell(50,8,$razsoc,0,0,'L');
  		$pdf->Cell(35,8,$importe,0,0,'R');
        $pdf->Cell(25,8,$remate,0,0,'R');
		$renglon +=1;
		$valor_y = $valor_y + 6;
	}
	$query_tipcomp_ant = sprintf("SELECT * FROM tipcomp WHERE codnum = $tcomp_ant");
	$tipcomp_ant = mysqli_query($amercado, $query_tipcomp_ant) or die("ERROR LEYENDO TIPO DE CBTES");
	$row_tipcomp_ant = mysqli_fetch_assoc($tipcomp_ant);
	$tcompafip_ant = $row_tipcomp_ant["codafip"];
	$tcompdesc_ant = $row_tipcomp_ant["descripcion"];
	$pdf->SetY($valor_y);
	$subtotal_ant   = number_format($subtotal_ant, 2, ',','.');
	$pdf->Cell(50);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(140,8,'Total '.$tcompafip_ant.' : '.$subtotal_ant,0,0,'R');
	$pdf->Line(10,$valor_y + 6, 190, $valor_y + 6);
	$subtotal_ant = 0;
	$valor_y = $valor_y + 6;
	$tcomp_ant = $tcomp;
	$pdf->SetY($valor_y);
	$pdf->Cell(125);
	$pdf->SetFont('Arial','B',11);
	$total   = number_format($total, 2, ',','.');
	$pdf->Cell(30,8,'TOTAL RETENIDO: ',0,0,'R');
	$pdf->Cell(35,8,$total,0,0,'R');

	$pdf->AddPage();
  	
  	$pdf->SetMargins(0.5, 0.5 , 0.5);
  	$pdf->SetFont('Arial','B',11);
	$pdf->SetY(5);
  	$pdf->Cell(10);
  	$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
  	$pdf->Cell(120);
  	$pagina = $pdf->PageNo();
  	$pdf->Cell(30,10,utf8_decode('Página : ').$pagina.'  ',0,0,'L');
  	$pdf->SetY(10);
  	$pdf->Cell(150);
  	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  	$pdf->SetX(35);
  	$pdf->SetY(20);
  	$pdf->SetFont('Arial','B',12);
  	$pdf->Cell(195,10,'Detalle de Retenciones (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');
  	$pdf->Line(10, 30, 198, 30);

	$query_tipcomp = sprintf("SELECT * FROM tipcomp WHERE codnum BETWEEN 66 AND 85 OR codnum = 90 OR codnum = 96 OR codnum = 106 ORDER BY codafip");
	$tipcomp = mysqli_query($amercado, $query_tipcomp) or die("ERROR LEYENDO TIPO DE CBTES");
	$valor_y = 40;
	while($row_tipcomp = mysqli_fetch_array($tipcomp)) {	
		$tcomp = $row_tipcomp["codnum"];
		$tcompafip = $row_tipcomp["codafip"];
		$tcompdesc = utf8_decode($row_tipcomp["descripcion"]);
		switch($tcomp) {
				case 66:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_66   = number_format($tot_66, 2, ',','.');
					//echo "ESTOY EN CASE 66:".'Total '.$tcompafip.' : '.$tot_66.'  ';
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_66,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 67:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_67   = number_format($tot_67, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_67,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 68:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_68   = number_format($tot_68, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_68,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 69:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_69   = number_format($tot_69, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_69,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 70:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_70   = number_format($tot_70, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_70,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 71:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_71   = number_format($tot_71, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_71,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 72:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_72   = number_format($tot_72, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_72,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 73:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_73   = number_format($tot_73, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_73,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 74:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_74   = number_format($tot_74, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_74,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 75:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_75   = number_format($tot_75, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_75,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 76:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_76   = number_format($tot_76, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_76,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 77:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_77   = number_format($tot_77, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_77,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 78:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_78   = number_format($tot_78, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_78,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 79:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_79   = number_format($tot_79, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_79,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 80:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_80   = number_format($tot_80, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_80,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 81:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_81   = number_format($tot_81, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_81,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 82:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_82   = number_format($tot_82, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_82,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 83:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_83   = number_format($tot_83, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_83,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 84:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_84   = number_format($tot_84, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_84,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 85:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_85   = number_format($tot_85, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_85,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
				case 90:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_90   = number_format($tot_90, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_90,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
                case 96:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_96   = number_format($tot_96, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_96,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
                 case 106:
					$pdf->SetY($valor_y);
  					$pdf->Cell(10);
					$tot_106   = number_format($tot_106, 2, ',','.');
					$pdf->Cell(120,8,'Total '.$tcompafip.' '.$tcompdesc.' : ',0,0,'L');
					$pdf->Cell(20);
					$pdf->Cell(40,8,$tot_106,0,0,'R');
					$valor_y = $valor_y + 6;
					break;
			}
	}
	mysqli_close($amercado);
	$pdf->Output();
?>  
