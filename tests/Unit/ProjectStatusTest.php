<?php

namespace Tests\Unit;

use App\Domain\ValueObjects\ProjectStatus;
use PHPUnit\Framework\TestCase;

class ProjectStatusTest extends TestCase
{
    public function test_from_with_invalid_value_can_throw_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ProjectStatus::from('not_a_status');
    }

    public function test_value_label_equals_and_to_string_can_work(): void
    {
        $s = ProjectStatus::from('working');
        $this->assertSame('working', $s->value());
        $this->assertSame('稼働中', $s->label());
        $this->assertTrue($s->equals(ProjectStatus::from('working')));
        $this->assertSame('working', (string) $s);
    }
}
