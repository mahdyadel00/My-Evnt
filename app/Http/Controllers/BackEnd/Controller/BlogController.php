<?php

namespace App\Http\Controllers\BackEnd\Controller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Blog\StoreBlogRequest;
use App\Http\Requests\Backend\Blog\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = Blog::with('company')->get();

        return view('backend.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::get();

        return view('backend.blogs.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        try{
            DB::beginTransaction();

            $blog = Blog::create($request->safe()->all());

            if($request->hasFile('image')){
                saveMedia($request , $blog);
            }


            DB::commit();
            session()->flash('success', 'Blog created successfully');
            return redirect()->route('admin.blogs.index');
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            Log::channel('error')->error('BlogController@store Error: '. $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            DB::beginTransaction();

            $blog = Blog::find($id);

            if(!$blog){
                session()->flash('error', 'Blog not found');
                return redirect()->back();
            }
            $companies = Company::get();

            DB::commit();
            return view('backend.blogs.edit', compact('blog', 'companies'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('BlogController@edit Error: '. $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, string $id)
    {
        try {
            DB::beginTransaction();

            $blog = Blog::find($id);

            if (!$blog) {
                session()->flash('error', 'Blog not found');
                return redirect()->back();
            }

            $blog->update($request->safe()->all());

            if($request->hasFile('image')){
                saveMedia($request , $blog);
            }
            DB::commit();
            session()->flash('success', 'Blog updated successfully');
            return redirect()->route('admin.blogs.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('error')->error('BlogController@update Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();

            $blog = Blog::find($id);

            if(!$blog){
                session()->flash('error', 'Blog not found');
                return redirect()->back();
            }

            $blog->delete();

            DB::commit();
            session()->flash('success', 'Blog deleted successfully');
            return redirect()->route('admin.blogs.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('BlogController@destroy Error: '. $e->getMessage());
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }
}
