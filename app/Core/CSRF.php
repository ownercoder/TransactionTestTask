<?php
namespace App\Core;

use App\Core\Session;

/**
 * Class CSRF
 *
 * @package App\Core
 */
class CSRF {
    /**
     * Возвращает csrf токен
     *
     * @return string
     */
    public static function get_csrf()
    {
        $code = md5(microtime());
        Session::write('csrf_token', $code);
        Session::commit();
        return $code;
    }

    /**
     * Проверяет csrf токен
     *
     * @param string $csrf
     *
     * @return bool
     */
    public static function validate($csrf)
    {
        $validCsrf = Session::read('csrf_token');
        Session::close();
        return (!empty($csrf) && $csrf == $validCsrf);
    }
}