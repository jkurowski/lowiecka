<?php

namespace App\Http\Controllers\Admin\Developro\Article;

use App\Http\Controllers\Controller;

//CMS
use App\Models\Investment;
use App\Repositories\InvestmentArticleRepository as Repository;
use App\Http\Requests\InvestArticlesFormRequest as FormRequest;
use App\Models\InvestmentArticles as Model;
use App\Services\InvestmentArticleService;

class IndexController extends Controller
{
    private Repository $repository;
    private InvestmentArticleService $service;

    public function __construct(Repository $repository, InvestmentArticleService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(Investment $investment)
    {
        return view('admin.developro.investment_article.index', ['list' => $this->repository->allSortBy('date', 'ASC'), 'investment' => $investment]);
    }

    public function create(Investment $investment)
    {
        return view('admin.developro.investment_article.form', [
            'cardTitle' => 'Dodaj wpis',
            'investment' => $investment,
            'backButton' => route('admin.developro.investment.article.index', $investment)
        ])->with('entry', Model::make());
    }

    public function store(FormRequest $request, Investment $investment)
    {
        $article = $this->repository->create($request->validated());

        if ($request->hasFile('file')) {
            $this->service->upload($request->title, $request->file('file'), $article);
        }

        return redirect(route('admin.developro.investment.article.index', $investment))->with('success', 'Nowy wpis dodany');
    }

    public function edit(Investment $investment, Model $article)
    {
        return view('admin.developro.investment_article.form', [
            'entry' => $article,
            'investment' => $investment,
            'cardTitle' => 'Edytuj wpis',
            'backButton' => route('admin.developro.investment.article.index', $investment)
        ]);
    }

    public function update(FormRequest $request, Investment $investment, Model $article)
    {
        $this->repository->update($request->validated(), $article);

        if ($request->hasFile('file')) {
            $this->service->upload($request->title, $request->file('file'), $article, true);
        }

        return redirect(route('admin.developro.investment.article.index', $investment))->with('success', 'Wpis zaktualizowany');
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }
}
