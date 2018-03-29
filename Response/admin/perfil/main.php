<?php require PATH_ABS . '/Response/admin/_includes/php/head.php'; ?>
    </head>

    <body>

        <?php require PATH_ABS . '/Response/admin/_includes/php/header.php'; ?>

        
        <div class="wrapper">
            <div class="container">
                <form role="form" method="post" action="<?=URL_BASE?>/<?=$this->core->action_urlName?>/<?=$this->command?>">

                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-group pull-right m-t-15">
                                <button type="submit" name="submit" class="btn btn-success waves-effect waves-light pull-right">Salvar</button>
                            </div>

                            <h4 class="page-title">Editar Perfil</h4>
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
                                                    <input name="email" type="email" id="email" class="form-control" disabled value="<?=$data['email']?>">
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
                </form>

               <?php require PATH_ABS.'/Response/admin/_includes/php/footer.php'; ?>

            </div>
            <!-- end container -->

        </div>

    <?php require PATH_ABS . '/Response/admin/_includes/php/foot.php'; ?>
    </body>
</html>