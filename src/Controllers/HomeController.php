<?php
namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController
{
    public function index(Request $request, Response $response): Response
    {

        $payload = json_encode([
            'AppName' => 'Slim App'
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);


    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
    }


}
