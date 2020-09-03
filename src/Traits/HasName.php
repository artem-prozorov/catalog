<?php

declare(strict_types=1);

namespace Catalog\Core\Traits;

use Catalog\Core\Contracts\AwareOfStateChange;

trait HasName
{
    protected string $name;

    /**
     * Returns name
     *
     * @access	public
     * @return	string|null
     */
    public function getName(): ?string
    {
        return $this->name ?? '';
    }

    /**
     * Set name
     *
     * @access	public
     * @param	string	$name
     * @return	self
     */
    public function setName(string $name)
    {
        $this->name = $name;
        
        if ($this instanceof AwareOfStateChange) {
            $this->stateChanged('name');
        }

        return $this;
    }
}
