<?php function mysqli_result($res,$row=0,$col=0){ 
    $numrows = mysqli_num_rows($res); 
    if ($numrows && $row <= ($numrows-1) && $row >=0){
        mysqli_data_seek($res,$row);
        $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
        if (isset($resrow[$col])){
            return $resrow[$col];
        }
    }
    return false;
}

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = addslashes($theValue);
  switch ($theType) {
	case "text":
	  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	  break;    
	case "long":
	case "int":
	  $theValue = ($theValue != "") ? intval($theValue) : "NULL";
	  break;
	case "double":
	  $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
	  break;
	case "date":
	  $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
	  break;
	case "defined":
	  $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
	  break;
  }
  return $theValue;
}

 function ValidarDuplicados($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$cuit     = $rsnew['cuit'];  
		$tipoent  = $rsnew['tipoent'];

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.cuit = $cuit AND entidades.tipoent = $tipoent");
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$activo = 0;                        
	if (mysqli_num_rows($Result) > 0) {  

			 $activo = 1;
	}
	if ($activo == 1) {

		// ACA LE MANDO EL MENSAJE DE ERROR Y NO DEBE GRABAR LA ENTIDAD    
		   return 0;
	}
	return 1;                   
} 

function CopiarFecha($rsnew) {              
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$fecha = $rsnew['fecval'];  
		return $fecha;
}

function EntiDir($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$dir = $row_Result['calle'];
	return $dir;
}          

function EntiDnro($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$dnro = $row_Result['numero'];
	return $dnro;
}          

function EntiPisodto($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$pisodto = $row_Result['pisodto'];
	return $pisodto;
}   

function EntiCodpost($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$codpost = $row_Result['codpost'];
	return $codpost;
}           

function EntiCodpais($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$codpais = $row_Result['codpais'];
	return $codpais;
}     

function EntiCodprov($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$codprov = $row_Result['codprov'];
	return $codprov;
}     

function EntiCodloc($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$codloc = $row_Result['codloc'];
	return $codloc;
}     

function EntiTipoiva($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$tipoiva = $row_Result['tipoiva'];
	return $tipoiva;
}

function EntiCuit($rsnew) {        
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$enti    = $rsnew['cliente'];  

		// LEO LA TABLA ENTIDADES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM entidades WHERE entidades.codnum = $enti"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$cuit = $row_Result['cuit'];
	return $cuit;
}          

function BuscoUltimoSerie($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$serie    = $rsnew['serie'];  

		// LEO LA TABLA SERIES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM series WHERE series.codnum = $serie"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$nroact = $row_Result['nroact'];
	return $nroact + 1;
}
function CambioUltimoSerie($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$serie    = $rsnew['serie'];  

		// LEO LA TABLA SERIES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM series WHERE series.codnum = $serie"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$nroact = $row_Result['nroact'] + 1;
	// ACTUALIZO EL ULTIMO NRO DE LA serie
	$query2 = sprintf("UPDATE SERIES SET nroact = $nroact WHERE series.codnum = $serie");
	$Result2 = mysqli_query($amercado, $query2) or die(mysqli_error($amercado));

}
function CambioClienteRemate($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$ncomp    = $rsnew['id'];
		$cliente  = $rsnew['vendedor'];

		// LEO LA TABLA SERIES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	
	// ACTUALIZO EL CLIENTE DEL REMATE
	$query2 = sprintf("UPDATE REMATES SET codcli = $cliente WHERE remates.ncomp = $ncomp");
	$Result2 = mysqli_query($amercado, $query2) or die("NO PUEDO REGRABAR EL REMATE ".$query2." - ");

}
function BuscoFechaRemate($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$remate    = $rsnew['remate'];  

		// LEO LA TABLA SERIES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM remates WHERE remates.ncomp = $remate"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$fecharem = $row_Result['fecreal'];
	return $fecharem;
}
function FechaTopeRemate($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$remate    = $rsnew['remate'];  

		// LEO LA TABLA SERIES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM remates WHERE remates.ncomp = $remate"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$fecharem = $row_Result['fecreal'];
	$month = date('m');
	$year = date('Y');
	$ult_dia = date("d",(mktime(0,0,0,$month+1,1,$year)-1));
	//echo "A VERRRRR    = ".$ult_dia."    ";
	$ult_dia_mes = date('Y-m')."-".$ult_dia;
	$FechaTope = $ult_dia_mes; 
	return $FechaTope;
}
function ArmoNrodoc($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$serie    = $rsnew['serie'];
		$numero   = $rsnew['ncomp'];

		// LEO LA TABLA SERIES
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM series WHERE series.codnum = $serie"); 
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	$row_Result = mysqli_fetch_assoc($Result);   
	$mascara = $row_Result['mascara'];
	//ARMO EL NRODOC CON LA mascara Y EL NUMERO
	 
	 if ($numero <10) {
	 	$mascara=$mascara."-"."0000000".$numero ;
	 }

	 if ($numero >9 && $numero <=99) {
	 	$mascara=$mascara."-"."000000".$numero;
	 }

	 if ($numero >99 && $numero <=999) {
	 	$mascara=$mascara."-"."00000".$numero;
	 }
	 if ($numero >999 && $numero <=9999) {
	 	$mascara=$mascara."-"."0000".$numero;
	 }
	 if ($numero >9999 && $numero <99999) {
	 	$mascara=$mascara."-"."000".$numero;
	 }
	 return $mascara;
}
function LiqCalcTotRemate($rsnew) {
	$totremate = 0.00;
return $totremate;
}
function LiqCalcTotNeto105($rsnew) {
	$totneto105 = 0.00;
return $totneto105;
}
function LiqCalcTotNeto21($rsnew) {
	$totneto21 = 0.00;
return $totneto21;
}
function LiqCalcTotIva105($rsnew) {
	$totiva105 = 0.00;
return $totiva105;
}
function LiqCalcTotIva21($rsnew) {
	$totiva21 = 0.00;
return $totiva21;
}
function LiqCalcSubTot105($rsnew) {
	$subtot105 = 0.00;
return $subtot105;
}
function LiqCalcSubTot21($rsnew) {
	$subtot21 = 0.00;
return $subtot21;
}
function LiqCalcTotAcuenta($rsnew) {
	$totacuenta = 0.00;
return $totacuenta;
}
function LiqCalcTotGastos($rsnew) {
	$totgastos = 0.00;
return $totgastos;
}
function LiqCalcTotVarios($rsnew) {
	$totvarios = 0.00;
return $totvarios;
}
function LiqCalcSaldoafav($rsnew) {
	$saldoafav = 0.00;
return $saldoafav;
}

function BuscoSecuenciaLotes($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";
		$remate = $rsnew['codrem'];
		// LEO LA TABLA LOTES
		$i = 0;
		
	
	// VERIFICO QUE NO EXISTA ESTA SECUENCIA POR SI BORRARON ALGUN LOTE
	$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($amercado, $database_amercado);
	$query = sprintf("SELECT * FROM lotes WHERE lotes.codrem = $remate order by codrem, secuencia");
	$Result = mysqli_query($amercado, $query) or die(mysqli_error($amercado));
	//$row_Result = mysqli_fetch_assoc($Result);
	
	while($row_Result = mysqli_fetch_array($Result)) {   
		$i = $row_Result['secuencia'] + 1;
		
	}
	
	
	return $i;
}

function BuscoNuevoRemate($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

				// BUSCO LOS CAMPOS NECESARIOS PARA VALIDAR
		$serie_remate    = 4;
		// LEO SERIES PARA SACAR EL ULTIMO NRO
		$amercado = mysqli_connect($hostname_amercado, $username_amercado, $password_amercado) or trigger_error(mysqli_error($amercado),E_USER_ERROR);
	mysqli_select_db($database_amercado, $amercado);
	$query_series = sprintf("SELECT * FROM series WHERE series.codnum = $serie_remate");
	$Result_series = mysqli_query($amercado, $query_series) or die(mysqli_error($amercado));
	$row_Result_series = mysqli_fetch_assoc($Result_series);   
	$remate = $row_Result_series['nroact'];
	$remate += 1;
	return $remate;
}
function SeparoNroLote($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		$codintlote   = $rsnew['codintlote'];
		$partes = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $codintlote);
		$numero = $partes[1];
		$letra  = $partes[2];
	return $numero;
		
		
}
function SeparoLetraLote($rsnew) {
		$hostname_amercado = isset($_SERVER['DB_HOSTNAME']) ? $_SERVER['DB_HOSTNAME'] : "localhost";
		$database_amercado = isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : "amremate";
		$username_amercado = isset($_SERVER['DB_USER']) ? $_SERVER['DB_USER'] : "root";
		$password_amercado = isset($_SERVER['DB_PASSWORD']) ? $_SERVER['DB_PASSWORD'] : "";

		$codintlote   = $rsnew['codintlote'];
		$partes = preg_split("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $codintlote);
		$numero = $partes[1];
		$letra  = $partes[2];
	return $letra;
		
		
}

?>