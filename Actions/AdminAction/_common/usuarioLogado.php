<?php
// RESPONSE OBJECT: dados do usuário logado
$usuarioLogado = $UsuariosModule->read("WHERE id = ".$LoginModule->userId())[0];