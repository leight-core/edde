<?php
declare(strict_types=1);

namespace Edde\File;

use Edde\File\Dto\ChunkDto;
use Edde\File\Dto\CommitDto;
use Edde\File\Dto\FileDto;
use Edde\Stream\IStream;
use Edde\User\CurrentUserServiceTrait;

class FileChunkService {
	use FileServiceTrait;
	use CurrentUserServiceTrait;

	public function chunk(ChunkDto $chunkDto) {
		return $chunkDto->stream->use(function (IStream $stream) use ($chunkDto) {
			$this->fileService->chunk($stream, $chunkDto->hash, $this->currentUserService->optionalId());
		});
	}

	public function commit(CommitDto $commitDto): FileDto {
		return $this->fileService->commit($commitDto->uuid, $commitDto->path, $commitDto->name, $commitDto->replace);
	}
}
