<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ProjectCreateDTO;
use App\Domain\Models\Project as DomainProject;
use App\Domain\Repositories\ProjectRepositoryInterface;

final class CreateProjectUseCase
{
    public function __construct(private ProjectRepositoryInterface $repo)
    {
    }

    public function execute(ProjectCreateDTO $dto): DomainProject
    {
        $domain = DomainProject::fromPrimitives($dto->toPrimitives());
        return $this->repo->save($domain);
    }
}
