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
                '@leight/rpc-client' => '^0.5.0',
                '@leight/utils'      => '^0.5.0',
            ],
            'devDependencies' => [
                '@leight/eslint' => '^0.5.0',
                '@leight/tsconfig' => '^0.5.0',
                'typescript'       => '^5.1.3',
            ],
        ], JSON_PRETTY_PRINT)));
    }

    protected function generateIndexTs() {
        file_put_contents("$this->output/src/index.ts", 'export * from "./$export/$export.ts"');
    }

    protected function generateEslintIgnore() {
        file_put_contents("$this->output/.eslintignore", "node_modules
dist
");
    }

    protected function generateEslintRc() {
        file_put_contents("$this->output/.eslintrc", '{
	"root": true,
	"extends": [
		"@leight/eslint"
	],
	"overrides": [
		{
			"files": [
				"src/**/*"
			],
			"settings": {
				"disable/plugins": [
					"react",
					"eslint-plugin-react"
				]
			}
		}
	]
}
');
    }

    protected function generateTsConfig() {
        file_put_contents("$this->output/tsconfig.json", '{
	"extends": "@leight/tsconfig/esbuild.json",
	"compilerOptions": {
		"rootDir": "src",
		"baseUrl": "src"
	},
	"include": [
		"src/**/*",
		"src/**/*.json"
	],
	"exclude": [
		"node_modules"
	]
}
');
    }

    public function generate(): ?string {
        @mkdir("$this->output/src", 0777, true);

        $this->generatePackageJson();
        $this->generateIndexTs();
        $this->generateEslintIgnore();
        $this->generateEslintRc();
        $this->generateTsConfig();

        $this->container
            ->injectOn(new SchemaGenerator())
            ->withOutput($this->output)
            ->generate();

        $this->container
            ->injectOn(new RpcHandlerGenerator())
            ->withOutput($this->output)
            ->generate();

        return null;
    }
}
