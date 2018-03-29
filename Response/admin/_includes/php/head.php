<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Blacksuit">

        <link rel="shortcut icon" href="<?=URL_BASE?>/Response/admin/_includes/images/favicon.ico">

        <title><?=USER_NAME?> - Painel de Controle - <?=$this->commandName?></title>

        <!-- Table css -->
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css" rel="stylesheet" type="text/css" media="screen">

        <?php
        /*
        <!-- Sweet Alert css -->
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-sweetalert/sweet-alert.css" rel="stylesheet" type="text/css" />
        */
        ?>

        <!-- Plugins css-->
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/multiselect/css/multi-select.css"  rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css">
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/select2/dist/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/switchery/switchery.min.css" rel="stylesheet" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
		<link href="<?=URL_BASE?>/Response/admin/_includes/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
		<link href="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
		<link href="<?=URL_BASE?>/Response/admin/_includes/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

        <link href="<?=URL_BASE?>/Response/admin/_includes/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- Append style -->
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/appendStyle.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?=URL_BASE?>/Response/admin/_includes/js/modernizr.min.js"></script>

        <!-- Variáveis globais do JS -->
        <script>
            const URL_BASE = "<?=URL_BASE?>";
        </script>