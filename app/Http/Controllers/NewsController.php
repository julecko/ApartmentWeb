<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsItemRequest;
use App\Http\Requests\UpdateNewsItemRequest;
use App\Http\Resources\NewsItemResource;
use App\Models\NewsAttachment;
use App\Models\NewsItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    /**
     * Public page — returns Inertia view with all posts.
     */
    public function index(): Response
    {
        $items = NewsItem::with('attachments')
            ->orderByDesc('pinned')
            ->orderByDesc('date')
            ->get();

        return Inertia::render('ApartmentNews', [
            'items' => NewsItemResource::collection($items),
        ]);
    }

    /**
     * Admin page — same data, different view.
     */
    public function adminIndex(): Response
    {
        $items = NewsItem::with('attachments')
            ->orderByDesc('pinned')
            ->orderByDesc('date')
            ->get();

        return Inertia::render('AdminNews', [
            'items' => NewsItemResource::collection($items),
        ]);
    }

    /**
     * Store a new post with optional file attachments.
     */
    public function store(StoreNewsItemRequest $request): JsonResponse
    {
        $item = DB::transaction(function () use ($request): NewsItem {
            $item = NewsItem::create([
                'badge'      => $request->badge,
                'date'       => $request->date,
                'title_sk'   => $request->titleSk,
                'title_en'   => $request->titleEn,
                'summary_sk' => $request->summarySk,
                'summary_en' => $request->summaryEn,
                'content_sk' => $request->contentSk,
                'content_en' => $request->contentEn,
                'pinned'     => $request->boolean('pinned'),
            ]);

            $this->storeAttachments($request->file('attachments', []), $item);

            return $item->load('attachments');
        });

        return (new NewsItemResource($item))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an existing post.
     * Supports adding new files and deleting existing ones in one request.
     */
    public function update(UpdateNewsItemRequest $request, NewsItem $newsItem): JsonResponse
    {
        DB::transaction(function () use ($request, $newsItem): void {
            $newsItem->update([
                'badge'      => $request->badge      ?? $newsItem->badge,
                'date'       => $request->date       ?? $newsItem->date,
                'title_sk'   => $request->titleSk    ?? $newsItem->title_sk,
                'title_en'   => $request->titleEn    ?? $newsItem->title_en,
                'summary_sk' => $request->summarySk  ?? $newsItem->summary_sk,
                'summary_en' => $request->summaryEn  ?? $newsItem->summary_en,
                'content_sk' => $request->contentSk  ?? $newsItem->content_sk,
                'content_en' => $request->contentEn  ?? $newsItem->content_en,
                'pinned'     => $request->has('pinned')
                    ? $request->boolean('pinned')
                    : $newsItem->pinned,
            ]);

            // Delete requested attachments
            if ($request->filled('deleteAttachmentIds')) {
                $toDelete = NewsAttachment::whereIn('id', $request->deleteAttachmentIds)
                    ->where('news_item_id', $newsItem->id)
                    ->get();

                foreach ($toDelete as $attachment) {
                    Storage::disk('public')->delete($attachment->path);
                    $attachment->delete();
                }
            }

            // Add new attachments
            $this->storeAttachments($request->file('attachments', []), $newsItem);
        });

        return (new NewsItemResource($newsItem->fresh('attachments')))
            ->response();
    }

    /**
     * Toggle pinned state.
     */
    public function togglePin(NewsItem $newsItem): JsonResponse
    {
        $newsItem->update(['pinned' => ! $newsItem->pinned]);

        return (new NewsItemResource($newsItem->load('attachments')))
            ->response();
    }

    /**
     * Delete a post and all its stored attachment files.
     */
    public function destroy(NewsItem $newsItem): JsonResponse
    {
        DB::transaction(function () use ($newsItem): void {
            foreach ($newsItem->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
            }
            $newsItem->delete(); // attachments cascade via DB
        });

        return response()->json(null, 204);
    }

    /**
     * Delete a single attachment from a post.
     */
    public function destroyAttachment(NewsItem $newsItem, NewsAttachment $newsAttachment): JsonResponse
    {
        abort_if($newsAttachment->news_item_id !== $newsItem->id, 404);

        Storage::disk('public')->delete($newsAttachment->path);
        $newsAttachment->delete();

        return response()->json(null, 204);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function storeAttachments(array $files, NewsItem $item): void
    {
        foreach ($files as $file) {
            $path = $file->store('news-attachments', 'public');

            $item->attachments()->create([
                'name'      => $file->getClientOriginalName(),
                'path'      => $path,
                'mime_type' => $file->getMimeType(),
                'size'      => $file->getSize(),
            ]);
        }
    }
}
