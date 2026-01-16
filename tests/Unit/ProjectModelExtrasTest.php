<?php

namespace Tests\Unit;

use App\Domain\Models\Project;
use App\Domain\ValueObjects\ProjectStatus;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ProjectModelExtrasTest extends TestCase
{
    public function test_constructor_validations_empty_title_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Project(null, '', 'c', 1000, null, null, ProjectStatus::from('contact'), null, null);
    }

    public function test_constructor_negative_unit_price_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Project(null, 't', 'c', -10, null, null, ProjectStatus::from('contact'), null, null);
    }

    public function test_constructor_start_after_end_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $start = new DateTimeImmutable('2026-02-10');
        $end = new DateTimeImmutable('2026-02-01');
        new Project(null, 't', 'c', 1000, $start, $end, ProjectStatus::from('contact'), null, null);
    }

    public function test_calculate_revenue_monthly(): void
    {
        $start = new DateTimeImmutable('2026-01-15');
        $end = new DateTimeImmutable('2026-03-14');
        $project = new Project(null, 't', 'c', 1000, $start, $end, ProjectStatus::from('working'), null, null);

        // Jan, Feb, Mar => 3 months
        $this->assertSame(3000, $project->calculateRevenue('monthly'));
    }

    public function test_calculate_revenue_unknown_unit_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $start = new DateTimeImmutable('2026-01-01');
        $end = new DateTimeImmutable('2026-01-05');
        $project = new Project(null, 't', 'c', 1000, $start, $end, ProjectStatus::from('working'), null, null);

        $project->calculateRevenue('yearly');
    }

    public function test_to_primitives_and_getters(): void
    {
        $start = new DateTimeImmutable('2026-01-01');
        $end = new DateTimeImmutable('2026-01-01');
        $project = new Project(7, 'Title X', 'Client X', 2000, $start, $end, ProjectStatus::from('contact'), 'note', 12);

        $pr = $project->toPrimitives();
        $this->assertSame(7, $project->id());
        $this->assertSame('Title X', $project->title());
        $this->assertSame('Title X', $pr['title']);
        $this->assertSame('2026-01-01', $pr['start_date']);
        $this->assertSame(2000, $pr['unit_price']);
    }
}
