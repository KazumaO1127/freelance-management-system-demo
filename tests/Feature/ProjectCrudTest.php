<?php

namespace Tests\Feature;

use App\Application\ViewModels\ProjectViewModel;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    private function fakeCreateUseCase()
    {
        return new class
        {
            public function execute($dto)
            {
                return null;
            }
        };
    }

    private function fakeUpdateUseCase()
    {
        return new class
        {
            public function execute($dto)
            {
                return null;
            }
        };
    }

    private function fakeDeleteUseCase()
    {
        return new class
        {
            public function execute(int $id): void {}
        };
    }

    private function fakeGetUseCase(?array $primitives = null)
    {
        return new class($primitives)
        {
            private $p;
            public function __construct($p)
            {
                $this->p = $p;
            }
            public function execute(int $id)
            {
                if (! $this->p) {
                    return null;
                }

                return ProjectViewModel::fromPrimitives($this->p);
            }
        };
    }

    private function fakeListUseCase(array $items = [])
    {
        return new class($items)
        {
            private $items;
            public function __construct($items)
            {
                $this->items = $items;
            }
            public function execute(int $perPage = 10)
            {
                $collection = collect($this->items);

                return new \Illuminate\Pagination\LengthAwarePaginator($collection, $collection->count(), $perPage);
            }
        };
    }

    private function makeInMemoryRepository(array $items = [])
    {
        return new class($items) implements \App\Domain\Repositories\ProjectRepositoryInterface
        {
            private $items;
            public function __construct($items = [])
            {
                $this->items = $items;
            }

            public function save(\App\Domain\Models\Project $project): \App\Domain\Models\Project
            {
                return $project;
            }

            public function findById(int $id): ?\App\Domain\Models\Project
            {
                foreach ($this->items as $p) {
                    if (isset($p['id']) && (int) $p['id'] === $id) {
                        return \App\Domain\Models\Project::fromPrimitives($p);
                    }
                }

                return null;
            }

            public function paginate(int $perPage = 10): \Illuminate\Contracts\Pagination\Paginator
            {
                $domain = collect($this->items)->map(fn ($p) => \App\Domain\Models\Project::fromPrimitives($p));

                return new \Illuminate\Pagination\LengthAwarePaginator($domain, $domain->count(), $perPage);
            }

            public function delete(int $id): void
            {
                // noop
            }
        };
    }

    public function test_index_displays_projects(): void
    {
        $pr = [
            'id' => 1,
            'title' => 'My Project Title',
            'client_name' => 'Client A',
            'unit_price' => 1000,
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-05',
            'status' => 'working',
            'memo' => 'note',
        ];

        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([$pr]));

        $response = $this->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertSee('My Project Title');
        $response->assertSee('Client A');
    }

    public function test_create_displays_form(): void
    {
        $response = $this->get(route('projects.create'));

        $response->assertStatus(200);
        $response->assertSee('問い合わせ');
    }

    public function test_edit_shows_project_and_404_when_missing(): void
    {
        $primitives = [
            'id' => 10,
            'title' => 'Edit Me',
            'client_name' => 'Client E',
            'unit_price' => 1500,
            'status' => 'contact',
        ];

        // success case
        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([$primitives]));
        $res = $this->get(route('projects.edit', $primitives['id']));
        $res->assertStatus(200);
        $res->assertSee('Edit Me');

        // not found case
        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([]));
        $res2 = $this->get(route('projects.edit', 9999));
        $res2->assertStatus(404);
    }

    public function test_destroy_returns_404_when_missing(): void
    {
        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([]));

        $res = $this->delete(route('projects.destroy', 9999));
        $res->assertStatus(404);
    }

    public function test_store_creates_project(): void
    {
        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository());

        $data = [
            'title' => 'New Project',
            'client_name' => 'Client B',
            'unit_price' => 5000,
            'start_date' => '2026-02-01',
            'end_date' => '2026-02-10',
            'status' => 'contact',
            'memo' => 'created by test',
        ];

        $response = $this->post(route('projects.store'), $data);

        $response->assertRedirect(route('projects.index'));
    }

    public function test_store_validation_fails_can_return_errors(): void
    {
        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository());

        $data = [
            'title' => '',
            'client_name' => '',
            'unit_price' => -1,
            'status' => 'invalid_status',
        ];

        $response = $this->post(route('projects.store'), $data);

        $response->assertSessionHasErrors(['title', 'client_name', 'unit_price', 'status']);
    }

    public function test_update_updates_project(): void
    {
        $primitives = [
            'id' => 2,
            'title' => 'Old Title',
            'client_name' => 'Client C',
            'unit_price' => 1000,
            'status' => 'contact',
        ];

        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([$primitives]));

        $update = [
            'title' => 'Updated Title',
            'client_name' => 'Client C',
            'unit_price' => 2000,
            'status' => 'working',
        ];

        $response = $this->put(route('projects.update', $primitives['id']), $update);

        $response->assertRedirect(route('projects.index'));
    }

    public function test_destroy_deletes_project(): void
    {
        $primitives = [
            'id' => 3,
            'title' => 'To Delete',
            'client_name' => 'Client D',
            'unit_price' => 1000,
            'status' => 'contact',
        ];

        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([$primitives]));

        $response = $this->delete(route('projects.destroy', $primitives['id']));

        $response->assertRedirect(route('projects.index'));
    }
}
