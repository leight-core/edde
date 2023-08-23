<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;

class PackageGenerator extends AbstractGenerator {
    protected $package;

    protected function generatePackageJson() {
        $this->writeTo("package.json", str_replace('\/', '/', json_encode([
            'version'         => '0.5.0',
            'name'            => $this->package,
            'description'     => 'Generated SDK',
            'sideEffects'     => false,
            'type'            => 'module',
            'main'            => 'src/index.ts',
            'module'          => 'src/index.ts',
            'types'           => 'src/index.ts',
            'dependencies'    => [
                '@pico/auth'  => '^1.0.0',
                '@pico/bulk'  => '^1.0.0',
                '@pico/job'   => '^1.0.0',
                '@pico/rpc'   => '^1.0.0',
                '@pico/utils' => '^1.0.0',
                'react' => '^18.2.0',
            ],
            'devDependencies' => [
                '@pico/eslint-config-eslint' => '^1.0.0',
                '@pico/tsconfig'             => '^1.0.0',
                '@types/react' => '^18.2.21',
                'typescript'                 => '^5.1.6',
            ],
        ], JSON_PRETTY_PRINT)));
    }

    protected function generateIndexTs() {
        $this->writeTo("src/index.ts", 'export * from "./$export/$export"');
    }

    protected function generateEslintIgnore() {
        $this->writeTo(".eslintignore", "node_modules
dist
");
    }

    protected function generateEslintRc() {
        $this->writeTo(".eslintrc", '{
	"root": true,
	"extends": [
		"@pico/eslint"
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
        $this->writeTo('tsconfig.json', '{
	"extends": "@pico/tsconfig/esbuild.json",
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

    public function withPackage(string $package): self {
        $this->package = $package;
        return $this;
    }

    public function generate(): void {
        $this->generatePackageJson();
        $this->generateIndexTs();
        $this->generateEslintIgnore();
        $this->generateEslintRc();
        $this->generateTsConfig();

        $generators = [
            new SchemaGenerator(),
            new RpcHandlerGenerator(),
            new FetchGenerator(),
            new SourceQueryGenerator(),
            new FormGenerator(),
            new SourceQueryInputGenerator(),
            new FindByQueryGenerator(),
        ];

        foreach ($generators as $generator) {
            $this->container
                ->injectOn($generator)
                ->withOutput($this->output)
                ->generate();
        }
    }
}
