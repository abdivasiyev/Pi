<?php


namespace app\core\base;


use app\core\exceptions\FileSystemException;

/**
 * @property mixed config
 * @property string group
 * @property  path
 */
class AbstractConfig extends AbstractComponent
{
    /**
     * AbstractConfig constructor.
     * @param $path
     * @param string $group
     * @throws FileSystemException
     */
    public function __construct($path, $group = 'main')
    {
        $path = $path . $group . '.php';

        if (!file_exists($path)) {
            throw new FileSystemException(sprintf('Config file doesn\'t exists: %s', $path));
        }

        $this->config = (object) require_once $path;
    }
}