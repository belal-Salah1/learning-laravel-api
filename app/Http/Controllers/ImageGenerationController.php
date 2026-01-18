<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneratePromptRequest;
use App\Http\Resources\ImageGenerationResource;
use App\Services\OpenAiServiceClass;
use Illuminate\Support\Str;

class ImageGenerationController extends Controller
{
    public function __construct(private OpenAiServiceClass $openAiService) {}

    public function index() {
        $user = request()->user();
        $imageGeneration = $user->imageGenerations()->latest()->paginate(10);
         return imageGenerationResource::collection($imageGeneration);
    }

    public function store(GeneratePromptRequest $request)
    {
        $user = $request->user();
        $image = $request->file('image');
        $originalName = $image->getClientOriginalName();
        $sanitizedName = preg_replace(
            '/[^a-zA-Z0-9._-]/',
            '_',
            pathinfo($originalName, PATHINFO_FILENAME)
        );

        $extension = $image->getClientOriginalExtension();
        $safeFilename = $sanitizedName.'_'.Str::random(10).'.'.$extension;

        $imagepath = $image->storeAs('uploads/images', $safeFilename, 'public');
        $generatedPrompt = $this->openAiService->generatePromptFromImage($image);
        $imageGeneration = $user->imageGenerations()->create([
            'image_path' => $imagepath,
            'generate_prompt' => $generatedPrompt,
            'original_filename' => $originalName,
            'file_size' => $image->getSize(),
            'mime_type' => $image->getMimeType(),
        ]);

        return  new ImageGenerationResource($imageGeneration);
    }
}
 