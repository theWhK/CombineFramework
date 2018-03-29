<?php
/**
 * by theWhK - 2018
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

// Caso haja envio de formulário para login, inicia o processo
if (isset($_POST['email'])) {
    $email = protect($_POST['email']);

    // Gera o token para o usuário
    $token = $LoginModule->startRecovery($email);
 
    // Envia o email com as etapas para recuperação
    if ($token) {
        $mail = new PHPMailer(DEBUG);                              // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.blacksuit.com.br';                // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'teste@blacksuit.com.br';         // SMTP username
            $mail->Password = 'blck1201@';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('teste@blacksuit.com.br', USER_NAME);
            $mail->addAddress($email);     // Add a recipient

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = USER_NAME.' - Processo de recuperação de senha';
            $mail->CharSet = 'UTF-8';
            $mail->Body = "
                <h1 style='text-align: center;'>".USER_NAME."</h1>
                <h3 style='text-align: center;'>Processo de recuperação de senha</h3>
                <p>Este email está sendo enviado pois foi solicitado um processo de recuperação de senha para a conta com este email registrado. Se não foi você, desconsidere este email.</p>
                <p>Para prosseguir com a recuperação, clique no link/botão abaixo:</p>
                <a href='".URL_BASE."/".$this->core->action_urlName."/".$this->command."/recuperar-senha-nova?token=".$token."&email=".$email."'><div style='display: inline-block; padding: 10px; background: #c66; border-radius: 3px; color: #fff; font-weight: 700;'>Clique aqui para recuperar sua senha</div></a>
            ";
            $mail->AltBody = 'Este email está sendo enviado pois foi solicitado um processo de recuperação 
            de senha para a conta do website '.URL_BASE.' com este email registrado. 
            Para prosseguir, copie e cole o link a seguir: '
            .URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command
            .'/recuperar-senha-nova?token='.$token.'&email='.$email
            .' Caso não tenha sido solicitada uma troca, apenas desconsidere este email.';

            $mail->send();
        } catch (Exception $e) {
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            //statusCode(500);
            $NotificacoesModule->sendCustom('adminRecoverPwd',
                '<p class="text-danger">Não foi possível concluir o processo. Tente novamente mais tarde.</p>', 
                'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/recuperar-senha');
        }
    }

    // Retorna a mensagem de provável sucesso
    $NotificacoesModule->sendCustom('adminRecoverPwd',
        '<p class="text-success">Caso haja um cadastro com este email no sistema, os passos para recuperação da senha estarão num email enviado.</p>', 
        'now', URL_BASE.'/'.$this->core->action_urlName.'/'.$this->command.'/recuperar-senha');
}

// RESPONSE
require PATH_ABS.'/Response/'.$this->core->action_urlName.'/'.$this->commandArchive.'/'.$this->methodArchive.'.php';

return;