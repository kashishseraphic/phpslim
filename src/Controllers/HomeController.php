<?php
namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Users;

class HomeController
{
    public function index(Request $request, Response $response): Response
    {
        $users = Users::all();

        $payload = json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $response->getBody()->write($payload);


    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
    }


}
