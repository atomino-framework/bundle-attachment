<?php namespace Atomino\Molecules\Module\Attachment\Server;

use Atomino\Molecules\Module\Attachment\Config;
use Atomino\Molecules\Module\Attachment\Img\ImgResolver;
use Atomino\RequestPipeline\FileServer\FileLocator;
use Atomino\RequestPipeline\FileServer\FileServer;
use Atomino\RequestPipeline\Pipeline\Handler;
use Atomino\RequestPipeline\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function Atomino\dic;

class ImgServer extends Handler{
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