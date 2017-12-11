<?php

namespace App\Core;

/**
 * Class Auth
 *
 * @package App\Core
 */
class Auth
{
    /**
     * @var Auth
     */
    private static $_instance = null;
    /**
     * @var \stdClass|null
     */
    private $user = null;

    /**
     * Auth constructor.
     */
    private function __construct()
    {
        $userSerialized = Session::read('user');
        Session::close();
        if (!empty($userSerialized)) {
            $this->user = unserialize($userSerialized);
        }
    }

    /**
     * Возвращает объект авторизации
     *
     * @return Auth
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Выполняет проверку корректности пароля
     *
     * @param \stdClass $user
     * @param string    $password
     *
     * @return bool
     */
    public function verifyPassword($user, $password)
    {
        $passwordHashed = $user->password;
        if (password_verify($password, $passwordHashed)) {
            return true;
        }

        return false;
    }

    /**
     * Выполняет авторизацию пользователя
     *
     * @param \stdClass $user
     */
    public function authenticate($user)
    {
        $this->user = $user;
        Session::write('user', serialize($user));
        Session::commit();
    }

    /**
     * Возвращает авторизованного пользователя
     *
     * @return \stdClass|null
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * Выполняет обновление авторизованного пользователя
     *
     * @param \stdClass $user
     */
    public function updateAuthUser($user)
    {
        $this->authenticate($user);
    }
}