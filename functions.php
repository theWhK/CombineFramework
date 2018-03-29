<?php
/*
 * by theWhK - 2018
 */

require_once 'constants.php';

/**
 * Verifica chaves de arrays
 *
 * Verifica se a chave existe no array e se ela tem algum valor.
 *
 * @param array  $array O array
 * @param string $key   A chave do array
 * @return string|null  O valor da chave do array ou nulo
 */
function chk_array($array, $key) {
	// Verifica se a chave existe no array
	if (isset($array[$key]) && !empty($array[$key])) {
		// Retorna o valor da chave
		return $array[$key];
	}
	
	// Retorna nulo por padrão
	return null;
}

/**
 * Check if a table exists in the current database.
 *
 * @param PDO $pdo PDO instance connected to a database.
 * @param string $table Table to search for.
 * @return bool TRUE if table exists, FALSE if no table found.
 */
function tableExists($pdo, $table) {

    // Try a select statement against the table
    // Run it in try/catch in case PDO is in ERRMODE_EXCEPTION.
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }

    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
}

/**
 * Processa um status code para enviar à interface e encerrar o programa.
 * 
 * @param code $code código do status
 */
function statusCode($code) 
{
	echo "Erro " . $code;
	exit;
}

/**
 * Realiza o dump da variável passada dentro da tag pre.
 * 
 * @param   any   $var          variável a ser dumpada.
 * @param   bool  $willExit     dar exit no script após o dump?
 * 
 * @return void
 */
function var_dump_pre($var, $willExit = false)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";

    if ($willExit) {
        exit;
    }
}

/**
 * Redireciona o usuário para o link entregue, e encerra o script.
 * 
 * @param   string  $url    URL a ser redirecionada.
 * 
 * @return  void
 */
function redirectTo($url)
{
    echo "<script>window.location = '{$url}';</script>";
    exit;
}

/**
 * Limpa a URL para evitar XSS.
 * 
 * @param string $url URL a ser processada.
 * 
 * @return string URL limpa.
 */
function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // Estamos interessados somente em links relacionados provenientes de $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

/**
 * Protege dados externos contra diversos tipos de hijack.
 * 
 * @param any $input dados de entrada.
 * 
 * @return any
 */
function protect($input)
{
    if (is_string($input)) $input = filter_var($input, FILTER_SANITIZE_STRING);

    return $input;
}