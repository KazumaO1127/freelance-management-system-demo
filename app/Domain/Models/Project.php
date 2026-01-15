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
        // Domain invariants / validation
        if (trim($title) === '') {
            throw new \InvalidArgumentException('Project title must not be empty');
        }

        if ($unitPrice < 0) {
            throw new \InvalidArgumentException('Unit price must be non-negative');
        }

        if ($startDate !== null && $endDate !== null && $startDate > $endDate) {
            throw new \InvalidArgumentException('Start date must be before or equal to end date');
        }

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
            $data['title'] ?? '',
            $data['client_name'] ?? '',
            (int)($data['unit_price'] ?? 0),
            $start,
            $end,
            ProjectStatus::from($data['status'] ?? 'contact'),
            $data['memo'] ?? null,
            $data['user_id'] ?? null
        );
    }

    /**
    * プロジェクトの収益を計算します。
    *
    * デフォルトでは `unitPrice` を日額とみなし、
    * `日額 * 含む日数（開始日〜終了日、両端含む）` を返します。
    *
    * サポートされている単位:
    * - "daily": 開始日と終了日の間の含む日数を使用
    * - "monthly": 開始月から終了月までの含む月数をカウント
    *
    * 注: コンストラクタは既に開始日が終了日以下であることを（両方設定されている場合）検証します。
     *
     * @param string $unit One of 'daily'|'monthly'
     * @return int
     */
    public function calculateRevenue(string $unit = 'daily'): int
    {
        if ($this->startDate === null || $this->endDate === null) {
            return 0;
        }

        // Compute inclusive day count in a robust way using timestamps
        $start = $this->startDate->setTime(0, 0, 0);
        $end = $this->endDate->setTime(0, 0, 0);

        $diffSeconds = $end->getTimestamp() - $start->getTimestamp();
        $days = (int)floor($diffSeconds / 86400) + 1;
        if ($days < 0) {
            $days = 0;
        }

        if ($unit === 'daily') {
            return $this->unitPrice * $days;
        }

        if ($unit === 'monthly') {
            // Inclusive month count: e.g., 2026-01-15 -> 2026-03-14 = 3 months (Jan, Feb, Mar)
            $startYear = (int)$start->format('Y');
            $startMonth = (int)$start->format('n');
            $endYear = (int)$end->format('Y');
            $endMonth = (int)$end->format('n');

            $months = ($endYear - $startYear) * 12 + ($endMonth - $startMonth) + 1;
            if ($months < 0) {
                $months = 0;
            }

            return $this->unitPrice * $months;
        }

        throw new \InvalidArgumentException('Unknown unit for revenue calculation: ' . $unit);
    }

    public function changeStatus(ProjectStatus $newStatus): void
    {
        $order = ProjectStatus::values();

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
