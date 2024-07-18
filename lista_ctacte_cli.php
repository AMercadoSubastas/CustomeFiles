<?php	
 require_once('Connections/amercado.php');
 require_once('funcion_mysqli_result.php');
?>

</head>
<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>
<body>
<form  name="libros" class="container-specific" method="post" action="rp_ctacte_cli.php">
  
    
    <tr>
      
    </tr>
 <table width="392" height="179" border="0" align="left" cellpadding="1" cellspacing="1">   
 <td width="181">&nbsp;
  
 </td>
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
          <td height="10" class="ewTableHeader">Razon Social</td>
          <td>
			<div class="search-box-razan-social">
        		<input id="razon-social" type="text" hidden />
        		<div class="result"></div>
    		</div>
		  </td>
          <td>&nbsp;</td>
     </tr>
 
      

    <tr>
      <td width="181">&nbsp;</td>
      <td width="132"><input type="submit" name="Submit" value="Enviar" /></td>
      <td width="69">&nbsp;</td>
    </tr>
  </table>
</form>
</body>
</html>
