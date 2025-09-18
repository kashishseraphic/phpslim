<?php

use DI\ContainerBuilder;
use DI\Bridge\Slim\Bridge as SlimBridge;
use App\Middleware\RequestLoggingMiddleware;
// use App\Middleware\OAuth2Middleware;
// use App\Controllers\AuthController;
// use App\Controllers\BookController;
// use App\Controllers\AnalyticsController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';
// Initialize database
$capsule = require __DIR__ . '/../config/database.php';


// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

// Build PHP-DI Container
$containerBuilder = new ContainerBuilder();
$dependencies = require __DIR__ . '/../php-di-config.php';
$dependencies($containerBuilder);
$container = $containerBuilder->build();

$app = SlimBridge::create($container);

// Add Middleware (order matters - last added runs first)
$app->addErrorMiddleware(true, true, true);
$app->add(RequestLoggingMiddleware::class);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// After (pass container too)
(require __DIR__ . '/../src/Route/routes.php')($app, $container);

$app->run();
