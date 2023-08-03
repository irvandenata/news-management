<?php

namespace App\Listeners;

use App\Events\UserLogging;
use App\Models\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveLogInDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLogging $event)
    {
        $user = $event->user;
        $action = $event->action;
        $status = $event->status;
        $ip_address = $event->ip_address;
        $message = $event->message;
        $model = $event->model;
        $log = Log::create([
            'user_id' => $user->id,
            'action' => $action,
            'status' => $status,
            "message" => $message,
            'ip_address' => $ip_address,
            'model' => $model,
        ]);
        return $log;
    }
}
