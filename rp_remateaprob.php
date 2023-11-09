<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior

$remate    	 = $_POST['remate_num'];

$fechahoy = date("d-m-Y");
//LEO LOS LOTES
$query_lotes = sprintf("SELECT * FROM lotes WHERE  codrem = %s ORDER BY codintnum, codintsublote", $remate);
$reglotes = mysqli_query($amercado, $query_lotes) or die("ERROR LEYENDO LOTES 68");

// Inicio el pdf con los datos de cabecera
  $pdf=new FPDF('P','mm','A4');
  
  $pdf->AddPage();
  $pdf->SetMargins(0.5, 0.5 , 0.5);
  $pdf->SetFont('Arial','B',11);
  $pdf->SetY(5);
  $pdf->Cell(10);
  $pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
  $pdf->Cell(100);
  $pagina = $pdf->PageNo();
  $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
  $pdf->SetY(10);
  $pdf->Cell(130);
  $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
  $pdf->SetY(15);
  $pdf->Cell(30);
  $pdf->Cell(60,10,' Resultado estimado por Remate aprobado (Id: '.$remate.' )',0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  $pdf->Cell(30,16,' Nro. Lote',1,0,'L');
   $pdf->Cell(26,16,'Precio obt. ',1,0,'L');
  $pdf->Cell(26,16,'Tasa Adm. ',1,0,'L');
  $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
  $pdf->Cell(26,16,'Total a facturar',1,0,'L');
  
 
  
  $valor_y = 42;
  
// Datos de los renglones
$i = 0;
$acum_tot_precioobt  = 0;
$acum_tot_comision = 0;
$acum_tot_tasa   = 0;
$acum_total  = 0;


while($row_reglotes = mysqli_fetch_array($reglotes))
{	
	$precio_obt      = $row_reglotes["preciobase"];
	$codintlote      = $row_reglotes["codintlote"];
	$comision        = $row_reglotes["preciobase"] * 0.1;
    // calculo la tasa segun la tabla
    $query_rangos = sprintf("SELECT * FROM imprangos WHERE  codimp = 8 AND $precio_obt >= monto_min AND $precio_obt <= monto_max");
    $rangos = mysqli_query($amercado, $query_rangos) or die("ERROR LEYENDO IMPRANGOS 67");
    $row_rangos = mysqli_fetch_array($rangos);
	$tasa            = $row_rangos["monto_fijo"];
    $tot_afact       = $comision + $tasa;
	if ($i > 32) {
        
          $pdf->AddPage();
          $pdf->SetMargins(0.5, 0.5 , 0.5);
          $pdf->SetFont('Arial','B',11);
          $pdf->SetY(5);
          $pdf->Cell(10);
          $pdf->Cell(20,10,' ADRIAN MERCADO S.A. ',0,0,'L');
          $pdf->Cell(100);
          $pagina = $pdf->PageNo();
          $pdf->Cell(30,10,'P�gina : '.$pagina,0,0,'L');
          $pdf->SetY(10);
          $pdf->Cell(130);
          $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
          $pdf->SetY(15);
          $pdf->Cell(30);
          $pdf->Cell(60,10,' Resultado estimado por Remate aprobado (Id: '.$remate.' )',0,0,'L');
          $pdf->SetFont('Arial','B',9);
          $pdf->SetY(25);
          $pdf->Cell(3);
          $pdf->Cell(30,16,' Nro. Lote',1,0,'L');
          $pdf->Cell(26,16,'Precio obt. ',1,0,'L');
          $pdf->Cell(26,16,'Tasa Adm. ',1,0,'L');
          $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
          $pdf->Cell(26,16,'Total a facturar',1,0,'L');
          $i = 0;
          $valor_y = 42;
    }
		// Imprimo subtotales de la hoja, uso otras variables porque el number_format
		// me jode los acumulados
		$f_precio_obt  = number_format($precio_obt, 2, ',','.');
		$f_comision    = number_format($comision, 2, ',','.');
		$f_tasa        = number_format($tasa, 2, ',','.');
        $f_tot_afact   = number_format($tot_afact, 2, ',','.');

   		// IMPRIMO LOS LOTES
    
        $pdf->SetFont('Arial','B',9);
        $pdf->SetY($valor_y);
        $pdf->Cell(3);
        $pdf->Cell(30,16,$codintlote,0,0,'L');
        $pdf->Cell(26,16,$f_precio_obt,0,0,'R');
        $pdf->Cell(26,16,$f_tasa,0,0,'R');
        $pdf->Cell(26,16,$f_comision,0,0,'R');
        $pdf->Cell(26,16,$f_tot_afact,0,0,'R');
    
		$acum_tot_precioobt  += $precio_obt;
        $acum_tot_comision   += $comision;
        $acum_tot_tasa       += $tasa;
        $acum_total  = $acum_total + $comision + $tasa;
        
        $valor_y +=6;
        $i++;
}
    
    
        $f_acum_tot_precioobt   = number_format($acum_tot_precioobt, 2, ',','.');
		$f_acum_total           = number_format($acum_total, 2, ',','.');
		$f_acum_tot_comision    = number_format($acum_tot_comision, 2, ',','.');
		$f_acum_tot_tasa        = number_format($acum_tot_tasa, 2, ',','.');
		
		$pdf->SetFont('Arial','B',9);
        $pdf->SetY($valor_y);
        $pdf->Cell(3);
        $pdf->Cell(30,16,'TOTALES ',0,0,'L');
        $pdf->Cell(26,16,$f_acum_tot_precioobt,0,0,'R');
        $pdf->Cell(26,16,$f_acum_tot_tasa,0,0,'R');
        $pdf->Cell(26,16,$f_acum_tot_comision,0,0,'R');
        $pdf->Cell(26,16,$f_acum_total,0,0,'R');
        $valor_y +=6;
        $pdf->SetY($valor_y);
        $pdf->Cell(3);
        $pdf->Cell(30,16,'FACTURA DE COMISI�N AL DUE�O ',0,0,'L');
        $pdf->SetX(85);
        $pdf->Cell(26,16,$f_acum_tot_comision,0,0,'R');
        $acum_total = $acum_total + $acum_tot_comision;
        $f_acum_total           = number_format($acum_total, 2, ',','.');
        $pdf->Cell(26,16,$f_acum_total,0,0,'R');

		
mysqli_close($amercado);
$pdf->Output();
?>  
