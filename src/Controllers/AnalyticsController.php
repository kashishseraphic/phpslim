<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\BorrowLog;
use App\Models\Books;
use Illuminate\Database\Capsule\Manager as DB;

class AnalyticsController
{
    public function latestBorrowPerBook(Request $request, Response $response): Response
    {
        // Extract userId from OAuth token attributes (for security validation)
        $userId = $request->getAttribute('oauth_user_id');
        $responseMessage = [
            'status' => false,
            'message' => 'Unknown Error.',
            'data' => $userId
        ];    

        if (!$userId) {
            $responseMessage['message'] = 'Authentication required';
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }


        $data = DB::table(DB::raw('(
                SELECT 
                    bl.bookId,
                    bl.userId,
                    bl.borrowLogDateTime,
                    ROW_NUMBER() OVER (
                        PARTITION BY bl.bookId 
                        ORDER BY bl.borrowLogDateTime DESC
                    ) as rn
                FROM borrowlog bl
            ) as ranked'))
            ->where('rn', 1)
            ->select('bookId', 'userId', 'borrowLogDateTime')
            ->get();

            $responseMessage['status'] = true;
            $responseMessage['message'] = 'Data found';
            $responseMessage['data'] = $data;

        $response->getBody()->write(json_encode($responseMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function borrowRankPerUser(Request $request, Response $response): Response
    {
        // Extract userId from OAuth token attributes (for security validation)
        $userId = $request->getAttribute('oauth_user_id');
        $responseMessage = [
            'status' => false,
            'message' => 'Unknown Error.',
            'data' => $userId
        ];    

        if (!$userId) {
            $responseMessage['message'] = 'Authentication required';
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $data =  DB::table(DB::raw('( 
                SELECT 
                    borrowLogId,
                    userId,
                    bookId,
                    borrowLogDateTime,
                    ROW_NUMBER() OVER (
                        PARTITION BY userId, bookId 
                        ORDER BY borrowLogDateTime ASC
                    ) AS borrowRank
                FROM borrowlog
            ) as ranked'))
            ->orderBy('userId')
            ->orderBy('bookId')
            ->orderBy('borrowLogDateTime')
            ->get(['borrowLogId', 'userId', 'bookId', 'borrowLogDateTime', 'borrowRank']);

            $responseMessage['status'] = true;
            $responseMessage['message'] = 'Data found';
            $responseMessage['data'] = $data;

        $response->getBody()->write(json_encode($responseMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function bookSummary(Request $request, Response $response): Response
    {
        // Extract userId from OAuth token attributes (for security validation)
        $userId = $request->getAttribute('oauth_user_id');
        $responseMessage = [
            'status' => false,
            'message' => 'Unknown Error.',
            'data' => $userId
        ];    

        if (!$userId) {
            $responseMessage['message'] = 'Authentication required';
            $response->getBody()->write(json_encode($responseMessage));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        $queryParams = $request->getQueryParams();
        $query = $queryParams['query'] ?? null;

        // Subquery: get the last borrow per book using ROW_NUMBER()
        $lastBorrowSubquery = BorrowLog::select(
                'borrowlog.bookId',
                'borrowlog.userId',
                'borrowlog.borrowLogDateTime',
                'users.username',
                DB::raw('ROW_NUMBER() OVER (PARTITION BY borrowlog.bookId ORDER BY borrowlog.borrowLogDateTime DESC) as rn')
            )
            ->join('users', 'borrowlog.userId', '=', 'users.userId');

        // Filter only the last borrow
        $lastBorrow = DB::table(DB::raw("({$lastBorrowSubquery->toSql()}) as lb"))
            ->mergeBindings($lastBorrowSubquery->getQuery())
            ->where('rn', 1);

        // Main query: books + borrow count + last borrower
        $queryBuilder = Books::select(
                'books.bookId',
                'books.bookTitle',
                'books.bookAuthor',
                'books.bookPublishYear',
                DB::raw('COUNT(borrowlog.borrowLogId) as borrowCount'),
                'lb.username as lastBorrowedBy'
            )
            ->leftJoin('borrowlog', 'books.bookId', '=', 'borrowlog.bookId')
            ->leftJoinSub($lastBorrow, 'lb', function ($join) {
                $join->on('books.bookId', '=', 'lb.bookId');
            })
            ->groupBy('books.bookId', 'books.bookTitle', 'books.bookAuthor', 'books.bookPublishYear', 'lb.username');

        // Optional full-text search
        if ($query) {
            $queryBuilder->whereRaw(
                'MATCH(books.bookTitle, books.bookAuthor) AGAINST(? IN BOOLEAN MODE)', 
                [$query]
            );
        }

        $data = $queryBuilder->get();

            $responseMessage['status'] = true;
            $responseMessage['message'] = 'Data found';
            $responseMessage['data'] = $data;

        $response->getBody()->write(json_encode($responseMessage));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
