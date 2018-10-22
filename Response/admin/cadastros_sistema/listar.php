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
                            <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/comando-criar"><button type="button" class="btn btn-success waves-effect waves-light" aria-expanded="false">Criar novo comando</button></a>
                            <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/metodo-criar"><button type="button" class="btn btn-success waves-effect waves-light" aria-expanded="false">Criar novo método</button></a>
                        </div>
                        <h4 class="page-title">Listagem de Cadastros do Sistema</h4>
                    </div>
                </div>

                <div class="row">
                <?php
                foreach ($itens as $comandoPai) {
                ?>
                    <div class="col-sm-12 col-lg-6">
                        <div class="card-box">
                            <div class="dropdown pull-right">
                                <a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="zmdi zmdi-more-vert"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/comando-editar/<?=$comandoPai['id']?>">Editar comando</a></li>
                                    <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/comando-apagar/<?=$comandoPai['id']?>">Apagar comando</a></li>
                                </ul>
                            </div>

                            <h4 class="header-title m-t-0 m-b-30">
                                <?php if (isset($comandoPai['classeIcone'])) { ?>
                                    <i class="<?=$comandoPai['classeIcone']?>"></i>
                                <?php } ?>
                                <?=$comandoPai['rotulo']?>
                            </h4>

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
                                        <th>Ícone</th>
                                        <th>Rótulo</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($comandoPai['listaMetodos'] as $metodo) {
                                    ?>
                                    <tr title="<?=$metodo['rotulo']?>">
                                        <th scope="row"><?=$metodo['id']?></th>
                                        <td><i class="<?=$metodo['classeIcone']?>"></i></td>
                                        <td><?=$metodo['rotulo']?></td>
                                        <td class="acoes-coluna">
                                            <a class="acao acao-editar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/metodo-editar/<?=$metodo['id']?>"><i title="Editar método" class="ti-pencil-alt"></i></a>
                                            <a class="acao acao-apagar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/metodo-apagar/<?=$metodo['id']?>" data-anchor-alert="confirmation"><i title="Apagar método" class="ti-trash"></i></a>
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
                                Não há métodos para este comando ainda. <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/metodo-criar">Clique aqui</a> para criar um novo.
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
                                            <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/comando-editar/<?=$comandoFilho['id']?>">Editar comando</a></li>
                                            <li><a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/comando-apagar/<?=$comandoFilho['id']?>">Apagar comando</a></li>
                                        </ul>
                                    </div>

                                    <h4 class="header-title m-t-0 m-b-30">
                                        <?php if (isset($comandoFilho['classeIcone'])) { ?>
                                            <i class="<?=$comandoFilho['classeIcone']?>"></i>
                                        <?php } ?>
                                        <?=$comandoFilho['rotulo']?>
                                    </h4>

                                    <? if ($comandoFilho['descricao']) { ?>
                                    <p class="text-muted font-13 m-b-15">
                                        <?=$comandoFilho['descricao']?>
                                    </p>
                                    <? } ?>
                                    
                                    <?php
                                    if (is_array($comandoFilho['listaMetodos'])) {
                                    ?>
                                    <table class="table table-condensed table-hover m-0"> 
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ícone</th>
                                                <th>Rótulo</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($comandoFilho['listaMetodos'] as $metodo) {
                                            ?>
                                            <tr title="<?=$metodo['rotulo']?>">
                                                <th scope="row"><?=$metodo['id']?></th>
                                                <td><i class="<?=$metodo['classeIcone']?>"></i></td>
                                                <td><?=$metodo['rotulo']?></td>
                                                <td class="acoes-coluna">
                                                    <a class="acao acao-editar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/metodo-editar/<?=$metodo['id']?>"><i title="Editar método" class="ti-pencil-alt"></i></a>
                                                    <a class="acao acao-apagar" href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/metodo-apagar/<?=$metodo['id']?>" data-anchor-alert="confirmation"><i title="Apagar método" class="ti-trash"></i></a>
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
                                        Não há permissões de método para este comando ainda. <a href="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/metodo-criar">Clique aqui</a> para criar um novo.
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