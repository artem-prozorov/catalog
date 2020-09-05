<?php

namespace Catalog\Core\Tests\Integration\Entities;

use Catalog\Core\Entities\Element;
use Catalog\Core\Properties\Property;
use Catalog\Core\Properties\Values\AbstractPropertyValue;
use Catalog\Core\Tests\BaseTestCase;
use Catalog\Core\Properties\PropertyMap;

class ElementTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures();
    }

    /**
     * @test
     */
    public function element_properties_are_lazy_loaded()
    {
        $properties = [
            'manufacturer' => 'Samsung',
            'country' => 'China',
        ];

        $element = new Element([
            'id' => 1,
            'name' => 'product',
            'code' => 'product',
        ]);

        $this->assertEquals('Samsung', (string) $element->getPropertyValue('manufacturer'));
        $this->assertEquals('China', (string) $element->getPropertyValue('country'));

        $this->repositoryFactory->assertNoCalls();

        $this->assertEquals('Samsung', (string) $element->getPropertyValue('manufacturer'));
        $this->assertEquals('China', (string) $element->getPropertyValue('country'));
    }

    /**
     * @test
     */
    public function properties_can_be_eager_loaded()
    {
        $element = new Element([
            'id' => 1,
            'name' => 'product',
            'code' => 'product',
        ]);

        $element->loadPropertyValues();

        $this->repositoryFactory->assertNoCalls();

        $this->assertEquals('Samsung', (string) $element->getPropertyValue('manufacturer'));
        $this->assertEquals('China', (string) $element->getPropertyValue('country'));
        $this->assertEquals([3 => 's', 4 => 'm'], $element->getPropertyValue('available_sizes')->toArray());
    }

    protected function loadFixtures(): void
    {
        $this->repositoryFactory->loadFixtures(Property::class, [
            ['id' => 1, 'code' => 'manufacturer', 'type' => 'string', 'is_multiple' => false],
            ['id' => 2, 'code' => 'country', 'type' => 'string', 'is_multiple' => false],
            ['id' => 3, 'code' => 'available_sizes', 'type' => 'string', 'is_multiple' => true],
        ]);

        $this->repositoryFactory->loadFixtures(AbstractPropertyValue::class, [
            ['id' => 1, 'element_id' => 1, 'property_id' => 1, 'value' => 'Samsung'],
            ['id' => 2, 'element_id' => 1, 'property_id' => 2, 'value' => 'China'],
            ['id' => 3, 'element_id' => 1, 'property_id' => 3, 'value' => 's'],
            ['id' => 4, 'element_id' => 1, 'property_id' => 3, 'value' => 'm'],
        ]);

        $this->repositoryFactory->loadFixtures(Element::class, [
            ['id' => 1, 'name' => 'product_a', 'code' => 'product_a_code'],
            ['id' => 2, 'name' => 'product_b', 'code' => 'product_b_code'],
        ]);

        $this->repositoryFactory->loadFixtures(PropertyMap::class, [
            ['id' => 1, 'code' => 'manufacturer', 'is_general' => true],
            ['id' => 2, 'code' => 'country', 'is_general' => true],
            ['id' => 3, 'code' => 'available_sizes', 'is_general' => true],
        ]);
    }
}
