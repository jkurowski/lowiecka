<?php

namespace App\Http\Controllers\Front\Developro\Article;

use App\Http\Controllers\Controller;
use App\Models\InvestmentPage;
use App\Models\Page;
use App\Repositories\InvestmentArticleRepository;
use App\Repositories\InvestmentRepository;

class IndexController extends Controller
{
    private InvestmentRepository $repository;
    private InvestmentArticleRepository $articleRepository;
    private int $pageId;

    public function __construct(InvestmentRepository $repository, InvestmentArticleRepository $articleRepository)
    {
        $this->repository = $repository;
        $this->articleRepository = $articleRepository;
        $this->pageId = 11;
    }

    public function index($slug)
    {
        $investment = $this->repository->findBySlug($slug);
        $investmentPage = InvestmentPage::where('investment_id', $investment->id)->where('slug', 'dziennik-budowy')->first();
        //$investmentArticles = $this->articleRepository->allSortByWhere('investment_id', $investment->id, 'date', 'ASC');
        $menu_page = Page::where('id', $this->pageId)->first();

        return view('front.developro.investment.news', [
            'investment' => $investment,
            'page' => $menu_page,
            'investment_page' => $investmentPage,
            //'articles' => $investmentArticles
        ]);
    }

    public function show($slug, $article)
    {

        $investment = $this->repository->findBySlug($slug);
        //$investmentPage = $investment->investmentPage()->where('slug', $slug)->first();
        $investmentArticle = $this->articleRepository->findBySlug($article);
        $menu_page = Page::where('id', $this->pageId)->first();

        return view('front.developro.investment.news-show', [
            'investment' => $investment,
            'page' => $menu_page,
            //'investment_page' => $investmentPage,
            'investment_news' => $investmentArticle
        ]);
    }
}
