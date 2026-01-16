<?php

namespace Tests\Unit\Application;

use App\Application\DTOs\ProjectUpdateDTO;
use App\Application\UseCases\UpdateProjectUseCase;
use App\Domain\Models\Project as DomainProject;
use Mockery;
use PHPUnit\Framework\TestCase;

class UpdateProjectUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_execute_with_valid_dto_can_save_and_return_domain_project(): void
    {
        $data = [
            'id' => 5,
            'title' => 'Updated Project',
            'client_name' => 'Client U',
            'unit_price' => 2000,
            'start_date' => '2026-03-01',
            'end_date' => '2026-03-10',
            'status' => 'working',
            'memo' => 'updated',
            'user_id' => null,
        ];

        $dto = ProjectUpdateDTO::fromArray($data);

        $repoMock = Mockery::mock(\App\Domain\Repositories\ProjectRepositoryInterface::class);

        $repoMock->shouldReceive('save')
            ->withArgs(function ($arg) use ($data) {
                if (! $arg instanceof DomainProject) {
                    return false;
                }
                $p = $arg->toPrimitives();

                return $p['id'] === $data['id'] && $p['title'] === $data['title'];
            })
            ->andReturnUsing(fn ($arg) => $arg);

        $useCase = new UpdateProjectUseCase($repoMock);

        $result = $useCase->execute($dto);

        $this->assertInstanceOf(DomainProject::class, $result);
        $this->assertSame(5, $result->id());
        $this->assertSame('Updated Project', $result->title());
    }
}
