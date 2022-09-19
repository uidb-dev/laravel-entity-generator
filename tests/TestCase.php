<?php

namespace Ybaruchel\EntityGenerator\Tests;

use Ybaruchel\EntityGenerator\EntityGeneratorServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [EntityGeneratorServiceProvider::class];
    }
}