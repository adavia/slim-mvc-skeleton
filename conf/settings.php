<?php
/**
 * Settings. Configuration definitions
 * 
 * PHP 7.3
 */
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function(ContainerBuilder $containerBuilder) 
{
	$rootPath = realpath(__DIR__ . '/..');

	// Global Settings Object
	$containerBuilder->addDefinitions([
		'settings' => [
			// Base path
			'base_path' => '',
		
			// Is debug mode
			'debug' => $_ENV['APPLICATION_ENV'] != 'production',

			// Temprorary directory
			'temporary_path' => $rootPath . '/var/tmp',

			// Route cache
			'route_cache' => $rootPath . '/var/routes.cache',

			// View settings
			'view' => [
				'template_path' => $rootPath . '/tmpl',
				'twig' => [
					'cache'       => $_ENV['APPLICATION_ENV'] != 'production' ? false : $rootPath . '/var/cache/twig', 
					'debug'       => $_ENV['APPLICATION_ENV'] != 'production',
					'auto_reload' => true,
				],
			],

			// DB Settings
			'database' => [
				'driver'    => $_ENV['DB_DRIVER'],
				'host'      => $_ENV['DB_HOST'],
				'database'  => $_ENV['DB_DATABASE'],
				'username'  => $_ENV['DB_USERNAME'],
				'password'  => $_ENV['DB_PASSWORD'],
				'charset'   => $_ENV['DB_CHARSET'],
				'collation' => 'utf8mb4_unicode_ci',
				'prefix'    => '',
				'options'   => [
					// Turn off persistent connections
					PDO::ATTR_PERSISTENT => false,
					// Enable exceptions
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					// Emulate prepared statements
					PDO::ATTR_EMULATE_PREPARES => true,
					// Set default fetch mode to array
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					// Set character set
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
				],
			],

			// Monolog settings
			'logger' => [
				'name'  => 'app',
				'path'  => $rootPath . '/var/log/app.log',
                'level' => $_ENV['APPLICATION_ENV'] != 'production' ? Logger::DEBUG : Logger::INFO,
			]
		],
	]);

	if ($_ENV['APPLICATION_ENV'] == 'production') { 
        // Should be set to true in production
		$containerBuilder->enableCompilation($rootPath . '/var/cache');
	}
};