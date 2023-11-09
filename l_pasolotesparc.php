<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin t&iacute;tulo</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="https://fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet">

<?php require_once('Connections/amercado.php'); 
	mysqli_select_db($amercado, $database_amercado);	
	$codrem = $_GET['remate_num'];//1578;
	$query_Recordset1 = "SELECT * FROM `lotes` WHERE `codrem` = $codrem ORDER BY `codintnum`,`codintsublote` asc";
	$Recordset1 = mysqli_query($amercado, $query_Recordset1) or die("NO SE PUEDEN LEER LOS LOTES");
	$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
	$cantreg = mysqli_num_rows($Recordset1);
?>
<?php

?>
<link type="text/css" rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/base/ui.all.css" />
<link type="text/css" href="css/ui.multiselect.css" rel="stylesheet" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.custom.min.js"></script>
<script type="text/javascript" src="js/ui.multiselect.js"></script>
<script type="text/javascript">
$(function(){
   $.localise('ui-multiselect', {path: 'js/'});
   $(".multiselect").multiselect();
});
</script>
</head>
<style type="text/css">

</style>
<style type="text/css">
  .boton{
        font-size:18px;
        font-family: 'Inconsolata', monospace;
        font-weight:bold;
        color:black;
        border:2px;
        width:120px;
        height:40px;
       }
	.chkbox{
        font-size:16px;
        font-family: 'Inconsolata', monospace;
		width:30px;
        height:30px;
        
       }

</style>
<body>

<form id="form1" name="form1" method="get"  action="rp_pasolotesparc.php">
  <table width="660" height="203" border="2" align="left" cellpadding="1" cellspacing="1">
    <tr>
      
    </tr>
    <tr>
      
    </tr>
     <tr>
     
<?php
		 if ($cantreg == 0) {
			 
		 ?>
     <td width="42">NO HAY LOTES EN ESTE REMATE</td>
     <?php
		 }
			 else {
			?>		 	 
				 	
               		<td>
			<?php
					 do {
					?>		 	 
            		
    <tr><font size=6>
            <td width="42">&nbsp;
     <input type ="checkbox" class="chkbox" name="lotes[]" value="<?php echo $row_Recordset1['codintlote']?>"><?php echo " |  "?><?php echo $row_Recordset1['codintlote']?><?php echo " |  "?><?php echo substr($row_Recordset1['descor'],0,50)?>
         
          </td>
    </tr>
            <?php
				} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1));
  				$rows = mysqli_num_rows($Recordset1);
  				if($rows > 0) {
      				mysqli_data_seek($Recordset1, 0);
	  				$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
  				}
			 }
	  ?>
          
       
    </tr>
  </table>
  </font>
  <table width="960" height="203" border="0" align="left" cellpadding="1" cellspacing="1">
    <tr>
      <input type="hidden" name="remate_num" value=<?php echo $codrem?>>
    </tr>
    <tr>
      
      <td colspam="4">
      
	
	</tr>
	<tr>
    
    
	<td width="50"><input style="background-color: #57A639" type="submit" name="enviar" value =" Enviar " class="boton"/>
	  		
	</tr>
	<tr>
	<td width="42">&nbsp;</td>
	<td width="42">&nbsp;</td>
</td>
	</tr>
  </table>

</form>

</body>
</html>
