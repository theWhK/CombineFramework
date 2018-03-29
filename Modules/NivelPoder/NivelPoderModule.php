<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\NivelPoder;

use Combine\Modules\BD\BDModule;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Cuida de uma hierarquia de poder de acesso em determinado
 * conjunto de usuários.
 * 
 * @abstract a hierarquia de acesso é:
 * Superuser, ID 2: possui todos os privilégios do sistema e
 * poder para modificações quaisquer no painel.
 * Administrador, ID 1: possui todos os privilégios concedidos
 * e acesso à áreas restritas.
 * Usuário, ID 0: possui privilégios garantidos apenas.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class NivelPoderModule
{  
    /**
     * Conexão ao banco de dados.
     */
    private $conn;

    /**
     * Opções.
     * 
     * @abstract Campos disponíveis:
     * 'TABELA_USUARIOS': (usuarios)                                nome da tabela com os usuários.
     * 'CHAVE_NIVEL_ACESSO': (nivelUso)                              chave do nível de acesso do usuário.
     */
    public $options;

    /**
     * Inicializa o banco de dados.
     * 
     * @param string $prefix prefixo para isolar a estrutura de login.
     * @param array $dataStructure estrutura dos rótulos
     */
    public function __construct($conn, $options = array()) 
    {
        // PDO Connect
        $this->conn = $conn;

        // Armazena as opções e as processa juntamente às opções padrão
        $options_default = array(
            'TABELA_USUARIOS'               =>      'usuarios',
            'CHAVE_NIVEL_ACESSO'            =>      'nivelUso'
        );
        $this->options = array_replace($options_default, $options);
    }

    /**
     * Verifica o poder de acesso do usuário.
     * 
     * @param int $idUsuario ID do usuário a verificar.
     * 
     * @return string "normal", "adm" ou "su"
     */
    public function getPower($idUsuario)
    {
        // Caso vazio, retorna falso
        if (!isset($idUsuario))
            return false;
            
        // Verifica o usuário armazenado no banco
        $stmt = $this->conn->PDO->prepare(
            "SELECT {$this->options['CHAVE_NIVEL_ACESSO']}
             FROM {$this->options['TABELA_USUARIOS']}
             WHERE id = ?
             LIMIT 1");
        if (!$stmt->execute([$idUsuario])) {
            // statusCode(500)
            return false;
        }
        
        // Armazena o resultado da query
        $result = $stmt->fetch()[$this->options['CHAVE_NIVEL_ACESSO']];

        // Checa o resultado e retorna
        switch ($result) {
            case 0:
                return "normal";
                break;
            case 1:
                return "adm";
                break;
            case 2:
                return "su";
                break;
        }
    }

    /**
     * Verifica o poder de acesso do usuário.
     * 
     * @param int $idUsuario ID do usuário.
     * @param string $power poder de acesso a ser concedido.
     * "normal", "adm" ou "su"
     * 
     * @return bool 
     */
    public function setPower($idUsuario, $power)
    {
        // Coloca o ID de acordo com a string passada
        switch ($idUsuario) {
            case "normal":
                $valor = 0;
                break;

            case "adm":
                $valor = 1;
                break;
            case "su":
                $valor = 2;
                break;
        }

        // Executa a query
        $stmt = $this->conn->PDO->prepare(
            "UPDATE {$this->options['TABELA_USUARIOS']}
             SET {$this->options['CHAVE_NIVEL_ACESSO']} = ?
             WHERE id = ?");
        if (!$stmt->execute([$valor, $idUsuario])) {
            // statusCode(500)
            return false;
        }
        
        // Checa se houve alteração e retorna
        if ($stmt->rowCount() > 0)
            return true; return false;
    }

    /**
     * Verifica se o usuário logado é superusuário.
     * 
     * @param int $idUsuario ID do usuário
     * 
     * @return string
     */
    public function isSuperUser($idUsuario)
    {
        // Checa o resultado e retorna
        if ($this->getPower($idUsuario) == "su")
            return true; return false;
    }

    /**
     * Verifica se o usuário logado é administrador.
     * 
     * @param int $idUsuario ID do usuário
     * 
     * @return string
     */
    public function isAdmUser($idUsuario)
    {
        // Checa o resultado e retorna
        if ($this->getPower($idUsuario) == "adm")
            return true; return false;
    }
}