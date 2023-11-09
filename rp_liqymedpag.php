<?php
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
// Leo los parámetros del formulario anterior
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
  	$pdf->Cell(20,10,' ADRIAN MERCADO  SUBASTAS S.A. ',0,0,'L');
  	$pdf->Cell(120);
  	$pagina = $pdf->PageNo();
  	$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
  	$pdf->SetY(10);
  	$pdf->Cell(150);
  	$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  	$pdf->SetY(15);
  	$pdf->Cell(30);
  	$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');
  	$pdf->SetFont('Arial','B',9);
  	$pdf->SetY(25);
  	$pdf->Cell(10);
  	$pdf->Cell(50,16,' Cliente ',1,0,'L');
  	$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
  	$pdf->Cell(30,16,' Importe ',1,0,'L');
  	$pdf->SetY(33);
  	$pdf->Cell(115);
  	$pdf->SetFont('Arial','B',8);  
  	$valor_y = 45;
  
	$i = 60;
	$j = 100;
	$cant_reng  = 0;
	$tipoenti=1;
	
	// Leo los clientes
	$query_entidades = sprintf("SELECT * FROM entidades WHERE  tipoent = %s ORDER BY tipoent, codnum", $tipoenti);
	$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
	$cant_reng = 0;
	$cant_cli = 0;
	$cant_cabfac = 0;
	$cant_cabrecibos = 0;
	$cant_cartvalores = 0;
	$total_facturas = 0;
	$total_recibos = 0;
	$total_cli = 0.0;
	$total = 0.0;
    $tot_liq = 0.0;
	$total_liquidaciones   = 0.0;
	$tot_gral_chq_terceros = 0.0;
	$tot_gral_chq_propio   = 0.0;
	$tot_gral_depositos    = 0.0;
	$tot_gral_efvo         = 0.0; 
	$tot_gral_dolares      = 0.0;
	$tot_gral_retiva       = 0.0;
	$tot_gral_retibrutos   = 0.0;
	$tot_gral_retganan     = 0.0; 
	$tot_gral_retsuss      = 0.0;
	while($row_entidades = mysqli_fetch_array($enti)) {
			$cant_cli++;
			$cliente      = $row_entidades["codnum"];
			$razsoc       = $row_entidades["razsoc"];
		
			// PRIMERO LEO LAS LIQUIDACIONES POR CLIENTE
			$query_liquidacion = sprintf("SELECT * FROM liquidacion WHERE  cliente = $cliente AND fechaliq BETWEEN %s AND %s ORDER BY cliente, fechaliq", $fecha_desde, $fecha_hasta);
			$liquidacion = mysqli_query($amercado, $query_liquidacion) or die(mysqli_error($amercado));  
			
			$totalRows_liquidacion = mysqli_num_rows($liquidacion);
			if ($totalRows_liquidacion == 0)
					continue;
			$tot_chq_terceros = 0.0;
			$tot_chq_propio   = 0.0;
			$tot_depositos    = 0.0;
			$tot_efvo         = 0.0; 
			$tot_dolares      = 0.0;
			$tot_retiva       = 0.0;
			$tot_retibrutos   = 0.0;
			$tot_retganan     = 0.0; 
			$tot_retsuss      = 0.0;
			while($row_liquidacion = mysqli_fetch_array($liquidacion)) {
				$remate = $row_liquidacion["codrem"];
				$total_liq = $row_liquidacion["totremate"];
				$tcompliq  = $row_liquidacion["tcomp"]; 
				$ncompliq  = $row_liquidacion["ncomp"];
				$nrodocliq = $row_liquidacion["nrodoc"];
				if ($ncompliq == 2043 || $ncompliq == 2045 || $ncompliq == 2047)
                    echo "TCOMPLIQ = ".$tcompliq."  NCOMPLIQ = ".$ncompliq." REMATE =   ".$remate."  ";
                // LEO CARTVALORES PARA ESA LIQUIDACION
				$query_cartvalores = sprintf("SELECT * FROM cartvalores WHERE  tcompsal = $tcompliq AND ncompsal = $ncompliq AND codrem = $remate ");
               if ($ncompliq == 2043 || $ncompliq == 2045 || $ncompliq == 2047)
                    echo "QUERY = ".$query_cartvalores." - "; 
				$cartvalores = mysqli_query($amercado, $query_cartvalores) or die(mysqli_error($amercado));  
			
				$totalRows_cartvalores = mysqli_num_rows($cartvalores);
				if ($totalRows_cartvalores == 0)
					continue;
                
                //$valor_y += 6;
                $pdf->SetY($valor_y);
                $pdf->Cell(10);
                $pdf->Cell(60,8,substr($razsoc,0,35),1,0,'L');
                $valor_y += 6;
                $cant_reng +=1;

				$pdf->SetY($valor_y);
				$pdf->Cell(10);
                $tot_liq   = number_format($total_liq, 2, ',','.');
				$pdf->Cell(70,8,'LIQUIDACION     '.$nrodocliq.'     IMPORTE :   '.$tot_liq.'  ',0,0,'L');
				$total_liquidaciones += $total_liq;
				$valor_y += 6;
				$cant_reng +=1;	
				
				
				
				while($row_medios = mysqli_fetch_array($cartvalores))	{

                    $tcomp_medpag      = $row_medios["tcomp"];
                    $serie_medpag      = $row_medios["serie"];
                    $ncomp_medpag      = $row_medios["ncomp"];

                    $cant_cartvalores++;

                    switch($tcomp_medpag) {
                        case 8:
                            $tot_gral_chq_terceros += $row_medios["importe"];
                            $tot_chq_terceros += $row_medios["importe"];
                            break;
                        case 9:
                        case 39:
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
                        case 14:
                            $tot_chq_propio      += $row_medios["importe"];
                            $tot_gral_chq_propio      += $row_medios["importe"];
                            break;
                        case 40:
                            $tot_retiva       += $row_medios["importe"];
                            $tot_gral_retiva       += $row_medios["importe"];
                            break;
                        case 41:
                            $tot_retibrutos   += $row_medios["importe"];
                            $tot_gral_retibrutos   += $row_medios["importe"];
                            break;
                        case 42:
                            $tot_retganan     += $row_medios["importe"];
                            $tot_gral_retganan     += $row_medios["importe"];
                            break;
                        case 43:
                            $tot_retsuss      += $row_medios["importe"];
                            $tot_gral_retsuss      += $row_medios["importe"];
                            break;

                    }
                }
            }
	
            if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,' Acumulado por Medio de Pago de las liquidaciones ',0,0,'L');
					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
			
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;

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

			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
			if ($tot_depositos != 0.0) { 
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Depósitos   : ',0,0,'L');
					$total_cli += $tot_depositos;
					$total     += $tot_depositos;
					$tot_depositos      = number_format($tot_depositos, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_depositos,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_depositos = 0;
			}	
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
			if ($tot_efvo != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Efectivo    : ',0,0,'L');
					$total_cli += $tot_efvo;
					$total     += $tot_efvo;
					$tot_efvo   = number_format($tot_efvo, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_efvo,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_efvo = 0;
			}
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
			if ($tot_dolares != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Dólares     : ',0,0,'L');
					$total_cli += $tot_dolares;
					$total     += $tot_dolares;
					$tot_dolares        = number_format($tot_dolares, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_dolares,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_dolares = 0;
			}
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;

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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
		
			//=================================================================================

			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
        /*
			if ($tot_chq_terceros != 0.0 || $tot_chq_propio != 0.0 || $tot_depositos != 0.0 || $tot_efvo != 0.0 || $tot_dolares != 0.0 || $tot_retiva != 0.0 || $tot_retibrutos != 0.0 || $tot_retganan != 0.0 || $tot_retsuss != 0.0) {

					$valor_y += 6;
					$pdf->SetY($valor_y);
		  			$pdf->Cell(10);
					$pdf->Cell(60,8,substr($razsoc,0,35),1,0,'L');
					$valor_y += 6;
					$cant_reng +=1;
			}
            */
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,' Acumulado por Medio de Pago de las liquidaciones ',0,0,'L');
					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;

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
			
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,' Acumulado por Medio de Pago de las liquidaciones ',0,0,'L');
					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;

			}
			if ($tot_chq_propio != 0.0) {
					$cant_reng +=1; 
					$pdf->SetY($valor_y);
		  			$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Cheques propios: ',0,0,'L');
					$total_cli += $tot_chq_propio;
					$total     += $tot_chq_propio;
					$tot_chq_propio   = number_format($tot_chq_propio, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_chq_propio,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_chq_propio = 0;
			}

			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
			if ($tot_depositos != 0.0) { 
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Depósitos   : ',0,0,'L');
					$total_cli += $tot_depositos;
					$total     += $tot_depositos;
					$tot_depositos      = number_format($tot_depositos, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_depositos,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_depositos = 0;
			}	
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
			if ($tot_dolares != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Dólares     : ',0,0,'L');
					$total_cli += $tot_dolares;
					$total     += $tot_dolares;
					$tot_dolares        = number_format($tot_dolares, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_dolares,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_dolares = 0;
			}
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;

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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');
					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
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
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
	
			}
			//=======================================================================================
			if ($total_cli == 0)
				continue;
			$pdf->SetY($valor_y);
			$pdf->Cell(10);
		  	$pdf->Cell(30,8,'Total Cliente ( '.$cliente.' ):',0,0,'L');
			$total_cli        = number_format($total_cli, 2, ',','.');
			$pdf->Cell(25,8,$total_cli,0,0,'R');
			$cant_reng +=1;
			if ($cant_reng > 30) {
					$cant_reng  = 0;
					$pdf->AddPage();
			  		$pdf->SetMargins(0.5, 0.5 , 0.5);
					$pdf->SetFont('Arial','B',11);
					$pdf->SetY(5);
					$pdf->Cell(10);
					$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
					$pdf->Cell(120);
					$pagina = $pdf->PageNo();
					$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
					$pdf->SetY(10);
					$pdf->Cell(150);
					$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
					$pdf->SetY(15);
					$pdf->Cell(40);
					$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

					$pdf->SetFont('Arial','B',9);
			  		$pdf->SetY(25);
			  		$pdf->Cell(10);
			  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
			  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
			  		$pdf->Cell(30,16,' Importe ',1,0,'L');
			  		$pdf->SetY(33);
			  		$pdf->Cell(115);
			  		$pdf->SetFont('Arial','B',8);  
			  		$valor_y = 45;
			}
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(10);
			$pdf->Cell(160,8,'======================================================================================================',0,0,'L');	
			$valor_y = $valor_y + 6;
			$total_cli = 0;
			$cant_reng +=1;

	}

	if ($cant_reng > 30) {
			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
			$pdf->Cell(120);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetY(15);
			$pdf->Cell(40);
			$pdf->Cell(20,10,'  Acumulado por Medio de Pago de las liquidaciones desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

			$pdf->SetFont('Arial','B',9);
	  		$pdf->SetY(25);
	  		$pdf->Cell(10);
	  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
	  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
	  		$pdf->Cell(30,16,' Importe ',1,0,'L');
	  		$pdf->SetY(33);
	  		$pdf->Cell(115);
	  		$pdf->SetFont('Arial','B',8);  
	  		$valor_y = 45;
			$pdf->Cell(60);
			$tot_liq = $total_liquidaciones;
			$total_liquidaciones   = number_format($total_liquidaciones, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL LIQUIDACIONES: ',0,0,'R');
			$pdf->Cell(35,8,$total_liquidaciones,0,0,'R');
			$cant_reng = 0;
	}
	else {
	
			// =============================================================
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_liq = $total_liquidaciones;
			$total_liquidaciones   = number_format($total_liquidaciones, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL LIQUIDACIONES: ',0,0,'R');
			$pdf->Cell(35,8,$total_liquidaciones,0,0,'R');
			// =============================================================
			
	}
	//ACA IMPRIMO LOS TOTALES POR MEDIO DE PAGO
	if ($cant_reng > 30) {
			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
			$pdf->Cell(120);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetY(15);
			$pdf->Cell(50);
			$pdf->Cell(20,10,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');

			$pdf->SetFont('Arial','B',9);
	  		$pdf->SetY(25);
	  		$pdf->Cell(10);
	  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
	  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
	  		$pdf->Cell(30,16,' Importe ',1,0,'L');
	  		$pdf->SetY(33);
	  		$pdf->Cell(115);
	  		$pdf->SetFont('Arial','B',8);  
	  		$valor_y = 45;
			$pdf->Cell(60);
			
			$tot_gral = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss + $tot_gral_dolares + $tot_gral_efvo + $tot_gral_chq_propio + $tot_gral_depositos + $tot_gral_chq_terceros;

			
			$tot_gral_chq_terceros   = number_format($tot_gral_chq_terceros, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL CHEQUES 3ROS: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_chq_terceros,0,0,'R');
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);

			$tot_gral_chq_propio   = number_format($tot_gral_chq_propio, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL CHEQUES PROPIOS: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_chq_propio,0,0,'R');
			

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
			$pdf->Cell(80,8,'TOTAL DOLARES: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_dolares,0,0,'R');		
						
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_gral_retenciones = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss;
			$tot_gral_retenciones   = number_format($tot_gral_retenciones, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL RETENCIONES: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_retenciones,0,0,'R');
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_gral_facgstos = $tot_liq - $tot_gral;
			$tot_gral_facgstos   = number_format($tot_gral_facgstos, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL FACTURAS DE GASTOS: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_facgstos,0,0,'R');
			$cant_reng = 0;
	}
	else {
			$pdf->AddPage();
			$pdf->SetMargins(0.5, 0.5 , 0.5);
			$pdf->SetFont('Arial','B',11);
			$pdf->SetY(5);
			$pdf->Cell(10);
			$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS  S.A. ',0,0,'L');
			$pdf->Cell(120);
			$pagina = $pdf->PageNo();
			$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
			$pdf->SetY(10);
			$pdf->Cell(150);
			$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
			$pdf->SetY(15);
			$pdf->Cell(50);
			$pdf->Cell(20,10,'  Acumulado por Medio de Pago de los Recibos desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');
			$pdf->SetFont('Arial','B',9);
	  		$pdf->SetY(25);
	  		$pdf->Cell(10);
	  		$pdf->Cell(50,16,' Cliente ',1,0,'L');
	  		$pdf->Cell(90,16,' Medio de pago ',1,0,'L');
	  		$pdf->Cell(30,16,' Importe ',1,0,'L');
	  		$pdf->SetY(33);
	  		$pdf->Cell(115);
	  		$pdf->SetFont('Arial','B',8);  
	  		$valor_y = 45;
			$pdf->Cell(60);
			
			$tot_gral = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss + $tot_gral_dolares + $tot_gral_efvo + $tot_gral_chq_propio + $tot_gral_depositos + $tot_gral_chq_terceros;
			
			$tot_gral_chq_terceros   = number_format($tot_gral_chq_terceros, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL CHEQUES 3ROS: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_chq_terceros,0,0,'R');
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);

			$tot_gral_chq_propio   = number_format($tot_gral_chq_propio, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL CHEQUES PROPIOS: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_chq_propio,0,0,'R');
	
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
			$pdf->Cell(80,8,'TOTAL DOLARES: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_dolares,0,0,'R');		
						
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_gral_retenciones = $tot_gral_retiva + $tot_gral_retibrutos + $tot_gral_retganan + $tot_gral_retsuss;
			$tot_gral_retenciones   = number_format($tot_gral_retenciones, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL RETENCIONES: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_retenciones,0,0,'R');
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$tot_gral_facgstos = $tot_liq - $tot_gral;
			$tot_gral_facgstos   = number_format($tot_gral_facgstos, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL FACTURAS DE GASTOS: ',0,0,'R');
			$pdf->Cell(35,8,$tot_gral_facgstos,0,0,'R');
			$cant_reng = 0;

			// =============================================================
	
	}

mysqli_close($amercado);

$pdf->Output();
?>  
