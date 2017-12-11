<?php

namespace App\Core;

/**
 * Class Messages
 *
 * @package App\Core
 */
class Messages
{
    /**
     * Сообщения об ошибке
     */
    const MESSAGE_ERROR = 0x01;
    /**
     * Информационные сообщения
     */
    const MESSAGE_SUCCESS = 0x02;

    /**
     * Возвращает список сообщение в виде многомерного массива,
     * где первый уровень это тип сообщения а второй список сообщений
     *
     * @param int $type Тип сообщений MESSAGE_*
     *
     * @return array
     */
    function getMessageList($type = Messages::MESSAGE_ERROR)
    {
        $messageList = [];

        if ($type & Messages::MESSAGE_ERROR) {
            if ($error = \App\Core\Session::read('message')) {
                $messageList['error'][] = $error;
            }
            \App\Core\Session::delete('message');
        }
        if ($type & Messages::MESSAGE_SUCCESS) {
            if ($success = \App\Core\Session::read('successMessage')) {
                $messageList['success'][] = $success;
            }
            \App\Core\Session::delete('successMessage');
        }

        \App\Core\Session::commit();

        return $messageList;
    }
}