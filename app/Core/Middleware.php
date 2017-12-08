<?php
namespace App\Core;

/**
 * Class Middleware
 *
 * @package App\Core
 */
class Middleware {
    /**
     * Основной обработчик middleware
     *
     * @param Route $router
     *
     * @return bool
     */
    public function handler($router)
    {
        return true;
    }
}