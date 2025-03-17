<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Traits\SMTPConfig;
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\File;


//CMS
use App\Http\Requests\SeoFormRequest;
use App\Services\SeoService;

class SeoController extends Controller
{
    use SMTPConfig;
    private $service;

    public function __construct(SeoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $robots = File::get(public_path('robots.txt'));
        return view('admin.settings.seo.index', ['robots' => $robots]);
    }

    public function store(SeoFormRequest $request)
    {
        $settings = Valuestore::make(storage_path('app/settings.json'));
        $settings->put($request->except(['page_favicon', 'robots_txt', '_token', 'submit']));

        if ($request->hasFile('page_favicon')) {
            $this->service->uploadFavIcon($request->file('page_favicon'));
        }

        $content = $request->get('robots_txt');
        $filteredContent = strip_tags($content);

        if ($content !== $filteredContent || preg_match('/<\?php|<\/?[a-z][\s\S]*>/i', $filteredContent)) {
            throw new \Exception('Invalid content detected');
        }
        File::put(public_path('robots.txt'), $filteredContent);


        // Check SMTP configuration for mass mailing
        $config = $request->only(['mailing_host', 'mailing_port', 'mailing_username', 'mailing_password', 'mailing_encryption', 'mailing_from_address', 'mailing_from_name']);

        if (collect($config)->every(fn($value) => !empty($value))) {
            $this->setSMTPConfig($config);
            $this->sendTestEmail('test@example.com');
        }

        return redirect(route('admin.settings.seo.index'))->with('success', 'Ustawienia zostały zapisane');
    }

    public function setSMTPConfig($config)
    {
        $this->setUserProvidedSMTPConfigToEnv(
            $config['mailing_host'],
            $config['mailing_port'],
            $config['mailing_username'],
            $config['mailing_password'],
            $config['mailing_encryption'],
            $config['mailing_from_address'],
            $config['mailing_from_name']
        );
    }
}
