<?php

declare(strict_types=1);

namespace Catalog\Core\Traits;

use Catalog\Core\Contracts\AwareOfStateChange;
use Catalog\Core\Catalog;
use Catalog\Core\Manager;

trait HasCatalog
{
    /**
     * @var string $catalogCode
     */
    protected $catalogCode;

    protected Catalog $catalog;

    /**
     * Returns catalog code
     *
     * @access	public
     * @return	string
     */
    public function getCatalogCode(): string
    {
        return $this->catalogCode;
    }

    /**
     * Returns catalog of the current entity
     *
     * @access	public
     * @return	Catalog
     */
    public function getCatalog(): Catalog
    {
        if (empty($this->catalog)) {
            $this->catalog = Manager::getCatalogByCode($this->getCatalogCode());
        }

        return $this->catalog;
    }
}
