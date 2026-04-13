<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UploadedImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'base64_data' => ['required', 'string'],
        ]);

        abort_if(! str_starts_with($request->base64_data, 'data:image/'), 422, 'Invalid image data.');

        $image = UploadedImage::create([
            'imageable_id' => 0,
            'imageable_type' => 'pending',
            'base64_data' => $request->base64_data,
        ]);

        return response()->json(['id' => $image->id]);
    }
}
