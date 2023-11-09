
<?php require_once('Connections/amercado.php');
//Select the Products you want to show in your PDF file
//$result=mysqli_query("select codintlote,descripcion,observ from lotes ORDER BY codintlote",$link);
//$number_of_products = mysql_numrows($result);
mysqli_select_db($amercado, $database_amercado);
// Leo los parámetros del formulario anterior
$pncomp = $_GET['ftcomp'];
$pncomp = 6 ;
$query_remate1 = sprintf("SELECT * FROM remates WHERE ncomp = %s", $pncomp);
$remates1 = mysqli_query($amercado, $query_remate1) or die(mysqli_error($amercado));
$row_remates1 = mysqli_fetch_assoc($remates1);
$codcli = $row_remates1["codcli"];
echo "Codigo Cliente".$codcli."<br>";
$query_entidad = sprintf("SELECT * FROM entidades WHERE codnum = %s", $codcli);
$tipo_iva = mysqli_query($amercado, $query_entidad) or die(mysqli_error($amercado));
$row_iva = mysqli_fetch_assoc($tipo_iva);
$iva = $row_iva["tipoiva"];

echo "Tipo de IVA".$iva."<br>";
echo "Codigo ClienteTipo de IVA".$codcli."<br>";