<?php namespace Atomino\Mercury\Plugins\Attachment;

use Atomino\Bundle\Attachment\Config;
use Atomino\Mercury\FileServer\StaticServer;
use Atomino\Mercury\Router\Router;
use function Atomino\dic;

class AttachmentServer {
	public static function route(Router $router) {
		$attachmentConfig = dic()->get(Config::class);
		StaticServer::route($router, $attachmentConfig->url . '/**', $attachmentConfig->path);
	}
}