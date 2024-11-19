<?php

namespace Codersgarden\PhpLexofficeApi;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Contact
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.lexoffice.io/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('constant.LEXOFFICE_API_TOKEN')
            ],
        ]);
    }

    /**
     * Create a contact in lexoffice API using Guzzle
     *
     * @param array $contactData
     * @return array
     */
    public function create(array $contactData)
    {
        try {
            $response = $this->client->post('contacts', [
                'json' => $contactData,
            ]);

            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $errorData = $response ? json_decode($response->getBody()->getContents(), true) : $e->getMessage();

            return [
                'success' => false,
                'status' => $response ? $response->getStatusCode() : $e->getCode(),
                'error' => $errorData['message'] ?? $e->getMessage(),
            ];
        }
    }

    /**
     * Update a contact in lexoffice API using Guzzle
     *
     * @param string $contactId
     * @param array $contactData
     * @return array
     */
    public function update(string $contactId, array $contactData)
    {
        try {
            $response = $this->client->put("contacts/{$contactId}", [
                'json' => $contactData,
            ]);

            return [
                'success' => true,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $errorData = $response ? json_decode($response->getBody()->getContents(), true) : $e->getMessage();

            return [
                'success' => false,
                'status' => $response ? $response->getStatusCode() : $e->getCode(),
                'error' => $errorData['message'] ?? $e->getMessage(),
            ];
        }
    }
}