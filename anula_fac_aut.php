<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
</head>
<?php require_once('Connections/amercado.php');
$mascara ="A0002";
for ($i = 214 ;;$i++) {
	$tcomp =6 ;
	$serie =5 ;
	$ncomp = $i ;
	$estado = "A" ;
	$fecha ="2008-03-13";
	  
    if ($i > 250) {
		break;
    }
	if ($i <10) {
		$mascara1=$mascara."-"."0000000".$i ;
	}

	if ($i >9 && $i <99) {
		$mascara1=$mascara."-"."000000".$i ;
	}

	if ($i >99 && $i <999) {
		$mascara1=$mascara."-"."00000".$i;
	}
	//if ($i >999 && $i <9999) {
	//$mascara=$mascara."-"."0000".$i;
	//};
    print $mascara1."  ".$tcomp."  ".$serie."  ".$ncomp."  ".$estado."<br>";
	$insertSQL = "INSERT INTO cabfac (tcomp, serie, ncomp, nrodoc ,estado , fecval , fecdoc ,fecreg , fecvenc ) VALUES ('$tcomp' , '$serie' ,'$ncomp', '$mascara1', '$estado', '$fecha' , '$fecha' , '$fecha' , '$fecha')";

  	mysqli_select_db($amercado, $database_amercado); 
  	$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
 	$insert1 = "INSERT INTO cbtesanul (tcomp, serie, ncomp, motivo ,fechaanul) VALUES ('$tcomp' , '$serie' ,'$ncomp', 'VENCIMIENTO DE FACTURAS', '2008-02-28')";
	$Result2 = mysqli_query($amercado, $insert1) or die(mysqli_error($amercado)); 


}
 
$mascara ="B0002";
for ($i = 9;;$i++) {
    $tcomp =24 ;
	$serie =12 ;
	$ncomp = $i ;
	$estado = "A" ;
	$fecha ="2008-03-13";
	  
    if ($i > 50) {
		break;
    }
	if ($i <10) {
		$mascara1=$mascara."-"."0000000".$i ;
	}

	if ($i >9 && $i <99) {
		$mascara1=$mascara."-"."000000".$i ;
	}

	if ($i >99 && $i <999) {
		$mascara1=$mascara."-"."00000".$i;
	}
	//if ($i >999 && $i <9999) {
		//$mascara=$mascara."-"."0000".$i;
	//};
    print $mascara1."  ".$tcomp."  ".$serie."  ".$ncomp."  ".$estado."<br>";
	$insertSQL = "INSERT INTO cabfac (tcomp, serie, ncomp, nrodoc ,estado , fecval , fecdoc ,fecreg , fecvenc ) VALUES ('$tcomp' , '$serie' ,'$ncomp', '$mascara1', '$estado', '$fecha' , '$fecha' , '$fecha' , '$fecha')";

  	mysqli_select_db($amercado, $database_amercado); 
  	$Result1 = mysqli_query($amercado, $insertSQL) or die(mysqli_error($amercado));
 	$insert1 = "INSERT INTO cbtesanul (tcomp, serie, ncomp, motivo ,fechaanul) VALUES ('$tcomp' , '$serie' ,'$ncomp', 'VENCIMIENTO DE FACTURAS', '2008-02-28')";
	$Result2 = mysqli_query($amercado, $insert1) or die(mysqli_error($amercado)); 
}
?>
</body>
</html>