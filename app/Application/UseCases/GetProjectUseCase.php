<?php

namespace App\Application\UseCases;

use App\Application\ViewModels\ProjectViewModel;
use App\Domain\Repositories\ProjectRepositoryInterface;

final class GetProjectUseCase
{
    public function __construct(private ProjectRepositoryInterface $repo) {}

    public function execute(int $id): ?ProjectViewModel
    {
        $domain = $this->repo->findById($id);
        if (! $domain) {
            return null;
        }

        $pr = $domain->toPrimitives();

        return ProjectViewModel::fromPrimitives($pr);
    }
}
