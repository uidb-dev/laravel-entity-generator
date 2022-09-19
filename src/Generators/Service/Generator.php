<?php

namespace Ybaruchel\EntityGenerator\Generators\Service;

use Illuminate\Support\Str;
use Ybaruchel\EntityGenerator\Generators\BaseGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;

class Generator extends BaseGenerator
{
	/**
	 * The type of classes being generated.
	 *
	 * @var string
	 */
	public $type = 'service';

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

		$basePath = $this->laravel['path'].'/Services/'.$name.'/';

		return [
			'facade' => $basePath.$name.'Facade.php',
			'service' => $basePath.$name.'Service.php',
			'service_provider' => $basePath.$name.'ServiceServiceProvider.php',
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