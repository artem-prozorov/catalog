<?php

namespace Catalog\Core\Properties\Values;

use Catalog\Core\Properties\Property;
use Catalog\Core\Entities\Element;
use InvalidArgumentException;

class StringValue extends AbstractPropertyValue
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

        if (! is_string($data['value'])) {
            throw new InvalidArgumentException(
                'String value expects string, ' . gettype($data['value']) . ' is provided'
            );
        }
    }
}
