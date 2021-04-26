<?php namespace Atomino\Molecules\Module\Attachment\Server;

use Atomino\Molecules\Module\Attachment\Config;
use Atomino\RequestPipeline\FileServer\FileLocator;
use Atomino\RequestPipeline\FileServer\FileServer;
use Atomino\RequestPipeline\FileServer\StaticServer;
use Atomino\RequestPipeline\Router\Router;
use function Atomino\dic;

class AttachmentServer {

	public static function route(Router $router) {
		$attachmentConfig = dic()->get(Config::class);
		StaticServer::route($router, $attachmentConfig->url . '/**', $attachmentConfig->path);
	}

}