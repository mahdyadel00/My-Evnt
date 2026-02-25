<?php

declare(strict_types=1);

namespace App\Imports;

use App\Models\User;
use App\Models\City;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class UserImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnFailure
{
    use SkipsFailures;

    /**
     * @var array<int, string>
     */
    private array $errors = [];

    /**
     * @var int
     */
    private int $successCount = 0;

    /**
     * @param array<string, mixed> $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row): ?\Illuminate\Database\Eloquent\Model
    {
        // Skip empty rows
        if (empty($row['email']) && empty($row['phone'])) {
            return null;
        }

        // Check for duplicate email
        if (!empty($row['email']) && User::where('email', $row['email'])->exists()) {
            $this->errors[] = "Row with email '{$row['email']}' already exists. Skipping.";
            return null;
        }

    
        $cityId = null;


        if (!empty($row['city'])) {
            $city = City::where('name', 'like', '%' . trim($row['city']) . '%')
                ->first();
            $cityId = $city?->id;
        }

        // Generate password if not provided
        $password = !empty($row['password']) 
            ? Hash::make($row['password']) 
            : Hash::make('password123'); // Default password

        return new User([
            'first_name'                    => $row['name'] ?? null,
            'last_name'                     => $row['last_name'] ?? null,
            'user_name'                     => $row['name'] ?? null,
            'phone'                         => $row['phone'] ?? null,
            'type'                          => 'Film Square',
        
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'first_name'                    => ['nullable', 'string', 'max:255'],
            'last_name'                     => ['nullable', 'string', 'max:255'],
            'email'                         => ['nullable', 'email', 'max:255'],
            'phone'                         => ['nullable', 'string', 'max:255'],
            'middle_name'                   => ['nullable', 'string', 'max:255'],
            'user_name'                     => ['nullable', 'string', 'max:255'],
            'password'                      => ['nullable', 'string', 'min:8'],
            'city'                          => ['nullable', 'string', 'exists:cities,name'],
            'about'                         => ['nullable', 'string'],
            'address'                       => ['nullable', 'string'],
            'birth_date'                    => ['nullable', 'date'],
            'is_active'                     => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function customValidationMessages(): array
    {
        return [
            'first_name.required'           => 'First name is required',
            'last_name.required'            => 'Last name is required',
            'email.email'                   => 'Email must be a valid email address',
            'email.unique'                  => 'Email must be unique',
            'phone.unique'                  => 'Phone must be unique',
            'user_name.unique'              => 'User name must be unique',
            'city.exists'                   => 'City must exist',
            'birth_date.date'               => 'Birth date must be a valid date',
            'is_active.boolean'             => 'Is active must be a boolean',
            'password.min'                  => 'Password must be at least 8 characters',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * @param Failure ...$failures
     * @return void
     */
    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
        }
    }

    /**
     * @return array<int, string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return int
     */
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    /**
     * @param int $count
     * @return void
     */
    public function setSuccessCount(int $count): void
    {
        $this->successCount = $count;
    }
}

