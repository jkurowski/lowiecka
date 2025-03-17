<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;

// CMS
use App\Models\Page;
use OpenGraph;
use Spatie\SchemaOrg\Schema;

class ArticleController extends Controller
{

    public function index()
    {
        //$page = Page::whereId(1)->first();
        $articles = Article::where('status', 1)->orderBy('id', 'DESC')->get();
        //return view('front.article.index', ['page' => $page, 'articles' => $articles]);
        return view('front.article.index', ['articles' => $articles]);
    }

    public function show($slug)
    {

        $article = Article::where('slug', $slug)->first();
//        $page = Page::where('uri', 'aktualnosci')->firstOrFail();
//
//        $schemaBlog = Schema::BlogPosting()
//            ->mainEntityOfPage(Schema::WebPage()->identifier(route('front.news.show', $article->slug)))
//            ->headline($article->title)
//            ->description($article->content_entry)
//            ->datePublished($article->created_at)
//            ->dateModified($article->updated_at)
//            ->image(Schema::imageObject()->url(asset('uploads/articles/'.$article->file))
//                ->height(config('images.article.big_height'))
//                ->width(config('images.article.big_width')))
//            ->author(Schema::person()->name('Autor'));
//
//        $og = OpenGraph::title($article->title)
//            ->type('article')
//            ->image('https://www.bliskiolechow.pl/public/uploads/articles/share/'.$article->file, [
//                'width' => 600,
//                'height' => 314
//            ])
//            ->description($article->content_entry)
//            ->url();

//        return view('front.article.show', [
//            'page' => $page,
//            'article' => $article,
//            'schema' => $schemaBlog,
//            'opengraph' => $og
//        ]);

        return view('front.article.show', [
            'article' => $article
        ]);
    }
}
