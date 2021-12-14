<?php
declare(strict_types=1);

namespace Edde\Log\Mapper;

use DateTime;
use Edde\Log\Dto\LogDto;
use Edde\Log\Repository\LogTagRepositoryTrait;
use Edde\Mapper\AbstractMapper;
use Edde\Tag\Mapper\TagMapperTrait;
use Marsh\User\Mapper\UserMapperTrait;
use Marsh\User\UserRepositoryTrait;

/**
 * @Injectable(lazy=true)
 */
class LogMapper extends AbstractMapper {
	use UserRepositoryTrait;
	use UserMapperTrait;
	use TagMapperTrait;
	use LogTagRepositoryTrait;

	public function item($item, array $params = []) {
		return $this->dtoService->fromArray(LogDto::class, [
			'id'        => $item->id,
			'type'      => $item->type,
			'log'       => $item->log,
			'stack'     => $item->stack,
			'stamp'     => $this->isoDateNull($item->stamp),
			'trace'     => $item->trace,
			'reference' => $item->reference,
			'microtime' => DateTime::createFromFormat('U.u', (string)$item->microtime)->format('Y-m-d H:i:s.u'),
			'user'      => $item->user_id ? $this->userMapper->item($this->userRepository->find($item->user_id), $params) : null,
			'context'   => json_decode($item->context),
			'tags'      => $this->tagMapper->map($this->logTagRepository->findTagByLog($item->id)),
		]);
	}
}
