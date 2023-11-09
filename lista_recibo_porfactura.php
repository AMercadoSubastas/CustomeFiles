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
	    var seleccionar = new Option("<-- esperando selección","","","");
    	combo[0] = seleccionar;
    }

    if (selec[1].selected == true) {
	    form1.text_serie.value = "FACTURA ELECTRONICA LOTES A0004-";
		form1.tcomp.value = 51;
	 	form1.serie.value = 29;
	}

    if (selec[2].selected == true) {
	    form1.text_serie.value = "FACTURA ELECTRONICA LOTE B0004-";
    	form1.tcomp.value = 53;
	 	form1.serie.value = 30;
    }
	
	if (selec[3].selected == true) {
	    form1.text_serie.value = "FACTURA ELECTRONICA CONCEPTOS A0004-";
     	form1.tcomp.value = 52;
	 	form1.serie.value = 29;
    }
	
	if (selec[4].selected == true) {
     	form1.text_serie.value = "FACTURA ELECTRONICA CONCEPTOS B0004-";
     	form1.tcomp.value = 54;
	 	form1.serie.value = 30;
    }
	
	if (selec[5].selected == true) {
     	form1.text_serie.value = "FACTURA ELECTRONICA INMOBILIARIA A0006-";
     	form1.tcomp.value = 55;
	 	form1.serie.value = 31;
    }

	if (selec[6].selected == true) {
     	form1.text_serie.value = "FACTURA ELECTRONICA INMOBILIARIA B0006-";
     	form1.tcomp.value = 56;
	 	form1.serie.value = 32;
    }
	
	
}
</script>
<?php require_once('Connections/amercado.php');  
?>
</head>
<body>
<form id="form1" name="form1" method="get" action="rp_recibo2.php?ncomp=<?php echo $ncomp ?>?tcomp=<?php echo $tcomp ?>?serie=<?php echo $serie ?>">
  <table width="727" height="203" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2"  align="center"></td>
    </tr>
    <tr>
      <td width="298">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="298" >Tipo del Comprobante  : </td>
      <td width="422" ><select name="tipos" onChange="agregarOpciones(this.form)">
	    <option value="0">[ELIJA TIPO DE COMPROBANTE]</option>
        <option value="1">FACTURA ELECTRONICA POR LOTES A0004</option>
        <option value="2">FACTURA ELECTRONICA POR LOTES B0004</option>
        <option value="3">FACTURA ELECTRONICA POR CONCEPTOS A0004</option>
        <option value="4">FACTURA ELECTRONICA POR CONCEPTOS B0004</option>
        <option value="5">FACTURA ELECTRONICA INMOB A0006</option>
        <option value="6">FACTURA ELECTRONICA INMOB B0006</option>
      </select></td>
    </tr>
    <tr>
      <td >Serie del Comprobante : </td>
      <td ><input name="text_serie" type="text" size="45"  />
      </td>
    </tr>
    <tr>
      <td >Nro.  del Comprobante : </td>
      <td ><input name="ncomp" type="text" id="ncomp" size="45"/></td>
    </tr>
    <tr>
      <td ><label>Tipo Comp&nbsp;</label><input name="tcomp" type="text"  size="2" readonly=""/></td>
      <td ><label>Serie Comp&nbsp;</label><input name="serie" type="text" size="2" readonly="" /></td>
    </tr>
    <tr>
      <td width="298">&nbsp;</td>
      <td width="422"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
