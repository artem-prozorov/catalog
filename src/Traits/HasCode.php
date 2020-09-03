<?php

declare(strict_types=1);

namespace Catalog\Core\Traits;

use Catalog\Core\Contracts\AwareOfStateChange;

trait HasCode
{
    protected string $code;

    /**
     * Returns code
     *
     * @access	public
     * @return	string|null
     */
    public function getCode(): ?string
    {
        return $this->code ?? '';
    }

    /**
     * Set code
     *
     * @access	public
     * @param	string	$code
     * @return	self
     */
    public function setCode(string $code)
    {
        $this->code = $code;

        if ($this instanceof AwareOfStateChange) {
            $this->stateChanged('name');
        }

        return $this;
    }
}
