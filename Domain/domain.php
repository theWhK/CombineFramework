<?php
/*
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Item do núcleo do software responsável por processar e disponibilizar
 * os dados entregues pela URL.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class Domain
{
    /**
     * Domínio principal da URL.
     */
    public $mainDomain;

    /**
     * Primeira instrução da URL, denominada Ação.
     */
    public $action;

    /**
     * 2ª à N-ésima instruções da URL, denominada Itinerário.
     */
    public $itinerary;

    public function __construct() {
        // Puxa os dados do domínio principal
        $this->mainDomain = apache_request_headers()['Host'];
        //$this->mainDomain = $_SERVER['SERVER_NAME'];

        // Verifica se há um domínio composto enviado
        if (isset($_GET['path'])) {
            $path = $_GET['path'];

            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);
            // $caminho = extProtect($path);

            $path = explode('/', $path);

            $this->action = chk_array($path, 0);

            if (chk_array($path, 1)) {
                unset($path[0]);

                $this->itinerary = array_values($path);
            }
        }
    }
}