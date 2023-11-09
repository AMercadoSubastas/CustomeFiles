<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
require('fpdf17/fpdf.php');

//Connect to your database
require_once('Connections/repuestos.php');

mysqli_select_db($repuestos, $database_repuestos);

// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$f_desde = $_POST['fecha_desde'];
$f_hasta = $_POST['fecha_hasta'];

$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");

$vendedor = $_POST['vend_num'];
//echo $cliente;

$query_vendedor = sprintf("SELECT * FROM vendedores WHERE codigo = %s ", $vendedor);
$q_vendedor = mysqli_query($repuestos, $query_vendedor) or die(mysqli_error($amercado));
$totalRows_vendedor = mysqli_num_rows($q_vendedor);
$row_vendedor = mysqli_fetch_array($q_vendedor);
$descr_ven = $row_vendedor['apynom'];


// AND fecha BETWEEN fecha_desde AND fecha_hasta
$query_cabped = sprintf("SELECT * FROM cabped WHERE vendedor = %s  AND fecha BETWEEN %s AND %s AND estado in (3,4,5) ORDER BY ncomp", $vendedor,$fecha_desde,$fecha_hasta);
$cabped = mysqli_query($repuestos, $query_cabped) or die(mysqli_error($amercado));
$totalRows_cabped = mysqli_num_rows($cabped);


/*
$query_detped = sprintf("SELECT * FROM detped WHERE codart = %s ORDER BY ncomp , codart", $articulo);
$detped = mysqli_query($repuestos, $query_detped) or die(mysqli_error($amercado));
$totalRows_detped = mysqli_num_rows($detped);
*/
$renglones = 0;
/*
// LEO EL REGISTRO DE INVENTARIO DEL ART�CULO
while($row_detmov = mysqli_fetch_array($detmov)) {
  	$cant_inv    = $row_detmov["cantidad"];
	// LEO MOVIM PARA SACAR LA FECHA
	$ncomp_inv    = $row_detmov["ncomp"];
	$tcomp_inv    = $row_detmov["tcomp"];
	$query_movim = sprintf("SELECT * FROM movim WHERE ncomp = %s ", $ncomp_inv);
	$movim = mysqli_query($repuestos, $query_movim) or die(mysqli_error($amercado));
	$totalRows_movim = mysqli_num_rows($movim);
	while ($row_movim = mysqli_fetch_array($movim))
		if ($row_movim["estado"]=='2');
			continue;
	$cant_inv    = $row_detmov["cantidad"];
	$fecha_inv   = $row_movim["fecha"];
	
}
*/
// LEO LOS REGISTROS DE COMPRAS
$preciototal = "";
$fecha = "";
$tcomp  = "";
$ncomp  = "";
$renglones = 0;
$signo = "";
$i = 0;
/*
while($row_detmovcmp = mysqli_fetch_array($detmovcmp)) {
  	
	// LEO MOVIMCMP PARA SACAR LA FECHA
	$ncomp[$i]    = $row_detmovcmp["ncomp"];
	$tcomp[$i]    = $row_detmovcmp["tcomp"];
	$query_movimcmp = sprintf("SELECT * FROM movimcmp WHERE ncomp = %s ", $ncomp[$i]);
	$movimcmp = mysqli_query($repuestos, $query_movimcmp) or die(mysqli_error($amercado));
	$totalRows_movimcmp = mysqli_num_rows($movimcmp);
	
	while ($row_movimcmp = mysqli_fetch_array($movimcmp))
		if ($row_movimcmp["estado"]=='2')
			continue;
	$cant[$i]    = $row_detmovcmp["cantidad"];
	$fecha[$i]   = $row_movimcmp["fecha"];
	$signo[$i] = '1';
	$renglones++;
	$i++;
}
*/
/*
// LEO LOS REGISTROS DE DEVOLUCIONES
while($row_detmovdev = mysqli_fetch_array($detmovdev)) {
  	
	// LEO MOVIMDEV PARA SACAR LA FECHA
	$ncomp[$i]    = $row_detmovdev["ncomp"];
	$tcomp[$i]    = $row_detmovdev["tcomp"];
	$query_movimdev = sprintf("SELECT * FROM movimdev WHERE ncomp = %s ", $ncomp[$i]);
	$movimdev = mysqli_query($repuestos, $query_movimdev) or die(mysqli_error($amercado));
	$totalRows_movimdev = mysqli_num_rows($movimdev);
	
	while ($row_movimdev = mysqli_fetch_array($movimdev))
	if ($row_movimdev["estado"]=='2')
		continue;
	$cant[$i]    = $row_detmovdev["cantidad"];
	$fecha[$i]    = $row_movimdev["fecha"];
	$renglones++;
	$signo[$i] = '1';
	$i++;
}
*/
// LEO LOS REGISTROS DE PEDIDOS
//while($row_cabped = mysqli_fetch_array($detped)) {
  	
	// LEO CABPED PARA SACAR LA FECHA
	//$ncomp[$i]    = $row_detped["ncomp"];
	//$tcomp[$i]    = $row_detped["tcomp"];
	/*
	$query_cabped = sprintf("SELECT * FROM cabped WHERE ncomp = %s ", $ncomp[$i]);
	$cabped = mysqli_query($repuestos, $query_cabped) or die(mysqli_error($amercado));
	$totalRows_cabped = mysqli_num_rows($cabped);
	*/
	while ($row_cabped = mysqli_fetch_array($cabped)) {
	
		$preciototal[$i]    = $row_cabped["preciototal"];
		$fecha[$i]    = $row_cabped["fecha"];
		$ncomp[$i] = $row_cabped["ncomp"];
		$renglones++;
		$i++;
	}
$hay_datos = 0;
if ($i > 0)
	$hay_datos = 1; // echo "CANT REGISTROS =  ".$i."  ";
//AHORA ORDENO LOS MOVIMIENTOS POR FECHA
for ($j=0; $j < $i-1; $j++) {
	if ($fecha[$j] > $fecha[$j+1]) {
		$cant_aux   = $preciototal[$j];
		$fecha_aux  = $fecha[$j];
		$preciototal[$j]   = $preciototal[$j+1];
		$fecha[$j]  = $fecha[$j+1];
		$preciototal[$j+1]   = $cant_aux;
		$fecha[$j+1]  = $fecha_aux;
	}
}

// AHORA LOS IMPRIMO Y VOY CALCULANDO EL SALDO
$hojas = ceil($renglones / 30);
if ($hojas == 0)
	$hojas = 1;

//Create a new PDF file
$pdf=new FPDF();

$pdf->SetAutoPageBreak(0, 5) ;
$pdf->AddPage();

	
//Fields Name position
$Y_Fields_Name_position = 50;
	
//Table position, under Fields Name
$Y_Table_Position = 60;
	
//$pdf->AddPage();
//$pdf->Image('repuestos.jpg',1,8,50);
 //Arial bold 15
 $pdf->SetFont('Arial','B',12);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$fechahoy = date("d-m-Y");
$pdf->Cell(70,10,'COMISIONES POR VENDEDOR',1,0,'C');
$pdf->SetFont('Arial','B',8);
$pdf->SetY(9);
$pdf->SetX(137);
$pdf->SetY(12);
$pdf->SetX(137);
$pdf->SetFont('Arial','B',11);
$pdf->SetY(22);
$pdf->SetX(1);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(25,10,'FECHA          :  ',0,0,'L');
$pdf->SetFont('Arial','B',11);
$pdf->Cell(10);
$pdf->Cell(25,10,$fechahoy.'    ',0,0,'L');
$pdf->SetY(27);
$pdf->SetX(1);
$pdf->Cell(25,10,'VENDEDOR          :  ',0,0,'L');
$pdf->Cell(10);
$pdf->Cell(25,10,$descr_ven.'    ',0,0,'L');
$pdf->SetY(32);
$pdf->SetX(1);
$pdf->Cell(25,10,'DESDE FECHA  :  ',0,0,'L');
$pdf->Cell(10);
$pdf->Cell(25,10,$f_desde.'    ',0,0,'L');
$pdf->SetY(37);
$pdf->SetX(1);
$pdf->Cell(25,10,'HASTA FECHA  :  ',0,0,'L');
$pdf->Cell(10);
$pdf->Cell(25,10,$f_hasta.'    ',0,0,'L');
$pdf->SetX(1);
$pdf->SetX(90);

	

if ($hay_datos) {


	$pdf->Ln(35);

	$pdf->SetFillColor(232,232,232);
	//Bold Font for Field Name
	$pdf->SetFont('Arial','B',11);
	$pdf->SetY($Y_Fields_Name_position);
	$pdf->SetX(5);
	$pdf->Cell(40,10,'TIPO MOV.',1,0,'C',1);
	$pdf->SetX(45);
	$pdf->Cell(40,10,'NRO.CBTE.',1,0,'C',1);
	$pdf->SetX(85);
	$pdf->Cell(30,10,'FECHA.',1,0,'C',1);
	$pdf->SetX(115);
	$pdf->Cell(40,10,'IMPORTE',1,0,'C',1);
	$pdf->SetX(155);
	$pdf->Cell(40,10,'COMISION',1,0,'C',1);
	$pdf->Ln();
	
	//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
	// luego una nueva pagina y la cabecera
	$k = 0;
	$saldo = 0.0;
	$total_cant = 0.00;
	$total_saldo = 0.00;
	$Y_Table_Position = 60;
	for($k=0;$k < $i; $k++) {
		$signo[$k] = 1;
		$tcbte = 2;
		$query_tipcomp = sprintf("SELECT * FROM tipcomp WHERE codnum = %s ", $tcbte);
		$tipcomp = mysqli_query($repuestos, $query_tipcomp) or die(mysqli_error($amercado));
		$totalRows_tipcomp = mysqli_num_rows($tipcomp);
	
		while ($row_tipcomp = mysqli_fetch_array($tipcomp))
			$tmov = $row_tipcomp["descripcion"];
		$nmov = $ncomp[$k];
		$fechmov = $fecha[$k];
	    $cantidad = $preciototal[$k];
 		$saldo   = ($preciototal[$k]) * 0.1;
		$total_cant += $cantidad;
		$total_saldo += $saldo;
				
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(15);
		$pdf->Cell(30,10,$tmov.'    ',0,0,'L');
		$pdf->SetX(55);
		$pdf->Cell(30,10,$nmov.'    ',0,0,'R');
		$pdf->SetX(85);
		$pdf->Cell(30,10,$fechmov.'    ',0,0,'R');
		$pdf->SetX(115);
		$cantidad1   = number_format($cantidad, 2, ',','.');
		$pdf->Cell(40,10,$cantidad1.'    ',0,0,'R');
		$pdf->SetX(155);
		$saldo1   = number_format($saldo, 2, ',','.');
		$pdf->Cell(40,10,$saldo1.'    ',0,0,'R');

		$Y_Table_Position += 4;


	}
	$Y_Table_Position += 4;
	$pdf->SetY($Y_Table_Position);
	$pdf->SetX(85);
	$pdf->Cell(30,10,'TOTALES',1,0,'C',1);
	$pdf->SetX(115);
	$total_cant1   = number_format($total_cant, 2, ',','.');
	$pdf->Cell(40,10,$total_cant1.'    ',0,0,'R');
	$pdf->SetX(155);
	$total_saldo1   = number_format($total_saldo, 2, ',','.');
	$pdf->Cell(40,10,$total_saldo1.'    ',0,0,'R');
	
}
// ACA VA EL PIE AGAIN
		//Posici�n: a 1,5 cm del final
   		$pdf->SetY(-15);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		//N�mero de p�gina
   		$pdf->Cell(0,10,'P�gina '.$pdf->PageNo().'/'.$hojas,0,0,'C');

mysqli_close($amercado);
$pdf->Output();
?>  
