<?php

namespace Tests\Unit;

use App\Domain\Models\Project as DomainProject;
use App\Infrastructure\Repositories\EloquentProjectRepository;
use DateTimeImmutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class EloquentProjectRepositoryTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_find_by_id_maps_to_domain(): void
    {
        $m = new \stdClass;
        $m->id = 42;
        $m->title = 'Repo Title';
        $m->client_name = 'Repo Client';
        $m->unit_price = 2500;
        $m->start_date = new DateTimeImmutable('2026-01-01');
        $m->end_date = null;
        $m->status = 'contact';
        $m->memo = 'memo';
        $m->user_id = null;

        $mock = Mockery::mock('alias:App\\Models\\Project');
        $mock->shouldReceive('find')->with(42)->andReturn($m);

        $repo = new EloquentProjectRepository;
        $domain = $repo->findById(42);

        $this->assertInstanceOf(DomainProject::class, $domain);
        $this->assertSame(42, $domain->id());
    }

    public function test_delete_calls_destroy(): void
    {
        $mock = Mockery::mock('alias:App\\Models\\Project');
        $mock->shouldReceive('destroy')->with(99)->once();

        $repo = new EloquentProjectRepository;
        $repo->delete(99);

        $this->addToAssertionCount(1);
    }

    public function test_paginate_transforms_models_to_domain(): void
    {
        $m = new \stdClass;
        $m->id = 5;
        $m->title = 'P1';
        $m->client_name = 'C1';
        $m->unit_price = 1000;
        $m->start_date = new DateTimeImmutable('2026-01-01');
        $m->end_date = null;
        $m->status = 'contact';
        $m->memo = null;
        $m->user_id = null;

        $collection = collect([$m]);
        $paginator = new LengthAwarePaginator($collection, 1, 10);

        $orderMock = Mockery::mock();
        $orderMock->shouldReceive('paginate')->with(10)->andReturn($paginator);

        $mock = Mockery::mock('alias:App\\Models\\Project');
        $mock->shouldReceive('orderBy')->with('created_at', 'desc')->andReturn($orderMock);

        $repo = new EloquentProjectRepository;
        $p = $repo->paginate(10);

        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\Paginator::class, $p);
        $this->assertSame(1, $p->total());
    }

    public function test_find_by_id_returns_null_when_not_found(): void
    {
        $mock = Mockery::mock('alias:App\\Models\\Project');
        $mock->shouldReceive('find')->with(123)->andReturn(null);

        $repo = new EloquentProjectRepository;
        $this->assertNull($repo->findById(123));
    }

    public function test_save_updates_existing_model_and_returns_domain(): void
    {
        $primitives = [
            'id' => 7,
            'title' => 'Updated',
            'client_name' => 'C',
            'unit_price' => 2000,
            'start_date' => '2026-01-01',
            'end_date' => null,
            'status' => 'working',
            'memo' => 'm',
            'user_id' => null,
        ];

        $domain = DomainProject::fromPrimitives($primitives);

        $modelInstance = new class
        {
            public $id = 7;
            public $title;
            public $client_name;
            public $unit_price;
            public $start_date;
            public $end_date;
            public $status;
            public $memo;
            public $user_id;

            public function fill($data)
            {
                foreach ($data as $k => $v) {
                    $this->$k = $v;
                }
            }

            public function save() {}
        };

        $saved = new \stdClass;
        $saved->id = 7;
        $saved->title = 'Updated';
        $saved->client_name = 'C';
        $saved->unit_price = 2000;
        $saved->start_date = new DateTimeImmutable('2026-01-01');
        $saved->end_date = null;
        $saved->status = 'working';
        $saved->memo = 'm';
        $saved->user_id = null;

        $mock = Mockery::mock('alias:App\\Models\\Project');
        $mock->shouldReceive('find')->with(7)->andReturn($modelInstance, $saved);

        $repo = new EloquentProjectRepository;
        $result = $repo->save($domain);

        $this->assertInstanceOf(DomainProject::class, $result);
        $this->assertSame(7, $result->id());
        $this->assertSame('Updated', $result->title());
    }
}
