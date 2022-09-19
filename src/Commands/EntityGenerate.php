<?php

namespace Ybaruchel\EntityGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Ybaruchel\EntityGenerator\Generators\GeneratorInterface;
use Ybaruchel\EntityGenerator\Generators\Repository\Generator as RepositoryGenerator;
use Ybaruchel\EntityGenerator\Generators\Service\Generator as ServiceGenerator;

class EntityGenerate extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $signature = 'make:entity {name}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new Entity';

	/**
	 * Execute the console command.
	 *
	 * @param RepositoryGenerator $repositoryGenerator
	 * @param ServiceGenerator $serviceGenerator
	 * @return void
	 */
	public function handle(RepositoryGenerator $repositoryGenerator, ServiceGenerator $serviceGenerator)
	{
		$name = $this->argument('name');
		$model = Str::studly(class_basename($name));

		// Creating model
		$this->call('make:model', [
			'name' => 'Models/Entities/' . $model,
		]);

		// Creating repository classes
		$this->handleGenerator($repositoryGenerator, $name);

		// Creating service classes
		$this->handleGenerator($serviceGenerator, $name);
	}

	public function handleGenerator(GeneratorInterface $generator, $name)
	{
		try {
			$this->info($generator->handle($name));
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
	}
}
