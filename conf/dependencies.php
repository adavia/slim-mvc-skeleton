<?php
/**
 * Container definition types for Dependency Injection
 * 
 * PHP 7.3
 */
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager as Capsule;

return function(ContainerBuilder $containerBuilder) 
{
	$containerBuilder->addDefinitions([
		'logger' => function (ContainerInterface $container) {
			$settings = $container->get('settings');

			$loggerSettings = $settings['logger'];
			$logger = new Logger($loggerSettings['name']);

			$processor = new UidProcessor();
			$logger->pushProcessor($processor);

			$handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
			$logger->pushHandler($handler);

			return $logger;
		},
		'session' => function (ContainerInterface $container) {
			return new \App\Middleware\SessionMiddleware;
		},
		'flash' => function (ContainerInterface $container) {
			$session = $container->get('session');
			return new \Slim\Flash\Messages($session);
		},
		'view' => function (ContainerInterface $container) {
			$settings = $container->get('settings');
			return new Twig($settings['view']['template_path'], $settings['view']['twig']);
		},
		'db' => function (ContainerInterface $container) {
			$settings = $container->get('settings');
			$capsule = new Capsule;
			$capsule->addConnection($settings['database']);

			$capsule->bootEloquent();
			$capsule->setAsGlobal();

			return $capsule;
		}
	]);
};
