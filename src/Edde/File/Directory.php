<?php
declare(strict_types=1);

namespace Edde\File;

use IteratorAggregate;
use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\PathPrefixer;
use League\Flysystem\StorageAttributes;
use function str_replace;
use const DIRECTORY_SEPARATOR;

class Directory implements IteratorAggregate {
	/** @var PathPrefixer */
	protected $prefixer;
	/** @var Filesystem */
	protected $filesystem;

	public function __construct(string $directory) {
		$this->prefixer = new PathPrefixer($directory, DIRECTORY_SEPARATOR);
		$this->filesystem = new Filesystem(new LocalFilesystemAdapter($directory));
	}

	public function list(string $location = '/', bool $deep = true): DirectoryListing {
		return $this->filesystem->listContents($location, $deep);
	}

	public function files(string $location = '/') {
		return $this->list($location)->filter(function (StorageAttributes $storageAttributes) {
			return $storageAttributes->isFile();
		});
	}

	public function sizeOf(string $file): int {
		clearstatcache();
		return $this->filesystem->fileSize($this->base($file));
	}

	public function prefix(string $path): string {
		return $this->prefixer->prefixPath($path);
	}

	public function normalize(string $path): string {
		return str_replace('/', DIRECTORY_SEPARATOR, $path);
	}

	public function base(string $file): string {
		return $this->prefixer->stripPrefix($file);
	}

	public function create(string $directory) {
		$this->filesystem->createDirectory($directory);
	}

	public function delete(string $path) {
		$this->filesystem->delete($path);
	}

	public function deleteDir(string $path) {
		$this->filesystem->deleteDirectory($this->prefixer->stripPrefix($path));
	}

	public function getIterator() {
		return $this->list();
	}
}
