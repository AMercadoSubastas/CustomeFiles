<?php 
include_once "src/userfn.php";
require_once('Connections/amercado.php'); 
include_once "FE_Pack_WSFE/config.php";
include_once "FE_Pack_WSFE/afip/AfipWsaa.php";
include_once "FE_Pack_WSFE/afip/AfipWsfev1.php";
//setcookie('factura',"");
$num_factura = "";
$fecha_hoy = date('d/m/Y');

$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$sigo_y_grabo = 0;
$todo_ok = 1;

	//echo "TODO_OK = ".$todo_ok;
	//========================================================================================================================
	//Datos Correspondientes a la factura
    /**
     * Para conocer que dato va en cada parametro se puede consultar el archivo:
     *
     * - fe/docs/Estructura de Datos del Request de WSFEv1.docx
     *
     *
     * Y para mas informacion se puede revisar el manual del WS
     * 
     * - fe/docs/manual_desarrollador_COMPG_v2.pdf 
     * 
     */
    
    //Cabecera
    $CbteTipo = 7; // N Cred B - Ver - AfipWsfev1::FEParamGetTiposCbte()
    $PtoVta = 4;

    //Requerimiento
    $Concepto = 3; //Productos y Servicios
    $DocTipo = 96; //DNI 80; //CUIT
    
	
	mysqli_select_db($amercado, $database_amercado);
	$query_cliente2 = sprintf("SELECT * FROM entidades WHERE codnum = 10243"); //%s",GetSQLValueString($_POST['codnum'],"int"));
	$cliente2 = mysqli_query($amercado, $query_cliente2) or die(mysqli_error($amercado));
	$row_cliente2 = mysqli_fetch_assoc($cliente2);
	
	$cuit_enti2 = "";
    $cuit_enti = $row_cliente2['cuit'];
	if (isset($cuit_enti)) {
		$cuit_enti2 =substr($cuit_enti, 3, 8); // str_replace("-","",$cuit_enti);
		//echo " CUIT   -   ".$cuit_enti2."    -   ";
	}
	//echo " CUIT   -   ".$cuit_enti."    -   ";
	$DocNro = $cuit_enti2; //30661087753; // 30710183437; //30661087753;
    /**
     * Estos dos parametros representan el numero de factura desde/hasta pero deben ser iguales
     * Se obtienen mediante el metodo: $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);
     * 
     * $CbteDesde = $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);;
     * $CbteHasta = $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);;
     * 
     */
    //$CbteDesde = $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);//1; 
	//$CbteHasta = $wsfe->FECompUltimoAutorizado($CbteTipo,$PtoVta);//1; 

    $CbteFch      = intval(date('Ymd'));
    $ImpTotal     = 2744.28; //$_POST['tot_general']; //GetSQLValueString($_POST['tot_general'], "double"); //121.00;
    $ImpTotConc   = 0.00; // GetSQLValueString($_POST['totiva21_1'], "double"); // 0.00;
    $ImpNeto      = 2744.28;// $_POST['totneto21'] + $_POST['totneto105'] + $_POST['totcomis'];//GetSQLValueString($_POST['totneto21'], "double") + GetSQLValueString($_POST['totneto105'], "double"); //100.00;
    $ImpOpEx      = 0.00;
    $ImpIVA       = 0.00;//$_POST['totiva21'] + $_POST['totiva105'];//GetSQLValueString($_POST['totiva21'], "double") + GetSQLValueString($_POST['totiva105'], "double"); // 21.00;
    $ImpTrib      = 0.00;
    $FchServDesde = intval(date('Ymd'));
    $FchServHasta = intval(date('Ymd'));
    $FchVtoPago   = intval(date('Ymd'));
    $MonId        = 'PES'; // Pesos (AR) - Ver - AfipWsfev1::FEParamGetTiposMonedas()
    $MonCotiz     = 1.00;

	//echo " IMPTOTAL = ".$ImpTotal."  IMPNETO  = ".$ImpNeto."  ImpIVA  = ".$ImpIVA."   ";

    //Informacion para agregar al array Tributos
    /** 
     * Esto aplica si las facturas tienen tributos agregados
     */
    $tributoId = null; // Ver - AfipWsfev1::FEParamGetTiposTributos()
    $tributoDesc = null;
    $tributoBaseImp = null;
    $tributoAlic = null;
    $tributoImporte = null;

    //Informacion para agregar al array IVA
/*
	if (isset($_POST['totneto105']) && $_POST['totneto105'] != 0.00 && isset($_POST['totiva21']) && $_POST['totiva21'] != 0.00) {
		$IvaAlicuotaId_1 = 4; // 10.5% Ver - AfipWsfev1::FEParamGetTiposIva()
		$IvaAlicuotaBaseImp_1 = $_POST['totneto105'];//GetSQLValueString($_POST['totneto105'], "double");
		$IvaAlicuotaImporte_1 = $_POST['totiva105'];//GetSQLValueString($_POST['totiva105'], "double");   
	
		$IvaAlicuotaId_2 = 5; // 21% Ver - AfipWsfev1::FEParamGetTiposIva()
		$IvaAlicuotaBaseImp_2 = $_POST['totneto21']  + $_POST['totcomis']; //GetSQLValueString($_POST['totneto21'], "double");
		$IvaAlicuotaImporte_2 = $_POST['totiva21'];//GetSQLValueString($_POST['totiva21'], "double");   
		
		$Iva = array(
			'AlicIva' => array ( 
				array (
					'Id' => $IvaAlicuotaId_1,
					'BaseImp' => number_format(abs($IvaAlicuotaBaseImp_1),2,'.',''),
					'Importe' => number_format(abs($IvaAlicuotaImporte_1),2,'.','')
				),
				array (
					'Id' => $IvaAlicuotaId_2,
					'BaseImp' => number_format(abs($IvaAlicuotaBaseImp_2),2,'.',''),
					'Importe' => number_format(abs($IvaAlicuotaImporte_2),2,'.','')
				)
			)
		);
	}
	else {
	    if (isset($_POST['totiva21']) && $_POST['totiva21'] != 0.00) {
	    	$IvaAlicuotaId = 5; // 21% Ver - AfipWsfev1::FEParamGetTiposIva()
	    	$IvaAlicuotaBaseImp = $_POST['totneto21'] + $_POST['totcomis'];// 100.00;
    		$IvaAlicuotaImporte = $_POST['totiva21'] ;//21.00;   
		}
		else {
			if (isset($_POST['totiva105']) && $_POST['totiva105'] != 0.00) {
				$IvaAlicuotaId = 4; // 10.5% Ver - AfipWsfev1::FEParamGetTiposIva()
	    		$IvaAlicuotaBaseImp = $_POST['totneto105'] ;// 100.00;
    			$IvaAlicuotaImporte = $_POST['totiva105'] ;//21.00;   
			}
			else {
			*/
				$IvaAlicuotaId = 3; // 0 % Ver - AfipWsfev1::FEParamGetTiposIva()
	    		$IvaAlicuotaBaseImp = 0.00; //$_POST['totneto105'] ;// 100.00;
    			$IvaAlicuotaImporte = 0.00; //$_POST['totiva105'] ;//21.00;   
		//	}  
		if (isset($Iva) || isset($IvaAlicuotaBaseImp) || isset($IvaAlicuotaImporte))
        {
            if (empty($Iva))
            {
                $Iva = array(
                    'AlicIva' => array (
                        'Id' => $IvaAlicuotaId,
                        'BaseImp' => number_format(abs($IvaAlicuotaBaseImp),2,'.',''),
                        'Importe' => number_format(abs($IvaAlicuotaImporte),2,'.','')
                        )
                    );
            }
		}
	//}
	//========================================================================================================================
	//LA MANDO AL WS
	
	$sigo_y_grabo = 0;
	


	//=============================================================================================================

	/**
	 * En el archivo php.ini se deben habilitar las siguientes extensiones
	 *
	 * extension=php_openssl (.dll / .so)
	 * extension=php_soap    (.dll / .so)
	 *
	 */

	error_reporting(E_ALL);
	ini_set('display_errors','Yes');

	//Cargando archivo de configuracion
	include_once "FE_Pack_WSFE/config.php";
	include_once "FE_Pack_WSFE/library/functions.php";

	//Cargando modelos de conexion a WebService
	include_once MDL_PATH."AfipWsaa.php";
	include_once MDL_PATH."AfipWsfev1.php";


	//Datos correspondiente a la empresa que emite la factura
    //CUIT (Sin guiones)
    $empresaCuit  = '30710183437';
    //El alias debe estar mencionado en el nombre de los archivos de certificados y firmas digitales
    $empresaAlias = 'amercado4';  // 'ldb'; // 'AMERCADO1';


	//Obtener los datos de la factura que se desea generar
    //Elegir uno de los include como para tener diferentes tipos de factura
    //include "FE_Pack_WSFE/test/data/TestRegistrarFeMultiIVA_ejemplo_Factura_tipo_A.php";
    //include "data/TestRegistrarFe_ejemplo_Factura_tipo_B.php";


	//WebService que utilizara la autenticacion
	$webService   = 'wsfe';
	//Creando el objeto WSAA (Web Service de Autenticaci�n y Autorizaci�n)
	$wsaa = new AfipWsaa($webService,$empresaAlias);

	//Creando el TA (Ticket de acceso)
	if ($ta = $wsaa->loginCms())
	{
    	$token      = $ta['token'];
    	$sign       = $ta['sign'];
    	$expiration = $ta['expiration'];
    	$uniqueid   = $ta['uniqueid'];
		
		//echo "  -  VOY A CONECTAR CON EL WS    -   ";

    	//Conectando al WebService de Factura electronica (WsFev1)
    	$wsfe = new AfipWsfev1($empresaCuit,$token,$sign);

    	//Obteniendo el ultimo numero de comprobante autorizado
    	$CompUltimoAutorizado = $wsfe->FECompUltimoAutorizado($PtoVta,$CbteTipo);
    	//echo "<h3>wsfe->FECompUltimoAutorizado(PtoVta,CbteTipo)</h3>";
    	//pr($CompUltimoAutorizado); //============================= COMENTADO ==========
    
    	/**
     	 * Aca se puede hacer una comparacion del Ultimo Comprobante Autorizado
     	 * y el ultimo comprobante que se registro en la base de datos.
    	 */

    	$CbteDesde = $CompUltimoAutorizado['CbteNro'] + 1;
    	$CbteHasta = $CbteDesde;
		$num_factura = $CbteDesde;
		$num_fac = $CbteDesde;
    	//Armando el array para el Request
    	//La estructura de este array esta dise�ada de acuerdo al registro XML del WebService y utiliza las variables antes declaradas.
        $FeCAEReq = array (
            'FeCAEReq' => array (
                'FeCabReq' => array (
                    'CantReg' => 1,
                    'CbteTipo' => $CbteTipo,
                    'PtoVta' => $PtoVta
                    ),
                'FeDetReq' => array (
                    'FECAEDetRequest' => array(
                        'Concepto' => $Concepto,
                        'DocTipo' => $DocTipo,
                        'DocNro' => $DocNro,
                        'CbteDesde' => $CbteDesde,
                        'CbteHasta' => $CbteHasta,
                        'CbteFch' => $CbteFch,
                        'FchServDesde' => $FchServDesde,
                        'FchServHasta' => $FchServHasta,
                        'FchVtoPago' => $FchVtoPago,
                        'ImpTotal' => number_format(abs($ImpTotal),2,'.',''),
                        'ImpTotConc' => number_format(abs($ImpTotConc),2,'.',''),
                        'ImpNeto' => number_format(abs($ImpNeto),2,'.',''),
                        'ImpOpEx' => number_format(abs($ImpOpEx),2,'.',''),
                        'ImpIVA' => number_format(abs($ImpIVA),2,'.',''),
                        'ImpTrib' => number_format(abs($ImpTrib),2,'.',''),
                        'MonId' => $MonId,
                        'MonCotiz' => $MonCotiz
                   	)
                )
            ),
        );


        if ($tributoBaseImp || $tributoImporte)    	{
           	$Tributos = array(
               	'Tributo' => array (
                   	'Id' => $tributoId,
                   	'Desc' => $tributoDesc,
                   	'BaseImp' => number_format(abs($tributoBaseImp),2,'.',''),
                   	'Alic' => number_format(abs($tributoAlic),2,'.',''),
                   	'Importe' => number_format(abs($tributoImporte),2,'.','')
               	)
           	);
           	$FeCAEReq['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Tributos'] = $Tributos;
       	}
		if (isset($Iva) || isset($IvaAlicuotaBaseImp) || isset($IvaAlicuotaImporte))
        {
            if (empty($Iva))
            {
                $Iva = array(
                    'AlicIva' => array (
                        'Id' => $IvaAlicuotaId,
                        'BaseImp' => number_format(abs($IvaAlicuotaBaseImp),2,'.',''),
                        'Importe' => number_format(abs($IvaAlicuotaImporte),2,'.','')
                        )
                    );
        	}
		}
       	$FeCAEReq['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Iva'] = $Iva;
/*
   		echo '
    		<table>
        	<caption>wsfe->FECAESolicitar(Request)</caption>
        	<tr>
            <th >Request</th>
            <th >Response</th>
        	</tr>
        	<tr>
            <td>
    		';
    		pr($FeCAEReq);

    		echo "
            </td>
            <td>
    		";
*/
   		//Registrando la factura electronica
   		$FeCAEResponse = $wsfe->FECAESolicitar($FeCAEReq);

    		/**
    		 * Tratamiento de errores
    		 */
        
       	if (!$FeCAEResponse)   	{
           	/* Procesando ERRORES */


           	echo '<h2 class="err">NO SE HA GENERADO EL CAE</h2>
               	  <h3 class="err">ERRORES DETECTADOS</h3>';

            	$errores = $wsfe->getErrLog();
           	if (isset($errores)) {
               	foreach ($errores as $v) {
                   	pr($v);
               	}
           	}
           	echo "<hr/><h3>Response</h3>";

       	}
       	elseif (!$FeCAEResponse['FeDetResp']['FECAEDetResponse']['CAE']) {
           	/* Procesando OBSERVACIONES */

           	echo '<h2 class="msg">NO SE HA GENERADO EL CAE</h2>
               	  <h3 class="msg">OBSERVACIONES INFORMADAS</h3>';

           	if (isset($FeCAEResponse['FeDetResp']['FECAEDetResponse']['Observaciones'])) {
               	foreach ($FeCAEResponse['FeDetResp']['FECAEDetResponse']['Observaciones'] as $v) {
                   	pr($v);
               	}
           	}
           	echo "<hr/><h3>Response</h3>";
       	}    
		else {
   			//pr($FeCAEResponse); //============================ COMENTADO =======================
			$CAE       = $FeCAEResponse['FeDetResp']['FECAEDetResponse']['CAE'];
			$CAEFchVto = $FeCAEResponse['FeDetResp']['FECAEDetResponse']['CAEFchVto'];
			$Resultado = $FeCAEResponse['FeDetResp']['FECAEDetResponse']['Resultado'];
			$sigo_y_grabo = 1;
		}
	/*
   		echo "CAE = ".$CAE." CAEFchVto = ".$CAEFchVto." Resultado = ".$Resultado."   -  ";
    		echo " 
            </td>
        	</tr>
    		</table>
    		";
*/


	}
	else		{
   		echo '
  		<hr/>
   		<h3>Errores detectados al generar el Ticket de Acceso</h3>';
   		pr($wsaa->getErrLog());
	}
//}

?>
