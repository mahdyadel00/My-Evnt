<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Article\{StoreArticleRequest, UpdateArticleRequest};
use App\Models\{Artical, Blog};
use Illuminate\Support\Facades\{DB, Log};

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Artical::orderBy('created_at', 'desc')->get();

        return view('backend.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blogs = Blog::all();

        return view('backend.articles.create', compact('blogs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        try{
            DB::beginTransaction();

            $article = Artical::create($request->safe()->all());

            if($request->hasFile('image')){
                saveMedia($request , $article);
            }

            DB::commit();
            session()->flash('success', 'Article created successfully');
            return redirect()->route('admin.articles.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in ArticleController@store: '.$e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            DB::beginTransaction();

            $article = Artical::find($id);

            if(!$article){
                session()->flash('error', 'Article not found');
                return back();
            }

            $blogs = Blog::all();

            DB::commit();
            return view('backend.articles.edit', compact('article', 'blogs'));
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in ArticleController@edit: '.$e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, string $id)
    {
        try{
            DB::beginTransaction();

            $article = Artical::find($id);

            if(!$article){
                session()->flash('error', 'Article not found');
                return back();
            }

            $article->update($request->safe()->all());

            if($request->hasFile('image')){
                saveMedia($request , $article);
            }

            DB::commit();
            session()->flash('success', 'Article updated successfully');
            return redirect()->route('admin.articles.index');
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in ArticleController@update: '.$e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            DB::beginTransaction();

            $article = Artical::find($id);

            if(!$article){
                session()->flash('error', 'Article not found');
                return back();
            }

            $article->delete();

            DB::commit();
            session()->flash('success', 'Article deleted successfully');
            return back();
        }catch(\Exception $e){
            DB::rollBack();
            Log::channel('error')->error('Error in ArticleController@destroy: '.$e->getMessage());
            session()->flash('error', 'Something went wrong');
            return back();
        }
    }
}
