<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNewsItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'badge'      => ['sometimes', Rule::in(['investment', 'repairFund', 'repair', 'general'])],
            'date'       => ['sometimes', 'date'],
            'titleSk'    => ['sometimes', 'string', 'max:255'],
            'titleEn'    => ['sometimes', 'string', 'max:255'],
            'summarySk'  => ['sometimes', 'string', 'max:500'],
            'summaryEn'  => ['sometimes', 'string', 'max:500'],
            'contentSk'  => ['nullable', 'string'],
            'contentEn'  => ['nullable', 'string'],
            'pinned'     => ['boolean'],

            'attachments'          => ['nullable', 'array', 'max:10'],
            'attachments.*'        => ['file', 'max:20480'],

            'deleteAttachmentIds'   => ['nullable', 'array'],
            'deleteAttachmentIds.*' => ['integer', 'exists:news_attachments,id'],
        ];
    }
}