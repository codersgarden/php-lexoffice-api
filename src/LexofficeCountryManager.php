<?php

namespace CodersGarden\PhpLexofficeApi;

use GuzzleHttp\Exception\RequestException;

class LexofficeCountryManager extends LexofficeBase
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Retrieve all countries from the LexOffice API.
     *
     * This method fetches the list of countries known to LexOffice, including
     * country codes, names in English and German, and their tax classification.
     *
     * ### Usage Example:
     * - Retrieve all countries:
     *   ```php
     *   $response = $lexofficeCountryManager->all();
     *   if ($response['success']) {
     *       print_r($response['data']);
     *   } else {
     *       echo 'Error: ' . $response['error'];
     *   }
     *   ```
     *
     * ### Sample Response:
     * On success, the response contains an array of countries:
     * ```json
     * [
     *     {
     *         "countryCode": "DE",
     *         "countryNameDE": "Deutschland",
     *         "countryNameEN": "Germany",
     *         "taxClassification": "de"
     *     },
     *     {
     *         "countryCode": "FR",
     *         "countryNameDE": "Frankreich",
     *         "countryNameEN": "France",
     *         "taxClassification": "intraCommunity"
     *     }
     * ]
     * ```
     *
     * ### Return Value:
     * - `'success'`: Indicates the success of the request.
     * - `'data'`: Contains the array of countries.
     * - `'status'`: HTTP status code on failure.
     * - `'error'`: Error message if the request fails.
     *
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function all()
    {
        try {
            $response = $this->client->get("countries");

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
