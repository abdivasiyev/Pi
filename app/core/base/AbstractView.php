<?php

namespace app\core\base;

use app\core\exceptions\FileSystemException;

class AbstractView extends AbstractComponent
{

    /**
     * @var
     */
    public $content;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @param $view
     * @param array $vars
     * @return false|string
     * @throws FileSystemException
     */
    public function render($view = '', $vars = [])
    {
        if ($view === '')
        {
            return '';
        }

        $viewPath = $this->getViewPath($view);

        if (!is_file($viewPath)) {
            throw new FileSystemException(
                sprintf('View "%s" not found in "%s"', $view, $viewPath)
            );
        }

        extract($vars);
        ob_start();
        ob_implicit_flush(0);

        try {
            require_once $viewPath;
        } catch (Exception $e) {
            ob_end_clean();
            throw new $e;
        }

        return ob_get_clean();
    }

    /**
     * @param $view
     * @return string
     */
    private function getViewPath($view)
    {
        return APP_DIR . 'app/views/' . $view . '.php';
    }
}