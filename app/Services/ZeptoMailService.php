<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZeptoMailService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('ZEPTOMAIL_API_KEY');
        $this->apiUrl = 'https://api.zeptomail.com/v1.1/email/template';
    }

    public function sendTemplateEmail($templateKey, $toEmail, $mergeInfo = [], $attachments = [])
    {
        $response = Http::withHeaders([
            'Authorization' => 'Zoho-enczapikey ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, [
            "template_key"   => $templateKey,
            "bounce_address" => "bounce@" . parse_url(config('app.url'), PHP_URL_HOST),
            "from" => [
                "address" => env('MAIL_FROM_ADDRESS'),
                "name"    => env('MAIL_FROM_NAME')
            ],
            "to" => [
                ["email_address" => ["address" => $toEmail]]
            ],
            "merge_info" => $mergeInfo,
            "attachments" => $attachments
        ]);

        return $response->json();
    }
}
