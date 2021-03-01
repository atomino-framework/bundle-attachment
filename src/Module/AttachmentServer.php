<?php namespace Atomino\Molecules\Module\Attachment;

use Atomino\Core\Application;
use Atomino\Molecules\Responder\FileServer\FileLocatorMiddleware;
use Atomino\Molecules\Responder\FileServer\FileServer;
use Atomino\Routing\Router;

class AttachmentServer {

	public static function route(Router $router) {
		$attachmentConfig = Application::DIC()->get(Config::class);
		$router(method: 'GET', path: $attachmentConfig->url . '/**')
			?->pipe(FileLocatorMiddleware::setup($attachmentConfig->path))
		     ->exec(FileServer::class)
		;
	}

}