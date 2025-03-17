@extends('layouts.page', ['body_class' => 'news'])

@section('meta_title', 'Aktualności')
{{--@section('seo_title', $page->meta_title)--}}
{{--@section('seo_description', $page->meta_description)--}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">Aktualności</span>
            </div>
        </div>
    </div>
    <main>

        <section class="section-first section-padding-bottom-large">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Nasze</span>
                            <span class="d-block text-uppercase" data-aos="fade-up"> aktualności</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="container">
                    <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 row-gap-30px">
                        @foreach($articles as $a)
                            <div class="col" data-aos="fade">
                                <x-news-list-item :article="$a"></x-news-list-item>
                            </div>
                        @endforeach
                    </div>
                    <div class="row d-none">
                        <div class="col-12 text-center mt-50px  py-md-40px">
                            <div class="d-flex justify-content-center align-items-center gap-4 gap-md-5">
                                <div>
                                    <a class="link-black" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0" />
                                        </svg>
                                    </a>
                                </div>
                                <div class="fs-43px">
                                    <a href="#">01</a>
                                </div>
                                <div class="fs-30px">
                                    <a class="link-black" href="#">02</a>
                                </div>
                                <div>
                                    <a class="link-black" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
