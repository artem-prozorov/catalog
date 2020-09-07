<?php

namespace Catalog\Core\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Repositories\Core\Resolvers\HardResolver;
use Catalog\Core\Tests\Support\RepositoryFactory;
use Catalog\Core\Tests\Support;
use Catalog\Core\Entities\Element;
use Catalog\Core\Properties\Property;
use Catalog\Core\Properties\PropertyMap;
use Catalog\Core\Properties\Values\AbstractPropertyValue;
use Catalog\Core\Properties\Values\PropertyValuesFactory;
use Catalog\Core\Properties\Values\StringValue;
use Catalog\Core\Manager;

class BaseTestCase extends MockeryTestCase
{
    protected RepositoryFactory $repositoryFactory;

    protected array $bindings = [
        Property::class => Support\FakeRepository::class,
        Element::class => Support\ElementFakeRepository::class,
        AbstractPropertyValue::class => Support\PropertyValueFakeRepository::class,
        PropertyMap::class => Support\PropertyMapFakeRepository::class,
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->repositoryFactory = new RepositoryFactory(
            new HardResolver(),
            $this->bindings
        );

        Manager::setRepositoryFactory($this->repositoryFactory);

        $propertyValueFactory = new PropertyValuesFactory([
            'string' => StringValue::class,
        ]);

        Manager::setPropertyValuesFactory($propertyValueFactory);
    }
}
