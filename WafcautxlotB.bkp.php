

<?php 

include_once "src/userfn.php";
include_once "src/phpfn.php";

require_once('Connections/amercado.php'); 
include_once "FE_Pack_WSFE/config.php";
include_once "FE_Pack_WSFE/afip/AfipWsaa.php";
include_once "FE_Pack_WSFE/afip/AfipWsfev1.php";

define('FC_B4','53');
define('SERIE_B4','30');

$num_factura = "";
$fecha_hoy = date('d/m/Y');

//LEVANTO LOS DATOS DEL FORM ANTERIOR
$codremate = $_POST["remate_num"];
mysqli_select_db($amercado, $database_amercado);

//LEVANTO LOS DATOS DEL FORM ANTERIOR
$codremate = $_POST["remate_num"];
if (isset($_POST['codusu']))
	$usuario = $_POST['codusu'];
else 
	if (isset($_GET['codusu']))
		$usuario = $_GET['codusu'];
	else 
		$usuario = 1;
echo "USUARIO ".$usuario."  ";
$cod_usuario = $usuario;
echo "COD_USUARIO = ".$cod_usuario."  ";
$editFormAction = $_SERVER['PHP_SELF'];

if (isset($_SERVER['QUERY_STRING'])) {
	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$sigo_y_grabo = 0;
$todo_ok = 1;

if (isset($_POST['lote']) && GetSQLValueString($_POST['lote'], "int")!="NULL") {

	if (!empty($_POST['impuesto']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote1']) && GetSQLValueString($_POST['lote1'], "int")!="NULL") {
	if (!empty($_POST['impuesto1']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote2']) && GetSQLValueString($_POST['lote2'], "int")!="NULL") {
	if (!empty($_POST['impuesto2']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote3']) && GetSQLValueString($_POST['lote3'], "int")!="NULL") {
	if (!empty($_POST['impuesto3']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote4']) && GetSQLValueString($_POST['lote4'], "int")!="NULL") {
	if (!empty($_POST['impuesto4']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote5']) && GetSQLValueString($_POST['lote5'], "int")!="NULL") {
	if (!empty($_POST['impuesto5']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote6']) && GetSQLValueString($_POST['lote6'], "int")!="NULL") {
	if (!empty($_POST['impuesto6']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote7']) && GetSQLValueString($_POST['lote7'], "int")!="NULL") {
	if (!empty($_POST['impuesto7']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote8']) && GetSQLValueString($_POST['lote8'], "int")!="NULL") {
	if (!empty($_POST['impuesto8']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote9']) && GetSQLValueString($_POST['lote9'], "int")!="NULL") {
	if (!empty($_POST['impuesto9']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote10']) && GetSQLValueString($_POST['lote10'], "int")!="NULL") {
	if (!empty($_POST['impuesto10']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote11']) && GetSQLValueString($_POST['lote11'], "int")!="NULL") {
	if (!empty($_POST['impuesto11']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote12']) && GetSQLValueString($_POST['lote12'], "int")!="NULL") {
	if (!empty($_POST['impuesto12']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote13']) && GetSQLValueString($_POST['lote13'], "int")!="NULL") {
	if (!empty($_POST['impuesto13']))
		$oka = 1;
	else
		$todo_ok = 0;
}
if (isset($_POST['lote14']) && GetSQLValueString($_POST['lote14'], "int")!="NULL") {
	if (!empty($_POST['impuesto14']))
		$oka = 1;
	else
		$todo_ok = 0;
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
	//echo "TODO_OK = ".$todo_ok;
	//==========================================================================================
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
    $CbteTipo = 6; // Factura B - Ver - AfipWsfev1::FEParamGetTiposCbte()
    $PtoVta = 4;

    //Requerimiento
    $Concepto = 3; //Productos y Servicios
    $DocTipo = 96; //DNI 80; //CUIT
    
	
	mysqli_select_db($amercado, $database_amercado);
	$query_cliente2 = sprintf("SELECT * FROM entidades WHERE codnum = %s",GetSQLValueString($_POST['codnum'],"int"));
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
    $ImpTotal     = $_POST['tot_general']; //GetSQLValueString($_POST['tot_general'], "double"); //121.00;
    $ImpTotConc   = 0.00; // GetSQLValueString($_POST['totiva21_1'], "double"); // 0.00;
    $ImpNeto      = $_POST['totneto21'] + $_POST['totneto105'] + $_POST['totcomis'] + $_POST['totimp'] ;//GetSQLValueString($_POST['totneto21'], "double") + GetSQLValueString($_POST['totneto105'], "double"); //100.00;
    $ImpOpEx      = 0.00;
    $ImpIVA       = $_POST['totiva21'] + $_POST['totiva105'];//GetSQLValueString($_POST['totiva21'], "double") + GetSQLValueString($_POST['totiva105'], "double"); // 21.00;
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

    if (isset($_POST['totneto105']) && $_POST['totneto105'] != 0.00  && ((isset($_POST['totneto21']) && $_POST['totneto21'] != 0.00) || (isset($_POST['totcomis']) && $_POST['totcomis'] != 0.00) || (isset($_POST['totimp']) && $_POST['totimp'] != 0.00))) {
		$IvaAlicuotaId_1 = 4; // 10.5% Ver - AfipWsfev1::FEParamGetTiposIva()
		$IvaAlicuotaBaseImp_1 = $_POST['totneto105'];//GetSQLValueString($_POST['totneto105'], "double");
		$IvaAlicuotaImporte_1 = $_POST['totiva105'];//GetSQLValueString($_POST['totiva105'], "double");   
	
		$IvaAlicuotaId_2 = 5; // 21% Ver - AfipWsfev1::FEParamGetTiposIva()
		$IvaAlicuotaBaseImp_2 = $_POST['totneto21']  + $_POST['totcomis'] + $_POST['totimp']; //GetSQLValueString($_POST['totneto21'], "double");
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
		if (isset($_POST['totneto105']) && $_POST['totneto105'] != 0.00) {
			$IvaAlicuotaId = 4; // 10.5% Ver - AfipWsfev1::FEParamGetTiposIva()
			$IvaAlicuotaBaseImp = $_POST['totneto105'];//GetSQLValueString($_POST['totneto105'], "double");
			$IvaAlicuotaImporte = $_POST['totiva105'];//GetSQLValueString($_POST['totiva105'], "double");   
		}
		else {
	    	$IvaAlicuotaId = 5; // 21% Ver - AfipWsfev1::FEParamGetTiposIva()
	    	$IvaAlicuotaBaseImp = $_POST['totneto21'] + $_POST['totcomis'] + $_POST['totimp'];// 100.00;
    		$IvaAlicuotaImporte = $_POST['totiva21'] ;//21.00;   
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
	}

	//======================================================================================
	//LA MANDO AL WS
	
	$sigo_y_grabo = 0;
	
	//======================================================================================

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
    $empresaCuit  = '30710183437'; //'20233616126';
    //El alias debe estar mencionado en el nombre de los archivos de certificados y firmas digitales
    $empresaAlias = 'amercado4';//'ldb'; // 'AMERCADO1';


	//Obtener los datos de la factura que se desea generar
    //Elegir uno de los include como para tener diferentes tipos de factura
    //include "FE_Pack_WSFE/test/data/TestRegistrarFeMultiIVA_ejemplo_Factura_tipo_A.php";
    //include "data/TestRegistrarFe_ejemplo_Factura_tipo_B.php";


	//WebService que utilizara la autenticacion
	$webService   = 'wsfe';
	//Creando el objeto WSAA (Web Service de AutenticaciÃ³n y AutorizaciÃ³n)
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
    	//La estructura de este array esta diseÃ±ada de acuerdo al registro XML del WebService y utiliza las variables antes declaradas.
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
   			//pr($FeCAEResponse); //================= COMENTADO =======================
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
}

if ($sigo_y_grabo == 1 && $todo_ok ==1) {
	
	$renglones = 0;
	$primera_vez = 1;
	if (isset($_POST['lote']) && GetSQLValueString($_POST['lote'], "int")!="NULL") {
		// DESDE ACA ===================================================================================
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
	
			mysqli_select_db($amercado, $database_amercado);
			$actualiza1 = sprintf("UPDATE `series` SET `nroact` = %s WHERE `series`.`codnum` = %s", $num_fac, 	GetSQLValueString($_POST['serie'], "int")) ;				 
			$resultado=mysqli_query($amercado,	$actualiza1);	

		}
		// HASTA ACA ===================================================================================
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			//mysqli_select_db($amercado, $database_amercado);
			$leolote = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote'], "text"));
			$lote = mysqli_query($amercado, $leolote) or die("ERROR LEYENDO LOTES");
			$row_lote = mysqli_fetch_assoc($lote);
			$descrip = $row_lote['descor'];
			$secu = $row_lote['secuencia'];
			//============================================================================
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, // GetSQLValueString($_POST['tcomp'], "int"),
                       SERIE_B4, // GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu, //GetSQLValueString($_POST['secuencia0'], "int"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['importe'], "double"),
					   GetSQLValueString($_POST['impuesto'], "double"),
                       GetSQLValueString($_POST['comision'], "double"),
					   GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 1;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu); //GetSQLValueString($_POST['secuencia0'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote1']) && GetSQLValueString($_POST['lote1'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote1 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote1'], "text"));
			$lote1 = mysqli_query($amercado, $leolote1) or die("ERROR LEYENDO LOTES");
			$row_lote1 = mysqli_fetch_assoc($lote1);
			$descrip1 = $row_lote1['descor'];
			$secu1 = $row_lote1['secuencia'];
			
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, // GetSQLValueString($_POST['tcomp'], "int"),
                       30, // GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu1, // GetSQLValueString($_POST['secuencia1'], "int"),
                       GetSQLValueString($_POST['descripcion1'], "text"),
                       GetSQLValueString($_POST['importe1'], "double"),
					   GetSQLValueString($_POST['impuesto1'], "double"),
                       GetSQLValueString($_POST['comision1'], "double"),
					   GetSQLValueString($_POST['codusu'], "int"));


  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 2;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe1'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu1); //GetSQLValueString($_POST['secuencia1'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote2']) && GetSQLValueString($_POST['lote2'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote2 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote2'], "text"));
			$lote2 = mysqli_query($amercado, $leolote2) or die("ERROR LEYENDO LOTES");
			$row_lote2 = mysqli_fetch_assoc($lote2);
			$descrip2 = $row_lote2['descor'];
			$secu2 = $row_lote2['secuencia'];
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu2, //GetSQLValueString($_POST['secuencia2'], "int"),
                       GetSQLValueString($_POST['descripcion2'], "text"),
                       GetSQLValueString($_POST['importe2'], "double"),
					   GetSQLValueString($_POST['impuesto2'], "double"),
                       GetSQLValueString($_POST['comision2'], "double"),
				       GetSQLValueString($_POST['codusu'], "int"));


  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 3;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe2'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu2); //GetSQLValueString($_POST['secuencia2'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote3']) && GetSQLValueString($_POST['lote3'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote3 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote3'], "text"));
			$lote3 = mysqli_query($amercado, $leolote3) or die("ERROR LEYENDO LOTES");
			$row_lote3 = mysqli_fetch_assoc($lote3);
			$descrip3 = $row_lote3['descor'];
			$secu3 = $row_lote3['secuencia'];
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu3, //GetSQLValueString($_POST['secuencia3'], "int"),
                       GetSQLValueString($_POST['descripcion3'], "text"),
                       GetSQLValueString($_POST['importe3'], "double"),
					   GetSQLValueString($_POST['impuesto3'], "double"),
                       GetSQLValueString($_POST['comision3'], "double"),
						GetSQLValueString($_POST['codusu'], "int"));


  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 4;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe3'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu3); //GetSQLValueString($_POST['secuencia3'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote4']) && GetSQLValueString($_POST['lote4'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote4 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote4'], "text"));
			$lote4 = mysqli_query($amercado, $leolote4) or die("ERROR LEYENDO LOTES");
			$row_lote4 = mysqli_fetch_assoc($lote4);
			$descrip4 = $row_lote4['descor'];
			$secu4 = $row_lote4['secuencia'];
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu4, //GetSQLValueString($_POST['secuencia4'], "int"),
                       GetSQLValueString($_POST['descripcion4'], "text"),
					   GetSQLValueString($_POST['importe4'], "double"),
					   GetSQLValueString($_POST['impuesto4'], "double"),
                       GetSQLValueString($_POST['comision4'], "double"),
					   GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 5;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe4'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu4);//GetSQLValueString($_POST['secuencia4'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote5']) && GetSQLValueString($_POST['lote5'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote5 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote5'], "text"));
			$lote5 = mysqli_query($amercado, $leolote5) or die("ERROR LEYENDO LOTES");
			$row_lote5 = mysqli_fetch_assoc($lote5);
			$descrip5 = $row_lote5['descor'];
			$secu5 = $row_lote5['secuencia'];
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu5, //GetSQLValueString($_POST['secuencia5'], "int"),
                       GetSQLValueString($_POST['descripcion5'], "text"),
                       GetSQLValueString($_POST['importe5'], "double"),
					   GetSQLValueString($_POST['impuesto5'], "double"),
                       GetSQLValueString($_POST['comision5'], "double"),
						GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 6;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe5'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu5); //GetSQLValueString($_POST['secuencia5'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote6']) && GetSQLValueString($_POST['lote6'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote6 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote6'], "text"));
			$lote6 = mysqli_query($amercado, $leolote6) or die("ERROR LEYENDO LOTES");
			$row_lote6 = mysqli_fetch_assoc($lote6);
			$descrip6 = $row_lote6['descor'];
			$secu6 = $row_lote6['secuencia'];
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu6, //GetSQLValueString($_POST['secuencia6'], "int"),
                       GetSQLValueString($_POST['descripcion6'], "text"),
                       GetSQLValueString($_POST['importe6'], "double"),
					   GetSQLValueString($_POST['impuesto6'], "double"),
                       GetSQLValueString($_POST['comision6'], "double"),
						GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 7;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe6'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu6); //GetSQLValueString($_POST['secuencia6'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote7']) && GetSQLValueString($_POST['lote7'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote7 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote7'], "text"));
			$lote7 = mysqli_query($amercado, $leolote7) or die("ERROR LEYENDO LOTES");
			$row_lote7 = mysqli_fetch_assoc($lote7);
			$descrip7 = $row_lote7['descor'];
			$secu7 = $row_lote7['secuencia'];
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu7, //GetSQLValueString($_POST['secuencia7'], "int"),
                       GetSQLValueString($_POST['descripcion7'], "text"),
                       GetSQLValueString($_POST['importe7'], "double"),
					   GetSQLValueString($_POST['impuesto7'], "double"),
                       GetSQLValueString($_POST['comision7'], "double"),
						GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 8;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe7'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu7); //GetSQLValueString($_POST['secuencia7'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote8']) && GetSQLValueString($_POST['lote8'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote8 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote8'], "text"));
			$lote8 = mysqli_query($amercado, $leolote8) or die("ERROR LEYENDO LOTES");
			$row_lote8 = mysqli_fetch_assoc($lote8);
			$descrip8 = $row_lote8['descor'];
			$secu8 = $row_lote8['secuencia'];
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu8, //GetSQLValueString($_POST['secuencia8'], "int"),
                       GetSQLValueString($_POST['descripcion8'], "text"),
                       GetSQLValueString($_POST['importe8'], "double"),
					   GetSQLValueString($_POST['impuesto8'], "double"),
                       GetSQLValueString($_POST['comision8'], "double"),
						GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 9;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe8'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu8); //GetSQLValueString($_POST['secuencia8'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote9']) && GetSQLValueString($_POST['lote9'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote9 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote9'], "text"));
			$lote9 = mysqli_query($amercado, $leolote9) or die("ERROR LEYENDO LOTES");
			$row_lote9 = mysqli_fetch_assoc($lote9);
			$descrip9 = $row_lote9['descor'];
			$secu9 = $row_lote9['secuencia'];
			
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu9, //GetSQLValueString($_POST['secuencia9'], "int"),
                       GetSQLValueString($_POST['descripcion9'], "text"),
                       GetSQLValueString($_POST['importe9'], "double"),
					   GetSQLValueString($_POST['impuesto9'], "double"),
                       GetSQLValueString($_POST['comision9'], "double"),
					   GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 10;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe9'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu9); //GetSQLValueString($_POST['secuencia9'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote10']) && GetSQLValueString($_POST['lote10'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote10 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote10'], "text"));
			$lote10 = mysqli_query($amercado, $leolote10) or die("ERROR LEYENDO LOTES");
			$row_lote10 = mysqli_fetch_assoc($lote10);
			$descrip10 = $row_lote10['descor'];
			$secu10 = $row_lote10['secuencia'];
			
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, // GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu10, //GetSQLValueString($_POST['secuencia10'], "int"),
                       GetSQLValueString($_POST['descripcion10'], "text"),
                       GetSQLValueString($_POST['importe10'], "double"),
					   GetSQLValueString($_POST['impuesto10'], "double"),
                       GetSQLValueString($_POST['comision10'], "double"),
						GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 11;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe10'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu10); //GetSQLValueString($_POST['secuencia10'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote11']) && GetSQLValueString($_POST['lote11'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote11 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote11'], "text"));
			$lote11 = mysqli_query($amercado, $leolote11) or die("ERROR LEYENDO LOTES");
			$row_lote11 = mysqli_fetch_assoc($lote11);
			$descrip11 = $row_lote11['descor'];
			$secu11 = $row_lote11['secuencia'];
			
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, // GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu11, //GetSQLValueString($_POST['secuencia11'], "int"),
                       GetSQLValueString($_POST['descripcion11'], "text"),
                       GetSQLValueString($_POST['importe11'], "double"),
					   GetSQLValueString($_POST['impuesto11'], "double"),
                       GetSQLValueString($_POST['comision11'], "double"),
						GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 12;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe11'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu11); //GetSQLValueString($_POST['secuencia11'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote12']) && GetSQLValueString($_POST['lote12'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote12 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote12'], "text"));
			$lote12 = mysqli_query($amercado, $leolote12) or die("ERROR LEYENDO LOTES");
			$row_lote12 = mysqli_fetch_assoc($lote12);
			$descrip12 = $row_lote12['descor'];
			$secu12 = $row_lote12['secuencia'];
			
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu12, //GetSQLValueString($_POST['secuencia12'], "int"),
                       GetSQLValueString($_POST['descripcion12'], "text"),
                       GetSQLValueString($_POST['importe12'], "double"),
					   GetSQLValueString($_POST['impuesto12'], "double"),
                       GetSQLValueString($_POST['comision12'], "double"),
						GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 13;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe12'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu12); //GetSQLValueString($_POST['secuencia12'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote13']) && GetSQLValueString($_POST['lote13'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote13 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote13'], "text"));
			$lote13 = mysqli_query($amercado, $leolote13) or die("ERROR LEYENDO LOTES");
			$row_lote13 = mysqli_fetch_assoc($lote13);
			$descrip13 = $row_lote13['descor'];
			$secu13 = $row_lote13['secuencia'];
			
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu13, //GetSQLValueString($_POST['secuencia13'], "int"),
                       GetSQLValueString($_POST['descripcion13'], "text"),
                       GetSQLValueString($_POST['importe13'], "double"),
					   GetSQLValueString($_POST['impuesto13'], "double"),
                       GetSQLValueString($_POST['comision13'], "double"),
					   GetSQLValueString($_POST['codusu'], "int"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 14;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe13'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu13); //GetSQLValueString($_POST['secuencia13'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote14']) && GetSQLValueString($_POST['lote14'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			$leolote14 = sprintf("SELECT * FROM lotes WHERE codrem = %s AND codintlote = %s", 
							   	GetSQLValueString($_POST['remate_num'], "int"),
							    GetSQLValueString($_POST['lote14'], "text"));
			$lote14 = mysqli_query($amercado, $leolote14) or die("ERROR LEYENDO LOTES");
			$row_lote14 = mysqli_fetch_assoc($lote14);
			$descrip14 = $row_lote14['descor'];
			$secu14 = $row_lote14['secuencia'];
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob, usuario) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, //GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       $secu14, //GetSQLValueString($_POST['secuencia14'], "int"),
                       GetSQLValueString($_POST['descripcion14'], "text"),
                       GetSQLValueString($_POST['importe14'], "double"),
					   GetSQLValueString($_POST['impuesto14'], "double"),
                       GetSQLValueString($_POST['comision14'], "double"),
					   GetSQLValueString($_POST['codusu'], "int"));
					   

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 15;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe14'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				$secu14); //GetSQLValueString($_POST['secuencia14'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	//echo "   5  - ";
	if (isset($_POST['lote']) && GetSQLValueString($_POST['lote'], "int")!="NULL") {
  		/// Crea la mascara 

  		$tcomp = $_POST['tcomp'];
  		$serie = $_POST['serie'];
  		//$num_fac = $_POST['num_factura'];
  		$query_mascara = "SELECT * FROM series  WHERE  series.codnum='30'";//'$tcomp'  AND series.codnum='$serie'";
  		$mascara1 = mysqli_query($amercado, $query_mascara) or die(mysqli_error($amercado));
  		$row_mascara = mysqli_fetch_assoc($mascara1);
  		$totalRows_mascara = mysqli_num_rows($mascara1);
  		$mascara  = $row_mascara['mascara'];
 		//echo "MASCARA".$mascara;
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
			//echo "CAE3 = ".$CAE." CAEFchVto3 = ".$CAEFchVto." Resultado3 = ".$Resultado."   -  ";
			$fecha_factura1 = $_POST['fecha_factura1'] ;
			$fecha_factura1 = substr($fecha_factura1,6,4)."-".substr($fecha_factura1,3,2)."-".substr($fecha_factura1,0,2);
			$en_liquid = 1;
  			$insertSQL = sprintf("INSERT INTO cabfac (tcomp, serie, ncomp, fecval, fecdoc, fecreg, cliente, fecvenc, estado, emitido, codrem, totbruto, totiva105, totiva21, totimp, totcomis, totneto105, totneto21, nrengs, nrodoc , tieneresol, en_liquid, CAE, CAEFchVto, Resultado, usuario, usuarioultmod) VALUES (%s, %s, %s, '$fecha_factura1','$fecha_factura1', '$fecha_factura1', %s, '$fecha_factura1', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,'$en_liquid', '$CAE', '$CAEFchVto', '$Resultado', %s, %s)",
                       FC_B4, //GetSQLValueString($_POST['tcomp'], "int"),
                       30, // GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['codnum'], "int"),
					   GetSQLValueString("P", "text"), 
					   GetSQLValueString("0", "int"),
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['tot_general'], "double"),
                       GetSQLValueString($_POST['totiva105'], "double"),
                       GetSQLValueString($_POST['totiva21'], "double"),
                       GetSQLValueString($_POST['totimp'], "double"),
                       GetSQLValueString($_POST['totcomis'], "double"),
                       GetSQLValueString($_POST['totneto105'], "double"),
                       GetSQLValueString($_POST['totneto21'], "double"),
					   GetSQLValueString($renglones, "int"),
					   GetSQLValueString($mascara, "text"),
					   "0", //GetSQLValueString(isset($_POST['tieneresol']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($_POST['codusu'], "int"),
					   GetSQLValueString($_POST['codusu'], "int"));
			// Medios de Pago  en Efectivo

 			mysqli_select_db($amercado, $database_amercado);
			$query_medios_pago = sprintf("SELECT * FROM cartvalores   WHERE cartvalores.tcomprel = %s  AND cartvalores.serierel = %s  AND cartvalores.ncomprel= %s",
                       GetSQLValueString($_POST['serie'], "int"),
					   GetSQLValueString($_POST['tcomp'], "int"),
					   $num_fac);

			$medios_pago = mysqli_query($amercado, $query_medios_pago) or die(mysqli_error($amercado));
			$row_medios_pago = mysqli_fetch_assoc($medios_pago);
			$totalRows_medios_pago = mysqli_num_rows($medios_pago);

			if ($totalRows_medios_pago==0 && strcmp(GetSQLValueString($_POST['GrupoOpciones1'], "text"),"'S'")==0) {
   				mysqli_select_db($amercado, $database_amercado);
   				$query_comprobante = "SELECT * FROM series  WHERE series.codnum =8";
   				$comprobante = mysqli_query($amercado, $query_comprobante) or die(mysqli_error($amercado));
   				$row_comprobante = mysqli_fetch_assoc($comprobante);
   				$totalRows_comprobante = mysqli_num_rows($comprobante);
    			$num_comp = ($row_comprobante['nroact'])+1 ; 
	        
  				$strSQL = sprintf("INSERT INTO cartvalores (tcomp, serie, ncomp,  codpais , importe , fechapago , fechaingr ,  serierel , tcomprel , estado , moneda ,codrem , ncomprel )
	VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
	                   GetSQLValueString("12", "int"),
                       GetSQLValueString("8", "int"),
                       GetSQLValueString("$num_comp", "int"),
                       GetSQLValueString("1", "int"),
					   GetSQLValueString($_POST['tot_general'], "double"),
                       $fecha_factura1,
                       $fecha_factura1,
                       GetSQLValueString($_POST['serie'], "int"),
					   GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString("P", "text"),
					   GetSQLValueString("1", "int"),
					   GetSQLValueString($_POST['remate_num'], "int"),
                       $num_fac);
		

				$result = mysqli_query($amercado, $strSQL);				         
			
				$actualiza = "UPDATE `series` SET `nroact` = '$num_comp' WHERE `series`.`codnum` ='8'" ;	
				$resultado=mysqli_query($amercado,	$actualiza);
				$total_fc = GetSQLValueString($_POST['tot_general'], "text");

			} 
			// Hasta aca los medios de PAgo Efectivo

  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  			if ($hay_lotes_pendientes == 1) {
  		 		$updateSQL = sprintf("UPDATE remates SET estado = %s , imptot = %s WHERE ncomp = %s" ,
  					GetSQLValueString("1", "int"),
					GetSQLValueString($_POST['tot_general'], "double"),
					GetSQLValueString($_POST['remate_num'], "int"));
  				mysqli_select_db($amercado, $database_amercado);
  				$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
 			}
 			else {
 				$updateSQL = sprintf("UPDATE remates SET estado = %s , imptot = %s WHERE ncomp = %s" ,
  					GetSQLValueString("1", "int"),
					GetSQLValueString($_POST['tot_general'], "double"),
					GetSQLValueString($_POST['remate_num'], "int"));
  				mysqli_select_db($amercado, $database_amercado);
  				$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
 			}
		}

		if (!empty($_POST['imprime'])) { 
			$facnum = $num_fac;
			$tipcomp = GetSQLValueString($_POST['tcomp'], "int");
			$numserie = GetSQLValueString($_POST['serie'], "int");
			$insertGoTo = "rp_facncB.php?ftcomp=$tipcomp&&fserie=$numserie&&fncomp=$facnum";
  			if (isset($_SERVER['QUERY_STRING'])) {
    			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    			$insertGoTo .= $_SERVER['QUERY_STRING'];
  			}
  			header(sprintf("Location: %s", "rp_facncB.php?ftcomp=$tipcomp&&fserie=$numserie&&fncomp=$facnum")); 

		} else { 
			
  			$facnum = $num_fac;
 			$insertGoTo = "facturaLB_ok.php?factura=$facnum";
  			if (isset($_SERVER['QUERY_STRING'])) {
    			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    			$insertGoTo .= $_SERVER['QUERY_STRING'];
  			}
  			header(sprintf("Location: %s", "facturaLB_ok.php?factura=$facnum")); 
		}
	}
	
	mysqli_select_db($amercado, $database_amercado);
	$query_facturas_a = "SELECT * FROM series  WHERE series.tipcomp=51  AND series.codnum=29";
	$facturas_a = mysqli_query($amercado, $query_facturas_a) or die(mysqli_error($amercado));
	$row_facturas_a = mysqli_fetch_assoc($facturas_a);
	$totalRows_facturas_a = mysqli_num_rows($facturas_a);
	$facturanum1 = ($row_facturas_a['nroact'])+1;
	// Agrega Mascara 
	$mascara1      = $row_facturas_a['mascara'];  
	$tcomp = $row_facturas_a['tipcomp'];
	mysqli_select_db($amercado, $database_amercado);
	$query_facturas_b = "SELECT * FROM series  WHERE series.tipcomp=53  AND series.codnum=30";
	$facturas_b = mysqli_query($amercado, $query_facturas_b) or die(mysqli_error($amercado));
	$row_facturas_b = mysqli_fetch_assoc($facturas_b);
	$totalRows_facturas_b = mysqli_num_rows($facturas_b);
	$facturanum2 = ($row_facturas_b['nroact'])+1;
	// DESDE ACA LA MASCARA
	$mascara2    = $row_facturas_b['mascara'];
	if ($mascara1='') {
 		$mascara = $mascara2 ;
		if ($facturanum2 <10) {
			$mascara=$mascara."-"."0000000".$facturanum2 ;
		}

		if ($facturanum2 >9 && $facturanum2 <=99) {
			$mascara=$mascara."-"."000000".$facturanum2;
		}

		if ($facturanum2 >99 && $facturanum2 <=999) {
			$mascara=$mascara."-"."00000".$facturanum2;
		}
		if ($facturanum2 >999 && $facturanum2 <=9999) {
			$mascara=$mascara."-"."0000".$facturanum2;
		}
		if ($facturanum2 >9999 && $facturanum2 <99999) {
			$mascara=$mascara."-"."000".$facturanum2;
		}

	} else {

 		$mascara = $mascara1 ;
 		if ($facturanum1 <10) {
			$mascara=$mascara."-"."0000000".$facturanum1 ;
		}

		if ($facturanum1 >9 && $facturanum1 <=99) {
			$mascara=$mascara."-"."000000".$facturanum1;
		}

		if ($facturanum1 >99 && $facturanum1 <=999) {
			$mascara=$mascara."-"."00000".$facturanum1;
		}
		if ($facturanum1 >999 && $facturanum1 <=9999) {
			$mascara=$mascara."-"."0000".$facturanum1;
		}
		if ($facturanum1 >9999 && $facturanum1 <99999) {
			$mascara=$mascara."-"."000".$facturanum1;
		}
	}

}  // ESTA ES LA NUEVA LLAVE 09082010

?>
<script language="javascript">
function agregarOpciones(form)
{
var selec = form.tipos.options;
    
		factura.serie.value = 30;
		factura.serie_texto.value = "SERIE DE FACTURA B0004";
		factura.tcomp.value = 53;
    
}
</script>
<?php
mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

//LEO TABLA LOTES
$query_lotes_rem = sprintf("SELECT * FROM lotes WHERE codrem = $codremate AND estado = 0 AND preciobase > 0");
$lotes_rem = mysqli_query($amercado, $query_lotes_rem) or die("ERROR LEYENDO LOTES");
$row_lotes_rem = mysqli_fetch_assoc($lotes_rem);
$totalRows_lotes_rem = mysqli_num_rows($lotes_rem);

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE (tipoent = '1' OR tipoent = '2') AND activo != 0 AND tipoiva != '1' AND tipoiva != '3' AND cuit != '\0' ORDER BY razsoc ASC";
$cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

$colname_serie = "30";
if (isset($_POST['tcomp'])) {
  $colname_serie = addslashes($_POST['tcomp']);
}

$nivel = 9 ;
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = sprintf("SELECT * FROM `remates` WHERE `fecest` >= NOW() ORDER BY `ncomp` desc");
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

$query_impuesto = "SELECT * FROM impuestos";
$impuesto = mysqli_query($amercado, $query_impuesto) or die(mysqli_error($amercado));
$row_impuesto = mysqli_fetch_assoc($impuesto);
$totalRows_impuesto = mysqli_num_rows($impuesto);
$iva_21_desc = mysqli_result($impuesto,0,2)."<br>";
$iva_21_porcen = mysqli_result($impuesto,0,1);
$iva_15_desc = mysqli_result($impuesto,1,2)."<br>";
$iva_15_porcen = mysqli_result($impuesto,1,1);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php 
 require_once('Connections/amercado.php');  ?>
<script language="javascript">
function sin_lotes(form)
{
	alert("Debe ingresar al menos un lote para facturar");
}
</script> 
<script language="javascript">
	// Lote 1
function getprov(form) {
	var seleccion =  form.lote.options;
	var cantidad  =  form.lote.options.length;
	var cant = (cantidad+1) ;
	var contador = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote.value;
	if (total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote.value = "" ;
		form.descripcion.value = "" ;
        form.comision.value = "" ;
        form.tasa.value = "" ;
        form.descripcion.value = "" ;
        form.importe.value = "" ;
		return 0;
	}
	for ( contador ; contador < cant ; contador++) {
   		if (seleccion[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion.value = "" ;
			form.comision.value = "" ;
			form.tasa.value = "" ;
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
	  		var opcion2 = opcion2.substring(posicion + 2,posicion + 4);
	  		form.comision.value = opcion2+" ";
			var opcion3 = new String (seleccion[contador].text);
	  		var opcion3 = opcion3.substring(posicion + 7,posicion + 10);
	  		form.tasa.value = opcion3+" ";
			var opcion4 = new String (seleccion[contador].text);
	  		var opcion4 = opcion4.substring(posicion + 13,100);
	  		form.importe.value = opcion4;
      		form.importe.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 2
function getprov1(form) {
	var seleccion1 =  form.lote1.options;
	var cantidad1  =  form.lote1.options.length;
	var cant1 = (cantidad1+1) ;
	var contador1 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote1.value;
	if (total === form.lote.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote1.value = "" ;
		form.descripcion1.value = "" ;
        form.comision1.value = "" ;
        form.tasa1.value = "" ;
        form.descripcion1.value = "" ;
        form.importe1.value = "" ;
		return 0;
	}
	for ( contador1 ; contador1 < cant1 ; contador1++) {
   		if (seleccion1[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion1.value = "" ;
			form.comision1.value = "" ;
			form.tasa1.value = "" ;
	  		form.descripcion1.disabled = true ;
      		form.importe1.disabled = true ;
	  		break ;	
    	}
    	if (seleccion1[contador1].selected) { 
	    	var opcion1 = new String (seleccion1[contador1].text);
	  		let posicion1 = opcion1.indexOf("|");
			if (posicion1 !== -1)
				opcion1 = opcion1.substring(2,posicion1 - 2);
	  		form.descripcion1.value = opcion1+" ";
			var opcion2_1 = new String (seleccion1[contador1].text);
	  		opcion2_1 = opcion2_1.substring(posicion1 + 2, posicion1 + 4);
	  		form.comision1.value = opcion2_1+" ";
			var opcion3_1 = new String (seleccion1[contador1].text);
	  		opcion3_1 = opcion3_1.substring(posicion1 + 7,posicion1 + 10);
	  		form.tasa1.value = opcion3_1+" ";
			var opcion4_1 = new String (seleccion1[contador1].text);
	  		opcion4_1 = opcion4_1.substring(posicion1 + 13,100);
	  		form.importe1.value = opcion4_1;
      		form.importe1.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 3
function getprov2(form) {
	var seleccion2 =  form.lote2.options;
	var cantidad2  =  form.lote2.options.length;
	var cant2 = (cantidad2+1) ;
	var contador2 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote2.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote2.value = "" ;
		form.descripcion2.value = "" ;
        form.comision2.value = "" ;
        form.tasa2.value = "" ;
        form.descripcion2.value = "" ;
        form.importe2.value = "" ;
		return 0;
	}
	for ( contador2 ; contador2 < cant2 ; contador2++) {
   		if (seleccion2[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion2.value = "" ;
			form.comision2.value = "" ;
			form.tasa2.value = "" ;
	  		form.descripcion2.disabled = true ;
      		form.importe2.disabled = true ;
	  		break ;	
    	}
    	if (seleccion2[contador2].selected) { 
	    	var opcion2 = new String (seleccion2[contador2].text);
			let posicion2 = opcion2.indexOf("|");
				if (posicion2 !== -1)
	  		var opcion2 = opcion2.substring(2,posicion2 - 2);
	  		form.descripcion2.value = opcion2+" ";
			var opcion2_2 = new String (seleccion2[contador2].text);
	  		opcion2_2 = opcion2_2.substring(posicion2 + 2, posicion2 + 4);
	  		form.comision2.value = opcion2_2+" ";
			var opcion3_2 = new String (seleccion2[contador2].text);
	  		opcion3_2 = opcion3_2.substring(posicion2 + 7,posicion2 + 10);
	  		form.tasa2.value = opcion3_2+" ";
			var opcion4_2 = new String (seleccion2[contador2].text);
	  		opcion4_2 = opcion4_2.substring(posicion2 + 13,100);
	  		form.importe2.value = opcion4_2;
      		form.importe2.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 4
function getprov3(form) {
	var seleccion3 =  form.lote3.options;
	var cantidad3  =  form.lote3.options.length;
	var cant3 = (cantidad3+1) ;
	var contador3 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote3.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote3.value = "" ;
		form.descripcion3.value = "" ;
        form.comision3.value = "" ;
        form.tasa3.value = "" ;
        form.descripcion3.value = "" ;
        form.importe3.value = "" ;
		return 0;
	}
	for ( contador3 ; contador3 < cant3 ; contador3++) {
   		if (seleccion3[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion3.value = "" ;
			form.comision3.value = "" ;
			form.tasa3.value = "" ;
	  		form.descripcion3.disabled = true ;
      		form.importe3.disabled = true ;
	  		break ;	
    	}
    	if (seleccion3[contador3].selected) { 
	    	var opcion3 = new String (seleccion3[contador3].text);
			let posicion3 = opcion3.indexOf("|");
				if (posicion3 !== -1)
	  		var opcion3 = opcion3.substring(2,posicion3 - 2);
	  		form.descripcion3.value = opcion3+" ";
			var opcion3_2 = new String (seleccion3[contador3].text);
	  		opcion3_2 = opcion3_2.substring(posicion3 + 2, posicion3 + 4);
	  		form.comision3.value = opcion3_2+" ";
			var opcion3_3 = new String (seleccion3[contador3].text);
	  		opcion3_3 = opcion3_3.substring(posicion3 + 7,posicion3 + 10);
	  		form.tasa3.value = opcion3_3+" ";
			var opcion3_4 = new String (seleccion3[contador3].text);
	  		opcion3_4 = opcion3_4.substring(posicion3 + 13,100);
	  		form.importe3.value = opcion3_4;
      		form.importe3.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 5
function getprov4(form) {
	var seleccion4 =  form.lote4.options;
	var cantidad4  =  form.lote4.options.length;
	var cant4 = (cantidad4+1) ;
	var contador4 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote4.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote4.value = "" ;
		form.descripcion4.value = "" ;
        form.comision4.value = "" ;
        form.tasa4.value = "" ;
        form.descripcion4.value = "" ;
        form.importe4.value = "" ;
		return 0;
	}
	for ( contador4 ; contador4 < cant4 ; contador4++) {
   		if (seleccion4[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion4.value = "" ;
			form.comision4.value = "" ;
			form.tasa4.value = "" ;
	  		form.descripcion4.disabled = true ;
      		form.importe4.disabled = true ;
	  		break ;	
    	}
    	if (seleccion4[contador4].selected) { 
	    	var opcion4 = new String (seleccion4[contador4].text);
			let posicion4 = opcion4.indexOf("|");
				if (posicion4 !== -1)
	  		var opcion4 = opcion4.substring(2,posicion4 - 2);
	  		form.descripcion4.value = opcion4+" ";
			var opcion4_2 = new String (seleccion4[contador4].text);
	  		opcion4_2 = opcion4_2.substring(posicion4 + 2, posicion4 + 4);
	  		form.comision4.value = opcion4_2+" ";
			var opcion4_3 = new String (seleccion4[contador4].text);
	  		opcion4_3 = opcion4_3.substring(posicion4 + 7,posicion4 + 10);
	  		form.tasa4.value = opcion4_3+" ";
			var opcion4_4 = new String (seleccion4[contador4].text);
	  		opcion4_4 = opcion4_4.substring(posicion4 + 13,100);
	  		form.importe4.value = opcion4_4;
      		form.importe4.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 6
function getprov5(form) {
	var seleccion5 =  form.lote5.options;
	var cantidad5  =  form.lote5.options.length;
	var cant5 = (cantidad5+1) ;
	var contador5 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote5.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote5.value = "" ;
		form.descripcion5.value = "" ;
        form.comision5.value = "" ;
        form.tasa5.value = "" ;
        form.descripcion5.value = "" ;
        form.importe5.value = "" ;
		return 0;
	}
	for ( contador5 ; contador5 < cant5 ; contador5++) {
   		if (seleccion5[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion5.value = "" ;
			form.comision5.value = "" ;
			form.tasa5.value = "" ;
	  		form.descripcion5.disabled = true ;
      		form.importe5.disabled = true ;
	  		break ;	
    	}
    	if (seleccion5[contador5].selected) { 
	    	var opcion5 = new String (seleccion5[contador5].text);
			let posicion5 = opcion5.indexOf("|");
				if (posicion5 !== -1)
	  		var opcion5 = opcion5.substring(2,posicion5 - 2);
	  		form.descripcion5.value = opcion5+" ";
			var opcion5_2 = new String (seleccion5[contador5].text);
	  		opcion5_2 = opcion5_2.substring(posicion5 + 2, posicion5 + 4);
	  		form.comision5.value = opcion5_2+" ";
			var opcion5_3 = new String (seleccion5[contador5].text);
	  		opcion5_3 = opcion5_3.substring(posicion5 + 7,posicion5 + 10);
	  		form.tasa5.value = opcion5_3+" ";
			var opcion5_4 = new String (seleccion5[contador5].text);
	  		opcion5_4 = opcion5_4.substring(posicion5 + 13,100);
	  		form.importe5.value = opcion5_4;
      		form.importe5.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 7
function getprov6(form) {
	var seleccion6 =  form.lote6.options;
	var cantidad6  =  form.lote6.options.length;
	var cant6 = (cantidad6+1) ;
	var contador6 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote6.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote6.value = "" ;
		form.descripcion6.value = "" ;
        form.comision6.value = "" ;
        form.tasa6.value = "" ;
        form.descripcion6.value = "" ;
        form.importe6.value = "" ;
		return 0;
	}
	for ( contador6 ; contador6 < cant6 ; contador6++) {
   		if (seleccion6[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion6.value = "" ;
			form.comision6.value = "" ;
			form.tasa6.value = "" ;
	  		form.descripcion6.disabled = true ;
      		form.importe6.disabled = true ;
	  		break ;	
    	}
    	if (seleccion6[contador6].selected) { 
	    	var opcion6 = new String (seleccion6[contador6].text);
			let posicion6 = opcion6.indexOf("|");
				if (posicion6 !== -1)
	  		var opcion6 = opcion6.substring(2,posicion6 - 2);
	  		form.descripcion6.value = opcion6+" ";
			var opcion6_2 = new String (seleccion6[contador6].text);
	  		opcion6_2 = opcion6_2.substring(posicion6 + 2, posicion6 + 4);
	  		form.comision6.value = opcion6_2+" ";
			var opcion6_3 = new String (seleccion6[contador6].text);
	  		opcion6_3 = opcion6_3.substring(posicion6 + 7,posicion6 + 10);
	  		form.tasa6.value = opcion6_3+" ";
			var opcion6_4 = new String (seleccion6[contador6].text);
	  		opcion6_4 = opcion6_4.substring(posicion6 + 13,100);
	  		form.importe6.value = opcion6_4;
      		form.importe6.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 8
function getprov7(form) {
	var seleccion7 =  form.lote7.options;
	var cantidad7  =  form.lote7.options.length;
	var cant7 = (cantidad7+1) ;
	var contador7 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote7.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote7.value = "" ;
		form.descripcion7.value = "" ;
        form.comision7.value = "" ;
        form.tasa7.value = "" ;
        form.descripcion7.value = "" ;
        form.importe7.value = "" ;
		return 0;
	}
	for ( contador7 ; contador7 < cant7 ; contador7++) {
   		if (seleccion7[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion7.value = "" ;
			form.comision7.value = "" ;
			form.tasa7.value = "" ;
	  		form.descripcion7.disabled = true ;
      		form.importe7.disabled = true ;
	  		break ;	
    	}
    	if (seleccion7[contador7].selected) { 
	    	var opcion7 = new String (seleccion7[contador7].text);
			let posicion7 = opcion7.indexOf("|");
				if (posicion7 !== -1)
	  		var opcion7 = opcion7.substring(2,posicion7 - 2);
	  		form.descripcion7.value = opcion7+" ";
			var opcion7_2 = new String (seleccion7[contador7].text);
	  		opcion7_2 = opcion7_2.substring(posicion7 + 2, posicion7 + 4);
	  		form.comision7.value = opcion7_2+" ";
			var opcion7_3 = new String (seleccion7[contador7].text);
	  		opcion7_3 = opcion7_3.substring(posicion7 + 7,posicion7 + 10);
	  		form.tasa7.value = opcion7_3+" ";
			var opcion7_4 = new String (seleccion7[contador7].text);
	  		opcion7_4 = opcion7_4.substring(posicion7 + 13,100);
	  		form.importe7.value = opcion7_4;
      		form.importe7.focus();
	   	}
	}
}  
</script> 
</script> 
<script language="javascript">
	// Lote 9
function getprov8(form) {
	var seleccion8 =  form.lote8.options;
	var cantidad8  =  form.lote8.options.length;
	var cant8 = (cantidad8+1) ;
	var contador8 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote8.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote8.value = "" ;
		form.descripcion8.value = "" ;
        form.comision8.value = "" ;
        form.tasa8.value = "" ;
        form.descripcion8.value = "" ;
        form.importe8.value = "" ;
		return 0;
	}
	for ( contador8 ; contador8 < cant8 ; contador8++) {
   		if (seleccion8[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion8.value = "" ;
			form.comision8.value = "" ;
			form.tasa8.value = "" ;
	  		form.descripcion8.disabled = true ;
      		form.importe8.disabled = true ;
	  		break ;	
    	}
    	if (seleccion8[contador8].selected) { 
	    	var opcion8 = new String (seleccion8[contador8].text);
			let posicion8 = opcion8.indexOf("|");
				if (posicion8 !== -1)
	  		var opcion8 = opcion8.substring(2,posicion8 - 2);
	  		form.descripcion8.value = opcion8+" ";
			var opcion8_2 = new String (seleccion8[contador8].text);
	  		opcion8_2 = opcion8_2.substring(posicion8 + 2, posicion8 + 4);
	  		form.comision8.value = opcion8_2+" ";
			var opcion8_3 = new String (seleccion8[contador8].text);
	  		opcion8_3 = opcion8_3.substring(posicion8 + 7,posicion8 + 10);
	  		form.tasa8.value = opcion8_3+" ";
			var opcion8_4 = new String (seleccion8[contador8].text);
	  		opcion8_4 = opcion8_4.substring(posicion8 + 13,100);
	  		form.importe8.value = opcion8_4;
      		form.importe8.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 10
function getprov9(form) {
	var seleccion9 =  form.lote9.options;
	var cantidad9  =  form.lote9.options.length;
	var cant9 = (cantidad9+1) ;
	var contador9 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote9.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote9.value = "" ;
		form.descripcion9.value = "" ;
        form.comision9.value = "" ;
        form.tasa9.value = "" ;
        form.descripcion9.value = "" ;
        form.importe9.value = "" ;
		return 0;
	}
	for ( contador9 ; contador9 < cant9 ; contador9++) {
   		if (seleccion9[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion9.value = "" ;
			form.comision9.value = "" ;
			form.tasa9.value = "" ;
	  		form.descripcion9.disabled = true ;
      		form.importe9.disabled = true ;
	  		break ;	
    	}
    	if (seleccion9[contador9].selected) { 
	    	var opcion9 = new String (seleccion9[contador9].text);
			let posicion9 = opcion9.indexOf("|");
				if (posicion9 !== -1)
	  		var opcion9 = opcion9.substring(2,posicion9 - 2);
	  		form.descripcion9.value = opcion9+" ";
			var opcion9_2 = new String (seleccion9[contador9].text);
	  		opcion9_2 = opcion9_2.substring(posicion9 + 2, posicion9 + 4);
	  		form.comision9.value = opcion9_2+" ";
			var opcion9_3 = new String (seleccion9[contador9].text);
	  		opcion9_3 = opcion9_3.substring(posicion9 + 7,posicion9 + 10);
	  		form.tasa9.value = opcion9_3+" ";
			var opcion9_4 = new String (seleccion9[contador9].text);
	  		opcion9_4 = opcion9_4.substring(posicion9 + 13,100);
	  		form.importe9.value = opcion9_4;
      		form.importe9.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 11
function getprov10(form) {
	var seleccion10 =  form.lote10.options;
	var cantidad10  =  form.lote10.options.length;
	var cant10 = (cantidad10+1) ;
	var contador10 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote10.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value) { 
		alert("EL lote ya ha sido seleccionado");
		form.lote10value = "" ;
		form.descripcion10.value = "" ;
        form.comision10.value = "" ;
        form.tasa10.value = "" ;
        form.descripcion10.value = "" ;
        form.importe10.value = "" ;
		return 0;
	}
	for ( contador10 ; contador10 < cant10 ; contador10++) {
   		if (seleccion10[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion10.value = "" ;
			form.comision10.value = "" ;
			form.tasa10.value = "" ;
	  		form.descripcion10.disabled = true ;
      		form.importe10.disabled = true ;
	  		break ;	
    	}
    	if (seleccion10[contador10].selected) { 
	    	var opcion10 = new String (seleccion10[contador10].text);
			let posicion10 = opcion10.indexOf("|");
				if (posicion10 !== -1)
	  		var opcion10 = opcion10.substring(2,posicion10 - 2);
	  		form.descripcion10.value = opcion10+" ";
			var opcion10_2 = new String (seleccion10[contador10].text);
	  		opcion10_2 = opcion10_2.substring(posicion10 + 2, posicion10 + 4);
	  		form.comision10.value = opcion10_2+" ";
			var opcion10_3 = new String (seleccion10[contador10].text);
	  		opcion10_3 = opcion10_3.substring(posicion10 + 7,posicion10 + 10);
	  		form.tasa10.value = opcion10_3+" ";
			var opcion10_4 = new String (seleccion10[contador10].text);
	  		opcion10_4 = opcion10_4.substring(posicion10 + 13,100);
	  		form.importe10.value = opcion10_4;
      		form.importe10.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 12
function getprov11(form) {
	var seleccion11 =  form.lote11.options;
	var cantidad11  =  form.lote11.options.length;
	var cant11 = (cantidad11+1) ;
	var contador11 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote11.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote12.value || total === form.lote13.value || total === form.lote14.value)  {
		alert("EL lote ya ha sido seleccionado");
		form.lote11.value = "" ;
		form.descripcion11.value = "" ;
        form.comision11.value = "" ;
        form.tasa11.value = "" ;
        form.descripcion11.value = "" ;
        form.importe11.value = "" ;
		return 0;
	}
	for ( contador11 ; contador11 < cant11 ; contador11++) {
   		if (seleccion11[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion11.value = "" ;
			form.comision11.value = "" ;
			form.tasa11.value = "" ;
	  		form.descripcion11.disabled = true ;
      		form.importe11.disabled = true ;
	  		break ;	
    	}
    	if (seleccion11[contador11].selected) { 
	    	var opcion11 = new String (seleccion11[contador11].text);
			let posicion11 = opcion11.indexOf("|");
				if (posicion11 !== -1)
	  		var opcion11 = opcion11.substring(2,posicion11 - 2);
	  		form.descripcion11.value = opcion11+" ";
			var opcion11_2 = new String (seleccion11[contador11].text);
	  		opcion11_2 = opcion11_2.substring(posicion11 + 2, posicion11 + 4);
	  		form.comision11.value = opcion11_2+" ";
			var opcion11_3 = new String (seleccion11[contador11].text);
	  		opcion11_3 = opcion11_3.substring(posicion11 + 7,posicion11 + 10);
	  		form.tasa11.value = opcion11_3+" ";
			var opcion11_4 = new String (seleccion11[contador11].text);
	  		opcion11_4 = opcion11_4.substring(posicion11 + 13,100);
	  		form.importe11.value = opcion11_4;
      		form.importe11.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 13
function getprov12(form) {
	var seleccion12 =  form.lote12.options;
	var cantidad12  =  form.lote12.options.length;
	var cant12 = (cantidad12+1) ;
	var contador12 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote12.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value ||  total === form.lote13.value || total === form.lote14.value)  {
		alert("EL lote ya ha sido seleccionado");
		form.lote12.value = "" ;
		form.descripcion12.value = "" ;
        form.comision12.value = "" ;
        form.tasa12.value = "" ;
        form.descripcion12.value = "" ;
        form.importe12.value = "" ;
		return 0;
	}
	for ( contador12 ; contador12 < cant12 ; contador12++) {
   		if (seleccion12[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion12.value = "" ;
			form.comision12.value = "" ;
			form.tasa12.value = "" ;
	  		form.descripcion12.disabled = true ;
      		form.importe12.disabled = true ;
	  		break ;	
    	}
    	if (seleccion12[contador12].selected) { 
	    	var opcion12 = new String (seleccion12[contador12].text);
			let posicion12 = opcion12.indexOf("|");
				if (posicion12 !== -1)
	  		var opcion12 = opcion12.substring(2,posicion12 - 2);
	  		form.descripcion12.value = opcion12+" ";
			var opcion12_2 = new String (seleccion12[contador12].text);
	  		opcion12_2 = opcion12_2.substring(posicion12 + 2, posicion12 + 4);
	  		form.comision12.value = opcion12_2+" ";
			var opcion12_3 = new String (seleccion12[contador12].text);
	  		opcion12_3 = opcion12_3.substring(posicion12 + 7,posicion12 + 10);
	  		form.tasa12.value = opcion12_3+" ";
			var opcion12_4 = new String (seleccion12[contador12].text);
	  		opcion12_4 = opcion12_4.substring(posicion12 + 13,100);
	  		form.importe12.value = opcion12_4;
      		form.importe12.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 14
function getprov13(form) {
	var seleccion13 =  form.lote13.options;
	var cantidad13  =  form.lote13.options.length;
	var cant13 = (cantidad13+1) ;
	var contador13 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote13.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote14.value) {
		alert("EL lote ya ha sido seleccionado");
		form.lote13.value = "" ;
		form.descripcion13.value = "" ;
        form.comision13.value = "" ;
        form.tasa13.value = "" ;
        form.descripcion13.value = "" ;
        form.importe13.value = "" ;
		return 0;
	}
	for ( contador13 ; contador13 < cant13 ; contador13++) {
   		if (seleccion13[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion13.value = "" ;
			form.comision13.value = "" ;
			form.tasa13.value = "" ;
	  		form.descripcion13.disabled = true ;
      		form.importe13.disabled = true ;
	  		break ;	
    	}
    	if (seleccion13[contador13].selected) { 
	    	var opcion13 = new String (seleccion13[contador13].text);
			let posicion13 = opcion13.indexOf("|");
				if (posicion13 !== -1)
	  		var opcion13 = opcion13.substring(2,posicion13 - 2);
	  		form.descripcion13.value = opcion13+" ";
			var opcion13_2 = new String (seleccion13[contador13].text);
	  		opcion13_2 = opcion13_2.substring(posicion13 + 2, posicion13 + 4);
	  		form.comision13.value = opcion13_2+" ";
			var opcion13_3 = new String (seleccion13[contador13].text);
	  		opcion13_3 = opcion13_3.substring(posicion13 + 7,posicion13 + 10);
	  		form.tasa13.value = opcion13_3+" ";
			var opcion13_4 = new String (seleccion13[contador13].text);
	  		opcion13_4 = opcion13_4.substring(posicion13 + 13,100);
	  		form.importe13.value = opcion13_4;
      		form.importe13.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
	// Lote 15
function getprov14(form) {
	var seleccion14 =  form.lote14.options;
	var cantidad14  =  form.lote14.options.length;
	var cant14 = (cantidad14+1) ;
	var contador14 = 0;
	// A VER SI MARCA LOS REPETIDOS
	var total = form.lote14.value;
	if (total === form.lote.value || total === form.lote1.value || total === form.lote2.value || total === form.lote3.value || total === form.lote4.value || total === form.lote5.value || total === form.lote6.value || total === form.lote7.value || total === form.lote8.value || total === form.lote9.value || total === form.lote10.value || total === form.lote11.value || total === form.lote12.value || total === form.lote13.value) {
		alert("EL lote ya ha sido seleccionado");
		form.lote14.value = "" ;
		form.descripcion14.value = "" ;
        form.comision14.value = "" ;
        form.tasa14.value = "" ;
        form.descripcion14.value = "" ;
        form.importe14.value = "" ;
		return 0;
	}
	for ( contador14 ; contador14 < cant14 ; contador14++) {
   		if (seleccion14[0].selected === true) { 
	   		alert("Debe seleccionar una opcion");
	  		form.descripcion14.value = "" ;
			form.comision14.value = "" ;
			form.tasa14.value = "" ;
	  		form.descripcion14.disabled = true ;
      		form.importe14.disabled = true ;
	  		break ;	
    	}
    	if (seleccion14[contador14].selected) { 
	    	var opcion14 = new String (seleccion14[contador14].text);
			let posicion14 = opcion14.indexOf("|");
				if (posicion14 !== -1)
	  		var opcion14 = opcion14.substring(2,posicion14 - 2);
	  		form.descripcion14.value = opcion14+" ";
			var opcion14_2 = new String (seleccion14[contador14].text);
	  		opcion14_2 = opcion14_2.substring(posicion14 + 2, posicion14 + 4);
	  		form.comision14.value = opcion14_2+" ";
			var opcion14_3 = new String (seleccion14[contador14].text);
	  		opcion14_3 = opcion14_3.substring(posicion14 + 7,posicion14 + 10);
	  		form.tasa14.value = opcion14_3+" ";
			var opcion14_4 = new String (seleccion14[contador14].text);
	  		opcion14_4 = opcion14_4.substring(posicion14 + 13,100);
	  		form.importe14.value = opcion14_4;
      		form.importe14.focus();
	   	}
	}
}  
</script> 
<script language="javascript">
function validarFormulario(form)
{
		
	var monto  = factura.importe.value; // Monto  primer lote
	var comi   = factura.comision.value; // Comision  primer lote
	var imp105 = (factura.impuesto[0].value) / 100; // Impuesto 10,5 %
	var imp21  = (factura.impuesto[1].value) / 100; // impuesto 21 %
	var tasaadm = factura.tasa.value; // % Tasa Administrativa
		
	var monto1 = factura.importe1.value; // Monto segundo lote
	var comi1  = factura.comision1.value;// Comision  segundo lote
	var imp105_1 = (factura.impuesto1[0].value) / 100; // Impuesto 10,5 %
	var imp21_1  = (factura.impuesto1[1].value) / 100; // impuesto 21 %
	var tasaadm1 = factura.tasa1.value; // % Tasa Administrativa
	
	
	var monto2 = factura.importe2.value; // Monto tercer lote
	var comi2  = factura.comision2.value; // Comision  tercer lote
	var imp105_2 = (factura.impuesto2[0].value)/100; // Impuesto 10,5 %
	var imp21_2  = (factura.impuesto2[1].value)/100; // impuesto 21 %
	var tasaadm2 = factura.tasa2.value; // % Tasa Administrativa
	
	var monto3 = factura.importe3.value; // Monto cuarto lote
	var comi3  = factura.comision3.value; // Comision  cuarto lote
	var imp105_3 = (factura.impuesto3[0].value)/100; // Impuesto 10,5 %
	var imp21_3  = (factura.impuesto3[1].value)/100; // impuesto 21 %
	var tasaadm3 = factura.tasa3.value; // % Tasa Administrativa
	
	var monto4 = factura.importe4.value; // Monto Quinto lote
	var comi4  = factura.comision4.value; // Comision  cuarto lote
	var imp105_4 = (factura.impuesto4[0].value)/100; // Impuesto 10,5 %
	var imp21_4  = (factura.impuesto4[1].value)/100; // impuesto 21 %
	var tasaadm4 = factura.tasa4.value; // % Tasa Administrativa
	
	var monto5 = factura.importe5.value; // Monto Sexto lote
	var comi5   = factura.comision5.value;  // Comision  Sexto lote
	var imp105_5 = (factura.impuesto5[0].value)/100; // Impuesto 10,5 %
	var imp21_5  = (factura.impuesto5[1].value)/100; // impuesto 21 %
	var tasaadm5 = factura.tasa5.value; // % Tasa Administrativa
	
	var monto6  = factura.importe6.value; // Monto Septimo lote
	var comi6   = factura.comision6.value; // Comision  Septimo lote
	var imp105_6 = (factura.impuesto6[0].value)/100; // Impuesto 10,5 %
	var imp21_6  = (factura.impuesto6[1].value)/100; // impuesto 21 %
	var tasaadm6 = factura.tasa6.value; // % Tasa Administrativa
	
	var monto7  = factura.importe7.value; // Monto Octavo lote
	var comi7   = factura.comision7.value; // Comision  Octavo lote
	var imp105_7 = (factura.impuesto7[0].value)/100; // Impuesto 10,5 %
	var imp21_7  = (factura.impuesto7[1].value)/100; // impuesto 21 %
	var tasaadm7 = factura.tasa7.value; // % Tasa Administrativa
	
	var monto8  = factura.importe8.value; // Monto Noveno lote
	var comi8   = factura.comision8.value; // Comision  Noveno lote
	var imp105_8 = (factura.impuesto8[0].value)/100; // Impuesto 10,5 %
	var imp21_8  = (factura.impuesto8[1].value)/100; // impuesto 21 %
	var tasaadm8 = factura.tasa8.value; // % Tasa Administrativa
	
	var monto9  = factura.importe9.value; // Monto DÃ©cimo lote
	var comi9   = factura.comision9.value; // Comision  DÃ©cimo lote
	var imp105_9 = (factura.impuesto9[0].value)/100; // Impuesto 10,5 %
	var imp21_9  = (factura.impuesto9[1].value)/100; // impuesto 21 %
	var tasaadm9 = factura.tasa9.value; // % Tasa Administrativa
	
	var monto10 = factura.importe10.value;  // Monto Onceavo lote
	var comi10  = factura.comision10.value; // Comision  Onceavo lote
	var imp105_10 = (factura.impuesto10[0].value)/100; // Impuesto 10,5 %
	var imp21_10  = (factura.impuesto10[1].value)/100; // impuesto 21 %
	var tasaadm10 = factura.tasa10.value; // % Tasa Administrativa
		
	var monto11 = factura.importe11.value; // Monto Doceavo lote
	var comi11  = factura.comision11.value; // Comision  Doceavo lote
	var imp105_11 = (factura.impuesto11[0].value)/100; // Impuesto 10,5 %
	var imp21_11  = (factura.impuesto11[1].value)/100; // impuesto 21 %
	var tasaadm11 = factura.tasa11.value; // % Tasa Administrativa
	
	var monto12 = factura.importe12.value; // Monto Treceavo lote
	var comi12  = factura.comision12.value; // Comision  Treceavo lote
	var imp105_12 = (factura.impuesto12[0].value)/100; // Impuesto 10,5 %
	var imp21_12  = (factura.impuesto12[1].value)/100; // impuesto 21 %
	var tasaadm12 = factura.tasa12.value; // % Tasa Administrativa
	
	var monto13 = factura.importe13.value; // Monto Catorceavo lote
	var comi13  = factura.comision13.value; // Comision  Catorceavo lote
	var imp105_13 = (factura.impuesto13[0].value)/100; // Impuesto 10,5 %
	var imp21_13  = (factura.impuesto13[1].value)/100; // impuesto 21 %
	var tasaadm13 = factura.tasa13.value; // % Tasa Administrativa
	
	var monto14 = factura.importe14.value; // Monto Quinceavo lote
	var comi14  = factura.comision14.value; // Comision  Quinceavo lote
	var imp105_14 = (factura.impuesto14[0].value)/100; // Impuesto 10,5 %
	var imp21_14  = (factura.impuesto14[1].value)/100; // impuesto 21 %
	var tasaadm14 = factura.tasa14.value; // % Tasa Administrativa
	
    var  tot_mon = 0 ;
    var tot_comi = 0 ;
	var neto105 = 0;
	var neto21 = 0 ;
	var imp_tot105 = 0 ;
	var imp_tot21 = 0 ;
	var tot_mon105 = 0 ;
	var tot_mon21  = 0;
	var totresol  = 0;
	var tot_tasa = 0;
	var tot_tasa1 = 0;
	var tot_tasa2 = 0;
	var tot_tasa3 = 0;
	var tot_tasa4 = 0;
	var tot_tasa5 = 0;
	var tot_tasa6 = 0;
	var tot_tasa7 = 0;
	var tot_tasa8 = 0;
	var tot_tasa9 = 0;
	var tot_tasa10 = 0;
	var tot_tasa11 = 0;
	
	var acum_tasa = 0;
	// LOTE 1 AL 10,5 %
    if (factura.impuesto[0].checked===true) {
    	if (monto.length!=0 ) {
			if (tasaadm!=0) {
			
              if (monto <= 5000)
                    tot_tasa = 100.0 * (tasaadm / 100.0);
                else if(monto <= 20000)
                        tot_tasa = 500.0 * (tasaadm / 100.0);
                else if (monto <= 40000)
                    tot_tasa =  1200.0 * (tasaadm / 100.0);
                else if (monto <= 80000)
                    tot_tasa = 2400.0 * (tasaadm / 100.0);
                else if (monto <= 100000)
                    tot_tasa =  3600.0 * (tasaadm / 100.0);
                else if (monto <= 150000)
                    tot_tasa = 5000.0 * (tasaadm / 100.0);
                else if (monto <= 200000)
                    tot_tasa =  7000.0 * (tasaadm / 100.0);
                else if (monto <= 300000)
                    tot_tasa = 10000.0 * (tasaadm / 100.0);
                else if (monto <= 400000)
                    tot_tasa = 14000.0 * (tasaadm / 100.0);
                else if (monto <= 500000)
                    tot_tasa = 18000.0 * (tasaadm / 100.0);
                else if (monto <= 600000)
                    tot_tasa = 22000.0 * (tasaadm / 100.0);
                else if (monto <= 700000)
                    tot_tasa = 26000.0 * (tasaadm / 100.0);
                else if (monto <= 800000)
                    tot_tasa = 30000.0 * (tasaadm / 100.0);
                else if (monto <= 1000000)
                    tot_tasa = 36000.0 * (tasaadm / 100.0);
                else if (monto <= 1500000)
                    tot_tasa = 50000.0 * (tasaadm / 100.0);
                else if (monto <= 2000000)
                    tot_tasa = 70000.0 * (tasaadm / 100.0);
                else
                    tot_tasa = 80000.0 * (tasaadm / 100.0);
			}
			else
				tot_tasa = 0.00;
		
			tot_mon = eval(monto);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi = (comi * monto) / 100;
			tot_mon105 = eval(monto);
			//tot_mon105 = tot_mon105.toFixed(2);
	        imp_tot105 = eval(monto * imp105) ;
			imp_tot21 = (tot_comi + tot_tasa ) * imp21;
			//tot_tasa = tot_tasa.toFixed(2);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa);
			            
         }
	}  
	// LOTE 1 AL 21 %	
	if (factura.impuesto[1].checked===true) {
		if (monto.length!=0) {
			if (tasaadm!=0) {
	           	if (monto <= 5000)
                    tot_tasa = 100.0 * (tasaadm / 100.0);
                else if(monto <= 20000)
                        tot_tasa = 500.0 * (tasaadm / 100.0);
                else if (monto <= 40000)
                    tot_tasa =  1200.0 * (tasaadm / 100.0);
                else if (monto <= 80000)
                    tot_tasa = 2400.0 * (tasaadm / 100.0);
                else if (monto <= 100000)
                    tot_tasa =  3600.0 * (tasaadm / 100.0);
                else if (monto <= 150000)
                    tot_tasa = 5000.0 * (tasaadm / 100.0);
                else if (monto <= 200000)
                    tot_tasa =  7000.0 * (tasaadm / 100.0);
                else if (monto <= 300000)
                    tot_tasa = 10000.0 * (tasaadm / 100.0);
                else if (monto <= 400000)
                    tot_tasa = 14000.0 * (tasaadm / 100.0);
                else if (monto <= 500000)
                    tot_tasa = 18000.0 * (tasaadm / 100.0);
                else if (monto <= 600000)
                    tot_tasa = 22000.0 * (tasaadm / 100.0);
                else if (monto <= 700000)
                    tot_tasa = 26000.0 * (tasaadm / 100.0);
                else if (monto <= 800000)
                    tot_tasa = 30000.0 * (tasaadm / 100.0);
                else if (monto <= 1000000)
                    tot_tasa = 36000.0 * (tasaadm / 100.0);
                else if (monto <= 1500000)
                    tot_tasa = 50000.0 * (tasaadm / 100.0);
                else if (monto <= 2000000)
                    tot_tasa = 70000.0 * (tasaadm / 100.0);
                else
                    tot_tasa = 80000.0 * (tasaadm / 100.0);
			}
			else	
				tot_tasa = 0.00;
	    	tot_mon = eval(monto);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi = (comi * monto) / 100;
			//tot_comi = tot_comi.toFixed(2);
			tot_mon21 = eval(monto); 
			imp_tot21 = (tot_mon21 + tot_comi + tot_tasa) * imp21;
			acum_tasa = eval(acum_tasa+('+')+tot_tasa);
			//tot_mon21 = tot_mon21.toFixed(2);
			//tot_tasa = tot_tasa.toFixed(2);
			
         }
	}  
	// LOTE 2 AL 10,5 %		
	if (factura.impuesto1[0].checked===true) { 
		if (monto1.length!=0) {
			if (tasaadm1!=0) {
			   	if (monto1 <= 5000)
                    tot_tasa1 = 100.0 * (tasaadm1 / 100.0);
                else if(monto1 <= 20000)
                    tot_tasa1 = 500.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 40000)
                    tot_tasa1 =  1200.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 80000)
                    tot_tasa1 = 2400.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 100000)
                    tot_tasa1 =  3600.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 150000)
                    tot_tasa1 = 5000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 200000)
                    tot_tasa1 =  7000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 300000)
                    tot_tasa1 = 10000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 400000)
                    tot_tasa1 = 14000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 500000)
                    tot_tasa1 = 18000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 600000)
                    tot_tasa1 = 22000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 700000)
                    tot_tasa1 = 26000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 800000)
                    tot_tasa1 = 30000.0 * (tasaadm1 / 100.0);
                else if (monto <= 1000000)
                    tot_tasa1 = 36000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 1500000)
                    tot_tasa1 = 50000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 2000000)
                    tot_tasa1 = 70000.0 * (tasaadm1 / 100.0);
                else
                    tot_tasa1 = 80000.0 * (tasaadm1 / 100.0);
			}
			else	
				tot_tasa1 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto1);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi1 = (comi1 * monto1) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto1); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi1);
			imp_tot105_1 = eval(monto1 * imp105_1) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_1);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_1 = (tot_comi1 + tot_tasa1 ) * imp21_1;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_1);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa1);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}
	// LOTE 2 AL 21 %		 
	if (factura.impuesto1[1].checked===true) {	 
		if (monto1.length!=0) {
			if (tasaadm1!=0) {
	           	if (monto1 <= 5000)
                    tot_tasa1 = 100.0 * (tasaadm1 / 100.0);
                else if(monto1 <= 20000)
                        tot_tasa1 = 500.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 40000)
                    tot_tasa1 =  1200.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 80000)
                    tot_tasa1 = 2400.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 100000)
                    tot_tasa1 =  3600.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 150000)
                    tot_tasa1 = 5000.0 * (tasaadm1/ 100.0);
                else if (monto1 <= 200000)
                    tot_tasa1 =  7000.0 * (tasaadm1 / 100.0);
                else if (monto <= 300000)
                    tot_tasa1 = 10000.0 * (tasaadm / 100.0);
                else if (monto1 <= 400000)
                    tot_tasa1 = 14000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 500000)
                    tot_tasa1 = 18000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 600000)
                    tot_tasa1 = 22000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 700000)
                    tot_tasa1 = 26000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 800000)
                    tot_tasa1 = 30000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 1000000)
                    tot_tasa1 = 36000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 1500000)
                    tot_tasa1 = 50000.0 * (tasaadm1 / 100.0);
                else if (monto1 <= 2000000)
                    tot_tasa1 = 70000.0 * (tasaadm1 / 100.0);
                else
                    tot_tasa1 = 80000.0 * (tasaadm1 / 100.0);
			}
			else	
				tot_tasa1 = 0.00;
				
	    	tot_mon = eval(tot_mon+('+')+monto1);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi1 = (comi1 * monto1) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto1); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi1);
			var imp_tot21_1 = eval(monto1+('+')+tot_comi1+('+')+tot_tasa1)*imp21_1 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_1;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa1);
			//var strAlerta = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_1" + "\n" + imp_tot21_1 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa1" + "\n" + tot_tasa1 + "tot_comi1" + "\n" + tot_comi1  + "imp21_1" + "\n" + imp21_1 + "monto1" + "\n" + monto1;
			//alert(strAlerta);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
	}	 
	// LOTE 3 AL 10,5 %
	if (factura.impuesto2[0].checked==true) { 
		if (monto2.length!=0) {
			if (tasaadm2!=0) {
			   	if (monto2 <= 5000)
                    tot_tasa2 = 100.0 * (tasaadm2 / 100.0);
                else if(monto2 <= 20000)
                    tot_tasa2 = 500.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 40000)
                    tot_tasa2 =  1200.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 80000)
                    tot_tasa2 = 2400.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 100000)
                    tot_tasa2 =  3600.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 150000)
                    tot_tasa2 = 5000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 200000)
                    tot_tasa2 =  7000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 300000)
                    tot_tasa2 = 10000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 400000)
                    tot_tasa2 = 14000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 500000)
                    tot_tasa2 = 18000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 600000)
                    tot_tasa2 = 22000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 700000)
                    tot_tasa2 = 26000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 800000)
                    tot_tasa2 = 30000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 1000000)
                    tot_tasa2 = 36000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 1500000)
                    tot_tasa2 = 50000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 2000000)
                    tot_tasa2 = 70000.0 * (tasaadm2 / 100.0);
                else
                    tot_tasa2 = 80000.0 * (tasaadm2 / 100.0);
			}
			else	
				tot_tasa2 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto2);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi2 = (comi2 * monto2) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto2); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi2);
			imp_tot105_2 = eval(monto2 * imp105_2) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_2);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_2 = (tot_comi2 + tot_tasa2 ) * imp21_2;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_2);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa2);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}
	// LOTE 3 AL 21 %		 
	if (factura.impuesto2[1].checked===true) {	 
		if (monto2.length!=0) {
			if (tasaadm2!=0) {
	           	if (monto2 <= 5000)
                    tot_tasa2 = 100.0 * (tasaadm2 / 100.0);
                else if(monto2 <= 20000)
                        tot_tasa2 = 500.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 40000)
                    tot_tasa2 =  1200.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 80000)
                    tot_tasa2 = 2400.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 100000)
                    tot_tasa2 =  3600.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 150000)
                    tot_tasa2 = 5000.0 * (tasaadm2/ 100.0);
                else if (monto2 <= 200000)
                    tot_tasa2 =  7000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 300000)
                    tot_tasa2 = 10000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 400000)
                    tot_tasa2 = 14000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 500000)
                    tot_tasa2 = 18000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 600000)
                    tot_tasa2 = 22000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 700000)
                    tot_tasa2 = 26000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 800000)
                    tot_tasa2 = 30000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 1000000)
                    tot_tasa2 = 36000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 1500000)
                    tot_tasa2 = 50000.0 * (tasaadm2 / 100.0);
                else if (monto2 <= 2000000)
                    tot_tasa2 = 70000.0 * (tasaadm2 / 100.0);
                else
                    tot_tasa2 = 80000.0 * (tasaadm2 / 100.0);
			}
			else	
				tot_tasa2 = 0.00;
				
	    	tot_mon = eval(tot_mon+('+')+monto2);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi2 = (comi2 * monto2) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto2); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi2);
			var imp_tot21_2 = eval(monto2+('+')+tot_comi2+('+')+tot_tasa2)*imp21_2 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_2;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa2);
			var strAlerta2 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_2" + "\n" + imp_tot21_2 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa2" + "\n" + tot_tasa2 + "tot_comi2" + "\n" + tot_comi2  + "imp21_2" + "\n" + imp21_2 + "monto2" + "\n" + monto2;
			//alert(strAlerta2);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 
	}
	// LOTE 4 AL 10,5 %	
	if (factura.impuesto3[0].checked==true) { 
		if (monto3.length!=0) {
			if (tasaadm3!=0) {
			   	if (monto3 <= 5000)
                    tot_tasa3 = 100.0 * (tasaadm3 / 100.0);
                else if(monto3 <= 20000)
                    tot_tasa3 = 500.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 40000)
                    tot_tasa3 =  1200.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 80000)
                    tot_tasa3 = 2400.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 100000)
                    tot_tasa3 =  3600.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 150000)
                    tot_tasa3 = 5000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 200000)
                    tot_tasa3 =  7000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 300000)
                    tot_tasa3 = 10000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 400000)
                    tot_tasa3 = 14000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 500000)
                    tot_tasa3 = 18000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 600000)
                    tot_tasa3 = 22000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 700000)
                    tot_tasa3 = 26000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 800000)
                    tot_tasa3 = 30000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 1000000)
                    tot_tasa3 = 36000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 1500000)
                    tot_tasa3 = 50000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 2000000)
                    tot_tasa3 = 70000.0 * (tasaadm3 / 100.0);
                else
                    tot_tasa3 = 80000.0 * (tasaadm3 / 100.0);
			}
			else	
				tot_tasa3 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto3);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi3 = (comi3 * monto3) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto3); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi3);
			imp_tot105_3 = eval(monto3 * imp105_3) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_3);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_3 = (tot_comi3 + tot_tasa3 ) * imp21_3;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_3);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa3);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}

	
	// LOTE 4 AL 21 %
	if (factura.impuesto3[1].checked===true) {	 
		if (monto3.length!=0) {
			if (tasaadm3!=0) {
	           	if (monto3 <= 5000)
                    tot_tasa3 = 100.0 * (tasaadm3 / 100.0);
                else if(monto3 <= 20000)
                        tot_tasa3 = 500.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 40000)
                    tot_tasa3 =  1200.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 80000)
                    tot_tasa3 = 2400.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 100000)
                    tot_tasa3 =  3600.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 150000)
                    tot_tasa3 = 5000.0 * (tasaadm3/ 100.0);
                else if (monto3 <= 200000)
                    tot_tasa3 =  7000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 300000)
                    tot_tasa3 = 10000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 400000)
                    tot_tasa3 = 14000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 500000)
                    tot_tasa3 = 18000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 600000)
                    tot_tasa3 = 22000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 700000)
                    tot_tasa3 = 26000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 800000)
                    tot_tasa3 = 30000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 1000000)
                    tot_tasa3 = 36000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 1500000)
                    tot_tasa3 = 50000.0 * (tasaadm3 / 100.0);
                else if (monto3 <= 2000000)
                    tot_tasa3 = 70000.0 * (tasaadm3 / 100.0);
                else
                    tot_tasa3 = 80000.0 * (tasaadm3 / 100.0);
			}
			else	
				tot_tasa3 = 0.00;
				
	    	tot_mon = eval(tot_mon+('+')+monto3);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi3 = (comi3 * monto3) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto3); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi3);
			var imp_tot21_3 = eval(monto3+('+')+tot_comi3+('+')+tot_tasa3)*imp21_3 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_3;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa3);
			var strAlerta3 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_3" + "\n" + imp_tot21_3 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa3" + "\n" + tot_tasa3 + "tot_comi3" + "\n" + tot_comi3  + "imp21_3" + "\n" + imp21_3 + "monto3" + "\n" + monto3;
			//alert(strAlerta3);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 
	}
// LOTE 5 AL 10,5 %	
	if (factura.impuesto4[0].checked==true) { 
		if (monto4.length!=0) {
			if (tasaadm4!=0) {
			   	if (monto4 <= 5000)
                    tot_tasa4 = 100.0 * (tasaadm4 / 100.0);
                else if(monto4 <= 20000)
                    tot_tasa4 = 500.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 40000)
                    tot_tasa4 =  1200.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 80000)
                    tot_tasa4 = 2400.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 100000)
                    tot_tasa4 =  3600.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 150000)
                    tot_tasa4 = 5000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 200000)
                    tot_tasa4 =  7000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 300000)
                    tot_tasa4 = 10000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 400000)
                    tot_tasa4 = 14000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 500000)
                    tot_tasa4 = 18000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 600000)
                    tot_tasa4 = 22000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 700000)
                    tot_tasa4 = 26000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 800000)
                    tot_tasa4 = 30000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 1000000)
                    tot_tasa4 = 36000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 1500000)
                    tot_tasa4 = 50000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 2000000)
                    tot_tasa4 = 70000.0 * (tasaadm4 / 100.0);
                else
                    tot_tasa4 = 80000.0 * (tasaadm4 / 100.0);
			}
			else	
				tot_tasa4 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto4);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi4 = (comi4 * monto4) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto4); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi4);
			imp_tot105_4 = eval(monto4 * imp105_4) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_4);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_4 = (tot_comi4 + tot_tasa4 ) * imp21_4;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_4);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa4);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}

	
	// LOTE 5 AL 21 %
	if (factura.impuesto4[1].checked===true) {	 
		if (monto4.length!=0) {
			if (tasaadm4!=0) {
	           	if (monto4 <= 5000)
                    tot_tasa4 = 100.0 * (tasaadm4 / 100.0);
                else if(monto4 <= 20000)
                        tot_tasa4 = 500.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 40000)
                    tot_tasa4 =  1200.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 80000)
                    tot_tasa4 = 2400.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 100000)
                    tot_tasa4 =  3600.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 150000)
                    tot_tasa4 = 5000.0 * (tasaadm4/ 100.0);
                else if (monto4 <= 200000)
                    tot_tasa4 =  7000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 300000)
                    tot_tasa4 = 10000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 400000)
                    tot_tasa4 = 14000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 500000)
                    tot_tasa4 = 18000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 600000)
                    tot_tasa4 = 22000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 700000)
                    tot_tasa4 = 26000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 800000)
                    tot_tasa4 = 30000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 1000000)
                    tot_tasa4 = 36000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 1500000)
                    tot_tasa4 = 50000.0 * (tasaadm4 / 100.0);
                else if (monto4 <= 2000000)
                    tot_tasa4 = 70000.0 * (tasaadm4 / 100.0);
                else
                    tot_tasa4 = 80000.0 * (tasaadm4 / 100.0);
			}
			else	
				tot_tasa4 = 0.00;
				
	    	tot_mon = eval(tot_mon+('+')+monto4);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi4 = (comi4 * monto4) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto4); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi4);
			var imp_tot21_4 = eval(monto4+('+')+tot_comi4+('+')+tot_tasa4)*imp21_4 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_4;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa4);
			var strAlerta4 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_3" + "\n" + imp_tot21_4 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa3" + "\n" + tot_tasa4 + "tot_comi3" + "\n" + tot_comi4  + "imp21_3" + "\n" + imp21_4 + "monto3" + "\n" + monto4;
			//alert(strAlerta4);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 
	}
	
	// LOTE 6 AL 10,5 %	
	if (factura.impuesto5[0].checked==true) { 
		if (monto5.length!=0) {
			if (tasaadm5!=0) {
			   	if (monto5 <= 5000)
                    tot_tasa5 = 100.0 * (tasaadm5 / 100.0);
                else if(monto5 <= 20000)
                    tot_tasa5 = 500.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 40000)
                    tot_tasa5 =  1200.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 80000)
                    tot_tasa5 = 2400.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 100000)
                    tot_tasa5 =  3600.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 150000)
                    tot_tasa5 = 5000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 200000)
                    tot_tasa5 =  7000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 300000)
                    tot_tasa5 = 10000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 400000)
                    tot_tasa5 = 14000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 500000)
                    tot_tasa5 = 18000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 600000)
                    tot_tasa5 = 22000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 700000)
                    tot_tasa5 = 26000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 800000)
                    tot_tasa5 = 30000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 1000000)
                    tot_tasa5 = 36000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 1500000)
                    tot_tasa5 = 50000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 2000000)
                    tot_tasa5 = 70000.0 * (tasaadm5 / 100.0);
                else
                    tot_tasa5 = 80000.0 * (tasaadm5 / 100.0);
			}
			else	
				tot_tasa5 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto5);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi5 = (comi5 * monto5) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto5); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi5);
			imp_tot105_5 = eval(monto5 * imp105_5) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_5);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_5 = (tot_comi5 + tot_tasa5 ) * imp21_5;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_5);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa5);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}

	
	// LOTE 6 AL 21 %
	if (factura.impuesto5[1].checked===true) {	 
		if (monto5.length!=0) {
			if (tasaadm5!=0) {
	           	if (monto5 <= 5000)
                    tot_tasa5 = 100.0 * (tasaadm5 / 100.0);
                else if(monto5 <= 20000)
                        tot_tasa5 = 500.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 40000)
                    tot_tasa5 =  1200.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 80000)
                    tot_tasa5 = 2400.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 100000)
                    tot_tasa5 =  3600.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 150000)
                    tot_tasa5 = 5000.0 * (tasaadm5/ 100.0);
                else if (monto5 <= 200000)
                    tot_tasa5 =  7000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 300000)
                    tot_tasa5 = 10000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 400000)
                    tot_tasa5 = 14000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 500000)
                    tot_tasa5 = 18000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 600000)
                    tot_tasa5 = 22000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 700000)
                    tot_tasa5 = 26000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 800000)
                    tot_tasa5 = 30000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 1000000)
                    tot_tasa5 = 36000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 1500000)
                    tot_tasa5 = 50000.0 * (tasaadm5 / 100.0);
                else if (monto5 <= 2000000)
                    tot_tasa5 = 70000.0 * (tasaadm5 / 100.0);
                else
                    tot_tasa5 = 80000.0 * (tasaadm5 / 100.0);
			}
			else	
				tot_tasa5 = 0.00;
				
	    	tot_mon = eval(tot_mon+('+')+monto5);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi5 = (comi5 * monto5) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto5); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi5);
			var imp_tot21_5 = eval(monto5+('+')+tot_comi5+('+')+tot_tasa5)*imp21_5 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_5;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa5);
			var strAlerta5 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_5" + "\n" + imp_tot21_5 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa5" + "\n" + tot_tasa5 + "tot_comi5" + "\n" + tot_comi5  + "imp21_5" + "\n" + imp21_5 + "monto5" + "\n" + monto5;
			//alert(strAlerta5);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 
	}
	
	// LOTE 7 AL 10,5 %	
	if (factura.impuesto6[0].checked==true) { 
		if (monto6.length!=0) {
			if (tasaadm6!=0) {
			   	if (monto6 <= 5000)
                    tot_tasa6 = 100.0 * (tasaadm6 / 100.0);
                else if(monto6 <= 20000)
                    tot_tasa6 = 500.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 40000)
                    tot_tasa6 =  1200.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 80000)
                    tot_tasa6 = 2400.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 100000)
                    tot_tasa6 =  3600.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 150000)
                    tot_tasa6 = 5000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 200000)
                    tot_tasa6 =  7000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 300000)
                    tot_tasa6 = 10000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 400000)
                    tot_tasa6 = 14000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 500000)
                    tot_tasa6 = 18000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 600000)
                    tot_tasa6 = 22000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 700000)
                    tot_tasa6 = 26000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 800000)
                    tot_tasa6 = 30000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 1000000)
                    tot_tasa6 = 36000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 1500000)
                    tot_tasa6 = 50000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 2000000)
                    tot_tasa6 = 70000.0 * (tasaadm6 / 100.0);
                else
                    tot_tasa6 = 80000.0 * (tasaadm6 / 100.0);
			}
			else	
				tot_tasa6 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto6);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi6 = (comi6 * monto6) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto6); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi6);
			imp_tot105_6 = eval(monto6 * imp105_6) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_6);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_6 = (tot_comi6 + tot_tasa6 ) * imp21_6;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_6);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa6);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}

	
	// LOTE 7 AL 21 %
	if (factura.impuesto6[1].checked===true) {	 
		if (monto6.length!=0) {
			if (tasaadm6!=0) {
			   	if (monto6 <= 5000)
                    tot_tasa6 = 100.0 * (tasaadm6 / 100.0);
                else if(monto6 <= 20000)
                    tot_tasa6 = 500.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 40000)
                    tot_tasa6 =  1200.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 80000)
                    tot_tasa6 = 2400.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 100000)
                    tot_tasa6 =  3600.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 150000)
                    tot_tasa6 = 5000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 200000)
                    tot_tasa6 =  7000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 300000)
                    tot_tasa6 = 10000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 400000)
                    tot_tasa6 = 14000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 500000)
                    tot_tasa6 = 18000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 600000)
                    tot_tasa6 = 22000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 700000)
                    tot_tasa6 = 26000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 800000)
                    tot_tasa6 = 30000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 1000000)
                    tot_tasa6 = 36000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 1500000)
                    tot_tasa6 = 50000.0 * (tasaadm6 / 100.0);
                else if (monto6 <= 2000000)
                    tot_tasa6 = 70000.0 * (tasaadm6 / 100.0);
                else
                    tot_tasa6 = 80000.0 * (tasaadm6 / 100.0);
			}
			else	
				tot_tasa6 = 0.00;
				
	    	tot_mon = eval(tot_mon+('+')+monto6);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi6 = (comi6 * monto6) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto6); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi6);
			var imp_tot21_6 = eval(monto6+('+')+tot_comi6+('+')+tot_tasa6)*imp21_6 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_6;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa6);
			var strAlerta6 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_6" + "\n" + imp_tot21_6 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa6" + "\n" + tot_tasa6 + "tot_comi6" + "\n" + tot_comi6  + "imp21_6" + "\n" + imp21_6 + "monto6" + "\n" + monto6;
			//alert(strAlerta6);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 
	}
 
	// LOTE 8 AL 10,5 %	
	if (factura.impuesto7[0].checked==true) { 
		if (monto7.length!=0) {
			if (tasaadm7!=0) {
			   	if (monto7 <= 5000)
                    tot_tasa7 = 100.0 * (tasaadm7 / 100.0);
                else if(monto7 <= 20000)
                    tot_tasa7 = 500.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 40000)
                    tot_tasa7 =  1200.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 80000)
                    tot_tasa7 = 2400.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 100000)
                    tot_tasa7 =  3600.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 150000)
                    tot_tasa7 = 5000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 200000)
                    tot_tasa7 =  7000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 300000)
                    tot_tasa7 = 10000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 400000)
                    tot_tasa7 = 14000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 500000)
                    tot_tasa7 = 18000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 600000)
                    tot_tasa7 = 22000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 700000)
                    tot_tasa7 = 26000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 800000)
                    tot_tasa7 = 30000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 1000000)
                    tot_tasa7 = 36000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 1500000)
                    tot_tasa7 = 50000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 2000000)
                    tot_tasa7 = 70000.0 * (tasaadm7 / 100.0);
                else
                    tot_tasa7 = 80000.0 * (tasaadm7 / 100.0);
			}
			else	
				tot_tasa7 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto7);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi7 = (comi7 * monto7) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto7); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi7);
			imp_tot105_7 = eval(monto7 * imp105_7) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_7);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_7 = (tot_comi7 + tot_tasa7 ) * imp21_7;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_7);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa7);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}

	
	// LOTE 9 AL 21 %
	if (factura.impuesto7[1].checked===true) {	 
		if (monto7.length!=0) {
			if (tasaadm7!=0) {
			   	if (monto7 <= 5000)
                    tot_tasa7 = 100.0 * (tasaadm7 / 100.0);
                else if(monto7 <= 20000)
                    tot_tasa7 = 500.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 40000)
                    tot_tasa7 =  1200.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 80000)
                    tot_tasa7 = 2400.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 100000)
                    tot_tasa7 =  3600.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 150000)
                    tot_tasa7 = 5000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 200000)
                    tot_tasa7 =  7000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 300000)
                    tot_tasa7 = 10000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 400000)
                    tot_tasa7 = 14000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 500000)
                    tot_tasa7 = 18000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 600000)
                    tot_tasa7 = 22000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 700000)
                    tot_tasa7 = 26000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 800000)
                    tot_tasa7 = 30000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 1000000)
                    tot_tasa7 = 36000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 1500000)
                    tot_tasa7 = 50000.0 * (tasaadm7 / 100.0);
                else if (monto7 <= 2000000)
                    tot_tasa7 = 70000.0 * (tasaadm7 / 100.0);
                else
                    tot_tasa7 = 80000.0 * (tasaadm7 / 100.0);
			}
			else	
				tot_tasa7 = 0.00;
				
	    	tot_mon = eval(tot_mon+('+')+monto7);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi7 = (comi7 * monto7) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto7); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi7);
			var imp_tot21_7 = eval(monto7+('+')+tot_comi7+('+')+tot_tasa7)*imp21_7 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_7;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa7);
			var strAlerta7 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_7" + "\n" + imp_tot21_7 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa7" + "\n" + tot_tasa7 + "tot_comi7" + "\n" + tot_comi7  + "imp21_7" + "\n" + imp21_7 + "monto7" + "\n" + monto7;
			//alert(strAlerta7);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 
	}
	
	// LOTE 9 AL 10,5 %	
	if (factura.impuesto8[0].checked==true) { 
		if (monto8.length!=0) {
			if (tasaadm8!=0) {
			   	if (monto8 <= 5000)
                    tot_tasa8 = 100.0 * (tasaadm8 / 100.0);
                else if(monto8 <= 20000)
                    tot_tasa8 = 500.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 40000)
                    tot_tasa8 =  1200.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 80000)
                    tot_tasa8 = 2400.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 100000)
                    tot_tasa8 =  3600.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 150000)
                    tot_tasa8 = 5000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 200000)
                    tot_tasa8 =  7000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 300000)
                    tot_tasa8 = 10000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 400000)
                    tot_tasa8 = 14000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 500000)
                    tot_tasa8 = 18000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 600000)
                    tot_tasa8 = 22000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 700000)
                    tot_tasa8 = 26000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 800000)
                    tot_tasa8 = 30000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 1000000)
                    tot_tasa8 = 36000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 1500000)
                    tot_tasa8 = 50000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 2000000)
                    tot_tasa8 = 70000.0 * (tasaadm8 / 100.0);
                else
                    tot_tasa8 = 80000.0 * (tasaadm8 / 100.0);
			}
			else	
				tot_tasa8 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto8);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi8 = (comi8 * monto8) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto8); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi8);
			imp_tot105_8 = eval(monto8 * imp105_8) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_8);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_8 = (tot_comi8 + tot_tasa8 ) * imp21_8;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_8);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa8);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}

	
	// LOTE 9 AL 21 %
	if (factura.impuesto8[1].checked===true) {	 
		if (monto8.length!=0) {
			if (tasaadm8!=0) {
			   	if (monto8 <= 5000)
                    tot_tasa8 = 100.0 * (tasaadm8 / 100.0);
                else if(monto8 <= 20000)
                    tot_tasa8 = 500.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 40000)
                    tot_tasa8 =  1200.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 80000)
                    tot_tasa8 = 2400.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 100000)
                    tot_tasa8 =  3600.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 150000)
                    tot_tasa8 = 5000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 200000)
                    tot_tasa8 =  7000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 300000)
                    tot_tasa8 = 10000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 400000)
                    tot_tasa8 = 14000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 500000)
                    tot_tasa8 = 18000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 600000)
                    tot_tasa8 = 22000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 700000)
                    tot_tasa8 = 26000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 800000)
                    tot_tasa8 = 30000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 1000000)
                    tot_tasa8 = 36000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 1500000)
                    tot_tasa8 = 50000.0 * (tasaadm8 / 100.0);
                else if (monto8 <= 2000000)
                    tot_tasa8 = 70000.0 * (tasaadm8 / 100.0);
                else
                    tot_tasa8 = 80000.0 * (tasaadm8 / 100.0);
			}
			else	
				tot_tasa8 = 0.00;				
	    	tot_mon = eval(tot_mon+('+')+monto8);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi8 = (comi8 * monto8) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto8); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi8);
			var imp_tot21_8 = eval(monto8+('+')+tot_comi8+('+')+tot_tasa8)*imp21_8 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_8;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa8);
			var strAlerta8 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_8" + "\n" + imp_tot21_8 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa8" + "\n" + tot_tasa8 + "tot_comi8" + "\n" + tot_comi8  + "imp21_8" + "\n" + imp21_8 + "monto8" + "\n" + monto8;
			//alert(strAlerta8);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 	}
	
		// LOTE 10 AL 10,5 %	
	if (factura.impuesto9[0].checked==true) { 
		if (monto9.length!=0) {
			if (tasaadm9!=0) {
			   	if (monto9 <= 5000)
                    tot_tasa9 = 100.0 * (tasaadm9 / 100.0);
                else if(monto9 <= 20000)
                    tot_tasa9 = 500.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 40000)
                    tot_tasa9 =  1200.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 80000)
                    tot_tasa9 = 2400.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 100000)
                    tot_tasa9 =  3600.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 150000)
                    tot_tasa9 = 5000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 200000)
                    tot_tasa9 =  7000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 300000)
                    tot_tasa9 = 10000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 400000)
                    tot_tasa9 = 14000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 500000)
                    tot_tasa9 = 18000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 600000)
                    tot_tasa9 = 22000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 700000)
                    tot_tasa9 = 26000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 800000)
                    tot_tasa9 = 30000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 1000000)
                    tot_tasa9 = 36000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 1500000)
                    tot_tasa9 = 50000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 2000000)
                    tot_tasa9 = 70000.0 * (tasaadm9 / 100.0);
                else
                    tot_tasa9 = 80000.0 * (tasaadm9 / 100.0);
			}
			else	
				tot_tasa9 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto9);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi9 = (comi9 * monto9) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto9); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi9);
			imp_tot105_9 = eval(monto9 * imp105_9) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_9);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_9 = (tot_comi9 + tot_tasa9 ) * imp21_9;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_9);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa9);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}
	
	// LOTE 10 AL 21 %
	if (factura.impuesto9[1].checked===true) {	 
		if (monto9.length!=0) {
			if (tasaadm9!=0) {
			   	if (monto9 <= 5000)
                    tot_tasa9 = 100.0 * (tasaadm9 / 100.0);
                else if(monto9 <= 20000)
                    tot_tasa9 = 500.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 40000)
                    tot_tasa9 =  1200.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 80000)
                    tot_tasa9 = 2400.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 100000)
                    tot_tasa9 =  3600.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 150000)
                    tot_tasa9 = 5000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 200000)
                    tot_tasa9 =  7000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 300000)
                    tot_tasa9 = 10000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 400000)
                    tot_tasa9 = 14000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 500000)
                    tot_tasa9 = 18000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 600000)
                    tot_tasa9 = 22000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 700000)
                    tot_tasa9 = 26000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 800000)
                    tot_tasa9 = 30000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 1000000)
                    tot_tasa9 = 36000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 1500000)
                    tot_tasa9 = 50000.0 * (tasaadm9 / 100.0);
                else if (monto9 <= 2000000)
                    tot_tasa9 = 70000.0 * (tasaadm9 / 100.0);
                else
                    tot_tasa9 = 80000.0 * (tasaadm9 / 100.0);
			}
			else	
				tot_tasa9 = 0.00;
			tot_mon = eval(tot_mon+('+')+monto9);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi9 = (comi9 * monto9) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto9); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi9);
			var imp_tot21_9 = eval(monto9+('+')+tot_comi9+('+')+tot_tasa9)*imp21_9 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_9;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa9);
			var strAlerta9 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_9" + "\n" + imp_tot21_9 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa9" + "\n" + tot_tasa9 + "tot_comi9" + "\n" + tot_comi9  + "imp21_9" + "\n" + imp21_9 + "monto9" + "\n" + monto9;
			//alert(strAlerta9);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 	}	
	// LOTE 11 AL 10,5 %	
	if (factura.impuesto10[0].checked==true) { 
		if (monto10.length!=0) {
			if (tasaadm10!=0) {
			   	if (monto10 <= 5000)
                    tot_tasa10 = 100.0 * (tasaadm10 / 100.0);
                else if(monto10 <= 20000)
                    tot_tasa10 = 500.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 40000)
                    tot_tasa10 =  1200.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 80000)
                    tot_tasa10 = 2400.0 * (tasaadm10/ 100.0);
                else if (monto10 <= 100000)
                    tot_tasa10 =  3600.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 150000)
                    tot_tasa10 = 5000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 200000)
                    tot_tasa10 =  7000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 300000)
                    tot_tasa10 = 10000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 400000)
                    tot_tasa10 = 14000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 500000)
                    tot_tasa10 = 18000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 600000)
                    tot_tasa10 = 22000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 700000)
                    tot_tasa10 = 26000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 800000)
                    tot_tasa10 = 30000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 1000000)
                    tot_tasa10 = 36000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 1500000)
                    tot_tasa10 = 50000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 2000000)
                    tot_tasa10 = 70000.0 * (tasaadm10 / 100.0);
                else
                    tot_tasa10 = 80000.0 * (tasaadm10 / 100.0);
			}
			else	
				tot_tasa10 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto10);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi10 = (comi10 * monto10) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto10); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi10);
			imp_tot105_10 = eval(monto10 * imp105_10) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_10);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_10 = (tot_comi10 + tot_tasa10 ) * imp21_10;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_10);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa10);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}
	
	// LOTE 11 AL 21 %
	if (factura.impuesto10[1].checked===true) {	 
		if (monto10.length!=0) {
			if (tasaadm10!=0) {
			   	if (monto10 <= 5000)
                    tot_tasa10 = 100.0 * (tasaadm10 / 100.0);
                else if(monto10 <= 20000)
                    tot_tasa10 = 500.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 40000)
                    tot_tasa10 =  1200.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 80000)
                    tot_tasa10 = 2400.0 * (tasaadm10/ 100.0);
                else if (monto10 <= 100000)
                    tot_tasa10 =  3600.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 150000)
                    tot_tasa10 = 5000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 200000)
                    tot_tasa10 =  7000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 300000)
                    tot_tasa10 = 10000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 400000)
                    tot_tasa10 = 14000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 500000)
                    tot_tasa10 = 18000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 600000)
                    tot_tasa10 = 22000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 700000)
                    tot_tasa10 = 26000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 800000)
                    tot_tasa10 = 30000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 1000000)
                    tot_tasa10 = 36000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 1500000)
                    tot_tasa10 = 50000.0 * (tasaadm10 / 100.0);
                else if (monto10 <= 2000000)
                    tot_tasa10 = 70000.0 * (tasaadm10 / 100.0);
                else
                    tot_tasa10 = 80000.0 * (tasaadm10 / 100.0);
			}
			else	
				tot_tasa10 = 0.00;
			tot_mon = eval(tot_mon+('+')+monto10);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi10 = (comi10 * monto10) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto10); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi10);
			var imp_tot21_10 = eval(monto10+('+')+tot_comi10+('+')+tot_tasa10)*imp21_10 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_10;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa10);
			var strAlerta10 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_10" + "\n" + imp_tot21_10 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa10" + "\n" + tot_tasa10 + "tot_comi10" + "\n" + tot_comi9  + "imp21_10" + "\n" + imp21_10 + "monto10" + "\n" + monto10;
			//alert(strAlerta10);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 	}	
	
		// LOTE 12 AL 10,5 %	
	if (factura.impuesto11[0].checked==true) { 
		if (monto11.length!=0) {
			if (tasaadm11!=0) {
			   	if (monto11 <= 5000)
                    tot_tasa11 = 100.0 * (tasaadm11 / 100.0);
                else if(monto11 <= 20000)
                    tot_tasa11 = 500.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 40000)
                    tot_tasa11 =  1200.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 80000)
                    tot_tasa11 = 2400.0 * (tasaadm11/ 100.0);
                else if (monto11 <= 100000)
                    tot_tasa11 =  3600.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 150000)
                    tot_tasa11 = 5000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 200000)
                    tot_tasa11 =  7000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 300000)
                    tot_tasa11 = 10000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 400000)
                    tot_tasa11 = 14000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 500000)
                    tot_tasa11 = 18000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 600000)
                    tot_tasa11 = 22000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 700000)
                    tot_tasa11 = 26000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 800000)
                    tot_tasa11 = 30000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 1000000)
                    tot_tasa11 = 36000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 1500000)
                    tot_tasa11 = 50000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 2000000)
                    tot_tasa11 = 70000.0 * (tasaadm11 / 100.0);
                else
                    tot_tasa11 = 80000.0 * (tasaadm11 / 100.0);
			}
			else	
				tot_tasa11 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto11);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi11 = (comi11 * monto11) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto11); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi11);
			imp_tot105_11 = eval(monto11 * imp105_11) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_11);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_11 = (tot_comi11 + tot_tasa11 ) * imp21_11;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_11);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa11);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}
	
	// LOTE 12 AL 21 %
	if (factura.impuesto11[1].checked===true) {	 
		if (monto11.length!=0) {
			if (tasaadm11!=0) {
			   	if (monto11 <= 5000)
                    tot_tasa11 = 100.0 * (tasaadm11 / 100.0);
                else if(monto11 <= 20000)
                    tot_tasa11 = 500.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 40000)
                    tot_tasa11 =  1200.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 80000)
                    tot_tasa11 = 2400.0 * (tasaadm11/ 100.0);
                else if (monto11 <= 100000)
                    tot_tasa11 =  3600.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 150000)
                    tot_tasa11 = 5000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 200000)
                    tot_tasa11 =  7000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 300000)
                    tot_tasa11 = 10000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 400000)
                    tot_tasa11 = 14000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 500000)
                    tot_tasa11 = 18000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 600000)
                    tot_tasa11 = 22000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 700000)
                    tot_tasa11 = 26000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 800000)
                    tot_tasa11 = 30000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 1000000)
                    tot_tasa11 = 36000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 1500000)
                    tot_tasa11 = 50000.0 * (tasaadm11 / 100.0);
                else if (monto11 <= 2000000)
                    tot_tasa11 = 70000.0 * (tasaadm11 / 100.0);
                else
                    tot_tasa11 = 80000.0 * (tasaadm11 / 100.0);
			}
			else	
				tot_tasa11 = 0.00;
			tot_mon = eval(tot_mon+('+')+monto11);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi11 = (comi11 * monto11) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto11); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi11);
			var imp_tot21_11 = eval(monto11+('+')+tot_comi11+('+')+tot_tasa11)*imp21_11 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_11;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa11);
			var strAlerta11 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_11" + "\n" + imp_tot21_11 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa11" + "\n" + tot_tasa11 + "tot_comi11" + "\n" + tot_comi11  + "imp21_11" + "\n" + imp21_11 + "monto11" + "\n" + monto11;
			//alert(strAlerta11);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 	}
	
	// LOTE 13 AL 10,5 %	
	if (factura.impuesto12[0].checked==true) { 
		if (monto12.length!=0) {
			if (tasaadm12!=0) {
			   	if (monto12 <= 5000)
                    tot_tasa12 = 100.0 * (tasaadm12 / 100.0);
                else if(monto11 <= 20000)
                    tot_tasa12 = 500.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 40000)
                    tot_tasa12 =  1200.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 80000)
                    tot_tasa12 = 2400.0 * (tasaadm12/ 100.0);
                else if (monto12 <= 100000)
                    tot_tasa12 =  3600.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 150000)
                    tot_tasa12 = 5000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 200000)
                    tot_tasa12 =  7000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 300000)
                    tot_tasa12 = 10000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 400000)
                    tot_tasa12 = 14000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 500000)
                    tot_tasa12 = 18000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 600000)
                    tot_tasa12 = 22000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 700000)
                    tot_tasa12 = 26000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 800000)
                    tot_tasa12 = 30000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 1000000)
                    tot_tasa12 = 36000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 1500000)
                    tot_tasa12 = 50000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 2000000)
                    tot_tasa12 = 70000.0 * (tasaadm12 / 100.0);
                else
                    tot_tasa12 = 80000.0 * (tasaadm12 / 100.0);
			}
			else	
				tot_tasa12 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto12);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi12 = (comi12 * monto12) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto12); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi12);
			imp_tot105_12 = eval(monto12 * imp105_12) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_12);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_12 = (tot_comi12 + tot_tasa12 ) * imp21_12;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_12);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa12);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}
	
	// LOTE 13 AL 21 %
	if (factura.impuesto12[1].checked===true) {	 
		if (monto12.length!=0) {
			if (tasaadm12!=0) {
			   	if (monto12 <= 5000)
                    tot_tasa12 = 100.0 * (tasaadm12 / 100.0);
                else if(monto12 <= 20000)
                    tot_tasa12 = 500.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 40000)
                    tot_tasa12 =  1200.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 80000)
                    tot_tasa12 = 2400.0 * (tasaadm12/ 100.0);
                else if (monto12 <= 100000)
                    tot_tasa12 =  3600.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 150000)
                    tot_tasa12 = 5000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 200000)
                    tot_tasa12 =  7000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 300000)
                    tot_tasa12 = 10000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 400000)
                    tot_tasa12 = 14000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 500000)
                    tot_tasa12 = 18000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 600000)
                    tot_tasa12 = 22000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 700000)
                    tot_tasa12 = 26000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 800000)
                    tot_tasa12 = 30000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 1000000)
                    tot_tasa12 = 36000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 1500000)
                    tot_tasa12 = 50000.0 * (tasaadm12 / 100.0);
                else if (monto12 <= 2000000)
                    tot_tasa12 = 70000.0 * (tasaadm12 / 100.0);
                else
                    tot_tasa12 = 80000.0 * (tasaadm12 / 100.0);
			}
			else	
				tot_tasa12 = 0.00;
			tot_mon = eval(tot_mon+('+')+monto12);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi12 = (comi12 * monto12) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto12); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi12);
			var imp_tot21_12 = eval(monto12+('+')+tot_comi12+('+')+tot_tasa12)*imp21_12 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_12;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa12);
			var strAlerta12 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_12" + "\n" + imp_tot21_12 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa12" + "\n" + tot_tasa12 + "tot_comi12" + "\n" + tot_comi12  + "imp21_12" + "\n" + imp21_12 + "monto12" + "\n" + monto12;
			//alert(strAlerta12);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 	}				
	// LOTE 14 AL 10,5 %	
	if (factura.impuesto13[0].checked==true) { 
		if (monto13.length!=0) {
			if (tasaadm13!=0) {
			   	if (monto13 <= 5000)
                    tot_tasa13 = 100.0 * (tasaadm13 / 100.0);
                else if(monto13 <= 20000)
                    tot_tasa13 = 500.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 40000)
                    tot_tasa13 =  1200.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 80000)
                    tot_tasa13 = 2400.0 * (tasaadm13/ 100.0);
                else if (monto13 <= 100000)
                    tot_tasa13 =  3600.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 150000)
                    tot_tasa13 = 5000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 200000)
                    tot_tasa13 =  7000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 300000)
                    tot_tasa13 = 10000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 400000)
                    tot_tasa13 = 14000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 500000)
                    tot_tasa13 = 18000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 600000)
                    tot_tasa13 = 22000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 700000)
                    tot_tasa13 = 26000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 800000)
                    tot_tasa13 = 30000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 1000000)
                    tot_tasa13 = 36000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 1500000)
                    tot_tasa13 = 50000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 2000000)
                    tot_tasa13 = 70000.0 * (tasaadm13 / 100.0);
                else
                    tot_tasa13 = 80000.0 * (tasaadm13 / 100.0);
			}
			else	
				tot_tasa13 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto13);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi13 = (comi13 * monto13) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto13); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi13);
			imp_tot105_13 = eval(monto12 * imp105_13) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_13);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_13 = (tot_comi13 + tot_tasa13 ) * imp21_13;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_13);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa13);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}
	
	// LOTE 14 AL 21 %
	if (factura.impuesto13[1].checked===true) {	 
		if (monto13.length!=0) {
			if (tasaadm13!=0) {
			   	if (monto13 <= 5000)
                    tot_tasa13 = 100.0 * (tasaadm13 / 100.0);
                else if(monto13 <= 20000)
                    tot_tasa13 = 500.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 40000)
                    tot_tasa13 =  1200.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 80000)
                    tot_tasa13 = 2400.0 * (tasaadm13/ 100.0);
                else if (monto13 <= 100000)
                    tot_tasa13 =  3600.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 150000)
                    tot_tasa13 = 5000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 200000)
                    tot_tasa13 =  7000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 300000)
                    tot_tasa13 = 10000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 400000)
                    tot_tasa13 = 14000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 500000)
                    tot_tasa13 = 18000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 600000)
                    tot_tasa13 = 22000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 700000)
                    tot_tasa13 = 26000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 800000)
                    tot_tasa13 = 30000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 1000000)
                    tot_tasa13 = 36000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 1500000)
                    tot_tasa13 = 50000.0 * (tasaadm13 / 100.0);
                else if (monto13 <= 2000000)
                    tot_tasa13 = 70000.0 * (tasaadm13 / 100.0);
                else
                    tot_tasa13 = 80000.0 * (tasaadm13 / 100.0);
			}
			else	
				tot_tasa13 = 0.00;
			tot_mon = eval(tot_mon+('+')+monto13);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi13 = (comi13 * monto13) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto13); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi13);
			var imp_tot21_13 = eval(monto13+('+')+tot_comi13+('+')+tot_tasa13)*imp21_13 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_13;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa13);
			var strAlerta13 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_13" + "\n" + imp_tot21_13 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa13" + "\n" + tot_tasa13 + "tot_comi13" + "\n" + tot_comi13  + "imp21_13" + "\n" + imp21_13 + "monto13" + "\n" + monto13;
			//alert(strAlerta13);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 	}	
	// LOTE 15 AL 10,5 %	
	if (factura.impuesto14[0].checked==true) { 
		if (monto14.length!=0) {
			if (tasaadm14!=0) {
			   	if (monto14 <= 5000)
                    tot_tasa14 = 100.0 * (tasaadm14 / 100.0);
                else if(monto14 <= 20000)
                    tot_tasa14 = 500.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 40000)
                    tot_tasa14 =  1200.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 80000)
                    tot_tasa14 = 2400.0 * (tasaadm14/ 100.0);
                else if (monto14 <= 100000)
                    tot_tasa14 =  3600.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 150000)
                    tot_tasa14 = 5000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 200000)
                    tot_tasa14 =  7000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 300000)
                    tot_tasa14 = 10000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 400000)
                    tot_tasa14 = 14000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 500000)
                    tot_tasa14 = 18000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 600000)
                    tot_tasa14 = 22000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 700000)
                    tot_tasa14 = 26000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 800000)
                    tot_tasa14 = 30000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 1000000)
                    tot_tasa14 = 36000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 1500000)
                    tot_tasa14 = 50000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 2000000)
                    tot_tasa14 = 70000.0 * (tasaadm14 / 100.0);
                else
                    tot_tasa14 = 80000.0 * (tasaadm14 / 100.0);
			}
			else	
				tot_tasa14 = 0.00;
			
	    	tot_mon = eval(tot_mon + ('+') + monto14);
	        //tot_mon = tot_mon.toFixed(2);
			tot_comi14 = (comi14 * monto14) / 100;
			tot_mon105 = eval(tot_mon105 + ('+') + monto14); 
			//tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi14);
			imp_tot105_14 = eval(monto14 * imp105_14) ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_14);
			//imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21_14 = (tot_comi14 + tot_tasa14 ) * imp21_14;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_14);
			acum_tasa = eval(acum_tasa+('+')+tot_tasa14);
			//tot_tasa1 = tot_tasa1.toFixed(2);
		} 
	}
	
	// LOTE 15 AL 21 %
	if (factura.impuesto14[1].checked===true) {	 
		if (monto14.length!=0) {
			if (tasaadm14!=0) {
			   	if (monto14 <= 5000)
                    tot_tasa14 = 100.0 * (tasaadm14 / 100.0);
                else if(monto14 <= 20000)
                    tot_tasa14 = 500.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 40000)
                    tot_tasa14 =  1200.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 80000)
                    tot_tasa14 = 2400.0 * (tasaadm14/ 100.0);
                else if (monto14 <= 100000)
                    tot_tasa14 =  3600.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 150000)
                    tot_tasa14 = 5000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 200000)
                    tot_tasa14 =  7000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 300000)
                    tot_tasa14 = 10000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 400000)
                    tot_tasa14 = 14000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 500000)
                    tot_tasa14 = 18000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 600000)
                    tot_tasa14 = 22000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 700000)
                    tot_tasa14 = 26000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 800000)
                    tot_tasa14 = 30000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 1000000)
                    tot_tasa14 = 36000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 1500000)
                    tot_tasa14 = 50000.0 * (tasaadm14 / 100.0);
                else if (monto14 <= 2000000)
                    tot_tasa14 = 70000.0 * (tasaadm14 / 100.0);
                else
                    tot_tasa14 = 80000.0 * (tasaadm14 / 100.0);
			}
			else	
				tot_tasa14 = 0.00;
			tot_mon = eval(tot_mon+('+')+monto14);
	        //tot_mon = tot_mon.toFixed(2);
			var tot_comi14 = (comi14 * monto14) / 100;
			tot_mon21 = eval(tot_mon21 + ('+') + monto14); 
			//tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi + ('+') + tot_comi14);
			var imp_tot21_14 = eval(monto14+('+')+tot_comi14+('+')+tot_tasa14)*imp21_14 ;
			//var imp_tot21_1 = (monto1 + tot_comi1 + tot_tasa1) * imp21_1;
			imp_tot21 = imp_tot21 + imp_tot21_14;
			acum_tasa = eval(acum_tasa + ('+') + tot_tasa14);
			//var strAlerta14 = "tot_mon " + tot_mon + "\n" +  "tot_mon21 " + tot_mon21 + "\n" + "tot_comi " + tot_comi + "\n"  + "\n" + "imp_tot21" + "\n" + imp_tot21 +  "imp_tot21_14" + "\n" + imp_tot21_14 + "acum_tasa" + "\n" + acum_tasa + "tot_tasa14" + "\n" + tot_tasa14 + "tot_comi14" + "\n" + tot_comi143  + "imp21_14" + "\n" + imp21_14 + "monto14" + "\n" + monto14;
			//alert(strAlerta14);
			//tot_tasa1 = tot_tasa1.toFixed(2);
	   	} 
 	}		
	tot_comi=tot_comi.toFixed(2);
	tot_neto = eval(tot_mon+('+')+tot_comi+('+')+imp_tot21+('+')+imp_tot105+('+')+acum_tasa);
	tot_neto = tot_neto.toFixed(2);
	
	imp_tot105 = imp_tot105.toFixed(2);
	imp_tot21 = imp_tot21.toFixed(2);
	var serie = factura.serie.value;
	if (serie==11) {
		// Oculto
		factura.totiva105.value = imp_tot105;
		factura.totiva21.value = imp_tot21;
		factura.totcomis.value = tot_comi ;
		factura.tot_general.value = tot_neto;
		factura.totneto105.value = tot_mon105;
		factura.totneto21.value = tot_mon21;
		// Visible
		factura.totneto105_1.value = eval(tot_mon105+('+')+imp_tot105);
		tot_mon21 = tot_mon21*1.21
		totcomis = tot_comi*1.21
		factura.totneto21_1.value = tot_mon21.toFixed(2);
		factura.totcomis_1.value = totcomis.toFixed(2);
		factura.tot_general_1.value = tot_neto;
					   
	} else {
		// Oculto   
		factura.totiva105.value = imp_tot105;
		factura.totiva21.value = imp_tot21;
		factura.totcomis.value = tot_comi ;
		factura.tot_general.value = tot_neto;
		factura.totneto105.value = tot_mon105;
		factura.totneto21.value = tot_mon21;
		factura.totimp.value = acum_tasa;
		// Visible
		factura.totiva105_1.value = imp_tot105;
		factura.totiva21_1.value = imp_tot21;
		factura.totcomis_1.value = tot_comi ;
		factura.tot_general_1.value = tot_neto;
		factura.totneto105_1.value = tot_mon105;
		factura.totneto21_1.value = tot_mon21;
		factura.totimp_1.value = acum_tasa;
	}
}
</script>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<script type="text/javascript" src="../js/ajax.js"></script>    
<script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script> 
<script language="javascript">
	
	function neto(form)
	{ 
		importe = factura.importes.value; 
   		document.write(importe);
	}

	function MM_findObj(n, d) { //v4.01
		//alert("en MM_findObj    ");
  		var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    	d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  		if(!x && d.getElementById) x=d.getElementById(n); return x;
	}

	function MM_validateForm() { //v4.0
		//alert("en MM_validateForm    ");
  		var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  		for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    	if (val) { nm=val.name; if ((val=val.value)!="") {
      	if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      	} else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='El importe debe contener un nÃºmero.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
        min=test.substring(8,p); max=test.substring(p+1);
        if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    	} } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  		} if (errors) alert('ERROR \n'+errors);
  		document.MM_returnValue = (errors == '');
	}
</script>

<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />
</head>
<body>
<form id="factura" name="factura" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="640" border="1" align="left" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
    <tr>
      <td colspan="3" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_aut_lotes.gif" width="358" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="3" valign="top" bgcolor="#82BADD"><table width="100%" border="1" cellspacing="1" cellpadding="1">
        <tr>
      <td width="14%" height="20" bgcolor="#82BADD">&nbsp;<span class="ewTableHeader">Tipo de Cbte </span></td>
          <td width="40%"><select name="tipos" onChange="agregarOpciones(this.form)">
                                      <option value="53">FACTURA POR LOTES B0004</option>
                                     
                           </select>
		  <input name="tcomp" id="tcomp"  type="hidden" value="53"/>
		         </td>
          <td width="1%">&nbsp;</td>
          <td width="12%" class="ewTableHeader">&nbsp;Serie</td>
          <td width="33%"><input name="serie_texto" type="text"  size="25" value="SERIE DE FACTURA B0004"/>
		  <input name="serie" type="hidden"  size="25" value="30"/>
            </td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Nro Factura </td>
          <td><input name="num_factura" type="num_factura" class="phpmakerlist" id="ncomp"  width="25" /></td>
          <td>&nbsp;</td>
          <td class="ewTableHeader">&nbsp;Fecha fact </td>
		  <input name="fecha_factura" type="hidden" id="fecha_factura"  />
          <td><input name="fecha_factura1" type="text" id="fecha_factura1" size="25"  value= <?php echo $fecha_hoy; ?> />
         <a href="javascript:showCal('Calendar4')"><img src="calendar/img.gif" width="22" height="14"  border="0"/></a></td>
        </tr>
        
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Nro Remate </td>
			<td><input name="remate_num" type="text" id="remate_num" size="25"  value= <?php echo $codremate; ?> />
          
          <td>&nbsp;</td>
          <td colspan="2" rowspan="4" valign="top" bgcolor="#82BADD" ></td>
          </tr>
        <tr>
          <td height="9" class="ewTableHeader">Lugar del Remate </td>
          <td><input name="lugar_remate" type="text" id="lugar_remate" /></td>
          <td>&nbsp;</td>
        </tr>
		<tr>
         <td height="20" class="ewTableHeader">Fecha del Remate</td>
          <td><input name="fecha_remate" type="text" size="12" /></td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td height="10" class="ewTableHeader"> Cliente </td>
          <td><select name="codnum" id="codnum"  required="required">
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
          <td>&nbsp;</td>
     </tr>
     </table></td>
    </tr>
    <tr>
      <td colspan="3"  background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
	  
	   
	  <table width="100%" border="1" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
        <tr>
          <td width="73" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg" > <div align="center">LOTE</div></td>
          <td width="308" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">DESCRIPCION</div></td>
          <td width="86" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">COM</div></td>
          <td width="58" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">% TASA</div></td>
			<td width="68" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">IMPORTE</div></td>
		 <td height="24" colspan="3" class="ewTableHeader" background="images/fonod_lote.jpg"><div align="center">IMPUESTOS</div></td>	  
        </tr>
        <tr>
          <td width="59" height="15" class="ewTableHeader"> <div align="center"><?php echo $iva_15_porcen     ?></div></td>
          <td class="ewTableHeader"><div align="center"><?php echo $iva_21_porcen     ?></div></td>
        </tr>
        
		<tr>
          <td size="5" bgcolor="#82BADD"><select name="lote" id="lote" required="required" onChange="getprov(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="45" />  </td>
        
		<td bgcolor="#82BADD"><input name="comision" type="text" id="comision" size="5" /></td> 
		
		
		<td bgcolor="#82BADD"><input name="tasa" type="text" id="tasa" size="5" /></td> 
		  		
         <td bgcolor="#82BADD"><input name="importe" type="text" id="importe"  required="required" onBlur= "MM_validateForm('importe','','NisNum');return document.MM_returnValue" size="10"   />
		</td>
          
		<td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" />
		</td>
		 
		<td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)"/>
		</td>
		<input name="secuencia0" type="hidden" class="phpmaker" id="secuencia0" size="5" />
        </tr>
		<tr>
          <td width="73" bgcolor="#82BADD"><select name="lote1" id="lote1" onChange="getprov1(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo "  "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);
        
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="45" /></td>
          <td bgcolor="#82BADD"><input name="comision1" type="text" id="comision1" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa1" type="text" id="tasa1" size="5" /></td> 
    		<td bgcolor="#82BADD"><input name="importe1" type="text" id="importe1" onBlur="MM_validateForm('importe1','','NisNum');return document.MM_returnValue" size="10"  /></td>
	<td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto1" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)"/></td>
	<td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto1" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)" /></td>
    
      <input name="secuencia1" type="hidden" class="phpmaker" id="secuencia1" size="65" />
	    </tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote2" id="lote2" onChange="getprov2(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);
        
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="45" /></td>
          <td bgcolor="#82BADD"><input name="comision2" type="text" id="comision2" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa2" type="text" id="tasa2" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe2" type="text" id="importe2" onBlur="MM_validateForm('importe2','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto2" value="<?php  echo $iva_15_porcen     ?>" onClick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto2" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia2" type="hidden" class="phpmaker" id="secuencia2" size="65" /> 
		</tr>
		  <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote3" id="lote3" onChange="getprov3(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision3" type="text" id="comision3" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa3" type="text" id="tasa3" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe3" type="text" id="importe3" onBlur="MM_validateForm('importe3','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto3" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto3" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
           <input name="secuencia3" type="hidden" class="phpmaker" id="secuencia3" size="65" />
		</tr>
		  <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote4" id="lote4" onChange="getprov4(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="45" /></td>
            <td bgcolor="#82BADD"><input name="comision4" type="text" id="comision4" size="5" /></td> 
			   <td bgcolor="#82BADD"><input name="tasa4" type="text" id="tasa4" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe4" type="text" id="importe4" onBlur="MM_validateForm('importe4','','NisNum');return document.MM_returnValue" size="10" /></td>
		   <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto4" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto4" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
          <input name="secuencia4" type="hidden" class="phpmaker" id="secuencia4" size="65" />
		</tr>
		  <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote5" id="lote5" onChange="getprov5(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision5" type="text" id="comision5" size="5" /></td> 
			 <td bgcolor="#82BADD"><input name="tasa5" type="text" id="tasa5" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe5" type="text" id="importe5" onBlur="MM_validateForm('importe5','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto5" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto5" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
          <input name="secuencia5" type="hidden" class="phpmaker" id="secuencia5" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote6" id="lote6" onChange="getprov6(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision6" type="text" id="comision6" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa6" type="text" id="tasan6" size="5" /></td> 
		   <td bgcolor="#82BADD"><input name="importe6" type="text" id="importe6" onBlur="MM_validateForm('importe6','','NisNum');return document.MM_returnValue" size="10" /></td>
           <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto6" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto6" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia6" type="hidden" class="phpmaker" id="secuencia6" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote7" id="lote7" onChange="getprov7(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision7" type="text" id="comision7" size="5" /></td> 
			 <td bgcolor="#82BADD"><input name="tasa7" type="text" id="tasa7" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe7" type="text" id="importe7" onBlur="MM_validateForm('importe7','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto7" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto7" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia7" type="hidden" class="phpmaker" id="secuencia7" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote8" id="lote8" onChange="getprov8(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="45" /></td>
          <td bgcolor="#82BADD"><input name="comision8" type="text" id="comision8" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa8" type="text" id="tasa8" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe8" type="text" id="importe8" onBlur="MM_validateForm('importe8','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto8" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto8" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia8" type="hidden" class="phpmaker" id="secuencia8" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote9" id="lote9" onChange="getprov9(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>

          <td bgcolor="#82BADD"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision9" type="text" id="comision9" size="5" /></td> 
			 <td bgcolor="#82BADD"><input name="tasa9" type="text" id="tasa9" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe9" type="text" id="importe9" onBlur="MM_validateForm('importe9','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto9" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto9" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia9" type="hidden" class="phpmaker" id="secuencia9" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote10" id="lote10" onChange="getprov10(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="45" /></td>
         <td bgcolor="#82BADD"><input name="comision10" type="text" id="comision10" size="5" /></td> 
			<td bgcolor="#82BADD"><input name="tasa10" type="text" id="tasa10" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe10" type="text" id="importe10" onBlur="MM_validateForm('importe10','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto10" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto10" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
          <input name="secuencia10" type="hidden" class="phpmaker" id="secuencia10" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote11" id="lote11" onChange="getprov11(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision11" type="text" id="comision11" size="5" /></td> 
			 <td bgcolor="#82BADD"><input name="tasa11" type="text" id="tasa11" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe11" type="text" id="importe11" onBlur="MM_validateForm('importe11','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto11" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto11" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
           <input name="secuencia11" type="hidden" class="phpmaker" id="secuencia11" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote12" id="lote12" onChange="getprov12(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision12" type="text" id="comision12" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa12" type="text" id="tasa12" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe12" type="text" id="importe12" onBlur="MM_validateForm('importe12','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto12" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto12" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
           <input name="secuencia12" type="hidden" class="phpmaker" id="secuencia12" size="65" />
		</tr>
		 <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote13" id="lote13" onChange="getprov13(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="45" /></td>
           <td bgcolor="#82BADD"><input name="comision13" type="text" id="comision13" size="5" /></td> 
			  <td bgcolor="#82BADD"><input name="tasa13" type="text" id="tasa13" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe13" type="text" id="importe13" onBlur="MM_validateForm('importe13','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto13" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto13" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
                     <input name="secuencia13" type="hidden" class="phpmaker" id="secuencia13" size="65" />
		</tr> <tr>
          <td width="73" bgcolor="#82BADD"><select name="lote14" id="lote14" onChange="getprov14(this.form)">
		  <option value="">[Lote]</option>
            <?php
      do {  
      ?>
            <option value="<?php if (isset($row_lotes_rem['codintlote'])) echo $row_lotes_rem['codintlote'];?>"><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['codintlote']; ?><?php echo " "?><?php if (isset($row_lotes_rem['codintlote']))  echo substr(utf8_encode($row_lotes_rem['descor']),0,49); ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['comiscobr']; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo 100; ?><?php echo " | "?><?php if (isset($row_lotes_rem['codintlote']))  echo $row_lotes_rem['preciobase']; ?></option>
            <?php
      } while ($row_lotes_rem = mysqli_fetch_assoc($lotes_rem));
        $rows = mysqli_num_rows($lotes_rem);
        if($rows > 0) {
            mysqli_data_seek($lotes_rem, 0);
            $row_lotes_rem = mysqli_fetch_assoc($lotes_rem);?>
        <?php
        }
      ?>
          </select></td>
          <td bgcolor="#82BADD"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="45" /></td>
        <td bgcolor="#82BADD"><input name="comision14" type="text" id="comision14" size="5" /></td>
		 <td bgcolor="#82BADD"><input name="tasa14" type="text" id="tasa14" size="5" /></td> 
          <td bgcolor="#82BADD"><input name="importe14" type="text" id="importe14" onBlur="MM_validateForm('importe14','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#82BADD" width="59" align="center"><input type="radio" name="impuesto14" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="31" align="center"><input type="radio" name="impuesto14" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia14" type="hidden" class="phpmaker" id="secuencia14" size="65" />
		</tr>
      </table>      </td>
    </tr>
    <tr bgcolor="#82BADD"><td width="413" bgcolor="#82BADD"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#82BADD"><tr>
      <td width="71" height="20" valign="top" class="ewTableHeader">&nbsp;Leyenda</td>
      <td width="293"  valign="top"><textarea name="leyenda" cols="55" rows="4"></textarea></td>
    </tr>
	<tr><td class="ewTableHeader">&nbsp;Imprimir </td><td><input type="checkbox" name="imprime" value="1" />
</td>
	</tr>
	 </table></td>
      <td width="426" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
        <tr>
          <td width="48%">&nbsp;<span class="ewTableHeader">                    </span></td>
          <td width="52%"></td>
        </tr>
        <tr>
          
        </tr>
      </table></tr>
  
  
    <tr>
      <td colspan="3" bgcolor="#82BADD"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
        <tr>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php  echo $iva_15_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php  echo $iva_21_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Comision </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php  echo $iva_15_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php  echo $iva_21_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Tasa adm</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Total </div></td>
        </tr>
        
        <tr>
          <td><input name="totneto105"  type="hidden"  size="12" /></td>
          <td><input name="totneto21"   type="hidden"  size="12" /></td>
          <td><input name="totcomis"    type="hidden"  size="12" /></td>
          <td><input name="totiva105"   type="hidden"  size="12" /></td>
          <td><input name="totiva21"    type="hidden"  size="12" /></td>
          <td><input name="totimp"      type="hidden"  size="10" /></td>
          <td><input name="tot_general" type="hidden"  size="15" /></td>
        </tr>
		 <tr>
          <td><input name="totneto105_1"  type="text"  size="12" /></td>
          <td><input name="totneto21_1"   type="text"  size="12" /></td>
          <td><input name="totcomis_1"    type="text"  size="12" /></td>
          <td><input name="totiva105_1"   type="text"  size="12" /></td>
          <td><input name="totiva21_1"    type="text"  size="12" /></td>
          <td><input name="totimp_1"      type="text"  size="10" /></td>
          <td><input name="tot_general_1" type="text"  size="15" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#82BADD">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#82BADD"><table width="100%" border="1" cellspacing="1" cellpadding="1">
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
  </table>
    <td><input type="hidden" name="codusu" id="codusu" size="12" value="<?php echo $cod_usuario ?>"/></td>
    <input type="hidden" name="MM_insert" value="factura">
</form>
 <script type="text/javascript"> 

chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('tcomp','serie','includes/getserxtc.php'); 
//chainedSelects.addChain('ncomp','datos','includes/getremate.php'); 

chainedSelects.init(); 
</script>
</body>
</html>