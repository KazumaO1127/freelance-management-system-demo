<?php

namespace App\Application\DTOs;

use App\Enums\ProjectStatus;

final class ProjectDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $title,
        public readonly string $client_name,
        public readonly int $unit_price,
        public readonly ?string $start_date,
        public readonly ?string $end_date,
        public readonly ProjectStatus $status,
        public readonly ?string $memo,
        public readonly ?int $user_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['title'],
            $data['client_name'],
            (int)($data['unit_price'] ?? 0),
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            ProjectStatus::from($data['status']),
            $data['memo'] ?? null,
            $data['user_id'] ?? null,
        );
    }
}
