<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Events\ArticleEvent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request('page') ? request('page') : 1;

        $articles = Cache::remember('articles'.$page, 3000, function() {
            return Article::latest()->with('user')->paginate(6);
        });

        if(request()->expectsJson()) {
            return response()->json($articles);
        }

        return view('article.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', [self::class]);

        $request->validate([
            'title' => 'required|min:6',
            'text' => 'required',
            'date' => 'required',
        ]);

        $article = new Article([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'short_desc' => $request->short_desc,
            'text' => $request->text,
            'date' => $request->date,
        ]);

        if($article->save()) {
            DB::table('cache')->where('key', 'LIKE', 'articles%')->delete();

            ArticleEvent::dispatch($article);
        }

        if(request()->expectsJson()) {
            return response()->json($article);
        }

        return redirect()->route('article.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Article $article)
    {
        $id_notify = $request->input('id_notify');

        if ($id_notify){
            auth()->user()->notifications->where('id', $id_notify)->first()->markAsRead();
        }

        $comments = Cache::rememberForever('article_comment'.$article->id, function() use($article) {
            return $article->comments()->where('accept', true)->latest()->get();
        });

        if(request()->expectsJson()) {
            return response()->json(['article' => $article, 'comments' => $comments]);
        }

        return view('article.show', ['article' => $article, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        Gate::authorize('article', $article);

        return view('article.edit', ['article'=>$article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        Gate::authorize('article', $article);

        $request->validate([
            'date' => 'required',
            'title' => 'required|min:6',
            'text' => 'required'
        ]);
        $article->date = $request->date;
        $article->title = $request->title;
        $article->short_desc = $request->short_desc;
        $article->text = $request->text;

        if($article->save()) {
            DB::table('cache')->where('key', 'LIKE', 'articles%')->delete();
        }

        if(request()->expectsJson()) {
            return response()->json($article);
        }

        return redirect()->route('article.show', ['article' => $article->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('article', $article);

        if($article->comments()->count()){
            $article->comments()->delete();
        }

        if($article->delete()) {
            Cache::forget('comments');
            Cache::forget('article_comment'.$article->id);
            DB::table('cache')->where('key', 'LIKE', 'articles%')->delete();
        }

        if(request()->expectsJson()) {
            return response()->json('destroy');
        }

        return redirect()->route('article.index');
    }
}
