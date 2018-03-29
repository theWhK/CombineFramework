<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Armazena o ID a ser apagado
$id_apagar = $this->parameters[0];

// Caso o ID seja nulo, retorna à home da sub-ação
if (!isset($id_apagar)) {
    redirectTo(URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command);
}

// Apaga o registro
$deleteStatus = $FirstMoldPermissionsModule->delete($id_apagar);

if ($deleteStatus == true) {
    $NotificacoesModule->sendAlert(
    '{
        title: "Ótimo!",
        text: "Cargo apagado com sucesso.",
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
        text: "A operação falhou. Desculpe-nos pelo inconveniente. Tente novamente mais tarde e, caso persista, consulte o FAQ.",
        icon: "error",
        button: {
            text: "OK",
            className: "btn-danger waves-effect waves-light"
        }
    }', 'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->commandArchive.'/listar');
}

return;