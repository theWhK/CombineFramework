<?php
/**
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Verifica a existência e validade do token
if (isset($_GET['token']) && isset($_GET['email'])) {
    // Salva o GET em variável
    $email = protect($_GET['email']);
    $token = protect($_GET['token']);

    // Verifica a existência do token
    $id = $LoginModule->requestRecoveryProcess($email, $token);

    if (!$id) {
        // Leva à página de recuperação de senha inicial
        redirectTo(URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/recuperar-senha');
    }
} else {
    // Leva à página de recuperação de senha inicial
    redirectTo(URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/recuperar-senha');
}

// Caso haja envio de formulário para login, inicia o processo
if (isset($_POST['password']) && isset($_POST['repeatPassword'])) {
    // Salva o POST em variável
    $pwd = protect($_POST['password']);
    $repeatPwd = protect($_POST['repeatPassword']);

    // Envia uma mensagem de erro caso as senhas não confiram
    if ($pwd != $repeatPwd) {
        $NotificacoesModule->sendCustom(
            'adminRecoverPwdNew',
            '<p class="text-danger">As senhas não são iguais. Tente novamente.</p>', 
            'now', 
            URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/recuperar-senha-nova?token='.$token.'&email='.$email);
    }

    // Busca o ID do usuário e armazena
    $stmt = $this->conn->PDO->prepare(
        "SELECT id
         FROM `usuarios`
         WHERE email = ?");
    $stmt->execute([$email]);
    $idUsuario = $stmt->fetch()['id'];

    // Apaga o token de recuperação
    $LoginModule->deleteRecoveryProcess($id);

    if ($UsuariosModule->update($idUsuario, ['password' => $pwd])) {
        // Atualização de senha feita com sucesso;
        // retorna à página de login com mensagem
        // de sucesso
        $NotificacoesModule->sendCustom(
            'adminLogin',
            '<p class="text-success">Senha redefinida com sucesso. Faça login com a nova senha para continuar.</p>', 
            'now',
            URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command);
    }
}

// RESPONSE
require PATH_ABS.'/Response/'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;