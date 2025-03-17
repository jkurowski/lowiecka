@if($list->count() > 0)
<div id="photos-list" class="container">
    <div class="row justify-content-center">
        @foreach ($list as $p)
        <div class="col-12 col-sm-6 col-md-4 p-2">
            <div class="col-gallery-thumb">
                <a href="/uploads/gallery/images/{{$p->file}}" class="glightbox" rel="gallery-1" title="">
                    <picture>
                        <source type="image/webp" srcset="{{asset('uploads/gallery/images/thumbs/webp/'.$p->file_webp) }}">
                        <source type="image/jpeg" srcset="{{asset('uploads/gallery/images/thumbs/'.$p->file) }}">
                        <img src="{{asset('uploads/gallery/images/thumbs/'.$p->file) }}" alt="{{ $p->name }}" class="w-100">
                    </picture>
                    <div><i class="las la-search-plus"></i></div>
                </a>
            </div>
            </div>
        @endforeach
    </div>
</div>
@endif
