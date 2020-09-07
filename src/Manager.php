<?php

declare(strict_types=1);

namespace Catalog\Core;

use Repositories\Core\RepositoryFactory;
use Catalog\Core\Properties\Values\PropertyValuesFactory;

class Manager
{
    protected static RepositoryFactory $repositoryFactory;

    protected static PropertyValuesFactory $propertyValueFactory;

    protected static array $resolved = [];

    /**
     * getRepositoryFactory.
     *
     * @access	public static
     * @return	RepositoryFactory
     */
    public static function getRepositoryFactory(): RepositoryFactory
    {
        if (empty(static::$repositoryFactory)) {
            throw new \RuntimeException('Repository factory is not configured');
        }

        return static::$repositoryFactory;
    }

    /**
     * setRepositoryFactory.
     *
     * @access	public static
     * @param	PropertyValuesFactory	$factory
     * @return	void
     */
    public static function setRepositoryFactory(RepositoryFactory $factory)
    {
        static::$repositoryFactory = $factory;
    }

    /**
     * getPropertyValuesFactory.
     *
     * @access	public static
     * @return	PropertyValuesFactory
     */
    public static function getPropertyValuesFactory(): PropertyValuesFactory
    {
        if (empty(static::$propertyValueFactory)) {
            throw new \RuntimeException('Property value factory is not configured');
        }

        return static::$propertyValueFactory;
    }

    /**
     * setPropertyValuesFactory.
     *
     * @access	public static
     * @param	PropertyValuesFactory	$factory
     * @return	void
     */
    public static function setPropertyValuesFactory(PropertyValuesFactory $factory): void
    {
        static::$propertyValueFactory = $factory;
    }

    /**
     * Returns Catalog by code or throws an exception
     *
     * @access	public static
     * @param	string	$code
     * @return	Catalog
     */
    public static function getCatalogByCode(string $code): Catalog
    {
        if (empty(static::$resolved[$code])) {
            static::$resolved[$code] = new Catalog($code);
        }

        return static::$resolved[$code];
    }
}
