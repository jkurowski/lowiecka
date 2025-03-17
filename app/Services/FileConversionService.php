<?php
namespace App\Services;

use CloudConvert\Laravel\Facades\CloudConvert;
use CloudConvert\Models\Job;
use CloudConvert\Models\Task;
use Illuminate\Support\Facades\Storage;

class FileConversionService
{
    public function convertDocxToPdf($inputFilePath, $outputFileName)
    {
        // Step 1: Create a job with tasks
        $job = new Job();

        // Add the import/upload task
        $job->addTask(
            (new Task('import/upload', 'upload-my-file'))
        );

        // Add the conversion task
        $job->addTask(
            (new Task('convert', 'convert-my-file'))
                ->set('input', 'upload-my-file')
                ->set('output_format', 'pdf')
        );

        // Add the export task
        $job->addTask(
            (new Task('export/url', 'export-my-file'))
                ->set('input', 'convert-my-file')
        );

        // Create the job on CloudConvert
        $job = CloudConvert::jobs()->create($job);

        // Step 2: Locate the upload task
        $uploadTask = null;
        foreach ($job->getTasks() as $task) {
            if ($task->getName() === 'upload-my-file') {
                $uploadTask = $task;
                break;
            }
        }

        if (!$uploadTask) {
            throw new Exception("Upload task 'upload-my-file' not found in job.");
        }

        // Upload the file
        $inputStream = fopen($inputFilePath, 'r');
        CloudConvert::tasks()->upload($uploadTask, $inputStream);


        CloudConvert::jobs()->wait($job); // Wait for job completion

        // Step 4: Locate the export task
        $exportTask = null;
        foreach ($job->getTasks() as $task) {
            if ($task->getName() === 'export-my-file') {
                $exportTask = $task;
                break;
            }
        }

        if (!$exportTask) {
            throw new Exception("Export task 'export-my-file' not found in job.");
        }

        // Step 5: Download the converted PDF
        $fileUrl = $exportTask->getResult()->files[0]->url;

        // Saving the converted PDF to storage
        $source = CloudConvert::getHttpTransport()->download($fileUrl)->detach();
        $destination = fopen($outputFileName, 'w');
        stream_copy_to_stream($source, $destination);
    }
}