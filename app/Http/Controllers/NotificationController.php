<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read.
     *
     * @param Request $request
     * @param string $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, $notification)
    {
        $notification = Auth::user()
            ->notifications()
            ->where('id', $notification)
            ->first();

        if ($notification) {
            // Update the read_at timestamp
            $notification->update(['read_at' => Carbon::now()]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ], 404);
    }
}
