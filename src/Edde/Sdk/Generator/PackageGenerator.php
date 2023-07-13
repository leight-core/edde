<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Container\ContainerTrait;
use Edde\Sdk\AbstractGenerator;

class PackageGenerator extends AbstractGenerator {
    use ContainerTrait;

    protected function generatePackageJson() {
        file_put_contents("$this->output/package.json", str_replace('\/', '/', json_encode([
            'version'         => '0.5.0',
            'name'            => '@edde/sdk',
            'description'     => 'Generated SDK',
            'sideEffects'     => false,
            'type'            => 'module',
            'main'            => 'src/index.ts',
            'module'          => 'src/index.ts',
            'types'           => 'src/index.ts',
            'dependencies'    => [
                '@leight/utils' => '^0.5.0',
            ],
            'devDependencies' => [
                '@leight/tsconfig' => '^0.5.0',
                'typescript'       => '^5.1.3',
            ],
        ], JSON_PRETTY_PRINT)));
    }

    public function generate(): ?string {
        $this->generatePackageJson();

        $this->container
            ->injectOn(new RpcHandlerGenerator())
            ->withOutput($this->output)
            ->generate();

        $this->container
            ->injectOn(new SchemaGenerator())
            ->withOutput($this->output)
            ->generate();
    }
}
