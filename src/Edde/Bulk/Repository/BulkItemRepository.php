<?php
declare(strict_types=1);

namespace Edde\Bulk\Repository;

use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Database\Repository\AbstractRepository;

class BulkItemRepository extends AbstractRepository {
    public function __construct() {
        parent::__construct(BulkItemSchema::class);
        $this->orderBy = [
            '$.created' => 'desc',
        ];
        $this->matchOf = [
            'bulkId' => '$.bulk_id',
            'userId' => '$.user_id',
        ];
    }
}
