<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\Cargos;

use Combine\Modules\CRUD\CRUDModule;
use Combine\Modules\Usuarios\UsuariosModule;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Cuida dos cargos dos usuários.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class CargosModule
{
    /**
     * Conexão ao banco.
     */
    private $conn;

    /**
     * Módulo de CRUD.
     */
    public $CRUD;

    /**
     * Buffer de dados.
     */
    public $bufferData = array();

    /**
     * Inicializa o banco de dados.
     */
    public function __construct($conn) 
    {
        // Guarda a conexão ao banco
        $this->conn = $conn;

        // Módulo de CRUD
        $this->CRUD = new CRUDModule(
            ['cargos'],
            $this->conn);
    }

    /**
     * Valida os dados do formulário, indica as flags e as armazena no buffer $bufferData
     * 
     * @return bool TRUE se os dados estiverem em condições para escrita, FALSE caso não
     */
    public function validateFormData() 
    {
        // ID: filtro
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $this->bufferData['id'] = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        }

        // ID do pai: filtro
        if (isset($_POST['id_depart'])) {
            $this->bufferData['id_depart'] = filter_var($_POST['id_depart'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['id_depart'] = '';
        }

        // Nome: filtro e campo obrigatório
        if (isset($_POST['nome']) && !empty($_POST['nome'])) {
            $this->bufferData['nome'] = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['nome'][] = 'empty';
        }

        // Descrição: filtro
        if (isset($_POST['descricao'])) {
            $this->bufferData['descricao'] = filter_var($_POST['descricao'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['descricao'] = '';
        }
        
        // Caso o array de flags esteja vazio, unseta ele
        if (empty($this->bufferData['flag'])) {
            unset($this->bufferData['flag']);
        }

        // Retorno: caso haja flags, retorna falso
        if ($this->bufferData['flag']) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Valida se o cargo recebido existe e o filtra.
     * 
     * @param int $idCargo ID do cargo.
     * 
     * @return int
     */
    public function checkCargoID($id)
    {
        // Filtra o dado externo
        $id = filter_var($id, FILTER_SANITIZE_STRING);

        // Se o dado for maior que zero, faz a checagem de ID
        if ($id > 0) {
            $retorno = $this->read('WHERE `id` = '.$id);
        } else {
            return 0;
        }

        // Caso o dado exista, retorna o próprio ID
        if (count($retorno) > 0) {
            return $id;
        } else {
            return 0;
        }
    }

    /**
     * Escreve o registro novo na tabela.
     * 
     * @param array $data dados do registro. Caso seja deixada em branco, a função usará os dados em buffer $bufferData
     * 
     * @return bool
     */
    public function create($data = null) 
    {
        // Caso os dados venham por parâmetro, os usa para cadastro; caso não, usa do buffer $bufferData
        if (!$data) {
            $data = $this->bufferData;
        }

        // Executa
        return $this->CRUD->create($data);
    }
    
    /**
     * Retorna uma lista com os registros. Lembre-se que, caso haja apenas uma linha de resultado,
     * a mesma será colocada como um array dentro do array, assim como fosse com vários itens.
     * 
     * @param string $where comando SQL caso a leitura seja específica. Lembre-se de colocar o verbo WHERE no comando.
     * 
     * @return array multi-array com dados dos registros
     */
    public function read($where = '')
    {
        return $this->CRUD->read([
        'id', 'id_depart', 'nome', 'descricao'], $where);
    }

    /**
     * Atualiza os dados do registro na tabela.
     * 
     * @param int     $id_edicao      ID do registro a ser editado. Caso em branco, usará o ID em buffer $bufferData
     * @param array   $data           dados do registro. Caso em branco, a função usará os dados em buffer $bufferData
     * 
     * @return bool
     */
    public function update($id_edicao = null, $data = null) 
    {
        // Caso o ID venha por parâmetro, o usa para edição; caso não, usa do buffer $bufferData
        if (!$id_edicao) {
            $id_edicao = $this->bufferData['id'];
        }
        
        // Caso os dados venham por parâmetro, os usa para edição; caso não, usa do buffer $bufferData
        if (!$data) {
            $data = $this->bufferData;
        }

        // Remove o ID do conjunto de dados
        unset($data['id']);

        // Executa
        return $this->CRUD->update($id_edicao, $data);
    }

    /**
     * Apaga a linha informada.
     * 
     * @param int/array     $id_apagar  ID da linha a ser apagada. Pode receber um array com vários IDs.
     * @param string        $where      comando SQL WHERE, para deletes personalizados. Caso usado, o parâmetro 1 será ignorado.
     */
    public function delete($id_apagar = null) {
        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // Apaga o cargo
            $deleteStatus = $this->CRUD->delete($id_apagar);

            // Reinicia os usuários que possuem tal cargo
            $UsuariosModule = new UsuariosModule($this->conn);
            $resetStatus = $UsuariosModule->modifyCargo($id_apagar);

            // Commita as alterações
            $this->conn->PDO->commit();

            return true;
        } catch (PDOException $e) {
            // Desfaz as alterações
            $this->conn->PDO->rollBack();
            // statusCode(500);
            return false;
        }
    }
}