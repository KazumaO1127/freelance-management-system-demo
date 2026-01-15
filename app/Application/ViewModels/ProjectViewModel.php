<?php

namespace App\Application\ViewModels;

use App\Domain\ValueObjects\ProjectStatus;

final class ProjectViewModel
{
    public int $id = 0;
    public string $title = '';
    public string $client_name = '';
    public int $unit_price = 0;
    public ?\DateTimeImmutable $start_date = null;
    public ?\DateTimeImmutable $end_date = null;
    public string $status = '';
    public string $status_label = '';
    public ?string $memo = null;
    public ?int $user_id = null;

    public static function fromPrimitives(array $primitives): self
    {
        $vm = new self;
        $vm->id = $primitives['id'] ?? 0;
        $vm->title = $primitives['title'] ?? '';
        $vm->client_name = $primitives['client_name'] ?? '';
        $vm->unit_price = (int) ($primitives['unit_price'] ?? 0);
        $vm->start_date = ! empty($primitives['start_date']) ? new \DateTimeImmutable($primitives['start_date']) : null;
        $vm->end_date = ! empty($primitives['end_date']) ? new \DateTimeImmutable($primitives['end_date']) : null;
        $vm->status = $primitives['status'] ?? 'contact';
        $vm->memo = $primitives['memo'] ?? null;
        $vm->user_id = $primitives['user_id'] ?? null;
        $vm->status_label = self::labelForStatus($vm->status);

        return $vm;
    }

    private static function labelForStatus(string $status): string
    {
        $options = ProjectStatus::options();

        return $options[$status] ?? '未設定';
    }
}
