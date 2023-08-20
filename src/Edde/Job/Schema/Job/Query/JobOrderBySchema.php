<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job\Query;

interface JobOrderBySchema {
	const meta = [
		'orderBy' => [
			'created',
			'status',
		],
	];
}
