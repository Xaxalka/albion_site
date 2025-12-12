<?php

namespace App\Http\Controllers;

use App\Models\SkillMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SkillMediaController extends Controller
{
    public function show(Request $request, SkillMedia $media)
    {
        if ($media->is_private && !$request->hasValidSignature()) {
            abort(403, 'Signed URL required for private media');
        }

        $disk = $media->disk ?? 'local';

        if (! Storage::disk($disk)->exists($media->path)) {
            abort(404);
        }

        return Storage::disk($disk)->response($media->path, $media->original_name);
    }
}
