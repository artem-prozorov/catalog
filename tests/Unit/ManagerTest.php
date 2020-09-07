<?php

namespace Catalog\Core\Tests\Unit\Properties;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Catalog\Core\Manager;
use RuntimeException;

class ManagerTest extends MockeryTestCase
{
    /**
     * @test
     */
    public function catalog_code_is_passed_to_element()
    {
        $catalogCode = 'catalog_id';

        $catalog = Manager::getCatalogByCode($catalogCode);

        $element = $catalog->makeElement();

        $this->assertEquals($catalogCode, $element->getCatalogCode());
        $this->assertSame($catalog, $element->getCatalog());
    }
}
