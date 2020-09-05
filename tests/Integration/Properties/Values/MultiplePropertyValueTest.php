<?php

namespace Catalog\Core\Tests\Integration\Properties\Values;

// use Mockery\Adapter\Phpunit\MockeryTestCase;
use Catalog\Core\Tests\BaseTestCase;
use Catalog\Core\Properties\Values\StringValue;
use Catalog\Core\Properties\Values\AbstractPropertyValue;
use Catalog\Core\Entities\Element;
use Catalog\Core\Properties\Property;
use Mockery;
use InvalidArgumentException;

use Catalog\Core\Properties\Values\MultiplePropertyValue;

class MultiplePropertyValueTest extends BaseTestCase
{
    /**
     * @test
     */
    public function multiple()
    {
        $stringValue = [
            'id' => 1,
            'element_id' => 1,
            'property_id' => 2,
            'value' => 'Some string',
        ];

        $property = new Property([
            'id' => 1,
            'code' => 'manufacturer',
            'name' => 'manufacturer',
        ], true);

        $element = new Element(['id' => 1]);

        $multipleString = new MultiplePropertyValue($property, $element, $stringValue);

        $multipleString->markInitialized();

        $this->assertEquals([1 => 'Some string'], $multipleString->__toString());
    }

    /**
     * @test
     */
    public function property_values_are_lazy_loaded()
    {
        $property = new Property([
            'id' => 1,
            'code' => 'country',
            'name' => 'country',
        ], true);

        $element = new Element(['id' => 1]);

        $multipleString = new MultiplePropertyValue($property, $element);

        $this->repositoryFactory->expectCallFor(AbstractPropertyValue::class);

        $this->repositoryFactory->loadFixtures(AbstractPropertyValue::class, [
            ['id' => 1, 'element_id' => 1, 'property_id' => 1, 'value' => 'Russia'],
            ['id' => 2, 'element_id' => 1, 'property_id' => 1, 'value' => 'China'],
        ]);

        $this->assertEquals([1 => 'Russia', 2 => 'China'], $multipleString->toArray());

        $this->repositoryFactory->assertExpectationsWereMet();
    }
}
