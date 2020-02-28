<?php 
/**
 * Home Controller
 * 
 * PHP 7.3
 */
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Post;

final class HomeController extends Controller
{
	/**
	 * Index action. Show home index page
	 * @param object $request. Instance of Request object
	 * @param object $response. Instance of Response object
	 * @param array $args. Array of params to inject in view
	 * 
	 * @return void
	 */
	public function index(Request $request, Response $response, array $args = []): Response
	{
		$post = new Post();
		$posts = $post->findPosts();
	
		//$this->flash->addMessage('info', 'Hello there');
		return $this->render($request, $response, 'home/index.html', ['posts' => $posts]);
	}
}