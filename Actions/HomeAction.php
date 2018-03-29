<?php
/*
 * by theWhK - 2018
 */

namespace Combine\Action;

// Proíbe o acesso externo
if (!defined('PATH_ABS')) {
    exit;
}

class HomeAction
{
    public function __construct($core) {
        require_once PATH_ABS . '/Response/home/main.php';
    }
}