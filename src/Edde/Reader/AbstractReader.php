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
				$source = ($dto ? $this->dtoService->fromArray($dto, $item) : $item);
				/**
				 * Again, current - but now we have also the source DTO (if the DTO is used).
				 */
				$progress->onCurrent([
					'index'  => $index,
					'source' => $source,
					'item'   => $item,
				]);
				yield $this->handle($source);
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
				$progress->onError($exception);
				throw new JobInterruptedException($exception->getMessage(), 0, $exception);
			} catch (Throwable $throwable) {
				$progress->onError($throwable);
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function read(Generator $generator, IProgress $progress = null): void {
		foreach ($this->stream($generator, $progress) as $_) ;
	}
}
