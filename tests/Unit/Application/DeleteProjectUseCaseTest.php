<?php

namespace Tests\Unit\Application;

use App\Application\UseCases\DeleteProjectUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;

class DeleteProjectUseCaseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_execute_with_id_can_call_repository_delete(): void
    {
        $repoMock = Mockery::mock(\App\Domain\Repositories\ProjectRepositoryInterface::class);
        $repoMock->shouldReceive('delete')->with(9)->once();

        $useCase = new DeleteProjectUseCase($repoMock);
        $useCase->execute(9);

        $this->addToAssertionCount(1);
    }
}
