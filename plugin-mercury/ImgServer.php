<?php namespace Atomino\Mercury\Plugins\Attachment;

use Atomino\Bundle\Attachment\Config;
use Atomino\Bundle\Attachment\Img\ImgResolver;
use Atomino\Mercury\FileServer\FileLocator;
use Atomino\Mercury\FileServer\FileServer;
use Atomino\Mercury\Pipeline\Handler;
use Atomino\Mercury\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Atomino\dic;

class ImgServer extends Handler {
	public static function route(Router $router) {
		$attachmentConfig = dic()->get(Config::class);

		$router(method: 'GET', path: $attachmentConfig->imgUrl . '/**')
			?->pipe(ImgServer::class)
		     ->pipe(...FileLocator::setup($attachmentConfig->imgPath))
		     ->pipe(FileServer::class)
		;
	}

	public function handle(Request $request): Response|null {
		$imgResolver = dic()->get(ImgResolver::class);
		$result = $imgResolver->resolve($request->getPathInfo());
		return $result ? $this->next($request) : null;
	}
}