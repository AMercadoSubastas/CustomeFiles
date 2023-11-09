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
$csv_file = "REMATE_".$remate_num.".csv";  
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
		$insert = "INSERT INTO lotes (secuencia,codrem,estado,moneda,comiscobr,comispag,descripcion,descor,codintlote,codintnum,codintsublote) VALUES (";
        $insert .= $nro.",";
		$insert .= $remate_num.",";
		$insert .= '0'.",";
		$insert .= '1'.",";
		$insert .= '10'.",";
		$insert .= '10'.",";
      
         for ($c=0; $c < $numero; $c++) {
			//echo "datos[c] = ".$datos[$c]."  c = ".$c."   "."FILA = ".$fila."  " ;
			
				
					if ($c == 0 ) {
						$insert .= '"';
						$insert .= $datos[$c];
						$insert .= '"';
						$insert .= ",";
                        $insert .= '"';
						$insert .= $datos[$c];
						$insert .= '"';
						$insert .= ",";
                    }
                    if ($c == 1) {
                        //$partes = preg_explode("/(,?\s+)|((?<=[a-z])(?=\d))|((?<=\d)(?=[a-z]))/i", $datos[$c]);
                        if (is_numeric($datos[$c])) {
                            $insert .= '"';
                            $insert .= $datos[$c];
                            $insert .= '"';
                            $insert .= ",";
                            $insert .= $datos[$c];
                            $insert .= ",";
                            $insert .= '"';
                            //$insert .= $letra;
                            $insert .= '"';
                        }
                        else {
                            $partes = explode(' ',$datos[$c]);
                            $numcod = $partes[0];
                            $letra  = $partes[1];
                            //echo "C =  ".$c."  "."partes 0 = ".$partes[0]."  partes 1 = ".$partes[1]."  ";
                            $insert .= '"';
                            $insert .= $datos[$c];
                            $insert .= '"';
                            $insert .= ",";
                            $insert .= $numcod;
                            $insert .= ",";
                            $insert .= '"';
                            $insert .= $letra;
                            $insert .= '"';
                        }
                    }
               
		}
        $insert .= ")";
        $insertSQL = $insert;
        //echo "  INSERT =  ".$insert."    ";
        $Result1 = mysqli_query($amercado, $insertSQL) or die("HUBO PROBLEMAS CON LA DESCARGA DE LOS LOTES, LLAME A SISTEMAS");
		//$insertSQL = $insert;
		//$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
		//echo "=== ".$insert." =====" ;
    }
	$filas = $fila - 2;
	echo "<p> <br>"."<br>"."<br>"."                        INSERTANDO  " . $filas . "  REGISTROS"."<br>"."<br>"."<br> </p>\n";
	fclose($gestor);
	$nuevo_fichero = "REMATE_".$remate_num.".bkp";
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