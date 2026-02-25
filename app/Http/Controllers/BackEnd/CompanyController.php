<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Company\CompanyRequest;
use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    protected $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        $companies = Company::all();
        
        return view('backend.companies.index', compact('companies'));
    }

    public function create()
    {
        $users = User::get();

        return view('backend.companies.create', compact('users'));
    }

    public function store(CompanyRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request
                ->safe()
                ->merge([
                    'api_token' => Str::random(60),
                ])
                ->all();

            $company = Company::create($data);

            if (count($request->files) > 0) {
                saveMedia($request, $company);
            }

            DB::commit();
            return redirect()->route('admin.companies.index')->with('success', 'Company created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('store', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function show(string $id)
    {
        try {
            $company = $this->getCompanyOrFail($id);
            return view('backend.companies.show', compact('company'));
        } catch (\Exception $e) {
            $this->logError('show', $e);
            return redirect()->route('admin.companies.index')->with('error', 'Company not found');
        }
    }

    public function edit(string $id)
    {
        try {
            $company = Company::findOrFail($id);
            return view('backend.companies.edit', compact('company'));
        } catch (\Exception $e) {
            $this->logError('edit', $e);
            return redirect()->route('admin.companies.index')->with('error', 'Company not found');
        }
    }

    public function update(CompanyRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $company = $this->getCompanyOrFail($id);
            $company->update($request->safe()->all());
            if (count($request->files) > 0) {
                saveMedia($request, $company);
            } else {
                if ($request->has('remove_files')) {
                    $company->clearMediaCollection();
                }
            }

            DB::commit();
            return redirect()->route('admin.companies.index')->with('success', 'Company updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('update', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $company = $this->getCompanyOrFail($id);
            $company->delete();

            DB::commit();
            return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('destroy', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Helper method to fetch a company or throw exception.
     */
    private function getCompanyOrFail(string $id): Company
    {
        $company = $this->companyRepository->getById($id);

        if (!$company) {
            throw new \Exception('Company not found');
        }

        return $company;
    }

    /**
     * Helper method to log errors consistently.
     */
    private function logError(string $method, \Exception $e)
    {
        Log::channel('error')->error("Error in CompanyController@{$method}: {$e->getMessage()} on line {$e->getLine()} in file {$e->getFile()}");
    }
}
