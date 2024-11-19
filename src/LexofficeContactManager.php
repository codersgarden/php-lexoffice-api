<?php

namespace Codersgarden\PhpLexofficeApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class LexofficeContactManager
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.lexoffice.io/v1/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('lexoffice.api_token')
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

    /**
     * Show a contact in lexoffice API using Guzzle
     *
     * @param string $contactId
     * @return array
     */
    public function show(string $contactId)
    {
        try {
            $response = $this->client->get("contacts/{$contactId}");

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
     * Get all contacts from lexoffice API using Guzzle
     *
     * @return array
     * [
     *     'success' => bool,
     *     'data' => array|mixed,
     *     'status' => int,
     *     'error' => string,
     * ]
     */
    public function get(){
        try {
            $response = $this->client->get("contacts");

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
     * Delete a contact in lexoffice API using Guzzle
     *
     * @param string $contactId
     * @return array
     */
    public function delete(string $contactId)
    {
        try {
            $response = $this->client->delete("contacts/{$contactId}");

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
