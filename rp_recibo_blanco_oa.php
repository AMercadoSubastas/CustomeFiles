<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf153/font/');
require('fpdf17/fpdf.php');
require('fpdf17/textbox.php');
require('numaletras.php');


//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior

//$pncomp = $_GET['ncomp'];
$tcomp = 2;
$serie = 3;
$pncomp = 33 ; // hecho el 07/10/2014 por setiembre de 2014

$total_cheques = 0;
$total_deposito = 0;
$total_efectivo = 0;
$total_iva = 0;
$total_ganancias = 0;
$total_suss = 0;
$total_ing_brutos = 0;


   

//Creo el PDF file
// ORIGINAL
$pdf=new FPDF();

$pdf->AddPage();
$pdf->SetAutoPageBreak(1 , 2) ;
// Imprimo la cabecera
   // Linea de arriba
    $pdf->Line(9,7.5,200,7.5);
	$pdf->Line(9,7.5,9,280);
	$pdf->Line(9,280,200,280);
	$pdf->Line(200,7.5,200,280);
	$pdf->Line(9,50,200,50);
	$pdf->Line(9,100,200,100);
	$pdf->Line(9,140,200,140);
	$pdf->Line(9,270,200,270);
	$pdf->Line(9,76,200,76);
	$pdf->Line(107,7.5,107,50);
    $pdf->SetFont('Arial','B',14);
	$pdf->SetY(8);
	$pdf->SetX(150);
	$pdf->Cell(160,10,'RECIBO');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(15);
	$pdf->SetX(130);
    $pdf->Cell(160,10,'Fecha :');
	$pdf->SetY(23);
	$pdf->SetX(130);
    $pdf->Cell(150,10,'N� :');
	$pdf->Cell(150,10,'Fecha : ');
	$pdf->SetY(32);
	$pdf->SetX(120);
    $pdf->Cell(150,10,'C.U.I.T : 20-14394751-4');
	$pdf->SetY(37);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Ing. Brutos : No Responsable');
	$pdf->SetY(42);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Fecha de Inicio de Actividades : 01/01/1999');
	$pdf->SetY(48);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Se�or/es:');
	$pdf->SetY(54);
	$pdf->SetX(10);
	$pdf->Cell(10,10,'Domicilio:');
//	$pdf->Cell(150,10,'Domicilio:');
	$pdf->SetX(125);
	$pdf->Cell(10,10,'Localidad:');
	$pdf->SetY(60);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'IVA:');
	$pdf->SetY(60);
	$pdf->SetX(125);
	$pdf->Cell(150,10,'CUIT:'.$cuit_cliente);
	$pdf->SetY(66);
	$pdf->SetY(66);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Telefono:'.$tel_cliente);
    //Movernos a la derecha
    
    //Fecha
	$pdf->SetY(15);
	$pdf->Cell(130);
	//$pdf->SetLineWidth(.4);
	
	$pdf->Image('images/logo_OFA.jpg',10,8);
//	$pdf->SetY(25);
	$pdf->Image('images/equis.jpg',100,8);

    //Arial bold 15
    $pdf->SetFont('Arial','B',15);
    //Movernos a la derecha
    $pdf->Cell(80);
    //T�tulo
   // $pdf->Cell(30,10,'Title',1,0,'C');
    //Salto de l�nea
    $pdf->Ln(20);
	$pdf->SetY(15);
	$pdf->SetX(150);
    $pdf->Cell(40,10,$fecha,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	// Nuemro de Remito
	$pdf->SetY(23);
	$pdf->SetX(150);
    $pdf->Cell(40,10,$pncomp ,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	// Datos del Cliente
	$pdf->SetY(48);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' '.$codp_cliente.' ',0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->SetX(150);
	$pdf->Cell(70,10,$nomloc,0,0,'L');
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	//$pdf->Ln(9);
	$pdf->SetY(60);
	$pdf->Cell(18);
	// Poner del Tipo de Impuesto 
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
	$pdf->SetX(130);
	
	//$pdf->Ln(10);
	$pdf->SetY(66);
	
	$pdf->Cell(30);
	//$pdf->Cell(20,10,'CONTADO',0,0,'L');
	$pdf->Cell(116);
	$pdf->SetX(29);
	
	$pdf->SetX(15);
	$pdf->SetY(76);
	$letras = convertir_a_letras($total);
	$total  = number_format($total, 2, ',','.');
	$total_de_cheques  = number_format($cheques_tot, 2, ',','.');
	

	$pdf->Cell(20,10,'Recib� la cantidad de pesos : ',0,0,'L');	
	$pdf->SetY(80);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(20,10,$letras,0,0,'L');	
	$pdf->SetX(15);
	$pdf->SetY(85);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,10,'en concepto de cancelaci�n de facturas N�:  ',0,0,'L');	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(89);
	$pdf->Cell(20,10,$factura_num,0,0,'L');	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(15);
	$pdf->SetY(100);
	$pdf->Cell(20,10,'Forma de Pago :',0,0,'L');		
	$pdf->SetX(15);
	$pdf->SetY(107);
	if ($efe_tot!=0) {
	$efe_tot  = number_format($efe_tot, 2, ',','.');
	$pdf->Cell(20,10,'Efectivo:---------------------------------------------------  $'.$efe_tot,0,0,'L');	
	 } else {
	$pdf->Cell(20,10,'Efectivo:',0,0,'L');	
	}
	$pdf->SetY(115);
	if ($cheques_tot!=0) {
	$pdf->Cell(20,10,'Cheques : ---------------------------------------------------  $'.$total_de_cheques,0,0,'L');	
	 } else {
	$pdf->Cell(20,10,'Cheques :',0,0,'L');	
	}	
	$pdf->SetX(15);
	$pdf->SetY(123);
if ($dep_tot!=0) {
    $dep_tot  = number_format($dep_tot, 2, ',','.');
//	$depositos= number_format($depositos, 2, ',','.');
	$pdf->Cell(20,10,'Dep�sitos: -------------------------------------------------  $'.$dep_tot,0,0,'L');
	 } else {
	$pdf->Cell(20,10,'Dep�sitos:',0,0,'L');	
     }
if ($total_retensiones!=0) {
    $total_retensiones  = number_format($total_retensiones, 2, ',','.');	 
	//$pdf->Cell(20,10,'Dep�sitos:',0,0,'L');	
	$pdf->SetY(131);
	$pdf->Cell(20,10,'Retenciones :--------------------------------------------  $'.$total_retensiones,0,0,'L');	
	} else {	
	$pdf->SetY(131);
	$pdf->Cell(20,10,'Retenciones :',0,0,'L');		
	}
	$pdf->SetX(15);
	$pdf->SetY(138);
	$pdf->Cell(20,10,'Detalle de Pago:',0,0,'L');	
	$pdf->SetY(142);
	$y=142;
	$cheques_texto =1;
    $depositos_texto =1;
    $efectivo_texto = 1;
	$retenciones_texto = 1;


   $y=$y+4;
   
  if (strlen($str_cheques) < 141 and (strlen($str_cheques) > 1)) {
    $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
    $y = $y+5 ;
   $pdf->SetY($y);
   //$pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
  if ((strlen($str_cheques) < 281) and (strlen($str_cheques) >140 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
    $y = $y+5 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
   }
  if ((strlen($str_cheques) < 421) and (strlen($str_cheques) >280 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
   $y = $y+5 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  } 
    if ((strlen($str_cheques) < 561) and (strlen($str_cheques) >420 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
   $y = $y+5 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  } 
     if ((strlen($str_cheques) < 701) and (strlen($str_cheques) >560 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
   $y = $y+5 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
     if ((strlen($str_cheques) < 841) and (strlen($str_cheques) >700 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
   $y = $y+5 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
     if ((strlen($str_cheques) < 981) and (strlen($str_cheques) >840 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
  
     $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
    if ((strlen($str_cheques) < 1121) and (strlen($str_cheques) >980 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
  
     $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
      $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
   if ((strlen($str_cheques) < 1261) and (strlen($str_cheques) >1120 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
  if ((strlen($str_cheques) < 1401) and (strlen($str_cheques) >1260 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
     $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
   if ((strlen($str_cheques) < 1541) and (strlen($str_cheques) >1400 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk11,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
    if ((strlen($str_cheques) < 1681) and (strlen($str_cheques) >1540 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk11,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk12,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
     if ((strlen($str_cheques) < 1821) and (strlen($str_cheques) >1680 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk11,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk12,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk13,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  }
if ((strlen($str_cheques) < 1961) and (strlen($str_cheques) >1820 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk11,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk12,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk13,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk14,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  } 
  if ((strlen($str_cheques) < 2101) and (strlen($str_cheques) >1960 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk11,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk12,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk13,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk14,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk15,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  } 
   if ((strlen($str_cheques) < 2241) and (strlen($str_cheques) >2100 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk11,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk12,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk13,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk14,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk15,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk16,0,0,'L');
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  } 

   if ((strlen($str_cheques) < 2381) and (strlen($str_cheques) >2240 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk11,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk12,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk13,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk14,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk15,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk16,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk17,0,0,'L');  // ACA
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  } 

   if ((strlen($str_cheques) < 2521) and (strlen($str_cheques) >2380 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk4,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk5,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk6,0,0,'L');   
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk7,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk8,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk9,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk10,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk11,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk12,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk13,0,0,'L');
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk14,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk15,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk16,0,0,'L');
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk17,0,0,'L');  // ACA
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_chk18,0,0,'L');  // ACA
   $y = $y+5 ;
   $pdf->SetY($y);
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
  } 

   $y=$y+3;
   
  if (strlen($str_deposito) < 141) {
    $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_dep1,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
    $y = $y+5 ;
   $pdf->SetY($y);
  // $pdf->Cell(20,10,"Total depositos :$".$total_depositos,0,0,'L');
  }
  if ((strlen($str_deposito) < 281) and (strlen($str_deposito) >140 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_dep1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_dep2,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
    $y = $y+5 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,"Total depositos :$".$total_depositos,0,0,'L');
   }
  if ((strlen($str_deposito) < 421) and (strlen($str_deposito) >280 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_dep1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_dep2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_dep3,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
   $y = $y+5 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,"Total depositos :$".$total_depositos,0,0,'L');
  } 
    if ((strlen($str_deposito) < 561) and (strlen($str_deposito) >420 )) {
   $pdf->SetY($y);
   $pdf->SetFont('Arial','B',7);
   $pdf->Cell(20,10,$ley_dep1,0,0,'L');	
    $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_dep2,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_dep3,0,0,'L');	
   $y = $y+3 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,$ley_dep4,0,0,'L');	
   $pdf->SetFont('Arial','B',10);
   $y = $y+5 ;
   $pdf->SetY($y);
   $pdf->Cell(20,10,"Total depositos :$".$total_depositos,0,0,'L');
  } 

	$pdf->SetY(214);
//	$pdf->Cell(100,4,$leyenda2,0,0,'L');
	$pdf->SetY(218);
//	$pdf->Cell(100,4,$leyenda3,0,0,'L');
	$pdf->SetY(222);
//	$pdf->Cell(100,4,$leyenda4,0,0,'L');
	$pdf->SetY(226);
//	$pdf->Cell(100,4,$leyenda5,0,0,'L');
	$pdf->SetY(270);
	$pdf->Cell(20,10,'Total: Pesos',0,0,'L');
	$pdf->SetX(50);
	//$total        = number_format($total, 2, ',','.');
	//$pdf->Cell(0,8,$total.'  ',0,0,'L');
	$pdf->SetY(280);
    $pdf->SetX(95);
	$pdf->Cell(20,10,'ORIGINAL',0,0,'C');
	//$pdf->SetFont('Arial','B',10);

    //Salto de l�nea
    //$pdf->Ln(35);
//Posici�n de los t�tulos de los renglones, en este caso no va
$Y_Fields_Name_position = 90;
//Posici�n del primer rengl�n 
$Y_Table_Position = 100;

//Los t�tulos de las columnas no los debo poner

//Aca van los datos de las columnas

$j = $Y_Table_Position;
$pdf->SetY($Y_Table_Position);

$pdf->SetFont('Arial','B',12);
$pdf->SetY($j);
$pdf->SetX(5);


$pdf->SetX(155);

// ACA VA EL PIE

 //Posici�n: a 5 cm del final
 // Ricard0	$pdf->SetY(-50);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetY(250);
//	$pdf->Cell(100,4,$leyenda1,0,0,'L');
  // Ricard0	$pdf->SetY(-46);
    $pdf->SetY(254);
  //  $pdf->Cell(100,4,$leyenda2,0,0,'L');
 //	Ricard0 $pdf->SetY(-42);
    $pdf->SetY(258);
  //  $pdf->Cell(100,4,$leyenda3,0,0,'L');
	$pdf->SetY(-38);
  //  $pdf->Cell(100,4,$leyenda4,0,0,'L');
	$pdf->SetY(-34);
 //   $pdf->Cell(100,4,$leyenda5,0,0,'L');

	$pdf->SetY(-25);
    //Arial italic 8
    $pdf->SetFont('Arial','I',8);

    $pdf->SetY(-20);
	$pdf->SetFont('Arial','B',10);

	$pdf->AddPage();
$pdf->SetAutoPageBreak(1 , 2) ;
// Imprimo la cabecera
   // Linea de arriba
    $pdf->Line(9,7.5,200,7.5);
	$pdf->Line(9,7.5,9,280);
	$pdf->Line(9,280,200,280);
	$pdf->Line(200,7.5,200,280);
	$pdf->Line(9,50,200,50);
	$pdf->Line(9,100,200,100);
	$pdf->Line(9,140,200,140);
	$pdf->Line(9,270,200,270);
	$pdf->Line(9,76,200,76);
	$pdf->Line(107,7.5,107,50);
    $pdf->SetFont('Arial','B',14);
	$pdf->SetY(8);
	$pdf->SetX(150);
	$pdf->Cell(160,10,'RECIBO');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(15);
	$pdf->SetX(130);
    $pdf->Cell(160,10,'Fecha :');
	$pdf->SetY(23);
	$pdf->SetX(130);
    $pdf->Cell(150,10,'N� :');
	$pdf->Cell(150,10,'Fecha :');
	$pdf->SetY(32);
	$pdf->SetX(120);
    $pdf->Cell(150,10,'C.U.I.T : 30-71018343-7');
	$pdf->SetY(37);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Ing. Brutos C.M : 901-265134-1');
	$pdf->SetY(42);
	$pdf->SetX(120);
	$pdf->Cell(150,10,'Fecha de Inicio a Actividades : 01/07/2007');
	$pdf->SetY(48);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Se�or/es:');
	$pdf->SetY(54);
	$pdf->SetX(10);
	$pdf->Cell(10,10,'Domicilio:');
//	$pdf->Cell(150,10,'Domicilio:');
	$pdf->SetX(125);
	$pdf->Cell(10,10,'Localidad:');
	$pdf->SetY(60);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'IVA:');
	$pdf->SetY(60);
	$pdf->SetX(125);
	$pdf->Cell(150,10,'CUIT:'.$cuit_cliente);
	$pdf->SetY(66);
	$pdf->SetY(66);
	$pdf->SetX(10);
	$pdf->Cell(150,10,'Telefono:'.$tel_cliente);
    //Movernos a la derecha
    
    //Fecha
	$pdf->SetY(15);
	$pdf->Cell(130);
	//$pdf->SetLineWidth(.4);
	
	$pdf->Image('images/logo_adrian.jpg',10,8);
//	$pdf->SetY(25);
	$pdf->Image('images/equis.jpg',100,8);

    //Arial bold 15
    $pdf->SetFont('Arial','B',15);
    //Movernos a la derecha
    $pdf->Cell(80);
    //T�tulo
   // $pdf->Cell(30,10,'Title',1,0,'C');
    //Salto de l�nea
    $pdf->Ln(20);
	$pdf->SetY(15);
	$pdf->SetX(150);
    $pdf->Cell(40,10,$fecha,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	// Nuemro de Remito
	$pdf->SetY(23);
	$pdf->SetX(150);
    $pdf->Cell(40,10,$pncomp ,0,0,'L');
	$pdf->SetFont('Arial','B',10);
	// Datos del Cliente
	$pdf->SetY(48);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$nom_cliente,0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->Cell(70,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') ',0,0,'L');
	$pdf->SetY(54);
	$pdf->Cell(18);
	$pdf->SetX(150);
	$pdf->Cell(70,10,$nomloc,0,0,'L');
	// Datos del Remate
	$pdf->SetFont('Arial','B',10);
	//$pdf->Ln(9);
	$pdf->SetY(60);
	$pdf->Cell(18);
	// Poner del Tipo de Impuesto 
	$pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
	$pdf->SetX(130);
	
	//$pdf->Ln(10);
	$pdf->SetY(66);
	
	$pdf->Cell(30);
	//$pdf->Cell(20,10,'CONTADO',0,0,'L');
	$pdf->Cell(116);
	$pdf->SetX(29);
	
	$pdf->SetX(15);
	$pdf->SetY(76);

	$pdf->Cell(20,10,'Recib� la cantidad de pesos : ',0,0,'L');	
	$pdf->SetY(80);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(20,10,$letras,0,0,'L');	
	$pdf->SetX(15);
	$pdf->SetY(85);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,10,'en concepto de cancelaci�n de facturas N�:  ',0,0,'L');	
	$pdf->SetFont('Arial','B',8);
	$pdf->SetY(89);
	$pdf->Cell(20,10,$factura_num,0,0,'L');	
	$pdf->SetFont('Arial','B',10);
	$pdf->SetX(15);
	$pdf->SetY(100);
	$pdf->Cell(20,10,'Forma de Pago :',0,0,'L');		
	$pdf->SetX(15);
	$pdf->SetY(107);
	if ($efe_tot!=0) {
	
	$pdf->Cell(20,10,'Efectivo:---------------------------------------------------  $'.$efe_tot,0,0,'L');	
	 } else {
	$pdf->Cell(20,10,'Efectivo:',0,0,'L');	
	}
	$pdf->SetY(115);
	if ($cheques_tot!=0) {
	$pdf->Cell(20,10,'Cheques : ---------------------------------------------------  $'.$total_de_cheques,0,0,'L');	
	 } else {
	$pdf->Cell(20,10,'Cheques :',0,0,'L');	
	}	
	$pdf->SetX(15);
	$pdf->SetY(123);
if ($dep_tot!=0) {
    
//	$depositos= number_format($depositos, 2, ',','.');
	$pdf->Cell(20,10,'Dep�sitos: -------------------------------------------------  $'.$dep_tot,0,0,'L');
	 } else {
	$pdf->Cell(20,10,'Dep�sitos:',0,0,'L');	
     }
if ($total_retensiones!=0) {
  
	//$pdf->Cell(20,10,'Dep�sitos:',0,0,'L');	
	$pdf->SetY(131);
	$pdf->Cell(20,10,'Retenciones :--------------------------------------------  $'.$total_retensiones,0,0,'L');	
	} else {	
	$pdf->SetY(131);
	$pdf->Cell(20,10,'Retenciones :',0,0,'L');		
	}
	$pdf->SetX(15);
	$pdf->SetY(138);
	$pdf->Cell(20,10,'Detalles de Pago:',0,0,'L');	
	$pdf->SetY(142);
	
	//// Desde ACAAA
		$y=142;
	
    $depositos_texto =1;
    $efectivo_texto = 1;
	$retenciones_texto = 1;


$pdf->Output();
?>  
