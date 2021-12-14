<?php
declare(strict_types=1);

namespace Edde\Rest;

/**
 * Marker interface for endpoints making server-side side effects (like modifying data).
 */
interface IMutationEndpoint extends IEndpoint {
}
