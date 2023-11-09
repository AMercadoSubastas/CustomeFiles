<?php

define('FPDF_FONTPATH','fpdf153/font/');
require('fpdf153/fpdf.php');
//require('rotation/rotation.php');

//Connect to your database
//include("fpdf153/examples/conectmysql.php");
//include("rp_header_catalogo.php");
require_once('Connections/amercado.php');
// DESDE ACA

	
// HASTA ACA

//Select the Products you want to show in your PDF file
//$result=mysqli_query("select codintlote,descripcion,observ from lotes ORDER BY codintlote",$link);
//$number_of_products = mysql_numrows($result);
mysqli_select_db($amercado, $database_amercado);
$cant_hojas = $_POST['remate_num'];
//$colname_remate = 5;
/*
$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);
// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$row_remates = mysqli_fetch_assoc($remates);
$direc = $row_remates["direccion"];
$fecha = $row_remates["fecest"];
$sello = $row_remates["sello"];
*/
//Initialize the 3 columns and the total
$column_code = "";
$column_name1 = "";
$column_name2 = "";
$column_name3 = "";
$column_name4 = "";
$column_name5 = "";
$column_price = "";
$name1 = "";
$name2 = "";
$name3 = "";
$name4 = "";
$name5 = "";
$total = 0;


//while($row_remates = mysqli_fetch_array($remates))
//{
//	$direc = $row_remates["direccion"];
//	$fecha = $row_remates["fecest"];
	//echo $direc;
	//echo $fecha;
//}
$i = 0;
//Create a new PDF file
$pdf=new FPDF('L','mm','A5');
$pdf->SetAutoPageBreak(1 , 5) ;
//$pdf=new FPDF();

$pdf->AddPage();
//while($row_lotes = mysqli_fetch_array($lotes))
while($i < $cant_hojas) 
{
/*
	$query_lotes1 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND secuencia = %s", $colname_remate, $i);
	$lotes1 = mysqli_query($amercado, $query_lotes1) or die(mysqli_error($amercado));
	$row_lotes1 = mysqli_fetch_assoc($lotes1);
	$totalRows_lotes1 = mysqli_num_rows($lotes1);
	*/
	$code = $row_lotes1["codintlote"];
	$name1 = "";
	$name2 = "";
	$name3 = "";
	$name4 = "";
	$name5 = "";
	$column_name1 = "";
	$column_name2 = "";
	$column_name3 = "";
	$column_name4 = "";
	$column_name5 = "";
	/*
	$name1 = substr($row_lotes1["descripcion"],0,50);
	$name2 = substr($row_lotes1["descripcion"],51,100);
	$name3 = substr($row_lotes1["descripcion"],101,150);
	$name4 = substr($row_lotes1["descripcion"],151,200);
	$name5 = substr($row_lotes1["descripcion"],201,250);
	*/
	$real_price = " ";
	//$row_lotes["observ"];
	//$price_to_show = number_format($row["Price"],',','.','.');

	$column_code = $code."\n";
	$column_name1 = $name1."\n";
	$column_name2 = $name2."\n";
	$column_name3 = $name3."\n";
	$column_name4 = $name4."\n";
	$column_name5 = $name5."\n";
	$column_price = $column_price.$price_to_show."\n";

	//Sum all the Prices (TOTAL)
	$total = $total+$real_price;
//}
//mysqli_close($amercado);

//Convert the Total Price to a number with (.) for thousands, and (,) for decimals.
$total = number_format($total,',','.','.');


// Va el header
 //Logo
    $pdf->Image('amercado.jpg',19,8,50);
	
    //Arial bold 15
    $pdf->SetFont('Arial','B',10);
    //Movernos a la derecha
	
    //T�tulo
	//Line(float x1, float y1, float x2, float y2) 
	//Line(1, 1, 186, 1);
	//Line(1, 1, 1, 134);
	//Line(1, 186, 134, 186);
	//Line(134, 1, 134, 186);
	//Rect(float x, float y, float w, float h [, string style]) 
	$pdf->Line(15, 43, 185, 43);
	//$pdf->Line(15, 181, 185, 181);
	$pdf->SetLineWidth(0.8);
	$pdf->Rect(15, 5, 185, 135);
	//Aca viene lo de ORIGINAL / DUPLICADO en vertical
	$pdf->SetY(90);
	$pdf->SetX(3);
	//$pdf->Rotate(90,3,90);
    //$pdf->Text(3,90,'ORIGINAL');
    //$pdf->Rotate(0);
	//$pdf->RotatedText(10,6,'ORIGINAL',90);
	//$pdf->RotatedText(10,6,'ORIGINAL',90);
	//$pdf->Rotate(90,6,90);
	//$pdf->Text(6,90,'ORIGINAL');
	//$pdf->Rotate(0);
	//$pdf->Rect(15, 143, 185, 134);
	$pdf->SetLineWidth(0.2);
	$pdf->SetY(5);
	$pdf->SetX(80);
  	$pdf->SetFont('Arial','B',14);
    $pdf->Cell(120,11,'LOTE N� ',1,0,'L');
	
	$pdf->SetFont('Arial','B',8);
	//$pdf->Ln(1);
	$pdf->SetY(25);
	$pdf->Cell(11);
	$pdf->Cell(70,10,'Av. J.B.Alberdi 1695 1� A Capital Federal',0,0,'L');
	//$pdf->Ln(3);
	$pdf->SetY(28);
	$pdf->Cell(11);
	$pdf->Cell(50,10,'TEL/FAX: (011) 4633-8121/8332/7989',0,0,'C');
	$pdf->SetY(31);
	$pdf->Cell(11);
	$pdf->Cell(50,10,'(1424) - CAPITAL FEDERAL',0,0,'C');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(16);
	$pdf->SetX(80);
	$pdf->Cell(120,11,'Precio $                                                   ',1,0,'B');
	$pdf->SetY(27);
	$pdf->SetX(80);
	$pdf->Cell(120,16,'Fecha                                                PLAZO _____________ DIAS',1,0,'B');
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	//$pdf->Ln(9);
	$pdf->SetY(43);
	$pdf->SetX(15);
	$pdf->Cell(185,8,'Articulo: ',0,0,'L');
	/*
	$pdf->SetY(51);
	$pdf->SetX(15);
	$pdf->Cell(185,8,''.$column_name2,0,0,'L');
	$pdf->SetY(59);
	$pdf->SetX(15);
	$pdf->Cell(185,8,''.$column_name3,0,0,'L');
	$pdf->SetY(67);
	$pdf->SetX(15);
	$pdf->Cell(185,8,''.$column_name4,0,0,'L');
	$pdf->SetY(75);
	$pdf->SetX(15);
	$pdf->Cell(185,8,''.$column_name5,0,0,'L');
	*/
	$pdf->SetY(83);
	$pdf->SetX(15);
	$pdf->Cell(185,14,'Comprador:                                                                                   ',1,0,'B');
	$pdf->SetY(97);
	$pdf->SetX(15);
	$pdf->Cell(185,14,'Se�a:                                                                                      ',1,0,'B');
	$pdf->SetY(111);
	$pdf->SetX(15);
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(185,13,'CONDICIONES DE VENTA',0,0,'C');
	if ($sello=='1') {
		//$pdf->SetX(120);
		$pdf->SetY(111);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(60);
		$pdf->Cell(70,10,'SUJETO A APROBACION',1,0,'C');
	}
	$pdf->SetY(-30);
	$pdf->SetX(17);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(185,10,'10% de comisi�n a cargo del comprador quien se obliga a retirar las mercader�as por su cuenta y riesgo y abonar el saldo de',0,0,'L');
	$pdf->SetY(-27);
	$pdf->SetX(17);
	$pdf->Cell(185,10,'precio dentro  de  las 24 hs.  de ejecutado  el remate, perdiendo la se�a entregada si as�  no  lo hiciere. Estando las mismas a ',0,0,'L');
	$pdf->SetY(-24);
	$pdf->SetX(17);
	$pdf->Cell(185,10,'la vista no se admiten reclamos por ning�n concepto.  	                                                                        ',0,0,'L');
	$pdf->SetY(140);
	$pdf->SetX(15);
	$pdf->SetY(-21);
	$pdf->SetX(15);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(185,13,'ORIGINAL',0,0,'C');
	//$pdf->Cell(185,10,'=================================================================================================================',0,0,'L');

	// DUPLICAMOS DESDE ACA =========================================================================
	//$i++;
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(1 , 5) ;
	$pdf->Image('amercado.jpg',19,8,50);
	
    //Arial bold 15
    $pdf->SetFont('Arial','B',10);
    //Movernos a la derecha
	
    //T�tulo
	//Line(float x1, float y1, float x2, float y2) 
	//Line(1, 1, 186, 1);
	//Line(1, 1, 1, 134);
	//Line(1, 186, 134, 186);
	//Line(134, 1, 134, 186);
	//Rect(float x, float y, float w, float h [, string style]) 
	$pdf->Line(15, 43, 185, 43);
	//$pdf->Line(15, 181, 185, 181);
	$pdf->SetLineWidth(0.8);
	$pdf->Rect(15, 5, 185, 135);
	//$pdf->Rect(15, 143, 185, 134);
	$pdf->SetLineWidth(0.2);
	$pdf->SetY(5);
	$pdf->SetX(80);
  	$pdf->SetFont('Arial','B',14);
    $pdf->Cell(120,11,'LOTE N� ',1,0,'L');
	
	$pdf->SetFont('Arial','B',8);
	//$pdf->Ln(1);
	$pdf->SetY(25);
	$pdf->Cell(11);
	$pdf->Cell(70,10,'Av. J.B.Alberdi 1695 1� A Capital Federal',0,0,'L');
	//$pdf->Ln(3);
	$pdf->SetY(28);
	$pdf->Cell(11);
	$pdf->Cell(50,10,'TEL/FAX: (011) 4633-8121/8332/7989',0,0,'C');
	$pdf->SetY(31);
	$pdf->Cell(11);
	$pdf->Cell(50,10,'(1424) - CAPITAL FEDERAL',0,0,'C');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(16);
	$pdf->SetX(80);
	$pdf->Cell(120,11,'Precio $                                                   ',1,0,'B');
	$pdf->SetY(27);
	$pdf->SetX(80);
	$pdf->Cell(120,16,'Fecha                                                     PLAZO _____________ DIAS',1,0,'B');
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	//$pdf->Ln(9);
	$pdf->SetY(43);
	$pdf->SetX(15);
	$pdf->Cell(185,8,'Articulo: ',0,0,'L');
	/*
	$pdf->SetY(51);
	$pdf->SetX(15);
	$pdf->Cell(185,8,''.$column_name2,0,0,'L');
	$pdf->SetY(59);
	$pdf->SetX(15);
	$pdf->Cell(185,8,''.$column_name3,0,0,'L');
	$pdf->SetY(67);
	$pdf->SetX(15);
	$pdf->Cell(185,8,''.$column_name4,0,0,'L');
	$pdf->SetY(75);
	$pdf->SetX(15);
	$pdf->Cell(185,8,''.$column_name5,0,0,'L');
	*/
	$pdf->SetY(83);
	$pdf->SetX(15);
	$pdf->Cell(185,14,'Comprador:                                                                                   ',1,0,'B');
	$pdf->SetY(97);
	$pdf->SetX(15);
	$pdf->Cell(185,14,'Se�a:                                                                                      ',1,0,'B');
	$pdf->SetY(111);
	$pdf->SetX(15);
	$pdf->SetFont('Arial','B',14);
	$pdf->Cell(185,13,'CONDICIONES DE VENTA',0,0,'C');
	if ($sello=='1') {
		//$pdf->SetX(120);
		$pdf->SetY(111);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(60);
		$pdf->Cell(70,10,'SUJETO A APROBACION',1,0,'C');
	}
	$pdf->SetY(-30);
	$pdf->SetX(17);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(185,10,'10% de comisi�n a cargo del comprador quien se obliga a retirar las mercader�as por su cuenta y riesgo y abonar el saldo de',0,0,'L');
	$pdf->SetY(-27);
	$pdf->SetX(17);
	$pdf->Cell(185,10,'precio dentro  de  las 24 hs.  de ejecutado  el remate, perdiendo la se�a entregada si as�  no  lo hiciere. Estando las mismas a ',0,0,'L');
	$pdf->SetY(-24);
	$pdf->SetX(17);
	$pdf->Cell(185,10,'la vista no se admiten reclamos por ning�n concepto.  	                                                                        ',0,0,'L');
	$pdf->SetY(-21);
	$pdf->SetX(15);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(185,13,'DUPLICADO',0,0,'C');
	// HASTA ACA ====================================================================================
	
    $pdf->Ln(35);
//Fields Name position
$Y_Fields_Name_position = 40;
//Table position, under Fields Name
$Y_Table_Position = 46;

//First create each Field Name
//Gray color filling each Field Name box
$pdf->SetFillColor(232,232,232);
//Bold Font for Field Name
/*
$pdf->SetFont('Arial','B',9);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(10);
$pdf->Cell(10,6,'LOTE',1,0,'L',1);
$pdf->SetX(20);
$pdf->Cell(130,6,'DESCRIPCION',1,0,'L',1);
$pdf->SetX(150);
$pdf->Cell(30,6,'PRECIO BASE',1,0,'R',1);
$pdf->Ln();
*/
//Now show the 3 columns
/*
$pdf->SetFont('Arial','',9);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(10);
$pdf->MultiCell(10,6,$column_code,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(20);
$pdf->MultiCell(130,6,$column_name,1);
$pdf->SetY($Y_Table_Position);
$pdf->SetX(150);
$pdf->MultiCell(30,6,$column_price,1,'R');
*/
//$pdf->SetX(175);
//$pdf->MultiCell(30,6,'$ '.$total,1,'R');

//Create lines (boxes) for each ROW (Product)
//If you don't use the following code, you don't create the lines separating each row
/*
$i = 0;
$pdf->SetY($Y_Table_Position);
while ($i < ($totalRows_lotes - 1))
{
	$pdf->SetX(10);
	$pdf->MultiCell(170,6,'',1);
	$i = $i +1;
}
*/
$i = $i + 1;
$pdf->AddPage();
}
mysqli_close($amercado);
// ACA VA EL PIE
 //Posici�n: a 1,5 cm del final
    //$pdf->SetY(-35);
    //Arial italic 8
    //$pdf->SetFont('Arial','I',8);
    //N�mero de p�gina
    //$pdf->Cell(0,10,'P�gina '.$pdf->PageNo(nb).'/{}',0,0,'C');

$pdf->Output();
?>  
