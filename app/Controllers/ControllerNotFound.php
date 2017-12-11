<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Контрол страницы 404
 *
 * @package App\Controllers
 */
class ControllerNotFound extends Controller
{
    /**
     * 404
     *
     * @throws \App\Exception\LayoutNotFoundException
     * @throws \App\Exception\TemplateNotFoundException
     */
    function index()
    {
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        $this->view->generate('404');
    }

}
