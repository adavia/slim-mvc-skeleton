<?php 
/**
 * Base Controller
 * 
 * PHP 7.3
 */
declare(strict_types=1);

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

abstract class Controller
{
	/**
	 * Twig view dependency
     * @var object
     */
	protected $view;

	/**
	 * Monolog dependency
     * @var object
     */
	protected $logger;

	/**
	 * Slim flash dependency
     * @var object
     */
	protected $flash;

	/**
	 * Class constructor
	 * @param object. Instance of ContainerInterface
	 * 
	 * Initialize properties from the ContainerInterface
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->view = $container->get('view');
		$this->logger = $container->get('logger');
		$this->flash = $container->get('flash');
	}

	/**
	 * Inject flash and session dependencies to all views
	 * @param object $request. Instance of Request object
	 * @param object $response. Instance of Response object
	 * @param string $template. Instance of Response object
	 * @param array $args. Array of params to inject in view
	 * 
	 * @return void
	 */
	protected function render(Request $request, Response $response, string $template, array $params = []): Response
	{
		$params['flash'] = $this->flash->getMessages();
		$params['uinfo'] = $request->getAttribute('uinfo');
		
		return $this->view->render($response, $template, $params);
	}
}