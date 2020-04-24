<?php

namespace Drinks\Storefront\RequestHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryRequestHandler implements RequestHandlerInterface
{
    public function canHandle(Request $request): bool
    {
        return $request->query->get('entity') === 'category';
    }

    public function handle(Request $request, Response $response): void
    {
    }
}