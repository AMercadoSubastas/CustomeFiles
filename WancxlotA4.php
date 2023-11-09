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
    $CbteTipo = 3; // N Cred A - Ver - AfipWsfev1::FEParamGetTiposCbte()
    $PtoVta = 4;

    //Requerimiento
    $Concepto = 3; //Productos y Servicios
    $DocTipo = 80; //CUIT
    
	
	mysqli_select_db($amercado, $database_amercado);
	$query_cliente2 = sprintf("SELECT * FROM entidades WHERE codnum = %s",GetSQLValueString($_POST['codnum'],"int"));
	$cliente2 = mysqli_query($amercado, $query_cliente2) or die(mysqli_error($amercado));
	$row_cliente2 = mysqli_fetch_assoc($cliente2);
	
	
    $cuit_enti = $row_cliente2['cuit'];
	if (isset($cuit_enti)) {
		$cuit_enti2 = str_replace("-","",$cuit_enti);
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
    $ImpNeto      = $_POST['totneto21'] + $_POST['totneto105'] + $_POST['totcomis'];//GetSQLValueString($_POST['totneto21'], "double") + GetSQLValueString($_POST['totneto105'], "double"); //100.00;
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

    //Informacion para agregar al array IVA
	if (isset($_POST['totneto105']) && $_POST['totneto105'] != 0.00) {
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
	    $IvaAlicuotaId = 5; // 21% Ver - AfipWsfev1::FEParamGetTiposIva()
	    $IvaAlicuotaBaseImp = $_POST['totneto21'] + $_POST['totcomis'];// 100.00;
    	$IvaAlicuotaImporte = $_POST['totiva21'] ;//21.00;   
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
    $empresaCuit  = '20233616126';
    //El alias debe estar mencionado en el nombre de los archivos de certificados y firmas digitales
    $empresaAlias = 'ldb'; // 'AMERCADO1';


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
}

if ($sigo_y_grabo == 1 && $todo_ok ==1) {
	//echo "CAE2 = ".$CAE." CAEFchVto2 = ".$CAEFchVto." Resultado2 = ".$Resultado."   -  ";
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
		
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia0'], "int"),
                       GetSQLValueString($_POST['descripcion'], "text"),
                       GetSQLValueString($_POST['importe'], "double"),
					   GetSQLValueString($_POST['impuesto'], "double"),
                       GetSQLValueString($_POST['comision'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 1;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia0'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote1']) && GetSQLValueString($_POST['lote1'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia1'], "int"),
                       GetSQLValueString($_POST['descripcion1'], "text"),
                       GetSQLValueString($_POST['importe1'], "double"),
					   GetSQLValueString($_POST['impuesto1'], "double"),
                       GetSQLValueString($_POST['comision1'], "double"));


  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 2;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe1'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia1'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote2']) && GetSQLValueString($_POST['lote2'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia2'], "int"),
                       GetSQLValueString($_POST['descripcion2'], "text"),
                       GetSQLValueString($_POST['importe2'], "double"),
					   GetSQLValueString($_POST['impuesto2'], "double"),
                       GetSQLValueString($_POST['comision2'], "double"));


  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 3;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
  			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe2'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia2'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote3']) && GetSQLValueString($_POST['lote3'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia3'], "int"),
                       GetSQLValueString($_POST['descripcion3'], "text"),
                       GetSQLValueString($_POST['importe3'], "double"),
					   GetSQLValueString($_POST['impuesto3'], "double"),
                       GetSQLValueString($_POST['comision3'], "double"));


  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 4;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe3'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia3'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote4']) && GetSQLValueString($_POST['lote4'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia4'], "int"),
                       GetSQLValueString($_POST['descripcion4'], "text"),
					   GetSQLValueString($_POST['importe4'], "double"),
					   GetSQLValueString($_POST['impuesto4'], "double"),
                       GetSQLValueString($_POST['comision4'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 5;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe4'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia4'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote5']) && GetSQLValueString($_POST['lote5'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia5'], "int"),
                       GetSQLValueString($_POST['descripcion5'], "text"),
                       GetSQLValueString($_POST['importe5'], "double"),
					   GetSQLValueString($_POST['impuesto5'], "double"),
                       GetSQLValueString($_POST['comision5'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 6;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe5'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia5'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote6']) && GetSQLValueString($_POST['lote6'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia6'], "int"),
                       GetSQLValueString($_POST['descripcion6'], "text"),
                       GetSQLValueString($_POST['importe6'], "double"),
					   GetSQLValueString($_POST['impuesto6'], "double"),
                       GetSQLValueString($_POST['comision6'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 7;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe6'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia6'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote7']) && GetSQLValueString($_POST['lote7'], "int")!="NULL") {

		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia7'], "int"),
                       GetSQLValueString($_POST['descripcion7'], "text"),
                       GetSQLValueString($_POST['importe7'], "double"),
					   GetSQLValueString($_POST['impuesto7'], "double"),
                       GetSQLValueString($_POST['comision7'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 8;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe7'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia7'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote8']) && GetSQLValueString($_POST['lote8'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia8'], "int"),
                       GetSQLValueString($_POST['descripcion8'], "text"),
                       GetSQLValueString($_POST['importe8'], "double"),
					   GetSQLValueString($_POST['impuesto8'], "double"),
                       GetSQLValueString($_POST['comision8'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 9;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe8'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia8'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote9']) && GetSQLValueString($_POST['lote9'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia9'], "int"),
                       GetSQLValueString($_POST['descripcion9'], "text"),
                       GetSQLValueString($_POST['importe9'], "double"),
					   GetSQLValueString($_POST['impuesto9'], "double"),
                       GetSQLValueString($_POST['comision9'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 10;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe9'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia9'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote10']) && GetSQLValueString($_POST['lote10'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia10'], "int"),
                       GetSQLValueString($_POST['descripcion10'], "text"),
                       GetSQLValueString($_POST['importe10'], "double"),
					   GetSQLValueString($_POST['impuesto10'], "double"),
                       GetSQLValueString($_POST['comision10'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 11;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe10'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia10'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote11']) && GetSQLValueString($_POST['lote11'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia11'], "int"),
                       GetSQLValueString($_POST['descripcion11'], "text"),
                       GetSQLValueString($_POST['importe11'], "double"),
					   GetSQLValueString($_POST['impuesto11'], "double"),
                       GetSQLValueString($_POST['comision11'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 12;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe11'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia11'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote12']) && GetSQLValueString($_POST['lote12'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia12'], "int"),
                       GetSQLValueString($_POST['descripcion12'], "text"),
                       GetSQLValueString($_POST['importe12'], "double"),
					   GetSQLValueString($_POST['impuesto12'], "double"),
                       GetSQLValueString($_POST['comision12'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 13;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe12'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia12'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote13']) && GetSQLValueString($_POST['lote13'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia13'], "int"),
                       GetSQLValueString($_POST['descripcion13'], "text"),
                       GetSQLValueString($_POST['importe13'], "double"),
					   GetSQLValueString($_POST['impuesto13'], "double"),
                       GetSQLValueString($_POST['comision13'], "double"));

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 14;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe13'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia13'], "int"));
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
		}
	}
	if (isset($_POST['lote14']) && GetSQLValueString($_POST['lote14'], "int")!="NULL") {
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
  			$insertSQL = sprintf("INSERT INTO detfac (tcomp, serie, ncomp, codrem, codlote, descrip, neto, porciva, comcob) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
                       GetSQLValueString($_POST['remate_num'], "int"),
                       GetSQLValueString($_POST['secuencia14'], "int"),
                       GetSQLValueString($_POST['descripcion14'], "text"),
                       GetSQLValueString($_POST['importe14'], "double"),
					   GetSQLValueString($_POST['impuesto14'], "double"),
                       GetSQLValueString($_POST['comision14'], "double"));
					   

  			mysqli_select_db($amercado, $database_amercado);
  			$renglones = 15;
  			$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
   			$updateSQL = sprintf("UPDATE lotes SET estado = %s, preciofinal =  %s WHERE codrem = %s AND secuencia = %s",
  				GetSQLValueString("1", "int"),
				GetSQLValueString($_POST['importe14'], "double"),
				GetSQLValueString($_POST['remate_num'], "int"),
				GetSQLValueString($_POST['secuencia14'], "int"));
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
  		$query_mascara = "SELECT * FROM series  WHERE series.tipcomp= '57' AND series.codnum='29'";//'$tcomp'  AND series.codnum='$serie'";
  		$mascara = mysqli_query($amercado, $query_mascara) or die(mysqli_error($amercado));
  		$row_mascara = mysqli_fetch_assoc($mascara);
  		$totalRows_mascara = mysqli_num_rows($mascara);
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
    
		if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "factura")) {
			//echo "CAE3 = ".$CAE." CAEFchVto3 = ".$CAEFchVto." Resultado3 = ".$Resultado."   -  ";
			$fecha_factura1 = $_POST['fecha_factura1'] ;
			$fecha_factura1 = substr($fecha_factura1,6,4)."-".substr($fecha_factura1,3,2)."-".substr($fecha_factura1,0,2);
			$en_liquid = 0;
  			$insertSQL = sprintf("INSERT INTO cabfac (tcomp, serie, ncomp, fecval, fecdoc, fecreg, cliente, fecvenc, estado, emitido, codrem, totbruto, totiva105, totiva21, totimp, totcomis, totneto105, totneto21, nrengs, nrodoc , tieneresol, en_liquid, CAE, CAEFchVto, Resultado) VALUES (%s, %s, %s, '$fecha_factura1','$fecha_factura1', '$fecha_factura1', %s, '$fecha_factura1', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,'$en_liquid', '$CAE', '$CAEFchVto', '$Resultado')",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
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
					   GetSQLValueString(isset($_POST['tieneresol']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($_POST['GrupoOpciones2'], "int"));

			// Medios de PAgo  en Efectivo

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
  					GetSQLValueString("2", "int"),
					GetSQLValueString($_POST['tot_general'], "double"),
					GetSQLValueString($_POST['remate_num'], "int"));
  				mysqli_select_db($amercado, $database_amercado);
  				$Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
 			}
		}
		// DESDE ACA =======================================================================================
		// Inserto la leyenda
		if (GetSQLValueString($_POST['leyenda'], "text")!="NULL") {

 			$insertSQLfactley = sprintf("INSERT INTO factley (tcomp, serie, ncomp, leyendafc, codrem) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['tcomp'], "int"),
                       GetSQLValueString($_POST['serie'], "int"),
                       $num_fac,
					   GetSQLValueString($_POST['leyenda'], "text"),
					   GetSQLValueString($_POST['remate_num'], "int"));
                       //GetSQLValueString($_POST['leyenda'], "int"));
    
  			mysqli_select_db($amercado, $database_amercado);
  			$Result1 = mysqli_query($amercado, $insertSQLfactley) or die(mysqli_error($amercado));
		}
		// HASTA ACA ===========================================================================================
//echo "   6  - ";
		if (!empty($_POST['imprime'])) { 
			$facnum = $num_fac;
			$tipcomp = GetSQLValueString($_POST['tcomp'], "int");
			$numserie = GetSQLValueString($_POST['serie'], "int");
			$insertGoTo = "rp_facncA.php?ftcomp=$tipcomp&&fserie=$numserie&&fncomp=$facnum";
  			if (isset($_SERVER['QUERY_STRING'])) {
    			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    			$insertGoTo .= $_SERVER['QUERY_STRING'];
  			}
  			header(sprintf("Location: %s", "rp_facncA.php?ftcomp=$tipcomp&&fserie=$numserie&&fncomp=$facnum")); 

		} else { 
			// Agregar el cambio que hice en el de pedidos, que lo env�a a un php que es contenedor del factura_ok, as� queda
			// el menu a la vista
  			$facnum = $num_fac;
 			$insertGoTo = "facturaLA_ok.php?factura=$facnum";
  			if (isset($_SERVER['QUERY_STRING'])) {
    			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    			$insertGoTo .= $_SERVER['QUERY_STRING'];
  			}
  			header(sprintf("Location: %s", "facturaLA_ok.php?factura=$facnum")); 
		}
	}
	setcookie("factura",""); // DEJAR LA COOKIE EN EL DISCO LOCAL
	mysqli_select_db($amercado, $database_amercado);
	$query_facturas_a = "SELECT * FROM series  WHERE  series.codnum=29";
	$facturas_a = mysqli_query($amercado, $query_facturas_a) or die(mysqli_error($amercado));
	$row_facturas_a = mysqli_fetch_assoc($facturas_a);
	$totalRows_facturas_a = mysqli_num_rows($facturas_a);
	$facturanum1 = ($row_facturas_a['nroact'])+1;
	// Agrega Mascara 
	$mascara1      = $row_facturas_a['mascara']; // 
	$tcomp = $row_facturas_a['tipcomp'];
	mysqli_select_db($amercado, $database_amercado);
	$query_facturas_b = "SELECT * FROM series  WHERE series.tipcomp=23  AND series.codnum=11";
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
    if (selec[0].selected == true)
    {
		var seleccionar = new Option("<-- esperando selecci�n","","","");
    }

    if (selec[1].selected == true)
    {
		factura.serie.value = 29;
		factura.serie_texto.value = "SERIE NOTA CRED A0004";
		factura.tcomp.value = 57;
		<?php //echo $facturanum1 ?>;
	
    }

    if (selec[2].selected == true)
    {
	
		factura.serie.value = 11;
		factura.serie_texto.value = "SERIE DE FACTURA B0001";
		factura.tcomp.value = 23;
		<?php //echo $facturanum2 ?>;
	}
}
</script>
<?php
mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

mysqli_select_db($amercado, $database_amercado);
$query_cliente = "SELECT * FROM entidades WHERE (tipoent='2' OR tipoent='1') AND activo = '1' AND tipoiva = '1'  ORDER BY razsoc ASC";
$cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($cliente);
$totalRows_cliente = mysqli_num_rows($cliente);

$colname_serie = "29";
if (isset($_POST['tcomp'])) {
  $colname_serie = addslashes($_POST['tcomp']);
}

$nivel = 9 ;
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM `remates` ORDER BY `ncomp` desc";
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

<script src="cjl_cookie.js" type="text/javascript"></script>
<script language="javascript">
function forma_pago(form) {

  	var total      = factura.tot_general.value;  // Total de la factura
	var fac_numero = factura.num_factura.value ; // Nuemro de la Factura 
	var tipo_comprobante = factura.tcomp.value ;  // Tipo de comprobante 
	var serie_num     = factura.serie.value ;  // Numero de Serie 
	var fecha_factura = factura.fecha_factura.value ; //  Fecha de Factura
	var remate_num    = factura.remate_num.value ; // Numero de Remate
	var chequedo = factura.pago_contado.value ;
  
	// DESDE ACA VALIDACION DE IMPUESTOS//
	var lote  = factura.lote.value ;
	var lote1 = factura.lote1.value ;
	var lote2 = factura.lote2.value ;
	var lote3 = factura.lote3.value ;
	var lote4 = factura.lote4.value ;
	var lote5 = factura.lote5.value ;
	var lote6 = factura.lote6.value ;
	var lote7 = factura.lote7.value ;
	var lote8 = factura.lote8.value ;
	var lote9 = factura.lote9.value ;
	var lote10 = factura.lote10.value ;
	var lote11 = factura.lote11.value ;
	var lote12 = factura.lote12.value ;
	var lote13 = factura.lote13.value ;
	var lote14 = factura.lote14.value ;
	var impuesto =   factura.impuesto[0].checked;//
	var impuesto_0 = factura.impuesto[1].checked;//
	var impuesto1 =   factura.impuesto1[0].checked;//
	var impuesto1_0 = factura.impuesto1[1].checked;//
	var impuesto2 =   factura.impuesto2[0].checked;//
	var impuesto2_0 = factura.impuesto2[1].checked;//
	var impuesto3 =   factura.impuesto3[0].checked;//
	var impuesto3_0 = factura.impuesto3[1].checked;//
	var impuesto4 =   factura.impuesto4[0].checked;//
	var impuesto4_0 = factura.impuesto4[1].checked;//
	
	var impuesto5 =   factura.impuesto5[0].checked;//
	var impuesto5_0 = factura.impuesto5[1].checked;//
	var impuesto6 =   factura.impuesto6[0].checked;//
	var impuesto6_0 = factura.impuesto6[1].checked;//
	var impuesto7 =   factura.impuesto7[0].checked;//
	var impuesto7_0 = factura.impuesto7[1].checked;//
	var impuesto8 =   factura.impuesto8[0].checked;//
	var impuesto8_0 = factura.impuesto8[1].checked;//
	var impuesto9 =   factura.impuesto9[0].checked;//
	var impuesto9_0 = factura.impuesto9[1].checked;//
	
	var impuesto10 =   factura.impuesto10[0].checked;//
	var impuesto10_0 = factura.impuesto10[1].checked;//
	var impuesto11 =   factura.impuesto11[0].checked;//
	var impuesto11_0 = factura.impuesto11[1].checked;//
	var impuesto12 =   factura.impuesto12[0].checked;//
	var impuesto12_0 = factura.impuesto12[1].checked;//
	var impuesto13 =   factura.impuesto13[0].checked;//
	var impuesto13_0 = factura.impuesto13[1].checked;//
	var impuesto14 =   factura.impuesto14[0].checked;//
	var impuesto14_0 = factura.impuesto14[1].checked;//
  
	var imp = 1;
	if (impuesto==false && impuesto_0==false && lote !="" ) {
		var imp = 0; 
  	}
  	if (impuesto1==false && impuesto1_0==false && lote1 !="" ) {
	  	var imp = 0; 
  	}
    if (impuesto2==false && impuesto2_0==false && lote2 !="" ) {
		var imp = 0; 
  	}
    if (impuesto3==false && impuesto3_0==false && lote3 !="" ) {
	  	var imp = 0; 
  	}
    if (impuesto4==false && impuesto4_0==false && lote4 !="" ) {
		var imp = 0; 
  	}
    if (impuesto5==false && impuesto5_0==false && lote5 !="" ) {
		var imp = 0; 
  	}
    if (impuesto6==false && impuesto6_0==false && lote6 !="" ) {
		var imp = 0; 
  	}
    if (impuesto7==false && impuesto7_0==false && lote7 !="" ) {
		var imp = 0; 
  	}
    if (impuesto8==false && impuesto8_0==false && lote8 !="" ) {
		var imp = 0; 
  	}
    if (impuesto9==false && impuesto9_0==false && lote9 !="" ) {
		var imp = 0; 
  	}
   	if (impuesto10==false && impuesto10_0==false && lote10 !="" ) {
		var imp = 0; 
  	}
   	if (impuesto11==false && impuesto11_0==false && lote11 !="" ) {
		var imp = 0; 
  	}
   	if (impuesto12==false && impuesto12_0==false && lote12 !="" ) {
		var imp = 0; 
  	}
   	if (impuesto13==false && impuesto13_0==false && lote13 !="" ) {
		var imp = 0; 
  	}
   	if (impuesto14==false && impuesto14_0==false && lote14 !="" ) {
		var imp = 0; 
  	}
	
	// HASTA ACA VALIDACION FORMULARIO 
	factura.leyenda.value = "";
  	var error ="";
  	if (tipo_comprobante=="" || total=="" || serie_num=="" || remate_num=="" || imp == 0  || fecha_factura=="") {
      	if (tipo_comprobante=="") {
         	error = "      Tipo de comprobante\n"; 
        }
      	if (imp == 0) {
	  		error = error+"     Valor impuesto\n";
	  	}
	    if (serie_num=="") {
        	error = error+"      Serie\n"; 
        }
	 	if (remate_num=="") {
        	error = error+"      Numero de remate\n"; 
        }	
	 	if (total=="") {
        	error = error+"      Total general\n"; 
        }
		if (fecha_factura=="") {
        	error = error+"      Fecha de Factura\n"; 
        }		 
		alert ("Faltan los siguientes datos :\n"+error);
	} else {
  		// escribimos el mensaje de alerta
		strAlerta = "Total Factura " + total + "\n" + "Numero Factura " + fac_numero + "\n" + "Tipo de comprobante " + tipo_comprobante + "\n" + "Serie Numero " + serie_num + "\n" + "Fecha Factura " + fecha_factura + "\n" + "Numero de remate " + remate_num + "\n";
//alert("Estoy en forma_pago 4 ");
 
   		var f = document.forms[0] ;
   		var ckUtil = new CJL_CookieUtil("factura", 30);
		//alert("Estoy en forma_pago 5 ");
 		function setFieldFromCookie(fieldId) {
			var cookieVal = ckUtil.getSubValue(fieldId);
	  		if( cookieVal )	{
	     		f[fieldId].value = cookieVal;
			}
		}
		//alert("Estoy en forma_pago 6 ");
    	function saveFieldToCookie(fieldId)
   		{
			var fieldVal = f[fieldId].value;	  
	 		ckUtil.setSubValue(fieldId, fieldVal);	  	  
   		}
		//alert("Estoy en forma_pago 7 ");
    	if( ckUtil.cookieExists() )
  		{
    		setFieldFromCookie("tcomp");
   			setFieldFromCookie("serie");
   			setFieldFromCookie("remate_num");
   			setFieldFromCookie("num_factura");
   			setFieldFromCookie("tot_general");
    	} else   
   		{
    		saveFieldToCookie("tcomp");
      		saveFieldToCookie("serie");
      		saveFieldToCookie("remate_num");
	  		saveFieldToCookie("num_factura");
	  		saveFieldToCookie("tot_general");	  
	  		 
		}
		
		window.open("medios_pago.php","nueva","fullscreen,scrollbars");
	}
}
</script>

<script language="javascript">
function sin_lotes(form)
{
	alert("Debe ingresar al menos un lote para facturar");
}
</script> 
<script language="javascript">
function OcultarCapa(capa)
{
	document.all.cheques_tercero.style.visibility='visible' // Si utilizamos IE
	document.all.medios_p.style.visibility='hidden'
}
</script>
<script language="javascript">
function cambia_fecha(form)
{ 

	var fecha = factura.fecha_factura1.value;
	var ano = fecha.substring(6,10);
	var mes = fecha.substring(3,5);
	var dia = fecha.substring(0,2);
	var fecha1 = ano+"-"+mes+"-"+dia ;
	factura.fecha_factura.value = fecha1;
}
function pasaValor(form)
{ 

	var comprobante = factura.tcomp.value;  // Nuemro de remate
	var serie       = factura.serie.value; // Tipo de industria
	var factnum     = factura.num_factura.value; // Codigo de cliente
	var fecha_fact  = factura.fecha_factura.value; // Direccion del remate
	var remate      = factura.remate_num.value; // Direccion del remate
}
</script>

<script language="javascript">
function  valor_prueba(form) {

    if (factura.impuesto[0].checked==true) {
	    var impuesto = (factura.impuesto[0].value)/100;
    }

    if (factura.impuesto[1].checked==true) {
    	var impuesto = (factura.impuesto[1].value)/100;
    }
    if (factura.impuesto1[0].checked==true) {
        var impuesto1 = (factura.impuesto1[0].value)/100;
    }
   	if (factura.impuesto1[1].checked==true) {
        var impuesto1 = (factura.impuesto1[1].value)/100;
    }
    if (factura.impuesto2[0].checked==true) {
        var impuesto2 = (factura.impuesto2[0].value)/100;
    }
    if (factura.impuesto2[1].checked==true) {
        var impuesto2 = (factura.impuesto2[1].value)/100;
    }
	if (factura.impuesto3[0].checked==true) {
		var impuesto3 = (factura.impuesto3[0].value)/100;
	}
	if (factura.impuesto3[1].checked==true) {
		var impuesto3 = (factura.impuesto3[1].value)/100;
	} 
	var total = factura.importe.value;
	var total_articulo = impuesto+('+')+total;
} 

</script>
<script language="javascript">
function validarFormulario(form)
{
		
	var monto  = factura.importe.value; // Monto  primer lote
	var comi   = factura.comision.value; // Comision  primer lote
	var imp105 = (factura.impuesto[0].value)/100; // Impuesto 10,5 %
	var imp21  = (factura.impuesto[1].value)/100; // impuesto 21 %
	
	var monto1 = factura.importe1.value; // Monto segundo lote
	var comi1  = factura.comision1.value;// Comision  segundo lote
	var imp105_1 = (factura.impuesto1[0].value)/100; // Impuesto 10,5 %
	var imp21_1  = (factura.impuesto1[1].value)/100; // impuesto 21 %
	
	var monto2 = factura.importe2.value; // Monto tercer lote
	var comi2  = factura.comision2.value; // Comision  tercer lote
	var imp105_2 = (factura.impuesto2[0].value)/100; // Impuesto 10,5 %
	var imp21_2  = (factura.impuesto2[1].value)/100; // impuesto 21 %
	
	var monto3 = factura.importe3.value; // Monto cuarto lote
	var comi3  = factura.comision3.value; // Comision  cuarto lote
	var imp105_3 = (factura.impuesto3[0].value)/100; // Impuesto 10,5 %
	var imp21_3  = (factura.impuesto3[1].value)/100; // impuesto 21 %
	
	var monto4 = factura.importe4.value; // Monto Quinto lote
	var comi4  = factura.comision4.value; // Comision  cuarto lote
	var imp105_4 = (factura.impuesto4[0].value)/100; // Impuesto 10,5 %
	var imp21_4  = (factura.impuesto4[1].value)/100; // impuesto 21 %
	
	var monto5 = factura.importe5.value; // Monto Sexto lote
	var comi5   = factura.comision5.value;  // Comision  Sexto lote
	var imp105_5 = (factura.impuesto5[0].value)/100; // Impuesto 10,5 %
	var imp21_5  = (factura.impuesto5[1].value)/100; // impuesto 21 %
	
	var monto6  = factura.importe6.value; // Monto Septimo lote
	var comi6   = factura.comision6.value; // Comision  Septimo lote
	var imp105_6 = (factura.impuesto6[0].value)/100; // Impuesto 10,5 %
	var imp21_6  = (factura.impuesto6[1].value)/100; // impuesto 21 %
	
	var monto7  = factura.importe7.value; // Monto Octavo lote
	var comi7   = factura.comision7.value; // Comision  Octavo lote
	var imp105_7 = (factura.impuesto7[0].value)/100; // Impuesto 10,5 %
	var imp21_7  = (factura.impuesto7[1].value)/100; // impuesto 21 %
	
	var monto8  = factura.importe8.value; // Monto Noveno lote
	var comi8   = factura.comision8.value; // Comision  Noveno lote
	var imp105_8 = (factura.impuesto8[0].value)/100; // Impuesto 10,5 %
	var imp21_8  = (factura.impuesto8[1].value)/100; // impuesto 21 %
	
	var monto9  = factura.importe9.value; // Monto D�cimo lote
	var comi9   = factura.comision9.value; // Comision  D�cimo lote
	var imp105_9 = (factura.impuesto9[0].value)/100; // Impuesto 10,5 %
	var imp21_9  = (factura.impuesto9[1].value)/100; // impuesto 21 %
	
	var monto10 = factura.importe10.value;  // Monto Onceavo lote
	var comi10  = factura.comision10.value; // Comision  Onceavo lote
	var imp105_10 = (factura.impuesto10[0].value)/100; // Impuesto 10,5 %
	var imp21_10  = (factura.impuesto10[1].value)/100; // impuesto 21 %
		
	var monto11 = factura.importe11.value; // Monto Doceavo lote
	var comi11  = factura.comision11.value; // Comision  Doceavo lote
	var imp105_11 = (factura.impuesto11[0].value)/100; // Impuesto 10,5 %
	var imp21_11  = (factura.impuesto11[1].value)/100; // impuesto 21 %
	
	var monto12 = factura.importe12.value; // Monto Treceavo lote
	var comi12  = factura.comision12.value; // Comision  Treceavo lote
	var imp105_12 = (factura.impuesto12[0].value)/100; // Impuesto 10,5 %
	var imp21_12  = (factura.impuesto12[1].value)/100; // impuesto 21 %
	
	var monto13 = factura.importe13.value; // Monto Catorceavo lote
	var comi13  = factura.comision13.value; // Comision  Catorceavo lote
	var imp105_13 = (factura.impuesto13[0].value)/100; // Impuesto 10,5 %
	var imp21_13  = (factura.impuesto13[1].value)/100; // impuesto 21 %
	
	var monto14 = factura.importe14.value; // Monto Quinceavo lote
	var comi14  = factura.comision14.value; // Comision  Quinceavo lote
	var imp105_14 = (factura.impuesto14[0].value)/100; // Impuesto 10,5 %
	var imp21_14  = (factura.impuesto14[1].value)/100; // impuesto 21 %
	
    var  tot_mon = 0 ;
    var tot_comi = 0 ;
	var neto105 = 0;
	var neto21 = 0 ;
	var imp_tot105 = 0 ;
	var imp_tot21 = 0 ;
	var tot_mon105 = 0 ;
	var tot_mon21  = 0;
	var totresol  = 0;

    if (factura.impuesto[0].checked==true) {
    	if (monto.length!=0 ) {
			tot_mon = eval(monto);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi = (comi*monto)/100;
			tot_mon105 = eval(monto);
			tot_mon105 = tot_mon105.toFixed(2);
	        imp_tot105 = eval(monto*imp105) ;
			imp_tot21 = eval(((comi*monto)/100)*imp21);
	  	}  
	}
	
	if (factura.impuesto[1].checked==true) {
		if (monto.length!=0) {
	    	tot_mon = eval(monto);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi = (comi*monto)/100;
			tot_mon21 = eval(monto); 
			tot_mon21 = tot_mon21.toFixed(2);
	        imp_tot21 = eval(monto+('+')+((comi*monto)/100))*imp21;
		}  
	}
		
	if (factura.impuesto1[0].checked==true) { 
		if (monto1.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto1);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi1 = (comi1*monto1)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto1); 
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi1);
			imp_tot105_1 = eval(monto1)*imp105_1 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_1);
			imp_tot21_1 = eval(tot_comi1)*imp21_1 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_1);
		} 
	}
			 
	if (factura.impuesto1[1].checked==true) {	 
		if (monto1.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto1);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi1 = (comi1*monto1)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto1); 
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi1);
			imp_tot21_1 = eval(monto1+('+')+tot_comi1)*imp21_1 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_1);
	   	} 
	}	 
	
	if (factura.impuesto2[0].checked==true) { 
		if (monto2.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto2);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi2 = (comi2*monto2)/100;
			tot_mon105 =eval(tot_mon105+('+')+monto2); 
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi2);
			imp_tot105_2 =  eval(monto2)*imp105_2 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_2);
			imp_tot21_2 = eval(tot_comi2)*imp21_2 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_2);
	   	} 
	}
			 
	if (factura.impuesto2[1].checked==true) {	 
		if (monto2.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto2);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi2 = (comi2*monto2)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto2); 
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi2);
			imp_tot21_2 = eval(monto2+('+')+tot_comi2)*imp21_2 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_2);
	   	} 
	}	
	
	if (factura.impuesto3[0].checked==true) { 
		if (monto3.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto3);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi3 = (comi3*monto3)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto3);
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi3);
			imp_tot105_3 = eval(monto3)*imp105_3 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_3);
			imp_tot21_3 = eval(tot_comi3)*imp21_3;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_3);
	  	} 
	}
			 
	if (factura.impuesto3[1].checked==true) {	 
		if (monto3.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto3);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi3 = (comi3*monto3)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto3); 
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi3);
			imp_tot21_3 = eval(monto3+('+')+tot_comi3)*imp21_3 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_3);
	  	} 
	}	
	
	if (factura.impuesto4[0].checked==true) { 
		if (monto4.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto4);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi4 = (comi4*monto4)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto4); 
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi4);
			imp_tot105_4 = eval(monto4)*imp105_4 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_4);
			imp_tot21_4 = eval(tot_comi4)*imp21_4 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_4);
	 	} 
	}
			 
	if (factura.impuesto4[1].checked==true) {	 
		if (monto4.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto4);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi4 = (comi4*monto4)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto4); 
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi4);
			imp_tot21_4 = eval(monto4+('+')+tot_comi4)*imp21_4 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_4);
	  	} 
	}	
	
	if (factura.impuesto5[0].checked==true) { 
		if (monto5.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto5);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi5 = (comi5*monto5)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto5); 
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi5);
			imp_tot105_5 = eval(monto5)*imp105_5 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_5);
			imp_tot21_5 = eval(tot_comi5)*imp21_5 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_5);
	  	} 
	}
			 
	if (factura.impuesto5[1].checked==true) {	 
		if (monto5.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto5);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi5 = (comi5*monto5)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto5);
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi5);
			imp_tot21_5 = eval(monto5+('+')+tot_comi5)*imp21_5 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_5);
	   	} 
	}	
	
	if (factura.impuesto6[0].checked==true) { 
		if (monto6.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto6);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi6 = (comi6*monto6)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto6);
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi6);
			imp_tot105_6 = eval(monto6)*imp105_6 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_6);
			imp_tot21_6 = eval(tot_comi6)*imp21_6 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_6);
	  	} 
	}
			 
	if (factura.impuesto6[1].checked==true) {	 
		if (monto6.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto6);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi6 = (comi6*monto6)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto6);
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi6);
			imp_tot21_6 = eval(monto6+('+')+tot_comi6)*imp21_6 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_6);
	  	} 
	}	
 
	if (factura.impuesto7[0].checked==true) { 
		if (monto7.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto7);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi7 = (comi7*monto7)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto7); 
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi7);
			imp_tot105_7 = eval(monto7)*imp105_7 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_7);
			imp_tot21_7 = eval(tot_comi7)*imp21_7 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_7);
	   	} 
	}
			 
	if (factura.impuesto7[1].checked==true) {	 
		if (monto7.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto7);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi7 = (comi7*monto7)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto7);
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi7);
			imp_tot21_7 = eval(monto7+('+')+tot_comi7)*imp21_7 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_7);
	   	} 
	}	
	
	if (factura.impuesto8[0].checked==true) { 
		if (monto8.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto8);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi8 = (comi8*monto8)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto8);
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi8);
			imp_tot105_8 = eval(monto8)*imp105_8 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_8);
			imp_tot21_8 = eval(tot_comi8)*imp21_8 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_8);
	   	} 
	}
			 
	if (factura.impuesto8[1].checked==true) {	 
		if (monto8.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto8);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi8 = (comi8*monto8)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto8);
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi8);
			imp_tot21_8 = eval(monto8+('+')+tot_comi8)*imp21_8 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_8);
	    } 
	}	
	
	if (factura.impuesto9[0].checked==true) { 
		if (monto9.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto9);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi9 = (comi9*monto9)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto9); 
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi9);
			imp_tot105_9 = eval(monto9)*imp105_9 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_9);
			imp_tot21_9 = eval(tot_comi9)*imp21_9 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_9);
	  	} 
	}
			 
	if (factura.impuesto9[1].checked==true) {	 
		if (monto9.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto9);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi9 = (comi9*monto9)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto9);
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi9);
			imp_tot21_9 = eval(monto9+('+')+tot_comi9)*imp21_9 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_9);
	  	} 
	}	
	
	if (factura.impuesto10[0].checked==true) { 
		if (monto10.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto10);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi10 = (comi10*monto10)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto10);
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi10);
			imp_tot105_10 = eval(monto10)*imp105_10 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_10);
			imp_tot21_10 = eval(tot_comi10)*imp21_10 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_10);
	  	} 
	}
			 
	if (factura.impuesto10[1].checked==true) {	 
		if (monto10.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto10);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi10 = (comi10*monto10)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto10);
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi10);
			imp_tot21_10 = eval(monto10+('+')+tot_comi10)*imp21_10 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_10);
	  	} 
	}	
	
	if (factura.impuesto11[0].checked==true) { 
		if (monto11.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto11);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi11 = (comi11*monto11)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto11);
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi11);
			imp_tot105_11 = eval(monto11)*imp105_11 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_11);
			imp_tot21_11 = eval(tot_comi11)*imp21_11 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_11);
	  	} 
	}
			 
	if (factura.impuesto11[1].checked==true) {	 
		if (monto11.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto11);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi11 = (comi11*monto11)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto11); 
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi11);
			imp_tot21_11 = eval(monto11+('+')+tot_comi11)*imp21_11 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_11);
	   	} 
	}	
	       
	if (factura.impuesto12[0].checked==true) { 
		if (monto12.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto12);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi12 = (comi12*monto12)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto12);
			tot_comi12 = (comi12*monto12)/100;
	        tot_comi = eval(tot_comi+('+')+tot_comi12);
			imp_tot105_12 = eval(monto12)*imp105_12 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_12);
			imp_tot21_12 = eval(tot_comi12)*imp21_12 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_12);
	  	} 
	}
			 
	if (factura.impuesto12[1].checked==true) {	 
		if (monto12.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto12);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi12 = (comi12*monto12)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto12);
			tot_comi12 = (comi12*monto12)/100;
	        tot_comi = eval(tot_comi+('+')+tot_comi12);
			imp_tot21_12 = eval(monto12+('+')+tot_comi12)*imp21_12 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_12);
	  	} 
	}
				
	if (factura.impuesto13[0].checked==true) { 
		if (monto13.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto13);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi13 = (comi13*monto13)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto13);
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi13);
			imp_tot105_13 = eval(monto13)*imp105_13 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_13);
			imp_tot21_13 = eval(tot_comi13)*imp21_13 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_13);
	   	} 
	}
			 
	if (factura.impuesto13[1].checked==true) {	 
		if (monto13.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto13);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi13 = (comi13*monto13)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto13); 
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi13);
			imp_tot21_13 = eval(monto13+('+')+tot_comi13)*imp21_13 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_13);
	   	} 
	}	
	
	if (factura.impuesto14[0].checked==true) { 
		if (monto14.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto14);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi14 = (comi14*monto14)/100;
			tot_mon105 = eval(tot_mon105+('+')+monto14);
			tot_mon105 = tot_mon105.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi14);
			imp_tot105_14 = eval(monto14)*imp105_14 ;
			imp_tot105 = eval(imp_tot105+('+')+imp_tot105_14);
			imp_tot21_14 = eval(tot_comi14)*imp21_14 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_14);
	   	} 
	}
			 
	if (factura.impuesto14[1].checked==true) {	 
		if (monto14.length!=0) {
	    	tot_mon = eval(tot_mon+('+')+monto14);
	        tot_mon = tot_mon.toFixed(2);
			tot_comi14 = (comi14*monto14)/100;
			tot_mon21 = eval(tot_mon21+('+')+monto14); 
			tot_mon21 = tot_mon21.toFixed(2);
	        tot_comi = eval(tot_comi+('+')+tot_comi14);
			imp_tot21_14 = eval(monto14+('+')+tot_comi14)*imp21_14 ;
			imp_tot21 = eval(imp_tot21+('+')+imp_tot21_14);
	  	} 
	}	
	
	tot_comi=tot_comi.toFixed(2);
	tot_neto = eval(tot_mon+('+')+tot_comi+('+')+imp_tot21+('+')+imp_tot105);
	tot_neto = tot_neto.toFixed(2);
	
	var	imp_tot105 = imp_tot105.toFixed(2);
	var imp_tot21 = imp_tot21.toFixed(2);
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
		// Visible
		factura.totiva105_1.value = imp_tot105;
		factura.totiva21_1.value = imp_tot21;
		factura.totcomis_1.value = tot_comi ;
		factura.tot_general_1.value = tot_neto;
		factura.totneto105_1.value = tot_mon105;
		factura.totneto21_1.value = tot_mon21;
	}
			  
	if  (factura.GrupoOpciones1[0].checked == true && factura.pago_contado.checked == true )
	{
		factura.leyenda.value = "Se abona con efectivo la cantidad de $ "+tot_neto;
	}
}
</script>

<script language="javascript">
function resol(form)
{
	var imp_tot105 = factura.totneto105.value;
	var imp_tot21  = factura.totneto21.value;
	porc105 = 0.015;
	porc21  = 0.03;
	if (factura.tieneresol.checked==true) {
		tot_resol = eval((imp_tot105+('*')+porc105)+('+')+(imp_tot21+('*')+porc21));
		tot_resol=tot_resol.toFixed(2);
		factura.totimp.value = tot_resol;
		factura.tot_general.value = eval(tot_neto+('+')+tot_resol);
	}
	else {
		factura.totimp.value = 0;
		factura.tot_general.value = tot_neto;
	}
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
	var ajax1 = new sack();
	var currentLoteID=false;
	// Segundo Lote
	var ajax2 = new sack();
	var currentLoteID1=false;
	// Tercer Lote
	var ajax3 = new sack();
	var currentLoteID2=false;
	// Cuarto Lote
	var ajax4 = new sack();
	var currentLoteID3=false;
	// Quinto Lote
	var ajax5 = new sack();
	var currentLoteID4=false;
	// Sexto Lote
	var ajax6 = new sack();
	var currentLoteID5=false;
	// Septimo Lote
	var ajax7 = new sack();
	var currentLoteID6=false;
	// Octavo Lote
	var ajax8 = new sack();
	var currentLoteID7=false;
	// Novenmo Lote
	var ajax9 = new sack();
	var currentLoteID8=false;
	// Decimo Lote
	var ajax10 = new sack();
	var currentLoteID9=false;
	// Lote Once
	var ajax11 = new sack();
	var currentLoteID10=false;
	// Lote Doce
	var ajax12 = new sack();
	var currentLoteID11=false;
	// Lote Trece
	var ajax13 = new sack();
	var currentLoteID12=false;
	// Lote Catorce
	var ajax14 = new sack();
	var currentLoteID13=false;
	// Lote Quince
	var ajax15 = new sack();
	var currentLoteID14=false;
	
	function getClientData()
	{
		var clientId = document.getElementById('remate_num').value.replace(/[^0-9]/g,'');
		
		if( clientId!=currentClientID){
			currentClientID = clientId
			ajax.requestFile = 'getFact.php?getClientId='+clientId;	// Specifying which file to get
			ajax.onCompletion = showClientData;	// Specify function that will be executed after file has been found
			ajax.runAJAX();		// Execute AJAX function			
		}
	}
	
	function getLoteData() // Primer lote
	{
		var loteId = document.getElementById('lote').value ;
		var RemateId = document.getElementById('remate_num').value;
	
		if( loteId!=currentLoteID){
			currentLoteID = loteId;
			ajax1.requestFile = 'getlote.php?getloteId='+loteId+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax1.onCompletion = showLoteData;	// Specify function that will be executed after file has been found
			ajax1.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData1()  /// Segundo Lote
	{
		var loteId1 = document.getElementById('lote1').value ;
		var RemateId = document.getElementById('remate_num').value;
				
		if( loteId1!=currentLoteID1){
	   		currentLoteID1 = loteId1;
			ajax2.requestFile = 'getlote1.php?getloteId1='+loteId1+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax2.onCompletion = showLoteData1;	// Specify function that will be executed after file has been found
			ajax2.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData2()  /// Tercer lote
	{
		var loteId2 = document.getElementById('lote2').value ;
		var RemateId = document.getElementById('remate_num').value;
		
		if( loteId2!=currentLoteID2){
			currentLoteID2 = loteId2;
			ajax3.requestFile = 'getlote2.php?getloteId2='+loteId2+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax3.onCompletion = showLoteData2;	// Specify function that will be executed after file has been found
			ajax3.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData3()  /// Cuarto lote
	{
		var loteId3 = document.getElementById('lote3').value ;
		var RemateId = document.getElementById('remate_num').value;
		
		if( loteId3!=currentLoteID3){
			currentLoteID3 = loteId3;
			ajax4.requestFile = 'getlote3.php?getloteId3='+loteId3+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax4.onCompletion = showLoteData3;	// Specify function that will be executed after file has been found
			ajax4.runAJAX();		// Execute AJAX function			
		}
	}

	function getLoteData4()  /// Quinto lote
	{
		var loteId4 = document.getElementById('lote4').value ;
		var RemateId = document.getElementById('remate_num').value;
		
		if( loteId4!=currentLoteID4){
			currentLoteID4 = loteId4;
			ajax5.requestFile = 'getlote4.php?getloteId4='+loteId4+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax5.onCompletion = showLoteData4;	// Specify function that will be executed after file has been found
			ajax5.runAJAX();		// Execute AJAX function			
		}	
	}	

	function getLoteData5()  /// Sexto lote
	{
		var loteId5 = document.getElementById('lote5').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId5!=currentLoteID5){
			currentLoteID5= loteId5;
			ajax6.requestFile = 'getlote5.php?getloteId5='+loteId5+'&getremate_num='+RemateId;	// Specifying which file to get
	    	ajax6.onCompletion = showLoteData5;	// Specify function that will be executed after file has been found
			ajax6.runAJAX();		// Execute AJAX function			
		}	
	}
		
	function getLoteData6()  /// Septimo lote
	{
		var loteId6 = document.getElementById('lote6').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId6!=currentLoteID6){
			currentLoteID6= loteId6;
			ajax7.requestFile = 'getlote6.php?getloteId6='+loteId6+'&getremate_num='+RemateId;	// Specifying which file to get
			ajax7.onCompletion = showLoteData6;	// Specify function that will be executed after file has been found
			ajax7.runAJAX();		// Execute AJAX function			
		}	
	}	

	function getLoteData7()  /// Octavo lote
	{
		var loteId7 = document.getElementById('lote7').value ;
		var RemateId = document.getElementById('remate_num').value;
 		if( loteId7!=currentLoteID7){
 			currentLoteID7= loteId7;
 			ajax8.requestFile = 'getlote7.php?getloteId7='+loteId7+'&getremate_num='+RemateId;	// Specifying which file to get
 			ajax8.onCompletion = showLoteData7;	// Specify function that will be executed after file has been found
 			ajax8.runAJAX();		// Execute AJAX function			
 		}	
 	}
 
 	function getLoteData8()  /// Noveno lote
	{
		var loteId8 = document.getElementById('lote8').value ;
		var RemateId = document.getElementById('remate_num').value;
 		if( loteId8!=currentLoteID8){
 			currentLoteID8= loteId8;
 			ajax9.requestFile = 'getlote8.php?getloteId8='+loteId8+'&getremate_num='+RemateId;	// Specifying which file to get
 			ajax9.onCompletion = showLoteData8;	// Specify function that will be executed after file has been found
 			ajax9.runAJAX();		// Execute AJAX function			
 		}	
 	}	
  
  	function getLoteData9()  /// Decimo lote
	{
		var loteId9 = document.getElementById('lote9').value ;
		var RemateId = document.getElementById('remate_num').value;
 		if( loteId9!=currentLoteID9){
 			currentLoteID9= loteId9;
 			ajax10.requestFile = 'getlote9.php?getloteId9='+loteId9+'&getremate_num='+RemateId;	// Specifying which file to get
 			ajax10.onCompletion = showLoteData9;	// Specify function that will be executed after file has been found
 			ajax10.runAJAX();		// Execute AJAX function			
 		}	
 	}
 
	function getLoteData10()  ///  Lote Once
	{
		var loteId10 = document.getElementById('lote10').value ;
		var RemateId = document.getElementById('remate_num').value;
 		if( loteId10!=currentLoteID10){
 			currentLoteID10= loteId10;
 			ajax11.requestFile = 'getlote10.php?getloteId10='+loteId10+'&getremate_num='+RemateId;	// Specifying which file to get
 			ajax11.onCompletion = showLoteData10;	// Specify function that will be executed after file has been found
 			ajax11.runAJAX();		// Execute AJAX function			
 		}	
 	}	
 
	function getLoteData11()  /// Lote Doce
	{
		var loteId11 = document.getElementById('lote11').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId11!=currentLoteID11){
			currentLoteID11= loteId11;
  			ajax12.requestFile = 'getlote11.php?getloteId11='+loteId11+'&getremate_num='+RemateId;	// Specifying which file to get
  			ajax12.onCompletion = showLoteData11;	// Specify function that will be executed after file has been found
  			ajax12.runAJAX();		// Execute AJAX function			
		}	
	}	

	function getLoteData12()  /// Lote trece
	{
		var loteId12 = document.getElementById('lote12').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId12!=currentLoteID12){
			currentLoteID12= loteId12;
  			ajax13.requestFile = 'getlote12.php?getloteId12='+loteId12+'&getremate_num='+RemateId;	// Specifying which file to get
  			ajax13.onCompletion = showLoteData12;	// Specify function that will be executed after file has been found
 			ajax13.runAJAX();		// Execute AJAX function			
		}	
	}	
		
	function getLoteData13()  /// Lote catorce
	{
		var loteId13 = document.getElementById('lote13').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId13!=currentLoteID13){
			currentLoteID13= loteId13;
  			ajax14.requestFile = 'getlote13.php?getloteId13='+loteId13+'&getremate_num='+RemateId;	// Specifying which file to get
  			ajax14.onCompletion = showLoteData13;	// Specify function that will be executed after file has been found
 			ajax14.runAJAX();		// Execute AJAX function			
		}	
	}	

	function getLoteData14()  /// Lote Quince
	{
		var loteId14 = document.getElementById('lote14').value ;
		var RemateId = document.getElementById('remate_num').value;
		if( loteId14!=currentLoteID14){
			currentLoteID14= loteId14;
  			ajax15.requestFile = 'getlote14.php?getloteId14='+loteId14+'&getremate_num='+RemateId;	// Specifying which file to get
  			ajax15.onCompletion = showLoteData14;	// Specify function that will be executed after file has been found
  			ajax15.runAJAX();		// Execute AJAX function			
		}	
	}			
		
	function showClientData()
	{
		var formObj = document.forms['factura'];	
		eval(ajax.response);
	}
	
	function showLoteData() // Primer lote
	{
		var formObj1 = document.forms['factura'];	
		eval(ajax1.response);
	}
	
	function showLoteData1() // Segundo  Lote
	{
		var formObj2 = document.forms['factura'];	
		eval(ajax2.response);
	}
	
	function showLoteData2() // Tercer lote
	{
		var formObj3 = document.forms['factura'];	
		eval(ajax3.response);
	}
	
	function showLoteData3() // Cuarto lote
	{
		var formObj4 = document.forms['factura'];	
		eval(ajax4.response);
	}
	
	function showLoteData4() // Quinto lote
	{
		var formObj5 = document.forms['factura'];	
		eval(ajax5.response);
	}
	
	function showLoteData5() // Sexto lote
	{
    	var formObj6 = document.forms['factura'];	
		eval(ajax6.response);
	}

	function showLoteData6() // Septimo lote
	{
    	var formObj7 = document.forms['factura'];	
		eval(ajax7.response);
	}

	function showLoteData7() // Octavo lote
	{
		var formObj8 = document.forms['factura'];	
	 	eval(ajax8.response);
	}

	function showLoteData8() // Noveno lote
	{
		var formObj9 = document.forms['factura'];	
	 	eval(ajax9.response);
	}

	function showLoteData9() // Decimo lote
	{
		var formObj10 = document.forms['factura'];	
	 	eval(ajax10.response);
	}
		
	function showLoteData10() // Once lote
	{
	 	var formObj11 = document.forms['factura'];	
	 	eval(ajax11.response);
	}
		
	function showLoteData11() // Docelote
	{
		var formObj12 = document.forms['factura'];	
	 	eval(ajax12.response);
	}	

	function showLoteData12() // Trece lote
	{
		var formObj13 = document.forms['factura'];	
	 	eval(ajax13.response);
	}	
			
	function showLoteData13() // Catorce lote
	{
		var formObj14 = document.forms['factura'];	
	 	eval(ajax14.response);
	}

	function showLoteData14() // Quince lote
	{
		var formObj15 = document.forms['factura'];	
	 	eval(ajax15.response);
	}
					
	function initFormEvents()
	{
	    var fecha1 = new Date();
		var dia = fecha1.getDate();
		var mes = (fecha1.getMonth()+1);
		var ano = fecha1.getYear();
		var fecha = ano+'-'+mes+'-'+dia ;
		var fecha11 = dia+'-'+mes+'-'+ano ;
		document.getElementById('fecha_factura').value = fecha ;
		document.getElementById('remate_num').onblur = getClientData;
		document.getElementById('remate_num').focus();
		document.getElementById('lote').onblur = getLoteData;
		document.getElementById('lote').focus();
		document.getElementById('lote1').onblur = getLoteData1;
		document.getElementById('lote1').focus();
		document.getElementById('lote2').onblur = getLoteData2;
		document.getElementById('lote2').focus();
		document.getElementById('lote3').onblur = getLoteData3;
		document.getElementById('lote3').focus();
		document.getElementById('lote4').onblur = getLoteData4;
		document.getElementById('lote4').focus();
		document.getElementById('lote5').onblur = getLoteData5;
		document.getElementById('lote5').focus();
		document.getElementById('lote6').onblur = getLoteData6;
		document.getElementById('lote6').focus();
		document.getElementById('lote7').onblur = getLoteData7;
		document.getElementById('lote7').focus();
		document.getElementById('lote8').onblur = getLoteData8;
		document.getElementById('lote8').focus();
		document.getElementById('lote9').onblur = getLoteData9;
		document.getElementById('lote9').focus();
		document.getElementById('lote10').onblur = getLoteData10;
		document.getElementById('lote10').focus();
	    document.getElementById('lote11').onblur = getLoteData11;
		document.getElementById('lote11').focus();
		document.getElementById('lote12').onblur = getLoteData12;
		document.getElementById('lote12').focus();
		document.getElementById('lote13').onblur = getLoteData13;
	    document.getElementById('lote13').focus();
		document.getElementById('lote14').onblur = getLoteData14;
		document.getElementById('lote14').focus();
	}
		
	window.onload = initFormEvents;
	</script>
	<script language="javascript">

	function pendiente(form)
	{
		if (factura.GrupoOpciones1[0].checked ==false)
    	{
			factura.leyenda.value=""; //"Detalle de medio de pago segun recibo";
			factura.pago_contado.disabled= true;

    	} else {
			factura.leyenda.value="";
     		factura.pago_contado.disabled= false;
 		}

	}
	</script>
	<!-- Hasta Aca  !-->
	<script language="javascript">
	<!--

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
        if (isNaN(val)) errors+='El importe debe contener un n�mero.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
        min=test.substring(8,p); max=test.substring(p+1);
        if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    	} } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  		} if (errors) alert('ERROR \n'+errors);
  		document.MM_returnValue = (errors == '');
	}
	//-->

	function validar_fatura(form)
	{
		//alert("Estoy en validar_factura 1 ");
		// DESDE ACA VALIDACION DE IMPUESTOS//
  		var lote  = factura.lote.value ;
  		var lote1 = factura.lote1.value ;
  		var lote2 = factura.lote2.value ;
  		var lote3 = factura.lote3.value ;
  		var lote4 = factura.lote4.value ;
  		var lote5 = factura.lote5.value ;
  		var lote6 = factura.lote6.value ;
  		var lote7 = factura.lote7.value ;
  		var lote8 = factura.lote8.value ;
  		var lote9 = factura.lote9.value ;
  		var lote10 = factura.lote10.value ;
  		var lote11 = factura.lote11.value ;
  		var lote12 = factura.lote12.value ;
  		var lote13 = factura.lote13.value ;
  		var lote14 = factura.lote14.value ;
  		var impuesto =   factura.impuesto[0].checked;//
  		var impuesto_0 = factura.impuesto[1].checked;//
  		var impuesto1 =   factura.impuesto1[0].checked;//
  		var impuesto1_0 = factura.impuesto1[1].checked;//
  		var impuesto2 =   factura.impuesto2[0].checked;//
  		var impuesto2_0 = factura.impuesto2[1].checked;//
  		var impuesto3 =   factura.impuesto3[0].checked;//
  		var impuesto3_0 = factura.impuesto3[1].checked;//
  		var impuesto4 =   factura.impuesto4[0].checked;//
  		var impuesto4_0 = factura.impuesto4[1].checked;//
    	var impuesto5 =   factura.impuesto5[0].checked;//
  		var impuesto5_0 = factura.impuesto5[1].checked;//
  		var impuesto6 =   factura.impuesto6[0].checked;//
  		var impuesto6_0 = factura.impuesto6[1].checked;//
  		var impuesto7 =   factura.impuesto7[0].checked;//
  		var impuesto7_0 = factura.impuesto7[1].checked;//
  		var impuesto8 =   factura.impuesto8[0].checked;//
  		var impuesto8_0 = factura.impuesto8[1].checked;//
  		var impuesto9 =   factura.impuesto9[0].checked;//
  		var impuesto9_0 = factura.impuesto9[1].checked;//
   		var impuesto10 =   factura.impuesto10[0].checked;//
  		var impuesto10_0 = factura.impuesto10[1].checked;//
  		var impuesto11 =   factura.impuesto11[0].checked;//
  		var impuesto11_0 = factura.impuesto11[1].checked;//
  		var impuesto12 =   factura.impuesto12[0].checked;//
  		var impuesto12_0 = factura.impuesto12[1].checked;//
  		var impuesto13 =   factura.impuesto13[0].checked;//
  		var impuesto13_0 = factura.impuesto13[1].checked;//
  		var impuesto14 =   factura.impuesto14[0].checked;//
  		var impuesto14_0 = factura.impuesto14[1].checked;//
  		var imp = 1;
  		if (impuesto==false && impuesto_0==false && lote !="" ) {
	  		var imp = 0; 
  		}
   		if (impuesto1==false && impuesto1_0==false && lote1 !="" ) {
			var imp = 0; 
  		}
     	if (impuesto2==false && impuesto2_0==false && lote2 !="" ) {
	  		var imp = 0; 
  		}
     	if (impuesto3==false && impuesto3_0==false && lote3 !="" ) {
	  		var imp = 0; 
  		}
     	if (impuesto4==false && impuesto4_0==false && lote4 !="" ) {
	  		var imp = 0; 
  		}
     	if (impuesto5==false && impuesto5_0==false && lote5 !="" ) {
	  		var imp = 0; 
  		}
     	if (impuesto6==false && impuesto6_0==false && lote6 !="" ) {
	  		var imp = 0; 
  		}
     	if (impuesto7==false && impuesto7_0==false && lote7 !="" ) {
	  		var imp = 0; 
  		}
     	if (impuesto8==false && impuesto8_0==false && lote8 !="" ) {
	  		var imp = 0; 
  		}
     	if (impuesto9==false && impuesto9_0==false && lote9 !="" ) {
	  		var imp = 0; 
  		}
   		if (impuesto10==false && impuesto10_0==false && lote10 !="" ) {
	  		var imp = 0; 
  		}
   		if (impuesto11==false && impuesto11_0==false && lote11 !="" ) {
	  		var imp = 0; 
  		}
   		if (impuesto12==false && impuesto12_0==false && lote12 !="" ) {
	  		var imp = 0; 
  		}
   		if (impuesto13==false && impuesto13_0==false && lote13 !="" ) {
	  		var imp = 0; 
  		}
   		if (impuesto14==false && impuesto14_0==false && lote14 !="" ) {
	  		var imp = 0; 
  		}
    	if (imp==0) {
	  		alert("Falta un tipo de impuesto");
  		} else {
			//alert("Voy al submit ");
	  		form.submit()
			//alert("Pase el submit ");
  		}
	}
	</script>

<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />
</head>
<body>

<form id="factura" name="factura" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="640" border="1" align="left" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
    <tr>
      <td colspan="3" background="images/fondo_titulos.jpg"><div align="center"><img src="images/not_cred_auto.gif" width="358" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="3" valign="top" bgcolor="#82BADD"><table width="100%" border="1" cellspacing="1" cellpadding="1">
        <tr>
      <td width="14%" height="20" bgcolor="#82BADD">&nbsp;<span class="ewTableHeader">Tipo de Cbte </span></td>
          <td width="40%"><select name="tipos" onChange="agregarOpciones(this.form)">
                                      <option value="">[seleccione una opci&oacute;n]</option>
                                      <option value="57">NOTA DE CREDITO A0004</option>
                                     
                           </select>
		  <input name="tcomp" id="tcomp"  type="hidden" />
		         </td>
          <td width="1%">&nbsp;</td>
          <td width="12%" class="ewTableHeader">&nbsp;Serie</td>
          <td width="33%"><input name="serie_texto" type="text"  size="25" />
		  <input name="serie" type="hidden"  size="25"/>
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
          <td><select name="remate_num" id="remate_num">
            <option value="">Remate</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_Recordset1['ncomp']?>"><?php echo $row_Recordset1['ncomp']?><?php echo " - "?><?php echo substr($row_Recordset1['direccion'],0,25)?></option>
            <?php
				} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  				$rows = mysqli_num_rows($Recordset1);
  				if($rows > 0) {
      				mysqli_data_seek($Recordset1, 0);
	  				$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  				}
			?>
          </select></td>
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
          <td><select name="codnum" id="codnum">
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
          <td width="39" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg" > <div align="center">LOTE</div></td>
          <td width="390" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">DESCRIPCION</div></td>
          <td width="40" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">COM</div></td>
          <td width="68" rowspan="2" class="ewTableHeader"  background="images/fonod_lote.jpg"> <div align="center">IMPORTE</div></td>
		 <td height="24" colspan="3" class="ewTableHeader" background="images/fonod_lote.jpg"><div align="center">IMPUESTOS</div></td>	  
        </tr>
        <tr>
          <td width="60" height="15" class="ewTableHeader"> <div align="center"><?php echo $iva_15_porcen     ?></div></td>
          <td class="ewTableHeader"><div align="center"><?php echo $iva_21_porcen     ?></div></td>
        </tr>
        
		<tr>
          <td bgcolor="#82BADD"><input name="lote" type="text" id="lote" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="65" />		  </td>
         <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision" type="text" id="comision" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision" type="text" id="comision" size="3"  readonly="" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe" type="text" id="importe" onBlur="MM_validateForm('importe','','NisNum');return document.MM_returnValue" size="10"   /></td>
          <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)"/></td>
		<input name="secuencia0" type="hidden" class="phpmaker" id="secuencia0" size="65" />
          
		 </tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote1" type="text" id="lote1" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision1" type="text" id="comision1" size="3" /></td> <?php } else { ?>
	<td bgcolor="#82BADD"><input name="comision1" type="text" id="comision1" size="3" readonly /></td> <?php } ?>
    <td bgcolor="#82BADD"><input name="importe1" type="text" id="importe1" onBlur="MM_validateForm('importe1','','NisNum');return document.MM_returnValue" size="10"  /></td>
	<td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto1" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)"/></td>
	<td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto1" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)" /></td>
    
      <input name="secuencia1" type="hidden" class="phpmaker" id="secuencia1" size="65" />
	    </tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote2" type="text" id="lote2" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision2" type="text" id="comision2" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision2" type="text" id="comision2" size="3" readonly /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe2" type="text" id="importe2" onBlur="MM_validateForm('importe2','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto2" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto2" value="<?php  echo $iva_21_porcen     ?>" onClick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia2" type="hidden" class="phpmaker" id="secuencia2" size="65" /> 
		</tr>
		  <tr>
          <td bgcolor="#82BADD"><input name="lote3" type="text" id="lote3" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision3" type="text" id="comision3" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision3" type="text" id="comision3" size="3"  readonly=""/></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe3" type="text" id="importe3" onBlur="MM_validateForm('importe3','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto3" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto3" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
           <input name="secuencia3" type="hidden" class="phpmaker" id="secuencia3" size="65" />
		</tr>
		  <tr>
          <td bgcolor="#82BADD"><input name="lote4" type="text" id="lote4" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="65" /></td>
           <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision4" type="text" id="comision4" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision4" type="text" id="comision4" size="3" readonly /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe4" type="text" id="importe4" onBlur="MM_validateForm('importe4','','NisNum');return document.MM_returnValue" size="10" /></td>
		   <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto4" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto4" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
          <input name="secuencia4" type="hidden" class="phpmaker" id="secuencia4" size="65" />
		</tr>
		  <tr>
          <td bgcolor="#82BADD"><input name="lote5" type="text" id="lote5" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision5" type="text" id="comision5" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision5" type="text" id="comision5" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe5" type="text" id="importe5" onBlur="MM_validateForm('importe5','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto5" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto5" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
          <input name="secuencia5" type="hidden" class="phpmaker" id="secuencia5" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote6" type="text" id="lote6" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="65" /></td>
          <?php if($nivel=='9') { ?>   <td bgcolor="#82BADD"><input name="comision6" type="text" id="comision6" size="3" /></td> <?php } else { ?>
           <td bgcolor="#82BADD"><input name="comision6" type="text" id="importe62" size="3" /></td> <?php } ?>
		   <td bgcolor="#82BADD"><input name="importe6" type="text" id="importe6" onBlur="MM_validateForm('importe6','','NisNum');return document.MM_returnValue" size="10" /></td>
           <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto6" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto6" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia6" type="hidden" class="phpmaker" id="secuencia6" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote7" type="text" id="lote7" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision7" type="text" id="comision7" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision7" type="text" id="comision7" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe7" type="text" id="importe7" onBlur="MM_validateForm('importe7','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto7" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto7" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia7" type="hidden" class="phpmaker" id="secuencia7" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote8" type="text" id="lote8" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision8" type="text" id="comision8" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision8" type="text" id="comision8" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe8" type="text" id="importe8" onBlur="MM_validateForm('importe8','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto8" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto8" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia8" type="hidden" class="phpmaker" id="secuencia8" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote9" type="text" id="lote9" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision9" type="text" id="comision9" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision9" type="text" id="comision9" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe9" type="text" id="importe9" onBlur="MM_validateForm('importe9','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto9" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto9" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
           <input name="secuencia9" type="hidden" class="phpmaker" id="secuencia9" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote10" type="text" id="lote10" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision10" type="text" id="comision10" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision10" type="text" id="comision10" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe10" type="text" id="importe10" onBlur="MM_validateForm('importe10','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto10" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto10" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
          <input name="secuencia10" type="hidden" class="phpmaker" id="secuencia10" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote11" type="text" id="lote11" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision11" type="text" id="comision11" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision11" type="text" id="comision11" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe11" type="text" id="importe11" onBlur="MM_validateForm('importe11','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto11" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto11" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
          
           <input name="secuencia11" type="hidden" class="phpmaker" id="secuencia11" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote12" type="text" id="lote12" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision12" type="text" id="comision12" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision12" type="text" id="comision12" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe12" type="text" id="importe12" onBlur="MM_validateForm('importe12','','NisNum');return document.MM_returnValue" size="10" /></td>
		  <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto12" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto12" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
        
           <input name="secuencia12" type="hidden" class="phpmaker" id="secuencia12" size="65" />
		</tr>
		 <tr>
          <td bgcolor="#82BADD"><input name="lote13" type="text" id="lote13" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision13" type="text" id="comision13" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision13" type="text" id="comision13" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe13" type="text" id="importe13" onBlur="MM_validateForm('importe13','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto13" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto13" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
                     <input name="secuencia13" type="hidden" class="phpmaker" id="secuencia13" size="65" />
		</tr> <tr>
          <td bgcolor="#82BADD"><input name="lote14" type="text" id="lote14" size="5" /></td>
          <td bgcolor="#82BADD"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="65" /></td>
       <?php if($nivel=='9') { ?> <td bgcolor="#82BADD"><input name="comision14" type="text" id="comision14" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#82BADD"><input name="comision14" type="text" id="comision14" size="3" /></td> <?php } ?>
          <td bgcolor="#82BADD"><input name="importe14" type="text" id="importe14" onBlur="MM_validateForm('importe14','','NisNum');return document.MM_returnValue" size="10" /></td>
		 <td bgcolor="#82BADD" width="24" align="center"><input type="radio" name="impuesto14" value="<?php  echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#82BADD" width="26" align="center"><input type="radio" name="impuesto14" value="<?php  echo $iva_21_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
         
          <input name="secuencia14" type="hidden" class="phpmaker" id="secuencia14" size="65" />
		</tr>
      </table>      </td>
    </tr>
    <tr bgcolor="#82BADD"><td bgcolor="#82BADD"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#82BADD"><tr>
      <td width="71" height="20" valign="top" class="ewTableHeader">&nbsp;Leyenda</td>
      <td width="280"  valign="top"><textarea name="leyenda" cols="55" rows="4"></textarea></td>
    </tr>
	<tr><td class="ewTableHeader">&nbsp;Imprimir </td><td><input type="checkbox" name="imprime" value="1" />
</td>
	</tr>
	 </table></td>
      <td width="281" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#82BADD">
        <tr>
          <td width="48%">&nbsp;<span class="ewTableHeader">Resolucion</span></td>
          <td width="52%"><input name="tieneresol" type="checkbox" id="tieneresol" value="si" onClick="resol(this.form)"   /></td>
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
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">R. G. 3337 </div></td>
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
			<input type="submit" value="Enviar a AFIP" id="evento_eliminar" name="evento_eliminar" />
			<input type="reset" value="Limpiar Formulario">
          </div></td>
          <td><div align="center">
            
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
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
<?php
mysql_free_result($tipo_comprobante);
mysql_free_result($cliente);
?>
