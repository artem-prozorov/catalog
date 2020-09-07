<?php

declare(strict_types=1);

namespace Catalog\Core;

use Repositories\Core\RepositoryFactory;
use Catalog\Core\Properties\Values\PropertyValuesFactory;
use Catalog\Core\Properties\PropertY;
use Catalog\Core\Entities\Element;
use Catalog\Core\Traits\HasCode;

class Catalog
{
    use HasCode;

    protected string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * makeElement.
     *
     * @access	public
     * @return	Element
     */
    public function makeElement(): Element
    {
        return new Element(['catalog_code' => $this->getCode()]);
    }

    /**
     * makeProperty.
     *
     * @access	public
     * @return	Property
     */
    public function makeProperty(): Property
    {
        return new Property(['catalog_code' => $this->getCode()]);
    }
}
