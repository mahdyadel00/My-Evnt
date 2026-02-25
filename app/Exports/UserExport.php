<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

/**
 * Export class for Users.
 *
 * @package App\Exports
 */
class UserExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return User::with(['roles', 'city'])->orderBy('created_at', 'desc')->get();
    }

    /**
     * @param User $user
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->user_name,
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->phone ?? 'N/A',
            $user->city?->name ?? 'N/A',
            $user->roles->first()?->name ?? 'N/A',
            $user->type ?? 'N/A',
            $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '-',
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'User Name',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'City',
            'Role',
            'Type',
            'Created At',
        ];
    }
}

