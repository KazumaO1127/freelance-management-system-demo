<?php

namespace App\Domain\Repositories;

use App\Application\DTOs\ProjectDTO;
use Illuminate\Contracts\Pagination\Paginator;

interface ProjectRepositoryInterface
{
    public function save(ProjectDTO $project): void;

    public function findById(int $id): ?ProjectDTO;

    public function paginate(int $perPage = 10): Paginator;

    public function delete(int $id): void;
}
