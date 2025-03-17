<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// CMS
use App\Models\Image;
use App\Http\Requests\ImageFormRequest;
use App\Repositories\ImageRepository;
use App\Services\ImageService;

class ImageController extends Controller
{
    private $repository;
    private $service;

    public function __construct(ImageRepository $repository, ImageService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $image = Image::create($request->merge([
            'gallery_id' => $request->get('gallery')
        ])->only([
            'gallery_id'
        ]));

        if ($request->hasFile('qqfile')) {
            try {
                $this->service->upload($request->file('qqfile'), $image);
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                // Log the error for debugging
                Log::error('Image upload failed.', [
                    'error' => $e->getMessage(),
                    'file' => $request->file('qqfile')->getClientOriginalName()
                ]);

                // Return an error response
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred during the upload process.',
                    'error' => $e->getMessage(),
                ], 500); // Return HTTP 500 for server errors
            }
        } else {
            // Return an error response if no file is uploaded
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded.',
            ], 400); // Return HTTP 400 for bad request
        }
    }

    public function edit($id)
    {
        $image = Image::find($id);
        return view('admin.image.form', [
            'entry' => $image,
            'cardTitle' => 'Edytuj zdjęcie',
            'backButton' => route('admin.gallery.show', $image->gallery_id)
        ]);
    }

    public function update(ImageFormRequest $request, int $id)
    {

        $image = $this->repository->find($id);
        $this->repository->update($request->only('file_alt'), $image);

        if ($request->hasFile('file')) {
            $this->service->upload($request->file('file'), $image, true);
        }

        return redirect(route('admin.gallery.show', $image->gallery_id))->with('success', 'Obrazek zapisany');
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function sort(Request $request)
    {
        $this->repository->updateOrder($request->get('recordsArray'));
    }
}
