<?php

namespace Catalog\Core\Tests\Unit\Properties;

use Catalog\Core\Tests\BaseTestCase;
use Catalog\Core\Properties\Property;
use Catalog\Core\Properties\PropertyMap;
use Catalog\Core\Catalog;

class PropertyMapTest extends BaseTestCase
{
    /**
     * @test
     */
    public function property_is_not_loaded_if_it_has_been_provided_to_constructor()
    {
        $map = new PropertyMap([
            'manufacturer',
            'country' => new Property($this->getPropertiesData()['country']),
        ]);

        $mock = $this->repositoryFactory->mock(Property::class);

        $mock->shouldReceive('get')
            ->once()
            ->with(['code' => ['manufacturer']])
            ->andReturn([
                $this->getPropertiesData()['manufacturer']
            ]);

        $map->load();
    }

    /**
     * @test
     */
    public function all_properties_are_loaded_if_load_is_forced()
    {
        $map = new PropertyMap([
            'manufacturer',
            'country' => new Property($this->getPropertiesData()['country']),
        ]);

        $mock = $this->repositoryFactory->mock(Property::class);

        $mock->shouldReceive('get')
            ->once()
            ->with(['code' => ['manufacturer', 'country']])
            ->andReturn($this->getPropertiesData());

        $map->load(null, true);
    }

    protected function getPropertiesData(): array
    {
        return [
            'country' => [
                'id' => 1,
                'code' => 'country',
                'name' => 'country',
            ],
            'manufacturer' => [
                'id' => 2,
                'code' => 'manufacturer',
                'name' => 'manufacturer',
            ],
        ];
    }
}
