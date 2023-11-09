<?php
define('FPDF_FONTPATH','fpdf17/font/');
set_time_limit(0); // Para evitar el timeout

require('fpdf17/fpdf.php');
require('numaletras.php');
//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior

$remate_num = $_POST['remate_num'];
//echo " ===   ".$remate_num."      =====  ";
$fechahoy = date("d-m-Y");

// Leo el remate (cabecera)
mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = sprintf("SELECT * FROM `remates` WHERE ncomp = %d",$remate_num);
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$rubro = $row_Recordset1['tipoind'];

// ACA INICIO LOS CAMPOS QUE NECESITO PARA GENERAR EL CSV =======================
$csv_sep = ";";  
$csv_file = "Precios/ID_".$remate_num.".csv";  
$csv="";  

$nro = 0;
$numero = 0;
$fila = 1;


//$archivolocal = file_get_contents("./$csv_file");
//echo "ARCHIVO LOCAL  ".$archivolocal;
//$csv_file = "EXCEL\REMATE_".$remate_num.".csv";

if (($gestor = fopen($csv_file, "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 0, ";",'"')) !== FALSE) {
        $numero = count($datos);
       echo "<p> $numero campos en la linea $fila: <br /></p>\n";
				
		$nro++;
        $fila++;
        
       // echo "datos[c] = ".$datos[$c]."  c = ".$c."   "."FILA = ".$fila."  " ;
		$update = "UPDATE lotes SET preciobase = $datos[1] WHERE codrem = $remate_num AND codintlote = $datos[0]";
        $updateSQL = $update;
        //echo "  INSERT =  ".$insert."    ";
        $Result1 = mysqli_query($amercado, $updateSQL) or die("HUBO PROBLEMAS CON EL PRECIO OBTENIDO DEL LOTE ".$datos[0]);
		//$insertSQL = $insert;
		//$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
		//echo "=== ".$insert." =====" ;
    }
	$filas = $fila - 2;
	echo "<p> <br>"."<br>"."<br>"."                        MODIFICANDO  " . $filas . "  REGISTROS"."<br>"."<br>"."<br> </p>\n";
	fclose($gestor);
	$nuevo_fichero = "Precios/ID_".$remate_num.".bkp";
	if(file_exists($nuevo_fichero)) { 
    	unlink($nuevo_fichero);
    	$ok = rename($csv_file, $nuevo_fichero);
	} else { 
		$ok = rename($csv_file, $nuevo_fichero); 
	}
	echo "<p> <br>"."<br>"."<br>"."                       RENOMBRANDO EL ARCHIVO A BACKUP"."<br>"."<br>"."<br> </p>\n";
}
else {
	echo "EL ARCHIVO NO EXISTE O ESTA CORRUPTO";
}

mysqli_close($amercado);
?>  