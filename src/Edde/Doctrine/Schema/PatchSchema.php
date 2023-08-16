<?php
declare(strict_types=1);

namespace Edde\Doctrine\Schema;

interface PatchSchema {
	function patch();

	function filter();
}
