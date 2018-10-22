<?php
/**
 * by theWhK - 2018
 */

namespace Combine\Modules\Sessao;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Cuida dos dados que são colocados em sessão, separando-os 
 * por módulos responsáveis.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class SessaoModule
{
    private $_prefix;

    /**
     * Armazena o prefixo que englobará os dados da instância.
     * 
     * @param string $prefix     
     */
    public function __construct($prefix) 
    {
        $this->_prefix = $prefix;
    }

    /**
     * Empurra um novo conjunto de dados para a sessão alocada.
     * 
     * @param array $data dados a serem enviados.
     * @param bool $update os dados novos serão atualizações 
     * dos antigos (true) ou substituirão completamente (false)? Falso por padrão.
     * 
     * @return void
     */
    public function set($data, $update = false)
    {
        // Caso os dados existam, continua o processo
        // Caso não, trata de forma nula
        if (isset($data)) {
            // Força array
            if (!is_array($data)) $data = [$data];
            
            // Caso o update seja verdadeiro, atualiza os campos
            // Caso seja falso, destrói os valores antigos e escreve os novos
            if ($update == true) {
                $_SESSION[$this->_prefix] = @array_merge($_SESSION[$this->_prefix], $data);
            } else {
                $_SESSION[$this->_prefix] = $data;
            }
            
        } else {
            // Caso o update seja verdadeiro, mantém os dados antigos
            // Caso seja falso, deixa a sessão em falso
            if ($update == true) {
                return;
            } else {
                $_SESSION[$this->_prefix] = false;
            }
        }
    }

    /**
     * Resgata os dados da sessão alocada.
     * 
     * @return any/bool dados da sessão. 'false' se não houver dados.
     */
    public function get()
    {
        if (isset($_SESSION[$this->_prefix])) {
            return $_SESSION[$this->_prefix];
        } else {
            return false;
        }
    }

    /**
     * Limpa a sessão alocada.
     * 
     * @return void
     */
    public function clean()
    {
        unset($_SESSION[$this->_prefix]);
    }
}