<?php
/**
 * by theWhK - 2018
 * --------
 * Combine Framework v1.0.1
 */

// Composer autoload
require 'vendor/autoload.php';

// Arquivos globais
require_once 'functions.php';

// Classes principais
require_once 'Core/core.php';
require_once 'Domain/domain.php';

// Inicia a sessão
session_name("COMBINESESS");
session_start();

// Dispara o Domain
$domain = new Domain();

// Dispara o Core
$core = new Core($domain);

// Dispara a Action
$action = new $core->action($core);