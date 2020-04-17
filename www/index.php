<?php

require '../vendor/autoload.php';

use Drinks\Storefront\Router;
use Symfony\Component\HttpFoundation\Request;

(new Router())->dispatch(Request::createFromGlobals());

