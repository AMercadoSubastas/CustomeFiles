<?php
set_time_limit(0); // Para evitar el timeout

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];
$anio = "";
$mes = "";
$anio = substr($fecha_desde,6,4);
$mes = substr($fecha_desde,3,2);
$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";

$fechahoy = date("d-m-Y");
// ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL TXT =========================
$csv_end = "  
";  
$csv_sep = "|";  
//$csv_file = "\LIBROS DE IVA PARA EXCEL\IVA_VENTAS".$fecha_hasta.".txt";  
$csv_file = "IVA_VENTAS".$anio.$mes.".txt";  

$csv="";  

// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO IMPUESTOS linea 33");
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

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN %s AND %s ORDER BY fecreg, nrodoc", $fecha_desde, $fecha_hasta);

$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC linea 43");


// ACA ARMO EL RENGLON DE TITULOS : ===============================================
$csv.="Fecha".$csv_sep."Nro Factura".$csv_sep."Razon Social".$csv_sep."CUIT".$csv_sep."Conceptos Exentos".$csv_sep."Comisiones".$csv_sep."Tasa de administracion".$csv_sep."Conceptos Gravados 21%".$csv_sep."Conceptos Gravados 10,5%".$csv_sep."IVA Debito Fiscal 21%".$csv_sep."IVA Debito Fiscal 10,5%".$csv_sep."Total Facturado".$csv_sep."Remate".$csv_end; 
  

// Datos de los renglones
$i = 0;
$acum_tot_neto21  = 0;
$acum_tot_neto105 = 0;
$acum_tot_iva21   = 0;
$acum_tot_iva105  = 0;
$acum_tot_resol   = 0;
$acum_total       = 0;
$acum_tot_exento  = 0;
$acum_totcomis    = 0;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	if ($tcomp !=  51 && $tcomp !=  52 && $tcomp !=  53 && $tcomp !=  54 && $tcomp != 55 && 
		$tcomp != 56 && $tcomp != 57 && $tcomp != 58 && $tcomp != 59 && $tcomp != 60 && 
		$tcomp != 61 && $tcomp != 62 &&	$tcomp != 63 && $tcomp != 64 && $tcomp != 89  && $tcomp != 92   && $tcomp != 93  && $tcomp != 94  && $tcomp != 103  && $tcomp != 104  && $tcomp != 105)
		continue;
	if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62  || $tcomp == 93   || $tcomp == 105) {
		$tc = "NC-";
		$signo = -1;
	}
	elseif ($tcomp == 59 ||  $tcomp == 60 || $tcomp == 63 || $tcomp == 64  || $tcomp == 94){
		$tc = "ND-";
		$signo = 1;
	}
	else {
		$tc = "FC-";
		$signo = 1;
	}
	
	if ($i < 22) {
		$fecha        = substr($row_cabecerafac["fecdoc"],8,2)."-".substr($row_cabecerafac["fecdoc"],5,2)."-".substr($row_cabecerafac["fecdoc"],0,4);
		$cliente      = $row_cabecerafac["cliente"];
		$tot_neto21   = $row_cabecerafac["totneto21"] ;
		$tot_neto105  = $row_cabecerafac["totneto105"];
		$tot_comision = $row_cabecerafac["totcomis"];
		$tot_iva21    = $row_cabecerafac["totiva21"];// + $row_cabecerafac["totcomis"]) * $porc_iva21;
		$tot_iva105   = $row_cabecerafac["totiva105"];
		$tot_resol    = $row_cabecerafac["totimp"];
		$total        = $row_cabecerafac["totbruto"];
		$nroorig      = $tc.$row_cabecerafac["nrodoc"];
        $codrem = $row_cabecerafac["codrem"];
		if ($tot_iva21 == 0.00 && $tot_iva105 == 0.00) {
			$tot_exento = $tot_neto21;
			$tot_neto21 = 0.00;
		}
		else 
			$tot_exento = 0.00;
				
		$estado = $row_cabecerafac["estado"];
				
		// Acumulo subtotales
		if ($estado == "P" || $estado == "S") {
			if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62  || $tcomp == 93) {
				// resto Notas de Cr�dito
				$acum_tot_neto21  = $acum_tot_neto21  - $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 - $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   - $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  - $tot_iva105;
				$acum_tot_resol   = $acum_tot_resol   - $tot_resol;
				$acum_tot_exento  = $acum_tot_exento  - $tot_exento;
				$acum_total       = $acum_total       - $total;
				$acum_totcomis    = $acum_totcomis    - $tot_comision;
			}
			else {
				// Sumo Facturas y Notas de D�bito
				$acum_tot_neto21  = $acum_tot_neto21  + $tot_neto21;
				$acum_tot_neto105 = $acum_tot_neto105 + $tot_neto105;
				$acum_tot_iva21   = $acum_tot_iva21   + $tot_iva21;
				$acum_tot_iva105  = $acum_tot_iva105  + $tot_iva105;
				$acum_tot_exento  = $acum_tot_exento  + $tot_exento;
				$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
				$acum_total       = $acum_total       + $total;
				$acum_totcomis    = $acum_totcomis    + $tot_comision;
					
			}
	
			$tot_neto21   = number_format($tot_neto21*$signo, 2, ',','.');
			$tot_neto105  = number_format($tot_neto105*$signo, 2, ',','.');
			$tot_iva21    = number_format($tot_iva21*$signo, 2, ',','.');
			$tot_iva105   = number_format($tot_iva105*$signo, 2, ',','.');
			$tot_resol    = number_format($tot_resol*$signo, 2, ',','.');
			$tot_comision = number_format($tot_comision*$signo, 2, ',','.');
			$tot_exento   = number_format($tot_exento*$signo, 2, ',','.');
			$total        = number_format($total*$signo, 2, ',','.');
	
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO ENTIDADES linea 141");
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  	
			// Imprimo los renglones DESDE ACA ARMO EL TXT
			
			
			$csv.=$fecha.$csv_sep.$nroorig.$csv_sep.$nom_cliente.$csv_sep.$cuit_cliente.$csv_sep.$tot_exento.$csv_sep.$tot_comision.$csv_sep.$tot_resol.$csv_sep.$tot_neto21.$csv_sep.$tot_neto105.$csv_sep.$tot_iva21.$csv_sep.$tot_iva105.$csv_sep.$total.$csv_sep.$codrem.$csv_end;
			
		}
		else {
			// Imprimo cbte anulado
		
			$csv.=$fecha.$csv_sep.$nroorig.$csv_sep."ANULADA".$csv_sep." ".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_sep."0,00".$csv_end;
		}
	}
	else {
		// Imprimo subtotales de la hoja, uso otras variables porque el number_format
		// me jode los acumulados
		$f_acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
		$f_acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
		$f_acum_tot_iva21   = number_format($acum_tot_iva21, 2, ',','.');
		$f_acum_tot_iva105  = number_format($acum_tot_iva105, 2, ',','.');
		$f_acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
		$f_acum_total       = number_format($acum_total, 2, ',','.');
		$f_acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
		$f_acum_tot_exento    = number_format($acum_tot_exento, 2, ',','.');
		
	}
	
}
// Imprimo subtotales de la hoja la �ltima vez
$acum_tot_neto21  = number_format($acum_tot_neto21, 2, ',','.');
$acum_tot_neto105 = number_format($acum_tot_neto105, 2, ',','.');
$acum_tot_iva21   = number_format($acum_tot_iva21, 2, ',','.');
$acum_tot_iva105  = number_format($acum_tot_iva105, 2, ',','.');
$acum_tot_resol   = number_format($acum_tot_resol, 2, ',','.');
$acum_total       = number_format($acum_total, 2, ',','.');
$acum_totcomis    = number_format($acum_totcomis, 2, ',','.');
$acum_tot_exentos = number_format($acum_tot_exento, 2, ',','.');
		
		
mysqli_close($amercado);
// ACA GRABO EL ARCHIVO TXT ====================================================
if (!$handle = fopen($csv_file, "w")) {  
    echo "No se puede abrir el archivo";  
    exit;  
}  
if (fwrite($handle, utf8_decode($csv)) === FALSE) {  
    echo "No se puede grabar el archivo";  
    exit;  
}  
fclose($handle);  

$file = $csv_file;
header("Content-disposition: attachment; filename=$file");
header("Content-type: application/octet-stream");
readfile($file);

if (!isset($file) || empty($file)) {
    exit();
}
$root = "C:\\LOTES WEB";
$file = basename($file);
$path = $root.$file;
$type = '';
 
if (is_file($path)) {
    $size = filesize($path);
    if (function_exists('mime_content_type')) {
        $type = mime_content_type($path);
    } else if (function_exists('finfo_file')) {
                $info = finfo_open(FILEINFO_MIME);
                $type = finfo_file($info, $path);
                finfo_close($info);
            }
    if ($type == '') {
        $type = "application/force-download";
    }
     // Define los headers
     header("Content-Type: $type");
     header("Content-Disposition: attachment; filename=$file");
     header("Content-Transfer-Encoding: binary");
     header("Content-Length: " . $size);
     // Descargar el archivo
     readfile($path);
} else {
    //die("El archivo no existe.");
}
?>