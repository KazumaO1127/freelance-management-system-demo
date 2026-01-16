<?php

namespace Tests\Unit\Application;

use App\Application\UseCases\GetProjectUseCase;
use App\Application\ViewModels\ProjectViewModel;
use App\Domain\Models\Project as DomainProject;
use Mockery;
use PHPUnit\Framework\TestCase;

class GetProjectUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_execute_with_existing_project_returns_viewmodel(): void
    {
        $primitives = [
            'id' => 11,
            'title' => 'Fetch Me',
            'client_name' => 'Client X',
            'unit_price' => 1200,
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-05',
            'status' => 'working',
            'memo' => 'memo',
            'user_id' => null,
        ];

        $domain = DomainProject::fromPrimitives($primitives);

        $repoMock = Mockery::mock(\App\Domain\Repositories\ProjectRepositoryInterface::class);
        $repoMock->shouldReceive('findById')->with(11)->andReturn($domain);

        $useCase = new GetProjectUseCase($repoMock);

        $result = $useCase->execute(11);

        $this->assertInstanceOf(ProjectViewModel::class, $result);
        $this->assertSame('Fetch Me', $result->title);
        $this->assertSame('Client X', $result->client_name);
    }

    public function test_execute_with_missing_project_returns_null(): void
    {
        $repoMock = Mockery::mock(\App\Domain\Repositories\ProjectRepositoryInterface::class);
        $repoMock->shouldReceive('findById')->with(999)->andReturnNull();

        $useCase = new GetProjectUseCase($repoMock);

        $result = $useCase->execute(999);

        $this->assertNull($result);
    }
}
