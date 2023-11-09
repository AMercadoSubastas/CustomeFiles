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

$acum_prov=array();
$acum_public=array();
$acum_serv=array();
$acum_comis=array();
$acum_lotes21=array();
$acum_lotes105=array();
$descrip_prov=array();
$remates = array();
$remates[0] = $remate;

for ($j=1;$j < 30; $j++) {
	$query_prov = sprintf("SELECT * FROM provincias WHERE codnum = %s", $j);
	$prov = mysqli_query($amercado, $query_prov) or die (mysqli_error($amercado));
		$row_prov = mysqli_fetch_assoc($prov);
		$descrip_prov[$j] = $row_prov["descripcion"];
}
for ($j=1;$j < 30; $j++) {
	$acum_prov[$j] = 0.00;
	$acum_public[$j] = 0.00;
	$acum_serv[$j] = 0.00;
	$acum_comis[$j] = 0.00;
	$acum_lotes21[$j] = 0.00;
	$acum_lotes105[$j] = 0.00;
}
$acum_totrem_neto21 = 0.00;
$acum_totrem_neto105 = 0.00;
$acum_totrem_iva = 0.00;
$acum_totrempublic = 0.00;
$acum_totrem_total = 0.00;
$acum_totremcomis = 0.00;
$acum_totremservicios = 0.00;
$fechahoy = date("d-m-Y");

//Primero leo la cantidad de remates que se generaron a partir del original
$query_remasoc = "SELECT * FROM REMATES WHERE observacion LIKE '%$remate%'";
//echo "query = ".$query_remasoc." - ";
$remasoc = mysqli_query($amercado, $query_remasoc) or die("ERROR LEYENDO REMATES ASOCIADOS");

$j = 1;
while ($rows_remasoc = mysqli_fetch_array($remasoc)){
    $remates[$j] = $rows_remasoc["ncomp"];
    //echo "REMATE NRO ".$j." - ".$remates[$j]." - ";
    $j++;
}
$tope = $j;
$pdf=new FPDF('L','mm','Legal');


for ($k = 0; $k < $tope;$k++) {
    $query_cabf = sprintf("SELECT * FROM cabfac WHERE codrem = %s AND tcomp NOT IN (98,99) ORDER BY tcomp  , ncomp", $remates[$k]);
    $cabf = mysqli_query($amercado, $query_cabf) or die("ERROR LEYENDO CABFAC 57");	
    $rows_cabf = mysqli_fetch_array($cabf);
    $usuario = $rows_cabf["usuario"];
    if (isset($usuario) && $usuario != null) {
        $query_usu = sprintf("SELECT * FROM usuarios WHERE codnum = %s ", $usuario);
        $usu = mysqli_query($amercado, $query_usu) or die ("ERROR LEYENDO USUARIOS 62");
        $rows_usu = mysqli_fetch_array($usu);
        $nombre_usu = $rows_usu["nombre"];
    }
    else 
        $nombre_usu = "No definido";
    $query_cabfac = sprintf("SELECT * FROM cabfac WHERE (tcomp BETWEEN 51 AND 64 OR tcomp IN (89,92,93,94,104,105,111,112)) AND codrem = %s ORDER BY fecreg, nrodoc, tcomp", $remates[$k]);
    $cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC 68");

    // Inicio el pdf con los datos de cabecera
      
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
    $pdf->Cell(110);
    $pdf->Cell(60,10,' Ventas por Remate Consolidado (C�rdoba) - Usuario cobranzas: '.$nombre_usu,0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->SetY(25);
    $pdf->Cell(3);
    $pdf->Cell(20,16,'    Fecha',1,0,'L');
    $pdf->Cell(30,16,' Nro.Factura',1,0,'L');
    $pdf->Cell(40,16,'       Raz�n Social',1,0,'L');
    $pdf->Cell(22,16,'     CUIT',1,0,'L');
    $pdf->Cell(23,16,' Publicidad y ',1,0,'L');
    $pdf->Cell(26,16,'    Servicios ',1,0,'L');
    $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
    $pdf->Cell(26,16,' Lotes al 21 % ',1,0,'L');
    $pdf->Cell(24,16,' Lotes al 10,5 %',1,0,'L');
    $pdf->Cell(24,16,' IVA Debito',1,0,'L');
    $pdf->Cell(24,16,' Provincia',1,0,'L');
    $pdf->Cell(26,16,'Total Facturado',1,0,'L');
    $pdf->Cell(15,16,'Id '.$remates[$k],1,0,'L');
    $pdf->SetY(28);
    $pdf->Cell(115);
    $pdf->Cell(23,16,'   Cargos',0,0,'L');
    $pdf->SetY(31);
    $pdf->Cell(115);
    $pdf->Cell(23,16,' financieros',0,0,'L');
    $valor_y = 45;

    // Limpio acunuladores
    $i = 0;
    $acum_tot_neto21     = 0;
    $acum_tot_neto105    = 0;
    $acum_tot_iva        = 0;
    $acum_totpublic      = 0;
    $acum_totservicios   = 0;
    $acum_total          = 0;
    $acum_totcomis       = 0;
    $lotes21             = 0;
    $lotes105            = 0;
    $tot_iva             = 0;

    while($row_cabecerafac = mysqli_fetch_array($cabecerafac)) {	
        $tcomp      = $row_cabecerafac["tcomp"];
        $serie      = $row_cabecerafac["serie"];
        $ncomp      = $row_cabecerafac["ncomp"];
        $iva21      = $row_cabecerafac["totiva21"];
        $iva105     = $row_cabecerafac["totiva105"];
        $codrem     = $row_cabecerafac["codrem"];
        if (($tcomp == 86 || $tcomp == 104) && $iva21 == 0.00 && $iva105 == 0.00)
            continue;
        
        if (($tcomp == 57 || $tcomp == 58 || $tcomp == 59 || $tcomp == 60) && $iva21 == 0 && $iva105 == 0)
            continue;
                
        if ($tcomp ==  57 ||  $tcomp ==  58 ||  $tcomp ==  61 ||  $tcomp ==  62 ||  $tcomp ==  93  ||  $tcomp ==  105 ) {
            $tc = "NC-";
            $signo = -1;
        }
        else {
            if ($tcomp == 59 ||  $tcomp == 60 ||  $tcomp ==  63 ||  $tcomp ==  64 ||  $tcomp ==  94){
                $tc = "ND-";
                $signo = 1;
            }
            else {
                $tc = "FC-";
                $signo = 1;
            }
        }
        //echo "TCOMP = ".$tcomp."   NCOMP = ".$ncomp."\n";
        if ($i <= 22) {
            if ($codrem != "" && $codrem > 0) {
                //Leo Direccion de exhibicion para saber la provincia
                $query_dir_exhib = sprintf("SELECT * FROM dir_remates WHERE codrem = %s ORDER BY codrem, secuencia", $codrem);
                $dir_exhib = mysqli_query($amercado, $query_dir_exhib) or die("ERROR LEYENDO DIR EXPO 160");
                if (mysqli_num_rows($dir_exhib) > 0) {
                    //Leo los lotes a ver si alguno est� asignado a la direccion de exhibicion
                    if ($tcomp == 51 || $tcomp == 53 || $tcomp == 89) {
                        // LEO DETFAC PARA VER LOS LOTES
                        $query_detfacprov = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp ";
                        $detfacprov = mysqli_query($amercado, $query_detfacprov) or die("ERROR LEYENDO DETFAC 166");
                        //echo "query_detfacprov = ".$query_detfacprov." - ";
                        while ($row_detfacprov = mysqli_fetch_array($detfacprov)) {
                            $secuencia = $row_detfacprov["codlote"];
                            $rematefac = $row_detfacprov["codrem"];
                            // LEO EL LOTE PARA VER SI TIENE DIR EXPO
                            $query_loteprov = "SELECT * FROM lotes WHERE codrem = $rematefac AND secuencia = $secuencia ";
                            $loteprov = mysqli_query($amercado, $query_loteprov) or die("ERROR LEYENDO LOTES 172");
                            $row_loteprov = mysqli_fetch_assoc($loteprov);
                            if ($row_loteprov["dir_secuencia"] == "") {
                                $row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
                                $prov_remate = $row_dir_exhib["codprov"];
                                break;
                            }
                            else {
                                //UFA, TENGO QUE LEER LA DIR DE EXPO DE ESTE LOTE
                                $secuexpo = $row_loteprov["dir_secuencia"];
                                $query_dirprov = "SELECT * FROM dir_remates WHERE codrem = $rematefac AND secuencia = $secuexpo ";
                                $dirprov = mysqli_query($amercado, $query_dirprov) or die("ERROR LEYENDO DIR EXPO 183");
                                $row_dirprov = mysqli_fetch_assoc($dirprov);
                                $prov_remate = $row_dirprov["codprov"];
                                //echo "paso 2 ";
                            }
                        }
                    }
                    else {
                        $row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
                        $prov_remate = $row_dir_exhib["codprov"];
                        //echo "paso 3 ";
                    }
                }
                else {	
                    //Leo el remate para conocer la provincia
                    $query_remates = sprintf("SELECT * FROM remates WHERE ncomp = %s ", $codrem);
                    $remates1 = mysqli_query($amercado, $query_remates) or die("ERROR LEYENDO REMATES 197");
                    $row_remate = mysqli_fetch_assoc($remates1);
                    $prov_remate = $row_remate["codprov"];
                    //echo "paso 4 ";
                }
            }
            else {
                if ($codrem != 0 && $codrem != "") {
                    $cli = $row_cabecerafac["cliente"];
                    // Leo el cliente
                    $query_enti = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cli);
                    $ent = mysqli_query($amercado, $query_enti) or die("ERROR LEYENDO ENTIDADES 207");
                    $row_enti = mysqli_fetch_assoc($ent);
                    $prov_remate   = $row_enti["codprov"];
                    //echo "paso 5 ";
                }
                else {
                    if ($serie == 29 || $serie == 30 || $serie == 37) {
                        $prov_remate = 1;
                        //echo "paso 6 ";
                    }
                    else {
                        $prov_remate = 2;
                        //echo "paso 7 ";
                    }
                    //echo "paso 8 ";
                }
                //echo "paso 9 ";

            }
            if ($row_cabecerafac["codprov"] != null) {
                    $prov_remate = $row_cabecerafac["codprov"];
            }
            if ($codrem == 3414 && ($ncomp == 12623 || $ncomp == 12905)) {
                $prov_remate = 6;
            }
            if ($prov_remate != 6)
                continue;
            //echo "TCOMP = ".$tcomp."   NCOMP = ".$ncomp."PROV = ".$prov_remate."\n";
            // Leo la descripci�n de la provincia
            $query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
            $provincias = mysqli_query($amercado, $query_provincias) or die ("ERROR LEYENDO PROVINCIAS");
            $row_provincias = mysqli_fetch_assoc($provincias);
            $desc_prov = substr($row_provincias["descripcion"],0,15);
            $tot_comision = 0.00;
            $tot_public   = 0.00;
            $tot_servicios= 0.00;
            $tot_neto21 = 0.00;
            $tot_neto105 = 0.00;
            $lotes21 = 0.00;
            $lotes105 = 0.00;
            $total = 0.00;
            $tot_iva21 = 0.00;
            $tot_iva105 = 0.00;
            $tot_iva  = 0.00;
            $fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
            switch ($tcomp) {
                case 51:
                case 89:
                case 111:
                    $cliente      = $row_cabecerafac["cliente"];
                    $lotes21      = $row_cabecerafac["totneto21"] ;
                    $lotes105     = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_public   = 0.00;
                    $tot_servicios= $row_cabecerafac["totimp"];
                    $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                    $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 52:
                case 92:
                case 112:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND (descrip like '%SUBAST%' OR descrip like '%SE�A%' OR concafac = 6)";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO TCOMP, NCOMP".$tcomp." - ".$ncomp." _ ");
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_public   = 0.00;
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];


                    }

                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        $tot_public   = $row_detfac2["neto"];
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND ( descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%' and descrip not like '%SUBAS%') and concafac != 6";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO TCOMP, NCOMP".$tcomp." - ".$ncomp." _ 2");
                    if (mysqli_num_rows($detfac3) == 1) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        //$tot_public   = $row_detfac1["neto"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }
                    else if (mysqli_num_rows($detfac3) > 1) {
                        $tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = 0.00;
                        $tot_iva105   = 0.00;
                        $tot_iva      = 0.00;
                        $tot_resol    = $desc_prov;
                        $total        = 0.00;
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        while ($row_detfac3 = mysqli_fetch_array($detfac3)) {
                            $cliente      = $row_cabecerafac["cliente"];

                            $tot_servicios += $row_detfac3["neto"];
                            $lotes21        = 0.00;
                            $lotes105       = 0.00;
                            $tot_iva21    = $row_cabecerafac["totiva21"] ;//(($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21);
                            $tot_iva105   = $row_cabecerafac["totiva105"];
                            $tot_iva      = $tot_iva21 + $tot_iva105;
                            $tot_resol    = $desc_prov;
                            $total        = $row_cabecerafac["totbruto"];
                            $nroorig      = $row_cabecerafac["nrodoc"];
                        }
                    }
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 53:
                    $cliente      = $row_cabecerafac["cliente"];
                    $lotes21      = $row_cabecerafac["totneto21"] ;
                    $lotes105     = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    //$tot_public   = 0.00;
                    $tot_servicios= $row_cabecerafac["totimp"];
                    $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                    $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 54:
                case 86:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_public   = 0.00;
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        $tot_public   = $row_detfac2["neto"];
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac3) == 1) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        //$tot_public   = $row_detfac1["neto"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }
                    else if (mysqli_num_rows($detfac3) > 1) {
                        $tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = 0.00;
                        $tot_iva105   = 0.00;
                        $tot_iva      = 0.00;
                        $tot_resol    = $desc_prov;
                        $total        = 0.00;
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        while ($row_detfac3 = mysqli_fetch_array($detfac3)) {
                            $cliente      = $row_cabecerafac["cliente"];

                            $tot_servicios += $row_detfac3["neto"];
                            $lotes21        = 0.00;
                            $lotes105       = 0.00;
                            $tot_iva21    = $row_cabecerafac["totiva21"] ;//(($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21);
                            $tot_iva105   = $row_cabecerafac["totiva105"];
                            $tot_iva      = $tot_iva21 + $tot_iva105;
                            $tot_resol    = $desc_prov;
                            $total        = $row_cabecerafac["totbruto"];
                            $nroorig      = $row_cabecerafac["nrodoc"];
                        }
                    }
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 55:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_public   = 0.00;
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        $tot_public   = $row_detfac2["neto"];
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac3) > 0) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        //$tot_public   = $row_detfac1["neto"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;	
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 56:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_public   = 0.00;
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        $tot_public   = $row_detfac2["neto"];
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac3) > 0) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        //$tot_public   = $row_detfac1["neto"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
               case 57:
               case 93:
               case 105:
                    if ($row_cabecerafac["tcomp"] == 105) {
                        //echo "ENCONTRE AL 105 ".$ncomp."  ";
                        $cliente      = $row_cabecerafac["cliente"];
                        $tot_public = $row_cabecerafac["totneto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_servicios = 0.00;
                        $tot_comision   = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }
                    if ($row_cabecerafac["tcomp"] == 93) {
                        //echo "ENCONTRE AL 105 ".$ncomp."  ";
                        $cliente      = $row_cabecerafac["cliente"];
                        $tot_servicios = $row_cabecerafac["totneto21"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_public = 0.00;
                        $tot_comision   = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }

                    if (($row_cabecerafac["ncomp"] == 1367  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1652  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1677  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1297  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1626  && $row_cabecerafac["tcomp"] == 57)|| ($row_cabecerafac["ncomp"] == 4  && $row_cabecerafac["tcomp"] == 93) || ($row_cabecerafac["ncomp"] == 6  && $row_cabecerafac["tcomp"] == 93)) {
                        // USAR ESTO PARA LOS CASOS ESPECIALES				
                          $cliente      = $row_cabecerafac["cliente"];
                          if (($row_cabecerafac["ncomp"] == 1626 || $row_cabecerafac["ncomp"] == 1677)  && $row_cabecerafac["tcomp"] == 57) {
                            $tot_servicios = $row_cabecerafac["totneto21"];
                            $tot_comision   = 0.00; //$row_cabecerafac["totneto21"];
                          }
                          else {
                            $tot_servicios = $row_cabecerafac["totimp"];
                            $tot_comision   = $row_cabecerafac["totneto21"];
                          }
                          $tot_public = 0.00; 
                          $lotes21      = 0.00;
                          $lotes105     = 0.00;
                          $tot_iva21    = $row_cabecerafac["totiva21"];
                          $tot_iva105   = $row_cabecerafac["totiva105"];
                          $tot_iva      = $tot_iva21 + $tot_iva105;
                          $tot_resol    = $desc_prov;
                          $total        = $row_cabecerafac["totbruto"];
                          $nroorig      = $row_cabecerafac["nrodoc"];
                          $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                          $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                          $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                          $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                          $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                          $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }
                        if (($row_cabecerafac["ncomp"] == 1563 || $row_cabecerafac["ncomp"] == 1374 ||$row_cabecerafac["ncomp"] == 1301 || $row_cabecerafac["ncomp"] == 1300 || $row_cabecerafac["ncomp"] == 1337 || $row_cabecerafac["ncomp"] == 1255 || $row_cabecerafac["ncomp"] == 1251 || $row_cabecerafac["ncomp"] == 1237 || $row_cabecerafac["ncomp"] == 1179  || $row_cabecerafac["ncomp"] == 1169  || $row_cabecerafac["ncomp"] == 1201)   && $row_cabecerafac["tcomp"] == 57) {
                        // USAR ESTO PARA LOS CASOS ESPECIALES				
                          $cliente      = $row_cabecerafac["cliente"];
                          if ($row_cabecerafac["ncomp"] == 1337) {
                              $tot_public = $row_cabecerafac["totneto21"]; 
                              $tot_comision = 0.00;
                          } else
                              if ($row_cabecerafac["ncomp"] == 1374) {
                              $tot_public = $row_cabecerafac["totneto"]; 
                              $tot_servicios = $row_cabecerafac["totimp"];
                              $tot_comision = $row_cabecerafac["totneto21"];
                          } else
                              if ($row_cabecerafac["ncomp"] == 1237) {
                                $tot_comision = $row_cabecerafac["totneto21"]; 
                                $tot_servicios = $row_cabecerafac["totimp"]; ;
                                }
                          else 
                            if ($row_cabecerafac["ncomp"] == 1563) {
                                $tot_public = $row_cabecerafac["totbruto"]; 
                                $tot_servicios = 0.00; //$row_cabecerafac["totimp"]; ;
                            }
                            else{
                                $tot_comision   = $row_cabecerafac["totneto21"];
                                $tot_public = 0.00; 
                            }
                          $lotes21      = 0.00;
                          $lotes105     = 0.00;
                          $tot_iva21    = $row_cabecerafac["totiva21"];
                          $tot_iva105   = $row_cabecerafac["totiva105"];
                          $tot_iva      = $tot_iva21 + $tot_iva105;
                          $tot_resol    = $desc_prov;
                          $total        = $row_cabecerafac["totbruto"];
                          $nroorig      = $row_cabecerafac["nrodoc"];
                          $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                          $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                          $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                          $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                          $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                          $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }
                    $cliente      = $row_cabecerafac["cliente"];
                    //Deber�a revisar detfac para ver si la fc es tipo 51 o 52 u 89 o 92
                    // DETFAC DE LA NOTA DE CREDITO
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%A000%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 574");
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $descri_det = $row_detfac1["descrip"];
                        if ($tcomp == 57 && $ncomp != 1163)
                           $prim_char = strpos($descri_det, "A0004");
                        else
                           $prim_char = strpos($descri_det, "A00005");
                        (int) $nrodoc_rel = substr($descri_det, $prim_char + 9, 5);
                        if ($ncomp == 1297)
                            $nrodoc_rel = 25;
                        if ($tcomp == 93 && $ncomp == 5)
                            $nrodoc_rel = 55;
                        if ($tcomp == 93 && $ncomp == 8)
                            $nrodoc_rel = 57;
                        if ($tcomp == 93 && $ncomp == 9)
                            $nrodoc_rel = 71;
                        //Leo el cabfac de la factura original
                        if (strpos($descri_det, "DEB")== FALSE && strpos($descri_det, "D�B")== FALSE && strpos($descri_det, "Nd")== FALSE) {
                            if ($tcomp == 93) {
                                $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (89, 92) AND ncomp = $nrodoc_rel";
                            }
                            else {
                                if ($ncomp == 1297)
                                    $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (89, 92) AND ncomp = $nrodoc_rel";
                                else
                                  $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (51,52,89, 92) AND ncomp = $nrodoc_rel";
                            }
                            $cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA 1".$nrodoc_rel);
                        }
                        else {
                            $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (59,94) AND ncomp = $nrodoc_rel";
                            $cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA 2".$nrodoc_rel);
                        }
                        // Y con estos datos leo el detfac correspondiente si es tipo 52:
                        if ($row_cabefac = mysqli_fetch_assoc($cabefac)) {
                            if ($row_cabefac["tcomp"] == 52 || $row_cabefac["tcomp"] == 92) {
                                $tcomp_cabefac = $row_cabefac["tcomp"];
                                $ncomp_cabefac = $row_cabefac["ncomp"];
                                //Debo leer los renglones de detfac para ver de que se trata
                                $query_detfacfc1 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%INMOB%' OR descrip like '%TASA%')";
                                $detfacfc1 = mysqli_query($amercado, $query_detfacfc1) or die("ERROR LEYENDO DETFAC 609");
                                if (mysqli_num_rows($detfacfc1) > 0) {
                                    $tot_servicios = 0.00;
                                    if($row_detfacfc1 = mysqli_fetch_array($detfacfc1)) {
                                        $tot_servicios += $row_detfacfc1["neto"]; 
                                        $cliente      = $row_cabecerafac["cliente"];
                                        $lotes21      = 0.00;
                                        $lotes105     = 0.00;
                                        $tot_iva21    = $row_cabecerafac["totiva21"];
                                        $tot_iva105   = $row_cabecerafac["totiva105"];
                                        $tot_iva      = $tot_iva21 + $tot_iva105;
                                        $tot_resol    = $desc_prov;
                                        $total        = $row_cabecerafac["totbruto"];
                                        $nroorig      = $row_cabecerafac["nrodoc"];
                                    }
                                }
                                $query_detfacfc2 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%PUBLIC%' OR descrip like '%RECUP%') ";
                                $detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die("ERROR LEYENDO DETFAC 626");
                                if (mysqli_num_rows($detfacfc2) > 0) {
                                    $row_detfacfc2 = mysqli_fetch_assoc($detfacfc2);
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $tot_public   = $row_detfacfc2["neto"];
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"];
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                                $query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%SUBAS%'";
                                $detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die("ERROR LEYENDO DETFAC 641");
                                if (mysqli_num_rows($detfacfc3) > 0) {
                                    $row_detfacfc3 = mysqli_fetch_assoc($detfacfc3);
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $tot_comision = $row_detfacfc3["neto"];
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"];
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                            }
                            else if ($row_cabefac["tcomp"] == 51 || $row_cabefac["tcomp"] == 89){
                                //if ($ncomp == 57)
                                 //   echo "LO ENCONTRE ";
                                $cliente      = $row_cabecerafac["cliente"];
                                $lotes21      = $row_cabecerafac["totneto21"] ;
                                $lotes105     = $row_cabecerafac["totneto105"];
                                $tot_comision = $row_cabecerafac["totcomis"];
                                $tot_servicios = $row_cabecerafac["totimp"];
                                $tot_iva21    = $row_cabecerafac["totiva21"];
                                $tot_iva105   = $row_cabecerafac["totiva105"];
                                $tot_iva      = $tot_iva21 + $tot_iva105;
                                $tot_resol    = $desc_prov;
                                $total        = $row_cabecerafac["totbruto"];
                                $nroorig      = $row_cabecerafac["nrodoc"];			
                            } else if ($row_cabefac["tcomp"] == 59) {
                                if ($row_cabecerafac["totiva105"] == 0.00 && $row_cabecerafac["totiva21"] == 0.00) {
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_public   = $row_cabecerafac["totneto21"];
                                    $tot_comision = 0.00;
                                    $tot_iva21    = 0.00;
                                    $tot_iva105   = 0.00;
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];	
                                }
                            }		
                            $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                            $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                            $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                            $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                            $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                            $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        }
                    }
                    else {
                        $cliente      = $row_cabecerafac["cliente"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_public   = $row_cabecerafac["totneto21"];
                        $tot_comision = 0.00;
                        $tot_iva21    = 0.00;
                        $tot_iva105   = 0.00;
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];	
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    }
                    break;
                case 58:

                    $tot_public   = 0.00;
                    if (($row_cabecerafac["ncomp"] == 169 || $row_cabecerafac["ncomp"] == 164 || $row_cabecerafac["ncomp"] == 170 || $row_cabecerafac["ncomp"] == 173 || $row_cabecerafac["ncomp"] == 174 || $row_cabecerafac["ncomp"] == 175 || $row_cabecerafac["ncomp"] == 205 || $row_cabecerafac["ncomp"] == 206 || $row_cabecerafac["ncomp"] == 207 || $row_cabecerafac["ncomp"] == 208 || $row_cabecerafac["ncomp"] == 209 || $row_cabecerafac["ncomp"] == 223 || $row_cabecerafac["ncomp"] == 229 || $row_cabecerafac["ncomp"] == 230 || $row_cabecerafac["ncomp"] == 232 || $row_cabecerafac["ncomp"] == 224) && $row_cabecerafac["tcomp"] == 58) {
                        // USAR ESTO PARA LOS CASOS ESPECIALES				
                        $cliente      = $row_cabecerafac["cliente"];
                        if ($row_cabecerafac["ncomp"] == 232) {
                            $tot_servicios = 0.00 ;
                            $tot_public = $row_cabecerafac["totneto21"];
                        }
                        else {
                            $tot_servicios =  $row_cabecerafac["totneto21"];
                            $tot_public = 0.00; 
                        }

                        $tot_comision   = $row_cabecerafac["totimp"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }
                    $cliente      = $row_cabecerafac["cliente"];
                    //Deber�a revisar detfac para ver si la fc es tipo 53 o 54
                    // DETFAC DE LA NOTA DE CREDITO
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%B0004%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 738");
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $descri_det = $row_detfac1["descrip"];
                        $prim_char = strpos($descri_det, "B0004");
                        $nrodoc_rel = substr($descri_det, $prim_char + 10, 4);
                        if (strpos($descri_det, "DEB")=== FALSE && strpos($descri_det, "D�B")=== FALSE) {
                            $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (53,54) AND ncomp = $nrodoc_rel";
                            $cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA 3".$nrodoc_rel);
                        }
                        else {
                            $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (60) AND ncomp = $nrodoc_rel";
                            $cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA 4".$nrodoc_rel);
                        }
                        // Y con estos datos leo el detfac correspondiente si es tipo 54:
                        if ($row_cabefac = mysqli_fetch_assoc($cabefac)) {
                            if ($row_cabefac["tcomp"] == 54) {
                                $tcomp_cabefac = $row_cabefac["tcomp"];
                                $ncomp_cabefac = $row_cabefac["ncomp"];
                                //Debo leer los renglones de detfac para ver de que se trata
                                $query_detfacfc1 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%INMOB%' OR descrip like '%GASTOS%'  OR descrip like '%TASA%')";
                                $detfacfc1 = mysqli_query($amercado, $query_detfacfc1) or die("ERROR LEYENDO DETFAC 759");
                                if (mysqli_num_rows($detfacfc1) > 0) {
                                    $row_detfacfc1 = mysqli_fetch_assoc($detfacfc1);
                                    $tot_servicios = $row_detfacfc1["neto"];
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"];
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                                $query_detfacfc2 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%PUBLIC%' OR descrip like '%SE�A%' ";
                                if ($detfacfc2 = mysqli_query($amercado, $query_detfacfc2)) {// or die("ERROR LEYENDO DETFAC 774");
                                if (mysqli_num_rows($detfacfc2) > 0) {
                                    $row_detfacfc2 = mysqli_fetch_assoc($detfacfc2);
                                    $cliente      = $row_cabecerafac["cliente"];
                                    if ($ncomp_cabefac != 1372) {
                                        $tot_public   = $row_detfacfc2["neto"];
                                    }
                                    else 
                                        $tot_public   = 1.00;
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"]; 
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                                }
                                $query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%SERVICIO%'";
                                $detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die("ERROR LEYENDO DETFAC 789");
                                if (mysqli_num_rows($detfacfc3) > 0) {
                                    $row_detfacfc3 = mysqli_fetch_assoc($detfacfc3);
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $tot_comision = $row_detfacfc3["neto"];
                                    $tot_public   = 0.00;
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"]; 
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                            }
                            else 
                                if ($row_cabefac["tcomp"] == 60) {
                                    if ($row_cabecerafac["totiva105"] == 0.00 && $row_cabecerafac["totiva21"] == 0.00) {
                                        $cliente      = $row_cabecerafac["cliente"];
                                        $lotes21      = 0.00;
                                        $lotes105     = 0.00;
                                        $tot_public   = $row_cabecerafac["totneto21"];
                                        $tot_comision = 0.00;
                                        $tot_iva21    = 0.00;
                                        $tot_iva105   = 0.00;
                                        $tot_iva      = $tot_iva21 + $tot_iva105;
                                        $tot_resol    = $desc_prov;
                                        $total        = $row_cabecerafac["totbruto"];
                                        $nroorig      = $row_cabecerafac["nrodoc"];	
                                    }
                                }	
                                else 
                                    if ($row_cabefac["tcomp"] == 53) {
                                        $cliente      = $row_cabecerafac["cliente"];
                                        $tot_servicios =  $row_cabecerafac["totimp"];
                                        $tot_comision   = $row_cabecerafac["totcomis"];
                                        $tot_public = 0.00; 
                                        $lotes21      = $row_cabecerafac["totneto21"];;
                                        $lotes105     = $row_cabecerafac["totneto105"];;
                                        $tot_iva21    = $row_cabecerafac["totiva21"];
                                        $tot_iva105   = $row_cabecerafac["totiva105"];
                                        $tot_iva      = $tot_iva21 + $tot_iva105;
                                        $tot_resol    = $desc_prov;
                                        $total        = $row_cabecerafac["totbruto"];
                                        $nroorig      = $row_cabecerafac["nrodoc"];
                                    }
                            $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                            $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                            $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                            $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                            $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                            $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        }
                    }
                    else {
                        $cliente      = $row_cabecerafac["cliente"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_public   = $row_cabecerafac["totneto21"];
                        $tot_comision = 0.00;
                        $tot_iva21    = 0.00;
                        $tot_iva105   = 0.00;
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];	
                        $acum_comis[$prov_remate]    = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate]   = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate]     = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate]  = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate]     = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    }
                    break;
                case 59:
                case 94:
                case 60:
                    if (($row_cabecerafac["totiva21"] != 0 && $row_cabecerafac["totiva105"] != 0) && ($row_cabecerafac["ncomp"] != 177 && $row_cabecerafac["ncomp"] != 378 && $row_cabecerafac["ncomp"] != 379 && $row_cabecerafac["ncomp"] != 380 && $row_cabecerafac["ncomp"] != 381 && $row_cabecerafac["ncomp"] != 382)) {
                        $cliente      = $row_cabecerafac["cliente"];
                        $lotes21      = $row_cabecerafac["totneto21"] ;
                        $lotes105     = $row_cabecerafac["totneto105"];
                        $tot_comision = $row_cabecerafac["totcomis"];
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_servicios =  $row_cabecerafac["totimp"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios ;
                    }
                    else {
                        $cliente      = $row_cabecerafac["cliente"];
                        //$lotes21      = $row_cabecerafac["totneto21"];
                        $lotes105     = $row_cabecerafac["totneto105"];
                        if ($row_cabecerafac["ncomp"] == 74 && $row_cabecerafac["tcomp"] == 60) {
                            $tot_public   = $row_cabecerafac["totneto21"];
                            $lotes21      = 0.00;
                        }
                        else {
                            $tot_public   = 0.00;
                            $lotes21      = $row_cabecerafac["totneto21"];
                        }
                        if ($row_cabecerafac["ncomp"] == 386 && $row_cabecerafac["tcomp"] == 59) {
                            $tot_servicios   = $row_cabecerafac["totneto21"];
                            $lotes21      = 0.00;
                        }
                        else
                            $tot_servicios =  $row_cabecerafac["totimp"];
                        $tot_comision = $row_cabecerafac["totcomis"];
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];	
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public ;
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios ;
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21 ;
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105 ;
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    }
                    break;
                case 61:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto105  = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_iva21    = $row_cabecerafac["totiva21"];
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    break;
                case 62:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto105  = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_iva21    = $row_cabecerafac["totiva21"];
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    break;
                case 63:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto21   = $row_cabecerafac["totneto21"] ;
                    $tot_neto105  = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_iva21    = $row_cabecerafac["totiva21"] ;
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    break;
                case 64:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto21   = $row_cabecerafac["totneto21"] ;
                    $tot_neto105  = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_iva21    = $row_cabecerafac["totiva21"] ;
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    break;
                case 86:
                    break;
                case 104:
                    break;
            }

            $estado = "P";
            // Acumulo subtotales
            if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93  || $tcomp == 105) {
                // resto Notas de Cr�dito
                $acum_tot_neto21  = $acum_tot_neto21  - $lotes21;
                $acum_tot_neto105 = $acum_tot_neto105 - $lotes105;
                $acum_tot_iva     = $acum_tot_iva     - $tot_iva;
                $acum_totservicios  = $acum_totservicios  - $tot_servicios;
                $acum_total       = $acum_total       - $total;
                $acum_totcomis    = $acum_totcomis    - $tot_comision;
                $acum_totpublic   = $acum_totpublic    - $tot_public;
            }
            else {
                // Sumo Facturas y Notas de D�bito
                $acum_tot_neto21  = $acum_tot_neto21  + $lotes21;
                $acum_tot_neto105 = $acum_tot_neto105 + $lotes105;
                $acum_tot_iva     = $acum_tot_iva     + $tot_iva;
                $acum_total       = $acum_total       + $total;
                $acum_totcomis    = $acum_totcomis    + $tot_comision;
                $acum_totservicios = $acum_totservicios + $tot_servicios;
                $acum_totpublic   = $acum_totpublic + $tot_public;

            }
            $lotes21       *= $signo;
            $lotes105      *= $signo;
            $tot_iva       *= $signo;
            $tot_comision  *= $signo;
            $tot_public    *= $signo;
            $tot_servicios *= $signo;
            $total         *= $signo;

            $lotes21       = number_format($lotes21, 2, ',','.');
            $lotes105      = number_format($lotes105, 2, ',','.');
            $tot_iva       = number_format($tot_iva, 2, ',','.');
            $tot_public    = number_format($tot_public, 2, ',','.');
            $tot_comision  = number_format($tot_comision, 2, ',','.');
            $tot_servicios = number_format($tot_servicios, 2, ',','.');
            $total         = number_format($total, 2, ',','.');

            // Leo el cliente
            $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
            $enti = mysqli_query($amercado, $query_entidades) or die("ENTIDADES".$cliente."TCOMP = ".$row_cabecerafac["tcomp"]."NCOMP = "-$row_cabecerafac["ncomp"]." - ");
            $row_entidades = mysqli_fetch_assoc($enti);
            $nom_cliente   = substr($row_entidades["razsoc"], 0, 17);
            $nro_cliente   = $row_entidades["numero"];
            $cuit_cliente  = $row_entidades["cuit"];

            // Imprimo los renglones
            $pdf->SetY($valor_y);
            $pdf->Cell(1);
            $pdf->Cell(19,6,$fecha,0,0,'L');
            $pdf->SetX(19);
            $pdf->Cell(6,6,$tc." ",0,0,'L');
            $pdf->Cell(28,6,$nroorig,0,0,'L');
            $pdf->Cell(40,6,$nom_cliente,0,0,'L');
            $pdf->Cell(22,6,$cuit_cliente,0,0,'L');
            $pdf->Cell(23,6,$tot_public,0,0,'R');
            $pdf->Cell(23,6,$tot_servicios,0,0,'R');
            $pdf->Cell(26,6,$tot_comision,0,0,'R');
            $pdf->Cell(26,6,$lotes21,0,0,'R');
            $pdf->Cell(26,6,$lotes105,0,0,'R');
            $pdf->Cell(24,6,$tot_iva,0,0,'R');
            $pdf->Cell(24,6,$tot_resol,0,0,'L');
            $pdf->Cell(26,6,$total,0,0,'R');
            $pdf->Cell(15,6,$codrem,0,0,'R');

            $i = $i + 1;
            $valor_y = $valor_y + 6;
            
        }
        else {
            // Imprimo subtotales de la hoja, uso otras variables porque el number_format
            // me jode los acumulados
            $f_acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
            $f_acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
            $f_acum_tot_iva     = number_format($acum_tot_iva, 2, ',','.');
            $f_acum_totpublic   = number_format($acum_totpublic, 2, ',','.');
            $f_acum_total       = number_format($acum_total, 2, ',','.');
            $f_acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
            $f_acum_totservicios = number_format($acum_totservicios, 2, ',','.');

            // ACUMULADOS PARCIALES DE PIE DE PAGINA
            $pdf->SetY($valor_y);
            $pdf->Cell(112);
            $pdf->Cell(26,6,$f_acum_totpublic,0,0,'R');
            $pdf->Cell(26,6,$f_acum_totservicios,0,0,'R');
            $pdf->Cell(26,6,$f_acum_totcomis,0,0,'R');
            $pdf->Cell(26,6,$f_acum_tot_neto21,0,0,'R');
            $pdf->Cell(24,6,$f_acum_tot_neto105,0,0,'R');
            $pdf->Cell(24,6,$f_acum_tot_iva,0,0,'R');
            $pdf->Cell(24,6,"------------------------",0,0,'L');
            $pdf->Cell(26,6,$f_acum_total,0,0,'R');

            // Voy a otra hoja e imprimo los titulos 
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
            $pdf->Cell(110);
            $pdf->Cell(60,10,' Ventas por Remate Consolidado (C�rdoba) - Usuario cobranzas: '.$nombre_usu,0,0,'L');
            $pdf->SetFont('Arial','B',9);
            $pdf->SetY(25);
            $pdf->Cell(3);
            $pdf->Cell(20,16,'    Fecha',1,0,'L');
            $pdf->Cell(30,16,' Nro.Factura',1,0,'L');
            $pdf->Cell(40,16,'       Raz�n Social',1,0,'L');
            $pdf->Cell(22,16,'     CUIT',1,0,'L');
            $pdf->Cell(23,16,' Publicidad y ',1,0,'L');
            $pdf->Cell(26,16,'    Servicios ',1,0,'L');
            $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
            $pdf->Cell(26,16,' Lotes al 21 % ',1,0,'L');
            $pdf->Cell(24,16,' Lotes al 10,5 %',1,0,'L');
            $pdf->Cell(24,16,' IVA Debito',1,0,'L');
            $pdf->Cell(24,16,' Provincia',1,0,'L');
            $pdf->Cell(26,16,'Total Facturado',1,0,'L');
            $pdf->Cell(15,16,'Id '.$remates[$k],1,0,'L');
            $pdf->SetY(28);
            $pdf->Cell(115);
            $pdf->Cell(23,16,'   Cargos',0,0,'L');
            $pdf->SetY(31);
            $pdf->Cell(115);
            $pdf->Cell(23,16,' financieros',0,0,'L');
            $valor_y = 45;
            // reinicio los contadores
            $i = 0;
            // IMPRIMO EL REGISTRO QUE TENGO LEIDO PORQUE SINO LO PIERDO
            if ($codrem != "" && $codrem > 0) {
                //Leo Direccion de exhibicion para saber la provincia
                $query_dir_exhib = sprintf("SELECT * FROM dir_remates WHERE codrem = %s ORDER BY codrem, secuencia", $codrem);
                $dir_exhib = mysqli_query($amercado, $query_dir_exhib) or die("ERROR LEYENDO DIR EXPO 160");
                if (mysqli_num_rows($dir_exhib) > 0) {
                    //Leo los lotes a ver si alguno est� asignado a la direccion de exhibicion
                    if ($tcomp == 51 || $tcomp == 53 || $tcomp == 89) {
                        // LEO DETFAC PARA VER LOS LOTES
                        $query_detfacprov = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp ";
                        $detfacprov = mysqli_query($amercado, $query_detfacprov) or die("ERROR LEYENDO DETFAC 166");
                        while ($row_detfacprov = mysqli_fetch_array($detfacprov)) {
                            $secuencia = $row_detfacprov["codlote"];
                            $rematefac = $row_detfacprov["codrem"];
                            // LEO EL LOTE PARA VER SI TIENE DIR EXPO
                            $query_loteprov = "SELECT * FROM lotes WHERE codrem = $rematefac AND secuencia = $secuencia ";
                            $loteprov = mysqli_query($amercado, $query_loteprov) or die("ERROR LEYENDO LOTES 172");
                            $row_loteprov = mysqli_fetch_assoc($loteprov);
                            if ($row_loteprov["dir_secuencia"] == "") {
                                $row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
                                $prov_remate = $row_dir_exhib["codprov"];
                                break;
                            }
                            else {
                                //UFA, TENGO QUE LEER LA DIR DE EXPO DE ESTE LOTE
                                $secuexpo = $row_loteprov["dir_secuencia"];
                                $query_dirprov = "SELECT * FROM dir_remates WHERE codrem = $rematefac AND secuencia = $secuexpo ";
                                $dirprov = mysqli_query($amercado, $query_dirprov) or die("ERROR LEYENDO DIR EXPO 183");
                                $row_dirprov = mysqli_fetch_assoc($dirprov);
                                $prov_remate = $row_dirprov["codprov"];
                               // echo "paso 2";
                            }
                        }
                    }
                    else {
                        $row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
                        $prov_remate = $row_dir_exhib["codprov"];
                    }
                }
                else {	
                    //Leo el remate para conocer la provincia
                    $query_remates = sprintf("SELECT * FROM remates WHERE ncomp = %s ", $codrem);
                    $remates1 = mysqli_query($amercado, $query_remates) or die("ERROR LEYENDO REMATES 197");
                    $row_remate = mysqli_fetch_assoc($remates1);
                    $prov_remate = $row_remate["codprov"];
                }
            }
            else {
                if ($codrem != 0 && $codrem != "") {
                    $cli = $row_cabecerafac["cliente"];
                    // Leo el cliente
                    $query_enti = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cli);
                    $ent = mysqli_query($amercado, $query_enti) or die("ERROR LEYENDO ENTIDADES 207");
                    $row_enti = mysqli_fetch_assoc($ent);
                    $prov_remate   = $row_enti["codprov"];
                }
                else {
                    if ($serie == 29 || $serie == 30 || $serie == 37) {
                        $prov_remate = 1;
                    }
                    else {
                        $prov_remate = 2;
                    }
                }

            }
            if ($row_cabecerafac["codprov"] != null) {
                    $prov_remate = $row_cabecerafac["codprov"];

            }
            if ($codrem == 3414 && ($ncomp == 12623 || $ncomp == 12905)) {
                $prov_remate = 6;
            }
            if ($prov_remate != 6)
                continue;
            //echo "TCOMP 2= ".$tcomp."   NCOMP2 = ".$ncomp."PROV2 = ".$prov_remate."\n";
            // Leo la descripci�n de la provincia
            $query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
            $provincias = mysqli_query($amercado, $query_provincias) or die (mysqli_error($amercado));
            $row_provincias = mysqli_fetch_assoc($provincias);
            $desc_prov = substr($row_provincias["descripcion"],0,15);
            $tot_comision = 0.00;
            $tot_public   = 0.00;
            $tot_servicios= 0.00;
            $lotes21 = 0.00;
            $lotes105 = 0.00;
            $tot_iva21 = 0.00;
            $tot_iva105 = 0.00;
            $tot_iva  = 0.00;
            $fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
            switch ($tcomp) {
                case 51:
                case 89:
                case 111:
                    $cliente      = $row_cabecerafac["cliente"];
                    $lotes21      = $row_cabecerafac["totneto21"] ;
                    $lotes105     = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_public   = 0.00;
                    $tot_servicios= $row_cabecerafac["totimp"];
                    $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                    $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 52:
                case 92:
                case 112:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND (descrip like '%SUBAST%' OR descrip like '%SE�A%' OR concafac = 6)";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO TCOMP, NCOMP".$tcomp." - ".$ncomp." _ ");
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_public   = 0.00;
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];


                    }

                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        $tot_public   = $row_detfac2["neto"];
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND ( descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%' and descrip not like '%SUBAS%') and concafac != 6";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO TCOMP, NCOMP".$tcomp." - ".$ncomp." _ 2");
                    if (mysqli_num_rows($detfac3) == 1) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        //$tot_public   = $row_detfac1["neto"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }
                    else if (mysqli_num_rows($detfac3) > 1) {
                        $tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = 0.00;
                        $tot_iva105   = 0.00;
                        $tot_iva      = 0.00;
                        $tot_resol    = $desc_prov;
                        $total        = 0.00;
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        while ($row_detfac3 = mysqli_fetch_array($detfac3)) {
                            $cliente      = $row_cabecerafac["cliente"];

                            $tot_servicios += $row_detfac3["neto"];
                            $lotes21        = 0.00;
                            $lotes105       = 0.00;
                            $tot_iva21    = $row_cabecerafac["totiva21"] ;//(($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21);
                            $tot_iva105   = $row_cabecerafac["totiva105"];
                            $tot_iva      = $tot_iva21 + $tot_iva105;
                            $tot_resol    = $desc_prov;
                            $total        = $row_cabecerafac["totbruto"];
                            $nroorig      = $row_cabecerafac["nrodoc"];
                        }
                    }
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 53:
                    $cliente      = $row_cabecerafac["cliente"];
                    $lotes21      = $row_cabecerafac["totneto21"] ;
                    $lotes105     = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    //$tot_public   = 0.00;
                    $tot_servicios= $row_cabecerafac["totimp"];
                    $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                    $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 54:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_public   = 0.00;
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        $tot_public   = $row_detfac2["neto"];
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac3) == 1) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        //$tot_public   = $row_detfac1["neto"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }
                    else if (mysqli_num_rows($detfac3) > 1) {
                        $tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = 0.00;
                        $tot_iva105   = 0.00;
                        $tot_iva      = 0.00;
                        $tot_resol    = $desc_prov;
                        $total        = 0.00;
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        while ($row_detfac3 = mysqli_fetch_array($detfac3)) {
                            $cliente      = $row_cabecerafac["cliente"];

                            $tot_servicios += $row_detfac3["neto"];
                            $lotes21        = 0.00;
                            $lotes105       = 0.00;
                            $tot_iva21    = $row_cabecerafac["totiva21"] ;//(($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21);
                            $tot_iva105   = $row_cabecerafac["totiva105"];
                            $tot_iva      = $tot_iva21 + $tot_iva105;
                            $tot_resol    = $desc_prov;
                            $total        = $row_cabecerafac["totbruto"];
                            $nroorig      = $row_cabecerafac["nrodoc"];
                        }
                    }
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 55:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_public   = 0.00;
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        $tot_public   = $row_detfac2["neto"];
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac3) > 0) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        //$tot_public   = $row_detfac1["neto"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;	
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
                case 56:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_public   = 0.00;
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        $tot_public   = $row_detfac2["neto"];
                        //$tot_servicios= 0.00;
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }

                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
                    if (mysqli_num_rows($detfac3) > 0) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        //$tot_comision = 0.00;
                        //$tot_public   = $row_detfac1["neto"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"] ;//($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];

                    }
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    break;
               case 57:
               case 93:
               case 105:
                    if ($row_cabecerafac["tcomp"] == 105) {
                        //echo "ENCONTRE AL 105 ".$ncomp."  ";
                        $cliente      = $row_cabecerafac["cliente"];
                        $tot_public = $row_cabecerafac["totneto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_servicios = 0.00;
                        $tot_comision   = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }
                    if ($row_cabecerafac["tcomp"] == 93) {
                        //echo "ENCONTRE AL 105 ".$ncomp."  ";
                        $cliente      = $row_cabecerafac["cliente"];
                        $tot_servicios = $row_cabecerafac["totneto21"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_public = 0.00;
                        $tot_comision   = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }

                    if (($row_cabecerafac["ncomp"] == 1367  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1652  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1677  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1297  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1626  && $row_cabecerafac["tcomp"] == 57)|| ($row_cabecerafac["ncomp"] == 4  && $row_cabecerafac["tcomp"] == 93) || ($row_cabecerafac["ncomp"] == 6  && $row_cabecerafac["tcomp"] == 93)) {
                        // USAR ESTO PARA LOS CASOS ESPECIALES				
                          $cliente      = $row_cabecerafac["cliente"];
                          if (($row_cabecerafac["ncomp"] == 1626 || $row_cabecerafac["ncomp"] == 1677)  && $row_cabecerafac["tcomp"] == 57) {
                            $tot_servicios = $row_cabecerafac["totneto21"];
                            $tot_comision   = 0.00; //$row_cabecerafac["totneto21"];
                          }
                          else {
                            $tot_servicios = $row_cabecerafac["totimp"];
                            $tot_comision   = $row_cabecerafac["totneto21"];
                          }
                          $tot_public = 0.00; 
                          $lotes21      = 0.00;
                          $lotes105     = 0.00;
                          $tot_iva21    = $row_cabecerafac["totiva21"];
                          $tot_iva105   = $row_cabecerafac["totiva105"];
                          $tot_iva      = $tot_iva21 + $tot_iva105;
                          $tot_resol    = $desc_prov;
                          $total        = $row_cabecerafac["totbruto"];
                          $nroorig      = $row_cabecerafac["nrodoc"];
                          $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                          $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                          $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                          $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                          $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                          $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }
                        if (($row_cabecerafac["ncomp"] == 1563 || $row_cabecerafac["ncomp"] == 1374 ||$row_cabecerafac["ncomp"] == 1301 || $row_cabecerafac["ncomp"] == 1300 || $row_cabecerafac["ncomp"] == 1337 || $row_cabecerafac["ncomp"] == 1255 || $row_cabecerafac["ncomp"] == 1251 || $row_cabecerafac["ncomp"] == 1237 || $row_cabecerafac["ncomp"] == 1179  || $row_cabecerafac["ncomp"] == 1169  || $row_cabecerafac["ncomp"] == 1201)   && $row_cabecerafac["tcomp"] == 57) {
                        // USAR ESTO PARA LOS CASOS ESPECIALES				
                          $cliente      = $row_cabecerafac["cliente"];
                          if ($row_cabecerafac["ncomp"] == 1337) {
                              $tot_public = $row_cabecerafac["totneto21"]; 
                              $tot_comision = 0.00;
                          } else
                              if ($row_cabecerafac["ncomp"] == 1374) {
                              $tot_public = $row_cabecerafac["totneto"]; 
                              $tot_servicios = $row_cabecerafac["totimp"];
                              $tot_comision = $row_cabecerafac["totneto21"];
                          } else
                              if ($row_cabecerafac["ncomp"] == 1237) {
                                $tot_comision = $row_cabecerafac["totneto21"]; 
                                $tot_servicios = $row_cabecerafac["totimp"]; ;
                                }
                          else 
                            if ($row_cabecerafac["ncomp"] == 1563) {
                                $tot_public = $row_cabecerafac["totbruto"]; 
                                $tot_servicios = 0.00; //$row_cabecerafac["totimp"]; ;
                            }
                            else{
                                $tot_comision   = $row_cabecerafac["totneto21"];
                                $tot_public = 0.00; 
                            }
                          $lotes21      = 0.00;
                          $lotes105     = 0.00;
                          $tot_iva21    = $row_cabecerafac["totiva21"];
                          $tot_iva105   = $row_cabecerafac["totiva105"];
                          $tot_iva      = $tot_iva21 + $tot_iva105;
                          $tot_resol    = $desc_prov;
                          $total        = $row_cabecerafac["totbruto"];
                          $nroorig      = $row_cabecerafac["nrodoc"];
                          $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                          $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                          $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                          $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                          $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                          $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }
                    $cliente      = $row_cabecerafac["cliente"];
                    //Deber�a revisar detfac para ver si la fc es tipo 51 o 52 u 89 o 92
                    // DETFAC DE LA NOTA DE CREDITO
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%A000%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 574");
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $descri_det = $row_detfac1["descrip"];
                        if ($tcomp == 57 && $ncomp != 1163)
                           $prim_char = strpos($descri_det, "A0004");
                        else
                           $prim_char = strpos($descri_det, "A00005");
                        (int) $nrodoc_rel = substr($descri_det, $prim_char + 9, 5);
                        if ($ncomp == 1297)
                            $nrodoc_rel = 25;
                        if ($tcomp == 93 && $ncomp == 5)
                            $nrodoc_rel = 55;
                        if ($tcomp == 93 && $ncomp == 8)
                            $nrodoc_rel = 57;
                        if ($tcomp == 93 && $ncomp == 9)
                            $nrodoc_rel = 71;
                        //Leo el cabfac de la factura original
                        if (strpos($descri_det, "DEB")== FALSE && strpos($descri_det, "D�B")== FALSE && strpos($descri_det, "Nd")== FALSE) {
                            if ($tcomp == 93) {
                                $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (89, 92) AND ncomp = $nrodoc_rel";
                            }
                            else {
                                if ($ncomp == 1297)
                                    $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (89, 92) AND ncomp = $nrodoc_rel";
                                else
                                  $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (51,52,89, 92) AND ncomp = $nrodoc_rel";
                            }
                            $cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA 1".$nrodoc_rel);
                        }
                        else {
                            $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (59,94) AND ncomp = $nrodoc_rel";
                            $cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA 2".$nrodoc_rel);
                        }
                        // Y con estos datos leo el detfac correspondiente si es tipo 52:
                        if ($row_cabefac = mysqli_fetch_assoc($cabefac)) {
                            if ($row_cabefac["tcomp"] == 52 || $row_cabefac["tcomp"] == 92) {
                                $tcomp_cabefac = $row_cabefac["tcomp"];
                                $ncomp_cabefac = $row_cabefac["ncomp"];
                                //Debo leer los renglones de detfac para ver de que se trata
                                $query_detfacfc1 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%INMOB%' OR descrip like '%TASA%')";
                                $detfacfc1 = mysqli_query($amercado, $query_detfacfc1) or die("ERROR LEYENDO DETFAC 609");
                                if (mysqli_num_rows($detfacfc1) > 0) {
                                    $tot_servicios = 0.00;
                                    if($row_detfacfc1 = mysqli_fetch_array($detfacfc1)) {
                                        $tot_servicios += $row_detfacfc1["neto"]; 
                                        $cliente      = $row_cabecerafac["cliente"];
                                        $lotes21      = 0.00;
                                        $lotes105     = 0.00;
                                        $tot_iva21    = $row_cabecerafac["totiva21"];
                                        $tot_iva105   = $row_cabecerafac["totiva105"];
                                        $tot_iva      = $tot_iva21 + $tot_iva105;
                                        $tot_resol    = $desc_prov;
                                        $total        = $row_cabecerafac["totbruto"];
                                        $nroorig      = $row_cabecerafac["nrodoc"];
                                    }
                                }
                                $query_detfacfc2 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%PUBLIC%' OR descrip like '%RECUP%') ";
                                $detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die("ERROR LEYENDO DETFAC 626");
                                if (mysqli_num_rows($detfacfc2) > 0) {
                                    $row_detfacfc2 = mysqli_fetch_assoc($detfacfc2);
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $tot_public   = $row_detfacfc2["neto"];
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"];
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                                $query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%SUBAS%'";
                                $detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die("ERROR LEYENDO DETFAC 641");
                                if (mysqli_num_rows($detfacfc3) > 0) {
                                    $row_detfacfc3 = mysqli_fetch_assoc($detfacfc3);
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $tot_comision = $row_detfacfc3["neto"];
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"];
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                            }
                            else if ($row_cabefac["tcomp"] == 51 || $row_cabefac["tcomp"] == 89){
                                //if ($ncomp == 57)
                                 //   echo "LO ENCONTRE ";
                                $cliente      = $row_cabecerafac["cliente"];
                                $lotes21      = $row_cabecerafac["totneto21"] ;
                                $lotes105     = $row_cabecerafac["totneto105"];
                                $tot_comision = $row_cabecerafac["totcomis"];
                                $tot_servicios = $row_cabecerafac["totimp"];
                                $tot_iva21    = $row_cabecerafac["totiva21"];
                                $tot_iva105   = $row_cabecerafac["totiva105"];
                                $tot_iva      = $tot_iva21 + $tot_iva105;
                                $tot_resol    = $desc_prov;
                                $total        = $row_cabecerafac["totbruto"];
                                $nroorig      = $row_cabecerafac["nrodoc"];			
                            } else if ($row_cabefac["tcomp"] == 59) {
                                if ($row_cabecerafac["totiva105"] == 0.00 && $row_cabecerafac["totiva21"] == 0.00) {
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_public   = $row_cabecerafac["totneto21"];
                                    $tot_comision = 0.00;
                                    $tot_iva21    = 0.00;
                                    $tot_iva105   = 0.00;
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];	
                                }
                            }		
                            $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                            $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                            $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                            $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                            $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                            $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        }
                    }
                    else {
                        $cliente      = $row_cabecerafac["cliente"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_public   = $row_cabecerafac["totneto21"];
                        $tot_comision = 0.00;
                        $tot_iva21    = 0.00;
                        $tot_iva105   = 0.00;
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];	
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    }
                    break;
                case 58:

                    $tot_public   = 0.00;
                    if (($row_cabecerafac["ncomp"] == 169 || $row_cabecerafac["ncomp"] == 164 || $row_cabecerafac["ncomp"] == 170 || $row_cabecerafac["ncomp"] == 173 || $row_cabecerafac["ncomp"] == 174 || $row_cabecerafac["ncomp"] == 175 || $row_cabecerafac["ncomp"] == 205 || $row_cabecerafac["ncomp"] == 206 || $row_cabecerafac["ncomp"] == 207 || $row_cabecerafac["ncomp"] == 208 || $row_cabecerafac["ncomp"] == 209 || $row_cabecerafac["ncomp"] == 223 || $row_cabecerafac["ncomp"] == 229 || $row_cabecerafac["ncomp"] == 230 || $row_cabecerafac["ncomp"] == 232 || $row_cabecerafac["ncomp"] == 224) && $row_cabecerafac["tcomp"] == 58) {
                        // USAR ESTO PARA LOS CASOS ESPECIALES				
                        $cliente      = $row_cabecerafac["cliente"];
                        if ($row_cabecerafac["ncomp"] == 232) {
                            $tot_servicios = 0.00 ;
                            $tot_public = $row_cabecerafac["totneto21"];
                        }
                        else {
                            $tot_servicios =  $row_cabecerafac["totneto21"];
                            $tot_public = 0.00; 
                        }

                        $tot_comision   = $row_cabecerafac["totimp"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        break;
                    }
                    $cliente      = $row_cabecerafac["cliente"];
                    //Deber�a revisar detfac para ver si la fc es tipo 53 o 54
                    // DETFAC DE LA NOTA DE CREDITO
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%B0004%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 738");
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $descri_det = $row_detfac1["descrip"];
                        $prim_char = strpos($descri_det, "B0004");
                        $nrodoc_rel = substr($descri_det, $prim_char + 10, 4);
                        if (strpos($descri_det, "DEB")=== FALSE && strpos($descri_det, "D�B")=== FALSE) {
                            $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (53,54) AND ncomp = $nrodoc_rel";
                            $cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA 3".$nrodoc_rel);
                        }
                        else {
                            $query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (60) AND ncomp = $nrodoc_rel";
                            $cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA 4".$nrodoc_rel);
                        }
                        // Y con estos datos leo el detfac correspondiente si es tipo 54:
                        if ($row_cabefac = mysqli_fetch_assoc($cabefac)) {
                            if ($row_cabefac["tcomp"] == 54) {
                                $tcomp_cabefac = $row_cabefac["tcomp"];
                                $ncomp_cabefac = $row_cabefac["ncomp"];
                                //Debo leer los renglones de detfac para ver de que se trata
                                $query_detfacfc1 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%INMOB%' OR descrip like '%GASTOS%'  OR descrip like '%TASA%')";
                                $detfacfc1 = mysqli_query($amercado, $query_detfacfc1) or die("ERROR LEYENDO DETFAC 759");
                                if (mysqli_num_rows($detfacfc1) > 0) {
                                    $row_detfacfc1 = mysqli_fetch_assoc($detfacfc1);
                                    $tot_servicios = $row_detfacfc1["neto"];
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"];
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                                $query_detfacfc2 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%PUBLIC%' OR descrip like '%SE�A%' ";
                                $detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die("ERROR LEYENDO DETFAC 774");
                                if (mysqli_num_rows($detfacfc2) > 0) {
                                    $row_detfacfc2 = mysqli_fetch_assoc($detfacfc2);
                                    $cliente      = $row_cabecerafac["cliente"];
                                    if ($ncomp_cabefac != 1372) {
                                        $tot_public   = $row_detfacfc2["neto"];
                                    }
                                    else 
                                        $tot_public   = 1.00;
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"]; 
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                                $query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%SERVICIO%'";
                                $detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die("ERROR LEYENDO DETFAC 789");
                                if (mysqli_num_rows($detfacfc3) > 0) {
                                    $row_detfacfc3 = mysqli_fetch_assoc($detfacfc3);
                                    $cliente      = $row_cabecerafac["cliente"];
                                    $tot_comision = $row_detfacfc3["neto"];
                                    $tot_public   = 0.00;
                                    $lotes21      = 0.00;
                                    $lotes105     = 0.00;
                                    $tot_iva21    = $row_cabecerafac["totiva21"]; 
                                    $tot_iva105   = $row_cabecerafac["totiva105"];
                                    $tot_iva      = $tot_iva21 + $tot_iva105;
                                    $tot_resol    = $desc_prov;
                                    $total        = $row_cabecerafac["totbruto"];
                                    $nroorig      = $row_cabecerafac["nrodoc"];
                                }
                            }
                            else 
                                if ($row_cabefac["tcomp"] == 60) {
                                    if ($row_cabecerafac["totiva105"] == 0.00 && $row_cabecerafac["totiva21"] == 0.00) {
                                        $cliente      = $row_cabecerafac["cliente"];
                                        $lotes21      = 0.00;
                                        $lotes105     = 0.00;
                                        $tot_public   = $row_cabecerafac["totneto21"];
                                        $tot_comision = 0.00;
                                        $tot_iva21    = 0.00;
                                        $tot_iva105   = 0.00;
                                        $tot_iva      = $tot_iva21 + $tot_iva105;
                                        $tot_resol    = $desc_prov;
                                        $total        = $row_cabecerafac["totbruto"];
                                        $nroorig      = $row_cabecerafac["nrodoc"];	
                                    }
                                }	
                                else 
                                    if ($row_cabefac["tcomp"] == 53) {
                                        $cliente      = $row_cabecerafac["cliente"];
                                        $tot_servicios =  $row_cabecerafac["totimp"];
                                        $tot_comision   = $row_cabecerafac["totcomis"];
                                        $tot_public = 0.00; 
                                        $lotes21      = $row_cabecerafac["totneto21"];;
                                        $lotes105     = $row_cabecerafac["totneto105"];;
                                        $tot_iva21    = $row_cabecerafac["totiva21"];
                                        $tot_iva105   = $row_cabecerafac["totiva105"];
                                        $tot_iva      = $tot_iva21 + $tot_iva105;
                                        $tot_resol    = $desc_prov;
                                        $total        = $row_cabecerafac["totbruto"];
                                        $nroorig      = $row_cabecerafac["nrodoc"];
                                    }
                            $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                            $acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
                            $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                            $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                            $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                            $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                        }
                    }
                    else {
                        $cliente      = $row_cabecerafac["cliente"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_public   = $row_cabecerafac["totneto21"];
                        $tot_comision = 0.00;
                        $tot_iva21    = 0.00;
                        $tot_iva105   = 0.00;
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];	
                        $acum_comis[$prov_remate]    = $acum_comis[$prov_remate] + ($tot_comision * $signo);
                        $acum_public[$prov_remate]   = $acum_public[$prov_remate] + ($tot_public * $signo);
                        $acum_serv[$prov_remate]     = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                        $acum_lotes21[$prov_remate]  = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                        $acum_prov[$prov_remate]     = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    }
                    break;
                case 59:
                case 94:
                case 60:
                    if (($row_cabecerafac["totiva21"] != 0 && $row_cabecerafac["totiva105"] != 0) && ($row_cabecerafac["ncomp"] != 177 && $row_cabecerafac["ncomp"] != 378 && $row_cabecerafac["ncomp"] != 379 && $row_cabecerafac["ncomp"] != 380 && $row_cabecerafac["ncomp"] != 381 && $row_cabecerafac["ncomp"] != 382)) {
                        $cliente      = $row_cabecerafac["cliente"];
                        $lotes21      = $row_cabecerafac["totneto21"] ;
                        $lotes105     = $row_cabecerafac["totneto105"];
                        $tot_comision = $row_cabecerafac["totcomis"];
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_servicios =  $row_cabecerafac["totimp"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios ;
                    }
                    else {
                        $cliente      = $row_cabecerafac["cliente"];
                        //$lotes21      = $row_cabecerafac["totneto21"];
                        $lotes105     = $row_cabecerafac["totneto105"];
                        if ($row_cabecerafac["ncomp"] == 74 && $row_cabecerafac["tcomp"] == 60) {
                            $tot_public   = $row_cabecerafac["totneto21"];
                            $lotes21      = 0.00;
                        }
                        else {
                            $tot_public   = 0.00;
                            $lotes21      = $row_cabecerafac["totneto21"];
                        }
                        if ($row_cabecerafac["ncomp"] == 386 && $row_cabecerafac["tcomp"] == 59) {
                            $tot_servicios   = $row_cabecerafac["totneto21"];
                            $lotes21      = 0.00;
                        }
                        else
                            $tot_servicios =  $row_cabecerafac["totimp"];
                        $tot_comision = $row_cabecerafac["totcomis"];
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];	
                        $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                        $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public ;
                        $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios ;
                        $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21 ;
                        $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105 ;
                        $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    }
                    break;
                case 61:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto105  = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_iva21    = $row_cabecerafac["totiva21"];
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    break;
                case 62:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto105  = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_iva21    = $row_cabecerafac["totiva21"];
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    break;
                case 63:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto21   = $row_cabecerafac["totneto21"] ;
                    $tot_neto105  = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_iva21    = $row_cabecerafac["totiva21"] ;
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    break;
                case 64:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto21   = $row_cabecerafac["totneto21"] ;
                    $tot_neto105  = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_iva21    = $row_cabecerafac["totiva21"] ;
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    break;
                case 86:
                    break;
                case 104:
                    break;
            }
            $estado = "P";
            
            if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62  || $tcomp == 93) {
                // resto Notas de Cr�dito
                $acum_tot_neto21  = $acum_tot_neto21  - $lotes21;
                $acum_tot_neto105 = $acum_tot_neto105 - $lotes105;
                $acum_tot_iva     = $acum_tot_iva     - $tot_iva;
                $acum_totservicios  = $acum_totservicios  - $tot_servicios;
                $acum_total       = $acum_total       - $total;
                $acum_totcomis    = $acum_totcomis    - $tot_comision;
                $acum_totpublic   = $acum_totpublic    - $tot_public;
            }
            else {
                // Sumo Facturas y Notas de D�bito
                $acum_tot_neto21  = $acum_tot_neto21  + $lotes21;
                $acum_tot_neto105 = $acum_tot_neto105 + $lotes105;
                $acum_tot_iva     = $acum_tot_iva     + $tot_iva;
                $acum_total       = $acum_total       + $total;
                $acum_totcomis    = $acum_totcomis    + $tot_comision;
                $acum_totservicios = $acum_totservicios + $tot_servicios;
                $acum_totpublic   = $acum_totpublic + $tot_public;
            }
            $lotes21       *= $signo;
            $lotes105      *= $signo;
            $tot_iva       *= $signo;
            $tot_comision  *= $signo;
            $tot_public    *= $signo;
            $tot_servicios *= $signo;
            $total         *= $signo;
            $lotes21       = number_format($lotes21, 2, ',','.');
            $lotes105      = number_format($lotes105, 2, ',','.');
            $tot_iva       = number_format($tot_iva, 2, ',','.');
            $tot_public    = number_format($tot_public, 2, ',','.');
            $tot_comision  = number_format($tot_comision, 2, ',','.');
            $tot_servicios = number_format($tot_servicios, 2, ',','.');
            $total         = number_format($total, 2, ',','.');

            // Leo el cliente
            $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
            $enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
            $row_entidades = mysqli_fetch_assoc($enti);
            $nom_cliente   = substr($row_entidades["razsoc"], 0, 17);
            $nro_cliente   = $row_entidades["numero"];
            $cuit_cliente  = $row_entidades["cuit"];

            // Imprimo los renglones
            $pdf->SetY($valor_y);
            $pdf->Cell(1);
            $pdf->Cell(19,6,$fecha,0,0,'L');
            $pdf->SetX(19);
            $pdf->Cell(6,6,$tc." ",0,0,'L');
            $pdf->Cell(28,6,$nroorig,0,0,'L');
            $pdf->Cell(40,6,$nom_cliente,0,0,'L');
            $pdf->Cell(22,6,$cuit_cliente,0,0,'L');
            $pdf->Cell(23,6,$tot_public,0,0,'R');
            $pdf->Cell(23,6,$tot_servicios,0,0,'R');
            $pdf->Cell(26,6,$tot_comision,0,0,'R');
            $pdf->Cell(26,6,$lotes21,0,0,'R');
            $pdf->Cell(26,6,$lotes105,0,0,'R');
            $pdf->Cell(24,6,$tot_iva,0,0,'R');
            $pdf->Cell(24,6,$tot_resol,0,0,'L');
            $pdf->Cell(26,6,$total,0,0,'R');
            $pdf->Cell(15,6,$codrem,0,0,'R');

            $i = $i + 1;	
            $valor_y = $valor_y + 6;
         }
    }
    // Imprimo subtotales de la hoja la �ltima vez de cada remate
    $acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
    $acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
    $acum_tot_iva     = number_format($acum_tot_iva, 2, ',','.');
    $acum_totpublic  = number_format($acum_totpublic, 2, ',','.');
    $acum_total       = number_format($acum_total, 2, ',','.');
    $acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
    $acum_totservicios = number_format($acum_totservicios, 2, ',','.');

    $pdf->SetY($valor_y);
    $pdf->Cell(112);
    $pdf->Cell(26,6,$acum_totpublic,0,0,'R');
    $pdf->Cell(26,6,$acum_totservicios,0,0,'R');
    $pdf->Cell(26,6,$acum_totcomis,0,0,'R');
    $pdf->Cell(26,6,$acum_tot_neto21,0,0,'R');
    $pdf->Cell(24,6,$acum_tot_neto105,0,0,'R');
    $pdf->Cell(24,6,$acum_tot_iva,0,0,'R');
    $pdf->Cell(24,6,"                       .",0,0,'R');
    $pdf->Cell(26,6,$acum_total,0,0,'R');
    
    // Acumulo con los demas remates
    $acum_totrem_neto21 += $acum_tot_neto21;
    $acum_totrem_neto105 += $acum_tot_neto105;
    $acum_totrem_iva += $acum_tot_iva;
    $acum_totrempublic += $acum_totpublic;
    $acum_totrem_total += $acum_total;
    $acum_totremcomis += $acum_totcomis;
    $acum_totremservicios += $acum_totservicios;
    
}


// Imprimo subtotales de la hoja la �ltima vez
/*
$acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
$acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
$acum_tot_iva     = number_format($acum_tot_iva, 2, ',','.');
$acum_totpublic  = number_format($acum_totpublic, 2, ',','.');
$acum_total       = number_format($acum_total, 2, ',','.');
$acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
$acum_totservicios = number_format($acum_totservicios, 2, ',','.');
*/

$pdf->SetY($valor_y);
$pdf->Cell(112);
$pdf->Cell(26,6,$acum_totpublic,0,0,'R');
$pdf->Cell(26,6,$acum_totservicios,0,0,'R');
$pdf->Cell(26,6,$acum_totcomis,0,0,'R');
$pdf->Cell(26,6,$acum_tot_neto21,0,0,'R');
$pdf->Cell(24,6,$acum_tot_neto105,0,0,'R');
$pdf->Cell(24,6,$acum_tot_iva,0,0,'R');
$pdf->Cell(24,6,"                       .",0,0,'R');
$pdf->Cell(26,6,$acum_total,0,0,'R');

// Voy a otra hoja e imprimo los titulos 
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
$pdf->Cell(110);
$pdf->Cell(60,10,' Ventas por Remate Consolidado (C�rdoba) - Usuario cobranzas: '.$nombre_usu,0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(10);
$pdf->Cell(60,16,' Provincia',1,0,'L');
$pdf->Cell(26,16,' Publicidad y',1,0,'L');
$pdf->Cell(26,16,'    Servicios ',1,0,'L');
$pdf->Cell(26,16,'    Comisiones ',1,0,'L');
$pdf->Cell(26,16,' Lotes al 21 % ',1,0,'L');
$pdf->Cell(26,16,' Lotes al 10,5 %',1,0,'L');
$pdf->Cell(30,16,'Total Neto',1,0,'L');

$pdf->SetY(28);
$pdf->Cell(70);
$pdf->Cell(23,16,'   Cargos',0,0,'L');
$pdf->SetY(31);
$pdf->Cell(70);
$pdf->Cell(23,16,' financieros',0,0,'L');
$valor_y = 45;
$tot_ac_prov = 0.00;
$tot_ac_comis = 0.00;
$tot_ac_serv = 0.00;
$tot_ac_public = 0.00;
$tot_ac_lotes21 = 0.00;
$tot_ac_lotes105 = 0.00;
// Pongo los totales por jurisdicci�n
for ($j = 1; $j < 30; $j++) {
	if ($acum_prov[$j] == 0)
		continue;
	
	$tot_ac_prov += $acum_prov[$j];
	$tot_ac_comis += $acum_comis[$j];
	$tot_ac_serv += $acum_serv[$j];
	$tot_ac_public += $acum_public[$j];
	$tot_ac_lotes21 += $acum_lotes21[$j];
	$tot_ac_lotes105 += $acum_lotes105[$j];

	
	$ac_prov = number_format($acum_prov[$j],2, ',','.');
	$ac_comis = number_format($acum_comis[$j],2, ',','.');
	$ac_serv = number_format($acum_serv[$j],2, ',','.');
	$ac_public = number_format($acum_public[$j],2, ',','.');
	$ac_lotes21 = number_format($acum_lotes21[$j],2, ',','.');
	$ac_lotes105 = number_format($acum_lotes105[$j],2, ',','.');
	
	$pdf->SetY($valor_y);
	$pdf->Cell(10);
	$pdf->Cell(30,6,$descrip_prov[$j],0,0,'L');
	$pdf->SetX(72);
	$pdf->Cell(26,6,$ac_public,0,0,'R');
	$pdf->Cell(26,6,$ac_serv,0,0,'R');
	$pdf->Cell(26,6,$ac_comis,0,0,'R');
	$pdf->Cell(26,6,$ac_lotes21,0,0,'R');
	$pdf->Cell(26,6,$ac_lotes105,0,0,'R');
	$pdf->Cell(30,6,$ac_prov,0,0,'R');
	
	$valor_y = $valor_y + 6;
}
$comisionesba = $tot_ac_comis + $tot_ac_public;
$totcomisionesba = $tot_ac_comis + $tot_ac_public + $tot_ac_serv;
$tot_ac_prov = number_format($tot_ac_prov,2, ',','.');
$tot_ac_comis = number_format($tot_ac_comis,2, ',','.');
$tot_ac_serv = number_format($tot_ac_serv,2, ',','.');
$tot_ac_public = number_format($tot_ac_public,2, ',','.');
$tot_ac_lotes21 = number_format($tot_ac_lotes21,2, ',','.');
$tot_ac_lotes105 = number_format($tot_ac_lotes105,2, ',','.');

$pdf->SetY($valor_y);
$pdf->Cell(10);
$pdf->Cell(30,6,"TOTALES ID CONSOLIDADO ".$remate,0,0,'L');
$pdf->SetX(72);
$pdf->Cell(26,6,$tot_ac_public,0,0,'R');
$pdf->Cell(26,6,$tot_ac_serv,0,0,'R');
$pdf->Cell(26,6,$tot_ac_comis,0,0,'R');
$pdf->Cell(26,6,$tot_ac_lotes21,0,0,'R');
$pdf->Cell(26,6,$tot_ac_lotes105,0,0,'R');
$pdf->Cell(30,6,$tot_ac_prov,0,0,'R');

$porc_comisba = $comisionesba * 0.02;
$porc_comistotba = $totcomisionesba * 0.02;
$valor_y = $valor_y + 6;
$pdf->SetY($valor_y);
$pdf->SetX(10);
$pdf->Cell(50,6,"2 % (comisi�n + publicidad) ===>  ",0,0,'L');
$porc_comisba    = number_format($porc_comisba,2, ',','.');
$pdf->SetX(70);
$pdf->Cell(26,6,$porc_comisba,0,0,'R');
$valor_y = $valor_y + 6;
$pdf->SetY($valor_y);
$pdf->SetX(10);
$pdf->Cell(50,6,"2 % (comisi�n + publicidad + tasa) ===>  ",0,0,'L');
$porc_comistotba    = number_format($porc_comistotba,2, ',','.');
$pdf->SetX(70);
$pdf->Cell(26,6,$porc_comistotba,0,0,'R');


mysqli_close($amercado);
$pdf->Output();
?>