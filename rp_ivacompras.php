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
define('FC_PROV_M','65');
define('NC_PROV_M','87');
define('ND_PROV_M','88');
define('FC_PROV_EXT','107');
define('FC_PROV_LIQ','110');
// CONCAFAC_NROCONC PARA RETENCIONES
define('CONC_NO_GRAV','20');
define('RET_IVA','30');
define('RET_IIBB_BA','31');
define('RET_IIBB_CABA','32');
define('RET_IIBB_SALTA','63');
define('RET_IIBB_STAFE','64');
define('RET_IIBB_CHACO','65');
define('RET_IIBB_CORRIENTES','66');
define('RET_IIBB_NEUQUEN','67');
define('RET_IIBB_SANLUIS','68');
define('RET_IIBB_CORDOBA','72');
define('RET_IIBB_JUJUY','94');
define('RET_IIBB_SJUAN','97');
define('RET_GAN','33');
define('IMP_INT','35');
define('TAS_CER','60');
define('IMP_DIES','87');
define('COMB_LIQ','88');
define('REC_GAS','89');
define('SER_SOC','91');
define('TASA_SSN','92');
define('SEL_CABA','93');
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
    $query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecval BETWEEN %s AND %s ORDER BY fecval , nrodoc ", $fecha_desde, $fecha_hasta);
    //$query_cabfac = sprintf("SELECT * FROM cabfac ORDER BY fecval");
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
    $pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. '.$fecha_desde.'   '.$fecha_hasta,0,0,'L');
    $pdf->Cell(200);
    $pagina = $pdf->PageNo();
    $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
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
    $pdf->Cell(29,16,' Comprobante ',1,0,'L');
    $pdf->Cell(42,16,'       Proveedor',1,0,'L');
    $pdf->Cell(24,16,'     CUIT',1,0,'L');
    $pdf->Cell(22,16,'Exento ',1,0,'L');
    $pdf->Cell(22,16,'Gravado ',1,0,'L');
    $pdf->Cell(22,16,utf8_decode(' IVA Crédito'),1,0,'L');
    $pdf->Cell(22,16,utf8_decode(' Alícuota'),1,0,'L');
    $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
    $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
    $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
    $pdf->Cell(22,16,'   Total ',1,0,'L');
    $pdf->SetY(34);
    $pdf->Cell(118);
    $pdf->Cell(22,8,'         ',0,0,'L');
    $pdf->Cell(22,8,'           ',0,0,'L');
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
    $t_acum_total_neto  = 0.00;
    $t_acum_total_iva   = 0.00;
    $t_acum_tot_resol   = 0.00;
    $t_acum_total       = 0.00;
    $t_acum_df_retiva   = 0.00;
    $t_acum_df_retib_CABA = 0.00;
    $t_acum_df_retib_BSAS = 0.00;
    $t_acum_total_exento = 0.00;
    while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
    {	
        $tcomp      = $row_cabecerafac["tcomp"];
        $serie      = $row_cabecerafac["serie"];
        $ncomp      = $row_cabecerafac["ncomp"];

        if ($tcomp != FC_PROV_A && $tcomp != FC_PROV_C && $tcomp != ND_PROV_A && $tcomp != ND_PROV_C && $tcomp != NC_PROV_A && $tcomp != NC_PROV_C && $tcomp != FC_PROV_M && $tcomp != NC_PROV_M && $tcomp != ND_PROV_M && $tcomp != FC_PROV_EXT && $tcomp != FC_PROV_LIQ)
            continue;
        if ($tcomp == FC_PROV_A || $tcomp == FC_PROV_C || $tcomp == ND_PROV_A || $tcomp == ND_PROV_C || $tcomp == FC_PROV_M || $tcomp == ND_PROV_M || $tcomp == FC_PROV_EXT || $tcomp == FC_PROV_LIQ) {
            $signo = 1;
        }
        else {
            $signo = -1;
        }
        if ($i < 22) {
            $query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
            $detallefac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC linea 179");
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

                if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA || $concafac == RET_GAN || $concafac == CONC_NO_GRAV || $concafac == IMP_INT || $concafac == TAS_CER || $concafac == RET_IIBB_SALTA || $concafac == RET_IIBB_STAFE || $concafac == RET_IIBB_CHACO || $concafac == RET_IIBB_CORRIENTES || $concafac == RET_IIBB_NEUQUEN || $concafac == RET_IIBB_SANLUIS || $concafac == RET_IIBB_CORDOBA || $concafac == RET_IIBB_JUJUY || $concafac == RET_IIBB_SJUAN  || $concafac == IMP_DIES || $concafac == COMB_LIQ || $concafac == SER_SOC || $concafac == TASA_SSN || $concafac == SEL_CABA || $concafac == REC_GAS) {
                    switch($concafac) {
                        case	CONC_NO_GRAV:
                            $df_concnograv += $row_detallefac["neto"] * $signo;
                            break;
                        case	RET_IVA:
                            $df_retiva = $row_detallefac["neto"] * $signo;
                            break;
                        case 	RET_IIBB_BA:
                            $df_retib_BSAS  += $row_detallefac["neto"] * $signo;
                            break;
                        case 	RET_IIBB_CABA:
                        case 	RET_IIBB_SALTA:
                        case 	RET_IIBB_STAFE:
                        case 	RET_IIBB_CHACO:
                        case 	RET_IIBB_CORRIENTES:
                        case 	RET_IIBB_NEUQUEN:
                        case 	RET_IIBB_SANLUIS:
                        case    RET_IIBB_CORDOBA:
                        case    RET_IIBB_JUJUY:
                        case    RET_IIBB_SJUAN:
                            $df_retib_CABA  += $row_detallefac["neto"] * $signo;
                            break;
                        case	RET_GAN:
                        case	TAS_CER:
                        case	IMP_INT:
                        case	IMP_DIES:
                        case	COMB_LIQ:
                        case	SER_SOC:
                        case	TASA_SSN:
                        case	SEL_CABA:
                        case	REC_GAS:
                            $df_concnograv += $row_detallefac["neto"] * $signo;
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
            $tot_neto     = $df_neto25 + $df_neto50 + $df_neto105 + $df_neto210 + $df_neto270;
            $tot_neto21   = $row_cabecerafac["totneto21"]  *$signo;
            $tot_neto105  = $row_cabecerafac["totneto105"] * $signo;
            $tot_comision = $row_cabecerafac["totcomis"] * $signo;
            $tot_iva21    = $row_cabecerafac["totiva21"] * $signo;
            $tot_iva105   = $row_cabecerafac["totiva105"]  * $signo;
            $tot_resol    = $row_cabecerafac["totimp"] * $signo;
            $total        = ($row_cabecerafac["totbruto"] * $signo) ;
            $nroorig     = $row_cabecerafac["nrodoc"];
            if ($tcomp == FC_PROV_A ||  $tcomp == FC_PROV_C || $tcomp == FC_PROV_M || $tcomp == FC_PROV_EXT)
                $nroorig = "FC-".$nroorig;
            if ($tcomp == NC_PROV_A ||  $tcomp == NC_PROV_C ||  $tcomp == NC_PROV_M)
                $nroorig = "NC-".$nroorig;
            if ($tcomp == ND_PROV_A ||  $tcomp == ND_PROV_C ||  $tcomp == ND_PROV_M)
                $nroorig = "ND-".$nroorig;
            if ($tcomp == FC_PROV_LIQ)
                $nroorig = "LQ-".$nroorig;


            $total_neto   = $tot_neto ; 
            $total_iva    = ($tot_iva21  + $tot_iva105) ;
            $total_exento = 0;
            if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C ||  $tcomp == FC_PROV_EXT) {
                $total_exento = $row_cabecerafac["totneto"] * $signo;
                $total_neto   = 0;
            }
            if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C &&  $tcomp != FC_PROV_EXT) {
                $total_exento += $df_concnograv;
            }
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
            $nom_cliente   = substr($row_entidades["razsoc"], 0, 17);
            $nro_cliente   = $row_entidades["numero"];
            $cuit_cliente  = $row_entidades["cuit"];

            // Imprimo los renglones
            $pdf->SetY($valor_y);
            $pdf->Cell(1);
            $pdf->Cell(19,6,$fecha,0,0,'L');
            $pdf->Cell(35,6,$nroorig,0,0,'L');
            $pdf->Cell(38,6,$nom_cliente,0,0,'L');
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
            $pdf->Cell(118);
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
            $pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
            $pdf->Cell(200);
            $pagina = $pdf->PageNo();
            $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
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
            $pdf->Cell(29,16,' Comprobante ',1,0,'L');
            $pdf->Cell(42,16,'       Proveedor',1,0,'L');
            $pdf->Cell(24,16,'     CUIT',1,0,'L');
            $pdf->Cell(22,16,' Exento ',1,0,'L');
            $pdf->Cell(22,16,' Gravado ',1,0,'L');
            $pdf->Cell(22,16,utf8_decode(' IVA Crédito'),1,0,'L');
            $pdf->Cell(22,16,utf8_decode(' Alícuota'),1,0,'L');
            $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
            $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
            $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
            $pdf->Cell(22,16,'   Total ',1,0,'L');
            $pdf->SetY(34);
            $pdf->Cell(118);
            $pdf->Cell(22,8,'        ',0,0,'L');
            $pdf->Cell(22,8,'        ',0,0,'L');
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
                if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA || $concafac == RET_GAN || $concafac == CONC_NO_GRAV || $concafac == IMP_INT || $concafac == TAS_CER || $concafac == RET_IIBB_SALTA || $concafac == RET_IIBB_STAFE || $concafac == RET_IIBB_CHACO || $concafac == RET_IIBB_CORRIENTES || $concafac == RET_IIBB_NEUQUEN || $concafac == RET_IIBB_SANLUIS || $concafac == RET_IIBB_CORDOBA || $concafac == RET_IIBB_JUJUY || $concafac == RET_IIBB_SJUAN  || $concafac == IMP_DIES || $concafac == COMB_LIQ || $concafac == SER_SOC || $concafac == TASA_SSN || $concafac == SEL_CABA || $concafac == REC_GAS) {
                    switch($concafac) {
                        case	CONC_NO_GRAV:
                            $df_concnograv += $row_detallefac["neto"] * $signo;
                            break;
                        case	RET_IVA:
                            $df_retiva = $row_detallefac["neto"] * $signo;
                            break;
                        case 	RET_IIBB_BA:
                            $df_retib_BSAS  += $row_detallefac["neto"] * $signo;
                            break;
                        case 	RET_IIBB_CABA:
                        case 	RET_IIBB_SALTA:
                        case 	RET_IIBB_STAFE:
                        case 	RET_IIBB_CHACO:
                        case 	RET_IIBB_CORRIENTES:
                        case 	RET_IIBB_NEUQUEN:
                        case 	RET_IIBB_SANLUIS:
                        case    RET_IIBB_CORDOBA:
                        case    RET_IIBB_JUJUY:
                        case    RET_IIBB_SJUAN:
                            $df_retib_CABA  += $row_detallefac["neto"] * $signo;
                            break;
                        case	RET_GAN:
                        case	TAS_CER:
                        case	IMP_INT:
                        case	IMP_DIES:
                        case	COMB_LIQ:
                        case	SER_SOC:
                        case	TASA_SSN:
                        case	SEL_CABA:
                        case	REC_GAS:
                            $df_concnograv += $row_detallefac["neto"] * $signo;
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
            $tot_neto     = $tot_neto     = $df_neto25 + $df_neto50 + $df_neto105 + $df_neto210 + $df_neto270; 
            $tot_neto21   = $row_cabecerafac["totneto21"]  * $signo ;
            $tot_neto105  = $row_cabecerafac["totneto105"]  * $signo;
            $tot_comision = $row_cabecerafac["totcomis"] * $signo;
            $tot_iva21    = $row_cabecerafac["totiva21"]  * $signo; 
            $tot_iva105   = $row_cabecerafac["totiva105"] * $signo;
            $tot_resol    = $row_cabecerafac["totimp"] * $signo;
            //$total        = $row_cabecerafac["totbruto"] * $signo;
            $total        = ($row_cabecerafac["totbruto"] * $signo) ;
            $nroorig      = $row_cabecerafac["nrodoc"];
            if ($tcomp == FC_PROV_A ||  $tcomp == FC_PROV_C || $tcomp == FC_PROV_M || $tcomp == FC_PROV_EXT)
                $nroorig = "FC-".$nroorig;
            if ($tcomp == NC_PROV_A ||  $tcomp == NC_PROV_C ||  $tcomp == NC_PROV_M)
                $nroorig = "NC-".$nroorig;
            if ($tcomp == ND_PROV_A ||  $tcomp == ND_PROV_C  ||  $tcomp == ND_PROV_M)
                $nroorig = "ND-".$nroorig;
            if ($tcomp == FC_PROV_LIQ)
                $nroorig = "LQ-".$nroorig;
            $total_neto   = $tot_neto ; 
            $total_iva    = ($tot_iva21  + $tot_iva105)  ;
            $total_exento = 0;
            if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C ||  $tcomp == FC_PROV_EXT) {
                $total_exento = $row_cabecerafac["totneto"] * $signo;
                $total_neto   = 0;
                //$total_exento += $df_concnograv;
            }
            if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C &&  $tcomp != FC_PROV_EXT) {
                $total_exento += $df_concnograv;
                //$total_neto   = ($tot_neto - $total_exento) ;
            }
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
            $nom_cliente   = substr($row_entidades["razsoc"], 0, 17);
            $nro_cliente   = $row_entidades["numero"];
            $cuit_cliente  = $row_entidades["cuit"];

            // Imprimo los renglones
            $pdf->SetY($valor_y);
            $pdf->Cell(1);
            $pdf->Cell(19,6,$fecha,0,0,'L');
            $pdf->Cell(35,6,$nroorig,0,0,'L');
            $pdf->Cell(38,6,$nom_cliente,0,0,'L');
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
            //===========================================================================
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
        $pdf->Cell(59);
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

        // Ahora voy por las Liquidaciones
        $acum_total_neto  = 0.00;
        $acum_total_iva   = 0.00;
        $acum_tot_resol   = 0.00;
        $acum_total       = 0.00;
        $acum_df_retiva  = 0.00;
        $acum_df_retib_CABA = 0.00;
        $acum_df_retib_BSAS = 0.00;
        $acum_total_exento = 0.00;
        $df_retiva = 0.00;
        $df_retib_CABA = 0.00;
        $df_retib_BSAS = 0.00;
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

                
                $estado = $row_liquidacion["estado"];
                
                if ($estado != "A") {
                    // Acumulo subtotales
                    $acum_df_neto105   = $acum_df_neto105   + $tot_neto105;
                    $acum_df_iva105    = $acum_df_iva105    + $tot_iva105;
                    $acum_df_neto210   = $acum_df_neto210   + $tot_neto21;
                    $acum_df_iva210    = $acum_df_iva210    + $tot_iva21;

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

                    // Leo el cliente
                    $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
                    $enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
                    $row_entidades = mysqli_fetch_assoc($enti);
                    $nom_cliente   = substr($row_entidades["razsoc"], 0, 17);
                    $nro_cliente   = $row_entidades["numero"];
                    $cuit_cliente  = $row_entidades["cuit"];

                    // Imprimo los renglones
                    $pdf->SetY($valor_y);
                    $pdf->Cell(1);
                    $pdf->Cell(19,6,$fecha,0,0,'L');
                    $pdf->Cell(35,6,$nroorig,0,0,'L');
                    $pdf->Cell(38,6,$nom_cliente,0,0,'L');
                    $pdf->Cell(24,6,$cuit_cliente,0,0,'L');
                    $pdf->Cell(22,6," ",0,0,'R');
                    $pdf->Cell(22,6,$total_neto,0,0,'R');
                    $pdf->Cell(22,6,$total_iva,0,0,'R');
                    $pdf->Cell(22,6," ",0,0,'R');
                    if ($df_retiva == 0)
                        $pdf->Cell(22,6,"0,00",0,0,'R');
                    else
                        $pdf->Cell(22,6,$df_retiva,0,0,'R');
                    if ($df_retib_CABA == 0)
                        $pdf->Cell(22,6,"0,00",0,0,'R');
                    else
                       $pdf->Cell(22,6,$df_retib_CABA,0,0,'R');
                    if ($df_retib_BSAS == 0)
                        $pdf->Cell(22,6,"0,00",0,0,'R');
                    else
                       $pdf->Cell(22,6,$df_retib_BSAS,0,0,'R');
                    $pdf->Cell(22,6,$total,0,0,'R');


                    $i = $i + 1;
                    $valor_y = $valor_y + 6;

                }
                else {
                    // Imprimo los renglones
                    $pdf->SetY($valor_y);
                    $pdf->Cell(1);
                    $pdf->Cell(19,6,$fecha,0,0,'L');
                    $pdf->Cell(35,6,$nroorig,0,0,'L');
                    $pdf->Cell(39,6,"ANULADA",0,0,'L');
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
                $f_acum_total_neto    = number_format($acum_total_neto, 2, ',','.');
                $f_acum_total_iva     = number_format($acum_total_iva, 2, ',','.');
                $f_acum_tot_resol     = number_format($acum_tot_resol, 2, ',','.');
                $f_acum_total         = number_format($acum_total, 2, ',','.');
                $f_acum_df_retiva     = number_format($acum_df_retiva, 2, ',','.');
                $f_acum_df_retib_CABA = number_format($acum_df_retib_CABA, 2, ',','.');
                $f_acum_df_retib_BSAS = number_format($acum_df_retib_BSAS, 2, ',','.');

                $pdf->SetY($valor_y);
                $pdf->Cell(140);
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
                $pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
                $pdf->Cell(200);
                $pagina = $pdf->PageNo();
                $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
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
                $pdf->Cell(29,16,' Comprobante ',1,0,'L');
                $pdf->Cell(42,16,'       Proveedor',1,0,'L');
                $pdf->Cell(24,16,'     CUIT',1,0,'L');
                $pdf->Cell(22,16,' Exento ',1,0,'L');
                $pdf->Cell(22,16,' Gravado ',1,0,'L');
                $pdf->Cell(22,16,utf8_decode(' IVA Crédito'),1,0,'L');
                $pdf->Cell(22,16,utf8_decode(' Alícuota'),1,0,'L');
                $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
                $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
                $pdf->Cell(22,16,utf8_decode(' Percepción'),1,0,'L');
                $pdf->Cell(22,16,'   Total ',1,0,'L');
                $pdf->SetY(34);
                $pdf->Cell(118);
                $pdf->Cell(22,8,'        ',0,0,'L');
                $pdf->Cell(22,8,'        ',0,0,'L');
                $pdf->Cell(22,8,'Fiscal ',0,0,'L');
                $pdf->Cell(22,8,'Diferencial',0,0,'L');
                $pdf->Cell(22,8,'    IVA',0,0,'L');
                $pdf->Cell(22,8,' IIBB CABA',0,0,'L');
                $pdf->Cell(22,8,' IIBB BsAs',0,0,'L');
                $pdf->Cell(22,8,' Facturado',0,0,'L');

                $valor_y = 45;
                // reinicio los contadores

                $i = 0;

                // Imprimo el primer renglón que ya lo había leído
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
                //$query_cbtesanul = sprintf("SELECT * FROM cbtesanul WHERE  tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
                //$cbtesanul = mysqli_query($amercado, $query_cbtesanul) or die(mysqli_error($amercado));
                //$row_cbtesanul = mysqli_fetch_assoc($cbtesanul);
                $estado = $row_liquidacion["estado"];
                //if (mysqli_num_rows($cbtesanul) > 0) 
                //	$estado		  = "A";

                // Acumulo subtotales
                if ($estado != "A") {
                    // Acumulo subtotales
                    $acum_df_neto105   = $acum_df_neto105   + $tot_neto105;
                    $acum_df_iva105    = $acum_df_iva105    + $tot_iva105;
                    $acum_df_neto210   = $acum_df_neto210   + $tot_neto21;
                    $acum_df_iva210    = $acum_df_iva210    + $tot_iva21;

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
                    $nom_cliente   = substr($row_entidades["razsoc"], 0, 17);
                    $nro_cliente   = $row_entidades["numero"];
                    $cuit_cliente  = $row_entidades["cuit"];

                    // Imprimo los renglones
                    $pdf->SetY($valor_y);
                    $pdf->Cell(1);
                    $pdf->Cell(19,6,$fecha,0,0,'L');
                    $pdf->Cell(35,6,$nroorig,0,0,'L');
                    $pdf->Cell(38,6,$nom_cliente,0,0,'L');
                    $pdf->Cell(24,6,$cuit_cliente,0,0,'L');
                    $pdf->Cell(22,6," ",0,0,'R');
                    $pdf->Cell(22,6,$total_neto,0,0,'R');
                    $pdf->Cell(22,6,$total_iva,0,0,'R');
                    $pdf->Cell(22,6," ",0,0,'R');
                    $pdf->Cell(22,6,"0,00",0,0,'R');
                    $pdf->Cell(22,6,"0,00",0,0,'R');
                    $pdf->Cell(22,6,"0,00",0,0,'R');
                    $pdf->Cell(22,6,$total,0,0,'R');
                    $i = $i + 1;
                    $valor_y = $valor_y + 6;
                }
                else {
                    // Imprimo los renglones
                    $pdf->SetY($valor_y);
                    $pdf->Cell(1);
                    $pdf->Cell(19,6,$fecha,0,0,'L');
                    $pdf->Cell(35,6,$nroorig,0,0,'L');
                    $pdf->Cell(39,6,"ANULADA",0,0,'L');
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


        // Imprimo totales generales de la hoja la última vez 
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
        $pdf->Cell(24,6," ",0,0,'R');
        $pdf->Cell(24,6,$f_acum_total_neto,0,0,'R');
        $pdf->Cell(24,6,$f_acum_total_iva,0,0,'R');
        $pdf->Cell(22,6," ",0,0,'R');
        $pdf->Cell(22,6,$f_acum_df_retiva,0,0,'R');
        $pdf->Cell(21,6,$f_acum_df_retib_CABA,0,0,'R');
        $pdf->Cell(21,6,$f_acum_df_retib_BSAS,0,0,'R');
        $pdf->Cell(24,6,$f_acum_total,0,0,'R');

        $valor_y = $valor_y + 6;


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
        $pdf->Cell(24,6,$t_acum_total_exento,0,0,'R');
        $pdf->Cell(24,6,$t_acum_total_neto,0,0,'R');
        $pdf->Cell(24,6,$t_acum_total_iva,0,0,'R');
        $pdf->Cell(20,6," ",0,0,'R');
        $pdf->Cell(24,6,$t_acum_df_retiva,0,0,'R');
        $pdf->Cell(22,6,$t_acum_df_retib_CABA,0,0,'R');
        $pdf->Cell(20,6,$t_acum_df_retib_BSAS,0,0,'R');
        $pdf->Cell(24,6,$t_acum_total,0,0,'R');
        // ACA IMPRIMO LOS NETOS  Y LOS IVAS POR CADA ALICUOTA DE IVA
        $valor_y += 7;
        $pdf->SetY($valor_y);

        //$pdf->Cell(58,6,"TOTAL NETO AL 2,5 % :  ",0,0,'L');
        // Voy a otra hoja e imprimo los titulos 
                $valor_y = 45;
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
                $pdf->Cell(20,10,' Libro IVA Compras ',0,0,'L');
                $pdf->SetFont('Arial','B',9);

        //$valor_y += 6;

        $pdf->SetY($valor_y);
        $pdf->SetX(93);
        $pdf->Cell(90,6,utf8_decode("Resumen IVA         Gravado           %        IVA Crédito fiscal "),1,0,'L');
        $valor_y += 7;
        $pdf->SetY($valor_y);
        $pdf->SetX(122);
        $pdf->Cell(15,6,$f_acum_df_neto25,0,0,'R');
        $pdf->SetX(144);
        $pdf->Cell(8,6," 2,50 % ",0,0,'R');
        $pdf->SetX(164);
        $pdf->Cell(15,6,$f_acum_df_iva25,0,0,'R');
        $valor_y += 7;
        $pdf->SetY($valor_y);
        $pdf->SetX(122);
        $pdf->Cell(15,6,$f_acum_df_neto50,0,0,'R');
        $pdf->SetX(144);
        $pdf->Cell(8,6," 5,00 % ",0,0,'R');
        $pdf->SetX(164);
        $pdf->Cell(15,6,$f_acum_df_iva50,0,0,'R');
        $valor_y += 7;
        $pdf->SetY($valor_y);
        $pdf->SetX(122);
        $pdf->Cell(15,6,$f_acum_df_neto105,0,0,'R');
        $pdf->SetX(144);
        $pdf->Cell(8,6," 10,50 % ",0,0,'R');
        $pdf->SetX(164);
        $pdf->Cell(15,6,$f_acum_df_iva105,0,0,'R');
        $valor_y += 7;
        $pdf->SetY($valor_y);
        $pdf->SetX(122);
        $pdf->Cell(15,6,$f_acum_df_neto210,0,0,'R');
        $pdf->SetX(144);
        $pdf->Cell(8,6," 21,00 % ",0,0,'R');
        $pdf->SetX(164);
        $pdf->Cell(15,6,$f_acum_df_iva210,0,0,'R');
        $valor_y += 7;
        $pdf->SetY($valor_y);
        $pdf->SetX(122);
        $pdf->Cell(15,6,$f_acum_df_neto270,0,0,'R');
        $pdf->SetX(144);
        $pdf->Cell(8,6," 27,00 % ",0,0,'R');
        $pdf->SetX(164);
        $pdf->Cell(15,6,$f_acum_df_iva270,0,0,'R');

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

    // ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL CSV =========================
    $csv_end = "  
    ";  
    $csv_sep = "|";  
    //$csv_file = "\LIBROS DE IVA PARA EXCEL\IVA_COMPRAS".$fecha_hasta.".txt";  
    $csv_file = "IVA_COMPRAS".$anio.$mes.".txt";  
    $csv="";  

    // Leo los renglones

    // Traigo impuestos
    $query_impuestos= "SELECT * FROM impuestos";
    $impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO IMPUESTOS línea 53");
    $row_Recordset2 = mysqli_fetch_assoc($impuestos);
    $totalRows_Recordset2 = mysqli_num_rows($impuestos);
    
    $impuestos->data_seek(1);
    $row = $impuestos->fetch_array();
// Calcular los porcentajes de impuestos
    $porc_iva105 = $row[1]/ 100 ."<br>";
    $impuestos->data_seek(0);
    $row = $impuestos->fetch_array();
    $porc_iva21 = $row[1] / 100;

    // $porc_iva105 = $row_Recordset2['porcen'] / 100;
    // $porc_iva21 = $row_Recordset2['porcen'] / 100;


    // Leo la cabecera

    $query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecval BETWEEN %s AND %s ORDER BY fecval , nrodoc ", $fecha_desde, $fecha_hasta);

    $cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC línea 64");


    // Leo las Liquidaciones
    $query_liquidacion = sprintf("SELECT * FROM liquidacion WHERE fechaliq BETWEEN %s AND %s ORDER BY fechaliq , nrodoc ", $fecha_desde, $fecha_hasta);
    $t_liquidacion = mysqli_query($amercado, $query_liquidacion) or die("ERROR LEYENDO LIQUIDACION línea 69");
    $totalRows_liquidacion = mysqli_num_rows($t_liquidacion);


      // ACA ARMO EL RENGLON DE TITULOS : ===============================================
    $csv.="Fecha".$csv_sep."Nro Factura".$csv_sep."Razon Social".$csv_sep."CUIT".$csv_sep."Conceptos Exentos".$csv_sep."Conceptos Gravados".$csv_sep."IVA Credito Fiscal".$csv_sep."Alicuota diferencial".$csv_sep."Percepcion IVA".$csv_sep."Percep IIBB CABA".$csv_sep."Percep IIBB BsAs".$csv_sep."Total Facturado".$csv_end;



     $i = 0;
    $acum_total_neto21  = 0;
    $acum_total_neto105 = 0;
    $acum_total_iva21   = 0;
    $acum_total_neto = 0;
    $acum_total_iva   = 0;
    $acum_total_iva105  = 0;
    $acum_total_exento  = 0;
    $acum_tot_resol   = 0;
    $acum_total       = 0;
    $acum_df_retiva  = 0;
    $acum_df_retib_CABA = 0;
    $acum_df_retib_BSAS = 0;
    $df_retiva = 0.0;
    $df_retib_BSAS = 0.0;
    $df_retib_CABA  = 0.0;
    while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
    {	
        $tcomp      = $row_cabecerafac["tcomp"];
        $serie      = $row_cabecerafac["serie"];
        $ncomp      = $row_cabecerafac["ncomp"];

        if ($tcomp != FC_PROV_A && $tcomp != FC_PROV_C && $tcomp != ND_PROV_A && $tcomp != ND_PROV_C && $tcomp != NC_PROV_A && $tcomp != NC_PROV_C && $tcomp != FC_PROV_M && $tcomp != FC_PROV_LIQ)
            continue;
        if ($tcomp == FC_PROV_A || $tcomp == FC_PROV_C || $tcomp == ND_PROV_A || $tcomp == ND_PROV_C || $tcomp == FC_PROV_M || $tcomp == FC_PROV_LIQ) {
            $signo = 1;
        }
        else {
            $signo = -1;
        }
        if ($tcomp == FC_PROV_A || $tcomp == FC_PROV_C  || $tcomp == FC_PROV_M)
            $tc_desc = "Fc-";
        if ($tcomp == ND_PROV_A || $tcomp == ND_PROV_C)
            $tc_desc = "Nd-";
        if ($tcomp == NC_PROV_A || $tcomp == NC_PROV_C)
            $tc_desc = "Nc-";
        if ($tcomp ==  FC_PROV_LIQ)
            $tc_desc = "Lq-";

        if ($i < 22) {
            $query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
            $detallefac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC línea 119");
            $totalRows_detallefac = mysqli_num_rows($detallefac);
            $df_concnograv  = 0.00;
            $df_nograv  = 0.00;
            $df_retib_CABA  = 0.00;
            $df_retib_BSAS = 0.00;
            $df_retiva = 0.00;
            $df_neto25 = 0.0;
            $df_retiva_uni = 0.00;
            $df_retib_CABA_uni  = 0.00;
            $df_retib_BSAS_uni = 0.00;
            $df_neto25_uni = 0.0;
            $df_neto50_uni = 0.0;
            $df_neto105_uni = 0.0;
            $df_neto210_uni = 0.0;
            $df_neto270_uni = 0.0;
            $df_iva25 = 0.0;
            $df_neto50 = 0.0;
            $df_iva50 = 0.0;
            $df_neto105 = 0.0;
            $df_iva105 = 0.0;
            $df_neto210 = 0.0;
            $df_iva210 = 0.0;
            $df_neto270 = 0.0;
            $df_iva270 = 0.0;
            //$csv.="Exentos".$csv_sep."Gravados".$csv_sep."IVA C.F.".$csv_sep."Neto 21 %".$csv_sep."Neto 10,5 %".$csv_sep."Neto 27 %".$csv_sep."Neto 5 %".$csv_sep."Neto 2,5 %".$csv_sep."Percepcion IVA".$csv_sep."Percep IIBB CABA".$csv_sep."Percep IIBB BsAs".$csv_sep."Subtotal".$csv_end; 
            while($row_detallefac = mysqli_fetch_array($detallefac)) {
                    $concafac = $row_detallefac["concafac"];
                    if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA || $concafac == RET_GAN || $concafac == CONC_NO_GRAV || $concafac == IMP_INT || $concafac == TAS_CER || $concafac == RET_IIBB_SALTA || $concafac == RET_IIBB_STAFE || $concafac == RET_IIBB_CHACO || $concafac == RET_IIBB_CORRIENTES || $concafac == RET_IIBB_NEUQUEN || $concafac == RET_IIBB_SANLUIS || $concafac == RET_IIBB_CORDOBA || $concafac == RET_IIBB_JUJUY || $concafac == RET_IIBB_SJUAN  || $concafac == IMP_DIES || $concafac == COMB_LIQ || $concafac == SER_SOC || $concafac == TASA_SSN|| $concafac == SEL_CABA) {
                        switch($concafac) {
                            case	CONC_NO_GRAV:
                                $df_concnograv += $row_detallefac["neto"] * $signo;
                                break;
                            case	RET_IVA:
                                $df_retiva = $row_detallefac["neto"] * $signo;
                                break;
                            case 	RET_IIBB_BA:
                                $df_retib_BSAS  += $row_detallefac["neto"] * $signo;
                                break;
                            case 	RET_IIBB_CABA:
                            case 	RET_IIBB_SALTA:
                            case 	RET_IIBB_STAFE:
                            case 	RET_IIBB_CHACO:
                            case 	RET_IIBB_CORRIENTES:
                            case 	RET_IIBB_NEUQUEN:
                            case 	RET_IIBB_SANLUIS:
                            case    RET_IIBB_CORDOBA:
                            case    RET_IIBB_JUJUY:
                            case    RET_IIBB_SJUAN:
                                $df_retib_CABA  += $row_detallefac["neto"] * $signo;
                                break;
                            case	RET_GAN:
                            case	TAS_CER:
                            case	IMP_INT:
                            case	IMP_DIES:
                            case	COMB_LIQ:
                            case	SER_SOC:
                            case	TASA_SSN:
                            case	SEL_CABA:
                                $df_concnograv += $row_detallefac["neto"] * $signo;
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
            //$csv.=$df_nograv.$csv_sep.($row_detallefac["neto"] * $signo).$csv_sep.($row_detallefac["iva"] * $signo).$csv_sep.$df_neto210_uni.$csv_sep.$df_neto105_uni.$csv_sep.$df_neto270_uni.$csv_sep.$df_neto50_uni.$csv_sep.$df_neto25_uni.$csv_sep.$df_retiva_uni.$csv_sep.$df_retib_CABA_uni.$csv_sep.$df_retib_BSAS_uni.$csv_sep.(($row_detallefac["neto"] + $row_detallefac["iva"]) * $signo).$csv_end; 
            $df_retiva_uni = 0.00;
            $df_retib_CABA_uni  = 0.00;
            $df_retib_BSAS_uni = 0.00;
            $df_neto25_uni = 0.0;
            $df_neto50_uni = 0.0;
            $df_neto105_uni = 0.0;
            $df_neto210_uni = 0.0;
            $df_neto270_uni = 0.0;
            

            $fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
            $cliente      = $row_cabecerafac["cliente"];
            $tot_neto     = ($row_cabecerafac["totneto"]  * $signo) - $df_retib_BSAS - $df_retib_CABA - $df_retiva;
            //$tot_neto     = $row_cabecerafac["totneto"] * $signo;
            $tot_neto21   = ($row_cabecerafac["totneto21"]) * $signo;
            $tot_neto105  = $row_cabecerafac["totneto105"] * $signo;
            $tot_comision = $row_cabecerafac["totcomis"] * $signo;
            $tot_iva21    = $row_cabecerafac["totiva21"] * $signo;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21 ;
            $tot_iva105   = $row_cabecerafac["totiva105"]  * $signo;
            $tot_resol    = $row_cabecerafac["totimp"] * $signo;
            $total        = ($row_cabecerafac["totbruto"] * $signo) ;
            //$nroorig      = $row_cabecerafac["nrodoc"];
            $nroorig      = $tc_desc.$row_cabecerafac["nrodoc"];
            $total_neto   = $tot_neto;
            $total_iva    = ($tot_iva21  + $tot_iva105) ;
            $total_exento = 0;
            if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C)
                $total_exento += ($row_cabecerafac["totneto"] * $signo);

            if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C)
                $total_exento += $df_concnograv;
            $total_neto   = ($tot_neto - $total_exento) ;
            // Acumulo subtotales
            $acum_total_neto  = $acum_total_neto  + $total_neto;
            $acum_total_iva   = $acum_total_iva   + $total_iva;
            $acum_tot_resol   = $acum_tot_resol   + $tot_resol;
            $acum_total       = $acum_total       + $total;
            $acum_df_retiva   = $acum_df_retiva   + $df_retiva;
            $acum_df_retib_CABA = $acum_df_retib_CABA + $df_retib_CABA;
            $acum_df_retib_BSAS = $acum_df_retib_BSAS + $df_retib_BSAS;
            $acum_total_exento  = $acum_total_exento  + $total_exento;

            // Formateo los campos antes de imprimir
            $total_neto  = number_format($total_neto, 2, ',','.');
            $total_iva   = number_format($total_iva, 2, ',','.');
            $tot_resol   = number_format($tot_resol, 2, ',','.');
            $total       = number_format($total, 2, ',','.');
            //$df_retiva   = number_format($df_retiva, 2, ',','.');
            //$df_retib_CABA = number_format($df_retib_CABA, 2, ',','.');
            //$df_retib_BSAS = number_format($df_retib_BSAS, 2, ',','.');
            $total_exento  = number_format($total_exento, 2, ',','.');

            // Leo el cliente
            $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
            $enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
            $row_entidades = mysqli_fetch_assoc($enti);
            $nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
            $nro_cliente   = $row_entidades["numero"];
            $cuit_cliente  = $row_entidades["cuit"];


            // Imprimo los renglones DESDE ACA ARMO EL TXT

            //if ($nroorig == "A0035-00051454") echo "TOTAL NETO = ".$total_neto." SIGNO = ".$signo."  ";

            $csv.=$fecha.$csv_sep.$nroorig.$csv_sep.$nom_cliente.$csv_sep.$cuit_cliente.$csv_sep.$total_exento.$csv_sep.$total_neto.$csv_sep.$total_iva.$csv_sep." ".$csv_sep.$df_retiva.$csv_sep.$df_retib_CABA.$csv_sep.$df_retib_BSAS.$csv_sep.$total.$csv_end;
            //$csv.="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx".$csv_end;


            $total_exento  = 0.00;

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

            // reinicio los contadores

            $i = 0;
            //================================================================================
            $query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
            $detallefac = mysqli_query($amercado, $query_detfac) or die(mysqli_error($amercado));
            $totalRows_detallefac = mysqli_num_rows($detallefac);
            $df_concnograv = 0.00;
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
            $csv.="Exentos".$csv_sep."Gravados".$csv_sep."IVA C.F.".$csv_sep."Neto 21 %".$csv_sep."Neto 10,5 %".$csv_sep."Neto 27 %".$csv_sep."Neto 5 %".$csv_sep."Neto 2,5 %".$csv_sep."Percepcion IVA".$csv_sep."Percep IIBB CABA".$csv_sep."Percep IIBB BsAs".$csv_sep."Subtotal".$csv_end; 
            while($row_detallefac = mysqli_fetch_array($detallefac)) {
                $concafac = $row_detallefac["concafac"];
                    if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA || $concafac == RET_GAN || $concafac == CONC_NO_GRAV || $concafac == IMP_INT || $concafac == TAS_CER || $concafac == RET_IIBB_SALTA || $concafac == RET_IIBB_STAFE || $concafac == RET_IIBB_CHACO || $concafac == RET_IIBB_CORRIENTES || $concafac == RET_IIBB_NEUQUEN || $concafac == RET_IIBB_SANLUIS || $concafac == RET_IIBB_CORDOBA || $concafac == RET_IIBB_JUJUY || $concafac == RET_IIBB_SJUAN  || $concafac == IMP_DIES || $concafac == COMB_LIQ || $concafac == SER_SOC || $concafac == TASA_SSN|| $concafac == SEL_CABA) {
                        switch($concafac) {
                            case	CONC_NO_GRAV:
                                $df_concnograv += $row_detallefac["neto"] * $signo;
                                break;
                            case	RET_IVA:
                                $df_retiva = $row_detallefac["neto"] * $signo;
                                break;
                            case 	RET_IIBB_BA:
                                $df_retib_BSAS  += $row_detallefac["neto"] * $signo;
                                break;
                            case 	RET_IIBB_CABA:
                            case 	RET_IIBB_SALTA:
                            case 	RET_IIBB_STAFE:
                            case 	RET_IIBB_CHACO:
                            case 	RET_IIBB_CORRIENTES:
                            case 	RET_IIBB_NEUQUEN:
                            case 	RET_IIBB_SANLUIS:
                            case    RET_IIBB_CORDOBA:
                            case    RET_IIBB_JUJUY:
                            case    RET_IIBB_SJUAN:
                                $df_retib_CABA  += $row_detallefac["neto"] * $signo;
                                break;
                            case	RET_GAN:
                            case	TAS_CER:
                            case	IMP_INT:
                            case	IMP_DIES:
                            case	COMB_LIQ:
                            case	SER_SOC:
                            case	TASA_SSN:
                            case	SEL_CABA:
                                $df_concnograv += $row_detallefac["neto"] * $signo;
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
                 $csv.=$df_nograv.$csv_sep.($row_detallefac["neto"] * $signo).$csv_sep.($row_detallefac["iva"] * $signo).$csv_sep.$df_neto210_uni.$csv_sep.$df_neto105_uni.$csv_sep.$df_neto270_uni.$csv_sep.$df_neto50_uni.$csv_sep.$df_neto25_uni.$csv_sep.$df_retiva_uni.$csv_sep.$df_retib_CABA_uni.$csv_sep.$df_retib_BSAS_uni.$csv_sep.(($row_detallefac["neto"] + $row_detallefac["iva"]) * $signo).$csv_end; 
                $df_retiva_uni = 0.00;
                $df_retib_CABA_uni  = 0.00;
                $df_retib_BSAS_uni = 0.00;
                $df_neto25_uni = 0.0;
                $df_neto50_uni = 0.0;
                $df_neto105_uni = 0.0;
                $df_neto210_uni = 0.0;
                $df_neto270_uni = 0.0;
            }

            $fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
            $cliente      = $row_cabecerafac["cliente"];
            $tot_neto     = ($row_cabecerafac["totneto"]  * $signo) - $df_retib_BSAS - $df_retib_CABA - $df_retiva;
            //$tot_neto     = $row_cabecerafac["totneto"]  * $signo;
            $tot_neto21   = ($row_cabecerafac["totneto21"] ) * $signo ;
            $tot_neto105  = $row_cabecerafac["totneto105"] * $signo;
            $tot_comision = $row_cabecerafac["totcomis"] * $signo;
            $tot_iva21    = $row_cabecerafac["totiva21"] * $signo; 
            $tot_iva105   = $row_cabecerafac["totiva105"] * $signo;
            $tot_resol    = $row_cabecerafac["totimp"] * $signo;
            $total        = $row_cabecerafac["totbruto"] * $signo;
            $nroorig      = $row_cabecerafac["nrodoc"];
            $total_neto   = $tot_neto;
            $total_iva    = ($tot_iva21  + $tot_iva105) ;
            $total_exento = 0;
            if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C)
                $total_exento += ($row_cabecerafac["totneto"] * $signo);

            if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C)
                $total_exento += $df_concnograv;

            $total_neto   = ($tot_neto - $total_exento) ;

            // Acumulo subtotales
            $acum_total_neto  = $acum_total_neto  + $total_neto;
            $acum_total_iva   = $acum_total_iva   + $total_iva;
            $acum_tot_resol   = $acum_tot_resol   + $tot_resol;
            $acum_total       = $acum_total       + $total;
            $acum_df_retiva   = $acum_df_retiva   + $df_retiva;
            $acum_df_retib_CABA = $acum_df_retib_CABA + $df_retib_CABA;
            $acum_df_retib_BSAS = $acum_df_retib_BSAS + $df_retib_BSAS;
            $acum_total_exento  = $acum_total_exento  + $total_exento;

            // Formateo los campos antes de imprimir
            $total_neto  = number_format($total_neto, 2, ',','.');
            $total_iva   = number_format($total_iva, 2, ',','.');
            $tot_resol   = number_format($tot_resol, 2, ',','.');
            $total       = number_format($total, 2, ',','.');
            //$df_retiva   = number_format($df_retiva, 2, ',','.');
            //$df_retib_CABA = number_format($df_retib_CABA, 2, ',','.');
            //$df_retib_BSAS = number_format($df_retib_BSAS, 2, ',','.');
            $total_exento  = number_format($total_exento, 2, ',','.');

            // Leo el cliente
            $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
            $enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
            $row_entidades = mysqli_fetch_assoc($enti);
            $nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
            $nro_cliente   = $row_entidades["numero"];
            $cuit_cliente  = $row_entidades["cuit"];

            // Imprimo los renglones

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

    /* LO DEJO POR SI NECESITAN LOS ACUMULADOS		
    $pdf->SetY($valor_y);
    $pdf->Cell(54);
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
    $df_retiva  = 0.00;
    $df_retib_CABA = 0.00;
    $df_retib_BSAS = 0.00;
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
            $tot_iva21    = $row_liquidacion["totiva21"];
            $tot_iva105   = $row_liquidacion["totiva105"];
            $tot_resol    = 0.00;
            $total        = $row_liquidacion["subtot1"] + $row_liquidacion["subtot2"];
            $nroorig      = $row_liquidacion["nrodoc"];
            $total_neto   = $tot_neto21 + $tot_neto105;
            $total_iva    = $tot_iva21  + $tot_iva105;
            $estado       = $row_liquidacion["estado"];


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
                $df_retiva   = 0.00;//number_format($df_retiva, 2, ',','.');
                $df_retib_CABA = 0.00;//number_format($df_retib, 2, ',','.');
                $df_retib_BSAS = 0.00;//number_format($df_retgan, 2, ',','.');

                // Leo el cliente
                $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
                $enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
                $row_entidades = mysqli_fetch_assoc($enti);
                $nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
                $nro_cliente   = $row_entidades["numero"];
                $cuit_cliente  = $row_entidades["cuit"];

                // Imprimo los renglones

                $csv.=$fecha.$csv_sep.$nroorig.$csv_sep.$nom_cliente.$csv_sep.$cuit_cliente.$csv_sep." ".$csv_sep.$total_neto.$csv_sep.$total_iva.$csv_sep." ".$csv_sep.$df_retiva.$csv_sep.$df_retib_CABA.$csv_sep.$df_retib_BSAS.$csv_sep.$total.$csv_end;

            }
            else {
                // Imprimo los renglones

                $csv.=$fecha.$csv_sep.$nroorig.$csv_sep."ANULADA".$csv_sep." ".$csv_sep." ".$csv_sep."0,00".$csv_sep."0,00".$csv_sep." ".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_end;

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

            // Voy a otra hoja e imprimo los titulos 

            $i = 0;

            // Imprimo el primer renglón que ya lo había leído
            $tcomp	      = $row_liquidacion["tcomp"];
            $serie	      = $row_liquidacion["serie"];
            $ncomp	      = $row_liquidacion["ncomp"];
            $fecha        = substr($row_liquidacion["fechaliq"],8,2)."-".substr($row_liquidacion["fechaliq"],5,2)."-".substr($row_liquidacion["fechaliq"],0,4);
            $cliente      = $row_liquidacion["cliente"];
            $tot_neto21   = $row_liquidacion["totneto1"];
            $tot_neto105  = $row_liquidacion["totneto2"];
            $tot_comision = $row_liquidacion["totcomis"];
            $tot_iva21    = $row_liquidacion["totiva21"];
            $tot_iva105   = $row_liquidacion["totiva105"];
            $tot_resol    = 0.00;
            $total        = $row_liquidacion["subtot1"] + $row_liquidacion["subtot2"];
            $nroorig      = $row_liquidacion["nrodoc"];
            $total_neto   = $tot_neto21 + $tot_neto105;
            $total_iva    = $tot_iva21  + $tot_iva105;
            $estado       = $row_liquidacion["estado"];

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
                $df_retiva   = number_format($df_retiva, 2, ',','.');
                $df_retib_CABA = number_format($df_retib_CABA, 2, ',','.');
                $df_retib_BSAS = number_format($df_retib_BSAS, 2, ',','.');

                // Leo el cliente
                $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
                $enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES línea 606");
                $row_entidades = mysqli_fetch_assoc($enti);
                $nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
                $nro_cliente   = $row_entidades["numero"];
                $cuit_cliente  = $row_entidades["cuit"];

                // Imprimo los renglones

            }
            else {
                // Imprimo los renglones ANULADOS

            }
                // ======================= HASTA ACA =====================================


        }

    }	
    // Imprimo totales generales de la hoja la última vez 
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
    /* POR LAS DUDAS 		
    $pdf->SetY($valor_y);
    $pdf->Cell(54);
    $pdf->Cell(58,6,"TOTAL LIQUIDACIONES  ",0,0,'L');
    $pdf->Cell(22,6," ",0,0,'R');
    $pdf->Cell(22,6,$f_acum_total_neto,0,0,'R');
    $pdf->Cell(22,6,$f_acum_total_iva,0,0,'R');
    $pdf->Cell(22,6," ",0,0,'R');
    $pdf->Cell(22,6,$f_acum_df_retiva,0,0,'R');
    $pdf->Cell(22,6,$f_acum_df_retib,0,0,'R');
    $pdf->Cell(22,6,$f_acum_df_retgan,0,0,'R');
    $pdf->Cell(22,6,$f_acum_total,0,0,'R');

    $valor_y = $valor_y + 6;

    */	
    $t_acum_total_neto  = number_format($t_acum_total_neto, 2, ',','.');
    $t_acum_total_iva   = number_format($t_acum_total_iva, 2, ',','.');
    $t_acum_tot_resol   = number_format($t_acum_tot_resol, 2, ',','.');
    $t_acum_total       = number_format($t_acum_total, 2, ',','.');
    $t_acum_df_retiva   = number_format($t_acum_df_retiva, 2, ',','.');
    $t_acum_df_retib_CABA = number_format($t_acum_df_retib_CABA, 2, ',','.');
    $t_acum_df_retib_BSAS = number_format($t_acum_df_retib_BSAS, 2, ',','.');
    $t_acum_total_exento = number_format($t_acum_total_exento, 2, ',','.');
    /*		
    $pdf->SetY($valor_y);
    $pdf->Cell(54);
    $pdf->Cell(58,6,"TOTAL GENERAL  ",0,0,'L');
    $pdf->Cell(22,6,$t_acum_total_exento,0,0,'R');
    $pdf->Cell(22,6,$t_acum_total_neto,0,0,'R');
    $pdf->Cell(22,6,$t_acum_total_iva,0,0,'R');
    $pdf->Cell(22,6," ",0,0,'R');
    $pdf->Cell(22,6,$t_acum_df_retiva,0,0,'R');
    $pdf->Cell(22,6,$t_acum_df_retib,0,0,'R');
    $pdf->Cell(22,6,$t_acum_df_retgan,0,0,'R');
    $pdf->Cell(22,6,$t_acum_total,0,0,'R');

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
    echo "<BR>"."<BR>"."<BR>"."<BR>"."<BR>"."<BR>"."<BR>";
    echo "<BR>"."======================== SE GENERÓ CORRECTAMENTE EL ARCHIVO ".$csv_file."========================"."<BR>";  
    */
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
?>  