<?php namespace Atomino\Molecules\Module\Attachment\Img;

use Atomino\Core\Application;
use Atomino\Molecules\Module\Attachment\Config;
use Atomino\Responder\Middleware;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\Response;

class ImgCreatorMiddleware extends Middleware{

	public function __construct(private ImgCreatorInterface $creator){ }

	protected function respond(Response $response): Response{

		$config = Application::DIC()->get(Config::class);
		if (!is_dir($config->imgPath)) mkdir($config->imgPath, 0777, true);

		$uri = explode('/', $this->getRequest()->getRequestUri());
		$uri = urldecode(array_pop($uri));
		$target = $config->imgPath . '/' . $uri;
		if (file_exists($target)) return $this->next($response);

		#region parse uri
		$parts = explode('.', $uri);
		$ext = array_pop($parts);
		$hash = array_pop($parts);
		$path = $pathId = array_pop($parts);
		$jpegQuality = ( $ext === 'jpg' || $ext === 'webp' ) ? array_pop($parts) : null;
		$opCode = array_pop($parts);
		$op = $this->parseOp($opCode);
		#endregion

		#region source file path
		$file = join('.', $parts);
		$path = substr_replace($path, '/', -6, 0);
		$path = substr_replace($path, '/', -4, 0);
		$path = substr_replace($path, '/', -2, 0);
		$source = realpath($config->path . '/' . $path . '/' . $file);
		if (!file_exists($source)) return $this->notfound($response);
		#endregion

		#region check hash
		$url = $file . '.' . $opCode . ( ( $jpegQuality ) ? ( '.' . $jpegQuality ) : ( '' ) ) . '.' . $pathId . '.' . $ext;
		$newHash = base_convert(crc32($url . $config->imgSecret), 10, 32);
		if ($newHash != $hash) return $this->notfound($response);
		#endregion

		if ( !match ( $op['op'] ) {
			'c' => $this->creator->crop($op['width'], $op['height'], $source, $target, $jpegQuality, $op['safezone'], $op['focus']),
			'h' => $this->creator->height($op['width'], $op['height'], $source, $target, $jpegQuality, $op['safezone'], $op['focus']),
			'w' => $this->creator->width($op['width'], $op['height'], $source, $target, $jpegQuality, $op['safezone'], $op['focus']),
			's' => $this->creator->scale($op['width'], $op['height'], $source, $target, $jpegQuality),
			'b' => $this->creator->box($op['width'], $op['height'], $source, $target, $jpegQuality),
			default => null,
		}) return $this->notfound($response);

		return $this->next($response);
	}

	#[ArrayShape( ['op' => "string", 'width' => "int", 'height' => "int"] )]
	private function parseOp(string $op): array{
		preg_match('/(?<op>[a-z])(?<arg>[a-z0-9]*)(~(?<safezone>[a-z0-9]*))?(-(?<focus>[a-z0-9]*))?/', $op, $match);
		$argLength = strlen($match['arg']) / 2;
		return [
			'op'     => $match['op'],
			'width'  => $this->bc($match['arg'],2)[0],
			'height' => $this->bc($match['arg'],2)[1],
			'safezone' => array_key_exists('safezone', $match) ? $this->bc($match['safezone'], 4) : null,
			'focus' => array_key_exists('focus', $match) ? $this->bc($match['focus'], 2) : null
		];
	}

	private function bc($num, $segments = 1){
		if($segments === 1) return (int)base_convert($num, 36, 10);
		$len = strlen($num) / $segments;
		$ret = [];
		for($i = 0; $i<$segments; $i++){
			$ret[] = (int)base_convert(substr($num, $i*$len, $len), 36, 10);
		}
		return $ret;
	}

	private function notfound(Response $response){
		$response->setStatusCode(404);
		return $response;
	}

}