<?php

namespace Tests\Unit;

use App\Domain\Models\Project;
use App\Domain\ValueObjects\ProjectStatus;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DomainProjectTest extends TestCase
{
    public function test_calculate_revenue_with_period_can_return_total_revenue(): void
    {
        $start = new DateTimeImmutable('2026-01-01');
        $end = new DateTimeImmutable('2026-01-05');
        $project = new Project(null, 't', 'c', 1000, $start, $end, ProjectStatus::from('working'), null, null);

        $this->assertSame(5000, $project->calculateRevenue());
    }

    public function test_calculate_revenue_without_period_can_return_zero(): void
    {
        $project = new Project(null, 't', 'c', 1000, null, null, ProjectStatus::from('contact'), null, null);
        $this->assertSame(0, $project->calculateRevenue());
    }

    public function test_change_status_forward_transition_can_succeed(): void
    {
        $project = new Project(null, 't', 'c', 1000, null, null, ProjectStatus::from('contact'), null, null);
        $project->changeStatus(ProjectStatus::from('negotiation'));
        $this->assertSame('negotiation', $project->toPrimitives()['status']);
    }

    public function test_change_status_backward_transition_can_throw_exception(): void
    {
        $this->expectException(\DomainException::class);
        $project = new Project(null, 't', 'c', 1000, null, null, ProjectStatus::from('working'), null, null);
        $project->changeStatus(ProjectStatus::from('negotiation'));
    }
}
