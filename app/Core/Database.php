<?php

namespace App\Core;

/**
 * Class Database
 *
 * @package App\Core
 */
class Database
{
    /**
     * @var Database
     */
    private static $_instance;

    /**
     * @var null|\PDO
     */
    protected $pdo = null;

    /**
     * Database constructor.
     */
    protected function __construct()
    {
        $dsn        = sprintf('%s:host=%s;dbname=%s;charset=%s',
            Config::instance()->database()->driver,
            Config::instance()->database()->host,
            Config::instance()->database()->dbname,
            Config::instance()->database()->charset);
        $optionList = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new \PDO($dsn, Config::instance()->database()->login,
            Config::instance()->database()->password, $optionList);
    }

    /**
     * Получает instance объекта для работы с БД
     *
     * @return Database
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Возвращает объект PDO
     *
     * @return null|\PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    private function __clone()
    {
    }
}