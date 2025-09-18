<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\UserService;


class UserController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function signUp(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        $responsemessage = [
            'status' => false,
            'message' => 'Unknown Error',
            'data' => $data
        ];
        if (empty($data['username'])) {
            $responsemessage['message'] = 'Username is required';
            $response->getBody()->write(json_encode($responsemessage));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        if (empty($data['password'])) {
            $responsemessage['message'] = 'Password is required';
            $response->getBody()->write(json_encode($responsemessage));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $user = $this->userService->createUser($data["username"], $data["password"]);
        $response->getBody()->write(json_encode($user));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

}
