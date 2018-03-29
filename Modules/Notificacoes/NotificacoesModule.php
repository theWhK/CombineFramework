<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\Notificacoes;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Trata e envia as notificações à interface.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class NotificacoesModule
{
    /**
     * Buffer dos códigos de notificação que serão enviados à interface.
     */
    private $buffer = array();

    public function __construct()
    {
        // Coloca a configuração inicial do Toastr
        $buffer[] = '
        toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-bottom-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
        }';
    }

    /**
     * Dispara um alerta: caixa obstrusiva no centro da tela
     * com informações e botões interativos.
     * 
     * @param   string  $params     parâmetros do Sweet Alert.
     * @param   string  $execTime   o alerta será executado 'now' ou 'onFlush'?
     * @param   string  $redirect   âncora de redirecionamento.
     * 
     * @return  void
     */
    public function sendAlert($params, $execTime = 'now', $redirect = null)
    {
        $code = "setTimeout(() => {swal(" . $params . ");}, 500);";

        switch ($execTime) {
            case "now":
                $_SESSION['data_notificacoes']['alert'][] = $code;
                if (isset($redirect)) {
                    redirectTo($redirect);
                }
                exit;
            break;

            case "onFlush":
                $this->appendCodeToBuffer($code);
            break;
        }
    }

    /**
     * Dispara um toast: caixa não-obstrusiva de mensagem rápida.
     * 
     * @param   string  $type           tipo da notificação. "success", "info", "error", "warning"
     * @param   string  $text           mensagem a ser exibida para o usuário.
     * @param   string  $execTime       o alerta será executado 'now' ou 'onFlush'?
     * @param   string  $redirect       âncora de redirecionamento.
     * @param   string  $config         configurações do Toastr.
     * @param   bool    $persistConfig  manter as configurações enviadas (true) ou repor a logo anterior para os próximos toasts (false)?
     * 
     * @abstract o recebimento de novas configurações ainda não está desenvolvido.
     * 
     * @return  void
     */
    public function sendToast($type, $text, $execTime = 'now', $redirect = null, $config = null, $persistConfig = false)
    {
        $code = "toastr." . $type . "(" . $text . ");";

        switch ($execTime) {
            case "now":
                $_SESSION['data_notificacoes']['toast'][] = $code;
                if (isset($redirect))
                    redirectTo($redirect);
            break;

            case "onFlush":
                $this->appendCodeToBuffer($code);
            break;
        }
    }

    /**
     * Recebe notificações personalizadas para envios específicos.
     * 
     * @param string $groupName nome do agrupamento (chave do array) aonde
     * será armazenado os dados.
     * @param string $data dados.
     * @param   string  $execTime       o alerta será executado 'now' ou 'onFlush'?
     * @param   string  $redirect       âncora de redirecionamento.
     * 
     * @return void
     */
    public function sendCustom($groupName, $data, $execTime = 'now', $redirect = null)
    {
        switch ($execTime) {
            case "now":
                $_SESSION['data_notificacoes'][$groupName][] = $data;
                if (isset($redirect))
                    redirectTo($redirect);
            break;

            case "onFlush":
                $_SESSION['data_notificacoes'][$groupName][] = $data;
            break;
        }
    }

    /**
     * Insere o código na listagem de scripts notificantes.
     * 
     * @param   string  $code   código a ser emendado.
     */
    private function appendCodeToBuffer($code)
    {
        // Insere o código do alerta no array da sessão
        $this->buffer[] = $code;
    }
}