<?php

namespace App\Observers;

use App\Models\Gallery;

class GalleryObserver
{
    /**
     * Handle the gallery "deleted" event.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return void
     */
    public function deleted(Gallery $gallery)
    {
        $image_gallery_path = public_path('uploads/gallery/' . $gallery->file);
        if (file_exists($image_gallery_path)) {
            unlink($image_gallery_path);
        }

        $image_gallery_web_path = public_path('uploads/gallery/webp/' . $gallery->file_webp);
        if (file_exists($image_gallery_web_path)) {
            unlink($image_gallery_web_path);
        }

        foreach ($gallery->photos as $photo) {
            if ($photo->file) {

                $image_path = public_path('uploads/gallery/images/' . $photo->file);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }

                $image_web_path = public_path('uploads/gallery/images/webp/' . $photo->file_webp);
                if (file_exists($image_web_path)) {
                    unlink($image_web_path);
                }

                $image_thumb_path = public_path('uploads/gallery/images/thumbs/' . $photo->file);
                if (file_exists($image_thumb_path)) {
                    unlink($image_thumb_path);
                }

                $image_thumb_web_path = public_path('uploads/gallery/images/thumbs/webp/' . $photo->file_webp);
                if (file_exists($image_thumb_web_path)) {
                    unlink($image_thumb_web_path);
                }
            }
        }
        $gallery->photos()->delete();
    }
}
