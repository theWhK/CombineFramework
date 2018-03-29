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

                            <h4 class="page-title">Editar Permissão</h4>
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
                                                <label class="col-md-3 control-label" for="nivelUso">Nível de poder</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="nivelUso" id="nivelUso">
                                                        <option value="2"  <?php if ($data['nivelUso'] == 2) {?>selected<?php } ?>>Superusuário</option>
                                                        <option value="1"  <?php if ($data['nivelUso'] == 1) {?>selected<?php } ?>>Administrador</option>
                                                        <option value="0"  <?php if ($data['nivelUso'] == 0) {?>selected<?php } ?>>Normal</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label" for="tipo">Tipo *</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="tipo" id="tipo" data-selecao-tipo>
                                                        <option value="command" <?php if ($data['tipo'] == "command") {?>selected<?php } ?>>Comando</option>
                                                        <option value="method"  <?php if ($data['tipo'] == "method") {?>selected<?php } ?>>Método</option>
                                                        <option value="custom"  <?php if ($data['tipo'] == "custom") {?>selected<?php } ?>>Customizado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group campo-selecao-idRegistroAtrelado">
                                                <label class="col-md-3 control-label" for="idRegistroAtrelado">Registro atrelado *</label>
                                                <div class="col-md-9">
                                                    <select class="form-control" name="idRegistroAtrelado" id="idRegistroAtrelado" data-selecao-idRegistroAtrelado>
                                                        <?php
                                                        if ($data['tipo'] == "command") {
                                                            foreach ($dataRegistros as $item) {
                                                            ?>
                                                            <option value="<?=$item['id']?>" <?php if ($data['idRegistroAtrelado'] == $item['id']) {?>selected<?php } ?>><?=$item['rotulo']?></option>
                                                            <?php
                                                                if (is_array($item['listaComandosFilho'])) {
                                                                    foreach ($item['listaComandosFilho'] as $itemFilho) {
                                                                        ?>
                                                                        <option value="<?=$itemFilho['id']?>" <?php if ($data['idRegistroAtrelado'] == $itemFilho['id']) {?>selected<?php } ?>>&rdca; <?=$itemFilho['rotulo']?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        } else if ($data['tipo'] == "method") {
                                                            foreach ($dataRegistros as $grupo) {
                                                            ?>
                                                            <optgroup label="<?=$grupo['rotulo']?>">
                                                                <?php
                                                                if (is_array($grupo['listaMetodos'])) {
                                                                    foreach ($grupo['listaMetodos'] as $item) {
                                                                        ?>
                                                                        <option value="<?=$item['id']?>" <?php if ($data['idRegistroAtrelado'] == $item['id']) {?>selected<?php } ?>><?=$item['rotulo']?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </optgroup>
                                                            <?php
                                                                if (is_array($grupo['listaComandosFilho'])) {
                                                                    foreach ($grupo['listaComandosFilho'] as $grupo) {
                                                                        ?>
                                                                        <optgroup label="&rdca; <?=$grupo['rotulo']?>">
                                                                            <?php
                                                                            if (is_array($grupo['listaMetodos'])) {
                                                                                foreach ($grupo['listaMetodos'] as $item) {
                                                                                    ?>
                                                                                    <option value="<?=$item['id']?>" <?php if ($data['idRegistroAtrelado'] == $item['id']) {?>selected<?php } ?>><?=$item['rotulo']?></option>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </optgroup>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
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

        </div>

    <?php require PATH_ABS . '/Response/admin/_includes/php/foot.php'; ?>
    <script src="<?=URL_BASE?>/Response/admin/_includes/js/load-comMetAtrelados.js"></script>
    </body>
</html>