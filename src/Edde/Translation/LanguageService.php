<?php
declare(strict_types=1);

namespace Edde\Translation;

use Edde\Bridge\User\CurrentUser;
use Edde\Dto\Common\SelectItemDto;
use Edde\Dto\DtoServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Exception\UserNotSelectedException;
use function array_map;
use function array_merge;
use function json_encode;

class LanguageService {
	use CurrentUserServiceTrait;
	use LoggerTrait;
	use DtoServiceTrait;
	use TranslationRepositoryTrait;

	/**
	 * Resolve a language for the given user. NULL as a result means translations are off.
	 *
	 * @param CurrentUser $user
	 * @param string|null $default
	 *
	 * @return string|null
	 */
	public function resolve(CurrentUser $user, string $default = null): ?string {
		return $user->settings ? $user->settings->language : $default;
	}

	/**
	 * @param string|null $default
	 *
	 * @return string|null
	 *
	 * @throws UserNotSelectedException
	 */
	public function forCurrentUser(string $default = null): ?string {
		$language = $this->resolve($user = $this->currentUserService->requireUser(), $default);
		$this->logger->debug(sprintf('Resolved language [%s] for current user [%s]; default [%s].', $language, json_encode($user), $default ?? '- without default -'));
		return $language;
	}

	/**
	 * @return SelectItemDto[]
	 */
	public function toList(): array {
		return array_merge(array_map(function ($item) {
			return $this->dtoService->fromArray(SelectItemDto::class, [
				'id'   => $item->locale,
				'code' => $item->locale,
			]);
		}, $this->translationRepository->toLanguages()), [
			$this->dtoService->fromArray(SelectItemDto::class, [
				'id'   => 'translation',
				'code' => 'translation',
			]),
		]);
	}
}
