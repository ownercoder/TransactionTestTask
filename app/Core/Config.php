<?php

namespace App\Core;

use App\Exception\ConfigNotFoundException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 *
 * @package App\Core
 */
class Config
{
    /**
     * @var Config|null
     */
    private static $_instance = null;
    /**
     * @var \stdClass|null
     */
    private $config = null;

    /**
     * Config constructor.
     *
     * @throws ConfigNotFoundException
     */
    private function __construct()
    {
        $configFile = CONFIG_PATH . '/' . CONFIG_FILE;
        if (!file_exists($configFile)) {
            throw new ConfigNotFoundException('Config file not found: ' . $configFile);
        }

        $this->config = Yaml::parseFile($configFile, Yaml::PARSE_OBJECT_FOR_MAP);
    }

    /**
     * Возвращает объект конфигурации
     *
     * @return Config
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Возвращает объкт настроек БД
     *
     * @return \stdClass|null
     */
    public function database()
    {
        return property_exists($this->config, 'database') ? $this->config->database : null;
    }

    private function __clone()
    {

    }
}