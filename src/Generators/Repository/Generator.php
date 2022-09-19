<?php

namespace Ybaruchel\EntityGenerator\Generators\Repository;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Ybaruchel\EntityGenerator\Generators\BaseGenerator;
use Illuminate\Contracts\Foundation\Application;

class Generator extends BaseGenerator
{
	/**
	 * The type of classes being generated.
	 *
	 * @var string
	 */
	public $type = 'repository';

	/**
	 * Get the destination classes path.
	 *
	 * @param  string  $name
	 * @return array
	 */
	protected function getPaths($name): array
	{
		$name = Str::replaceFirst($this->rootNamespace(), '', $name);
		$name = str_replace('\\', '/', $name);

		$basePath = $this->laravel['path'].'/Repositories/'.$name.'/';

		return [
			'interface' => $basePath.$name.'Interface.php',
			'repository' => $basePath.$name.'Repository.php',
			'service_provider' => $basePath.$name.'RepositoryServiceProvider.php',
		];
	}

	/**
	 * Returns stub file.
	 *
	 * @param $classType
	 * @return string
	 */
	protected function getStub($classType): string
	{
		return __DIR__.'/stubs/'.$classType.'.stub';
	}
}