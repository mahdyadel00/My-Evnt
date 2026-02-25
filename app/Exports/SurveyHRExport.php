<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\FromServayHR;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Export class for Survey Forms.
 *
 * @package App\Exports
 */
class SurveyHRExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison, Responsable
{
    /**
     * @var array
     */
    protected array $filters;

    /**
     * @var string
     */
    private $fileName = 'survey_forms.xlsx';

    /**
     * SurveyExport constructor.
     * @param array $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $query = FromServayHR::with('event');

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhereHas('event', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%$search%")
                            ->orWhere('id', $search);
                    });
            });
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['session_type'])) {
            $query->where('session_type', $this->filters['session_type']);
        }
        if (!empty($this->filters['from'])) {
            $query->whereDate('created_at', '>=', $this->filters['from']);
        }
        if (!empty($this->filters['to'])) {
            $query->whereDate('created_at', '<=', $this->filters['to']);
        }

        return $query->orderByDesc('created_at')->get();
    }

    /**
     * @param mixed $survey
     * @return array
     */
    public function map($survey): array
    {
        return [
            $survey->event?->name ?? '-',
            $survey->first_name . ' ' . $survey->last_name,
            $survey->email,
            $survey->phone,
            $survey->company_name,
            $survey->position,
            $survey->status,
            $survey->created_at ? $survey->created_at->format('Y-m-d') : '-',
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Event Name',
            'Full Name',
            'Email',
            'Phone',
            'Company Name',
            'Position',
            'Status',
            'Date',
        ];
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * Create an HTTP response for the export.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function toResponse($request)
    {
        return Excel::download(new SurveyHRExport($request->all()), $this->getFileName());
    }
}
