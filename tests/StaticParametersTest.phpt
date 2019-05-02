<?php

namespace IcarusTests\StaticParameters;


use Icarus\StaticParameters\Parameters;
use Icarus\StaticParameters\StaticParameters;
use Nette\Configurator;
use Tester\Assert;
use Tester\TestCase;


require_once __DIR__ . '/bootstrap.php';

class StaticParametersTest extends TestCase
{

    /**
     * @var Configurator
     */
    private $configurator;



    protected function setUp()
    {
        $this->configurator = new Configurator();
        $this->configurator->setTempDirectory(TEMP_DIR);
        $this->configurator->setDebugMode(true);
        $this->configurator->addConfig(__DIR__ . "/config/config.neon");

        StaticParameters::reset();
    }



    public function testLock(): void
    {
        Assert::false(Parameters::isLocked());

        $this->configurator->createContainer();

        Assert::true(Parameters::isLocked());
    }



    public function testValid(): void
    {
        Assert::false(CustomParameters::isLocked());

        $this->configurator->addConfig(__DIR__ . "/data/valid.neon");

        Assert::noError(function () {
            $this->configurator->createContainer();
        });

        Assert::true(CustomParameters::isLocked());
        Assert::equal("mySpecialKey", CustomParameters::apiKey());


        Assert::noError(function () {
            CustomParameters::someNull();
        });

        Assert::equal(null, CustomParameters::someNull());

        Assert::same(['apiKey' => 'mySpecialKey', 'someNull' => null], CustomParameters::all());
    }



    public function testInvalidConfig(): void
    {
        $this->configurator->addConfig(__DIR__ . "/data/invalid-structure.config.neon");
        Assert::exception(
            function () {
                $this->configurator->createContainer();
            },
            \Throwable::class
        );
    }
}

(new StaticParametersTest())->run();