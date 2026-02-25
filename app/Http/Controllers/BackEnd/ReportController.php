<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Event;
use App\Models\City;
use App\Models\EventCategory;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        $cities = City::all();
        $events = Event::with(['company', 'category', 'city'])->get();
        $categories = EventCategory::all();
        return view('backend.reports.index', compact('companies', 'cities', 'events', 'categories'));
    }

    public function filter(Request $request)
    {
        try {
            DB::beginTransaction();
            $events = Event::with(['company', 'category', 'city', 'media', 'tickets', 'currency'])
                ->when($request->filled('company_id'), function ($query) use ($request) {
                    $query->where('company_id', $request->company_id);
                })
                ->when($request->filled('city_id'), function ($query) use ($request) {
                    $query->where('city_id', $request->city_id);
                })
                ->when($request->filled('category_id'), function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                })
                ->when($request->filled('date_from'), function ($query) use ($request) {
                    $query->whereHas('eventDates', function ($q) use ($request) {
                        $q->where('start_date', '>=', $request->date_from);
                    });
                })
                ->when($request->filled('date_to'), function ($query) use ($request) {
                    $query->whereHas('eventDates', function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->date_to);
                    });
                })->get();
            // dd($events);
            $html = view('backend.reports.partials.events-rows', compact('events'))->render();  //make sure to use the correct view
            DB::commit();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $events = Event::with(['company', 'category', 'city', 'media', 'tickets', 'currency'])->get();

            // render html table rows
            $html = view('backend.reports.partials.events-rows', compact('events'))->render();

            // generate pdf and save to storage
            $pdf = Pdf::loadView('backend.reports.partials.export', compact('events'));
            $fileName = 'reports_' . time() . '.pdf';
            $filePath = storage_path('app/public/reports/' . $fileName);


            if (!file_exists(storage_path('app/public/reports'))) {
                mkdir(storage_path('app/public/reports'), 0777, true);
            }

            $pdf->save($filePath);

            // public URL to download
            $downloadUrl = asset('storage/reports/' . $fileName);

            return response()->json([
                'success' => true,
                'download_url' => $downloadUrl,
                'html' => $html
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
