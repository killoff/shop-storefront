<?php

namespace Drinks\Storefront\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

class CmsRequestHandler implements RequestHandlerInterface
{
    public function canHandle(Request $request): bool
    {
        return $request->query->get('entity') === 'page';
    }

    public function handle(Request $request): void
    {
    }
}