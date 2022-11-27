<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\PostStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ClearOverduePosts implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $posts =  Post::where()::where('published_at', '<=', Carbon::now()->subDays(7)->toDateTimeString())->get();
        $archiveStatus = PostStatus::findOrFail(2);

        foreach ($posts as $post){
          $post->status_id = $archiveStatus;
          $post->published_at = null;
          $post->save();
        }
    }
}
