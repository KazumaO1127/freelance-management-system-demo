<?php

namespace App\Application\UseCases;

use App\Domain\Repositories\ProjectRepositoryInterface;
use Illuminate\Contracts\Pagination\Paginator;

final class ListProjectsUseCase
{
    public function __construct(private ProjectRepositoryInterface $repo) {}

    public function execute(int $perPage = 10): Paginator
    {
        $p = $this->repo->paginate($perPage);

        $p->getCollection()->transform(function ($domainProject) {
            $pr = $domainProject->toPrimitives();

            return \App\Application\ViewModels\ProjectViewModel::fromPrimitives($pr);
        });

        return $p;
    }
}
