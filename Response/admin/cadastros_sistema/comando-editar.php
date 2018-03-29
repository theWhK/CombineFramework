<?php require PATH_ABS . '/Response/admin/_includes/php/head.php'; ?>
    </head>

    <body>

        <?php require PATH_ABS . '/Response/admin/_includes/php/header.php'; ?>

        
        <div class="wrapper">
            <div class="container">
                <form role="form" method="post" action="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->commandArchive?>/comando-editar/<?=$id_edicao?>">

                    <input type="hidden" name="id" value="<?=$id_edicao?>">

                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-group pull-right m-t-15">
                                <button type="submit" name="submit" class="btn btn-success waves-effect waves-light pull-right">Salvar</button>
                            </div>

                            <h4 class="page-title">Editar Comando</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">

                                <h4 class="header-title m-t-0 m-b-30">Informações Principais</h4>

                                <p class="text-purple m-b-30 font-13">Os campos com asterisco (*) são de preenchimento obrigatório.</p>

                                <div class="row">

                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="rotulo">Rótulo *</label>
                                                <div class="col-md-9">
                                                    <input name="rotulo" type="text" id="rotulo" class="form-control <?php if (isset($data['flag']['rotulo'])){echo 'parsley-error';} ?>" placeholder="Rótulo" value="<?=$data['rotulo']?>">
                                                    <?php if (isset($data['flag']['rotulo'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['rotulo'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="rotulo">Comando ativado?</label>
                                                <div class="col-md-9">
                                                    <div class="checkbox checkbox-success">
                                                    <input id="status" name="status" type="checkbox" value="ativado" <?php if (isset($data['status'])) { ?>checked<?php } ?>>
                                                        <label for="status">
                                                            Ativado
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="descricao">Descrição</label>
                                                <div class="col-md-9">
                                                    <textarea name="descricao" id="descricao" class="form-control <?php if (isset($data['flag']['descricao'])){echo 'parsley-error';} ?>" rows="3"><?=$data['descricao']?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="id_comando_pai">Comando-pai *</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="id_comando_pai" id="id_comando_pai">
                                                        <option value="0">Este item é um comando-pai</option>
                                                        <?php
                                                        foreach ($comandosPai as $item) {
                                                        ?>
                                                        <option 
                                                                value="<?=$item['id']?>"
                                                                <?php
                                                                if ($item['id'] == $data['id_comando_pai']) {
                                                                ?>
                                                                selected
                                                                <?php
                                                                }
                                                                ?>
                                                                ><?=$item['rotulo']?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="urlAmigavel">URL Amigável *</label>
                                                <div class="col-md-9">
                                                    <input name="urlAmigavel" type="text" id="urlAmigavel" class="form-control <?php if (isset($data['flag']['urlAmigavel'])){echo 'parsley-error';} ?>" placeholder="urlamigavel" value="<?=$data['urlAmigavel']?>">
                                                    <?php if (isset($data['flag']['urlAmigavel'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['urlAmigavel'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                        if (in_array('alreadyExists', $data['flag']['urlAmigavel'])) {
                                                            ?><li class="parsley-required">Esta URL amigável já existe. Utilize outro.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="nomeArquivo">Nome do arquivo *</label>
                                                <div class="col-md-9">
                                                    <input name="nomeArquivo" type="text" id="nomeArquivo" class="form-control <?php if (isset($data['flag']['nomeArquivo'])){echo 'parsley-error';} ?>" placeholder="Nome do arquivo" value="<?=$data['nomeArquivo']?>">
                                                    <?php if (isset($data['flag']['nomeArquivo'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['nomeArquivo'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                        if (in_array('alreadyExists', $data['flag']['nomeArquivo'])) {
                                                            ?><li class="parsley-required">Este nome de arquivo já existe. Utilize outro.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="classeIcone">Ícone FontAwesome</label>
                                                <div class="col-md-9">
                                                    <input name="classeIcone" type="text" id="classeIcone" class="form-control <?php if (isset($data['flag']['classeIcone'])){echo 'parsley-error';} ?>" placeholder="fa fa-sample" value="<?=$data['classeIcone']?>">
                                                    <?php if (isset($data['flag']['classeIcone'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['classeIcone'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end col -->

                                </div><!-- end row -->
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->
                </form>

                <?php require PATH_ABS . '/Response/admin/_includes/php/footer.php'; ?>

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
    </body>
</html>