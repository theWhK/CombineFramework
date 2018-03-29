<?php
// Checagem de login
$LoginModule->requireLogin(
    URL_BASE.'/'.$this->core->action_urlName.'/login');