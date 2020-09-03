<?php

declare(strict_types=1);

namespace Catalog\Core\Base;

use InvalidArgumentException;
use OutOfBoundsException;

abstract class AbstractFactory
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->validateConfig($config);

        $this->config = $config;
    }

    public function createSuitableObject(string $code, $data)
    {
        if (! array_key_exists($code, $this->config)) {
            throw new InvalidArgumentException();
        }

        if (is_string($this->config[$code])) {
            return $this->createFromRaw($data);
        }

        if (is_callable($this->config[$code])) {
            return $this->config[$code]($data);
        }

        throw new OutOfBoundsException();
    }

    abstract protected function validateConfig(array $config): void;

    abstract protected function createFromRaw($data);
}
