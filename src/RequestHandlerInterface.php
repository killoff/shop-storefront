<?php

namespace Drinks\Storefront;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerInterface
{
    public function canHandle(Request $request): bool;

    public function handle(Request $request): void;
}
