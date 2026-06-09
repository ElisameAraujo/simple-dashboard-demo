<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Controller;
use App\Services\Media\RichTextMediaManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class RichTextMediaUploadController extends Controller
{
    public function __construct(
        private readonly RichTextMediaManager $uploads
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
                'file' => [
                    'required',
                    File::image()->max(10240),
                ],
                'disk' => [
                    'required',
                    'string',
                    Rule::in(array_keys(config('filesystems.disks'))),
                ],
                'mode' => [
                    'required',
                    'string',
                    Rule::in(['temporary', 'owner']),
                ],
                'temporary_key' => [
                    Rule::requiredIf($request->input('mode') === 'temporary'),
                    'nullable',
                    'string',
                    'max:120',
                ],
                'owner_key' => [
                    Rule::requiredIf($request->input('mode') === 'owner'),
                    'nullable',
                    'string',
                    'max:120',
                ],
            ],
            [
                'file.uploaded' => 'The file exceeds the PHP upload limit.',
                'file.max' => 'The image must be at most 10MB.',
                'file.image' => 'The uploaded file must be a valid image.',
            ]
        );

        $uploaded = $validated['mode'] === 'temporary'
            ? $this->uploads->uploadTemporaryImage($validated['file'], $validated['disk'], $validated['temporary_key'])
            : $this->uploads->uploadOwnerImage($validated['file'], $validated['disk'], $validated['owner_key']);

        return response()->json([
            'url' => $uploaded['url'],
            'location' => $uploaded['location'],
            'path' => $uploaded['path'],
        ]);
    }
}
