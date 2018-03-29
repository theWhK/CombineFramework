<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Executa o logout
$LoginModule->logout();

// Redireciona para a página inicial
redirectTo(URL_BASE."/".$this->core->action_urlName);

return;