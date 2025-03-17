<?php

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\ArticleFormRequest;
use App\Repositories\ArticleRepository;
use App\Services\ArticleService;
use App\Models\Article;

class IndexController extends Controller
{
    private ArticleRepository $repository;
    private ArticleService $service;

    public function __construct(ArticleRepository $repository, ArticleService $service)
    {
//        $this->middleware('permission:box-list|box-create|box-edit|box-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:box-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:box-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:box-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
        $this->service = $service;
    }

    public function index()
    {
        return view('admin.article.index', ['list' => $this->repository->idDesc()]);
    }

    public function create()
    {
        return view('admin.article.form', [
            'cardTitle' => 'Dodaj artykuł',
            'backButton' => route('admin.article.index')
        ])->with('entry', Article::make());
    }

    public function store(ArticleFormRequest $request)
    {
        $validatedData = $request->validated();
        $article = $this->repository->create($validatedData);

        $this->updateArticleFiles($request, $article, 'file', 'upload', false);
        $this->updateArticleFiles($request, $article, 'file_facebook', 'uploadFileFacebook', false);

        return redirect(route('admin.article.index'))->with('success', 'Nowy artykuł dodany');
    }

    public function edit(int $id)
    {
        return view('admin.article.form', [
            'entry' => Article::find($id),
            'cardTitle' => 'Edytuj artykuł',
            'backButton' => route('admin.article.index')
        ]);
    }

    public function update(ArticleFormRequest $request, int $id)
    {
        $article = $this->repository->find($id);
        $this->repository->update($request->validated(), $article);

        $this->updateArticleFiles($request, $article, 'file', 'upload', true);
        $this->updateArticleFiles($request, $article, 'file_facebook', 'uploadFileFacebook', true);

        return redirect(route('admin.article.index'))->with('success', 'Artykuł zaktualizowany');
    }

    private function updateArticleFiles(ArticleFormRequest $request, object $article, string $fileField, string $uploadMethod, bool $delete)
    {
        if ($request->hasFile($fileField)) {
            $this->service->$uploadMethod($request->title, $request->file($fileField), $article, $delete);
        }
    }

    public function destroy(int $id)
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }
}
