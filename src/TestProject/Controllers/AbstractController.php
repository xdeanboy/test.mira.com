<?php

namespace TestProject\Controllers;

use TestProject\Services\UserAuthServices;
use TestProject\View\View;

abstract class AbstractController
{
    protected $view;
    protected $user;

    public function __construct()
    {
        //include templates for view
        $this->view = new View(__DIR__ . '/../../../templates/');

        //user by cookie authorized token
        $this->user = UserAuthServices::getUserByToken();
        $this->view->setVars('user', $this->user);
    }
}