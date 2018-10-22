<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\FirstMold;

use Combine\Modules\CRUD\CRUDModule;
use PDO;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Gerencia as permissões de acesso duma Action baseada no Primeiro Molde.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class FirstMoldPermissionsModule
{
    /**
     * Conexão ao banco.
     */
    private $conn;

    /**
     * Prefixo;
     */
    public $prefix;

    /**
     * Módulo CRUD.
     */
    private $CRUD;

    /**
     * Buffer de dados.
     */
    public $bufferData = array();

    /**
     * Inicializa o banco de dados.
     */
    public function __construct($conn, $prefix) 
    {
        // Definição do prefixo
        $this->conn = $conn;

        // Definição do conexão
        $this->prefix = $prefix;

        // Módulo base: CRUD
        $this->CRUD = new CRUDModule([
            $this->prefix.'_permissoes_lista',
            $this->prefix.'_permissoes_concedidas'],
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
            $this->bufferData['id'] = protect($_POST['id']);
        }

        // Nome: filtro e campo obrigatório
        if (isset($_POST['nome']) && !empty($_POST['nome'])) {
            $this->bufferData['nome'] = protect($_POST['nome']);
        } else {
            $this->bufferData['flag']['nome'][] = 'empty';
        }

        // Descrição: filtro
        if (isset($_POST['descricao'])) {
            $this->bufferData['descricao'] = protect($_POST['descricao']);
        } else {
            $this->bufferData['descricao'] = '';
        }

        // Tipo: filtro e campo obrigatório
        if (isset($_POST['tipo'])) {
            $this->bufferData['tipo'] = protect($_POST['tipo']);
        } else {
            $this->bufferData['flag']['tipo'][] = 'empty';
        }

        // ID do Registro atrelado: filtro
        if (isset($_POST['idRegistroAtrelado'])) {
            $this->bufferData['idRegistroAtrelado'] = protect($_POST['idRegistroAtrelado']);
        }

        // Tipo: filtro e campo obrigatório
        if (isset($_POST['nivelUso'])) {
            $this->bufferData['nivelUso'] = protect($_POST['nivelUso']);
        } else {
            $this->bufferData['nivelUso'] = 0;
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
     * Valida os dados do formulário de permissões concedidas
     * 
     * @param array $data dados das permissões. array linear,
     * com conjunto de IDs de permissões, em int
     * 
     * @return bool
     */
    public function validatePermsFormData($data) 
    {
        // Caso não haja dados, retorna positivo
        if ($data == null) return true;

        if (is_array($data)) {
            foreach ($data as $key =>  $value) {
                $data[$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }

            $this->bufferData['permissoes'] = $data;

            return true;
        } else {
            return false;
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
        'id', 'nome', 'descricao', 'tipo', 'idRegistroAtrelado', 'nivelUso'], $where);
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
     * Apaga-se as permissões informadas e remove as concessões relacionadas.
     * 
     * @param int/array     $id_apagar  ID da linha a ser apagada. Pode receber um array com vários IDs.
     *
     * @return bool
     */
    public function delete($id_apagar) {
        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // Apaga as permissões
            $deleteStatus = $this->CRUD->delete($id_apagar);

            // Apaga as concessões
            $revokeStatus = $this->revoke($id_apagar);

            // Commita as alterações
            $this->conn->PDO->commit();

            return true;
        } catch (PDOException $e) {
            $this->conn->PDO->rollBack();

            return false;
        }
    }

    /**
     * Apaga-se as permissões através do ID do comando ou método e remove
     * as concessões relacionadas.
     * 
     * @param string    $tipo       "command" ou "method".
     * @param int/array $idPerms    IDs dos comandos ou métodos.
     * 
     * @return bool TRUE caso ele localize e apague as linhas.
     */
    public function deleteByType($tipo, $idPerms)
    {
        // Encapsula o item solto em um conjunto
        if (!is_array($idPerms)) $idPerms = [$idPerms];

        // Monta a coleção de interrogações para
        // inserção dos argumentos
        $idParaQuery = array_fill(1, count($idPerms), '?');
        $idParaQuery = implode(',', $idParaQuery);

        // Busca os IDs das permissões relacionadas
        $stmt = $this->conn->PDO->prepare(
            "SELECT id FROM {$this->prefix}_permissoes_lista
             WHERE tipo = ?
             AND idRegistroAtrelado IN ({$idParaQuery})");

        // Caso a execução da query dê errado, retorna falso
        if (!$stmt->execute(array_merge([$tipo], $idPerms))) return false;

        // Caso não haja resultados, retorna falso
        if ($stmt->rowCount() < 1) return false;

        // Gera o array com todos os IDs a serem apagados
        $idsParaApagar = [];
        foreach ($stmt->fetchAll() as $item)  {
            $idsParaApagar[] = $item['id'];
        }

        // Executa o método para apagar as permissões
        return $this->delete($idsParaApagar);
    }

    /**
     * Faz uma query personalizada.
     * 
     * @param string $query Query SQL a ser executada.
     * @param array $params Parâmetros para definir nas variáveis da query. Use "?".
     * 
     * @return PDOStatement solicitação SQL em PDO. 
     */
    public function query($query, $params = null)
    {
        return $this->CRUD->query($query, $params);
    }

    /**
     * Verifica se a permissão a ser colocada ou editada 
     * pode ser processada pelo usuário em voga.
     * 
     * @param string $nivelUsoUsuario nível de poder do usuário.
     * @param int $nivelUsoPermissao nível de poder da permissão.
     * Caso deixada em branco, utilizará os dados do buffer
     * 
     * @return bool
     */
    public function canSetPerm($nivelUsoUsuario, $nivelUsoPermissao = null)
    {
        // Caso em branco, utiliza a bufferData
        if ($nivelUsoPermissao == null)
            $nivelUsoPermissao = $this->bufferData['nivelUso'];

        // Permite de acordo com o nível do usuário
        switch ($nivelUsoUsuario) {
            case "su":
                return true;
                break;

            case "adm":
                if ($nivelUsoPermissao == 0)
                    return true;
                return false;
                break;
                
            case "normal":
            default:
                return false;
                break;
            
        }
    }

    /**
     * Concede permissão a dado usuário.
     * 
     * @param array $idUsuario ID do usuário.
     * @param int/array $idPermissoes ID das permissões a serem setadas
     * 
     * @return bool
     */
    public function grant($idUsuario, $permissoes)
    {
        // Processa as permissões para mantê-lo em array
        if (!is_array($permissoes)) $permissoes = [$permissoes];

        // Remove itens que já estejam colocados
        foreach ($permissoes as $key => $item) {
            $stmt = $this->conn->PDO->prepare(
                "SELECT 1 FROM {$this->prefix}_permissoes_concedidas
                 WHERE idUsuario = ?
                AND idPermissao = ?"
            );
            $stmt->execute([$idUsuario, $item]);
            if ($stmt->rowCount() > 0) {
                unset($permissoes[$key]);
            }
        }

        // Montagem da query
            // Monta a string de valores
            $values = "";
            foreach ($permissoes as $item) {
                $values .= "({$idUsuario}, {$item}),";
            }
            $values = substr($values, 0, -1);

            // Prepara a query
            $stmt = $this->conn->PDO->prepare(
                "INSERT INTO {$this->prefix}_permissoes_concedidas
                (idUsuario, idPermissao)
                VALUES {$values}");

        // Executa a query
        if ($stmt->execute())
            return true; return false;
    }

    /**
     * Reescreve as permissões de determinado usuário.
     * 
     * @param array $idUsuario ID do usuário.
     * @param int/array $idPermissoes ID das permissões a serem setadas
     * 
     * @return bool
     */
    public function regrant($idUsuario, $permissoes)
    {
        // Processa as permissões para mantê-lo em array
        if (!is_array($permissoes)) $permissoes = [$permissoes];

        // Remove as permissões antigas
        $stmt = $this->conn->PDO->prepare(
            "DELETE FROM {$this->prefix}_permissoes_concedidas
             WHERE idUsuario = ?");
        $stmt->execute([$idUsuario]);

        // Montagem da query
            // Monta a string de valores
            $values = "";
            foreach ($permissoes as $item) {
                $values .= "({$idUsuario}, {$item}),";
            }
            $values = substr($values, 0, -1);

            // Prepara a query
            $stmt = $this->conn->PDO->prepare(
                "INSERT INTO {$this->prefix}_permissoes_concedidas
                (idUsuario, idPermissao)
                VALUES {$values}");

        // Executa a query
        if ($stmt->execute())
            return true; return false;
    }

    /**
     * Remove concessão/concessões ao determinado usuário.
     * 
     * @param int/array $idPerms    ID das permissões.
     * @param int       $idUsuario  ID do usuário. Caso deixado em branco,
     * a permissão será removida de todos os usuários.
     * 
     * @return bool
     */
    public function revoke($idPerms, $idUsuario = null)
    {
        // Encapsula o item solto em um conjunto
        if (!is_array($idPerms)) $idPerms = [$idPerms];

        // Monta a coleção de interrogações para
        // inserção dos argumentos
        $permsSentence = array_fill(1, count($idPerms), '?');
        $permsSentence = implode(',', $permsSentence);

        // Define os parâmetros finais
        if ($idUsuario) {
            // Caso o usuário não seja nulo, coloca a ordem
            // de remoção específica para ele
            $userStatement = " AND idUsuario = ?";
            $finalParams = array_merge($idPerms, [$idUsuario]);
        } else {
            $userStatement = "";
            $finalParams = $idPerms;
        }

        // Prepara a query
        $stmt = $this->conn->PDO->prepare(
            "DELETE FROM {$this->prefix}_permissoes_concedidas
             WHERE idPermissao IN ({$permsSentence})
             {$userStatement}");

        // Executa a query
        if ($stmt->execute($finalParams))
            return true; return false;
    }

    /**
     * Verifica se o usuário possui permissão.
     * 
     * @param int   $idUsuario
     * @param int   $idPermissao
     * 
     * @return bool
     */
    public function request($idUsuario, $idPermissao)
    {
        // Prepara a query
        $stmt = $this->conn->PDO->prepare(
            "SELECT 1 FROM {$this->prefix}_permissoes_concedidas
             WHERE idUsuario = ? AND idPermissao = ?");

        // Executa a query
        $stmt->execute([$idUsuario, $idPermissao]);

        // Verifica se há o registro e retorna
        if ($stmt->rowCount() > 0)
            return true; return false;
    }

    /**
     * Retorna todas as permissões que o usuário possui.
     * 
     * @param int $idUsuario
     * 
     * @return array
     */
    public function lista($idUsuario)
    {
        // Prepara a query
        $stmt = $this->conn->PDO->prepare(
            "SELECT idPermissao FROM {$this->prefix}_permissoes_concedidas
             WHERE idUsuario = ?");

        // Executa a query
        $stmt->execute([$idUsuario]);

        // Organiza a resposta em um array
        $retornoCanvas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($retornoCanvas as $item) {
            $retorno[] = $item['idPermissao'];
        }

        return $retorno;
    }

    /**
     * Define permissões pré-definidas para um determinado grupo.
     * 
     * @param string $suffixTable sufixo da tabela de predef's a utilizar. Ex.: "cargos"
     * @param int $idGrupo ID do grupo a trabalhar.
     * @param array $permissoes array linear com os IDs das permissões a pré-definir.
     * @param string $insertType define se irá sobreescrever os dados anteriores "write"
     * ou irá adicionar aos existentes "alter"
     * 
     * @return bool
     */
    public function setPredef($suffixTable, $idGrupo, $permissoes, $insertType = "write")
    {
        // Concatena o nome da tabela
        $tableName = $this->prefix . "_permissoes_predef_" . $suffixTable;

        // Verifica se o grupo já possui registro
        $stmt = $this->CRUD->query(
            'SELECT permissoes FROM '.$tableName.' WHERE idGrupo = ?',
            [$idGrupo]);

        if ($stmt->rowCount() > 0) {
            // Já possui registro; executa uma atualização

            switch ($insertType) {
                // Faz reescrita nas pré-definições
                case "write":
                    // Prepara o novo registro
                    $novoRegistro = serialize($permissoes);

                    // Executa a atualização
                    $stmt = $this->CRUD->query(
                        'UPDATE '.$tableName.' SET permissoes = ? WHERE idGrupo = ?',
                        [$novoRegistro, $idGrupo]);
                    
                    // Retorno
                    return $stmt->rowCount() ? true : false;
                break;

                // Faz adições nas pré-definições
                case "alter":
                    // Prepara o novo registro
                    $registroAnterior = unserialize($stmt->fetch()['permissoes']);
                    $novoRegistro = array_merge($registroAnterior, $permissoes);
                    $novoRegistro = array_unique($novoRegistro);
                    $novoRegistro = serialize($novoRegistro);

                    // Executa a atualização
                    $stmt = $this->CRUD->query(
                        'UPDATE '.$tableName.' SET permissoes = ? WHERE idGrupo = ?',
                        [$novoRegistro, $idGrupo]);
                    
                    // Retorno
                    return $stmt->rowCount() ? true : false;
                break;

                default:
                    return false;
                break;
            }
        } else {
            // Não há o registro; executa uma inserção

            // Prepara o novo registro
            $novoRegistro = serialize($permissoes);

            // Executa a inserção
            $stmt = $this->CRUD->query(
                'INSERT INTO '.$tableName.' VALUES (?, ?)',
                [$idGrupo, $novoRegistro]
            );

            // Retorno
            return $stmt->rowCount() ? true : false;
        }
    }

    /**
     * Resgata as pré-definições de determinado grupo.
     * 
     * @param string $suffixTable sufixo da tabela de predef's a utilizar. Ex.: "cargos"
     * @param int $idGrupo ID do grupo a trabalhar.
     * 
     * @return array/bool
     */
    public function getPredef($suffixTable, $idGrupo)
    {
        $stmt = $this->CRUD->query(
            'SELECT permissoes FROM '.$this->prefix.'_permissoes_predef_'.$suffixTable.' WHERE idGrupo = ?',
            [$idGrupo]);
        
        if ($stmt->rowCount() > 0) {
            return unserialize($stmt->fetch()['permissoes']);
        } else {
            return false;
        }
    }
}