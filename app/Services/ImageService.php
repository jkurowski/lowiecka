<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as ImageManager;
use Illuminate\Support\Facades\Log;

class ImageService
{
    public function upload(UploadedFile $file, object $model, bool $delete = false)
    {
        try {
            Log::info('Starting upload process.', ['model_id' => $model->id]);

            if ($delete) {
                Log::info('Delete flag is true. Attempting to delete old files.', ['model_id' => $model->id]);

                if (File::isFile(public_path('uploads/gallery/images/' . $model->file))) {
                    File::delete(public_path('uploads/gallery/images/' . $model->file));
                    Log::info('Deleted old image file.', ['file' => $model->file]);
                }
                if (File::isFile(public_path('uploads/gallery/images/webp/' . $model->file_webp))) {
                    File::delete(public_path('uploads/gallery/images/webp/' . $model->file_webp));
                    Log::info('Deleted old WebP file.', ['file_webp' => $model->file_webp]);
                }

                if (File::isFile(public_path('uploads/gallery/images/thumbs/' . $model->file))) {
                    File::delete(public_path('uploads/gallery/images/thumbs/' . $model->file));
                    Log::info('Deleted old thumbnail file.', ['thumb_file' => $model->file]);
                }
                if (File::isFile(public_path('uploads/gallery/images/thumbs/webp/' . $model->file_webp))) {
                    File::delete(public_path('uploads/gallery/images/thumbs/webp/' . $model->file_webp));
                    Log::info('Deleted old thumbnail WebP file.', ['thumb_file_webp' => $model->file_webp]);
                }
            }

            $name_file = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $name = date('His') . '_' . Str::slug($name_file) . '.' . $file->getClientOriginalExtension();
            $name_webp = date('His') . '_' . Str::slug($name_file) . '.webp';

            Log::info('Generated filenames for new image.', ['name' => $name, 'name_webp' => $name_webp]);

            $file->storeAs('gallery/images/', $name, 'public_uploads');
            Log::info('File stored successfully.', ['path' => 'gallery/images/' . $name]);

            $filepath = public_path('uploads/gallery/images/' . $name);
            $filepath_webp = public_path('uploads/gallery/images/webp/' . $name_webp);
            $thumb_filepath = public_path('uploads/gallery/images/thumbs/' . $name);
            $thumb_filepath_webp = public_path('uploads/gallery/images/thumbs/webp/' . $name_webp);

            ImageManager::make($filepath)
                ->resize(
                    config('images.gallery.big_width'),
                    config('images.gallery.big_height'),
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                )->save($filepath);
            Log::info('Resized and saved main image.', ['path' => $filepath]);

            ImageManager::make($filepath)->encode('webp', 90)->save($filepath_webp);
            Log::info('Encoded and saved WebP version of the image.', ['path_webp' => $filepath_webp]);

            ImageManager::make($filepath)
                ->fit(
                    config('images.gallery.thumb_width'),
                    config('images.gallery.thumb_height')
                )->save($thumb_filepath);
            Log::info('Resized and saved thumbnail.', ['thumb_path' => $thumb_filepath]);

            ImageManager::make($thumb_filepath)->encode('webp', 90)->save($thumb_filepath_webp);
            Log::info('Encoded and saved WebP thumbnail.', ['thumb_path_webp' => $thumb_filepath_webp]);

            $model->update([
                'file' => $name,
                'file_webp' => $name_webp,
                'name' => $file->getClientOriginalName()
            ]);
            Log::info('Model updated with new file information.', ['model_id' => $model->id]);
        } catch (\Exception $e) {
            Log::error('An error occurred during the upload process.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e; // Re-throw the exception to ensure it propagates if needed
        }
    }
}
