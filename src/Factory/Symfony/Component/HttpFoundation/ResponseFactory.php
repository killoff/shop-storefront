<?php

namespace Drinks\Storefront\Factory\Symfony\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    public function create(): Response
    {
        return new Response('', Response::HTTP_OK, ['content-type' => 'text/html']);
    }
}
