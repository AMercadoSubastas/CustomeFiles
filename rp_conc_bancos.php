<?php
set_time_limit(0); // Para evitar el timeout
require_once('fpdf17/fpdf.php');

require_once('numaletras.php');
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
	$pdf=new FPDF('L','mm','A4');
	
	$pdf->AddPage('L');
	$pdf->SetMargins(0.5, 0.5 , 0.5);
  	$pdf->SetFont('Arial','B',11);
  	$pdf->SetY(5);
  	$pdf->Cell(10);
  	$pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
    $pdf->Cell(205);
    $pagina = $pdf->PageNo();
    $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
    $pdf->SetY(10);
    $pdf->Cell(235);
    $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
    $pdf->SetY(18);
    $pdf->Cell(90);
  	$pdf->Cell(20,6,'  Medios de pago desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');
  	$pdf->SetFont('Arial','B',9);
  	$pdf->SetY(25);
    $pdf->Cell(10);
    $pdf->Cell(30,8,' Tipo cbte ',1,0,'C');
    $pdf->Cell(40,8,' Banco ',1,0,'C');
    $pdf->Cell(30,8,' Ingreso ',1,0,'C');
    $pdf->Cell(30,8,' Egreso ',1,0,'C');
    $pdf->Cell(30,8,' Detalle ',1,0,'C');
    $pdf->Cell(20,8,' Fecha ',1,0,'C');
    $pdf->Cell(60,8,'Cbte. relacionado ',1,0,'C');
    $pdf->Cell(40,8,' Cliente ',1,0,'C');
    $pdf->SetY(33);
  	$pdf->Cell(115);
  	$pdf->SetFont('Arial','B',8);  
  	$valor_y = 35;
  
	$i = 60;
	$j = 100;
	$cant_reng  = 0;
    $ingreso = $egreso = 0;
	$tipoenti=1;
	
	// Leo cartvalores
	$query_cartval = sprintf("SELECT * FROM cartvalores WHERE  fechapago BETWEEN %s AND %s ORDER BY fechapago ", $fecha_desde, $fecha_hasta);
	$cartval = mysqli_query($amercado, $query_cartval) or die("ERROR LEYENDO CARTVALORES");


	while($row_cartval = mysqli_fetch_array($cartval)) {
			$fechapago = $row_cartval["fechapago"];
            $tcompval  = $row_cartval["tcomp"];
            $detalle   = $row_cartval["codchq"];
            $banco     = $row_cartval["codban"];
            $tcomprel  = $row_cartval["tcomprel"];
            $ncomprel  = $row_cartval["ncomprel"];
            $tcompsal  = $row_cartval["tcompsal"];
            $ncompsal  = $row_cartval["ncompsal"];
            if ($tcompval != 8 && $tcompval != 9 && $tcompval != 12 && $tcompval != 14 && $tcompval != 38)
                continue;

            switch ($tcompval) {
                case 8:
                    // CHEQUES DE TERCEROS (Recibos)
                case 9:
                    // DEPOSITOS (Recibos)
                case 12:
                    // EFECTIVO (Recibos)
                    
                    // PRIMERO LEO LOS RECIBOS 
                    $ingreso = $row_cartval["importe"];
                    $egreso  = 0;
                    $query_cabrec = sprintf("SELECT * FROM cabrecibo WHERE  ncomp = $ncomprel");
                    //echo "RECIBO NRO : ".$ncomprel."  ";
                    $cabrec = mysqli_query($amercado, $query_cabrec) or die("ERROR LEYENDO RECIBOS");
                    $row_cabrec = mysqli_fetch_array($cabrec);
                    $cliente = $row_cabrec["cliente"];
                    // LEO EL DETALLE DEL RECIBO PARA ENCONTRAR LA FACTURA
                    $query_detrec = sprintf("SELECT * FROM detrecibo WHERE  ncomp = $ncomprel");
                    $detrec = mysqli_query($amercado, $query_detrec) or die("ERROR LEYENDO DETRECIBOS");
                    $row_detrec = mysqli_fetch_array($detrec);
                    $nrodocfac = $row_detrec["nrodoc"];
                    // LEO EL CLIENTE
                    $query_cliente = sprintf("SELECT * FROM entidades WHERE  codnum = $cliente");
                    $cli = mysqli_query($amercado, $query_cliente) or die("ERROR LEYENDO CLIENTES");
                    $row_cli = mysqli_fetch_array($cli);
                    $razsoc = $row_cli["razsoc"];
                    // LEO EL BANCO
                    if ($banco != "") {
                        $query_banco = sprintf("SELECT * FROM bancos WHERE  codnum = $banco");
                        $bco = mysqli_query($amercado, $query_banco) or die("ERROR LEYENDO BANCOS");
                        $row_bco = mysqli_fetch_array($bco);
                        $nombre_bco = $row_bco["nombre"];
                    }
                    else
                        $nombre_bco = "";
                    // LEO EL TIPO DE CBTE
                    $query_tcomp = sprintf("SELECT * FROM tipcomp WHERE  codnum = $tcompval");
                    $t_cbte = mysqli_query($amercado, $query_tcomp) or die("ERROR LEYENDO TIPOS DE CBTES");
                    $row_cbte = mysqli_fetch_array($t_cbte);
                    $nombre_cbte = $row_cbte["descripcion"];
                    // BUSCO LA DESCRIPCION DEL CBTE REL
                    $query_tcomprel = sprintf("SELECT * FROM tipcomp WHERE  codnum = $tcomprel");
                    $t_cbterel = mysqli_query($amercado, $query_tcomprel) or die("ERROR LEYENDO TIPOS DE CBTES");
                    $row_cbterel = mysqli_fetch_array($t_cbterel);
                    $nombre_cbterel = "REC "; //$row_cbterel["descripcion"];
                    $nrodoc = "";
                    break;
                case 14:
                    // CHEQUES PROPIOS (Liquidaciones)
                case 38:
                    // EFVO PROPIO (liquidaciones)
                    $egreso   = $row_cartval["importe"];
                    $ingreso  = 0;
                    // LEO EL TIPO DE CBTE
                    $query_tcomp = sprintf("SELECT * FROM tipcomp WHERE  codnum = $tcompval");
                    $t_cbte = mysqli_query($amercado, $query_tcomp) or die("ERROR LEYENDO TIPOS DE CBTES");
                    $row_cbte = mysqli_fetch_array($t_cbte);
                    $nombre_cbte = $row_cbte["descripcion"];
                    // BUSCO LA DESCRIPCION DEL CBTE REL
                    $query_tcomprel = sprintf("SELECT * FROM tipcomp WHERE  codnum = $tcompsal");
                    $t_cbterel = mysqli_query($amercado, $query_tcomprel) or die("ERROR LEYENDO TIPOS DE CBTES");
                    $row_cbterel = mysqli_fetch_array($t_cbterel);
                    $nombre_cbterel = "LQ-"; //$row_cbterel["descripcion"];
                    // BUSCO EL CLIENTE DE LA LIQUIDACION
                    $query_clisal = sprintf("SELECT * FROM liquidacion WHERE  tcomp = $tcompsal AND ncomp = $ncompsal");
                    $t_clisal = mysqli_query($amercado, $query_clisal) or die("ERROR LEYENDO CLIENTES LIQUID");
                    $row_clisal = mysqli_fetch_array($t_clisal);
                    $cliente = $row_clisal["cliente"];
                    $nrodoc = $row_clisal["nrodoc"];
                    // LEO EL CLIENTE
                    $query_cliliq = sprintf("SELECT * FROM entidades WHERE  codnum = $cliente");
                    $cliliq = mysqli_query($amercado, $query_cliliq) or die("ERROR LEYENDO CLIENTES DE LIQUIDACION");
                    $row_cliliq = mysqli_fetch_array($cliliq);
                    $razsoc = $row_cliliq["razsoc"];
                    
                    break;
                case 39:
                    // DEPOSITOS A TERCEROS (Liquidaciones)
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
                case 96:
                    // SON TODAS RETENCIONES SUFRIDAS
                    break;
                case 91:
                    // CREDITO POR FIFERENCIA (cuando piden recibo por el saldo a favor)
                    break;
                case 95:
                    // DEPOSITO EN CTA DE TERCEROS (NO INFLUYE)
                    break;
                case 98:
                    // CBTE INTERNO DE SE�A (INGRESOS)
                    break;
                case 99:
                     // CBTE INTERNO DE DEVOLUCION DE SE�A (EGRESOS)
                    break;
            }
		
			
	
			$tot_chq_terceros = 0.0;
			$tot_depositos    = 0.0;
			$tot_efvo         = 0.0; 
			$tot_dolares      = 0.0;
			$tot_retiva       = 0.0;
			$tot_retibrutos   = 0.0;
			$tot_retganan     = 0.0; 
			$tot_retsuss      = 0.0;
			
            // IMPRIMO
	
					
			//======================================================================
            if ($cant_reng > 24) {
                $cant_reng  = 0;
                $pdf->AddPage('L');
                $pdf->SetMargins(0.5, 0.5 , 0.5);
                $pdf->SetFont('Arial','B',11);
                $pdf->SetY(5);
                $pdf->Cell(10);
                $pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
                $pdf->Cell(205);
                $pagina = $pdf->PageNo();
                $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
                $pdf->SetY(10);
                $pdf->Cell(235);
                $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
                $pdf->SetY(18);
                $pdf->Cell(90);
                $pdf->Cell(20,6,'  Medios de pago desde '.$f_desde.' hasta '.$f_hasta,0,0,'L');
                $pdf->SetFont('Arial','B',9);
                $pdf->SetY(25);
                $pdf->Cell(10);
                $pdf->Cell(30,8,' Tipo cbte ',1,0,'C');
                $pdf->Cell(40,8,' Banco ',1,0,'C');
                $pdf->Cell(30,8,' Ingreso ',1,0,'C');
                $pdf->Cell(30,8,' Egreso ',1,0,'C');
                $pdf->Cell(30,8,' Detalle ',1,0,'C');
                $pdf->Cell(20,8,' Fecha ',1,0,'C');
                $pdf->Cell(60,8,'Cbte. relacionado ',1,0,'C');
                $pdf->Cell(40,8,' Cliente ',1,0,'C');
                $pdf->SetY(33);
                $pdf->Cell(115);
                $pdf->SetFont('Arial','B',8);  
                $valor_y = 35;
            }

            //======================================================================
	        // IMPRIMO LOS  RENGLONES
            $pdf->SetY($valor_y); 
            $pdf->Cell(10);
            $pdf->Cell(30,8,substr($nombre_cbte,0,14),0,0,'L');
            $pdf->Cell(40,8,substr($nombre_bco,0,20),0,0,'L');
            $ingreso = number_format((int) $ingreso, 2, ',','.');
            $egreso = number_format((int) $egreso, 2, ',','.');
            $pdf->Cell(30,8,$ingreso,0,0,'R');
            $pdf->Cell(30,8,$egreso,0,0,'R');
            $pdf->Cell(33,8,"  ".$detalle,0,0,'L');
            $pdf->Cell(20,8,$fechapago,0,0,'L');
            if ($nrodoc == "")
                $pdf->Cell(60,8,substr($nombre_cbterel,0,10)." -.$ncomprel - ".$nrodocfac,0,0,'L');
            else
               $pdf->Cell(60,8,substr($nombre_cbterel,0,10)." -.$nrodoc",0,0,'L');
            $pdf->Cell(40,8,substr($razsoc,0,20),0,0,'L');
            $cant_reng  += 1;
            $valor_y += 6;
     }
mysqli_close($amercado);
$pdf->Output();
?>  
