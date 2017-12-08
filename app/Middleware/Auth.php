<?php
namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Session;

/**
 * Class Auth
 * Middleware для проверки авторизации
 *
 * @package App\Middleware
 */
class Auth extends Middleware {
    /**
     * @inheritdoc
     */
    public function handler($router)
    {
        $user = get_user();

        if ($user instanceof \stdClass == false) {
            $router->redirect('/', 'Session expired');
            return false;
        }

        return true;
    }
}