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

function formato($c) { 
	$d = number_format($c, 2, ',','.');
	$e = str_replace(",","",$d);
	$f = str_replace(".","",$e);
	$g = sprintf("%015d",  $f); 
	return $g;
} 

$fechahoy = date("d-m-Y");
// ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL TXT =========================
$csv_end = "  
";  
$csv_sep = "|";  
$csv_file = "IMPO_VENTAS_ALIC".$anio.$mes.".txt";  
$csv="";  

// Leo la cabecera

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecreg BETWEEN %s AND %s ORDER BY fecreg, nrodoc", $fecha_desde, $fecha_hasta);

$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABFAC".$query_cabfac);

while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
	
	if ($tcomp !=  51 && $tcomp !=  52 && $tcomp !=  53 && $tcomp !=  54 && $tcomp != 55 && $tcomp != 56 && $tcomp != 57 && $tcomp != 58 && $tcomp != 59 && $tcomp != 60 && $tcomp != 61 && $tcomp != 62 &&	$tcomp != 63 && $tcomp != 64  && $tcomp != 89 &&	$tcomp != 92 && $tcomp != 93  && $tcomp != 94   && $tcomp != 103  && $tcomp != 104  && $tcomp != 105)
		continue;
	if ($tcomp ==  57 ||  $tcomp ==  58 || $tcomp == 61 || $tcomp == 62 || $tcomp == 93 || $tcomp == 105) {
		$tc = "NC-";
		$signo = 1;
	}
	elseif ($tcomp == 59 ||  $tcomp == 60 || $tcomp == 63 || $tcomp == 64  || $tcomp == 94){
		$tc = "ND-";
		$signo = 1;
	}
	else {
		$tc = "FC-";
		$signo = 1;
	}
	
    $fecha        = $row_cabecerafac["fecdoc"];
    $cliente      = $row_cabecerafac["cliente"];
    $tot_neto21   = $row_cabecerafac["totneto21"] +  $row_cabecerafac["totimp"];
    $tot_neto105  = $row_cabecerafac["totneto105"];
    $tot_comision = $row_cabecerafac["totcomis"];
    $tot_iva21    = $row_cabecerafac["totiva21"]; 
    $tot_iva105   = $row_cabecerafac["totiva105"];
    $tot_resol    = 0;//$row_cabecerafac["totimp"];
    $total        = $row_cabecerafac["totbruto"];
    $nroorig      = $row_cabecerafac["nrodoc"];

    $totneto_oper = $tot_neto21 + $tot_neto105 + $tot_comision;
    $totneto_oper_afip = formato($totneto_oper);

    
	
    $tot_neto21   = number_format($tot_neto21  * $signo, 2, ',','.');
    $tot_neto105  = number_format($tot_neto105*$signo, 2, ',','.');
    $tot_iva21    = number_format($tot_iva21*$signo, 2, ',','.');
    $tot_iva105   = number_format($tot_iva105*$signo, 2, ',','.');
    $tot_resol    = number_format(0, 2, ',','.');
    $tot_comision = number_format($tot_comision*$signo, 2, ',','.');
    $total        = number_format($total*$signo, 2, ',','.');
	
    // TCOMP segun AFIP
    $tcomp_afip = "";
    $pto_vta = "";
    $ncomp_afip = "";
    $tdoc_cli = 0;
    $tdoc_cli_afip = "";
    $nro_vend = "";
    $nro_vend_afip = "";
    switch($tcomp) {
        case 51:
        case 52:
        case 55: // FACTURA A
        case 104:
            $tcomp_afip = "001";
            break;
        case 59: // N DEB A
        case 63:
        case 94:
            $tcomp_afip = "002";
            break;
        case 57:
        case 61: // N CRED A
        case 93:
            $tcomp_afip = "003";
            break;
        case 53:
        case 54:
        case 56: // FACTURA B
            $tcomp_afip = "006";
            break;
        case 60: // N DEB B
        case 64:
            $tcomp_afip = "007";
            break;
        case 58:
        case 62: // N CRED B
            $tcomp_afip = "008";
            break;
        case 103:
            $tcomp_afip = "019";
            break;
        case 89:
        case 92: // FC CRED A
            $tcomp_afip = "201";
            break;
        case 105:
            $tcomp_afip = "203";
            break;
    }
    if ($tcomp == 89 || $tcomp == 92 || $tcomp == 93 || $tcomp == 94 || $tcomp == 103 || $tcomp == 104 || $tcomp == 105) {
        $pto_vta = substr($row_cabecerafac["nrodoc"], 1, 5);
        //$pto_vta = "0".$pto_vta;
        $ncomp_afip = substr($row_cabecerafac["nrodoc"], 7, 8);
        $ncomp_afip = "000000000000".$ncomp_afip;
    }
    else {
        $pto_vta = substr($row_cabecerafac["nrodoc"], 1, 4);
        $pto_vta = "0".$pto_vta;
        $ncomp_afip = substr($row_cabecerafac["nrodoc"], 6, 8);
        $ncomp_afip = "000000000000".$ncomp_afip;
    }
    // Leo el cliente
    $query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
    $enti = mysqli_query($amercado, $query_entidades) or die(mysqli_error($amercado));
    $row_entidades = mysqli_fetch_assoc($enti);
    $nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
    $nro_cliente   = $row_entidades["numero"];
    $cuit_cliente  = $row_entidades["cuit"];
    $tdoc_cli = substr($row_entidades["cuit"], 0, 2);
    /*
    if ($tdoc_cli < 30) 
        $tdoc_cli_afip = "86";
    else
    */
    $tdoc_cli_afip = "80";
    $nro_vend = str_replace("-","",$cuit_cliente);
    $nro_vend_afip = "000000000".$nro_vend;
    $nombre_cli = str_pad($nom_cliente, 30);

    if ($tot_iva21 != 0.00) {
        $alic_afip = "0005";
        $tot_oper = formato($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"] + $row_cabecerafac["totimp"] );
        $tot_iva = formato($row_cabecerafac["totiva21"]);
        $csv.=$tcomp_afip.$pto_vta.$ncomp_afip.$tot_oper.$alic_afip.$tot_iva.$csv_end;
    }
    if ($tot_iva105 != 0.00) {
        $alic_afip = "0004";
        $tot_oper = formato($row_cabecerafac["totneto105"]);
        $tot_iva = formato($row_cabecerafac["totiva105"]);
        $csv.=$tcomp_afip.$pto_vta.$ncomp_afip.$tot_oper.$alic_afip.$tot_iva.$csv_end;
    }
    if ($tot_iva105 == 0.00 && $tot_iva21 == 0.00) {
        $alic_afip = "0003";
        $tot_oper = formato($row_cabecerafac["totneto"]);
        $tot_iva = formato($row_cabecerafac["totiva21"]);
        $csv.=$tcomp_afip.$pto_vta.$ncomp_afip.$tot_oper.$alic_afip.$tot_iva.$csv_end;
        
    }			
    // Imprimo los renglones DESDE ACA ARMO EL TXT
    $totnonetograv = "000000000000000";
    $cod_mon = "PES";
    $tipo_cbio = "0001000000";
    $cod_oper = "0";
    $fecha_afip = str_replace("-","",$fecha);

	
}

		
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
