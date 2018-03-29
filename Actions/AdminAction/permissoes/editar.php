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
    redirectTo(URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command);
}

// Verifica se há um formulário de edição enviado
if (isset($_POST['submit'])) {
    // Valida o formulário
    $validate = $FirstMoldPermissionsModule->validateFormData(true);

    // Caso os dados estejam ok, edita
    if ($validate == true) {
        // Edita
        $updateStatus = $FirstMoldPermissionsModule->update($id_edicao);

        if ($updateStatus == true) {
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
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->commandArchive.'/comando-editar//'.$id_edicao);
        }
    }
}

// RESPONSE OBJECTS: dados em edição
$data = $FirstMoldPermissionsModule->read('WHERE id = ' . $id_edicao)[0];

// RESPONSE OBJECTS: flags do formulário
$data['flag'] = $FirstMoldPermissionsModule->bufferData['flag'];

// Puxa os dados de registro correspondentes
switch ($data['tipo']) {
    case "command":
        // Resgata a lista de comandos
        $stmt = $FirstMoldHierarchyModule->query(
            "SELECT id, id_comando_pai, rotulo
             FROM ".$this->dbPrefix."_commands");
        $dataRegistrosCanvas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dataRegistrosCanvas as $item) {
            if ($item['id_comando_pai'] == 0) {
                $dataRegistros[$item['id']] = $item;
            }
        }

        foreach ($dataRegistrosCanvas as $item) {
            if ($item['id_comando_pai'] != 0) {
                $dataRegistros[$item['id_comando_pai']]['listaComandosFilho'][] = $item;
            }
        }
    break;

    case "method":
        // Resgata a lista de métodos
        $stmt = $FirstMoldHierarchyModule->query(
            "SELECT id, id_comando_pai, rotulo
             FROM ".$this->dbPrefix."_methods");
        $dataRegistrosCanvas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Resgata a lista de comandos
        $stmt = $FirstMoldHierarchyModule->query(
            "SELECT id, id_comando_pai, rotulo
             FROM ".$this->dbPrefix."_commands");
        $dataComandosPaiCanvas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insere os métodos nos comandos
        foreach ($dataComandosPaiCanvas as $keyPai => $valuePai) {
            foreach ($dataRegistrosCanvas as $key => $value) {
                if ($valuePai['id'] == $value['id_comando_pai']) {
                    $dataComandosPaiCanvas[$keyPai]['listaMetodos'][] = $value;
                    unset($dataRegistrosCanvas[$key]);
                }
            }
        }

        // Organiza os comandos-pai
        foreach ($dataComandosPaiCanvas as $item) {
            if ($item['id_comando_pai'] == 0) {
                $dataRegistros[$item['id']] = $item;
            }
        }

        // Organiza os comandos-filho
        foreach ($dataComandosPaiCanvas as $item) {
            if ($item['id_comando_pai'] != 0) {
                $dataRegistros[$item['id_comando_pai']]['listaComandosFilho'][] = $item;
            }
        }

        // Organiza os métodos soltos
        if (!empty($dataRegistrosCanvas)) {
            $dataRegistros[] = array(
                'rotulo' => 'Métodos Soltos',
                'listaMetodos' => $dataRegistrosCanvas);
        }
    break;
}

// RESPONSE
require PATH_ABS . '/Response/'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;