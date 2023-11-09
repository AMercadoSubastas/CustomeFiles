<?php
set_time_limit(0); // Para evitar el timeout
set_include_path(get_include_path() . PATH_SEPARATOR . '../../PHP/pear');
//require_once('../../PHP/pear/Net/SFTP.php');
# Comprobamos que se haya enviado algo desde el formulario
if (isset($_GET['archivo']))
    $archivo = $_GET['archivo'];
//echo "ARCHIVO =    ".$archivo."   ";
if (isset($archivo))

{
    $payload = $archivo;
    $ch = curl_init( "https://admin.adrianmercado.com.ar/api/subastas/alta-masiva" );
    # Setup request to send json via POST.

    curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    # Return response instead of printing.
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    # Send request.
    $result = curl_exec($ch);
    curl_close($ch);

}

else {
    echo "NO EXISTE EL ARCHIVO ";
}

?>
