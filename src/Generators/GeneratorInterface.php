<?php

namespace Ybaruchel\EntityGenerator\Generators;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

interface GeneratorInterface
{
	/**
	 * Handle entity type generate.
	 *
	 * @param $name
	 * @return string
	 * @throws FileNotFoundException
	 * @throws \Exception
	 */
	public function handle($name): string;
}