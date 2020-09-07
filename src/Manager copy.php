<?php

declare(strict_types=1);

namespace Catalog\Core;

use Repositories\Core\RepositoryFactory;
use Catalog\Core\Properties\Values\PropertyValuesFactory;
use RuntimeException;
use OutOfBoundsException;

class Manager
{
    /**
     * Configuration of the Catalog library
     * Expects an array of the following structure:
     * [
     *     'catalog' => [
     *         $catalogId => $repositoryFactory,
     *     ],
     *     'property_value_factory' => $propertyValueFactory,
     * ]
     * 
     * @var array $config
     */
    protected static array $config;

    protected static array $resolved = [];

    /**
     * Returns Catalog with the provided ID of throws exception
     *
     * @access	public static
     * @param	int|string	$catalogId	
     * @return	Catalog
     */
    public static function getCatalogOrFail($catalogId): Catalog
    {
        if (empty(static::$config)) {
            throw new RuntimeException('Catalog is not configured');
        }

        if (! array_key_exists($catalogId, static::$config['catalog'])) {
            throw new OutOfBoundsException('No such catalog: ' . $catalogId);
        }

        if (empty(static::$resolved[$catalogId])) {
            static::initializeCatalog($catalogId);
        }

        return static::$resolved[$catalogId];
    }

    /**
     * Sets manager configuration
     *
     * @access	public static
     * @param	array	$config	
     * @return	void
     */
    public static function setConfig(array $config): void
    {
        static::$config = $config;
    }

    protected function initializeCatalog(string $catalogId): void
    {
        $repositoryFactory = static::$config['catalog'][$catalogId];

        if (is_string($repositoryFactory)) {
            throw new RuntimeException('Currently Manager does not support class names resolving');
        }


    }
}
