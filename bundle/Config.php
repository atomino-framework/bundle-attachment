<?php namespace Atomino\Bundle\Attachment;

class Config {
	public function __construct(
		public string $path,
		public string $url,
		public string $restrictedAccessPostfix,
		public string $imgUrl,
		public string $imgPath,
		public string $imgSecret,
		public int $imgJpegQuality,
	) {
	}
}