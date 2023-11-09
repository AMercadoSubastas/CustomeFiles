<?php
	define('FPDF_FONTPATH','fpdf17/font/');
	set_time_limit(0); // Para evitar el timeout

	//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES DE PROVEEDORES
	define('FC_CLI_A4_LOT','51');
	define('FC_CLI_A4_CON','52');
	define('FC_CLI_B4_LOT','53');
	define('FC_CLI_B4_CON','54');
	define('FC_CLI_A6','55');
	define('FC_CLI_B6','56');
	define('NC_CLI_A4','57');
	define('NC_CLI_B4','58');
	define('ND_CLI_A4','59');
	define('ND_CLI_B4','60');
	define('NC_CLI_A6','61');
	define('NC_CLI_B6','62');
	define('ND_CLI_A6','63');
	define('ND_CLI_B6','64');


	require('fpdf17/fpdf.php');
	require('numaletras.php');
	//Conecto con la  base de datos
	require_once('Connections/amercado.php');

	mysqli_select_db($amercado, $database_amercado);

	// Leo los par�metros del formulario anterior
	$f_desde = $_POST['fecha_desde'];
	$f_hasta = $_POST['fecha_hasta'];

	$fecha_desde = "'".substr($f_desde,6,4)."-".substr($f_desde,3,2)."-".substr($f_desde,0,2)."'";
	$fecha_hasta = "'".substr($f_hasta,6,4)."-".substr($f_hasta,3,2)."-".substr($f_hasta,0,2)."'";
	
	$fechahoy = date("d-m-Y");
	$total_inmob = 0.00;
    $total_subastas = 0.00;
    $total_gral = 0.00;
    $tcomp = "";
	// Leo la tabla cartvalores para los cbtes de retenciones de Subastas

	$query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE fechapago BETWEEN $fecha_desde AND $fecha_hasta AND tcomp BETWEEN 66 AND 67 ORDER BY tcomp,fechapago");
	$cartvalores = mysqli_query($amercado, $query_cartvalores) or die("ERROR LEYENDO CARTVALORES");
	$renglon = 0;


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
	$pdf->Cell(30,10,'P�gina : '.$pagina.' ',0,0,'L');
	$pdf->SetY(10);
	$pdf->Cell(150);
	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
	$pdf->SetX(35);
	$pdf->SetY(20);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(195,10,'Detalle de Retenciones Subastas (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');

 
  

  	$pdf->SetY(30);
  	$pdf->Cell(10);
  	$pdf->Cell(20,8,'Fecha',0,0,'C');
  	$pdf->Cell(34,8,'Comprobante ',0,0,'C');
  	$pdf->Cell(35,8,'CUIT ',0,0,'C');
  	$pdf->Cell(60,8,'Cliente ',0,0,'C');
  	$pdf->Cell(35,8,'Importe ',0,0,'C');
  	$pdf->SetY(36);
  	$pdf->Cell(10);
  	$pdf->Line(10, 36, 190, 36);
  
    
  	$valor_y = 40;
  
	$i = 60;
	$j = 100;
	$total = 0;
	$subtotal_ant = 0;
	$tcomp_ant = 0;
	$pdf->SetFont('Arial','',9);
	$tot_66 = 0.00; $tot_67 = 0.00; 
	while($row_cartvalores = mysqli_fetch_array($cartvalores)) {	
        $ncomprel = $row_cartvalores["ncomprel"];
        $query_detrecibo = sprintf("SELECT * FROM detrecibo WHERE ncomp = %s", $ncomprel);
	    $detrecibo = mysqli_query($amercado, $query_detrecibo) or die("ERROR LEYENDO DETRECIBO");
        $row_detrecibo = mysqli_fetch_array($detrecibo);
        $tcomp_fc = $row_detrecibo["tcomprel"];
        $ncomp_fc = $row_detrecibo["ncomprel"];
        //echo "NCOMPREL = ".$ncomprel."  TCOMP_FC = ".$tcomp_fc."  NCOMP_FC =  ".$ncomp_fc." - ";
        // LEO LA FACTURA PASA SABER SI ES SUBASTAS O INMOBILIARIA
        $query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = %s AND ncomp = %s", $tcomp_fc, $ncomp_fc);
	    $cabfac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
        $row_cabfac = mysqli_fetch_array($cabfac);
        $usuario = $row_cabfac["usuario"];
        if ($usuario == 25 || $usuario == 26)
            continue;
        
        
		$tcomp      = $row_cartvalores["tcomp"];
		if ($renglon > 24){
			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
			$pdf->Cell(120);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,'P�gina : '.$pagina.' ',0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetX(35);
			$pdf->SetY(20);
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(195,10,'Detalle de Retenciones Subastas (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');




			$pdf->SetY(30);
			$pdf->Cell(10);
			$pdf->Cell(20,8,'Fecha',0,0,'C');
			$pdf->Cell(34,8,'Comprobante ',0,0,'C');
			$pdf->Cell(35,8,'CUIT ',0,0,'C');
			$pdf->Cell(60,8,'Cliente ',0,0,'C');
			$pdf->Cell(35,8,'Importe ',0,0,'C');
			$pdf->SetY(36);
			$pdf->Cell(10);
			$pdf->Line(10, 36, 190, 36);

			$renglon = 0;
			$valor_y = 40;


		}
		if ($tcomp_ant == 0 || $tcomp_ant != $tcomp) {
			//Imprimo el subt�tulo
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
  		$pdf->Cell(22,8,$fcomp,0,0,'L');
		$pdf->Cell(36,8,$nrodoc,0,0,'L');
		$pdf->Cell(37,8,$cuit,0,0,'L');
		$pdf->Cell(50,8,$razsoc,0,0,'L');
  		$pdf->Cell(35,8,$importe,0,0,'R');
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
    $total_subastas = $total;
    $total_gral = $total;
	$total   = number_format($total, 2, ',','.');
	$pdf->Cell(30,8,'TOTAL RETENIDO SUBASTAS: ',0,0,'R');
	$pdf->Cell(35,8,$total,0,0,'R');

	$pdf->AddPage();
  	
  	$pdf->SetMargins(0.5, 0.5 , 0.5);
  	$pdf->SetFont('Arial','B',11);
	$pdf->SetY(5);
  	$pdf->Cell(10);
  	$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
  	$pdf->Cell(120);
  	$pagina = $pdf->PageNo();
  	$pdf->Cell(30,10,'P�gina : '.$pagina.'  ',0,0,'L');
  	$pdf->SetY(10);
  	$pdf->Cell(150);
  	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  	$pdf->SetX(35);
  	$pdf->SetY(20);
  	$pdf->SetFont('Arial','B',12);
  	$pdf->Cell(195,10,'Detalle de Retenciones Subastas (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');
  	$pdf->Line(10, 30, 190, 30);

	$query_tipcomp = sprintf("SELECT * FROM tipcomp WHERE codnum BETWEEN 66 AND 67 ORDER BY codafip");
	$tipcomp = mysqli_query($amercado, $query_tipcomp) or die("ERROR LEYENDO TIPO DE CBTES");
	$valor_y = 40;
	while($row_tipcomp = mysqli_fetch_array($tipcomp)) {	
		$tcomp = $row_tipcomp["codnum"];
		$tcompafip = $row_tipcomp["codafip"];
		$tcompdesc = $row_tipcomp["descripcion"];
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
			}
	}
    $total = 0.00;
    // ACA COMIENZA INMOBILIARIA
	$query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE fechapago BETWEEN $fecha_desde AND $fecha_hasta AND tcomp BETWEEN 66 AND 67 ORDER BY tcomp,fechapago");
	$cartvalores = mysqli_query($amercado, $query_cartvalores) or die("ERROR LEYENDO CARTVALORES");
	$renglon = 0;


	// Inicio el pdf con los datos de cabecera
	//$pdf=new FPDF('P','mm','A4');
	//
	$pdf->AddPage();
	$pdf->SetMargins(0.5, 0.5 , 0.5);
	$pdf->SetFont('Arial','B',11);
	$pdf->SetY(5);
	$pdf->Cell(10);
	$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
	$pdf->Cell(120);
	$pagina = $pdf->PageNo();
	$pdf->Cell(30,10,'P�gina : '.$pagina.' ',0,0,'L');
	$pdf->SetY(10);
	$pdf->Cell(150);
	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
	$pdf->SetX(35);
	$pdf->SetY(20);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(195,10,'Detalle de Retenciones Inmobiliaria (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');

 
  

  	$pdf->SetY(30);
  	$pdf->Cell(10);
  	$pdf->Cell(20,8,'Fecha',0,0,'C');
  	$pdf->Cell(34,8,'Comprobante ',0,0,'C');
  	$pdf->Cell(35,8,'CUIT ',0,0,'C');
  	$pdf->Cell(60,8,'Cliente ',0,0,'C');
  	$pdf->Cell(35,8,'Importe ',0,0,'C');
  	$pdf->SetY(36);
  	$pdf->Cell(10);
  	$pdf->Line(10, 36, 190, 36);
  
    
  	$valor_y = 40;
  
	$i = 60;
	$j = 100;
	$total = 0;
	$subtotal_ant = 0;
	$tcomp_ant = 0;
	$pdf->SetFont('Arial','',9);
	$tot_66 = 0.00; $tot_67 = 0.00; 
	while($row_cartvalores = mysqli_fetch_array($cartvalores)) {	
        $ncomprel = $row_cartvalores["ncomprel"];
        $query_detrecibo = sprintf("SELECT * FROM detrecibo WHERE ncomp = %s", $ncomprel);
	    $detrecibo = mysqli_query($amercado, $query_detrecibo) or die("ERROR LEYENDO DETRECIBO");
        $row_detrecibo = mysqli_fetch_array($detrecibo);
        $tcomp_fc = $row_detrecibo["tcomprel"];
        $ncomp_fc = $row_detrecibo["ncomprel"];
        // LEO LA FACTURA PASA SABER SI ES SUBASTAS O INMOBILIARIA
        $query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = %s AND ncomp = %s", $tcomp_fc, $ncomp_fc);
	    $cabfac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");
        $row_cabfac = mysqli_fetch_array($cabfac);
        $usuario = $row_cabfac["usuario"];
        if ($usuario != 25 && $usuario != 26)
            continue;
        
        
		$tcomp      = $row_cartvalores["tcomp"];
		if ($renglon > 24){
			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
			$pdf->Cell(120);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,'P�gina : '.$pagina.' ',0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetX(35);
			$pdf->SetY(20);
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(195,10,'Detalle de Retenciones Inmobiliaria (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');




			$pdf->SetY(30);
			$pdf->Cell(10);
			$pdf->Cell(20,8,'Fecha',0,0,'C');
			$pdf->Cell(34,8,'Comprobante ',0,0,'C');
			$pdf->Cell(35,8,'CUIT ',0,0,'C');
			$pdf->Cell(60,8,'Cliente ',0,0,'C');
			$pdf->Cell(35,8,'Importe ',0,0,'C');
			$pdf->SetY(36);
			$pdf->Cell(10);
			$pdf->Line(10, 36, 190, 36);

			$renglon = 0;
			$valor_y = 40;


		}
		if ($tcomp_ant == 0 || $tcomp_ant != $tcomp) {
			//Imprimo el subt�tulo
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
  		$pdf->Cell(22,8,$fcomp,0,0,'L');
		$pdf->Cell(36,8,$nrodoc,0,0,'L');
		$pdf->Cell(37,8,$cuit,0,0,'L');
		$pdf->Cell(50,8,$razsoc,0,0,'L');
  		$pdf->Cell(35,8,$importe,0,0,'R');
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
    $total_inmob = $total;
    $total_gral += $total;
	$total   = number_format($total, 2, ',','.');
	$pdf->Cell(30,8,'TOTAL RETENIDO INMOBIILIARIA: ',0,0,'R');
	$pdf->Cell(35,8,$total,0,0,'R');

	$pdf->AddPage();
  	
  	$pdf->SetMargins(0.5, 0.5 , 0.5);
  	$pdf->SetFont('Arial','B',11);
	$pdf->SetY(5);
  	$pdf->Cell(10);
  	$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
  	$pdf->Cell(120);
  	$pagina = $pdf->PageNo();
  	$pdf->Cell(30,10,'P�gina : '.$pagina.'  ',0,0,'L');
  	$pdf->SetY(10);
  	$pdf->Cell(150);
  	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  	$pdf->SetX(35);
  	$pdf->SetY(20);
  	$pdf->SetFont('Arial','B',12);
  	$pdf->Cell(195,10,'Detalle de Retenciones Inmobiliaria (Ventas) '.' Desde: '.$f_desde.'  Hasta: '.$f_hasta,0,0,'C');
  	$pdf->Line(10, 30, 190, 30);

	$query_tipcomp = sprintf("SELECT * FROM tipcomp WHERE codnum BETWEEN 66 AND 67 ORDER BY codafip");
	$tipcomp = mysqli_query($amercado, $query_tipcomp) or die("ERROR LEYENDO TIPO DE CBTES");
	$valor_y = 40;
	while($row_tipcomp = mysqli_fetch_array($tipcomp)) {	
		$tcomp = $row_tipcomp["codnum"];
		$tcompafip = $row_tipcomp["codafip"];
		$tcompdesc = $row_tipcomp["descripcion"];
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
			}
	}
    $valor_y = $valor_y + 6;
    $pdf->SetY($valor_y);
    $porc_inmob = 0.00;
    $porc_subastas = 0.00;
    if ($total_gral != 0) {
        $porc_subastas = $total_subastas / $total_gral * 100.0;
        $porc_inmob    = $total_inmob / $total_gral * 100.0;
    }
    $total_inmob   = number_format($total_inmob, 2, ',','.');
    $porc_inmob    = number_format($porc_inmob, 2, ',','.');
    $pdf->SetX(20);
	$pdf->Cell(40,8,'TOTAL  INMOBIILIARIA: ',0,0,'R');
    $pdf->SetX(60);
	$pdf->Cell(35,8,$total_inmob,0,0,'R');
    $pdf->Cell(10);
    $pdf->Cell(35,8,$porc_inmob." %",0,0,'R');
    $valor_y = $valor_y + 6;
    $pdf->SetY($valor_y);
    $total_subastas   = number_format($total_subastas, 2, ',','.');
    $porc_subastas    = number_format($porc_subastas, 2, ',','.');
    $pdf->SetX(20);
	$pdf->Cell(40,8,'TOTAL  SUBASTAS: ',0,0,'R');
    $pdf->SetX(60);
	$pdf->Cell(35,8,$total_subastas,0,0,'R');
    $pdf->Cell(10);
    $pdf->Cell(35,8,$porc_subastas." %",0,0,'R');


	mysqli_close($amercado);
	$pdf->Output();
?>  
