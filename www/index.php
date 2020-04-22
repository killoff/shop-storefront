<?php

require '../vendor/autoload.php';

use Drinks\Storefront\App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('STOREFRONT_DIR', dirname(__DIR__));
(new App())->run(Request::createFromGlobals(), new Response());

