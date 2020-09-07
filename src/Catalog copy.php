<?php

declare(strict_types=1);

namespace Catalog\Core;

use Repositories\Core\RepositoryFactory;
use Catalog\Core\Properties\Values\PropertyValuesFactory;
use Catalog\Core\Properties\PropertY;
use Catalog\Core\Entities\Element;
use Catalog\Core\Traits\HasId;

class Catalog
{
    use HasId;

    protected static RepositoryFactory $repositoryFactory;

    protected static PropertyValuesFactory $propertyValueFactory;

    public function __construct()
    {

    }

    public function makeElement(): Element
    {
        return new Element();
    }

    public function makeProperty(): Property
    {
        return new Property();
    }
}
