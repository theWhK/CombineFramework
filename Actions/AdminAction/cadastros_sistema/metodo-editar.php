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

// Faz a proteção da variável externa
$id_edicao = protect($id_edicao);

// Caso o ID seja nulo, retorna à home da sub-ação
if (!isset($id_edicao)) {
    redirectTo(URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command);
}

// Verifica se há um formulário de edição enviado
if (isset($_POST['submit'])) {
    // Valida o formulário
    $validate = $FirstMoldHierarchyModule->method_validateFormData(true);

    // Caso os dados estejam ok, edita
    if ($validate == true) {
        // Edita
        $updateStatus = $FirstMoldHierarchyModule->method_update($id_edicao);

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
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->commandArchive.'/metodo-editar//'.$id_edicao);
        }
    }
}

// RESPONSE OBJECTS: comandos
$comandos = $FirstMoldHierarchyModule->command_read();

// RESPONSE OBJECTS: dados em edição
$data = $FirstMoldHierarchyModule->method_read('WHERE id = ' . $id_edicao)[0];

// RESPONSE OBJECTS: flags do formulário
$data['flag'] = $FirstMoldHierarchyModule->bufferData['flag'];

// RESPONSE
require PATH_ABS . '/Response//'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;