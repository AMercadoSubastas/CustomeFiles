<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Procesando el archivo enviado</title>
<style type="text/css">
*{ font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif}
.main{ margin:auto; border:1px solid #7C7A7A; width:50%; text-align:left; padding:30px; background:#85c587}
input[type=submit]{ background:#6ca16e; width:100%;
    padding:5px 15px; 
    background:#ccc; 
    cursor:pointer;
	font-size:16px;
   
}
table td{ padding:5px;}
</style>
</head>

<body bgcolor="#bed7c0">
<div class="main">
<h1>Subir archivo con PHP:</h1>
<?php
$directorio = '';
$subir_archivo = $directorio.basename($_FILES['subir_archivo']['name']);
echo "<div>";
if (move_uploaded_file($_FILES['subir_archivo']['tmp_name'], $subir_archivo)) {
      echo "El archivo se cargó correctamente.<br><br>";
	   echo"<a href='".$subir_archivo."' target='_blank'><img src='".$subir_archivo."' width='150'></a>";
    ?>
    <div style="border:1px solid #000000; text-transform:uppercase">  
    <h3 align="center"><div align="center"><a href="lista_import_lotes.php">Ir al programa de carga de lotes </a></div></h3></div>
    <?php
    } else {
       echo "La subida ha fallado";
    ?>
    <br>
        <div style="border:1px solid #000000; text-transform:uppercase">  
        <h3 align="center"><div align="center"><a href="cargar.html">Volver </a></div></h3></div>
    <?php
    }
    echo "</div>";
?>
 
</div>
	</body>
</html>