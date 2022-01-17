<?php
declare(strict_types=1);

namespace Edde\User\Api;

use Edde\Dto\DtoServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Dto\Settings\UpdateSettingsDto;
use Edde\User\Dto\Settings\UserSettingsDto;
use Edde\User\Repository\UserRepositoryTrait;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Throwable;

/**
 * @description Endpoint used to update user's settings.
 */
class UpdateSettingsEndpoint extends AbstractMutationEndpoint {
	use UserRepositoryTrait;
	use CurrentUserServiceTrait;
	use DtoServiceTrait;

	/**
	 * @param UpdateSettingsDto $updateSettingsDto
	 *
	 * @return UserSettingsDto
	 *
	 * @throws JsonException
	 * @throws Throwable
	 */
	public function post(UpdateSettingsDto $updateSettingsDto): UserSettingsDto {
		return $this->dtoService->fromObject(
			UserSettingsDto::class,
			Json::decode($this->userRepository->updateSettings($this->currentUserService->requiredId(), $updateSettingsDto->settings)->settings)
		);
	}
}
