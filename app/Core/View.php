<?php

namespace App\Core;

use App\Exception\LayoutNotFoundException;
use App\Exception\TemplateNotFoundException;

/**
 * Class View
 *
 * @package App\Core
 */
class View
{

    /**
     * Генерирует представление
     *
     * @param string $layout Основной шаблон
     * @param string $template Шаблон страницы
     * @param array  $data Список дополнительных параметров
     *
     * @throws TemplateNotFoundException
     * @throws LayoutNotFoundException
     */
    function generate($template, $layout = 'layout', $data = [])
    {
        $templateFile = TEMPLATE_PATH . '/' . $template . TEMPLATE_EXT;
        $layoutFile   = TEMPLATE_PATH . '/' . $layout . TEMPLATE_EXT;

        if (!file_exists($templateFile)) {
            throw new TemplateNotFoundException();
        }
        if (!file_exists($layoutFile)) {
            throw new LayoutNotFoundException();
        }
        $messages = new Messages();

        $messageList = $messages->getMessageList(Messages::MESSAGE_ERROR | Messages::MESSAGE_SUCCESS);

        include $layoutFile;
    }
}
