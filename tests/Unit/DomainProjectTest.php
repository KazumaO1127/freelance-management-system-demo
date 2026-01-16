<?php

namespace Tests\Unit;

use App\Domain\Models\Project;
use App\Domain\ValueObjects\ProjectStatus;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DomainProjectTest extends TestCase
{
    public function test_calculate_revenue_with_period(): void
    {
        $start = new DateTimeImmutable('2026-01-01');
        $end = new DateTimeImmutable('2026-01-05');
        $project = new Project(null, 't', 'c', 1000, $start, $end, ProjectStatus::from('working'), null, null);

        $this->assertSame(5000, $project->calculateRevenue());
    }

    public function test_calculate_revenue_without_period_returns_zero(): void
    {
        $project = new Project(null, 't', 'c', 1000, null, null, ProjectStatus::from('contact'), null, null);
        $this->assertSame(0, $project->calculateRevenue());
    }

    public function test_change_status_allows_forward_transition(): void
    {
        $project = new Project(null, 't', 'c', 1000, null, null, ProjectStatus::from('contact'), null, null);
        $project->changeStatus(ProjectStatus::from('negotiation'));
        $this->assertSame('negotiation', $project->toPrimitives()['status']);
    }

    public function test_change_status_disallows_backward(): void
    {
        $this->expectException(\DomainException::class);
        $project = new Project(null, 't', 'c', 1000, null, null, ProjectStatus::from('working'), null, null);
        $project->changeStatus(ProjectStatus::from('negotiation'));
    }
}
