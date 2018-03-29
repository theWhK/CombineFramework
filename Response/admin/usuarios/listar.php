<?php require PATH_ABS . '/Response/admin/_includes/php/head.php'; ?>
    </head>

    <body>

        <?php require PATH_ABS . '/Response/admin/_includes/php/header.php'; ?>


        <div class="wrapper">
            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-15">
                            <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/criar"><button type="button" class="btn btn-success waves-effect waves-light" aria-expanded="false">Criar novo usuário</button></a>
                        </div>
                        <h4 class="page-title">Listagem de Usuários</h4>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        if (isset($usuarios) && !empty($usuarios)) {
                        ?>
                        <div class="card-box">
                            <div class="table-rep-plugin">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table class="table  table-striped">
                                        <thead>
                                            <tr>
                                                <th data-priority="1">Nome/Sobrenome</th>
                                                <th data-priority="2">Email</th>
                                                <th data-priority="2">Telefone</th>
                                                <th data-priority="1">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($usuarios as $item) { 
                                            ?>
                                            <tr>
                                                <td><?=$item["nome"]?> <?=$item["sobrenome"]?></td>
                                                <td><?=$item["email"]?></td>
                                                <td><?=$item["telefone"]?></td>
                                                <td class="acoes-coluna">
                                                    <a class="acao acao-editar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/editar/<?=$item['id']?>"><i title="Editar usuário" class="ti-pencil-alt"></i></a>
                                                    <a class="acao acao-apagar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/apagar/<?=$item['id']?>" data-anchor-alert="confirmation"><i title="Apagar usuário" class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                        } else {
                        ?>
                        <div class="panel panel-color panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Não há usuários!</h3>
                            </div>
                            <div class="panel-body">
                                <p>
                                    Você não possui usuários cadastrados no sistema. Para criar o primeiro usuário, utilize o botão "Criar novo usuário" à direita, logo acima.
                                </p>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- End row -->

                <?php require PATH_ABS . '/Response/admin/_includes/php/footer.php'; ?>
            </div>
            <!-- end container -->

        </div>

    <?php require PATH_ABS . '/Response/admin/_includes/php/foot.php'; ?>
    </body>
</html>