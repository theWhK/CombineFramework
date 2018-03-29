<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Caso já esteja logado, redireciona para a página inicial da Ação
if ($LoginModule->loginCheck() == true) {
    $NotificacoesModule->sendAlert(
    '{
        title: "Ops!",
        text: "Você já está logado. Caso queira entrar com outro usuário, faça o logout antes.",
        icon: "warning",
        button: {
            text: "OK",
            className: "btn-warning waves-effect waves-light"
        }
    }', 'now', URL_BASE.'/'.$this->core->action_urlName);
}

// Caso haja envio de formulário para login, inicia o processo
if (isset($_POST['usuario']) && isset($_POST['senha'])) {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $rememberMe = $_POST['rememberMe'] ? 1 : 0;
 
    if ($LoginModule->loginWithUsername($usuario, $senha, $rememberMe) == true) {
        redirectTo(URL_BASE.'/'.$this->core->action_urlName);
    } else {
        $NotificacoesModule->sendCustom('adminLogin',
        '<p class="text-danger">Nome de usuário ou senha inválidos. Tente novamente.</p>', 
        'now', URL_BASE.'/'.$this->core->action_urlName.'/login');
    }
}

// RESPONSE
require PATH_ABS.'/Response/'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;