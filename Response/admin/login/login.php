<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="<?=URL_BASE?>/Response/admin/_includes/images/favicon.ico">

        <!-- App title -->
        <title><?=USER_NAME?> - Painel de Controle - Área Restrita</title>

        <!-- App CSS -->
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/components.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_BASE?>/Response/admin/_includes/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?=URL_BASE?>/Response/admin/_includes/js/modernizr.min.js"></script>
        
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
                    <h4 class="text-uppercase font-bold m-b-0">Área Restrita</h4>
                </div>
                <?php
                // Notificações
                // Impressão dos códigos de notificações
                if (isset($_SESSION['data_notificacoes']['adminLogin'])) {
                    ?><div class="text-left m-t-15"><?php
                    foreach ($_SESSION['data_notificacoes']['adminLogin'] as $item) {
                        echo $item;
                    }
                    ?></div<?php
                    unset($_SESSION['data_notificacoes']['adminLogin']);
                }
                ?>
                <div class="panel-body">
                    <form class="form-horizontal m-t-20" role="form" action="<?=URL_BASE?>/<?=$this->core->action_urlName?>/login" method="post">

                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="usuario" type="text" required="" value="<?=$data['usuario']?>" placeholder="Nome de usuário">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="senha" type="password" required="" placeholder="Senha">
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="col-xs-12">
                                <div class="checkbox checkbox-custom">
                                    <input id="checkbox-signup" type="checkbox" name="rememberMe">
                                    <label for="checkbox-signup">
                                        Lembre de mim
                                    </label>
                                </div>

                            </div>
                        </div>
                        
                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12">
                                <button class="btn btn-custom btn-bordred btn-block waves-effect waves-light" name="submit" type="submit">Entrar</button>
                            </div>
                        </div>
                        
                        <div class="form-group m-t-30 m-b-0">
                            <div class="col-sm-12">
                                <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/login/recuperar-senha" class="text-muted"><i class="fa fa-lock m-r-5"></i> Esqueceu sua senha?</a>
                            </div>
                        </div>
                        
                    </form>

                </div>
            </div>
            <!-- end card-box-->
            
        </div>
        <!-- end wrapper page -->
        

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.min.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/bootstrap.min.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/detect.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/fastclick.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.slimscroll.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.blockUI.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/waves.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/wow.min.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.nicescroll.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.scrollTo.min.js"></script>

        <!-- App js -->
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.core.js"></script>
        <script src="<?=URL_BASE?>/Response/admin/_includes/js/jquery.app.js"></script>
	
	</body>
</html>