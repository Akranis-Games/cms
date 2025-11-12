<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Nur Broadcasting aktivieren, wenn nicht 'null' oder 'log' Driver verwendet wird
        $driver = config('broadcasting.default', 'log');
        
        if ($driver !== 'null' && $driver !== 'log') {
            // Prüfe ob Pusher verfügbar ist, wenn pusher Driver verwendet wird
            if ($driver === 'pusher' && !class_exists('Pusher\Pusher')) {
                return;
            }
            
            Broadcast::routes();
            require base_path('routes/channels.php');
        }
    }
}

