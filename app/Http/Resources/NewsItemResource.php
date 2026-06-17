<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'badge'      => $this->badge,
            'date'       => $this->date->format('Y-m-d'),
            'titleSk'    => $this->title_sk,
            'titleEn'    => $this->title_en,
            'summarySk'  => $this->summary_sk,
            'summaryEn'  => $this->summary_en,
            'contentSk'  => $this->content_sk ?? '',
            'contentEn'  => $this->content_en ?? '',
            'pinned'     => $this->pinned,
            'attachments' => NewsAttachmentResource::collection(
                $this->whenLoaded('attachments')
            ),
        ];
    }
}
