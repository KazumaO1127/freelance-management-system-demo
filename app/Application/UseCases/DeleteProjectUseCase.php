<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;

final class DeleteProjectUseCase
{
    public function __construct(private ProjectRepositoryInterface $repo)
    {
    }

    public function execute(int $id): void
    {
        $this->repo->delete($id);
    }
}
