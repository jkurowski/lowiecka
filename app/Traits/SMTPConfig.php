<?php

namespace App\Traits;

use App\Mail\SMTPTestEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

trait SMTPConfig
{

  public function setSettingsSMTPConfig()
  {
    $config = $this->getUserProvidedSMTPConfig();
    $this->setUserProvidedSMTPConfigToEnv($config['host'], $config['port'], $config['username'], $config['password'], $config['encryption'], $config['from_address'], $config['from_name']);
  }
  public function getUserProvidedSMTPConfig()
  {
    if (!$this->settingsKeysExist()) {
      return false;
    }

    return [
      'host' => settings("mailing_host") ,
      'port' => settings("mailing_port"),
      'username' => settings("mailing_username"),
      'password' => settings("mailing_password"),
      'encryption' => settings("mailing_encryption"),
      'from_address' => settings("mailing_from_address"),
      'from_name' => settings("mailing_from_name"),
    ];
  }

  public function settingsKeysExist()
  {
    return settings()->has('mailing_host') && settings()->has('mailing_port') && settings()->has('mailing_username') && settings()->has('mailing_password') && settings()->has('mailing_encryption') && settings()->has('mailing_from_address') && settings()->has('mailing_from_name');
  }

  public function setUserProvidedSMTPConfigToEnv(
    string $host,
    string $port,
    string $username,
    string $password,
    string $encryption,
    string $from_address,
    string $from_name,
  ) {



    config(['mail.mailers.smtp.host' => $host]);
    config(['mail.mailers.smtp.port' => $port]);
    config(['mail.mailers.smtp.username' => $username]);
    config(['mail.mailers.smtp.password' => $password]);
    config(['mail.mailers.smtp.encryption' => $encryption]);
    config(['mail.mailers.smtp.from.address' => $from_address]);
    config(['mail.mailers.smtp.from.name' => $from_name]);
  }

  public function sendTestEmail(string $toAddress)
  {
    try {
      Mail::to($toAddress)->send(new SMTPTestEmail());
      $this->setConfigValid(true);
      return response()->json(['status' => 'success', 'message' => 'Email został wysłany poprawnie']);
    } catch (\Exception $e) {
      Log::error('SMTP Test Email Error: ' . $e->getMessage());
      $this->setConfigValid(false);
      return response()->json(['status' => 'error', 'message' => 'Wystąpił błąd podczas wysyłania emaila', 'error' => $e->getMessage()], 500);
    }
  }

  public function sendTestMailSilently(string $toAddress)
  {
    try {
      Mail::to($toAddress)->send(new SMTPTestEmail());
      $this->setConfigValid(true);
      return true;
    } catch (\Exception $e) {
      Log::error('SMTP Test Email Error: ' . $e->getMessage());
      $this->setConfigValid(false);
      return false;
    }
  }

  public function setConfigValid(bool $valid)
  {
    settings()->put('smtp_config_valid', $valid);
  }

  public function isSMTPConfigValid()
  {
    return settings('smtp_config_valid');
  }
}
