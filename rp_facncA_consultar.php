<?php
set_time_limit(0); // Para evitar el timeout
define('FPDF_FONTPATH','fpdf17/font/');
require('fpdf17/fpdf.php');
//require('fpdf17/i25.php');
require('numaletras.php');

//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

	// Leo los parametros del formulario anterior
	$CbteTipo = $_GET['ftcomp'];
	$PtoVta   = $_GET['fpvta'];
	$CbteNro  = $_GET['fncomp'];

	//error_reporting(E_ALL);
	//ini_set('display_errors','Yes');

	//Cargando archivo de configuracion
	include_once "FE_Pack_WSFE/config.php";
	include_once "FE_Pack_WSFE/library/functions.php";

	//Cargando modelos de conexion a WebService
	include_once MDL_PATH."AfipWsaa.php";
	include_once MDL_PATH."AfipWsfev1.php";


	//Datos correspondiente a la empresa que emite la factura
    //CUIT (Sin guiones)
    $empresaCuit  = '30718033612';
    //El alias debe estar mencionado en el nombre de los archivos de certificados y firmas digitales
    $empresaAlias = 'SubastasV8';


	//Obtener los datos de la factura que se desea generar
    //Elegir uno de los include como para tener diferentes tipos de factura
    //include "FE_Pack_WSFE/test/data/TestRegistrarFeMultiIVA_ejemplo_Factura_tipo_A.php";
    //include "data/TestRegistrarFe_ejemplo_Factura_tipo_B.php";


	//WebService que utilizara la autenticacion
	$webService   = 'wsfe';
	//Creando el objeto WSAA (Web Service de Autenticacion y Autorizacion)
	$wsaa = new AfipWsaa($webService,$empresaAlias);

	//Creando el TA (Ticket de acceso)
	if ($ta = $wsaa->loginCms())
	{
    	$token      = $ta['token'];
    	$sign       = $ta['sign'];
    	$expiration = $ta['expiration'];
    	$uniqueid   = $ta['uniqueid'];

    	//Conectando al WebService de Factura electronica (WsFev1)
    	$wsfe = new AfipWsfev1($empresaCuit,$token,$sign);
		$PtoVta = 2;
		$CompConsultado = $wsfe->FECompConsultar($CbteTipo,$CbteNro,$PtoVta);
    	echo "<h3>wsfe->FECompConsultar($CbteTipo,$CbteNro,$PtoVta)</h3>";
    	pr($CompConsultado); 
        
       // $concepto = $CompConsultado['Concepto'];
       // echo "CONCEPTO = ".$concepto;
		/*
        
Array
(
    [Concepto] => 3
    [DocTipo] => 80
    [DocNro] => 30714516155
    [CbteDesde] => 14250
    [CbteHasta] => 14250
    [CbteFch] => 20220321
    [ImpTotal] => 167343
    [ImpTotConc] => 0
    [ImpNeto] => 138300
    [ImpOpEx] => 0
    [ImpTrib] => 0
    [ImpIVA] => 29043
    [FchServDesde] => 20220321
    [FchServHasta] => 20220321
    [FchVtoPago] => 20220321
    [MonId] => PES
    [MonCotiz] => 1
    [Iva] => Array
        (
            [AlicIva] => Array
                (
                    [Id] => 5
                    [BaseImp] => 138300
                    [Importe] => 29043
                )

        )

    [Resultado] => A
    [CodAutorizacion] => 72127489672381
    [EmisionTipo] => CAE
    [FchVto] => 20220331
    [FchProceso] => 20220321133545
    [PtoVta] => 4
    [CbteTipo] => 1
)
*/	
	}
?>