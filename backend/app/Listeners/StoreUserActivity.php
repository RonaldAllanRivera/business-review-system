<?php

namespace App\Listeners;

use App\Events\UserActivityOccurred;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class StoreUserActivity implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(UserActivityOccurred $event): void
    {
        DB::table('user_activities')->insert([
            'user_id' => $event->user->id,
            'action' => $event->action,
            'metadata' => json_encode($event->metadata),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
