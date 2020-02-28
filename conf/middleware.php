<?php 
/**
 * Global Middleware
 * 
 * PHP 7.3
 */
declare(strict_types=1);

use Slim\App;
use Slim\Views\TwigMiddleware;

return function(App $app) 
{
	$container = $app->getContainer();

	// Initialize session middleware
	$app->add($container->get('session'));

	// Initialize Twig middleware
	$app->add(TwigMiddleware::createFromContainer($app));
};