<?php
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES DE CLIENTES
define('FC_CLI_MAN_A','6');
define('FC_CLI_AUT_A','1');
define('FC_CLI_MAN_B','24');	
define('FC_CLI_AUT_B','23');
define('NC_CLI_MAN_A','7');
define('NC_CLI_AUT_A','5');
define('NC_CLI_MAN_B','26');
define('NC_CLI_AUT_B','25');
define('ND_CLI_MAN_A','22');
define('ND_CLI_AUT_A','21');
define('ND_CLI_MAN_B','30');
define('ND_CLI_AUT_B','29');
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES DE PROVEEDORES
define('FC_PRO_A','32');
define('FC_PRO_B','33');
define('NC_PRO_A','36');
define('NC_PRO_B','37');
define('ND_PRO_A','34');
define('ND_PRO_B','35');

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
	$cant_cartvalores = 0;
	$total_facturas = 0;
	$total_recibos = 0;
	$total_cli = 0.0;
	$total = 0.0;
	while($row_entidades = mysqli_fetch_array($enti)) {
			$cant_cli++;
			$cliente      = $row_entidades["codnum"];
			$razsoc       = $row_entidades["razsoc"];

			// LEO CABFAC POR RANGO DE FECHAS PARA CADA CLIENTE
			$query_cabfac = sprintf("SELECT * FROM cabfac WHERE  cliente = $cliente AND fecreg BETWEEN %s AND %s ORDER BY cliente, fecreg", $fecha_desde, $fecha_hasta);
			$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));  
			
			$totalRows_cabecerafac = mysqli_num_rows($cabecerafac);
			if ($totalRows_cabecerafac == 0)
					continue;
			
			$tot_chq_terceros = 0.0;
			$tot_depositos    = 0.0;
			$tot_efvo         = 0.0; 
			$tot_dolares      = 0.0;
			$tot_retiva       = 0.0;
			$tot_retibrutos   = 0.0;
			$tot_retganan     = 0.0; 
			$tot_retsuss      = 0.0;
	
			while($row_cabecerafac = mysqli_fetch_array($cabecerafac))	{	
		
					if ( $row_cabecerafac["estado"] == "A")
						continue;
					if ( $row_cabecerafac["estado"] == "P")
						continue;
	
					$cant_cabfac++;
	
					$tcomp      = $row_cabecerafac["tcomp"];
					$serie      = $row_cabecerafac["serie"];
					$ncomp      = $row_cabecerafac["ncomp"];
					$nrodoc     = $row_cabecerafac["nrodoc"];
					$totbruto   = $row_cabecerafac["totbruto"];

	
					$pdf->SetY($valor_y);
			  		$pdf->Cell(10);
			  		$pdf->Cell(70,8,'FACTURA     '.$nrodoc.'     IMPORTE :   '.$totbruto.'  ',0,0,'L');
					$total_facturas += $totbruto;
					$valor_y += 6;
					$cant_reng +=1;	

					// LEO CARTVALORES PARA LA FACTURA RELACIONADA

					$query_medios = "SELECT * FROM cartvalores WHERE tcomprel = $tcomp AND serierel = $serie AND ncomprel = $ncomp";
					$medios = mysqli_query($amercado, $query_medios) or die(mysqli_error($amercado));
			
					$totalRows_medios = mysqli_num_rows($medios);

					while($row_medios = mysqli_fetch_array($medios))	{

							$tcomp_medpag      = $row_medios["tcomp"];
							$serie_medpag      = $row_medios["serie"];
							$ncomp_medpag      = $row_medios["ncomp"];
			
							$cant_cartvalores++;

							switch($tcomp_medpag) {
								case 8:
									$tot_chq_terceros += $row_medios["importe"];
									break;
								case 9:
									$tot_depositos    += $row_medios["importe"];
									break;
								case 12:
									$tot_efvo         += $row_medios["importe"];
									break;
								case 13:
									$tot_dolares      += $row_medios["importe"];
									break;
								case 40:
									$tot_retiva       += $row_medios["importe"];
									break;
								case 41:
									$tot_retibrutos   += $row_medios["importe"];
									break;
								case 42:
									$tot_retganan     += $row_medios["importe"];
									break;
								case 43:
									$tot_retsuss      += $row_medios["importe"];
									break;
			
							}
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
					$pdf->Cell(50);
					$pdf->Cell(20,10,' Acumulado por Medio de Pago de los Recibos ',0,0,'L');
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
			if ($tot_chq_terceros != 0.0 || $tot_depositos != 0.0 || $tot_efvo != 0.0 ||
		    		 $tot_dolares != 0.0 || $tot_retiva != 0.0 || $tot_retibrutos != 0.0 ||
		 		     $tot_retganan != 0.0 || $tot_retsuss != 0.0) {

					$valor_y += 6;
					$pdf->SetY($valor_y);
		  			$pdf->Cell(10);
					$pdf->Cell(60,8,substr($razsoc,0,35),1,0,'L');
					$valor_y += 6;
					$cant_reng +=1;
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
	
			}
			if ($tot_depositos != 0.0) { 
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Dep�sitos   : ',0,0,'L');
					$total_cli += $tot_depositos;
					$total     += $tot_depositos;
					$tot_depositos      = number_format($tot_depositos, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_depositos,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_depositos = 0;
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
	
			}
			if ($tot_dolares != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total D�lares     : ',0,0,'L');
					$total_cli += $tot_dolares;
					$total     += $tot_dolares;
					$tot_dolares        = number_format($tot_dolares, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_dolares,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_dolares = 0;
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
	
			}
			
			//===============================================================================================
			// LEO CABRECIBO POR RANGO DE FECHAS PARA CADA CLIENTE
			$query_cabrecibo = sprintf("SELECT * FROM cabrecibo WHERE  cliente = $cliente AND fecha BETWEEN %s AND %s ORDER BY cliente, fecha", $fecha_desde, $fecha_hasta);
			$cabecerarecibo = mysqli_query($amercado, $query_cabrecibo) or die(mysqli_error($amercado));  
			
			$totalRows_cabecerarecibo = mysqli_num_rows($cabecerarecibo);
			if ($totalRows_cabecerarecibo == 0)
					continue;
			
			$tot_chq_terceros = 0.0;
			$tot_depositos    = 0.0;
			$tot_efvo         = 0.0; 
			$tot_dolares      = 0.0;
			$tot_retiva       = 0.0;
			$tot_retibrutos   = 0.0;
			$tot_retganan     = 0.0; 
			$tot_retsuss      = 0.0;
	
			while($row_cabecerarecibo = mysqli_fetch_array($cabecerarecibo))	{	
		
					$cant_cabfac++;
					$tcomp      = $row_cabecerarecibo["tcomp"];
					$serie      = $row_cabecerarecibo["serie"];
					$ncomp      = $row_cabecerarecibo["ncomp"];
					//$nrodoc     = $row_cabecerarecibo["nrodoc"];
					$totbruto   = $row_cabecerarecibo["imptot"];

					$pdf->SetY($valor_y);
			  		$pdf->Cell(10);
			  		$pdf->Cell(70,8,'RECIBO     '.$ncomp.'     IMPORTE :   '.$totbruto.'  ',0,0,'L');
					$total_recibos += $totbruto;
					$valor_y += 6;
					$cant_reng +=1;	

					// LEO CARTVALORES PARA LA FACTURA RELACIONADA

					$query_medios = "SELECT * FROM cartvalores WHERE tcomprel = $tcomp AND serierel = $serie AND ncomprel = $ncomp";
					$medios = mysqli_query($amercado, $query_medios) or die(mysqli_error($amercado));
			
					$totalRows_medios = mysqli_num_rows($medios);

					while($row_medios = mysqli_fetch_array($medios))	{

							$tcomp_medpag      = $row_medios["tcomp"];
							$serie_medpag      = $row_medios["serie"];
							$ncomp_medpag      = $row_medios["ncomp"];
			
							$cant_cartvalores++;

							switch($tcomp_medpag) {
								case 8:
									$tot_chq_terceros += $row_medios["importe"];
									break;
								case 9:
									$tot_depositos    += $row_medios["importe"];
									break;
								case 12:
									$tot_efvo         += $row_medios["importe"];
									break;
								case 13:
									$tot_dolares      += $row_medios["importe"];
									break;
								case 40:
									$tot_retiva       += $row_medios["importe"];
									break;
								case 41:
									$tot_retibrutos   += $row_medios["importe"];
									break;
								case 42:
									$tot_retganan     += $row_medios["importe"];
									break;
								case 43:
									$tot_retsuss      += $row_medios["importe"];
									break;
			
							}
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
	
			}
			if ($tot_chq_terceros != 0.0 || $tot_depositos != 0.0 || $tot_efvo != 0.0 ||
		    		 $tot_dolares != 0.0 || $tot_retiva != 0.0 || $tot_retibrutos != 0.0 ||
		 		     $tot_retganan != 0.0 || $tot_retsuss != 0.0) {

					$valor_y += 6;
					$pdf->SetY($valor_y);
		  			$pdf->Cell(10);
					$pdf->Cell(60,8,substr($razsoc,0,35),1,0,'L');
					$valor_y += 6;
					$cant_reng +=1;
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
					$pdf->Cell(50);
					$pdf->Cell(20,10,' Acumulado por Medio de Pago de los Recibos ',0,0,'L');
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
	
			}
			if ($tot_depositos != 0.0) { 
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total Dep�sitos   : ',0,0,'L');
					$total_cli += $tot_depositos;
					$total     += $tot_depositos;
					$tot_depositos      = number_format($tot_depositos, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_depositos,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_depositos = 0;
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
	
			}
			if ($tot_dolares != 0.0) {
					$cant_reng +=1;
					$pdf->SetY($valor_y);
					$pdf->Cell(70);
		  			$pdf->Cell(30,8,' Total D�lares     : ',0,0,'L');
					$total_cli += $tot_dolares;
					$total     += $tot_dolares;
					$tot_dolares        = number_format($tot_dolares, 2, ',','.');
					$pdf->Cell(50);
					$pdf->Cell(25,8,$tot_dolares,0,0,'R');
					$valor_y = $valor_y + 6;
					$tot_dolares = 0;
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
	
			}
			//===============================================================================================
			if ($total_cli == 0)
				continue;
			$pdf->SetY($valor_y);
			$pdf->Cell(10);
		  	$pdf->Cell(30,8,'Total Cliente ( '.$cliente.' ):',0,0,'L');
			$total_cli        = number_format($total_cli, 2, ',','.');
			$pdf->Cell(25,8,$total_cli,0,0,'R');
			$cant_reng +=1;
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
			}
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(10);
			$pdf->Cell(160,8,'======================================================================================================',0,0,'L');	
			$valor_y = $valor_y + 6;
			$total_cli = 0;
			$cant_reng +=1;

	}

	if ($cant_reng > 28) {
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
			$total   = number_format($total, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL GRAL: ',0,0,'R');
			$pdf->Cell(35,8,$total,0,0,'R');
			$cant_reng = 0;
	}
	else {
			$valor_y = $valor_y + 6;
			$pdf->SetY($valor_y);
			$pdf->Cell(60);
			$total   = number_format($total, 2, ',','.');
			$pdf->Cell(80,8,'TOTAL GRAL: ',0,0,'R');
			$pdf->Cell(35,8,$total,0,0,'R');
			// =============================================================
			$valor_y = $valor_y + 6;
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
	}

mysqli_close($amercado);

$pdf->Output();
?>  
