<?php namespace Atomino\Molecules\Module\Attachment\Server;

use Atomino\Molecules\Module\Attachment\Config;
use Atomino\RequestPipeline\FileServer\FileLocator;
use Atomino\RequestPipeline\FileServer\FileServer;
use Atomino\RequestPipeline\Router\Router;
use function Atomino\dic;

class AttachmentServer {

	public static function route(Router $router) {
		$attachmentConfig = dic()->get(Config::class);
		$router(method: 'GET', path: $attachmentConfig->url . '/**')
			?->pipe(FileLocator::setup($attachmentConfig->path))
		     ->pipe(FileServer::class)
		;
	}

}