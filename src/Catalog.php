<?php

declare(strict_types=1);

namespace Catalog\Core;

use Repositories\Core\RepositoryFactory;
use Catalog\Core\Properties\Values\PropertyValuesFactory;

class Catalog
{
    protected static RepositoryFactory $repositoryFactory;

    protected static PropertyValuesFactory $propertyValueFactory;

    public static function getRepositoryFactory(): RepositoryFactory
    {
        if (empty(static::$repositoryFactory)) {
            throw new \RuntimeException('Repository factory is not configured');
        }

        return static::$repositoryFactory;
    }

    public static function setRepositoryFactory(RepositoryFactory $factory)
    {
        static::$repositoryFactory = $factory;
    }

    public static function getPropertyValuesFactory(): PropertyValuesFactory
    {
        if (empty(static::$propertyValueFactory)) {
            throw new \RuntimeException('Property value factory is not configured');
        }

        return static::$propertyValueFactory;
    }

    public static function setPropertyValuesFactory(PropertyValuesFactory $factory)
    {
        static::$propertyValueFactory = $factory;
    }
}
