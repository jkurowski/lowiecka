<?php

namespace App\Http\Controllers\Admin\Crm\Inbox;

use App\Http\Controllers\Controller;
use App\Models\ClientMessage;
use Illuminate\Support\Facades\File;
use App\Repositories\Client\ClientRepository;
use App\Services\AutoAssignLeadsService;
use App\Services\Leads\DominiumStrategy;
use App\Services\Leads\ObidoStrategy;
use App\Services\Leads\OwnStrategy;
use App\Services\Leads\RynekPierwotnyStrategy;
use App\Services\Leads\TabelaOfertStrategy;
use Illuminate\Support\Facades\Log;
use Webklex\IMAP\Facades\Client as IMAPClient;

class ProcessLeadsController extends Controller
{
    private $strategy;
    private $repository;

    public function __construct(ClientRepository $repository)
    {
        $this->repository = $repository;
    }

    public function processEmails()
    {
        File::delete(storage_path('logs/laravel.log'));

        try {
            $client = IMAPClient::account('default');
            $client->connect();
            $folder = $client->getFolder('INBOX');

            // Fetch unseen messages
            $unseenMessages = $folder->query()->unseen()->get();
            //$unseenMessages = $folder->query()->all()->get();

            $i = 0;

            foreach ($unseenMessages as $message) {
//
//                $hasTextBody = !empty($message->getTextBody());
//                $hasHtmlBody = !empty($message->getHTMLBody());
//
//                $bodyType = '';
//
//                if ($hasTextBody && $hasHtmlBody) {
//                    $bodyType = 'Both Text and HTML';
//                } elseif ($hasTextBody) {
//                    $bodyType = 'Text Only';
//                } elseif ($hasHtmlBody) {
//                    $bodyType = 'HTML Only';
//                } else {
//                    $bodyType = 'No Body Content';
//                }
//
//                Log::info('Message Subject: ' . $message->getSubject());
//                Log::info('Body Type: ' . $bodyType);
//
//                Log::info('From: ' . $message->getFrom());
//                Log::info('Flag: ' . $message->getFlags());
//
                $msg = $this->cleanEmailBody($message->getHtmlBody());
//                //$textBody = strip_tags($msg);

                $from = $message->getFrom();
                $emailAddress = $from[0]->mail ?? 'Unknown';

//                Log::info('From: ' . $emailAddress);
//
                $this->chooseStrategy($emailAddress);
//
                if (!$this->strategy) {
                    continue;
                }
//
                $customerData = $this->strategy->process($msg);
                $mappedCustomerData = $this->mapCustomerDataToClient($customerData);
//
//                Log::info('Portal Name: ' . $mappedCustomerData['portal_name']);
//                $strategyName = $this->strategy ? get_class($this->strategy) : 'None';
//                Log::info('Strategia: ' . $strategyName);
//                Log::info('Email: ' . $mappedCustomerData['email']);
//                Log::info('Name: ' . $mappedCustomerData['name']);
//                Log::info('Phone: ' . $mappedCustomerData['phone']);
//                Log::info('Investment: ' . $mappedCustomerData['investment_name']);
//                Log::info('Property: ' . $mappedCustomerData['property_name']);
//                Log::info('Message: ' . $mappedCustomerData['message']);
//                Log::info('-------------------------------------------------------');

                if (!empty($mappedCustomerData['email'])) {
                    $client = $this->repository->createClient(
                        $mappedCustomerData,
                        null,
                        1,
                        $mappedCustomerData['portal_name']
                    );

                    // Auto assignment
                    $clientMsg = ClientMessage::where('client_id', $client->id)->latest()->first();
                    if ($clientMsg) {
                        $autoAssignService = new AutoAssignLeadsService($clientMsg);
                        $autoAssignService->process();
                    }
                }
//
                $message->setFlag('Seen');

                $i++;
            }

            Log::channel('jobs_log')->info('Emails processed successfully: ' .$i);

            return response()->json(['status' => 'Emails processed successfully: ' .$i]);

        } catch (\Exception $e) {
            Log::error("Error processing leads: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
            return response()->json(['status' => 'Error processing emails.'], 500);
        }
    }

    public function cleanEmailBody($message)
    {
        // Remove single '*' and '**'
        $message = str_replace(['*', '**'], '', $message);

        // Remove any HTML tags
        $message = strip_tags($message);

        // Decode HTML entities (e.g., "&lt;" becomes "<")
        $message = html_entity_decode($message, ENT_QUOTES, 'UTF-8');

        // Remove any newline characters (\r\n, \r, \n)
        //$message = str_replace(["\r\n", "\r", "\n"], ' ', $message);

        // Optionally, you can remove extra spaces if needed (replaces multiple spaces with one)
        //$message = preg_replace('/\s+/', ' ', $message);

        // Trim any leading or trailing spaces
        //$message = trim($message);

        return $message;
    }

    private function chooseStrategy($from)
    {
        switch ($from) {
            case 'zgloszenia@tabelaofert.pl':
                $this->strategy = new TabelaOfertStrategy();
                break;
            default:
                $this->strategy = null;
        }
    }

    private function mapCustomerDataToClient($customerData)
    {
        if (!$customerData) {
            return null;
        }

        return [
            'name' => $customerData['name'] ?? $customerData['email'],
            'email' => $customerData['email'],
            'phone' => $customerData['phone'],
            'portal_name' => $customerData['portal_name'],
            'message' => $customerData['message'],
            'investment_name' => $customerData['investment_name'],
            'property_name' => $customerData['property_name'],
            'is_external_source' => true,
            'ip' => 'localhost',
        ];
    }
}