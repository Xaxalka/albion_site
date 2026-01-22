<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkillMedia;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function auth;

class MediaController extends Controller
{
    /**
     * Upload skill media (image, gif, or video)
     */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'nullable|file',
            'type' => 'required|in:image,gif,video',
            'media_url' => 'nullable|url',
            'skillable_id' => 'required|integer',
            'skillable_type' => 'required|in:weapon-skill,armor-skill,line-skill',
        ]);

        $file = $request->file('file');
        $type = $validated['type'];
        $mediaUrl = $validated['media_url'] ?? null;

        if (! $file && ! $mediaUrl) {
            return response()->json(['message' => 'File or media_url is required'], 422);
        }

        if ($mediaUrl && $type === 'image') {
            return response()->json(['message' => 'Image links are not supported'], 422);
        }

        // Map skillable_type to model class
        $modelMap = [
            'weapon-skill' => 'App\Models\WeaponSkill',
            'armor-skill' => 'App\Models\ArmorSkill',
            'line-skill' => 'App\Models\LineSkill',
        ];

        $modelClass = $modelMap[$validated['skillable_type']] ?? null;
        if (!$modelClass) {
            return response()->json(['message' => 'Invalid skillable type'], 422);
        }

        // Verify skillable exists
        $skillable = $modelClass::findOrFail($validated['skillable_id']);

        if ($mediaUrl) {
            if ($type === 'gif' && ! $this->isGifUrl($mediaUrl)) {
                return response()->json(['message' => 'GIF link must end with .gif or be from Giphy'], 422);
            }

            if ($type === 'video' && ! $this->isYoutubeUrl($mediaUrl)) {
                return response()->json(['message' => 'Only YouTube links are supported for video'], 422);
            }

            $normalizedUrl = $type === 'video'
                ? $this->normalizeYoutubeUrl($mediaUrl)
                : $mediaUrl;

            $media = SkillMedia::create([
                'skillable_type' => $modelClass,
                'skillable_id' => $validated['skillable_id'],
                'path' => $normalizedUrl,
                'disk' => 'url',
                'original_name' => $mediaUrl,
                'type' => $type,
                'is_private' => false,
            ]);
        } else {
            $maxMb = match ($type) {
                'image' => 10,
                'gif' => 20,
                'video' => 50,
            };

            if ($file->getSize() > ($maxMb * 1024 * 1024)) {
                return response()->json(['message' => "File too large. Max {$maxMb} MB"], 422);
            }

            // Generate unique filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Store based on type
            $path = match($type) {
                'image' => $file->storeAs('media/images', $filename, 'local'),
                'gif' => $file->storeAs('media/gifs', $filename, 'local'),
                'video' => $file->storeAs('media/videos', $filename, 'local'),
            };

            // Create SkillMedia record
            $media = SkillMedia::create([
                'skillable_type' => $modelClass,
                'skillable_id' => $validated['skillable_id'],
                'path' => $path,
                'disk' => 'local',
                'original_name' => $file->getClientOriginalName(),
                'type' => $type,
                'is_private' => true,
            ]);
        }

        return response()->json([
            'id' => $media->id,
            'type' => $media->type,
            'original_name' => $media->original_name,
            'preview' => route('media.show', ['media' => $media]),
        ], 201);
    }

    /**
     * Delete skill media
     */
    public function destroy(SkillMedia $media)
    {
        // Check authorization
        if (!auth()->check() || !auth()->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete file from storage
        if (Storage::disk($media->disk)->exists($media->path)) {
            Storage::disk($media->disk)->delete($media->path);
        }

        // Delete database record
        $media->delete();

        return response()->json(['message' => 'Media deleted successfully']);
    }

    /**
     * Get media for a skillable
     */
    public function index(Request $request)
    {
        $skillable_type = $request->query('skillable_type');
        $skillable_id = $request->query('skillable_id');

        $media = SkillMedia::where('skillable_type', $skillable_type)
            ->where('skillable_id', $skillable_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => $item->type,
                    'original_name' => $item->original_name,
                    'preview' => route('media.show', ['media' => $item]),
                ];
            });

        return response()->json($media);
    }

    private function isGifUrl(string $url): bool
    {
        $lower = strtolower($url);

        return str_ends_with($lower, '.gif') || str_contains($lower, 'giphy.com');
    }

    private function isYoutubeUrl(string $url): bool
    {
        $lower = strtolower($url);

        return str_contains($lower, 'youtube.com') || str_contains($lower, 'youtu.be');
    }

    private function normalizeYoutubeUrl(string $url): string
    {
        $parsed = parse_url($url);
        $host = strtolower($parsed['host'] ?? '');
        $path = trim($parsed['path'] ?? '', '/');
        $query = [];
        parse_str($parsed['query'] ?? '', $query);

        if ($host === 'youtu.be' && $path !== '') {
            return "https://www.youtube.com/embed/{$path}";
        }

        if ($host === 'www.youtube.com' || $host === 'youtube.com') {
            if (str_starts_with($path, 'watch') && !empty($query['v'])) {
                return "https://www.youtube.com/embed/{$query['v']}";
            }

            if (str_starts_with($path, 'embed/')) {
                return "https://www.youtube.com/{$path}";
            }

            if (str_starts_with($path, 'shorts/')) {
                $id = substr($path, strlen('shorts/'));
                return "https://www.youtube.com/embed/{$id}";
            }
        }

        return $url;
    }
}
