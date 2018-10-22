<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\BD;

use Combine\Classes\ExtendedPDO\ExtendedPDO;
use PDO;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Funções-base para as conexões PDO do projeto.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class BDModule
{
    /**
     * Objeto da conexão.
     */
    public $PDO;

    /**
     * Inicializa o banco de dados.
     * 
     * @param string $qualBanco seleciona o banco "central" ou do "usuario".
     */
    public function __construct($qualBanco = "central", $connOptions = array()) 
    {
        // Define a constante entregue no momento da conexão
        switch ($qualBanco) {
            case "central":
                $const_nomeBanco = DB_NAME;
            break;
            case "usuario":
                $const_nomeBanco = USER_DB;
            break;
        }

        // String de conexão
        $connString =  'mysql:host='.HOSTNAME.';
                        dbname='.$const_nomeBanco.';
                        charset=utf8';

        // Opções de conexão padrão
        $connOptionsDefault = array(
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_PERSISTENT => true);

        // Funde as opções de conexão passadas com as padrões
        $connOptions = array_replace($connOptionsDefault, $connOptions);

        // PDO Connect
        try 
        {
            $this->PDO = new ExtendedPDO($connString, DB_USER, DB_PASSWORD, $connOptions);
        }
        catch (PDOException $e) 
        {
            statusCode(500);
        }
    }
}