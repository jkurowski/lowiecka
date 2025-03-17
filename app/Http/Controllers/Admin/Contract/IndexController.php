<?php

namespace App\Http\Controllers\Admin\Contract;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Investment;
use App\Models\Template;
use App\Services\FileConversionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;

use \CloudConvert\Laravel\Facades\CloudConvert;
use \CloudConvert\Models\Job;
use \CloudConvert\Models\Task;

// CMS
use App\Repositories\ContractRepository;
use App\Http\Requests\ContractFormRequest;
use App\Services\ContractService;
use App\Models\ContractTemplate;
use App\Models\Contract;


class IndexController extends Controller
{
    private ContractRepository $repository;
    private ContractService $service;
    protected $fileConversionService;

    public function __construct(ContractRepository $repository, ContractService $service, FileConversionService $fileConversionService)
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
        $this->service = $service;
        $this->fileConversionService = $fileConversionService;
    }

    public function index()
    {
        // Retrieve all contracts
        $contracts = Contract::all()->map(function ($contract) {
            $contract['type'] = 1;
            return $contract;
        });

        $templates = Template::all()->map(function ($template) {
            $template['type'] = 2;
            return $template;
        });

        $list = $contracts->concat($templates);
        return view('admin.contract.index', ['list' => $list]);
    }

    public function generate(Request $request, Contract $contract)
    {
        // Get data excluding token and submit keys
        $requestData = $request->except('_token', 'submit', 'file_type');

        // Word file name and path
        $fileName = $contract->template;
        $fileNameFilled = Str::slug($contract->name) . '_' . date('His') . '_template.docx';
        $file_path = public_path('uploads/contract/templates/' . $fileName);

        // Create a TemplateProcessor to fill the template
        $templateProcessor = new TemplateProcessor($file_path);

        // Set values in the template
        foreach ($requestData as $key => $value) {
            $templateProcessor->setValue($key, $value);

            Log::info('Template Placeholder', ['key' => $key, 'value' => $value]);
        }

        // Save the filled Word file
        $fileStorage = public_path('uploads/storage/' . $fileNameFilled);
        $templateProcessor->saveAs($fileStorage);

        // Load Word file with PHPWord
        $file = new \Illuminate\Http\File($fileStorage);

        $fileModel = new File();
        $fileModel->parent_id = 14; // You may need to update this to the correct value
        $fileModel->user_id = auth()->id();
        $fileModel->type = 0; // Update as per your file type categorization
        $fileModel->name = $contract->name . ' ' . $request->get('number');
        $fileModel->description = 'Dokument wygenerowany';

        // Export as .pdf
        if($request->input('file_type') == 2){
            $outputFilePath = public_path
            ('uploads/storage/' . Str::slug(pathinfo($fileNameFilled, PATHINFO_FILENAME)) . '_convert.pdf');

            // Perform conversion
            $result = $this->fileConversionService->convertDocxToPdf($fileStorage, $outputFilePath);

            // Get file details for the generated PDF
            $pdfFile = new \Illuminate\Http\File($outputFilePath);

            $fileSize = $pdfFile->getSize();
            $fileExtension = $pdfFile->getExtension();
            $fileMimeType = $pdfFile->getMimeType();

            $fileModel->file = $outputFilePath;
        }

        // Export as .docx
        if($request->input('file_type') == 1) {
            $fileSize = $file->getSize();
            $fileExtension = $file->getExtension();
            $fileMimeType = $file->getMimeType();

            $fileModel->file = $fileNameFilled;
        }

        $fileModel->size = $fileSize;
        $fileModel->extension = $fileExtension;
        $fileModel->mime = $fileMimeType;

        // Save the file record to the database
        $fileModel->save();

        // Redirect with success message
        return redirect(route('admin.contract.index'))->with('success', 'Nowy dokument został wygenerowany');
    }

    public function create()
    {
        return view('admin.contract.form', [
            'cardTitle' => 'Dodaj dokument',
            'backButton' => route('admin.contract.index')
        ])->with('entry', Contract::make());
    }

    public function store(ContractFormRequest $request)
    {
        $validatedData = $request->validated();
        $contract = $this->repository->create($validatedData);

        $this->updateArticleFiles($request, $contract, 'file', 'upload', false);

        return redirect(route('admin.contract.index'))->with('success', 'Nowy dokument dodany');
    }

    public function show(Contract $contract)
    {

        $placeholders = $contract->contractTemplates->first();

        if($placeholders) {

            $placeholders = json_decode($placeholders->placeholders, true);

            return view('admin.contract.generate', [
                'entry' => $contract,
                'cardTitle' => $contract->name,
                'placeholders' => $placeholders,
                'investments' => Investment::select(['id', 'name'])->get(),
                'backButton' => route('admin.contract.index')
            ]);
        } else {
            return view('admin.contract.generate', [
                'entry' => $contract,
                'cardTitle' => $contract->name,
                'placeholders' => null,
                'investments' => Investment::select(['id', 'name'])->get(),
                'backButton' => route('admin.contract.index')
            ]);
        }
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function settings(Contract $contract)
    {
        $templateFile = $contract->template;
        $templateFilePath = public_path('uploads/contract/templates/' . $templateFile);

        // Ensure the file exists
        if (!file_exists($templateFilePath)) {
            Log::error("Template file does not exist: {$templateFilePath}");
            throw new \Exception("Template file does not exist.");
        }

        // Check the existing contract template
        $contractTemplates = $contract->contractTemplates;

        // Log the template file name to track the process
        Log::info("Processing template for contract: {$contract->name}, template file: {$templateFile}");

        // Check if there is an existing template
        if ($contractTemplates->isNotEmpty()) {
            $existingTemplate = $contractTemplates->first();

            // Log current template source and new template file comparison
            Log::info("Existing template source: {$existingTemplate->source}, New template file: {$templateFile}");

            // Compare file names (Contract.template vs ContractTemplate.source)
            if ($existingTemplate->source !== $templateFile) {
                // Template file has changed, check if placeholders exist
                if (!empty($existingTemplate->placeholders)) {
                    Log::info("Placeholders are not empty, using existing placeholders.");
                    $placeholdersData = json_decode($existingTemplate->placeholders, true); // Use existing placeholders
                } else {
                    Log::info("Placeholders are empty, re-processing template.");
                    $placeholdersData = $this->processTemplateFile($templateFilePath); // Re-process if placeholders are empty
                }

                // Update the source if the template file has changed
                $existingTemplate->update([
                    'source' => $templateFile, // Update the template source
                    'placeholders' => json_encode($placeholdersData), // Update placeholders if necessary
                ]);

                Log::info("Template source and placeholders updated for contract: {$contract->name}");
            } else {
                // Template is the same, use existing placeholders
                Log::info("Template file is the same, using existing placeholders.");
                $placeholdersData = json_decode($existingTemplate->placeholders, true);
            }
        } else {
            // No existing template, process the new template
            Log::info("No existing template found, processing new template for contract: {$contract->name}");
            $placeholdersData = $this->processTemplateFile($templateFilePath);

            // Create a new ContractTemplate record
            ContractTemplate::create([
                'contract_id' => $contract->id,
                'source' => $templateFile,
                'placeholders' => json_encode($placeholdersData),
            ]);

            Log::info("New template created for contract: {$contract->name}");
        }

        return view('admin.contract.settings', [
            'entry' => $contract,
            'cardTitle' => $contract->name . ' - Ustawienia dokumentu',
            'placeholders' => $placeholdersData,
            'backButton' => route('admin.contract.index'),
        ]);
    }

    public function saveSettings(Request $request, Contract $contract)
    {
        $requestData = $request->except('_token', 'submit');

        ContractTemplate::updateOrCreate(
            ['contract_id' => $contract->id], // Matching criteria
            [
                'source' => $contract->template, // Values to update or create
                'placeholders' => json_encode($requestData)
            ]
        );

        return redirect(route('admin.contract.index'))->with('success', 'Zmiany zapisane');
    }

    public function edit(Contract $contract)
    {
        return view('admin.contract.form', [
            'entry' => $contract,
            'cardTitle' => 'Edytuj dokument',
            'backButton' => route('admin.contract.index')
        ]);
    }

    public function update(ContractFormRequest $request, Contract $contract)
    {
        $this->repository->update($request->validated(), $contract);

        $this->updateArticleFiles($request, $contract, 'file', 'upload', true);

        return redirect(route('admin.contract.index'))->with('success', 'Dokument zaktualizowany');
    }

    public function destroy($id)
    {
        //
    }

    private function updateArticleFiles(ContractFormRequest $request, object $contract, string $fileField, string $uploadMethod, bool $delete)
    {
        if ($request->hasFile($fileField)) {
            $this->service->$uploadMethod($request->name, $request->file($fileField), $contract, $delete);
        }
    }

    protected function processTemplateFile($filePath)
    {
        Log::info("Processing template file for placeholders extraction: {$filePath}");

        $zip = new \ZipArchive();

        if ($zip->open($filePath) === true) {
            $xmlFilePath = 'word/document.xml';
            $xmlContent = $zip->getFromName($xmlFilePath);

            // Log to confirm XML content extraction
            Log::info("Extracted XML content from: {$xmlFilePath}");

            $pattern = '/\[(.*?)\]/';
            $updatedXmlContent = preg_replace_callback($pattern, function ($matches) {
                $variableName = str_replace(' ', '_', $matches[1]);
                return '${' . $variableName . '}';
            }, $xmlContent);

            $zip->addFromString($xmlFilePath, $updatedXmlContent);
            $zip->close();
        }

        // Use TemplateProcessor to extract placeholders from the template
        $templateProcessor = new TemplateProcessor($filePath);
        $placeholders = $templateProcessor->getVariables();

        Log::info("Extracted placeholders: " . implode(', ', $placeholders));

        return $placeholders;
    }
}
