<?php

declare(strict_types=1);

namespace Catalog\Core\Entities;

use Catalog\Core\Traits\HasId;
use Catalog\Core\Traits\HasName;
use Catalog\Core\Traits\HasCode;
use Catalog\Core\Contracts\AwareOfStateChange;
use Catalog\Core\Collections\PropertyValues;
use Catalog\Core\Collections\Sections;
use Catalog\Core\Properties\Property;
use Catalog\Core\Properties\PropertyMap;
use Catalog\Core\Properties\Values\AbstractPropertyValue;
use Catalog\Core\Catalog;

class Element implements AwareOfStateChange
{
    use HasId;
    use HasName;
    use HasCode;

    protected bool $hasChanges;

    protected PropertyValues $propertyValues;

    protected Sections $sections;

    protected PropertyMap $map;

    public function __construct(iterable $data = [])
    {
        $this->id = $data['id'] ?? 0;
    }

    /**
     * {@inheritDoc}
     */
    public function stateChanged(string $fieldName): void
    {
        $this->hasChanges = true;
    }

    /**
     * Get the value of properties
     *
     * @access	public
     * @return	PropertyValues
     */
    public function getPropertyValues(): PropertyValues
    {
        if (empty($this->propertyValues)) {
            $this->propertyValues = new PropertyValues($this, $this->getPropertyMap());
        }

        return $this->propertyValues;
    }

    /**
     * Returns specified property value
     *
     * @access	public
     * @param	string	$code
     * @return	AbstractPropertyValue|null
     */
    public function getPropertyValue(string $code): ?AbstractPropertyValue
    {
        return $this->getPropertyValues()->getByCode($code);
    }

    /**
     * Returns property map
     *
     * @access	public
     * @return	PropertyMap
     */
    public function getPropertyMap(): PropertyMap
    {
        if (empty($this->map)) {
            $this->loadPropertyMap();
        }

        return $this->map;
    }

    /**
     * Load property values
     *
     * @access	public
     * @param	array	$codes	Default: null
     * @return	Element
     */
    public function loadPropertyValues(array $codes = null): Element
    {
        if (empty($codes)) {
            $codes = $this->getPropertyMap()->getCodes();
        }

        $this->getPropertyValues()->load($codes);

        return $this;
    }

    protected function loadPropertyMap(): void
    {
        // Load sections

        // Get section properties

        $raw = Catalog::getRepositoryFactory()
            ->getRepository(PropertyMap::class)
            ->get(['is_general' => true]);

        $this->map = new PropertyMap(array_column($raw, 'code'));
    }
}
