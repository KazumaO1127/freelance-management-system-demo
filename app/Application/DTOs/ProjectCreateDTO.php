<?php

namespace App\Application\DTOs;

final class ProjectCreateDTO
{
    public function __construct(
        public string $title,
        public string $client_name,
        public int $unit_price = 0,
        public ?string $start_date = null,
        public ?string $end_date = null,
        public string $status = 'contact',
        public ?string $memo = null,
        public ?int $user_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['title'],
            $data['client_name'],
            (int) ($data['unit_price'] ?? 0),
            $data['start_date'] ?? null,
            $data['end_date'] ?? null,
            $data['status'] ?? 'contact',
            $data['memo'] ?? null,
            $data['user_id'] ?? null,
        );
    }

    public function toPrimitives(): array
    {
        return [
            'title' => $this->title,
            'client_name' => $this->client_name,
            'unit_price' => $this->unit_price,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'memo' => $this->memo,
            'user_id' => $this->user_id,
        ];
    }
}
