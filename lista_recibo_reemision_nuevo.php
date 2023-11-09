<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<link href="v_estilo_factura.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.Estilo1 {color: #ECE9D8}
-->
.seleccion {
	color: #000066; /* text color */
	font-family: Verdana; /* font name */
	font-size:9px;
	
	
}
</style>
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
     form1.text_serie.value = "RECIBO DE COBRANZAS";
	 form1.tcomp.value = 2;
	 form1.serie.value = 3;
	}

    
	//var combo1 = form.estilo.options[0].value;
	//alert(combo1);
	//var combo1 = form.estilo.options[1].value;
	
}
</script>


<?php require_once('Connections/amercado.php');  


mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM `detrecibo` ORDER BY `ncomp` desc";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die(mysqli_error($amercado));
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
//echo $totalRows_Recordset1;
 ?>

</head>

<body>
<form id="form1" name="form1" method="get" action="rp_recibo_nuevo.php?ncomp=<?php echo $ncomp ?>">
  <table width="600" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <td colspan="2" background="images/fondo_titulos.jpg" align="center"></td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="150" >Tipo del Comprobante  : </td>
      <td width="50" ><select name="tipos" onChange="agregarOpciones(this.form)">
	    <option value="0">[ELIJA TIPO DE COMPROBANTE]</option>
        <option value="1">     RECIBO DE COBRANZAS    </option>
		
      </select></td>
    </tr>
    <tr>
      <td >Serie del Comprobante : </td>
      <td ><input name="text_serie" type="text" size="40"  />
      </td>
    </tr>
    <tr>
      <td >Nro.  del Comprobante : </td>
      <td width="100"><select name="ncomp" id="ncomp">
            <option value="">===========Recibo===========</option>
		
            <?php
				do {  
			?>
            		<option value="<?php echo $row_Recordset1['ncomp']?>"><?php echo $row_Recordset1['ncomprel']?><?php echo " - "?><?php echo $row_Recordset1['ncomp']?><?php echo " - "?><?php echo $row_Recordset1['nrodoc']?></option>
            <?php
				} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  				$rows = mysqli_num_rows($Recordset1);
  				if($rows > 0) {
      				mysqli_data_seek($Recordset1, 0);
	  				$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  				}
			?>
          </select></td>
    </tr>
    <tr>
      <td ><label>Tipo Comp&nbsp;</label><input name="tcomp" type="text"  size="2" readonly=""/></td>
      <td ><label>Serie Comp&nbsp;</label><input name="serie" type="text" size="2" readonly="" /></td>
    </tr>
    <tr>
      <td width="150">&nbsp;</td>
      <td width="50"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>
