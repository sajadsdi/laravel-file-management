<?php

namespace Sajadsdi\LaravelFileManagement\Repository;

use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\Model\File;
use Sajadsdi\LaravelRepository\Concerns\Crud\Crud;
use Sajadsdi\LaravelRepository\Repository;

class FileRepository extends Repository implements FileRepositoryInterface
{
    use Crud;

    /**
     * @return string
     */
    public function getModelName(): string
    {
        return File::class;
    }

    /**
     * @return array
     */
    public function getSearchable(): array
    {
        return ['type', "title", "name", "ext"];
    }

    /**
     * @return array
     */
    public function getFilterable(): array
    {
        return ["type", "ext", "path", "disk", "size"];
    }

    /**
     * @return array
     */
    public function getSortable(): array
    {
        return ["title", "name", "type", "ext", "path", "disk", "size", "created_at", "updated_at"];
    }

    public function create(array $data): array
    {
        return $this->query()->create($data)->makeVisible(["path", "disk"])?->toArray() ?? [];
    }

}
