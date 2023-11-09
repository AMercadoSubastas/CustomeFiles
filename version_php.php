<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
</head>
<?php
if (strlen(decbin(~0)) === 32) {
    echo "Es de 32 bits";
} else {
    echo "Es de 64 bits";
}
?>
<body>
</body>
</html>