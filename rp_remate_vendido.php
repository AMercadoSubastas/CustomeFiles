<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');

require('fpdf17/fpdf.php');
require('numaletras.php');
//Connect to your database
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);
$colname_remate = 2152; //$_POST['remate_num'];
//$colname_remate = 1;

$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
//$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);
$renglones = 0;
while($row_lotes = mysqli_fetch_array($lotes)) 
{
  // desde aca 
	$code = "";
	$texto = "";
	$texto2 = "";
	$texto3 = "";
	$texto4 = "";
	$texto5 = "";
	
	$code = $row_lotes["codintlote"];
	$texto = $row_lotes["descor"];
	     $tamano = 53; // tama�o m�ximo renglon
         $contador = 0; 
         $contador1 = 0; 
         $contador2 = 0; 
         $contador3 = 0; 
         $contador4 = 0; 
         $contador5 = 0; 
         $contador6 = 0; 
         $contador7 = 0; 

        $texto = strtoupper($texto);
//echo $texto."<br><br><br>" ;
        $texto_orig= $texto ;
//echo "Texto Original".$texto_orig."<br><br>";
        $largo_orig = strlen($texto_orig);
//echo $largo_orig."<br>";
// Cortamos la cadena por los espacios 

      $arrayTexto =explode(' ',$texto); 
$texto = ''; 

// Reconstruimos el primer renglon
while(isset($texto) && isset($arrayTexto[$contador]) && $tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
    $texto .= ' '.$arrayTexto[$contador]; 
    $contador++; 
} 
//echo $texto."<br>"; 
$largo_primer_renglon = strlen($texto)."<br>"; 
//echo "LARGO TEXTo1:".$largo_primer_renglon."<br>"; 
// Aca empieza un renglon
$texto1 = substr($texto_orig,strlen($texto)) ;


//echo "El TEXTO UNO ES: ".$texto1."<br><br>"; 

$arrayTexto1 =explode(' ',$texto1); 
$texto1 = ''; 
// Reconstruimos el segundo renglon
while(isset($texto1) && isset($arrayTexto1[$contador1]) &&$tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1])&& strlen($arrayTexto1[$contador1])!=0){ 
    $texto1 .= ' '.$arrayTexto1[$contador1]; 
//	echo $texto1."<br>";; 
    $contador1++; 
}
$largo_segundo_renglon = strlen($texto1);
// Aca termiba
//echo "Segundo Renglon".$largo_segundo_renglon."<br><br>"; 
//echo "Largo 1 + 2  :".($largo_segundo_renglon+$largo_primer_renglon);
//echo $texto1."<br>"  ;
$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
$arrayTexto2 =explode(' ',$texto2); 
$texto2 = ''; 
// Reconstruimos el segundo renglon
while(isset($texto2) && isset($arrayTexto2[$contador2]) &&$tamano >= strlen($texto2) + strlen($arrayTexto2[$contador2])&& strlen($arrayTexto2[$contador2])!=0){ 
    $texto2 .= ' '.$arrayTexto2[$contador2]; 
//	echo $texto1."<br>";; 
    $contador2++; 
}
$largo_tercer_renglon = strlen($texto2);
//echo "Tercer Renglon".$largo_tercer_renglon."<br><br>"; 
$largo_tercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);
//echo "Largo 1 + 2  + 3 :".($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);
//echo $texto2."<br>"  ;

$texto3 = substr($texto_orig,$largo_tercer) ;
$arrayTexto3 =explode(' ',$texto3); 
$texto3 = ''; 
// Reconstruimos el c4art6 renglon
while($tamano >= strlen($texto3) + strlen($arrayTexto3[$contador3])&& strlen($arrayTexto3[$contador3])!=0){ 
    $texto3 .= ' '.$arrayTexto3[$contador3]; 
//	echo $texto1."<br>";; 
    $contador3++; 
}
$largo_cuarto_renglon = strlen($texto3);
//echo "Cuarto".$largo_tercer_renglon."<br><br>"; 
$largo_cuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon);
//echo "Largo 1 + 2  + 3  4:".$largo_cuarto;
//echo $texto3."<br>"  ;
$texto4 = substr($texto_orig,$largo_cuarto) ;
$arrayTexto4 =explode(' ',$texto4); 
$texto4 = ''; 
while($tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& strlen($arrayTexto4[$contador4])!=0){ 
    $texto4 .= ' '.$arrayTexto4[$contador4]; 
//	echo $texto1."<br>";; 
    $contador4++; 
}
$largo_quinto_renglon = strlen($texto4);
//echo "Quinto".$largo_quinto_renglon."<br><br>"; 
$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);
//echo "Largo 1 + 2  + 3  4:".$largo_quinto;
//echo $texto4."<br>"  ;
$texto5 = substr($texto_orig,$largo_quinto) ;
$arrayTexto5 =explode(' ',$texto5); 
$texto5 = ''; 
while($tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5])&& strlen($arrayTexto5[$contador5])!=0){ 
    $texto5 .= ' '.$arrayTexto5[$contador5]; 
//	echo $texto1."<br>";; 
    $contador5++; 
}
$largo_sexto_renglon = strlen($texto5);
//echo "Sexto".$largo_sexto_renglon."<br>"; 
$largo_sext6 = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);
//echo "Largo 1 + 2  + 3  4:".$largo_sext6;
//echo $texto5."<br>" 


	//$texto2 = substr($row_lotes["descripcion"],70,140);
	//$texto3 = substr($row_lotes["descripcion"],140,210);
	//$texto4 = substr($row_lotes["descripcion"],210,280);
	//$texto5 = substr($row_lotes["descripcion"],280,350);
	$renglones = $renglones + 1;
	if (strcmp($texto1, "")!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto2, "")!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto3, "")!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto4, "")!=0) {
		$renglones = $renglones + 1;
	}
	if (strcmp($texto5, "")!=0) {
		$renglones = $renglones + 1;
	}
}
$hojas = ceil($renglones / 25);

$query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s ORDER BY codintnum  , codintsublote", $colname_remate);
$lotes = mysqli_query($amercado, $query_lotes) or die(mysqli_error($amercado));
//$row_lotes = mysqli_fetch_assoc($lotes);
$totalRows_lotes = mysqli_num_rows($lotes);

// Leo el remate
$query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $colname_remate);
$remates = mysqli_query($amercado, $query_remate) or die(mysqli_error($amercado));
$row_remates = mysqli_fetch_assoc($remates);
$direc = $row_remates["direccion"];
$fecha = $row_remates["fecreal"];
$sello = $row_remates["sello"];
$cod_loc = $row_remates["codloc"];

$fecha         = substr($fecha,8,2)."-".substr($fecha,5,2)."-".substr($fecha,0,4);

$query_localidad = sprintf("SELECT * FROM localidades WHERE codnum = %s", $cod_loc);
$localidad = mysqli_query($amercado, $query_localidad) or die(mysqli_error($amercado));
$row_localidad = mysqli_fetch_assoc($localidad);
$localid = $row_localidad["descripcion"];
$j = 0; // Me dar� el total de renglones

//Create a new PDF file
$pdf=new FPDF();

$pdf->SetAutoPageBreak(0, 5) ;
$pdf->AddPage();

	
//Fields Name position
$Y_Fields_Name_position = 50;
	
//Table position, under Fields Name
$Y_Table_Position = 60;
	
//$pdf->AddPage();
$pdf->Image('amercado.jpg',10,8,50);
//Arial bold 15
$pdf->SetFont('Arial','B',15);
//Movernos a la derecha
$pdf->Cell(55);
//T�tulo
$pdf->Cell(110,10,'PRECIOS OBTENIDOS EN EL REMATE',1,0,'C');
$pdf->SetFont('Arial','B',8);
//$pdf->Ln(1);
$pdf->SetY(25);
$pdf->Cell(1);
$pdf->Cell(70,10,'Av.Alicia Moreau de Justo 740 4to 19 - CABA',0,0,'L');
	
$pdf->SetY(28);
$pdf->Cell(1);
$pdf->Cell(70,10,'TEL/FAX: (011) 4343-9893',0,0,'L');
	
$pdf->SetFont('Arial','B',10);

$pdf->SetY(22);
$pdf->Cell(80);
	
$pdf->Cell(2);
	
$pdf->SetY(25);
$pdf->Cell(80);
$pdf->Cell(20,10,'Direcci�n :  '.$direc."  ".$localid,0,0,'L');
$pdf->SetX(90);
$pdf->SetY(25);
	
$pdf->SetY(28);
$pdf->Cell(80);
$pdf->Cell(20,10,'Fecha       :  '.$fecha,0,0,'L');
if ($sello=='1') {
	$pdf->SetX(120);
	$pdf->SetY(38);
	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(55);
	$pdf->Cell(70,10,'SUJETO A APROBACION',1,0,'C');
}

$pdf->Ln(35);

$pdf->SetFillColor(232,232,232);
//Bold Font for Field Name
$pdf->SetFont('Arial','B',11);
$pdf->SetY($Y_Fields_Name_position);
$pdf->SetX(1);
$pdf->Cell(14,10,'LOTE',1,0,'C',1);
$pdf->SetX(15);
$pdf->Cell(115,10,'DESCRIPCION',1,0,'C',1);
$pdf->SetX(130);
$pdf->Cell(27,10,'PRECIO OBT',1,0,'C',1);
$pdf->SetX(157);
$pdf->Cell(48,10,'OBSERVACIONES',1,0,'C',1);
$pdf->Ln();
	
//Ahora itero con los datos de los renglones, cuando pasa los 18 mando el pie
// luego una nueva pagina y la cabecera
$j = 0;
while($row_lotes = mysqli_fetch_array($lotes)) 
{
	$texto = "";
	$texto2 = "";
	$texto3 = "";
	$texto4 = "";
	$texto5 = "";
	
	$code = $row_lotes["codintlote"];
	$texto = $row_lotes["descor"];
	$precio = $row_lotes["preciofinal"];
	$observaciones = $row_lotes["observ"];
	if ($precio != 0)
		continue;
	     $tamano = 42; // tama�o m�ximo renglon
         $contador = 0; 
         $contador1 = 0; 
         $contador2 = 0; 
         $contador3 = 0; 
         $contador4 = 0; 
         $contador5 = 0; 
         $contador6 = 0; 
         $contador7 = 0; 

        $texto = strtoupper($texto);
//echo $texto."<br><br><br>" ;
        $texto_orig= $texto ;
//echo "Texto Original".$texto_orig."<br><br>";
        $largo_orig = strlen($texto_orig);
//echo $largo_orig."<br>";
// Cortamos la cadena por los espacios 

      $arrayTexto =explode(' ',$texto); 
$texto = ''; 

// Reconstruimos el primer renglon
while(isset($texto) && isset($arrayTexto[$contador]) && $tamano >= strlen($texto) + strlen($arrayTexto[$contador])){ 
    $texto .= ' '.$arrayTexto[$contador]; 
    $contador++; 
} 
//echo $texto."<br>"; 
$largo_primer_renglon = strlen($texto) ;
//echo "LARGO TEXTo1:".$largo_primer_renglon."<br>"; 
// Aca empieza un renglon
$texto1 = substr($texto_orig,strlen($texto)) ;


//echo "El TEXTO UNO ES: ".$texto1."<br><br>"; 

$arrayTexto1 =explode(' ',$texto1); 
$texto1 = ''; 
// Reconstruimos el segundo renglon
while(isset($texto1) && isset($arrayTexto1[$contador1]) && $tamano >= strlen($texto1) + strlen($arrayTexto1[$contador1])&& strlen($arrayTexto1[$contador1])!=0){ 
    $texto1 .= ' '.$arrayTexto1[$contador1]; 
//	echo $texto1."<br>";; 
    $contador1++; 
}
$largo_segundo_renglon = strlen($texto1);
// Aca termiba
//echo "Segundo Renglon".$largo_segundo_renglon."<br><br>"; 
//echo "Largo 1 + 2  :".($largo_segundo_renglon+$largo_primer_renglon);
//echo $texto1."<br>"  ;
$texto2 = substr($texto_orig,($largo_segundo_renglon+$largo_primer_renglon)) ;
$arrayTexto2 =explode(' ',$texto2); 
$texto2 = ''; 
// Reconstruimos el segundo renglon
while(isset($texto2) && isset($arrayTexto2[$contador2]) && $tamano >= strlen($texto2) + strlen($arrayTexto2[$contador2])&& strlen($arrayTexto2[$contador2])!=0){ 
    $texto2 .= ' '.$arrayTexto2[$contador2]; 
//	echo $texto1."<br>";; 
    $contador2++; 
}
$largo_tercer_renglon = strlen($texto2);
//echo "Tercer Renglon".$largo_tercer_renglon."<br><br>"; 
$largo_tercer = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);
//echo "Largo 1 + 2  + 3 :".($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon);
//echo $texto2."<br>"  ;

$texto3 = substr($texto_orig,$largo_tercer) ;
$arrayTexto3 =explode(' ',$texto3); 
$texto3 = ''; 
// Reconstruimos el c4art6 renglon
while($tamano >= strlen($texto3) + strlen($arrayTexto3[$contador3])&& strlen($arrayTexto3[$contador3])!=0){ 
    $texto3 .= ' '.$arrayTexto3[$contador3]; 
//	echo $texto1."<br>";; 
    $contador3++; 
}
$largo_cuarto_renglon = strlen($texto3);
//echo "Cuarto".$largo_tercer_renglon."<br><br>"; 
$largo_cuarto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon);
//echo "Largo 1 + 2  + 3  4:".$largo_cuarto;
//echo $texto3."<br>"  ;
$texto4 = substr($texto_orig,$largo_cuarto) ;
$arrayTexto4 =explode(' ',$texto4); 
$texto4 = ''; 
while($tamano >= strlen($texto4) + strlen($arrayTexto4[$contador4])&& strlen($arrayTexto4[$contador4])!=0){ 
    $texto4 .= ' '.$arrayTexto4[$contador4]; 
//	echo $texto1."<br>";; 
    $contador4++; 
}
$largo_quinto_renglon = strlen($texto4);
//echo "Quinto".$largo_quinto_renglon."<br><br>"; 
$largo_quinto = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon);
//echo "Largo 1 + 2  + 3  4:".$largo_quinto;
//echo $texto4."<br>"  ;
$texto5 = substr($texto_orig,$largo_quinto) ;
$arrayTexto5 =explode(' ',$texto5); 
$texto5 = ''; 
while($tamano >= strlen($texto5) + strlen($arrayTexto5[$contador5]) && strlen($arrayTexto5[$contador5])!=0){ 
    $texto5 .= ' '.$arrayTexto5[$contador5]; 
//	echo $texto1."<br>";; 
    $contador5++; 
}
$largo_sexto_renglon = strlen($texto5);
//echo "Sexto".$largo_sexto_renglon."<br>"; 
$largo_sext6 = ($largo_segundo_renglon+$largo_primer_renglon+$largo_tercer_renglon+$largo_cuarto_renglon+$largo_quinto_renglon+$largo_sexto_renglon);
//echo "Largo 1 + 2  + 3  4:".$largo_sext6;
//echo $texto5."<br>" 





	$pdf->SetFont('Arial','B',12);
	$pdf->SetY($Y_Table_Position);
	//$pdf->SetX(5);
	//$pdf->Cell(14,9,$code,1,'C');
	//$pdf->SetY($Y_Table_Position);
	//$pdf->SetX(19);
	
//	if (strcmp($texto5, "")!=0) {
	if ($texto5!="") {
		$pdf->SetY($Y_Table_Position);
		$pdf->SetX(1);
		$pdf->Cell(10,7,$code,0,'C');
		//$pdf->SetY($Y_Table_Position);
		$pdf->SetX(1);
		$pdf->Cell(10,42,'',1);
		$pdf->SetX(19);
		$pdf->Cell(140,7,$texto,0,'L');
		$pdf->SetX(131);
		$pdf->Cell(15,7,$precio,0,'L');
		$pdf->SetX(158);
		$pdf->SetFont('Arial','B',9);
		$pdf->Cell(15,7,$observaciones,0,'L');
		$pdf->SetFont('Arial','B',12);
		
		
		//$pdf->SetY($Y_Table_Position);
		$pdf->SetX(19);
		$pdf->Cell(140,42,'',1);
		
		//$pdf->SetY($Y_Table_Position);
		$pdf->SetX(159);
		$pdf->Cell(44,42,'',1);
		$pdf->SetY($Y_Table_Position+7);
		//$pdf->SetY($Y_Table_Position);
		$pdf->SetX(19);
		$pdf->Cell(140,7,$texto1,0,'L');
		
		$pdf->SetY($Y_Table_Position+14);
		$pdf->SetX(19);
		$pdf->Cell(140,7,$texto2,0,'L');
		//$pdf->SetY($Y_Table_Position);
		$pdf->SetY($Y_Table_Position+21);
		$pdf->SetX(19);
		$pdf->Cell(140,7,$texto3,0,'L');
		//$pdf->SetY($Y_Table_Position);
	    $pdf->SetY($Y_Table_Position+28);
		$pdf->SetX(19);
		$pdf->Cell(140,7,$texto4,0,'L');
		//$pdf->SetY($Y_Table_Position);
		$pdf->SetY($Y_Table_Position+35);
			$pdf->SetX(19);
		$pdf->Cell(140,7,$texto5,0,'L');
		//$pdf->SetY($Y_Table_Position);
		$Y_Table_Position = $Y_Table_Position + 35;
	}
	elseif (strcmp($texto4, "")!=0) {
			$pdf->SetY($Y_Table_Position);
			 $pdf->SetX(1);
			$pdf->Cell(14,35,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,35,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,35,'',1);
			$pdf->SetX(157);
			$pdf->Cell(38,35,'',1);
			$pdf->SetX(195);
			$pdf->Cell(10,35,'',1);
			$pdf->SetX(1);
			$pdf->Cell(14,7,$code,0,'C');
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(1);
			$pdf->Cell(14,35,'',1);
			$pdf->SetX(19);
			$pdf->Cell(140,7,$texto,0,'L');
		    $pdf->SetX(131);
		    $pdf->Cell(15,7,$precio,0,'L');
			$pdf->SetX(158);
			$pdf->SetFont('Arial','B',9);
		    $pdf->Cell(15,7,$observaciones,0,'L');
			$pdf->SetFont('Arial','B',12);
		
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(19);
			$pdf->Cell(140,35,$precio,1);
		
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(159);
		//	$pdf->Cell(44,35,'',1);
			$pdf->SetY($Y_Table_Position+7);
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(19);
			$pdf->Cell(140,7,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+14);
			$pdf->SetX(19);
			$pdf->Cell(140,7,$texto2,0,'L');
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetY($Y_Table_Position+21);
			$pdf->SetX(19);
			$pdf->Cell(140,7,$texto3,0,'L');
			//$pdf->SetY($Y_Table_Position);
	    	$pdf->SetY($Y_Table_Position+28);
			$pdf->SetX(19);
			$pdf->Cell(140,7,$texto4,0,'L');
			//$pdf->SetY($Y_Table_Position);
			
			$Y_Table_Position = $Y_Table_Position + 28;
		}
		elseif (strcmp($texto3, "")!=0) {
			$pdf->SetY($Y_Table_Position);
			 $pdf->SetX(1);
			$pdf->Cell(14,28,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,28,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,28,'',1);
			$pdf->SetX(157);
			$pdf->Cell(48,28,'',1);
			//$pdf->SetX(195);
			//$pdf->Cell(10,28,'',1);
			
			$pdf->SetX(5);
			$pdf->Cell(10,7,$code,0,'C');
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(5);
		//	$pdf->Cell(14,28,'',1);
			$pdf->SetX(15);
			$pdf->Cell(140,7,$texto,0,'L');
		     $pdf->SetX(131);
		    $pdf->Cell(15,7,$precio,0,'L');
			$pdf->SetX(158);
			$pdf->SetFont('Arial','B',9);
		    $pdf->Cell(15,7,$observaciones,0,'L');
			$pdf->SetFont('Arial','B',12);
			
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(15);
		//	$pdf->Cell(140,28,'',1);
		
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(159);
		//	$pdf->Cell(44,28,'',1);
			$pdf->SetY($Y_Table_Position+7);
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(15);
			$pdf->Cell(140,7,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+14);
			$pdf->SetX(15);
			$pdf->Cell(140,7,$texto2,0,'L');
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetY($Y_Table_Position+21);
			$pdf->SetX(15);
			$pdf->Cell(140,7,$texto3,0,'L');
			
			
			$Y_Table_Position = $Y_Table_Position + 21;
		}
		elseif (strcmp($texto2, "")!=0) {
		    $pdf->SetX(1);
			$pdf->Cell(14,21,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,21,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,21,'',1);
			$pdf->SetX(157);
			$pdf->Cell(48,21,'',1);
		//	$pdf->SetX(195);
		//	$pdf->Cell(10,21,'',1);
			$pdf->SetX(1);
		
		
			$pdf->SetY($Y_Table_Position);
			$pdf->SetX(1);
			$pdf->Cell(14,7,$code,0,'C');
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(1);
			//$pdf->Cell(10,21,'',1);
			$pdf->SetX(15);
			$pdf->Cell(140,7,$texto,0,'L');
		   	$pdf->SetX(131);
		    $pdf->Cell(15,7,$precio,0,'L');
			$pdf->SetX(158);
			$pdf->SetFont('Arial','B',9);
		    $pdf->Cell(15,7,$observaciones,0,'L');
			$pdf->SetFont('Arial','B',12);
			//$pdf->SetY($Y_Table_Position);
		//	$pdf->SetX(19);
		//	$pdf->Cell(140,21,'',1);
		
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(159);
		//	$pdf->Cell(44,21,'',1);
			$pdf->SetY($Y_Table_Position+7);
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(15);
			$pdf->Cell(140,7,$texto1,0,'L');
		
			$pdf->SetY($Y_Table_Position+14);
			$pdf->SetX(15);
			$pdf->Cell(140,7,$texto2,0,'L');
						


			$Y_Table_Position = $Y_Table_Position + 14;
		}
		elseif (strcmp($texto1, "")!=0) {
			$pdf->SetY($Y_Table_Position);
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(1);
			$pdf->Cell(14,14,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,14,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,14,'',1);
			$pdf->SetX(157);
			$pdf->Cell(48,14,'',1);
			$pdf->SetX(195);
			//$pdf->Cell(10,14,'',1);
			$pdf->SetX(1);
			$pdf->Cell(14,7,$code,0,'C');
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(1);
			$pdf->Cell(14,14,'',1);
			$pdf->SetX(15);
			$pdf->Cell(15,7,$texto,0,'L');
			$pdf->SetX(131);
		    $pdf->Cell(15,7,$precio,0,'L');
			$pdf->SetX(158);
			$pdf->SetFont('Arial','B',9);
		    $pdf->Cell(15,7,$observaciones,0,'L');
			$pdf->SetFont('Arial','B',12);
			//$pdf->SetY($Y_Table_Position);
		//	$pdf->SetX(19);
			//$pdf->Cell(140,14,'',1);
		
			//$pdf->SetY($Y_Table_Position);
		//	$pdf->SetX(159);
		//	$pdf->Cell(44,14,'',1);
			$pdf->SetY($Y_Table_Position+7);
			//$pdf->SetY($Y_Table_Position);
			$pdf->SetX(15);
			$pdf->Cell(15,7,$texto1,0,'L');
								
			$Y_Table_Position = $Y_Table_Position + 7;
		} else  {
			$pdf->SetY($Y_Table_Position);
			$pdf->SetX(1);
			$pdf->Cell(14,7,'',1);
			$pdf->SetX(15);
			$pdf->Cell(115,7,'',1);
			$pdf->SetX(130);
			$pdf->Cell(27,7,'',1);
			$pdf->SetX(157);
			$pdf->Cell(48,7,'',1);
			$pdf->SetX(1);
			$pdf->Cell(10,7,$code,0,'C');
			$pdf->SetX(15);
			$pdf->Cell(15,7,$texto,0,'L');
			$pdf->SetX(131);
		    $pdf->Cell(15,7,$precio,0,'L');
			$pdf->SetX(158);
			$pdf->SetFont('Arial','B',9);
		    $pdf->Cell(15,7,$observaciones,0,'L');
			$pdf->SetFont('Arial','B',12);
			//$pdf->SetY($Y_Table_Position);
			
			//$pdf->Cell(10,7,'',1);
			
			
		
			//$pdf->SetY($Y_Table_Position);
			//$pdf->SetX(15);
		//	$pdf->Cell(115,7,'',1);
		
			//$pdf->SetY($Y_Table_Position);
		//	$pdf->SetX(159);
		//	$pdf->Cell(44,7,'',1);
			//$pdf->SetY($Y_Table_Position+7);
			//$pdf->SetY($Y_Table_Position);
		//	$pdf->SetX(19);
		//	$pdf->Cell(140,7,$texto1,0,'L');
								
		//	$Y_Table_Position = $Y_Table_Position + 7;
		}
	$pdf->SetY($Y_Table_Position);

		
	$j = $j +1;
	$Y_Table_Position = $Y_Table_Position + 7;
	if ($j >=25 || $Y_Table_Position >= 270) {
		// ACA VA EL PIE
		//Posici�n: a 1,5 cm del final
   		$pdf->SetY(-15);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		//N�mero de p�gina
   		$pdf->Cell(0,10,'P�gina '.$pdf->PageNo('nb'),0,0,'C');
		$pdf->AddPage();
		// LUEGO LA CABECERA DE NUEVO
		//Fields Name position
		$Y_Fields_Name_position = 50;
	
		//Table position, under Fields Name
		$Y_Table_Position = 60;
	
	 	//$pdf->AddPage();
   		$pdf->Image('amercado.jpg',10,8,50);
   		//Arial bold 15
   		$pdf->SetFont('Arial','B',15);
	    //Movernos a la derecha
	    $pdf->Cell(55);
	    //T�tulo
	    $pdf->Cell(110,10,'PRECIOS OBTENIDOS EN EL REMATE',1,0,'C');
		$pdf->SetFont('Arial','B',8);
		//$pdf->Ln(1);
		$pdf->SetY(25);
		$pdf->Cell(1);
		$pdf->Cell(70,10,'Av.Alicia Moreau de Justo 740 4to 19 - CABA',0,0,'L');

		$pdf->SetY(28);
		$pdf->Cell(1);
		$pdf->Cell(70,10,'TEL/FAX: (011) 4343-9893',0,0,'L');
	
		$pdf->SetFont('Arial','B',10);

		$pdf->SetY(22);
		$pdf->Cell(80);
	
		$pdf->Cell(2);
	
		$pdf->SetY(25);
		$pdf->Cell(80);
	    $pdf->Cell(20,10,'Direcci�n :  '.$direc."  ".$localid,0,0,'L');
		$pdf->SetX(90);
		$pdf->SetY(25);
	
		$pdf->SetY(28);
		$pdf->Cell(80);
		$pdf->Cell(20,10,'Fecha       :  '.$fecha,0,0,'L');
		if ($sello=='1') {
			$pdf->SetX(120);
			$pdf->SetY(38);
			$pdf->SetFont('Arial','B',15);
			$pdf->Cell(55);
			$pdf->Cell(70,10,'SUJETO A APROBACION',1,0,'C');
		}

	    $pdf->Ln(35);


		$pdf->SetFillColor(232,232,232);
		//Bold Font for Field Name
		$pdf->SetFont('Arial','B',11);
		$pdf->SetY($Y_Fields_Name_position);
		$pdf->SetX(1);
		$pdf->Cell(14,10,'LOTE',1,0,'C',1);
		$pdf->SetX(15);
		$pdf->Cell(115,10,'DESCRIPCION',1,0,'C',1);
		$pdf->SetX(130);
		$pdf->Cell(27,10,'PRECIO OBT',1,0,'C',1);
		$pdf->SetX(157);
		$pdf->Cell(48,10,'OBSERVACIONES',1,0,'C',1);
		//$pdf->SetX(195);
		//$pdf->Cell(10,10,'OBS',1,0,'C',1);
		$pdf->Ln();
		$j = 0;
		//Fields Name position
		$Y_Fields_Name_position = 50;
	
		//Table position, under Fields Name
		$Y_Table_Position = 60;
		
	}
	
}
// ACA VA EL PIE AGAIN
		//Posici�n: a 1,5 cm del final
   		$pdf->SetY(-15);
   		//Arial italic 8
   		$pdf->SetFont('Arial','I',8);
   		//N�mero de p�gina
   		$pdf->Cell(0,10,'P�gina '.$pdf->PageNo('nb'),0,0,'C');

mysqli_close($amercado);
$pdf->Output();
?>  
