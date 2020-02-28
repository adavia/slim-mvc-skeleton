<?php 
/**
 * HTML Error renderer
 * 
 * PHP 7.3
 */
declare(strict_types=1);

namespace App\Renderer;

use Psr\Container\ContainerInterface;
use Slim\Interfaces\ErrorRendererInterface;
use Slim\Views\Twig;

class HtmlErrorRenderer implements ErrorRendererInterface
{
    /**
	 * Twig view dependency
     * @var object
     */
	protected $view;

    /**
	 * Class constructor
	 * @param object. Instance of ContainerInterface
	 * 
	 * Inititalize new Twig instance based on settings definitions
	 */
	public function __construct(ContainerInterface $container)
	{
		$settings = $container->get('settings');
		$this->view = new Twig($settings['view']['template_path'], $settings['view']['twig']);
	}

    /**
	 * Invoked function
	 * @param exception $exception. Instance of Throwable exception
     * @param bool $displayErrorDetails. Show error details
	 * 
	 * @return void
	 */
	public function __invoke(\Throwable $exception, bool $displayErrorDetails): string
	{
		if ($exception->getCode() == 404) {
			return $this->view->fetch('error/404.html');
		}

		$title = '500 - ' . get_class($exception);
        
		return $this->view->fetch('error/default.html', [
			'title'   => $title,
			'debug'   => $displayErrorDetails,
			'type'    => get_class($exception),
			'code'    => $exception->getCode(),
			'message' => $exception->getMessage(),
			'file'    => $exception->getFile(),
			'line'    => $exception->getLine(),
			'trace'   => $exception->getTraceAsString()
		]);
	}
}