<?php

namespace Catalog\Core\Tests\Support;

use Repositories\Core\RepositoryFactory as BaseRepositoryFactory;
use Repositories\Core\Contracts\RepositoryInterface;
use RuntimeException;

class RepositoryFactory extends BaseRepositoryFactory
{
    protected bool $assertNoCalls = false;

    protected array $expectedCalls = [];

    public function getRepository(string $code): RepositoryInterface
    {
        if ($this->assertNoCalls) {
            throw new RuntimeException(
                'Repository ' . $code . ' is requested but no calls to repositories are expected'
            );
        }

        if (! empty($this->expectedCalls)) {
            if (! in_array($code, $this->expectedCalls)) {
                throw new RuntimeException(
                    'Expected calls for ' . implode(' ', $this->expectedCalls) . 
                    '. Unexpected call for ' . $code . ' received'
                );
            } else {
                unset($this->expectedCalls[array_search($code, $this->expectedCalls)]);
            }
        }

        return parent::getRepository($code);
    }

    public function assertNoCalls(): void
    {
        $this->assertNoCalls = true;
    }

    public function expectCallFor(string $code): void
    {
        $this->expectedCalls[] = $code;
    }

    public function assertExpectationsWereMet(): void
    {
        if (! empty($this->expectedCalls)) {
            throw new RuntimeException(
                'Expected repositories were not called: ' . implode(' ', $this->expectedCalls)
            );
        }
    }
}
