<?php

namespace Tests\Unit\Application;

use App\Application\DTOs\ProjectCreateDTO;
use App\Application\UseCases\CreateProjectUseCase;
use App\Domain\Models\Project as DomainProject;
use Mockery;
use PHPUnit\Framework\TestCase;

class CreateProjectUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_execute_with_valid_dto_can_save_and_return_domain_project(): void
    {
        $data = [
            'title' => 'New Project',
            'client_name' => 'Client B',
            'unit_price' => 5000,
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-10',
            'status' => 'contact',
            'memo' => 'created by test',
            'user_id' => null,
        ];

        $dto = ProjectCreateDTO::fromArray($data);

        $repoMock = Mockery::mock(\App\Domain\Repositories\ProjectRepositoryInterface::class);

        $repoMock->shouldReceive('save')
            ->withArgs(function ($arg) use ($data) {
                if (! $arg instanceof DomainProject) {
                    return false;
                }

                $p = $arg->toPrimitives();

                return $p['title'] === $data['title']
                    && $p['client_name'] === $data['client_name']
                    && $p['unit_price'] === $data['unit_price'];
            })
            ->andReturnUsing(function ($arg) {
                return $arg; // return the domain object
            });

        $useCase = new CreateProjectUseCase($repoMock);

        $result = $useCase->execute($dto);

        $this->assertInstanceOf(DomainProject::class, $result);
        $this->assertSame('New Project', $result->title());
    }
}
