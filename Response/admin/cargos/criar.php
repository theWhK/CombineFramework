<?php require PATH_ABS . '/Response/admin/_includes/php/head.php'; ?>
    </head>

    <body>

        <?php require PATH_ABS . '/Response/admin/_includes/php/header.php'; ?>

        
        <div class="wrapper">
            <div class="container">
                <form role="form" method="post" action="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/criar">

                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-group pull-right m-t-15">
                                <button type="submit" name="submit" class="btn btn-success waves-effect waves-light pull-right">Salvar</button>
                            </div>

                            <h4 class="page-title">Criar Cargo/Departamento</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">

                                <h4 class="header-title m-t-0 m-b-30">Informações Principais</h4>

                                <p class="text-purple m-b-30 font-13">Os campos com asterisco (*) são de preenchimento obrigatório.</p>

                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="nome">Nome *</label>
                                                <div class="col-md-9">
                                                    <input name="nome" type="text" id="nome" class="form-control <?php if (isset($data['flag']['nome'])){echo 'parsley-error';} ?>" placeholder="Nome" value="<?=$data['nome']?>">
                                                    <?php if (isset($data['flag']['nome'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['nome'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="id_depart">Departamento correspondente *</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="id_depart" id="id_depart">
                                                        <option value="0">Este item é um departamento</option>
                                                        <?php
                                                        foreach ($departamentos as $item) {
                                                        ?>
                                                        <option value="<?=$item['id']?>"><?=$item['nome']?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
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

                                </div><!-- end row -->
                            </div>
                        </div><!-- end col -->

                        <div class="col-sm-12">
                            <div class="card-box">
                                <h4 class="header-title m-t-0 m-b-30">Permissões do cargo</h4>

                                <p class="m-b-30 font-13">Selecione as permissões pré-definidas que os cargos e usuários descendentes deste cargo/departamento terão no momento do cadastro. Cada cargo e usuário poderão ter, posteriormente, modificações individuais em suas permissões.</p>
                                
                                <div class="row">
                                <?php
                                foreach ($permissoes_hierarquia as $comandoPai) {
                                ?>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="card-box">
                                            
                                            <div class="checkbox checkbox-primary">
                                                <input id="<?=$comandoPai['id']?>-checkbox" type="checkbox" name="permissoesSelecionadas[]" value="<?=$comandoPai['id']?>" <?php if ($comandoPai['concedida']) { ?>checked<?php } ?>>
                                                <label for="<?=$comandoPai['id']?>-checkbox">
                                                    <h4 class="header-title m-t-0 m-b-30">[<?=$comandoPai['rotulo']?>] <?=$comandoPai['nome']?></h4>
                                                </label>
                                            </div>

                                            <?php
                                            if (is_array($comandoPai['listaMetodos'])) {
                                                foreach ($comandoPai['listaMetodos'] as $metodo) {
                                                ?>
                                                <div class="checkbox checkbox-primary">
                                                    <input id="<?=$metodo['id']?>-checkbox" type="checkbox" name="permissoesSelecionadas[]" value="<?=$metodo['id']?>" <?php if ($metodo['concedida']) { ?>checked<?php } ?>>
                                                    <label for="<?=$metodo['id']?>-checkbox">
                                                        <?=$metodo['id']?> - <?=$metodo['nome']?>
                                                    </label>
                                                </div>
                                                <?php
                                                }
                                            }
                                            ?>

                                            <?php
                                            if (is_array($comandoPai['listaComandosFilho'])) {
                                                foreach ($comandoPai['listaComandosFilho'] as $comandoFilho) {
                                                ?>
                                                <div class="card-box m-t-30">

                                                    <div class="checkbox checkbox-primary">
                                                        <input id="<?=$comandoFilho['id']?>-checkbox" type="checkbox" name="permissoesSelecionadas[]" value="<?=$comandoFilho['id']?>" <?php if ($comandoFilho['concedida']) { ?>checked<?php } ?>>
                                                        <label for="<?=$comandoFilho['id']?>-checkbox">
                                                            <h4 class="header-title m-t-0 m-b-30">[<?=$comandoFilho['rotulo']?>] <?=$comandoFilho['nome']?></h4>
                                                        </label>
                                                    </div>

                                                    <?php
                                                    if (is_array($comandoFilho['listaMetodos'])) {
                                                        foreach ($comandoFilho['listaMetodos'] as $metodo) {
                                                        ?>
                                                        <div class="checkbox checkbox-primary">
                                                            <input id="<?=$metodo['id']?>-checkbox" type="checkbox" name="permissoesSelecionadas[]" value="<?=$metodo['id']?>" <?php if ($metodo['concedida']) { ?>checked<?php } ?>>
                                                            <label for="<?=$metodo['id']?>-checkbox">
                                                                <?=$metodo['id']?> - <?=$metodo['nome']?>
                                                            </label>
                                                        </div>
                                                        <?php
                                                        }
                                                    } else {
                                                    ?>
                                                    
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

                                    <?php
                                    foreach ($permissoes_metodo as $permissao) {
                                    ?>
                                    <div class="checkbox checkbox-primary">
                                        <input id="<?=$permissao['id']?>-checkbox" type="checkbox" name="permissoesSelecionadas[]" value="<?=$permissao['id']?>" <?php if ($permissao['concedida']) { ?>checked<?php } ?>>
                                        <label for="<?=$permissao['id']?>-checkbox">
                                            <?=$permissao['id']?> - <?=$permissao['nome']?>
                                        </label>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                }
                                ?>

                                <?php
                                if (isset($permissoes_custom) && !empty($permissoes_custom)) {
                                ?>
                                <div class="card-box">
                                    <h4 class="header-title m-t-0 m-b-30">Permissões customizadas</h4>

                                    <?php
                                    foreach ($permissoes_custom as $permissao) {
                                    ?>
                                    <div class="checkbox checkbox-primary">
                                    <input id="<?=$permissao['id']?>-checkbox" type="checkbox" name="permissoesSelecionadas[]" value="<?=$permissao['id']?>" <?php if ($permissao['concedida']) { ?>checked<?php } ?>>
                                        <label for="<?=$permissao['id']?>-checkbox">
                                            <?=$permissao['id']?> - <?=$permissao['nome']?>
                                        </label>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

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