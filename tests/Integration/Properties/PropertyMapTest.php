<?php

namespace Catalog\Core\Tests\Integration\Properties;

use Catalog\Core\Tests\BaseTestCase;
use Catalog\Core\Properties\Property;
use Catalog\Core\Properties\PropertyMap;
use Catalog\Core\Catalog;

class PropertyMapTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures();
    }

    /**
     * @test
     */
    public function properties_are_lazy_loaded()
    {
        $codes = ['manufacturer', 'country'];

        $map = new PropertyMap($codes);

        foreach ($codes as $expectedCode) {
            $this->repositoryFactory->expectCallFor(Property::class);

            $property = $map->getProperty($expectedCode);

            $this->assertInstanceOf(Property::class, $property);

            $this->assertEquals($expectedCode, $property->getCode());

            $this->repositoryFactory->assertExpectationsWereMet();
        }
    }

    /**
     * @test
     */
    public function properties_are_eager_loaded()
    {
        $codes = ['manufacturer', 'country'];

        $map = new PropertyMap($codes);

        $map->load();

        $this->repositoryFactory->assertNoCalls();

        foreach ($codes as $expectedCode) {
            $property = $map->getProperty($expectedCode);

            $this->assertInstanceOf(Property::class, $property);

            $this->assertEquals($expectedCode, $property->getCode());
        }
    }

    /**
     * @test
     */
    public function property_ids_are_correct()
    {
        $codes = ['manufacturer', 'country'];

        $map = new PropertyMap($codes);

        $this->assertEquals([1], $map->getPropertyIds(['manufacturer']));
        $this->assertEquals([2], $map->getPropertyIds(['country']));
        $this->assertEquals([1, 2], $map->getPropertyIds());
    }

    protected function loadFixtures(): void
    {
        $this->repositoryFactory->loadFixtures(Property::class, [
            ['id' => 1, 'code' => 'manufacturer', 'type' => 'string'],
            ['id' => 2, 'code' => 'country', 'type' => 'string'],
        ]);
    }
}
