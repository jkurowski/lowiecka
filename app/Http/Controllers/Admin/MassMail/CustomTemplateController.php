<?php

namespace App\Http\Controllers\Admin\MassMail;

use App\Helpers\TemplateTypes;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\File as FileModel;
use App\Services\ClientFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ZipArchive;

class CustomTemplateController extends Controller
{
    public function __construct(private ClientFileService $clientFileService) {}

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'file|mimes:zip', // 10MB max
        ]);


        if ($request->file('file')->isValid()) {
            $uniqueId = Str::uuid()->toString();
            $zipPath = $request->file('file')->storeAs('temp', $uniqueId . '.zip');


            // Extract and check structure
            $result = $this->extractAndCheck($zipPath, $uniqueId);

            if ($result['success']) {
                // Move files to final destination
                $finalPath = $this->moveExtractedFiles($uniqueId, $result['structure']);
                $result['finalPath'] = $finalPath;
                $imagesPathsToUpdateInTemplate = [];

                // save image files to db
                foreach ($result['structure']['images'] as $image) {

                    $filepath = $image;
                    $publicStoragePath = public_path('uploads/storage');

                    $imageName = pathinfo($filepath, PATHINFO_FILENAME);

                    // Check if the file exists in uploads/storage/ add suffix to the filename

                    if (File::exists($publicStoragePath . '/' . $image)) {

                        $imageExtension = pathinfo($image, PATHINFO_EXTENSION);
                        $imageNameWithSuffix = $imageName . '-' . time() . '.' . $imageExtension;
                        File::move($result['finalPath'] . '/images/' . $image, public_path('uploads/storage/' . $imageNameWithSuffix));
                        $this->saveFile(public_path('uploads/storage/' . $imageNameWithSuffix));
                        $imagesPathsToUpdateInTemplate[] = [
                            'old' => $image,
                            'new' => $imageNameWithSuffix
                        ];
                    } else {
                        File::move($result['finalPath'] . '/images/' . $image, public_path('uploads/storage/' . $image));
                        $this->saveFile(public_path('uploads/storage/' . $image));
                        $imagesPathsToUpdateInTemplate[] = [
                            'old' => $image,
                            'new' => $image
                        ];
                    }
                }

                $templateName = pathinfo($result['finalPath'] . '/' . $result['structure']['root'][0], PATHINFO_FILENAME);
                $templateContent = file_get_contents($result['finalPath'] . '/' . $result['structure']['root'][0]);

                $updatedTemplateContent = $this->updateImagesPathsInTemplate($templateContent, $imagesPathsToUpdateInTemplate);

                EmailTemplate::create(
                    [
                        'user_id' => Auth::user()->id,
                        'name' => $templateName . '_' . date('Y-m-d-H-i-s'),
                        'content' => $updatedTemplateContent,
                        'investment_id' => 0, // investment_id = 0 means that this is a newsletter template
                        'meta' => ['template_type' => TemplateTypes::NOT_EDITABLE],
                        'is_uploaded' => 1,
                    ]
                );
            }

            // Clean up temporary files and directories
            $this->cleanupAfterUpload($zipPath, $uniqueId);

            redirect()->back()->with('success', 'Szablon został załadowany');

            return response()->json($result);
        }


        return response()->json(['error' => 'Invalid file'], 400);
    }

    private function extractAndCheck($zipPath, $uniqueId)
    {
        $zip = new ZipArchive;
        $extractPath = storage_path('app/extracted_' . $uniqueId);

        if ($zip->open(storage_path('app/' . $zipPath)) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();

            // Check the structure
            $structure = $this->checkStructure($extractPath);

            return [
                'success' => $structure['valid'],
                'structure' => $structure['contents'],
                'errors' => $structure['errors'],
                'extractPath' => $extractPath
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Failed to open zip file'
            ];
        }
    }

    private function checkStructure($path)
    {
        $structure = [
            'valid' => true,
            'errors' => [],
            'contents' => [
                'root' => [],
                'images' => []
            ]
        ];

        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ignoreFolders = ['__MACOSX'];
        $ignoreFiles = ['.DS_Store', '.temp'];

        // Find the client's folder
        $clientFolder = null;
        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isFile() || in_array($fileInfo->getFilename(), $ignoreFolders)) continue;
            $clientFolder = $fileInfo->getPathname();
            break;
        }

        if (!$clientFolder) {
            $structure['valid'] = false;
            $structure['errors'][] = "Client folder not found";
            return $structure;
        }

        $rootPath = $clientFolder;
        $imagesPath = $rootPath . DIRECTORY_SEPARATOR . 'images';

        // Check files in root directory (client's folder)
        foreach (new \DirectoryIterator($rootPath) as $fileInfo) {
            if ($fileInfo->isDot() || in_array($fileInfo->getFilename(), $ignoreFiles)) continue;

            if ($fileInfo->isFile()) {
                if ($fileInfo->getExtension() !== 'html') {
                    $structure['valid'] = false;
                    $structure['errors'][] = "Non-HTML file found in root: " . $fileInfo->getFilename();
                } else {
                    $structure['contents']['root'][] = $fileInfo->getFilename();
                }
            } elseif ($fileInfo->isDir() && $fileInfo->getFilename() !== 'images' && !in_array($fileInfo->getFilename(), $ignoreFolders)) {
                $structure['valid'] = false;
                $structure['errors'][] = "Unexpected directory in root: " . $fileInfo->getFilename();
            }
        }

        // Check images directory
        if (!is_dir($imagesPath)) {
            $structure['valid'] = false;
            $structure['errors'][] = "Images directory not found";
        } else {
            foreach (new \DirectoryIterator($imagesPath) as $fileInfo) {
                if ($fileInfo->isDot() || in_array($fileInfo->getFilename(), $ignoreFiles)) continue;

                if ($fileInfo->isFile()) {
                    $extension = strtolower($fileInfo->getExtension());
                    if (!in_array($extension, $allowedImageExtensions)) {
                        $structure['valid'] = false;
                        $structure['errors'][] = "Invalid image file in images directory: " . $fileInfo->getFilename();
                    } else {

                        $structure['contents']['images'][] = $fileInfo->getFilename();
                    }
                } elseif ($fileInfo->isDir() && !in_array($fileInfo->getFilename(), $ignoreFolders)) {
                    $structure['valid'] = false;
                    $structure['errors'][] = "Unexpected directory in images: " . $fileInfo->getFilename();
                }
            }
        }

        return $structure;
    }

    private function moveExtractedFiles($uniqueId, $structure)
    {
        $extractPath = storage_path('app/extracted_' . $uniqueId);
        $finalPath = public_path('uploads/storage/' . $uniqueId);
        $ignoreFiles = ['.DS_Store', '.temp'];

        // Find the client's folder
        $clientFolder = null;
        foreach (new \DirectoryIterator($extractPath) as $fileInfo) {
            if ($fileInfo->isDot() || $fileInfo->isFile() || $fileInfo->getFilename() === '__MACOSX') continue;
            $clientFolder = $fileInfo->getFilename();
            break;
        }

        if (!$clientFolder) {
            throw new \Exception("Client folder not found");
        }

        // Create the final directory if not exists
        if (!is_dir($finalPath)) {
            mkdir($finalPath, 0755, true);
        }

        // Move HTML files
        foreach ($structure['root'] as $htmlFile) {
            if (!in_array($htmlFile, $ignoreFiles)) {
                $sourcePath = $extractPath . '/' . $clientFolder . '/' . $htmlFile;
                $destinationPath = $finalPath . '/' . $htmlFile;
                if (!File::move($sourcePath, $destinationPath)) {
                    throw new \Exception("Failed to move file: $htmlFile");
                }
            }
        }

        // Move images directory
        $sourceImagesPath = $extractPath . '/' . $clientFolder . '/images';
        $destImagesPath = $finalPath . '/images';

        if (!is_dir($destImagesPath)) {
            mkdir($destImagesPath, 0755, true);
        }

        foreach (new \DirectoryIterator($sourceImagesPath) as $fileInfo) {
            if ($fileInfo->isDot() || in_array($fileInfo->getFilename(), $ignoreFiles)) continue;

            $sourcePath = $fileInfo->getPathname();
            $destinationPath = $destImagesPath . '/' . $fileInfo->getFilename();
            if (!File::move($sourcePath, $destinationPath)) {
                throw new \Exception("Failed to move image: " . $fileInfo->getFilename());
            }
        }

        return $finalPath;
    }

    private function cleanupAfterUpload($zipPath, $uniqueId)
    {
        // Delete the temporary zip file
        Storage::delete($zipPath);
        // Delete the temporary extraction directory
        Storage::deleteDirectory('extracted_' . $uniqueId);
        // Delete the final directory
        File::deleteDirectory(public_path('uploads/storage/' . $uniqueId));
    }

    private function saveFile($filePath)
    {


        $name = pathinfo($filePath, PATHINFO_FILENAME);
        $size = filesize($filePath);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mime = mime_content_type($filePath);

        $file = FileModel::create([
            'user_id' => Auth::user()->id,
            'name' => $name,
            'parent_id' => 1, // Main catalog
            'file' => $name . '.' . $extension,
            'size' => $size,
            'extension' => $extension,
            'type' => '0',
            'mime' => $mime
        ]);

        return $file;
    }

    private function updateImagesPathsInTemplate($templateContent, $imagesPathsToUpdateInTemplate)
    {
        $newTemplateContent = $templateContent;
        foreach ($imagesPathsToUpdateInTemplate as $image) {

            $newTemplateContent = str_replace('images/' . $image['old'], asset('uploads/storage/' . $image['new']), $templateContent);
        }
        return $newTemplateContent;
    }
}
