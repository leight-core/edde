<?php
declare(strict_types=1);

namespace Edde\Doctrine\Schema;

interface UpdateSchema {
	function update($type = 'mixed');

	function filter($type = 'mixed', $required = false);

	function force(): ?bool;
}
