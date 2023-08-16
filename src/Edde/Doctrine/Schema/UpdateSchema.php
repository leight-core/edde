<?php
declare(strict_types=1);

namespace Edde\Doctrine\Schema;

interface UpdateSchema {
	function update();

	function filter($required = false);

	function force(): ?bool;
}
