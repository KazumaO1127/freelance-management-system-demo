<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
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

    public function test_index_with_projects_can_display_projects(): void
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

    public function test_create_page_can_return_form_view(): void
    {
        $response = $this->get(route('projects.create'));

        $response->assertStatus(200);
        $response->assertSee('問い合わせ');
    }

    public function test_edit_with_project_can_show(): void
    {
        $primitives = [
            'id' => 10,
            'title' => 'Edit Me',
            'client_name' => 'Client E',
            'unit_price' => 1500,
            'status' => 'contact',
        ];

        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([$primitives]));

        $res = $this->get(route('projects.edit', $primitives['id']));

        $res->assertStatus(200);
        $res->assertSee('Edit Me');
    }

    public function test_edit_missing_can_return_404(): void
    {
        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([]));

        $res = $this->get(route('projects.edit', 9999));

        $res->assertStatus(404);
    }

    public function test_destroy_with_missing_can_return_404(): void
    {
        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([]));

        $res = $this->delete(route('projects.destroy', 9999));
        $res->assertStatus(404);
    }

    public function test_store_with_valid_data_can_create_project(): void
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

    public function test_update_with_valid_data_can_update_project(): void
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

    public function test_destroy_with_existing_project_can_delete_and_redirect(): void
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

    public function test_update_validation_fails_can_return_errors(): void
    {
        $primitives = [
            'id' => 4,
            'title' => 'Will Fail',
            'client_name' => 'Client X',
            'unit_price' => 1000,
            'status' => 'contact',
        ];

        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([$primitives]));

        $data = [
            'title' => '',
            'client_name' => '',
            'unit_price' => -10,
            'status' => 'not_a_status',
        ];

        $response = $this->put(route('projects.update', $primitives['id']), $data);

        $response->assertSessionHasErrors(['title', 'client_name', 'unit_price', 'status']);
    }

    public function test_store_with_invalid_date_format_and_order_returns_errors(): void
    {
        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository());

        // invalid format
        $data1 = [
            'title' => 'Dated',
            'client_name' => 'Client D',
            'unit_price' => 1000,
            'start_date' => 'not-a-date',
            'end_date' => '2026-02-02',
            'status' => 'contact',
        ];

        $res1 = $this->post(route('projects.store'), $data1);
        $res1->assertSessionHasErrors(['start_date']);

        // end_date before start_date
        $data2 = [
            'title' => 'Bad Order',
            'client_name' => 'Client D',
            'unit_price' => 1000,
            'start_date' => '2026-02-10',
            'end_date' => '2026-02-01',
            'status' => 'contact',
        ];

        $res2 = $this->post(route('projects.store'), $data2);
        $res2->assertSessionHasErrors(['end_date']);
    }

    public function test_update_with_invalid_date_format_and_order_returns_errors(): void
    {
        $primitives = [
            'id' => 5,
            'title' => 'Date Update',
            'client_name' => 'Client Y',
            'unit_price' => 1000,
            'status' => 'contact',
        ];

        $this->app->bind(\App\Domain\Repositories\ProjectRepositoryInterface::class, fn () => $this->makeInMemoryRepository([$primitives]));

        // invalid format
        $data1 = [
            'title' => 'Dated',
            'client_name' => 'Client Y',
            'unit_price' => 1000,
            'start_date' => '32-99-99',
            'end_date' => '2026-02-02',
            'status' => 'contact',
        ];

        $res1 = $this->put(route('projects.update', $primitives['id']), $data1);
        $res1->assertSessionHasErrors(['start_date']);

        // end_date before start_date
        $data2 = [
            'title' => 'Bad Order',
            'client_name' => 'Client Y',
            'unit_price' => 1000,
            'start_date' => '2026-03-10',
            'end_date' => '2026-03-01',
            'status' => 'contact',
        ];

        $res2 = $this->put(route('projects.update', $primitives['id']), $data2);
        $res2->assertSessionHasErrors(['end_date']);
    }
}
