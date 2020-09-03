<?php

namespace Catalog\Core\Properties\Values;

use Catalog\Core\Properties\Property;
use Catalog\Core\Entities\Element;
use InvalidArgumentException;

class MultiplePropertyValue extends AbstractPropertyValue
{
    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * {@inheritDoc}
     */
    public function validate($data): void
    {
        if (empty($data['value'])) {
            return;
        }

        if (is_iterable($data)) {
            foreach ($data as $property) {
                $this->addValue($data);
            }

            return;
        }

        $this->addValue($data);
    }

    protected function addValue($data): void
    {
        // instanceof Single Value (not multiple)
        if ($data instanceof AbstractPropertyValue) {
            $this->value[$data->getId()] = $data;
        }
    }
}
