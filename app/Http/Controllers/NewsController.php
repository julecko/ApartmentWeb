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
use Illuminate\Support\Facades\Log;

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
     * Store a new post with optional file attachments.
     */
    public function store(StoreNewsItemRequest $request)
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

        return redirect()
            ->route('admin.index')
            ->with('success', 'Created');
    }

    /**
     * Update an existing post.
     * Supports adding new files and deleting existing ones in one request.
     */
    public function update(UpdateNewsItemRequest $request, NewsItem $newsItem)
    {
        sleep(3);
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
            $ids = $request->input('deleteAttachmentIds', []);
            if (!empty($ids)) {
                $toDelete = NewsAttachment::whereIn('id', $ids)
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

        return redirect()
            ->back()
            ->with('success', 'Updated');
    }

    /**
     * Toggle pinned state.
     */
    public function togglePin(NewsItem $newsItem)
    {
        $newsItem->update(['pinned' => ! $newsItem->pinned]);

        return redirect()
            ->back()
            ->with('success', 'Updated');
    }

    /**
     * Delete a post and all its stored attachment files.
     */
    public function destroy(NewsItem $newsItem)
    {
        DB::transaction(function () use ($newsItem): void {
            foreach ($newsItem->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->path);
            }
            $newsItem->delete(); // attachments cascade via DB
        });

        return redirect()
            ->back()
            ->with('success', 'Deleted');
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
