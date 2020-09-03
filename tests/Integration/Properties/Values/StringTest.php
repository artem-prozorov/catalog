<?php

namespace Catalog\Core\Tests\Integration\Properties\Values;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Catalog\Core\Properties\Values\StringValue;
use Catalog\Core\Entities\Element;
use Catalog\Core\Properties\Property;
use Mockery;
use InvalidArgumentException;

class StringTest extends MockeryTestCase
{
    /**
     * @test
     */
    public function string_property_turns_to_string()
    {
        $expected = [
            'id' => 1,
            'value' => 'Some string'
        ];

        $string = new StringValue(Mockery::mock(Property::class), Mockery::mock(Element::class), $expected);

        $this->assertEquals($expected['value'], $string->__toString());
    }

    /**
     * @test
     */
    public function exception_is_thrown_if_no_string_provided()
    {
        $unexpected = ['value' => 123];

        $this->expectException(InvalidArgumentException::class);

        $string = new StringValue(Mockery::mock(Property::class), Mockery::mock(Element::class), $unexpected);
    }
}
