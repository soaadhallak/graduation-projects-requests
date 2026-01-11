<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index():AnonymousResourceCollection
    {
        $notifications=Auth::user()->notifications()->latest()->paginate(1);

        return NotificationResource::collection($notifications)
            ->additional([
                'message'=>ResponseMessages::RETRIEVED->message()
            ]);
    }

    public function markAsRead(string $id):NotificationResource
    {
        $notification=Auth::user()->unreadNotifications()->findOrFail($id);

        $notification->markAsRead();

        return NotificationResource::make($notification)
            ->additional([
                'message'=>ResponseMessages::RETRIEVED->message()
            ]);
    }

    public function markAllAsRead():AnonymousResourceCollection
    {
        $notifications=Auth::user()->unreadNotifications;

        $notifications->markAsRead();

        return NotificationResource::collection($notifications)
            ->additional([
                'message'=>ResponseMessages::UPDATED->message()
            ]);
    }
}
