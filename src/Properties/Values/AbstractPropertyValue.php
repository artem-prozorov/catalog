<?php

namespace Catalog\Core\Properties\Values;

use Catalog\Core\Properties\Property;
use Catalog\Core\Entities\Element;
use Catalog\Core\Traits\HasId;
use Catalog\Core\Catalog;
use Catalog\Core\Manager;
use InvalidArgumentException;

abstract class AbstractPropertyValue
{
    use HasId;

    protected Property $property;

    protected Element $element;

    protected bool $initialized = false;

    protected bool $readOnly = false;

    protected $value;

    /**
     * Returns string representation of the data
     */
    abstract public function __toString();

    /**
     * Validates provided data
     */
    abstract public function validate($data): void;

    public function __construct(Property $property, Element $element, iterable $data = null)
    {
        $this->validate($data);
        
        $this->property = $property;
        $this->element = $element;

        $this->setData($data);
    }

    /**
     * setData.
     *
     * @access	public
     * @param	iterable	$data	Default: null
     * @return	void
     */
    public function setData(iterable $data = null)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? null;
        $this->code = $data['code'] ?? null;

        $this->value = $data['value'] ?? null;

        $this->initialized = ($data !== null);
    }

    /**
     * Get the value of property
     *
     * @access	public
     * @return	Property
     */
    public function getProperty(): Property
    {
        return $this->property;
    }

    /**
     * Get the value of element
     *
     * @access	public
     * @return	Element
     */
    public function getElement(): Element
    {
        return $this->element;
    }

    /**
     * Get the value of data
     *
     * @access	public
     * @return	mixed
     */
    public function getValue()
    {
        if (! $this->isInitialized()) {
            $this->loadData();
        }

        return $this->value;
    }

    /**
     * Returns true if the current property value is initialized (the value is loaded from the data storage)
     *
     * @access	public
     * @return	bool
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }

    /**
     * Mark this propery value as initialized
     *
     * @access	public
     * @return	AbstractPropertyValue
     */
    public function markInitialized(): AbstractPropertyValue
    {
        $this->initialized = true;

        return $this;
    }

    protected function getRepositoryName(): string
    {
        return self::class;
    }

    protected function loadData(): void
    {
        if (empty($this->getId())) {
            if ($this->property->isMultiple()) {
                throw new InvalidArgumentException('Imposible to lazy load multiple property value');
            }

            $where = [
                'property_id' => $this->property->getId(),
                'element_id' => $this->element->getId(),
            ];
        } else {
            $where = ['id' => $this->getId()];
        }

        $raw = Manager::getRepositoryFactory()->getRepository($this->getRepositoryName())
            ->first($where);

        $this->setData($raw);
    }
}
