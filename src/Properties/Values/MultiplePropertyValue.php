<?php

namespace Catalog\Core\Properties\Values;

use Catalog\Core\Properties\Property;
use Catalog\Core\Entities\Element;
use Catalog\Core\Manager;
use InvalidArgumentException;

class MultiplePropertyValue extends AbstractPropertyValue
{
    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }

    /**
     * {@inheritDoc}
     */
    public function validate($data): void
    {
        if (empty($data['value'])) {
            return;
        }
    }

    /**
     * Adds a value
     *
     * @access	public
     * @param	mixed	$data
     * @return	MultiplePropertyValue
     */
    public function addValue($data): MultiplePropertyValue
    {
        if ($data instanceof AbstractPropertyValue) {
            $this->value[$data->getId()] = $data;
        }

        return $this;
    }

    /**
     * toArray
     *
     * @access	public
     * @return	array
     */
    public function toArray(): array
    {
        if (empty($this->value)) {
            $this->loadData();
        }

        $data = [];

        foreach ($this->value as $value) {
            $data[$value->getId()] = (string) $value;
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function setData(iterable $data = null)
    {
        if (is_iterable($data)) {
            $data = Manager::getPropertyValuesFactory()
                ->createSuitableProperty(
                    $this->property,
                    $this->element,
                    $data
                );

            $data->markInitialized();
        }

        $this->addValue($data);
    }

    protected function loadData(): void
    {
        $where = [
            'property_id' => $this->property->getId(),
            'element_id' => $this->element->getId(),
        ];

        $rows = Manager::getRepositoryFactory()->getRepository($this->getRepositoryName())
            ->get($where);

        foreach ($rows as $row) {
            $value = Manager::getPropertyValuesFactory()
                ->createSuitableProperty(
                    $this->property,
                    $this->element,
                    $row
                );

            $this->addValue($value);
        }

        $this->markInitialized();
    }
}
