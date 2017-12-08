<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Core\Route;
use App\Exception\ConfigNotFoundException;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Yaml\Yaml;

define('TEMPLATE_PATH', __DIR__ . '/Views');
define('TEMPLATE_EXT', '.php');
define('CONFIG_PATH', dirname(__DIR__) . '/config');
define('CONFIG_FILE', 'config.yaml');

const MESSAGE_ERROR   = 0x01;
const MESSAGE_SUCCESS = 0x02;

if (!function_exists('config')) {
    /**
     * Возвращает объект конфигурации
     *
     * @return stdClass
     * @throws ConfigNotFoundException
     */
    function config()
    {
        static $config = null;

        if (is_null($config)) {
            $configFile = CONFIG_PATH . '/' . CONFIG_FILE;
            if (!file_exists($configFile)) {
                throw new ConfigNotFoundException('Config file not found: ' . $configFile);
            }

            $config = Yaml::parseFile($configFile, Yaml::PARSE_OBJECT_FOR_MAP);
        }

        return $config;
    }
}

if (!function_exists('csrf_token'))
{
    /**
     * Возвращает сгенерированный csrf токен
     * @see \App\Core\CSRF::get_csrf()
     * @return string
     */
    function csrf_token()
    {
        return \App\Core\CSRF::get_csrf();
    }
}

if (!function_exists('get_message'))
{
    /**
     * Возвращает список сообщение в виде многомерного массива,
     * где первый уровень это тип сообщения а второй список сообщений
     *
     * @param int $type Тип сообщений MESSAGE_*
     *
     * @return array
     */
    function get_message($type = MESSAGE_ERROR)
    {
        $messageList = [];

        if ($type & MESSAGE_ERROR) {
            if ($error = \App\Core\Session::read('message')) {
                $messageList['error'][] = $error;
            }
            \App\Core\Session::delete('message');
        }
        if ($type & MESSAGE_SUCCESS) {
            if ($success = \App\Core\Session::read('successMessage')) {
                $messageList['success'][] = $success;
            }
            \App\Core\Session::delete('successMessage');
        }

        \App\Core\Session::commit();

        return $messageList;
    }
}

if (!function_exists('get_user'))
{
    /**
     * Возвращает объект авторизованного пользователя
     *
     * @return mixed|null
     */
    function get_user()
    {
        $userSerialized = \App\Core\Session::read('user');
        \App\Core\Session::close();
        if (!empty($userSerialized)) {
            return unserialize($userSerialized);
        }

        return null;
    }
}

$log = new Logger('name');
$errorLog = dirname(__DIR__) . '/logs/log-' . date('Y-m-d') . '.log';
$log->pushHandler(new StreamHandler($errorLog, Logger::INFO));

try {
    $route = new Route();
    $route->start();
} catch (Exception $e) {
    $log->error('Exception: ' . $e->getMessage());
    echo $e->getMessage();
}