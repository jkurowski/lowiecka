<?php

namespace App\Services\Leads;

use App\Interfaces\LeadEmailProcessingStrategy;


class TabelaOfertStrategy implements LeadEmailProcessingStrategy
{

    private $portal_name = 'tabelaofert.pl';
    public function process($message)
    {

        $data = [
            'portal_name' => $this->portal_name,
            'name' => $this->findCustomerName($message),
            'email' => $this->findCustomerEmail($message),
            'phone' => $this->findCustomerPhone($message),
            'investment_name' => $this->findInvestmentName($message),
            'property_name' => $this->findPropertyName($message),
            'message' => $this->findMessageContent($message)
        ];

        // remove CRLF from strings if any
        foreach ($data as $key => $value) {
            if($key !=='message' && $value !== null) {
                $data[$key] = str_replace(["\r", "\n"], '', $value);
            }
        }

        return $data;
    }

    public function findMessageContent($message)
    {
        $pattern = '/(Telefon:\s*.*)/s';
        $messageContent = null;

        if (preg_match($pattern, $message, $matches)) {
            $messageContent = trim($matches[1]);
        }

        return $messageContent;
    }

    public function findCustomerName($message)
    {
        $pattern = '/Imię i nazwisko:\s*([^\(]+)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findCustomerEmail($message)
    {
        $pattern = '/Adres e-mail:\s*(.*?)(\r?\n|$)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findCustomerPhone($message)
    {
        // Adjust the regex to allow different phone formats and remove extra spaces
        $pattern = '/Telefon:\s*([\+\d\s\(\)-]+)(\r?\n|$)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findInvestmentName($message)
    {
        $pattern = '/Nazwa inwestycji:\s*(.*)(\r?\n|$)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }

    public function findPropertyName($message)
    {
        $pattern = '/Numer oferty:\s*(.*)(\r?\n|$)/';
        if (preg_match($pattern, $message, $matches)) {
            return trim($matches[1]);
        } else {
            return null;
        }
    }
}
