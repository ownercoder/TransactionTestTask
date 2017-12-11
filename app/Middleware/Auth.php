<?php

namespace App\Middleware;

use App\Core\Middleware;

/**
 * Class Auth
 * Middleware для проверки авторизации
 *
 * @package App\Middleware
 */
class Auth extends Middleware
{
    /**
     * @inheritdoc
     */
    public function handler($router)
    {
        $user = \App\Core\Auth::instance()->user();

        if ($user instanceof \stdClass == false) {
            $router->redirect('/', 'Session expired');
            return false;
        }

        return true;
    }
}