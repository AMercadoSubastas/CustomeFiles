<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];
//$cant_cli    = $_POST['cant_cli'];
$cli         = $_POST['codnum'];

$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");

// Leo las cabeceras de facturas
$pe = "P";
$ese = "S";
$query_cabfac = sprintf("SELECT * FROM cabfac WHERE cliente = %s AND fecdoc BETWEEN %s AND %s  ORDER BY cliente, fecreg, nrodoc", $cli, $fecha_desde, $fecha_hasta);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC ".$query_cabfac);

// Inicio el pdf con los datos de cabecera
$pdf=new FPDF('P','mm','Legal');

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
$pdf->Cell(70);
$pdf->Cell(20,10,' D�as de Mora por Cliente',0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(10);
$pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'C');
$pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'C');
$pdf->Cell(30,8,' D�as  ',1,0,'C');
$pdf->Cell(30,8,'  Factura   ',1,0,'C');
$pdf->Cell(30,8,' Importe ',1,0,'C');
  
$valor_y = 45;
  
// Datos de los renglones
$i = 0;

$acum_cli    = array();
$dias_cli     = array();
$cant_cbtes_cli = array();
$avg_cli     = array();
$nom_cliente = array();
$cuit_cliente = array();
$total       = 0.00;
$acum_total  = 0.00;
$cant_dias   = 0;
		
        

for ($arr= 0;$arr < 15500;$arr++) {
	$acum_cli[$arr] = 0.00;
    $dias_cli[$arr] = 0;
    $cant_cbtes_cli[$arr] = 0;
    $avg_cli[$arr] = 0;
    $nro_cliente[$arr] = 0;
    $nom_cliente[$arr] = "";
    $tot_cli[$arr] = 0.00;
    $cuit_cliente[$arr] = "";
    $porc_cli[$arr] = 0;
}
$lin = 1;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
    $tcomp      = $row_cabecerafac["tcomp"];
    if ($tcomp!=  51 &&  $tcomp != 52 && $tcomp != 53 && $tcomp != 54 && $tcomp != 89 && $tcomp != 92 && $tcomp != 104 && $tcomp != 111 && $tcomp != 112 ) {
		continue;
	}
    
	$estado = $row_cabecerafac["estado"];
    
	switch($estado) {
        case "S":
            $cliente    = $row_cabecerafac["cliente"];
            $totneto105 = $row_cabecerafac["totneto105"];
            $totneto21  = $row_cabecerafac["totneto21"];
            $totcomis   = $row_cabecerafac["totcomis"];

            
            $cliente = $row_cabecerafac["cliente"];
            $total   = 0.00; //($row_cabecerafac["totneto105"] + $row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]);

            // Acumulo subtotales

            $acum_cli[$cliente] = $acum_cli[$cliente] + $total;
            $acum_total         = $acum_total + $total;
            $cabfac_tcomp = $row_cabecerafac["tcomp"];
            $cabfac_ncomp = $row_cabecerafac["ncomp"];
            $cabfac_remate = $row_cabecerafac["codrem"];
            //LEO LA FECHA DE PAGO
            $query_recibo = "SELECT * FROM detrecibo WHERE tcomprel =  $cabfac_tcomp AND ncomprel = $cabfac_ncomp";
            $recibo = mysqli_query($amercado, $query_recibo) or die("ERROR LEYENDO DETRECIBO ".$query_recibo);
            $row_recibo = mysqli_fetch_assoc($recibo);
            $recibo_ncomp = $row_recibo["ncomp"];
            //echo "RECIBO NRO= ".$recibo_ncomp." ";
            if (isset($recibo_ncomp)) {
                $query_cabrecibo = "SELECT * FROM cabrecibo WHERE ncomp = $recibo_ncomp";
                $cabrecibo = mysqli_query($amercado, $query_cabrecibo) or die("ERROR LEYENDO CABRECIBO ".$query_cabrecibo);
                $row_cabrecibo = mysqli_fetch_assoc($cabrecibo);
                // VERIFICO SI LA FACTURA VIENE DE UNA ANTERIOR
                $query_remate = sprintf("SELECT * FROM remates WHERE observacion LIKE %s", "\"%$cabfac_remate%\"");
                $remate = mysqli_query($amercado, $query_remate) or die("ERROR LEYENDO REMATE ".$query_remate);
                $row_remate = mysqli_fetch_assoc($remate);


                $datetime1 = date_create($row_cabrecibo["fecha"]);
                $datetime2 = date_create($row_cabecerafac["fecreg"]);
                $contador  = date_diff($datetime1, $datetime2);
                $differenceFormat = '%a';
                $cant_dias = $contador->format($differenceFormat);
                //$cant_dias = $row_cabrecibo["fecha"] - $row_cabecerafac["fecreg"];
                //echo "CASE S CLIENTE = ".$cliente." FECHA RECIBO = ".$row_cabrecibo["fecha"]." FECHA FACTURA = ".$row_cabecerafac["fecreg"]." CANT DIAS = ".$cant_dias." - ";
                $dias_cli[$cliente] += $cant_dias;
                $cant_cbtes_cli[$cliente] += 1;
                
                
                
  					
                if ($lin < 40) {
                    $pdf->SetY($valor_y);
                    $pdf->Cell(1);
                    $pdf->SetX(10);
                    $pdf->Cell(30,6,"REC: ".$row_cabrecibo["fecha"],0,0,'L');
                    $pdf->Cell(30,6,"FAC: ".$row_cabecerafac["fecreg"],0,0,'L');
                    $pdf->Cell(30,6,"DIAS: ".$cant_dias,0,0,'L');
                    $pdf->Cell(30,6,"FC: ".$row_cabecerafac["nrodoc"],0,0,'R');
                    $pdf->Cell(30,6,$row_cabecerafac["totbruto"],0,0,'R');
                    $valor_y = $valor_y + 6; 
                    $lin++;
                }
                else {
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
                    $pdf->Cell(70);
                    $pdf->Cell(20,10,' D�as de Mora por Cliente',0,0,'L');
                    $pdf->SetFont('Arial','B',9);
                    $pdf->SetY(25);
                    $pdf->Cell(10);
                    $pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'C');
                    $pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'C');
                    $pdf->Cell(30,8,' D�as  ',1,0,'C');
                    $pdf->Cell(30,8,'  Factura   ',1,0,'C');
                    $pdf->Cell(30,8,' Importe ',1,0,'C');
                    $valor_y = 45;
                    $lin = 1;
                    $pdf->SetY($valor_y);
                    $pdf->Cell(1);
                    $pdf->SetX(10);
                    $pdf->Cell(30,6,"REC: ".$row_cabrecibo["fecha"],0,0,'L');
                    $pdf->Cell(30,6,"FAC: ".$row_cabecerafac["fecreg"],0,0,'L');
                    $pdf->Cell(30,6,"DIAS: ".$cant_dias,0,0,'L');
                    $pdf->Cell(30,6,"FC: ".$row_cabecerafac["nrodoc"],0,0,'R');
                    $pdf->Cell(30,6,$row_cabecerafac["totbruto"],0,0,'R');
                    $valor_y = $valor_y + 6; 
                    $lin++;
                }
               
            }
            else 
                continue;
            break;
        case "C":
            // ESTO QUIERE DECIR QUE TIENE UNA NOTA DE CREDITO ASOCIADA
            // TOMO LOS DIAS ENTRE LA FACTURA Y LA NOTA DE CREDITO
            $cliente    = $row_cabecerafac["cliente"];
            $totneto105 = $row_cabecerafac["totneto105"];
            $totneto21  = $row_cabecerafac["totneto21"];
            $totcomis   = $row_cabecerafac["totcomis"];

            
            $cliente = $row_cabecerafac["cliente"];
            $total   = 0.00; //($row_cabecerafac["totneto105"] + $row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]);

            // Acumulo subtotales

            $acum_cli[$cliente] = $acum_cli[$cliente] + $total;
            $acum_total         = $acum_total + $total;

            
            // LEO DETFAC PARA LEER LA N CRED
            $cabfac_nrodoc = $row_cabecerafac["nrodoc"];
            $query_detfnc = sprintf("SELECT * FROM detfac WHERE descrip like  %s", "\"%$cabfac_nrodoc%\"");
            $detfnc = mysqli_query($amercado, $query_detfnc) or die("ERROR LEYENDO DETFAC ".$query_detfnc);
            $row_detfnc = mysqli_fetch_assoc($detfnc);
            $ncred_ncomp = $row_detfnc["ncomp"];
            $ncred_serie = $row_detfnc["serie"];
            if ($ncred_serie == 29) 
                $ncred_tcomp = 57;
            else
                $ncred_tcomp = 58;
            //echo "NCRED_NCOMP = ".$ncred_ncomp." ";
            // LEO CABFAC PARA VER LA FECHA
            $query_cabfnc = sprintf("SELECT * FROM cabfac WHERE ncomp =  %s and tcomp = %s", $ncred_ncomp, $ncred_tcomp);
            $cabfnc = mysqli_query($amercado, $query_cabfnc) or die("ERROR LEYENDO CABFAC ".$query_cabfnc);
            $row_cabfnc = mysqli_fetch_assoc($cabfnc);
            $ncred_fecha = $row_cabfnc["fecreg"];
            
            $datetime1 = date_create($row_cabecerafac["fecreg"]);
            $datetime2 = date_create($row_cabfnc["fecreg"]);
            $contador  = date_diff($datetime1, $datetime2);
            $differenceFormat = '%a';
            $cant_dias = $contador->format($differenceFormat);
            //$cant_dias = $row_cabrecibo["fecha"] - $row_cabecerafac["fecreg"];
            //echo "CASE C CLIENTE = ".$cliente." FECHA N CRED = ".$row_cabfnc["fecreg"]." FECHA FACTURA = ".$row_cabecerafac["fecreg"]." CANT DIAS = ".$cant_dias." - ";
            $dias_cli[$cliente] += $cant_dias;
            $cant_cbtes_cli[$cliente] += 1;
            
            

            if ($lin < 40) {
                $pdf->SetY($valor_y);
                $pdf->Cell(1);
                $pdf->SetX(10);
                $pdf->Cell(30,6,"FAC: ".$row_cabecerafac["fecreg"],0,0,'L');
                $pdf->Cell(30,6,"NCr: ".$row_cabfnc["fecreg"],0,0,'L');
                $pdf->Cell(30,6,"DIAS: ".$cant_dias,0,0,'L');
                $pdf->Cell(30,6,"FC: ".$row_cabecerafac["nrodoc"],0,0,'R');
                $pdf->Cell(30,6,$row_cabecerafac["totbruto"],0,0,'R');
                $valor_y = $valor_y + 6;
                $lin++;
            }
            else {
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
                $pdf->Cell(70);
                $pdf->Cell(20,10,' D�as de Mora por Cliente',0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->SetY(25);
                $pdf->Cell(10);
                $pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'C');
                $pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'C');
                $pdf->Cell(30,8,' D�as  ',1,0,'C');
                $pdf->Cell(30,8,'  Factura   ',1,0,'C');
                $pdf->Cell(30,8,' Importe ',1,0,'C');
                $valor_y = 45;
                $lin = 1;
                $pdf->SetY($valor_y);
                $pdf->Cell(1);
                $pdf->SetX(10);
                $pdf->Cell(30,6,"FAC: ".$row_cabecerafac["fecreg"],0,0,'L');
                $pdf->Cell(30,6,"NCr: ".$row_cabfnc["fecreg"],0,0,'L');
                $pdf->Cell(30,6,"DIAS: ".$cant_dias,0,0,'L');
                $pdf->Cell(30,6,"FC: ".$row_cabecerafac["nrodoc"],0,0,'R');
                $pdf->Cell(30,6,$row_cabecerafac["totbruto"],0,0,'R');
                $valor_y = $valor_y + 6;
                $lin++;
            }
            
            break;
        case "P":
            // SI ESTA PENDIENTE TOMO LA CANTIDAD DE DIAS ENTRE LA FACTURA Y LA FECHA DE HOY
            $cliente    = $row_cabecerafac["cliente"];
            $totneto105 = $row_cabecerafac["totneto105"];
            $totneto21  = $row_cabecerafac["totneto21"];
            $totcomis   = $row_cabecerafac["totcomis"];
            
            $cliente = $row_cabecerafac["cliente"];
            $total   = ($row_cabecerafac["totneto105"] + $row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]);

            // Acumulo subtotales
            $acum_cli[$cliente] = $acum_cli[$cliente] + $total;
            $acum_total         = $acum_total + $total;
            $datetime1 = date_create($row_cabecerafac["fecreg"]);
            $datetime2 = date_create(date("Y-m-d"));
            $contador  = date_diff($datetime1, $datetime2);
            $differenceFormat = '%a';
            $cant_dias = $contador->format($differenceFormat);
            //$cant_dias = $row_cabrecibo["fecha"] - $row_cabecerafac["fecreg"];
            //echo "CASE P CLIENTE = ".$cliente." FECHA FACTURA = ".$row_cabecerafac["fecreg"]." FECHA HOY = ".date("Y-m-d")." CANT DIAS = ".$cant_dias." - ";
            $dias_cli[$cliente] += $cant_dias;
            $cant_cbtes_cli[$cliente] += 1;
            
            

            if ($lin < 40) {
                $pdf->SetY($valor_y);
                $pdf->Cell(1);
                $pdf->SetX(10);
                $pdf->Cell(30,6,"FAC: ".$row_cabecerafac["fecreg"],0,0,'L');
                $pdf->Cell(30,6,"HOY: ".date("Y-m-d"),0,0,'L');
                $pdf->Cell(30,6,"DIAS: ".$cant_dias,0,0,'L');
                $pdf->Cell(30,6,"FC: ".$row_cabecerafac["nrodoc"],0,0,'R');
                $pdf->Cell(30,6,$row_cabecerafac["totbruto"],0,0,'R');
                $valor_y = $valor_y + 6;
                $lin++;
            }
            else {
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
                $pdf->Cell(70);
                $pdf->Cell(20,10,' D�as de Mora por Cliente',0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->SetY(25);
                $pdf->Cell(10);
                $pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'C');
                $pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'C');
                $pdf->Cell(30,8,' D�as  ',1,0,'C');
                $pdf->Cell(30,8,'  Factura   ',1,0,'C');
                $pdf->Cell(30,8,' Importe ',1,0,'C');
                $valor_y = 45;
                $lin = 1;
                $pdf->SetY($valor_y);
                $pdf->Cell(1);
                $pdf->SetX(10);
                $pdf->Cell(30,6,"FAC: ".$row_cabecerafac["fecreg"],0,0,'L');
                $pdf->Cell(30,6,"HOY: ".date("Y-m-d"),0,0,'L');
                $pdf->Cell(30,6,"DIAS: ".$cant_dias,0,0,'L');
                $pdf->Cell(30,6,"FC: ".$row_cabecerafac["nrodoc"],0,0,'R');
                $pdf->Cell(30,6,$row_cabecerafac["totbruto"],0,0,'R');
                $valor_y = $valor_y + 6;
                $lin++;
            }
            break;
    }
}
	
$i = 1;

// Leo los clientes
$tipoent = 1;
$query_entidades = sprintf("SELECT * FROM entidades WHERE  tipoent = %s ORDER BY codnum", $tipoent);
$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES ".$query_entidades);
//echo " -  0 termino cabfac  -  ";	
while($row_entidades = mysqli_fetch_array($enti))
{	

			if ($cant_cbtes_cli[$row_entidades["codnum"]] == 0 || $row_entidades["codnum"] != $cli)
                continue;

  			$nom_cliente[$row_entidades["codnum"]]   = substr($row_entidades["razsoc"], 0, 30);
  			$nro_cliente[$row_entidades["codnum"]]   = $row_entidades["codnum"];
  			$cuit_cliente[$row_entidades["codnum"]]  = $row_entidades["cuit"];
			$tot_cli[$row_entidades["codnum"]]       = $acum_cli[$row_entidades["codnum"]];
			$porc_cli[$row_entidades["codnum"]]      = $acum_cli[$row_entidades["codnum"]] * 100.0 / $acum_total;
            if ($cant_cbtes_cli[$row_entidades["codnum"]] != 0)
                $avg_cli[$row_entidades["codnum"]]       = round($dias_cli[$row_entidades["codnum"]] / $cant_cbtes_cli[$row_entidades["codnum"]]);
            else 
                $avg_cli[$row_entidades["codnum"]] = 0;
            //echo "i = ".$i." cliente = ".$nom_cliente[$row_entidades["codnum"]]." dias_cli = ".$dias_cli[$row_entidades["codnum"]]." cant_cbtes = ".$cant_cbtes_cli[$row_entidades["codnum"]]."avg = ".$avg_cli[$row_entidades["codnum"]]." - ";
			$i++;



} 	
$nom_cliente_aux   = "";
$nro_cliente_aux   = 0;
$cuit_cliente_aux  = "";
$tot_cli_aux       = 0.00;
$porc_cli_aux       = 0.00;
$avg_cli_aux       = 0;

// ACA ORDENO LOS QUE ME QUEDARON POR IMPORTE
for ($vueltas=1;$vueltas < 15500;$vueltas++) 
    for ($j=0;$j < 15500;$j++) {
        if (isset($avg_cli[$j+1]) && isset($tot_cli[$j]) && $avg_cli[$j] < $avg_cli[$j+1]) {
            $nom_cliente_aux   = $nom_cliente[$j];
            $nro_cliente_aux   = $nro_cliente[$j];
            $cuit_cliente_aux  = $cuit_cliente[$j];
            $tot_cli_aux       = $tot_cli[$j];
            $porc_cli_aux      = $porc_cli[$j];
            $avg_cli_aux       = $avg_cli[$j];

            $nom_cliente[$j]   = $nom_cliente[$j+1];
            $nro_cliente[$j]   = $nro_cliente[$j+1];
            $cuit_cliente[$j]  = $cuit_cliente[$j+1];
            $tot_cli[$j]       = $tot_cli[$j+1];
            $porc_cli[$j]      = $porc_cli[$j+1];
            $avg_cli[$j]       = $avg_cli[$j+1];

            $nom_cliente[$j+1]   = $nom_cliente_aux;
            $nro_cliente[$j+1]   = $nro_cliente_aux;
            $cuit_cliente[$j+1]  = $cuit_cliente_aux;
            $tot_cli[$j+1]       = $tot_cli_aux;
            $porc_cli[$j+1]      = $porc_cli_aux;
            $avg_cli[$j+1]       = $avg_cli_aux;


        }
        $nom_cliente_aux   = "";
        $nro_cliente_aux   = 0;
        $cuit_cliente_aux  = "";
        $tot_cli_aux       = 0.00;
        $porc_cli_aux       = 0.00;
        $avg_cli_aux       = 0;
    }
$linea = 0;

for ($j=0;$j < 15500 ;$j++) {
	if (!isset($tot_cli[$j]) || $avg_cli[$j] == 0)
        continue;
	if ($linea < 48) {
			$tot_cli[$j]  = number_format($tot_cli[$j], 2, ',','.');
			$porc_cli[$j] = number_format($porc_cli[$j], 2, ',','.');

			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->SetX(10);
  			$pdf->Cell(60,6,$nom_cliente[$j],0,0,'L');
  			$pdf->Cell(22,6,$cuit_cliente[$j],0,0,'L');
			$pdf->Cell(40,6,$tot_cli[$j],0,0,'R');
  			$pdf->Cell(40,6,$porc_cli[$j],0,0,'R');
            $pdf->Cell(30,6,$avg_cli[$j]." dias",0,0,'R');
  					
			$valor_y = $valor_y + 6;
			$linea++;
	}
	else {
		$linea = 0;
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
		$pdf->Cell(70);
		$pdf->Cell(20,10,' D�as de Mora por Cliente',0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(10);
		$pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'L');
        $pdf->Cell(30,8,' Cbte-Fecha  ',1,0,'L');
        $pdf->Cell(30,8,' D�as  ',1,0,'L');
        $pdf->Cell(30,8,'  Factura   ',1,0,'L');
        $pdf->Cell(30,8,' Importe ',1,0,'L');

		$valor_y = 45;
        
        $tot_cli[$j] = number_format($tot_cli[$j], 2, ',','.');
        $porc_cli[$j] = number_format($porc_cli[$j], 2, ',','.');
						
        $pdf->SetY($valor_y);
        $pdf->Cell(1);
        $pdf->SetX(10);
        $pdf->Cell(60,6,$nom_cliente[$j],0,0,'L');
        $pdf->Cell(22,6,$cuit_cliente[$j],0,0,'L');
        $pdf->Cell(40,6,$tot_cli[$j],0,0,'R');
        $pdf->Cell(40,6,$porc_cli[$j],0,0,'R');
        $pdf->Cell(30,6,$avg_cli[$j]." dias",0,0,'R');

        $valor_y = $valor_y + 6;
        $linea++;
	}
}

// Imprimo subtotales de la hoja la �ltima vez
//echo " -  4 total  -  ";
$acum_total       = number_format($acum_total, 2, ',','.');

		
$pdf->SetY($valor_y);
$pdf->Cell(80);
$pdf->Cell(26,6,"TOTAL GRAL:  ",0,0,'R');

$pdf->Cell(26,6,$acum_total,0,0,'R');
		
mysqli_close($amercado);
$pdf->Output();
?>