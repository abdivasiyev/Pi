<?php

namespace app\core\base;

use app\core\exceptions\FileSystemException;

class AbstractView extends AbstractComponent
{

    public $content;

    public $params = [];
    
    /**
     * @param $view
     * @param array $vars
     * @throws \Exception
     */
    public function render($view, $vars = [])
    {
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

        $content = ob_get_clean();

        return $content;
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