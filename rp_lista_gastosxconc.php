<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');

//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES DE PROVEEDORES
define('FC_PRO_A','32');
define('FC_PRO_C','33');
define('NC_PRO_A','36');
define('NC_PRO_C','37');
define('ND_PRO_A','34');
define('ND_PRO_C','35');
define('FC_PRO_M','65');
define('NC_PRO_M','87');
define('ND_PRO_M','88');
define('FC_PRO_LIQ','110');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];
$concepto    = $_POST['concepto_num'];

$fecha_desde = "'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");
// Leo los renglones
// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO IMPUESTOS");
$row_Recordset2 = mysqli_fetch_assoc($impuestos);
$totalRows_Recordset2 = mysqli_num_rows($impuestos);
$porc_iva105 = (mysqli_result($impuestos,1, 1)/100); 
$porc_iva21  = (mysqli_result($impuestos,0, 1)/100);


// Leo la cabecera


$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN %s AND %s ORDER BY fecreg", $fecha_desde, $fecha_hasta);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC");

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
  $pdf->Cell(70);
  $pdf->Cell(20,10,'         Conceptos de Gastos        ',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(10);
  $pdf->Cell(20,16,' Fecha',1,0,'L');
  $pdf->Cell(30,16,' Comprobante ',1,0,'L');
  $pdf->Cell(50,16,' Proveedor ',1,0,'L');
  $pdf->Cell(24,16,'    CUIT ',1,0,'L');
  $pdf->Cell(25,16,' Importe ',1,0,'L');
  $pdf->Cell(30,16,' Concepto',1,0,'L');
  
  $pdf->SetY(33);
  $pdf->Cell(115);
  //$pdf->Cell(20,8,' Cbte',0,0,'L');
  $pdf->SetFont('Arial','B',8);  
  $valor_y = 45;
  
$i = 60;
$j = 100;
$total = 0;
	$tot1 = $tot2 = $tot3 = $tot4 = $tot5 = $tot6 = $tot7 = $tot8 = $tot9 = $tot10 = 0.0;
	$tot11 = $tot12 = $tot13 = $tot14 = $tot15 = $tot16 = $tot17 = $tot18 = $tot19 = $tot20 = 0.0;
	$tot21 = $tot22 = $tot23 = $tot24 = $tot25 = $tot26 = $tot27 = $tot28 = $tot29 = $tot30 = 0.0;
	$tot31 = $tot32 = $tot33 = $tot34 = $tot35 = $tot36 = $tot37 = $tot38 = $tot39 = $tot40 = 0.0;
	$tot41 = $tot42 = $tot43 = $tot44 = $tot45 = $tot46 = $tot47 = $tot48 = $tot49 = $tot50 = 0.0;
	$tot51 = $tot52 = $tot53 = $tot54 = $tot55 = $tot56 = $tot57 = $tot58 = $tot59 = $tot60 = 0.0; 
	$tot61 = $tot62 = $tot63 = $tot64 = $tot65 = $tot66 = $tot67 = $tot68 = $tot69 = $tot70 = 0.00;
    $tot71 = $tot72 = $tot73 = $tot74 = $tot75 = $tot76 = $tot77 = $tot78 = $tot79 = $tot80 = 
    $tot81 = $tot82 = $tot83 = $tot84 = $tot85 = $tot86 = $tot87 = $tot88 = $tot89 = $tot90 = 0.00 ;
$cant_reng  = 0;

while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{			
	
	$tcomp      = $row_cabecerafac["tcomp"];
    if ($tcomp !=  32 && $tcomp !=  33 && $tcomp != 34 && $tcomp != 35 && $tcomp != 36 && $tcomp != 37 && $tcomp != 65 && $tcomp != 110 && $tcomp != 87 && $tcomp != 88)  
		continue;
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	$fecha_cbte = substr($row_cabecerafac["fecreg"],8,2)."-".substr($row_cabecerafac["fecreg"],5,2)."-".substr($row_cabecerafac["fecreg"],0,4);
	$fcomp      = $fecha_cbte;
	$nrodoc     = $row_cabecerafac["nrodoc"];
	$fecha_cab  = substr($row_cabecerafac["fechahora"],0,4)."-".substr($row_cabecerafac["fechahora"],5,2)."-".substr($row_cabecerafac["fechahora"],8,2);
	if ($tcomp == FC_PRO_A || $tcomp == FC_PRO_C || $tcomp == FC_PRO_M) {
		$tc = "FC-";
		$signo = 1;
	}
	if ($tcomp == NC_PRO_A || $tcomp == NC_PRO_C || $tcomp == NC_PRO_M) {
		$tc = "NC-";
		$signo = -1;
	}
	if ($tcomp == ND_PRO_A || $tcomp == ND_PRO_C || $tcomp == ND_PRO_M) {
		$tc = "ND-";
		$signo = 1;
	}
    if ($tcomp == FC_PRO_LIQ) {
		$tc = "LQ-";
		$signo = 1;
	}
	
	
	$cliente      = $row_cabecerafac["cliente"];
	// Leo el cliente
  	$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  	$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES");
  	$row_entidades = mysqli_fetch_assoc($enti);
  	
	$cuit         = $row_entidades["cuit"];
	$razsoc       = $row_entidades["razsoc"];
		
	// Leo detfac
	$query_detfac = sprintf("SELECT * FROM detfac WHERE  tcomp = %d AND serie = %d AND ncomp = %ld", $tcomp, $serie, $ncomp);
  	$detfac       = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC");
  	while($row_detfac   = mysqli_fetch_array($detfac)) {
        if ($row_detfac["concafac"] != $concepto)
            continue;
		
		$fecha_det  = substr($row_detfac["fechahora"],0,4)."-".substr($row_detfac["fechahora"],5,2)."-".substr($row_detfac["fechahora"],8,2);
		
		$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = %d ", $row_detfac["concafac"]);
		$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT");
		$row_gastos   = mysqli_fetch_assoc($gastos);

		$importe   = $row_detfac["neto"] * $signo;
		$total     = $total + $importe;
		$importe   = number_format($importe, 2, ',','.');


		switch($row_detfac["concafac"]) {
			case 1:
				$tot1 += $row_detfac["neto"] * $signo;
				break;
			case 2:
				$tot2 += $row_detfac["neto"] * $signo;
				break;
			case 3:
				$tot3 += $row_detfac["neto"] * $signo;
				break;
			case 4:
				$tot4 += $row_detfac["neto"] * $signo;
				break;
			case 5:
				$tot5 += $row_detfac["neto"] * $signo;
				break;
			case 6:
				$tot6 += $row_detfac["neto"] * $signo;
				break;
			case 7:
				$tot7 += $row_detfac["neto"] * $signo;
				break;
			case 8:
				$tot8 += $row_detfac["neto"] * $signo;
				break;
			case 9:
				$tot9 += $row_detfac["neto"] * $signo;
				break;
			case 10:
				$tot10 += $row_detfac["neto"] * $signo;
				break;
			case 11:
				$tot11 += $row_detfac["neto"] * $signo;
				break;
			case 12:
				$tot12 += $row_detfac["neto"] * $signo;
				break;
			case 13:
				$tot13 += $row_detfac["neto"] * $signo;
				break;
			case 14:
				$tot14 += $row_detfac["neto"] * $signo;
				break;
			case 15:
				$tot15 += $row_detfac["neto"] * $signo;
				break;
			case 16:
				$tot16 += $row_detfac["neto"] * $signo;
				break;
			case 17:
				$tot17 += $row_detfac["neto"] * $signo;
				break;
			case 18:
				$tot18 += $row_detfac["neto"] * $signo;
				break;
			case 19:
				$tot19 += $row_detfac["neto"] * $signo;
				break;
			case 20:
				$tot20 += $row_detfac["neto"] * $signo;
				break;
			case 21:
				$tot21 += $row_detfac["neto"] * $signo;
				break;
			case 22:
				$tot22 += $row_detfac["neto"] * $signo;
				break;
			case 23:
				$tot23 += $row_detfac["neto"] * $signo;
				break;
			case 24:
				$tot24 += $row_detfac["neto"] * $signo;
				break;
			case 25:
				$tot25 += $row_detfac["neto"] * $signo;
				break;
			case 26:
				$tot26 += $row_detfac["neto"] * $signo;
				break;
			case 27:
				$tot27 += $row_detfac["neto"] * $signo;
				break;
			case 28:
				$tot28 += $row_detfac["neto"] * $signo;
				break;
			case 29:
				$tot29 += $row_detfac["neto"] * $signo;
				break;
			case 30:
				$tot30 += $row_detfac["neto"] * $signo;
				break;
			case 31:
				$tot31 += $row_detfac["neto"] * $signo;
				break;
			case 32:
				$tot32 += $row_detfac["neto"] * $signo;
				break;
			case 33:
				$tot33 += $row_detfac["neto"] * $signo;
				break;
			case 34:
				$tot34 += $row_detfac["neto"] * $signo;
				break;
			case 35:
				$tot35 += $row_detfac["neto"] * $signo;
				break;
			case 36:
				$tot36 += $row_detfac["neto"] * $signo;
				break;
			case 37:
				$tot37 += $row_detfac["neto"] * $signo;
				break;
			case 38:
				$tot38 += $row_detfac["neto"] * $signo;
				break;
			case 39:
				$tot39 += $row_detfac["neto"] * $signo;
				break;
			case 40:
				$tot40 += $row_detfac["neto"] * $signo;
				break;
			case 41:
				$tot41 += $row_detfac["neto"] * $signo;
				break;
			case 42:
				$tot42 += $row_detfac["neto"] * $signo;
				break;
			case 43:
				$tot43 += $row_detfac["neto"] * $signo;
				break;
			case 44:
				$tot44 += $row_detfac["neto"] * $signo;
				break;
			case 45:
				$tot45 += $row_detfac["neto"] * $signo;
				break;
			case 46:
				$tot46 += $row_detfac["neto"] * $signo;
				break;
			case 47:
				$tot47 += $row_detfac["neto"] * $signo;
				break;
			case 48:
				$tot48 += $row_detfac["neto"] * $signo;
				break;
			case 49:
				$tot49 += $row_detfac["neto"] * $signo;
				break;
			case 50:
				$tot50 += $row_detfac["neto"] * $signo;
				break;
			case 51:
				$tot51 += $row_detfac["neto"] * $signo;
				break;
			case 52:
				$tot52 += $row_detfac["neto"] * $signo;
				break;
			case 53:
				$tot53 += $row_detfac["neto"] * $signo;
				break;
			case 54:
				$tot54 += $row_detfac["neto"] * $signo;
				break;
			case 55:
				$tot55 += $row_detfac["neto"] * $signo;
				break;
			case 56:
				$tot56 += $row_detfac["neto"] * $signo;
				break;
			case 57:
				$tot57 += $row_detfac["neto"] * $signo;
				break;
			case 58:
				$tot58 += $row_detfac["neto"] * $signo;
				break;
			case 59:
				$tot59 += $row_detfac["neto"] * $signo;
				break;
			case 60:
				$tot60 += $row_detfac["neto"] * $signo;
				break;
			case 61:
				$tot61 += $row_detfac["neto"] * $signo;
				break;
			case 62:
				$tot62 += $row_detfac["neto"] * $signo;
				break;
			
			case 63:
				$tot63 += $row_detfac["neto"] * $signo;
				break;
			
			case 64:
				$tot64 += $row_detfac["neto"] * $signo;
				break;
			
			case 65:
				$tot65 += $row_detfac["neto"] * $signo;
				break;
			
			case 66:
				$tot66 += $row_detfac["neto"] * $signo;
				break;
			
			case 67:
				$tot67 += $row_detfac["neto"] * $signo;
				break;
			
			case 68:
				$tot68 += $row_detfac["neto"] * $signo;
				break;
            
			case 69:
				$tot69 += $row_detfac["neto"] * $signo;
				break;
            
			case 70:
				$tot70 += $row_detfac["neto"] * $signo;
				break;
             
			case 71:
				$tot71 += $row_detfac["neto"] * $signo;
				break;
              
			case 72:
				$tot72 += $row_detfac["neto"] * $signo;
				break;
                
            case 73:
				$tot73 += $row_detfac["neto"] * $signo;
				break;
                
             case 74:
				$tot74 += $row_detfac["neto"] * $signo;
				break;
                
             case 75:
				$tot75 += $row_detfac["neto"] * $signo;
				break;
                
             case 76:
				$tot76 += $row_detfac["neto"] * $signo;
				break;
                
             case 77:
				$tot77 += $row_detfac["neto"] * $signo;
				break;
                
             case 78:
				$tot78 += $row_detfac["neto"] * $signo;
				break;
                
             case 79:
				$tot79 += $row_detfac["neto"] * $signo;
				break;
                
             case 80:
				$tot80 += $row_detfac["neto"] * $signo;
				break;
  
            case 81:
				$tot81 += $row_detfac["neto"] * $signo;
				break;
   
            case 82:
				$tot82 += $row_detfac["neto"] * $signo;
				break;
            
            case 83:
				$tot83 += $row_detfac["neto"] * $signo;
				break;
            
            case 84:
				$tot84 += $row_detfac["neto"] * $signo;
				break;
            
            case 85:
				$tot85 += $row_detfac["neto"] * $signo;
				break;
            
            case 86:
				$tot86 += $row_detfac["neto"] * $signo;
				break;
            
            case 87:
				$tot87 += $row_detfac["neto"] * $signo;
				break;
            
            case 88:
				$tot88 += $row_detfac["neto"] * $signo;
				break;
                  
            case 89:
				$tot89 += $row_detfac["neto"] * $signo;
				break;
                  
            case 90:
				$tot90 += $row_detfac["neto"] * $signo;
				break;
                    
                
                
		}
		$nombre_conc = $row_gastos["descrip"];

		// Acumulo subtotales
		$pdf->SetY($valor_y);
		$pdf->Cell(10);
		$pdf->Cell(20,8,$fcomp,0,0,'L');
		$pdf->Cell(30,8,$tc.$nrodoc,0,0,'L');
		$pdf->Cell(50,8,substr($razsoc,0,25),0,0,'L');
		$pdf->Cell(24,8,$cuit,0,0,'L');
		$pdf->Cell(25,8,$importe,0,0,'R');
		$pdf->Cell(30,8,substr($nombre_conc,0,25),0,0,'L');
		$cant_reng +=1;

		if ($cant_reng > 36) {
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
			  $pdf->Cell(70);
			  $pdf->Cell(20,10,'         Conceptos de Gastos        ',0,0,'L');
			  $pdf->SetFont('Arial','B',9);
			  $pdf->SetY(25);
			  $pdf->Cell(10);
			  $pdf->Cell(20,16,' Fecha',1,0,'L');
			  $pdf->Cell(30,16,' Comprobante ',1,0,'L');
			  $pdf->Cell(50,16,' Proveedor ',1,0,'L');
			  $pdf->Cell(24,16,'    CUIT ',1,0,'L');
			  $pdf->Cell(25,16,' Importe ',1,0,'L');
			  $pdf->Cell(30,16,' Concepto',1,0,'L');

			  $pdf->SetY(33);
			  $pdf->Cell(115);
			  //$pdf->Cell(20,8,' Cbte',0,0,'L');
			  $pdf->SetFont('Arial','B',8);  
			  $valor_y = 45;
			  $pdf->SetY($valor_y);

		}
		else {	
			$valor_y = $valor_y + 6;
			
		}
	}
}
$pdf->AddPage();
$valor_y = 45;
$pdf->SetY($valor_y);
if ($tot1 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 1 ");
	$pdf->Cell(50);
  	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 1");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot1   = number_format($tot1, 2, ',','.');
	$pdf->Cell(35,8,$tot1,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot2 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 2 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 2");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot2   = number_format($tot2, 2, ',','.');
	$pdf->Cell(35,8,$tot2,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot3 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 3 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 3");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot3   = number_format($tot3, 2, ',','.');
	$pdf->Cell(35,8,$tot3,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot4 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 4 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 4");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot4   = number_format($tot4, 2, ',','.');
	$pdf->Cell(35,8,$tot4,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot5 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 5 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 5");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot5   = number_format($tot5, 2, ',','.');
	$pdf->Cell(35,8,$tot5,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot6 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 6 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 6");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot6   = number_format($tot6, 2, ',','.');
	$pdf->Cell(35,8,$tot6,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot7 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 7 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 7");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot7   = number_format($tot7, 2, ',','.');
	$pdf->Cell(35,8,$tot7,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot8 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 8 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 8");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot8   = number_format($tot8, 2, ',','.');
	$pdf->Cell(35,8,$tot8,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot9 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 9 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 9");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot9   = number_format($tot9, 2, ',','.');
	$pdf->Cell(35,8,$tot9,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot10 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 10 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 10");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot10   = number_format($tot10, 2, ',','.');
	$pdf->Cell(35,8,$tot10,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot11 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 11 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 11");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot11   = number_format($tot11, 2, ',','.');
	$pdf->Cell(35,8,$tot11,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot12 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 12 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 12");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot12   = number_format($tot12, 2, ',','.');
	$pdf->Cell(35,8,$tot12,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot13 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 13 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 13");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot13   = number_format($tot13, 2, ',','.');
	$pdf->Cell(35,8,$tot13,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot14 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 14 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 14");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot14   = number_format($tot14, 2, ',','.');
	$pdf->Cell(35,8,$tot14,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot15 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 15 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 15");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot15   = number_format($tot15, 2, ',','.');
	$pdf->Cell(35,8,$tot15,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot16 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 16 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 16");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot16   = number_format($tot16, 2, ',','.');
	$pdf->Cell(35,8,$tot16,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot17 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 17 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 17");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot17   = number_format($tot17, 2, ',','.');
	$pdf->Cell(35,8,$tot17,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot18 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 18 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 18");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot18   = number_format($tot18, 2, ',','.');
	$pdf->Cell(35,8,$tot18,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot19 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 19 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 19");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot19   = number_format($tot19, 2, ',','.');
	$pdf->Cell(35,8,$tot19,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot20 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 20 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 20");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot20   = number_format($tot20, 2, ',','.');
	$pdf->Cell(35,8,$tot20,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot21 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 21 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 21");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot21   = number_format($tot21, 2, ',','.');
	$pdf->Cell(35,8,$tot21,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot22 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 22 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 22");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot22   = number_format($tot22, 2, ',','.');
	$pdf->Cell(35,8,$tot22,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot23 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 23 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 23");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot23   = number_format($tot23, 2, ',','.');
	$pdf->Cell(35,8,$tot23,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot24 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 24 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 24");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot24   = number_format($tot24, 2, ',','.');
	$pdf->Cell(35,8,$tot24,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot25 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 25 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 25");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot25   = number_format($tot25, 2, ',','.');
	$pdf->Cell(35,8,$tot25,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot26 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 26 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 26");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot26   = number_format($tot26, 2, ',','.');
	$pdf->Cell(35,8,$tot26,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot27 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 27 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 27");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot27   = number_format($tot27, 2, ',','.');
	$pdf->Cell(35,8,$tot27,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot28 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 28 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 28");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot28   = number_format($tot28, 2, ',','.');
	$pdf->Cell(35,8,$tot28,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot29 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 29 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 29");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot29   = number_format($tot29, 2, ',','.');
	$pdf->Cell(35,8,$tot29,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot30 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 30 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 30");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot30   = number_format($tot30, 2, ',','.');
	$pdf->Cell(35,8,$tot30,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot31 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 31 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 31");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot31   = number_format($tot31, 2, ',','.');
	$pdf->Cell(35,8,$tot31,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot32 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 32 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 32");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot32   = number_format($tot32, 2, ',','.');
	$pdf->Cell(35,8,$tot32,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot33 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 33 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 33");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot33   = number_format($tot33, 2, ',','.');
	$pdf->Cell(35,8,$tot33,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot34 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 34 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 34");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot34   = number_format($tot34, 2, ',','.');
	$pdf->Cell(35,8,$tot34,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot35 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 35 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 35");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot35   = number_format($tot35, 2, ',','.');
	$pdf->Cell(35,8,$tot35,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot36 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 36 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 36");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot36   = number_format($tot36, 2, ',','.');
	$pdf->Cell(35,8,$tot36,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot37 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 37 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 37");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot37   = number_format($tot37, 2, ',','.');
	$pdf->Cell(35,8,$tot37,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot38 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 38 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 38");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot38   = number_format($tot38, 2, ',','.');
	$pdf->Cell(35,8,$tot38,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot39 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 39 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 39");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot39   = number_format($tot39, 2, ',','.');
	$pdf->Cell(35,8,$tot39,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot40 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 40 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 40");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot40   = number_format($tot40, 2, ',','.');
	$pdf->Cell(35,8,$tot40,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot41 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 41 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 41");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot41   = number_format($tot41, 2, ',','.');
	$pdf->Cell(35,8,$tot41,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot42 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 42 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 42");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot42   = number_format($tot42, 2, ',','.');
	$pdf->Cell(35,8,$tot42,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot43 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 43 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 43");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot43   = number_format($tot43, 2, ',','.');
	$pdf->Cell(35,8,$tot43,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot44 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 44 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 44");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot44   = number_format($tot44, 2, ',','.');
	$pdf->Cell(35,8,$tot44,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot45 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 45 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 45");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot45   = number_format($tot45, 2, ',','.');
	$pdf->Cell(35,8,$tot45,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot46 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 46 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 46");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot46   = number_format($tot46, 2, ',','.');
	$pdf->Cell(35,8,$tot46,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot47 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 47 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 47");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot47   = number_format($tot47, 2, ',','.');
	$pdf->Cell(35,8,$tot47,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot48 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 48 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 48");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot48   = number_format($tot48, 2, ',','.');
	$pdf->Cell(35,8,$tot48,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot49 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 49 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 49");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot49   = number_format($tot49, 2, ',','.');
	$pdf->Cell(35,8,$tot49,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}

if ($tot50 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 50 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 50");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot50   = number_format($tot50, 2, ',','.');
	$pdf->Cell(35,8,$tot50,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot51 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 51 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 51");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot51   = number_format($tot51, 2, ',','.');
	$pdf->Cell(35,8,$tot51,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot52 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 52 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 52");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot52   = number_format($tot52, 2, ',','.');
	$pdf->Cell(35,8,$tot52,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot53 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 53 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 53");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot53   = number_format($tot53, 2, ',','.');
	$pdf->Cell(35,8,$tot53,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot54 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 54 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 54");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot54   = number_format($tot54, 2, ',','.');
	$pdf->Cell(35,8,$tot54,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot55 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 55 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 55");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot55   = number_format($tot55, 2, ',','.');
	$pdf->Cell(35,8,$tot55,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot56 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 56 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 56");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot56   = number_format($tot56, 2, ',','.');
	$pdf->Cell(35,8,$tot56,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot57 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 57 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 57");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot57   = number_format($tot57, 2, ',','.');
	$pdf->Cell(35,8,$tot57,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot58 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 58 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 58");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                    ',0,0,'R');
	$tot58   = number_format($tot58, 2, ',','.');
	$pdf->Cell(35,8,$tot58,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot59 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 59 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 59");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot59   = number_format($tot59, 2, ',','.');
	$pdf->Cell(35,8,$tot59,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot60 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 60 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 60");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot60   = number_format($tot60, 2, ',','.');
	$pdf->Cell(35,8,$tot60,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot61 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 61 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 61");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot61   = number_format($tot61, 2, ',','.');
	$pdf->Cell(35,8,$tot61,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot62 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 62 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 62");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot62   = number_format($tot62, 2, ',','.');
	$pdf->Cell(35,8,$tot62,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot63 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 63 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 63");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot63   = number_format($tot63, 2, ',','.');
	$pdf->Cell(35,8,$tot63,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot64 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 64 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 64");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot64   = number_format($tot64, 2, ',','.');
	$pdf->Cell(35,8,$tot64,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot65 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 65 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 65");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot65   = number_format($tot65, 2, ',','.');
	$pdf->Cell(35,8,$tot65,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot66 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 66 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 66");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot66   = number_format($tot66, 2, ',','.');
	$pdf->Cell(35,8,$tot66,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot67 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 67 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 67");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot67   = number_format($tot67, 2, ',','.');
	$pdf->Cell(35,8,$tot67,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot68 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 68 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 68");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot68   = number_format($tot68, 2, ',','.');
	$pdf->Cell(35,8,$tot68,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot69 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 69 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 69");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot69   = number_format($tot69, 2, ',','.');
	$pdf->Cell(35,8,$tot69,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot70 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 70 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 70");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot70   = number_format($tot70, 2, ',','.');
	$pdf->Cell(35,8,$tot70,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot71 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 71 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 71");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot71   = number_format($tot71, 2, ',','.');
	$pdf->Cell(35,8,$tot71,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot72 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 72 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 72");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot72   = number_format($tot72, 2, ',','.');
	$pdf->Cell(35,8,$tot72,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot73 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 73 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 73");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot73   = number_format($tot73, 2, ',','.');
	$pdf->Cell(35,8,$tot73,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot74 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 74 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 74");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot74   = number_format($tot74, 2, ',','.');
	$pdf->Cell(35,8,$tot74,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot75 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 75 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 75");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot75   = number_format($tot75, 2, ',','.');
	$pdf->Cell(35,8,$tot75,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot76 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 76 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 76");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot76   = number_format($tot76, 2, ',','.');
	$pdf->Cell(35,8,$tot76,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot77 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 77 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 77");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot77   = number_format($tot77, 2, ',','.');
	$pdf->Cell(35,8,$tot77,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot78 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 78 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 78");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot78   = number_format($tot78, 2, ',','.');
	$pdf->Cell(35,8,$tot78,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot79 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 79 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 79");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot79   = number_format($tot79, 2, ',','.');
	$pdf->Cell(35,8,$tot79,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot80 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 80 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 80");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot80   = number_format($tot80, 2, ',','.');
	$pdf->Cell(35,8,$tot80,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot81 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 81 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 81");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot81   = number_format($tot81, 2, ',','.');
	$pdf->Cell(35,8,$tot81,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot82 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 82 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 82");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot82   = number_format($tot82, 2, ',','.');
	$pdf->Cell(35,8,$tot82,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot83 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 83 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 83");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot83   = number_format($tot83, 2, ',','.');
	$pdf->Cell(35,8,$tot83,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot84 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 84 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 84");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot84   = number_format($tot84, 2, ',','.');
	$pdf->Cell(35,8,$tot84,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot85 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 85 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 85");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot85   = number_format($tot85, 2, ',','.');
	$pdf->Cell(35,8,$tot85,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot86 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 86 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 86");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot86   = number_format($tot86, 2, ',','.');
	$pdf->Cell(35,8,$tot86,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot87 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 87 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 87");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot87   = number_format($tot87, 2, ',','.');
	$pdf->Cell(35,8,$tot87,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot88 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 88 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 88");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot88   = number_format($tot88, 2, ',','.');
	$pdf->Cell(35,8,$tot88,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot89 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 89 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 89");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot89   = number_format($tot89, 2, ',','.');
	$pdf->Cell(35,8,$tot89,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}
if ($tot90 != 0.0) {
	$query_gastos = sprintf("SELECT * FROM concafact WHERE  nroconc = 90 ");
  	$pdf->Cell(50);
	$gastos       = mysqli_query($amercado, $query_gastos) or die("ERROR LEYENDO CONCAFACT 90");
  	$row_gastos   = mysqli_fetch_assoc($gastos);
	$nombre_conc = $row_gastos["descrip"];
	$cta_bas = $row_gastos["ctacbleBAS"];
	$pdf->Cell(105,8,'TOTAL '.substr($nombre_conc,0,28).' ( '.$cta_bas.' ) :                   ',0,0,'R'); 
	$tot90   = number_format($tot90, 2, ',','.');
	$pdf->Cell(35,8,$tot90,0,0,'R');
	$valor_y = $valor_y + 6;
	$pdf->SetY($valor_y);
}

$pdf->Cell(127);
$total   = number_format($total, 2, ',','.');
$pdf->Cell(28,8,'TOTAL GASTOS: ',0,0,'R');
$pdf->Cell(35,8,$total,0,0,'R');
mysqli_close($amercado);

$pdf->Output();
?>  