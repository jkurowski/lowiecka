@extends('layouts.page')

@section('meta_title', 'Inwestycje')
@if($page)
    @section('seo_title', $page->meta_title)

    @section('seo_description', $page->meta_description)

    @section('pageheader')
        @include('layouts.partials.page-header', ['page' => $page, 'header_file' => 'rooms.jpg', 'heading' => ''])
    @stop
@endif

@section('content')
    <div class="container">
        <div class="row">
            @foreach($list as $p)
                <div class="col-4">
                    <a href="{{ route('front.developro.plan', ['slug' => $p->slug]) }}" itemprop="url">
                        <div class="card">
                            <img src="{{asset('investment/thumbs/'.$p->file_thumb) }}" alt="{{ $p->name }}">
                            <div class="card-body">
                                <h1 class="card-title">{{$p->name}}</h1>
                                <p>{{$p->entry_content}}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
