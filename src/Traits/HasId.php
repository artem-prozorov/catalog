<?php

declare(strict_types=1);

namespace Catalog\Core\Traits;

use Catalog\Core\Contracts\AwareOfStateChange;

trait HasId
{
    protected int $id;

    /**
     * Returns ID
     *
     * @access	public
     * @return	int|null
     */
    public function getId(): ?int
    {
        return $this->id ?? 0;
    }

    /**
     * Set ID
     *
     * @access	public
     * @param	int	$id	
     * @return	self
     */
    public function setId(int $id)
    {
        $this->id = $id;

        if ($this instanceof AwareOfStateChange) {
            $this->stateChanged('name');
        }

        return $this;
    }
}
