<?php
/*
 * by theWhK - 2018
 */

// Configurações do PHP
error_reporting(E_ALL ^ E_NOTICE);

// Caminho absoluto do projeto. Utilizado para carregar recursos e 
// verificar internalidade de operação
define('PATH_ABS', dirname(__FILE__));

// URL de prefixo para arquivos carregados externamente
define('URL_BASE', 'http://localhost/projetos/combineframework');

// Nome do host da base de dados
define('HOSTNAME', 'www.blacksuit.com.br');

// Nome do DB
define('DB_NAME', 'combiine');

// Usuário do DB
define('DB_USER', 'blacksui_user');

// Senha do DB
define('DB_PASSWORD', 'blck1201@');

// Charset da conexão ao DB
define('DB_CHARSET', 'utf8');

// Se você estiver desenvolvendo, modifique o valor para true
define('DEBUG', true);