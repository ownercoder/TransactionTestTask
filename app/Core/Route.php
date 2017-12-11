<?php

namespace App\Core;

use App\Exception\MiddlewareNotFoundException;

/**
 * Class Route
 *
 * @package App\Core
 */
class Route
{
    /**
     * @var string Контроллер по умолчанию
     */
    protected $defaultController = 'Main';
    /**
     * @var string Метод контроллера по умолчанию
     */
    protected $defaultAction = 'index';

    /**
     * @var Controller Объект контроллера
     */
    protected $controller;
    /**
     * @var null|string Метод контроллера
     */
    protected $action;

    /**
     * Route constructor.
     */
    public function __construct()
    {
        $controllerName = null;
        $actionName     = null;

        $routeList = explode('/', $_SERVER['REQUEST_URI']);

        if (!empty($routeList[1])) {
            $controllerName = ucfirst($routeList[1]);
        }

        if (!empty($routeList[2])) {
            $actionName = strtolower($routeList[2]);
        }

        $namespacedName = 'App\\Controllers\\Controller' . ($controllerName ?? $this->defaultController);

        if (!class_exists($namespacedName)) {
            Route::ErrorPage404();
            exit();
        }

        $this->controller = new $namespacedName($this);
        $this->action     = $actionName ?? $this->defaultAction;
    }

    /**
     * Выполняет перенаправление на страницу 404
     */
    public static function ErrorPage404()
    {
        $host = 'https://' . $_SERVER['HTTP_HOST'] . '/';
        header('Location: ' . $host . 'NotFound');
    }

    /**
     * Запускает маршрутизацию
     *
     * @throws MiddlewareNotFoundException
     */
    public function start()
    {
        if (!$this->controllerMiddleware()) {
            return;
        }

        if (method_exists($this->controller, $this->action)) {
            call_user_func([$this->controller, $this->action]);
        } else {
            Route::ErrorPage404();
        }

    }

    /**
     * Выполняет список middleware
     *
     * @throws MiddlewareNotFoundException
     */
    protected function controllerMiddleware()
    {
        $middlewareFail = false;

        $middlewareList = $this->controller->getMiddleware();
        foreach ($middlewareList as $item) {
            list($name, $useActionList) = $item;
            $className       = ucfirst($name);
            $namespacedClass = 'App\\Middleware\\' . $className;

            if (!in_array($this->action, $useActionList)) {
                continue;
            }

            if (!class_exists($namespacedClass)) {
                throw new MiddlewareNotFoundException('Middleware ' . $className . ' not found');
            }

            /**
             * @var $middleware Middleware
             */
            $middleware = new $namespacedClass();
            if (!$middleware->handler($this)) {
                $middlewareFail = true;
            }
        }

        return !$middlewareFail;
    }

    /**
     * Выполняет переадресацию
     *
     * @param string $url            Адрес
     * @param string $message        Сообщение с ошибкой
     * @param string $successMessage Сообщение с успехом
     */
    public function redirect($url, $message = '', $successMessage = '')
    {
        if (!empty($message) || !empty($successMessage)) {
            if (!empty($message)) {
                Session::write('message', $message);
            }
            if (!empty($successMessage)) {
                Session::write('successMessage', $successMessage);
            }
            Session::commit();
        }

        $host = 'https://' . $_SERVER['HTTP_HOST'] . '/';
        $url  = ltrim($url, '/');
        header('Location: ' . $host . $url);
    }

}
