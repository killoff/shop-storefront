<?php

namespace Drinks\Storefront\App;


class Config
{
    private $data = [];

    public function __construct()
    {
        $files = glob(STOREFRONT_DIR . '/config/*.php');
        foreach ($files as $file) {
            $fileConfig = include $file;
            $this->data = array_merge($this->data, $fileConfig);
        }
    }

    public function get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        if (strpos($key, '/') !== false) {
            list($level1, $level2) = explode('/', $key);
            if (isset($this->data[$level1][$level2])) {
                return $this->data[$level1][$level2];
            }
        }
        throw new \InvalidArgumentException("Config not found for key '{$key}'");
    }

}