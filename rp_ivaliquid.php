<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES
define('FC_PROV_A','32');
define('FC_PROV_C','33');
define('ND_PROV_A','34');
define('ND_PROV_C','35');
define('NC_PROV_A','36');
define('NC_PROV_C','37');
// CONCAFAC_NROCONC PARA RETENCIONES
define('CONC_NO_GRAV','20');
define('RET_IVA','30');
define('RET_IIBB_BA','31');
define('RET_IIBB_CABA','32');
define('RET_GAN','33');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
//$fecha_desde = $_GET['fecha_desde'];
//$fecha_hasta = $_GET['fecha_hasta'];
//$fecha_desde = "2008-01-01";
//$fecha_hasta = "2008-02-28";
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];


$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");
// Leo los renglones
// Verificado 
/*
$query_detfac = sprintf("SELECT * FROM detfac WHERE fecreg BETWEEN %s AND %s", $fecha_desde, $fecha_hasta);
$detallefac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
//$row_detallefac = mysqli_fetch_assoc($detallefac);
$totalRows_detallefac = mysqli_num_rows($detallefac);
*/
// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die(mysqli_error($amercado));
$row_Recordset2 = mysqli_fetch_assoc($impuestos);
$totalRows_Recordset2 = mysqli_num_rows($impuestos);
$impuestos->data_seek(1);
    $row = $impuestos->fetch_array();
// Calcular los porcentajes de impuestos
    $porc_iva105 = $row[1]/ 100 ."<br>";
    $impuestos->data_seek(0);
    $row = $impuestos->fetch_array();
    $porc_iva21 = $row[1] / 100;


// Leo la cabecera

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN %s AND %s ORDER BY fecreg , nrodoc ", $fecha_desde, $fecha_hasta);
//$query_cabfac = sprintf("SELECT * FROM cabfac ORDER BY fecreg");
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));
//$row_cabecerafac = mysqli_fetch_assoc($cabecerafac);

// Leo las Liquidaciones
$query_liquidacion = sprintf("SELECT * FROM liquidacion WHERE fechaliq BETWEEN %s AND %s ORDER BY fechaliq , nrodoc ", $fecha_desde, $fecha_hasta);
$t_liquidacion = mysqli_query($amercado, $query_liquidacion) or die(mysqli_error($amercado));
$totalRows_liquidacion = mysqli_num_rows($t_liquidacion);

// Inicio el pdf con los datos de cabecera
  $pdf=new FPDF('L','mm','Legal');
  
  $pdf->AddPage();
  //$pdf->SetAutoPageBreak(1 , 2) ;
  $pdf->SetMargins(0.5, 0.5 , 0.5);
  $pdf->SetFont('Arial','B',11);
  $pdf->SetY(5);
  $pdf->Cell(10);
  $pdf->Cell(20,10,' ADRIAN MERCADO S.A. '.$fecha_desde.'   '.$fecha_hasta,0,0,'L');
  $pdf->Cell(200);
  $pagina = $pdf->PageNo();
  $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
  $pdf->SetY(10);
  $pdf->Cell(230);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(130);
  $pdf->Cell(20,10,' Libro Liquidaciones ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  $pdf->Cell(20,16,'    Fecha',1,0,'L');
  //$pdf->SetY(18);
  //$pdf->Cell(5);
  $pdf->Cell(26,16,' Nro.Liquidaci�n',1,0,'L');
  $pdf->Cell(42,16,'       Raz�n Social',1,0,'L');
  $pdf->Cell(24,16,'     CUIT',1,0,'L');
  $pdf->Cell(22,16,'Conceptos ',1,0,'L');
  $pdf->Cell(22,16,'Conceptos ',1,0,'L');
  $pdf->Cell(22,16,' IVA Cr�dito',1,0,'L');
  $pdf->Cell(22,16,' Al�cuota',1,0,'L');
  $pdf->Cell(22,16,' Percepci�n',1,0,'L');
  $pdf->Cell(22,16,' Percepci�n',1,0,'L');
  $pdf->Cell(22,16,' Percepci�n',1,0,'L');
  $pdf->Cell(22,16,'   Total ',1,0,'L');
  $pdf->SetY(34);
  $pdf->Cell(114);
  $pdf->Cell(22,8,' Exentos ',0,0,'L');
  $pdf->Cell(22,8,' Gravados  ',0,0,'L');
  $pdf->Cell(22,8,'Fiscal ',0,0,'L');
  $pdf->Cell(22,8,'Diferencial',0,0,'L');
  $pdf->Cell(22,8,'    IVA',0,0,'L');
  $pdf->Cell(22,8,' IIBB CABA',0,0,'L');
  $pdf->Cell(22,8,' IIBB BsAs',0,0,'L');
  $pdf->Cell(22,8,' Facturado',0,0,'L');
  
  $valor_y = 45;
  
// Datos de los renglones
$i = 0;
$acum_total_neto  = 0;
$acum_total_iva   = 0;
$acum_total_neto21  = 0;
$acum_total_neto105 = 0;
$acum_total_iva21   = 0;
$acum_total_iva105  = 0;
$acum_total_exento  = 0;
$acum_tot_resol   = 0;
$acum_total       = 0;
$acum_df_retiva  = 0;
$acum_df_retib_CABA = 0.0;
$acum_df_retib_BSAS = 0.0;
$acum_df_neto25 = 0.0;
$acum_df_iva25 = 0.0;
$acum_df_neto50 = 0.0;
$acum_df_iva50 = 0.0;
$acum_df_neto105 = 0.0;
$acum_df_iva105 = 0.0;
$acum_df_neto210 = 0.0;
$acum_df_iva210 = 0.0;
$acum_df_neto270 = 0.0;
$acum_df_iva270 = 0.0;

$df_retiva   = 0.0;
$df_retib_CABA    = 0.0;
$df_retib_BSAS   = 0.0;
$df_neto25 = 0.0;
$df_iva25 = 0.0;
$df_neto50 = 0.0;
$df_iva50 = 0.0;
$df_neto105 = 0.0;
$df_iva105 = 0.0;
$df_neto210 = 0.0;
$df_iva210 = 0.0;
$df_neto270 = 0.0;
$df_iva270 = 0.0;

$t_acum_total_neto = 0.00;
$t_acum_total_iva = 0.00;
$t_acum_tot_resol = 0.00;
$t_acum_total = 0.00;
$t_acum_df_retiva  = 0.00;
$t_acum_df_retib_CABA = 0.00;
$t_acum_df_retib_BSAS = 0.00;
/*
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	if ($tcomp != FC_PROV_A && $tcomp != FC_PROV_C && $tcomp != ND_PROV_A && $tcomp != ND_PROV_C && $tcomp != NC_PROV_A && $tcomp != NC_PROV_C)
		continue;
	if ($tcomp == FC_PROV_A || $tcomp == FC_PROV_C || $tcomp == ND_PROV_A || $tcomp == ND_PROV_C) {
		$signo = 1;
	}
	else {
		$signo = -1;
	}
	if ($i < 22) {
		$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
		$detallefac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
		$totalRows_detallefac = mysqli_num_rows($detallefac);
		$df_concnograv  = 0.00;
		$df_retib_CABA  = 0.00;
		$df_retib_BSAS = 0.00;
		$df_retiva = 0.00;
		$df_neto25 = 0.0;
		$df_iva25 = 0.0;
		$df_neto50 = 0.0;
		$df_iva50 = 0.0;
		$df_neto105 = 0.0;
		$df_iva105 = 0.0;
		$df_neto210 = 0.0;
		$df_iva210 = 0.0;
		$df_neto270 = 0.0;
		$df_iva270 = 0.0;

		while($row_detallefac = mysqli_fetch_array($detallefac)) {
			$concafac = $row_detallefac["concafac"];
			
			if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA 
						 || $concafac == CONC_NO_GRAV) {
				switch($concafac) {
					case	CONC_NO_GRAV:
						//$df_retiva = "0,00";
						$df_concnograv += ($row_detallefac["neto"] * $signo);
						//$df_retib  = "0,00";
						//$df_retgan = "0,00";
						break;
					case	RET_IVA:
						$df_retiva += ($row_detallefac["neto"] * $signo);
						//$df_concnograv = "0,00";
						//$df_retib  = "0,00";
						//$df_retgan = "0,00";
						break;
					case 	RET_IIBB_BA:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						$df_retib_BSAS  += ($row_detallefac["neto"] * $signo);
						//$df_retgan = "0,00";
						break;
					case 	RET_IIBB_CABA:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						$df_retib_CABA  += ($row_detallefac["neto"] * $signo);
						//$df_retgan = "0,00";
						break;

					
				}
			}
			else {
				$porciva  =  $row_detallefac["porciva"];
				if ($porciva != 0) {
					
					switch($porciva) {
						
						case	2.5:
							$df_neto25 += ($row_detallefac["neto"] * $signo);
							$df_iva25  += ($row_detallefac["iva"] * $signo);
							break;
						case	5:
							$df_neto50 += ($row_detallefac["neto"] * $signo);
							$df_iva50  += ($row_detallefac["iva"] * $signo);
							break;
						case 	10.5:
							$df_neto105 += ($row_detallefac["neto"] * $signo);
							$df_iva105  += ($row_detallefac["iva"] * $signo);
							break;
						case 	21:
							$df_neto210 += ($row_detallefac["neto"] * $signo);
							$df_iva210  += ($row_detallefac["iva"] * $signo);
							break;
						case 	27:
							$df_neto270 += ($row_detallefac["neto"] * $signo);
							$df_iva270  += ($row_detallefac["iva"] * $signo);
							break;
					
					}	
				
				}
				else {
					continue;
				}
			}
		}
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		//if ($row_cabecerafac["totneto"] > 0)
			$tot_neto     = ($row_cabecerafac["totneto"] - $df_retib_BSAS - $df_retib_CABA - $df_retiva) * $signo;
			//$tot_neto     = $row_cabecerafac["totneto"] * $signo;
		//else
		//	$tot_neto     = $row_cabecerafac["totbruto"] * $signo;
		$tot_neto21   = $row_cabecerafac["totneto21"]  *$signo;
		$tot_neto105  = $row_cabecerafac["totneto105"] * $signo;
		$tot_comision = $row_cabecerafac["totcomis"] * $signo;
		$tot_iva21    = $row_cabecerafac["totiva21"] * $signo;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21 ;
		$tot_iva105   = $row_cabecerafac["totiva105"]  * $signo;
		$tot_resol    = $row_cabecerafac["totimp"] * $signo;
		$total        = $row_cabecerafac["totbruto"] * $signo;
		$nroorig      = $row_cabecerafac["nrodoc"];
		$total_neto   = $tot_neto ; // ($tot_neto21 + $tot_neto105)  * $signo;
		$total_iva    = ($tot_iva21  + $tot_iva105) ;
		$total_exento = 0;
		if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C)
			$total_exento = $row_cabecerafac["totneto"] * $signo;
		
		if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C)
			$total_exento += $df_concnograv;
		$total_neto   = ($tot_neto - $total_exento) ;
		// Acumulo subtotales
		$acum_total_neto  = $acum_total_neto  + $total_neto;
		$acum_total_iva   = $acum_total_iva   + $total_iva;
		$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
		$acum_total       = $acum_total       + $total;
		$acum_df_retiva   = $acum_df_retiva   + $df_retiva;
		$acum_df_retib_CABA    = $acum_df_retib_CABA    + $df_retib_CABA;
		$acum_df_retib_BSAS   = $acum_df_retib_BSAS   + $df_retib_BSAS;
		$acum_total_exento  = $acum_total_exento  + $total_exento;
		
		$acum_df_neto25    = $acum_df_neto25    + $df_neto25;
		$acum_df_iva25     = $acum_df_iva25     + $df_iva25;
		$acum_df_neto50    = $acum_df_neto50    + $df_neto50;
		$acum_df_iva50     = $acum_df_iva50     + $df_iva50;
		$acum_df_neto105   = $acum_df_neto105   + $df_neto105;
		$acum_df_iva105    = $acum_df_iva105    + $df_iva105;
		$acum_df_neto210   = $acum_df_neto210   + $df_neto210;
		$acum_df_iva210    = $acum_df_iva210    + $df_iva210;
		$acum_df_neto270   = $acum_df_neto270   + $df_neto270;
		$acum_df_iva270    = $acum_df_iva270    + $df_iva270;
		
		// Formateo los campos antes de imprimir
		$total_neto    = number_format($total_neto, 2, ',','.');
		$total_iva     = number_format($total_iva, 2, ',','.');
		$tot_resol     = number_format($tot_resol, 2, ',','.');
		$total         = number_format($total, 2, ',','.');
		$df_retiva     = number_format($df_retiva, 2, ',','.');
		$df_retib_CABA = number_format($df_retib_CABA, 2, ',','.');
		$df_retib_BSAS = number_format($df_retib_BSAS, 2, ',','.');
		$total_exento  = number_format($total_exento, 2, ',','.');
	
		// Leo el cliente
  		$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  		$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  		$row_entidades = mysqli_fetch_assoc($enti);
  		$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  		$nro_cliente   = $row_entidades["numero"];
  		$cuit_cliente  = $row_entidades["cuit"];
  	
		// Imprimo los renglones
		$pdf->SetY($valor_y);
  		$pdf->Cell(1);
  		$pdf->Cell(19,6,$fecha,0,0,'L');
  		$pdf->Cell(26,6,$nroorig,0,0,'L');
  		$pdf->Cell(42,6,$nom_cliente,0,0,'L');
  		$pdf->Cell(24,6,$cuit_cliente,0,0,'L');
  		$pdf->Cell(22,6,$total_exento,0,0,'R');
  		$pdf->Cell(22,6,$total_neto,0,0,'R');
  		$pdf->Cell(22,6,$total_iva,0,0,'R');
		$pdf->Cell(22,6," ",0,0,'R');
		$pdf->Cell(22,6,$df_retiva,0,0,'R');
  		$pdf->Cell(22,6,$df_retib_CABA,0,0,'R');
  		$pdf->Cell(22,6,$df_retib_BSAS,0,0,'R');
  		$pdf->Cell(22,6,$total,0,0,'R');
		$total_exento  = "0,00";
		
		$i = $i + 1;
		$valor_y = $valor_y + 6;
	}
	else {
		// Imprimo subtotales de la hoja, uso otras variables porque el number_format
		// me jode los acumulados
		$f_acum_total_neto  = number_format($acum_total_neto, 2, ',','.');
		$f_acum_total_iva   = number_format($acum_total_iva, 2, ',','.');
		$f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
		$f_acum_total       = number_format($acum_total, 2, ',','.');
		$f_acum_df_retiva   = number_format($acum_df_retiva, 2, ',','.');
		$f_acum_df_retib_CABA = number_format($acum_df_retib_CABA, 2, ',','.');
		$f_acum_df_retib_BSAS = number_format($acum_df_retib_BSAS, 2, ',','.');
		$f_acum_total_exento  = number_format($acum_total_exento, 2, ',','.');
		
		$pdf->SetY($valor_y);
		$pdf->Cell(112);
		$pdf->Cell(22,6,$f_acum_total_exento,0,0,'R');
		$pdf->Cell(22,6,$f_acum_total_neto,0,0,'R');
		$pdf->Cell(22,6,$f_acum_total_iva,0,0,'R');
		$pdf->Cell(22,6," ",0,0,'R');
		$pdf->Cell(22,6,$f_acum_df_retiva,0,0,'R');
  		$pdf->Cell(22,6,$f_acum_df_retib_CABA,0,0,'R');
  		$pdf->Cell(22,6,$f_acum_df_retib_BSAS,0,0,'R');
		$pdf->Cell(22,6,$f_acum_total,0,0,'R');
		
		// Voy a otra hoja e imprimo los titulos 
		$pdf->AddPage();
		$pdf->SetMargins(0.5, 0.5 , 0.5);
  		$pdf->SetFont('Arial','B',11);
		$pdf->SetY(5);
		$pdf->Cell(10);
		$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
		$pdf->Cell(200);
		$pagina = $pdf->PageNo();
		$pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
		$pdf->SetY(10);
		$pdf->Cell(230);
		$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
		$pdf->SetY(15);
		$pdf->Cell(130);
		$pdf->Cell(20,10,' Libro IVA Compras ',0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(3);
		$pdf->Cell(20,16,'    Fecha',1,0,'L');
		//$pdf->SetY(18);
		//$pdf->Cell(5);
		$pdf->Cell(24,16,' Nro.Factura',1,0,'L');
		$pdf->Cell(42,16,'       Raz�n Social',1,0,'L');
		$pdf->Cell(24,16,'     CUIT',1,0,'L');
		$pdf->Cell(22,16,' Conceptos ',1,0,'L');
		$pdf->Cell(22,16,' Conceptos ',1,0,'L');
		$pdf->Cell(22,16,' IVA Cr�dito',1,0,'L');
		$pdf->Cell(22,16,' Al�cuota',1,0,'L');
		$pdf->Cell(22,16,' Percepci�n',1,0,'L');
		$pdf->Cell(22,16,' Percepci�n',1,0,'L');
		$pdf->Cell(22,16,' Percepci�n',1,0,'L');
		$pdf->Cell(22,16,'   Total ',1,0,'L');
		$pdf->SetY(34);
		$pdf->Cell(114);
		$pdf->Cell(22,8,'Exentos ',0,0,'L');
		$pdf->Cell(22,8,'Gravados',0,0,'L');
		$pdf->Cell(22,8,'Fiscal ',0,0,'L');
		$pdf->Cell(22,8,'Diferencial',0,0,'L');
		$pdf->Cell(22,8,'    IVA',0,0,'L');
		$pdf->Cell(22,8,' IIBB CABA',0,0,'L');
		$pdf->Cell(22,8,' IIBB BsAs',0,0,'L');
		$pdf->Cell(22,8,' Facturado',0,0,'L');
  
		$valor_y = 45;
		// reinicio los contadores
		
		$i = 0;
		//================================================================================
		$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
		$detallefac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
		$totalRows_detallefac = mysqli_num_rows($detallefac);
		$df_concnograv = 0.0;
		$df_retib_CABA  = 0.0;
		$df_retib_BSAS = 0.0;
		$df_retiva = 0.0;
		$df_retiva = 0.00;
		$df_neto25 = 0.0;
		$df_iva25 = 0.0;
		$df_neto50 = 0.0;
		$df_iva50 = 0.0;
		$df_neto105 = 0.0;
		$df_iva105 = 0.0;
		$df_neto210 = 0.0;
		$df_iva210 = 0.0;
		$df_neto270 = 0.0;
		$df_iva270 = 0.0;

		while($row_detallefac = mysqli_fetch_array($detallefac)) {
			$concafac = $row_detallefac["concafac"];
			if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA || $concafac == CONC_NO_GRAV) {
				switch($concafac) {
					case	CONC_NO_GRAV:
						//$df_retiva = "0,00";
						$df_concnograv += ($row_detallefac["neto"] * $signo);
						//$df_retib  = "0,00";
						//$df_retgan = "0,00";
						break;
					case	RET_IVA:
						$df_retiva += ($row_detallefac["neto"] * $signo);
						//$df_concnograv = "0,00";
						//$df_retib  = "0,00";
						//$df_retgan = "0,00";
						break;
					case 	RET_IIBB_BA:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						$df_retib_BSAS  += ($row_detallefac["neto"] * $signo);
						//$df_retgan = "0,00";
						break;
					case 	RET_IIBB_CABA:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						$df_retib_CABA  += ($row_detallefac["neto"] * $signo);
						//$df_retgan = "0,00";
						break;

					
				}
			}
			else {
				$porciva  =  $row_detallefac["porciva"];
				if ($porciva != 0) {
					
					switch($porciva) {
						case	2.5:
							$df_neto25 += ($row_detallefac["neto"] * $signo);
							$df_iva25  += ($row_detallefac["iva"] * $signo);
							break;
						case	5:
							$df_neto50 += ($row_detallefac["neto"] * $signo);
							$df_iva50  += ($row_detallefac["iva"] * $signo);
							break;
						case 	10.5:
							$df_neto105 += ($row_detallefac["neto"] * $signo);
							$df_iva105  += ($row_detallefac["iva"] * $signo);
							break;
						case 	21:
							$df_neto210 += ($row_detallefac["neto"] * $signo);
							$df_iva210  += ($row_detallefac["iva"] * $signo);
							break;
						case 	27:
							$df_neto270 += ($row_detallefac["neto"] * $signo);
							$df_iva270  += ($row_detallefac["iva"] * $signo);
							break;
					
					}	
				
				}
				else {
					continue;
				}
			}
		}
		
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		//if ($row_cabecerafac["totneto"] > 0)
			$tot_neto     = $row_cabecerafac["totneto"] * $signo;
		//else
		//	$tot_neto     = $row_cabecerafac["totbruto"] * $signo;
		$tot_neto21   = $row_cabecerafac["totneto21"]  * $signo ;
		$tot_neto105  = $row_cabecerafac["totneto105"]  * $signo;
		$tot_comision = $row_cabecerafac["totcomis"] * $signo;
		$tot_iva21    = $row_cabecerafac["totiva21"]  * $signo; 
		$tot_iva105   = $row_cabecerafac["totiva105"] * $signo;
		$tot_resol    = $row_cabecerafac["totimp"] * $signo;
		$total        = $row_cabecerafac["totbruto"] * $signo;
		$nroorig      = $row_cabecerafac["nrodoc"];
		$total_neto   = $tot_neto ; 
		$total_iva    = ($tot_iva21  + $tot_iva105)  ;
		$total_exento = 0;
		if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C)
			$total_exento += $row_cabecerafac["totneto"] * $signo;
		
		if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C)
			$total_exento += $df_concnograv;
		
		// Acumulo subtotales
		$acum_total_neto  = $acum_total_neto  + $total_neto;
		$acum_total_iva   = $acum_total_iva   + $total_iva;
		$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
		$acum_total       = $acum_total       + $total;
		$acum_df_retiva   = $acum_df_retiva   + $df_retiva;
		$acum_df_retib_CABA = $acum_df_retib_CABA   + $df_retib_CABA;
		$acum_df_retib_BSAS   = $acum_df_retib_BSAS   + $df_retib_BSAS;
		$acum_total_exento  = $acum_total_exento  + $total_exento;
	
		$acum_df_neto25    = $acum_df_neto25    + $df_neto25;
		$acum_df_iva25     = $acum_df_iva25     + $df_iva25;
		$acum_df_neto50    = $acum_df_neto50    + $df_neto50;
		$acum_df_iva50     = $acum_df_iva50     + $df_iva50;
		$acum_df_neto105   = $acum_df_neto105   + $df_neto105;
		$acum_df_iva105    = $acum_df_iva105    + $df_iva105;
		$acum_df_neto210   = $acum_df_neto210   + $df_neto210;
		$acum_df_iva210    = $acum_df_iva210    + $df_iva210;
		$acum_df_neto270   = $acum_df_neto270   + $df_neto270;
		$acum_df_iva270    = $acum_df_iva270    + $df_iva270;
	
		// Formateo los campos antes de imprimir
		$total_neto  = number_format($total_neto, 2, ',','.');
		$total_iva   = number_format($total_iva, 2, ',','.');
		$tot_resol   = number_format($tot_resol, 2, ',','.');
		$total       = number_format($total, 2, ',','.');
		$df_retiva   = number_format($df_retiva, 2, ',','.');
		if (isset($df_retib_CABA))
			$df_retib_CABA = number_format($df_retib_CABA, 2, ',','.');
		if (isset($df_retib_BSAS))
			$df_retib_BSAS = number_format($df_retib_BSAS, 2, ',','.');
		$total_exento  = number_format($total_exento, 2, ',','.');
	
		// Leo el cliente
  		$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  		$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  		$row_entidades = mysqli_fetch_assoc($enti);
  		$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  		$nro_cliente   = $row_entidades["numero"];
  		$cuit_cliente  = $row_entidades["cuit"];
  	
		// Imprimo los renglones
		$pdf->SetY($valor_y);
  		$pdf->Cell(1);
  		$pdf->Cell(19,6,$fecha,0,0,'L');
  		$pdf->Cell(26,6,$nroorig,0,0,'L');
  		$pdf->Cell(42,6,$nom_cliente,0,0,'L');
  		$pdf->Cell(24,6,$cuit_cliente,0,0,'L');
  		$pdf->Cell(22,6,$total_exento,0,0,'R');
  		$pdf->Cell(22,6,$total_neto,0,0,'R');
  		$pdf->Cell(22,6,$total_iva,0,0,'R');
		$pdf->Cell(22,6," ",0,0,'R');
		$pdf->Cell(22,6,$df_retiva,0,0,'R');
  		$pdf->Cell(22,6,$df_retib_CABA,0,0,'R');
  		$pdf->Cell(22,6,$df_retib_BSAS,0,0,'R');
  		$pdf->Cell(22,6,$total,0,0,'R');
		$total_exento  = "0,00";
		
		$i = $i + 1;
		$valor_y = $valor_y + 6;
		//================================================================================
	}
}


// Imprimo subtotales de la hoja cuando termino con las facturas pero antes los acumulo 
// para el total general
$t_acum_total_neto  = $acum_total_neto;
$t_acum_total_iva   = $acum_total_iva;
$t_acum_tot_resol   = $acum_tot_resol;
$t_acum_total       = $acum_total;
$t_acum_df_retiva   = $acum_df_retiva;
$t_acum_df_retib_CABA = $acum_df_retib_CABA;
$t_acum_df_retib_BSAS = $acum_df_retib_BSAS;
$t_acum_total_exento  = $acum_total_exento;
		
$f_acum_total_neto  = number_format($acum_total_neto, 2, ',','.');
$f_acum_total_iva   = number_format($acum_total_iva, 2, ',','.');
$f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
$f_acum_total       = number_format($acum_total, 2, ',','.');
$f_acum_df_retiva   = number_format($acum_df_retiva, 2, ',','.');
$f_acum_df_retib_CABA = number_format($acum_df_retib_CABA, 2, ',','.');
$f_acum_df_retib_BSAS = number_format($acum_df_retib_BSAS, 2, ',','.');
$f_acum_total_exento   = number_format($acum_total_exento, 2, ',','.');
		
$pdf->SetY($valor_y);
$pdf->Cell(54);
$pdf->Cell(58,6,"TOTAL FACTURAS, NCRED. Y NDEB. ",0,0,'L');
$pdf->Cell(22,6,$f_acum_total_exento,0,0,'R');
$pdf->Cell(22,6,$f_acum_total_neto,0,0,'R');
$pdf->Cell(22,6,$f_acum_total_iva,0,0,'R');
$pdf->Cell(22,6," ",0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retiva,0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retib_CABA,0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retib_BSAS,0,0,'R');
$pdf->Cell(22,6,$f_acum_total,0,0,'R');
$valor_y = $valor_y + 12;
$i = $i + 2;
// $i = 0 ;
*/
// Ahora voy por las Liquidaciones
$acum_total_neto  = 0;
$acum_total_iva   = 0;
$acum_tot_resol   = 0;
$acum_total       = 0;
$acum_df_retiva  = 0;
$acum_df_retib_CABA = 0;
$acum_df_retib_BSAS = 0;
$acum_total_exento = 0;
while($row_liquidacion = mysqli_fetch_array($t_liquidacion))
{
	if ($i < 22) {
		$tcomp		  = $row_liquidacion["tcomp"];
		$serie		  = $row_liquidacion["serie"];
		$ncomp		  = $row_liquidacion["ncomp"];
		$fecha        = substr($row_liquidacion["fechaliq"],8,2)."-".substr($row_liquidacion["fechaliq"],5,2)."-".substr($row_liquidacion["fechaliq"],0,4);
		$cliente      = $row_liquidacion["cliente"];
		$tot_neto21   = $row_liquidacion["totneto1"];
		$tot_neto105  = $row_liquidacion["totneto2"];
		//$tot_comision = $row_liquidacion["totcomis"];
		$tot_iva21    = $row_liquidacion["totiva21"];
		$tot_iva105   = $row_liquidacion["totiva105"];
		$tot_resol    = 0.00;
		$total        = $row_liquidacion["subtot1"] + $row_liquidacion["subtot2"];
		$nroorig      = $row_liquidacion["nrodoc"];
		$total_neto   = $tot_neto21 + $tot_neto105;
		$total_iva    = $tot_iva21  + $tot_iva105;
	
		// ACA LEO EL CBTESANUL PARA VERIFICAR SI ESTA ANULADA
		$query_cbtesanul = sprintf("SELECT * FROM cbtesanul WHERE  tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
  		$cbtesanul = mysqli_query($amercado, $query_cbtesanul) or die(mysqli_error($amercado));
		//$row_cbtesanul = mysqli_fetch_assoc($cbtesanul);
		$estado = "P";
		if (mysqli_num_rows($cbtesanul) > 0) 
			$estado		  = "A";
			
		// Acumulo subtotales
		if ($estado != "A") {
			// Acumulo subtotales
			$acum_total_neto  = $acum_total_neto  + $total_neto;
			$acum_total_iva   = $acum_total_iva   + $total_iva;
			$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
			$acum_total       = $acum_total       + $total;
			$acum_df_retiva   = $acum_df_retiva   + $df_retiva;
			$acum_df_retib_CABA = $acum_df_retib_CABA  + $df_retib_CABA;
			$acum_df_retib_BSAS = $acum_df_retib_BSAS  + $df_retib_BSAS;
	
			// Formateo los campos antes de imprimir
			$total_neto  = number_format($total_neto, 2, ',','.');
			$total_iva   = number_format($total_iva, 2, ',','.');
			$tot_resol   = number_format($tot_resol, 2, ',','.');
			$total       = number_format($total, 2, ',','.');
			//$df_retiva   = number_format($df_retiva, 2, ',','.');
			//$df_retib    = number_format($df_retib, 2, ',','.');
			//$df_retgan   = number_format($df_retgan, 2, ',','.');
	
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
			$contacto      = $row_entidades["contacto"];
			$mailcont      = $row_entidades["mailcont"];
			$tellinea      = $row_entidades["tellinea"];
			$telcelu       = $row_entidades["telcelu"];
  	
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
  			$pdf->Cell(26,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,$nom_cliente,0,0,'L');
  			$pdf->Cell(24,6,$cuit_cliente,0,0,'L');
  			$pdf->Cell(22,6," ",0,0,'R');
  			$pdf->Cell(22,6,$total_neto,0,0,'R');
  			$pdf->Cell(22,6,$total_iva,0,0,'R');
			$pdf->Cell(22,6," ",0,0,'R');
			$pdf->Cell(22,6,$df_retiva,0,0,'R');
  			$pdf->Cell(22,6,$df_retib_CABA,0,0,'R');
  			$pdf->Cell(22,6,$df_retib_BSAS,0,0,'R');
  			$pdf->Cell(22,6,$total,0,0,'R');
		
		
			$i = $i + 1;
			$valor_y = $valor_y + 6;
	
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(60,6,"Contacto: ".$contacto,0,0,'L');
  			$pdf->Cell(50,6,"Mail: ".$mailcont,0,0,'L');
  			$pdf->Cell(50,6,"Tel.L�nea: ".$tellinea,0,0,'L');
  			$pdf->Cell(50,6,"Tel.Celular: ".$telcelu,0,0,'L');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
			
		}
		else {
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
  			$pdf->Cell(26,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,"ANULADA",0,0,'L');
  			$pdf->Cell(24,6," ",0,0,'L');
  			$pdf->Cell(22,6," ",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
			$pdf->Cell(22,6," ",0,0,'R');
			$pdf->Cell(22,6,"0,00",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
		
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
	}
	else {
		// Imprimo subtotales de la hoja, uso otras variables porque el number_format
		// me jode los acumulados
		$f_acum_total_neto  = number_format($acum_total_neto, 2, ',','.');
		$f_acum_total_iva   = number_format($acum_total_iva, 2, ',','.');
		$f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
		$f_acum_total       = number_format($acum_total, 2, ',','.');
		$f_acum_df_retiva   = number_format($acum_df_retiva, 2, ',','.');
		$f_acum_df_retib_CABA = number_format($acum_df_retib_CABA, 2, ',','.');
		$f_acum_df_retib_BSAS = number_format($acum_df_retib_BSAS, 2, ',','.');
		
		$pdf->SetY($valor_y);
		$pdf->Cell(134);
		$pdf->Cell(22,6,$f_acum_total_neto,0,0,'R');
		$pdf->Cell(22,6,$f_acum_total_iva,0,0,'R');
		$pdf->Cell(22,6," ",0,0,'R');
		$pdf->Cell(22,6,$f_acum_df_retiva,0,0,'R');
  		$pdf->Cell(22,6,$f_acum_df_retib_CABA,0,0,'R');
  		$pdf->Cell(22,6,$f_acum_df_retib_BSAS,0,0,'R');
		$pdf->Cell(22,6,$f_acum_total,0,0,'R');
		
		// Voy a otra hoja e imprimo los titulos 
		$pdf->AddPage();
		$pdf->SetMargins(0.5, 0.5 , 0.5);
  		$pdf->SetFont('Arial','B',11);
		$pdf->SetY(5);
		$pdf->Cell(10);
		$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
		$pdf->Cell(200);
		$pagina = $pdf->PageNo();
		$pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
		$pdf->SetY(10);
		$pdf->Cell(230);
		$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
		$pdf->SetY(15);
		$pdf->Cell(130);
		$pdf->Cell(20,10,' Libro Liquidaciones ',0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(3);
		$pdf->Cell(20,16,'    Fecha',1,0,'L');
		//$pdf->SetY(18);
		//$pdf->Cell(5);
		$pdf->Cell(26,16,' Nro.Liquidaci�n',1,0,'L');
		$pdf->Cell(42,16,'       Raz�n Social',1,0,'L');
		$pdf->Cell(24,16,'     CUIT',1,0,'L');
		$pdf->Cell(22,16,' Conceptos ',1,0,'L');
		$pdf->Cell(22,16,' Conceptos ',1,0,'L');
		$pdf->Cell(22,16,' IVA Cr�dito',1,0,'L');
		$pdf->Cell(22,16,' Al�cuota',1,0,'L');
		$pdf->Cell(22,16,' Percepci�n',1,0,'L');
		$pdf->Cell(22,16,' Percepci�n',1,0,'L');
		$pdf->Cell(22,16,' Percepci�n',1,0,'L');
		$pdf->Cell(22,16,'   Total ',1,0,'L');
		$pdf->SetY(34);
		$pdf->Cell(114);
		$pdf->Cell(22,8,'Exentos ',0,0,'L');
		$pdf->Cell(22,8,'Gravados',0,0,'L');
		$pdf->Cell(22,8,'Fiscal ',0,0,'L');
		$pdf->Cell(22,8,'Diferencial',0,0,'L');
		$pdf->Cell(22,8,'    IVA',0,0,'L');
		$pdf->Cell(22,8,' IIBB CABA',0,0,'L');
		$pdf->Cell(22,8,' IIBB BsAs',0,0,'L');
		$pdf->Cell(22,8,' Facturado',0,0,'L');
  	
		$valor_y = 45;
		// reinicio los contadores
		
		$i = 0;

		// Imprimo el primer rengl�n que ya lo hab�a le�do
		$tcomp	      = $row_liquidacion["tcomp"];
		$serie	      = $row_liquidacion["serie"];
		$ncomp	      = $row_liquidacion["ncomp"];
		$fecha        = substr($row_liquidacion["fechaliq"],8,2)."-".substr($row_liquidacion["fechaliq"],5,2)."-".substr($row_liquidacion["fechaliq"],0,4);
		$cliente      = $row_liquidacion["cliente"];
		$tot_neto21   = $row_liquidacion["totneto1"];
		$tot_neto105  = $row_liquidacion["totneto2"];
		//$tot_comision = $row_liquidacion["totcomis"];
		$tot_iva21    = $row_liquidacion["totiva21"];
		$tot_iva105   = $row_liquidacion["totiva105"];
		$tot_resol    = 0.00;
		$total        = $row_liquidacion["subtot1"] + $row_liquidacion["subtot2"];
		$nroorig      = $row_liquidacion["nrodoc"];
		$total_neto   = $tot_neto21 + $tot_neto105;
		$total_iva    = $tot_iva21  + $tot_iva105;
	
		// ACA LEO EL CBTESANUL PARA VERIFICAR SI ESTA ANULADA
		$query_cbtesanul = sprintf("SELECT * FROM cbtesanul WHERE  tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
  		$cbtesanul = mysqli_query($amercado, $query_cbtesanul) or die(mysqli_error($amercado));
		//$row_cbtesanul = mysqli_fetch_assoc($cbtesanul);
		$estado = "P";
		if (mysqli_num_rows($cbtesanul) > 0) 
			$estado		  = "A";

		// Acumulo subtotales
		if ($estado != "A") {
			// Acumulo subtotales
			$acum_total_neto  = $acum_total_neto  + $total_neto;
			$acum_total_iva   = $acum_total_iva   + $total_iva;
			$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
			$acum_total       = $acum_total       + $total;
			$acum_df_retiva   = $acum_df_retiva   + $df_retiva;
			$acum_df_retib_CABA = $acum_df_retib_CABA + $df_retib_CABA;
			$acum_df_retib_BSAS = $acum_df_retib_BSAS + $df_retib_BSAS;
	
			// Formateo los campos antes de imprimir
			$total_neto  = number_format($total_neto, 2, ',','.');
			$total_iva   = number_format($total_iva, 2, ',','.');
			$tot_resol   = number_format($tot_resol, 2, ',','.');
			$total       = number_format($total, 2, ',','.');
			//$df_retiva   = number_format($df_retiva, 2, ',','.');
			//$df_retib    = number_format($df_retib, 2, ',','.');
			//$df_retgan   = number_format($df_retgan, 2, ',','.');
	
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  			$contacto      = $row_entidades["contacto"];
			$mailcont      = $row_entidades["mailcont"];
			$tellinea      = $row_entidades["tellinea"];
			$telcelu       = $row_entidades["telcelu"];
			
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
  			$pdf->Cell(26,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,$nom_cliente,0,0,'L');
  			$pdf->Cell(24,6,$cuit_cliente,0,0,'L');
  			$pdf->Cell(22,6," ",0,0,'R');
  			$pdf->Cell(22,6,$total_neto,0,0,'R');
  			$pdf->Cell(22,6,$total_iva,0,0,'R');
			$pdf->Cell(22,6," ",0,0,'R');
			$pdf->Cell(22,6,$df_retiva,0,0,'R');
  			$pdf->Cell(22,6,$df_retib_CABA,0,0,'R');
  			$pdf->Cell(22,6,$df_retib_BSAS,0,0,'R');
  			$pdf->Cell(22,6,$total,0,0,'R');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
			
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(60,6,"Contacto: ".$contacto,0,0,'L');
  			$pdf->Cell(50,6,"Mail: ".$mailcont,0,0,'L');
  			$pdf->Cell(50,6,"Tel.L�nea: ".$tellinea,0,0,'L');
  			$pdf->Cell(50,6,"Tel.Celular: ".$telcelu,0,0,'L');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
		else {
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
  			$pdf->Cell(26,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,"ANULADA",0,0,'L');
  			$pdf->Cell(24,6," ",0,0,'L');
  			$pdf->Cell(22,6," ",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
			$pdf->Cell(22,6," ",0,0,'R');
			$pdf->Cell(22,6,"0,00",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
  			$pdf->Cell(22,6,"0,00",0,0,'R');
		
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
			// ======================= HASTA ACA =====================================
		
			
	}
	
}	
// Imprimo totales generales de la hoja la �ltima vez 
// Imprimo subtotales de la hoja, uso otras variables porque el number_format
		// me jode los acumulados
$t_acum_total_neto  += $acum_total_neto;
$t_acum_total_iva   += $acum_total_iva;
$t_acum_tot_resol   += $acum_tot_resol;
$t_acum_total       += $acum_total;
$t_acum_df_retiva   += $acum_df_retiva;
$t_acum_df_retib_CABA += $acum_df_retib_CABA;
$t_acum_df_retib_BSAS += $acum_df_retib_BSAS;

$f_acum_total_neto  = number_format($acum_total_neto, 2, ',','.');
$f_acum_total_iva   = number_format($acum_total_iva, 2, ',','.');
$f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
$f_acum_total       = number_format($acum_total, 2, ',','.');
$f_acum_df_retiva   = number_format($acum_df_retiva, 2, ',','.');
$f_acum_df_retib_CABA = number_format($acum_df_retib_CABA, 2, ',','.');
$f_acum_df_retib_BSAS = number_format($acum_df_retib_BSAS, 2, ',','.');

$f_acum_df_neto25   = number_format($acum_df_neto25, 2, ',','.');
$f_acum_df_iva25    = number_format($acum_df_iva25, 2, ',','.');
$f_acum_df_neto50   = number_format($acum_df_neto50, 2, ',','.');
$f_acum_df_iva50    = number_format($acum_df_iva50, 2, ',','.');
$f_acum_df_neto105  = number_format($acum_df_neto105, 2, ',','.');
$f_acum_df_iva105   = number_format($acum_df_iva105, 2, ',','.');
$f_acum_df_neto210  = number_format($acum_df_neto210, 2, ',','.');
$f_acum_df_iva210   = number_format($acum_df_iva210, 2, ',','.');
$f_acum_df_neto270  = number_format($acum_df_neto270, 2, ',','.');
$f_acum_df_iva270   = number_format($acum_df_iva270, 2, ',','.');

		
$pdf->SetY($valor_y);
$pdf->Cell(54);
$pdf->Cell(58,6,"TOTAL LIQUIDACIONES  ",0,0,'L');
$pdf->Cell(22,6," ",0,0,'R');
$pdf->Cell(22,6,$f_acum_total_neto,0,0,'R');
$pdf->Cell(22,6,$f_acum_total_iva,0,0,'R');
$pdf->Cell(22,6," ",0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retiva,0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retib_CABA,0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retib_BSAS,0,0,'R');
$pdf->Cell(22,6,$f_acum_total,0,0,'R');

$valor_y = $valor_y + 6;

/*		
$t_acum_total_neto  = number_format($t_acum_total_neto, 2, ',','.');
$t_acum_total_iva   = number_format($t_acum_total_iva, 2, ',','.');
$t_acum_tot_resol   = number_format($t_acum_tot_resol, 2, ',','.');
$t_acum_total       = number_format($t_acum_total, 2, ',','.');
$t_acum_df_retiva   = number_format($t_acum_df_retiva, 2, ',','.');
$t_acum_df_retib_CABA = number_format($t_acum_df_retib_CABA, 2, ',','.');
$t_acum_df_retib_BSAS = number_format($t_acum_df_retib_BSAS, 2, ',','.');
$t_acum_total_exento = number_format($t_acum_total_exento, 2, ',','.');
		
$pdf->SetY($valor_y);
$pdf->Cell(54);
$pdf->Cell(58,6,"TOTAL GENERAL  ",0,0,'L');
$pdf->Cell(22,6,$t_acum_total_exento,0,0,'R');
$pdf->Cell(22,6,$t_acum_total_neto,0,0,'R');
$pdf->Cell(22,6,$t_acum_total_iva,0,0,'R');
$pdf->Cell(22,6," ",0,0,'R');
$pdf->Cell(22,6,$t_acum_df_retiva,0,0,'R');
$pdf->Cell(22,6,$t_acum_df_retib_CABA,0,0,'R');
$pdf->Cell(22,6,$t_acum_df_retib_BSAS,0,0,'R');
$pdf->Cell(22,6,$t_acum_total,0,0,'R');
// ACA IMPRIMO LOS NETOS  Y LOS IVAS POR CADA ALICUOTA DE IVA
$valor_y += 7;
$pdf->SetY($valor_y);
$pdf->Cell(54);
$pdf->Cell(58,6,"TOTAL NETO AL 2,5 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_neto25,0,0,'R');
//$valor_y += 6;
$pdf->SetY($valor_y);
$pdf->Cell(144);
$pdf->Cell(58,6,"TOTAL IVA AL 2,5 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_iva25,0,0,'R');
$valor_y += 6;
$pdf->SetY($valor_y);
$pdf->Cell(54);
$pdf->Cell(58,6,"TOTAL NETO AL 5 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_neto50,0,0,'R');
//$valor_y += 6;
$pdf->SetY($valor_y);
$pdf->Cell(144);
$pdf->Cell(58,6,"TOTAL IVA AL 5 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_iva50,0,0,'R');
$valor_y += 6;
$pdf->SetY($valor_y);
$pdf->Cell(54);
$pdf->Cell(58,6,"TOTAL NETO AL 10,5 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_neto105,0,0,'R');
//$valor_y += 6;
$pdf->SetY($valor_y);
$pdf->Cell(144);
$pdf->Cell(58,6,"TOTAL IVA AL 10,5 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_iva105,0,0,'R');
$valor_y += 6;
$pdf->SetY($valor_y);
$pdf->Cell(54);
$pdf->Cell(58,6,"TOTAL NETO AL 21 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_neto210,0,0,'R');
//$valor_y += 6;
$pdf->SetY($valor_y);
$pdf->Cell(144);
$pdf->Cell(58,6,"TOTAL IVA AL 21 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_iva210,0,0,'R');
$valor_y += 6;
$pdf->SetY($valor_y);
$pdf->Cell(54);
$pdf->Cell(58,6,"TOTAL NETO AL 27 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_neto270,0,0,'R');
//$valor_y += 6;
//$pdf->SetY($valor_y);
$pdf->Cell(144);
$pdf->Cell(58,6,"TOTAL IVA AL 27 % :  ",0,0,'L');
$pdf->Cell(22,6,$f_acum_df_iva270,0,0,'R');
*/

	
mysqli_close($amercado);
//mysqli_close($amercado);
$pdf->Output();
?>  
