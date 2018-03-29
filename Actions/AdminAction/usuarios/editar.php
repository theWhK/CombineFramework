<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Armazena o ID a ser editado
$id_edicao = $this->parameters[0];

// Caso o ID seja nulo, retorna à home da sub-ação
if (!isset($id_edicao)) {
    redirectTo(URL_BASE.'/admin/usuarios');
}

// Verifica se há um formulário de edição enviado
if (isset($_POST['submit'])) {
    // Valida o formulário
    $validate = $UsuariosModule->validateFormData(true);

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
                }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/editar/'.$id_edicao);
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
                }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/editar/'.$id_edicao);
            }
            break;
    }

    // Valida o cargo
    $cargoFiltrado = $CargosModule->checkCargoID($_POST['idCargo']);

    // Valida as permissões no formulário
    $validatePerms = $FirstMoldPermissionsModule->validatePermsFormData($_POST['permissoesSelecionadas']);

    // Combina os buffers
    $UsuariosModule->bufferData['idCargo'] = $cargoFiltrado;

    // Caso os dados estejam ok, edita o item
    if ($validate == true && $validatePerms == true) {
        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // Edita o usuário
            // (o cargo do usuário é atributo do cadastro abaixo)
            $updateStatus = $UsuariosModule->update($id_edicao);

            // Atualiza as permissões
            $permsStatus = $FirstMoldPermissionsModule->regrant($id_edicao, $FirstMoldPermissionsModule->bufferData['permissoes']);

            // Confirma a transação
            $this->conn->PDO->commit();
        } catch (PDOExcepction $e) {
            // Desfaz a transação
            $this->conn->PDO->rollBack();

            //statusCode(500);
        }

        if ($updateStatus && $permsStatus) {
            $NotificacoesModule->sendAlert(
            '{
                title: "Ótimo!",
                text: "Edição feita com sucesso.",
                icon: "success",
                button: {
                    text: "OK",
                    className: "btn-success waves-effect waves-light"
                }
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/listar');
        } else {
            $NotificacoesModule->sendAlert(
            '{
                title: "Ops!",
                text: "A edição falhou. Desculpe-nos pelo inconveniente. Tente novamente mais tarde e, caso persista, consulte o FAQ.",
                icon: "error",
                button: {
                    text: "OK",
                    className: "btn-danger waves-effect waves-light"
                }
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/editar/'.$id_edicao);
        }
    }
}

// RESPONSE OBJECTS: dados do usuário em edição
$data = $UsuariosModule->read('WHERE id = ' . $id_edicao)[0];

// RESPONSE OBJECTS: flags do formulário
$data['flag'] = $UsuariosModule->bufferData['flag'];

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

// RESPONSE OBJECTS: permissões concedidas
$permissoes_concedidas = $FirstMoldPermissionsModule->list($id_edicao);

// Permissões do comando
$permissoes_comando_stmt = $FirstMoldPermissionsModule->query(
    "SELECT
    permissoes.id,
    permissoes.nome,
    permissoes.descricao,
    permissoes.tipo,
    permissoes.idRegistroAtrelado,
    comandos.rotulo,
    comandos.id_comando_pai
    FROM
    {$this->dbPrefix}_permissoes_lista permissoes
    LEFT JOIN {$this->dbPrefix}_commands comandos
        ON permissoes.idRegistroAtrelado = comandos.id
    WHERE permissoes.tipo = 'command'
    ORDER BY permissoes.id");
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

// Varre as permissões concedidas e aplica o selo nas listas de permissões
if (is_array($permissoes_concedidas)) {
    foreach ($permissoes_concedidas as $item) {
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

// RESPONSE: /admin/usuarios/editar
require PATH_ABS . '/Response//'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;