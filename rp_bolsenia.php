<?php

set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');

require('fpdf17/fpdf.php');
require('numaletras.php');
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = $_POST['remate_num'];
//$colname_remate = 5;
$original    = $_POST['GrupoOpciones2'];
//Create a new PDF file
$pdf=new FPDF('P','mm','A4');
$pdf->SetAutoPageBreak(1 , 5) ;
$pdf->SetTopMargin(1) ;


//if($original == 1) {
	$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
	$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
	//$row_lotes = mysqli_fetch_assoc($lotes);
	//$totalRows_lotes = mysqli_num_rows($lotes);
	// Leo el remate
	$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
	$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
	$row_remates = mysqli_fetch_assoc($remates);
	$direc = $row_remates["direccion"];
	$fecha = $row_remates["fecreal"];
	$fecha         = substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);
	$sello = $row_remates["sello"];
	$plazo_sap = $row_remates["plazoSAP"]; 
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



	$i = 2;

	while($row_lotes = mysqli_fetch_array($lotes))
	{
		
		$code    = "";
		$texto   = ""; $texto1 = ""; $texto2  = ""; $texto3  = ""; $texto4  = "";	$texto5  = ""; 
		$texto6  = "";
		$texto7  = ""; $texto8  = ""; $texto9  = ""; $texto10 = "";	$texto11 = ""; $texto12 = "";
		$texto13 = ""; $texto14 = ""; $texto15 = ""; $texto16 = ""; $texto17 = ""; $texto18 = "";
		$texto19 = ""; $texto20 = ""; $texto21 = ""; $texto22 = ""; 


		$code = $row_lotes["codintlote"];
		// Desde aca 

		$texto = $row_lotes["descripcion"];
		$tamano = 75; // tama�o m�ximo renglon
		$contador = 0; 
		$contador1 = 0; 
		$contador2 = 0; 
		$contador3 = 0; 
		$contador4 = 0; 
		$contador5 = 0; 
		$contador6 = 0; 
		$contador7 = 0; 

		$texto = strtoupper($texto);

		$texto_orig= $texto ;

		$largo_orig = strlen($texto_orig);
		// Cortamos la cadena por los espacios 

			$arrayTexto =explode(' ',$texto); 
		$texto = ""; 

		// Reconstruimos el primer renglon
		while(isset($arrayTexto[$contador]) && $tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
			$texto .= ' '.$arrayTexto[$contador]; 
			$contador++; 
		} 
		$largo_primer_renglon = strlen($texto)."<br>"; 
		// Aca empieza un renglon
		$texto1 = substr($texto_orig,strlen($texto)) ;
		$arrayTexto1 =explode(' ',$texto1); 
		$texto1 = ""; 
		// Reconstruimos el segundo renglon
		while(isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1]) && strlen($arrayTexto1[$contador1])!=0){
			$texto1 .= ' '.$arrayTexto1[$contador1]; 
			$contador1++; 
		}
		$largo_segundo_renglon = strlen($texto1);

		$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
		$arrayTexto2 =explode(' ',$texto2); 
		$texto2 = ""; 

		// Reconstruimos el tercer renglon
		while(isset($arrayTexto2[$contador2]) && $tamano >= strlen($texto2) + strlen($arrayTexto2[$contador2])&& strlen($arrayTexto2[$contador2])!=0){ 
			$texto2 .= ' '.$arrayTexto2[$contador2]; 
			$contador2++; 
		}
		$largo_tercer_renglon = strlen($texto2);
		$largo_tercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);
		$texto3 = substr($texto_orig,$largo_tercer) ;
		$arrayTexto3 =explode(' ',$texto3); 
		$texto3 = ""; 

		// Reconstruimos el cuarto renglon
		while(isset($arrayTexto3[$contador3]) && $tamano >= strlen($texto3) + strlen($arrayTexto3[$contador3])&& strlen($arrayTexto3[$contador3])!=0){ 
			$texto3 .= ' '.$arrayTexto3[$contador3]; 
			$contador3++; 
		}
		$largo_cuarto_renglon = strlen($texto3);
		$largo_cuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon);

		// ===========================================================================================
		$texto4 = substr($texto_orig,$largo_cuarto) ;
		$arrayTexto4 =explode(' ',$texto4); 
		$texto4 = ""; 
		while(isset($arrayTexto4[$contador4]) && $tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& strlen($arrayTexto4[$contador4])!=0){ 
			$texto4 .= ' '.$arrayTexto4[$contador4]; 
			$contador4++; 
		}
		$largo_quinto_renglon = strlen($texto4);
		$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);

		//==============================================================================================
		$texto5 = substr($texto_orig,$largo_quinto) ;
		$arrayTexto5 =explode(' ',$texto5); 
		$texto5 = ""; 
		while(isset($arrayTexto5[$contador5]) && $tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5])&& strlen($arrayTexto5[$contador5])!=0){ 
			$texto5 .= ' '.$arrayTexto5[$contador5]; 
			$contador5++; 
		}
		$largo_sexto_renglon = strlen($texto5);
		$largo_sexto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);

		// ============================================================================================

		if(($i%2)==0) {
			$pdf->AddPage();
			$Y_Table_Position = 45;
			$pdf->SetFont('Arial','B',10);
			$pdf->SetY($Y_Table_Position);

			if ($texto5!="") {

				$pdf->SetXY(30,44);
				//$pdf->Cell(110,6,"primer renglon seis renglones",0,'L');
				$pdf->Cell(130,6,$texto,0,'L');
				$pdf->SetXY(30,50);
				$pdf->Cell(130,6,$texto1,0,'L');
				$pdf->SetXY(30,56);
				$pdf->Cell(130,6,$texto2,0,'L');
				$pdf->SetXY(30,62);
				$pdf->Cell(130,6,$texto3,0,'L');
				$pdf->SetXY(30,68);
				$pdf->Cell(130,6,$texto4,0,'L');
				$pdf->SetXY(30,74);
				if (strlen($texto5) > 40) {
					$pdf->Cell(130,6,substr($texto5,0,45).' (Mayor detalle en cat�logo)',0,'L');
				} else
					$pdf->Cell(130,6,$texto5,0,'L');
			}
			elseif (strcmp($texto4, "")!=0) {
					$pdf->SetXY(30,44);
					//$pdf->Cell(110,6,"primer renglon cinco renglones",0,'L');
					$pdf->Cell(130,6,$texto,0,'L');
					$pdf->SetXY(30,50);
					$pdf->Cell(130,6,$texto1,0,'L');
					$pdf->SetXY(30,56);
					$pdf->Cell(130,6,$texto2,0,'L');
					$pdf->SetXY(30,62);
					$pdf->Cell(130,6,$texto3,0,'L');
					$pdf->SetXY(30,68);
					$pdf->Cell(130,6,$texto4,0,'L');

				}
				elseif (strcmp($texto3, "")!=0) {
					$pdf->SetXY(30,44);
					//$pdf->Cell(110,6,"primer renglon cuatro renglones",0,'L');
					$pdf->Cell(130,6,$texto,0,'L');
					$pdf->SetXY(30,50);
					$pdf->Cell(130,6,$texto1,0,'L');
					$pdf->SetXY(30,56);
					$pdf->Cell(130,6,$texto2,0,'L');
					$pdf->SetXY(30,62);
					$pdf->Cell(130,6,$texto3,0,'L');

				}
				elseif (strcmp($texto2, "")!=0) {
					$pdf->SetXY(30,44);
					//$pdf->Cell(110,6,"primer renglon tres renglones",0,'L');
					$pdf->Cell(130,6,$texto,0,'L');
					$pdf->SetXY(30,50);
					$pdf->Cell(130,6,$texto1,0,'L');
					$pdf->SetXY(30,56);
					$pdf->Cell(130,6,$texto2,0,'L');

				}
				elseif (strcmp($texto1, "")!=0) {
					$pdf->SetXY(30,44);
					$pdf->Cell(110,6,$texto,0,'L');
					//$pdf->Cell(110,6,"primer renglon dos renglones",0,'L');
					$pdf->SetXY(30,50);
					$pdf->Cell(110,6,$texto1,0,'L');
					//$pdf->Cell(110,6,"segundo renglon",0,'L');

				} else  {
					$pdf->SetXY(30,44);
					//$pdf->Cell(110,6,"primer renglon, un renglon",0,'L');
					$pdf->Cell(130,6,$texto,0,'L');
				}


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

			$real_price = " ";

			$name1 = substr($row_lotes["descripcion"],0,50);
			$name2 = substr($row_lotes["descripcion"],51,100);
			$name3 = substr($row_lotes["descripcion"],101,150);
			$name4 = substr($row_lotes["descripcion"],151,200);
			$name5 = substr($row_lotes["descripcion"],201,250);
			$column_code = $code."\n";
			$column_name1 = $name1."\n";
			$column_name2 = $name2."\n";
			$column_name3 = $name3."\n";
			$column_name4 = $name4."\n";
			$column_name5 = $name5."\n";

			$total = $total+$real_price;


			$total = number_format($total, 2, ',','.');

			// Va el header
			//Logo
			$pdf->Image('images/bolsenia2.jpg',17,6,60);

			$pdf->SetFont('Arial','B',10);


			//T�tulo

			$pdf->Line(15, 43, 185, 43);
			$pdf->SetLineWidth(0.8);
			$pdf->Rect(15, 5, 185, 134);
			//Aca viene lo de ORIGINAL / DUPLICADO en vertical
			$pdf->SetY(90);
			$pdf->SetX(3);

			$pdf->SetLineWidth(0.2);
			$pdf->SetY(5);
			$pdf->SetX(80);
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(120,11,'LOTE N�  '.$column_code,1,0,'L');

			$pdf->SetFont('Arial','B',7);
			$pdf->SetY(25);
			$pdf->Cell(13);
			$pdf->Cell(50,10,'Olga Cossettini 731, Piso 3 - CABA',0,0,'C');
			//$pdf->Ln(3);
			$pdf->SetY(28);
			$pdf->Cell(11);
			$pdf->Cell(50,10,'Tel.: (+54) 11 3984-7400 - L�neas Rotativas',0,0,'C');
			$pdf->SetY(31);
			$pdf->Cell(11);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(50,10,'subastas@grupoadrianmercado.com',0,0,'C');
			$pdf->SetY(34);
			$pdf->Cell(11);
			$pdf->Cell(50,10,'www.grupoadrianmercado.com ',0,0,'C');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetY(16);
			$pdf->SetX(80);
			$pdf->Cell(120,11,'Precio $:',1,0,'B');
			$pdf->SetY(19);
			$pdf->SetX(80);
			$pdf->Cell(100,11,'                     ...............................................................................  ',0,0,'B');
			$pdf->SetY(27);
			$pdf->SetX(80);
			$pdf->Cell(120,16,'Fecha:   '.$fecha.'                                        Plazo: 1 d�a  ',1,0,'B');
			/*
			if ($sello ==1) {
				$pdf->SetY(36);
				$pdf->SetX(80);
				$pdf->Cell(114,7,'Venta sujeta a aprobaci�n por la empresa vendedora '.$plazo_sap,0,0,'C');
			}
			*/
			$pdf->SetY(36);
			$pdf->SetX(80);
			//$pdf->Cell(110,7,'Acepto condiciones generales/particulares de subasta.-',0,0,'C');
			// Datos del Remate
			$pdf->SetFont('Arial','B',10);
			$pdf->SetY(43);
			$pdf->SetX(15);
			$pdf->Cell(185,8,'Art�culo: ',0,0,'L');
			$pdf->SetY(51);
			$pdf->SetX(15);

			$pdf->SetY(59);
			$pdf->SetX(15);

			$pdf->SetY(67);
			$pdf->SetX(15);

			$pdf->SetY(75);
			$pdf->SetX(15);

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

				$pdf->SetY(111);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(130);
				$pdf->Cell(55,8,'SUJETO A APROBACI�N '.$plazo_sap,1,0,'C');
			}
			$pdf->SetY(118);
			$pdf->SetX(17);
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(185,10,'10% de comisi�n a cargo del comprador quien se obliga a retirar las mercader�as por su cuenta y riesgo y abonar el saldo de precio dentro de las 24 hs.',0,0,'L');
			$pdf->SetY(121);
			$pdf->SetX(17);
			$pdf->Cell(185,10,' de ejecutada la subasta, perdiendo la se�a entregada si as� no lo hiciere. Estando las mismas a la vista no se admiten reclamos por ning�n concepto.-',0,0,'L');

			$pdf->SetY(126);
			$pdf->SetX(15);
			$pdf->SetFont('Arial','B',10);
			if ($original == 1)
				$pdf->Cell(185,13,'ORIGINAL',0,0,'C');
			else
				$pdf->Cell(185,13,'DUPLICADO',0,0,'C');

			$pdf->SetY(130);
			$pdf->Line(15, 130, 200, 130);
			$pdf->SetFont('Arial','B',8);
			$pdf->SetY(131);
			$pdf->SetX(15);
			
			$pdf->Cell(185,10,'Acepto condiciones generales y/o particulares de venta en subasta  (seg�n corresponda).-',0,0,'C');
			
			$pdf->SetY(138);
			$pdf->SetX(15);
			
			$pdf->Cell(185,8,'                Control Interno: C�d. RPG 07-02.1 / Versi�n: 0 / Revisi�n: 1 / Confecciona: Subastas / Aprueba: Direcci�n                                          ',0,0,'L');

			$i++;
		}
		// SEGUNDO LOTE IMPRESO EN MISMA HOJA DESDE ACA =========================================================================
		else {
			//$pdf->AddPage();
			//$pdf->Image('images/bolsenia2.png',17,6,60);
			//$pdf->SetAutoPageBreak(1 , 5) ;


			$dup = 145;//150;
			// LINEA DIVISORIA
			$pdf->Line(1, $dup + 1, 210, $dup + 1);
			$Y_Table_Position = 45 + $dup;
			$pdf->SetFont('Arial','B',10);
			$pdf->SetY($Y_Table_Position);

			if ($texto5!="") {
				$pdf->SetXY(30,44 + $dup);
				//$pdf->Cell(110,6,"primer renglon seis renglones",0,'L');
				$pdf->Cell(130,6,$texto,0,'L');
				$pdf->SetXY(30,50 + $dup);
				$pdf->Cell(130,6,$texto1,0,'L');
				$pdf->SetXY(30,56 + $dup);
				$pdf->Cell(130,6,$texto2,0,'L');
				$pdf->SetXY(30,62 + $dup);
				$pdf->Cell(130,6,$texto3,0,'L');
				$pdf->SetXY(30,68 + $dup);
				$pdf->Cell(130,6,$texto4,0,'L');
				$pdf->SetXY(30,74 + $dup);
				if (strlen($texto5) > 40) {
					$pdf->Cell(130,6,substr($texto5,0,45).' (Mayor detalle en cat�logo)',0,'L');
				} else
					$pdf->Cell(130,6,$texto5,0,'L');
			}
			elseif (strcmp($texto4, "")!=0) {
					$pdf->SetXY(30,44 + $dup);
					//$pdf->Cell(110,6,"primer renglon cinco renglones",0,'L');
					$pdf->Cell(130,6,$texto,0,'L');
					$pdf->SetXY(30,50 + $dup);
					$pdf->Cell(130,6,$texto1,0,'L');
					$pdf->SetXY(30,56 + $dup);
					$pdf->Cell(130,6,$texto2,0,'L');
					$pdf->SetXY(30,62 + $dup);
					$pdf->Cell(130,6,$texto3,0,'L');
					$pdf->SetXY(30,68 + $dup);
					$pdf->Cell(130,6,$texto4,0,'L');

				}
				elseif (strcmp($texto3, "")!=0) {
					$pdf->SetXY(30,44 + $dup);
					//$pdf->Cell(110,6,"primer renglon cuatro renglones",0,'L');
					$pdf->Cell(130,6,$texto,0,'L');
					$pdf->SetXY(30,50 + $dup);
					$pdf->Cell(130,6,$texto1,0,'L');
					$pdf->SetXY(30,56 + $dup);
					$pdf->Cell(130,6,$texto2,0,'L');
					$pdf->SetXY(30,62 + $dup);
					$pdf->Cell(130,6,$texto3,0,'L');

				}
				elseif (strcmp($texto2, "")!=0) {
					$pdf->SetXY(30,44 + $dup);
					//$pdf->Cell(110,6,"primer renglon tres renglones",0,'L');
					$pdf->Cell(130,6,$texto,0,'L');
					$pdf->SetXY(30,50 + $dup);
					$pdf->Cell(130,6,$texto1,0,'L');
					$pdf->SetXY(30,56 + $dup);
					$pdf->Cell(130,6,$texto2,0,'L');

				}
				elseif (strcmp($texto1, "")!=0) {
					$pdf->SetXY(30,44 + $dup);
					$pdf->Cell(110,6,$texto,0,'L');
					//$pdf->Cell(110,6,"primer renglon dos renglones",0,'L');
					$pdf->SetXY(30,50 + $dup);
					$pdf->Cell(110,6,$texto1,0,'L');
					//$pdf->Cell(110,6,"segundo renglon",0,'L');

				} else  {
					$pdf->SetXY(30,44 + $dup);
					//$pdf->Cell(110,6,"primer renglon, un renglon",0,'L');
					$pdf->Cell(130,6,$texto,0,'L');
				}


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
			$real_price = " ";
			$name1 = substr($row_lotes["descripcion"],0,50);
			$name2 = substr($row_lotes["descripcion"],51,100);
			$name3 = substr($row_lotes["descripcion"],101,150);
			$name4 = substr($row_lotes["descripcion"],151,200);
			$name5 = substr($row_lotes["descripcion"],201,250);

			$column_code = $code."\n";
			$column_name1 = $name1."\n";
			$column_name2 = $name2."\n";
			$column_name3 = $name3."\n";
			$column_name4 = $name4."\n";
			$column_name5 = $name5."\n";

			$total = $total+$real_price;


			$total = number_format($total, 2, ',','.');

			// Va el header
			//Logo
			$pdf->Image('images/bolsenia2.jpg',17,8 + $dup,60);

			$pdf->SetFont('Arial','B',10);


			//T�tulo

			$pdf->Line(15, 43 + $dup, 185, 43 + $dup);
			$pdf->SetLineWidth(0.8);
			$pdf->Rect(15, 5 + $dup, 185, 134);
			//Aca viene lo de ORIGINAL / DUPLICADO en vertical
			$pdf->SetY(90 + $dup);
			$pdf->SetX(3);

			$pdf->SetLineWidth(0.2);
			$pdf->SetY(5 + $dup);
			$pdf->SetX(80);
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(120,11,'LOTE N�  '.$column_code,1,0,'L');

			$pdf->SetFont('Arial','B',7);
			$pdf->SetY(25 + $dup);
			$pdf->Cell(13);
			$pdf->Cell(50,10,'Olga Cossettini 731, Piso 3 - CABA',0,0,'C');
			//$pdf->Ln(3);
			$pdf->SetY(28 + $dup);
			$pdf->Cell(11);
			$pdf->Cell(50,10,'Tel.: (+54) 11 3984-7400 - L�neas Rotativas',0,0,'C');
			$pdf->SetY(31 + $dup);
			$pdf->Cell(11);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(50,10,'subastas@grupoadrianmercado.com',0,0,'C');
			$pdf->SetY(34 + $dup);
			$pdf->Cell(11);
			$pdf->Cell(50,10,'www.grupoadrianmercado.com ',0,0,'C');
			$pdf->SetFont('Arial','B',10);
			$pdf->SetY(16 + $dup);
			$pdf->SetX(80);
			$pdf->Cell(120,11,'Precio $: ',1,0,'B');
			$pdf->SetY(19 + $dup);
			$pdf->SetX(80);
			$pdf->Cell(100,11,'                     ...............................................................................  ',0,0,'B');

			$pdf->SetY(27 + $dup);
			$pdf->SetX(80);
			$pdf->Cell(120,16,'Fecha:   '.$fecha.'                                        Plazo: 1 d�a  ',1,0,'B');
			/*
			if ($sello ==1) {
				$pdf->SetY(36 + $dup);
				$pdf->SetX(80);
				$pdf->Cell(114,7,'Venta sujeta a aprobaci�n por la empresa vendedora '.$plazo_sap,0,0,'C');
			}
			*/
			$pdf->SetY(36);
			$pdf->SetX(80);
			//$pdf->Cell(110,7,'Acepto condiciones generales/particulares de subasta.-',0,0,'C');
			// Datos del Remate
			$pdf->SetFont('Arial','B',10);
			$pdf->SetY(43 + $dup);
			$pdf->SetX(15);
			$pdf->Cell(185,8,'Art�culo: ',0,0,'L');
			$pdf->SetY(51 + $dup);
			$pdf->SetX(15);

			$pdf->SetY(59 + $dup);
			$pdf->SetX(15);

			$pdf->SetY(67 + $dup);
			$pdf->SetX(15);

			$pdf->SetY(75 + $dup);
			$pdf->SetX(15);

			$pdf->SetY(83 + $dup);
			$pdf->SetX(15);
			$pdf->Cell(185,14,'Comprador:                                                                                   ',1,0,'B');
			$pdf->SetY(97 + $dup);
			$pdf->SetX(15);
			$pdf->Cell(185,14,'Se�a:                                                                                      ',1,0,'B');
			$pdf->SetY(111 + $dup);
			$pdf->SetX(15);
			$pdf->SetFont('Arial','B',14);
			$pdf->Cell(185,13,'CONDICIONES DE VENTA',0,0,'C');
			if ($sello=='1') {

				$pdf->SetY(111 + $dup);
				$pdf->SetFont('Arial','B',8);
				$pdf->Cell(130);
				$pdf->Cell(55,8,'SUJETO A APROBACI�N '.$plazo_sap,1,0,'C');
			}
			$pdf->SetY(118 + $dup);
			$pdf->SetX(17);
			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(185,10,'10% de comisi�n a cargo del comprador quien se obliga a retirar las mercader�as por su cuenta y riesgo y abonar el saldo de precio dentro de las 24 hs.',0,0,'L');
			$pdf->SetY(121 + $dup);
			$pdf->SetX(17);
			$pdf->Cell(185,10,' de ejecutada la subasta, perdiendo la se�a entregada si as� no lo hiciere. Estando las mismas a la vista no se admiten reclamos por ning�n concepto.-',0,0,'L');

			$pdf->SetFont('Arial','B',10);
			$pdf->SetY(126 + $dup);
			$pdf->SetX(15);
			if ($original == 1)
				$pdf->Cell(185,13,'ORIGINAL',0,0,'C');
			else
				$pdf->Cell(185,13,'DUPLICADO',0,0,'C');
			$pdf->SetY(130);
			$pdf->Line(15, 130 + $dup, 200, 130 + $dup);

			
			$pdf->SetY(130 + $dup);
			$pdf->Line(15, 130 + $dup, 200, 130 + $dup);
			$pdf->SetFont('Arial','B',8);
			$pdf->SetY(131 + $dup);
			$pdf->SetX(15);
			$pdf->Cell(185,10,'Acepto condiciones generales y/o particulares de venta en subasta  (seg�n corresponda).-',0,0,'C');
			
			$pdf->SetY(138 + $dup);
			$pdf->SetX(15);
			
			$pdf->Cell(185,8,'                Control Interno: C�d. RPG 07-02.1 / Versi�n: 0 / Revisi�n: 1 / Confecciona: Subastas / Aprueba: Direcci�n                                          ',0,0,'L');
			
							// HASTA ACA 

				//$pdf->Ln(35);
				//Fields Name position
				//$Y_Fields_Name_position = 40;
				//Table position, under Fields Name
				//$Y_Table_Position = 46;

				//First create each Field Name
				//Gray color filling each Field Name box
				$pdf->SetFillColor(232,232,232);
				$i++;
				//$pdf->AddPage();

		}
	}
//}
mysqli_close($amercado);
$pdf->Output();
?> 