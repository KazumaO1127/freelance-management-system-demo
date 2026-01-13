<?php

namespace App\Domain\ValueObjects;

final class ProjectStatus
{
    private const LABELS = [
        'contact'     => '問い合わせ',
        'negotiation' => '商談中',
        'contracted'  => '契約締結',
        'working'     => '稼働中',
        'completed'   => '完了',
    ];

    private function __construct(private string $value) {}

    public static function from(string $value): self
    {
        if (!in_array($value, array_keys(self::LABELS), true)) {
            throw new \InvalidArgumentException("Invalid project status: {$value}");
        }
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function label(): string
    {
        return self::LABELS[$this->value];
    }

    public static function options(): array
    {
        return self::LABELS;
    }

    public static function values(): array
    {
        return array_keys(self::LABELS);
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
