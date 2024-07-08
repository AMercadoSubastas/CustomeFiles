<?php
ob_start();
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


$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$acum_prov=array();
$acum_public=array();
$acum_serv=array();
$acum_serv_inmob=array();
$acum_tasa=array();
$acum_tasac_muebles=array();
$acum_tasac_inmuebles=array();
$acum_comis=array();
$acum_lotes21=array();
$acum_lotes105=array();
$descrip_prov=array();
for ($j=1;$j < 30; $j++) {
	$query_prov = sprintf("SELECT * FROM provincias WHERE codnum = %s", $j);
	$prov = mysqli_query($amercado, $query_prov) or die ("ERROR LEYENDO PROVINCIAS 29");
		$row_prov = mysqli_fetch_assoc($prov);
		if ($row_prov["codnum"] != 2)
			$descrip_prov[$j] = $row_prov["descripcion"];
		else
			$descrip_prov[$j] = "CABA";
}
for ($j=1;$j < 30; $j++) {
	$acum_prov[$j] = 0.00;
	$acum_public[$j] = 0.00;
	$acum_serv[$j] = 0.00;
    $acum_serv_inmob[$j] = 0.00;
	$acum_comis[$j] = 0.00;
	$acum_lotes21[$j] = 0.00;
	$acum_lotes105[$j] = 0.00;
    $acum_tasa[$j] = 0.00;
    $acum_tasac_muebles[$j] = 0.00;
    $acum_tasac_inmuebles[$j] = 0.00;
}


$fechahoy = date("d-m-Y");
// Leo los renglones

// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO IMPUESTOS 51");
$row_Recordset2 = mysqli_fetch_assoc($impuestos);
$totalRows_Recordset2 = mysqli_num_rows($impuestos);
$impuestos->data_seek(1);
    $row = $impuestos->fetch_array();
// Calcular los porcentajes de impuestos
    $porc_iva105 = $row[1]/ 100 ."<br>";
    $impuestos->data_seek(0);
    $row = $impuestos->fetch_array();
    $porc_iva21 = $row[1] / 100;

// Leo la cabecera

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE (tcomp BETWEEN 51 AND 64 OR tcomp IN (89,92,93,94)) AND fecreg BETWEEN %s AND %s ORDER BY serie, tcomp, fecreg, nrodoc", $fecha_desde, $fecha_hasta);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC 68");

// Inicio el pdf con los datos de cabecera
$pdf=new FPDF('L','mm','Legal');

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
$pdf->Cell(130);
$pdf->Cell(20,10,' Ventas por concepto ',0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(3);
$pdf->Cell(20,16,'    Fecha',1,0,'L');
$pdf->Cell(30,16,' Nro.Comprobante',1,0,'L');
$pdf->Cell(40,16,'       Raz�n Social',1,0,'L');
$pdf->Cell(26,16,' Publicidad y ',1,0,'L');
$pdf->Cell(26,16,'    Comisiones ',1,0,'L');
$pdf->Cell(26,16,'  Tasaci�n ',1,0,'L');
$pdf->Cell(26,16,'   Tasaci�n',1,0,'L');
$pdf->Cell(26,16,'  Tasa Adm.',1,0,'L');
$pdf->Cell(26,16,'   Servicios ',1,0,'L');
$pdf->Cell(26,16,'    Servicios ',1,0,'L');
$pdf->Cell(24,16,' Provincia',1,0,'L');
$pdf->Cell(26,16,'Total Facturado',1,0,'L');
$pdf->Cell(15,16,'Remate',1,0,'L');
$pdf->SetY(28);
$pdf->Cell(93);
$pdf->Cell(26,16,'   Cargos',0,0,'L');
$pdf->SetY(31);
$pdf->Cell(93);
$pdf->Cell(26,16,' financieros',0,0,'L');
$pdf->SetY(28);
$pdf->Cell(146);
$pdf->Cell(26,16,'  Muebles',0,0,'L');
$pdf->SetY(28);
$pdf->Cell(172);
$pdf->Cell(26,16,'  Inmuebles',0,0,'L');
$pdf->SetY(28);
$pdf->Cell(223);
$pdf->Cell(26,16,'   subastas',0,0,'L');
$pdf->SetY(28);
$pdf->Cell(249);
$pdf->Cell(26,16,'  inmobiliaria',0,0,'L');

$valor_y = 45;
  
// Datos de los renglones
$i = 0;
$acum_tot_neto21  = 0;
$acum_tot_neto105 = 0;
$acum_tot_iva   = 0;
$acum_totpublic  = 0;
$acum_totservicios   = 0;
$acum_tot_tasa   = 0;
$acum_totservicios_inmob   = 0;
$acum_t_muebles = 0;
$acum_t_inmuebles = 0;
$acum_total       = 0;
$acum_totcomis    = 0;
$lotes21          = 0;
$lotes105         = 0;
$tot_iva          = 0;

while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
    $tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	$codrem     = $row_cabecerafac["codrem"];
	
	if ($tcomp !=  51 && $tcomp !=  52 && $tcomp !=  53 && $tcomp !=  54 && $tcomp !=  55 && $tcomp !=  56  &&  $tcomp != 57 && $tcomp != 58 && $tcomp != 59 && $tcomp != 60 && $tcomp !=  61 && $tcomp !=  62 && $tcomp !=  63 && $tcomp !=  64 && $tcomp !=  89 && $tcomp !=  92  && $tcomp !=  93 && $tcomp !=  94)
		continue;
	if ($tcomp ==  57 ||  $tcomp ==  58 ||  $tcomp ==  61 ||  $tcomp ==  62 ||  $tcomp ==  93  ) {
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
					while ($row_detfacprov = mysqli_fetch_array($detfacprov)) {
                        $secuencia = $row_detfacprov["codlote"];
                        $rematefac = $row_detfacprov["codrem"];
                        // LEO EL LOTE PARA VER SI TIENE DIR EXPO
                        $query_loteprov = "SELECT * FROM lotes WHERE codrem = $rematefac AND secuencia = $secuencia ";
                        $loteprov = mysqli_query($amercado, $query_loteprov) or die("ERROR LEYENDO LOTES 172 - ".$rematefac." - ".$secuencia);
						$row_loteprov = mysqli_fetch_assoc($loteprov);
						if ($row_loteprov["dir_secuencia"] == "") {
							$row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
							$prov_remate = $row_dir_exhib["codprov"];
                            //echo " paso 1  - ";
							break;
						}
						else {
							//UFA, TENGO QUE LEER LA DIR DE EXPO DE ESTE LOTE
							$secuexpo = $row_loteprov["dir_secuencia"];
							$query_dirprov = "SELECT * FROM dir_remates WHERE codrem = $rematefac AND secuencia = $secuexpo ";
                        	$dirprov = mysqli_query($amercado, $query_dirprov) or die("ERROR LEYENDO DIR EXPO 183");
							$row_dirprov = mysqli_fetch_assoc($dirprov);
							$prov_remate = $row_dirprov["codprov"];
                            if ($prov_remate == "")
                                $prov_remate = 1;
                            //echo "paso 2  -";
						}
					}
				}
				else {
					$row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
					$prov_remate = $row_dir_exhib["codprov"];
                    //echo " paso 3  - ";
				}
			}
			else {	
				//Leo el remate para conocer la provincia
				$query_remates = sprintf("SELECT * FROM remates WHERE ncomp = %s ", $codrem);
				$remates = mysqli_query($amercado, $query_remates) or die("ERROR LEYENDO REMATES 197");
				$row_remate = mysqli_fetch_assoc($remates);
				$prov_remate = $row_remate["codprov"];
                //echo " paso 4  - ";
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
                //echo " paso 5  - ";
                if ($prov_remate == "")
                    $prov_remate = 1;
 			}
			else {
				if ($serie == 29 || $serie == 30 || $serie == 37) {
					$prov_remate = 1;
                    //echo " paso 6  - ";
 				}
				else {
					$prov_remate = 2;
                    //echo " paso 7  - ";
 				}
			}

		}
       // echo "PROV_REMATE = ".$prov_remate."  - ";
		// Leo la descripci�n de la provincia
		$query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
		$provincias = mysqli_query($amercado, $query_provincias) or die ("ERROR LEYENDO PROVINCIAS 223 REM: ".$codrem);
		$row_provincias = mysqli_fetch_assoc($provincias);
		$desc_prov = substr($row_provincias["descripcion"],0,15);
		$tot_comision = 0.00;
        $tot_servicios_inmob = 0.00;
        $tot_tasa = 0.00;
        $tot_tasac_muebles = 0.00;
        $tot_tasac_inmuebles = 0.00;
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
			case 92:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 263");
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
				$detfac2 = mysqli_query($amercado, $query_detfac2) or die("ERROR LEYENDO DETFAC 283");
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
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND ( descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%')";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO DETFAC 302");
				if (mysqli_num_rows($detfac3) == 1) {
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
						$tot_iva21    = $row_cabecerafac["totiva21"];
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
			case 54:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 378");
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
				$detfac2 = mysqli_query($amercado, $query_detfac2) or die("ERROR LEYENDO DETFAC 389");
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
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO DETFAC 408");
				if (mysqli_num_rows($detfac3) == 1) {
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
						$tot_iva21    = $row_cabecerafac["totiva21"];
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
			case 93:
                if (($row_cabecerafac["ncomp"] == 1367  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1297  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 4  && $row_cabecerafac["tcomp"] == 93)) {
					// USAR ESTO PARA LOS CASOS ESPECIALES				
                      $cliente      = $row_cabecerafac["cliente"];
                      $tot_servicios = $row_cabecerafac["totimp"];
                      $tot_comision   = $row_cabecerafac["totneto21"];
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
                if (($row_cabecerafac["ncomp"] == 1367  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1652  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1812  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1806  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1677  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1297  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1626  && $row_cabecerafac["tcomp"] == 57)|| ($row_cabecerafac["ncomp"] == 4  && $row_cabecerafac["tcomp"] == 93) || ($row_cabecerafac["ncomp"] == 6  && $row_cabecerafac["tcomp"] == 93)) {
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
                      else {
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
					//Leo el cabfac de la factura original
					if (strpos($descri_det, "DEB")== FALSE && strpos($descri_det, "D�B")== FALSE) {
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
							$query_detfacfc2 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%PUBLIC%' ";
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
                 if (($row_cabecerafac["ncomp"] == 169 || $row_cabecerafac["ncomp"] == 164 || $row_cabecerafac["ncomp"] == 170 || $row_cabecerafac["ncomp"] == 173 || $row_cabecerafac["ncomp"] == 174 || $row_cabecerafac["ncomp"] == 175) && $row_cabecerafac["tcomp"] == 58) {
					// USAR ESTO PARA LOS CASOS ESPECIALES				
                      $cliente      = $row_cabecerafac["cliente"];
                      $tot_servicios =  $row_cabecerafac["totimp"];
                      $tot_comision   = $row_cabecerafac["totneto21"];
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
							$query_detfacfc2 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%PUBLIC%' ";
							$detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die("ERROR LEYENDO DETFAC 774");
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
							$query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%SERVICIO%'";
							$detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die("ERROR LEYENDO DETFAC 789");
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
						else if ($row_cabefac["tcomp"] == 60) {
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
                        else if ($row_cabefac["tcomp"] == 53) {
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
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                    $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				}
				else {
					$cliente      = $row_cabecerafac["cliente"];
					$lotes21      = $row_cabecerafac["totneto21"];
                    $lotes105     = $row_cabecerafac["totneto105"];
					$tot_public   = 0.00; 
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
				$cliente       = $row_cabecerafac["cliente"];
				$tot_servicios = $row_cabecerafac["totneto21"] ;
				$tot_neto21    = $row_cabecerafac["totneto21"] ;
				$tot_neto105   = $row_cabecerafac["totneto105"];
				$tot_comision  = $row_cabecerafac["totcomis"];
				$tot_iva21     = $row_cabecerafac["totiva21"];
				$tot_iva105    = $row_cabecerafac["totiva105"];
				$tot_iva       = $tot_iva21 + $tot_iva105;
				$tot_resol     = $desc_prov;
				$total         = $row_cabecerafac["totbruto"];
				$nroorig       = $row_cabecerafac["nrodoc"];
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
				break;
			case 64:
				$cliente         = $row_cabecerafac["cliente"];
				$tot_servicios   = $row_cabecerafac["totneto21"] ;
				$tot_neto21      = $row_cabecerafac["totneto21"] ;
				$tot_neto105     = $row_cabecerafac["totneto105"];
				$tot_comision    = $row_cabecerafac["totcomis"];
				$tot_iva21       = $row_cabecerafac["totiva21"];
				$tot_iva105      = $row_cabecerafac["totiva105"];
				$tot_iva         = $tot_iva21 + $tot_iva105;
				$tot_resol       = $desc_prov;
				$total           = $row_cabecerafac["totbruto"];
				$nroorig         = $row_cabecerafac["nrodoc"];
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
				break;
		}
		$estado = "P";

		// Acumulo subtotales
		if ($estado != "A") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93 ) {
				// resto Notas de Cr�dito
				$acum_tot_neto21  = $acum_tot_neto21  - $lotes21;
				$acum_tot_neto105 = $acum_tot_neto105 - $lotes105;
				$acum_tot_iva     = $acum_tot_iva     - $tot_iva;
				$acum_totservicios  = $acum_totservicios  - $tot_servicios;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
				$acum_totpublic   = $acum_totpublic    - $tot_public;
                $acum_totservicios_inmob    = $acum_totservicios_inmob   - $tot_servicios_inmob;
				$acum_tot_tasa   = $acum_tot_tasa    - $tot_tasa;
                $acum_t_muebles   = $acum_t_muebles    - $tot_tasac_muebles;
                $acum_t_inmuebles   = $acum_t_inmuebles    - $tot_tasac_inmuebles;
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
                $acum_totservicios_inmob    = $acum_totservicios_inmob   + $tot_servicios_inmob;
				$acum_tot_tasa   = $acum_tot_tasa    + $tot_tasa;
                $acum_t_muebles   = $acum_t_muebles    + $tot_tasac_muebles;
                $acum_t_inmuebles   = $acum_t_inmuebles    + $tot_tasac_inmuebles;
					
			}
			$lotes21       *= $signo;
			$lotes105      *= $signo;
			$tot_iva       *= $signo;
			$tot_comision  *= $signo;
			$tot_public    *= $signo;
			$tot_servicios *= $signo;
            $tot_servicios_inmob *= $signo;
            $tot_tasa      *= $signo;
            $tot_tasac_muebles   *= $signo;
            $tot_tasac_inmuebles *= $signo;
			$total         *= $signo;
			
			$lotes21       = number_format($lotes21, 2, ',','.');
			$lotes105      = number_format($lotes105, 2, ',','.');
			$tot_iva       = number_format($tot_iva, 2, ',','.');
			$tot_public    = number_format($tot_public, 2, ',','.');
			$tot_comision  = number_format($tot_comision, 2, ',','.');
			$tot_servicios = number_format($tot_servicios, 2, ',','.');
            $tot_servicios_inmob = number_format($tot_servicios_inmob, 2, ',','.');
            $tot_tasa      = number_format($tot_tasa, 2, ',','.');
            $tot_tasac_muebles = number_format($tot_tasac_muebles, 2, ',','.');
            $tot_tasac_inmuebles = number_format($tot_tasac_inmuebles, 2, ',','.');
			$total         = number_format($total, 2, ',','.');
			
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES 1002");
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
  			$pdf->Cell(23,6,$tot_public,0,0,'R');
            $pdf->Cell(26,6,$tot_comision,0,0,'R');
            $pdf->Cell(26,6,$tot_tasac_muebles,0,0,'R');
            $pdf->Cell(26,6,$tot_tasac_inmuebles,0,0,'R');
            $pdf->Cell(26,6,$tot_tasa,0,0,'R');
			$pdf->Cell(26,6,$tot_servicios,0,0,'R');
            $pdf->Cell(26,6,$tot_servicios_inmob,0,0,'R');
			$pdf->Cell(24,6,$tot_resol,0,0,'L');
  			$pdf->Cell(26,6,$total,0,0,'R');
			$pdf->Cell(15,6,$codrem,0,0,'R');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
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
        $f_acum_totservicios_inmob = number_format($acum_totservicios_inmob, 2, ',','.');
        $f_acum_tot_tasa = number_format($acum_tot_tasa, 2, ',','.');
        $f_acum_tot_tasac_muebles = number_format($acum_t_muebles, 2, ',','.');
        $f_acum_tot_tasac_inmuebles = number_format($acum_t_inmuebles, 2, ',','.');
		
		// ACUMULADOS PARCIALES DE PIE DE PAGINA
		$pdf->SetY($valor_y);
		$pdf->Cell(90);
		$pdf->Cell(26,6,$f_acum_totpublic,0,0,'R');
        $pdf->Cell(26,6,$f_acum_totcomis,0,0,'R');
        $pdf->Cell(26,6,$f_acum_tot_tasac_muebles,0,0,'R');
       	$pdf->Cell(26,6,$f_acum_tot_tasac_inmuebles,0,0,'R');
        $pdf->Cell(26,6,$f_acum_tot_tasa,0,0,'R');
        $pdf->Cell(26,6,$f_acum_totservicios,0,0,'R');
        $pdf->Cell(26,6,$f_acum_totservicios_inmob,0,0,'R');
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
        $pdf->Cell(130);
        $pdf->Cell(20,10,' Ventas por concepto ',0,0,'L');
        $pdf->SetFont('Arial','B',9);
        $pdf->SetY(25);
        $pdf->Cell(3);
        $pdf->Cell(20,16,'    Fecha',1,0,'L');
        $pdf->Cell(30,16,' Nro.Comprobante',1,0,'L');
        $pdf->Cell(40,16,'       Raz�n Social',1,0,'L');
        $pdf->Cell(26,16,' Publicidad y ',1,0,'L');
        $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
        $pdf->Cell(26,16,'  Tasaci�n ',1,0,'L');
        $pdf->Cell(26,16,'   Tasaci�n',1,0,'L');
        $pdf->Cell(26,16,'  Tasa Adm.',1,0,'L');
        $pdf->Cell(26,16,'   Servicios ',1,0,'L');
        $pdf->Cell(26,16,'    Servicios ',1,0,'L');
        $pdf->Cell(24,16,' Provincia',1,0,'L');
        $pdf->Cell(26,16,'Total Facturado',1,0,'L');
        $pdf->Cell(15,16,'Remate',1,0,'L');
        $pdf->SetY(28);
        $pdf->Cell(93);
        $pdf->Cell(26,16,'   Cargos',0,0,'L');
        $pdf->SetY(31);
        $pdf->Cell(93);
        $pdf->Cell(26,16,' financieros',0,0,'L');
        $pdf->SetY(28);
        $pdf->Cell(146);
        $pdf->Cell(26,16,'  Muebles',0,0,'L');
        $pdf->SetY(28);
        $pdf->Cell(172);
        $pdf->Cell(26,16,'  Inmuebles',0,0,'L');
        $pdf->SetY(28);
        $pdf->Cell(223);
        $pdf->Cell(26,16,'   subastas',0,0,'L');
        $pdf->SetY(28);
        $pdf->Cell(249);
        $pdf->Cell(26,16,'  inmobiliaria',0,0,'L');
		$valor_y = 45;
		// reinicio los contadores
		$i = 0;
		// IMPRIMO EL REGISTRO QUE TENGO LEIDO PORQUE SINO LO PIERDO
		if ($codrem != "" && $codrem > 0) {
			//Leo Direccion de exhibicion para saber la provincia
			$query_dir_exhib = sprintf("SELECT * FROM dir_remates WHERE codrem = %s ORDER BY codrem, secuencia", $codrem);
			$dir_exhib = mysqli_query($amercado, $query_dir_exhib) or die("ERROR LEYENDO DIR_REMATES 1098");
			if (mysqli_num_rows($dir_exhib) > 0) {
				//Leo los lotes a ver si alguno est� asignado a la direccion de exhibicion
				$row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
				$prov_remate = $row_dir_exhib["codprov"];
			}
			else {	
				//Leo el remate para conocer la provincia
				$query_remates = sprintf("SELECT * FROM remates WHERE ncomp = %s ", $codrem);
				$remates = mysqli_query($amercado, $query_remates) or die("ERROR LEYENDO REMATES 1107");
				$row_remate = mysqli_fetch_assoc($remates);
				$prov_remate = $row_remate["codprov"];
			}
		}
		else {
			if ($codrem != 0 && $codrem != "") {
				$cli = $row_cabecerafac["cliente"];
				// Leo el cliente
				$query_enti = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cli);
				$ent = mysqli_query($amercado, $query_enti) or die("ERROR LEYENDO ENTIDADES 1117");
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
		// Leo la descripci�n de la provincia
		$query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
		$provincias = mysqli_query($amercado, $query_provincias) or die ("ERROR LEYEMDO PROVINCIAS 1133");
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
			case 92:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 263");
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
				$detfac2 = mysqli_query($amercado, $query_detfac2) or die("ERROR LEYENDO DETFAC 283");
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
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND ( descrip not like '%SERVICIO%' and descrip not like '%PUBLIC%')";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO DETFAC 302");
				if (mysqli_num_rows($detfac3) == 1) {
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
						$tot_iva21    = $row_cabecerafac["totiva21"];
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
			case 54:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%SERVICIO%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die("ERROR LEYENDO DETFAC 378");
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
				$detfac2 = mysqli_query($amercado, $query_detfac2) or die("ERROR LEYENDO DETFAC 389");
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
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die("ERROR LEYENDO DETFAC 408");
				if (mysqli_num_rows($detfac3) == 1) {
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
						$tot_iva21    = $row_cabecerafac["totiva21"];
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
			case 93:
                if (($row_cabecerafac["ncomp"] == 1367  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1297  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 4  && $row_cabecerafac["tcomp"] == 93)) {
					// USAR ESTO PARA LOS CASOS ESPECIALES				
                      $cliente      = $row_cabecerafac["cliente"];
                      $tot_servicios = $row_cabecerafac["totimp"];
                      $tot_comision   = $row_cabecerafac["totneto21"];
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
                if (($row_cabecerafac["ncomp"] == 1367  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1652  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1812  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1806  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1677  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1297  && $row_cabecerafac["tcomp"] == 57) || ($row_cabecerafac["ncomp"] == 1626  && $row_cabecerafac["tcomp"] == 57)|| ($row_cabecerafac["ncomp"] == 4  && $row_cabecerafac["tcomp"] == 93) || ($row_cabecerafac["ncomp"] == 6  && $row_cabecerafac["tcomp"] == 93)) {
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
                      else {
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
					//Leo el cabfac de la factura original
					if (strpos($descri_det, "DEB")== FALSE && strpos($descri_det, "D�B")== FALSE) {
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
							$query_detfacfc2 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%PUBLIC%' ";
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
                 if (($row_cabecerafac["ncomp"] == 169 || $row_cabecerafac["ncomp"] == 164 || $row_cabecerafac["ncomp"] == 170 || $row_cabecerafac["ncomp"] == 173 || $row_cabecerafac["ncomp"] == 174 || $row_cabecerafac["ncomp"] == 175) && $row_cabecerafac["tcomp"] == 58) {
					// USAR ESTO PARA LOS CASOS ESPECIALES				
                      $cliente      = $row_cabecerafac["cliente"];
                      $tot_servicios =  $row_cabecerafac["totimp"];
                      $tot_comision   = $row_cabecerafac["totneto21"];
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
							$query_detfacfc2 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%PUBLIC%' ";
							$detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die("ERROR LEYENDO DETFAC 774");
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
							$query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%SERVICIO%'";
							$detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die("ERROR LEYENDO DETFAC 789");
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
						else if ($row_cabefac["tcomp"] == 60) {
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
                        else if ($row_cabefac["tcomp"] == 53) {
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
                    $tot_iva      = $tot_iva21 + $tot_iva105;
                    $tot_resol    = $desc_prov;
                    $total        = $row_cabecerafac["totbruto"];
                    $nroorig      = $row_cabecerafac["nrodoc"];
                    $acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
                    $acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
                    $acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
                    $acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				}
				else {
					$cliente      = $row_cabecerafac["cliente"];
					$lotes21      = $row_cabecerafac["totneto21"];
                    $lotes105     = $row_cabecerafac["totneto105"];
					$tot_public   = 0.00; 
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
				$cliente       = $row_cabecerafac["cliente"];
				$tot_servicios = $row_cabecerafac["totneto21"] ;
				$tot_neto21    = $row_cabecerafac["totneto21"] ;
				$tot_neto105   = $row_cabecerafac["totneto105"];
				$tot_comision  = $row_cabecerafac["totcomis"];
				$tot_iva21     = $row_cabecerafac["totiva21"];
				$tot_iva105    = $row_cabecerafac["totiva105"];
				$tot_iva       = $tot_iva21 + $tot_iva105;
				$tot_resol     = $desc_prov;
				$total         = $row_cabecerafac["totbruto"];
				$nroorig       = $row_cabecerafac["nrodoc"];
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
				break;
			case 64:
				$cliente         = $row_cabecerafac["cliente"];
				$tot_servicios   = $row_cabecerafac["totneto21"] ;
				$tot_neto21      = $row_cabecerafac["totneto21"] ;
				$tot_neto105     = $row_cabecerafac["totneto105"];
				$tot_comision    = $row_cabecerafac["totcomis"];
				$tot_iva21       = $row_cabecerafac["totiva21"];
				$tot_iva105      = $row_cabecerafac["totiva105"];
				$tot_iva         = $tot_iva21 + $tot_iva105;
				$tot_resol       = $desc_prov;
				$total           = $row_cabecerafac["totbruto"];
				$nroorig         = $row_cabecerafac["nrodoc"];
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + (($total - $tot_iva) * $signo);
				break;
		}
		$estado = "P";
		// Acumulo subtotales
		$estado = "P";

		// Acumulo subtotales
		if ($estado != "A") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93 ) {
				// resto Notas de Cr�dito
				$acum_tot_neto21  = $acum_tot_neto21  - $lotes21;
				$acum_tot_neto105 = $acum_tot_neto105 - $lotes105;
				$acum_tot_iva     = $acum_tot_iva     - $tot_iva;
				$acum_totservicios  = $acum_totservicios  - $tot_servicios;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
				$acum_totpublic   = $acum_totpublic    - $tot_public;
                $acum_totservicios_inmob    = $acum_totservicios_inmob   - $tot_servicios_inmob;
				$acum_tot_tasa   = $acum_tot_tasa    - $tot_tasa;
                $acum_t_muebles   = $acum_t_muebles    - $tot_tasac_muebles;
                $acum_t_inmuebles   = $acum_t_inmuebles    - $tot_tasac_inmuebles;
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
                $acum_totservicios_inmob    = $acum_totservicios_inmob   + $tot_servicios_inmob;
				$acum_tot_tasa   = $acum_tot_tasa    + $tot_tasa;
                $acum_t_muebles   = $acum_t_muebles    + $tot_tasac_muebles;
                $acum_t_inmuebles   = $acum_t_inmuebles    + $tot_tasac_inmuebles;
					
			}
			$lotes21       *= $signo;
			$lotes105      *= $signo;
			$tot_iva       *= $signo;
			$tot_comision  *= $signo;
			$tot_public    *= $signo;
			$tot_servicios *= $signo;
            $tot_servicios_inmob *= $signo;
            $tot_tasa      *= $signo;
            $tot_tasac_muebles   *= $signo;
            $tot_tasac_inmuebles *= $signo;
			$total         *= $signo;
			
			$lotes21       = number_format($lotes21, 2, ',','.');
			$lotes105      = number_format($lotes105, 2, ',','.');
			$tot_iva       = number_format($tot_iva, 2, ',','.');
			$tot_public    = number_format($tot_public, 2, ',','.');
			$tot_comision  = number_format($tot_comision, 2, ',','.');
			$tot_servicios = number_format($tot_servicios, 2, ',','.');
            $tot_servicios_inmob = number_format($tot_servicios_inmob, 2, ',','.');
            $tot_tasa      = number_format($tot_tasa, 2, ',','.');
            $tot_tasac_muebles = number_format($tot_tasac_muebles, 2, ',','.');
            $tot_tasac_inmuebles = number_format($tot_tasac_inmuebles, 2, ',','.');
			$total         = number_format($total, 2, ',','.');
			
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES 1002");
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
  			$pdf->Cell(23,6,$tot_public,0,0,'R');
            $pdf->Cell(26,6,$tot_comision,0,0,'R');
            $pdf->Cell(26,6,$tot_tasac_muebles,0,0,'R');
            $pdf->Cell(26,6,$tot_tasac_inmuebles,0,0,'R');
            $pdf->Cell(26,6,$tot_tasa,0,0,'R');
			$pdf->Cell(26,6,$tot_servicios,0,0,'R');
            $pdf->Cell(26,6,$tot_servicios_inmob,0,0,'R');
			$pdf->Cell(24,6,$tot_resol,0,0,'L');
  			$pdf->Cell(26,6,$total,0,0,'R');
			$pdf->Cell(15,6,$codrem,0,0,'R');
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
	}
}

// Imprimo subtotales de la hoja la �ltima vez
$acum_tot_neto21   = number_format($acum_tot_neto21, 2, ',','.');
$acum_tot_neto105  = number_format($acum_tot_neto105, 2, ',','.');
$acum_tot_iva      = number_format($acum_tot_iva, 2, ',','.');
$acum_totpublic    = number_format($acum_totpublic, 2, ',','.');
$acum_total        = number_format($acum_total, 2, ',','.');
$acum_totcomis     = number_format($acum_totcomis, 2, ',','.');
$acum_totservicios = number_format($acum_totservicios, 2, ',','.');
$acum_totservicios_inmob = number_format($acum_totservicios_inmob, 2, ',','.');
$acum_t_muebles = number_format($acum_t_muebles, 2, ',','.');
$acum_t_inmuebles = number_format($acum_t_inmuebles, 2, ',','.');
$acum_tot_tasa = number_format($acum_tot_tasa, 2, ',','.');
		
$pdf->SetY($valor_y);
$pdf->Cell(90);
$pdf->Cell(26,6,$acum_totpublic,0,0,'R');
$pdf->Cell(26,6,$acum_totcomis,0,0,'R');
$pdf->Cell(26,6,$acum_t_muebles,0,0,'R');
$pdf->Cell(26,6,$acum_t_inmuebles,0,0,'R');
$pdf->Cell(26,6,$acum_tot_tasa,0,0,'R');
$pdf->Cell(26,6,$acum_totservicios,0,0,'R');
$pdf->Cell(26,6,$acum_totservicios_inmob,0,0,'R');
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
$pdf->Cell(130);
$pdf->Cell(20,10,' Ventas por concepto ',0,0,'L');
$pdf->SetFont('Arial','B',9);
$pdf->SetY(25);
$pdf->Cell(3);
//$pdf->Cell(20,16,'    Fecha',1,0,'L');
//$pdf->Cell(30,16,' Nro.Comprobante',1,0,'L');
//$pdf->Cell(40,16,'       Raz�n Social',1,0,'L');
$pdf->Cell(40,16,' Provincia',1,0,'L');
$pdf->Cell(26,16,' Publicidad y ',1,0,'L');
$pdf->Cell(26,16,'    Comisiones ',1,0,'L');
$pdf->Cell(26,16,'  Tasaci�n ',1,0,'L');
$pdf->Cell(26,16,'   Tasaci�n',1,0,'L');
$pdf->Cell(26,16,'  Tasa Adm.',1,0,'L');
$pdf->Cell(26,16,'   Servicios ',1,0,'L');
$pdf->Cell(26,16,'    Servicios ',1,0,'L');

$pdf->Cell(26,16,'Total Facturado',1,0,'L');
//$pdf->Cell(15,16,'Remate',1,0,'L');
$pdf->SetY(28);
$pdf->Cell(43);
$pdf->Cell(26,16,'   Cargos',0,0,'L');
$pdf->SetY(31);
$pdf->Cell(43);
$pdf->Cell(26,16,' financieros',0,0,'L');
$pdf->SetY(28);
$pdf->Cell(96);
$pdf->Cell(26,16,'  Muebles',0,0,'L');
$pdf->SetY(28);
$pdf->Cell(122);
$pdf->Cell(26,16,'  Inmuebles',0,0,'L');
$pdf->SetY(28);
$pdf->Cell(173);
$pdf->Cell(26,16,'   subastas',0,0,'L');
$pdf->SetY(28);
$pdf->Cell(199);
$pdf->Cell(26,16,'  inmobiliaria',0,0,'L');
$valor_y         = 45;
$tot_ac_prov     = 0.00;
$tot_ac_comis    = 0.00;
$tot_ac_tasa     = 0.00;
$tot_ac_tasac_muebles    = 0.00;
$tot_ac_tasac_inmuebles  = 0.00;
$tot_ac_serv     = 0.00;
$tot_ac_serv_inmob    = 0.00;
$tot_ac_public   = 0.00;
$tot_ac_lotes21  = 0.00;
$tot_ac_lotes105 = 0.00;
// Pongo los totales por jurisdicci�n
for ($j = 1; $j < 30; $j++) {
	if ($acum_prov[$j] == 0 || !isset($acum_prov[$j]))
		continue;

	$tot_ac_comis += $acum_comis[$j];
	$tot_ac_serv += $acum_serv[$j];
    $tot_ac_serv_inmob += $acum_serv_inmob[$j];
    $tot_ac_tasa += $acum_tasa[$j];
    $tot_ac_tasac_muebles += $acum_tasac_muebles[$j];
    $tot_ac_tasac_inmuebles += $acum_tasac_inmuebles[$j];
	$tot_ac_public += $acum_public[$j];
	$tot_ac_lotes21 += $acum_lotes21[$j];
	$tot_ac_lotes105 += $acum_lotes105[$j];
    $tot_ac_prov = $tot_ac_comis + $tot_ac_serv + $tot_ac_public + $tot_ac_tasa + $tot_ac_tasac_muebles + $tot_ac_tasac_inmuebles + $tot_ac_serv_inmob;
	
    $ac_prov = $acum_comis[$j] + $acum_serv[$j] + $acum_public[$j] + $acum_tasa[$j] + $acum_tasac_muebles[$j] + $acum_tasac_inmuebles[$j] + $acum_serv_inmob[$j];

    $ac_prov     = number_format($ac_prov,2, ',','.');
	$ac_comis    = number_format($acum_comis[$j],2, ',','.');
	$ac_serv     = number_format($acum_serv[$j],2, ',','.');
    $ac_serv_inmob     = number_format($acum_serv_inmob[$j],2, ',','.');
    $ac_tasa     = number_format($acum_tasa[$j],2, ',','.');
    $ac_tasac_muebles     = number_format((double) $acum_tasac_muebles[$j],2, ',','.');
    $ac_tasac_inmuebles     = number_format((double)$acum_tasac_inmuebles[$j],2, ',','.');
	$ac_public   = number_format($acum_public[$j],2, ',','.');
	$ac_lotes21  = number_format($acum_lotes21[$j],2, ',','.');
	$ac_lotes105 = number_format($acum_lotes105[$j],2, ',','.');
	
	$pdf->SetY($valor_y);
	$pdf->Cell(10);
	$pdf->Cell(30,6,$descrip_prov[$j],0,0,'L');
	//$pdf->SetX(32);
	$pdf->Cell(26,6,$ac_public,0,0,'R');
    $pdf->Cell(26,6,$ac_comis,0,0,'R');
    $pdf->Cell(26,6,$ac_tasac_muebles,0,0,'R');
    $pdf->Cell(26,6,$ac_tasac_inmuebles,0,0,'R');
    $pdf->Cell(26,6,$ac_tasa,0,0,'R');
	$pdf->Cell(26,6,$ac_serv,0,0,'R');
	$pdf->Cell(26,6,$ac_serv_inmob,0,0,'R');
	//$pdf->Cell(26,6,$ac_lotes21,0,0,'R');
	//$pdf->Cell(26,6,$ac_lotes105,0,0,'R');
	$pdf->Cell(30,6,$ac_prov,0,0,'R');
	
	$valor_y = $valor_y + 6;
}
$tot_ac_prov     = number_format($tot_ac_prov,2, ',','.');
$tot_ac_comis    = number_format($tot_ac_comis,2, ',','.');
$tot_ac_serv     = number_format($tot_ac_serv,2, ',','.');
$tot_ac_serv_inmob     = number_format($tot_ac_serv_inmob,2, ',','.');
$tot_ac_public   = number_format($tot_ac_public,2, ',','.');
$tot_ac_tasa  = number_format($tot_ac_tasa,2, ',','.');
$tot_ac_tasac_muebles     = number_format($tot_ac_tasac_muebles,2, ',','.');
$tot_ac_tasac_inmuebles     = number_format($tot_ac_tasac_inmuebles,2, ',','.');

$pdf->SetY($valor_y);
$pdf->Cell(10);
$pdf->Cell(30,6,"TOTALES    ",0,0,'L');
//$pdf->SetX(22);
$pdf->Cell(26,6,$tot_ac_public,0,0,'R');
$pdf->Cell(26,6,$tot_ac_comis,0,0,'R');
$pdf->Cell(26,6,$tot_ac_tasac_muebles,0,0,'R');
$pdf->Cell(26,6,$tot_ac_tasac_inmuebles,0,0,'R');
$pdf->Cell(26,6,$tot_ac_tasa,0,0,'R');
$pdf->Cell(26,6,$tot_ac_serv,0,0,'R');
$pdf->Cell(26,6,$tot_ac_serv_inmob,0,0,'R');
//$pdf->Cell(26,6,$tot_ac_comis,0,0,'R');
//$pdf->Cell(26,6,$tot_ac_lotes21,0,0,'R');
//$pdf->Cell(26,6,$tot_ac_lotes105,0,0,'R');
$pdf->Cell(30,6,$tot_ac_prov,0,0,'R');

mysqli_close($amercado);
ob_end_clean();
$pdf->Output();
?> 