<?php
declare(strict_types=1);

namespace Edde\Slim;

use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Psr\Http\Message\ResponseInterface;

class Response {
	/**
	 * @param ResponseInterface $response
	 * @param                   $payload
	 * @param int               $status
	 *
	 * @return ResponseInterface
	 *
	 * @throws JsonException
	 */
	static public function withJson(ResponseInterface $response, $payload, int $status = 200) {
		$response->getBody()->write(Json::encode($payload, Json::PRETTY));
		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($status);
	}
}
