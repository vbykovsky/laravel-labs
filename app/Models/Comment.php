<?php

namespace App\Models;

use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'article_id',
        'title',
        'desc',
        'accept',
    ];

    function user(){
        return $this->belongsTo(User::class);
    }

    function article(){
        return $this->belongsTo(Article::class);
    }
}
