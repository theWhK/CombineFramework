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
    $validate = $FirstMoldPermissionsModule->validateFormData();

    // Armazena os dados em sessão
    $SessaoModule->set($FirstMoldPermissionsModule->bufferData);

    // Caso os dados estejam ok, cadastra
    if ($validate == true) {
        // Cadastra
        $createStatus = $FirstMoldPermissionsModule->create();

        if ($createStatus == true) {
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
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->commandArchive.'/listar');
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
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->commandArchive.'/comando-criar');
        }
    }
}

// RESPONSE OBJECTS: dados da sessão
$data = $SessaoModule->get();

// Puxa os dados de registro correspondentes
switch ($data['tipo']) {
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

    case "command":
    default:
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
}

// RESPONSE
require PATH_ABS . '/Response//'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;