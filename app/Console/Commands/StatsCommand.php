<?php

namespace App\Console\Commands;

use App\Mail\StatsMail;
use App\Models\Comment;
use App\Models\StatsArticle;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class StatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stats-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $commentCount = Comment::whereDate('created_at', Carbon::today())->count();
        $articleCount = StatsArticle::all()->count();

        Mail::to(env('MAIL_TO'))->send(new StatsMail($commentCount, $articleCount));

        StatsArticle::whereNotNull('id')->delete();
    }
}
