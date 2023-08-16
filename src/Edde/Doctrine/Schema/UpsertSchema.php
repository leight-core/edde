<?php
declare(strict_types=1);

namespace Edde\Doctrine\Schema;

interface UpsertSchema {
	function create($required = false);

	function update($required = false);

	function filter($required = false);
}
