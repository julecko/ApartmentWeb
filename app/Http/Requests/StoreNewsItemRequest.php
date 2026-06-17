<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNewsItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'badge'      => ['required', Rule::in(['investment', 'repairFund', 'repair', 'general'])],
            'date'       => ['required', 'date'],
            'titleSk'    => ['required', 'string', 'max:255'],
            'titleEn'    => ['required', 'string', 'max:255'],
            'summarySk'  => ['nullable', 'string', 'max:500'],
            'summaryEn'  => ['nullable', 'string', 'max:500'],
            'contentSk'  => ['nullable', 'string'],
            'contentEn'  => ['nullable', 'string'],
            'pinned'     => ['boolean'],

            'attachments'          => ['nullable', 'array', 'max:10'],
            'attachments.*'        => ['file', 'max:20480'], // 20 MB per file
        ];
    }
}