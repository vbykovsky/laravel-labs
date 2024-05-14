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
        $comments = Cache::rememberForever('comments', function(){
            return
            DB::table('comments')
                ->join('articles', 'articles.id', 'comments.article_id')
                ->join('users', 'users.id', 'comments.user_id')
                ->select('comments.*','articles.id as article_id', 'articles.title as article', 'users.name')
                ->get();
        });
        // Log::alert($comments);
        return view('comment.index', ['comments' => $comments]);
    }

    public function store(Request $request){
        Cache::forget('comments');
        $request->validate([
            'title'=>'required|min:5',
            'desc'=>'required'
        ]);

        $article = Article::findOrFail($request->article_id);

        $comment = new Comment;
        $comment->title = $request->title;
        $comment->desc = $request->desc;
        $comment->user_id = auth()->id();
        $comment->article_id = $request->article_id;
        $res = $comment->save();
        if ($res) {
            VeryLongJob::dispatch($comment, $article);
        }
        return redirect()->route('article.show', ['article'=>$request->article_id])->with(['res'=>$res]);
    }

    public function edit(Comment $comment){
        Gate::authorize('comment', ['comment'=>$comment]);
        return view('comment.update', ['comment'=>$comment]);
    }

    public function delete(Comment $comment){
        Gate::authorize('comment',['comment'=>$comment]);
        return redirect()->route('article.show', ['article'=>1]);
    }

    public function accept(Comment $comment){
        Cache::forget('comments');
        Cache::forget('article_comment'.$comment->article_id);
        $comment->accept = true;
        $users = User::where('id', '!=', $comment->user_id)->get();
        // Log::alert($users);
        $res = $comment->save();
        if ($res) Notification::send($users, new CommentNotify($comment->title, $comment->article_id));
        return redirect()->route('comment.index');
    }

    public function reject(Comment $comment){
        Cache::forget('comments');
        Cache::forget('article_comment'.$comment->article_id);
        $comment->accept = false;
        $comment->save();
        return redirect()->route('comment.index');
    }
}
