<?php
declare(strict_types=1);

namespace Edde\Translation;

use Edde\Dto\DtoServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Translation\Repository\TranslationRepositoryTrait;
use Marsh\Shared\Dto\LanguageDto;
use Marsh\User\CurrentUserTrait;
use Marsh\User\Exception\UserNotSelectedException;
use Marsh\User\User;
use function array_map;
use function array_merge;

class LanguageService {
	use CurrentUserTrait;
	use LoggerTrait;
	use DtoServiceTrait;
	use TranslationRepositoryTrait;

	/**
	 * Resolve a language for the given user. NULL as a result means translations are off.
	 *
	 * @param User        $user
	 * @param string|null $default
	 *
	 * @return string|null
	 */
	public function resolve(User $user, string $default = null): ?string {
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
		$language = $this->resolve($user = $this->currentUser->requireUser(), $default);
		$this->logger->info(sprintf('Resolved language [%s] for current user [%s]; default [%s].', $language, $user->emea, $default ?? 'null'));
		return $language;
	}

	/**
	 * @return LanguageDto[]
	 */
	public function toList(): array {
		return array_merge(array_map(function ($item) {
			return $this->dtoService->fromArray(LanguageDto::class, [
				'id'   => $item->locale,
				'code' => $item->locale,
			]);
		}, $this->translationRepository->toLanguages()), [
			$this->dtoService->fromArray(LanguageDto::class, [
				'id'   => 'translation',
				'code' => 'translation',
			]),
		]);
	}
}
