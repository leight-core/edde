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
				'@leight/auth' => '^0.6.0',
				'@leight/bulk' => '^0.6.0',
				'@leight/job'  => '^0.6.0',
				'@leight/rpc'   => '^0.6.0',
				'@leight/utils' => '^0.6.0',
			],
			'devDependencies' => [
				'@leight/eslint-config-eslint' => '^0.6.0',
				'@leight/tsconfig'             => '^0.6.0',
				'typescript'                   => '^5.1.6',
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
		$this->writeTo('tsconfig.json', '{
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

		$this->container
			->injectOn(new SchemaGenerator())
			->withOutput($this->output)
			->generate();

		$this->container
			->injectOn(new RpcHandlerGenerator())
			->withOutput($this->output)
			->generate();

		$this->container
			->injectOn(new FetchGenerator())
			->withOutput($this->output)
			->generate();

		$this->container
			->injectOn(new SourceQueryGenerator())
			->withOutput($this->output)
			->generate();

		$this->container
			->injectOn(new FormGenerator())
			->withOutput($this->output)
			->generate();

		$this->container
			->injectOn(new SourceQueryInputGenerator())
			->withOutput($this->output)
			->generate();
	}
}
