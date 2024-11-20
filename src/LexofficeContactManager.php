<?php

namespace Codersgarden\PhpLexofficeApi;

use GuzzleHttp\Exception\RequestException;

class LexofficeContactManager extends LexofficeBase
{
    /**
     * Create a new instance of the LexofficeContactManager
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
    public function find(string $contactId)
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
     * Retrieve all contacts or filter contacts from the LexOffice API.
     *
     * This method retrieves all contacts or filters contacts based on the
     * provided criteria. The filtering mechanism supports query parameters
     * such as email, name, number, customer, and vendor roles, which can be
     * combined using logical AND operations.
     *
     * Filters that are not set are ignored, and only the provided filters will
     * be applied. The response includes pagination details when applicable.
     *
     * Example filters:
     * - ['email' => 'max@gmx.de', 'name' => 'Mustermann']
     * - ['vendor' => true, 'customer' => false]
     *
     * @param array $filters Associative array of filter parameters as key-value pairs.
     *                       Supported keys include 'email', 'name', 'number', 'customer', and 'vendor'.
     * @return array Response array with 'success' status, 'data' containing the contacts,
     *               or 'error' with error details.
     */
    public function all(array $filters = [])
    {
        try {
            $queryParams = http_build_query($filters);
            $response = $this->client->get("contacts?" . $queryParams);

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
