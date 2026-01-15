<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Project as DomainProject;
use Illuminate\Contracts\Pagination\Paginator;

interface ProjectRepositoryInterface
{
    public function save(DomainProject $project): DomainProject;

    public function findById(int $id): ?DomainProject;

    public function paginate(int $perPage = 10): Paginator;

    public function delete(int $id): void;
}
