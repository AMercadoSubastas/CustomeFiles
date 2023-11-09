<?php
require_once 'Connections/amercado.php';

 $valorCampo1 = $_POST['username'];

 $nameUser = "SELECT * FROM `usuarios` WHERE usuario = '$valorCampo1'";

 $nombreusuario = mysqli_query($amercado, $nameUser) or die ("ERROR LEYENDO USUARIOS 62");
 var_dump($nombreusuario);
 $rows_usu = mysqli_fetch_array($nombreusuario);
 $id_usu = $rows_usu['codnum'];

session_start();
$_SESSION['id'] = $id_usu;
?>