<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Verifica se há um formulário de edição enviado
if (isset($_POST['submit'])) {
    // Define o ID de edição
    $id_edicao = $LoginModule->userId();
    $UsuariosModule->bufferData['id'] = $id_edicao;

    // Valida o formulário
    $validate = $UsuariosModule->validatePublicFormData(true);

    // Caso os dados estejam ok, edita o item
    if ($validate == true) {
        // Edita o usuário
        $updateStatus = $UsuariosModule->update($id_edicao);

        if ($updateStatus) {
            $NotificacoesModule->sendAlert(
            '{
                title: "Ótimo!",
                text: "Edição feita com sucesso.",
                icon: "success",
                button: {
                    text: "OK",
                    className: "btn-success waves-effect waves-light"
                }
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command);
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
            }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command);
        }
    }
}

// RESPONSE OBJECTS: dados do usuário em edição
$data = $UsuariosModule->read('WHERE id = ' . $LoginModule->userId())[0];

// RESPONSE OBJECTS: flags do formulário
$data['flag'] = $UsuariosModule->bufferData['flag'];

// RESPONSE
require PATH_ABS.'/Response/'.$this->core->action_urlName.'/'.$this->commandArchive.'/main.php';

return;