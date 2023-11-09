<?php require_once('Connections/amercado.php'); ?>
<?php
mysqli_select_db($amercado, $database_amercado);
$query_tipo_comprobante = "SELECT * FROM tipcomp WHERE esfactura='1'";
$tipo_comprobante = mysqli_query($amercado, $query_tipo_comprobante) or die(mysqli_error($amercado));
$row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
$totalRows_tipo_comprobante = mysqli_num_rows($tipo_comprobante);

// $nivel = 9 ;
$query_num_remates = "SELECT * FROM remates";
$num_remates = mysqli_query($amercado, $query_num_remates) or die(mysqli_error($amercado));
$row_num_remates = mysqli_fetch_assoc($num_remates);
$totalRows_num_remates = mysqli_num_rows($num_remates);


$query_impuesto = "SELECT * FROM impuestos";
$impuesto = mysqli_query($amercado, $query_impuesto) or die(mysqli_error($amercado));
$row_impuesto = mysqli_fetch_assoc($impuesto);
$totalRows_impuesto = mysqli_num_rows($impuesto);
$iva_21_desc = mysqli_result($impuesto,0,2)."<br>";
	$iva_21_porcen = mysqli_result($impuesto,0,1);
//	echo $iva_21_desc ;
//	echo $iva_21_porcen."<br>" ;
	$iva_15_desc = mysqli_result($impuesto,1,2)."<br>";
	$iva_15_porcen = mysqli_result($impuesto,1,1);
//	echo $iva_15_desc ;
//	echo $iva_15_porcen."<br>" ;
	//echo $iva_21_desc ;
//	echo $iva_21_porcen."<br>" ;

$valor1 =$_GET['valor'];
echo $valor1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
<?php require_once('Connections/amercado.php');  ?>
 <?php include_once "ewcfg50.php" ?>
<?php include_once "ewmysql50.php" ?>
<?php include_once "phpfn50.php" ?>
<?php include_once  "userfn50.php" ?>
<?php include_once "usuariosinfo.php" ?>
<?php
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
//header("Cache-Control: private, no-store, no-cache, must-revalidate"); // HTTP/1.1 
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache"); // HTTP/1.0
?>
<?php include "header.php" ;
echo $nivel;
?>
 
 <script language="javascript">
 function forma_pago(form)
 {
  var total      = factura.comisiontotal.value;  // Total de la boleta
  var fac_numero = factura.num_factura.value ; // Nuemro de la Factura 
  var tipo_comprobante = factura.tcomp.value ;  // Tipo de comprobante 
  var serie_num     = factura.serie.value ;  // Numero de Serie 
  var fecha_factura = factura.fecha_factura.value ; //  Fecha de Factura
  var remate_num    = factura.remate_num.value ; // Numero de Remate
  
 // var elformulario.factura3.value = fac_numero ; 
  alert(fac_numero) ;
//  var pagos.total.value = total ;
//  var pagos.tip_comprobante.value = tipo_comprobante ;
//  var pagos.fecha_de_fact.value = fecha_factura ;
//  var pagos.remate_numero.value = remate_num ;
 // pagos.submit()
  
  // escribimos el mensaje de alerta
strAlerta = "Total Factura " + total + "\n" + "Numero Factura " + fac_numero + "\n" + "Tipo de comprobante " + tipo_comprobante + "\n" + "Serie Numero " + serie_num + "\n" + "Fecha Factura " + fecha_factura + "\n" + "Numero de remate " + remate_num + "\n";

// lanzamos la acci�n
alert(strAlerta);

//  alert("Valor del Total"+total);
//document.write('<form name"pagos" action="medios_pago.php">');
//document.write(" <input name='factura' type='text'  />");
//document.write(' <input name="total" type ="text"   />');
//document.write(' <input name="tip_comprobante" type ="text"   />');
//document.write(' <input name="fecha_de_fact" type="text"   />');
//document.write(' <input name="remate_numero" type="text"   />');
//document.write('<form>');

 }
 </script>
 
 
 
 
<script language="javascript">
 function cambia_fecha(form)

{ 
var fecha = remate.fecest1.value;
var ano = fecha.substring(6,10);
var mes = fecha.substring(3,5);
var dia = fecha.substring(0,2);

var hora = remate.horaest.value;
//alert (ano);
//alert (mes);
//alert (dia);
var fecha1 = ano+"-"+mes+"-"+dia+" "+hora;
//alert (fecha1);
//alert(fecha + hora) ;
remate.fecest.value = fecha1;

}

function pasaValor(form)

{ 
//alert (form1.remate.value);
var comprobante = factura.tcomp.value;  // Nuemro de remate
var serie = factura.serie.value; // Tipo de industria
var factnum = factura.num_factura.value; // Codigo de cliente
var fecha_fact    = factura.fecha_factura.value; // Direccion del remate
var remate    = factura.remate_num.value; // Direccion del remate

//var remate = factura.select.options[factura.select.selectedIndex].value; // Codigo del pais
//formulario.select.options[formulario.select.selectedIndex].value
//alert (comprobante );
//alert (serie );
//alert (factnum );
//alert (fecha_fact );
//alert (remate);
//var codcli = form1.codcli.value;

//formulario2.comprobante.value = comprobante;
//formulario2.serie.value = serie;
//formulario2.factnum.value = factnum;
//formulario2.fecha_fact.value = fecha_fact;
//formulario2.remate.value = remate;
//formulario2.submit()
//formulario2.industria.value = industria;
//formulario2.codcli.value = codcli;

//alert (formulario2.carga.value);
 }
</script>

<script language="javascript">
function  valor_prueba(form) {

    if (factura.impuesto[0].checked==true) {
          var impuesto = (factura.impuesto[0].value)/100;
       //   alert("Impuesto 0: "+impuesto);
       }

    if (factura.impuesto[1].checked==true) {
         var impuesto = (factura.impuesto[1].value)/100;
    //     alert("Impuesto 1: "+impuesto);
        }
    //   alert("Impuesto : "+impuesto);

   if (factura.impuesto1[0].checked==true) {
         var impuesto1 = (factura.impuesto1[0].value)/100;
        }

   if (factura.impuesto1[1].checked==true) {
         var impuesto1 = (factura.impuesto1[1].value)/100;
        }
        alert("Impuesto1 : "+impuesto1);

    if (factura.impuesto2[0].checked==true) {
         var impuesto2 = (factura.impuesto2[0].value)/100;
         }

    if (factura.impuesto2[1].checked==true) {
         var impuesto2 = (factura.impuesto2[1].value)/100;
       }
         alert("Impuesto2 : "+impuesto2);  

if (factura.impuesto3[0].checked==true) {
var impuesto3 = (factura.impuesto3[0].value)/100;
}

if (factura.impuesto3[1].checked==true) {
var impuesto3 = (factura.impuesto3[1].value)/100;
} 
//alert("Impuesto3 : "+impuesto3); 
//form.casilla1[i].checked = false;
var total = factura.importe.value;
var total_articulo = impuesto+('+')+total;
//alert(total_articulo);

} 

</script>
<script language="javascript">
function validarFormulario(form)
{
		
	var monto  = factura.importe.value; // Monto  primer lote
	var comi   = factura.comision.value; // Comision  primer lote
	var imp105 = (factura.impuesto[0].value)/100; // Impuesto 10,5 %
	var imp21  = (factura.impuesto[1].value)/100; // impuesto 21 %
	
	var monto1 = factura.importe1.value; // Monto segundo lote
	var comi1  = factura.comision1.value;// Comision  segundo lote
	var imp105_1 = (factura.impuesto1[0].value)/100; // Impuesto 10,5 %
	var imp21_1  = (factura.impuesto1[1].value)/100; // impuesto 21 %
	
	var monto2 = factura.importe2.value; // Monto tercer lote
	var comi2  = factura.comision2.value; // Comision  tercer lote
	var imp105_2 = (factura.impuesto2[0].value)/100; // Impuesto 10,5 %
	var imp21_2  = (factura.impuesto2[1].value)/100; // impuesto 21 %
	
	var monto3 = factura.importe3.value; // Monto cuarto lote
	var comi3  = factura.comision3.value; // Comision  cuarto lote
	var imp105_3 = (factura.impuesto3[0].value)/100; // Impuesto 10,5 %
	var imp21_3  = (factura.impuesto3[1].value)/100; // impuesto 21 %
	
	var monto4 = factura.importe4.value;
	var comi4  = factura.comision4.value;
	
	var monto5 = factura.importe5.value;
	var comi5   = factura.comision5.value;
	
	var monto6  = factura.importe6.value;
	var comi6   = factura.comision6.value;
	
	var monto7  = factura.importe7.value;
	var comi7   = factura.comision7.value;
	
	var monto8  = factura.importe8.value;
	var comi8   = factura.comision8.value;
	
	var monto9  = factura.importe9.value;
	var comi9   = factura.comision9.value;
	
	var monto10 = factura.importe10.value;
	var comi10  = factura.comision10.value;
	
	var monto11 = factura.importe11.value;
	var comi11  = factura.comision11.value;
	var monto12 = factura.importe12.value;
	var comi12  = factura.comision12.value;
	var monto13 = factura.importe13.value;
	var comi13  = factura.comision13.value;
	var monto14 = factura.importe14.value;
	var comi14  = factura.comision14.value;
    var  tot_mon = 0 ;
    var tot_comi = 0 ;
	var neto105 = 0;
	var neto21 = 0 ;
	var imp_tot105 = 0 ;
	var imp_tot21 = 0 ;
	
	      //// Primer LOTE 
          if (factura.impuesto[0].checked==true) {
             if (monto.length!=0) {
	             tot_mon = eval(monto);
	             tot_mon = tot_mon.toFixed(2);
	             tot_comi = (comi*monto)/100;
				 imp_tot105 = monto*imp105 ;
				 alert("Impuesto 10,5% "+imp_tot105);
	       	 }  
	       }
	
	      if (factura.impuesto[1].checked==true) {
	         if (monto.length!=0) {
	             tot_mon = eval(monto);
	             tot_mon = tot_mon.toFixed(2);
	             tot_comi = (comi*monto)/100;
				 imp_tot21 = monto*imp21 ;
				 alert("Impuesto 21% "+imp_tot21);
				 
	       	  }  
	       }
		   
		   // SEGUNDO LOTE
		 if (factura.impuesto1[0].checked==true) { 
			 if (monto1.length!=0) {
	                   tot_mon = eval(monto+('+')+monto1);
	                   tot_mon = tot_mon.toFixed(2);
	                   tot_comi1 = (comi1*monto1)/100;
	                   tot_comi = eval(tot_comi+('+')+tot_comi1);
					   imp_tot105_1 = monto1*imp105_1 ;
			 		   imp_tot105 = eval(imp_tot105+('+')+imp_tot105_1);
	              } 
	         }
			 
		 if (factura.impuesto1[1].checked==true) {	 
			if (monto1.length!=0) {
	                   tot_mon = eval(monto+('+')+monto1);
	                   tot_mon = tot_mon.toFixed(2);
	                   tot_comi1 = (comi1*monto1)/100;
	                   tot_comi = eval(tot_comi+('+')+tot_comi1);
				       imp_tot21_1 = monto1*imp21_1 ;
					   
	             } 
			}	 
			
			
			  if (monto2.length!=0) {
	              tot_mon = eval(tot_mon+('+')+monto2);
                  tot_mon = tot_mon.toFixed(2);
	              tot_comi2 = (comi2*monto2)/100;
	              tot_comi = eval(tot_comi+('+')+tot_comi2);
			    }
			
			
			
			
				  if (monto3.length!=0) {
	             tot_mon = eval(tot_mon+('+')+monto3);
                 tot_mon = tot_mon.toFixed(2);
	             tot_comi3 = (comi3*monto3)/100;
	             tot_comi = eval(tot_comi+('+')+tot_comi3);
			//	 alert (tot_mon)
	             } 
				 
	        if (monto4.length!=0) {
	             tot_mon = eval(tot_mon+('+')+monto4);
                 tot_mon = tot_mon.toFixed(2);
	             tot_comi4 = (comi4*monto4)/100;
	             tot_comi = eval(tot_comi+('+')+tot_comi4);
			//	 alert (tot_mon)
	             } 
             if (monto5.length!=0) {
                  tot_mon = eval(tot_mon+('+')+monto5);
                  tot_mon = tot_mon.toFixed(2);
	              tot_comi5= (comi5*monto5)/100;
	              tot_comi = eval(tot_comi+('+')+tot_comi5);
				//  alert (tot_mon)
	             } 
	          if (monto6.length!=0) {
			    //   alert(monto6);
	               tot_mon = eval(tot_mon+('+')+monto6);
                   tot_mon = tot_mon.toFixed(2);
	               tot_comi6 = (comi6*monto6)/100;
	               tot_comi = eval(tot_comi+('+')+tot_comi6);
				//  alert (tot_mon);
	               } 
 
                if (monto7.length!=0) {
	              tot_mon = eval(tot_mon+('+')+monto7);
                  tot_mon = tot_mon.toFixed(2);
	               tot_comi7 = (comi7*monto7)/100;
	               tot_comi = eval(tot_comi+('+')+tot_comi7);
				//   alert (tot_mon);
	             } 
	            if (monto8.length!=0) {
	              tot_mon = eval(tot_mon+('+')+monto8);
                    tot_mon = tot_mon.toFixed(2);
	                tot_comi8 = (comi8*monto8)/100;
	                tot_comi = eval(tot_comi+('+')+tot_comi8);
				//	alert (tot_mon);
	              } 
				  
				   if (monto9.length!=0) {
	                 tot_mon = eval(tot_mon+('+')+monto9);
                     tot_mon = tot_mon.toFixed(2);
	                  tot_comi9 = (comi9*monto9)/100;
	                   tot_comi = eval(tot_comi+('+')+tot_comi9);
				//	   alert (tot_mon);
	                } 
					
					if (monto10.length!=0) {
	                tot_mon = eval(tot_mon+('+')+monto10);
					tot_mon = tot_mon.toFixed(2);
	                tot_comi10 = (comi10*monto10)/100;
	                tot_comi = eval(tot_comi+('+')+tot_comi10);
                 //    alert (tot_mon);
	                   } 
	               if (monto11.length!=0) {
                   	tot_mon = eval(tot_mon+('+')+monto11);
                    tot_mon = tot_mon.toFixed(2);
	                 tot_comi11 = (comi11*monto11)/100;
	               tot_comi = eval(tot_comi+('+')+tot_comi11);
				//    alert (tot_mon);
	               } 
	              if (monto12.length!=0) {
		            tot_mon = eval(tot_mon+('+')+monto12);
	                tot_mon = tot_mon.toFixed(2);
	                tot_comi12 = (comi12*monto12)/100;
	                 tot_comi = eval(tot_comi+('+')+tot_comi12);
				//	 alert (tot_mon);
	               } 
	             if (monto13.length!=0) {
	                  tot_mon = eval(tot_mon+('+')+monto13);
	                  tot_mon = tot_mon.toFixed(2);
	                    tot_comi13 = (comi13*monto13)/100;
	                   tot_comi = eval(tot_comi+('+')+tot_comi13);
			//		   alert (tot_mon);
	                 } 
	             if (monto14.length!=0) {
	                tot_mon = eval(tot_mon+('+')+monto14);
	                tot_mon = tot_mon.toFixed(2);
	                tot_comi14 = (comi14*monto14)/100;
	                 tot_comi = eval(tot_comi+('+')+tot_comi14);
	              //    alert (tot_mon);
					//  alert (tot_comi);
	                 } 
					  alert ("Total vendido"+tot_mon);
					  tot_comi=tot_comi.toFixed(2);
					  alert ("Comision "+tot_comi);
					  
				  factura.comisiontotal.value = tot_comi ;
 //   if (factura.impuesto[1].checked==true) {
//	    var impuesto = (factura.impuesto[0].value)/100;
//             if (monto.length!=0) {
//	             tot_mon = eval(monto);
//	             tot_mon_105 = tot_mon.toFixed(2);
//	             tot_comi = (comi*monto)/100;
//				 impuesto_105 = impuesto*tot_mon105;
//				 impuesto_105 = impuesto_105.toFixed(2);
//        	 }  
//     }
  //   alert("Monto Total :"+tot_mon+"<br>"+Comision :"+tot_comi+"<br>"+Impuesto 10.5"+impuesto_105+"<br>")
 
	}
</script>


<script language="javascript" src="cal2.js" >
</script>
<script language="javascript" src="cal_conf2.js"></script>

<script type="text/javascript" src="../js/ajax.js"></script>    

<script type="text/javascript" src="../js/separateFiles/dhtmlSuite-common.js"></script> 
<script tpe="text/javascript">
DHTMLSuite.include("chainedSelect");
</script>
<!-- Desde Aca !--> 
	<script type="text/javascript" src="AJAX/ajax.js"></script>
	<script type="text/javascript">
	// Cliente
	var ajax = new sack();
	var currentClientID=false;
	// Primer Lote
	var ajax1 = new sack();
	var currentLoteID=false;
	// Segundo Lote
	var ajax2 = new sack();
	var currentLoteID1=false;
	// Tercer Lote
	var ajax3 = new sack();
	var currentLoteID2=false;
	// Cuarto Lote
	var ajax4 = new sack();
	var currentLoteID3=false;
	// Quinto Lote
	var ajax5 = new sack();
	var currentLoteID4=false;
	// Sexto Lote
	var ajax6 = new sack();
	var currentLoteID5=false;
	// Septimo Lote
	var ajax7 = new sack();
	var currentLoteID6=false;
	// Octavo Lote
	var ajax8 = new sack();
	var currentLoteID7=false;
	// Novenmo Lote
	var ajax9 = new sack();
	var currentLoteID8=false;
	// Decimo Lote
	var ajax10 = new sack();
	var currentLoteID9=false;
	// Lote Once
	var ajax11 = new sack();
	var currentLoteID10=false;
	// Lote Doce
	var ajax12 = new sack();
	var currentLoteID11=false;
	// Lote Trece
	var ajax13 = new sack();
	var currentLoteID12=false;
	// Lote Catorce
	var ajax14 = new sack();
	var currentLoteID13=false;
	// Lote Quince
	var ajax15 = new sack();
	var currentLoteID14=false;
	//	alert(currentlote);
	//alert(currentLoteID2);
	function getClientData()
	{
		var clientId = document.getElementById('remate_num').value.replace(/[^0-9]/g,'');
		//	alert (clientId);
		if( clientId!=currentClientID){
		//alert()
			currentClientID = clientId
			ajax.requestFile = 'getFact.php?getClientId='+clientId;	// Specifying which file to get
			ajax.onCompletion = showClientData;	// Specify function that will be executed after file has been found
			ajax.runAJAX();		// Execute AJAX function			
		}
		
		
	}
	function getLoteData() // Primer lote
	{
		var loteId = document.getElementById('lote').value ;
		//alert ("LOT" +document.getElementById('lote').value);
	 //  alert("LoteID "+ loteId);
		
		if( loteId!=currentLoteID){
	  //  alert(currentLoteID);
		currentLoteID = loteId;
		//alert("Current "+currentLoteID);
			ajax1.requestFile = 'getlote.php?getloteId='+loteId;	// Specifying which file to get
			ajax1.onCompletion = showLoteData;	// Specify function that will be executed after file has been found
			ajax1.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData1()  /// Segundo Lote
	{
		var loteId1 = document.getElementById('lote1').value ;
		//alert ("LOT" +document.getElementById('lote1').value);
	   //alert("LoteID "+ loteId1);
		
		if( loteId1!=currentLoteID1){
	   // alert(currentLoteID1);
		currentLoteID1 = loteId1;
		//alert("Current "+currentLoteID);
			ajax2.requestFile = 'getlote1.php?getloteId1='+loteId1;	// Specifying which file to get
			ajax2.onCompletion = showLoteData1;	// Specify function that will be executed after file has been found
			ajax2.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData2()  /// Tercer lote
	{
		var loteId2 = document.getElementById('lote2').value ;
	//	alert ("LOT" +document.getElementById('lote2').value);
	 //  alert("Tercer lote "+ loteId2);
		
		if( loteId2!=currentLoteID2){
	 //   alert("Tercer lote en IF"+currentLoteID2);
		currentLoteID2 = loteId2;
		//alert("Current "+currentLoteID);
			ajax3.requestFile = 'getlote2.php?getloteId2='+loteId2;	// Specifying which file to get
			ajax3.onCompletion = showLoteData2;	// Specify function that will be executed after file has been found
			ajax3.runAJAX();		// Execute AJAX function			
		}
		
	}
	function getLoteData3()  /// Cuarto lote
	{
		var loteId3 = document.getElementById('lote3').value ;
	//	alert ("LOT" +document.getElementById('lote3').value);
	 //  alert("Cuarto lote "+ loteId3);
		
		if( loteId3!=currentLoteID3){
	 //   alert("Cuarto lote en IF"+currentLoteID3);
		currentLoteID3 = loteId3;
	//	alert("Current "+currentLoteID3+"  "+loteId3);
			ajax4.requestFile = 'getlote3.php?getloteId3='+loteId3;	// Specifying which file to get
			ajax4.onCompletion = showLoteData3;	// Specify function that will be executed after file has been found
			ajax4.runAJAX();		// Execute AJAX function			
		}
}

function getLoteData4()  /// Quinto lote
{
	var loteId4 = document.getElementById('lote4').value ;
	//alert ("LOT" +document.getElementById('lote4').value);
 //  alert("Quinto lote "+ loteId4);
		
	if( loteId4!=currentLoteID4){
	//    alert("Quinto lote en IF"+currentLoteID4);
		currentLoteID4 = loteId4;
//	alert("Current "+currentLoteID4+"  "+loteId4);
			ajax5.requestFile = 'getlote4.php?getloteId4='+loteId4;	// Specifying which file to get
		ajax5.onCompletion = showLoteData4;	// Specify function that will be executed after file has been found
			ajax5.runAJAX();		// Execute AJAX function			
		}	
}	
function getLoteData5()  /// Sexto lote
{
	var loteId5 = document.getElementById('lote5').value ;
//	alert ("LOT" +document.getElementById('lote5').value);
//   alert("Sexto lote "+ loteId5);
//alert("CurrentLoteID"+currentLoteID5)
	if( loteId5!=currentLoteID5){
//	    alert("sexto lote en IF"+currentLoteID5);
		currentLoteID5= loteId5;
//	alert("Current "+currentLoteID5+"  "+loteId5);
			ajax6.requestFile = 'getlote5.php?getloteId5='+loteId5;	// Specifying which file to get
	    	ajax6.onCompletion = showLoteData5;	// Specify function that will be executed after file has been found
			ajax6.runAJAX();		// Execute AJAX function			
		}	
}	
function getLoteData6()  /// Septimo lote
{
	var loteId6 = document.getElementById('lote6').value ;
	//alert ("LOT" +document.getElementById('lote6').value);
//   alert("Septimo lote "+ loteId6);
//alert("CurrentLoteID"+currentLoteID6)
	if( loteId6!=currentLoteID6){
	//    alert("Septimo lote en IF"+currentLoteID6);
		currentLoteID6= loteId6;
//	alert("Current "+currentLoteID6+"  "+loteId6);
		ajax7.requestFile = 'getlote6.php?getloteId6='+loteId6;	// Specifying which file to get
		ajax7.onCompletion = showLoteData6;	// Specify function that will be executed after file has been found
		ajax7.runAJAX();		// Execute AJAX function			
		}	
}	
function getLoteData7()  /// Octavo lote
{
	var loteId7 = document.getElementById('lote7').value ;
//	alert ("LOT" +document.getElementById('lote7').value);
//    alert("Sexto lote "+ loteId7);
//    alert("CurrentLoteID"+currentLoteID7)
 	if( loteId7!=currentLoteID7){
//	    alert("sexto lote en IF"+currentLoteID7);
 		currentLoteID7= loteId7;
 //   	alert("Current "+currentLoteID7+"  "+loteId7);
 		ajax8.requestFile = 'getlote7.php?getloteId7='+loteId7;	// Specifying which file to get
 		ajax8.onCompletion = showLoteData7;	// Specify function that will be executed after file has been found
 		ajax8.runAJAX();		// Execute AJAX function			
 		}	
 }
 function getLoteData8()  /// Noveno lote
{
	var loteId8 = document.getElementById('lote8').value ;
//	alert ("LOT" +document.getElementById('lote8').value);
 //   alert("Sexto lote "+ loteId8);
//    alert("CurrentLoteID"+currentLoteID8)
 	if( loteId8!=currentLoteID8){
//	    alert("sexto lote en IF"+currentLoteID8);
 		currentLoteID8= loteId8;
 //   	alert("Current "+currentLoteID7+"  "+loteId8);
 		ajax9.requestFile = 'getlote8.php?getloteId8='+loteId8;	// Specifying which file to get
 		ajax9.onCompletion = showLoteData8;	// Specify function that will be executed after file has been found
 		ajax9.runAJAX();		// Execute AJAX function			
 		}	
 }	
  function getLoteData9()  /// Decimo lote
{
	var loteId9 = document.getElementById('lote9').value ;
//	alert ("LOT" +document.getElementById('lote9').value);
//  alert("Sexto lote "+ loteId9);
//  alert("CurrentLoteID"+currentLoteID9)
 	if( loteId9!=currentLoteID9){
//	alert("sexto lote en IF"+currentLoteID9);
 		currentLoteID9= loteId9;
// 	alert("Current "+currentLoteID9+"  "+loteId9);
 		ajax10.requestFile = 'getlote9.php?getloteId9='+loteId9;	// Specifying which file to get
 		ajax10.onCompletion = showLoteData9;	// Specify function that will be executed after file has been found
 		ajax10.runAJAX();		// Execute AJAX function			
 		}	
 }
 
function getLoteData10()  ///  Lote Once
{
	var loteId10 = document.getElementById('lote10').value ;
//	alert ("LOT" +document.getElementById('lote10').value);
//    alert("Lote Once "+ loteId10);
//    alert("CurrentLoteID "+currentLoteID10)
 	if( loteId10!=currentLoteID10){
//   alert("Lote Once en IF "+currentLoteID10);
 		currentLoteID10= loteId10;
//   	alert("Current "+currentLoteID10+"  "+loteId10);
 		ajax11.requestFile = 'getlote10.php?getloteId10='+loteId10;	// Specifying which file to get
 		ajax11.onCompletion = showLoteData10;	// Specify function that will be executed after file has been found
 		ajax11.runAJAX();		// Execute AJAX function			
 		}	
 }	
 
function getLoteData11()  /// Lote Doce
{
	var loteId11 = document.getElementById('lote11').value ;
//alert ("LOT" +document.getElementById('lote11').value);
//  alert("Lote Doce "+ loteId11);
 //  alert("CurrentLoteID "+currentLoteID11)
	if( loteId11!=currentLoteID11){
 //  alert("Lote DOCE en IF"+currentLoteID9);
currentLoteID11= loteId11;
//    alert("Current "+currentLoteID11+"  "+loteId11);
  ajax12.requestFile = 'getlote11.php?getloteId11='+loteId11;	// Specifying which file to get
  ajax12.onCompletion = showLoteData11;	// Specify function that will be executed after file has been found
  ajax12.runAJAX();		// Execute AJAX function			
		}	
}	

function getLoteData12()  /// Lote trece
{
	var loteId12 = document.getElementById('lote12').value ;
//alert ("LOT" +document.getElementById('lote12').value);
//  alert("Lote trece "+ loteId12);
//   alert("CurrentLoteID "+currentLoteID12)
	if( loteId12!=currentLoteID12){
 //  alert("Lote TRECE en IF"+currentLoteID12);
currentLoteID12= loteId12;
 //   alert("Current "+currentLoteID12+"  "+loteId12);
  ajax13.requestFile = 'getlote12.php?getloteId12='+loteId12;	// Specifying which file to get
  ajax13.onCompletion = showLoteData12;	// Specify function that will be executed after file has been found
 ajax13.runAJAX();		// Execute AJAX function			
		}	
}	
		
function getLoteData13()  /// Lote catorce
{
	var loteId13 = document.getElementById('lote13').value ;
//alert ("LOT" +document.getElementById('lote13').value);
 // alert("Lote trece "+ loteId13);
 //  alert("CurrentLoteID "+currentLoteID13)
	if( loteId13!=currentLoteID13){
 //  alert("Lote TRECE en IF"+currentLoteID13);
currentLoteID13= loteId13;
//    alert("Current "+currentLoteID13+"  "+loteId13);
  ajax14.requestFile = 'getlote13.php?getloteId13='+loteId13;	// Specifying which file to get
  ajax14.onCompletion = showLoteData13;	// Specify function that will be executed after file has been found
 ajax14.runAJAX();		// Execute AJAX function			
		}	
}	

function getLoteData14()  /// Lote Quince
{
	var loteId14 = document.getElementById('lote14').value ;
//alert ("LOT" +document.getElementById('lote14').value);
//  alert("Lote trece "+ loteId14);
//   alert("CurrentLoteID "+currentLoteID14)
	if( loteId14!=currentLoteID14){
//   alert("Lote TRECE en IF"+currentLoteID14);
currentLoteID14= loteId14;
//    alert("Current "+currentLoteID14+"  "+loteId14);
  ajax15.requestFile = 'getlote14.php?getloteId14='+loteId14;	// Specifying which file to get
  ajax15.onCompletion = showLoteData14;	// Specify function that will be executed after file has been found
  ajax15.runAJAX();		// Execute AJAX function			
		}	
}			
		
	function showClientData()
	{
		var formObj = document.forms['factura'];	
		eval(ajax.response);
	}
	function showLoteData() // Primer lote
	{
		var formObj1 = document.forms['factura'];	
		eval(ajax1.response);
	}
	function showLoteData1() // Segundo  Lote
	{
		var formObj2 = document.forms['factura'];	
		eval(ajax2.response);
	}
	
	function showLoteData2() // Tercer lote
	{
		var formObj3 = document.forms['factura'];	
		eval(ajax3.response);
	}
	function showLoteData3() // Cuarto lote
	{
		var formObj4 = document.forms['factura'];	
		eval(ajax4.response);
	}
	
function showLoteData4() // Quinto lote
	{
		var formObj5 = document.forms['factura'];	
		eval(ajax5.response);
	}
function showLoteData5() // Sexto lote
	{
    var formObj6 = document.forms['factura'];	
	eval(ajax6.response);
	}
function showLoteData6() // Septimo lote
	{
    var formObj7 = document.forms['factura'];	
	eval(ajax7.response);
	}
function showLoteData7() // Octavo lote
	{
	 var formObj8 = document.forms['factura'];	
	 eval(ajax8.response);
	}
// Ver	
function showLoteData8() // Noveno lote
	{
	 var formObj9 = document.forms['factura'];	
	 eval(ajax9.response);
	}
function showLoteData9() // Decimo lote
	{
	 var formObj10 = document.forms['factura'];	
	 eval(ajax10.response);
	}	
function showLoteData10() // Once lote
	{
	 var formObj11 = document.forms['factura'];	
	 eval(ajax11.response);
	}	
function showLoteData11() // Docelote
	{
	 var formObj12 = document.forms['factura'];	
	 eval(ajax12.response);
	}	
function showLoteData12() // Trece lote
{
	 var formObj13 = document.forms['factura'];	
	 eval(ajax13.response);
	}			
function showLoteData13() // Catorce lote
	{
	 var formObj14 = document.forms['factura'];	
	 eval(ajax14.response);
	}

function showLoteData14() // Quince lote
	{
	 var formObj15 = document.forms['factura'];	
	 eval(ajax15.response);
	}
					
	function initFormEvents()
	{
		document.getElementById('remate_num').onblur = getClientData;
		document.getElementById('remate_num').focus();
		//alert("CLIENTE DATA"+getClientData);
		document.getElementById('lote').onblur = getLoteData;
		document.getElementById('lote').focus();
		document.getElementById('lote1').onblur = getLoteData1;
		document.getElementById('lote1').focus();
		document.getElementById('lote2').onblur = getLoteData2;
		document.getElementById('lote2').focus();
		document.getElementById('lote3').onblur = getLoteData3;
		document.getElementById('lote3').focus();
		document.getElementById('lote4').onblur = getLoteData4;
		document.getElementById('lote4').focus();
		document.getElementById('lote5').onblur = getLoteData5;
		document.getElementById('lote5').focus();
		document.getElementById('lote6').onblur = getLoteData6;
		document.getElementById('lote6').focus();
		document.getElementById('lote7').onblur = getLoteData7;
		document.getElementById('lote7').focus();
		document.getElementById('lote8').onblur = getLoteData8;
		document.getElementById('lote8').focus();
		document.getElementById('lote9').onblur = getLoteData9;
		document.getElementById('lote9').focus();
		document.getElementById('lote10').onblur = getLoteData10;
		document.getElementById('lote10').focus();
	    document.getElementById('lote11').onblur = getLoteData11;
		document.getElementById('lote11').focus();
		document.getElementById('lote12').onblur = getLoteData12;
		document.getElementById('lote12').focus();
		document.getElementById('lote13').onblur = getLoteData13;
	    document.getElementById('lote13').focus();
		document.getElementById('lote14').onblur = getLoteData14;
		document.getElementById('lote14').focus();
		//alert (document.getElementById('lote'));
		//alert("LOTE DATA"+getLoteData);
	}
	
	
	window.onload = initFormEvents;
	</script>
<!-- Hasta Aca  !-->
<script languaje="javascript">

function neto(form)
{ importe = factura.importes.value; 
   document.write(importe);
  alert (importe);

}

</script>

<link href="estilo_factura.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form id="factura" name="factura" method="post" action="">
  <table width="640" border="0" align="left" cellpadding="1" cellspacing="1" bgcolor="#003366">
    <tr>
      <td colspan="3" background="images/fondo_titulos.jpg"><div align="center"><img src="images/carga_facturas.gif" width="200" height="30" /></div></td>
    </tr>
    <tr>
      <td colspan="3" valign="top" bgcolor="#003366"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td width="22%" height="20" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Tipo comprobante </span></td>
          <td width="24%"><select name="tcomp" id="tcomp" >
            <option value="">Tipo comprobante</option>
            <?php
do {  
?>
            <option value="<?php echo $row_tipo_comprobante['codnum']?>"><?php echo $row_tipo_comprobante['descripcion']?></option>
            <?php
} while ($row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante));
  $rows = mysqli_num_rows($tipo_comprobante);
  if($rows > 0) {
      mysqli_data_seek($tipo_comprobante, 0);
	  $row_tipo_comprobante = mysqli_fetch_assoc($tipo_comprobante);
  }
?>
          </select>          </td>
          <td width="7%">&nbsp;</td>
          <td width="13%" class="ewTableHeader">&nbsp;Serie</td>
          <td width="34%"><select name="serie" id="serie">
          </select>          </td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num Factura </td>
          <td><input name="num_factura" type="num_factura" class="phpmakerlist" id="ncomp" /></td>
          <td>&nbsp;</td>
          <td class="ewTableHeader">&nbsp;Fecha fact </td>
          <td><input name="fecha_factura" type="text" id="fecha_factura" />
         <a href="javascript:showCal('Calendar4')"><img src="calendar/img.gif" width="20" height="14"  border="0"/></a></td>
        </tr>
        
        <tr>
          <td height="20" class="ewTableHeader">&nbsp;Num. remate </td>
          <td><input name="remate_num" type="text" id="remate_num" /></td>
          <td>&nbsp;</td>
          <td colspan="2" rowspan="4" valign="top" bgcolor="#0094FF" ><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#003366">
            <tr>
              <td colspan="2" bgcolor="#0094FF" align="center"><img src="images/cond_pago.gif" width="150" height="30" /></td>
            </tr>
            <tr>
              <td width="48%" bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Contado</span></td>
              <td width="52%" bgcolor="#0094FF"><input name="GrupoOpciones1" type="radio" value="opci&oacute;n" checked="checked" /></td>
            </tr>
            <tr>
              <td bgcolor="#0094FF">&nbsp;<span class="ewTableHeader">Pendiente de pago</span></td>
              <td bgcolor="#0094FF"><input type="radio" name="GrupoOpciones1" value="opci&oacute;n" /></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td height="9" class="ewTableHeader">Lugar del remate </td>
          <td><input name="lugar_remate" type="text" id="lugar_remate" /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="10" class="ewTableHeader"> Cliente </td>
          <td><select name="codnum" id="codnum">
          </select></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="20" class="ewTableHeader">Fecha de remate</td>
          <td><input name="fecha_remate" type="text" size="12" value="<?php echo $fecha_est ?>"/></td>
          <td>&nbsp;</td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3"  background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">
	  
	   
	  <table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#003366">
        <tr>
          <td width="39" rowspan="2" class="ewTableHeader">
              <div align="center">Lote</div></td>
          <td width="369" rowspan="2" class="ewTableHeader">
              <div align="center">Descripci&oacute;n</div></td>
          <td width="44" rowspan="2" class="ewTableHeader"><div align="center">Com</div></td>
          <td width="60" rowspan="2" class="ewTableHeader">
              <div align="center">Importe</div></td>
		 <td height="24" colspan="3" class="ewTableHeader"><div align="center">Impuestos</div></td>	  
        </tr>
        <tr>
          <td height="15" class="ewTableHeader"> <div align="center"><?php echo $iva_15_porcen     ?></div></td>
          <td class="ewTableHeader"><div align="center"><?php echo $iva_21_porcen     ?></div></td>
          <td class="ewTableHeader">RG 33337 </td>
        </tr>
        
		<tr>
          <td bgcolor="#0094FF"><input name="lote" type="text" id="lote" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion" type="text" class="phpmaker" id="descripcion" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision" type="text" id="comision" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision" type="text" id="comision" size="3" readonly="" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe" type="text" id="importe" size="10"   /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto" value="<?php echo $iva_15_porcen     ?>"  onclick="validarFormulario(this.form)" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto" value="<?php echo $iva_21_porcen     ?>" onclick="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote1" type="text" id="lote1" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion1" type="text" class="phpmaker" id="descripcion1" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision1" type="text" id="comision1" size="3" /></td> <?php } else { ?>
	<td bgcolor="#0094FF"><input name="comision1" type="text" id="comision1" size="3" readonly="readonly" /></td> <?php } ?>
    <td bgcolor="#0094FF"><input name="importe1" type="text" id="importe1" size="10"  onBlur="validarFormulario(this.form)"/></td>
	<td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto1" value="<?php echo $iva_15_porcen     ?>"  onclick="valor_prueba(this.form)" /></td>
	<td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto1" value="<?php echo $iva_21_porcen     ?>" onclick="valor_prueba(this.form)" /></td>
    <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion1" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote2" type="text" id="lote2" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion2" type="text" class="phpmaker" id="descripcion2" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision2" type="text" id="comision2" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision2" type="text" id="comision2" size="3" readonly="readonly" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe2" type="text" id="importe2" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto2" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto2" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion2" value="checkbox" /></td>
        </tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote3" type="text" id="lote3" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion3" type="text" class="phpmaker" id="descripcion3" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision3" type="text" id="comision3" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision3" type="text" id="comision3" size="3"  readonly="readonly"/></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe3" type="text" id="importe3" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto3" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto3" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion3" value="checkbox" /></td>
        </tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote4" type="text" id="lote4" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion4" type="text" class="phpmaker" id="descripcion4" size="65" /></td>
           <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision4" type="text" id="comision4" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision4" type="text" id="comision4" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe4" type="text" id="importe4" size="10" onBlur="validarFormulario(this.form)"/></td>
		   <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto4" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto4" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion4" value="checkbox" /></td>
        </tr>
		  <tr>
          <td bgcolor="#0094FF"><input name="lote5" type="text" id="lote5" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion5" type="text" class="phpmaker" id="descripcion5" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision5" type="text" id="comision5" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision5" type="text" id="comision5" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe5" type="text" id="importe5" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto5" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto5" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion5" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote6" type="text" id="lote6" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion6" type="text" class="phpmaker" id="descripcion6" size="65" /></td>
           <td bgcolor="#0094FF"><input name="comision6" type="text" id="comision6" size="3" /></td> 
          <td bgcolor="#0094FF"><input name="importe6" type="text" id="importe6" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto6" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto6" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion6" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote7" type="text" id="lote7" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion7" type="text" class="phpmaker" id="descripcion7" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision7" type="text" id="comision7" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision7" type="text" id="comision7" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe7" type="text" id="importe7" size="10" onBlur="validarFormulario(this.form)"/></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto7" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto7" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion7" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote8" type="text" id="lote8" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion8" type="text" class="phpmaker" id="descripcion8" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision8" type="text" id="comision8" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision8" type="text" id="comision8" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe8" type="text" id="importe8" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto8" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto8" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion8" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote9" type="text" id="lote9" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion9" type="text" class="phpmaker" id="descripcion9" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision9" type="text" id="comision9" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision9" type="text" id="comision9" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe9" type="text" id="importe9" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto9" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto9" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion9" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote10" type="text" id="lote10" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion10" type="text" class="phpmaker" id="descripcion10" size="65" /></td>
         <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision10" type="text" id="comision10" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision10" type="text" id="comision10" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe10" type="text" id="importe10" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto10" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto10" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion10" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote11" type="text" id="lote11" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion11" type="text" class="phpmaker" id="descripcion11" size="65" /></td>
          <?php if($nivel=='9') { ?> <td bgcolor="#0094FF"><input name="comision11" type="text" id="comision11" size="3" /></td> <?php } else { ?>
		  <td bgcolor="#0094FF"><input name="comision11" type="text" id="comision11" size="3" /></td> <?php } ?>
          <td bgcolor="#0094FF"><input name="importe11" type="text" id="importe11" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto11" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto11" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion11" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote12" type="text" id="lote12" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion12" type="text" class="phpmaker" id="descripcion12" size="65" /></td>
          <td bgcolor="#0094FF"><input name="comision12" type="text" id="comision12" size="3" /></td>
          <td bgcolor="#0094FF"><input name="importe12" type="text" id="importe12" size="10" onBlur="validarFormulario(this.form)"/></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto12" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto12" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion12" value="checkbox" /></td>
        </tr>
		 <tr>
          <td bgcolor="#0094FF"><input name="lote13" type="text" id="lote13" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion13" type="text" class="phpmaker" id="descripcion13" size="65" /></td>
          <td bgcolor="#0094FF"><input name="comision13" type="text" id="comision13" size="3" /></td>
          <td bgcolor="#0094FF"><input name="importe13" type="text" id="importe13" size="10" onBlur="validarFormulario(this.form)"/></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto13" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto13" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion13" value="checkbox" /></td>
        </tr> <tr>
          <td bgcolor="#0094FF"><input name="lote14" type="text" id="lote14" size="5" /></td>
          <td bgcolor="#0094FF"><input name="descripcion14" type="text" class="phpmaker" id="descripcion14" size="65" /></td>
          <td bgcolor="#0094FF"><input name="comision14" type="text" id="comision14" size="3" /></td>
          <td bgcolor="#0094FF"><input name="importe14" type="text" id="importe14" size="10" onBlur="validarFormulario(this.form)"/></td>
		 <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto14" value="opci�n" /></td>
		  <td bgcolor="#0094FF" width="29" align="center"><input type="radio" name="impuesto14" value="opci�n" /></td>
          <td bgcolor="#0094FF" width="49" align="center"><input type="checkbox" name="resolucion14" value="checkbox" /></td>
        </tr>
      </table> 
      </td>
    </tr>
    <tr>
      <td width="71" height="20" valign="top" class="ewTableHeader">&nbsp;Leyenda</td>
      <td width="280" rowspan="3" valign="top"><textarea name="textarea" cols="55" rows="4"></textarea></td>
      <td width="281" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        <tr>
          <td width="48%">&nbsp;<span class="ewTableHeader">Resolucion</span></td>
          <td width="52%"><input name="tieneresol" type="checkbox" id="tieneresol" value="si" onclick="resol(this.form)"   /></td>
        </tr>
        <tr>
          <td colspan="2"><div align="center"><img src="images/con_pago.gif" width="140" height="28" /></div></td>
          </tr>
        <tr>
          <td>&nbsp;<span class="ewTableHeader">Efectivo</span></td>
		  
          <td><input name="pago_contado" type="checkbox" onclick="forma_pago(this.form)" value="si" checked="checked"/></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td height="20" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" background="images/separador3.gif">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#003366"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#0094FF">
        <tr>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php echo $iva_15_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Neto <?php echo $iva_21_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Comision </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php echo $iva_15_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Iva <?php echo $iva_21_porcen ?> %</div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">R. G. 3337 </div></td>
          <td height="20" background="images/fonod_lote.jpg" class="ewTableHeader"><div align="center">Total </div></td>
        </tr>
        
        <tr>
          <td><input name="neto_105" type="text" id="neto_105" size="12" /></td>
          <td><input name="neto_21" type="text" id="neto_21" size="12" /></td>
          <td><input name="comisiontotal" id="comisiontotal" type="text" size="12" /></td>
          <td><input name="imp_105" type="text" size="12" /></td>
          <td><input name="imp_21"  type="text" size="12" /></td>
          <td><input name="res_3337" type="text" id="res_3337" size="10" /></td>
          <td><input name="tot_general" type="text" id="tot_general" size="15" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#ECE9D8"><table width="100%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td><div align="center">
            <input type="submit" name="Submit" value="Salir" />
          </div></td>
          <td><div align="center">
            <input type="submit" name="Submit2" value="Imprimir" />
          </div></td>
          <td><div align="center">
            <input type="submit" name="Submit3" value="Grabar" />
          </div></td>
        </tr>
      </table></td>
    </tr>
  </table>
</form>


<form name="elformulario" action="medios_pago.php">
<input name="factura3" type="text" value="" />
<input name="total" type ="text"   />
<input name="tip_comprobante" type ="text"   />
<input name="fecha_de_fact" type="text"   />
<input name="remate_numero" type="text"   />
</form>


 <script type="text/javascript"> 

chainedSelects = new DHTMLSuite.chainedSelect();   // Creating object of class DHTMLSuite.chainedSelects 
chainedSelects.addChain('tcomp','serie','includes/getserie.php'); 
//chainedSelects.addChain('ncomp','datos','includes/getremate.php'); 

chainedSelects.init(); 

</script> 
	
</body>
</html>
<?php
mysql_free_result($tipo_comprobante);

mysql_free_result($num_remates);

//mysql_free_result($serie);
?>
