<?php

namespace App\Http\Middleware;

use App\Models\Attachment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFilePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            abort(403, 'You do not have permission to access this file.');
        }

        $fileName = $request->route('fileName');

        // Find the attachment by its name
        $attachment = Attachment::where('path', "event-attachments/$fileName")->first();

        // Check if attachment exists and if the user owns the event
        if (! $attachment || $attachment->event->owner_id !== Auth::id()) {
            abort(403, 'You do not have permission to access this file.');
        }

        return $next($request);
    }
}
