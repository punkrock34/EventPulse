<?php

namespace App\Http\Requests;

use App\Models\Attachment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventAttachmentDeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $attachmentId = $this->route('attachment');

        // Check if the attachment exists
        $attachment = Attachment::find($attachmentId);
        if (! $attachment) {
            return false;
        }

        // Check if the authenticated user owns the events associated with this attachment
        return Auth::check() && Auth::user()->events->contains($attachment->event_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }
}
