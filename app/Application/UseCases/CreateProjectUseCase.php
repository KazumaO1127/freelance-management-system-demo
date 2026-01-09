<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ProjectDTO;
use App\Domain\Repositories\ProjectRepositoryInterface;

final class CreateProjectUseCase
{
    public function __construct(private ProjectRepositoryInterface $repo)
    {
    }

    public function execute(ProjectDTO $dto): void
    {
        $this->repo->save($dto);
    }
}
