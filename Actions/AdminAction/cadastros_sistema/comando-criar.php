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
    $validate = $FirstMoldHierarchyModule->command_validateFormData();

    // Armazena os dados em sessão
    $SessaoModule->set($FirstMoldHierarchyModule->bufferData);

    // Caso os dados estejam ok, cadastra
    if ($validate == true) {
        // Cadastra
        $createStatus = $FirstMoldHierarchyModule->command_create();

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

// RESPONSE OBJECTS: comandos-pai
$comandosPai = $FirstMoldHierarchyModule->command_read('WHERE id_comando_pai = 0');

// RESPONSE OBJECTS: dados da sessão
$data = $SessaoModule->get();

// RESPONSE
require PATH_ABS . '/Response//'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;