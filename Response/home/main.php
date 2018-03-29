<?php
/*
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Página Inicial</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=URL_BASE?>/Response/home/main.css" />
</head>
<body>
    <h1>Eita home</h1>
    <pre>
    <?php var_dump($parameters) ?>
    </pre>
</body>
</html>