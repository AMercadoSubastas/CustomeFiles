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
$f_desde = $fecha_desde;
$f_hasta = $fecha_hasta;
$fecha_desde = substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2);
$fecha_hasta = substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2);
//echo "F DESDE = ".$fecha_desde." F HASTA = ".$fecha_hasta."  ";

$acum_prov=array();
$acum_public=array();
$acum_cfinan=array();
$acum_serv=array();
$acum_comis=array();
$acum_lotes21=array();
$acum_lotes105=array();
$descrip_prov=array();
$remates = array();
//$remates[0] = $remate;

for ($j=1;$j < 30; $j++) {
	$query_prov = sprintf("SELECT * FROM provincias WHERE codnum = %s", $j);
	$prov = mysqli_query($amercado, $query_prov) or die ("ERROR LEYENDO PROVINCIAS ".$query_prov." ");
		$row_prov = mysqli_fetch_assoc($prov);
		$descrip_prov[$j] = $row_prov["descripcion"];
}
for ($j=1;$j < 30; $j++) {
	$acum_prov[$j] = 0.00;
	$acum_public[$j] = 0.00;
    $acum_cfinan[$j] = 0.00;
	$acum_serv[$j] = 0.00;
	$acum_comis[$j] = 0.00;
	$acum_lotes21[$j] = 0.00;
	$acum_lotes105[$j] = 0.00;
}
$acum_totrem_neto21 = 0.00;
$acum_totrem_neto105 = 0.00;
$acum_totrem_iva = 0.00;
$acum_totrempublic = 0.00;
$acum_totremcfinan = 0.00;
$acum_totrem_total = 0.00;
$acum_totremcomis = 0.00;
$acum_totremservicios = 0.00;
$fechahoy = date("d-m-Y");
//$fecha_desde = "2022-03-01";
//$fecha_hasta = "2023-02-28";
//Primero leo la cantidad de remates que se generaron a partir del original 
$query_remasoc = "SELECT * FROM remates WHERE fecreal BETWEEN '$fecha_desde' AND '$fecha_hasta' AND codcli != 940";
//echo "query = ".$query_remasoc." - ";
$remasoc = mysqli_query($amercado, $query_remasoc) or die("ERROR LEYENDO REMATES ASOCIADOS ".$query_remasoc." ");

$j = 0;
while ($rows_remasoc = mysqli_fetch_array($remasoc)){
    $remates[$j] = $rows_remasoc["ncomp"];
    //echo "REMATE NRO ".$j." - ".$remates[$j]." - ";
    $j++;
}
$tope = $j ;
/*
echo "TOPE = ".$tope." - ";
for ($rem = 0; $rem < $tope;$rem++) {
    echo "REMATE = ".$remates[$rem]." -K = ".$rem." | ";
}
*/
$pdf=new FPDF('L','mm','Legal');

$nombre_usu = "";
for ($rem = 0; $rem < $tope;$rem++) {
    if ($remates[$rem] == "")
        continue;
   // echo "REMATE NRO ".$rem." - ".$remates[$rem]." - ";
    $query_cabf = sprintf("SELECT * FROM cabfac WHERE codrem = $remates[$rem] AND tcomp NOT IN (98,99) ORDER BY tcomp, ncomp");
    if ($cabf = mysqli_query($amercado, $query_cabf)) //or die("ERROR LEYENDO CABFAC 57 ".$query_cabf." ".$rem);
        $rows_cabf = mysqli_fetch_array($cabf);
    else {
        //echo "ERROR ".$query_cabf."  ".$rem." REMATE = ".$remates[$rem]." ";
    }
    $usuario = $rows_cabf["usuario"];
    if (isset($usuario) && $usuario != null) {
        $query_usu = sprintf("SELECT * FROM usuarios WHERE codnum = %s ", $usuario);
        $usu = mysqli_query($amercado, $query_usu) or die ("ERROR LEYENDO USUARIOS 62");
        $rows_usu = mysqli_fetch_array($usu);
        $nombre_usu = $rows_usu["nombre"];
    }
    else {
        $nombre_usu = "No definido";
    }
    $query_cabfac = sprintf("SELECT * FROM cabfac WHERE (tcomp BETWEEN 51 AND 64 OR tcomp IN (86,89,92,93,94,103,104,105,111,112,113)) AND codrem = $remates[$rem] ORDER BY fecreg, nrodoc, tcomp" );
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
    $pdf->Cell(60,10,' Ventas por Remate entre fechas - Usuario cobranzas: '.$nombre_usu,0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->SetY(25);
    $pdf->Cell(3);
    $pdf->Cell(20,16,'    Fecha',1,0,'L');
    $pdf->Cell(30,16,' Nro.Factura',1,0,'L');
    $pdf->Cell(40,16,'       Raz�n Social',1,0,'L');
    $pdf->Cell(22,16,'   Cargos',1,0,'L');
    $pdf->Cell(23,16,'  Publicidad  ',1,0,'L');
    $pdf->Cell(26,16,'    Servicios ',1,0,'L');
    $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
    $pdf->Cell(26,16,' Lotes al 21 % ',1,0,'L');
    $pdf->Cell(24,16,' Lotes al 10,5 %',1,0,'L');
    $pdf->Cell(24,16,' IVA Debito',1,0,'L');
    $pdf->Cell(24,16,' Provincia',1,0,'L');
    $pdf->Cell(26,16,'Total Facturado',1,0,'L');
    $pdf->Cell(15,16,'Id '.$remates[$rem],1,0,'L');
    
    $valor_y = 45;

    // Limpio acunuladores
    $i = 0;
    $acum_tot_neto21     = 0;
    $acum_tot_neto105    = 0;
    $acum_tot_iva        = 0;
    $acum_totpublic      = 0;
    $acum_totcfinan      = 0;
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
        if ($tcomp ==  57 ||  $tcomp ==  58 ||  $tcomp ==  61 ||  $tcomp ==  62 ||  $tcomp ==  93  || $tcomp == 105 || $tcomp == 113 ) {
            $tc = "NC-";
            $signo = -1;
        }

        elseif ($tcomp == 59 ||  $tcomp == 60 ||  $tcomp ==  63 ||  $tcomp ==  64 ||  $tcomp ==  94){
            $tc = "ND-";
            $signo = 1;
        }
        else {
            $tc = "FC-";
            $signo = 1;
        }
       //echo " TIENE CABFAC ";
        if ($i <= 23) {
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
                    $rematesv= mysqli_query($amercado, $query_remates) or die("ERROR LEYENDO REMATES 197");
                    $row_remate = mysqli_fetch_assoc($rematesv);
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
            if ($prov_remate != 8)
                continue;
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
				$tot_iva21    = $row_cabecerafac["totiva21"];
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
            case 54:
            case 86:
			case 92:
            case 103:
            case 104:
            case 112:
                $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                
                $tot_public   = 0.00;
				$lotes21      = 0.00;
				$lotes105     = 0.00;
                $tot_comision = 0.00;
				$tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac)) {
                    if ($row_detfac["concafac"] == 3)
                        $tot_public   += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 14)
				        $lotes21      += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 4  || $row_detfac["concafac"] == 24 || $row_detfac["concafac"] == 28)
				        $tot_public      += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 13)
				        $lotes105     += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 5 || $row_detfac["concafac"] == 6)
                        $tot_comision += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 1 || $row_detfac["concafac"] == 7 || $row_detfac["concafac"] == 8 || $row_detfac["concafac"] == 9)
				        $tot_servicios += $row_detfac["neto"];
                }
                $cliente      = $row_cabecerafac["cliente"];
                $tot_iva21    = $row_cabecerafac["totiva21"];
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
                $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				$acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
				$acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                break;
			case 53:
				$cliente      = $row_cabecerafac["cliente"];
				$lotes21      = $row_cabecerafac["totneto21"] ;
				$lotes105     = $row_cabecerafac["totneto105"];
				$tot_comision = $row_cabecerafac["totcomis"];
				$tot_servicios= $row_cabecerafac["totimp"];
				$tot_iva21    = $row_cabecerafac["totiva21"];
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
			case 55:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 457");
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
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
				$query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
				$detfac2 = mysqli_query($amercado, $query_detfac2) or die("ERROR LEYENDO DETFAC 476");
				if (mysqli_num_rows($detfac2) > 0) {
					$row_detfac2 = mysqli_fetch_assoc($detfac2);
					$cliente      = $row_cabecerafac["cliente"];
					$tot_public   = $row_detfac2["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = $row_cabecerafac["totiva21"];
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
				}
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO DETFAC 495");
				if (mysqli_num_rows($detfac3) > 0) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = $row_cabecerafac["totiva21"];
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
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 520");
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
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
				$query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
				$detfac2 = mysqli_query($amercado, $query_detfac2) or die("ERROR LEYENDO DETFAC 539");
				if (mysqli_num_rows($detfac2) > 0) {
					$row_detfac2 = mysqli_fetch_assoc($detfac2);
					$cliente      = $row_cabecerafac["cliente"];
					$tot_public   = $row_detfac2["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = $row_cabecerafac["totiva21"];
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
				}
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO DETFAC 558");
				if (mysqli_num_rows($detfac3) > 0) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = $row_cabecerafac["totiva21"];
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
            case 58:
			case 93:
            case 105:
            case 113:
                $tot_comision = 0.00;
                    if ($row_cabecerafac["totcomis"] > 0.00) {
                        $tot_comision += $row_cabecerafac["totcomis"];
                    }
                $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                $tot_public   = 0.00;
				$lotes21      = 0.00;
				$lotes105     = 0.00;
                $tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac)) {
                    if ($row_detfac["concafac"] == 20 || $row_detfac["concafac"] == 28 || $row_detfac["concafac"] == 33 || $row_detfac["concafac"] == 41)
                        $tot_public   += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 14)
				        $lotes21      += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 13 || $row_detfac["concafac"] == 16)
				        $lotes105     += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 19 || $row_detfac["concafac"] == 36)
                        $tot_comision += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 18 || $row_detfac["concafac"] == 25 || $row_detfac["concafac"] == 32 || $row_detfac["concafac"] == 42)
				        $tot_servicios += $row_detfac["neto"];
                }
                $cliente      = $row_cabecerafac["cliente"];
                $tot_iva21    = $row_cabecerafac["totiva21"];
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
                $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
				$acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
				$acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
				break;
			case 59:
			case 94:
			case 60:
				if (($row_cabecerafac["totiva21"] != 0 && $row_cabecerafac["totiva105"] != 0) && ($row_cabecerafac["ncomp"] != 177 && $row_cabecerafac["ncomp"] != 378 && $row_cabecerafac["ncomp"] != 400 && $row_cabecerafac["ncomp"] != 380 && $row_cabecerafac["ncomp"] != 381 && $row_cabecerafac["ncomp"] != 382)) {
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
                    if ($row_cabecerafac["ncomp"] == 400 && $row_cabecerafac["tcomp"] == 59) {
                        $tot_comision   = $row_cabecerafac["totneto21"];
                        $lotes21      = 0.00;
                    }
                    else
                        $tot_servicios =  $row_cabecerafac["totimp"];
                    if (($row_cabecerafac["ncomp"] == 390) && $row_cabecerafac["tcomp"] == 59) {
                        $tot_servicios   = $row_cabecerafac["totimp"];
                        $tot_comision    = $row_cabecerafac["totneto21"];
                        $lotes21         = 0.00;
                    } else if (($row_cabecerafac["ncomp"] != 400) && $row_cabecerafac["tcomp"] == 59)
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
				$tot_iva21    = $row_cabecerafac["totiva21"];
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
				break;
			case 64:
				$cliente      = $row_cabecerafac["cliente"];
				$tot_servicios   = $row_cabecerafac["totneto21"] ;
				$tot_neto21   = $row_cabecerafac["totneto21"] ;
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
		}
		

		// Acumulo subtotales
		 
        if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93 || $tcomp == 105 || $tcomp == 113) {
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
        $enti = mysqli_query($amercado, $query_entidades) or die("ENTIDADES = ".$cliente."TCOMP = ".$row_cabecerafac["tcomp"]."NCOMP = "-$row_cabecerafac["ncomp"]." - ");
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
        $pdf->Cell(60,10,' Ventas por Remate entre fechas - Usuario cobranzas: '.$nombre_usu,0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->SetY(25);
        $pdf->Cell(3);
        $pdf->Cell(20,16,'    Fecha',1,0,'L');
        $pdf->Cell(30,16,' Nro.Factura',1,0,'L');
        $pdf->Cell(40,16,'       Raz�n Social',1,0,'L');
        $pdf->Cell(22,16,'     CUIT',1,0,'L');
        $pdf->Cell(23,16,' Publicidad  ',1,0,'L');
        $pdf->Cell(26,16,'    Servicios ',1,0,'L');
        $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
        $pdf->Cell(26,16,' Lotes al 21 % ',1,0,'L');
        $pdf->Cell(24,16,' Lotes al 10,5 %',1,0,'L');
        $pdf->Cell(24,16,' IVA Debito',1,0,'L');
        $pdf->Cell(24,16,' Provincia',1,0,'L');
        $pdf->Cell(26,16,'Total Facturado',1,0,'L');
        $pdf->Cell(15,16,'Id '.$remates[$rem],1,0,'L');
        
        $valor_y = 45;
        // reinicio los contadores
        $i = 0;
        // IMPRIMO EL REGISTRO QUE TENGO LEIDO PORQUE SINO LO PIERDO
        if ($codrem != "" && $codrem > 0) {
            //Leo Direccion de exhibicion para saber la provincia
            $query_dir_exhib = sprintf("SELECT * FROM dir_remates WHERE codrem = %s ORDER BY codrem, secuencia", $codrem);
            $dir_exhib = mysqli_query($amercado, $query_dir_exhib) or die("ERROR LEYENDO DIR EXPO 1292 ".$query_dir_exhib);
            if (mysqli_num_rows($dir_exhib) > 0) {
                //Leo los lotes a ver si alguno est� asignado a la direccion de exhibicion
                if ($tcomp == 51 || $tcomp == 53 || $tcomp == 89) {
                    // LEO DETFAC PARA VER LOS LOTES
                    $query_detfacprov = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp ";
                    $detfacprov = mysqli_query($amercado, $query_detfacprov) or die("ERROR LEYENDO DETFAC 1298 ".$query_detfacprov);
                    while ($row_detfacprov = mysqli_fetch_array($detfacprov)) {
                        $secuencia = $row_detfacprov["codlote"];
                        $rematefac = $row_detfacprov["codrem"];
                        // LEO EL LOTE PARA VER SI TIENE DIR EXPO
                        $query_loteprov = "SELECT * FROM lotes WHERE codrem = $rematefac AND secuencia = $secuencia ";
                        $loteprov = mysqli_query($amercado, $query_loteprov) or die("ERROR LEYENDO LOTES 1304 ".$query_loteprov);
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
                            $dirprov = mysqli_query($amercado, $query_dirprov) or die("ERROR LEYENDO DIR EXPO 1315 ".$query_dirprov);
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
                $rematesv= mysqli_query($amercado, $query_remates) or die("ERROR LEYENDO REMATES 1330 ".$query_remates);
                $row_remate = mysqli_fetch_assoc($rematesv);
                $prov_remate = $row_remate["codprov"];
            }
        }
        else {
            if ($codrem != 0 && $codrem != "") {
                $cli = $row_cabecerafac["cliente"];
                // Leo el cliente
                $query_enti = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cli);
                $ent = mysqli_query($amercado, $query_enti) or die("ERROR LEYENDO ENTIDADES 1340 ".$query_enti);
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
        if ($prov_remate != 8)
                continue;    
        // Leo la descripci�n de la provincia
        $query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
        $provincias = mysqli_query($amercado, $query_provincias) or die ("ERROR LEYENDO PROVINCIAS 1366 ".$query_provincias);
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
                    $tot_iva21    = $row_cabecerafac["totiva21"];
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
                case 54:
                case 86:
                case 92:
                case 103:
                case 104:
                case 112:
                    $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
                    $detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);

                    $tot_public   = 0.00;
                    $lotes21      = 0.00;
                    $lotes105     = 0.00;
                    $tot_comision = 0.00;
                    $tot_servicios= 0.00;

                    while ($row_detfac = mysqli_fetch_array($detfac)) {
                        if ($row_detfac["concafac"] == 3 || $row_detfac["concafac"] == 24 || $row_detfac["concafac"] == 28)
                            $tot_public   += $row_detfac["neto"];
                        if ($row_detfac["concafac"] == 14)
                            $lotes21      += $row_detfac["neto"];
                        if ($row_detfac["concafac"] == 13)
                            $lotes105     += $row_detfac["neto"];
                        if ($row_detfac["concafac"] == 5 || $row_detfac["concafac"] == 6)
                            $tot_comision += $row_detfac["neto"];
                        if ($row_detfac["concafac"] == 1 || $row_detfac["concafac"] == 7 || $row_detfac["concafac"] == 8 || $row_detfac["concafac"] == 9)
                            $tot_servicios += $row_detfac["neto"];
                    }
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_iva21    = $row_cabecerafac["totiva21"];
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_public[$prov_remate] = $acum_public[$prov_remate] + $tot_public;
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                    $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                    $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                    break;
                case 53:
                    $cliente      = $row_cabecerafac["cliente"];
                    $lotes21      = $row_cabecerafac["totneto21"] ;
                    $lotes105     = $row_cabecerafac["totneto105"];
                    $tot_comision = $row_cabecerafac["totcomis"];
                    $tot_servicios= $row_cabecerafac["totimp"];
                    $tot_iva21    = $row_cabecerafac["totiva21"];
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
                case 55:
                    //Debo leer los renglones de detfac para ver de que se trata
                    $query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 457");
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
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
                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die("ERROR LEYENDO DETFAC 476");
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        $tot_public   = $row_detfac2["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                    }
                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO DETFAC 495");
                    if (mysqli_num_rows($detfac3) > 0) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
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
                    $detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 520");
                    if (mysqli_num_rows($detfac1) > 0) {
                        $row_detfac1 = mysqli_fetch_assoc($detfac1);
                        $tot_comision = $row_detfac1["neto"];
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
                    $query_detfac2 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%PUBLIC%'";
                    $detfac2 = mysqli_query($amercado, $query_detfac2) or die("ERROR LEYENDO DETFAC 539");
                    if (mysqli_num_rows($detfac2) > 0) {
                        $row_detfac2 = mysqli_fetch_assoc($detfac2);
                        $cliente      = $row_cabecerafac["cliente"];
                        $tot_public   = $row_detfac2["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
                        $tot_iva105   = $row_cabecerafac["totiva105"];
                        $tot_iva      = $tot_iva21 + $tot_iva105;
                        $tot_resol    = $desc_prov;
                        $total        = $row_cabecerafac["totbruto"];
                        $nroorig      = $row_cabecerafac["nrodoc"];
                    }
                    $query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%'";
                    $detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO DETFAC 558");
                    if (mysqli_num_rows($detfac3) > 0) {
                        $row_detfac3 = mysqli_fetch_assoc($detfac3);
                        $cliente      = $row_cabecerafac["cliente"];
                        $tot_servicios= $row_detfac3["neto"];
                        $lotes21      = 0.00;
                        $lotes105     = 0.00;
                        $tot_iva21    = $row_cabecerafac["totiva21"];
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
                case 58:
                case 93:
                case 105:
                case 113:
                    $tot_comision = 0.00;
                    if ($row_cabecerafac["totcomis"] > 0.00) {
                        $tot_comision += $row_cabecerafac["totcomis"];
                    }
                    $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
                    $detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                    //$row_detfac = mysqli_fetch_assoc($detfac);
                    $tot_public   = 0.00;
                    $lotes21      = 0.00;
                    $lotes105     = 0.00;
                    $tot_servicios= 0.00;
                    //if ($tcomp == 52 && $ncomp == 15457)
                        //echo "ah� lo ten�s al pelotudo... ".$ncomp." 2 ";
                    while ($row_detfac = mysqli_fetch_array($detfac)) {
                        if ($row_detfac["concafac"] == 20 || $row_detfac["concafac"] == 28 || $row_detfac["concafac"] == 33 || $row_detfac["concafac"] == 41)
                            $tot_public   += $row_detfac["neto"];
                        if ($row_detfac["concafac"] == 14)
                            $lotes21      += $row_detfac["neto"];
                        if ($row_detfac["concafac"] == 13 || $row_detfac["concafac"] == 16)
                            $lotes105     += $row_detfac["neto"];
                        if ($row_detfac["concafac"] == 19 || $row_detfac["concafac"] == 36)
                            $tot_comision += $row_detfac["neto"];
                        if ($row_detfac["concafac"] == 18 || $row_detfac["concafac"] == 25 || $row_detfac["concafac"] == 32 || $row_detfac["concafac"] == 42)
                            $tot_servicios += $row_detfac["neto"];
                    }
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_iva21    = $row_cabecerafac["totiva21"];
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
				$acum_comis[$prov_remate] = $acum_comis[$prov_remate] + ($tot_comision * $signo);
				$acum_public[$prov_remate] = $acum_public[$prov_remate] + ($tot_public * $signo);
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
                    break;
                case 59:
                case 94:
                case 60:
                    if (($row_cabecerafac["totiva21"] != 0 && $row_cabecerafac["totiva105"] != 0) && ($row_cabecerafac["ncomp"] != 177 && $row_cabecerafac["ncomp"] != 378 && $row_cabecerafac["ncomp"] != 400 && $row_cabecerafac["ncomp"] != 380 && $row_cabecerafac["ncomp"] != 381 && $row_cabecerafac["ncomp"] != 382)) {
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
                        if ($row_cabecerafac["ncomp"] == 400 && $row_cabecerafac["tcomp"] == 59) {
                            $tot_comision   = $row_cabecerafac["totneto21"];
                            $lotes21      = 0.00;
                        }
                        else
                            $tot_servicios =  $row_cabecerafac["totimp"];
                        if (($row_cabecerafac["ncomp"] == 390) && $row_cabecerafac["tcomp"] == 59) {
                            $tot_servicios   = $row_cabecerafac["totimp"];
                            $tot_comision    = $row_cabecerafac["totneto21"];
                            $lotes21         = 0.00;
                        } else if (($row_cabecerafac["ncomp"] != 400) && $row_cabecerafac["tcomp"] == 59)
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
                    $tot_iva21    = $row_cabecerafac["totiva21"];
                    $tot_iva105   = $row_cabecerafac["totiva105"];
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
                    break;
                case 64:
                    $cliente      = $row_cabecerafac["cliente"];
                    $tot_servicios   = $row_cabecerafac["totneto21"] ;
                    $tot_neto21   = $row_cabecerafac["totneto21"] ;
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
            }

            // Acumulo subtotales

            if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93 || $tcomp == 105 || $tcomp == 113) {
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
            $enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES 2308 ".$query_entidades);
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
$pdf->Cell(60,10,' Ventas por Remate entre fechas  ',0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(10);
$pdf->Cell(60,16,'       Provincia       ',1,0,'C');
$pdf->SetY(25);
$pdf->SetX(70);
$pdf->Cell(26,16,'  ',1,0,'L');
$pdf->SetY(22);
$pdf->SetX(70);
$pdf->Cell(26,16,'   Publicidad ',0,0,'L');
$pdf->SetY(25);
$pdf->SetX(96);
$pdf->Cell(26,16,'    Servicios ',1,0,'L');
$pdf->Cell(26,16,'    Comisiones ',1,0,'L');
$pdf->Cell(26,16,' Lotes al 21 % ',1,0,'L');
$pdf->Cell(26,16,' Lotes al 10,5 %',1,0,'L');
$pdf->Cell(30,16,'    Total Neto',1,0,'L');


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

$pdf->SetY($valor_y);
$pdf->Cell(10);
$pdf->Cell(30,6,"TOTALES ANUALES ",0,0,'L');
$pdf->SetX(72);
$pdf->Cell(26,6,$tot_ac_public,0,0,'R');
$pdf->Cell(26,6,$tot_ac_serv,0,0,'R');
$pdf->Cell(26,6,$tot_ac_comis,0,0,'R');
$pdf->Cell(26,6,$tot_ac_lotes21,0,0,'R');
$pdf->Cell(26,6,$tot_ac_lotes105,0,0,'R');
$pdf->Cell(30,6,$tot_ac_prov,0,0,'R');

// PCIA DE BS AS
if ($acum_prov[1] != 0) {
    $comisionesba = $acum_comis[1] + $acum_public[1];
    $totcomisionesba = $acum_comis[1] + $acum_public[1] + $acum_serv[1];

    $porc_comisba = $comisionesba * 0.06;
    $porc_comistotba = $totcomisionesba * 0.06;
    $valor_y = $valor_y + 6;
    $pdf->SetY($valor_y);
    $pdf->SetX(10);
    $pdf->Cell(50,6,"6 % (Bs. As. comis + public) ===>  ",0,0,'L');
    $porc_comisba    = number_format($porc_comisba,2, ',','.');
    $pdf->SetX(70);
    $pdf->Cell(26,6,$porc_comisba,0,0,'R');
    $valor_y = $valor_y + 6;
    $pdf->SetY($valor_y);
    $pdf->SetX(10);
    $pdf->Cell(50,6,"6 % (Bs. As. comis + public + tasa) ===>  ",0,0,'L');
    $porc_comistotba    = number_format($porc_comistotba,2, ',','.');
    $pdf->SetX(70);
    $pdf->Cell(26,6,$porc_comistotba,0,0,'R');
}
// PCIA DE CORDOBA
if ($acum_prov[6] != 0) {
    $comisionesba = $acum_comis[6] + $acum_public[6];
    $totcomisionesba = $acum_comis[6] + $acum_public[6] + $acum_serv[6];

    $porc_comisba = $comisionesba * 0.02;
    $porc_comistotba = $totcomisionesba * 0.02;
    $valor_y = $valor_y + 6;
    $pdf->SetY($valor_y);
    $pdf->SetX(10);
    $pdf->Cell(50,6,"2 % (Cba. comis + public) ===>  ",0,0,'L');
    $porc_comisba    = number_format($porc_comisba,2, ',','.');
    $pdf->SetX(70);
    $pdf->Cell(26,6,$porc_comisba,0,0,'R');
    $valor_y = $valor_y + 6;
    $pdf->SetY($valor_y);
    $pdf->SetX(10);
    $pdf->Cell(50,6,"2 % (Cba. comis + public + tasa) ===>  ",0,0,'L');
    $porc_comistotba    = number_format($porc_comistotba,2, ',','.');
    $pdf->SetX(70);
    $pdf->Cell(26,6,$porc_comistotba,0,0,'R');
}
mysqli_close($amercado);
$pdf->Output();
?>