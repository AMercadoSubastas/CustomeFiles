<?php
set_time_limit(0); // Para evitar el timeout
// echo "NRO 0  -  ";
define('FPDF_FONTPATH','fpdf17/font/');
require('fpdf17/fpdf.php');
//require('fpdf153/textbox.php');
//require('numaletras.php');
require('numeros_a_letras.php');
header('Content-Type: text/html; charset=utf-8');
//Conecto con la  base de datos
require_once('Connections/amercado.php');
 mysqli_select_db( $database_amercado,$amercado);

// Leo los par�metros del formulario anterior
$f_desde = $_POST['fecha_desde'];
$f_hasta = $_POST['fecha_hasta'];
$f_desde ="'".substr($f_desde,6,4)."-".substr($f_desde,3,2)."-".substr($f_desde,0,2)." 00:00:00'";
$f_hasta = "'".substr($f_hasta,6,4)."-".substr($f_hasta,3,2)."-".substr($f_hasta,0,2)." 23:59:59'";
//echo "FECHA DESDE = ".$f_desde."  FECHA HASTA = ".$f_hasta."  ";
$tcomp = 2;
$serie = 3;
$tcompefe = 0;
$pdf=new FPDF();

$query_det_rec_ord = sprintf("SELECT * FROM detrecibo  WHERE fechahora BETWEEN %s AND %s ORDER BY serierel, ncomprel",$f_desde,$f_hasta);
$det_rec_ord = mysqli_query($amercado, $query_det_rec_ord)  or die("ERROR LEYENDO DETALLE DE RECIBO");
while ($row_det_rec_ord = mysqli_fetch_array($det_rec_ord)) { 
    $tcomp_rec_ord = $row_det_rec_ord["tcomp"];
    $serie_rec_ord = $row_det_rec_ord["serie"];
    $ncomp_rec_ord = $row_det_rec_ord["ncomp"];
    
    $query_cab_rec = sprintf("SELECT * FROM cabrecibo  WHERE ncomp = %d",$ncomp_rec_ord);
    if ($cab_rec = mysqli_query($amercado, $query_cab_rec)) //  or die("ERROR LEYENDO CABECERA DE RECIBO");
    $row_cab_rec = mysqli_fetch_array($cab_rec);
//while ($row_cab_rec = mysqli_fetch_array($cab_rec)) { 
    // echo "NRO 1  -  ";
    // Leo la cabecera
    $pncomp = $row_cab_rec["ncomp"];
    $query_cab_recibo = "SELECT * FROM cabrecibo  WHERE tcomp ='$tcomp' AND serie ='$serie' AND ncomp = '$pncomp'" ;
    
    if ($cab_recibo = mysqli_query($amercado, $query_cab_recibo)) { // or die("ERROR LEYENDO ");
        $row_cab_recibo = mysqli_fetch_assoc($cab_recibo);
        $cliente = $row_cab_recibo["cliente"];
        $fecha = $row_cab_recibo["fecha"];
        $fecha = substr($row_cab_recibo["fecha"],8,2)."-".substr($row_cab_recibo["fecha"],5,2)."-".substr($row_cab_recibo["fecha"],0,4);
        $total = $row_cab_recibo["imptot"];
    }
    // echo "NRO 2  -  ";
    // Leo los renglones
    $query_det_recibo = "SELECT detrecibo.nrodoc , detrecibo.netocbterel FROM detrecibo WHERE ncomp ='$pncomp'";
    $selec_det_recibo = mysqli_query($amercado, $query_det_recibo) or die("ERROR LEYENDO 32");

    $monto_recibo = 0;
    $factura_num = " ";

    while ($monto = mysqli_fetch_array($selec_det_recibo)) {
        $factura_num =$factura_num.$monto['0'].";";
    }
    $fact_tcomp = "";
    $fact_serie = "";
    $fact_ncomp = "";
    $fact_nrodoc = "";

    // echo "NRO 3  -  ";
    // Leo los renglones
    $query_det_recibo2 = "SELECT * FROM detrecibo WHERE ncomp ='$pncomp'";
    $selec_det_recibo2 = mysqli_query($amercado, $query_det_recibo2) or die("ERROR LEYENDO 48");
    $i = 0;
    while ($det_recibo2 = mysqli_fetch_array($selec_det_recibo2)) {
        $fact_tcomp[$i] =	$det_recibo2['tcomprel'];
        $fact_serie[$i] =	$det_recibo2['serierel'];
        $fact_ncomp[$i] =	$det_recibo2['ncomprel'];
        $fact_nrodoc[$i] =	$det_recibo2['nrodoc'];
        $i++;
    }
    $tope_fact = $i;

    // echo "NRO 4  -  ".$tope_fact;

    // Leo el cliente
    $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
    $enti = mysqli_query( $query_entidades,$amercado) or die("ERROR LEYENDO 63");
    $row_entidades = mysqli_fetch_assoc($enti);
    $nom_cliente   = $row_entidades["razsoc"];
    $calle_cliente = $row_entidades["calle"];
    $nro_cliente   = $row_entidades["numero"];
    $codp_cliente  = $row_entidades["codpost"];
    $loc_cliente   = $row_entidades["codloc"]; 
    $cuit_cliente  = $row_entidades["cuit"];
    $tel_cliente   = $row_entidades["tellinea"];
    $tipo_iva   =    $row_entidades["tipoiva"];

    // echo "NRO 5  -  ";
    // TIPO DE IVA 
    $sql_iva = sprintf("SELECT * FROM tipoiva WHERE  codnum = %s", $tipo_iva);
    $tipo_de_iva = mysqli_query( $sql_iva ) or die("ERROR LEYENDO 77");
    $row_tip_iva = mysqli_fetch_assoc($tipo_de_iva);
    $tip_iva_cliente = $row_tip_iva["descrip"];

    // echo "NRO 6  -  ";
    // Leo la localidad
    $query_localidades = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_cliente);
    $localidad = mysqli_query( $query_localidades,$amercado) or die("ERROR LEYENDO 84");
    $row_localidades = mysqli_fetch_assoc($localidad);
    $nomloc = $row_localidades["descripcion"];

    // echo "NRO 7  -  ";
    // CHEQUES
    $query_cheques = "SELECT codban,codsuc,codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='8' AND serie='6'";
    $selec_cheques = mysqli_query( $query_cheques) or die("ERROR LEYENDO 91");

    // echo "NRO 8  -  ";

    $tot_cheques = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='8' AND serie='6'";
    $result_cheques = mysqli_query( $tot_cheques,$amercado) or die("ERROR LEYENDO 96");
    $cheques_tot = 0 ;

    while($row = mysqli_fetch_array($result_cheques)){
        $cheques_tot0	= $row['0'];
        $cheques_tot = $cheques_tot + $cheques_tot0 ;
    }

    // echo "NRO 9  -  ";
    // DEPOSITOS
    $query_depositos = "SELECT codban,codsuc,codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='9' AND serie='7'";
    $selec_depositos = mysqli_query( $query_depositos,$amercado) or die("ERROR LEYENDO 107 ");

    // echo "NRO 10  -  ";

    $tot_dep = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='9' AND serie='7'";
    $result_dep = mysqli_query( $tot_dep ,$amercado) or die("ERROR LEYENDO 112");
    $dep_tot = 0 ;
    while($row1 = mysqli_fetch_array($result_dep)){
        $dep_tot0	= $row1['0'];
        $dep_tot = $dep_tot + $dep_tot0 ;
    }
    //============================================================================================
    // echo "NRO 95  -  ";
    // DEPOSITOS
    $query_depositos_terceros = "SELECT codban,codsuc,codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='95' AND serie='39'";
    $selec_depositos_terceros = mysqli_query( $query_depositos_terceros,$amercado) or die("ERROR LEYENDO 122");

    // echo "NRO 10  -  ";

    $tot_dep_terceros = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='95' AND serie='39'";
    $result_dep_terceros = mysqli_query( $tot_dep_terceros,$amercado ) or die("ERROR LEYENDO 127");
    $dep_tot_terceros = 0 ;
    while($row1_terceros = mysqli_fetch_array($result_dep_terceros)){
        $dep_tot_terceros0	= $row1_terceros['0'];
        $dep_tot_terceros = $dep_tot_terceros + $dep_tot_terceros0 ;
    }

    //============================================================================================

    // echo "NRO 11  -  ";
    // EFECTIVO
    $query_efectivo = "SELECT importe, codchq,tcomp  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND (tcomp='12' OR tcomp='91' OR tcomp='97')";
    $selec_efectivo = mysqli_query( $query_efectivo,$amercado) or die("ERROR LEYENDO 139");
    $efe_tot = 0 ;
    while($row2 = mysqli_fetch_array($selec_efectivo)){
        $efe_tot0	= $row2['0'];
        $efe_tot = $efe_tot + $efe_tot0 ;
        $ndeb = $row2['1'];
        $tcompefe = $row2['2'];

    }
    if ($pncomp == 23714) {
        $efe_tot = 242000.00;
    }

    // echo "NRO 12  -  ";

    // NOTAS DE CREDITO ASOCIADAS A LA FACTURA
    $query_ganan = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='42' ";
    $selec_ganancias = mysqli_query( $query_ganan,$amercado) or die("ERROR LEYENDO 156");

    // echo "NRO 13  -  ";

    $query_ganan1 = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='42' ";
    $selec_ganan1 = mysqli_query( $query_ganan1,$amercado) or die("ERROR LEYENDO 161");

    $ganancia = 0 ;
    while($row3 = mysqli_fetch_array($selec_ganancias)){
        $ganan	= $row3['1'];
        $ganancia = $ganancia + $ganan ;
    }

    //echo "NRO 14  -  ";

    // RETENCION ING BRUTOS
    $query_ing_brutos = "SELECT codchq,importe, tcomp FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND serie='34'";
    $selec_ing_brutos = mysqli_query( $query_ing_brutos,$amercado) or die("ERROR LEYENDO 173");

    // echo "NRO 15  -  ";

    $query_ing_brutos1 = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND serie='34'";
    $selec_ing_brutos1 = mysqli_query( $query_ing_brutos1,$amercado) or die("ERROR LEYENDO 178");

    $t_ing_br = 0 ;
    while($row4 = mysqli_fetch_array($selec_ing_brutos1)){
        $iibb	= $row4['1'];
        $t_ing_br = $t_ing_br + $iibb ;
        //echo " ADENTRO DEL WHILE DE RETENCIONES";
    }

    // echo "NRO 16  -  ";

    // RETENCION IVA
    $query_iva = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='40' AND serie='22'";
    $selec_iva = mysqli_query( $query_iva,$amercado) or die("ERROR LEYENDO 191");

    // echo "NRO 17  -  ";

    $query_iva1 = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='40' AND serie='22'";
    $selec_iva1 = mysqli_query( $query_iva1,$amercado) or die("ERROR LEYENDO 196");

    $tot_iva = 0 ;
    while($row5 = mysqli_fetch_array($selec_iva1)){
        $iva_p	= $row5['1'];
        $tot_iva = $tot_iva + $iva_p ;
    }

    // echo "NRO 18  -  ";

    // RETENCION SUSS
    $query_suss = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='43' AND serie='25'";
    $selec_suss = mysqli_query( $query_suss,$amercado) or die("ERROR LEYENDO 208");

    // echo "NRO 19  -  ";

    $query_suss1 = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='43' AND serie='25'";
    $selec_suss1 = mysqli_query( $query_suss1,$amercado) or die("ERROR LEYENDO 213");

    $tot_sus = 0 ;
    while($row6 = mysqli_fetch_array($selec_suss1)){
        $sus_p	= $row6['1'];
        $tot_sus = $tot_sus + $sus_p ;
    }
    $ganan = 0;
    $total_retenciones = 0;
    $total_retenciones = $tot_sus + $tot_iva + $t_ing_br + $ganan ;
    $total_cheques = 0;
    $total_deposito = 0;
    $total_deposito_terceros = 0;
    $total_efectivo = 0;
    $total_iva = 0;
    $total_ganancias = 0;
    $total_ncred = 0;
    $total_suss = 0;
    $total_ing_brutos = 0;

    // echo "NRO 20  -  ";

        //Creo el PDF file
        // ORIGINAL
        
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
        $pdf->SetX(125);
        //$pdf->Cell(10,10,'Localidad:');
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
        $pdf->Image('images/logo_adrian.jpg',10,8);
        $pdf->Image('images/equis.jpg',100,8);
        $pdf->SetY(23);
        $pdf->SetX(15);
        $pdf->Cell(150,10,'Av. Alicia Moreau de Justo 1080 Piso 4 Of. 198');
        $pdf->SetY(26);
        $pdf->SetX(15);
        $pdf->Cell(150,10,'CABA  (C1107AAP) Tel/Fax: (011)  3984-7400');
        $pdf->SetY(32);
        $pdf->SetX(15);
        $pdf->Cell(150,10,'       IVA  RESPONSABLE  INSCRIPTO');
        //Arial bold 15
        $pdf->SetFont('Arial','B',15);
        //Movernos a la derecha
        $pdf->Cell(80);
        //T�tulo
        //Salto de l�nea
        $pdf->Ln(20);
        $pdf->SetY(15);
        $pdf->SetX(150);
        $pdf->Cell(40,10,$fecha,0,0,'L');
        $pdf->SetFont('Arial','B',10);
        // Numero de Factura
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
        if (isset($codp_cliente))
            $pdf->Cell(100,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') '.$nomloc,0,0,'L');
        else
            $pdf->Cell(100,10,$calle_cliente.' '.$nro_cliente.'                      '.$nomloc,0,0,'L');
        $pdf->SetY(54);
        $pdf->Cell(18);
        $pdf->SetX(120);
        //$pdf->Cell(70,10,$nomloc,0,0,'L');

        // Datos del Remate
        $pdf->SetFont('Arial','B',10);
        $pdf->SetY(60);
        $pdf->Cell(18);


        // Poner del Tipo de Impuesto 
        $pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
        $pdf->SetX(130);
        $pdf->SetY(66);
        $pdf->Cell(30);
        $pdf->Cell(116);
        $pdf->SetX(29);
        $pdf->SetX(15);
        $pdf->SetY(76);



        //$total = 15000.00;

        $letras = numtoletras($total); // convertir_a_letras($total);
        $total  = number_format($total, 2, ',','.');



        $total_de_cheques  = number_format($cheques_tot, 2, ',','.');
        $pdf->Cell(20,10,'Recib� la cantidad de pesos : ',0,0,'L');	
        $pdf->SetY(80);
        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(20,10,$letras,0,0,'L');	
        $pdf->SetX(15);
        $pdf->SetY(85);
        $pdf->SetFont('Arial','B',10);
        if ($pncomp == 26001 || $pncomp == 26002)
            $pdf->Cell(20,10,'en concepto de dep�sito en garant�a para habilitaci�n subasta on line  ',0,0,'L');	
        else
            $pdf->Cell(20,10,'en concepto de cancelaci�n de facturas N�:  ',0,0,'L');	
        $pdf->SetFont('Arial','B',8);
        $pdf->SetY(89);
        $pdf->Cell(20,8,substr($factura_num, 0,121),0,0,'L');
        $pdf->SetY(92);
        $pdf->Cell(20,8,substr($factura_num, 121,120),0,0,'L');
        $pdf->SetFont('Arial','B',10);
        $pdf->SetX(15);
        $pdf->SetY(100);
        $pdf->Cell(20,10,'Forma de Pago :',0,0,'L');		
        $pdf->SetX(15);
        $pdf->SetY(105);

        $efec = $efe_tot;
    if ($pncomp == 26231) {
        $pdf->SetX(15);
        $pdf->SetY(104);
        $efe_tot = 423914.00;
        $efe_nd = "40.000,00";
        $efe_tot  = number_format($efe_tot, 2, ',','.');
        $pdf->Cell(20,10,'Cr�dito a Favor :'.$ndeb.'-------------  $'.$efe_nd,0,0,'L');
        $pdf->SetX(15);
        $pdf->SetY(108);
        $pdf->Cell(20,10,'Efectivo:---------------------------------------------------  $'.$efe_tot,0,0,'L');
    }
    else {
        if ($efe_tot!=0) {
            $efe_tot  = number_format($efe_tot, 2, ',','.');
            if ($tcompefe == 91) {
                $pdf->Cell(20,10,'Cr�dito a Favor :'.$ndeb.'-------------  $'.$efe_tot,0,0,'L');	
            }
            else if ($tcompefe == 97) {
                $pdf->Cell(20,10,'Cancela contra FCPRO:'.$ndeb.'-------------  $'.$efe_tot,0,0,'L');
            }
            else {
                $pdf->Cell(20,10,'Efectivo:---------------------------------------------------  $'.$efe_tot,0,0,'L');	
            }
        } else {
            if ($tcompefe == 91) {
                $pdf->Cell(20,10,'Cr�dito a Favor :'.$ndeb.'-------------  $'.$efe_tot,0,0,'L');	
            }
            else if ($tcompefe == 97) {
                $pdf->Cell(20,10,'Cancela contra FCPRO:'.$ndeb.'-------------  $'.$efe_tot,0,0,'L');
            }
            else {
                $pdf->Cell(20,10,'Efectivo:',0,0,'L');	
            }
        }
    }
        $pdf->SetY(112);
        if ($cheques_tot!=0) {
            $pdf->Cell(20,10,'Cheques : ---------------------------------------------------  $'.$total_de_cheques,0,0,'L');	
        } else {
            $pdf->Cell(20,10,'Cheques :',0,0,'L');	
        }	
        $pdf->SetX(15);
        $pdf->SetY(119);
        if ($dep_tot!=0) {
            $dep_tot  = number_format($dep_tot, 2, ',','.');
            $pdf->Cell(20,10,'Dep�sitos: -------------------------------------------------  $'.$dep_tot,0,0,'L');
        } else {
            $pdf->Cell(20,10,'Dep�sitos:',0,0,'L');	
        }
        $pdf->SetX(15);
        $pdf->SetY(126);
        if ($dep_tot_terceros!=0) {
            $dep_tot_terceros_orig = $dep_tot_terceros;
            $dep_tot_terceros  = number_format($dep_tot_terceros, 2, ',','.');
            $pdf->Cell(20,10,'Dep�sitos a terceros: -----------------------------------------  $'.$dep_tot_terceros,0,0,'L');
        } else {
            $pdf->Cell(20,10,'Dep�sitos a terceros:',0,0,'L');	
        }

        if ($total_retenciones!=0) {
            $total_retenciones  = number_format($total_retenciones, 2, ',','.');	 
            $pdf->SetY(133);
            $pdf->Cell(20,10,'Retenciones :--------------------------------------------  $'.$total_retenciones,0,0,'L');	
        } else {	
            $pdf->SetY(133);
            $pdf->Cell(20,10,'Retenciones :',0,0,'L');		
        }
        $pdf->SetX(15);
        $pdf->SetY(138);
        $pdf->Cell(20,10,'Detalles de Pago:',0,0,'L');	
        $pdf->SetY(142);
        $y=142;
        $cheques_texto =1;
        $depositos_texto =1;
        $depositos_texto_terceros =1;
        $efectivo_texto = 1;
        $retenciones_texto = 1;
        $str_cheques = "";


        if (mysqli_num_rows($selec_cheques)!=0) {	  


            while ($cheques = mysqli_fetch_array($selec_cheques)){
                $codban =$cheques['0'];
                $codsuc =$cheques['1'];
                $codchq =$cheques['2'];
                if ( $cheques_texto==1) {
                    $pdf->Cell(20,10,'CHEQUES:',0,0,'L');	
                    $cheques_texto = $cheques_texto+1;
                }
                $importe_cheque =$cheques['3'];
                // mysqli_select_db($database_amercado);
                $query_de_bancos = "SELECT *  FROM bancos WHERE codnum ='$codban'";
                $selecciona_bancos = mysqli_query( $query_de_bancos,$amercado) or die("ERROR LEYENDO 481");
                $row_bancos = mysqli_fetch_assoc($selecciona_bancos);
                $nombre_bancos =  $row_bancos['nombre'];
                $total_cheques=$total_cheques+$importe_cheque;
                $importe_cheque= number_format($importe_cheque, 2, ',','.');
                $str_cheques = $nombre_bancos."- N�".$codchq."- $".$importe_cheque." ,".$str_cheques ;
            } 


            if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53) 
                $str_cheques = $str_cheques." Retira contra acreditaci�n de cheques."; 


            $total_cheques= number_format($total_cheques, 2, ',','.');
            $largo_str = strlen($str_cheques);
            $ley_chk1 = substr($str_cheques,0,140);
            $ley_chk2 = substr($str_cheques,140,140);
            $ley_chk3 = substr($str_cheques,280,140);
            $ley_chk4 = substr($str_cheques,420,140);
            $ley_chk5 = substr($str_cheques,560,140);
            $ley_chk6 = substr($str_cheques,700,140);
            $ley_chk7 = substr($str_cheques,840,140);
            $ley_chk8 = substr($str_cheques,980,140);
            $ley_chk9 = substr($str_cheques,1120,140);
            $ley_chk10 = substr($str_cheques,1260,140);
            $ley_chk11 = substr($str_cheques,1400,140);
            $ley_chk12 = substr($str_cheques,1540,140);
            $ley_chk13 = substr($str_cheques,1680,140);
            $ley_chk14 = substr($str_cheques,1820,140);
            $ley_chk15 = substr($str_cheques,1960,140);
            $ley_chk16 = substr($str_cheques,2100,140);
            $ley_chk17 = substr($str_cheques,2240,140);
            $ley_chk18 = substr($str_cheques,2380,140);
            $ley_chk19 = substr($str_cheques,2520,140);
            $ley_chk20 = substr($str_cheques,2660,140);
            $ley_chk21 = substr($str_cheques,2800,140);
            $ley_chk22 = substr($str_cheques,2940,140);
            $ley_chk23 = substr($str_cheques,3080,140);
            $ley_chk24 = substr($str_cheques,3220,140);
            $ley_chk25 = substr($str_cheques,3360,140);
            $y=$y+4;

            if (strlen($str_cheques) < 141) {
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ley_chk1,0,0,'L');	
                $pdf->SetFont('Arial','B',10);
                $y = $y+5 ;
                $pdf->SetY($y);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');		
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)	
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else		
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)	
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)	
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
            if ((strlen($str_cheques) < 2661) and (strlen($str_cheques) >2520 )) {
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk19,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)	
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
            if ((strlen($str_cheques) < 2801) and (strlen($str_cheques) >2660 )) {
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)	
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
            if ((strlen($str_cheques) < 2941) and (strlen($str_cheques) >2800 )) {
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk19,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk20,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
            if ((strlen($str_cheques) < 3081) and (strlen($str_cheques) >2940 )) {
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk19,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk20,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk21,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
            if ((strlen($str_cheques) < 3221) and (strlen($str_cheques) >3080 )) {
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk19,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk20,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk21,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk22,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
            if ((strlen($str_cheques) < 3361) and (strlen($str_cheques) >3220 )) {
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk19,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk20,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk21,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk22,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk23,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
            if ((strlen($str_cheques) < 3501) and (strlen($str_cheques) >3360 )) {
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk19,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk20,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk21,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk22,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk23,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk24,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
            if ((strlen($str_cheques) < 3641) and (strlen($str_cheques) >3500 )) {
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
                $pdf->Cell(20,10,$ley_chk17,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk18,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk19,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk20,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk21,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk22,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk23,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk24,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_chk25,0,0,'L');
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',10);
                if ($fact_tcomp[0] == 51 || $fact_tcomp[0] == 53)
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques." Retira contra acreditaci�n de cheques",0,0,'L');
                else
                    $pdf->Cell(20,10,"Total cheques :$".$total_cheques,0,0,'L');
            }
        }

        // Depositos
        $total_depositos=0;
        $str_deposito = "";
        if (mysqli_num_rows($selec_depositos)!=0) {	   
            while ($depositos = mysqli_fetch_array($selec_depositos)){

                $codban =$depositos['0'];
                $codsuc =$depositos['1'];
                $codchq =$depositos['2'];

                if ( $depositos_texto==1) {
                    $y = $y+4 ;
                    $pdf->SetY($y);
                    $pdf->Cell(20,10,'DEPOSITOS:',0,0,'L');	
                    $depositos_texto = $depositos_texto+1;
                }
                $importe_deposito =$depositos['3'];
                // mysqli_select_db($database_amercado);
                $query_de_bancos = "SELECT *  FROM bancos WHERE codnum ='$codban'";
                $selecciona_bancos = mysqli_query( $query_de_bancos,$amercado) or die("ERROR LEYENDO 1889");
                $row_bancos = mysqli_fetch_assoc($selecciona_bancos);
                $nombre_bancos =  $row_bancos['nombre'];
                $total_depositos=$total_depositos+$importe_deposito ;
                $importe_deposito = number_format($importe_deposito, 2, ',','.');
                $str_deposito = $nombre_bancos."- N�".$codchq."- $".$importe_deposito." ,".$str_deposito ;
            } 
            $str_deposito = $str_deposito ; 
            $total_depositos= number_format($total_depositos, 2, ',','.');
            $largo_str = strlen($str_deposito);
            $ley_dep1 = substr($str_deposito,0,140);
            $ley_dep2 = substr($str_deposito,140,140);
            $ley_dep3 = substr($str_deposito,280,140);
            $ley_dep4 = substr($str_deposito,420,140);
            $y=$y+3;

            if (strlen($str_deposito) < 141) {
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ley_dep1,0,0,'L');	
                $pdf->SetFont('Arial','B',10);
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,"Total depositos :$".$total_depositos,0,0,'L');
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
        }
        //=========================================================================

        // Depositos a terceros
        $total_depositos_terceros=0;
        $str_deposito_terceros = "";
        if (mysqli_num_rows($selec_depositos_terceros)!=0) {	   
            while ($depositos_terceros = mysqli_fetch_array($selec_depositos_terceros)){

                $codban_terceros =$depositos_terceros['0'];
                $codsuc_terceros =$depositos_terceros['1'];
                $codchq_terceros =$depositos_terceros['2'];

                if ( $depositos_texto_terceros==1) {
                    $y = $y+4 ;
                    $pdf->SetY($y);
                    $pdf->Cell(20,10,'DEPOSITOS A TERCEROS:',0,0,'L');	
                    $depositos_texto_terceros = $depositos_texto_terceros+1;
                }
                $importe_deposito_terceros =$depositos_terceros['3'];
                // mysqli_select_db($database_amercado);
                $query_de_bancos_terceros = "SELECT *  FROM bancos WHERE codnum ='$codban_terceros'";
                $selecciona_bancos_terceros = mysqli_query( $query_de_bancos_terceros,$amercado) or die("ERROR LEYENDO 1901");
                $row_bancos_terceros = mysqli_fetch_assoc($selecciona_bancos_terceros);
                $nombre_bancos_terceros =  $row_bancos_terceros['nombre'];
                $total_depositos_terceros=$total_depositos_terceros+$importe_deposito_terceros ;
                $importe_deposito_terceros = number_format($importe_deposito_terceros, 2, ',','.');
                $str_deposito_terceros = $nombre_bancos_terceros."- N�".$codchq_terceros."- $".$importe_deposito_terceros." ,".$str_deposito_terceros ;
            } 
            $str_deposito_terceros = $str_deposito_terceros ; 
            $total_depositos_terceros= number_format($total_depositos_terceros, 2, ',','.');
            $largo_str_terceros = strlen($str_deposito_terceros);
            $ley_dep1_terceros = substr($str_deposito_terceros,0,140);
            $ley_dep2_terceros = substr($str_deposito_terceros,140,140);
            $ley_dep3_terceros = substr($str_deposito_terceros,280,140);
            $ley_dep4_terceros = substr($str_deposito_terceros,420,140);
            $y=$y+3;

            if (strlen($str_deposito_terceros) < 141) {
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ley_dep1_terceros,0,0,'L');	
                $pdf->SetFont('Arial','B',10);
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,"Total depositos a terceros :$".$total_depositos_terceros,0,0,'L');
            }
            if ((strlen($str_deposito_terceros) < 281) and (strlen($str_deposito_terceros) >140 )) {
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ley_dep1_terceros,0,0,'L');	
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_dep2_terceros,0,0,'L');	
                $pdf->SetFont('Arial','B',10);
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,"Total depositos a terceros :$".$total_depositos_terceros,0,0,'L');
            }
            if ((strlen($str_deposito_terceros) < 421) and (strlen($str_deposito_terceros) >280 )) {
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ley_dep1_terceros,0,0,'L');	
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_dep2_terceros,0,0,'L');	
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_dep3_terceros,0,0,'L');	
                $pdf->SetFont('Arial','B',10);
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,"Total depositos a terceros:$".$total_depositos_terceros,0,0,'L');
            } 
            if ((strlen($str_deposito_terceros) < 561) and (strlen($str_deposito_terceros) >420 )) {
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ley_dep1_terceros,0,0,'L');	
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_dep2_terceros,0,0,'L');	
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_dep3_terceros,0,0,'L');	
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,$ley_dep4_terceros,0,0,'L');	
                $pdf->SetFont('Arial','B',10);
                $y = $y+5 ;
                $pdf->SetY($y);
                $pdf->Cell(20,10,"Total depositos a terceros:$".$total_depositos_terceros,0,0,'L');
            } 
        }
        //=========================================================================


        if (mysqli_num_rows($selec_efectivo)!=0) {	
            if ( $efectivo_texto==1) {
                if ($pncomp == 26231) {
                    $y = $y+4 ;
                    $pdf->SetY($y);
                    $pdf->Cell(20,10,'Total Cr�dito por N Cr.A0004-00001300: $ '.$efe_nd,0,0,'L');	
                    $y = $y+4 ;
                    $pdf->SetY($y);
                    $pdf->Cell(20,10,'Total Efectivo: $ '.$efe_tot,0,0,'L');	
                    $efectivo_texto = $efectivo_texto+1;
                }
                else {
                    if ($tcompefe == 91) {
                        $y = $y+8 ;
                        $pdf->SetY($y);
                        $pdf->Cell(20,10,'Cr�dito ',0,0,'L');	
                        $y = $y+4 ;
                        $pdf->SetY($y);
                        $pdf->Cell(20,10,'Total Cr�dito  : $'.$efe_tot,0,0,'L');	
                        $efectivo_texto = $efectivo_texto+1;
                    }
                    else if ($tcompefe == 97) {
                        $pdf->Cell(20,10,'FCPRO ',0,0,'L');	
                        $y = $y+4 ;
                        $pdf->SetY($y);
                        $pdf->Cell(20,10,'Total FCPRO  : $'.$efe_tot,0,0,'L');	
                    }
                    else {
                        $y = $y+8 ;
                        $pdf->SetY($y);
                        $pdf->Cell(20,10,'EFECTIVO',0,0,'L');	
                        $y = $y+4 ;
                        $pdf->SetY($y);
                        $pdf->Cell(20,10,'Total efectivo : $'.$efe_tot,0,0,'L');	
                        $efectivo_texto = $efectivo_texto+1;
                    }	


                }

            }
        }
        $ret_iva = "";
        $ret_suss = "";
        $ret_gan = "";
        $ret_ing_br = "";
        if ((mysqli_num_rows($selec_ganancias)!=0) or (mysqli_num_rows($selec_ing_brutos)!=0)	or (mysqli_num_rows($selec_suss)!=0) or (mysqli_num_rows($selec_iva)!=0)) {  
            $y = $y+4 ;
            $pdf->SetY($y);
            $pdf->Cell(20,10,'RETENCIONES',0,0,'L'); 
            if ((mysqli_num_rows($selec_ganancias)!=0)) {
                $total_ganancia=0;
                $ganancia_texto= 1;
                while ($ganancia = mysqli_fetch_array($selec_ganancias)){	
                    $codban =$ganancia['0'];
                    $importe_ganancia =$ganancia['1'];
                    if ( $ganancia_texto==1) {
                        $y = $y+4 ;
                        $pdf->SetY($y);
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(20,10,'Retencion de ganancias',0,0,'L'); 
                        $ganancia_texto =  2;
                    }
                    $total_ganancia=$total_ganancia+$importe_ganancia;
                    $importe_ganancia= number_format($importe_ganancia, 2, ',','.');
                    $ret_gan = $codban." - $".$importe_ganancia." ,".$ret_gan ;
                } 
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ret_gan,0,0,'L'); 
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',8);
                $total_ganancia = number_format($total_ganancia, 2, ',','.');
                $pdf->Cell(20,10,'Total Retencion ganancias $'.$total_ganancia,0,0,'L');

            }

        // ESTA ES LA RUTINA QUE IMPRIME LAS RETENCIONES A PARTIR DE OCTUBRE DE 2018

            if ((mysqli_num_rows($selec_ing_brutos)!=0)) {

                $total_ing_brutos =0;
                $ing_brutos_texto= 1;
                while ($ing_brutos = mysqli_fetch_array($selec_ing_brutos)){	

                    $codban =$ing_brutos['0'];
                    $importe_ing_brutos =$ing_brutos['1'];
                    $tcomp_ret = $ing_brutos['2'];
                    if ( $ing_brutos_texto==1) {
                        $y = $y+4 ;
                        $pdf->SetY($y);

                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(20,10,' ',0,0,'L'); 
                        $ing_brutos_texto =  2;
                    }
                    // Aca leo el tipcomp para ver la descripcion de la retenci�n
                    // mysqli_select_db($database_amercado);
                    $query_tipcomp_ret = "SELECT * FROM tipcomp WHERE codnum = $tcomp_ret";
                    $tipcomp_ret = mysqli_query( $query_tipcomp_ret,$amercado) or die("ERROR LEYENDO RETENCIONES 2070");
                    $row_tipcomp_ret = mysqli_fetch_assoc($tipcomp_ret);
                    $totalRows_tipcomp_ret = mysqli_num_rows($tipcomp_ret);
                    $descr_ret = $row_tipcomp_ret['descripcion'];
                    $total_ing_brutos = $total_ing_brutos+$importe_ing_brutos;
                    $ret_ing_br = $descr_ret." -".$codban." - $".$importe_ing_brutos." , "; //.$ret_ing_br ;
                //} 
                    $y = $y+3 ;
                    $pdf->SetY($y);
                    $pdf->SetFont('Arial','B',7);
                    $pdf->Cell(20,10,$ret_ing_br,0,0,'L');
                }
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',8);
                $total_ing_brutos = number_format($total_ing_brutos, 2, ',','.');
                $pdf->Cell(20,10,'Total Retenciones $'.$total_ing_brutos,0,0,'L');
            }

            if ((mysqli_num_rows($selec_iva)!=0)) {
                $total_iva =0;
                $ing_iva_texto= 1;
                while ($iva = mysqli_fetch_array($selec_iva)){	
                    $codban =$iva['0'];
                    $importe_iva =$iva['1'];
                    if ( $ing_iva_texto ==1) {
                        $y = $y+4 ;
                        $pdf->SetY($y);
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(20,10,'Retencion de IVA',0,0,'L'); 
                        $ing_iva_texto =  2;
                    }

                    $total_iva = $total_iva + $importe_iva;
                    $ret_iva = $codban." - $".$importe_iva." ,".$ret_iva ;
                } 
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ret_iva,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(20,10,'Total Retencion IVA $'.$total_iva,0,0,'L');
            }
            // SUSS  
            if ((mysqli_num_rows($selec_suss)!=0)) {
                $total_suss =0;
                $ing_suss_texto= 1;
                while ($sus = mysqli_fetch_array($selec_suss)){	
                    $codban =$sus['0'];
                    $importe_sus =$sus['1'];
                    if ( $ing_suss_texto ==1) {
                        $y = $y+4 ;
                        $pdf->SetY($y);
                        $pdf->SetFont('Arial','B',9);
                        $pdf->Cell(20,10,'Retencion de SUSS',0,0,'L'); 
                        $ing_suss_texto =  2;
                    }
                    $total_suss = $total_suss + $importe_sus;
                    $ret_suss = $codban." - $".$importe_sus." ,".$ret_suss ;
                } 
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',7);
                $pdf->Cell(20,10,$ret_suss,0,0,'L');
                $y = $y+3 ;
                $pdf->SetY($y);
                $pdf->SetFont('Arial','B',8);
                $pdf->Cell(20,10,'Total Retencion SUSS $'.$total_suss,0,0,'L');
            }
        }

        $pdf->SetY(270);
        $pdf->Cell(20,10,'Total: Pesos',0,0,'L');
        $pdf->SetX(50);
        $pdf->Cell(0,8,$total.'  ',0,0,'L');
        $pdf->SetY(280);
        $pdf->SetX(95);
        $pdf->Cell(20,10,'DUPLICADO',0,0,'C');


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

    

        // ================ACA LE AGREGO EL REMITO EN LOS CASOS QUE CORRESPONDA ====================
        // ACA INTENTAREMOS AGREGAR EL REMITO ===== 09/03/2015 =====================================
        // BUSCO EL PROXIMO NRO DE REMITO

        //Creo el PDF file
        // REMITO ORIGINAL
        //$pdf=new FPDF();
        //$fact_tcomp[$i] =	$det_recibo2['tcomprel'];
        //$fact_serie[$i] =	$det_recibo2['serie'];
        //$fact_ncomp[$i] =	$det_recibo2['ncomprel'];
        //$fact_nrodoc[$i] =	$det_recibo2['nrodoc'];


        for ($j=0;$j < $tope_fact;$j++) {
            //echo "DENTRO DEL FOR".$j."  ";
            if ($fact_tcomp[$j] == 51 || $fact_tcomp[$j] == 53) {
                //echo "DENTRO DEL IF".$j."  ".$fact_tcomp[$j]."  ".$fact_serie[$j]."  ".$fact_ncomp[$j]."  ";
                // LEO LA FACTURA
                // Leo la cabecera de factura
                $query_cabfac = sprintf("SELECT * FROM cabfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $fact_tcomp[$j],$fact_serie[$j],$fact_ncomp[$j]);
                $cabecerafac = mysqli_query( $query_cabfac,$amercado) or die("ERROR LEYENDO 4087");
                $row_cabecerafac = mysqli_fetch_assoc($cabecerafac);

                $fecharem     = $row_cabecerafac["fecdoc"];
                $cliente      = $row_cabecerafac["cliente"];
                $remate       = $row_cabecerafac["codrem"];
                $nrodoc_rem   = $row_cabecerafac["nrodoc"];
                // Leo los renglones

                $query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s ORDER BY codlote" , $fact_tcomp[$j], $fact_serie[$j], $fact_ncomp[$j]);
                $detallefac = mysqli_query( $query_detfac,$amercado) or die("ERROR LEYENDO 4097");
                $totalRows_detallefac = mysqli_num_rows($detallefac);

                //El remate
                $query_remate = sprintf("SELECT * FROM remates WHERE ncomp = %s", $remate);
                $remates = mysqli_query( $query_remate,$amercado) or die("ERROR LEYENDO 4102");
                $row_remates = mysqli_fetch_assoc($remates);

                $remate_ncomp = $row_remates["ncomp"];
                $remate_direc = $row_remates["direccion"];
                $remate_fecha = $row_remates["fecreal"];
                $loc_remate   = $row_remates["codloc"];
                $prov_remate  = $row_remates["codprov"];
                $remate_fecha = substr($remate_fecha,8,2)."-".substr($remate_fecha,5,2)."-".substr($remate_fecha,0,4);

                $totalFilas = 0;
                //Leo si hay direccion de exposicion
                $query_remate_expo = sprintf("SELECT * FROM dir_remates WHERE codrem = %s", $remate);
                $remates_expo = mysqli_query( $query_remate_expo,$amercado) or die("ERROR LEYENDO 4115");
                $totalFilas    =    mysqli_num_rows($remates_expo);
                if ($totalFilas != 0) {
                    $row_remates_expo = mysqli_fetch_assoc($remates_expo);
                    $remate_ncomp = $row_remates_expo["codrem"];
                    $remate_direc = $row_remates_expo["direccion"];
                    $loc_remate   = $row_remates_expo["codloc"];
                    $prov_remate  = $row_remates_expo["codprov"];
                }

                // Leo la localidad del Remate
                $query_localidades_rem = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_remate);
                $localidad_rem = mysqli_query( $query_localidades_rem,$amercado) or die("ERROR LEYENDO 4127");
                $row_localidades_rem = mysqli_fetch_assoc($localidad_rem);
                $nomlocrem = $row_localidades_rem["descripcion"];

                // Leo la Provincia del Remate
                $query_provincia_rem = sprintf("SELECT * FROM provincias WHERE  codnum = %s",$prov_remate);
                $provincia_rem = mysqli_query( $query_provincia_rem,$amercado) or die("ERROR LEYENDO 4133");
                $row_provincia_rem = mysqli_fetch_assoc($provincia_rem);
                $nomprovrem = $row_provincia_rem["descripcion"];


                // Leo el cliente
                $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
                $enti = mysqli_query( $query_entidades,$amercado) or die("ERROR LEYENDO 4140");
                $row_entidades = mysqli_fetch_assoc($enti);
                $nom_cliente   = $row_entidades["razsoc"];
                $calle_cliente = $row_entidades["calle"];
                $nro_cliente   = $row_entidades["numero"];
                $codp_cliente  = $row_entidades["codpost"];
                $loc_cliente   = $row_entidades["codloc"]; 
                $cuit_cliente  = $row_entidades["cuit"];
                $tel_cliente   = $row_entidades["tellinea"];
                $tipo_iva   =    $row_entidades["tipoiva"];

                // Leo la localidad
                $query_localidades = sprintf("SELECT * FROM localidades WHERE  codnum = %s", $loc_cliente);
                $localidad = mysqli_query( $query_localidades,$amercado) or die("ERROR LEYENDO 4153");
                $row_localidades = mysqli_fetch_assoc($localidad);
                $nomloc = $row_localidades["descripcion"];

                // TIPO DE IVA 
                $sql_iva = sprintf("SELECT * FROM tipoiva WHERE  codnum = %s", $tipo_iva);
                $tipo_de_iva = mysqli_query( $sql_iva,$amercado ) or die("ERROR LEYENDO 4159");
                $row_tip_iva = mysqli_fetch_assoc($tipo_de_iva);
                $tip_iva_cliente = $row_tip_iva["descrip"];

                // LEO ULTIMO NRO DE REMITO Y ACTUALIZO SERIES	
                $serie_remito = 28;
                $query_remito = sprintf("SELECT * FROM series WHERE  codnum = %s",$serie_remito);
                $remito = mysqli_query($amercado, $query_remito) or die("ERROR LEYENDO 4209");
                $row_remito = mysqli_fetch_assoc($remito);
                $ultimo = $row_remito["nroact"];
                $remitonum = $ultimo + 1;
                $query_act_remito = sprintf("UPDATE series SET nroact = %s WHERE  codnum = %s",$remitonum, $serie_remito);
                $act_remito = mysqli_query($amercado, $query_act_remito) or die("ERROR LEYENDO 4214");

                //echo "VOY A IMPRIMIR".$j."  ";
                //
                $pdf->AddPage();
                $pdf->SetAutoPageBreak(1 , 2) ;

                // Imprimo la cabecera
                // Linea de arriba
                $pdf->SetLineWidth(.2);
                $pdf->Line(9,7.5,200,7.5);
                $pdf->Line(9,7.5,9,280);
                $pdf->Line(9,280,200,280);
                $pdf->Line(200,7.5,200,280);
                $pdf->Line(9,50,200,50);
                $pdf->Line(9,90,200,90);
                $pdf->Line(9,100,200,100);
                $pdf->Line(25,90,25,280);
                $pdf->Line(9,76,200,76);
                $pdf->Line(107,7.5,107,50);
                $pdf->SetFont('Arial','B',14);

                $pdf->SetY(8);
                $pdf->SetX(150);
                $pdf->Cell(160,10,'REMITO');
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
                $pdf->SetX(125);
                //$pdf->Cell(10,10,'Localidad:');
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

                $pdf->Image('images/logo_adrian.jpg',10,8);
                $pdf->Image('images/equis.jpg',100,8);
                $pdf->SetY(23);
                $pdf->SetX(15);
                $pdf->Cell(150,10,'Av. Alicia Moreau de Justo 1080 Piso 4 Of. 198');
                $pdf->SetY(26);
                $pdf->SetX(15);
                $pdf->Cell(150,10,'CABA  (C1107AAP) Tel/Fax: (011)  3984-7400');
                $pdf->SetY(32);
                $pdf->SetX(15);
                $pdf->Cell(150,10,'       IVA  RESPONSABLE  INSCRIPTO');

                //Arial bold 15
                $pdf->SetFont('Arial','B',15);
                //Movernos a la derecha
                $pdf->Cell(80);
                //T�tulo
                //Salto de l�nea
                $pdf->Ln(20);
                $pdf->SetY(15);
                $pdf->SetX(150);
                // ACA ESTA EL BOLONQUI DE LA FECHA
                //$fecharem = "21/11/2014";
                // D�a del mes con 2 d�gitos, y con ceros iniciales, de 01 a 31
                $dia = date("d");
                // Mes actual en 2 d�gitos y con 0 en caso del 1 al 9, de 1 a 12
                $mes = date("m");
                // A�o actual con 4 d�gitos, ej 2013
                $anio = date("Y");
                $fecharem = $fecha; //$dia."/".$mes."/".$anio;
                $pdf->Cell(40,10,$fecharem,0,0,'L');
                $pdf->SetFont('Arial','B',10);
                // Numero de Remito
                $pdf->SetY(23);
                $pdf->SetX(150);
                $pdf->Cell(40,10,$remitonum ,0,0,'L');
                $pdf->SetFont('Arial','B',10);
                // Datos del Cliente
                $pdf->SetY(48);
                $pdf->Cell(18);
                $pdf->Cell(70,10,$nom_cliente,0,0,'L');
                $pdf->SetY(54);
                $pdf->Cell(18);
                if (isset($codp_cliente))
                    $pdf->Cell(100,10,$calle_cliente.' '.$nro_cliente.' ('.$codp_cliente.') '.$nomloc,0,0,'L');
                else
                    $pdf->Cell(100,10,$calle_cliente.' '.$nro_cliente.'                      '.$nomloc,0,0,'L');
                $pdf->SetY(54);
                $pdf->Cell(18);
                $pdf->SetX(100);
                $pdf->Cell(70,10,$nomloc,0,0,'L');
                // Datos del Remate
                $pdf->SetFont('Arial','B',10);
                $pdf->SetY(60);
                $pdf->Cell(18);
                // Poner del Tipo de Impuesto 
                $pdf->Cell(20,10,$tip_iva_cliente,0,0,'L');
                $pdf->SetX(130);
                $pdf->SetY(66);
                $pdf->Cell(30);
                $pdf->Cell(116);
                $pdf->SetX(29);
                $pdf->SetX(15);
                $pdf->SetY(76);
                $pdf->SetX(15);
                $pdf->SetY(78);
                $pdf->Cell(20,10,'Relacionado con factura N�:  '.$nrodoc_rem ,0,0,'L');	
                $pdf->SetY(92);
                $pdf->SetX(12);
                $pdf->Cell(150,10,' LOTE                                         DESCRIPCION ');

                // DESDE ACA EL WHILE
                            //Inicializo los datos de las columnas de lotes
                $df_codintlote = "";
                $df_descrip1   = "";
                $df_descrip2   = "";
                $df_neto       = "";
                $df_importe    = "";

                // Datos de los renglones
                if ($remate!="" ) {
                    //Posici�n del primer rengl�n 
                     $Y_Table_Position = 102; // $Y_Table_Position = 100;

                     //Los t�tulos de las columnas no los debo poner
                     //Aca van los datos de las columnas

                     $p = $Y_Table_Position;
                     //$pdf->SetY($Y_Table_Position);
                    while($row_detallefac = mysqli_fetch_array($detallefac)) {
                        $lote_num =  $row_detallefac["codlote"];
                        if ($lote_num=="" ) {
                            $df_lote    =  $row_detallefac["concafac"];
                        }
                        if ($lote_num!="" ){
                            $df_lote     = $row_detallefac["codlote"];
                        }
                        $neto          = $row_detallefac["neto"];
                        $importe  = number_format($row_detallefac["neto"], 2, ',','.');
                        $df_neto  = number_format($row_detallefac["neto"], 2, ',','.');
                        $df_importe    = $df_importe.$importe."\n";
                        $query_lotes = sprintf("SELECT * FROM lotes WHERE codrem = %s AND secuencia = %s" , $remate, $df_lote);
                        $lotes = mysqli_query( $query_lotes,$amercado) or die("ERROR LEYENDO 4185");
                        $row_lotes = mysqli_fetch_assoc($lotes);
                        $totalRows_lotes = mysqli_num_rows($lotes);

                        $codintlote    = $row_lotes['codintlote'];
                        $descrip1      = substr($row_lotes['descor'],0,100);
                        $descrip2      = "";//substr(utf8_decode($row_detallefac['descrip']),90,90);
                        if ($lote_num=="" ) {
                            $codintlote    = $row_detallefac['concafac']; // antes decian $row_lotes['concafac'];
                            $df_codintlote = $row_detallefac['concafac']; // antes decian $row_lotes['concafac'];
                        }
                        if ($lote_num!="" ){
                            $codintlote    = $row_lotes['codintlote'];
                        }
                        $df_codintlote = $codintlote; //$df_codintlote.$codintlote."\n";
                        $df_descrip1   = $descrip1; //$df_descrip1.$descrip1;
                        $df_descrip2   = $descrip2; //$df_descrip2.$descrip2;





                //Posici�n de los t�tulos de los renglones, en este caso no va
                $Y_Fields_Name_position = 90;


                $pdf->SetFont('Arial','B',10);
                $pdf->SetY($p);
                $pdf->SetX(11);

                // C�digo interno de Lote
                $pdf->Cell(12,9,$df_codintlote,0,'L');
                $pdf->SetY($p);
                $pdf->SetX(11);

                // Descripci�n del lote en uno o dos renglones
                if (isset($df_descrip2)) {
                    $pdf->SetX(25);
                    $pdf->Cell(100,9,$df_descrip1,0,'L');
                    $pdf->SetY($p+4);
                    $pdf->SetX(25);
                    $pdf->Cell(100,9,$df_descrip2,0,'L');
                    //$pdf->SetY($p);
                    $pdf->SetX(155);
                     $p = $p+8;
                }
                else{
                    $pdf->SetY($p);
                    $pdf->Cell(150,9,$df_descrip1,0,'L');
                    $pdf->SetX(155);
                     $p = $p+4;
                }
            }
                $pdf->SetFont('Arial','B',10);
                $pdf->SetY(280);
                $pdf->SetX(95);
                $pdf->Cell(20,10,'DUPLICADO',0,0,'C');

                //Salto de l�nea
                //Posici�n de los t�tulos de los renglones, en este caso no va
                $Y_Fields_Name_position = 90;
                //Posici�n del primer rengl�n 
                $Y_Table_Position = 100;

                //Los t�tulos de las columnas no los debo poner
                //Aca van los datos de las columnas
                //$p = $Y_Table_Position;
                $pdf->SetY($Y_Table_Position);
                $pdf->SetFont('Arial','B',12);
                $pdf->SetY($p);
                $pdf->SetX(5);
                $pdf->SetX(155);

                // ACA VA EL PIE
                //Posici�n: a 5 cm del final
                $pdf->SetFont('Arial','B',9);
                $pdf->SetY(250);
                $pdf->SetY(254);
                $pdf->SetY(258);
                $pdf->SetY(-38);
                $pdf->SetY(-34);
                $pdf->SetY(-25);
                //Arial italic 8
                $pdf->SetFont('Arial','I',8);
                $pdf->SetY(-20);
                $pdf->SetFont('Arial','B',10);


         
            }
        } 
    }  // del for
}
$pdf->Output();
?>