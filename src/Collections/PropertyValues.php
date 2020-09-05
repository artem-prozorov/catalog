<?php

declare(strict_types=1);

namespace Catalog\Core\Collections;

use Catalog\Core\Properties\Values\AbstractPropertyValue;
use Catalog\Core\Properties\Values\MultiplePropertyValue;
use Catalog\Core\Properties\Values\StringValue;
use Catalog\Core\Properties\Property;
use Catalog\Core\Properties\PropertyMap;
use Catalog\Core\Entities\Element;
use Catalog\Core\Catalog;

class PropertyValues
{
    protected Element $element;

    protected array $loadedProperties = [];

    public function __construct(Element $element)
    {
        $this->element = $element;
    }

    public function getByCode(string $code): ?AbstractPropertyValue
    {
        if (empty($this->loadedProperties[$code])) {
            $this->load([$code]);
        }

        return $this->loadedProperties[$code];
    }

    /**
     * Loads values of the given code
     *
     * @access	public
     * @param	array<string> $codes
     * @return	mixed
     */
    public function load(array $codes): PropertyValues
    {
        $toBeLoaded = [];

        $this->element->getPropertyMap()->load($codes);

        foreach ($codes as $code) {
            if (empty($this->loadedProperties[$code])) {
                $toBeLoaded[] = $this->element->getPropertyMap()->getProperty($code)->getId();
            }
        }

        if (! empty($toBeLoaded)) {
            $rows = Catalog::getRepositoryFactory()
                ->getRepository(AbstractPropertyValue::class)
                ->get([
                    'element_id' => $this->element->getId(),
                    'property_id' => $toBeLoaded,
                ]);

            foreach ($rows as $row) {
                $this->initValue(
                    $this->element->getPropertyMap()->getPropertyById($row['property_id']),
                    $row
                );
            }
        }

        return $this;
    }

    protected function initValue(Property $property, array $data = null): void
    {
        $value = Catalog::getPropertyValuesFactory()
            ->createSuitableProperty(
                $property,
                $this->element,
                $data
            );

        if ($property->isMultiple()) {
            $multipleValue = $this->getMultiplePropertyValue($property);

            $multipleValue->addValue($value);

            $this->loadedProperties[$property->getCode()] = $multipleValue;

            return;
        }

        $this->loadedProperties[$property->getCode()] = $value;
    }

    protected function getMultiplePropertyValue(Property $property): MultiplePropertyValue
    {
        if (empty($this->loadedProperties[$property->getCode()])) {
            return new MultiplePropertyValue($property, $this->element);
        }

        return $this->loadedProperties[$property->getCode()];
    }
}
