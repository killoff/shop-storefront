<?php

namespace Drinks\Storefront\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

class CategoryRequestHandler implements RequestHandlerInterface
{
    public function canHandle(Request $request)
    {
        return $request->query->get('entity') === 'category';
    }

    public function handle(Request $request)
    {
    }
}