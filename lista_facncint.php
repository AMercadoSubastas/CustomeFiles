<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />

<script language="javascript">
function agregarOpciones(form) {
	var selec = form.tipos.options;

    if (selec[0].selected == true) {
	    var seleccionar = new Option("<-- esperando selecci�n","","","");
    	combo[0] = seleccionar;
    }

    if (selec[1].selected == true) {
	    form1.text_serie.value = "CBTE INT SALDO A FAVOR X0001-";
		form1.ftcomp.value = 98;
	 	form1.fserie.value = 42;
	}

	if (selec[2].selected == true) {
	    form1.text_serie.value = "CBTE INT DEVOL SALDO A FAVOR X0002-";
     	form1.ftcomp.value = 99;
	 	form1.fserie.value = 43;
    }

}
</script>
<?php require_once('Connections/amercado.php');  
 //include_once "ewcfg50.php" ;
 //include_once "ewmysql50.php" ;
 //include_once "phpfn50.php" ; 
 //include_once  "userfn50.php" ;
 //include_once "usuariosinfo.php" ; ?>
<?php
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
//header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache"); // HTTP/1.0
?>
<?php //include "header.php" ;
//echo $nivel;
?>
</head>

<body>
<form id="form1" name="form1" method="get" action="rp_facncint.php">
  <table width="727" height="203" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/fondo_titulos.jpg" align="center"><img src="images/impre_facnc.gif" width="300" height="30" /></td>
    </tr>
    <tr>
      <td width="298">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="298" >Tipo del Comprobante  : </td>
      <td width="422" ><select name="tipos" onChange="agregarOpciones(this.form)">
	    <option value="0">[ELIJA TIPO DE COMPROBANTE]</option>
        <option value="1">CBTE INT SALDO A FAVOR X0001</option>
        <option value="2">CBTE INT DEV SALDO A FAVOR X0002</option>
        
      </select></td>
    </tr>
    <tr>
      <td >Serie del Comprobante : </td>
      <td ><input name="text_serie" type="text" size="45"  />
      </td>
    </tr>
    <tr>
      <td >Nro.  del Comprobante : </td>
      <td ><input name="fncomp" type="text" id="fncomp" size="45"/></td>
    </tr>
    <tr>
      <td ><label>Tipo Comp&nbsp;</label><input name="ftcomp" type="text"  size="2" readonly=""/></td>
      <td ><label>Serie Comp&nbsp;</label><input name="fserie" type="text" size="2" readonly="" /></td>
    </tr>
    <tr>
      <td width="298">&nbsp;</td>
      <td width="422"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
