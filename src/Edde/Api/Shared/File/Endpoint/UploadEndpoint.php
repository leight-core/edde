<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\Dto\DtoServiceTrait;
use Edde\File\Dto\ChunkDto;
use Edde\File\FileChunkServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;
use Edde\Stream\ResourceStream;

/**
 * @query uuid
 */
class UploadEndpoint extends AbstractMutationEndpoint {
	use FileChunkServiceTrait;
	use DtoServiceTrait;

	public function post(): array {
		$this->fileChunkService->chunk($this->dtoService->fromArray(ChunkDto::class, [
			'hash'   => $uuid = $this->param('uuid'),
			'stream' => ResourceStream::wrap($this->request->getBody()->detach()),
		]));
		return [
			$uuid,
		];
	}
}
