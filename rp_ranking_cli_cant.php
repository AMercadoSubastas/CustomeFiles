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
$cant_cli    = $_POST['cant_cli'];

$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");

// Leo las cabeceras de facturas
$pe = "P";
$ese = "S";
$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecdoc BETWEEN %s AND %s  ORDER BY cliente, fecreg, nrodoc", $fecha_desde, $fecha_hasta);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));

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
$pdf->Cell(20,10,' Ranking de Operaciones por Cliente',0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(10);
$pdf->Cell(60,8,'     Raz�n Social  ',1,0,'L');
$pdf->Cell(25,8,'       CUIT  ',1,0,'L');
$pdf->Cell(45,8,'     Cant. Operac.      ',1,0,'L');
$pdf->Cell(45,8,'        % Sobre Total   ',1,0,'L');
  
$valor_y = 45;
  
// Datos de los renglones
$i = 0;

$acum_cli    = array();
$total       = 0;
$acum_total  = 0;

for ($arr= 0;$arr < 15000;$arr++)
	$acum_cli[$arr] = 0.00;

while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	

	if($row_cabecerafac["estado"] == "A")
		continue;

	$tcomp      = $row_cabecerafac["tcomp"];
	$cliente    = $row_cabecerafac["cliente"];
	$totneto105 = $row_cabecerafac["totneto105"];
	$totneto21  = $row_cabecerafac["totneto21"];
	$totcomis   = $row_cabecerafac["totcomis"];
	
	// Veo si son cbtes de ventas
	$query_tipcomp= "SELECT * FROM tipcomp WHERE codnum = $tcomp";
	$tipcomp = mysqli_query($amercado, $query_tipcomp) or die(mysqli_error($amercado));
	$row_Recordset3 = mysqli_fetch_assoc($tipcomp);
	if ($row_Recordset3["esfactura"] != 1)
		continue;

	if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 5 || $tcomp == 6 || $tcomp == 25 || $tcomp == 26 || $tcomp == 46 || $tcomp == 47 ) {
		$tc = "NC-";
		$signo = -1;
		$total   = 0;
	}
	
	else {
		$total   = 1;
		$tc = "FC-";
		$signo = 1;
	}
	
	$cliente = $row_cabecerafac["cliente"];
	
	$total   = 1;//($row_cabecerafac["totneto105"] + $row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $signo;
				
		// Acumulo subtotales
	
		$acum_cli[$cliente] = $acum_cli[$cliente] + $total;
		$acum_total         = $acum_total + $total;

}
	
$i = 0;

// Leo los clientes
$tipoent = 1;
$query_entidades = sprintf("SELECT * FROM entidades WHERE  tipoent = %s", $tipoent);
$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
//echo " -  0 termino cabfac  -  ";	
while($row_entidades = mysqli_fetch_array($enti))
{	

			if ($acum_cli[$row_entidades["codnum"]] == 0)
				continue;

  			$nom_cliente[$i]   = substr($row_entidades["razsoc"], 0, 30);
  			$nro_cliente[$i]   = $row_entidades["codnum"];
  			$cuit_cliente[$i]  = $row_entidades["cuit"];
			$tot_cli[$i]       = $acum_cli[$row_entidades["codnum"]];
			$porc_cli[$i]      = $acum_cli[$row_entidades["codnum"]] * 100.0 / $acum_total;
			$i++;



} 	
	$nom_cliente_aux   = "";
	$nro_cliente_aux   = 0;
	$cuit_cliente_aux  = "";
	$tot_cli_aux       = 0;
	$porc_cli_aux       = 0.00;

// ACA ORDENO LOS QUE ME QUEDARON POR IMPORTE
for ($vueltas=0;$vueltas < $i;$vueltas++) 
for ($j=0;$j < $i;$j++) {
	if (isset($tot_cli[$j]) && isset($tot_cli[$j+1]) && $tot_cli[$j] < $tot_cli[$j+1]) {
		$nom_cliente_aux   = $nom_cliente[$j];
		$nro_cliente_aux   = $nro_cliente[$j];
		$cuit_cliente_aux  = $cuit_cliente[$j];
		$tot_cli_aux       = $tot_cli[$j];
		$porc_cli_aux       = $porc_cli[$j];
		
		$nom_cliente[$j]   = $nom_cliente[$j+1];
		$nro_cliente[$j]   = $nro_cliente[$j+1];
  		$cuit_cliente[$j]  = $cuit_cliente[$j+1];
		$tot_cli[$j]       = $tot_cli[$j+1];
		$porc_cli[$j]      = $porc_cli[$j+1];
		
		$nom_cliente[$j+1]   = $nom_cliente_aux;
		$nro_cliente[$j+1]   = $nro_cliente_aux;
  		$cuit_cliente[$j+1]  = $cuit_cliente_aux;
		$tot_cli[$j+1]       = $tot_cli_aux;
		$porc_cli[$j+1]      = $porc_cli_aux;
		
			
	}
	$nom_cliente_aux   = "";
	$nro_cliente_aux   = 0;
	$cuit_cliente_aux  = "";
	$tot_cli_aux       = 0;
	$porc_cli_aux       = 0.00;

}
$linea = 0;
if($i < $cant_cli)
	$cant_cli = $i;
for ($j=0;$j < $cant_cli;$j++) {
	if ($linea < 48) {
			$tot_cli[$j] = number_format($tot_cli[$j], 2, ',','.');
			$porc_cli[$j] = number_format($porc_cli[$j], 2, ',','.');
			
			
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->SetX(10);
  			$pdf->Cell(60,6,$nom_cliente[$j],0,0,'L');
  			$pdf->Cell(22,6,$cuit_cliente[$j],0,0,'L');
			$pdf->Cell(40,6,$tot_cli[$j],0,0,'R');
  			$pdf->Cell(40,6,$porc_cli[$j],0,0,'R');
  					
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
		$pdf->Cell(20,10,' Ranking de Operaciones por Cliente',0,0,'L');
		$pdf->SetFont('Arial','B',9);
		$pdf->SetY(25);
		$pdf->Cell(10);
		$pdf->Cell(60,8,'     Raz�n Social  ',1,0,'L');
		$pdf->Cell(25,8,'       CUIT  ',1,0,'L');
		$pdf->Cell(45,8,'     Cant. Operac.     ',1,0,'L');
		$pdf->Cell(45,8,'        % Sobre Total   ',1,0,'L');
		  
		$valor_y = 45;
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
