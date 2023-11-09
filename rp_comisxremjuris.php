<?php
//set_time_limit(0); // Para evitar el timeout
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
define('FPDF_FONTPATH','fpdf17/font/');
require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');
mysqli_select_db($amercado, $database_amercado);
// Leo los parametros del formulario anterior
$remate    	 = $_POST['remate_num'];
//echo "REMATE = ".$remate."  ";
$acum_prov=array();
$acum_public=array();
$acum_cargosfin=array();
$acum_serv=array();
$acum_comis=array();
$acum_lotes21=array();
$acum_lotes105=array();
$descrip_prov=array();

for ($j=1;$j < 25; $j++) {
	$query_prov = sprintf("SELECT * FROM provincias WHERE codnum = %s", $j);
	$prov = mysqli_query($amercado, $query_prov) or die ("ERROR LEYENDO PROVINCIAS ".$query_prov." ");
		$row_prov = mysqli_fetch_assoc($prov);
		if (isset($row_prov["codnum"]) && ($row_prov["codnum"] != 2))
			$descrip_prov[$j] = substr($row_prov["descripcion"],0,12);
		else
			$descrip_prov[$j] = "CABA";
}
for ($j=1;$j < 25; $j++) {
	$acum_prov[$j] = 0.00;
	$acum_public[$j] = 0.00;
    $acum_cargosfin[$j] = 0.00;
	$acum_serv[$j] = 0.00;
	$acum_comis[$j] = 0.00;
	$acum_lotes21[$j] = 0.00;
	$acum_lotes105[$j] = 0.00;
}
/*
$acum_totrem_neto21 = 0.00;
$acum_totrem_neto105 = 0.00;
$acum_totrem_iva = 0.00;
$acum_totrempublic = 0.00;
$acum_totcargosfin = 0.00;
$acum_totrem_total = 0.00;
$acum_totremcomis = 0.00;
$acum_totremservicios = 0.00;
*/
$fechahoy = date("d-m-Y");


$pdf=new FPDF('L','mm','Legal');
$descrip_prov[2] = "CABA";
$nombre_usu = "";

    $query_cabf = sprintf("SELECT * FROM cabfac WHERE codrem = $remate AND tcomp NOT IN (98,99) ORDER BY tcomp  , ncomp");
    if ($cabf = mysqli_query($amercado, $query_cabf)) //or die("ERROR LEYENDO CABFAC 57 ".$query_cabf." ".$k);
        $rows_cabf = mysqli_fetch_array($cabf);
    else {
        echo "ERROR ".$query_cabf."  "."REMATE = ".$remate." ";
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
    
    $query_cabfac = sprintf("SELECT * FROM cabfac WHERE (tcomp IN (115,116,117,119,120,121,122,123,124,125,126,127,133,134,135)) AND codrem = $remate ORDER BY fecreg, nrodoc, tcomp" );
    $cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC 68");

    // Inicio el pdf con los datos de cabecera
      
    $pdf->AddPage();
    $pdf->SetMargins(0.5, 0.5 , 0.5);
    $pdf->SetFont('Arial','B',11);
    $pdf->SetY(5);
    $pdf->Cell(10);
    $pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
    $pdf->Cell(200);
    $pagina = $pdf->PageNo();
    $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
    $pdf->SetY(10);
    $pdf->Cell(230);
    $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
    $pdf->SetY(15);
    $pdf->Cell(110);
    $pdf->Cell(60,10,' Ventas por Subasta - Usuario cobranzas: '.$nombre_usu,0,0,'L');
    $pdf->SetFont('Arial','B',9);
    $pdf->SetY(25);
    $pdf->Cell(3);
    $pdf->Cell(20,16,'    Fecha',1,0,'L');
    $pdf->Cell(30,16,' Nro.Factura',1,0,'L');
    $pdf->Cell(40,16,utf8_decode('       Razón Social'),1,0,'L');
    $pdf->Cell(22,16,'   Cargos',1,0,'L');
    $pdf->Cell(23,16,'  Publicidad  ',1,0,'L');
    $pdf->Cell(26,16,'   Gs. Adm. ',1,0,'L');
    $pdf->Cell(26,16,'Uso Plataforma ',1,0,'L');
    $pdf->Cell(26,16,' Lotes al 21 % ',1,0,'L');
    $pdf->Cell(24,16,' Lotes al 10,5 %',1,0,'L');
    $pdf->Cell(24,16,utf8_decode(' IVA Débito'),1,0,'L');
    $pdf->Cell(24,16,' Provincia',1,0,'L');
    $pdf->Cell(26,16,'Total Facturado',1,0,'L');
    $pdf->Cell(15,16,'Id '.$remate,1,0,'L');
    $pdf->SetY(28);
    $pdf->Cell(93);
    $pdf->Cell(23,16,' financieros',0,0,'L');
    $valor_y = 45;

    // Limpio acunuladores
    $i = 0;
    (double) $acum_tot_neto21     = 0.00;
    (double) $acum_tot_neto105    = 0.00;
    (double) $acum_tot_iva        = 0.00;
    (double) $acum_totpublic      = 0.00;
    (double) $acum_totcargosfin   = 0.00;
    (double) $acum_totservicios   = 0.00;
    (double) $acum_total          = 0.00;
    (double) $acum_totcomis       = 0.00;
    $lotes21             = 0.00;
    $lotes105            = 0.00;
    $tot_iva             = 0.00;


    while($row_cabecerafac = mysqli_fetch_array($cabecerafac)) {	
        $tcomp      = $row_cabecerafac["tcomp"];
        $serie      = $row_cabecerafac["serie"];
        $ncomp      = $row_cabecerafac["ncomp"];
        $iva21      = $row_cabecerafac["totiva21"];
        $iva105     = $row_cabecerafac["totiva105"];
        $codrem     = $row_cabecerafac["codrem"];
        if ($tcomp ==  119 ||  $tcomp ==  120 ||  $tcomp ==  121 ||  $tcomp ==  135) {
            $tc = "NC-";
            $signo = -1;
        }

        elseif ($tcomp == 122 ||  $tcomp == 123 ||  $tcomp ==  124){
            $tc = "ND-";
            $signo = 1;
        }
        else {
            $tc = "FC-";
            $signo = 1;
        }
       
        if ($i <= 23) {
            if ($codrem != "" && $codrem > 0) {
                //Leo Direccion de exhibicion para saber la provincia
                $query_dir_exhib = sprintf("SELECT * FROM dir_remates WHERE codrem = %s ORDER BY codrem, secuencia", $codrem);
                $dir_exhib = mysqli_query($amercado, $query_dir_exhib) or die("ERROR LEYENDO DIR EXPO 160");
                if (mysqli_num_rows($dir_exhib) > 0) {
                    //Leo los lotes a ver si alguno esta asignado a la direccion de exhibicion
                    if ($tcomp == 115 || $tcomp == 116 || $tcomp == 117) {
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
                            if ($row_loteprov["dir_secuencia"] == "" || $row_loteprov["dir_secuencia"] == 0) {
                                $row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
                                $prov_remate = $row_dir_exhib["codprov"];
								//echo "paso 1 PRO = ".$prov_remate." ";
                                break;
                            }
                            else {
                                //UFA, TENGO QUE LEER LA DIR DE EXPO DE ESTE LOTE
                                $secuexpo = $row_loteprov["dir_secuencia"];
                                $query_dirprov = "SELECT * FROM dir_remates WHERE codrem = $rematefac AND secuencia = $secuexpo ";
                                $dirprov = mysqli_query($amercado, $query_dirprov) or die("ERROR LEYENDO DIR EXPO 183");
                                $row_dirprov = mysqli_fetch_assoc($dirprov);
                                $prov_remate = $row_dirprov["codprov"];
                               // echo "paso 2 ";
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
                    $remate1 = mysqli_query($amercado, $query_remates) or die("ERROR LEYENDO REMATES 197");
                    $row_remate = mysqli_fetch_assoc($remate1);
                    $prov_remate = $row_remate["codprov"];
                   // echo "paso 4 ";
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
                   // echo "paso 5 ";
                }
                else {
                    if ($serie == 52 || $serie == 53 || $serie == 54 || $serie == 55 || $serie == 56) {
                        $prov_remate = 1;
                       // echo "paso 6 ";
                    }
                    else {
                        $prov_remate = 2;
                        //echo "paso 7 ";
                    }
                   // echo "paso 8 ";
                }
               // echo "paso 9 ";

            }
			/*
            if ($row_cabecerafac["codprov"] != null) {
                    $prov_remate = $row_cabecerafac["codprov"];
            }
            */
            //echo "PROV REMATE = ".$prov_remate;
            // Leo la descripcion de la provincia
            $query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
            $provincias = mysqli_query($amercado, $query_provincias) or die ("ERROR LEYENDO PROVINCIAS");
            $row_provincias = mysqli_fetch_assoc($provincias);
            $desc_prov = $row_provincias["descripcion"];
            $tot_comision = 0.00;
            $tot_public   = 0.00;
            $tot_cargosfin   = 0.00;
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
			case 115:
			case 117:
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
			case 125:
            case 126:
            case 127:
			case 133:
            case 134:
               $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                $tot_cargosfin   = 0.00;
                $tot_public   = 0.00;
				$lotes21      = 0.00;
				$lotes105     = 0.00;
                $tot_comision = 0.00;
				$tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac)) {
                    if ($row_detfac["concafac"] == 2)
                        $tot_cargosfin   += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 14)
				        $lotes21      += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 4 || $row_detfac["concafac"] == 3 || $row_detfac["concafac"] == 24 || $row_detfac["concafac"] == 28)
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
                $acum_cargosfin[$prov_remate] = $acum_cargosfin[$prov_remate] + $tot_cargosfin;
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                break;

			case 116:
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
			case 119:
            case 120:
			case 121:
            case 135:
               $tot_comision = 0.00;
                    if ($row_cabecerafac["totcomis"] > 0.00) {
                        $tot_comision += $row_cabecerafac["totcomis"];
                    }
                $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                $tot_public   = 0.00;
                $tot_cargosfin   = 0.00;
				$lotes21      = 0.00;
				$lotes105     = 0.00;
                $tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac)) {
                    if ($row_detfac["concafac"] == 20 || $row_detfac["concafac"] == 28 || $row_detfac["concafac"] == 33 || $row_detfac["concafac"] == 41)
                        $tot_public   += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 41)
				        $tot_cargosfin      += $row_detfac["neto"];
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
                $acum_cargosfin[$prov_remate] = $acum_cargosfin[$prov_remate] + ($tot_cargosfin * $signo);
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
				break;
			case 122:
			case 123:
			case 124:
 				if ($row_cabecerafac["totiva21"] != 0 && $row_cabecerafac["totiva105"] != 0)  {
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
					$tot_servicios   = $row_cabecerafac["totimp"];
                	$tot_comision    = $row_cabecerafac["totneto21"];
                	$lotes21         = 0.00;
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
		}
		

		// Acumulo subtotales
		 
        if ($tcomp ==  119 ||  $tcomp ==  120 || $tcomp == 121 || $tcomp == 135) {
            // resto Notas de Credito
            $acum_tot_neto21  = $acum_tot_neto21  - $lotes21;
            $acum_tot_neto105 = $acum_tot_neto105 - $lotes105;
            $acum_tot_iva     = $acum_tot_iva     - $tot_iva;
            $acum_totservicios  = $acum_totservicios  - $tot_servicios;
            $acum_total       = $acum_total       - $total;
            $acum_totcomis    = $acum_totcomis    - $tot_comision;
            $acum_totpublic   = $acum_totpublic    - $tot_public;
            $acum_totcargosfin   = $acum_totcargosfin    - $tot_cargosfin;
        }
        else {
            // Sumo Facturas y Notas de Debito
            $acum_tot_neto21  = $acum_tot_neto21  + $lotes21;
            $acum_tot_neto105 = $acum_tot_neto105 + $lotes105;
            $acum_tot_iva     = $acum_tot_iva     + $tot_iva;
            $acum_total       = $acum_total       + $total;
            $acum_totcomis    = $acum_totcomis    + $tot_comision;
            $acum_totservicios = $acum_totservicios + $tot_servicios;
            $acum_totpublic   = $acum_totpublic + $tot_public;
            $acum_totcargosfin   = $acum_totcargosfin + $tot_cargosfin;
			
			//echo "acum_tot_neto21 = ".$acum_tot_neto21." ";
			//echo "lotes21 = ".$lotes21." ";

        }
        $lotes21       *= $signo;
        $lotes105      *= $signo;
        $tot_iva       *= $signo;
        $tot_comision  *= $signo;
        $tot_public    *= $signo;
        $tot_cargosfin    *= $signo;
        $tot_servicios *= $signo;
        $total         *= $signo;

        $lotes21       = number_format($lotes21, 2, ',','.');
        $lotes105      = number_format($lotes105, 2, ',','.');
        $tot_iva       = number_format($tot_iva, 2, ',','.');
        $tot_public    = number_format($tot_public, 2, ',','.');
        $tot_cargosfin    = number_format($tot_cargosfin, 2, ',','.');
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
        $pdf->Cell(22,6,$tot_cargosfin,0,0,'R');
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
        $f_acum_cargosfin   = number_format($acum_totcargosfin, 2, ',','.');
        $f_acum_total       = number_format($acum_total, 2, ',','.');
        $f_acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
        $f_acum_totservicios = number_format($acum_totservicios, 2, ',','.');

        // ACUMULADOS PARCIALES DE PIE DE PAGINA
        $pdf->SetY($valor_y);
        $pdf->Cell(86);
        $pdf->Cell(26,6,$f_acum_cargosfin,0,0,'R');
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
        $pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
        $pdf->Cell(200);
        $pagina = $pdf->PageNo();
        $pdf->Cell(30,10,utf8_decode('Página : ').$pagina,0,0,'L');
        $pdf->SetY(10);
        $pdf->Cell(230);
        $pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
        $pdf->SetY(15);
        $pdf->Cell(110);
        $pdf->Cell(60,10,' Ventas por Subasta - Usuario cobranzas: '.$nombre_usu,0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->SetY(25);
        $pdf->Cell(3);
        $pdf->Cell(20,16,'    Fecha',1,0,'L');
        $pdf->Cell(30,16,' Nro.Factura',1,0,'L');
        $pdf->Cell(40,16,utf8_decode('       Razón Social'),1,0,'L');
        $pdf->Cell(22,16,'   Cargos',1,0,'L');
        $pdf->Cell(23,16,'  Publicidad  ',1,0,'L');
        $pdf->Cell(26,16,'   Gs. Adm. ',1,0,'L');
    	$pdf->Cell(26,16,'Uso Plataforma ',1,0,'L');
        $pdf->Cell(26,16,' Lotes al 21 % ',1,0,'L');
        $pdf->Cell(24,16,' Lotes al 10,5 %',1,0,'L');
        $pdf->Cell(24,16,utf8_decode(' IVA Débito'),1,0,'L');
        $pdf->Cell(24,16,' Provincia',1,0,'L');
        $pdf->Cell(26,16,'Total Facturado',1,0,'L');
        $pdf->Cell(15,16,'Id '.$remate,1,0,'L');
        $pdf->SetY(28);
        $pdf->Cell(93);
        $pdf->Cell(23,16,' financieros',0,0,'L');
        $valor_y = 45;
        // reinicio los contadores
        $i = 0;
        // IMPRIMO EL REGISTRO QUE TENGO LEIDO PORQUE SINO LO PIERDO
        if ($codrem != "" && $codrem > 0) {
            //Leo Direccion de exhibicion para saber la provincia
            $query_dir_exhib = sprintf("SELECT * FROM dir_remates WHERE codrem = %s ORDER BY codrem, secuencia", $codrem);
            $dir_exhib = mysqli_query($amercado, $query_dir_exhib) or die("ERROR LEYENDO DIR EXPO 1292 ".$query_dir_exhib);
            if (mysqli_num_rows($dir_exhib) > 0) {
                //Leo los lotes a ver si alguno esta asignado a la direccion de exhibicion
                if ($tcomp == 115 || $tcomp == 116 || $tcomp == 117) {
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
                        if ($row_loteprov["dir_secuencia"] == "" || $row_loteprov["dir_secuencia"] == 0) {
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
                $remate1 = mysqli_query($amercado, $query_remates) or die("ERROR LEYENDO REMATES 1330 ".$query_remates);
                $row_remate = mysqli_fetch_assoc($remate1);
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
                if ($serie == 52 || $serie == 53 || $serie == 54 || $serie == 55 || $serie == 56) {
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
        
            
        // Leo la descripcion de la provincia
        $query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
        $provincias = mysqli_query($amercado, $query_provincias) or die ("ERROR LEYENDO PROVINCIAS 1366 ".$query_provincias);
        $row_provincias = mysqli_fetch_assoc($provincias);
        $desc_prov = substr($row_provincias["descripcion"],0,15);
        $tot_comision = 0.00;
        $tot_public   = 0.00;
        $tot_cargosfin   = 0.00;
        $tot_servicios= 0.00;
        $lotes21 = 0.00;
        $lotes105 = 0.00;
        $tot_iva21 = 0.00;
        $tot_iva105 = 0.00;
        $tot_iva  = 0.00;
        $fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
  		switch ($tcomp) {
			case 115:
			case 117:
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
			case 125:
            case 126:
            case 127:
			case 133:
            case 134:
               $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                $tot_cargosfin   = 0.00;
                $tot_public   = 0.00;
				$lotes21      = 0.00;
				$lotes105     = 0.00;
                $tot_comision = 0.00;
				$tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac)) {
                    if ($row_detfac["concafac"] == 2)
                        $tot_cargosfin   += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 14)
				        $lotes21      += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 4 || $row_detfac["concafac"] == 3 || $row_detfac["concafac"] == 24 || $row_detfac["concafac"] == 28)
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
                $acum_cargosfin[$prov_remate] = $acum_cargosfin[$prov_remate] + $tot_cargosfin;
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + $tot_servicios;
                $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                break;

			case 116:
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
			case 119:
            case 120:
			case 121:
            case 135:
               $tot_comision = 0.00;
                    if ($row_cabecerafac["totcomis"] > 0.00) {
                        $tot_comision += $row_cabecerafac["totcomis"];
                    }
                $query_detfac = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp";
				$detfac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETFAC ".$query_detfac);
                $tot_public   = 0.00;
                $tot_cargosfin   = 0.00;
				$lotes21      = 0.00;
				$lotes105     = 0.00;
                $tot_servicios= 0.00;
                
                while ($row_detfac = mysqli_fetch_array($detfac)) {
                    if ($row_detfac["concafac"] == 20 || $row_detfac["concafac"] == 28 || $row_detfac["concafac"] == 33 || $row_detfac["concafac"] == 41)
                        $tot_public   += $row_detfac["neto"];
                    if ($row_detfac["concafac"] == 41)
				        $tot_cargosfin      += $row_detfac["neto"];
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
                $acum_cargosfin[$prov_remate] = $acum_cargosfin[$prov_remate] + ($tot_cargosfin * $signo);
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
                $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + ($lotes21 * $signo);
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + ($lotes105 * $signo);
				break;
			case 122:
			case 123:
			case 124:
 				if ($row_cabecerafac["totiva21"] != 0 && $row_cabecerafac["totiva105"] != 0)  {
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
					$tot_servicios   = $row_cabecerafac["totimp"];
                	$tot_comision    = $row_cabecerafac["totneto21"];
                	$lotes21         = 0.00;
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

            // Acumulo subtotales

            if ($tcomp ==  119 ||  $tcomp ==  120 || $tcomp == 121 || $tcomp == 135) {
                // resto Notas de Credito
                $acum_tot_neto21  = $acum_tot_neto21  - $lotes21;
                $acum_tot_neto105 = $acum_tot_neto105 - $lotes105;
                $acum_tot_iva     = $acum_tot_iva     - $tot_iva;
                $acum_totservicios  = $acum_totservicios  - $tot_servicios;
                $acum_total       = $acum_total       - $total;
                $acum_totcomis    = $acum_totcomis    - $tot_comision;
                $acum_totpublic   = $acum_totpublic    - $tot_public;
                $acum_totcargosfin   = $acum_totcargosfin    - $tot_cargosfin;
            }
            else {
                // Sumo Facturas y Notas de Debito
                $acum_tot_neto21  = $acum_tot_neto21  + $lotes21;
                $acum_tot_neto105 = $acum_tot_neto105 + $lotes105;
                $acum_tot_iva     = $acum_tot_iva     + $tot_iva;
                $acum_total       = $acum_total       + $total;
                $acum_totcomis    = $acum_totcomis    + $tot_comision;
                $acum_totservicios = $acum_totservicios + $tot_servicios;
                $acum_totpublic   = $acum_totpublic + $tot_public;
                $acum_totcargosfin   = $acum_totcargosfin + $tot_cargosfin;

            }
            $lotes21       *= $signo;
            $lotes105      *= $signo;
            $tot_iva       *= $signo;
            $tot_comision  *= $signo;
            $tot_public    *= $signo;
            $tot_cargosfin *= $signo;
            $tot_servicios *= $signo;
            $total         *= $signo;

            $lotes21       = number_format($lotes21, 2, ',','.');
            $lotes105      = number_format($lotes105, 2, ',','.');
            $tot_iva       = number_format($tot_iva, 2, ',','.');
            $tot_public    = number_format($tot_public, 2, ',','.');
            $tot_cargosfin = number_format($tot_cargosfin, 2, ',','.');
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
            $pdf->Cell(22,6,$tot_cargosfin,0,0,'R');
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
}	
    // Imprimo subtotales de la hoja la ultima vez de cada remate
		 // Imprimo subtotales de la hoja, uso otras variables porque el number_format
        // me jode los acumulados
        $f_acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
        $f_acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
        $f_acum_tot_iva     = number_format($acum_tot_iva, 2, ',','.');
        $f_acum_totpublic   = number_format($acum_totpublic, 2, ',','.');
        $f_acum_cargosfin   = number_format($acum_totcargosfin, 2, ',','.');
        $f_acum_total       = number_format($acum_total, 2, ',','.');
        $f_acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
        $f_acum_totservicios = number_format($acum_totservicios, 2, ',','.');
		/*
    $acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
    $acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
    $acum_tot_iva     = number_format($acum_tot_iva, 2, ',','.');
    $acum_totpublic  = number_format($acum_totpublic, 2, ',','.');
    $acum_totcargosfin  = number_format($acum_totcargosfin, 2, ',','.');
    $acum_total       = number_format($acum_total, 2, ',','.');
    $acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
    $acum_totservicios = number_format($acum_totservicios, 2, ',','.');
 */
    $pdf->SetY($valor_y);
    $pdf->Cell(86);
    $pdf->Cell(26,6,$f_acum_cargosfin,0,0,'R');
    $pdf->Cell(26,6,$f_acum_totpublic,0,0,'R');
    $pdf->Cell(26,6,$f_acum_totservicios,0,0,'R');
    $pdf->Cell(26,6,$f_acum_totcomis,0,0,'R');
    $pdf->Cell(26,6,$f_acum_tot_neto21,0,0,'R');
    $pdf->Cell(24,6,$f_acum_tot_neto105,0,0,'R');
    $pdf->Cell(24,6,$f_acum_tot_iva,0,0,'R');
    $pdf->Cell(24,6,"                       .",0,0,'R');
    $pdf->Cell(26,6,$f_acum_total,0,0,'R');
   
    // Acumulo con los demas remates
  /*
	$acum_totrem_neto21 += $acum_tot_neto21;
    $acum_totrem_neto105 += $acum_tot_neto105;
    $acum_totrem_iva += $acum_tot_iva;
    $acum_totrempublic += $acum_totpublic;
    $acum_totrem_total += $acum_total;
    $acum_totremcomis += $acum_totcomis;
    $acum_totremservicios += $acum_totservicios;
*/

// Voy a otra hoja e imprimo los titulos 
$pdf->AddPage();
$pdf->SetMargins(0.5, 0.5 , 0.5);
$pdf->SetFont('Arial','B',11);
$pdf->SetY(5);
$pdf->Cell(10);
$pdf->Cell(20,10,' ADRIAN MERCADO SUBASTAS S.A. ',0,0,'L');
$pdf->Cell(200);
$pagina = $pdf->PageNo();
$pdf->Cell(30,10,'Página : '.$pagina,0,0,'L');
$pdf->SetY(10);
$pdf->Cell(230);
$pdf->Cell(40,10,'Fecha   : '.$fechahoy,0,0,'L');
$pdf->SetY(15);
$pdf->Cell(110);
$pdf->Cell(60,10,' Ventas por Remate Consolidado  - Usuario cobranzas: '.$nombre_usu,0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(10);
$pdf->Cell(60,16,'       Provincia       ',1,0,'C');
$pdf->SetY(25);
$pdf->SetX(70);
$pdf->Cell(26,16,'  ',1,0,'L');
$pdf->SetY(22);
$pdf->SetX(70);
$pdf->Cell(25,16,'    Cargos',0,0,'L');
$pdf->SetY(30);
$pdf->SetX(70);
$pdf->Cell(26,16,'  financieros',0,0,'L');
$pdf->SetY(25);
$pdf->SetX(96);
$pdf->Cell(26,16,'   Publicidad ',1,0,'L');
$pdf->SetY(25);
$pdf->SetX(122);
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
$tot_ac_cargosfin = 0.00;
$tot_ac_lotes21 = 0.00;
$tot_ac_lotes105 = 0.00;

// Pongo los totales por jurisdiccion
for ($j = 1; $j < 25; $j++) {
	if ($acum_prov[$j] == 0)
		continue;
	
	$tot_ac_prov += $acum_prov[$j];
	$tot_ac_comis += $acum_comis[$j];
	$tot_ac_serv += $acum_serv[$j];
	$tot_ac_public += $acum_public[$j];
    $tot_ac_cargosfin += $acum_cargosfin[$j];
	$tot_ac_lotes21 += $acum_lotes21[$j];
	$tot_ac_lotes105 += $acum_lotes105[$j];

	
	$ac_prov = number_format($acum_prov[$j],2, ',','.');
	$ac_comis = number_format($acum_comis[$j],2, ',','.');
	$ac_serv = number_format($acum_serv[$j],2, ',','.');
	$ac_public = number_format($acum_public[$j],2, ',','.');
    $ac_cargosfin = number_format($acum_cargosfin[$j],2, ',','.');
	$ac_lotes21 = number_format($acum_lotes21[$j],2, ',','.');
	$ac_lotes105 = number_format($acum_lotes105[$j],2, ',','.');
	
	$pdf->SetY($valor_y);
	$pdf->Cell(10);
	$pdf->Cell(30,6,$descrip_prov[$j],0,0,'L');
	$pdf->SetX(72);
    $pdf->Cell(26,6,$ac_cargosfin,0,0,'R');
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
//echo "REMATE = ".$remate;
$pdf->Cell(30,6,'Totales Id '.$remate,0,0,'L');
$pdf->SetX(72);
$pdf->Cell(26,6,$tot_ac_cargosfin,0,0,'R');
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