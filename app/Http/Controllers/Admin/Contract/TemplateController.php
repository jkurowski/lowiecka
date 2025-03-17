<?php

namespace App\Http\Controllers\Admin\Contract;

use App\Http\Controllers\Controller;
use App\Models\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// CMS
use App\Http\Requests\TemplateFormRequest;
use App\Repositories\TemplateRepository;
use App\Models\Template;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class TemplateController extends Controller
{
    private TemplateRepository $repository;
    public function __construct(TemplateRepository $repository)
    {
//        $this->middleware('permission:contract-list|contract-create|contract-edit|contract-delete', [
//            'only' => ['index','store']
//        ]);
//        $this->middleware('permission:contract-create', [
//            'only' => ['create','store']
//        ]);
//        $this->middleware('permission:contract-edit', [
//            'only' => ['edit','update']
//        ]);
//        $this->middleware('permission:contract-delete', [
//            'only' => ['destroy']
//        ]);

        $this->repository = $repository;
    }

    public function index()
    {
        //
    }

    public function create()
    {
        return view('admin.contract.template.form', [
            'cardTitle' => 'Dodaj dokument',
            'backButton' => route('admin.contract.index')
        ])->with('entry', Template::make());
    }

    public function store(TemplateFormRequest $request)
    {
        $validatedData = $request->validated();
        $this->repository->create($validatedData);
        return redirect(route('admin.contract.index'))->with('success', 'Nowy szablon dodany');
    }

    public function show(Template $template)
    {
        return view('admin.contract.template.generate', [
            'entry' => $template,
            'cardTitle' => $template->name,
            'placeholders' => json_decode($template->placeholders, true),
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function generate(Request $request, Template $template)
    {
        function replaceTags($str) {
            return preg_replace_callback('/\[(.*?)\]/', function ($matches) {
                $tagName = $matches[1];
                $modifiedTag = str_replace([' ', ': ', '.'], ['_', ':_', '_'], $tagName);
                return '[' . $modifiedTag . ']';
            }, $str);
        }

        $requestData = $request->except('_token', 'submit', 'file_type');
        $modifiedTemplate = replaceTags($template->text);

        // Export as .pdf
        if($request->input('file_type') == 2){
            foreach ($requestData as $key => $value) {
                $text = str_replace("[{$key}]", $value, $modifiedTemplate);
                $modifiedTemplate = $text;
            }

            $modifiedTemplate = '<!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF-8">
            <title>PDF with Polish Letters</title>
            <style>
        @page {
            margin: 10mm;
        }
        body {
            font-family: "DejaVu Sans", sans-serif;
            margin: 0;
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            padding: 5px 10px;
            line-height: normal;
        }
        dl, dd {margin:0 !important;padding:0 !important}
        h1, h2, h3, h4, h5, h6, p, ul, li, ol {
            font-size: initial;
            font-weight: initial;
            font-family: initial;
            color: initial !important;
            background: none !important;
            outline: none !important;
            border: none !important;
            text-shadow: none !important;
            margin: 0 !important;
            line-height: normal !important;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        </style>
        </head>
        <body>' . $modifiedTemplate . '</body>
        </html>';

            // Ensure UTF-8 encoding
            $modifiedTemplate = mb_convert_encoding($modifiedTemplate, 'UTF-8', 'UTF-8');

            $pdf = PDF::loadHTML($modifiedTemplate)
                ->setPaper('A4', 'portrait');

            $fileNameFilled = Str::slug($template->name) . '_' . date('His') . '.pdf';
            $fileStorage = public_path('uploads/storage/' . $fileNameFilled);

            $pdf->save($fileStorage);

            $filePdf = new \Illuminate\Http\File($fileStorage);

            $fileSize = $filePdf->getSize();
            $fileExtension = $filePdf->getExtension();
            $fileMimeType = $filePdf->getMimeType();

            $file = new File();
            $file->parent_id = 14;
            $file->user_id = auth()->id();
            $file->type = 0;
            $file->name = $template->name . ' ' . $request->get('number');
            $file->description = 'Dokument wygenerowany';
            $file->file = $fileNameFilled;
            $file->size = $fileSize;
            $file->extension = $fileExtension;
            $file->mime = $fileMimeType;
            $file->save();
        }

        // Export as .docx
        if($request->input('file_type') == 1){
            // Create a new PHPWord instance
            $phpWord = new PhpWord();

            // Add a new section
            $section = $phpWord->addSection();

            foreach ($requestData as $key => $value) {
                $text = str_replace("[{$key}]", $value, $modifiedTemplate);
                $modifiedTemplate = $text;
            }

            Html::addHtml($section, $modifiedTemplate);

            $fileNameFilled = Str::slug($template->name) . '_' . date('His') . '.docx';
            $fileStorage = public_path('uploads/storage/' . $fileNameFilled);

            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($fileStorage);

            $fileWord= new \Illuminate\Http\File($fileStorage);

            $fileSize = $fileWord->getSize();
            $fileExtension = $fileWord->getExtension();
            $fileMimeType = $fileWord->getMimeType();

            $file = new File();
            $file->parent_id = 14;
            $file->user_id = auth()->id();
            $file->type = 0;
            $file->name = $template->name . ' ' . $request->get('number');
            $file->description = 'Dokument wygenerowany';
            $file->file = $fileNameFilled;
            $file->size = $fileSize;
            $file->extension = $fileExtension;
            $file->mime = $fileMimeType;
            $file->save();
        }

        //return redirect(route('admin.contract.index'))->with('success', 'Nowy dokument został wygenerowany');
    }

    public function settings(Template $template)
    {

        $tagsString = $template->tags;
        $placeholdersString = $template->placeholders;

        $tagsArray = json_decode($tagsString, true);

        if (is_array($tagsArray)) {
            $tagsArray = array_combine($tagsArray, $tagsArray);
        } else {
            $tagsArray = [];
        }

        $placeholdersArray = $placeholdersString ? json_decode($placeholdersString, true) : [];

        return view('admin.contract.template.settings', [
            'entry' => $template,
            'cardTitle' => $template->name . ' - Ustawienia szablonu',
            'tagsArray' => $tagsArray,
            'placeholdersArray' => $placeholdersArray,
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function saveSettings(Request $request, Template $template)
    {
        $requestData = $request->except('_token', 'submit');
        $template->update(['placeholders' => json_encode($requestData)]);
        return redirect(route('admin.contract.index'))->with('success', 'Zmiany zapisane');
    }

    public function edit(Template $template)
    {
        return view('admin.contract.template.form', [
            'entry' => $template,
            'cardTitle' => 'Edytuj szablon',
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function update(TemplateFormRequest $request, Template $template)
    {
        $this->repository->update($request->validated(), $template);
        return redirect(route('admin.contract.index'))->with('success', 'Szablon zaktualizowany');
    }

    public function destroy(string $id)
    {
        //
    }
}
