<?php
set_time_limit(0); // Para evitar el timeout

//Conecto con la  base de datos
require_once('Connections/amercado.php');

mysqli_select_db($amercado, $database_amercado);

// Leo los par�metros del formulario anterior
$remate    	 = $_POST['remate_num'];
$fecha_tope  = $_POST['fecha_tope'];

$nueva_fecha =  substr($fecha_tope,6,4)."-".substr($fecha_tope,3,2)."-".substr($fecha_tope,0,2);
echo "NUEVA FECHA ORIG = ".$nueva_fecha."  ";
$fechahoy = date("d-m-Y");

// Leo la cabecera del remate

$query_rem = sprintf("UPDATE remates SET fecest = %s WHERE tcomp  = %s ", $nueva_fecha, $remate);
$cabecerarem = mysqli_query($amercado, $query_rem) or die("ERROR REGRABANDO EL REMATE");

echo "NUEVA FECHA TOPE = ".$fecha_tope."   ";


mysqli_close($amercado);

?>  
