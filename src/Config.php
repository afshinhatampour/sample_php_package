<?php


namespace Afs\Src;

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
     * Config constructor.
     */
    public function __construct()
    {
        $this->getDataFromJsonConfigFile();
    }

    private function getDataFromJsonConfigFile()
    {
        $this->info = file_get_contents('config.json');
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