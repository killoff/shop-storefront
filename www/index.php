<?php

require '../vendor/autoload.php';

use Drinks\Storefront\App;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

(new App())->run(Request::createFromGlobals(), new Response());

