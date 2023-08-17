<?php
declare(strict_types=1);

namespace Edde\Job\Schema\Job;

interface JobOrderBySchema {
	const meta = [
		'orderBy' => [
			'created',
			'status',
		],
	];
}
