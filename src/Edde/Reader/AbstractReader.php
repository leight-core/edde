<?php
declare(strict_types=1);

namespace Edde\Reader;

use Edde\Dto\DtoServiceTrait;
use Edde\Job\Exception\JobInterruptedException;
use Edde\Mapper\Exception\SkipException;
use Edde\Php\Exception\MemoryLimitException;
use Edde\Progress\IProgress;
use Edde\Progress\NoProgress;
use Edde\Reflection\ReflectionServiceTrait;
use Generator;
use Throwable;

abstract class AbstractReader implements IReader {
	use DtoServiceTrait;
	use ReflectionServiceTrait;

	/**
	 * @inheritdoc
	 */
	public function stream(Generator $generator, IProgress $progress = null): Generator {
		$progress = NoProgress::ensure($progress);
		$this->onStart();
		try {
			$dto = $this->reflectionService->toClass(static::class)->getRequestClassOf('handle');
			foreach ($generator as $index => $item) {
				try {
					$progress->check();
					/**
					 * This method could be called arbitrary as it eventually goes updated.
					 *
					 * But keep in mind it could confuse that one good guy who's looking here and
					 * scratching his head why the fck this thing behaves so strange...
					 */
					$progress->onCurrent([
						'index' => $index,
						'item'  => $item,
					]);
					if (($source = ($dto ? $this->dtoService->fromArray($dto, $item, true) : $item)) === null) {
						throw new SkipException(sprintf('An empty source of [%s].', $dto));
					}
					/**
					 * Again, current - but now we have also the source DTO (if the DTO is used).
					 */
					$progress->onCurrent([
						'index'  => $index,
						'source' => $source,
						'item'   => $item,
					]);
					$source && yield $this->handle($source);
					$progress->onProgress();
				} catch (SkipException $exception) {
					$progress->log(
						$progress::LOG_WARNING,
						$exception->getMessage()
					);
					$progress->onProgress();
				} catch (JobInterruptedException $exception) {
					/**
					 * When interrupted, re-throw the exception.
					 */
					throw $exception;
				} catch (MemoryLimitException $exception) {
					$progress->onError($exception, $index);
					throw new JobInterruptedException($exception->getMessage(), 0, $exception);
				} catch (Throwable $throwable) {
					$progress->onError($throwable, $index);
				}
			}
			$this->onSuccess();
		} finally {
			$this->onFinish();
		}
	}

	/**
	 * @inheritdoc
	 */
	public function read(Generator $generator, IProgress $progress = null): void {
		foreach ($this->stream($generator, $progress) as $_) ;
	}

	/**
	 * Called before a streaming loop
	 */
	protected function onStart() {
	}

	/**
	 * Called when a streaming finishes successfully
	 */
	protected function onSuccess() {
	}

	/**
	 * Called when a streaming is finished (regardless of success)
	 */
	protected function onFinish() {
	}
}
