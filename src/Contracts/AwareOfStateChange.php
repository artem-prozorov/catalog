<?php

declare(strict_types=1);

namespace Catalog\Core\Contracts;

interface AwareOfStateChange
{
    /**
     * Run this method once some property is changed
     *
     * @access	public
     * @param	string	$fieldName
     * @return	void
     */
    public function stateChanged(string $fieldName): void;
}
