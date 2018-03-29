<?php
// Checagem de permissões
if (!$NivelPoderModule->isSuperUser($LoginModule->userId())) {
    if (!$FirstMoldPermissionsModule->request($LoginModule->userId(), $this->commandPerm)) {
        $NotificacoesModule->sendAlert(
        '{
            title: "Permissão negada",
            text: "Você não possui permissão para esta ação.",
            icon: "error",
            button: {
                text: "OK",
                className: "btn-danger waves-effect waves-light"
            }
        }', 'now', URL_BASE.'/'.$this->core->action_urlName);
    }
}