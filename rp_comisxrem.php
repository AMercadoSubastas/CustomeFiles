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
/*
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";
�*/
$acum_prov=array();
$acum_public=array();
$acum_serv=array();
$acum_comis=array();
$acum_lotes21=array();
$acum_lotes105=array();
$descrip_prov=array();

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


$fechahoy = date("d-m-Y");
// Leo los renglones

// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die(mysqli_error($amercado));
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

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE (tcomp BETWEEN 51 AND 64 OR tcomp = 89) AND codrem = %s ORDER BY fecreg, nrodoc, tcomp", $remate);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die(mysqli_error($amercado));

// Inicio el pdf con los datos de cabecera
  $pdf=new FPDF('P','mm','Legal');
  
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
  $pdf->Cell(60);
  $pdf->Cell(20,10,' Comisiones del Remate '.$remate,0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  $pdf->Cell(20,16,'    Fecha',1,0,'L');
  $pdf->Cell(28,16,' Nro.Factura',1,0,'L');
  $pdf->Cell(42,16,'       Raz�n Social',1,0,'L');
  $pdf->Cell(22,16,'     CUIT',1,0,'L');
  $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
  $pdf->Cell(26,16,'Total Facturado',1,0,'L');
  $pdf->Cell(15,16,'Remate',1,0,'L');
  /*
  $pdf->SetY(34);
  $pdf->Cell(114);
  $pdf->Cell(23,8,'    Exentos ',0,0,'L');
  $pdf->Cell(26,8,'             ',0,0,'L');
  $pdf->Cell(26,8,' Gravados 21% ',0,0,'L');
  $pdf->Cell(26,8,' Gravados 10,5%',0,0,'L');
  $pdf->Cell(24,8,' Fiscal 21%',0,0,'L');
  $pdf->Cell(24,8,' Fiscal 10,5%',0,0,'L');
  */
  
  $valor_y = 45;
  
// Datos de los renglones
$i = 0;

$acum_total       = 0;
$acum_totcomis    = 0;


while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	$codrem     = $row_cabecerafac["codrem"];
	/*
	if ($tcomp == 57 && $ncomp == 457)
		echo "APARECIO NCRED = ".$ncomp."  ";
	*/
	if ($tcomp !=  51 && $tcomp !=  52 && $tcomp !=  53 && $tcomp !=  54 && $tcomp !=  55 && $tcomp !=  56  &&  $tcomp != 57 && $tcomp != 58 && $tcomp != 59 && $tcomp != 60 && $tcomp !=  61 && $tcomp !=  62 && $tcomp !=  63 && $tcomp !=  64 && $tcomp != 89)
		continue;
	if ($tcomp ==  57 ||  $tcomp ==  58 ||  $tcomp ==  61 ||  $tcomp ==  62  ) {
		$tc = "NC-";
		$signo = -1;
	}
	
	elseif ($tcomp == 59 ||  $tcomp == 60 ||  $tcomp ==  63 ||  $tcomp ==  64){
		$tc = "ND-";
		$signo = 1;
	}
	else {
		$tc = "FC-";
		$signo = 1;
	}
	//echo "TCOMP = ".$tcomp."   NCOMP = ".$ncomp."\n";
	if ($i <= 22) {
		if ($codrem != "" && $codrem > 0) {
			//Leo Direccion de exhibicion para saber la provincia
			$query_dir_exhib = sprintf("SELECT * FROM dir_remates WHERE codrem = %s ORDER BY codrem, secuencia", $codrem);
			$dir_exhib = mysqli_query($amercado, $query_dir_exhib) or die(mysqli_error($amercado));
			if (mysqli_num_rows($dir_exhib) > 0) {
				//Leo los lotes a ver si alguno est� asignado a la direccion de exhibicion
				$row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
				$prov_remate = $row_dir_exhib["codprov"];
			}
			else {	
				//Leo el remate para conocer la provincia
				$query_remates = sprintf("SELECT * FROM remates WHERE ncomp = %s ", $codrem);
				$remates = mysqli_query($amercado, $query_remates) or die(mysqli_error($amercado));
				$row_remate = mysqli_fetch_assoc($remates);
				$prov_remate = $row_remate["codprov"];
			}
		}
		else {
			if ($codrem != 0 && $codrem != "") {
				$cli = $row_cabecerafac["cliente"];
				// Leo el cliente
				$query_enti = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cli);
				$ent = mysqli_query($amercado, $query_enti) or die(mysqli_error($amercado));
				$row_enti = mysqli_fetch_assoc($ent);
				$prov_remate   = $row_enti["codprov"];
			}
			else {
				if ($serie == 29 || $serie == 30) {
					$prov_remate = 1;
				}
				else {
					$prov_remate = 2;
				}
			}

		}
		// Leo la descripci�n de la provincia
		$query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
		$provincias = mysqli_query($amercado, $query_provincias) or die (mysqli_error($amercado));
		$row_provincias = mysqli_fetch_assoc($provincias);
		$desc_prov = substr($row_provincias["descripcion"],0,17);
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
				$cliente      = $row_cabecerafac["cliente"];
				$lotes21      = $row_cabecerafac["totneto21"] ;
				$lotes105     = $row_cabecerafac["totneto105"];
				$tot_comision = $row_cabecerafac["totcomis"];
				$tot_public   = 0.00;
				$tot_servicios= 0.00;
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				$acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
				$acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
				break;
			case 52:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%COMISION%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = 0.00;
					//$tot_servicios= 0.00;
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
					
				}
				
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%COMISION%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac3) == 1) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_comision = 0.00;
					//$tot_public   = $row_detfac1["neto"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
						$tot_iva21    = (($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21);
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
				//$tot_servicios= 0.00;
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				$acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
				$acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
				break;
			case 54:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%COMISION%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = 0.00;
					//$tot_servicios= 0.00;
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
					
				}
				
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%COMISION%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac3) == 1) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_comision = 0.00;
					//$tot_public   = $row_detfac1["neto"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
						$tot_iva21    = (($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21);
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
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%COMISION%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = 0.00;
					//$tot_servicios= 0.00;
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
					
				}
				
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%COMISION%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac3) > 0) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_comision = 0.00;
					//$tot_public   = $row_detfac1["neto"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%COMISION%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = 0.00;
					//$tot_servicios= 0.00;
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
					
				}
				
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%COMISION%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac3) > 0) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_comision = 0.00;
					//$tot_public   = $row_detfac1["neto"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
				$cliente      = $row_cabecerafac["cliente"];
				//Deber�a revisar detfac para ver si la fc es tipo 51 o 52
				// DETFAC DE LA NOTA DE CREDITO
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%A0004%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
				
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					
					$descri_det = $row_detfac1["descrip"];
					//echo "DESCRI_DET = ".$descri_det."    "."TCOMP =  ".$tcomp."  NCOMP = ".$ncomp."  ";
					$prim_char = strpos($descri_det, "A0004");
					$nrodoc_rel = substr($descri_det, $prim_char + 10, 4);
					//echo "NRODOC_REL = ".$nrodoc_rel."  ";
					//Leo el cabfac de la factura original
					$query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (51,52) AND ncomp = $nrodoc_rel";
					$cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA".$nrodoc_rel);
					// Y con estos datos leo el detfac correspondiente si es tipo 52:

					if ($row_cabefac = mysqli_fetch_assoc($cabefac)) {
						if ($row_cabefac["tcomp"] == 52) {
							$tcomp_cabefac = $row_cabefac["tcomp"];
							$ncomp_cabefac = $row_cabefac["ncomp"];
							//echo " TCOMP_CABEFAC = ".$tcomp_cabefac."  NCOMP_CABEFAC = ".$ncomp_cabefac."  ";
							//Debo leer los renglones de detfac para ver de que se trata
							$query_detfacfc1 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%INMOB%' OR descrip like '%GASTOS%' OR descrip like '%SERVICIO%')";
							$detfacfc1 = mysqli_query($amercado, $query_detfacfc1) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc1) > 0) {
								$tot_servicios = 0.00;
								while($row_detfacfc1 = mysqli_fetch_array($detfacfc1)) {
									//$row_detfacfc1 = mysqli_fetch_assoc($detfacfc1);
									$tot_servicios += $row_detfacfc1["neto"]; // $row_detfacfc1["neto"];
									$cliente      = $row_cabecerafac["cliente"];
									//$tot_public   = 0.00;
									//$tot_servicios= 0.00;
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
							$detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc2) > 0) {
								$row_detfacfc2 = mysqli_fetch_assoc($detfacfc2);
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_comision = 0.00;
								$tot_public   = $row_cabecerafac["totneto21"];
								//$tot_servicios= 0.00;
								$lotes21      = 0.00;
								$lotes105     = 0.00;
								$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
								$tot_iva105   = $row_cabecerafac["totiva105"];
								$tot_iva      = $tot_iva21 + $tot_iva105;
								$tot_resol    = $desc_prov;
								$total        = $row_cabecerafac["totbruto"];
								$nroorig      = $row_cabecerafac["nrodoc"];

							}

							$query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%COMISION%'";
							$detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc3) > 0) {
								$row_detfacfc3 = mysqli_fetch_assoc($detfacfc3);
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_comision = 0.00;
								//$tot_public   = $row_detfac1["neto"];
								$tot_comision = $row_detfacfc3["neto"];
								//$row_detfacfc3["neto"];
								$lotes21      = 0.00;
								$lotes105     = 0.00;
								$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
								$tot_iva105   = $row_cabecerafac["totiva105"];
								$tot_iva      = $tot_iva21 + $tot_iva105;
								$tot_resol    = $desc_prov;
								$total        = $row_cabecerafac["totbruto"];
								$nroorig      = $row_cabecerafac["nrodoc"];

							}
						}
						else {
							$lotes21      = $row_cabecerafac["totneto21"] ;
							$lotes105     = $row_cabecerafac["totneto105"];
							$tot_comision = $row_cabecerafac["totcomis"];
							$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
/*
				if ($tcomp == 57 && $ncomp == 457) {
					echo "APARECIO NCRED = ".$ncomp."  ";
					echo "TOT_COMISION = ".$tot_comision."  TOT_PUBLIC = ".$tot_public."  TOT_SERVICIOS = ".$tot_servicios."  LOTES21 =  ".$lotes21."  LOTES105 = ".$lotes105." TOTAL =  ".$total."  TOT_IVA =  ".$tot_iva."  ";
				}
				*/
				break;
			case 58:
				$cliente      = $row_cabecerafac["cliente"];
				//Deber�a revisar detfac para ver si la fc es tipo 53 o 54
				// DETFAC DE LA NOTA DE CREDITO
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%B0004%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
				
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					
					$descri_det = $row_detfac1["descrip"];
					//echo "DESCRI_DET = ".$descri_det."    "."TCOMP =  ".$tcomp."  NCOMP = ".$ncomp."  ";
					$prim_char = strpos($descri_det, "B0004");
					$nrodoc_rel = substr($descri_det, $prim_char + 10, 4);
					//echo "NRODOC_REL = ".$nrodoc_rel."  ";
					//Leo el cabfac de la factura original
					$query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (53,54) AND ncomp = $nrodoc_rel";
					$cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA".$nrodoc_rel);
					// Y con estos datos leo el detfac correspondiente si es tipo 52:

					if ($row_cabefac = mysqli_fetch_assoc($cabefac)) {
						if ($row_cabefac["tcomp"] == 54) {
							$tcomp_cabefac = $row_cabefac["tcomp"];
							$ncomp_cabefac = $row_cabefac["ncomp"];
							//echo " TCOMP_CABEFAC = ".$tcomp_cabefac."  NCOMP_CABEFAC = ".$ncomp_cabefac."  ";
							//Debo leer los renglones de detfac para ver de que se trata
							$query_detfacfc1 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%INMOB%' OR descrip like '%GASTOS%'  OR descrip like '%SERVICIO%')";
							$detfacfc1 = mysqli_query($amercado, $query_detfacfc1) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc1) > 0) {
								$row_detfacfc1 = mysqli_fetch_assoc($detfacfc1);
								$tot_servicios = $row_detfacfc1["neto"];
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_public   = 0.00;
								//$tot_servicios= 0.00;
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
							$detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc2) > 0) {
								$row_detfacfc2 = mysqli_fetch_assoc($detfacfc2);
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_comision = 0.00;
								$tot_public   = $row_detfacfc2["neto"];
								//$tot_servicios= 0.00;
								$lotes21      = 0.00;
								$lotes105     = 0.00;
								$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
								$tot_iva105   = $row_cabecerafac["totiva105"];
								$tot_iva      = $tot_iva21 + $tot_iva105;
								$tot_resol    = $desc_prov;
								$total        = $row_cabecerafac["totbruto"];
								$nroorig      = $row_cabecerafac["nrodoc"];

							}

							$query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%COMISION%'";
							$detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc3) > 0) {
								$row_detfacfc3 = mysqli_fetch_assoc($detfacfc3);
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_comision = 0.00;
								//$tot_public   = $row_detfac1["neto"];
								$tot_comision = $row_detfacfc3["neto"];
								$lotes21      = 0.00;
								$lotes105     = 0.00;
								$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
								$tot_iva105   = $row_cabecerafac["totiva105"];
								$tot_iva      = $tot_iva21 + $tot_iva105;
								$tot_resol    = $desc_prov;
								$total        = $row_cabecerafac["totbruto"];
								$nroorig      = $row_cabecerafac["nrodoc"];

							}
						}
						else {
							$lotes21      = $row_cabecerafac["totneto21"] ;
							$lotes105     = $row_cabecerafac["totneto105"];
							$tot_comision = $row_cabecerafac["totcomis"];
							$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
				break;
			case 59:
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
				break;
			case 60:
				$cliente      = $row_cabecerafac["cliente"];
				$lotes21   = $row_cabecerafac["totneto21"] ;
				$lotes105  = $row_cabecerafac["totneto105"];
				$tot_comision = $row_cabecerafac["totcomis"];
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
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
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				break;
		}
		$estado = "P";
		/*
		if ($total == 0 && $tot_servicios == 0 && $lotes21 == 0 && $lotes105 == 0 && $tot_comision == 0 && $tot_iva == 0)
			continue;
		*/
		
		// Acumulo subtotales
		if ($estado != "A") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 ) {
				// resto Notas de Cr�dito
				//$acum_tot_neto21  = $acum_tot_neto21  - $lotes21;
				//$acum_tot_neto105 = $acum_tot_neto105 - $lotes105;
				//$acum_tot_iva     = $acum_tot_iva     - $tot_iva;
				//$acum_totservicios  = $acum_totservicios  - $tot_servicios;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
				//$acum_totpublic   = $acum_totpublic    - $tot_public;
			}
			else {
				// Sumo Facturas y Notas de D�bito
				//$acum_tot_neto21  = $acum_tot_neto21  + $lotes21;
				//$acum_tot_neto105 = $acum_tot_neto105 + $lotes105;
				//$acum_tot_iva     = $acum_tot_iva     + $tot_iva;
				$acum_total       = $acum_total       + $total;
				$acum_totcomis    = $acum_totcomis    + $tot_comision;
				//$acum_totservicios = $acum_totservicios + $tot_servicios;
				//$acum_totpublic   = $acum_totpublic + $tot_public;
					
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
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  	
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
			$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(26,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,$nom_cliente,0,0,'L');
  			$pdf->Cell(22,6,$cuit_cliente,0,0,'L');
  			//$pdf->Cell(23,6,$tot_public,0,0,'R');
			//$pdf->Cell(23,6,$tot_servicios,0,0,'R');
			$pdf->Cell(26,6,$tot_comision,0,0,'R');
  			//$pdf->Cell(26,6,$lotes21,0,0,'R');
  			//$pdf->Cell(26,6,$lotes105,0,0,'R');
  			//$pdf->Cell(24,6,$tot_iva,0,0,'R');
  			//$pdf->Cell(24,6,$tot_resol,0,0,'L');
  			$pdf->Cell(26,6,$total,0,0,'R');
			$pdf->Cell(15,6,$codrem,0,0,'R');
		
		
			$i = $i + 1;
			$valor_y = $valor_y + 6;
		}
	}
	else {
		// Imprimo subtotales de la hoja, uso otras variables porque el number_format
		// me jode los acumulados
		//$f_acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
		//$f_acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
		//$f_acum_tot_iva     = number_format($acum_tot_iva, 2, ',','.');
		//$f_acum_totpublic   = number_format($acum_totpublic, 2, ',','.');
		$f_acum_total       = number_format($acum_total, 2, ',','.');
		$f_acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
		//$f_acum_totservicios = number_format($acum_totservicios, 2, ',','.');
		
		// ACUMULADOS PARCIALES DE PIE DE PAGINA
		$pdf->SetY($valor_y);
		$pdf->Cell(112);
		//$pdf->Cell(26,6,$f_acum_totpublic,0,0,'R');
		//$pdf->Cell(26,6,$f_acum_totservicios,0,0,'R');
		$pdf->Cell(26,6,$f_acum_totcomis,0,0,'R');
  		//$pdf->Cell(26,6,$f_acum_tot_neto21,0,0,'R');
  		//$pdf->Cell(24,6,$f_acum_tot_neto105,0,0,'R');
  		//$pdf->Cell(24,6,$f_acum_tot_iva,0,0,'R');
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
		$pdf->Cell(20,10,' Comisiones del Remate '.$remate,0,0,'L');
	  $pdf->SetFont('Arial','B',9);
	  $pdf->SetY(25);
	  $pdf->Cell(3);
	  $pdf->Cell(20,16,'    Fecha',1,0,'L');
	  $pdf->Cell(28,16,' Nro.Factura',1,0,'L');
	  $pdf->Cell(42,16,'       Raz�n Social',1,0,'L');
	  $pdf->Cell(22,16,'     CUIT',1,0,'L');
	  $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
	  $pdf->Cell(26,16,'Total Facturado',1,0,'L');
	  $pdf->Cell(15,16,'Remate',1,0,'L');
	  /*
	  $pdf->SetY(34);
	  $pdf->Cell(114);
	  $pdf->Cell(23,8,'    Exentos ',0,0,'L');
	  $pdf->Cell(26,8,'             ',0,0,'L');
	  $pdf->Cell(26,8,' Gravados 21% ',0,0,'L');
	  $pdf->Cell(26,8,' Gravados 10,5%',0,0,'L');
	  $pdf->Cell(24,8,' Fiscal 21%',0,0,'L');
	  $pdf->Cell(24,8,' Fiscal 10,5%',0,0,'L');
	  */
		
				
  
		$valor_y = 45;
		// reinicio los contadores
		
		$i = 0;
		// IMPRIMO EL REGISTRO QUE TENGO LEIDO PORQUE SINO LO PIERDO
		if ($codrem != "" && $codrem > 0) {
			//Leo Direccion de exhibicion para saber la provincia
			$query_dir_exhib = sprintf("SELECT * FROM dir_remates WHERE codrem = %s ORDER BY codrem, secuencia", $codrem);
			$dir_exhib = mysqli_query($amercado, $query_dir_exhib) or die(mysqli_error($amercado));
			if (mysqli_num_rows($dir_exhib) > 0) {
				//Leo los lotes a ver si alguno est� asignado a la direccion de exhibicion
				$row_dir_exhib = mysqli_fetch_assoc($dir_exhib);
				$prov_remate = $row_dir_exhib["codprov"];
			}
			else {	
				//Leo el remate para conocer la provincia
				$query_remates = sprintf("SELECT * FROM remates WHERE ncomp = %s ", $codrem);
				$remates = mysqli_query($amercado, $query_remates) or die(mysqli_error($amercado));
				$row_remate = mysqli_fetch_assoc($remates);
				$prov_remate = $row_remate["codprov"];
			}
		}
		else {
			if ($codrem != 0 && $codrem != "") {
				$cli = $row_cabecerafac["cliente"];
				// Leo el cliente
				$query_enti = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cli);
				$ent = mysqli_query($amercado, $query_enti) or die(mysqli_error($amercado));
				$row_enti = mysqli_fetch_assoc($ent);
				$prov_remate   = $row_enti["codprov"];
			}
			else {
				if ($serie == 29 || $serie == 30) {
					$prov_remate = 1;
				}
				else {
					$prov_remate = 2;
				}
			}

		}
		// Leo la descripci�n de la provincia
		$query_provincias = sprintf("SELECT * FROM provincias WHERE codnum = %s", $prov_remate);
		$provincias = mysqli_query($amercado, $query_provincias) or die (mysqli_error($amercado));
		$row_provincias = mysqli_fetch_assoc($provincias);
		$desc_prov = substr($row_provincias["descripcion"],0,17);
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
				$cliente      = $row_cabecerafac["cliente"];
				$lotes21      = $row_cabecerafac["totneto21"] ;
				$lotes105     = $row_cabecerafac["totneto105"];
				$tot_comision = $row_cabecerafac["totcomis"];
				//$tot_public   = 0.00;
				//$tot_servicios= 0.00;
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				$acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
				$acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
				break;
			case 52:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%COMISION%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = 0.00;
					//$tot_servicios= 0.00;
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
					
				}
				
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%COMISION%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac3) == 1) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_comision = 0.00;
					//$tot_public   = $row_detfac1["neto"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
						$tot_iva21    = (($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21);
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
				//$tot_servicios= 0.00;
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				$acum_lotes21[$prov_remate] = $acum_lotes21[$prov_remate] + $lotes21;
				$acum_lotes105[$prov_remate] = $acum_lotes105[$prov_remate] + $lotes105;
				$acum_comis[$prov_remate] = $acum_comis[$prov_remate] + $tot_comision;
				break;
			case 54:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%COMISION%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = 0.00;
					//$tot_servicios= 0.00;
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
					
				}
				
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%COMISION%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac3) > 0) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					//$tot_comision = 0.00;
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = $row_detfac1["neto"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
			case 55:
				//Debo leer los renglones de detfac para ver de que se trata
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%COMISION%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = 0.00;
					//$tot_servicios= 0.00;
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
					
				}
				
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%COMISION%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac3) > 0) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_comision = 0.00;
					//$tot_public   = $row_detfac1["neto"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%COMISION%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					$tot_comision = $row_detfac1["neto"];
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_public   = 0.00;
					//$tot_servicios= 0.00;
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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

					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
					$tot_iva105   = $row_cabecerafac["totiva105"];
					$tot_iva      = $tot_iva21 + $tot_iva105;
					$tot_resol    = $desc_prov;
					$total        = $row_cabecerafac["totbruto"];
					$nroorig      = $row_cabecerafac["nrodoc"];
					
				}
				
				$query_detfac3 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip not like '%COMISION%' and descrip not like '%PUBLIC%'";
				$detfac3 = mysqli_query($amercado, $query_detfac3) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac3) > 0) {
					$row_detfac3 = mysqli_fetch_assoc($detfac3);
					$cliente      = $row_cabecerafac["cliente"];
					//$tot_comision = 0.00;
					//$tot_public   = $row_detfac1["neto"];
					$tot_servicios= $row_detfac3["neto"];
					$lotes21      = 0.00;
					$lotes105     = 0.00;
					$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
				$cliente      = $row_cabecerafac["cliente"];
				//Deber�a revisar detfac para ver si la fc es tipo 51 o 52
				// DETFAC DE LA NOTA DE CREDITO
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%A0004%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
				
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					
					$descri_det = $row_detfac1["descrip"];
					//echo "DESCRI_DET = ".$descri_det."    "."TCOMP =  ".$tcomp."  NCOMP = ".$ncomp."  ";
					$prim_char = strpos($descri_det, "A0004");
					$nrodoc_rel = substr($descri_det, $prim_char + 10, 4);
					//echo "NRODOC_REL = ".$nrodoc_rel."  ";
					//Leo el cabfac de la factura original
					$query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (51,52) AND ncomp = $nrodoc_rel";
					$cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA".$nrodoc_rel);
					// Y con estos datos leo el detfac correspondiente si es tipo 52:

					if ($row_cabefac = mysqli_fetch_assoc($cabefac)) {
						if ($row_cabefac["tcomp"] == 52) {
							$tcomp_cabefac = $row_cabefac["tcomp"];
							$ncomp_cabefac = $row_cabefac["ncomp"];
							//echo " TCOMP_CABEFAC = ".$tcomp_cabefac."  NCOMP_CABEFAC = ".$ncomp_cabefac."  ";
							//Debo leer los renglones de detfac para ver de que se trata
							$query_detfacfc1 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%INMOB%' OR descrip like '%GASTOS%' OR descrip like '%SERVICIO%')";
							$detfacfc1 = mysqli_query($amercado, $query_detfacfc1) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc1) > 0) {
								$tot_servicios = 0.00;
								while($row_detfacfc1 = mysqli_fetch_array($detfacfc1)) {
									//$row_detfacfc1 = mysqli_fetch_assoc($detfacfc1);
									$tot_servicios += $row_detfacfc1["neto"]; // $row_detfacfc1["neto"];
									$cliente      = $row_cabecerafac["cliente"];
									//$tot_public   = 0.00;
									//$tot_servicios= 0.00;
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
							$detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc2) > 0) {
								$row_detfacfc2 = mysqli_fetch_assoc($detfacfc2);
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_comision = 0.00;
								$tot_public   = $row_cabecerafac["totneto21"];
								//$tot_servicios= 0.00;
								$lotes21      = 0.00;
								$lotes105     = 0.00;
								$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
								$tot_iva105   = $row_cabecerafac["totiva105"];
								$tot_iva      = $tot_iva21 + $tot_iva105;
								$tot_resol    = $desc_prov;
								$total        = $row_cabecerafac["totbruto"];
								$nroorig      = $row_cabecerafac["nrodoc"];

							}

							$query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%COMISION%'";
							$detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc3) > 0) {
								$row_detfacfc3 = mysqli_fetch_assoc($detfacfc3);
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_comision = 0.00;
								//$tot_public   = $row_detfac1["neto"];
								$tot_comision = $row_detfacfc3["neto"];
								//$row_detfacfc3["neto"];
								$lotes21      = 0.00;
								$lotes105     = 0.00;
								$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
								$tot_iva105   = $row_cabecerafac["totiva105"];
								$tot_iva      = $tot_iva21 + $tot_iva105;
								$tot_resol    = $desc_prov;
								$total        = $row_cabecerafac["totbruto"];
								$nroorig      = $row_cabecerafac["nrodoc"];

							}
						}
						else {
							$lotes21      = $row_cabecerafac["totneto21"] ;
							$lotes105     = $row_cabecerafac["totneto105"];
							$tot_comision = $row_cabecerafac["totcomis"];
							$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
/*
				if ($tcomp == 57 && $ncomp == 457) {
					echo "APARECIO NCRED = ".$ncomp."  ";
					echo "TOT_COMISION = ".$tot_comision."  TOT_PUBLIC = ".$tot_public."  TOT_SERVICIOS = ".$tot_servicios."  LOTES21 =  ".$lotes21."  LOTES105 = ".$lotes105." TOTAL =  ".$total."  TOT_IVA =  ".$tot_iva."  ";
				}
				*/
				break;
			case 58:
				$cliente      = $row_cabecerafac["cliente"];
				//Deber�a revisar detfac para ver si la fc es tipo 53 o 54
				// DETFAC DE LA NOTA DE CREDITO
				$query_detfac1 = "SELECT * FROM detfac WHERE tcomp = $tcomp AND ncomp = $ncomp AND descrip like '%B0004%'";
				$detfac1 = mysqli_query($amercado, $query_detfac1) or die(mysqli_error($amercado));
				if (mysqli_num_rows($detfac1) > 0) {
				
					$row_detfac1 = mysqli_fetch_assoc($detfac1);
					
					$descri_det = $row_detfac1["descrip"];
					//echo "DESCRI_DET = ".$descri_det."    "."TCOMP =  ".$tcomp."  NCOMP = ".$ncomp."  ";
					$prim_char = strpos($descri_det, "B0004");
					$nrodoc_rel = substr($descri_det, $prim_char + 10, 4);
					//echo "NRODOC_REL = ".$nrodoc_rel."  ";
					//Leo el cabfac de la factura original
					$query_cabf = "SELECT * FROM cabfac WHERE  tcomp in (53,54) AND ncomp = $nrodoc_rel";
					$cabefac = mysqli_query($amercado, $query_cabf) or die("ACA STA LA CAGADA".$nrodoc_rel);
					// Y con estos datos leo el detfac correspondiente si es tipo 52:

					if ($row_cabefac = mysqli_fetch_assoc($cabefac)) {
						if ($row_cabefac["tcomp"] == 54) {
							$tcomp_cabefac = $row_cabefac["tcomp"];
							$ncomp_cabefac = $row_cabefac["ncomp"];
							//echo " TCOMP_CABEFAC = ".$tcomp_cabefac."  NCOMP_CABEFAC = ".$ncomp_cabefac."  ";
							//Debo leer los renglones de detfac para ver de que se trata
							$query_detfacfc1 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND (descrip like '%INMOB%' OR descrip like '%GASTOS%'  OR descrip like '%SERVICIO%')";
							$detfacfc1 = mysqli_query($amercado, $query_detfacfc1) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc1) > 0) {
								$row_detfacfc1 = mysqli_fetch_assoc($detfacfc1);
								$tot_servicios = $row_detfacfc1["neto"];
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_public   = 0.00;
								//$tot_servicios= 0.00;
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
							$detfacfc2 = mysqli_query($amercado, $query_detfacfc2) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc2) > 0) {
								$row_detfacfc2 = mysqli_fetch_assoc($detfacfc2);
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_comision = 0.00;
								$tot_public   = $row_detfacfc2["neto"];
								//$tot_servicios= 0.00;
								$lotes21      = 0.00;
								$lotes105     = 0.00;
								$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
								$tot_iva105   = $row_cabecerafac["totiva105"];
								$tot_iva      = $tot_iva21 + $tot_iva105;
								$tot_resol    = $desc_prov;
								$total        = $row_cabecerafac["totbruto"];
								$nroorig      = $row_cabecerafac["nrodoc"];

							}

							$query_detfacfc3 = "SELECT * FROM detfac WHERE tcomp = $tcomp_cabefac AND ncomp = $ncomp_cabefac AND descrip like '%COMISION%'";
							$detfacfc3 = mysqli_query($amercado, $query_detfacfc3) or die(mysqli_error($amercado));
							if (mysqli_num_rows($detfacfc3) > 0) {
								$row_detfacfc3 = mysqli_fetch_assoc($detfacfc3);
								$cliente      = $row_cabecerafac["cliente"];
								//$tot_comision = 0.00;
								//$tot_public   = $row_detfac1["neto"];
								$tot_comision = $row_detfacfc3["neto"];
								$lotes21      = 0.00;
								$lotes105     = 0.00;
								$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
								$tot_iva105   = $row_cabecerafac["totiva105"];
								$tot_iva      = $tot_iva21 + $tot_iva105;
								$tot_resol    = $desc_prov;
								$total        = $row_cabecerafac["totbruto"];
								$nroorig      = $row_cabecerafac["nrodoc"];

							}
						}
						else {
							$lotes21      = $row_cabecerafac["totneto21"] ;
							$lotes105     = $row_cabecerafac["totneto105"];
							$tot_comision = $row_cabecerafac["totcomis"];
							$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
				break;
			case 59:
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
				break;
			case 60:
				$cliente      = $row_cabecerafac["cliente"];
				$lotes21   = $row_cabecerafac["totneto21"] ;
				$lotes105  = $row_cabecerafac["totneto105"];
				$tot_comision = $row_cabecerafac["totcomis"];
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
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
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
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
				$tot_iva21    = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) * $porc_iva21;
				$tot_iva105   = $row_cabecerafac["totiva105"];
				$tot_iva      = $tot_iva21 + $tot_iva105;
				$tot_resol    = $desc_prov;
				$total        = $row_cabecerafac["totbruto"];
				$nroorig      = $row_cabecerafac["nrodoc"];
				$acum_serv[$prov_remate] = $acum_serv[$prov_remate] + ($tot_servicios * $signo);
				$acum_prov[$prov_remate] = $acum_prov[$prov_remate] + $total - $tot_iva;
				break;
		}
		$estado = "P";
		/*if ($total == 0 && $tot_servicios == 0 && $lotes21 == 0 && $lotes105 == 0 && $tot_comision == 0 && $tot_iva == 0)

			continue;
		*/
		// Acumulo subtotales
		if ($estado != "A") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 ) {
				// resto Notas de Cr�dito
				//$acum_tot_neto21  = $acum_tot_neto21  - $lotes21;
				//$acum_tot_neto105 = $acum_tot_neto105 - $lotes105;
				//$acum_tot_iva     = $acum_tot_iva     - $tot_iva;
				//$acum_totservicios  = $acum_totservicios  - $tot_servicios;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
				//$acum_totpublic   = $acum_totpublic    - $tot_public;
			}
			else {
				// Sumo Facturas y Notas de D�bito
				//$acum_tot_neto21  = $acum_tot_neto21  + $lotes21;
				//$acum_tot_neto105 = $acum_tot_neto105 + $lotes105;
				//$acum_tot_iva     = $acum_tot_iva     + $tot_iva;
				$acum_total       = $acum_total       + $total;
				$acum_totcomis    = $acum_totcomis    + $tot_comision;
				//$acum_totservicios = $acum_totservicios + $tot_servicios;
				//$acum_totpublic   = $acum_totpublic + $tot_public;
					
			}
			$lotes21       *= $signo;
			$lotes105      *= $signo;
			$tot_iva       *= $signo;
			$tot_comision  *= $signo;
			$tot_public    *= $signo;
			$tot_servicios *= $signo;
			$total         *= $signo;
			
			//$lotes21       = number_format($lotes21, 2, ',','.');
			//$lotes105      = number_format($lotes105, 2, ',','.');
			//$tot_iva       = number_format($tot_iva, 2, ',','.');
			//$tot_public    = number_format($tot_public, 2, ',','.');
			$tot_comision  = number_format($tot_comision, 2, ',','.');
			//$tot_servicios = number_format($tot_servicios, 2, ',','.');
			$total         = number_format($total, 2, ',','.');
			
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  	
			// Imprimo los renglones
			$pdf->SetY($valor_y);
  			$pdf->Cell(1);
  			$pdf->Cell(19,6,$fecha,0,0,'L');
			$pdf->SetX(19);
 	 		$pdf->Cell(6,6,$tc." ",0,0,'L');
  			$pdf->Cell(26,6,$nroorig,0,0,'L');
  			$pdf->Cell(42,6,$nom_cliente,0,0,'L');
  			$pdf->Cell(22,6,$cuit_cliente,0,0,'L');
  			//$pdf->Cell(23,6,$tot_public,0,0,'R');
			//$pdf->Cell(23,6,$tot_servicios,0,0,'R');
			$pdf->Cell(26,6,$tot_comision,0,0,'R');
  			//$pdf->Cell(26,6,$lotes21,0,0,'R');
  			//$pdf->Cell(26,6,$lotes105,0,0,'R');
  			//$pdf->Cell(24,6,$tot_iva,0,0,'R');
  			//$pdf->Cell(24,6,$tot_resol,0,0,'L');
  			$pdf->Cell(26,6,$total,0,0,'R');
			$pdf->Cell(15,6,$codrem,0,0,'R');
		
			$i = $i + 1;	
			$valor_y = $valor_y + 6;
		}
		// HASTA ACA =========================================================================
			
	}
}

// Imprimo subtotales de la hoja la �ltima vez
//$acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
//$acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
//$acum_tot_iva     = number_format($acum_tot_iva, 2, ',','.');
//$acum_totpublic  = number_format($acum_totpublic, 2, ',','.');
$acum_total       = number_format($acum_total, 2, ',','.');
$acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
//$acum_totservicios = number_format($acum_totservicios, 2, ',','.');
		
$pdf->SetY($valor_y);
$pdf->Cell(112);

//$pdf->Cell(26,6,$acum_totpublic,0,0,'R');
//$pdf->Cell(26,6,$acum_totservicios,0,0,'R');
$pdf->Cell(26,6,$acum_totcomis,0,0,'R');
//$pdf->Cell(26,6,$acum_tot_neto21,0,0,'R');
//$pdf->Cell(24,6,$acum_tot_neto105,0,0,'R');
//$pdf->Cell(24,6,$acum_tot_iva,0,0,'R');
//$pdf->Cell(24,6,"                       .",0,0,'R');
$pdf->Cell(26,6,$acum_total,0,0,'R');
/*
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
		$pdf->Cell(20,10,' Comisiones del Remate '.$remate,0,0,'L');
  $pdf->SetFont('Arial','B',9);
  $pdf->SetY(25);
  $pdf->Cell(3);
  $pdf->Cell(20,16,'    Fecha',1,0,'L');
  $pdf->Cell(28,16,' Nro.Factura',1,0,'L');
  $pdf->Cell(42,16,'       Raz�n Social',1,0,'L');
  $pdf->Cell(22,16,'     CUIT',1,0,'L');
  $pdf->Cell(26,16,'    Comisiones ',1,0,'L');
  $pdf->Cell(26,16,'Total Facturado',1,0,'L');
  $pdf->Cell(15,16,'Remate',1,0,'L');

  $pdf->SetY(34);
  $pdf->Cell(114);
  $pdf->Cell(23,8,'    Exentos ',0,0,'L');
  $pdf->Cell(26,8,'             ',0,0,'L');
  $pdf->Cell(26,8,' Gravados 21% ',0,0,'L');
  $pdf->Cell(26,8,' Gravados 10,5%',0,0,'L');
  $pdf->Cell(24,8,' Fiscal 21%',0,0,'L');
  $pdf->Cell(24,8,' Fiscal 10,5%',0,0,'L');
  

		
				
  
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
	//$pdf->Cell(30,6,$descrip_prov[$j],0,0,'L');
	$pdf->SetX(72);
	//$pdf->Cell(26,6,$ac_public,0,0,'R');
	//$pdf->Cell(26,6,$ac_serv,0,0,'R');
	$pdf->Cell(26,6,$ac_comis,0,0,'R');
	//$pdf->Cell(26,6,$ac_lotes21,0,0,'R');
	//$pdf->Cell(26,6,$ac_lotes105,0,0,'R');
	//$pdf->Cell(30,6,$ac_prov,0,0,'R');
	
	$valor_y = $valor_y + 6
	
}

$tot_ac_prov = number_format($tot_ac_prov,2, ',','.');
$tot_ac_comis = number_format($tot_ac_comis,2, ',','.');
$tot_ac_serv = number_format($tot_ac_serv,2, ',','.');
$tot_ac_public = number_format($tot_ac_public,2, ',','.');
$tot_ac_lotes21 = number_format($tot_ac_lotes21,2, ',','.');
$tot_ac_lotes105 = number_format($tot_ac_lotes105,2, ',','.');

$pdf->SetY($valor_y);
$pdf->Cell(10);
$pdf->Cell(30,6,"TOTALES    ",0,0,'L');
$pdf->SetX(72);
//$pdf->Cell(26,6,$tot_ac_public,0,0,'R');
//$pdf->Cell(26,6,$tot_ac_serv,0,0,'R');
$pdf->Cell(26,6,$tot_ac_comis,0,0,'R');
//$pdf->Cell(26,6,$tot_ac_lotes21,0,0,'R');
//$pdf->Cell(26,6,$tot_ac_lotes105,0,0,'R');
//$pdf->Cell(30,6,$tot_ac_prov,0,0,'R');
*/
mysqli_close($amercado);
$pdf->Output();
?>  
