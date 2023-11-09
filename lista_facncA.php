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
	    form1.text_serie.value = "FACTURA ELECTRONICA A0002-";
		form1.ftcomp.value = 115;
	 	form1.fserie.value = 52;
	}

	
	
	
	if (selec[2].selected == true) {
     	form1.text_serie.value = "FACTURA ELECTRONICA MiPyme A0005-";
     	form1.ftcomp.value = 117;
	 	form1.fserie.value = 54;
    }

	if (selec[3].selected == true) {
     	form1.text_serie.value = "NOTA DE CRED MiPyme A0005-";
		form1.ftcomp.value = 121;
		form1.fserie.value = 54;
    }
    
    if (selec[4].selected == true) {
     	form1.text_serie.value = "NOTA DE CRED ELECTRONICA A0002-";
		form1.ftcomp.value = 119;
		form1.fserie.value = 52;
    }
    
	if (selec[5].selected == true) {
     	form1.text_serie.value = "NOTA DE DEB ELECTRONICA A0002-";
     	form1.ftcomp.value = 122;
	 	form1.fserie.value = 52;
    }

	

}
</script>
<?php require_once('Connections/amercado.php');  
?>
</head>
<body>
<form id="form1" name="form1" method="get" action="rp_facncA.php">
  <table width="727" height="203" border="1" align="left" cellpadding="1" cellspacing="1">
    
    <tr>
      <td width="298" >Tipo del Comprobante  : </td>
      <td width="422" ><select name="tipos" onChange="agregarOpciones(this.form)">
	    <option value="0">[ELIJA TIPO DE COMPROBANTE]</option>
        <option value="1">FACTURA ELECTRONICA  A0002</option>
        <option value="2">FACTURA ELECTRONICA MyPyme A0005</option>
          <option value="3">NOTA CREDITO MiPyme A0005</option>
        <option value="4">NOTA CREDITO ELECTRONICA A0002</option>
        <option value="5">NOTA DEBITO ELECTRONICA A0002</option>
		
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
      <td ><label>Tipo Comp&nbsp;</label><input name="ftcomp" type="text"  size="2" /></td>
      <td ><label>Serie Comp&nbsp;</label><input name="fserie" type="text" size="2"  /></td>
    </tr>
    <tr>
      <td width="298">&nbsp;</td>
      <td width="422"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
