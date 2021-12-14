<?php
declare(strict_types=1);

namespace Edde\Link;

use Edde\Config\ConfigServiceTrait;
use League\Uri\Uri;
use League\Uri\UriTemplate;
use Slim\App;

class LinkGenerator {
	use ConfigServiceTrait;

	/** @var App */
	protected $app;

	public function __construct(App $app) {
		$this->app = $app;
	}

	public function link(string $path, bool $canonical = false): string {
		$components = [
			'scheme' => $this->configService->get('http.protocol', 'https'),
			'host'   => $this->configService->get('http.host'),
			'port'   => ($port = $this->configService->get('http.port')) ? (int)$port : null,
			'path'   => '/' . ltrim($this->app->getBasePath() . $path, '/'),
		];
		if (!$canonical) {
			unset($components['scheme'], $components['host'], $components['port']);
		}
		return (string)Uri::createFromComponents($components);
	}

	public function template(string $path, bool $canonical = false): UriTemplate {
		return new UriTemplate($this->link($path, $canonical));
	}
}
