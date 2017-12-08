<?php
/**
 *
 */

namespace App\Core;

/**
 * Class Session
 *
 * @package App\Core
 */
class Session
{
    /**
     * @var int Время жизни сессии
     */
    protected static $SESSION_AGE = 1800;

    /**
     * Запускает сессию
     *
     * @return bool
     */
    public static function start()
    {
        return self::_init();
    }

    /**
     * Уничтожает сессию
     */
    public static function destroy()
    {
        if ('' !== session_id()) {
            $_SESSION  = array();
            $paramList = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $paramList["path"], $paramList["domain"],
                $paramList["secure"], $paramList["httponly"]
            );
            session_destroy();
        }
    }

    /**
     * Получает значение сессии
     *
     * @param string $key Ключ сохраненных данных
     *
     * @return bool|string
     */
    public static function read($key)
    {
        self::_init();

        if (isset($_SESSION[$key])) {
            self::_age();

            return $_SESSION[$key];
        }

        return false;
    }

    /**
     * Удаляет по ключу данные сессии
     *
     * @param string $key Ключ сохраненных данных
     */
    public static function delete($key)
    {
        self::_init();
        unset($_SESSION[$key]);
        self::_age();
    }

    /**
     * Записывает данные сессии
     *
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public static function write($key, $value)
    {
        self::_init();
        $_SESSION[$key] = $value;
        self::_age();
        return $value;
    }

    /**
     * Фиксация данных и закрытие сессии
     * @see close
     */
    public static function commit()
    {
        self::close();
    }

    /**
     * Актуализация времени жизни сессии
     */
    private static function _age()
    {
        $last = isset($_SESSION['LAST_ACTIVE']) ? $_SESSION['LAST_ACTIVE'] : false;

        if (false !== $last && (time() - $last > self::$SESSION_AGE)) {
            self::destroy();
        }
        $_SESSION['LAST_ACTIVE'] = time();
    }

    /**
     * Инициализация сессий
     *
     * @return bool
     */
    private static function _init()
    {
        if (session_status() == PHP_SESSION_NONE) {
            $secure   = true;
            $httponly = true;

            $params = session_get_cookie_params();
            session_set_cookie_params($params['lifetime'],
                $params['path'], $params['domain'],
                $secure, $httponly
            );
            return session_start();
        }

        return session_regenerate_id(true);
    }

    /**
     * Закрывает сессию с фиксацией изменений
     *
     * @return bool|void
     */
    public static function close()
    {
        if (session_id() !== '') {
            return session_write_close();
        }

        return true;
    }
}