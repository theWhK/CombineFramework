<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Modules\CRUD;

use PDO;
use Combine\Modules\BD\BDModule;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

/**
 * Funções-base para as conexões PDO do projeto.
 * 
 * @author Willian Hideki <willhkblz@gmail.com>
 */
class CRUDModule
{
    /**
     * Tabelas a serem utilizadas.
     */
    public $tables;

    /**
     * Objeto da conexão.
     */
    public $conn;

    /**
     * Levanta a conexão ao banco, caso não tenha sido enviado.
     * 
     * @param array $tables tabelas a serem utilizadas.
     * A de índice 0 será a usada por padrão.
     * @param BDModule $conn conexão ao banco de dados
     */
    public function __construct($tables, $conn)
    {
        // Atribuição das tabelas
        $this->tables = $tables;

        // Atribuição da conexão
        $this->conn = $conn;
    }

    /**
     * Escreve o registro novo na tabela.
     * 
     * @param array $data dados a serem gravados.
     * @param int $tableKey ordem da tabela (no conjunto de tabelas $this->tables) que será utilizada. Por padrão, será a 0.
     * 
     * @return bool
     */
    public function create($data, $tableId = 0) 
    {
        // Trabalha os dados enviados para a query
        $chaves = [];
        $valores = [];
        $interrogacoes = [];
        foreach ($data as $key => $value) {
            array_push($chaves, $key);
            array_push($valores, $value);
            array_push($interrogacoes, '?');
        }
        $interrogacoes = implode(',', $interrogacoes);

        // Prepara a query
        $stmt = $this->conn->PDO->prepare(
        'INSERT INTO '.$this->tables[0].'
        ('.implode(',', $chaves).')
        VALUES
        ('.$interrogacoes.');');

        // Executa a query
        if ($stmt->execute($valores)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Retorna uma lista com os registros. Lembre-se que, caso haja apenas uma linha de resultado,
     * a mesma será colocada como um array dentro do array, assim como fosse com vários itens.
     * 
     * @param array $chaves chaves a serem resgatadas da tabela.
     * @param string $where comando SQL caso a leitura seja específica. Lembre-se de colocar o verbo WHERE no comando.
     * @param int $tableKey ordem da tabela (no conjunto de tabelas $this->tables) que será utilizada. Por padrão, será a 0.
     * 
     * @return array multi-array com dados dos registros
     */
    public function read($chaves, $where = '', $tableKey = 0)
    {
        $stmt = $this->conn->PDO->prepare(
        'SELECT '.implode(',', $chaves).' FROM '.$this->tables[$tableKey].' '.$where.';');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza os dados do registro na tabela.
     * 
     * @param int $id_edicao ID do registro a ser editado
     * @param array $data dados para atualização
     * @param int $tableKey ordem da tabela (no conjunto de tabelas $this->tables) que será utilizada. Por padrão, será a 0.
     * 
     * @return bool
     */
    public function update($id_edicao, $data, $tableKey = 0) 
    {
        // Trabalha os dados enviados para a query
        $index = 0;
        $maxIndex = count($data) - 1;
        $chaves = "";
        $valores = [];
        foreach ($data as $key => $value) {
            // Caso seja o último registro, não coloca a vírgula
            if ($index < $maxIndex) {
                $chaves .= '`'.$key.'` = ?,';
            } else {
                $chaves .= '`'.$key.'` = ? ';
            }
            array_push($valores, $value);
            $index++;
        }

        // Anexa o ID na fila de dados de valores
        $valores[] = $id_edicao;

        // Prepara a query
        $stmt = $this->conn->PDO->prepare(
        "UPDATE {$this->tables[$tableKey]} 
         SET {$chaves}
         WHERE id = ?");

        // Executa a query
        if ($stmt->execute($valores)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Apaga a linha informada.
     * 
     * @param int/array $id_apagar ID da linha a ser apagada. Pode receber um array com vários IDs.
     * @param string $where comando SQL WHERE, para deletes personalizados. Caso usado, o parâmetro 1 será ignorado.
     * @param int $tableKey ordem da tabela (no conjunto de tabelas $this->tables) que será utilizada. Por padrão, será a 0.
     * 
     * @return bool
     */
    public function delete($id_apagar, $tableKey = 0) {
        // Caso o ID não esteja dentro de array, coloca-o
        if (!is_array($id_apagar)) $id_apagar = [$id_apagar];

        // Montagem da query
        $where = ' WHERE ';
        $index = 0;
        foreach ($id_apagar as $id) {
            if ($index > 0) $where .= ' OR ';
            $where .= 'id =  ?';
            $index++;
        }

        $stmt = $this->conn->PDO->prepare('DELETE FROM '.$this->tables[$tableKey].$where);

        // Executa a query
        if ($stmt->execute($id_apagar)) {
            return true;
        }

        return false;
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
        $stmt = $this->conn->PDO->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }
}