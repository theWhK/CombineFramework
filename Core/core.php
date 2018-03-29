<?php
/*
 * by theWhK - 2018
 */

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Item do núcleo do software responsável por escolher o roteiro
 * lógico (denominado Action) e processá-lo.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class Core
{
    /**
     * Namespace da Ação definida pela URL.
     */
    public $action;

    /**
     * Nome da classe da Ação.
     */
    public $action_className;

    /**
     * 1º item da URL, denominada Ação.
     */
    public $action_urlName;

    /**
     * Array com informações do Itinerário definidos pela URL.
     */
    public $itinerary;

    public function __construct($domain) 
    {

        // LÓGICA DA AÇÃO

            // Remove caracteres inválidos do nome da Ação e salva na variável do objeto em voga
            $this->action = preg_replace('/[^a-zA-Z-_]/i', '', $domain->action);

            // Se não houver Ação indicada, engatilha a Home
            if (!isset($this->action) || empty($this->action)) {
                $this->action = 'home';
            }

            // Guarda o nome da Ação passado na URL no atributo interno
            $this->action_urlName = $this->action;

            // Transforma o nome da Ação de "trace-spaced" em "CamelCase"
            $inProcessActionName = explode('-', $this->action);
            if (count($inProcessActionName) > 1) {
                foreach ($inProcessActionName as $key => $value) {
                    $inProcessActionName[$key] = ucfirst($value);
                }
                $this->action = implode('', $inProcessActionName);
            } else {
                // Caso não seja "trace-spaced", apenas capitaliza a palavra
                $this->action = ucfirst($this->action);
            }

            // Faz a troca de nomes equivalentes para Ação
            switch ($this->action) {
                case "Index":
                    $this->action = "Home";
                break;
            }

            // Salva o nome da classe da Ação no atributo interno
            $this->action_className = $this->action;
            
            // Verifica a existência da Ação recebida
            // Caso não, envia 404
            if (!file_exists(PATH_ABS . '/Actions/' . $this->action . 'Action.php')) {
                statusCode(404);
                return;
            }

            // Monta a chamada da Ação
            $this->action = 'Combine\Action\\' . $this->action . 'Action';
            
        // LÓGICA DO ITINERÁRIO

            $this->itinerary = $domain->itinerary;
    }
}