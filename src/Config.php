<?php


namespace Mci\Behsa;

/**
 * Class Config
 * @package Afs\Src
 */
class Config
{
    /**
     * @var
     */
    public $info;

    /**
     * config json file name
     */
    const CONFIG_JSON_FILE_NAME = 'mci-behsa-config.json';

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->getDataFromJsonConfigFile();
    }

    private function getDataFromJsonConfigFile()
    {
        if (file_exists(self::CONFIG_JSON_FILE_NAME)) {
            $this->info = file_get_contents(self::CONFIG_JSON_FILE_NAME);
        } elseif (file_exists('../' . self::CONFIG_JSON_FILE_NAME)) {
            $this->info = file_get_contents('../' . self::CONFIG_JSON_FILE_NAME);
        } else {
            die('cant find config.json file');
        }

    }

    /**
     * @param $index
     * @return mixed
     */
    public function get($index)
    {
        return json_decode($this->info)->$index;
    }
}