<?php

declare(strict_types=1);

namespace Catalog\Core\Collections;

use Catalog\Core\Properties\Values\AbstractPropertyValue;
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
        if ($property->isMultiple()) {
            // Initialize multiple property, a separate class MultiplePropertyValue
            // $this->loadedProperties[$code] = ..; //

            return;
        }

        $this->loadedProperties[$property->getCode()] = Catalog::getPropertyValuesFactory()
            ->createSuitableProperty(
                $property,
                $this->element,
                $data
            );
    }
}
