<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<?php //Conecto con la  base de datos
require_once('Connections/amercado.php');
$pncomp = 196 ;
mysqli_select_db($amercado, $database_amercado);
$tot_cheques = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='8' AND serie='6'";
$result_cheques = mysqli_query($amercado, $tot_cheques) or die(mysqli_error($amercado));
$cheques_tot = 0 ;
while($row = mysqli_fetch_array($result_cheques)){
$cheques_tot0	= $row['0'];
//echo $row['0']."<br>";
$cheques_tot = $cheques_tot + $cheques_tot0 ;
}
//echo $cheques_tot;
$tot_dep = "SELECT importe  FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='9' AND serie='7'";
$result_dep = mysqli_query($amercado, $tot_dep ) or die(mysqli_error($amercado));
$dep_tot = 0 ;
while($row1 = mysqli_fetch_array($result_dep)){
$dep_tot0	= $row1['0'];
//echo $row['0']."<br>";
$dep_tot = $dep_tot + $dep_tot0 ;
}
//echo $dep_tot;
$query_suss = "SELECT codchq,importe FROM cartvalores WHERE ncomprel ='$pncomp' AND tcomprel ='2' AND serierel='3' AND tcomp='43' AND serie='25'";
$selec_suss = mysqli_query($amercado, $query_suss) or die(mysqli_error($amercado));
echo mysqli_num_rows($selec_suss);
?>
</body>
</html>
