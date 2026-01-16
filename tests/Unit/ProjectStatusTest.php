<?php

namespace Tests\Unit;

use App\Domain\ValueObjects\ProjectStatus;
use PHPUnit\Framework\TestCase;

class ProjectStatusTest extends TestCase
{
    public function test_from_invalid_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ProjectStatus::from('not_a_status');
    }

    public function test_label_value_equals_and_tostring(): void
    {
        $s = ProjectStatus::from('working');
        $this->assertSame('working', $s->value());
        $this->assertSame('稼働中', $s->label());
        $this->assertTrue($s->equals(ProjectStatus::from('working')));
        $this->assertSame('working', (string) $s);
    }
}
