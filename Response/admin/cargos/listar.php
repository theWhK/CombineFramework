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
                            <a href="<?=URL_BASE?>/admin/cargos/criar"><button type="button" class="btn btn-success waves-effect waves-light" aria-expanded="false">Criar novo cargo/departamento</button></a>
                        </div>
                        <h4 class="page-title">Listagem de Cargos</h4>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        if (isset($cargos) && !empty($cargos)) {
                        ?>
                        <div class="card-box">
                            <div class="table-rep-plugin">
                                <div class="table-responsive" data-pattern="priority-columns">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th data-priority="1">Nome</th>
                                                <th data-priority="2">Descrição</th>
                                                <th data-priority="1">Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $index = 0;

                                            foreach ($departamentos as $departamento) {
                                            
                                            // Coloca o elemento de espaçamento caso não seja o primeiro departamento
                                            if ($index != 0) {
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr>
                                                <td><strong><?=$departamento["nome"]?></strong></td>
                                                <td><strong><?=substr($departamento["descricao"], 0, 80)?>...</strong></td>
                                                <td class="acoes-coluna">
                                                    <a class="acao acao-editar" href="<?=URL_BASE?>/admin/cargos/editar/<?=$departamento['id']?>"><i title="Editar departamento" class="ti-pencil-alt"></i></a>
                                                    <a class="acao acao-apagar" href="<?=URL_BASE?>/admin/cargos/apagar/<?=$departamento['id']?>" data-anchor-alert="confirmation"><i title="Apagar departamento" class="ti-trash"></i></a>
                                                </td>
                                            </tr>
                                                <?php

                                                // Limpa o conjunto de cargos do departamento
                                                $cargosDoDepartamento = array();

                                                // Varre os cargos
                                                foreach ($cargos as $cargo) {
                                                    // Se o cargo pertencer ao departamento em voga, insere-o no conjunto
                                                    if ($cargo['id_depart'] == $departamento['id']) {
                                                        array_push($cargosDoDepartamento, $cargo);
                                                    }
                                                }

                                                // Imprime os cargos do departamento
                                                foreach ($cargosDoDepartamento as $cargo) { 
                                                ?>
                                                <tr>
                                                    <td><?=$cargo["nome"]?></td>
                                                    <td><?=substr($cargo["descricao"], 0, 80)?>...</td>
                                                    <td class="acoes-coluna">
                                                        <a class="acao acao-editar" href="<?=URL_BASE?>/admin/cargos/editar/<?=$cargo['id']?>"><i title="Editar cargo" class="ti-pencil-alt"></i></a>
                                                        <a class="acao acao-apagar" href="<?=URL_BASE?>/admin/cargos/apagar/<?=$cargo['id']?>" data-anchor-alert="confirmation"><i title="Apagar cargo" class="ti-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                                }
                                                ?>
                                            <?php
                                            $index++;
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
                                <h3 class="panel-title">Não há cargos!</h3>
                            </div>
                            <div class="panel-body">
                                <p>
                                    Você não possui cargos cadastrados no sistema. Para criar o primeiro cargo, utilize o botão "Criar novo cargo/departamento" à direita, logo acima.
                                </p>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- End row -->

                <?php require PATH_ABS.'/Response/admin/_includes/php/footer.php'; ?>

            </div>
            <!-- end container -->



            <!-- Right Sidebar -->
            <div class="side-bar right-bar">
                <a href="javascript:void(0);" class="right-bar-toggle">
                    <i class="zmdi zmdi-close-circle-o"></i>
                </a>
                <h4 class="">Notifications</h4>
                <div class="notification-list nicescroll">
                    <ul class="list-group list-no-border user-list">
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-2.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">Michael Zenaty</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-info">
                                    <i class="zmdi zmdi-account"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">New Signup</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">5 hours ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-pink">
                                    <i class="zmdi zmdi-comment"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">New Message received</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">1 day ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item active">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="assets/images/users/avatar-3.jpg" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">James Anderson</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 days ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="list-group-item active">
                            <a href="#" class="user-list-item">
                                <div class="icon bg-warning">
                                    <i class="zmdi zmdi-settings"></i>
                                </div>
                                <div class="user-desc">
                                    <span class="name">Settings</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">1 day ago</span>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <!-- /Right-bar -->

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