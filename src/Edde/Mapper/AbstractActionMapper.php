<?php
declare(strict_types=1);

namespace Edde\Mapper;

/**
 * Action mappers are intended to be used to map an input data to some action (with a result or
 * not). This could be simple small piece of interchangeable mapper used in various complex
 * (usually abstract) services.
 *
 * The whole idea is basically a function call in quite strange interface.
 */
abstract class AbstractActionMapper extends AbstractMapper implements IActionMapper {
}
