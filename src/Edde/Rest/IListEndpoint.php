<?php
declare(strict_types=1);

namespace Edde\Rest\;

/**
 * Marks an Endpoint as returning list of small amount of data; could be used
 * to generate Select component in SDK.
 */
interface IListEndpoint extends IEndpoint {
}
