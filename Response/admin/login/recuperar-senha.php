<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Blacksuit">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/images/favicon.ico">

        <!-- App title -->
        <title><?=USER_NAME?> - Painel de Controle - Recuperar senha</title>

        <!-- App CSS -->
        <link href="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/modernizr.min.js"></script>

    </head>
    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="text-center">
                <a href="www.blacksuit.com.br" class="logo"><img src="<?=URL_BASE?>/Response/admin/_includes/images/brand/blacksuit.png" alt="Blacksuit" width="250"></a>
                <h5 class="text-muted m-t-0 font-600">Painel de Controle</h5>
            </div>
        	<div class="m-t-40 card-box">
                <div class="text-center">
                    <h4 class="text-uppercase font-bold m-b-0">Recuperar senha</h4>

					<p class="text-muted m-b-0 font-13 m-t-20">Digite seu email e enviaremos instruções sobre como você poderá recuperar a sua senha.</p>
                </div>

                <?php
                // Notificações
                // Impressão dos códigos de notificações
                if (isset($_SESSION['data_notificacoes']['adminRecoverPwd'])) {
                    ?><div class="text-center m-t-15"><?php
                    foreach ($_SESSION['data_notificacoes']['adminRecoverPwd'] as $item) {
                        echo $item;
                    }
                    ?></div<?php
                    unset($_SESSION['data_notificacoes']['adminRecoverPwd']);
                }
                ?>

                <div class="panel-body">
                    <form class="form-horizontal m-t-20" method="post" action="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/recuperar-senha">

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="email" name="email" required="" placeholder="Digite o email">
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20 m-b-0">
                            <div class="col-xs-12">
                                <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" type="submit">Recuperar senha</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
            <!-- end card-box -->

			<div class="row">
				<div class="col-sm-12 text-center">
					<p class="text-muted"><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/login" class="text-primary m-l-5"><b>Clique aqui</b></a> caso queira ir para a página de login.</p>
				</div>
			</div>

        </div>
        <!-- end wrapper page -->


    	<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/jquery.min.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/bootstrap.min.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/detect.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/fastclick.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/jquery.slimscroll.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/jquery.blockUI.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/waves.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/wow.min.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/jquery.nicescroll.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/jquery.scrollTo.min.js"></script>

        <!-- App js -->
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/jquery.core.js"></script>
        <script src="<?=URL_BASE?>/Response/<?=$this->core->action_urlName?>/_includes/js/jquery.app.js"></script>

	</body>
</html>