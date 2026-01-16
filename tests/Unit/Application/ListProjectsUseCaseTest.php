<?php

namespace Tests\Unit\Application;

use App\Application\UseCases\ListProjectsUseCase;
use App\Application\ViewModels\ProjectViewModel;
use App\Domain\Models\Project as DomainProject;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class ListProjectsUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_execute_returns_paginator_of_viewmodels(): void
    {
        $items = [
            [
                'id' => 21,
                'title' => 'List One',
                'client_name' => 'Client L1',
                'unit_price' => 1000,
                'start_date' => '2026-05-01',
                'end_date' => '2026-05-02',
                'status' => 'contact',
                'memo' => null,
                'user_id' => null,
            ],
            [
                'id' => 22,
                'title' => 'List Two',
                'client_name' => 'Client L2',
                'unit_price' => 2000,
                'start_date' => '2026-06-01',
                'end_date' => '2026-06-03',
                'status' => 'working',
                'memo' => null,
                'user_id' => null,
            ],
        ];

        $domainCollection = collect(array_map(fn ($p) => DomainProject::fromPrimitives($p), $items));

        $paginator = new LengthAwarePaginator($domainCollection, $domainCollection->count(), 10);

        $repoMock = Mockery::mock(\App\Domain\Repositories\ProjectRepositoryInterface::class);
        $repoMock->shouldReceive('paginate')->with(10)->andReturn($paginator);

        $useCase = new ListProjectsUseCase($repoMock);
        $result = $useCase->execute(10);

        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\Paginator::class, $result);
        $this->assertCount(2, $result->getCollection());

        foreach ($result->getCollection() as $vm) {
            $this->assertInstanceOf(ProjectViewModel::class, $vm);
        }
    }
}
