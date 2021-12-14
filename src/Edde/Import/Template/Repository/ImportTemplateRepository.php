<?php
declare(strict_types=1);

namespace Edde\Import\Template\Repository;

use Edde\Repository\AbstractRepository;

class ImportTemplateRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(['name' => true], ['z_import_template_hash_unique']);
	}
}
