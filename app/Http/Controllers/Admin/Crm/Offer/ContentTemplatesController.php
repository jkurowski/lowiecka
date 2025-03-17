<?php

namespace App\Http\Controllers\Admin\Crm\Offer;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\ContentTemplateFormRequest as FormRequest;
use App\Models\ContentTemplate as Model;

class ContentTemplatesController extends Controller
{
    private const CONTENT_TEMPLATES_ROUTE = 'admin.crm.offer.content-templates';
    private const CONTENT_TEMPLATES_FORM = 'admin.crm.offer.content-template.form';

    public function index()
    {
        $templates = Model::all();
        return view('admin.crm.offer.content-template.index', compact('templates'));
    }

    public function create()
    {
        return view(self::CONTENT_TEMPLATES_FORM, [
            'cardTitle' => 'Dodaj szablon',
            'backButton' => route(self::CONTENT_TEMPLATES_ROUTE),
        ])->with('entry', Model::make());
    }

    public function store(FormRequest $request)
    {
        Model::create($request->validated());
        return redirect(route(self::CONTENT_TEMPLATES_ROUTE))->with('success', 'Nowy szablon dodany');
    }

    public function show()
    {
        $templates = Model::all()->map(function ($template) {
            return [
                'title' => $template->title,
                'description' => $template->description,
                'content' => $template->content,
            ];
        });

        return response()->json($templates);
    }

    public function edit(int $id)
    {
        return view(self::CONTENT_TEMPLATES_FORM, [
            'entry' => Model::find($id),
            'cardTitle' => 'Edytuj szablon',
            'backButton' => route(self::CONTENT_TEMPLATES_ROUTE)
        ]);
    }

    public function update(FormRequest $request, Model $template)
    {
        $template->update($request->validated());
        return redirect(route(self::CONTENT_TEMPLATES_ROUTE))->with('success', 'Szablon zapisany');
    }

    public function destroy(int $id)
    {
        $entry = Model::find($id);
        $entry->delete();
        return response()->json('Deleted');
    }
}
