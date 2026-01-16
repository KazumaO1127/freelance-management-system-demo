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

    public function test_value_with_working_can_return_working(): void
    {
        $s = ProjectStatus::from('working');

        $this->assertSame('working', $s->value());
    }

    public function test_label_with_working_can_return_japanese_label(): void
    {
        $s = ProjectStatus::from('working');

        $this->assertSame('稼働中', $s->label());
    }

    public function test_equals_with_same_value_can_return_true(): void
    {
        $s = ProjectStatus::from('working');

        $this->assertTrue($s->equals(ProjectStatus::from('working')));
    }

    public function test_to_string_with_working_can_return_value_string(): void
    {
        $s = ProjectStatus::from('working');

        $this->assertSame('working', (string) $s);
    }
}
