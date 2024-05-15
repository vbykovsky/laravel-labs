<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Jobs\VeryLongJob;
use App\Notifications\CommentNotify;

class CommentController extends Controller
{
    public function index(){
        $comments = Cache::remember('comments', 3000, function(){
            return Comment::latest()->with('article')->with('user')->get();
        });

        return view('comment.index', ['comments' => $comments]);
    }

    public function store(Request $request){
        $article = Article::findOrFail($request->article_id);

        $request->validate([
            'title'=>'required|min:5',
            'desc'=>'required'
        ]);

        $comment = new Comment([
            'article_id' => $article->id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'desc' => $request->desc,
            'accept' => false,
        ]);

        if ($comment->save()) {
            Cache::forget('comments');
            Cache::forget('article_comment'.$article->id);

            VeryLongJob::dispatch($comment, $article);
        }

        return redirect()->route('article.show', ['article'=>$request->article_id])->with(['res' => true]);
    }

    public function edit(Comment $comment){
        Gate::authorize('comment', $comment);

        return view('comment.edit', ['comment' => $comment]);
    }

    public function update(Request $request, Comment $comment){
        Gate::authorize('comment', $comment);

        $request->validate([
            'title'=>'required|min:5',
            'desc'=>'required',
        ]);

        $comment->title = $request->title;
        $comment->desc = $request->desc;

        $comment->load('article');

        if($comment->save()) {
            Cache::forget('comments');
            Cache::forget('article_comment'.$comment->article->id);
        }

        return redirect(route('article.show', $comment->article->id));
    }

    public function destroy(Comment $comment){
        Gate::authorize('comment', $comment);

        $comment->load('article');

        if($comment->delete()){
            Cache::forget('comments');
            Cache::forget('article_comment'.$comment->article->id);
        }

        return redirect()->route('article.show', ['article' => $comment->article->id]);
    }

    public function accept(Comment $comment){
        Gate::authorize('comment-moderation', $comment);

        $comment->accept = true;

        if($comment->save()){
            Cache::forget('comments');
            Cache::forget('article_comment'.$comment->article_id);

            $users = User::where('id', '!=', $comment->user_id)->get();
            Notification::send($users, new CommentNotify($comment->title, $comment->article_id));
        }

        return redirect()->route('comment.index');
    }

    public function reject(Comment $comment){
        Gate::authorize('comment-moderation', $comment);

        $comment->accept = false;

        if($comment->save()){
            Cache::forget('comments');
            Cache::forget('article_comment'.$comment->article_id);
        }

        return redirect()->route('comment.index');
    }
}
