<?php
declare(strict_types=1);

namespace Edde\Sdk;

trait SdkTrait {
    /** @var Sdk */
    protected $sdk;

    /**
     * @Inject
     *
     * @param Sdk $sdk
     */
    public function setSdk(Sdk $sdk): void {
        $this->sdk = $sdk;
    }
}
