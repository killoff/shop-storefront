<?php

namespace Drinks\Storefront\RequestHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequestHandlerInterface
{
    public function canHandle(Request $request): bool;
    public function handle(Request $request, Response $response): void;
}