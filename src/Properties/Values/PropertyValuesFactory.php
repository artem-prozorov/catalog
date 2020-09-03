<?php

declare(strict_types=1);

namespace Catalog\Core\Properties\Values;

use Catalog\Core\Properties\Property;
use Catalog\Core\Entities\Element;
use InvalidArgumentException;
use OutOfBoundsException;

class PropertyValuesFactory
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->validateConfig($config);

        $this->config = $config;
    }

    /**
     * Create suitable property
     *
     * @access	public
     * @param	Property	$property
     * @param	Element 	$element
     * @param	mixed   	$data    	Default: null
     * @return	AbstractPropertyValue
     */
    public function createSuitableProperty(Property $property, Element $element, $data = null): AbstractPropertyValue
    {
        $type = $property->getType();

        if (! array_key_exists($type, $this->config)) {
            throw new InvalidArgumentException('Unknown property value type: ' . $type);
        }

        if (is_string($this->config[$type])) {
            return new $this->config[$type]($property, $element, $data);
        }

        if (is_callable($this->config[$type])) {
            return $this->config[$type]($data);
        }

        throw new OutOfBoundsException('No suitable config for property value type ' . $type);
    }

    protected function validateConfig(array $config): void
    {

    }
}
