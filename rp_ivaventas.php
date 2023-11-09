<?php
//set_time_limit(0); // Para evitar el timeout
include_once "funcion_mysqli_result.php";
define('FPDF_FONTPATH','fpdf17/font/');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los parámetros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];
$salida = $_POST['GrupoOpciones1'];

if ($salida == 1) {
    $fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
    $fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

    $fechahoy = date("d-m-Y");
    
    // Traigo impuestos
    $query_impuestos= "SELECT * FROM impuestos";
    $impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO IMPUESTOS ".$query_impuestos." ");
    $row_Recordset2 = mysqli_fetch_assoc($impuestos);
    $totalRows_Recordset2 = mysqli_num_rows($impuestos);
    $porc_iva105 = 10.5; //(mysqli_result($impuestos,1, 1)/100); 
    $porc_iva21 = 21; //(mysqli_result($impuestos,0, 1)/100);

    // Leo la cabecera

    $query_cabfac = sprintf("SELECT * FROM cabfac WHERE CAE is not null AND fecreg BETWEEN %s AND %s ORDER BY fecreg, nrodoc", $fecha_desde, $fecha_hasta);
    $cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC ".$query_cabfac." ");

    // Inicio el pdf con los datos de cabecera
    $pdf=new FPDF('L','mm','Legal');
    
    $pdf->AddPage();
    $pdf->SetMargins(0.5, 0.5 , 0.5);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetY(5);
    $pdf->Cell(10);
    $pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
    $pdf->Cell(200);
    $pagina = $pdf->PageNo();
    $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
    $pdf->SetY(10);
    $pdf->Cell(230);
    $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
    $pdf->SetY(15);
    $pdf->Cell(130);
    $pdf->Cell(20,10,' Libro IVA Ventas ',0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->SetY(25);
    $pdf->Cell(3);
    $pdf->Cell(20,16,'    Fecha',1,0,'L');
    $pdf->Cell(28,16,' Nro.Factura',1,0,'L');
    $pdf->Cell(42,16,utf8_decode('       Razón Social'),1,0,'L');
    $pdf->Cell(22,16,'     CUIT',1,0,'L');
    $pdf->Cell(23,16,'    Conceptos ',1,0,'L');
    $pdf->Cell(26,16,'Uso Plataforma',1,0,'L');
    $pdf->Cell(26,16,'    Lotes  ',1,0,'L');
    $pdf->Cell(26,16,'    Lotes  ',1,0,'L');
    $pdf->Cell(24,16,utf8_decode(' IVA Débito'),1,0,'L');
    $pdf->Cell(24,16,utf8_decode(' IVA Débito'),1,0,'L');
    $pdf->Cell(24,16,' Gs. Adm.',1,0,'L');
	$pdf->Cell(24,16,' Publicidad',1,0,'L');
    $pdf->Cell(26,16,'Total Facturado',1,0,'L');
    $pdf->SetY(34);
    $pdf->Cell(114);
    $pdf->Cell(23,8,'    Exentos ',0,0,'L');
    $pdf->Cell(26,8,'             ',0,0,'L');
    $pdf->Cell(26,8,' Gravados 21% ',0,0,'L');
    $pdf->Cell(26,8,' Gravados 10,5%',0,0,'L');
    $pdf->Cell(24,8,' Fiscal 21%',0,0,'L');
    $pdf->Cell(24,8,' Fiscal 10,5%',0,0,'L');
    $pdf->Cell(24,8,'        ',0,0,'L');

    $valor_y = 45;

    // Datos de los renglones
    $i = 0;
    $acum_tot_neto21  = 0;
    $acum_tot_exento  = 0;
    $acum_tot_neto105 = 0;
    $acum_tot_iva21   = 0;
    $acum_tot_iva105  = 0;
	$acum_tot_public  = 0;
    $acum_tot_resol   = 0;
    $acum_total       = 0;
    $acum_totcomis    = 0;
	$acum_totservicios    = 0;
	$acum_totpublic   = 0;
    $pri_vez = 0;
    $lo_imprimi = 0;
    $tcompant = $ncompant = 0;
    while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
    {	
        $tcomp      = $row_cabecerafac["tcomp"];
        $serie      = $row_cabecerafac["serie"];
        $ncomp      = $row_cabecerafac["ncomp"];
        if ($tcomp == $tcompant && $ncomp == $ncompant)
            continue;
        $tcompant   = $tcomp;
        $ncompant   = $ncomp;
                
        if ($tcomp ==  119 ||  $tcomp ==  120 || $tcomp == 121 || $tcomp == 135) {
            $tc = "NC-";
            $signo = -1;
        }
        elseif ($tcomp == 122 ||  $tcomp == 123 || $tcomp == 124){
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
            if (($tcomp == 119 || $tcomp == 120 || $tcomp == 121 || $tcomp == 122 || $tcomp == 123 || $tcomp == 124) && ($row_cabecerafac["totiva21"] == 0.00 &&  $row_cabecerafac["totiva105"] == 0.00)) {
                $tot_neto21   = 0.00;
                $tot_neto105  = 0.00;
                $tot_exento   = $row_cabecerafac["totneto21"] + $row_cabecerafac["totneto"];
                $tot_comision   = 0.00;
				$tot_servicios   = 0.00;
                //if ($tcomp == 57 && $ncomp == 1918)
                 //   echo " LINEA 123 ";
            }
            else if (($tcomp == 135 ) && ($row_cabecerafac["totiva21"] == 0.00 &&  $row_cabecerafac["totiva105"] == 0.00))  {
                $tot_exento   = $row_cabecerafac["totneto"] ;
                $tot_neto21   = 0.00; 
                $tot_neto105  = 0.00; 
                $tot_comision   = 0.00;
                $tot_resol    = 0.00;
				$tot_servicios    = 0.00;
            }
            else if ($tcomp == 125 || $tcomp == 126 || $tcomp == 127 || $tcomp == 133 || $tcomp == 134) {
				//Aca leo detfac para ver el concepto facturado
				$query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                //$row_detfac = mysqli_fetch_assoc($detfac);
                $tot_public   = 0.00;
				$tot_gastos   = 0.00;
				$tot_neto21      = 0.00;
				$tot_neto105     = 0.00;
                $tot_comision = 0.00;
				$tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac, MYSQLI_BOTH)) {
                    if ($row_detfac["concafac"] == 3)
                        $tot_public   += $row_detfac["neto"];
					
                    if ($row_detfac["concafac"] == 5)
                        $tot_comision += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 1 || $row_detfac["concafac"] == 6 || $row_detfac["concafac"] == 7 || $row_detfac["concafac"] == 8 || $row_detfac["concafac"] == 9)
				        $tot_servicios += $row_detfac["neto"];
                }
                
                $tot_neto21   = 0.00; //$row_cabecerafac["totneto21"] ;
                $tot_neto105  = 0.00; //$row_cabecerafac["totneto105"];
                $tot_exento   = $row_cabecerafac["totneto"];
                //$tot_servicios    = $row_cabecerafac["totimp"];
            } else if ($tcomp == 119 || $tcomp == 120 || $tcomp == 121  || $tcomp == 135) {
				//Aca leo detfac para ver el concepto facturado
				$query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                //$row_detfac = mysqli_fetch_assoc($detfac);
                $tot_public   = 0.00;
				$tot_gastos   = 0.00;
				$tot_neto21     = 0.00;
				$tot_neto105     = 0.00;
                $tot_comision = 0.00;
				$tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac, MYSQLI_BOTH)) {
                    if ($row_detfac["concafac"] == 13 || $row_detfac["concafac"] == 16 || $row_detfac["concafac"] == 34)
                        $tot_neto105   += $row_detfac["neto"];
					
                    if ($row_detfac["concafac"] == 14  || $row_detfac["concafac"] == 17 || $row_detfac["concafac"] == 35)
                        $tot_neto21 += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 18 || $row_detfac["concafac"] == 21)
				        $tot_servicios += $row_detfac["neto"];
					if ($row_detfac["concafac"] == 19)
				        $tot_comision += $row_detfac["neto"];
					if ($row_detfac["concafac"] == 43 || $row_detfac["concafac"] == 71)
                        $tot_gastos   += $row_detfac["neto"];
					if ($row_detfac["concafac"] == 33)
				        $tot_public += $row_detfac["neto"];
                }
				if ($tot_comision == 0)
                	$tot_comision = $row_cabecerafac["totcomis"];
                
            } else {
                $tot_comision = $row_cabecerafac["totcomis"];
                $tot_neto21   = $row_cabecerafac["totneto21"] ;
                $tot_neto105  = $row_cabecerafac["totneto105"];
                $tot_exento   = $row_cabecerafac["totneto"] ;
                $tot_servicios    = $row_cabecerafac["totimp"];
            }
            
            $tot_iva21    = $row_cabecerafac["totiva21"];
            $tot_iva105   = $row_cabecerafac["totiva105"];

            $total        = $row_cabecerafac["totbruto"];
            $nroorig      = $row_cabecerafac["nrodoc"];

            // Acumulo subtotales

            // resto Notas de Crédito
            $acum_tot_exento  = $acum_tot_exento  + ($tot_exento * $signo);
            $acum_tot_neto21  = $acum_tot_neto21  + ($tot_neto21 * $signo);
            $acum_tot_neto105 = $acum_tot_neto105 + ($tot_neto105 * $signo);
            $acum_tot_iva21   = $acum_tot_iva21   + ($tot_iva21 * $signo);
            $acum_tot_iva105  = $acum_tot_iva105  + ($tot_iva105 * $signo);
            $acum_tot_resol   = $acum_tot_resol   + ($tot_resol * $signo);
            $acum_total       = $acum_total       + ($total * $signo);
            $acum_totcomis    = $acum_totcomis    + ($tot_comision * $signo);
			$acum_totservicios = $acum_totservicios + ($tot_servicios * $signo);
			$acum_totpublic    = $acum_totpublic    + ($tot_public * $signo);
           
            $tot_exento   = $tot_exento * $signo;
            $tot_neto21   = $tot_neto21 * $signo;
            $tot_neto105  = $tot_neto105 * $signo;
            $tot_iva21    = $tot_iva21 * $signo;
            $tot_iva105   = $tot_iva105 * $signo;
            $tot_resol    = $tot_resol * $signo;
            $tot_comision = $tot_comision * $signo;
			$tot_servicios = $tot_servicios * $signo;
			$tot_public   = $tot_public * $signo;
            $total        = $total * $signo;
           
            $tot_exento   = number_format($tot_exento, 2, ',','.');
            $tot_neto21   = number_format($tot_neto21, 2, ',','.');
            $tot_neto105  = number_format($tot_neto105, 2, ',','.');
            $tot_iva21    = number_format($tot_iva21, 2, ',','.');
            $tot_iva105   = number_format($tot_iva105, 2, ',','.');
            $tot_resol    = number_format($tot_resol, 2, ',','.');
            $tot_comision = number_format($tot_comision, 2, ',','.');
			$tot_servicios = number_format($tot_servicios, 2, ',','.');
			$tot_public   = number_format($tot_public, 2, ',','.');
            $total        = number_format($total, 2, ',','.');

            // Leo el cliente
            $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
            $enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES lin 212");
            $row_entidades = mysqli_fetch_assoc($enti);
            $nom_cliente   = substr($row_entidades["razsoc"], 0, 19);
            $nro_cliente   = $row_entidades["numero"];
            $cuit_cliente  = $row_entidades["cuit"];
            
            // Imprimo los renglones
            $pdf->SetY($valor_y);
            $pdf->Cell(1);
            $pdf->Cell(19,6,$fecha,0,0,'L');
            $pdf->SetX(19);
            $pdf->Cell(6,6,$tc." ",0,0,'L');
            $pdf->Cell(27,6,$nroorig,0,0,'L');
            $pdf->Cell(42,6,$nom_cliente,0,0,'L');
            $pdf->Cell(22,6,$cuit_cliente,0,0,'L');
            $pdf->Cell(23,6,$tot_exento,0,0,'R');
            $pdf->Cell(26,6,$tot_comision,0,0,'R');
            $pdf->Cell(26,6,$tot_neto21,0,0,'R');
            $pdf->Cell(26,6,$tot_neto105,0,0,'R');
            $pdf->Cell(24,6,$tot_iva21,0,0,'R');
            $pdf->Cell(24,6,$tot_iva105,0,0,'R');
            $pdf->Cell(24,6,$tot_servicios,0,0,'R');
			$pdf->Cell(24,6,$tot_public,0,0,'R');
            $pdf->Cell(26,6,$total,0,0,'R');
            $tot_exento   = 0.00; // number_format($tot_exento, 2, ',','.');
            $tot_neto21   = 0.00; // number_format($tot_neto21, 2, ',','.');
            $tot_neto105  = 0.00; // number_format($tot_neto105, 2, ',','.');
            $tot_iva21    = 0.00; // number_format($tot_iva21, 2, ',','.');
            $tot_iva105   = 0.00; // number_format($tot_iva105, 2, ',','.');
            $tot_resol    = 0.00; // number_format($tot_resol, 2, ',','.');
            $tot_comision = 0.00; // number_format($tot_comision, 2, ',','.');
			$tot_servicios = 0.00;
			$tot_public = 0.00;
            $total        = 0.00; // number_format($total, 2, ',','.');
            $i = $i + 1;
            $valor_y = $valor_y + 6;
        }
        else {
            // Imprimo subtotales de la hoja, uso otras variables porque el number_format
            // me jode los acumulados
            $f_acum_tot_exento  = number_format($acum_tot_exento, 2, ',','.');
            $f_acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
            $f_acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
            $f_acum_tot_iva21   = number_format($acum_tot_iva21, 2, ',','.');
            $f_acum_tot_iva105  = number_format($acum_tot_iva105, 2, ',','.');
            $f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
            $f_acum_total       = number_format($acum_total, 2, ',','.');
            $f_acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
			$f_acum_totservicios    = number_format($acum_totservicios, 2, ',','.');
			$f_acum_totpublic    = number_format($acum_totpublic, 2, ',','.');

            $pdf->SetY($valor_y);
            $pdf->Cell(113);
            $pdf->Cell(26,6,$f_acum_tot_exento,0,0,'R');
            $pdf->Cell(26,6,$f_acum_totcomis,0,0,'R');
            $pdf->Cell(26,6,$f_acum_tot_neto21,0,0,'R');
            $pdf->Cell(26,6,$f_acum_tot_neto105,0,0,'R');
            $pdf->Cell(24,6,$f_acum_tot_iva21,0,0,'R');
            $pdf->Cell(24,6,$f_acum_tot_iva105,0,0,'R');
            $pdf->Cell(24,6,$f_acum_totservicios,0,0,'R');
			$pdf->Cell(24,6,$f_acum_totpublic,0,0,'R');
            $pdf->Cell(26,6,$f_acum_total,0,0,'R');

            // Voy a otra hoja e imprimo los titulos 
            $pdf->AddPage();
            $pdf->SetMargins(0.5, 0.5 , 0.5);
            $pdf->SetFont('Arial','B',11);
            $pdf->SetY(5);
            $pdf->Cell(10);
            $pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
            $pdf->Cell(200);
            $pagina = $pdf->PageNo();
            $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
            $pdf->SetY(10);
            $pdf->Cell(230);
            $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
            $pdf->SetY(15);
            $pdf->Cell(130);
            $pdf->Cell(20,10,' Libro IVA Ventas ',0,0,'L');
            $pdf->SetFont('Arial','B',9);
            $pdf->SetY(25);
            $pdf->Cell(3);
            $pdf->Cell(20,16,'    Fecha',1,0,'L');
            $pdf->Cell(28,16,' Nro.Factura',1,0,'L');
            $pdf->Cell(42,16,utf8_decode('       Razón Social'),1,0,'L');
            $pdf->Cell(22,16,'     CUIT',1,0,'L');
            $pdf->Cell(23,16,'    Conceptos ',1,0,'L');
            $pdf->Cell(26,16,'Uso Plataforma',1,0,'L');
            $pdf->Cell(26,16,'    Lotes  ',1,0,'L');
            $pdf->Cell(26,16,'    Lotes  ',1,0,'L');
            $pdf->Cell(24,16,utf8_decode(' IVA Débito'),1,0,'L');
            $pdf->Cell(24,16,utf8_decode(' IVA Débito'),1,0,'L');
            $pdf->Cell(24,16,' Gs. Adm.',1,0,'L');
			$pdf->Cell(24,16,' Publicidad',1,0,'L');
            $pdf->Cell(26,16,'Total Facturado',1,0,'L');
            $pdf->SetY(34);
            $pdf->Cell(114);
            $pdf->Cell(23,8,'    Exentos ',0,0,'C');
            $pdf->Cell(26,8,'             ',0,0,'C');
            $pdf->Cell(26,8,' Gravados 21% ',0,0,'C');
            $pdf->Cell(26,8,' Gravados 10,5%',0,0,'C');
            $pdf->Cell(24,8,' Fiscal 21%',0,0,'C');
            $pdf->Cell(24,8,' Fiscal 10,5%',0,0,'C');
            $pdf->Cell(24,8,'        ',0,0,'C');

            $valor_y = 45;
            // reinicio los contadores
            $i = 0;
  
            // ACA TENGO QUE IMPRIMIR EL REGISTRO QUE ACABO DE LEER PORQUE SI NO LO PIERDO CUANDO VUELVO AL WHILE
            $fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
            $cliente      = $row_cabecerafac["cliente"];
 			if (($tcomp == 119 || $tcomp == 120 || $tcomp == 121 || $tcomp == 122 || $tcomp == 123 || $tcomp == 124) && ($row_cabecerafac["totiva21"] == 0.00 &&  $row_cabecerafac["totiva105"] == 0.00)) {
                $tot_neto21   = 0.00;
                $tot_neto105  = 0.00;
                $tot_exento   = $row_cabecerafac["totneto21"] + $row_cabecerafac["totneto"];
                $tot_comision   = 0.00;
				$tot_servicios   = 0.00;
                //if ($tcomp == 57 && $ncomp == 1918)
                 //   echo " LINEA 123 ";
            }
            else if (($tcomp == 135 ) && ($row_cabecerafac["totiva21"] == 0.00 &&  $row_cabecerafac["totiva105"] == 0.00))  {
                $tot_exento   = $row_cabecerafac["totneto"] ;
                $tot_neto21   = 0.00; 
                $tot_neto105  = 0.00; 
                $tot_comision   = 0.00;
                $tot_resol    = 0.00;
				$tot_servicios    = 0.00;
            }
            else if ($tcomp == 125 || $tcomp == 126 || $tcomp == 127 || $tcomp == 133 || $tcomp == 134) {
				//Aca leo detfac para ver el concepto facturado
				$query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                //$row_detfac = mysqli_fetch_assoc($detfac);
                $tot_public   = 0.00;
				$tot_gastos   = 0.00;
				$tot_neto21      = 0.00;
				$tot_neto105     = 0.00;
                $tot_comision = 0.00;
				$tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac, MYSQLI_BOTH)) {
                    if ($row_detfac["concafac"] == 3)
                        $tot_public   += $row_detfac["neto"];
					
                    if ($row_detfac["concafac"] == 5)
                        $tot_comision += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 1 || $row_detfac["concafac"] == 6 || $row_detfac["concafac"] == 7 || $row_detfac["concafac"] == 8 || $row_detfac["concafac"] == 9)
				        $tot_servicios += $row_detfac["neto"];
                }
                
                $tot_neto21   = 0.00; //$row_cabecerafac["totneto21"] ;
                $tot_neto105  = 0.00; //$row_cabecerafac["totneto105"];
                $tot_exento   = $row_cabecerafac["totneto"];
                //$tot_servicios    = $row_cabecerafac["totimp"];
            } else if ($tcomp == 119 || $tcomp == 120 || $tcomp == 121  || $tcomp == 135) {
				//Aca leo detfac para ver el concepto facturado
				$query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                //$row_detfac = mysqli_fetch_assoc($detfac);
                $tot_public   = 0.00;
				$tot_gastos   = 0.00;
				$tot_neto21     = 0.00;
				$tot_neto105     = 0.00;
                $tot_comision = 0.00;
				$tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac, MYSQLI_BOTH)) {
                    if ($row_detfac["concafac"] == 13 || $row_detfac["concafac"] == 16 || $row_detfac["concafac"] == 34)
                        $tot_neto105   += $row_detfac["neto"];
					
                    if ($row_detfac["concafac"] == 14  || $row_detfac["concafac"] == 17 || $row_detfac["concafac"] == 35)
                        $tot_neto21 += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 18 || $row_detfac["concafac"] == 21)
				        $tot_servicios += $row_detfac["neto"];
					if ($row_detfac["concafac"] == 19)
				        $tot_comision += $row_detfac["neto"];
					if ($row_detfac["concafac"] == 43 || $row_detfac["concafac"] == 71)
                        $tot_gastos   += $row_detfac["neto"];
					if ($row_detfac["concafac"] == 33)
				        $tot_public += $row_detfac["neto"];
                }
				if ($tot_comision == 0)
                	$tot_comision = $row_cabecerafac["totcomis"];
                
            } else {
                $tot_comision = $row_cabecerafac["totcomis"];

                $tot_neto21   = $row_cabecerafac["totneto21"] ;
                $tot_neto105  = $row_cabecerafac["totneto105"];
                $tot_exento   = $row_cabecerafac["totneto"] ;
                $tot_servicios    = $row_cabecerafac["totimp"];
            }
            
            $tot_iva21    = $row_cabecerafac["totiva21"];
            $tot_iva105   = $row_cabecerafac["totiva105"];

            $total        = $row_cabecerafac["totbruto"];
            $nroorig      = $row_cabecerafac["nrodoc"];
            // LE AGREGO LA CLAVE PORQUE ME PARECE QUE LA PIERDE
            $tcomp       = $row_cabecerafac["tcomp"];
            $serie       = $row_cabecerafac["serie"];
            $ncomp       = $row_cabecerafac["ncomp"];

 
            // resto Notas de Crédito
            $acum_tot_exento  = $acum_tot_exento  + ($tot_exento * $signo);
            $acum_tot_neto21  = $acum_tot_neto21  + ($tot_neto21 * $signo);
            $acum_tot_neto105 = $acum_tot_neto105 + ($tot_neto105 * $signo);
            $acum_tot_iva21   = $acum_tot_iva21   + ($tot_iva21 * $signo);
            $acum_tot_iva105  = $acum_tot_iva105  + ($tot_iva105 * $signo);
            $acum_tot_resol   = $acum_tot_resol   + ($tot_resol * $signo);
            $acum_total       = $acum_total       + ($total * $signo);
            $acum_totcomis    = $acum_totcomis    + ($tot_comision * $signo);
			$acum_totservicios = $acum_totservicios    + ($tot_servicios * $signo);
			$acum_totpublic   = $acum_totpublic   + ($tot_public * $signo);
           
            $tot_exento   = $tot_exento * $signo;
            $tot_neto21   = $tot_neto21 * $signo;
            $tot_neto105  = $tot_neto105 * $signo;
            $tot_iva21    = $tot_iva21 * $signo;
            $tot_iva105   = $tot_iva105 * $signo;
            $tot_resol    = $tot_resol * $signo;
            $tot_comision = $tot_comision * $signo;
			$tot_servicios = $tot_servicios * $signo;
			$tot_public   = $tot_public * $signo;
            $total        = $total * $signo;

            $tot_exento   = number_format($tot_exento, 2, ',','.');
            $tot_neto21   = number_format($tot_neto21, 2, ',','.');
            $tot_neto105  = number_format($tot_neto105, 2, ',','.');
            $tot_iva21    = number_format($tot_iva21, 2, ',','.');
            $tot_iva105   = number_format($tot_iva105, 2, ',','.');
            $tot_resol    = number_format($tot_resol, 2, ',','.');
            $tot_comision = number_format($tot_comision, 2, ',','.');
			$tot_servicios = number_format($tot_servicios, 2, ',','.');
			$tot_public   = number_format($tot_public, 2, ',','.');
            $total        = number_format($total, 2, ',','.');

            // Leo el cliente
            $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
            $enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES lin 411");
            $row_entidades = mysqli_fetch_assoc($enti);
            $nom_cliente   = substr($row_entidades["razsoc"], 0, 19);
            $nro_cliente   = $row_entidades["numero"];
            $cuit_cliente  = $row_entidades["cuit"];

            // Imprimo los renglones
            $pdf->SetY($valor_y);
            $pdf->Cell(1);
            $pdf->Cell(19,6,$fecha,0,0,'L');
            $pdf->SetX(19);
            $pdf->Cell(6,6,$tc." ",0,0,'L');
            $pdf->Cell(27,6,$nroorig,0,0,'L');
            $pdf->Cell(42,6,$nom_cliente,0,0,'L');
            $pdf->Cell(22,6,$cuit_cliente,0,0,'L');
            $pdf->Cell(23,6,$tot_exento,0,0,'R');
            $pdf->Cell(26,6,$tot_comision,0,0,'R');
            $pdf->Cell(26,6,$tot_neto21,0,0,'R');
            $pdf->Cell(26,6,$tot_neto105,0,0,'R');
            $pdf->Cell(24,6,$tot_iva21,0,0,'R');
            $pdf->Cell(24,6,$tot_iva105,0,0,'R');
            $pdf->Cell(24,6,$tot_servicios,0,0,'R');
			$pdf->Cell(24,6,$tot_public,0,0,'R');
            $pdf->Cell(26,6,$total,0,0,'R');
            $tot_exento   = 0.00; // number_format($tot_exento, 2, ',','.');
            $tot_neto21   = 0.00; // number_format($tot_neto21, 2, ',','.');
            $tot_neto105  = 0.00; // number_format($tot_neto105, 2, ',','.');
            $tot_iva21    = 0.00; // number_format($tot_iva21, 2, ',','.');
            $tot_iva105   = 0.00; // number_format($tot_iva105, 2, ',','.');
            $tot_resol    = 0.00; // number_format($tot_resol, 2, ',','.');
            $tot_comision = 0.00; // number_format($tot_comision, 2, ',','.');
			$tot_servicios = 0.00;
			$tot_public   = 0.00;
            $total        = 0.00; // number_format($total, 2, ',','.');

            $valor_y = $valor_y + 6;
            $i = $i + 1;
            // HASTA ACA ========================================================================================
        }
    }
    // Imprimo subtotales de la hoja la última vez
    $acum_tot_exento  = number_format($acum_tot_exento, 2, ',','.');
    $acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
    $acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
    $acum_tot_iva21   = number_format($acum_tot_iva21, 2, ',','.');
    $acum_tot_iva105  = number_format($acum_tot_iva105, 2, ',','.');
    $acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
	$acum_totpublic  = number_format($acum_totpublic, 2, ',','.');
    $acum_total       = number_format($acum_total, 2, ',','.');
    $acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
	$acum_totservicios    = number_format($acum_totservicios, 2, ',','.');

    $pdf->SetY($valor_y);
    $pdf->Cell(113);
    $pdf->Cell(26,6,$acum_tot_exento,0,0,'R');
    $pdf->Cell(26,6,$acum_totcomis,0,0,'R');
    $pdf->Cell(26,6,$acum_tot_neto21,0,0,'R');
    $pdf->Cell(26,6,$acum_tot_neto105,0,0,'R');
    $pdf->Cell(24,6,$acum_tot_iva21,0,0,'R');
    $pdf->Cell(24,6,$acum_tot_iva105,0,0,'R');
    $pdf->Cell(24,6,$acum_totservicios,0,0,'R');
	$pdf->Cell(24,6,$acum_totpublic,0,0,'R');
    $pdf->Cell(26,6,$acum_total,0,0,'R');

    mysqli_close($amercado);
    $pdf->Output();
}

else {
    $anio = "";
    $mes = "";
    $anio = substr($fecha_desde,6,4);
    $mes = substr($fecha_desde,3,2);
    $fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
    $fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";
    $fechahoy = date("d-m-Y");

    // ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL TXT =========================
    $csv_end = "  
    ";  
    $csv_sep = "|";  
    $csv_file = "IVA_VENTAS".$anio.$mes.".txt";  
    $csv="";  

    // Traigo impuestos
    $query_impuestos= "SELECT * FROM impuestos";
    $impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO IMPUESTOS ".$query_impuestos." ");
    $row_Recordset2 = mysqli_fetch_assoc($impuestos);
    $totalRows_Recordset2 = mysqli_num_rows($impuestos);
    $porc_iva105 = 10.5; //(mysqli_result($impuestos,1, 1)/100); 
    $porc_iva21 = 21; //(mysqli_result($impuestos,0, 1)/100);

    // Leo la cabecera
    $query_cabfac = sprintf("SELECT * FROM cabfac WHERE CAE is not null AND fecreg BETWEEN %s AND %s ORDER BY fecreg, nrodoc", $fecha_desde, $fecha_hasta);
    $cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC ".$query_cabfac." ");

    // ACA ARMO EL RENGLON DE TITULOS : ===============================================
    $csv.="Fecha".$csv_sep."Nro Factura".$csv_sep."Razon Social".$csv_sep."CUIT".$csv_sep."Conceptos Exentos".$csv_sep."Uso Plataforma".$csv_sep."Gs. Adm.".$csv_sep."Lotes Gravados 21%".$csv_sep."Lotes Gravados 10,5%".$csv_sep."IVA Debito Fiscal 21%".$csv_sep."IVA Debito Fiscal 10,5%".$csv_sep."Publicidad".$csv_sep."Total Facturado".$csv_sep."Remate".$csv_end; 

    // Datos de los renglones
    $i = 0;
    $acum_tot_neto21  = 0;
    $acum_tot_neto105 = 0;
    $acum_tot_iva21   = 0;
    $acum_tot_iva105  = 0;
    $acum_tot_resol   = 0;
    $acum_total       = 0;
    $acum_tot_exento  = 0;
    $acum_totcomis    = 0;
	$acum_totpublic    = 0;
    while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
    {	
        $tcomp      = $row_cabecerafac["tcomp"];
        $serie      = $row_cabecerafac["serie"];
        $ncomp      = $row_cabecerafac["ncomp"];

        
        if ($tcomp ==  119 ||  $tcomp ==  120 || $tcomp == 121 || $tcomp == 135) {
            $tc = "NC-";
            $signo = -1;
        }
        elseif ($tcomp == 122 ||  $tcomp == 123 || $tcomp == 124){
            $tc = "ND-";
            $signo = 1;
        }
        else {
            $tc = "FC-";
            $signo = 1;
        }

        if ($i < 22) {
            $fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
			//Aca leo detfac para ver el concepto facturado
            $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
            $detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
            //$row_detfac = mysqli_fetch_assoc($detfac);
            $tot_public   = 0.00;
            $tot_gastos   = 0.00;
            $lotes21      = 0.00;
            $lotes105     = 0.00;
            $tot_comision = 0.00;
            $tot_servicios= 0.00;

            while ($row_detfac = mysqli_fetch_array($detfac, MYSQLI_BOTH)) {
                if ($row_detfac["concafac"] == 3)
                    $tot_public   += $row_detfac["neto"];
                if ($row_detfac["concafac"] == 5)
                    $tot_comision += $row_detfac["neto"];
                if ($row_detfac["concafac"] == 1 || $row_detfac["concafac"] == 6 || $row_detfac["concafac"] == 7 || $row_detfac["concafac"] == 8 || $row_detfac["concafac"] == 9)
                    $tot_servicios += $row_detfac["neto"];
            }
            $cliente      = $row_cabecerafac["cliente"];
            $tot_neto21   = $row_cabecerafac["totneto21"] ;
            $tot_neto   = $row_cabecerafac["totneto"] ;
            $tot_neto105  = $row_cabecerafac["totneto105"];
            $tot_comision = $row_cabecerafac["totcomis"];
            $tot_iva21    = $row_cabecerafac["totiva21"];// + $row_cabecerafac["totcomis"]) * $porc_iva21;
            $tot_iva105   = $row_cabecerafac["totiva105"];
            $tot_resol    = $row_cabecerafac["totimp"];
            $total        = $row_cabecerafac["totbruto"];
            $nroorig      = $tc.$row_cabecerafac["nrodoc"];
            $codrem = $row_cabecerafac["codrem"];
            if ($tot_iva21 == 0.00 && $tot_iva105 == 0.00) {
                $tot_exento = $tot_neto21 + $tot_neto;
                $tot_neto21 = 0.00;
            }
            else 
                $tot_exento = 0.00;

            $estado = $row_cabecerafac["estado"];

            // Acumulo subtotales
            if ($estado == "P" || $estado == "S" || $estado == "C") {
                if ($tcomp ==  119 ||  $tcomp ==  120 || $tcomp == 121 || $tcomp == 135) {
                    // resto Notas de Crédito
                    $acum_tot_neto21  = $acum_tot_neto21  - $tot_neto21;
                    $acum_tot_neto105 = $acum_tot_neto105 - $tot_neto105;
                    $acum_tot_iva21   = $acum_tot_iva21   - $tot_iva21;
                    $acum_tot_iva105  = $acum_tot_iva105  - $tot_iva105;
                    $acum_tot_resol   = $acum_tot_resol   - $tot_resol;
                    $acum_tot_exento  = $acum_tot_exento  - $tot_exento;
                    $acum_total       = $acum_total       - $total;
                    $acum_totcomis    = $acum_totcomis    - $tot_comision;
					$acum_totpublic   = $acum_totpublic   - $tot_public;
                }
                else {
                    // Sumo Facturas y Notas de Débito
                    $acum_tot_neto21  = $acum_tot_neto21  + $tot_neto21;
                    $acum_tot_neto105 = $acum_tot_neto105 + $tot_neto105;
                    $acum_tot_iva21   = $acum_tot_iva21   + $tot_iva21;
                    $acum_tot_iva105  = $acum_tot_iva105  + $tot_iva105;
                    $acum_tot_exento  = $acum_tot_exento  + $tot_exento;
                    $acum_tot_resol   = $acum_tot_resol   + $tot_resol;
                    $acum_total       = $acum_total       + $total;
                    $acum_totcomis    = $acum_totcomis    + $tot_comision;
					$acum_totpublic   = $acum_totpublic   + $tot_public;

                }

                $tot_neto21   = number_format($tot_neto21*$signo, 2, ',','.');
                $tot_neto105  = number_format($tot_neto105*$signo, 2, ',','.');
                $tot_iva21    = number_format($tot_iva21*$signo, 2, ',','.');
                $tot_iva105   = number_format($tot_iva105*$signo, 2, ',','.');
                $tot_resol    = number_format($tot_resol*$signo, 2, ',','.');
                $tot_comision = number_format($tot_comision*$signo, 2, ',','.');
                $tot_exento   = number_format($tot_exento*$signo, 2, ',','.');
				$tot_public   = number_format($tot_public*$signo, 2, ',','.');
                $total        = number_format($total*$signo, 2, ',','.');

                // Leo el cliente
                $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
                $enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES ".$query_entidades." ");
                $row_entidades = mysqli_fetch_assoc($enti);
                $nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
                $nro_cliente   = $row_entidades["numero"];
                $cuit_cliente  = $row_entidades["cuit"];

                // Imprimo los renglones DESDE ACA ARMO EL TXT
                $csv.=$fecha.$csv_sep.$nroorig.$csv_sep.$nom_cliente.$csv_sep.$cuit_cliente.$csv_sep.$tot_exento.$csv_sep.$tot_comision.$csv_sep.$tot_resol.$csv_sep.$tot_neto21.$csv_sep.$tot_neto105.$csv_sep.$tot_iva21.$csv_sep.$tot_iva105.$csv_sep.$tot_public.$csv_sep.$total.$csv_sep.$codrem.$csv_end;

            }
            else {
                // Imprimo cbte anulado

                $csv.=$fecha.$csv_sep.$nroorig.$csv_sep."ANULADA".$csv_sep." ".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_end;
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
			$f_acum_totpublic    = number_format($acum_totpublic, 2, ',','.');
            $f_acum_tot_exento  = number_format($acum_tot_exento, 2, ',','.');

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
	$acum_totpublic   = number_format($acum_totpublic, 2, ',','.');
    $acum_tot_exentos = number_format($acum_tot_exento, 2, ',','.');


    mysqli_close($amercado);
    // ACA GRABO EL ARCHIVO TXT ====================================================
    if (!$handle = fopen($csv_file, "w")) {  
        echo "No se puede abrir el archivo";  
        exit;  
    }  
    if (fwrite($handle, utf8_decode($csv)) === FALSE) {  
        echo "No se puede grabar el archivo";  
        exit;  
    }  
    fclose($handle);  

    $file = $csv_file;
    header("Content-disposition: attachment; filename=$file");
    header("Content-type: application/octet-stream");
    readfile($file);

    if (!isset($file) || empty($file)) {
        exit();
    }
    $root = "C:\\LOTES WEB";
    $file = basename($file);
    $path = $root.$file;
    $type = '';

    if (is_file($path)) {
        $size = filesize($path);
        if (function_exists('mime_content_type')) {
            $type = mime_content_type($path);
        } else if (function_exists('finfo_file')) {
                    $info = finfo_open(FILEINFO_MIME);
                    $type = finfo_file($info, $path);
                    finfo_close($info);
                }
        if ($type == '') {
            $type = "application/force-download";
        }
         // Define los headers
         header("Content-Type: $type");
         header("Content-Disposition: attachment; filename=$file");
         header("Content-Transfer-Encoding: binary");
         header("Content-Length: " . $size);
         // Descargar el archivo
         readfile($path);
    } else {
        //die("El archivo no existe.");
    }

}
?>