<?php
set_time_limit(0); // Para evitar el timeout
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES
define('FC_PROV_A','32');
define('FC_PROV_C','33');
define('FC_PROV_M','65');
define('NC_PROV_M','87');
define('ND_PROV_M','88');
define('ND_PROV_A','34');
define('ND_PROV_C','35');
define('NC_PROV_A','36');
define('NC_PROV_C','37');
define('FC_PROV_EXT','107');
define('FC_PROV_LIQ','110');
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

// Leo los parámetros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");
$entidad =  $_POST['enti'];
//echo $entidad;
// Leo los renglones
// Leo la entidad para saber el tipo de entidad (cliente o proveedor)
// Leo el cliente
$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %d", $entidad);
$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDAD lin 57");
$row_entidades = mysqli_fetch_assoc($enti);
$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
$nro_cliente   = $row_entidades["numero"];
$cuit_cliente  = $row_entidades["cuit"];
$tipoent  = $row_entidades["tipoent"];
// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO IMPUESTOS lin 65");
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
 
$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN %s AND %s AND cliente = %s ORDER BY fecreg, nrodoc", $fecha_desde, $fecha_hasta, $entidad);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC lin 74");
if ($tipoent == 1) {
// Inicio el pdf con los datos de cabecera
  $pdf=new FPDF('L','mm','Legal');
  
  $pdf->AddPage();
  $pdf->SetMargins(0.5, 0.5 , 0.5);
  $pdf->SetFont('Arial','B',11);
  $pdf->SetY(5);
  $pdf->Cell(10);
  $pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
  $pdf->Cell(200);
  $pagina = $pdf->PageNo();
  $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
  $pdf->SetY(10);
  $pdf->Cell(230);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(130);
  $pdf->Cell(20,10,' Comprobantes de Clientes ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  $pdf->Cell(20,16,'    Fecha',1,0,'L');
  $pdf->Cell(28,16,' Nro.Factura',1,0,'L');
  $pdf->Cell(42,16,utf8_decode('       Razón Social'),1,0,'L');
  $pdf->Cell(22,16,'     CUIT',1,0,'L');
  $pdf->Cell(25,16,'    Conceptos   ',1,0,'L');
  $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
  $pdf->Cell(26,16,'    Conceptos ',1,0,'L');
  $pdf->Cell(26,16,'    Conceptos ',1,0,'L');
  $pdf->Cell(24,16,utf8_decode(' IVA Débito'),1,0,'L');
  $pdf->Cell(24,16,utf8_decode(' IVA Débito'),1,0,'L');
  $pdf->Cell(24,16,utf8_decode(' Percepción'),1,0,'L');
  $pdf->Cell(26,16,'Total Facturado',1,0,'L');
  $pdf->SetY(34);
  $pdf->Cell(116);
  $pdf->Cell(23,8,'    Exentos ',0,0,'L');
  $pdf->Cell(26,8,'             ',0,0,'L');
  $pdf->Cell(26,8,' Gravados 21% ',0,0,'L');
  $pdf->Cell(26,8,' Gravados 10,5%',0,0,'L');
  $pdf->Cell(24,8,'  Fiscal 21%',0,0,'L');
  $pdf->Cell(24,8,'  Fiscal 10,5%',0,0,'L');
  $pdf->Cell(24,8,utf8_decode('  o Retención'),0,0,'C');
  
  $valor_y = 45;
  
// Datos de los renglones
$i = 0;
$acum_tot_neto21  = 0;
$acum_tot_neto105 = 0;
$acum_tot_iva21   = 0;
$acum_tot_iva105  = 0;
$acum_tot_resol   = 0;
$acum_total       = 0;
$acum_totcomis    = 0;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	if ($tcomp !=  51 && $tcomp !=  52 && $tcomp !=  53 && $tcomp !=  54 && $tcomp != 55 && 
		$tcomp != 56 && $tcomp != 57 && $tcomp != 58 && $tcomp != 59 && $tcomp != 60 && 
		$tcomp != 61 && $tcomp != 62 &&	$tcomp != 63 && $tcomp != 64  && $tcomp != 65 && $tcomp != 89 && $tcomp != 92 && $tcomp != 93 && $tcomp != 103 && $tcomp != 104 && $tcomp != 105)
		continue;
	if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62  ) {
		$tc = "NC-";
		$signo = -1;
	}
	elseif ($tcomp == 59 ||  $tcomp == 60 || $tcomp == 63 || $tcomp == 64 ){
		$tc = "ND-";
		$signo = 1;
	}

	else {
		$tc = "FC-";
		$signo = 1;
	}
	if ($i <= 22) {
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		$tot_neto21   = $row_cabecerafac["totneto21"] ;
		$tot_neto105  = $row_cabecerafac["totneto105"];
		$tot_comision = $row_cabecerafac["totcomis"];
		$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
		$tot_iva105   = $row_cabecerafac["totiva105"];
		$tot_resol    = $row_cabecerafac["totimp"];
		$total        = $row_cabecerafac["totbruto"];
		$nroorig      = $row_cabecerafac["nrodoc"];

		$estado = "P";
	
		// Acumulo subtotales
		if ($estado != "A") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 ) {
				// resto Notas de Crédito
				$acum_tot_neto21  = $acum_tot_neto21  - $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 - $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   - $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  - $tot_iva105;
				$acum_tot_resol   = $acum_tot_resol   - $tot_resol;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
			}
			else {
				// Sumo Facturas y Notas de Débito
				$acum_tot_neto21  = $acum_tot_neto21  + $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 + $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   + $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  + $tot_iva105;
				$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
				$acum_total       = $acum_total       + $total;
				$acum_totcomis    = $acum_totcomis    + $tot_comision;
					
			}
	
			$tot_neto21   = number_format($tot_neto21*$signo, 2, ',','.');
			$tot_neto105  = number_format($tot_neto105*$signo, 2, ',','.');
			$tot_iva21    = number_format($tot_iva21*$signo, 2, ',','.');
			$tot_iva105   = number_format($tot_iva105*$signo, 2, ',','.');
			$tot_resol    = number_format($tot_resol*$signo, 2, ',','.');
			$tot_comision = number_format($tot_comision*$signo, 2, ',','.');
			$total        = number_format($total*$signo, 2, ',','.');
	
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES 206");
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  	
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
			$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(30,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,utf8_decode($nom_cliente),0,0,'L');
  			$pdf->Cell(22,6,$cuit_cliente,0,0,'L');
  			$pdf->Cell(23,6,"0,00",0,0,'R');
			$pdf->Cell(26,6,$tot_comision,0,0,'R');
  			$pdf->Cell(26,6,$tot_neto21,0,0,'R');
  			$pdf->Cell(26,6,$tot_neto105,0,0,'R');
  			$pdf->Cell(24,6,$tot_iva21,0,0,'R');
  			$pdf->Cell(24,6,$tot_iva105,0,0,'R');
			$pdf->Cell(24,6,$tot_resol,0,0,'R');
  			$pdf->Cell(26,6,$total,0,0,'R');
		
		
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
		else {
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
  	    	$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(30,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,"ANULADA",0,0,'L');
  			$pdf->Cell(22,6," ",0,0,'L');
  			$pdf->Cell(23,6,"0,00",0,0,'R');
			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(24,6,"0,00",0,0,'R');
  			$pdf->Cell(24,6,"0,00",0,0,'R');
			$pdf->Cell(24,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
	}
	else {
		// Imprimo subtotales de la hoja, uso otras variables porque el number_format
		// me jode los acumulados
		$f_acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
		$f_acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
		$f_acum_tot_iva21   = number_format($acum_tot_iva21, 2, ',','.');
		$f_acum_tot_iva105  = number_format($acum_tot_iva105, 2, ',','.');
		$f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
		$f_acum_total       = number_format($acum_total, 2, ',','.');
		$f_acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
		
		$pdf->SetY($valor_y);
		$pdf->Cell(116);
		$pdf->Cell(26,6,"0,00",0,0,'R');
		$pdf->Cell(26,6,$f_acum_totcomis,0,0,'R');
		$pdf->Cell(26,6,$f_acum_tot_neto21,0,0,'R');
  		$pdf->Cell(26,6,$f_acum_tot_neto105,0,0,'R');
  		$pdf->Cell(24,6,$f_acum_tot_iva21,0,0,'R');
  		$pdf->Cell(24,6,$f_acum_tot_iva105,0,0,'R');
		$pdf->Cell(24,6,$f_acum_tot_resol,0,0,'R');
  		$pdf->Cell(26,6,$f_acum_total,0,0,'R');
		
		// Voy a otra hoja e imprimo los titulos 
		$pdf->AddPage();
		$pdf->SetMargins(0.5, 0.5 , 0.5);
  		$pdf->SetFont('Arial','B',11);
		$pdf->SetY(5);
		$pdf->Cell(10);
		$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
		$pdf->Cell(200);
		$pagina = $pdf->PageNo();
		$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
		$pdf->SetY(10);
		$pdf->Cell(230);
		$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
		$pdf->SetY(15);
		$pdf->Cell(130);
		$pdf->Cell(20,10,' Comprobantes de Clientes ',0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(3);
		$pdf->Cell(20,16,'    Fecha',1,0,'L');
		$pdf->Cell(28,16,' Nro.Factura',1,0,'L');
		$pdf->Cell(42,16,utf8_decode('       Razón Social'),1,0,'L');
		$pdf->Cell(22,16,'     CUIT',1,0,'L');
		$pdf->Cell(25,16,'    Conceptos   ',1,0,'L');
		$pdf->Cell(26,16,'   Comisiones ',1,0,'L');
		$pdf->Cell(26,16,'    Conceptos ',1,0,'L');
		$pdf->Cell(26,16,'    Conceptos ',1,0,'L');
		$pdf->Cell(24,16,' IVA ',1,0,'L');
		$pdf->Cell(24,16,' IVA ',1,0,'L');
		$pdf->Cell(24,16,utf8_decode(' Percepción'),1,0,'L');
		$pdf->Cell(26,16,'Total Facturado',1,0,'L');
		$pdf->SetY(34);
		$pdf->Cell(116);
		$pdf->Cell(23,8,'    Exentos ',0,0,'C');
		$pdf->Cell(26,8,'             ',0,0,'C');
		$pdf->Cell(26,8,' Gravados 21% ',0,0,'C');
		$pdf->Cell(26,8,' Gravados 10,5%',0,0,'C');
		$pdf->Cell(24,8,'  Fiscal 21%',0,0,'C');
		$pdf->Cell(24,8,'  Fiscal 10,5%',0,0,'C');
		$pdf->Cell(24,8,utf8_decode('  o Retención'),0,0,'C');
  
		$valor_y = 45;
		
		$i = 0;
		// ACA TENGO QUE IMPRIMIR EL REGISTRO QUE ACABO DE LEER PORQUE SI NO LO PIERDO CUANDO VUELVO AL WHILE
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		$tot_neto21   = $row_cabecerafac["totneto21"] ;
		$tot_neto105  = $row_cabecerafac["totneto105"];
		$tot_comision = $row_cabecerafac["totcomis"];
		$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
		$tot_iva105   = $row_cabecerafac["totiva105"];
		$tot_resol    = $row_cabecerafac["totimp"];
		$total        = $row_cabecerafac["totbruto"];
		$nroorig      = $row_cabecerafac["nrodoc"];
		// LE AGREGO LA CLAVE PORQUE ME PARECE QUE LA PIERDE
		$tcomp       = $row_cabecerafac["tcomp"];
		$serie       = $row_cabecerafac["serie"];
		$ncomp       = $row_cabecerafac["ncomp"];

		$estado = "P";

		// Acumulo subtotales
		if ($estado != "A") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 ) {
				// resto Notas de Crédito
				$acum_tot_neto21  = $acum_tot_neto21  - $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 - $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   - $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  - $tot_iva105;
				$acum_tot_resol   = $acum_tot_resol   - $tot_resol;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
			}
			else {
				// Sumo Facturas y Notas de Débito
				$acum_tot_neto21  = $acum_tot_neto21  + $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 + $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   + $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  + $tot_iva105;
				$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
				$acum_total       = $acum_total       + $total;
				$acum_totcomis    = $acum_totcomis    + $tot_comision;
					
			}
	
			$tot_neto21   = number_format($tot_neto21*$signo, 2, ',','.');
			$tot_neto105  = number_format($tot_neto105*$signo, 2, ',','.');
			$tot_iva21    = number_format($tot_iva21*$signo, 2, ',','.');
			$tot_iva105   = number_format($tot_iva105*$signo, 2, ',','.');
			$tot_resol    = number_format($tot_resol*$signo, 2, ',','.');
			$tot_comision = number_format($tot_comision*$signo, 2, ',','.');
			$total        = number_format($total*$signo, 2, ',','.');
	
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES 354");
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  	
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
			$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(30,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,utf8_decode($nom_cliente),0,0,'L');
  			$pdf->Cell(22,6,$cuit_cliente,0,0,'L');
  			$pdf->Cell(23,6,"0,00",0,0,'R');
			$pdf->Cell(26,6,$tot_comision,0,0,'R');
  			$pdf->Cell(26,6,$tot_neto21,0,0,'R');
  			$pdf->Cell(26,6,$tot_neto105,0,0,'R');
  			$pdf->Cell(24,6,$tot_iva21,0,0,'R');
  			$pdf->Cell(24,6,$tot_iva105,0,0,'R');
			$pdf->Cell(24,6,$tot_resol,0,0,'R');
  			$pdf->Cell(26,6,$total,0,0,'R');
		
		
			
			$valor_y = $valor_y + 6;
		}
		// desde aca el else
		else {
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
  	    	$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(30,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,"ANULADA",0,0,'L');
  			$pdf->Cell(22,6," ",0,0,'L');
  			$pdf->Cell(23,6,"0,00",0,0,'R');
			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
  			$pdf->Cell(24,6,"0,00",0,0,'R');
  			$pdf->Cell(24,6,"0,00",0,0,'R');
			$pdf->Cell(24,6,"0,00",0,0,'R');
  			$pdf->Cell(26,6,"0,00",0,0,'R');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
    }
}
// Imprimo subtotales de la hoja la última vez
$acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
$acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
$acum_tot_iva21   = number_format($acum_tot_iva21, 2, ',','.');
$acum_tot_iva105  = number_format($acum_tot_iva105, 2, ',','.');
$acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
$acum_total       = number_format($acum_total, 2, ',','.');
$acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
		
$pdf->SetY($valor_y);
$pdf->Cell(116);
$pdf->Cell(26,6,"0,00",0,0,'R');
$pdf->Cell(26,6,$acum_totcomis,0,0,'R');
$pdf->Cell(26,6,$acum_tot_neto21,0,0,'R');
$pdf->Cell(26,6,$acum_tot_neto105,0,0,'R');
$pdf->Cell(24,6,$acum_tot_iva21,0,0,'R');
$pdf->Cell(24,6,$acum_tot_iva105,0,0,'R');
$pdf->Cell(24,6,$acum_tot_resol,0,0,'R');
$pdf->Cell(26,6,$acum_total,0,0,'R');

}
else {
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
  $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
  $pdf->SetY(10);
  $pdf->Cell(230);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(130);
  $pdf->Cell(20,10,' Comprobantes de Proveedores ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  $pdf->Cell(20,16,'    Fecha',1,0,'L');
  //$pdf->SetY(18);
  //$pdf->Cell(5);
  $pdf->Cell(28,16,' Nro.Factura',1,0,'L');
  $pdf->Cell(42,16,utf8_decode('       Razón Social'),1,0,'L');
  $pdf->Cell(24,16,'     CUIT',1,0,'L');
  $pdf->Cell(22,16,'Conceptos ',1,0,'L');
  $pdf->Cell(22,16,'Conceptos ',1,0,'L');
  $pdf->Cell(22,16,utf8_decode(' IVA Crédito'),1,0,'L');
  $pdf->Cell(22,16,utf8_decode(' Alícuota'),1,0,'L');
  $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
  $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
  $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
  $pdf->Cell(22,16,'   Total ',1,0,'L');
  $pdf->SetY(34);
  $pdf->Cell(118);
  $pdf->Cell(22,8,' Exentos ',0,0,'L');
  $pdf->Cell(22,8,' Gravados  ',0,0,'L');
  $pdf->Cell(22,8,'Fiscal ',0,0,'L');
  $pdf->Cell(22,8,'Diferencial',0,0,'L');
  $pdf->Cell(22,8,'    IVA',0,0,'L');
  $pdf->Cell(22,8,'   IIBB',0,0,'L');
  $pdf->Cell(22,8,' Ganancias',0,0,'L');
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
$acum_df_retib = 0;
$acum_df_retgan = 0;
$df_retiva   = 0.0;
$df_retib    = 0.0;
$df_retgan   = 0.0;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	if ($tcomp != FC_PROV_A && $tcomp != FC_PROV_C  && $tcomp != FC_PROV_M && $tcomp != ND_PROV_A && $tcomp != ND_PROV_C && $tcomp != NC_PROV_A && $tcomp != NC_PROV_C && $tcomp != FC_PROV_M && $tcomp != NC_PROV_M && $tcomp != ND_PROV_M && $tcomp != FC_PROV_EXT && $tcomp != FC_PROV_LIQ)
		continue;
	if ($tcomp == FC_PROV_A || $tcomp == FC_PROV_C || $tcomp == ND_PROV_A || $tcomp == ND_PROV_C|| $tcomp == FC_PROV_EXT  || $tcomp == FC_PROV_M || $tcomp == ND_PROV_M || $tcomp == FC_PROV_LIQ) {
		$signo = 1;
	}
	else {
		$signo = -1;
	}
	if ($i < 22) {
		$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
		$detallefac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC lin 510");
		$totalRows_detallefac = mysqli_num_rows($detallefac);
		$df_concnograv  = 0.00;
		$df_retib  = 0.00;
		$df_retgan = 0.00;
		$df_retiva = 0.00;
		while($row_detallefac = mysqli_fetch_array($detallefac)) {
			$concafac = $row_detallefac["concafac"];
			if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA 
						 || $concafac == RET_GAN || $concafac == CONC_NO_GRAV) {
				switch($concafac) {
					case	CONC_NO_GRAV:
						//$df_retiva = "0,00";
						$df_concnograv += $row_detallefac["neto"] * $signo;
						//$df_retib  = "0,00";
						//$df_retgan = "0,00";
						break;
					case	RET_IVA:
						$df_retiva += $row_detallefac["neto"] * $signo;
						//$df_concnograv = "0,00";
						//$df_retib  = "0,00";
						//$df_retgan = "0,00";
						break;
					case 	RET_IIBB_BA:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						$df_retib  += $row_detallefac["neto"] * $signo;
						//$df_retgan = "0,00";
						break;
					case 	RET_IIBB_CABA:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						$df_retib  += $row_detallefac["neto"] * $signo;
						//$df_retgan = "0,00";
						break;

					case	RET_GAN:
						
						$df_retgan += $row_detallefac["neto"] * $signo;
						break;
				}
			}
			else {
				//if ($row_detallefac["porciva"]==0)
				//	$df_concnograv += $row_detallefac["neto"] * $signo;
				continue;
			}
		}
		
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		
		$tot_neto     = ($row_cabecerafac["totneto"] - $df_retgan - $df_retib - $df_retiva) * $signo;
			
		$tot_neto21   = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) ;
		$tot_neto105  = $row_cabecerafac["totneto105"] ;
		$tot_comision = $row_cabecerafac["totcomis"] * $signo;
		$tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21 ;
		$tot_iva105   = $row_cabecerafac["totiva105"] ;
		$tot_resol    = $row_cabecerafac["totimp"] * $signo;
		$total        = $row_cabecerafac["totbruto"] * $signo;
		$nroorig      = $row_cabecerafac["nrodoc"];
		$total_neto   = $tot_neto * $signo; // ($tot_neto21 + $tot_neto105)  * $signo;
		$total_iva    = ($tot_iva21  + $tot_iva105) * $signo;
		$total_exento = 0;
		if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C) {
			$total_exento = $row_cabecerafac["totneto"] * $signo;
            $total_neto   = 0.00;
        }
		if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C)
			$total_exento += $df_concnograv;
		$total_neto   = ($tot_neto - $total_exento) * $signo;
		// Acumulo subtotales
		$acum_total_neto  = $acum_total_neto  + $total_neto;
		$acum_total_iva   = $acum_total_iva   + $total_iva;
		$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
		$acum_total       = $acum_total       + $total;
		$acum_df_retiva   = $acum_df_retiva   + $df_retiva;
		$acum_df_retib    = $acum_df_retib    + $df_retib;
		$acum_df_retgan   = $acum_df_retgan   + $df_retgan;
		$acum_total_exento  = $acum_total_exento  + $total_exento;
	
		// Formateo los campos antes de imprimir
		$total_neto    = number_format($total_neto, 2, ',','.');
		$total_iva     = number_format($total_iva, 2, ',','.');
		$tot_resol     = number_format($tot_resol, 2, ',','.');
		$total         = number_format($total, 2, ',','.');
		$df_retiva     = number_format($df_retiva, 2, ',','.');
		$df_retib      = number_format($df_retib, 2, ',','.');
		$df_retgan     = number_format($df_retgan, 2, ',','.');
		$total_exento  = number_format($total_exento, 2, ',','.');
	
		// Leo el cliente
  		$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  		$enti = mysqli_query( $query_entidades,$amercado) or die("ERROR LEYENDO ENTIDADES 620");
  		$row_entidades = mysqli_fetch_assoc($enti);
  		$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  		$nro_cliente   = $row_entidades["numero"];
  		$cuit_cliente  = $row_entidades["cuit"];
  	
		// Imprimo los renglones
		$pdf->SetY($valor_y);
  		$pdf->Cell(1);
  		$pdf->Cell(19,6,$fecha,0,0,'L');
  		$pdf->Cell(30,6,$nroorig,0,0,'L');
  		$pdf->Cell(42,6,$nom_cliente,0,0,'L');
  		$pdf->Cell(24,6,$cuit_cliente,0,0,'L');
  		$pdf->Cell(22,6,$total_exento,0,0,'R');
  		$pdf->Cell(22,6,$total_neto,0,0,'R');
  		$pdf->Cell(22,6,$total_iva,0,0,'R');
		$pdf->Cell(22,6," ",0,0,'R');
		$pdf->Cell(22,6,$df_retiva,0,0,'R');
  		$pdf->Cell(22,6,$df_retib,0,0,'R');
  		$pdf->Cell(22,6,$df_retgan,0,0,'R');
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
		$f_acum_df_retib    = number_format($acum_df_retib, 2, ',','.');
		$f_acum_df_retgan   = number_format($acum_df_retgan, 2, ',','.');
		$f_acum_total_exento  = number_format($acum_total_exento, 2, ',','.');
		
		$pdf->SetY($valor_y);
		$pdf->Cell(116);
		$pdf->Cell(22,6,$f_acum_total_exento,0,0,'R');
		$pdf->Cell(22,6,$f_acum_total_neto,0,0,'R');
		$pdf->Cell(22,6,$f_acum_total_iva,0,0,'R');
		$pdf->Cell(22,6," ",0,0,'R');
		$pdf->Cell(22,6,$f_acum_df_retiva,0,0,'R');
  		$pdf->Cell(22,6,$f_acum_df_retib,0,0,'R');
  		$pdf->Cell(22,6,$f_acum_df_retgan,0,0,'R');
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
		$pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
		$pdf->SetY(10);
		$pdf->Cell(230);
		$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
		$pdf->SetY(15);
		$pdf->Cell(130);
		$pdf->Cell(20,10,' Comprobantes de Proveedores ',0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(3);
		$pdf->Cell(20,16,'    Fecha',1,0,'L');
		//$pdf->SetY(18);
		//$pdf->Cell(5);
		$pdf->Cell(28,16,' Nro.Factura',1,0,'L');
		$pdf->Cell(42,16,utf8_decode('       Razón Social'),1,0,'L');
		$pdf->Cell(24,16,'     CUIT',1,0,'L');
		$pdf->Cell(22,16,' Conceptos ',1,0,'L');
		$pdf->Cell(22,16,' Conceptos ',1,0,'L');
		$pdf->Cell(22,16,utf8_decode(' IVA Crédito'),1,0,'L');
		$pdf->Cell(22,16,utf8_decode(' Alícuota'),1,0,'L');
		$pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
		$pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
		$pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
		$pdf->Cell(22,16,'   Total ',1,0,'L');
		$pdf->SetY(34);
		$pdf->Cell(118);
		$pdf->Cell(22,8,'Exentos ',0,0,'L');
		$pdf->Cell(22,8,'Gravados',0,0,'L');
		$pdf->Cell(22,8,'Fiscal ',0,0,'L');
		$pdf->Cell(22,8,'Diferencial',0,0,'L');
		$pdf->Cell(22,8,'    IVA',0,0,'L');
		$pdf->Cell(22,8,'   IIBB',0,0,'L');
		$pdf->Cell(22,8,' Ganancias',0,0,'L');
		$pdf->Cell(22,8,' Facturado',0,0,'L');
  
		$valor_y = 45;
		// reinicio los contadores
		
		$i = 0;
		//================================================================================
		$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
		$detallefac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC");
		$totalRows_detallefac = mysqli_num_rows($detallefac);
		$df_concnograv = 0.0;
		$df_retib  = 0.0;
		$df_retgan = 0.0;
		$df_retiva = 0.0;
		while($row_detallefac = mysqli_fetch_array($detallefac)) {
			$concafac = $row_detallefac["concafac"];
			if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA ||  $concafac == RET_GAN || $concafac == CONC_NO_GRAV) {
				switch($concafac) {
					case	CONC_NO_GRAV:
						//$df_retiva = "0,00";
						$df_concnograv = $row_detallefac["neto"] * $signo;
						//$df_retib  = "0,00";
						//$df_retgan = "0,00";
						break;
					case	RET_IVA:
						$df_retiva = $row_detallefac["neto"] * $signo;
						//$df_concnograv = "0,00";
						//$df_retib  = "0,00";
						//$df_retgan = "0,00";
						break;
					case 	RET_IIBB_BA:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						$df_retib  += $row_detallefac["neto"] * $signo;
						//$df_retgan = "0,00";
						break;
					case 	RET_IIBB_CABA:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						$df_retib  += $row_detallefac["neto"] * $signo;
						//$df_retgan = "0,00";
						break;

					case	RET_GAN:
						//$df_retiva = "0,00";
						//$df_concnograv = "0,00";
						//$df_retib  = "0,00";
						$df_retgan = $row_detallefac["neto"] * $signo;
						break;
				}
			}
			else {
				//if ($row_detallefac["porciva"]==0)
				//	$df_concnograv += $row_detallefac["neto"] * $signo;
				continue;
			}
		}
		
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		//if ($row_cabecerafac["totneto"] > 0)
			$tot_neto     = $row_cabecerafac["totneto"] * $signo;
		//else
		//	$tot_neto     = $row_cabecerafac["totbruto"] * $signo;
		$tot_neto21   = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"])  ;
		$tot_neto105  = $row_cabecerafac["totneto105"] ;
		$tot_comision = $row_cabecerafac["totcomis"] * $signo;
		$tot_iva21    = $row_cabecerafac["totiva21"] ; //($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
		$tot_iva105   = $row_cabecerafac["totiva105"] ;
		$tot_resol    = $row_cabecerafac["totimp"] * $signo;
		$total        = $row_cabecerafac["totbruto"] * $signo;
		$nroorig      = $row_cabecerafac["nrodoc"];
		$total_neto   = $tot_neto * $signo; // ($tot_neto21 + $tot_neto105)  * $signo;
		//$total_neto   = ($tot_neto21 + $tot_neto105)  * $signo;
		$total_iva    = ($tot_iva21  + $tot_iva105)  * $signo;
		$total_exento = 0;
		if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C) {
			$total_exento += $row_cabecerafac["totneto"] * $signo;
            $total_neto   = 0.00;
        }
		if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C)
			$total_exento += $df_concnograv;
		
		// Acumulo subtotales
		$acum_total_neto  = $acum_total_neto  + $total_neto;
		$acum_total_iva   = $acum_total_iva   + $total_iva;
		$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
		$acum_total       = $acum_total       + $total;
		$acum_df_retiva   = $acum_df_retiva   + $df_retiva;
		$acum_df_retib    = $acum_df_retib    + $df_retib;
		$acum_df_retgan   = $acum_df_retgan   + $df_retgan;
		$acum_total_exento  = $acum_total_exento  + $total_exento;
	
		// Formateo los campos antes de imprimir
		$total_neto  = number_format($total_neto, 2, ',','.');
		$total_iva   = number_format($total_iva, 2, ',','.');
		$tot_resol   = number_format($tot_resol, 2, ',','.');
		$total       = number_format($total, 2, ',','.');
		$df_retiva   = number_format($df_retiva, 2, ',','.');
		$df_retib    = number_format($df_retib, 2, ',','.');
		$df_retgan   = number_format($df_retgan, 2, ',','.');
		$total_exento  = number_format($total_exento, 2, ',','.');
	
		// Leo el cliente
  		$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  		$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDAD 832");
  		$row_entidades = mysqli_fetch_assoc($enti);
  		$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  		$nro_cliente   = $row_entidades["numero"];
  		$cuit_cliente  = $row_entidades["cuit"];
  	
		// Imprimo los renglones
		$pdf->SetY($valor_y);
  		$pdf->Cell(1);
  		$pdf->Cell(19,6,$fecha,0,0,'L');
  		$pdf->Cell(30,6,$nroorig,0,0,'L');
  		$pdf->Cell(42,6,utf8_decode($nom_cliente),0,0,'L');
  		$pdf->Cell(24,6,$cuit_cliente,0,0,'L');
  		$pdf->Cell(22,6,$total_exento,0,0,'R');
  		$pdf->Cell(22,6,$total_neto,0,0,'R');
  		$pdf->Cell(22,6,$total_iva,0,0,'R');
		$pdf->Cell(22,6," ",0,0,'R');
		$pdf->Cell(22,6,$df_retiva,0,0,'R');
  		$pdf->Cell(22,6,$df_retib,0,0,'R');
  		$pdf->Cell(22,6,$df_retgan,0,0,'R');
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
$t_acum_df_retib    = $acum_df_retib;
$t_acum_df_retgan   = $acum_df_retgan;
$t_acum_total_exento  = $acum_total_exento;
		
$f_acum_total_neto  = number_format($acum_total_neto, 2, ',','.');
$f_acum_total_iva   = number_format($acum_total_iva, 2, ',','.');
$f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');

$f_acum_total       = number_format($acum_total, 2, ',','.');
$f_acum_df_retiva   = number_format($acum_df_retiva, 2, ',','.');
$f_acum_df_retib    = number_format($acum_df_retib, 2, ',','.');
$f_acum_df_retgan   = number_format($acum_df_retgan, 2, ',','.');
$f_acum_total_exento   = number_format($acum_total_exento, 2, ',','.');

$t_acum_total_neto  = number_format($acum_total_neto, 2, ',','.');
$t_acum_total_iva   = number_format($acum_total_iva, 2, ',','.');
$t_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');

$t_acum_total       = number_format($acum_total, 2, ',','.');
$t_acum_df_retiva   = number_format($acum_df_retiva, 2, ',','.');
$t_acum_df_retib    = number_format($acum_df_retib, 2, ',','.');
$t_acum_df_retgan   = number_format($acum_df_retgan, 2, ',','.');
$t_acum_total_exento   = number_format($acum_total_exento, 2, ',','.');    

$pdf->SetY($valor_y);
$pdf->Cell(58);
$pdf->Cell(58,6,"TOTAL FACTURAS, NCRED. Y NDEB. ",0,0,'L');
$pdf->Cell(22,6,$f_acum_total_exento,0,0,'R');
$pdf->Cell(22,6,$f_acum_total_neto,0,0,'R');
$pdf->Cell(22,6,$f_acum_total_iva,0,0,'R');
$pdf->Cell(22,6," ",0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retiva,0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retib,0,0,'R');
$pdf->Cell(22,6,$f_acum_df_retgan,0,0,'R');
$pdf->Cell(22,6,$f_acum_total,0,0,'R');
$valor_y = $valor_y + 12;
$i = $i + 2;
// $i = 0 ;

		
$pdf->SetY($valor_y);
$pdf->Cell(58);
$pdf->Cell(58,6,"TOTAL GENERAL  ",0,0,'L');
$pdf->Cell(22,6,$t_acum_total_exento,0,0,'R');
$pdf->Cell(22,6,$t_acum_total_neto,0,0,'R');
$pdf->Cell(22,6,$t_acum_total_iva,0,0,'R');
$pdf->Cell(22,6," ",0,0,'R');
$pdf->Cell(22,6,$t_acum_df_retiva,0,0,'R');
$pdf->Cell(22,6,$t_acum_df_retib,0,0,'R');
$pdf->Cell(22,6,$t_acum_df_retgan,0,0,'R');
$pdf->Cell(22,6,$t_acum_total,0,0,'R');

}
		
mysqli_close($amercado);
$pdf->Output();
?>  
