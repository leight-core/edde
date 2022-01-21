<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Endpoint;

use Edde\File\FileServiceTrait;
use Edde\Rest\Endpoint\AbstractEndpoint;
use Edde\Rest\Exception\RestException;

/**
 * @description Endpoint used to fetch a file by an UUID.
 * @query       fileId
 */
class DownloadEndpoint extends AbstractEndpoint {
	use FileServiceTrait;

	/**
	 * @throws RestException
	 */
	public function get() {
		return $this->fileService->send($this->param('fileId'), $this->response);
	}
}
