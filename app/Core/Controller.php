<?php

namespace App\Core;

/**
 * Class Controller
 *
 * @package App\Core
 */
class Controller
{

    /**
     * @var Route Объект роутера
     */
    protected $router;
    /**
     * @var View Объект представления
     */
    protected $view;

    /**
     * @var array Список middleware
     */
    protected $middleware = [];

    /**
     * Controller constructor.
     *
     * @param Route $router
     */
    public function __construct($router)
    {
        $this->view   = new View();
        $this->router = $router;
    }

    /**
     * Получает список middleware с настройками
     *
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * Объявить middleware
     *
     * @param string $middleware    Список middleware для выполнения
     * @param array  $useActionList Список страниц для выполнения
     */
    protected function middleware($middleware, $useActionList)
    {
        $this->middleware[] = [$middleware, $useActionList];
    }
}
