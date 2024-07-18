<?php



// datos a pedir gestionar en el .php

    mysqli_select_db($amercado,$database_amercado);
	$query_cliente2 = sprintf("SELECT * FROM entidades WHERE razsoc = %s",GetSQLValueString($_POST['cliente'],"text"));
	$cliente2 = mysqli_query($amercado,$query_cliente2) or die(mysqli_error($amercado));
	$row_cliente2 = mysqli_fetch_assoc($cliente2);
	
	$cod_cliente = $row_cliente2['codnum'];
    $Concepto = 3; //Productos y Servicios
    $DocTipo =  80; //CUIT
    $cuit_enti = $row_cliente2['cuit'];
	if (isset($cuit_enti)) {
		$cuit_enti2 = str_replace("-","",$cuit_enti);
	}

	$DocNro = $cuit_enti2;
	$direcc_cliente = $row_cliente2['calle'].$row_cliente2['numero'];
	$TipoIVA_cliente = $row_cliente2['tipoiva'];
	$codpost_cliente = $row_cliente2['codpost'];
	$codprov_cliente = $row_cliente2['codprov'];
	$mailcont_cliente = $row_cliente2['mailcont'];


    // datos para factura

    $ahora = new DateTime();
	$fechaEmision = $ahora->format('Y-m-d\TH:i:s.v');
	$clienteRS = $_POST['cliente'];
	$descripcion = $_POST['descripcion'];
	$importeConcepto = $_POST['importe'];
	$ivaConcepto = $_POST['tipoiva'];
	$totNetoItem = $_POST['totneto21_1'];
	$tazaIvaTotal = $_POST['tot_general_1'];
	$tazaIva = $_POST['totiva21_1'];

    //
    // $mascara1 HAY QUE PEDIR NUEVAMENTE PORQUE SE SOBREESCRIBE ($mascara1  = $row_mascara['mascara'];)
    // $num_fac ya lo tenemos en todos los archivos
    // 
    // Distribuir bien el tema de los importes y no equivocarse con precio unitario, en WafcautxconcA.php esta distribuido correctamente para un solo item
    //

// array para enviar

$parametrosJson =   ["{\"Comprobante_Tipo\": \"FC\",
    \"Comprobante_Letra\": \"A\",
    \"Comprobante_PtoVenta\": \"$mascara1\",
    \"Comprobante_Numero\": \"$num_fac\",
    \"Comprobante_LoteHasta\": \" \",
    \"Comprobante_FechaEmision\": \"$fechaEmision\",
    \"Cliente_Codigo\": \"$cod_cliente\",
    \"Cliente_SitIVA\": \"$clienteTipoIVA\",
    \"Cliente_TipoDocumento\": 1,
    \"Cliente_NroDocumento\": \"$DocNro\",
    \"Cliente_Direccion\": \"$direcc_cliente\",
    \"Cliente_Localidad\": \"$loc_cliente\",
    \"Cliente_CodigoPostal\": \"$codpost_cliente\",
    \"Cliente_Provincia\": \"$codprov_cliente\",
    \"Cliente_Email\": \"$mailcont_cliente\",
    \"Cliente_CodigoClase\": \"\",
    \"Cliente_CodigoClase2\": \"\",
    \"Vendedor_Codigo\": \"0001\",  
    \"Comprobante_CondVenta\": \"00\",
    \"Comprobante_FechaVencimiento\": \"null\",
    \"Comprobante_ImporteTotal\": 121000.00,
    \"Comprobante_Moneda\": \"1\",
    \"Comprobante_TipoCambio\": \"UNI\",
    \"Comprobante_CotizacionCambio\": 1,
    \"Comprobante_ListaPrecios\": \"02\",

    \"Comprobante_Items\":
        [{
            \"Comprobante_Tipo\": \"FC\",
            \"Comprobante_Letra\": \"A\",
            \"Comprobante_PtoVenta\": \"00001\",
            \"Comprobante_Numero\": \"00000004\",
            \"Comprobante_LoteHasta\": \" \",
            \"Comprobante_FechaEmision\": \"2023-12-01T16:42:39.44\",
            \"Cliente_Codigo\": \"@@@#@@\",
            \"Item_Tipo\":\"A\",
            \"Item_CodigoArticulo\": \"29\",
            \"Item_CantidadUM1\": 1,
            \"Item_CantidadUM2\": 0,
            \"Item_DescripArticulo\": \"$descripcion\",
            \"Item_PrecioUnitario\": 100000.00,
            \"Item_TasaIVAInscrip\": 21,
            \"Item_TasaIVANoInscrip\": 0,
            \"Item_ImporteIVAInscrip\": 21000.00,
            \"Item_ImporteIVANoInscrip\": 0,
            \"Item_ImporteTotal\": 121000.00,
            \"Item_ImporteDescComercial\": 0,
            \"Item_ImporteDescFinanciero\": 0,
            \"Item_ImporteDescGeneral\": 0,
            \"Item_ImporteIVANoGravado\": 0,
            \"Item_TipoIVA\": \"1\",
            \"Item_ImporteDescPorLinea\": 0,
            \"Item_Deposito\": \"00\",
            \"Item_Partida\": \" \",
            \"Item_TasaDescPorItem\": 0,
            \"Item_Importe\": 0.00,
            \"Item_Proveedor_Codigo\": \"null\",
            \"Item_FechaEntrega\": \"null\",
        }],

        \"Comprobante_MediosPago\": null,
        \"Comprobante_DatosAdicionales\": null,
        \"Comprobante_RelacionComprobante\": null }",
        "N",
        "R"];


$response = $bejermanClient->ejecutar('VENTAS', 'IngresarComprobanteJSON', $parametrosJson);

// echo $response; TE AYUDA A COMPROBAR SI SE ENVIO. // deberia hacer un puntito de color o algo como para confirmarme el envio.