<?php

declare(strict_types=1);

namespace Catalog\Core\Properties;

use Catalog\Core\Traits\HasId;
use Catalog\Core\Traits\HasName;
use Catalog\Core\Traits\HasCode;

class Property
{
    use HasId;
    use HasName;
    use HasCode;

    protected string $type = 'string';

    protected bool $multiple = false;

    protected bool $required = false;

    public function __construct(iterable $data = [])
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->code = $data['code'] ?? '';
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * Get the value of type
     *
     * @access	public
     * @return	string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
