<?php namespace Atomino\Molecules\Module\Attachment;

use Atomino\Core\Application;

class Config {

	public function __construct(
		public string $path,
		public string $url,
		public string $restrictedAccessPostfix,
		public string $imgUrl,
		public string $imgPath,
		public string $imgSecret,
		public int $imgJpegQuality,
	) {	}

//	public string $path;
//	public string $url;
//	public string $imgUrl;
//	public string $imgPath;
//	public string $imgSecret;
//	public int $imgJpegQuality;
//	public string $restrictedAccessPostfix;

//	public function __construct(array $config) {
//		$this->path = $config['path'];
//		$this->url = $config['url'];
//		$this->restrictedAccessPostfix = $config['restricted-access-postfix'];
//		$this->imgUrl = $config['img']['url'];
//		$this->imgPath = $config['img']['path'];
//		$this->imgSecret = $config['img']['secret'];
//		$this->imgJpegQuality = $config['img']['jpeg-quality'];
//
//		$this->path = Application::cpath($this->path);
//		$this->imgPath = realpath($this->imgPath);
//	}
}