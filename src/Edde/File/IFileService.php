<?php
declare(strict_types=1);

namespace Edde\File;

use Dibi\Exception;
use Edde\File\Dto\FileDto;
use Edde\File\Dto\GcResultDto;
use Edde\File\Exception\FileNotReadableException;
use Edde\Stream\IStream;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

interface IFileService {
	/**
	 * @param IStream     $stream
	 * @param string      $name
	 * @param string|null $userId
	 *
	 * @return FileDto
	 *
	 * @throws FilesystemException
	 */
	public function chunk(IStream $stream, string $name, ?string $userId = null): FileDto;

	/**
	 * Commit the given chunk; when committed, chunk became a file.
	 *
	 * @param string      $chunk
	 * @param string      $path
	 * @param string|null $name
	 *
	 * @return FileDto
	 */
	public function commit(string $chunk, string $path, string $name = null): FileDto;

	/**
	 * Prepare writable file and it's record in database.
	 *
	 * @param string      $path
	 * @param string      $name
	 * @param string      $mime
	 * @param float|null  $ttl
	 * @param string|null $userId
	 *
	 * @return FileDto
	 */
	public function file(string $path, string $name, string $mime, float $ttl = null, ?string $userId = null): FileDto;

	/**
	 * Accepts an uploaded PHP file (thus this method is working with $_FILES).
	 *
	 * @param string     $file name in $_FILES[]
	 * @param string     $path
	 * @param string     $name
	 * @param float|null $ttl
	 *
	 * @return FileDto
	 *
	 * @throws Exception
	 * @throws FileNotReadableException
	 * @throws Throwable
	 */
	public function accept(string $file, string $path, string $name, float $ttl = null): FileDto;

	/**
	 * Takes an input file and copy it to managed space of File Service
	 *
	 * @param IStream     $stream
	 * @param string      $path
	 * @param string      $name
	 * @param float|null  $ttl
	 * @param string|null $userId
	 *
	 * @return FileDto
	 */
	public function store(IStream $stream, string $path, string $name, float $ttl = null, ?string $userId = null): FileDto;

	/**
	 * Return filesize
	 *
	 * @param string $file
	 *
	 * @return int
	 */
	public function sizeOf(string $file): int;

	/**
	 * Run garbage collector - removed stale files from the database and try to clean-up stale filesystem
	 * files managed by this service.
	 *
	 * GC will run with probability to prevent heavy loads.
	 */
	public function gc(bool $force = false): GcResultDto;

	public function send(string $fileId, ResponseInterface $response, ?string $name = null): ResponseInterface;

	public function useFile(string $fileId, callable $callback);

	public function consumeFile(string $fileId, callable $callback);

	/**
	 * Ensures requested (persistent) directory - files stored there are not managed by GC and this service at all.
	 *
	 * @return string
	 */
	public function persistent(string $directory = null): string;

	/**
	 * @param string $path
	 */
	public function remove(string $path);
}
