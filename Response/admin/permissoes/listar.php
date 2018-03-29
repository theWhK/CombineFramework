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
                            <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/criar"><button type="button" class="btn btn-success waves-effect waves-light" aria-expanded="false">Criar permissão</button></a>
                        </div>
                        <h4 class="page-title">Listagem de Permissões de Usuário</h4>
                    </div>
                </div>

                <div class="row">
                <?php
                foreach ($permissoes_hierarquia as $comandoPai) {
                ?>
                    <div class="col-sm-12 col-lg-6">
                        <div class="card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="zmdi zmdi-more-vert"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/editar/<?=$comandoPai['id']?>">Editar permissão</a></li>
                                    <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/apagar/<?=$comandoPai['id']?>">Apagar permissão</a></li>
                                </ul>
                            </div>

                            <h4 class="header-title m-t-0 m-b-30">[<?=$comandoPai['rotulo']?>] <?=$comandoPai['nome']?></h4>

                            <? if ($comandoPai['descricao']) { ?>
                            <p class="text-muted font-13 m-b-15">
                                <?=$comandoPai['descricao']?>
                            </p>
                            <? } ?>

                            <?php
                            if (is_array($comandoPai['listaMetodos'])) {
                            ?>
                            <table class="table table-condensed table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nome</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($comandoPai['listaMetodos'] as $metodo) {
                                    ?>
                                    <tr title="<?=$metodo['descricao']?>">
                                        <th scope="row"><?=$metodo['id']?></th>
                                        <td><?=$metodo['nome']?></td>
                                        <td class="acoes-coluna">
                                            <a class="acao acao-editar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/editar/<?=$metodo['id']?>"><i title="Editar permissão" class="ti-pencil-alt"></i></a>
                                            <a class="acao acao-apagar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/apagar/<?=$metodo['id']?>" data-anchor-alert="confirmation"><i title="Apagar permissão" class="ti-trash"></i></a>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                            } else {
                            ?>
                            <p class="font-13 m-b-15">
                                Não há permissões de método para este comando ainda. <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/criar">Clique aqui</a> para criar um novo.
                            </p>
                            <?php
                            }
                            ?>

                            <?php
                            if (is_array($comandoPai['listaComandosFilho'])) {
                                foreach ($comandoPai['listaComandosFilho'] as $comandoFilho) {
                                ?>
                                <div class="card-box m-t-30">
                                    <div class="dropdown pull-right">
                                        <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                        aria-expanded="false">
                                            <i class="zmdi zmdi-more-vert"></i>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/editar/<?=$comandoFilho['id']?>">Editar permissão</a></li>
                                            <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/apagar/<?=$comandoFilho['id']?>">Apagar permissão</a></li>
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30">[<?=$comandoFilho['rotulo']?>] <?=$comandoFilho['nome']?></h4>

                                    <p class="text-muted font-13 m-b-15">
                                        <?=$comandoFilho['descricao']?>
                                    </p>
                                    
                                    <?php
                                    if (is_array($comandoFilho['listaMetodos'])) {
                                    ?>
                                    <table class="table table-condensed table-hover m-0">
                                                                                
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($comandoFilho['listaMetodos'] as $metodo) {
                                            ?>
                                            <tr title="<?=$metodo['descricao']?>">
                                                <th scope="row"><?=$metodo['id']?></th>
                                                <td><?=$metodo['nome']?></td>
                                                <td class="acoes-coluna">
                                                    <a class="acao acao-editar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/editar/<?=$metodo['id']?>"><i title="Editar permissão" class="ti-pencil-alt"></i></a>
                                                    <a class="acao acao-apagar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/apagar/<?=$metodo['id']?>" data-anchor-alert="confirmation"><i title="Apagar permissão" class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    } else {
                                    ?>
                                    <p class="font-13 m-b-15">
                                        Não há permissões de método para este comando ainda. <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/criar">Clique aqui</a> para criar um novo.
                                    </p>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>
                </div>

                <?php
                if (isset($permissoes_metodo) && !empty($permissoes_metodo)) {
                ?>
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Permissões de método soltas</h4>

                    <p class="text-muted font-13 m-t-30">Abaixo encontram-se as permissões de método cujo comando-pai não possui permissão de comando vinculada. Recomenda-se criar uma.</p>

                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th data-priority="2">#</th>
                                        <th data-priority="1">Nome</th>
                                        <th data-priority="2">Descrição</th>
                                        <th data-priority="1">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($permissoes_metodo as $permissao) {
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$permissao['id']?></th>
                                        <td><strong><?=$permissao["nome"]?></strong></td>
                                        <td><strong><?=$permissao["descricao"]?></strong></td>
                                        <td class="acoes-coluna">
                                            <a class="acao acao-editar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/editar/<?=$permissao['id']?>"><i title="Editar permissão" class="ti-pencil-alt"></i></a>
                                            <a class="acao acao-apagar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/apagar/<?=$permissao['id']?>" data-anchor-alert="confirmation"><i title="Apagar permissão" class="ti-trash"></i></a>
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
                        <h3 class="panel-title">Não há permissões de método soltas!</h3>
                    </div>
                </div>
                <?php
                }
                ?>

                <?php
                if (isset($permissoes_custom) && !empty($permissoes_custom)) {
                ?>
                <div class="card-box">
                    <h4 class="header-title m-t-0 m-b-30">Permissões customizadas</h4>

                    <div class="table-rep-plugin">
                        <div class="table-responsive" data-pattern="priority-columns">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th data-priority="2">#</th>
                                        <th data-priority="1">Nome</th>
                                        <th data-priority="2">Descrição</th>
                                        <th data-priority="1">Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($permissoes_custom as $permissao) {
                                    ?>
                                    <tr>
                                        <th scope="row"><?=$permissao['id']?></th>
                                        <td><?=$permissao["nome"]?></td>
                                        <td><?=$permissao["descricao"]?></td>
                                        <td class="acoes-coluna">
                                            <a class="acao acao-editar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/editar/<?=$permissao['id']?>"><i title="Editar permissão" class="ti-pencil-alt"></i></a>
                                            <a class="acao acao-apagar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/apagar/<?=$permissao['id']?>" data-anchor-alert="confirmation"><i title="Apagar permissão" class="ti-trash"></i></a>
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
                        <h3 class="panel-title">Não há permissões personalizadas!</h3>
                    </div>
                </div>
                <?php
                }
                ?>

                <?php require PATH_ABS.'/Response/admin/_includes/php/footer.php'; ?>

            </div>
            <!-- end container -->

        </div>

        

    <?php require PATH_ABS . '/Response/admin/_includes/php/foot.php'; ?>
    <script>
    jQuery('.usuario-acao-apagar').click((event) => {
        event.preventDefault();

        swal({
            title: 'Você tem certeza?',
            text: 'Não será possível reverter esta ação.',
            icon: 'warning',
            buttons: {
                yep: {
                    text: "Sim"
                },
                nope: {
                    text: "Não"
                }
            }
        })
        .then((value) => {
            switch (value) {
                case 'yep':
                    window.location = event.currentTarget.href;
                break;
                case 'nope':
                    return false;
                break;
            }
        });
    })
    </script>
    </body>
</html>