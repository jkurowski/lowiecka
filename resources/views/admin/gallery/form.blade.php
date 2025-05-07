@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.gallery.edit'))
        <form method="POST" action="{{route('admin.gallery.update', $entry)}}" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="POST" action="{{route('admin.gallery.store')}}" enctype="multipart/form-data">
            @endif
            @csrf
            <div class="container">
                <div class="card-head container">
                    <div class="row">
                        <div class="col-12 pl-0">
                            <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.gallery.index')}}" class="p-0">Galeria</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card mt-3">
                    @include('form-elements.back-route-button')
                    <div class="card-body control-col12">
                        <div class="row w-100 form-group">
                            @include('form-elements.html-select', ['label' => 'Status', 'name' => 'status', 'selected' => $entry->status, 'select' => ['1' => 'Pokaż na liście', '0' => 'Ukryj na liście']])
                        </div>
                        <div class="row w-100 form-group">
                            @include('form-elements.html-input-text', ['label' => 'Nazwa', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                        </div>
                        <div class="row w-100 form-group">
                            @include('form-elements.html-input-file', [
                                'label' => 'Zdjęcie',
                                'sublabel' => '(wymiary: '.config('images.gallery.catalog_width').'px / '.config('images.gallery.catalog_height').'px)',
                                'name' => 'file',
                                'file' => $entry->file,
                                'file_preview' => config('images.gallery.preview_file_path')
                            ])
                        </div>
                        <div class="row w-100 form-group d-none">
                            @include('form-elements.textarea-fullwidth', ['label' => 'Treść', 'name' => 'text', 'value' => $entry->text, 'rows' => 21, 'class' => 'tinymce', 'required' => 1])
                        </div>
                    </div>
                </div>
            </div>
            @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
        </form>
        @include('form-elements.tintmce')
@endsection
