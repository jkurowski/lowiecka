<?php

namespace App\Http\Controllers\Admin\SMTP;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckSMTPFormRequest;
use App\Traits\SMTPConfig;


class IndexController extends Controller
{
    use SMTPConfig;

    public function checkSMTP(CheckSMTPFormRequest $request)
    {
        $validated = $request->validated();

        $this->updateSMTPConfig($validated);

        return $this->sendTestEmail($validated['to_address']);
    }

    private function updateSMTPConfig(array $config)
    {
        $this->setUserProvidedSMTPConfigToEnv(
            $config['host'],
            $config['port'],
            $config['username'],
            $config['password'],
            $config['encryption'],
            $config['from_address'],
            $config['from_name']
        );
    }
}
