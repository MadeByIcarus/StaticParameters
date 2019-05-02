<?php

declare(strict_types=1);

namespace Icarus\StaticParameters\DI;


use Icarus\StaticParameters\Parameters;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette;


class StaticParametersExtension extends CompilerExtension
{

    protected $defaults = [];

    public function getConfigSchema(): Schema
    {
        return Expect::arrayOf(
            Expect::structure([
                'class' => Expect::string()->required(),
                'parameters' => Expect::arrayOf(Expect::mixed())->required()
            ]));
    }



    public function afterCompile(Nette\PhpGenerator\ClassType $class)
    {
        $initialize = $class->getMethod('initialize');
        // all parameters
        $initialize->addBody('// StaticParameters start');

        $parameters = $this->getContainerBuilder()->parameters;
        foreach ($parameters as $key => $value) {
            $initialize->addBody(Parameters::class . '::add(?, ?);', [$key, $value]);
        }
        $initialize->addBody(Parameters::class . "::lock();");

        // custom
        $initialize->addBody('// StaticParameters custom');

        foreach ($this->config as $name => $schema) {
            $class = $schema->class;
            foreach ($schema->parameters as $key => $value) {
                $initialize->addBody($class . '::add(?, ?);', [$key, $value]);
            }
            $initialize->addBody($class . '::lock();');
        }
        $initialize->addBody('// StaticParameters end');
    }
}