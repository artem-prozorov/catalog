<?php

declare(strict_types=1);

namespace Catalog\Core\Tests\Support;

use Repositories\Core\AbstractRepository;

class FakeRepository extends AbstractRepository
{
    protected function doGet($params): ?iterable
    {
        return [];
    }

    protected function doFirst(array $filter)
    {
        return null;
    }

    protected function doGetById(int $id, array $select = null)
    {
        return null;
    }

    protected function doGetByIdOrFail(int $id, array $select = null)
    {
        return [];
    }

    protected function doCreate(array $data)
    {

    }

    protected function doUpdate($model, array $data): void
    {

    }

    protected function doDelete($model): void
    {

    }

    protected function doExists(array $filter): bool
    {
        return true;
    }

    protected function doCount(array $filter = []): int
    {
        return 1;
    }

    protected function doOpenTransaction(): void
    {

    }

    protected function doCommitTransaction(): void
    {

    }

    protected function doRollbackTransaction(): void
    {

    }

    protected function doInsert(iterable $data): void
    {

    }
}
