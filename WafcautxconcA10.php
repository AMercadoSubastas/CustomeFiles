<?php 
require_once('Connections/amercado.php'); 
include_once "src/userfn.php";
include_once "src/phpfn.php";

include 'afip.php-master/src/Afip.php';
//include_once "FE_Pack_WSFE/config.php";
//include_once "FE_Pack_WSFE/afip/AfipWsaa.php";
//include_once "FE_Pack_WSFE/afip/AfipWsfev1.php";
define('FC_A10','112');
define('SERIE_A10','49');
$num_factura = "";
$fecha_hoy = date('d/m/Y');
mysqli_select_db($amercado, $database_amercado);
$lo_limite = 0;
$query_comprobante = sprintf("SELECT * FROM series  WHERE series.codnum = %s", "1");
$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
$row_comprobante = mysqli_fetch_assoc($comprobante);
$totalRows_comprobante = mysqli_num_rows($comprobante);
$num_comp = ($row_comprobante['nroact'])+1 ; 

$usuario = CurrentUserName();
//echo "USUARIO ".$usuario."  ";
// LEO EL USUARIO
mysqli_select_db($amercado, $database_amercado);
$cod_usuario = 1;
$usuario = "\"".$usuario."\"";
$query_usuarios = sprintf("SELECT * FROM usuarios WHERE usuario = %s",$usuario);
//echo "QUERY = ".$query_usuarios."   ";
$res_usuarios = mysqli_query($amercado, $query_usuarios) or  die("ERROR LEYENDO USUARIOS");
$row_usuarios = mysqli_fetch_assoc($res_usuarios);
$cod_usuario = $row_usuarios['codnum'];
$cod_usuario = 1;

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$sigo_y_grabo = 0;

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
    
    //=========================================================================================
	//Datos Correspondientes a la factura
    /**
     * Para conocer que dato va en cada parametro se puede consultar el archivo:
     * - fe/docs/Estructura de Datos del Request de WSFEv1.docx
     * Y para mas informacion se puede revisar el manual del WS
     * - fe/docs/manual_desarrollador_COMPG_v2.pdf 
     */
    //Cabecera
    $afip = new Afip(array('CUIT' => 30710183437,
                            'production' => TRUE,
                          'key' 		=> 'amercado4_prod.key',
                          'cert' 		=> 'amercado4_prod.crt'));
    /**
     * Numero del punto de venta
     **/
    $punto_de_venta = 10;

    /**
     * Tipo de factura
     **/
    $tipo_de_factura = 201; // 201 = Factura de Cr�dito electr�nica MiPyMEs (FCE) A

    /**
     * N�mero de la ultima Factura de Cr�dito electr�nica MiPyMEs (FCE) A
     **/
    $last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_factura);

    /**
     * Concepto de la factura
     *
     * Opciones:
     *
     * 1 = Productos 
     * 2 = Servicios 
     * 3 = Productos y Servicios
     **/
    $concepto = 2;

    /**
     * Tipo de documento del comprador
     *
     * Opciones:
     *
     * 80 = CUIT 
     * 86 = CUIL 
     * 96 = DNI
     * 99 = Consumidor Final 
     **/
    $tipo_de_documento = 80;

    
    /**
     * Numero de factura
     **/
    $numero_de_factura = $last_voucher+1;
    $CbteDesde = $numero_de_factura;
    /**
     * Numero de CBU del cliente
     * Es requerido para realizar una factura de cr�dito electr�nica
     **/
    $CBU = '0170123020000000738338';

    /**
     * Fecha de la factura en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
     **/
    $fecha = date('Y-m-d');

    /**
     * Fecha de vencimiento del pago en formato aaaa-mm-dd
     **/
    $fecha_vencimiento_pago = date('Y-m-d');

    $query_cliente2 = sprintf("SELECT * FROM entidades WHERE codnum = %s",GetSQLValueString($_POST['codnum'],"int"));
	$cliente2 = mysqli_query( $query_cliente2, $amercado) or die("ERROR LEYENDO ENTIDADES");
	$row_cliente2 = mysqli_fetch_assoc($cliente2);
	
    $cuit_enti = $row_cliente2['cuit'];
	if (isset($cuit_enti)) {
		$cuit_enti2 = str_replace("-","",$cuit_enti);
	}
	//$DocNro = $cuit_enti2; 
    /**
     * Numero de documento del comprador (0 para consumidor final)
     **/
    $numero_de_documento = $cuit_enti2;
    /**
     * Estos dos parametros representan el numero de factura desde/hasta pero deben ser iguales
     * Se obtienen mediante el metodo: $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);
     * 
     * $CbteDesde = $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);;
     * $CbteHasta = $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);;
     */
    //$CbteDesde = $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);//1; 
	//$CbteHasta = $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);//1; 

    //$CbteFch      = intval(date('Ymd'));
    /**
     * Los siguientes campos solo son obligatorios para los conceptos 2 y 3
     **/
    if ($concepto === 2 || $concepto === 3) {
        /**
         * Fecha de inicio de servicio en formato aaaammdd
         **/
        $fecha_servicio_desde = intval(date('Ymd'));

        /**
         * Fecha de fin de servicio en formato aaaammdd
         **/
        $fecha_servicio_hasta = intval(date('Ymd'));
    }
    else {
        $fecha_servicio_desde = null;
        $fecha_servicio_hasta = null;
    }
    $ImpTotal     = $_POST['tot_general']; 
    $ImpTotConc   = 0.00; 
    $importe_gravado = $_POST['totneto21'];
    $importe_exento_iva = 0.00;
    $importe_iva  = $_POST['totiva21'];
    $ImpTrib      = 0.00;
    $FchServDesde = intval(date('Ymd'));
    $FchServHasta = intval(date('Ymd'));
    $FchVtoPago   = intval(date('Ymd'));
    $MonId        = 'PES'; // Pesos (AR) - Ver - AfipWsfev1::FEParamGetTiposMonedas()
    $MonCotiz     = 1.00;

    
	//Informacion para agregar al array Tributos
    /** 
     * Esto aplica si las facturas tienen tributos agregados
     */
    $tributoId = null; // Ver - AfipWsfev1::FEParamGetTiposTributos()
    $tributoDesc = null;
    $tributoBaseImp = null;
    $tributoAlic = null;
    $tributoImporte = null;

    //Informacion para agregar el array IVA
	if (isset($_POST['totneto105']) && $_POST['totneto105'] != 0.00  && ((isset($_POST['totneto21']) && $_POST['totneto21'] != 0.00) || (isset($_POST['totcomis']) && $_POST['totcomis'] != 0.00) || (isset($_POST['totimp']) && $_POST['totimp'] != 0.00))) {
		$IvaAlicuotaId_1 = 4; // 10.5% Ver - AfipWsfev1::FEParamGetTiposIva()
		$IvaAlicuotaBaseImp_1 = $_POST['totneto105'];
		$IvaAlicuotaImporte_1 = $_POST['totiva105'];
	
		$IvaAlicuotaId_2 = 5; // 21% Ver - AfipWsfev1::FEParamGetTiposIva()
		$IvaAlicuotaBaseImp_2 = $_POST['totneto21']  + $_POST['totcomis'] + $_POST['totimp']; 
		$IvaAlicuotaImporte_2 = $_POST['totiva21'];
		
         $data = array(
            'CantReg' 	=> 1, // Cantidad de facturas a registrar
            'PtoVta' 	=> $punto_de_venta,
            'CbteTipo' 	=> $tipo_de_factura, 
            'Concepto' 	=> $concepto,
            'DocTipo' 	=> $tipo_de_documento,
            'DocNro' 	=> $numero_de_documento,
            'CbteDesde' => $numero_de_factura,
            'CbteHasta' => $numero_de_factura,
            'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
            'FchServDesde'  => $fecha_servicio_desde,
            'FchServHasta'  => $fecha_servicio_hasta,
            'FchVtoPago'    => intval(str_replace('-', '', $fecha_vencimiento_pago)),
            'ImpTotal' 	=> $importe_gravado + $importe_iva + $importe_exento_iva,
            'ImpTotConc'=> 0, // Importe neto no gravado
            'ImpNeto' 	=> $importe_gravado,
            'ImpOpEx' 	=> $importe_exento_iva,
            'ImpIVA' 	=> $importe_iva,
            'ImpTrib' 	=> 0, //Importe total de tributos
            'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
            'MonCotiz' 	=> 1, // Cotizaci�n de la moneda usada (1 para pesos argentinos)  
           'Iva' 		=> array( // Al�cuotas asociadas al factura
            array(
                'Id' 		=> $IvaAlicuotaId_2, // Id del tipo de IVA (5 = 21%)
                'BaseImp' 	=> number_format(abs($IvaAlicuotaBaseImp_2),2,'.',''),  //$importe_gravado,
                'Importe' 	=> number_format(abs($IvaAlicuotaImporte_2),2,'.','')   //$importe_iva 
            ),
            array(
                'Id' 		=> $IvaAlicuotaId_1, //4, // Id del tipo de IVA (4 = 10,5%)
                'BaseImp' 	=> number_format(abs($IvaAlicuotaBaseImp_1),2,'.',''),  //$importe_gravado,
                'Importe' 	=> number_format(abs($IvaAlicuotaImporte_1),2,'.','')   //$importe_iva 
                )
            ),
            'Opcionales' => array( // (Opcional) Al�cuotas asociadas al comprobante
                array(
                    'Id'	=> 2101, // ID del campo opcion opcional (2101 = CBU)
                    'Valor'	=> $CBU
                ),
                array(
                    'Id'	=> 27, // ID del campo opcion opcional (27 = Tipo de negociaci�n)
                    'Valor'	=> "SCA"
                )
            ),

        );
   
	}
	else {
		if (isset($_POST['totneto105']) && $_POST['totneto105'] != 0.00) {
			$IvaAlicuotaId = 4; // 10.5% Ver - AfipWsfev1::FEParamGetTiposIva()
			$IvaAlicuotaBaseImp = $_POST['totneto105'];
			$IvaAlicuotaImporte = $_POST['totiva105'];
            $data = array(
                'CantReg' 	=> 1, // Cantidad de facturas a registrar
                'PtoVta' 	=> $punto_de_venta,
                'CbteTipo' 	=> $tipo_de_factura, 
                'Concepto' 	=> $concepto,
                'DocTipo' 	=> $tipo_de_documento,
                'DocNro' 	=> $numero_de_documento,
                'CbteDesde' => $numero_de_factura,
                'CbteHasta' => $numero_de_factura,
                'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
                'FchServDesde'  => $fecha_servicio_desde,
                'FchServHasta'  => $fecha_servicio_hasta,
                'FchVtoPago'    => intval(str_replace('-', '', $fecha_vencimiento_pago)),
                'ImpTotal' 	=> $importe_gravado + $importe_iva + $importe_exento_iva,
                'ImpTotConc'=> 0, // Importe neto no gravado
                'ImpNeto' 	=> $importe_gravado,
                'ImpOpEx' 	=> $importe_exento_iva,
                'ImpIVA' 	=> $importe_iva,
                'ImpTrib' 	=> 0, //Importe total de tributos
                'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
                'MonCotiz' 	=> 1, // Cotizaci�n de la moneda usada (1 para pesos argentinos)  
                'Iva' 		=> array(// Al�cuotas asociadas al factura
                    
                        'Id' 		=> $IvaAlicuotaId, // Id del tipo de IVA (4 = 10,5%)
                        'BaseImp' 	=> number_format(abs($IvaAlicuotaBaseImp),2,'.',''),  //$importe_gravado,
                        'Importe' 	=> number_format(abs($IvaAlicuotaImporte),2,'.','')   //$importe_iva 
                    
                    ),
                 'Opcionales' => array( // (Opcional) Al�cuotas asociadas al comprobante
                    array(
                        'Id'	=> 2101, // ID del campo opcion opcional (2101 = CBU)
                        'Valor'	=> $CBU
                    ),
                    array(
                        'Id'	=> 27, // ID del campo opcion opcional (27 = Tipo de negociaci�n)
                        'Valor'	=> "SCA"
                    )
                ),
                
            );
		}
		else {
	    	$IvaAlicuotaId = 5; // 21% Ver - AfipWsfev1::FEParamGetTiposIva()
	    	$IvaAlicuotaBaseImp = $_POST['totneto21'];
    		$IvaAlicuotaImporte = $_POST['totiva21'] ;//21.00;  
            $data = array(
                'CantReg' 	=> 1, // Cantidad de facturas a registrar
                'PtoVta' 	=> $punto_de_venta,
                'CbteTipo' 	=> $tipo_de_factura, 
                'Concepto' 	=> $concepto,
                'DocTipo' 	=> $tipo_de_documento,
                'DocNro' 	=> $numero_de_documento,
                'CbteDesde' => $numero_de_factura,
                'CbteHasta' => $numero_de_factura,
                'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
                'FchServDesde'  => $fecha_servicio_desde,
                'FchServHasta'  => $fecha_servicio_hasta,
                'FchVtoPago'    => intval(str_replace('-', '', $fecha_vencimiento_pago)),
                'ImpTotal' 	=> $importe_gravado + $importe_iva + $importe_exento_iva,
                'ImpTotConc'=> 0, // Importe neto no gravado
                'ImpNeto' 	=> $importe_gravado,
                'ImpOpEx' 	=> $importe_exento_iva,
                'ImpIVA' 	=> $importe_iva,
                'ImpTrib' 	=> 0, //Importe total de tributos
                'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
                'MonCotiz' 	=> 1, // Cotizaci�n de la moneda usada (1 para pesos argentinos)  
                'Iva' 		=> array(// Al�cuotas asociadas al factura

                        'Id' 		=> $IvaAlicuotaId, // Id del tipo de IVA (5 = 21%)
                        'BaseImp' 	=> number_format(abs($IvaAlicuotaBaseImp),2,'.',''),  //$importe_gravado,
                        'Importe' 	=> number_format(abs($IvaAlicuotaImporte),2,'.','')   //$importe_iva 

                    ),
                 'Opcionales' => array( // (Opcional) Al�cuotas asociadas al comprobante
                    array(
                        'Id'	=> 2101, // ID del campo opcion opcional (2101 = CBU)
                        'Valor'	=> $CBU
                    ),
                    array(
                        'Id'	=> 27, // ID del campo opcion opcional (27 = Tipo de negociaci�n)
                        'Valor'	=> "SCA"
                    )
                ),

            );
		}
 
	}
   
	//======================================================================================
	//LA MANDO AL WS
	$sigo_y_grabo = 0;
	//======================================================================================

    /** 
     * Creamos la Factura 
     **/
    //print_r($data);
    
    $res = $afip->ElectronicBilling->CreateVoucher($data);
    
    //print_r($res);

    var_dump(array(
        'cae' => $res['CAE'], //CAE asignado a la Factura
        'vencimiento' => $res['CAEFchVto'] //Fecha de vencimiento del CAE
    ));
    $CAE = $res['CAE'];
    $CAEFchVto = $res['CAEFchVto'];
    //$Resultado = $res['Resultado'];
    $Resultado = "A";
    $Observaciones = ""; //$res['Observaciones'];
    $num_fac = $numero_de_factura;
    if ($CAE == "") {
        $sigo_y_grabo = 0;
        print_r($Observaciones);
    }
    else 
        $sigo_y_grabo = 1;
	//========================================================================================
	
}
	//====================================================================================
	$renglones = 0;
	$primera_vez = 1;
	if ($sigo_y_grabo == 1) {
		if (isset($_POST['descripcion']) && GetSQLValueString($_POST['descripcion'], "text")!="NULL") {
			// DESDE ACA ===================================================================================
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
				mysqli_select_db($amercado, $database_amercado);
				$actualiza1 = sprintf("UPDATE `series` SET `nroact` = %s WHERE `series`.`codnum` = %s", GetSQLValueString($_POST['num_factura'], "int"), 			GetSQLValueString($_POST['serie'], "int")) ;				 
				$resultado=mysqli_query($amercado,	$actualiza1);	

			}
			// HASTA ACA ===================================================================================
            $descrip = GetSQLValueString($_POST['descripcion'], "text");
            if ($descrip[0] == " ")
                $descrip = substr($descrip,1,70);
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  				$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem , descrip, neto,  concafac, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $descrip, //GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['importe'], "double"),
					   GetSQLValueString($_POST['secuencia'], "int"),
						$cod_usuario);

  				mysqli_select_db($amercado, $database_amercado);
  				$renglones = 1;
  				$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

			}
		}

		if (isset($_POST['descripcion1']) && GetSQLValueString($_POST['descripcion1'], "text")!="NULL") {
            $descrip1 = GetSQLValueString($_POST['descripcion1'], "text");
            if ($descrip1[0] == " ")
                $descrip1 = substr($descrip1,1,70);
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  				$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia1'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia1'], "int"),
                       $descrip1, //GetSQLValueString($_POST['descripcion1'], "text"),
                       GetSQLValueString($_POST['importe1'], "double"),
                       GetSQLValueString($_POST['secuencia1'], "double"),
					   $cod_usuario);

  				mysqli_select_db($amercado, $database_amercado);
  				$renglones = 2;
  				$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

			}
		}

		if (isset($_POST['descripcion2']) && GetSQLValueString($_POST['descripcion2'], "text")!="NULL") {
            $descrip2 = GetSQLValueString($_POST['descripcion2'], "text");
            if ($descrip2[0] == " ")
                $descrip2 = substr($descrip2,1,70);
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  				$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia2'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia2'], "int"),
                       $descrip2, //GetSQLValueString($_POST['descripcion2'], "text"),
                       GetSQLValueString($_POST['importe2'], "double"),
                       GetSQLValueString($_POST['comision2'], "double"),
						$cod_usuario);

  				mysqli_select_db($amercado, $database_amercado);
  				$renglones = 3;
  				$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
 
			}
		}

		if (isset($_POST['descripcion3']) && GetSQLValueString($_POST['descripcion3'], "text")!="NULL") {
            $descrip3 = GetSQLValueString($_POST['descripcion3'], "text");
            if ($descrip3[0] == " ")
                $descrip3 = substr($descrip3,1,70);
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  				$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia3'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia3'], "int"),
                       $descrip3, //GetSQLValueString($_POST['descripcion3'], "text"),
                       GetSQLValueString($_POST['importe3'], "double"),
                       GetSQLValueString($_POST['comision3'], "double"),
                       $cod_usuario);

  				mysqli_select_db($amercado, $database_amercado);
  				$renglones = 4;
  				$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

			}
		}

		if (isset($_POST['descripcion4']) && GetSQLValueString($_POST['descripcion4'], "text")!="NULL") {
            $descrip2 = GetSQLValueString($_POST['descripcion2'], "text");
            if ($descrip4[0] == " ")
                $descrip4 = substr($descrip4,1,70);
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  				$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto,comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia4'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia4'], "int"),
                       $descrip4, //GetSQLValueString($_POST['descripcion4'], "text"),
					   GetSQLValueString($_POST['importe4'], "double"),
                       GetSQLValueString($_POST['comision4'], "double"),
						$cod_usuario);

  				mysqli_select_db($amercado, $database_amercado);
  				$renglones = 5;
  				$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));

			}
		}

		if (isset($_POST['descripcion5']) && GetSQLValueString($_POST['descripcion5'], "text")!="NULL") {
            $descrip5 = GetSQLValueString($_POST['descripcion2'], "text");
            if ($descrip5[0] == " ")
                $descrip5 = substr($descrip5,1,70);
			if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  				$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
					   GetSQLValueString($_POST['secuencia5'], "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia5'], "int"),
                       $descrip5, //GetSQLValueString($_POST['descripcion5'], "text"),
                       GetSQLValueString($_POST['importe5'], "double"),
                       GetSQLValueString($_POST['comision5'], "double"),
						$cod_usuario);

  				mysqli_select_db($amercado, $database_amercado);
  				$renglones = 6;
  				$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
 
		}
	}
	
	if (isset($_POST['descripcion6']) && GetSQLValueString($_POST['descripcion6'], "text")!="NULL") {
	
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia6'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia6'], "int"),
						   GetSQLValueString($_POST['descripcion6'], "text"),
						   GetSQLValueString($_POST['importe6'], "double"),
						   GetSQLValueString($_POST['comision6'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 7;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		}
	}
	
	if (isset($_POST['descripcion7']) && GetSQLValueString($_POST['descripcion7'], "text")!="NULL") {
	
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia7'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia7'], "int"),
						   GetSQLValueString($_POST['descripcion7'], "text"),
						   GetSQLValueString($_POST['importe7'], "double"),
						   GetSQLValueString($_POST['comision7'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 8;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		}
	}
	
	if (isset($_POST['descripcion8']) && GetSQLValueString($_POST['descripcion8'], "text")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia8'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia8'], "int"),
						   GetSQLValueString($_POST['descripcion8'], "text"),
						   GetSQLValueString($_POST['importe8'], "double"),
						   GetSQLValueString($_POST['comision8'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 9;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	 
		}
	}
	
	if (isset($_POST['descripcion9']) && GetSQLValueString($_POST['descripcion9'], "text")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia9'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia9'], "int"),
						   GetSQLValueString($_POST['descripcion9'], "text"),
						   GetSQLValueString($_POST['importe9'], "double"),
						   GetSQLValueString($_POST['comision9'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 10;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		}
	}
	
	if (isset($_POST['descripcion10']) && GetSQLValueString($_POST['descripcion10'], "text")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia10'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia10'], "int"),
						   GetSQLValueString($_POST['descripcion10'], "text"),
						   GetSQLValueString($_POST['importe10'], "double"),
						   GetSQLValueString($_POST['comision10'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 11;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		}
	}
	
	if (isset($_POST['descripcion11']) && GetSQLValueString($_POST['descripcion11'], "text")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia11'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia11'], "int"),
						   GetSQLValueString($_POST['descripcion11'], "text"),
						   GetSQLValueString($_POST['importe11'], "double"),
						   GetSQLValueString($_POST['comision11'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 12;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		}
	}
	
	if (isset($_POST['descripcion12']) && GetSQLValueString($_POST['descripcion12'], "text")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia12'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia12'], "int"),
						   GetSQLValueString($_POST['descripcion12'], "text"),
						   GetSQLValueString($_POST['importe12'], "double"),
						   GetSQLValueString($_POST['comision12'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 13;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		}
	}
	
	if (isset($_POST['descripcion13']) && GetSQLValueString($_POST['descripcion13'], "text")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia13'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia13'], "int"),
						   GetSQLValueString($_POST['descripcion13'], "text"),
						   GetSQLValueString($_POST['importe13'], "double"),
						   GetSQLValueString($_POST['comision13'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 14;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		}
	}
	
	if (isset($_POST['descripcion14']) && GetSQLValueString($_POST['descripcion14'], "text")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, nreng, codrem, concafac, descrip, neto, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['secuencia14'], "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['secuencia14'], "int"),
						   GetSQLValueString($_POST['descripcion14'], "text"),
						   GetSQLValueString($_POST['importe14'], "double"),
						   GetSQLValueString($_POST['comision14'], "double"),
							$cod_usuario);
	
			mysqli_select_db($amercado, $database_amercado);
			$renglones = 15;
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		}
	}
	
	// Crea la mascara 
	
	if (isset($_POST['descripcion']) && GetSQLValueString($_POST['descripcion'], "text")!="NULL") {
	
		$tcomp = $_POST['tcomp'];
		
		$serie = 49; // $_POST['serie'];
		
		
		$num_fac = $CbteDesde; //&$_POST['num_factura'];
		$query_mascara = "SELECT * FROM series  WHERE  series.codnum='$serie'";
		$mascara = mysqli_query($amercado, $query_mascara) or die(mysqli_error($amercado));
		$row_mascara = mysqli_fetch_assoc($mascara);
		$totalRows_mascara = mysqli_num_rows($mascara);
		$mascara  = $row_mascara['mascara'];
	 
		if ($num_fac <10) {
			$mascara=$mascara."-"."0000000".$num_fac ;
		}
	
		if ($num_fac >9 && $num_fac <=99) {
			$mascara=$mascara."-"."000000".$num_fac;
		}
	
		if ($num_fac >99 && $num_fac <=999) {
			$mascara=$mascara."-"."00000".$num_fac;
		}
	  
		if ($num_fac >999 && $num_fac <=9999) {
			$mascara=$mascara."-"."0000".$num_fac;
		}
		if ($num_fac >9999 && $num_fac <=99999) {
			$mascara=$mascara."-"."000".$num_fac;
		}
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
	
			$fecha_factura1 =$_POST['fecha_factura1'] ;
			$fecha_factura1 = substr($fecha_factura1,6,4)."-".substr($fecha_factura1,3,2)."-".substr($fecha_factura1,0,2);
	
			$insertSQL = sprintf("INSERT INTO cabfac (tcomp, serie, ncomp, fecval, fecdoc, fecreg, cliente, fecvenc, estado, emitido, codrem, totneto, totbruto, totiva21, totneto21, nrengs, nrodoc, en_liquid, CAE, CAEFchVto, Resultado, usuario, usuarioultmod, fecultmod) VALUES (%s, %s, %s, '$fecha_factura1','$fecha_factura1', '$fecha_factura1', %s, '$fecha_factura1', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,'$cod_usuario', '$cod_usuario', '$fecha_factura1')",
						   GetSQLValueString($_POST['tcomp'], "int"),
						   GetSQLValueString($_POST['serie'], "int"),
						   $num_fac, //GetSQLValueString($_POST['num_factura'], "int"),
						   GetSQLValueString($_POST['codnum'], "int"),
						   GetSQLValueString("P", "text"), //GetSQLValueString($_POST['GrupoOpciones1'], "text"), 
						   GetSQLValueString("0", "int"),
						   GetSQLValueString($_POST['remate_num'], "int"),
						   GetSQLValueString($_POST['totneto'], "double"),
						   GetSQLValueString($_POST['tot_general'], "double"),
						   GetSQLValueString($_POST['totiva21'], "double"),
						   GetSQLValueString($_POST['totneto21'], "double"),
						   GetSQLValueString($renglones, "int"),
						   GetSQLValueString($mascara, "text"),
							$lo_limite == 0 ?
						   GetSQLValueString($_POST['GrupoOpciones2'], "int") :
						   GetSQLValueString("0", "int"),
						   $CAE,
						   $CAEFchVto,
						   GetSQLValueString($Resultado, "text"));
						 

			mysqli_select_db($amercado, $database_amercado);
			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
	
		
			if (!empty($_POST['imprime'])) { 
				$facnum = GetSQLValueString($_POST['num_factura'], "int");
				$tipcomp = GetSQLValueString($_POST['tcomp'], "int");
				$numserie = GetSQLValueString($_POST['serie'], "int");
				$insertGoTo = "rp_facncA.php?ftcomp=$tipcomp&&fserie=$numserie&&fncomp=$num_fac";
				if (isset($_SERVER['QUERY_STRING'])) {
					$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
					$insertGoTo .= $_SERVER['QUERY_STRING'];
				}
				header(sprintf("Location: %s", "rp_facncA.php?ftcomp=$tipcomp&&fserie=$numserie&&fncomp=$num_fac")); 
	
			} else { 
				$facnum = $CbteDesde; //GetSQLValueString($_POST['num_factura'], "int");
				$insertGoTo = "facturaCA_ok.php?factura=$facnum";
				if (isset($_SERVER['QUERY_STRING'])) {
					$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
					$insertGoTo .= $_SERVER['QUERY_STRING'];
				}
				header(sprintf("Location: %s", "facturaCA_ok.php?factura=$facnum")); 
			}
		}
	}
}

mysqli_select_db($amercado, $database_amercado);
$query_facturas_a = "SELECT * FROM series  WHERE series.codnum=49"; // antes decia 1 que es por lotes
$facturas_a = mysqli_query($amercado, $query_facturas_a) or die("ERROR LEYENDO SERIES A");
$row_facturas_a = mysqli_fetch_assoc($facturas_a);
$totalRows_facturas_a = mysqli_num_rows($facturas_a);
$facturanum1 = ($row_facturas_a['nroact'])+1;
// Agrega Mascara 
$mascara1      = $row_facturas_a['mascara']; 

$tcomp = 52;
mysqli_select_db($amercado, $database_amercado);
$query_facturas_b = "SELECT * FROM series  WHERE series.tipcomp=30"; // antes decia 23 que es por lotes
$facturas_b = mysqli_query($amercado, $query_facturas_b) or die("ERROR LEYENDO SERIES B");
$row_facturas_b = mysqli_fetch_assoc($facturas_b);
$totalRows_facturas_b = mysqli_num_rows($facturas_b);
$facturanum2 = ($row_facturas_b['nroact'])+1;
// DESDE ACA LA MASCARA
$mascara2    = $row_facturas_b['mascara'];
/*
if ($mascara1='') {
 	$mascara = $mascara2 ;
	if ($facturanum2 <10) {
		$mascara=$mascara."-"."0000000".$facturanum2 ;
	}

	if ($facturanum2 >9 && $facturanum2 <99) {
		$mascara=$mascara."-"."000000".$facturanum2;
	}

	if ($facturanum2 >99 && $facturanum2 <999) {
		$mascara=$mascara."-"."00000".$facturanum2;
	}
	if ($facturanum2 >999 && $facturanum2 <9999) {
		$mascara=$mascara."-"."0000".$facturanum2;
	}


} else {

 	$mascara = $mascara1 ;
 	if ($facturanum1 <10) {
		$mascara=$mascara."-"."0000000".$facturanum1 ;
	}

	if ($facturanum1 >9 && $facturanum1 <99) {
		$mascara=$mascara."-"."000000".$facturanum1;
	}

	if ($facturanum1 >99 && $facturanum1 <999) {
		$mascara=$mascara."-"."00000".$facturanum1;
	}
	if ($facturanum1 >999 && $facturanum1 <9999) {
		$mascara=$mascara."-"."0000".$facturanum1;
	}
}
*/
// HASTA ACA LA MASCARA 

mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE codnum='112'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die("ERROR LEYENDO TIPCOMP ");
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

// CON UN IF, DEFINIR SEGUN SEA FC A O FC B; QUE EL IVA DEL CLIENTE SE CORRESPONDA:

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE (tipoent = '1' OR tipoent = '2') AND activo = '1' AND (tipoiva = '1' OR tipoiva = '3') ORDER BY razsoc ASC";
$cliente = mysqli_query($amercado, $query_cliente) or die("ERROR LEYENDO ENTIDADES ".$query_cliente);
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

$colname_serie = "49";
if (isset($_POST['tcomp'])) {
  	$colname_serie = addslashes($_POST['tcomp']);
}

mysqli_select_db($amercado, $database_amercado);
$query_conceptos_a_facturar = "SELECT * FROM concafactven WHERE impuesto = 1 ORDER BY nroconc  ASC";
$conceptos_a_facturar = mysqli_query($amercado, $query_conceptos_a_facturar) or die("ERROR LEYENDO CONCEPTOS ".$query_conceptos_a_facturar);
$row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
$totalRows_conceptos_a_facturar = mysqli_num_rows($conceptos_a_facturar);

$en_liquid = 0;
$query_num_remates = "SELECT * FROM remates WHERE servicios IS NOT NULL AND gastos IS NOT NULL AND codcli != 940 AND fecest >= NOW() ORDER BY `ncomp` desc";
//$query_num_remates = sprintf("SELECT * FROM `remates` WHERE `fecest` >= NOW() ORDER BY `ncomp` desc");
$num_remates = mysqli_query($amercado, $query_num_remates) or die("ERROR LEYENDO REMATES");
$row_num_remates = mysqli_fetch_assoc($num_remates);
$totalRows_num_remates = mysqli_num_rows($num_remates);

$query_impuesto = "SELECT * FROM impuestos";
$impuesto = mysqli_query($amercado, $query_impuesto) or die("ERROR LEYENDO IMPUESTOS");
$row_impuesto = mysqli_fetch_assoc($impuesto);
$totalRows_impuesto = mysqli_num_rows($impuesto);
$iva_21_desc = mysqli_result($impuesto,0,2)."<br>";
$iva_21_porcen = mysqli_result($impuesto,0,1);

$iva_15_desc = mysqli_result($impuesto,1,2)."<br>";
$iva_15_porcen = mysqli_result($impuesto,1,1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php require_once('Connections/amercado.php');  ?>

<script language="javascript">
function sin_lotes(form)
{
	alert("Debe ingresar al menos un concepto para facturar");
}
</script> 
<script language="javascript">
function ValidoForm(form)
{
		// DESDE ACA
	var id_remate = form.remate_num.value ; // ID del Remate
	var usuario = form.cod_usuario.value ;  // Codigo de usuario
    var cliente = form.cliente.value; // Codigo de cliente
	
  	var error ="";
  	
	 //alert ("imp ="+tipoiva);
	 //alert ("usuario ="+usuario);
	
	
	
	if (id_remate=="" && usuario != 25 && usuario != 26) {
      	     	
	    if (id_remate=="") {
        	error = error+"      Id de Remate\n"; 
        }
	 			 
		alert ("Faltan los siguientes datos :\n"+error);
	} 
	//HASTA ACA
}
</script>

<script language="javascript">
function validarFormulario(form)
{
	var series = form.serie.value  // serie 
	var imp21  = form.iva21.value/100; // impuesto 21 %	
	var monto  = form.importe.value; // Monto  primer concepto
	var monto1 = form.importe1.value; // Monto segundo concepto
	var monto2 = form.importe2.value; // Monto tercer concepto
	var monto3 = form.importe3.value; // Monto cuarto concepto
	var monto4 = form.importe4.value;  // Monto Quinto concepto
	var monto5 = form.importe5.value; // Monto Sexto concepto
	var monto6  = form.importe6.value; // Monto Septimo concepto
	var monto7  = form.importe7.value; // Monto Octavo concepto
	var monto8  = form.importe8.value; // Monto Noveno concepto
	var monto9  = form.importe9.value; // Monto D�cimo concepto
	var monto10 = form.importe10.value;  // Monto Onceavo concepto
	var monto11 = form.importe11.value; // Monto Doceavo concepto
	var monto12 = form.importe12.value; // Monto Treceavo concepto
	var monto13 = form.importe13.value; // Monto Catorceavo concepto
	var monto14 = form.importe14.value; // Monto Quinceavo concepto
	
	var porciva  = form.tipoiva.value / 100; // %IVA  primer concepto
	var porciva1 = form.tipoiva1.value / 100; // %IVA segundo concepto
	var porciva2 = form.tipoiva2.value / 100; // %IVA tercer concepto
	var porciva3 = form.tipoiva3.value / 100; // %IVA cuarto concepto
	var porciva4 = form.tipoiva4.value / 100;  // %IVA Quinto concepto
	var porciva5 = form.tipoiva5.value / 100; // %IVA Sexto concepto
	var porciva6  = form.tipoiva6.value / 100; // %IVA Septimo concepto
	var porciva7  = form.tipoiva7.value / 100; // %IVA Octavo concepto
	var porciva8  = form.tipoiva8.value / 100; // %IVA Noveno concepto
	var porciva9  = form.tipoiva9.value / 100; // %IVA D�cimo concepto
	var porciva10 = form.tipoiva10.value / 100;  // %IVA Onceavo concepto
	var porciva11 = form.tipoiva11.value / 100; // %IVA Doceavo concepto
	var porciva12 = form.tipoiva12.value / 100; // %IVA Treceavo concepto
	var porciva13 = form.tipoiva13.value / 100; // %IVA Catorceavo concepto
	var porciva14 = form.tipoiva14.value / 100; // %IVA Quinceavo concepto
	 //alert ("TIPO IVA =" + porciva);
	
   	var tot_mon =  0 ;
	var tot_mon_ex =  0 ;
	var tot_mon_1 =  0 ;
	var tot_mon_2 =  0 ;
	var tot_mon_3 =  0 ;
	var tot_mon_4 =  0 ;
	var tot_mon_5 =  0 ;
	var tot_mon_6 =  0 ;
	var tot_mon_7 =  0 ;
	var tot_mon_8 =  0 ;
	var tot_mon_9 =  0 ;
	var tot_mon_10 =  0 ;
	var tot_mon_11 =  0 ;
	var tot_mon_12 =  0 ;
	var tot_mon_13 =  0 ;
	var tot_mon_14 =  0 ;
	
   	var imp_21 =   0 ;
	var imp_21_1 =  0 ;
	var imp_21_2 =  0 ;
	var imp_21_3 =  0 ;
	var imp_21_4 =  0 ;
	var imp_21_5 =  0 ;
	var imp_21_6 =  0 ;
	var imp_21_7 =  0 ;
	var imp_21_8 =  0 ;
	var imp_21_9 =  0 ;
	var imp_21_10 = 0 ;
	var imp_21_11 = 0 ;
	var imp_21_12 = 0 ;
	var imp_21_13 = 0 ;
	var imp_21_14 = 0 ;
		
	var tot_monto    = 0;
	var imp_tot_21   = 0;
	var tot_general  = 0;
	// PRIMER CONCEPTO
	
	if (monto.length!=0) {
		//alert("ENTRO EN CONCEPTO 1");
		if (porciva===0) {
			//alert ("IVA 0 ");
			var tot_mon_ex = eval(monto); 	// Monto de Primer Lote
			var imp_tot_21 = 0;
			var	tot_general = eval(monto);	// Total general
		}
		else {
			//alert ("IVA  != 0 ");
			var tot_mon = eval(monto); 	// Monto de Primer Lote
			var imp_tot_21 = eval(monto+('*')+porciva);   // Impuesto Monto 1 
	  		var	tot_general = eval(monto+('+')+imp_tot_21);	// Total general
		}
		form.totneto.value = tot_mon_ex.toFixed(2) ;	
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
		
		 
	} 
	
	
	// SEGUNDO LOTE
	if (monto1.length!=0) {
		//porciva1 = form.tipoiva1.value / 100;
		imp_21_1 = eval(monto1+('*')+porciva1);        // Impuesto Monto 2 
		var tot_mon21_1 = eval(monto1+('+')+imp_21_1);	// Total renglon 2
		
		tot_mon = eval(tot_mon+('+')+monto1);
		imp_tot_21 = eval(imp_tot_21+('+')+imp_21_1);
		tot_general = eval(tot_general+('+')+tot_mon21_1)
	
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
		//strAlerta = "Total neto 2 = " + form.tot_totneto21.value + "\n"  + "Total IVA 2 = " + form.totiva21.value + "\n" + "Total general 2 = " + form.tot_general.value + "\n" + "porciva =  " + porciva + "\n";
		// lanzamos la acci�n
		//alert(strAlerta);			 
	} 
	
	// TERCER LOTE
	if (monto2.length!=0) {
		//porciva2 = forma.tipoiva2.value / 100;
		imp_21_2 = eval(monto2+('*')+porciva2);         // Impuesto Monto 3
		var tot_mon21_2 = eval(monto2+('+')+imp_21_2);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto2);
		tot_mon21_2 = eval(monto2+('+')+imp_21_2);	// Total renglon 3
	   	imp_tot_21 = eval(imp_tot_21+('+')+imp_21_2);
	   	tot_general = eval(tot_general+('+')+tot_mon21_2) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
	} 
		
	// CUARTO LOTE
	if (monto3.length!=0) {
		//porciva3 = form.tipoiva3.value / 100;
		imp_21_3 = eval(monto3+('*')+porciva3);         // Impuesto Monto 1 al 21 %
		var tot_mon21_3 = eval(monto3+('+')+imp_21_3);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto3);
		tot_mon21_3 = eval(monto3+('+')+imp_21_3);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_3);
	   	tot_general = eval(tot_general+('+')+tot_mon21_3) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
			
			 
	} 
		
	// QUINTO LOTE
	if (monto4.length!=0) {
		//porciva4 = form.tipoiva4.value / 100;
		imp_21_4 = eval(monto4+('*')+porciva4);              // Impuesto Monto 1 al 21 %
		var tot_mon21_4 = eval(monto4+('+')+imp_21_4);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto4);
		tot_mon21_4 = eval(monto4+('+')+imp_21_4);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_4);
	   	tot_general = eval(tot_general+('+')+tot_mon21_4) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
		
	} 
	// SEXTO LOTE
	if (monto5.length!=0) {
		//porciva5 = form.tipoiva5.value / 100;
		imp_21_5 = eval(monto5+('*')+porciva5);              // Impuesto Monto 1 al 21 %
		var tot_mon21_5 = eval(monto5+('+')+imp_21_5);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto5);
		tot_mon21_5 = eval(monto5+('+')+imp_21_5);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_5);
	   	tot_general = eval(tot_general+('+')+tot_mon21_5) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
			
	} 
	// SEPTIMO LOTE
	if (monto6.length!=0) {
		//porciva6 = form.tipoiva6.value / 100;
		imp_21_6 = eval(monto6+('*')+porciva6);              // Impuesto Monto 1 al 21 %
		var tot_mon21_6 = eval(monto6+('+')+imp_21_6);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto6);
		tot_mon21_6 = eval(monto6+('+')+imp_21_6);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_6);
	   	tot_general = eval(tot_general+('+')+tot_mon21_6) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
			
	} 
	// OCTAVO LOTE
	if (monto7.length!=0) {
		//porciva7 = form.tipoiva7.value / 100;
		imp_21_7 = eval(monto7+('*')+porciva7);              // Impuesto Monto 1 al 21 %
		var tot_mon21_7 = eval(monto7+('+')+imp_21_7);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto7);
		tot_mon21_7 = eval(monto7+('+')+imp_21_7);	// Total general
	   	imp_tot_21 = eval(imp_tot_21+('+')+imp_21_7);
	   	tot_general = eval(tot_general+('+')+tot_mon21_7) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
		
	} 
	// NOVENO LOTE
	if (monto8.length!=0) {
		//porciva8 = form.tipoiva8.value / 100;
		imp_21_8 = eval(monto8+('*')+porciva8);              // Impuesto Monto 1 al 21 %
		var tot_mon21_8 = eval(monto8+('+')+imp_21_8);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto8);
		tot_mon21_8 = eval(monto8+('+')+imp_21_8);	// Total general
	   	imp_tot_21 = eval(imp_tot_21+('+')+imp_21_8);
	   	tot_general = eval(tot_general+('+')+tot_mon21_8) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
		
	} 
	// DECIMO LOTE
	if (monto9.length!=0) {
		//porciva9 = form.tipoiva9.value / 100;
		imp_21_9 = eval(monto9+('*')+porciva9);              // Impuesto Monto 1 al 21 %
		var tot_mon21_9 = eval(monto9+('+')+imp_21_9);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto9);
		tot_mon21_9 = eval(monto9+('+')+imp_21_9);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_9);
	   	tot_general = eval(tot_general+('+')+tot_mon21_9) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
						 
	} 
	//  LOTE ONCE
	if (monto10.length!=0) {
		//porciva10 = form.tipoiva10.value / 100;
		imp_21_10 = eval(monto10+('*')+porciva10);              // Impuesto Monto 1 al 21 %
		var tot_mon21_10 = eval(monto10+('+')+imp_21_10);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto10);
		tot_mon21_10 = eval(monto10+('+')+imp_21_10);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_10);
	   	tot_general = eval(tot_general+('+')+tot_mon21_10) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
					 
	} 
	//  LOTE DOCE
	if (monto11.length!=0) {
		//porciva11 = form.tipoiva11.value / 100;
	    imp_21_11 = eval(monto11+('*')+porciva11);              // Impuesto Monto 1 al 21 %
	    var tot_mon21_11 = eval(monto11+('+')+imp_21_11);	// Total general
		
         tot_mon = eval(tot_mon+('+')+monto11);
         tot_mon21_11 = eval(monto11+('+')+imp_21_11);	// Total general
         imp_tot_21 = eval(imp_tot_21+('+')+imp_21_11);
         tot_general = eval(tot_general+('+')+tot_mon21_11) 

         form.totneto21.value = tot_mon.toFixed(2) ;	
         form.totiva21.value = imp_tot_21.toFixed(2) ;
         form.tot_general.value = tot_general.toFixed(2) ;
	} 
	//  LOTE TRECE
	if (monto12.length!=0) {
		//porciva12 = form.tipoiva12.value / 100;
	   	imp_21_12 = eval(monto12+('*')+porciva12);              // Impuesto Monto 1 al 21 %
		var tot_mon21_12 = eval(monto11+('+')+imp_21_12);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto11);
		tot_mon21_12 = eval(monto12+('+')+imp_21_12);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_12);
	   	tot_general = eval(tot_general+('+')+tot_mon21_12) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
	
	} 
	//  LOTE CATORCE
	if (monto13.length!=0) {
		//porciva13 = form.tipoiva13.value / 100;
		imp_21_13 = eval(monto13+('*')+porciva13);              // Impuesto Monto 1 al 21 %
		var tot_mon21_13 = eval(monto11+('+')+imp_21_13);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto11);
		tot_mon21_13 = eval(monto13+('+')+imp_21_13);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_13);
	   	tot_general = eval(tot_general+('+')+tot_mon21_13) 
		
		form.totneto21.value = tot_mon.toFixed(2) ;	
		form.totiva21.value = imp_tot_21.toFixed(2) ;
		form.tot_general.value = tot_general.toFixed(2) ;
	}
	//  LOTE QUINCE
	if (monto14.length!=0) {
		//porciva14 = form.tipoiva14.value / 100;
		imp_21_14 = eval(monto14+('*')+porciva14);              // Impuesto Monto 1 al 21 %
		var tot_mon21_14 = eval(monto11+('+')+imp_21_14);	// Total general
		
		tot_mon = eval(tot_mon+('+')+monto11);
		tot_mon21_14 = eval(monto14+('+')+imp_21_14);	// Total general
	    imp_tot_21 = eval(imp_tot_21+('+')+imp_21_14);
	   	tot_general = eval(tot_general+('+')+tot_mon21_14) 
		
	}
   
    // Oculto   
	form.totneto.value = tot_mon_ex.toFixed(2) ;	
    form.totneto21.value = tot_mon.toFixed(2) ;	
    form.totiva21.value = imp_tot_21.toFixed(2) ;
    form.tot_general.value = tot_general.toFixed(2) ;
    // Visible
	form.totneto_1.value = tot_mon_ex.toFixed(2) ;	
    form.totneto21_1.value = tot_mon.toFixed(2) ;	
    form.totiva21_1.value = imp_tot_21.toFixed(2) ;
    form.tot_general_1.value = tot_general.toFixed(2) ;
    
	
}	
</script> 
<script language="javascript">
function agregarOpciones(form)
{
	var selec = form.tipos.options;

		factura.serie.value = 49;
		factura.serie_texto.value = "SERIE DE FC CONC MiPyme A0010";
		factura.tcomp.value = 112;
		
}
</script>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<script type="text/javascript" src="../js/ajax.js"></script>    
<script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script> 
<script type="text/javascript">
DHTMLSuite.include("chainedSelect");
</script>
<!-- Desde Aca !--> 
<script type="text/javascript" src="AJAX/ajax.js"></script>
<script type="text/javascript">
	// Cliente
	var ajax = new sack();
	var currentClientID=false;
	// Primer Lote
	
	function getClientData()
	{
		var clientId = document.getElementById('remate_num').value.replace(/[^0-9]/g,'');
		//alert (clientId);
		if( clientId!=currentClientID){
			//alert(clientId);
			currentClientID = clientId
			ajax.requestFile = 'getFact.php?getClientId='+clientId;	
			ajax.onCompletion = showClientData;	
			ajax.runAJAX();				
		}
	}
			
	function showClientData()
	{
		var formObj = document.forms['factura'];	
		eval(ajax.response);
	}
	
					
	function initFormEvents()
	{
	    var fecha1 = new Date();
		var dia = fecha1.getDate();
		var ano = fecha1.getYear();
		var fecha11 = dia+'/'+mes+'/'+ano;
		var fecha = ano+'-'+mes+'-'+dia;
		document.getElementById('fecha_factura').value = fecha ;
		document.getElementById('remate_num').onblur = getClientData();
		document.getElementById('remate_num').focus();
		
	}
		
	window.onload = initFormEvents;
</script>

<script language="javascript">
function liquidacion(form)
{
	if (factura.GrupoOpciones2[0].checked ==false)
   	{
   	} else {
 	}
}
</script>
<script language="javascript">
// Remate
function getRemate(form) {
	var seleccion =  form.remate_num.options;
	var cantidad  =  form.remate_num.options.length;
	var cant = (cantidad+1) ;
	var contador = 0;
	
	for ( contador ; contador < cant ; contador++) {
   		if (seleccion[0].selected == true) { 
	  
	  		//alert("Debe seleccionar una opcion");
	  		form.remate_num.value = "" ;
	  		form.lugar_remate.disabled = true ;
      		form.fecha_remate.disabled = true ;
	  		break ;	
    	}

    	if (seleccion[contador].selected) { 
	    	var todo = new String (seleccion[contador].text);
			let pos1 = todo.indexOf("|");
			if (pos1 !== -1) {
    			var lugar = new String (todo.substr(pos1 +1, 150));
				let pos2 = lugar.indexOf("|");
				if (pos2 !== -1) {
					lugar = lugar.substr(pos1 , pos2 - 5);
					var fecha = todo.substr(pos2 + 7,  150);
				
				}
			}
	  		//form.remate_num.value = opcion+" ";
			form.lugar_remate.value = lugar+" ";
			form.fecha_remate.value = fecha+" ";
	  		form.codnum.focus();
			strAlerta = "lugar" + lugar + "\n" + "fecha " + fecha + "\n" + "cant " + cant + "\n" + "contador " + contador + "\n";
			//alert(strAlerta);
	   	}
		
	}
}
</script> 
<script language="javascript">
// Concepto 1
function getprov(form) {
	var seleccion =  form.concepto.options;
	var cantidad  =  form.concepto.options.length;
	var cant = (cantidad+1) ;
	var contador = 0;
	strAlerta = "seleccion " + seleccion + "\n" + "cantidad " + cantidad + "\n" + "cant " + cant + "\n" + "contador " + contador + "\n" + "seleccion[0].selected" + seleccion[0].selected + "\n" + "seleccion[contador].selected" + "\n" + seleccion[contador].selected;
	//alert(strAlerta);
	for ( contador ; contador < cant ; contador++) {
   		if (seleccion[0].selected == true) { 
	  
	  		alert("Debe seleccionar una opcion");
	  		form.descripcion.value = "" ;
	  		form.descripcion.disabled = true ;
      		form.importe.disabled = true ;
	  		break ;	
    	}

    	if (seleccion[contador].selected) { 
	    	var opcion = new String (seleccion[contador].text);
			let posicion = opcion.indexOf("|");
			if (posicion !== -1)
    			opcion = opcion.substr(2,posicion - 2);
	  		form.descripcion.value = opcion+" ";
			//form.lote.value = opcion+" ";
			var opcion2 = new String (seleccion[contador].text);
	  		var opcion2 = opcion2.substring(posicion + 2,posicion + 6);
			form.tipoiva.value = opcion2+" ";
	  		form.importe.focus();

	   	}
		strAlerta = "seleccion " + seleccion + "\n" + "cantidad " + cantidad + "\n" + "cant " + cant + "\n" + "contador " + contador + "\n" + "seleccion[0].selected" + seleccion[0].selected + "\n" + "seleccion[contador].selected" + "\n" + seleccion[contador].selected;
		//alert(strAlerta);
	}
	strAlerta2 = "seleccion " + seleccion + "\n" + "cantidad " + cantidad + "\n" + "cant " + cant + "\n" + "contador " + contador + "\n" + "seleccion[0].selected" + seleccion[0].selected + "\n" + "seleccion[contador].selected" + "\n" + seleccion[contador].selected;
	//alert(strAlerta2);
}  
</script> 
<script language="javascript">
// Concepto 2
function getprov1(form) {

	var seleccion1 = form.concepto1.options;
	var cantidad1 =  form.concepto1.options.length;
	var cant1 = (cantidad1+1) ;
	var contador1 = 0;
  
	for (contador1; contador1<cantidad1 ; contador1++) {
   		if (seleccion1[0].selected == true)  { 
	  		alert("Debe seleccionar una opcion")
	  		provedor.descripcion1.value = "" ;
	  		provedor.descripcion1.disabled = true ;
      		provedor.importe1.disabled = true ;
	  		break ;	
    	}

    	if (seleccion1[contador1].selected == true)  { 
	  		var opcion1 = new String (seleccion1[contador1].text);
			let posicion1 = opcion1.indexOf("|");
			if (posicion1 !== -1)
    			opcion1 = opcion1.substr(2,posicion1 - 2);
	  		form.descripcion1.value = opcion1+" ";
			//form.lote.value = opcion+" ";
			var opcion2_1 = new String (seleccion1[contador1].text);
	  		var opcion2_1 = opcion2_1.substring(posicion1 + 2,posicion1 + 6);
			form.tipoiva1.value = opcion2_1+" ";
	  		form.importe1.focus();
   		}
   	}
}   
</script> 
<script language="javascript">
// TERCER CONCEPTO
function getprov2(form) {

	var seleccion2 = form.concepto2.options;
	var cantidad2  =  form.concepto2.options.length;
	var cant2      = (cantidad2+1) ;
	var contador2  = 0;
  
	for (contador2; contador2<cantidad2 ; contador2++) {
   		if (seleccion2[0].selected == true) { 
	  		alert("Debe seleccionar una opcion");
	  		provedor.descripcion2.value = "" ;
	  		provedor.descripcion2.disabled = true ;
      		provedor.importe2.disabled = true ;
	  		break ;	
    	}

    	if (seleccion2[contador2].selected == true)  { 
	  		var opcion2 = new String (seleccion2[contador2].text);
			let posicion2 = opcion2.indexOf("|");
			if (posicion2 !== -1)
    			opcion2 = opcion2.substr(2,posicion2 - 2);
	  		form.descripcion2.value = opcion2+" ";
			//form.lote.value = opcion+" ";
			var opcion2_2 = new String (seleccion2[contador2].text);
	  		var opcion2_2 = opcion2_2.substring(posicion2 + 2,posicion2 + 6);
			form.tipoiva2.value = opcion2_2+" ";
	  		form.importe2.focus();
		}
   	}
}   
</script>
<script language="javascript">
 
function getprov3(form) {
	var seleccion3 = form.concepto3.options;
	var cantidad3 =  form.concepto3.options.length
	var cant3 = (cantidad3+1) ;
	var contador3 = 0;
  
	for (contador3; contador3<cantidad3 ; contador3++) {
		if (seleccion3[0].selected == true){ 
	  		alert("Debe seleccionar una opcion")
	  		provedor.descripcion3.value = "" ;
	  		provedor.descripcion3.disabled = true ;
      		provedor.importe3.disabled = true ;
	  		break ;	
    	}

    	if (seleccion3[contador3].selected == true) { 
		  	var opcion3 = new String (seleccion3[contador3].text);
			let posicion3 = opcion3.indexOf("|");
			if (posicion3 !== -1)
    			opcion3 = opcion3.substr(2,posicion3 - 2);
	  		form.descripcion3.value = opcion3+" ";
			//form.lote.value = opcion+" ";
			var opcion2_3 = new String (seleccion3[contador3].text);
	  		var opcion2_3 = opcion2_3.substring(posicion3 + 2,posicion3 + 6);
			form.tipoiva3.value = opcion2_3+" ";
	  		form.importe3.focus();
		}
   	}
}    
</script>
<script language="javascript">

function getprov4(form) {
	var seleccion4 = form.concepto4.options;
	var cantidad4 =  form.concepto4.options.length
	var cant4 = (cantidad4+1) ;
	var contador4 = 0;
  
	for (contador4; contador4<cantidad4 ; contador4++) {
   		if (seleccion4[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		provedor.descripcion4.value = "" ;
	  		provedor.descripcion4.disabled = true ;
      		provedor.importe4.disabled = true ;
	  		break ;	
    	}

    	if (seleccion4[contador4].selected == true) { 
		  	var opcion4 = new String (seleccion4[contador4].text);
			let posicion4 = opcion4.indexOf("|");
			if (posicion4 !== -1)
    			opcion4 = opcion4.substr(2,posicion4 - 2);
	  		form.descripcion4.value = opcion4+" ";
			//form.lote.value = opcion+" ";
			var opcion2_4 = new String (seleccion4[contador4].text);
	  		var opcion2_4 = opcion2_4.substring(posicion4 + 2,posicion4 + 6);
			form.tipoiva4.value = opcion2_4+" ";
	  		form.importe4.focus();
	  	}
	}	
}  
</script>
<script language="javascript">
 
function getprov5(form) {
	var seleccion5 = form.concepto5.options;
	var cantidad5 =  form.concepto5.options.length
	var cant5 = (cantidad5+1) ;
	var contador5 = 0;
  
	for (contador5; contador5<cantidad5 ; contador5++) {
		if (seleccion5[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		provedor.descripcion5.value = "" ;
	  		provedor.descripcion5.disabled = true ;
      		provedor.importe5.disabled = true ;
	  		break ;	
    	}

    	if (seleccion5[contador5].selected == true)  { 
		  	var opcion5 = new String (seleccion5[contador5].text);
			let posicion5 = opcion5.indexOf("|");
			if (posicion5 !== -1)
    			opcion5 = opcion5.substr(2,posicion5 - 2);
	  		form.descripcion5.value = opcion5+" ";
			//form.lote.value = opcion+" ";
			var opcion2_5 = new String (seleccion5[contador5].text);
	  		var opcion2_5 = opcion2_5.substring(posicion5 + 2,posicion5 + 6);
			form.tipoiva5.value = opcion2_5+" ";
	  		form.importe5.focus();
		}
   	}
}   
</script>
<script language="javascript">
 
function getprov6(form) {
	var seleccion6 = form.concepto6.options;
	var cantidad6 =  form.concepto6.options.length
	var cant6 = (cantidad6+1) ;
	var contador6 = 0;
  
	for (contador6; contador6<cantidad6 ; contador6++){ 
   		if (seleccion6[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		provedor.descripcion6.value = "" ;
	  		provedor.descripcion6.disabled = true ;
      		provedor.importe6.disabled = true ;
	  		break ;	
    	}

    	if (seleccion6[contador6].selected == true) { 
		  	var opcion6 = new String (seleccion6[contador6].text);
			let posicion6 = opcion6.indexOf("|");
			if (posicion6 !== -1)
    			opcion6 = opcion6.substr(2,posicion6 - 2);
	  		form.descripcion6.value = opcion6+" ";
			//form.lote.value = opcion+" ";
			var opcion2_6 = new String (seleccion6[contador6].text);
	  		var opcion2_6 = opcion2_6.substring(posicion6 + 2,posicion6 + 6);
			form.tipoiva6.value = opcion2_6+" ";
	  		form.importe6.focus();
	   }
   	}
}   
</script>
<script language="javascript">

function getprov7(form) {
	var seleccion7 = form.concepto7.options;
	var cantidad7 =  form.concepto7.options.length
	var cant7 = (cantidad7+1) ;
	var contador7 = 0;
  
	for (contador7; contador7<cantidad7 ; contador7++) {   
		if (seleccion7[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		provedor.descripcion7.value = "" ;
	  		provedor.descripcion7.disabled = true ;
      		provedor.importe7.disabled = true ;
	  		break ;	
    	}

    	if (seleccion7[contador7].selected == true) { 
		  	var opcion7 = new String (seleccion7[contador7].text);
			let posicion7 = opcion7.indexOf("|");
			if (posicion7 !== -1)
    			opcion7 = opcion7.substr(2,posicion7 - 2);
	  		form.descripcion7.value = opcion7+" ";
			//form.lote.value = opcion+" ";
			var opcion2_7 = new String (seleccion7[contador7].text);
	  		var opcion2_7 = opcion2_7.substring(posicion7 + 2,posicion7 + 6);
			form.tipoiva7.value = opcion2_7+" ";
	  		form.importe7.focus();
   		}
   	}
}   
</script>
<script language="javascript">

function getprov8(form) {
	var seleccion8 = form.concepto8.options;
	var cantidad8 =  form.concepto8.options.length
	var cant8 = (cantidad8+1) ;
	var contador8 = 0;
  
	for (contador8; contador8<cantidad8 ; contador8++) { 
   		if (seleccion8[0].selected == true) { 
	  	  	alert("Debe seleccionar una opcion")
	  		provedor.descripcion8.value = "" ;
	  		provedor.descripcion8.disabled = true ;
      		provedor.importe8.disabled = true ;
	  		break ;	
    	}

    	if (seleccion8[contador8].selected == true) { 
		  	var opcion8 = new String (seleccion8[contador8].text);
			let posicion8 = opcion8.indexOf("|");
			if (posicion8 !== -1)
    			opcion8 = opcion8.substr(2,posicion8 - 2);
	  		form.descripcion8.value = opcion8+" ";
			//form.lote.value = opcion+" ";
			var opcion2_8 = new String (seleccion8[contador8].text);
	  		var opcion2_8 = opcion2_8.substring(posicion8 + 2,posicion8 + 6);
			form.tipoiva8.value = opcion2_8+" ";
	  		form.importe8.focus();
	   }
	}
}   
</script>
<script language="javascript">

function getprov9(form) {
	var seleccion9 = form.concepto9.options;
	var cantidad9 =  form.concepto9.options.length
	var cant9 = (cantidad9+1) ;
	var contador9 = 0;
  	
	for (contador9; contador9<cantidad9 ; contador9++) {
   		if (seleccion9[0].selected == true) { 
	  	  	alert("Debe seleccionar una opcion")
	  		provedor.descripcion9.value = "" ;
	  		provedor.descripcion9.disabled = true ;
      		provedor.importe9.disabled = true ;
	  		break ;	
    	}

    	if (seleccion9[contador9].selected == true) { 
		  	var opcion9 = new String (seleccion9[contador9].text);
			let posicion9 = opcion9.indexOf("|");
			if (posicion9 !== -1)
    			opcion9 = opcion9.substr(2,posicion9 - 2);
	  		form.descripcion9.value = opcion9+" ";
			//form.lote.value = opcion+" ";
			var opcion2_9 = new String (seleccion9[contador9].text);
	  		var opcion2_9 = opcion2_9.substring(posicion9 + 2,posicion9 + 6);
			form.tipoiva9.value = opcion2_9+" ";
	  		form.importe9.focus();
	
   		}
   	}
}   
</script>
<script language="javascript">

function getprov10(form) {
	var seleccion10 = form.concepto10.options;
	var cantidad10 =  form.concepto10.options.length
	var cant10 = (cantidad10+1) ;
	var contador10 = 0;
  
	for (contador10; contador10<cantidad10 ; contador10++) {
   		if (seleccion10[0].selected == true) { 
	  		alert("Debe seleccionar una opcion")
	  		provedor.descripcion10.value = "" ;
	  		provedor.descripcion10.disabled = true ;
      		provedor.importe10.disabled = true ;
	  		break ;	
    	}

    	if (seleccion10[contador10].selected == true)    { 
		  	var opcion10 = new String (seleccion10[contador10].text);
			let posicion10 = opcion10.indexOf("|");
			if (posicion10 !== -1)
    			opcion10 = opcion10.substr(2,posicion10 - 2);
	  		form.descripcion10.value = opcion10+" ";
			//form.lote.value = opcion+" ";
			var opcion2_10 = new String (seleccion10[contador10].text);
	  		var opcion2_10 = opcion2_10.substring(posicion10 + 2,posicion10 + 6);
			form.tipoiva10.value = opcion2_10+" ";
	  		form.importe10.focus();
	   	}
   	}
}   
</script>
<script language="javascript">
 
function getprov11(form) {
	var seleccion11 = form.concepto11.options;
	var cantidad11 =  form.concepto11.options.length
	var cant11 = (cantidad11+1) ;
	var contador11 = 0;
  
	for (contador11; contador11<cantidad11 ; contador11++) { 
   		if (seleccion11[0].selected == true) { 
	  		alert("Debe seleccionar una opcion")
	  		provedor.descripcion11.value = "" ;
	  		provedor.descripcion11.disabled = true ;
      		provedor.importe11.disabled = true ;
	  		break ;	
    	}

    	if (seleccion11[contador11].selected == true) { 
		  	var opcion11 = new String (seleccion11[contador11].text);
			let posicion11 = opcion11.indexOf("|");
			if (posicion11 !== -1)
    			opcion11 = opcion11.substr(2,posicion11 - 2);
	  		form.descripcion11.value = opcion11+" ";
			//form.lote.value = opcion+" ";
			var opcion2_11 = new String (seleccion11[contador11].text);
	  		var opcion2_11 = opcion2_11.substring(posicion11 + 2,posicion11 + 6);
			form.tipoiva11.value = opcion2_11+" ";
	  		form.importe11.focus();
		}
   	}
}   
</script>
<script language="javascript">

function getprov12(form) {
	var seleccion12 = form.concepto12.options;
	var cantidad12 =  form.concepto12.options.length
	var cant12 = (cantidad12+1) ;
	var contador12 = 0;
  
	for (contador12; contador12<cantidad12 ; contador12++) {
   		if (seleccion12[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		provedor.descripcion12.value = "" ;
	  		provedor.descripcion12.disabled = true ;
      		provedor.importe12.disabled = true ;
	  		break ;	
    	}

    	if (seleccion12[contador12].selected == true)  { 
		  	var opcion12 = new String (seleccion12[contador12].text);
			let posicion12 = opcion12.indexOf("|");
			if (posicion12 !== -1)
    			opcion12 = opcion12.substr(2,posicion12 - 2);
	  		form.descripcion12.value = opcion12+" ";
			//form.lote.value = opcion+" ";
			var opcion2_12 = new String (seleccion12[contador12].text);
	  		var opcion2_12 = opcion2_12.substring(posicion12 + 2,posicion12 + 6);
			form.tipoiva12.value = opcion2_12+" ";
	  		form.importe12.focus();
	   	}
   	}
}
</script>
<script language="javascript">
 function getprov13(form) {
	var seleccion13 = form.concepto13.options;
	var cantidad13 =  form.concepto13.options.length
	var cant13 = (cantidad13+1) ;
	var contador13 = 0;
  
	for (contador13; contador13<cantidad13 ; contador13++) { //alert();
   		if (seleccion13[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		provedor.descripcion13.value = "" ;
	  		provedor.descripcion13.disabled = true ;
      		provedor.importe13.disabled = true ;
	  		break ;	
    	}
    	if (seleccion13[contador13].selected == true) { 
		  	var opcion13 = new String (seleccion13[contador13].text);
			let posicion13 = opcion13.indexOf("|");
			if (posicion13 !== -1)
    			opcion13 = opcion13.substr(2,posicion13 - 2);
	  		form.descripcion13.value = opcion13+" ";
			//form.lote.value = opcion+" ";
			var opcion2_13 = new String (seleccion13[contador13].text);
	  		var opcion2_13 = opcion2_13.substring(posicion13 + 2,posicion13 + 6);
			form.tipoiva13.value = opcion2_13+" ";
	  		form.importe13.focus();
	   	}
   	}
}  
</script>
<script language="javascript">
 
function getprov14(form) {
	var seleccion14 = form.concepto14.options;
	var cantidad14 =  form.concepto14.options.length
	var cant14 = (cantidad14+1) ;
	var contador14 = 0;
  	
	for (contador14; contador14<cantidad14 ; contador14++) { //alert();
   		if (seleccion14[0].selected == true) { 
	    	alert("Debe seleccionar una opcion")
	  		provedor.descripcion14.value = "" ;
	  		provedor.descripcion14.disabled = true ;
      		provedor.importe14.disabled = true ;
	  		break ;	
    	}
    	if (seleccion14[contador14].selected == true) { 
		  	var opcion14 = new String (seleccion14[contador14].text);
			let posicion14 = opcion14.indexOf("|");
			if (posicion14 !== -1)
    			opcion14 = opcion14.substr(2,posicion14 - 2);
	  		form.descripcion14.value = opcion14+" ";
			//form.lote.value = opcion+" ";
			var opcion2_14 = new String (seleccion14[contador14].text);
	  		var opcion2_14 = opcion2_14.substring(posicion14 + 2,posicion14 + 6);
			form.tipoiva14.value = opcion2_14+" ";
	  		form.importe14.focus();
	  	}
   	}
}   	
</script>
<!-- Hasta Aca  !-->
<script language="javascript">
function neto(form)
{ importe = factura.importes.value; 
	document.write(importe);
  	//alert (importe);

}
function MM_findObj(n, d) { //v4.01
	var p,i,x;  
	if(!d) 
		d=document; 
	if((p=n.indexOf("?"))>0&&parent.frames.length) {
    		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
	}
  	if(!(x=d[n])&&d.all) 
		x=d.all[n]; 
	for (i=0;!x&&i<d.forms.length;i++) 
		x=d.forms[i][n];
  	for(i=0;!x&&d.layers&&i<d.layers.length;i++) 
		x=MM_findObj(n,d.layers[i].document);
  	if(!x && d.getElementById) 
		x=d.getElementById(n); 
	return x;
}
function MM_validateForm() { //v4.0
	var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  	for (i=0; i<(args.length-2); i+=3) { 
		test=args[i+2]; 
		val=MM_findObj(args[i]);
    		if (val) { 
			nm=val.name; 
			if ((val=val.value)!="") {
      				if (test.indexOf('isEmail')!=-1) { 
					p=val.indexOf('@');
        				if (p<1 || p==(val.length-1)) 
						errors+='- '+nm+' must contain an e-mail address.\n';
      				} else if (test!='R') { 
					num = parseFloat(val);
        				if (isNaN(val)) 
						errors+='El importe debe contener un n�mero.\n';
        				if (test.indexOf('inRange') != -1) { 
						p=test.indexOf(':');
          					min=test.substring(8,p); 
						max=test.substring(p+1);
          					if (num<min || max<num) 
							errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    					} 
				} 
			} else if (test.charAt(0) == 'R') 
				errors += '- '+nm+' is required.\n'; 
		}
	} if (errors) alert('ERROR \n'+errors);
		document.MM_returnValue = (errors == '');
}
</script>
<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="factura" name="factura" method="POST" action="<?php echo $editFormAction; ?>">
  <table  border="1" align="left" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF" width="900">
    <tr>
      <td width="895"  colspan="1" ><div align="center"><img src="images/carga_aut_conceptos.gif"  height="30" /></div></td>
      
    </tr>
    <tr>
      <td colspan="4" valign="top" bgcolor="#FFFFFF"><table width="100%" border="1" cellspacing="1" cellpadding="1">
        
		 <td width="16%" height="20" class="ewTableHeader">&nbsp;Tipo de Cbte </td>
          <td width="35%"><select name="tipos" onChange="agregarOpciones(this.form)">
                        <option value="112">FC CONCEPTOS A00010 MiPyme</option>
                                     
                           </select>
		  <input name="tcomp" type="hidden" value="112" size="25" >
		         </td>
          
          <td width="14%" class="ewTableHeader">Serie</td>
          <td width="35%"><input name="serie_texto" value="SERIE DE FC CONC A00010 MiPyme" type="text"  size="44" />
		  <input name="serie" type="hidden" value="49" size="25"/>
            </td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">Nro Factura </td>
          <td><input name="num_factura" type="num_factura" size="25" class="phpmakerlist"/></td>
          
          <td class="ewTableHeader">Fecha Factura </td>
          <td><input name="fecha_factura1" type="text" size="16" id="fecha_factura1" value= <?php echo $fecha_hoy; ?> />
         <a href="javascript:showCal('Calendar4')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
        </tr>
         <tr>
          <td height="20" class="ewTableHeader">Nro Remate</td>
          <td>
              <?php if ($cod_usuario == 25 || $cod_usuario == 26)  { //echo "estoy en cod_usuario = ".$cod_usuario?>
              <select name="remate_num" id="remate_num" onchange="getRemate(this.form)">
            <option value="">Remate</option>
		
            
          </select>
             <?php } else  { ?>
              <select name="remate_num" id="remate_num"  required="required" onchange="getRemate(this.form)">
            <option value="">Remate</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_num_remates['ncomp']?>"><?php echo $row_num_remates['ncomp']?><?php echo " | "?><?php echo substr($row_num_remates['direccion'],0,31)?><?php echo " | "?><?php echo $row_num_remates['fecreal']?></option>
            <?php 
				} while ($row_num_remates = mysqli_fetch_assoc($num_remates));
  				$rows = mysqli_num_rows($num_remates);
  				if($rows > 0) {
      				mysqli_data_seek($num_remates, 0);
	  				$row_num_remates = mysqli_fetch_assoc($num_remates);
					
  				}
			?>
          </select>
              <?php } ?>
             </td>
         
             
          <td colspan="4" rowspan="5" valign="top" bgcolor="#FFFFFF" ><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">
            
         <td colspan="2" bgcolor="#FFFFFF" align="center"><img src="images/cond_pago.gif" width="150" height="30" /></td>
           </tr>
           <tr>
              <td width="50%" height="39" bgcolor="#FFFFFF">&nbsp;<span class="ewTableHeader">Afecta Liquidacion</span></td>
              <td width="50%" bgcolor="#FFFFFF"><input name="GrupoOpciones2" type="radio" value='1' onclick="liquidacion(this.form)"  /></td>
          </tr>
            <tr>
              <td width="50%" height="39" bgcolor="#FFFFFF">&nbsp;<span class="ewTableHeader">No afecta Liquidacion</span></td>
              <td width="50%" bgcolor="#FFFFFF"><input type="radio" name="GrupoOpciones2" value='0' checked="checked"onClick="liquidacion(this.form)"/></td>
              </tr>
          </table></td>
          </tr>
        <tr>
          <td height="9" class="ewTableHeader">Lugar del remate </td>
          <td><input name="lugar_remate" type="text" size="30" id="lugar_remate" /></td>
          
        </tr>
		<tr>
         <td height="20" class="ewTableHeader">Fecha de remate</td>
          <td><input name="fecha_remate" type="text" size="25" /></td>
           </tr>
        <tr>
          <td height="10" class="ewTableHeader"> Cliente </td>
          <td><select name="codnum" id="codnum" required="required">
            <option value="">Cliente</option>
		
            <?php
do {  
?>
            <option value="<?php echo $row_cliente['codnum']?>"><?php echo substr($row_cliente['razsoc'],0,22)?><?php echo $row_cliente['cuit']?></option>
            <?php
} while ($row_cliente = mysqli_fetch_assoc($cliente));
  $rows = mysqli_num_rows($cliente);
  if($rows > 0) {
      mysqli_data_seek($cliente, 0);
	  $row_cliente = mysqli_fetch_assoc($cliente);
	  $cuit_enti = $row_cliente['cuit'];
  }
?>
          </select></td>
         </tr>
      </table></td>
      <tr>
      <td colspan="4"  background="images/separador3.gif"></td>
    </tr>
    <tr>
      <td colspan="3" valign="top">
	  <table width="895" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        <tr>
          <td width="195" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div  align="center">CONCEPTO</div></td>
			<td width="70" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div  align="center">% IVA</div></td>
          <td width="470" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center">DESCRIPCION</div></td>
          <td width="160" background="images/fonod_lote.jpg" class="ewTableHeader">
              <div align="center">IMPORTE</div></td>
        </tr>
       
        
		<tr>
          <td bgcolor="#FFFFFF"> <select name="concepto" required="required" onchange="getprov(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);?>
<input name="imp" type="hidden" class="phpmaker"  size="75"  value="<?php echo $row_conceptos_a_facturar['porcentaje'];?>"/>
  
  <?php
  }
?>
          </select></td>
			<td bgcolor="#FFFFFF"><input name="tipoiva" size="10" required="required" onchange="getprov(this.form)">
		  </td>
          <td bgcolor="#FFFFFF"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="80" required="required"/></td><input name="secuencia" type="hidden" class="phpmaker"  size="5"  value="1"/>
         
          <td width="156" bgcolor="#FFFFFF"><input name="importe" type="text" id="importe" required="required"  onchange="validarFormulario(this.form)" size="25"   /></td>
          </tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto1" onchange="getprov1(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
 

          </select></td>
			 <td bgcolor="#FFFFFF"><input name="tipoiva1" size="10" onchange="getprov(this.form)">
		  </td>
          <td bgcolor="#FFFFFF"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="80" /></td>
         
    <td width="156" bgcolor="#FFFFFF"><input name="importe1" type="text" id="importe1" onchange="validarFormulario(this.form)" size="25"  /></td>
	<input name="secuencia1" type="hidden" class="phpmaker" id="secuencia1" size="5" value="2" />
	    </tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto2" onchange="getprov2(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			 <td bgcolor="#FFFFFF"><input name="tipoiva2" size="10" onchange="getprov(this.form)">
		  </td>

          <td bgcolor="#FFFFFF"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="80" /></td>
        
          <td width="156" bgcolor="#FFFFFF"><input name="importe2" type="text" id="importe2" onchange="validarFormulario(this.form)" size="25" /></td>
		  <input name="secuencia2" type="hidden" class="phpmaker" id="secuencia2" size="5" value="3"/> 
		</tr>
		  <tr>
          <td bgcolor="#FFFFFF"><select name="concepto3" onchange="getprov3(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <<option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			  <td bgcolor="#FFFFFF"><input name="tipoiva3" size="10" onchange="getprov(this.form)">
		  </td>

          <td bgcolor="#FFFFFF"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="80" /></td>
          
          <td width="156" bgcolor="#FFFFFF"><input name="importe3" type="text" id="importe3" onchange="validarFormulario(this.form)" size="25" /></td>
		  <input name="secuencia3" type="hidden" class="phpmaker" id="secuencia3" size="5" value="4"/>
		</tr>
		  <tr>
          <td bgcolor="#FFFFFF"><select name="concepto4" onchange="getprov4(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			  <td bgcolor="#FFFFFF"><input name="tipoiva4" size="10" onchange="getprov(this.form)">
		  </td>
          <td bgcolor="#FFFFFF"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="80" /></td>
           
          <td width="156" bgcolor="#FFFFFF"><input name="importe4" type="text" id="importe4" onchange="validarFormulario(this.form)" size="25" /></td>
		   <input name="secuencia4" type="hidden" class="phpmaker" id="secuencia4" size="5" value="5"/>
		</tr>
		  <tr>
          <td bgcolor="#FFFFFF"><select name="concepto5" onchange="getprov5(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			  <td bgcolor="#FFFFFF"><input name="tipoiva5" size="10" onchange="getprov(this.form)">
		  </td>
          <td bgcolor="#FFFFFF"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="80" /></td>
         
          <td width="156" bgcolor="#FFFFFF"><input name="importe5" type="text" id="importe5" onchange="validarFormulario(this.form)" size="25" /></td>
		  <input name="secuencia5" type="hidden" class="phpmaker" id="secuencia5" size="5" value="6"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto6" onchange="getprov6(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			 <td bgcolor="#FFFFFF"><input name="tipoiva6" size="10" onchange="getprov(this.form)">
		 </td>

          <td bgcolor="#FFFFFF"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="80" /></td>
          
		   <td width="156" bgcolor="#FFFFFF"><input name="importe6" type="text" id="importe6" onchange="validarFormulario(this.form)" size="25" /></td>
           <input name="secuencia6" type="hidden" class="phpmaker" id="secuencia6" size="5" value="7"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto7" onchange="getprov7(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			 <td bgcolor="#FFFFFF"><input name="tipoiva7" size="10" onchange="getprov(this.form)">
		 </td>
          <td bgcolor="#FFFFFF"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="80" /></td>
          
          <td width="156" bgcolor="#FFFFFF"><input name="importe7" type="text" id="importe7" onchange="validarFormulario(this.form)" size="25" /></td>
		 <input name="secuencia7" type="hidden" class="phpmaker" id="secuencia7" size="5" value="8"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto8" onchange="getprov8(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			  <td bgcolor="#FFFFFF"><input name="tipoiva8" size="10" onchange="getprov(this.form)">
		 </td>
 
          <td bgcolor="#FFFFFF"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="80" /></td>
          
          <td width="156" bgcolor="#FFFFFF"><input name="importe8" type="text" id="importe8" onchange="validarFormulario(this.form)" size="25" /></td>
		  <input name="secuencia8" type="hidden" class="phpmaker" id="secuencia8" size="5" value="9" />
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto9" onchange="getprov9(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			 <td bgcolor="#FFFFFF"><input name="tipoiva9" size="10" onchange="getprov(this.form)">
		  </td>
          <td bgcolor="#FFFFFF"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="80" /></td>
          
          <td width="156" bgcolor="#FFFFFF"><input name="importe9" type="text" id="importe9" onchange="validarFormulario(this.form)" size="25" /></td>
		  <input name="secuencia9" type="hidden" class="phpmaker" id="secuencia9" size="5" value="10"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto10" onchange="getprov10(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			 <td bgcolor="#FFFFFF"><input name="tipoiva10" size="10" onchange="getprov(this.form)">
		 </td>
          <td bgcolor="#FFFFFF"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="80" /></td>
         
          <td width="156" bgcolor="#FFFFFF"><input name="importe10" type="text" id="importe10" onchange="validarFormulario(this.form)" size="25" /></td>
		  <input name="secuencia10" type="hidden" class="phpmaker" id="secuencia10" size="5" value="11"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto11" onchange="getprov11(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			 <td bgcolor="#FFFFFF"><input name="tipoiva11" size="10" onchange="getprov(this.form)">
		 </td>
          <td bgcolor="#FFFFFF"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="80" /></td>
          
          <td width="156" bgcolor="#FFFFFF"><input name="importe11" type="text" id="importe11" onchange="validarFormulario(this.form)" size="25" /></td>
		  <input name="secuencia11" type="hidden" class="phpmaker" id="secuencia11" size="5" value="12"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto12" onchange="getprov12(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			  <td bgcolor="#FFFFFF"><input name="tipoiva12" size="10" onchange="getprov(this.form)">
		</td>
          <td bgcolor="#FFFFFF"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="80" /></td>
         
          <td width="156" bgcolor="#FFFFFF"><input name="importe12" type="text" id="importe12" onchange="validarFormulario(this.form)" size="25" /></td>
		  <input name="secuencia12" type="hidden" class="phpmaker" id="secuencia12" size="5" value="13"/>
		</tr>
		 <tr>
          <td bgcolor="#FFFFFF"><select name="concepto13" onchange="getprov13(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
			  <td bgcolor="#FFFFFF"><input name="tipoiva13" size="10" onchange="getprov(this.form)">
		</td>
          <td bgcolor="#FFFFFF"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="80" /></td>
        
          <td width="156" bgcolor="#FFFFFF"><input name="importe13" type="text" id="importe13" onchange="validarFormulario(this.form)" size="25" /></td>
		 <input name="secuencia13" type="hidden" class="phpmaker" id="secuencia13" size="5" value="14"/>
		</tr> <tr>
          <td bgcolor="#FFFFFF"><select name="concepto14" onchange="getprov14(this.form)">
		  <option value="">[Tipo de concepto]</option>
            <?php
do {  
?>
            <option value="<?php echo $row_conceptos_a_facturar['nroconc'];?>"><?php echo $row_conceptos_a_facturar['nroconc'];?>&nbsp;<?php echo utf8_encode($row_conceptos_a_facturar['descrip']);?><?php echo" | ";?><?php echo $row_conceptos_a_facturar['porcentaje'];?></option>
            <?php
} while ($row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar));
  $rows = mysqli_num_rows($conceptos_a_facturar);
  if($rows > 0) {
      mysqli_data_seek($conceptos_a_facturar, 0);
	  $row_conceptos_a_facturar = mysqli_fetch_assoc($conceptos_a_facturar);
  }
?>
          </select></td>
		  <td bgcolor="#FFFFFF"><input name="tipoiva14" size="10" onchange="getprov(this.form)">
	</td>

          <td bgcolor="#FFFFFF"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="80" /></td>
       
          <td width="156" bgcolor="#FFFFFF"><input name="importe14" type="text" id="importe14" onchange="validarFormulario(this.form)" size="25" /></td>
		 <input name="secuencia14" type="hidden" class="phpmaker" id="secuencia14" size="5" value="15"/>
		</tr>
      </table>      </td>
      
    </tr>
    <tr>
      <td ><table width="895" height="20" border="1" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF" style="width:100%">
	<tr><td width="15%" height="10" bgcolor="#FFFFFF" class="ewTableHeader" border="1">&nbsp;Imprimir   </td><td width="85%" bgcolor="#FFFFFF" ><input type="checkbox" name="imprime" value="1" />
</td>
	</tr>
	 </table></td>
      
        
    </tr>
    <tr>
      <input name="iva21" type="hidden" size="12" value="<?php echo $iva_21_porcen     ?>"/>
        <input name="cod_usuario" type="hidden" size="12" value="<?php echo $cod_usuario  ?>"/>
      
    </tr>
    <tr>
      
    </tr>
  
    <tr>
      <td colspan="4" bgcolor="#FFFFFF"><table width="100%" border="1" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF">
        <tr>
         <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto Exento </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php echo $iva_21_porcen ?> %</div></td>
          
          
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php echo $iva_21_porcen ?> %</div></td>
          
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Total </div></td>
        </tr>
         <tr>
          <td align="center"><input name="totneto" type="hidden" id="totneto" size="15" /></td>
          <td align="center"><input name="totneto21" type="hidden" id="totneto21" size="15" /></td>
          
         
          <td align="center"><input name="totiva21"  type="hidden"  id="totiva21" size="20" /></td>
          
          <td align="center"><input name="tot_general" type="hidden"  id="tot_general" size="20" /></td>
        </tr>
        <tr>
          <td align="center"><input name="totneto_1" type="text" id="totneto_1" size="15" /></td>
          <td align="center"><input name="totneto21_1" type="text" id="totneto21_1" size="15" /></td>
          
         
          <td align="center"><input name="totiva21_1"  type="text" id="totiva21_1" size="20" /></td>
          
          <td align="center"><input name="tot_general_1" type="text" required="required" id="tot_general_1" size="20" /></td>
        </tr>
      </table></td>
      
    </tr>
    
    <tr>
      <td colspan="3" bgcolor="#FFFFFF"><table width="100%" border="1" cellspacing="1" cellpadding="1">
        <tr>
          <td><div align="center">
            
          </div></td>
          <td><div align="center">
            
            <input type="hidden" value="Quehago" id="pageOperation" name="pageOperation" />
			<input type="submit" value="Enviar a AFIP"  id="evento_eliminar" name="evento_eliminar" />
			  
			<input type="reset" value="Limpiar Formulario">
          </div></td>
          <td><div align="center">
            
          </div></td>
        </tr>
      </table></td>
    </tr>
<input type="hidden" name="MM_insert" value="factura">
  </table>
 </form>
 <script type="text/javascript"> 
chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('tcomp','serie','includes/getserxtc.php'); 
//chainedSelects.addChain('ncomp','datos','includes/getremate.php'); 
chainedSelects.init(); 
</script>
 </body>
</html>
<?php
mysql_free_result($tipo_comprobante);
mysql_free_result($cliente);
mysql_free_result($num_remates);
?>
