<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\Usuarios;

use Combine\Classes\Bcrypt\Bcrypt;
use Combine\Modules\CRUD\CRUDModule;
use PDO;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Cuida dos dados dos usuários.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class UsuariosModule
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
        // Armazenamento da conexão ao banco
        $this->conn = $conn;

        // Módulo de CRUD
        $this->CRUD = new CRUDModule(
            ['usuarios'],
            $this->conn);
    }

    /**
     * Valida os dados do formulário, indica as flags e as armazena no buffer $bufferData
     * 
     * @param bool $editMode ativa ou desativa o tratamento para dados a atualizar ou novos
     * 
     * @return bool TRUE se os dados estiverem em condições para escrita, FALSE caso não
     */
    public function validateFormData($editMode = false) 
    {
        // ID: filtro
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $this->bufferData['id'] = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        }

        // Nome: filtro e campo obrigatório
        if (isset($_POST['nome']) && !empty($_POST['nome'])) {
            $this->bufferData['nome'] = filter_var($_POST['nome'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['nome'][] = 'empty';
        }

        // Sobrenome: filtro e campo obrigatório
        if (isset($_POST['sobrenome']) && !empty($_POST['sobrenome'])) {
            $this->bufferData['sobrenome'] = filter_var($_POST['sobrenome'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['sobrenome'][] = 'empty';
        }

        // Email: filtro
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $this->bufferData['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        } else {
            $this->bufferData['email'] = '';
        }

        // Nickname: filtro e campo obrigatório
        if (isset($_POST['nickname']) && !empty($_POST['nickname'])) {
            $this->bufferData['nickname'] = filter_var($_POST['nickname'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['nickname'][] = 'empty';
        }

        // Senha: filtro e campo obrigatório
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $this->bufferData['password'] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['password'][] = 'empty';
        }

        // Repetir senha: filtro e campo obrigatório
        if (isset($_POST['repeatPassword']) && !empty($_POST['repeatPassword'])) {
            $this->bufferData['repeatPassword'] = filter_var($_POST['repeatPassword'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['repeatPassword'][] = 'empty';
        }

        // Telefone: filtro
        if (isset($_POST['telefone'])) {
            $this->bufferData['telefone'] = filter_var($_POST['telefone'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['telefone'] = '';
        }

        // Celular: filtro
        if (isset($_POST['celular'])) {
            $this->bufferData['celular'] = filter_var($_POST['celular'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['celular'] = '';
        }

        // Descrição: filtro
        if (isset($_POST['descricao'])) {
            $this->bufferData['descricao'] = filter_var($_POST['descricao'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['descricao'] = '';
        }

        // ID do cargo: chave estrangeira
        if (!isset($this->bufferData['idCargo'])) {
            $this->bufferData['idCargo'] = 0;
        }

        // Nível de poder de uso: dado externo
        if (isset($_POST['nivelUso'])) {
            $this->bufferData['nivelUso'] = protect($_POST['nivelUso']);
        } else {
            $this->bufferData['nivelUso'] = 0;
        }

        // Verifica se as senhas são iguais
        if (isset($this->bufferData['password']) && isset($this->bufferData['repeatPassword'])) {
            if ($this->bufferData['password'] != $this->bufferData['repeatPassword']) {
                $this->bufferData['flag']['password'][] = 'mismatch';
            }
        }

        // Caso esteja em modo de edição, permite que os campos de senha sejam vazios
        if (is_array($this->bufferData['flag']['password']) && is_array($this->bufferData['flag']['repeatPassword'])) {
            if ($editMode && in_array('empty', $this->bufferData['flag']['password']) && in_array('empty', $this->bufferData['flag']['repeatPassword'])) {
                unset($this->bufferData['flag']['password']);
                unset($this->bufferData['flag']['repeatPassword']);            
            }
        }

        // Verifica se o nickname já existe; em modo de edição, anula-se o
        // positivo se o registro não foi alterado
        if (@!in_array('empty', $this->bufferData['flag']['nickname'])) {
            // Busca por registros iguais
            $stmt = $this->conn->PDO->prepare(
                'SELECT id FROM `usuarios` WHERE `nickname` = ?');
            $stmt->execute([$this->bufferData['nickname']]);
            
            // Armazena o resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                if ($editMode) {
                    if ($result['id'] != $this->bufferData['id']) {
                        $this->bufferData['flag']['nickname'][] = 'alreadyExists';
                    }
                } else {
                    $this->bufferData['flag']['nickname'][] = 'alreadyExists';
                }
            }
        }

        // Verifica se o email já existe; em modo de edição, anula-se o 
        // positivo se o registro não foi alterado
        if (@!in_array('empty', $this->bufferData['flag']['email'])) {
            // Busca por registros iguais
            $stmt = $this->conn->PDO->prepare(
                'SELECT id FROM `usuarios` WHERE `email` = ?');
            $stmt->execute([$this->bufferData['email']]);
            
            // Armazena o resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                if ($editMode) {
                    if ($result['id'] != $this->bufferData['id']) {
                        $this->bufferData['flag']['email'][] = 'alreadyExists';
                    }
                } else {
                    $this->bufferData['flag']['email'][] = 'alreadyExists';
                }
            }
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
     * Valida os dados do formulário público, indica as flags e as 
     * armazena no buffer $bufferData
     * 
     * @return bool TRUE se os dados estiverem em condições 
     * para escrita, FALSE caso não
     */
    public function validatePublicFormData() 
    {
        // Nome: filtro e campo obrigatório
        if (isset($_POST['nome']) && !empty($_POST['nome'])) {
            $this->bufferData['nome'] = protect($_POST['nome']);
        } else {
            $this->bufferData['flag']['nome'][] = 'empty';
        }

        // Sobrenome: filtro e campo obrigatório
        if (isset($_POST['sobrenome']) && !empty($_POST['sobrenome'])) {
            $this->bufferData['sobrenome'] = protect($_POST['sobrenome']);
        } else {
            $this->bufferData['flag']['sobrenome'][] = 'empty';
        }

        // Nickname: filtro e campo obrigatório
        if (isset($_POST['nickname']) && !empty($_POST['nickname'])) {
            $this->bufferData['nickname'] = protect($_POST['nickname']);
        } else {
            $this->bufferData['flag']['nickname'][] = 'empty';
        }

        // Senha: filtro e campo obrigatório
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $this->bufferData['password'] = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['password'][] = 'empty';
        }

        // Repetir senha: filtro e campo obrigatório
        if (isset($_POST['repeatPassword']) && !empty($_POST['repeatPassword'])) {
            $this->bufferData['repeatPassword'] = filter_var($_POST['repeatPassword'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['repeatPassword'][] = 'empty';
        }

        // Telefone: filtro
        if (isset($_POST['telefone'])) {
            $this->bufferData['telefone'] = filter_var($_POST['telefone'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['telefone'] = '';
        }

        // Celular: filtro
        if (isset($_POST['celular'])) {
            $this->bufferData['celular'] = filter_var($_POST['celular'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['celular'] = '';
        }

        // Descrição: filtro
        if (isset($_POST['descricao'])) {
            $this->bufferData['descricao'] = filter_var($_POST['descricao'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['descricao'] = '';
        }

        // Verifica se as senhas são iguais
        if (isset($this->bufferData['password']) && isset($this->bufferData['repeatPassword'])) {
            if ($this->bufferData['password'] != $this->bufferData['repeatPassword']) {
                $this->bufferData['flag']['password'][] = 'mismatch';
            }
        }

        // Caso esteja em modo de edição, permite que os campos de senha sejam vazios
        if (is_array($this->bufferData['flag']['password']) && is_array($this->bufferData['flag']['repeatPassword'])) {
            if (in_array('empty', $this->bufferData['flag']['password']) && in_array('empty', $this->bufferData['flag']['repeatPassword'])) {
                unset($this->bufferData['flag']['password']);
                unset($this->bufferData['flag']['repeatPassword']);            
            }
        }

        // Verifica se o nickname já existe; em modo de edição, anula-se o
        // positivo se o registro não foi alterado
        if (@!in_array('empty', $this->bufferData['flag']['nickname'])) {
            // Busca por registros iguais
            $stmt = $this->conn->PDO->prepare(
                'SELECT id FROM `usuarios` WHERE `nickname` = ?');
            $stmt->execute([$this->bufferData['nickname']]);
            
            // Armazena o resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                if ($result['id'] != $this->bufferData['id']) {
                    $this->bufferData['flag']['nickname'][] = 'alreadyExists';
                }
            }
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
     * Escreve o usuário novo na tabela.
     * 
     * @param array $data dados do usuário. Caso seja deixada em branco, a função usará os dados em buffer $bufferData
     * 
     * @return bool
     */
    public function create($data = null) 
    {
        // Caso os dados venham por parâmetro, os usa para cadastro; caso não, usa do buffer $bufferData
        if (!$data) {
            $data = $this->bufferData;
        }

        // Encripta a senha
        $data['password'] = Bcrypt::hash($data['password']);

        // Remove a senha repetida
        unset($data['repeatPassword']);

        // Executa a query
        return $this->CRUD->create($data);
    }
    
    /**
     * Retorna uma lista com os usuários resgatados. Lembre-se que, caso haja apenas uma linha de resultado,
     * a mesma será colocada como um array dentro do array, assim como fosse com vários itens.
     * 
     * @param string $where comando SQL caso a leitura seja específica. 
     * Lembre-se de colocar o verbo WHERE no comando.
     * 
     * @return array multi-array com dados dos usuários
     */
    public function read($where = '')
    {
        return $this->CRUD->read(
            [
                'id',
                'nickname',
                'nome',
                'sobrenome',
                'email',
                'telefone',
                'celular',
                'descricao',
                'idCargo',
                'nivelUso'
            ], $where);
    }

    /**
     * Atualiza os dados do usuário na tabela.
     * 
     * @param int     $id_edicao      ID do usuário a ser editado. Caso em branco, usará o ID em buffer $bufferData
     * @param array   $data           dados do usuário. Caso em branco, a função usará os dados em buffer $bufferData
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

        // Encripta a senha, caso esteja em processo de atualização também
        if (isset($data['password'])) {
            $data['password'] = Bcrypt::hash($data['password']);
        }

        // Remove a senha repetida
        unset($data['repeatPassword']);

        // Executa a query
        return $this->CRUD->update(
            $id_edicao,
            $data);
    }

    /**
     * Modifica os usuários com determinados cargos para nenhum ou outro cargo.
     * 
     * @param   int/array   $idCargos        ID do cargo cujos usuários estão atrelados.
     * @param   int         $idNovoCargo    ID do novo cargo a ser colocado. Caso em branco, deixará sem cargo.
     * 
     * @return  bool
     */
    public function modifyCargo($idCargos, $idNovoCargo = 0)
    {
        // Transforma o elemento único em conjunto
        if (!is_array($idCargos)) $idCargos = [$idCargos];

        // Monta a coleção de interrogações para
        // inserção dos argumentos
        $idParaQuery = array_fill(1, count($idCargos), '?');
        $idParaQuery = implode(',', $idParaQuery);

        // Executa a atualização
        $stmt = $this->conn->PDO->prepare(
            "UPDATE usuarios SET idCargo = ? WHERE idCargo IN ({$idParaQuery})");
        
        // Retorna
        if ($stmt->execute(array_merge([$idNovoCargo], $idCargos)))
            return true; return false;
    }

    /**
     * Apaga a linha informada.
     * 
     * @param int/array     $id_apagar  ID da linha a ser apagada. Pode receber um array com vários IDs.
     * 
     * @return bool
     */
    public function delete($id_apagar = null) {
        return $this->CRUD->delete($id_apagar);
    }
}