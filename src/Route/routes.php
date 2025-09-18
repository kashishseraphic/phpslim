<?php
use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\UserController;

use App\Middleware\OAuth2Middleware;
use App\Controllers\BookController;
use App\Controllers\AnalyticsController;

/**
 * @param App $app
 * @param Psr\Container\ContainerInterface $container
 */
return function (App $app, $container) {

// // Add Middleware (order matters - last added runs first)
// $app->addErrorMiddleware(true, true, true);
// $app->add(RequestLoggingMiddleware::class);
// $app->addBodyParsingMiddleware();
// $app->addRoutingMiddleware();



$app->get('/', HomeController::class . ':index');

$app->post('/oauth/token', AuthController::class . ':token');
$app->post('/api/signup', UserController::class . ':signUp');

    // Protected routes group (requires OAuth2 authentication)
$app->group('/books', function ($app) {
    $app->post('', BookController::class . ':add');
    $app->get('', BookController::class . ':list');
    $app->post('/{bookId}/borrow', BookController::class . ':borrow');
    $app->get('/{bookId}/borrows', BookController::class . ':listBorrows');

})->add(OAuth2Middleware::class);

$app->group('/analytics', function ($app) {
    $app->get('/latest-borrow-per-book', AnalyticsController::class . ':latestBorrowPerBook');
    $app->get('/borrow-rank-per-user', AnalyticsController::class . ':borrowRankPerUser');
    $app->get('/book-summary', AnalyticsController::class . ':bookSummary');

})->add(OAuth2Middleware::class);

};


