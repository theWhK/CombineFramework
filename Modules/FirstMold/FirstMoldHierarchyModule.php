<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\FirstMold;

use PDO;
use Combine\Modules\BD\BDModule;
use Combine\Modules\CRUD\CRUDModule;
use Combine\Modules\FirstMold\FirstMoldPermissionsModule;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Gerencia as hierarquias duma Action baseada no Primeiro Molde.
 * Há dois CRUDs neste Módulo: de Comandos e de Métodos.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class FirstMoldHierarchyModule
{
    /**
     * Conexão ao banco.
     */
    private $conn;

    /**
     * Prefixo.
     */
    public $prefix;

    /**
     * Módulo de CRUD.
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
        $this->prefix = $prefix;

        // Conexão ao banco de dados
        $this->conn = $conn;

        // Módulo base: CRUD
        $this->CRUD = new CRUDModule([
            $this->prefix.'_commands',
            $this->prefix.'_methods'],
            $this->conn);
    }

    /**
     * Valida os dados do formulário de Comando, indica as flags e as armazena no buffer $bufferData
     * 
     * @param bool $editMode ativa o modo de edição
     * 
     * @return bool TRUE se os dados estiverem em condições para escrita, FALSE caso não
     */
    public function command_validateFormData($editMode = false) 
    {
        // ID: filtro
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $this->bufferData['id'] = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        }

        // ID do Comando Pai: filtro
        if (isset($_POST['id_comando_pai'])) {
            $this->bufferData['id_comando_pai'] = filter_var($_POST['id_comando_pai'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['id_comando_pai'][] = 'empty';
        }

        // Rótulo: filtro e campo obrigatório
        if (isset($_POST['rotulo']) && !empty($_POST['rotulo'])) {
            $this->bufferData['rotulo'] = filter_var($_POST['rotulo'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['rotulo'][] = 'empty';
        }

        // URL Amigável: filtro
        if (isset($_POST['urlAmigavel'])) {
            $this->bufferData['urlAmigavel'] = filter_var($_POST['urlAmigavel'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['urlAmigavel'] = '';
        }

        // URL Amigável: filtro
        if (isset($_POST['nomeArquivo'])) {
            $this->bufferData['nomeArquivo'] = filter_var($_POST['nomeArquivo'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['nomeArquivo'][] = 'empty';
        }

        // Ícone do Font Awesome: filtro
        if (isset($_POST['classeIcone'])) {
            $this->bufferData['classeIcone'] = filter_var($_POST['classeIcone'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['classeIcone'] = '';
        }

        // Status: filtro e remontagem para int
        if (isset($_POST['status'])) {
            if (filter_var($_POST['status'], FILTER_SANITIZE_STRING) == "ativado") {
                $this->bufferData['status'] = '1';
            }
        } else {
            $this->bufferData['status'] = '0';
        }

        // Descrição: filtro
        if (isset($_POST['descricao'])) {
            $this->bufferData['descricao'] = filter_var($_POST['descricao'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['descricao'] = '';
        }

        // Verifica se a URL amigável já existe; em modo de edição,
        // anula-se o positivo se o registro não foi alterado
        if (@!in_array('empty', $this->bufferData['flag']['urlAmigavel'])) {
            // Busca por registros iguais
            $stmt = $this->conn->PDO->prepare(
                "SELECT id FROM `{$this->prefix}_commands` WHERE `urlAmigavel` = ?");
            $stmt->execute([$this->bufferData['urlAmigavel']]);
            
            // Armazena o resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                if ($editMode) {
                    if ($result['id'] != $this->bufferData['id']) {
                        $this->bufferData['flag']['urlAmigavel'][] = 'alreadyExists';
                    }
                } else {
                    $this->bufferData['flag']['urlAmigavel'][] = 'alreadyExists';
                }
            }
        }

        // Verifica se o nome de arquivo já existe; em modo de edição,
        // anula-se o positivo se o registro não foi alterado
        if (@!in_array('empty', $this->bufferData['flag']['nomeArquivo'])) {
            // Busca por registros iguais
            $stmt = $this->conn->PDO->prepare(
                "SELECT id FROM `{$this->prefix}_commands` WHERE `nomeArquivo` = ?");
            $stmt->execute([$this->bufferData['nomeArquivo']]);
            
            // Armazena o resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                if ($editMode) {
                    if ($result['id'] != $this->bufferData['id']) {
                        $this->bufferData['flag']['nomeArquivo'][] = 'alreadyExists';
                    }
                } else {
                    $this->bufferData['flag']['nomeArquivo'][] = 'alreadyExists';
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
     * Escreve o Comando novo na tabela.
     * 
     * @param array $data dados. Caso seja deixada em branco, a função usará os dados em buffer $bufferData
     * 
     * @return bool
     */
    public function command_create($data = null) 
    {
        // Caso os dados venham por parâmetro, os usa para cadastro; caso não, usa do buffer $bufferData
        if (!$data) {
            $data = $this->bufferData;
        }

        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // INSERÇÃO NA TABELA DE COMANDOS
                // Prepara a query
                $stmt = $this->conn->PDO->prepare(
                'INSERT INTO '.$this->prefix.'_commands
                (
                    id_comando_pai,
                    rotulo,
                    urlAmigavel,
                    nomeArquivo,
                    classeIcone,
                    status,
                    descricao
                )
                VALUES
                (
                    :id_comando_pai,
                    :rotulo,
                    :urlAmigavel,
                    :nomeArquivo,
                    :classeIcone,
                    :status,
                    :descricao
                )');

                // Vincula os parâmetros
                $stmt->bindParam(':id_comando_pai', $data['id_comando_pai']);
                $stmt->bindParam(':rotulo', $data['rotulo']);
                $stmt->bindParam(':urlAmigavel', $data['urlAmigavel']);
                $stmt->bindParam(':nomeArquivo', $data['nomeArquivo']);
                $stmt->bindParam(':classeIcone', $data['classeIcone']);
                $stmt->bindParam(':status', $data['status']);
                $stmt->bindParam(':descricao', $data['descricao']);

                // Executa a query
                $stmt->execute();

                // Resgata a ID gerada
                $id_comandoNovo = $this->conn->PDO->lastInsertId();

            // INSERÇÃO NA TABELA DE PERMISSÕES
                // Prepara a query
                $stmt = $this->conn->PDO->prepare(
                'INSERT INTO '.$this->prefix.'_permissoes_lista
                (
                    nome,
                    descricao,
                    tipo,
                    idRegistroAtrelado
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?
                )');
            
                // Executa a query
                $stmt->execute([
                    "Acesso ao comando ".$data['rotulo'],
                    "Libera o acesso ao comando ".$data['rotulo'].". Permissão criada automaticamente.",
                    "command",
                    $id_comandoNovo
                ]);

            // COMMIT
            $this->conn->PDO->commit();
        }
        catch (PDOException $e) {
            $this->conn->PDO->rollBack();
            statusCode(500);

            return false;
        }

        return true;
    }
    
    /**
     * Retorna uma lista com os Comandos resgatados. Lembre-se que, caso haja apenas uma linha de resultado,
     * a mesma será colocada como um array dentro do array, assim como fosse com vários itens.
     * 
     * @param string $where comando SQL caso a leitura seja específica. Lembre-se de colocar o verbo WHERE no comando.
     * 
     * @return array multi-array com dados
     */
    public function command_read($where = '')
    {
        $stmt = $this->conn->PDO->prepare(
        'SELECT
        *
        FROM '.$this->prefix.'_commands ' . $where . ';');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Atualiza os dados na tabela.
     * 
     * @param int     $id_edicao      ID a ser editado. Caso em branco, usará o ID em buffer $bufferData
     * @param array   $data           dados. Caso em branco, a função usará os dados em buffer $bufferData
     * 
     * @return bool
     */
    public function command_update($id_edicao = null, $data = null) 
    {
        // Caso o ID venha por parâmetro, o usa para edição; caso não, usa do buffer $bufferData
        if (!$id_edicao) {
            $id_edicao = $this->bufferData['id'];
        }
        
        // Caso os dados venham por parâmetro, os usa para edição; caso não, usa do buffer $bufferData
        if (!$data) {
            $data = $this->bufferData;
        }

        // Prepara a query
        $stmt = $this->conn->PDO->prepare(
        'UPDATE '.$this->prefix.'_commands SET
        id_comando_pai = :id_comando_pai,
        rotulo = :rotulo,
        urlAmigavel = :urlAmigavel,
        nomeArquivo = :nomeArquivo,
        classeIcone = :classeIcone,
        status = :status,
        descricao = :descricao
        WHERE id = :id');

        // Vincula os parâmetros
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':id_comando_pai', $data['id_comando_pai']);
        $stmt->bindParam(':rotulo', $data['rotulo']);
        $stmt->bindParam(':urlAmigavel', $data['urlAmigavel']);
        $stmt->bindParam(':nomeArquivo', $data['nomeArquivo']);
        $stmt->bindParam(':classeIcone', $data['classeIcone']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':descricao', $data['descricao']);

        // Executa a query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Apaga a linha informada.
     * 
     * @param int/array     $id_apagar  ID da linha a ser apagada. Pode receber um array com vários IDs.
     * 
     * @return bool
     */
    public function command_delete($id_apagar = null) {
        // Encapsula o item único em um conjunto
        if (!is_array($id_apagar)) $id_apagar = [$id_apagar];
        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // Apaga o(s) comando(s) passado(s)
            $deleteStatus = $this->CRUD->delete($id_apagar);

            // Apaga os métodos atrelados
            $this->method_deleteByCommand($id_apagar);

            // Apaga as permissões e concessões relativas aos comandos passados
            $FirstMoldPermissionsModule = new FirstMoldPermissionsModule($this->conn, $this->prefix);
            $permsStatus = $FirstMoldPermissionsModule->deleteByType("command", $id_apagar);

            // Coloca os comandos-filho do comando que está sendo deletado em estado
            // de comando-pai
            $itens = implode(",", $id_apagar);
            $upChildrenStatus = $this->query(
                "UPDATE {$this->prefix}_commands 
                 SET id_comando_pai = 0
                 WHERE id_comando_pai IN ({$itens})");

            // Commita as alterações
            $this->conn->PDO->commit();

            return true;
        } catch (PDOException $e) {
            $this->conn->PDO->rollBack();

            // statusCode(500);
            return false;
        }
    }

    /**
     * Valida os dados do formulário de Método, indica as flags e as armazena no buffer $bufferData
     * 
     * @param bool $editMode ativa o modo de edição
     * 
     * @return bool TRUE se os dados estiverem em condições para escrita, FALSE caso não
     */
    public function method_validateFormData($editMode = false) 
    {
        // ID: filtro
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $this->bufferData['id'] = filter_var($_POST['id'], FILTER_SANITIZE_STRING);
        }

        // ID do Comando Pai: filtro
        if (isset($_POST['id_comando_pai'])) {
            $this->bufferData['id_comando_pai'] = filter_var($_POST['id_comando_pai'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['id_comando_pai'][] = 'empty';
        }

        // Rótulo: filtro e campo obrigatório
        if (isset($_POST['rotulo']) && !empty($_POST['rotulo'])) {
            $this->bufferData['rotulo'] = filter_var($_POST['rotulo'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['rotulo'][] = 'empty';
        }

        // URL Amigável: filtro
        if (isset($_POST['urlAmigavel'])) {
            $this->bufferData['urlAmigavel'] = filter_var($_POST['urlAmigavel'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['urlAmigavel'] = '';
        }

        // Ícone do Font Awesome: filtro
        if (isset($_POST['nomeArquivo'])) {
            $this->bufferData['nomeArquivo'] = filter_var($_POST['nomeArquivo'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['flag']['nomeArquivo'] = 'empty';
        }

        // Ícone do Font Awesome: filtro
        if (isset($_POST['classeIcone'])) {
            $this->bufferData['classeIcone'] = filter_var($_POST['classeIcone'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['classeIcone'] = '';
        }

        // Local: filtro
        if (isset($_POST['local'])) {
            $this->bufferData['local'] = filter_var($_POST['local'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['local'] = '';
        }

        // Status: filtro e remontagem para int
        if (isset($_POST['status'])) {
            if (filter_var($_POST['status'], FILTER_SANITIZE_STRING) == "ativado") {
                $this->bufferData['status'] = '1';
            }
        } else {
            $this->bufferData['status'] = '0';
        }

        // Descrição: filtro
        if (isset($_POST['descricao'])) {
            $this->bufferData['descricao'] = filter_var($_POST['descricao'], FILTER_SANITIZE_STRING);
        } else {
            $this->bufferData['descricao'] = '';
        }

        // Verifica se a URL amigável já existe; em modo de edição,
        // anula-se o positivo se o registro não foi alterado
        if (@!in_array('empty', $this->bufferData['flag']['urlAmigavel'])) {
            // Busca por registros iguais
            $stmt = $this->conn->PDO->prepare(
                "SELECT id FROM `{$this->prefix}_methods` 
                 WHERE `urlAmigavel` = ? AND id_comando_pai = ?");
            $stmt->execute([
                $this->bufferData['urlAmigavel'],
                $this->bufferData['id_comando_pai']
            ]);
            
            // Armazena o resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                if ($editMode) {
                    if ($result['id'] != $this->bufferData['id']) {
                        $this->bufferData['flag']['urlAmigavel'][] = 'alreadyExists';
                    }
                } else {
                    $this->bufferData['flag']['urlAmigavel'][] = 'alreadyExists';
                }
            }
        }

        // Verifica se o nome de arquivo já existe; em modo de edição,
        // anula-se o positivo se o registro não foi alterado
        if (@!in_array('empty', $this->bufferData['flag']['nomeArquivo'])) {
            // Busca por registros iguais
            $stmt = $this->conn->PDO->prepare(
                "SELECT id FROM `{$this->prefix}_methods` 
                 WHERE `nomeArquivo` = ? AND id_comando_pai = ?");
            $stmt->execute([
                $this->bufferData['nomeArquivo'],
                $this->bufferData['id_comando_pai']
            ]);
            
            // Armazena o resultado
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                if ($editMode) {
                    if ($result['id'] != $this->bufferData['id']) {
                        $this->bufferData['flag']['nomeArquivo'][] = 'alreadyExists';
                    }
                } else {
                    $this->bufferData['flag']['nomeArquivo'][] = 'alreadyExists';
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
     * Escreve o Método novo na tabela.
     * 
     * @param array $data dados. Caso seja deixada em branco, a função usará os dados em buffer $bufferData
     * 
     * @return bool
     */
    public function method_create($data = null) 
    {
        // Caso os dados venham por parâmetro, os usa para cadastro; caso não, usa do buffer $bufferData
        if (!$data) {
            $data = $this->bufferData;
        }

        // Busca o nome do Comando responsável, para armazenar na
        // tabela de permissões
        $stmt = $this->conn->PDO->prepare(
        'SELECT
        rotulo
        FROM '.$this->prefix.'_commands
        WHERE id = ?;');
        $stmt->execute([$data['id_comando_pai']]);
        $command_search = $stmt->fetchAll();

        // Verifica se há o Comando e retorna o nome;
        // Caso não exista, retorna erro
        if (count($command_search) == 1) {
            $rotulo_comando = $command_search[0]['rotulo'];
        } else {
            statusCode(500);
        }

        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // INSERÇÃO NA TABELA DE MÉTODOS
                // Prepara a query
                $stmt = $this->conn->PDO->prepare(
                'INSERT INTO '.$this->prefix.'_methods
                (
                    id_comando_pai,
                    rotulo,
                    urlAmigavel,
                    nomeArquivo,
                    classeIcone,
                    local,
                    status,
                    descricao
                )
                VALUES
                (
                    :id_comando_pai,
                    :rotulo,
                    :urlAmigavel,
                    :nomeArquivo,
                    :classeIcone,
                    :local,
                    :status,
                    :descricao
                )');

                // Vincula os parâmetros
                $stmt->bindParam(':id_comando_pai', $data['id_comando_pai']);
                $stmt->bindParam(':rotulo', $data['rotulo']);
                $stmt->bindParam(':urlAmigavel', $data['urlAmigavel']);
                $stmt->bindParam(':nomeArquivo', $data['nomeArquivo']);
                $stmt->bindParam(':classeIcone', $data['classeIcone']);
                $stmt->bindParam(':local', $data['local']);
                $stmt->bindParam(':status', $data['status']);
                $stmt->bindParam(':descricao', $data['descricao']);

                // Executa a query
                $stmt->execute();

                // Resgata a ID gerada
                $id_metodoNovo = $this->conn->PDO->lastInsertId();

            // INSERÇÃO NA TABELA DE PERMISSÕES
                // Prepara a query
                $stmt = $this->conn->PDO->prepare(
                'INSERT INTO '.$this->prefix.'_permissoes_lista
                (
                    nome,
                    descricao,
                    tipo,
                    idRegistroAtrelado
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?
                )');
            
                // Executa a query
                $stmt->execute([
                    "Acesso ao método ".$data['rotulo'],
                    "Libera o acesso ao método ".$data['rotulo'].", do comando ".$rotulo_comando.". Permissão criada automaticamente.",
                    "method",
                    $id_metodoNovo
                ]);

            // COMMIT
            $this->conn->PDO->commit();
        }
        catch (PDOException $e) {
            $this->conn->PDO->rollBack();
            statusCode(500);

            return false;
        }

        return true;
    }
    
    /**
     * Retorna uma lista com os Comandos resgatados. Lembre-se que, caso haja apenas uma linha de resultado,
     * a mesma será colocada como um array dentro do array, assim como fosse com vários itens.
     * 
     * @param string $where comando SQL caso a leitura seja específica. Lembre-se de colocar o verbo WHERE no comando.
     * 
     * @return array multi-array com dados
     */
    public function method_read($where = '')
    {
        $stmt = $this->conn->PDO->prepare(
        'SELECT
        *
        FROM '.$this->prefix.'_methods ' . $where . ';');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Atualiza os dados na tabela.
     * 
     * @param int     $id_edicao      ID a ser editado. Caso em branco, usará o ID em buffer $bufferData
     * @param array   $data           dados. Caso em branco, a função usará os dados em buffer $bufferData
     * 
     * @return bool
     */
    public function method_update($id_edicao = null, $data = null) 
    {
        // Caso o ID venha por parâmetro, o usa para edição; caso não, usa do buffer $bufferData
        if (!$id_edicao) {
            $id_edicao = $this->bufferData['id'];
        }
        
        // Caso os dados venham por parâmetro, os usa para edição; caso não, usa do buffer $bufferData
        if (!$data) {
            $data = $this->bufferData;
        }

        // Prepara a query
        $stmt = $this->conn->PDO->prepare(
        'UPDATE '.$this->prefix.'_methods SET
        id_comando_pai = :id_comando_pai,
        rotulo = :rotulo,
        urlAmigavel = :urlAmigavel,
        nomeArquivo = :nomeArquivo,
        classeIcone = :classeIcone,
        status = :status,
        local = :local,
        descricao = :descricao
        WHERE id = :id');

        // Vincula os parâmetros
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':id_comando_pai', $data['id_comando_pai']);
        $stmt->bindParam(':rotulo', $data['rotulo']);
        $stmt->bindParam(':urlAmigavel', $data['urlAmigavel']);
        $stmt->bindParam(':nomeArquivo', $data['nomeArquivo']);
        $stmt->bindParam(':classeIcone', $data['classeIcone']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':local', $data['local']);
        $stmt->bindParam(':descricao', $data['descricao']);

        // Executa a query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Apaga a o método informado e as permissões atreladas a ele.
     * 
     * @param int/array     $id_apagar  ID da linha a ser apagada. Pode receber um array com vários IDs.
     *
     * @return bool
     */
    public function method_delete($id_apagar = null) 
    {
        // Inicia a transação
        $this->conn->PDO->beginTransaction();

        try {
            // Apaga o(s) comando(s) passado(s)
            $deleteStatus = $this->CRUD->delete($id_apagar, 1);

            // Apaga as permissões e concessões relativas aos métodos passados
            $FirstMoldPermissionsModule = new FirstMoldPermissionsModule($this->conn, $this->prefix);
            $permsStatus = $FirstMoldPermissionsModule->deleteByType("method", $id_apagar);

            // Commita as alterações
            $this->conn->PDO->commit();

            return true;
        } catch (PDOException $e) {
            $this->conn->PDO->rollBack();

            // statusCode(500);
            return false;
        }
    }

    /**
     * Apaga os métodos relacionados com os comandos passados.
     * 
     * @param int/array $id_apagar ID dos comandos.
     * 
     * @return bool
     */
    public function method_deleteByCommand($id_apagar) 
    {
        // Encapsula o item solto em um conjunto
        if (!is_array($id_apagar)) $id_apagar = [$id_apagar];

        // Monta a coleção de interrogações para
        // inserção dos argumentos
        $idParaQuery = array_fill(1, count($id_apagar), '?');
        $idParaQuery = implode(',', $idParaQuery);

        // Busca os IDs das permissões relacionadas
        $stmt = $this->conn->PDO->prepare(
            "SELECT id FROM {$this->prefix}_methods
             WHERE id_comando_pai IN ({$idParaQuery})");

        // Caso a execução da query dê errado, retorna falso
        if (!$stmt->execute($id_apagar)) return false;

        // Caso não haja resultados, retorna falso
        if ($stmt->rowCount() < 1) return false;

        // Gera o array com todos os IDs a serem apagados
        $idsParaApagar = [];
        foreach ($stmt->fetchAll() as $item)  {
            $idsParaApagar[] = $item['id'];
        }

        // Executa o método para apagar as permissões
        return $this->method_delete($idsParaApagar);
    }

    /**
     * Faz uma query personalizada.
     * 
     * @param string $query Query SQL a ser executada.
     * @param array $params Parâmetros para definir nas variáveis da query. Use "?".
     * 
     * @return array
     */
    public function query($query, $params = null)
    {
        return $this->CRUD->query($query, $params);
    }

    /**
     * Retorna um multi-array com os comandos e métodos organizados de
     * forma hierárquica.
     * 
     * @return array
     */
    public function readNested()
    {
        // Lê os comandos-pai
        $stmt = $this->query(
            "SELECT
                id,
                rotulo,
                urlAmigavel,
                nomeArquivo,
                classeIcone,
                descricao,
                status
            FROM
                {$this->prefix}_commands
            WHERE
                id_comando_pai = 0");
        $comandosPai = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Lê os comandos-filho
        $stmt = $this->query(
            "SELECT
                id,
                id_comando_pai,
                rotulo,
                urlAmigavel,
                nomeArquivo,
                classeIcone,
                descricao,
                status
            FROM
                {$this->prefix}_commands
            WHERE
                id_comando_pai != 0");
        $comandosFilho = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Para cada comando-pai, busca e emenda os métodos
        foreach ($comandosPai as $item) {
            // Joga o comando no próximo conjunto, com a chave sendo o ID
            $retorno[$item['id']] = $item;

            // Busca os métodos
            $stmt = $this->query(
                "SELECT
                    id,
                    rotulo,
                    urlAmigavel,
                    nomeArquivo,
                    classeIcone,
                    descricao,
                    status
                FROM
                    {$this->prefix}_methods
                WHERE
                    id_comando_pai = ?",
                [$item['id']]);
            $metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Armazena os métodos dentro do comando
            if (!empty($metodos))
                $retorno[$item['id']]['listaMetodos'] = $metodos;
        }

        // Para cada comando-filho, busca e emenda os métodos e
        // coloca o comando-filho dentro do comando-pai
        foreach ($comandosFilho as $item) {
            // Busca os métodos
            $stmt = $this->query(
                "SELECT
                    id,
                    rotulo,
                    urlAmigavel,
                    nomeArquivo,
                    classeIcone,
                    descricao,
                    status
                FROM
                    {$this->prefix}_methods
                WHERE
                    id_comando_pai = ?",
                [$item['id']]);
            $metodos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Armazena os métodos dentro do comando
            if (!empty($metodos))
                $item['listaMetodos'] = $metodos;

            // Joga o comando no próximo conjunto, nestado no comando-pai correspondente
            $retorno[$item['id_comando_pai']]['listaComandosFilho'][$item['id']] = $item;
        }

        // Retorna a lista nestada
        return $retorno;
    }
}