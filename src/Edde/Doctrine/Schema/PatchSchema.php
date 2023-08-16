<?php
declare(strict_types=1);

namespace Edde\Doctrine\Schema;

interface PatchSchema {
	function patch($type = 'mixed');

	function filter($type = 'mixed');
}
