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
     * @param string $layout
     * @param string $template
     * @param null $data
     *
     * @throws TemplateNotFoundException
     * @throws LayoutNotFoundException
     */
    function generate($template, $layout = 'layout', $data = null)
    {
        $templateFile = TEMPLATE_PATH . '/' . $template . TEMPLATE_EXT;
        $layoutFile   = TEMPLATE_PATH . '/' . $layout . TEMPLATE_EXT;

        if (!file_exists($templateFile)) {
            throw new TemplateNotFoundException();
        }
        if (!file_exists($layoutFile)) {
            throw new LayoutNotFoundException();
        }

        $messageList = get_message(MESSAGE_ERROR | MESSAGE_SUCCESS);

        include $layoutFile;
    }
}
