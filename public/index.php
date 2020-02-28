<?php 
/**
 * Bootstrap file
 * App configuration
 * 
 * PHP 7.3
 */
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

// Set the absolute path to the root directory.
$rootPath = realpath(__DIR__ . '/..');

// Include the composer autoloader.
include_once($rootPath . '/vendor/autoload.php');

// Load ENV variables
$dotenv = Dotenv\Dotenv::createImmutable($rootPath);
$dotenv->load();

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$settings = require $rootPath . '/conf/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require $rootPath . '/conf/dependencies.php';
$dependencies($containerBuilder);

// Build PHP-DI Container instance
$container = $containerBuilder->build();

$settings = $container->get('settings');

// Instantiate the app
$app = AppFactory::createFromContainer($container);
$app->setBasePath($settings['base_path']);

// Eloquent DB ORM
$container->get('db');

// Register middleware
$middleware = require $rootPath . '/conf/middleware.php';
$middleware($app);

// Register routes
$routes = require $rootPath . '/conf/routes.php';
$routes($app);

// Set the cache file for the routes. Note that you have to delete this file
// whenever you change the routes.
if (!$settings['debug']) {
	$app->getRouteCollector()->setCacheFile($settings['route_cache']);
}

// Add the routing middleware.
$app->addRoutingMiddleware();

// Add error handling middleware.
$errorMiddleware = $app->addErrorMiddleware($settings['debug'], !$settings['debug'], false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->registerErrorRenderer('text/html', App\Renderer\HtmlErrorRenderer::class);

// Run the app
$app->run();