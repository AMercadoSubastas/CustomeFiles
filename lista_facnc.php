<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />

<script language="javascript">
function agregarOpciones(form)
{
var selec = form.tipos.options;
//var combo = form.estilo.options;
//combo.length = null;

    if (selec[0].selected == true)
    {
    var seleccionar = new Option("<-- esperando selecci�n","","","");
    combo[0] = seleccionar;
    }

    if (selec[1].selected == true)
    {
     form1.text_serie.value = "FACTURA AUTO LOTE A0001-";
	 form1.ftcomp.value = 1;
	 form1.fserie.value = 1;
	}

    if (selec[2].selected == true)
    {
     form1.text_serie.value = "FACTURA AUTO LOTE B0001-";
     form1.ftcomp.value = 23;
	 form1.fserie.value = 11;
    }
	
	if (selec[3].selected == true)
    {
     form1.text_serie.value = "FACTURA MAN LOTE A0002-";
     form1.ftcomp.value = 6;
	 form1.fserie.value = 5;
    }
	
	if (selec[4].selected == true)
    {
     form1.text_serie.value = "FACTURA MAN LOTE B0002-";
     form1.ftcomp.value = 24;
	 form1.fserie.value = 12;
    }
	
	if (selec[5].selected == true)
    {
     form1.text_serie.value = "FACTURA AUTO CONC A0001-";
     form1.ftcomp.value = 18;
	 form1.fserie.value = 1;
    }
	
	if (selec[6].selected == true)
    {
     form1.text_serie.value = "FACTURA MAN CONC A0002-";
     form1.ftcomp.value = 19;
	 form1.fserie.value = 5;
    }
	
	if (selec[7].selected == true)
    {
     form1.text_serie.value = "FACTURA MAN CONC B0002-";
     	//form1.ftcomp.value = 27;
	//form1.fserie.value = 11;
	
	form1.ftcomp.value = 28;
	form1.fserie.value = 12;
    }
	
	if (selec[8].selected == true)
    {
     form1.text_serie.value = "FACTURA AUTO CONC B0001-";
     form1.ftcomp.value = 27;
	 form1.fserie.value = 11;
    }
	if (selec[9].selected == true)
    {
     form1.text_serie.value = "NOTA CREDITO AUTO A0001-";
     form1.ftcomp.value = 5;
	 form1.fserie.value = 1;
    }
	
	if (selec[10].selected == true)
    {
     form1.text_serie.value = "NOTA CREDITO AUTO B0001-";
     form1.ftcomp.value = 25;
	 form1.fserie.value = 11;
    }
	if (selec[11].selected == true)
    {
     form1.text_serie.value = "NOTA CREDITO MAN A0002-";
     form1.ftcomp.value = 7;
	 form1.fserie.value = 5;
    }
	
	if (selec[12].selected == true)
    {
     form1.text_serie.value = "NOTA CREDITO MAN B0002-";
     form1.ftcomp.value = 26;
	 form1.fserie.value = 12;
    }
	if (selec[13].selected == true)
    {
     form1.text_serie.value = "NOTA DEBITO AUTO A0001-";
     form1.ftcomp.value = 21;
	 form1.fserie.value = 1;
    }
	
	if (selec[14].selected == true)
    {
     form1.text_serie.value = "NOTA DEBITO AUTO B0001-";
     form1.ftcomp.value = 29;
	 form1.fserie.value = 11;
    }
	if (selec[15].selected == true)
    {
     form1.text_serie.value = "NOTA DEBITO MAN A0002-";
     form1.ftcomp.value = 22;
	 form1.fserie.value = 5;
    }
	
	if (selec[16].selected == true)
    {
     form1.text_serie.value = "NOTA DEBITO MAN B0002-";
     form1.ftcomp.value = 30;
	 form1.fserie.value = 12;
    }
	if (selec[17].selected == true)
    {
     form1.text_serie.value = "FACTURA CONC INMOB A0003-";
     form1.ftcomp.value = 44;
	 form1.fserie.value = 26;
    }
	
	if (selec[18].selected == true)
    {
     form1.text_serie.value = "FACTURA CONC INMOB B0003-";
     form1.ftcomp.value = 45;
	 form1.fserie.value = 27;
    }
	if (selec[19].selected == true)
    {
     form1.text_serie.value = "NOTA CREDITO CONC INMOB A0003-";
     form1.ftcomp.value = 46;
	 form1.fserie.value = 26;
    }
	
	if (selec[20].selected == true)
    {
     form1.text_serie.value = "NOTA CREDITO CONC INMOB B0003-";
     form1.ftcomp.value = 47;
	 form1.fserie.value = 27;
    }
	if (selec[21].selected == true)
    {
     form1.text_serie.value = "NOTA DEBITO CONC INMOB A0003-";
     form1.ftcomp.value = 48;
	 form1.fserie.value = 26;
    }
	
	if (selec[22].selected == true)
    {
     form1.text_serie.value = "NOTA DEBITO CONC INMOB B0003-";
     form1.ftcomp.value = 49;
	 form1.fserie.value = 27;
    }
	//var combo1 = form.estilo.options[0].value;
	//alert(combo1);
	//var combo1 = form.estilo.options[1].value;
	
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
<form id="form1" name="form1" method="get" action="rp_facnc.php">
  <table width="600" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/fondo_titulos.jpg" align="center"><img src="images/impre_facnc.gif" width="300" height="30" /></td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="150" >Tipo del Comprobante  : </td>
      <td width="50" ><select name="tipos" onChange="agregarOpciones(this.form)">
	    <option value="0">[ELIJA TIPO DE COMPROBANTE]</option>
        <option value="1">FACTURA AUTOMATICA POR LOTES A0001</option>
		<option value="2">FACTURA AUTOMATICA POR LOTES B0001</option>
        <option value="3">FACTURA MANUAL POR LOTES A0002</option>
		<option value="4">FACTURA MANUAL POR LOTES B0002</option>
		<option value="5">FACTURA AUTOMATICA POR CONCEPTOS A0001</option>
		<option value="6">FACTURA MANUAL POR CONCEPTOS A0002</option>
        <option value="7">FACTURA MANUAL POR CONCEPTOS B0002</option>
		<option value="8">FACTURA AUTOMATICA POR CONCEPTOS B0001</option>
        <option value="9">NOTA CREDITO AUTOMATICA A0001</option>
		<option value="10">NOTA CREDITO AUTOMATICA B0001</option>
        <option value="11">NOTA CREDITO MANUAL A0002</option>
		<option value="12">NOTA CREDITO MANUAL B0002</option>
        <option value="13">NOTA DEBITO AUTOMATICA A0001</option>
		<option value="14">NOTA DEBITO AUTOMATICA B0001</option>
        <option value="15">NOTA DEBITO MANUAL A0002</option>
		<option value="16">NOTA DEBITO MANUAL B0002</option>
		<option value="17">FACTURA POR CONCEPTOS INMOB A0003</option>
		<option value="18">FACTURA POR CONCEPTOS INMOB B0003</option>
        <option value="19">NOTA CREDITO POR CONCEPTOS INMOB A0003</option>
		<option value="20">NOTA CREDITO POR CONCEPTOS INMOB B0003</option>
        <option value="21">NOTA DEBITO CONCEPTOS INMOB A0003</option>
		<option value="22">NOTA DEBITO CONCEPTOS INMOB B0003</option>
       
      </select></td>
    </tr>
    <tr>
      <td >Serie del Comprobante : </td>
      <td ><input name="text_serie" type="text" size="40"  />
      </td>
    </tr>
    <tr>
      <td >Nro.  del Comprobante : </td>
      <td ><input name="fncomp" type="text" id="fncomp" /></td>
    </tr>
    <tr>
      <td ><label>Tipo Comp&nbsp;</label><input name="ftcomp" type="text"  size="2" readonly=""/></td>
      <td ><label>Serie Comp&nbsp;</label><input name="fserie" type="text" size="2" readonly="" /></td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td width="50"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
