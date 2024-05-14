<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentMail;


class VeryLongJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    public function __construct(public $comment, public $article)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to(env('MAIL_TO'))->send(new CommentMail($this->comment, $this->article));
    }
}
