<?php

namespace App\Application\UseCases;

use App\Application\DTOs\ProjectUpdateDTO;
use App\Domain\Models\Project as DomainProject;
use App\Domain\Repositories\ProjectRepositoryInterface;

final class UpdateProjectUseCase
{
    public function __construct(private ProjectRepositoryInterface $repo) {}

    public function execute(ProjectUpdateDTO $dto): DomainProject
    {
        $domain = DomainProject::fromPrimitives($dto->toPrimitives());

        return $this->repo->save($domain);
    }
}
