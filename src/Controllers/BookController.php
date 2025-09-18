<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


use App\Models\Books;
use App\Models\BorrowLog;
use App\Models\Users;
use Carbon\Carbon;


class BookController
{
    public function add(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();

        $responseMessage = [
            'status' => false,
            'message' => 'Unknown Error.',
            'data' => $data
        ];    
        if (empty($data['bookTitle'])) {
            $responseMessage['message'] = 'bookTitle is required';
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $book = Books::create([
            'bookTitle' => $data['bookTitle'],
            'bookAuthor' => $data['bookAuthor'] ?? null,
            'bookPublishYear' => $data['bookPublishYear'] ?? null,
        ]);

        $response->getBody()->write(json_encode([
            'status' => true,
            'message' => 'Success, Record Added.',
            'data' => $book
        ]));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function list(Request $request, Response $response): Response
    {
        $books = Books::all(); // get all books
        $responseMessage = [
            'status' => true,
            'message' => 'Success, Record Found.',
            'data' => $books
        ];    

        $response->getBody()->write(json_encode($responseMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function borrow(Request $request, Response $response, int $bookId): Response
    {
        $userId = $request->getAttribute('userId');
        $responseMessage = [
            'status' => false,
            'message' => 'Unknown Error.',
            'data' => $userId
        ];    

        if (!$userId) {
            $responseMessage['message'] = 'Unauthorized Access';
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        // Check if book exists
        $book = Books::find($bookId);
        if (!$book) {
            $responseMessage['message'] = 'Book not found';
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        try {
            $borrowLog = BorrowLog::create([
                'bookId' => $bookId,
                'userId' => $userId,
                'borrowLogDateTime' => Carbon::now(),
            ]);

            $responseMessage['status'] = true;
            $responseMessage['message'] = 'Book found';
            $responseMessage['data'] = $borrowLog;
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }

    public function listBorrows(Request $request, Response $response, int $bookId): Response
    {
        $responseMessage = [
            'status' => false,
            'message' => 'Unknown Error.',
            'data' => $bookId
        ];    

        $borrows = Books::where('bookId', $bookId)->with('borrowLogs')->first();

        // Check if book exists
        if (!$borrows) {
            $responseMessage['message'] = 'Book Not Found.';
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

            $responseMessage['status'] = true;
            $responseMessage['message'] = 'Book found';
            $responseMessage['data'] = $borrows;

        $response->getBody()->write(json_encode($responseMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
