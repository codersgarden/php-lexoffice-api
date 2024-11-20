<?php
namespace Codersgarden\PhpLexofficeApi;
use GuzzleHttp\Client;

class LexofficeBase{

    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('lexoffice.base_uri'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('lexoffice.api_token')
            ],
        ]);
    }

}