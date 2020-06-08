<?php

namespace Drinks\Storefront\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerInterface
{
    public function canHandle(Request $request): bool;

    public function handle(Request $request): void;
}
