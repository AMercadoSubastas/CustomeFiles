<?php require_once('Connections/amercado.php'); 
include_once "funcion_mysqli_result.php";
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  	$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$renglones = 0;
$primera_vez = 1;
$fechahoy = date("d-m-Y");
if (isset($_POST['renglon1']) && GetSQLValueString($_POST['renglon1'], "int")!="NULL") {
	// DESDE ACA ===================================================================================
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
    	// BUSCA el Numero del ULTIMO  RECIBO
	 	mysqli_select_db($amercado, $database_amercado);
  		$query_recibo = "SELECT nroact   FROM series WHERE codnum ='3' AND tipcomp  ='2'";
  		$sel_recibo =mysqli_query($amercado, $query_recibo) or die(mysqli_error($amercado));
  		$row_recibonum = mysqli_fetch_assoc($sel_recibo);
  		$recibo = $row_recibonum['nroact']+1;
    	// Actualiza el numero del ultimo recibo
		mysqli_select_db($amercado, $database_amercado);
		$actualiza1 = "UPDATE `series` SET nroact = '$recibo' WHERE codnum ='3' AND tipcomp='2'" ;	 
		$resultado=mysqli_query($amercado,	$actualiza1);	
	}
    
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA PRIMER FACTURA
		$tcomprel = GetSQLValueString($_POST['tcomprel'], "int");
		$serierel = GetSQLValueString($_POST['serierel'], "int");
		$ncomprel = GetSQLValueString($_POST['ncomprel'], "int");
		$doc1 = $_POST['doc1'];
		$netocbterel = $_POST['netocbterel'];
		$insertSQL = "INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '1' , '$tcomprel', '$serierel','$ncomprel','$netocbterel','$doc1')";
        $Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel == 115 || $tcomprel == 116 || $tcomprel == 117) {
  
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec = GetSQLValueString($_POST['tcomprel'], "int");
                $serierec = GetSQLValueString($_POST['serierel'], "int");
                $ncomprec = GetSQLValueString($_POST['ncomprel'], "int");
                $doc1 = $_POST['doc1'];
                $netocbterel = $_POST['netocbterel'];
                $fecha = $_POST['fecha_recibo1'];
	            $fecha = substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);
                $insertSQL2 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,'$doc1', '$tcomprec', '$serierec','$ncomprec','$fecha')";
                $agregocabremi = mysqli_query($amercado, $insertSQL2) or die("ERROR GRABANDO REMITO 1");
                //echo "SE GRABO EL REMITO 1? ".$insertSQL2."   ";
            
        }
 		// CAMBIO EL ESTADO DE LA PRIMER FACTURA DE PENDIENTE  a PAGADA
        mysqli_select_db($amercado, $database_amercado);
        $renglones = 1;
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel'], "int"),
                       GetSQLValueString($_POST['serierel'], "int"),
                       GetSQLValueString($_POST['ncomprel'], "int"));
        mysqli_select_db($amercado, $database_amercado);
        $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon2']) && GetSQLValueString($_POST['renglon2'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA SEGUNDA FACTURA
        $tcomprel2 = GetSQLValueString($_POST['tcomprel_2'], "int");
		$serierel2 = GetSQLValueString($_POST['serierel_2'], "int");
		$ncomprel2 = GetSQLValueString($_POST['ncomprel_2'], "int");
		$doc2 = $_POST['doc2'];
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '2' , %s, %s, %s, %s, '$doc2')",
                       GetSQLValueString($_POST['tcomprel_2'], "int"),
                       GetSQLValueString($_POST['serierel_2'], "int"),
                       GetSQLValueString($_POST['ncomprel_2'], "int"),
                       GetSQLValueString($_POST['netocbterel2'], "double"));
					 

 		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 2;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel2 == 51 || $tcomprel2 == 53) {
            
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec2 = GetSQLValueString($_POST['tcomprel_2'], "int");
                $serierec2 = GetSQLValueString($_POST['serierel_2'], "int");
                $ncomprec2 = GetSQLValueString($_POST['ncomprel_2'], "int");
                $doc2 = $_POST['doc2'];
                $netocbterel = $_POST['netocbterel2'];
                $insertSQL2 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,'$doc2', '$tcomprec2', '$serierec2','$ncomprec2','$fecha')";
                $agregocabremi = mysqli_query($amercado, $insertSQL2) or die("ERROR GRABANDO REMITO 2:  ".$insertSQL2);
                mysqli_select_db($amercado, $database_amercado);
                
            
        }
  		// CAMBIO EL ESTADO DE LA SEGUNDA FACTURA DE PENDIENTE  a PAGADA
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_2'], "int"),
                       GetSQLValueString($_POST['serierel_2'], "int"),
                       GetSQLValueString($_POST['ncomprel_2'], "int"));
        mysqli_select_db($amercado, $database_amercado);
        $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon3']) && GetSQLValueString($_POST['renglon3'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA TERCER FACTURA
        $tcomprel3 = GetSQLValueString($_POST['tcomprel_3'], "int");
		$serierel3 = GetSQLValueString($_POST['serierel_3'], "int");
		$ncomprel3 = GetSQLValueString($_POST['ncomprel_3'], "int");
		$doc3 = $_POST['doc3'];
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '3' , %s, %s, %s, %s, '$doc3' )",
                       GetSQLValueString($_POST['tcomprel_3'], "int"),
                       GetSQLValueString($_POST['serierel_3'], "int"),
                       GetSQLValueString($_POST['ncomprel_3'], "int"),
                       GetSQLValueString($_POST['netocbterel3'], "double"));
					 

 		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 3;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel3 == 51 || $tcomprel3 == 53) {
            
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec3 = GetSQLValueString($_POST['tcomprel_3'], "int");
                $serierec3 = GetSQLValueString($_POST['serierel_3'], "int");
                $ncomprec3 = GetSQLValueString($_POST['ncomprel_3'], "int");
                $doc3 = $_POST['doc3'];
                $netocbterel = $_POST['netocbterel3'];
                $insertSQL3 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,$doc3, '$tcomprec3', '$serierec3','$ncomprec3','$fecha')";
                //$agregocabremi = mysqli_query($amercado, $insertSQL3)  or die("ERROR GRABANDO REMITO 3".$insertSQL3);
                mysqli_select_db($amercado, $database_amercado);
                
            
        }
		// CAMBIO EL ESTADO DE LA TERCER FACTURA DE PENDIENTE  a PAGADA

	 	$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_3'], "int"),
                       GetSQLValueString($_POST['serierel_3'], "int"),
                       GetSQLValueString($_POST['ncomprel_3'], "int"));
		mysqli_select_db($amercado, $database_amercado);
        $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon4']) && GetSQLValueString($_POST['renglon4'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
        $tcomprel4 = GetSQLValueString($_POST['tcomprel_4'], "int");
		$serierel4 = GetSQLValueString($_POST['serierel_4'], "int");
		$ncomprel4 = GetSQLValueString($_POST['ncomprel_4'], "int");
 		$doc4 = $_POST['doc4'];
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA CUARTA FACTURA
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '4' , %s, %s, %s, %s, '$doc4')",
                       GetSQLValueString($_POST['tcomprel_4'], "int"),
                       GetSQLValueString($_POST['serierel_4'], "int"),
                       GetSQLValueString($_POST['ncomprel_4'], "int"),
                       GetSQLValueString($_POST['netocbterel4'], "double"));
					 

		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 4;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel4 == 51 || $tcomprel4 == 53) {
 
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec4 = GetSQLValueString($_POST['tcomprel_4'], "int");
                $serierec4 = GetSQLValueString($_POST['serierel_4'], "int");
                $ncomprec4 = GetSQLValueString($_POST['ncomprel_4'], "int");
                $doc4 = $_POST['doc4'];
                $netocbterel = $_POST['netocbterel4'];
                $insertSQL4 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,$doc4, '2', '3','$ncomprec4','$fecha')";
                //$agregocabremi = mysqli_query($amercado, $insertSQL4) or die("ERROR GRABANDO REMITO 4:  ".$insertSQL4);
                mysqli_select_db($amercado, $database_amercado);
                
            
        }
        
   		// CAMBIO EL ESTADO DE LA CUARTA FACTURA DE PENDIENTE  a PAGADA
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_4'], "int"),
                       GetSQLValueString($_POST['serierel_4'], "int"),
                       GetSQLValueString($_POST['ncomprel_4'], "int"));
        mysqli_select_db($amercado, $database_amercado);
        $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon5']) && GetSQLValueString($_POST['renglon5'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
        $tcomprel5 = GetSQLValueString($_POST['tcomprel_5'], "int");
		$serierel5 = GetSQLValueString($_POST['serierel_5'], "int");
		$ncomprel5 = GetSQLValueString($_POST['ncomprel_5'], "int");
 		$doc5 = $_POST['doc5'];
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA QUINTA FACTURA
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '5' , %s, %s, %s, %s, '$doc5')",
                       GetSQLValueString($_POST['tcomprel_5'], "int"),
                       GetSQLValueString($_POST['serierel_5'], "int"),
                       GetSQLValueString($_POST['ncomprel_5'], "int"),
                       GetSQLValueString($_POST['netocbterel5'], "double"));
					 

 		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 5;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel5 == 51 || $tcomprel5 == 53) {
    
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec5 = GetSQLValueString($_POST['tcomprel_5'], "int");
                $serierec5 = GetSQLValueString($_POST['serierel_5'], "int");
                $ncomprec5 = GetSQLValueString($_POST['ncomprel_5'], "int");
                $doc5 = $_POST['doc5'];
                $netocbterel = $_POST['netocbterel5'];
                $insertSQL5 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,$doc5, '2', '3','$ncomprec5','$fecha')";
                //$agregocabremi = mysqli_query($amercado, $insertSQL5) or die("ERROR GRABANDO REMITO 5:  ".$insertSQL5);
                mysqli_select_db($amercado, $database_amercado);
                
           
        }
 

   		// CAMBIO EL ESTADO DE LA QUINTA FACTURA DE PENDIENTE  a PAGADA
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_5'], "int"),
                       GetSQLValueString($_POST['serierel_5'], "int"),
                       GetSQLValueString($_POST['ncomprel_5'], "int"));
                      mysqli_select_db($amercado, $database_amercado);
                      $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon6']) && GetSQLValueString($_POST['renglon6'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA SEXTA FACTURA
        $tcomprel6 = GetSQLValueString($_POST['tcomprel_6'], "int");
		$serierel6 = GetSQLValueString($_POST['serierel_6'], "int");
		$ncomprel6 = GetSQLValueString($_POST['ncomprel_6'], "int");
		$doc6 = $_POST['doc6'];
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '6' , %s, %s, %s, %s, '$doc6' )",
                       GetSQLValueString($_POST['tcomprel_6'], "int"),
                       GetSQLValueString($_POST['serierel_6'], "int"),
                       GetSQLValueString($_POST['ncomprel_6'], "int"),
                       GetSQLValueString($_POST['netocbterel6'], "double"));
					 

 		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 6;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel6 == 51 || $tcomprel6 == 53) {
    
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec6 = GetSQLValueString($_POST['tcomprel_6'], "int");
                $serierec6 = GetSQLValueString($_POST['serierel_6'], "int");
                $ncomprec6 = GetSQLValueString($_POST['ncomprel_6'], "int");
                $doc6 = $_POST['doc6'];
                $netocbterel = $_POST['netocbterel6'];
                $insertSQL6 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,$doc6, '2', '3','$ncomprec6','$fecha')";
                //$agregocabremi = mysqli_query($amercado, $insertSQL6) or die("ERROR GRABANDO REMITO 6:  ".$insertSQL6);
                mysqli_select_db($amercado, $database_amercado);
                
        }
   		// CAMBIO EL ESTADO DE LA SEXTA FACTURA DE PENDIENTE  a PAGADA
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_6'], "int"),
                       GetSQLValueString($_POST['serierel_6'], "int"),
                       GetSQLValueString($_POST['ncomprel_6'], "int"));
        mysqli_select_db($amercado, $database_amercado);
        $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon7']) && GetSQLValueString($_POST['renglon7'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA SEPTIMA FACTURA
        $tcomprel7 = GetSQLValueString($_POST['tcomprel_7'], "int");
		$serierel7 = GetSQLValueString($_POST['serierel_7'], "int");
		$ncomprel7 = GetSQLValueString($_POST['ncomprel_7'], "int");
		$doc7 = $_POST['doc7'];
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '7' , %s, %s, %s, %s,'$doc7' )",
                       GetSQLValueString($_POST['tcomprel_7'], "int"),
                       GetSQLValueString($_POST['serierel_7'], "int"),
                       GetSQLValueString($_POST['ncomprel_7'], "int"),
                       GetSQLValueString($_POST['netocbterel7'], "double"));
					 

 		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 7;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel7 == 51 || $tcomprel7 == 53) {
    
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec7 = GetSQLValueString($_POST['tcomprel_7'], "int");
                $serierec7 = GetSQLValueString($_POST['serierel_7'], "int");
                $ncomprec7 = GetSQLValueString($_POST['ncomprel_7'], "int");
                $doc7 = $_POST['doc7'];
                $netocbterel = $_POST['netocbterel7'];
                $insertSQL7 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,$doc7, '2', '3','$ncomprec7','$fecha')";
                //$agregocabremi = mysqli_query($amercado, $insertSQL2) or die("ERROR GRABANDO REMITO 7:  ".$insertSQL7);
                mysqli_select_db($amercado, $database_amercado);
                
            
        }
   		// CAMBIO EL ESTADO DE LA  SEPTIMA FACTURA DE PENDIENTE  a PAGADA
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_7'], "int"),
                       GetSQLValueString($_POST['serierel_7'], "int"),
                       GetSQLValueString($_POST['ncomprel_7'], "int"));
        mysqli_select_db($amercado, $database_amercado);
        $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon8']) && GetSQLValueString($_POST['renglon8'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
        $tcomprel8 = GetSQLValueString($_POST['tcomprel_8'], "int");
		$serierel8 = GetSQLValueString($_POST['serierel_8'], "int");
		$ncomprel8 = GetSQLValueString($_POST['ncomprel_8'], "int");
		$doc8 = $_POST['doc8'];
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA OCTAVA FACTURA
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '8' , %s, %s, %s, %s, '$doc8')",
                       GetSQLValueString($_POST['tcomprel_8'], "int"),
                       GetSQLValueString($_POST['serierel_8'], "int"),
                       GetSQLValueString($_POST['ncomprel_8'], "int"),
                       GetSQLValueString($_POST['netocbterel8'], "double"));
					 

 		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 8;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel8 == 51 || $tcomprel8 == 53) {
  
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec8 = GetSQLValueString($_POST['tcomprel_8'], "int");
                $serierec8 = GetSQLValueString($_POST['serierel_8'], "int");
                $ncomprec8 = GetSQLValueString($_POST['ncomprel_8'], "int");
                $doc8 = $_POST['doc8'];
                $netocbterel = $_POST['netocbterel8'];
                $insertSQL8 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,$doc8, '2', '3','$ncomprec8','$fecha')";
                //$agregocabremi = mysqli_query($amercado, $insertSQL8) or die("ERROR GRABANDO REMITO 8:  ".$insertSQL8);
                mysqli_select_db($amercado, $database_amercado);
                
           
        }
   		// CAMBIO EL ESTADO DE LA  OCTAVA FACTURA DE PENDIENTE  a PAGADA
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_8'], "int"),
                       GetSQLValueString($_POST['serierel_8'], "int"),
                       GetSQLValueString($_POST['ncomprel_8'], "int"));
        mysqli_select_db($amercado, $database_amercado);
        $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon9']) && GetSQLValueString($_POST['renglon9'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
        $tcomprel9 = GetSQLValueString($_POST['tcomprel_9'], "int");
		$serierel9 = GetSQLValueString($_POST['serierel_9'], "int");
		$ncomprel9 = GetSQLValueString($_POST['ncomprel_9'], "int");
		$doc9 = $_POST['doc9'];
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA NOVENA FACTURA
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '9' , %s, %s, %s, %s, '$doc9')",
                       GetSQLValueString($_POST['tcomprel_9'], "int"),
                       GetSQLValueString($_POST['serierel_9'], "int"),
                       GetSQLValueString($_POST['ncomprel_9'], "int"),
                       GetSQLValueString($_POST['netocbterel9'], "double"));
					 

 		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 9;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel9 == 51 || $tcomprel9 == 53) {
     
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec9 = GetSQLValueString($_POST['tcomprel_9'], "int");
                $serierec9 = GetSQLValueString($_POST['serierel_9'], "int");
                $ncomprec9 = GetSQLValueString($_POST['ncomprel_9'], "int");
                $doc9 = $_POST['doc9'];
                $netocbterel = $_POST['netocbterel9'];
                $insertSQL9 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,$doc9, '2', '3','$ncomprec9','$fecha')";
                //$agregocabremi = mysqli_query($amercado, $insertSQL9) or die("ERROR GRABANDO REMITO 9:  ".$insertSQL9);
                mysqli_select_db($amercado, $database_amercado);
                
           
        }
   		// CAMBIO EL ESTADO DE LA  NOVENA FACTURA DE PENDIENTE  a PAGADA
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_9'], "int"),
                       GetSQLValueString($_POST['serierel_9'], "int"),
                       GetSQLValueString($_POST['ncomprel_9'], "int"));
                      mysqli_select_db($amercado, $database_amercado);
                      $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if (isset($_POST['renglon10']) && GetSQLValueString($_POST['renglon10'], "int")!="NULL") {
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo")) {
		// INSERTO LOS DETALLES DE LA FACTURA EN EL RECIBO DE LA SEXTA FACTURA
        $tcomprel10 = GetSQLValueString($_POST['tcomprel_10'], "int");
		$serierel10 = GetSQLValueString($_POST['serierel_10'], "int");
		$ncomprel10 = GetSQLValueString($_POST['ncomprel_10'], "int");
		$doc10 = $_POST['doc10'];
		$insertSQL = sprintf("INSERT INTO detrecibo (tcomp, serie, ncomp, nreng , tcomprel, serierel , ncomprel , netocbterel, nrodoc) VALUES ('2', '3', '$recibo', '10' , %s, %s, %s, %s, '$doc10')",
                       GetSQLValueString($_POST['tcomprel_10'], "int"),
                       GetSQLValueString($_POST['serierel_10'], "int"),
                       GetSQLValueString($_POST['ncomprel_10'], "int"),
                       GetSQLValueString($_POST['netocbterel10'], "double"));
					 

 		mysqli_select_db($amercado, $database_amercado);
  		$renglones = 10;
 		$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
        if ($tcomprel10 == 51 || $tcomprel10 == 53) {
  
                // BUSCA el Numero del ULTIMO  REMITO
                mysqli_select_db($amercado, $database_amercado);
                $query_remito = "SELECT nroact   FROM series WHERE codnum ='28' AND tipcomp  ='50'";
                $sel_remito =mysqli_query($amercado, $query_remito) or die(mysqli_error($amercado));
                $row_remitonum = mysqli_fetch_assoc($sel_remito);
                $remito = $row_remitonum['nroact']+1;
                // Actualiza el numero del ultimo recibo
                mysqli_select_db($amercado, $database_amercado);
                $actualiza1 = "UPDATE `series` SET nroact = '$remito' WHERE codnum ='28' AND tipcomp='50'" ;				 
                $resultado=mysqli_query($amercado,	$actualiza1);	
            
                // INSERTO LOS DETALLES DE LA FACTURA EN EL REMITO DE LA PRIMER FACTURA
                $tcomprec10 = GetSQLValueString($_POST['tcomprel_10'], "int");
                $serierec10 = GetSQLValueString($_POST['serierel_10'], "int");
                $ncomprec10 = GetSQLValueString($_POST['ncomprel_10'], "int");
                $doc10 = $_POST['doc10'];
                $netocbterel = $_POST['netocbterel'];
                $insertSQL10 = "INSERT INTO cabremi (tcomp, serie, ncomp, cantrengs , observaciones, tcomprel, serierel , ncomprel, fecharemi ) VALUES ('50', '28', '$remito', '1' ,$doc10, '2', '3','$ncomprec10','$fecha')";
                //$agregocabremi = mysqli_query($amercado, $insertSQL10) or die("ERROR GRABANDO REMITO 10:  ".$insertSQL10);
                mysqli_select_db($amercado, $database_amercado);
                
           
        }        
   		// CAMBIO EL ESTADO DE LA  SEXTA FACTURA DE PENDIENTE  a PAGADA
 		$updateSQL = sprintf("UPDATE cabfac SET estado = 'S' WHERE tcomp = %s AND serie = %s AND ncomp = %s",
                       GetSQLValueString($_POST['tcomprel_10'], "int"),
                       GetSQLValueString($_POST['serierel_10'], "int"),
                       GetSQLValueString($_POST['ncomprel_10'], "int"));
                      mysqli_select_db($amercado, $database_amercado);
                      $Result1 = mysqli_query($amercado, $updateSQL) or die(mysqli_error($amercado));
	}
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "recibo"))  {
	$cliente = $_POST['cliente'];
	$tot_general = $_POST['total_general'];
	$remate = $_POST['remate'];
	$fecha = $_POST['fecha_recibo1'];
	$fecha = substr($fecha,6,4)."-".substr($fecha,3,2)."-".substr($fecha,0,2);

  	$insertSQL = sprintf("INSERT INTO cabrecibo (tcomp, serie, ncomp, cantcbtes, fecha , cliente , imptot ,emitido ) VALUES ('2', '3', '$recibo', '$renglones' , '$fecha', '$cliente' ,  '$tot_general', '0')");
  	mysqli_select_db($amercado, $database_amercado);
  	$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
    $facnum = GetSQLValueString($_POST['num_factura'], "int");
	$tipcomp = GetSQLValueString($_POST['tcomp'], "int");
	$numserie = GetSQLValueString($_POST['serie'], "int");
	$insertGoTo = "medios_pago_recibos.php?ftcomp='2'&&fserie='3'&&fncomp=$recibo";
  	if (isset($_SERVER['QUERY_STRING'])) {
    	$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    	$insertGoTo .= $_SERVER['QUERY_STRING'];
  	}
  	//header(sprintf("Location: %s", "medios_pago_recibos.php?ftcomp=2&&fserie=3&&fncomp=$recibo&&total_general=$tot_general&&remate=$remate&&cliente=$cliente"));
	echo '<script>window.open("medios_pago_recibos.php?ftcomp=2&&fserie=3&&fncomp=' . $recibo . '&&total_general=' . $tot_general . '&&remate=' . $remate . '&&cliente=' . $cliente . '");</script>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
}
.Estilo2 {font-size: 14px;
background:  #0099FF;
color:#000000; }

.Estilo3 {font-size: 19px;
background:  #00CCFF;
color:#000000; }
.facturas
{
text-align:center;
font-size: 19px;
background:  #00CCFF;
color:#000000; 
 
 
}

.Estilo1 {	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 12px;
}
</style>

<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<script language="javascript">
function cambia_fecha(form)
{ 
	var fecha = recibo_cab.fecha_recibo1.value;
	var ano = fecha.substring(6,10);
	var mes = fecha.substring(3,5);
	var dia = fecha.substring(0,2);
	var fecha1 = ano+"-"+mes+"-"+dia ;
	recibo_cab.fecha_recibo.value = fecha1;
}
</script>
</head>
<body>
<?php
$cliente = $_POST['cli'];
$codnum  = $_POST['cod_num'];
$codigo  = explode (",",$codnum);

mysqli_select_db($amercado, $database_amercado);
$query_recibo = "SELECT nroact   FROM series WHERE series.codnum ='3' AND series.tipcomp  ='2'";
$sel_recibo =mysqli_query($amercado, $query_recibo) or die(mysqli_error($amercado));
$row_recibonum = mysqli_fetch_assoc($sel_recibo);
$recibo = $row_recibonum['nroact']+1;

$query_cliente = "SELECT entidades.razsoc , entidades.calle , entidades.numero ,  entidades.codloc , entidades.codprov FROM entidades WHERE codnum='$cliente'";
$selec_cliente = mysqli_query($amercado, $query_cliente) or die(mysqli_error($amercado));
$row_cliente = mysqli_fetch_assoc($selec_cliente);
$localidad = $row_cliente['codloc'];
$provincia = $row_cliente['codprov'];

$query_localidad = "SELECT localidades.descripcion FROM localidades WHERE localidades.codnum='$localidad' AND localidades.codprov='$provincia'";
$localidad = mysqli_query($amercado, $query_localidad) or die(mysqli_error($amercado));
$row_loc = mysqli_fetch_assoc($localidad);

$query_prov = "SELECT provincias.descripcion FROM provincias WHERE provincias.codnum='$provincia'";
$provincia = mysqli_query($amercado, $query_prov) or die(mysqli_error($amercado));
$row_prov = mysqli_fetch_assoc($provincia);

?>
 <table width="640" border="1" cellspacing="1" cellpadding="1"  bgcolor="#ECE9D8" >
<form id="recibo_cab" name="recibo" method="POST" action="<?php echo $editFormAction; ?>"> 
   
    <tr  bgcolor="#00CCFF">
      <td class="Estilo2" >Fecha</td><input name="fecha" type="hidden" size="12" />
      <td class="Estilo3"><input name="fecha_recibo1" type="text" size="12"  value="<?php echo date('d-m-Y') ;?>"  onblur="cambia_fecha(this.form)" />
      <a href="javascript:showCal('Calendar16')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
    </tr>
    <input type="hidden" name="cliente"  value="<?php echo $cliente ?>" />
	<input type="hidden" name="tcomp"  value="2" />
	<input type="hidden" name="serie"  value="3" />
	<input type="hidden" name="cantcbtes"  value="<?php echo sizeof($codigo) ?>" />
     <tr bgcolor="#003366">
      <td class="Estilo2">Nombre</td>
      <td><input type="text" name="nombre"  value="<?php echo $row_cliente['razsoc'] ?>"/></td>
    </tr>
    <tr bgcolor="#003366">
      <td class="Estilo2">Direccion</td>
      <td><input name="direccion" type="text" value="<?php echo $row_cliente['calle']."  ".$row_cliente['numero'] ?>" size="50"/></td>
    </tr>
     <tr bgcolor="#003366">
      <td class="Estilo2">Localidad</td>
      <td><input type="text" name="localidad" value="<?php echo $row_loc['descripcion'] ?>" /></td>
    </tr>
    <tr bgcolor="#003366">
      <td class="Estilo2">Provincia</td>
      <td><input type="text" class="seleccion" name="provincia" value="<?php echo $row_prov['descripcion'] ?>" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" class="facturas">Factura Seleccionadas </td>
    </tr>
    <tr>
      <td colspan="2"><table width="100%" border="0" bgcolor="#003366" cellpadding="1" cellspacing="1">
	  <?php 
        echo '<tr>';
		echo '  <td width="41%" bgcolor="#00CCFF">Item</td>';
         echo '  <td width="41%" bgcolor="#00CCFF">Factura Numero </td>';
        echo '   <td width="24%" bgcolor="#00CCFF">Fecha</td>';
         echo '  <td width="35%" bgcolor="#00CCFF">Monto</td>';
        echo ' </tr>';
		$total_gral= 0 ;
		
		for ($a=0 ; $a < sizeof($codigo); $a++) 
		{
			$query_factura = "SELECT *   FROM cabfac WHERE codnum=$codigo[$a]";
			$sel_factura =mysqli_query($amercado, $query_factura) or die(mysqli_error($amercado)); 
			$row_factura = mysqli_fetch_assoc($sel_factura);
			$total_gral = $total_gral+$row_factura['totbruto'];
			$codrem = $row_factura['codrem'];
 			if  ($a== 0) { ?>
            
        <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon1" value="1" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc1" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha1" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel" value="<?php echo $row_factura['totbruto'] ?>" /></td>
	   <input type="hidden" class="seleccion" name="tcomprel" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr> <?php } 
	  if ($a==1 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon2" value="2" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc2" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha2" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel2" value="<?php echo $row_factura['totbruto'] ?>" /></td>
	   <input type="hidden" class="seleccion" name="tcomprel_2" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel_2" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel_2" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php } 
	    if ($a==2 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon3" value="3" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc3" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha3" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel3" value="<?php echo $row_factura['totbruto'] ?>" /></td>    <input type="hidden" class="seleccion" name="tcomprel_3" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel_3" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel_3" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php } 
	    if ($a==3 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon4" value="4" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc4" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha4" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel4" value="<?php echo $row_factura['totbruto'] ?>" /></td><input type="hidden" class="seleccion" name="tcomprel_4" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel_4" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel_4" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php } 
	    if ($a==4 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon5" value="5" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc5" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha5" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel5" value="<?php echo $row_factura['totbruto'] ?>" /></td><input type="hidden" class="seleccion" name="tcomprel_5" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel_5" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel_5" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php } 
	    if ($a==5 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon6" value="6" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc6" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha6" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel6" value="<?php echo $row_factura['totbruto'] ?>" /></td><input type="hidden" class="seleccion" name="tcomprel_6" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel_6" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel_6" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php } 
	    if ($a==6 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon7" value="7" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc7" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha7" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel7" value="<?php echo $row_factura['totbruto'] ?>" /></td><input type="hidden" class="seleccion" name="tcomprel_7" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel_7" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel_7" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php } 
	    if ($a==7 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon8" value="8" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc8" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha8" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel8" value="<?php echo $row_factura['totbruto'] ?>" /></td><input type="hidden" class="seleccion" name="tcomprel_8" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel_8" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel_8" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php } 
	    if ($a==8 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon9" value="9" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc9" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha9" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel9" value="<?php echo $row_factura['totbruto'] ?>" /></td><input type="text" class="seleccion" name="tcomprel_9" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="text" class="seleccion" name="serierel_9" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="text" class="seleccion" name="ncomprel_9" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php } 
	    if ($a==9 ) { ?>
	     <tr bgcolor="FFFFFF">
		<td bgcolor="#0099FF"><input type="text" class="seleccion" size="3" name="renglon10" value="10" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="doc10" value="<?php echo $row_factura['nrodoc'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="fecha10" value="<?php echo $row_factura['fecval'] ?>" /></td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="netocbterel10" value="<?php echo $row_factura['totbruto'] ?>" /></td><input type="hidden" class="seleccion" name="tcomprel_10" value="<?php echo $row_factura['tcomp'] ?>" />
	   <input type="hidden" class="seleccion" name="serierel_10" value="<?php echo $row_factura['serie'] ?>" />
	   <input type="hidden" class="seleccion" name="ncomprel_10" value="<?php echo $row_factura['ncomp'] ?>" />
      </tr><?php }
  }
 ?>    <tr bgcolor="FFFFFF"><input type="hidden"  name="remate" value="<?php echo $codrem ?>" />
       <td bgcolor="#0099FF" colspan="3">Total</td>
       <td bgcolor="#0099FF"><input type="text" class="seleccion" name="total_general" value="<?php echo $total_gral ?>" /></td>
      </tr>
 <tr bgcolor="FFFFFF">
   <td bgcolor="#ECE9D8" colspan="3">&nbsp;</td>
   <td bgcolor="#ECE9D8">&nbsp;</td>
 </tr>

 <tr bgcolor="FFFFFF">
   <td bgcolor="#ECE9D8">&nbsp;</td>
   <td bgcolor="#ECE9D8">&nbsp;</td>
   <td bgcolor="#ECE9D8">&nbsp;</td>
   <td bgcolor="#ECE9D8">&nbsp;</td>
 </tr>
 <tr bgcolor="FFFFFF">
   <td colspan="2" bgcolor="#0099CC">&nbsp;</td>
   <td bgcolor="#0099CC">&nbsp;</td>
   <td bgcolor="#0099CC"><input type="submit" name="Submit" value="Grabar" /></td>
 </tr>
      </table></td>
    </tr>
	<input type="hidden" name="MM_insert" value="recibo">
   </form>
</table>

</body>
</html>
