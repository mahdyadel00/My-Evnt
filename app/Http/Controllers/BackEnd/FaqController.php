<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Faq\StoreFaqRequest;
use App\Http\Requests\Backend\Faq\UpdateFaqRequest;
use App\Models\FAQ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FaqController extends Controller
{
    protected $faqRepository;

    /**
     * Display a listing of the FAQs.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $faqs =  FAQ::latest()->cursorPaginate(10);

        return view('backend.faq.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new FAQ.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('backend.faq.create');
    }

    /**
     * Store a newly created FAQ in storage.
     *
     * @param StoreFaqRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreFaqRequest $request)
    {
        try {
            DB::beginTransaction();

            $faq = FAQ::create($request->validated());

            DB::commit();
            return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('store', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified FAQ.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $id)
    {
        $faq = FAQ::findOrFail($id);
        return view('backend.faq.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified FAQ.
     *
     * @param string $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(string $id)
    {
        $faq = FAQ::findOrFail($id);
        return view('backend.faq.edit', compact('faq'));
    }

    /**
     * Update the specified FAQ in storage.
     *
     * @param UpdateFaqRequest $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateFaqRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $faq = FAQ::findOrFail($id);
            $faq->update($request->validated());

            DB::commit();
            return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('update', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified FAQ from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $faq = FAQ::findOrFail($id);
            $faq->delete();

            DB::commit();
            return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logError('destroy', $e);
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Helper method to log errors consistently.
     *
     * @param string $method
     * @param \Exception $e
     */
    private function logError(string $method, \Exception $e)
    {
        Log::channel('error')->error("Error in FaqController@{$method}: {$e->getMessage()} on line {$e->getLine()} in file {$e->getFile()}");
    }
}
