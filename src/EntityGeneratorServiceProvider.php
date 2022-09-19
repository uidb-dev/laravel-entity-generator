<?php

namespace Ybaruchel\EntityGenerator;

use Illuminate\Support\ServiceProvider;
use Ybaruchel\EntityGenerator\Commands\EntityGenerate;

class EntityGeneratorServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		if ($this->app->runningInConsole()) {
			$this->commands([
				EntityGenerate::class,
			]);
		}
    }
}
