<?php

namespace Sajadsdi\LaravelFileManagement\Contracts;


interface FileRepositoryInterface
{
    public function read(string|int $id): array;

    public function index(string $search = null, string $filter = null, string $sort = null, int $perPage = 15): array;

    public function create(array $data): array;

    public function update(string|int $id, array $data): array;

    public function delete(string|int $id): bool;

    public function getById(string $id);

}
