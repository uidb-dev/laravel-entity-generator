<?php

namespace Ybaruchel\EntityGenerator\Generators;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

abstract class BaseGenerator implements GeneratorInterface
{
	/**
	 * The type of class being generated.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * The filesystem instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * The Laravel application instance.
	 *
	 * @var \Illuminate\Contracts\Foundation\Application
	 */
	protected $laravel;

	public function __construct(Filesystem $files, Application $laravel)
	{
		$this->files = $files;
		$this->laravel = $laravel;
	}

	/**
	 * Handle entity type generate.
	 *
	 * @param $name
	 * @return string
	 * @throws FileNotFoundException
	 * @throws \Exception
	 */
	public function handle($name): string
	{
		$name = $this->qualifyClass($this->getNameInput($name));

		$paths = $this->getPaths($name);

		// First we will check to see if the class already exists. If it does, we don't want
		// to create the class and overwrite the user's code. So, we will bail out so the
		// code is untouched. Otherwise, we will continue generating this class' files.
		if ($this->alreadyExists($this->getNameInput($name))) {
			throw new \Exception(ucfirst($this->type).' already exists!');
		}

		// Next, we will generate the path to the location where this class' file should get
		// written. Then, we will build the class and make the proper replacements on the
		// stub files so that it gets the correctly formatted namespace and class name.
		$this->makeDirectory($paths);

		foreach ($paths as $classType => $path) {
			$this->files->put($path, $this->buildClass($classType, $name));
		}

		return ucwords($this->type).' created successfully.';
	}

	/**
	 * Get the stub file for the generator.
	 *
	 * @param $classType
	 * @return string
	 */
	abstract protected function getStub($classType): string;

	/**
	 * Get the destination class path.
	 *
	 * @param  string  $name
	 * @return array
	 */
	abstract protected function getPaths($name): array;

	/**
	 * Get the desired class name from the input.
	 *
	 * @return string
	 */
	protected function getNameInput($name)
	{
		return studly_case(trim($name));
	}

	/**
	 * Parse the class name and format according to the root namespace.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function qualifyClass($name)
	{
		$name = ltrim($name, '\\/');

		$rootNamespace = $this->rootNamespace();

		if (Str::startsWith($name, $rootNamespace)) {
			return $name;
		}

		$name = str_replace('/', '\\', $name);

		return $this->qualifyClass(
			$this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
		);
	}

	/**
	 * Determine if the class already exists.
	 *
	 * @param  string  $rawName
	 * @return bool
	 */
	protected function alreadyExists($rawName)
	{
		$existingFiles = array_filter(
			$this->getPaths($this->qualifyClass($rawName)),
			function ($path) { return $this->files->exists($path); }
		);
		return (bool) count($existingFiles);
	}

	/**
	 * Get the default namespace for the class.
	 *
	 * @param  string  $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace)
	{
		return $rootNamespace;
	}

	/**
	 * Get the root namespace for the class.
	 *
	 * @return string
	 */
	protected function rootNamespace()
	{
		return $this->laravel->getNamespace();
	}

	/**
	 * Write a string as error output.
	 *
	 * @param  string $string
	 * @return void
	 * @throws \Exception
	 */
	public function error($string)
	{
		throw new \Exception($string);
	}

	/**
	 * Build the directory for the class if necessary.
	 *
	 * @param  array  $paths
	 * @return void
	 */
	protected function makeDirectory($paths): void
	{
		$paths = array_wrap($paths);
		$firstPath = reset($paths);

		if (! $this->files->isDirectory(dirname($firstPath))) {
			$this->files->makeDirectory(dirname($firstPath), 0777, true, true);
		}
	}

	/**
	 * Build the class with the given name.
	 *
	 * @param  string $classType
	 * @param $name
	 * @return string
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 */
	protected function buildClass($classType, $name)
	{
		$stub = $this->files->get($this->getStub($classType));

		return $this->replaceStudly($stub, $name)->replaceCamel($stub, $name);
	}

	/**
	 * Replace the namespace for the given stub.
	 *
	 * @param  string  $stub
	 * @param  string  $name
	 * @return $this
	 */
	protected function replaceStudly(&$stub, $name)
	{
		$stub = str_replace(
			'Dummy',
			class_basename($this->getNameInput($name)),
			$stub
		);

		return $this;
	}

	/**
	 * Replace the namespace for the given stub.
	 *
	 * @param  string  $stub
	 * @param  string  $name
	 * @return $this
	 */
	protected function replaceCamel(&$stub, $name)
	{
		return str_replace(
			'dummy',
			camel_case(class_basename($this->getNameInput($name))),
			$stub
		);
	}
}