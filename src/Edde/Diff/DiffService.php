<?php
declare(strict_types=1);

namespace Edde\Diff;

use Diff\Differ\Differ;
use Diff\Differ\MapDiffer;
use Diff\DiffOp\DiffOp;
use Diff\DiffOp\DiffOpChange;
use Exception;
use function array_diff_assoc;
use function array_diff_key;
use function array_filter;
use function array_flip;

class DiffService {
	/** @var Differ */
	protected $differ;

	public function __construct() {
		$this->differ = new MapDiffer(true);
	}

	/**
	 * @param array $alfa
	 * @param array $beta
	 * @param array $exclude
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	public function changes(array $alfa, array $beta, array $exclude = []): array {
		return array_diff_key(array_filter($this->differ->doDiff($alfa, $beta), function (DiffOp $diffOp) {
			return $diffOp instanceof DiffOpChange;
		}), array_flip($exclude));
	}

	/**
	 * @param array $alfa
	 * @param array $beta
	 * @param array $exclude
	 *
	 * @return bool
	 *
	 * @throws Exception
	 */
	public function hasChanges(array $alfa, array $beta, array $exclude = []): bool {
		return !empty($this->changes($alfa, $beta, $exclude));
	}

	public function diffOf($alfa, $beta, array $exclude = []): array {
		return array_diff_key(array_diff_assoc((array)$alfa, (array)$beta), array_flip($exclude));
	}

	public function isDiff($alfa, $beta, array $exclude = []): bool {
		return !empty($this->diffOf($alfa, $beta, $exclude));
	}
}
