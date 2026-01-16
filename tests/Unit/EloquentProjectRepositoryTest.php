<?php

namespace Tests\Unit;

use App\Domain\Models\Project as DomainProject;
use App\Infrastructure\Repositories\EloquentProjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentProjectRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_by_id_with_existing_id_can_return_domain_project(): void
    {
        $primitives = [
            'id' => null,
            'title' => 'Repo Title',
            'client_name' => 'Repo Client',
            'unit_price' => 2500,
            'start_date' => '2026-01-01',
            'end_date' => null,
            'status' => 'contact',
            'memo' => 'memo',
            'user_id' => null,
        ];

        $domain = DomainProject::fromPrimitives($primitives);

        $repo = new EloquentProjectRepository;
        $saved = $repo->save($domain);

        $this->assertNotNull($saved->id());

        $found = $repo->findById($saved->id());
        $this->assertInstanceOf(DomainProject::class, $found);
        $this->assertSame($saved->id(), $found->id());
    }

    public function test_delete_with_existing_id_can_call_destroy(): void
    {
        $domain = DomainProject::fromPrimitives([
            'id' => null,
            'title' => 'ToDelete',
            'client_name' => 'C',
            'unit_price' => 1000,
            'start_date' => '2026-01-01',
            'end_date' => null,
            'status' => 'contact',
            'memo' => null,
            'user_id' => null,
        ]);

        $repo = new EloquentProjectRepository;
        $saved = $repo->save($domain);

        $repo->delete($saved->id());
        $this->assertNull($repo->findById($saved->id()));
    }

    public function test_paginate_can_transform_models_to_domain(): void
    {
        $repo = new EloquentProjectRepository;

        for ($i = 1; $i <= 3; $i++) {
            $repo->save(DomainProject::fromPrimitives([
                'id' => null,
                'title' => "P{$i}",
                'client_name' => "C{$i}",
                'unit_price' => 1000 * $i,
                'start_date' => '2026-01-01',
                'end_date' => null,
                'status' => 'contact',
                'memo' => null,
                'user_id' => null,
            ]));
        }

        $p = $repo->paginate(10);

        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\Paginator::class, $p);
        $this->assertSame(3, $p->total());
        $this->assertInstanceOf(DomainProject::class, $p->items()[0]);
    }

    public function test_find_by_id_with_missing_id_can_return_null(): void
    {
        $repo = new EloquentProjectRepository;
        $this->assertNull($repo->findById(123456789));
    }

    public function test_save_with_existing_model_can_return_domain_project(): void
    {
        $repo = new EloquentProjectRepository;

        $domain = DomainProject::fromPrimitives([
            'id' => null,
            'title' => 'Updated',
            'client_name' => 'C',
            'unit_price' => 2000,
            'start_date' => '2026-01-01',
            'end_date' => null,
            'status' => 'working',
            'memo' => 'm',
            'user_id' => null,
        ]);

        $saved = $repo->save($domain);

        $this->assertInstanceOf(DomainProject::class, $saved);

        // Update and save again
        $updatedDomain = DomainProject::fromPrimitives([
            'id' => $saved->id(),
            'title' => 'Updated2',
            'client_name' => 'C2',
            'unit_price' => 2500,
            'start_date' => '2026-01-01',
            'end_date' => null,
            'status' => 'working',
            'memo' => 'm2',
            'user_id' => null,
        ]);

        $result = $repo->save($updatedDomain);

        $this->assertSame($saved->id(), $result->id());
        $this->assertSame('Updated2', $result->title());
    }
}
