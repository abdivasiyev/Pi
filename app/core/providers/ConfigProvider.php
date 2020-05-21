<?php


namespace app\core\providers;


use app\core\base\AbstractProvider;
use app\core\config\MainConfig;
use app\core\exceptions\FileSystemException;
use Pi;

class ConfigProvider extends AbstractProvider
{

    /**
     * @var string
     */
    public $name = 'config';

    /**
     * @inheritDoc
     * @throws FileSystemException
     */
    function init()
    {
        return (new MainConfig(Pi::$app->configPath))->config;
    }
}