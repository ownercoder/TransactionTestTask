<?php

namespace App\Middleware;

use App\Core\Middleware;

/**
 * Class Csrf
 * Middleware для проверки csrf токена
 *
 * @package App\Middleware
 */
class Csrf extends Middleware
{
    /**
     * @inheritdoc
     */
    public function handler($router)
    {
        $csrf = $_POST['csrf'];

        if (!\App\Core\CSRF::validate($csrf)) {
            $router->redirect('/', 'CSRF token validation failed');
            return false;
        }

        return true;
    }
}