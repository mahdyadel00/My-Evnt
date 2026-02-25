<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Partner\StorePartnerRequest;
use App\Http\Requests\Backend\Partner\UpdatePartnerRequest;
use App\Models\Partner;
use App\Repositories\PartnerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PartnerController extends Controller
{
    protected $partnerRepository;

    /**
     * PartnerController constructor.
     *
     * @param PartnerRepository $partnerRepository
     */
    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
    }

    /**
     * Display a listing of the partners.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $partners = Partner::latest()->get();
        return view('backend.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new partner.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('backend.partners.create');
    }

    /**
     * Store a newly created partner in storage.
     *
     * @param StorePartnerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePartnerRequest $request)
    {
        try {
            DB::beginTransaction();

            $partner = Partner::create($request->validated());

            if ($request->hasFile('image')) {
                saveMedia($request, $partner);
            }

            DB::commit();
            return redirect()->route('admin.partners.index')
                ->with('success', __('Partner created successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('store', $e);
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Display the specified partner.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $id)
    {
        try {
            $partner = $this->getPartnerOrFail($id);
            return view('backend.partners.show', compact('partner'));
        } catch (\Exception $e) {
            $this->logError('show', $e);
            return redirect()->route('admin.partners.index')
                ->with('error', __('Partner not found'));
        }
    }

    /**
     * Show the form for editing the specified partner.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        try {
            $partner = $this->getPartnerOrFail($id);
            return view('backend.partners.edit', compact('partner'));
        } catch (\Exception $e) {
            $this->logError('edit', $e);
            return redirect()->route('admin.partners.index')
                ->with('error', __('Partner not found'));
        }
    }

    /**
     * Update the specified partner in storage.
     *
     * @param UpdatePartnerRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdatePartnerRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $partner = $this->getPartnerOrFail($id);
            $partner->update($request->validated());

            if ($request->hasFile('image')) {
                saveMedia($request, $partner);
            } elseif ($request->has('remove_image')) {
                $partner->media()->delete();
            }

            DB::commit();
            return redirect()->route('admin.partners.index')
                ->with('success', __('Partner updated successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('update', $e);
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Remove the specified partner from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $partner = $this->getPartnerOrFail($id);
            $partner->delete();

            DB::commit();
            return redirect()->route('admin.partners.index')
                ->with('success', __('Partner deleted successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('destroy', $e);
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Helper method to fetch a partner or throw exception.
     *
     * @param string $id
     * @return Partner
     * @throws \Exception
     */
    private function getPartnerOrFail(string $id): Partner
    {
        $partner = $this->partnerRepository->getById($id);

        if (!$partner) {
            throw new \Exception("Partner not found");
        }

        return $partner;
    }

    /**
     * Helper method to log errors consistently.
     *
     * @param string $method
     * @param \Exception $e
     */
    private function logError(string $method, \Exception $e)
    {
        Log::channel('error')->error("Error in PartnerController@{$method}: {$e->getMessage()} on line {$e->getLine()} in file {$e->getFile()}");
    }
}