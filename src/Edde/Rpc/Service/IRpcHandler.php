<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

use Edde\Dto\SmartDto;

/**
 * Every RPC "endpoint" must implement this interface. It is a single-purpose
 * service handling exactly one thing (so simplified version of the classic controller).
 */
interface IRpcHandler {
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): string;

	public function isMutator(): bool;

	/**
	 * Get schema name used to construct/validate SmartDto.
	 *
	 * @return string
	 */
	public function getRequestSchema(): ?string;

	public function isRequestSchemaOptional(): bool;

	/**
	 * Schema used to validate response from the handler.
	 *
	 * @return string
	 */
	public function getResponseSchema(): ?string;

	public function isResponseSchemaOptional(): bool;

	/**
	 * Handle incoming request; handler should be responsible for
	 * data validation or AbstractRpcHandler can be used to do so
	 * automagically.
	 */
	public function handle(SmartDto $request): ?SmartDto;
}
