<?php require_once('Connections/amercado.php'); ?>
<?php
include_once "funcion_mysqli_result.php";

mysqli_select_db($amercado, $database_amercado);
$query_Recordset1 = "SELECT * FROM entidades  WHERE entidades.tipoent=1 AND activo = 1 ORDER BY entidades.razsoc ASC";
$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("ERROR LEYENDO CLIENTES");
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
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
</head>
<body>
<form id="form1" name="form1" method="post" action="carga_recibo.php">
  <table width="807" border="1" align="left" cellpadding="1" cellspacing="1">
    
    <tr>
      <td width="267">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="267">Nombre del Cliente : </td>
      <td>


      <tr>
          <td height="10" class="ewTableHeader">Cliente </td>
          <td>
			<div class="search-box">
        		<input id="search-field" type="text" autocomplete="off" name="cliente" required placeholder="Buscar..." />
        		<div class="result"></div>
    		</div>
		   </td>
          <td>&nbsp;</td>
     	</tr>
		 <tr hidden>
          <td height="10" class="ewTableHeader">CUIT</td>
          <td>
			<div class="search-box-cuit">
        		<input id="CUIT" name="cuit" type="text" />
        		<div class="result"></div>
    		</div>
		  </td>
          <td>&nbsp;</td>
		</tr>

		<tr hidden>
			<td height="10" class="ewTableHeader">Razosn Social</td>
			<td>
				<div class="search-box-razan-social">
					<input id="razon-social" name="razsoc" type="text"/>
					<div class="result"></div>
				</div>
			</td>
			<td>&nbsp;</td>
		</tr>

			</td>
    </tr>
    <tr>
      <td width="267">&nbsp;</td>
      <td width="569">&nbsp;</td>
    </tr>
    <tr>
      <td width="267">&nbsp;</td>
      <td width="569"><input type="submit" name="Submit" value="Enviar" /></td>
    </tr>
  </table>
</form>
</body>
</html>