<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Verifica se há um formulário de cadastro enviado
if (isset($_POST['submit'])) {
    // Valida o formulário
    $validate = $UsuariosModule->validateFormData();

    /*
     * Verifica se há permissão para alterar o usuário:
     * Superuser: qualquer um.
     * Admin: qualquer um, menos superusers.
     * Usuários: somente usuários.
     */
    switch ($NivelPoderModule->getPower($LoginModule->userId())) {
        case "admin":
            if ($UsuariosModule->bufferData['nivelUso'] != 0
             || $UsuariosModule->bufferData['nivelUso'] != 1) {
                $NotificacoesModule->sendAlert(
                '{
                    title: "Ops!",
                    text: "O nível de poder enviado não pode ser definido pelo usuário logado.",
                    icon: "error",
                    button: {
                        text: "OK",
                        className: "btn-danger waves-effect waves-light"
                    }
                }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/criar');
            }
            break;
        
        case "normal":
            if ($UsuariosModule->bufferData['nivelUso'] != 0) {
                $NotificacoesModule->sendAlert(
                '{
                    title: "Ops!",
                    text: "O nível de poder enviado não pode ser definido pelo usuário logado.",
                    icon: "error",
                    button: {
                        text: "OK",
                        className: "btn-danger waves-effect waves-light"
                    }
                }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/criar');
            }
            break;
    }
    
    // Valida as permissões no formulário
    $validatePerms = $FirstMoldPermissionsModule->validatePermsFormData($_POST['permissoesSelecionadas']);

    // Valida o cargo
    $cargoFiltrado = $CargosModule->checkCargoID($_POST['idCargo']);

    // Combina os buffers
    $UsuariosModule->bufferData['idCargo'] = $cargoFiltrado;
    $comboBufferData = $UsuariosModule->bufferData;
    $comboBufferData['permissoesSelecionadas'] = $FirstMoldPermissionsModule->bufferData['permissoes'];
    
    // Armazena os dados em sessão
    $SessaoModule->set($comboBufferData);

    // Caso os dados estejam ok, cria o item
    if ($validate == true && $validatePerms == true) {
        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // Cadastra o usuário
            // (o cargo do usuário é atributo do cadastro abaixo)
            $createStatus = $UsuariosModule->create();

            // Resgata a ID gerada
            $idUsuarioNovo = $this->conn->PDO->lastInsertId();

            // Atualiza as permissões
            $permsStatus = $FirstMoldPermissionsModule->regrant($idUsuarioNovo, $comboBufferData['permissoesSelecionadas']);

            // Confirma a transação
            $this->conn->PDO->commit();
        } catch (PDOException $e) {
            // Desfaz a transação
            $this->conn->PDO->rollBack();

            //statusCode(500);
        }

        if ($createStatus && $permsStatus) {
            // Limpa a sessão
            $SessaoModule->clean();

            // Informa o sucesso da operação
            $NotificacoesModule->sendAlert(
            '{
                title: "Ótimo!",
                text: "Cadastro feito com sucesso.",
                icon: "success",
                button: {
                    text: "OK",
                    className: "btn-success waves-effect waves-light"
                }
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/listar');
        } else {
            // Informa a falha da operação
            $NotificacoesModule->sendAlert(
            '{
                title: "Ops!",
                text: "O cadastro falhou. Desculpe-nos pelo inconveniente. Tente novamente mais tarde e, caso persista, consulte o FAQ.",
                icon: "error",
                button: {
                    text: "OK",
                    className: "btn-danger waves-effect waves-light"
                }
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/criar');
        }
    }
}

// RESPONSE OBJECTS: dados da sessão
$data = $SessaoModule->get();

// RESPONSE OBJECTS: cargos hierarquizados
$cargos_hierarquia_canvas = $CargosModule->read();
foreach ($cargos_hierarquia_canvas as $key => $item) {
    if ($item['id_depart'] == 0) {
        $cargos_hierarquia[$item['id']] = $item;
        unset($cargos_hierarquia_canvas[$key]);
    }
}
foreach ($cargos_hierarquia_canvas as $item) {
    $cargos_hierarquia[$item['id_depart']]['listaCargos'][] = $item;
}

// Permissões do comando
$permissoes_comando_stmt = $FirstMoldPermissionsModule->query(
    'SELECT
    permissoes.id,
    permissoes.nome,
    permissoes.descricao,
    permissoes.tipo,
    permissoes.idRegistroAtrelado,
    comandos.rotulo,
    comandos.id_comando_pai
    FROM
    '.$this->dbPrefix.'_permissoes_lista permissoes
    LEFT JOIN '.$this->dbPrefix.'_commands comandos
        ON permissoes.idRegistroAtrelado = comandos.id
    WHERE permissoes.tipo = "command"
    ORDER BY permissoes.id');
$permissoes_comando = $permissoes_comando_stmt->fetchAll(PDO::FETCH_ASSOC);

// Permissões do método
$permissoes_metodo_stmt = $FirstMoldPermissionsModule->query(
    'SELECT
    permissoes.id,
    permissoes.nome,
    permissoes.descricao,
    permissoes.tipo,
    permissoes.idRegistroAtrelado,
    metodos.rotulo,
    metodos.id_comando_pai
    FROM
    '.$this->dbPrefix.'_permissoes_lista permissoes
    LEFT JOIN '.$this->dbPrefix.'_methods metodos
        ON permissoes.idRegistroAtrelado = metodos.id
    WHERE permissoes.tipo = "method"
    ORDER BY permissoes.id');
$permissoes_metodo = $permissoes_metodo_stmt->fetchAll(PDO::FETCH_ASSOC);

// RESPONSE OBJECTS: permissões customizadas
$permissoes_custom = $FirstMoldPermissionsModule->read("WHERE tipo = 'custom'");

// Caso haja dados em sessão, checam as permissões já selecionadas e as marcam
if (is_array($SessaoModule->get()['permissoesSelecionadas'])) {
    foreach ($SessaoModule->get()['permissoesSelecionadas'] as $item) {
        $flag = false;

        foreach ($permissoes_metodo as $key => $perm) {
            if ($perm['id'] == $item) {
                $permissoes_metodo[$key]['concedida'] = true;
                $flag = true;
                break;
            }
        }

        if (!$flag) {
            foreach ($permissoes_comando as $key => $perm) {
                if ($perm['id'] == $item) {
                    $permissoes_comando[$key]['concedida'] = true;
                    $flag = true;
                    break;
                }
            }
        };

        if (!$flag) {
            foreach ($permissoes_custom as $key => $perm) {
                if ($perm['id'] == $item) {
                    $permissoes_custom[$key]['concedida'] = true;
                    break;
                }
            }
        }
    }
}

// Permissões de comandos e métodos estruturadas hierarquicamente
    // Hierarquia das permissões de métodos
    foreach ($permissoes_comando as $key => $comando) {
        // Reseta a chave
        $permissoes_hierarquia_canvas[$comando['idRegistroAtrelado']] = $permissoes_comando[$key];

        // Varre os métodos
        foreach ($permissoes_metodo as $key_sec => $metodo) {
            // Se o método pertencer ao comando em voga, insere-o no conjunto
            if ($comando['idRegistroAtrelado'] == $metodo['id_comando_pai']) {
                $permissoes_hierarquia_canvas[$comando['idRegistroAtrelado']]['listaMetodos'][] = $metodo;
                unset($permissoes_metodo[$key_sec]);
            }
        }
    }

    // Hierarquia entre permissões de comandos
    foreach ($permissoes_hierarquia_canvas as $key => $comando) {
        // Hierarquiza os comandos
        if ($comando['id_comando_pai'] == 0) {
            $permissoes_hierarquia[$comando['idRegistroAtrelado']] = $comando;
        }
    }
    foreach ($permissoes_hierarquia_canvas as $key => $comando) {
        // Hierarquiza os comandos
        if ($comando['id_comando_pai'] != 0) {
            $permissoes_hierarquia[$comando['id_comando_pai']]['listaComandosFilho'][] = $comando;
        }
    }

// RESPONSE OBJECTS: permissões hierarquizadas
$permissoes_hierarquia;

// RESPONSE: /admin/usuarios/criar
require PATH_ABS . '/Response//'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;