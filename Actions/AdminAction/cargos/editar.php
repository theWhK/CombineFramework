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
    redirectTo(URL_BASE . '/admin/cargos');
}

// Verifica se há um formulário de edição enviado
if (isset($_POST['submit'])) {
    // Valida o formulário
    $validate = $CargosModule->validateFormData(true);

    // Valida as permissões pré-definidas
    $validatePerms = $FirstMoldPermissionsModule->validatePermsFormData($_POST['permissoesSelecionadas']);

    // Caso os dados estejam ok, edita o cargo
    if ($validate && $validatePerms) {
        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // Edita o cargo
            $updateStatus = $CargosModule->update($id_edicao);

            // Edita o grupo de permissões
            $permsStatus = $FirstMoldPermissionsModule->setPredef(
                "cargos", $id_edicao, $FirstMoldPermissionsModule->bufferData['permissoes'], "alter");

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
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->commandArchive.'/listar');
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
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->commandArchive.'/listar');
        }
    }
}

// RESPONSE OBJECTS: departamentos
$departamentos = $CargosModule->read('WHERE id_depart = "0"');

// RESPONSE OBJECTS: dados do cargo em edição
$data = $CargosModule->read('WHERE id = ' . $id_edicao)[0];

// RESPONSE OBJECTS: flags do formulário
$data['flag'] = $CargosModule->bufferData['flag'];

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

// Marcam-se as permissões de pré-definição do cargo
$permissoesPredefinidas = $FirstMoldPermissionsModule->getPredef("cargos", $id_edicao);
if (is_array($permissoesPredefinidas)) {
    foreach ($permissoesPredefinidas as $item) {
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