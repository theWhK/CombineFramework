<?php require PATH_ABS . '/Response/admin/_includes/php/head.php'; ?>
    </head>

    <body>

        <?php require PATH_ABS . '/Response/admin/_includes/php/header.php'; ?>

        
        <div class="wrapper">
            <div class="container">
                <form role="form" method="post" action="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>/editar/<?=$id_edicao?>">

                    <input type="hidden" name="id" value="<?=$id_edicao?>">

                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-group pull-right m-t-15">
                                <button type="submit" name="submit" class="btn btn-success waves-effect waves-light pull-right">Salvar</button>
                            </div>

                            <h4 class="page-title">Editar Usuário</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">

                                <h4 class="header-title m-t-0 m-b-30">Informações Principais</h4>

                                <p class="text-purple m-b-30 font-13">Os campos com asterisco (*) são de preenchimento obrigatório.</p>

                                <div class="row">
                                    <div class="col-md-6">
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
                                                <label class="col-md-3 control-label" for="sobrenome">Sobrenome *</label>
                                                <div class="col-md-9">
                                                    <input name="sobrenome" type="text" id="sobrenome" class="form-control <?php if (isset($data['flag']['sobrenome'])){echo 'parsley-error';} ?>" placeholder="Sobrenome" value="<?=$data['sobrenome']?>">
                                                    <?php if (isset($data['flag']['sobrenome'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['sobrenome'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="password">Senha</label>
                                                <div class="col-md-9">
                                                    <input name="password" type="password" id="password" class="form-control <?php if (isset($data['flag']['password'])){echo 'parsley-error';} ?>">
                                                    <?php if (isset($data['flag']['password'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['password'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                        if (in_array('mismatch', $data['flag']['password'])) {
                                                            ?><li class="parsley-required">As senhas não são iguais.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="repeatPassword">Repetir Senha</label>
                                                <div class="col-md-9">
                                                    <input name="repeatPassword" type="password" id="repeatPassword" class="form-control <?php if (isset($data['flag']['repeatPassword'])){echo 'parsley-error';} ?>">
                                                    <?php if (isset($data['flag']['repeatPassword'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['repeatPassword'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <?php
                                            switch ($NivelPoderModule->getPower($LoginModule->userId())) {
                                                case "su":
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="nivelUso">Nível de poder</label>
                                                    <div class="col-md-9">
                                                        <select class="form-control" name="nivelUso" id="nivelUso">
                                                            <option value="0" <?php if ($data['nivelUso'] == 0) {?>selected<?php } ?>>Usuário normal</option>
                                                            <option value="1"  <?php if ($data['nivelUso'] == 1) {?>selected<?php } ?>>Administrador</option>
                                                            <option value="2"  <?php if ($data['nivelUso'] == 2) {?>selected<?php } ?>>Superuser</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                                break;

                                                case "adm":
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label" for="nivelUso">Nível de poder</label>
                                                    <div class="col-md-9">
                                                        <select class="form-control" name="nivelUso" id="nivelUso">
                                                            <option value="0" <?php if ($data['nivelUso'] == 0) {?>selected<?php } ?>>Usuário normal</option>
                                                            <option value="1"  <?php if ($data['nivelUso'] == 1) {?>selected<?php } ?>>Administrador</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php
                                                break;

                                                case "normal":
                                                default:
                                                ?>
                                                <input type="hidden" name="nivelUso" value="0">
                                                <?php
                                                break;
                                            }
                                            ?>
                                        </div>
                                    </div><!-- end col -->

                                    <div class="col-md-6">
                                        <div class="form-horizontal" role="form">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="nickname">Nome de Usuário *</label>
                                                <div class="col-md-9">
                                                    <input name="nickname" type="text" id="nickname" class="form-control <?php if (isset($data['flag']['nickname'])){echo 'parsley-error';} ?>" placeholder="Nickname" value="<?=$data['nickname']?>">
                                                    <?php if (isset($data['flag']['nickname'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('empty', $data['flag']['nickname'])) {
                                                            ?><li class="parsley-required">É necessário preencher este campo.</li><?php
                                                        }
                                                        if (in_array('alreadyExists', $data['flag']['nickname'])) {
                                                            ?><li class="parsley-required">Este nome de usuário já existe. Utilize outro.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="email">Email</label>
                                                <div class="col-md-9">
                                                    <input name="email" type="email" id="email" class="form-control <?php if (isset($data['flag']['email'])){echo 'parsley-error';} ?>" placeholder="Email" value="<?=$data['email']?>">
                                                    <?php if (isset($data['flag']['email'])) {
                                                    ?><ul class="parsley-errors-list filled"><?php
                                                        if (in_array('alreadyExists', $data['flag']['email'])) {
                                                            ?><li class="parsley-required">Este email já existe. Utilize outro.</li><?php
                                                        }
                                                    ?></ul><?php
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="telefone">Telefone</label>
                                                <div class="col-md-9">
                                                    <input name="telefone" type="text" id="telefone" class="form-control <?php if (isset($data['flag']['telefone'])){echo 'parsley-error';} ?>" placeholder="(XX) XXXXX-XXXX" data-inputmask="'mask': '(99) 9999[9]-9999'" value="<?=$data['telefone']?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="celular">Celular</label>
                                                <div class="col-md-9">
                                                    <input name="celular" type="text" id="celular" class="form-control <?php if (isset($data['flag']['celular'])){echo 'parsley-error';} ?>" placeholder="(XX) XXXXX-XXXX" data-inputmask="'mask': '(99) 9999[9]-9999'" value="<?=$data['celular']?>">
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
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">
                                <h4 class="header-title m-t-0 m-b-30">Cargos do Usuário</h4>

                                <p class="text-muted m-b-30 font-13">Cargos servem para gerar uma hierarquia entre os usuários do sistema, permitindo um controle detalhado acerca das relações interpessoais da organização. Também possuem as permissões pré-definidas, facilitando o cadastro do usuário no sistema.</p>

                                <h5><b>Selecione o cargo</b></h5>

                                <select class="form-control select2" name="idCargo" data-load-perm-predef-cargos="select">
                                    <option value="0">Nenhum</option>
                                    <?php
                                    if (is_array($cargos_hierarquia)) {
                                        foreach ($cargos_hierarquia as $departamento) {
                                        ?>
                                        <optgroup label="<?=$departamento['nome']?>">
                                            <?php
                                            foreach ($departamento['listaCargos'] as $cargo) {
                                            ?>
                                            <option 
                                                value="<?=$cargo['id']?>"
                                                <?php if ($data['idCargo'] == $cargo['id']) { ?>
                                                selected<?php } ?>>
                                                <?=$cargo['nome']?></option>
                                            <?php
                                            }
                                            ?>
                                        </optgroup>
                                        <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box">
                                <h4 class="header-title m-t-0 m-b-30">Permissões Concedidas ao Usuário</h4>

                                <p class="text-muted m-b-30 font-13">Selecione as permissões as quais o usuário possuirá para interagir com o sistema.</p>

                                <button class="btn btn-primary waves-effect waves-light m-b-15" type="button" data-load-perm-predef-cargos="button">Carregar pré-definições das permissões</button>
                                
                                <div class="permissoes-itens-wrapper" data-load-perm-predef-cargos="wrapper">
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
                    </div>

                </form>

               <?php require PATH_ABS.'/Response/admin/_includes/php/footer.php'; ?>

            </div>
            <!-- end container -->

        </div>

    <?php require PATH_ABS . '/Response/admin/_includes/php/foot.php'; ?>
    <script src="<?=URL_BASE?>/Response/admin/_includes/js/load-permPredefCargos.js"></script>
    </body>
</html>