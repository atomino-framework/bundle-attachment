<?php namespace Atomino\Molecules\Module\Attachment\Img;

use Atomino\Core\Application;
use Atomino\Molecules\Module\Attachment\Config;
use Atomino\Molecules\Responder\FileServer\FileLocatorMiddleware;
use Atomino\Molecules\Responder\FileServer\FileServer;
use Atomino\Routing\Router;

class ImageServer {
	public static function route(Router $router){
		$attachmentConfig = Application::DIC()->get(Config::class);
		$router(method: 'GET', path: $attachmentConfig->imgUrl . '/**')
			?->pipe(ImgCreatorMiddleware::class)
		     ->pipe(FileLocatorMiddleware::setup($attachmentConfig->imgPath))
		     ->exec(FileServer::class)
		;
	}
}