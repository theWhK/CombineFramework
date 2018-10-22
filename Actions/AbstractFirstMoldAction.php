<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Action;

use PDO;
use Combine\Action\Action;
use Combine\Modules\BD\BDModule;
use Combine\Modules\FirstMold\FirstMoldHierarchyModule;
use Combine\Modules\FirstMold\FirstMoldPermissionsModule;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Primeiro Molde: Modelo de Action.
 * 
 * @abstract atente-se aos atributos desta classe. Elas são parte
 * fundamental da esquemática de funcionamento do Primeiro Molde,
 * que possui um conjunto de Modules homônimo, responsável pela 
 * gerência da hierarquia de comandos e métodos e permissões 
 * de usuário para as mesmas.
 * @abstract Cuidado para não confundir os termos de Comandos e 
 * Métodos com recursos do paradigma OOP ou da linguagem de pro-
 * gramação.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
abstract class AbstractFirstMoldAction extends Action
{
    /**
     * Conexão com banco.
     */
    public $conn;

    /**
     * Nome da Classe.
     */
    public $className;

    /**
     * Prefixo para DB.
     */
    public $dbPrefix;

    /**
     * Cópia do Itinerário para ser trabalhado.
     */
    public $itinerary;

    /**
     * Segunda instrução da URL, denominada Comando.
     */
    public $command;

    /**
     * Rótulo do Comando.
     */
    public $commandName;

    /**
     * Nome do arquivo relacionado ao Comando.
     */
    public $commandArchive;

    /**
     * ID do Comando em voga, caso haja. Caso não, é null.
     */
    public $commandId;

    /**
     * ID da permissão do Comando em voga, caso haja. Caso não, é null.
     */
    public $commandPerm;

    /**
     * Terceira instrução da URL, denominada Método.
     */
    public $method;

    /**
     * Rótulo do Método.
     */
    public $methodName;

    /**
     * Nome do arquivo relacionado ao Método.
     */
    public $methodArchive;

    /**
     * ID do Método em voga, caso haja. Caso não, é null.
     */
    public $methodId;

    /**
     * ID da permissão do Método em voga, caso haja. Caso não, é null.
     */
    public $methodPerm;

    /**
     * 4ª à N-ésima instruções da URL, denominados Parâmetros.
     */
    public $parameters = [];

    /**
     * Método construtor.
     */
    public function __construct($core) 
    {
        // Construtor do pai
        parent::__construct($core);

        // Conecta ao banco de dados
        $this->conn = new BDModule();

        // Copia o Itinerário para trabalhar os Parâmetros
        $this->itinerary = $core->itinerary;

        // Caso não haja Comando definido, leva para a index
        if (!$core->itinerary[0]) {
            $this->command = 'index';
        } else {
            $this->command = $core->itinerary[0];
        }

        // Caso não haja Método definido, deixa-o em branco
        if (!$core->itinerary[1]) {
            $this->method = '';
        } else {
            $this->method = $core->itinerary[1];
        }
        
        // Preenche os Parâmetros
        if (is_array($this->itinerary)) {
            unset($this->itinerary[0]);
            unset($this->itinerary[1]);
            $this->parameters = array_values($this->itinerary);
        } else {
            $this->parameters = NULL;
        }

        // Prepara os atributos relativos ao comando e método
        $this->prepareCommand();
        $this->prepareMethod();

        // Se o Comando indicado existir, executa-o
		if (method_exists($this, $this->commandArchive)) {
			$this->{$this->commandArchive}();
			
			return;
		}
		
		// Página não encontrada
		statusCode(404);

		return;
    }

    /**
     * Prepara os atributos da Action relativos ao Comando.
     * 
     * @return void
     */
    protected function prepareCommand()
    {
        // Procura e armazena os IDs dos Comando e Método, caso existam
        // Resgata o ID do Comando
        if (!empty($this->command)) {
            // Prepara a query
            $stmt = $this->conn->PDO->prepare(
            "SELECT
            id,
            rotulo,
            nomeArquivo
            FROM
            {$this->dbPrefix}_commands
            WHERE `urlAmigavel` = ?");

            // Executa a query
            $stmt->execute(array(
                $this->command
            ));

            // Checa a existência de retorno
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->commandId = $data['id'];
                if (empty($this->commandId)) {
                    $this->commandId == null;
                }

                $this->commandArchive = $data['nomeArquivo'];
                if (empty($this->commandArchive)) {
                    $this->commandArchive == null;
                }

                $this->commandName = $data['rotulo'];
                if (empty($this->commandName)) {
                    $this->commandName == null;
                }

                // Procura e armazena o ID da permissão
                $stmt = $this->conn->PDO->prepare(
                    "SELECT id
                     FROM {$this->dbPrefix}_permissoes_lista
                     WHERE tipo = 'command'
                     AND idRegistroAtrelado = ?
                     LIMIT 1");
                $stmt->execute([$this->commandId]);

                if ($stmt->rowCount() > 0) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    $this->commandPerm = $data['id'];
                    
                    if (!isset($this->commandPerm) && empty($this->commandPerm)) {
                        $this->commandPerm == null;
                    }
                }
            }
        }
    }

    /**
     * Prepara os atributos da Action relacionados ao Método.
     * 
     * @return void
     */
    protected function prepareMethod()
    {
        // Resgata o ID do Método
        if (!empty($this->method)) {
            // Prepara a query
            $stmt = $this->conn->PDO->prepare(
            "SELECT
            id,
            rotulo,
            nomeArquivo
            FROM
            {$this->dbPrefix}_methods
            WHERE urlAmigavel = ?
            AND id_comando_pai = ?");

            // Executa a query
            $stmt->execute(array(
                $this->method,
                $this->commandId
            ));

            // Checa a existência de retorno
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->methodId = $data['id'];
                if (empty($this->methodId)) {
                    $this->methodId == null;
                }

                $this->methodArchive = $data['nomeArquivo'];
                if (empty($this->methodArchive)) {
                    $this->methodArchive == null;
                }

                $this->methodName = $data['rotulo'];
                if (empty($this->methodName)) {
                    $this->methodName == null;
                }

                // Procura e armazena o ID da permissão
                $stmt = $this->conn->PDO->prepare(
                    "SELECT id
                    FROM {$this->dbPrefix}_permissoes_lista
                    WHERE tipo = 'method'
                    AND idRegistroAtrelado = ?
                    LIMIT 1");
                $stmt->execute([$this->methodId]);

                if ($stmt->rowCount() > 0) {
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    $this->methodPerm = $data['id'];
                    
                    if (!isset($this->methodPerm) && empty($this->methodPerm)) {
                        $this->methodPerm == null;
                    }
                }
            }
        }
    }

    abstract function index();
}