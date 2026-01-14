<?php

namespace App\Domain\Models;

use DateTimeImmutable;
use App\Domain\ValueObjects\ProjectStatus;

final class Project
{
    private ?int $id;
    private string $title;
    private string $clientName;
    private int $unitPrice;
    private ?DateTimeImmutable $startDate;
    private ?DateTimeImmutable $endDate;
    private ProjectStatus $status;
    private ?string $memo;
    private ?int $userId;

    public function __construct(
        ?int $id,
        string $title,
        string $clientName,
        int $unitPrice,
        ?DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        ProjectStatus $status,
        ?string $memo,
        ?int $userId
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->clientName = $clientName;
        $this->unitPrice = $unitPrice;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->memo = $memo;
        $this->userId = $userId;
    }

    public static function fromPrimitives(array $data): self
    {
        $start = !empty($data['start_date']) ? new DateTimeImmutable($data['start_date']) : null;
        $end = !empty($data['end_date']) ? new DateTimeImmutable($data['end_date']) : null;

        return new self(
            $data['id'] ?? null,
            $data['title'],
            $data['client_name'],
            (int)($data['unit_price'] ?? 0),
            $start,
            $end,
            ProjectStatus::from($data['status'] ?? 'contact'),
            $data['memo'] ?? null,
            $data['user_id'] ?? null,
        );
    }

    public function calculateRevenue(): int
    {
        if ($this->startDate === null || $this->endDate === null) {
            return 0;
        }
        $days = $this->endDate->diff($this->startDate)->days + 1;
        return $this->unitPrice * $days;
    }

    public function changeStatus(ProjectStatus $newStatus): void
    {
        $order = ['contact', 'negotiation', 'contracted', 'working', 'completed'];

        $currentIndex = array_search($this->status->value(), $order, true);
        $newIndex = array_search($newStatus->value(), $order, true);

        if ($currentIndex === false || $newIndex === false) {
            throw new \DomainException('Unknown status value');
        }

        if ($newIndex < $currentIndex) {
            throw new \DomainException('Invalid status transition: backward transition is not allowed');
        }

        $this->status = $newStatus;
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'client_name' => $this->clientName,
            'unit_price' => $this->unitPrice,
            'start_date' => $this->startDate?->format('Y-m-d'),
            'end_date' => $this->endDate?->format('Y-m-d'),
                'status' => $this->status->value(),
            'memo' => $this->memo,
            'user_id' => $this->userId,
        ];
    }

    // getters used by callers
    public function id(): ?int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }
}
