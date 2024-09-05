<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventAttachmentDeleteRequest;
use App\Http\Requests\EventAttachmentUploadRequest;
use App\Services\EventAttachmentService;

class EventAttachmentController extends Controller
{
    public function __construct(
        protected EventAttachmentService $eventAttachmentService
    ) {}

    public function store(EventAttachmentUploadRequest $request)
    {
        $request->validated();
        $eventId = $request->event_id;
        $attachment = $request->file('attachment');

        try {
            // Store the attachment and get the newly created attachment record
            $newAttachment = $this->eventAttachmentService->store($attachment, $eventId);

            return $request->wantsJson()
                ? response()->json([
                    'message' => 'Attachment uploaded successfully',
                    'attachment' => $newAttachment,
                ], 201)
                : back()->with('success', 'Attachment uploaded successfully');

        } catch (\Exception $e) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Attachment upload failed'], 500)
                : back()->with('error', 'Attachment upload failed');
        }
    }

    public function destroy(EventAttachmentDeleteRequest $request, $attachmentId)
    {
        try {
            $request->validated();
            $this->eventAttachmentService->destroy($attachmentId);

            return response()->json(['message' => 'Attachment deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Attachment deletion failed'], 500);
        }
    }
}
