<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout
//CABFAC_TCOMP PARA TIPOS DE COMPROBANTES
define('FC_PROV_A','32');
define('FC_PROV_C','33');
define('ND_PROV_A','34');
define('ND_PROV_C','35');
define('NC_PROV_A','36');
define('NC_PROV_C','37');
define('FC_PROV_M','65');
define('NC_PROV_M','87');
define('ND_PROV_M','88');
define('FC_PROV_LIQ','110');
// CONCAFAC_NROCONC PARA RETENCIONES
define('CONC_NO_GRAV','20');
define('RET_IVA','30');
define('RET_IIBB_BA','31');
define('RET_IIBB_CABA','32');
define('RET_IIBB_SALTA','63');
define('RET_IIBB_STAFE','64');
define('RET_IIBB_CHACO','65');
define('RET_IIBB_CORRIENTES','66');
define('RET_IIBB_NEUQUEN','67');
define('RET_IIBB_SANLUIS','68');
define('RET_IIBB_CORDOBA','72');
define('RET_IIBB_JUJUY','94');
define('RET_IIBB_SJUAN','97');
define('RET_GAN','33');
define('IMP_INT','35');
define('TAS_CER','60');
define('IMP_DIES','87');
define('COMB_LIQ','88');
define('REC_GAS','89');
define('SER_SOC','91');
define('TASA_SSN','92');
define('SEL_CABA','93');

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los parametros del formulario anterior

$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

function formato($c) { 
	$d = number_format($c, 2, ',','.'); 
	$e = str_replace(",","",$d);
	$f = str_replace(".","",$e);
	$g = sprintf("%015d",  $f); 
	return $g;
} 
$anio = "";
$mes = "";
$anio = substr($fecha_desde,6,4);
$mes = substr($fecha_desde,3,2);
$fecha_desde ="'".substr($fecha_desde,6,4)."-".substr($fecha_desde,3,2)."-".substr($fecha_desde,0,2)."'";
$fecha_hasta = "'".substr($fecha_hasta,6,4)."-".substr($fecha_hasta,3,2)."-".substr($fecha_hasta,0,2)."'";
$fechahoy = date("d-m-Y");

// ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL CSV =========================
$csv_end = "  
";  
$csv_sep = "|";  
$csv_file = "IMPO_COMPRAS".$anio.$mes.".txt";  
$csv="";  

// Leo los renglones

// Traigo impuestos
$query_impuestos= "SELECT * FROM impuestos";
$impuestos = mysqli_query($amercado, $query_impuestos) or die("ERROR LEYENDO TABLA IMPUESTOS ".$query_impuestos." ");
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

$query_cabfac = sprintf("SELECT * FROM cabfac WHERE fecval BETWEEN %s AND %s ORDER BY fecval , nrodoc ", $fecha_desde, $fecha_hasta);
$cabecerafac = mysqli_query($amercado, $query_cabfac) or die("ERROR LEYENDO CABECERA DE FACTURAS ".$query_cabfac." ");

// Leo las Liquidaciones
$query_liquidacion = sprintf("SELECT * FROM liquidacion WHERE fechaliq BETWEEN %s AND %s ORDER BY fechaliq , nrodoc ", $fecha_desde, $fecha_hasta);
$t_liquidacion = mysqli_query($amercado, $query_liquidacion) or die("ERROR LEYENDO TABLA LIQUIDACIONES ".$query_liquidacion." ");
$totalRows_liquidacion = mysqli_num_rows($t_liquidacion);


$i = 0;
$acum_total_neto21  = 0;
$acum_total_neto105 = 0;
$acum_total_iva21   = 0;
$acum_total_neto = 0;
$acum_total_iva   = 0;
$acum_total_iva105  = 0;
$acum_total_exento  = 0;
$acum_tot_resol   = 0;
$acum_total       = 0;
$acum_df_retiva  = 0;
$acum_df_retib = 0;
$acum_df_retgan = 0;
$df_retiva = 0.0;
$df_retgan = 0.0;
$df_retib  = 0.0;
$df_impint = 0.0;
$df_tascer = 0.0;
while($row_cabecerafac = mysqli_fetch_array($cabecerafac))
{	
    
	$tcomp      = $row_cabecerafac["tcomp"];
	$serie      = $row_cabecerafac["serie"];
	$ncomp      = $row_cabecerafac["ncomp"];
    
	$cliente    = $row_cabecerafac["cliente"];
    
        
	if ($tcomp != FC_PROV_A && $tcomp != FC_PROV_C && $tcomp != ND_PROV_A && $tcomp != ND_PROV_C && $tcomp != NC_PROV_A && $tcomp != NC_PROV_C && $tcomp != FC_PROV_M  && $tcomp != NC_PROV_M && $tcomp != ND_PROV_M && $tcomp != FC_PROV_LIQ)
		continue;
    // Leo el cliente
    $query_enti = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
    $ent = mysqli_query($amercado, $query_enti) or die("ERROR LEYENDO ENTIDADES Cliente = ".$cliente." TCOMP = ".$tcomp." NCOMP = ".$ncomp);
    $row_enti = mysqli_fetch_assoc($ent);
    $tipiva_cli   = $row_enti["tipoiva"];
    
    
    
    
    
	if ($tcomp == FC_PROV_A || $tcomp == FC_PROV_C || $tcomp == ND_PROV_A || $tcomp == ND_PROV_C || $tcomp == FC_PROV_M || $tcomp == ND_PROV_M || $tcomp == FC_PROV_LIQ)  {
		$signo = 1;
	}
	else {
		$signo = 1;
	}
	
    
    
		$query_detfac = sprintf("SELECT * FROM detfac WHERE tcomp = %s AND serie = %s AND ncomp = %s", $tcomp, $serie, $ncomp);
		$detallefac = mysqli_query($amercado, $query_detfac) or die("ERROR LEYENDO DETALLE DE FACTURAS ".$query_detfac." ");
		$totalRows_detallefac = mysqli_num_rows($detallefac);
        
		$df_concnograv  = 0.00;
		$df_retib  = 0.00;
		$df_retgan = 0.00;
		$df_retiva = 0.00;
		$df_impint = 0.00;
		$df_tascer = 0.00;
		while($row_detallefac = mysqli_fetch_array($detallefac)) {
            
			$concafac = $row_detallefac["concafac"];
			if ($concafac == RET_IVA || $concafac == RET_IIBB_BA || $concafac == RET_IIBB_CABA || $concafac == RET_GAN || $concafac == CONC_NO_GRAV || $concafac == IMP_INT || $concafac == TAS_CER || $concafac == RET_IIBB_SALTA || $concafac == RET_IIBB_STAFE || $concafac == RET_IIBB_CHACO || $concafac == RET_IIBB_CORRIENTES || $concafac == RET_IIBB_NEUQUEN || $concafac == RET_IIBB_SANLUIS || $concafac == RET_IIBB_CORDOBA || $concafac == RET_IIBB_JUJUY || $concafac == RET_IIBB_SJUAN  || $concafac == IMP_DIES || $concafac == COMB_LIQ || $concafac == SER_SOC || $concafac == TASA_SSN || $concafac == SEL_CABA || $concafac == REC_GAS) {
				switch($concafac) {
					case	CONC_NO_GRAV:
						$df_concnograv = $row_detallefac["neto"] * $signo;
						break;
					case	RET_IVA:
						$df_retiva = $row_detallefac["neto"] * $signo;
						break;
					case 	RET_IIBB_BA:
				    case 	RET_IIBB_CABA:
                    case 	RET_IIBB_SALTA:
                    case 	RET_IIBB_STAFE:
                    case 	RET_IIBB_CHACO:
                    case 	RET_IIBB_CORRIENTES:
                    case 	RET_IIBB_NEUQUEN:
                    case 	RET_IIBB_SANLUIS:
                    case    RET_IIBB_CORDOBA:
                    case    RET_IIBB_JUJUY:
                    case    RET_IIBB_SJUAN:
						$df_retib  += $row_detallefac["neto"] * $signo;
						break;

					case	RET_GAN:
						$df_retgan = $row_detallefac["neto"] * $signo;
						break;
					
					case	TAS_CER:
						$df_tascer = $row_detallefac["neto"] * $signo;
						break;
                    case	IMP_INT:
				    case	IMP_DIES:
				    case	COMB_LIQ:
                    case	SER_SOC:
                    case	TASA_SSN:
                    case    REC_GAS:
                    case	SEL_CABA:
				        $df_impint += $row_detallefac["neto"] * $signo;
                        $alic_afip = "0002";
				        break;
				}
			}
			else {
				continue;
			}
		}
		
		$fecha        = $row_cabecerafac["fecdoc"];
		$tcomp_afip = "";
		$pto_vta = "";
		$ncomp_afip = "";
		$tdoc_cli = 0;
		$tdoc_cli_afip = "";
		$nro_vend = "";
		$nro_vend_afip = "";
		switch($tcomp) {
			case FC_PROV_A:
				$tcomp_afip = "001";
				break;
			case FC_PROV_C:
				$tcomp_afip = "011";
				break;
			case ND_PROV_A:
				$tcomp_afip = "002";
				break;
			case ND_PROV_C:
				$tcomp_afip = "012";
				break;
			case NC_PROV_A:
				$tcomp_afip = "003";
				break;
			case NC_PROV_C:
				$tcomp_afip = "013";
				break;
			case FC_PROV_M:
				$tcomp_afip = "051";
				break;
            case NC_PROV_M:
				$tcomp_afip = "053";
				break;
            case ND_PROV_M:
				$tcomp_afip = "052";
				break;
            case FC_PROV_LIQ:
				$tcomp_afip = "001";
				break;
		}
		
		if (strlen($row_cabecerafac["nrodoc"]) == 14) {
			$pto_vta = substr($row_cabecerafac["nrodoc"], 1, 4);
			$pto_vta = "0".$pto_vta;
			$ncomp_afip = substr($row_cabecerafac["nrodoc"], 6, 8);
			$ncomp_afip = "000000000000".$ncomp_afip;
		}
		else {
			$pto_vta = substr($row_cabecerafac["nrodoc"], 1, 5);
			//$pto_vta = "0".$pto_vta;
			$ncomp_afip = substr($row_cabecerafac["nrodoc"], 7, 8);
			$ncomp_afip = "000000000000".$ncomp_afip;
		}
		$ndesp_impo = "                ";
		
		$cliente      = $row_cabecerafac["cliente"];
		$tot_neto     = $row_cabecerafac["totneto"];
		$tot_neto21   = ($row_cabecerafac["totneto21"] + $row_cabecerafac["totcomis"]) ;
		$tot_neto105  = $row_cabecerafac["totneto105"] ;
		$tot_comision = $row_cabecerafac["totcomis"] * $signo;
		$tot_iva21    = $row_cabecerafac["totiva21"] ;
		$tot_iva105   = $row_cabecerafac["totiva105"] ;
		$tot_resol    = $row_cabecerafac["totimp"] * $signo;
		$total        = $row_cabecerafac["totbruto"] * $signo;
		$nroorig      = $row_cabecerafac["nrodoc"];
		$total_neto   = ($tot_neto21 + $tot_neto105)  * $signo;
		$total_iva    = ($tot_iva21  + $tot_iva105) * $signo;
		$total_exento = 0;
		
		
		$tot_oper = formato($row_cabecerafac["totbruto"]);
		$tot_nonetograv = "000000000000000"; 
		if ($tcomp  != FC_PROV_C && $tcomp !=  ND_PROV_C && $tcomp != NC_PROV_C) {
			$df_concnograv += $df_tascer;
			$tot_exen = formato($df_concnograv);
		}
		else
			$tot_exen = "000000000000000";
		$tot_perc_iva = formato($df_retiva);
		$tot_perc_gan = formato($df_retgan);
		$tot_perc_ib = formato($df_retib);
		$tot_perc_impint = formato($df_impint);
		$tot_perc_impmun = "000000000000000";
				
		if ($tcomp !=  FC_PROV_C &&  $tcomp != ND_PROV_C &&  $tcomp != NC_PROV_C) {
			$total_exento += $df_concnograv;
			$total_exento += $df_tascer;
		}
		// Acumulo subtotales
		$acum_total_neto  = $acum_total_neto  + $total_neto;
		$acum_total_iva   = $acum_total_iva   + $total_iva;
		$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
		$acum_total       = $acum_total       + $total;
		$acum_df_retiva   = $acum_df_retiva   + $df_retiva;
		$acum_df_retib    = $acum_df_retib    + $df_retib;
		$acum_df_retgan   = $acum_df_retgan   + $df_retgan;
		$acum_total_exento  = $acum_total_exento  + $total_exento;
		$cod_mon = "PES";
		$tipo_cbio = "0001000000";
	
		// Formateo los campos antes de imprimir
		$total_neto  = number_format($total_neto, 2, ',','.');
		$total_iva   = number_format($total_iva, 2, ',','.');
		$tot_resol   = number_format($tot_resol, 2, ',','.');
		$total       = number_format($total, 2, ',','.');
		$df_retiva   = number_format($df_retiva, 2, ',','.');
		$df_retib    = number_format($df_retib, 2, ',','.');
		$df_retgan   = number_format($df_retgan, 2, ',','.');
		$total_exento  = number_format($total_exento, 2, ',','.');
	
		// Leo el cliente
  		$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  		$enti = mysqli_query($amercado, $query_entidades) or die("ERROR LEYENDO TABLA ENTIDADES ".$query_entidades." ");
  		$row_entidades = mysqli_fetch_assoc($enti);
  		$nom_cliente   = substr($row_entidades["razsoc"], 0, 30);
  		$nro_cliente   = $row_entidades["numero"];
  		$cuit_cliente  = $row_entidades["cuit"];
		$tdoc_cli = substr($row_entidades["cuit"], 0, 2);
		if ($tdoc_cli < 30) 
			$tdoc_cli_afip = "80";
		else
			$tdoc_cli_afip = "80";
		$nro_vend = str_replace("-","",$cuit_cliente);
		if ($nro_vend=="")
			$nro_vend_afip = "00000000000000000000";
		else
			$nro_vend_afip = "000000000".$nro_vend;
		$nombre_cli = str_pad($nom_cliente, 30);
  	
		if ($tot_iva21 > 0.00 && $tot_iva105 > 0.00)	{
			$alic_iva = "2";
		}
		else 
			if ($tot_iva21 > 0.00 || $tot_iva105 > 0.00) {
				$alic_iva = "1";
			}
			else
				$alic_iva = "0";
        
        if ($tcomp == 32 and $ncomp == 20855)
            $alic_iva = "3";
		if ($tcomp ==  FC_PROV_C ||  $tcomp == ND_PROV_C ||  $tcomp == NC_PROV_C)
			$alic_iva = "0";
		if (($tot_iva21 == 0.00 && $tot_iva105 == 0.00) && ($tcomp ==  FC_PROV_A ||  $tcomp == ND_PROV_A ||  $tcomp == NC_PROV_A)) {
			$cod_oper = "0"; //$cod_oper = "E";
            $alic_iva = "1";
            $total_exento = $row_cabecerafac["totneto"] * $signo;
            
        }
		else
			$cod_oper = "0";
		$cred_fis = $tot_iva21 + $tot_iva105;
		$cred_fis_afip = formato($cred_fis);
		$otros_trib = "000000000000000";
		$cuit_emisor = "00000000000";
		$denom_emisor = "                              ";
		$iva_comis = "000000000000000";
		$fecha_afip = str_replace("-","",$fecha);
		// Imprimo los renglones DESDE ACA ARMO EL TXT
		
    $csv.=$fecha_afip.$tcomp_afip.$pto_vta.$ncomp_afip.$ndesp_impo.$tdoc_cli_afip.$nro_vend_afip.$nombre_cli.$tot_oper.$tot_nonetograv.$tot_exen.$tot_perc_iva.$tot_perc_gan.$tot_perc_ib.$tot_perc_impmun.$tot_perc_impint.$cod_mon.$tipo_cbio.$alic_iva.$cod_oper.$cred_fis_afip.$otros_trib.$cuit_emisor.$denom_emisor.$iva_comis.$csv_end;
			
		
		$total_exento  = 0.00;
				
}
// Ahora voy por las Liquidaciones
$fecha_afip = ""; $tcomp_afip = ""; $pto_vta = "";
$ncomp_afip = ""; $ndesp_impo = ""; $tdoc_cli_afip = ""; $nro_vend_afip = ""; $nombre_cli = ""; $tot_oper = ""; $tot_nonetograv = ""; $tot_exen = ""; $tot_perc_iva = ""; $tot_perc_gan = "";   $tot_perc_impint = "";
$alic_iva = ""; 
$cod_oper = "" ;
$cred_fis_afip = "";
$otros_trib = "";
$cuit_emisor = "";
$denom_emisor = "";
$iva_comis = "";
$acum_total_neto  = 0;
$acum_total_iva   = 0;
$acum_tot_resol   = 0;
$acum_total       = 0;
$acum_df_retiva  = 0;
$acum_df_retib = 0;
$acum_df_retgan = 0;
$acum_total_exento = 0;
$df_retiva  = 0.00;
$df_retib = 0.00;
$df_retgan = 0.00;
$df_impint = 0.00;
$tot_exen = "000000000000000";
$tot_perc_iva = formato($df_retiva);
$tot_perc_gan = formato($df_retgan);
$tot_perc_ib = formato($df_retib);
$tot_perc_impint = formato($df_impint);
$tot_perc_impmun = "000000000000000";
$tot_exen = "000000000000000";
$tot_nonetograv = "000000000000000";
while($row_liquidacion = mysqli_fetch_array($t_liquidacion))
{
	
		$tcomp		  = $row_liquidacion["tcomp"];
		$serie		  = $row_liquidacion["serie"];
		$ncomp		  = $row_liquidacion["ncomp"];
		$fecha        = $row_liquidacion["fechaliq"];
		$cliente      = $row_liquidacion["cliente"];
		$tot_neto21   = $row_liquidacion["totneto1"];
		$tot_neto105  = $row_liquidacion["totneto2"];
		$tot_iva21    = $row_liquidacion["totiva21"];
		$tot_iva105   = $row_liquidacion["totiva105"];
		$tot_resol    = 0.00;
		$total        = $row_liquidacion["subtot1"] + $row_liquidacion["subtot2"];
		$nroorig      = $row_liquidacion["nrodoc"];
		$total_neto   = $tot_neto21 + $tot_neto105;
		$total_iva    = $tot_iva21  + $tot_iva105;
	    		
			
		$estado = $row_liquidacion["estado"];
		// Acumulo subtotales
		if ($estado != "A") {
			// Acumulo subtotales
			$acum_total_neto  = $acum_total_neto  + $total_neto;
			$acum_total_iva   = $acum_total_iva   + $total_iva;
			$acum_tot_resol   = $acum_tot_resol   + $tot_resol;
			$acum_total       = $acum_total       + $total;
			$acum_df_retiva   = $acum_df_retiva   + $df_retiva;
			$acum_df_retib    = $acum_df_retib    + $df_retib;
			$acum_df_retgan   = $acum_df_retgan   + $df_retgan;
	
			// Formateo los campos antes de imprimir
			$total_neto  = number_format($total_neto, 2, ',','.');
			$total_iva   = number_format($total_iva, 2, ',','.');
			$tot_resol   = number_format($tot_resol, 2, ',','.');
			$total       = number_format($total, 2, ',','.');
			$df_retiva   = 0.00;
			$df_retib    = 0.00;
			$df_retgan   = 0.00;
	
			// Leo el cliente
  			$query_entidades = sprintf("SELECT * FROM entidades WHERE  codnum = %s", $cliente);
  			$enti = mysqli_query($amercado, $query_entidades) or die("NO SE PUDO LEER LA ENTIDAD lin 429");
  			$row_entidades = mysqli_fetch_assoc($enti);
  			$nom_cliente   = substr($row_entidades["razsoc"], 0, 20);
  			$nro_cliente   = $row_entidades["numero"];
  			$cuit_cliente  = $row_entidades["cuit"];
  	
			// Imprimo los renglones
			
			$fecha_afip = str_replace("-","",$fecha);
			if ($tcomp==3) 
				$tcomp_afip = "063";
			else
				$tcomp_afip = "064";
			$pto_vta = "00001";
			$ncomp_afip = substr($row_liquidacion["nrodoc"], 6, 8);
			$ncomp_afip = "000000000000".$ncomp_afip;
			$ndesp_impo = "                ";
			//CODIGO: 063 DESCRIPCION: LIQUIDACIONES A
			//CODIGO: 064 DESCRIPCION: LIQUIDACIONES B
			
			$tdoc_cli = substr($row_entidades["cuit"], 0, 2);
			if ($tdoc_cli < 30) 
				$tdoc_cli_afip = "80";
			else
				$tdoc_cli_afip = "80";
			$nro_vend = str_replace("-","",$cuit_cliente);
			$nro_vend_afip = "000000000".$nro_vend;
			$nombre_cli = str_pad($nom_cliente, 30);
  	
			if ($tot_iva21 != 0.00 && $tot_iva105 != 0.00)	{
				$alic_iva = "2";
			}
			else 
				if ($tot_iva21 != 0.00 || $tot_iva105 != 0.00) {
					$alic_iva = "1";
				}
				else
					continue; //$alic_iva = "0";
			$cod_oper = "0";
			$cred_fis = $tot_iva21 + $tot_iva105;
			$cred_fis_afip = formato($cred_fis);
			$otros_trib = "000000000000000";
			if ($tcomp==3) {
				$cuit_emisor = "30718033612";
				$denom_emisor = "ADRIAN MERCADO SUBASTAS S.A.  ";
			}
			else {
				$cuit_emisor = "00000000000";
				$denom_emisor = "                              ";
			}
			$iva_comis = "000000000000000";
			$tot_op = $row_liquidacion["totremate"];
			$tot_oper = formato($tot_op);
			$csv.=$fecha_afip.$tcomp_afip.$pto_vta.$ncomp_afip.$ndesp_impo.$tdoc_cli_afip.$nro_vend_afip.$nombre_cli.$tot_oper.$tot_nonetograv.$tot_exen.$tot_perc_iva.$tot_perc_gan.$tot_perc_ib.$tot_perc_impmun.$tot_perc_impint.$cod_mon.$tipo_cbio.$alic_iva.$cod_oper.$cred_fis_afip.$otros_trib.$cuit_emisor.$denom_emisor.$iva_comis.$csv_end;
			
			
		}
		else {
			continue;
	
		}

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