<?php

namespace Drinks\Storefront;

class AppDir
{
    public static function init()
    {
        if (!defined('STOREFRONT_DIR')) {
            define('STOREFRONT_DIR', dirname(__DIR__));
        }
    }
}

