<?php

namespace CodersGarden\PhpLexofficeApi;

use Codersgarden\PhpLexofficeApi\LexofficeBase;
use GuzzleHttp\Exception\RequestException;

class LexofficeCreditNoteManager extends LexofficeBase
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new credit note in the LexOffice API.
     *
     * This method creates a credit note with the specified details, such as
     * address, line items, total price, and tax conditions. By default, credit
     * notes are created in draft mode. To finalize a credit note, set the `finalize`
     * parameter to true.
     *
     * ### Usage Example:
     * ```php
     * $creditNoteData = [
     *     'voucherDate' => '2023-02-22T00:00:00.000+01:00',
     *     'address' => [
     *         'name' => 'Bike & Ride GmbH & Co. KG',
     *         'street' => 'Musterstraße 42',
     *         'city' => 'Freiburg',
     *         'zip' => '79112',
     *         'countryCode' => 'DE',
     *     ],
     *     'lineItems' => [
     *         [
     *             'type' => 'custom',
     *             'name' => 'Abus Kabelschloss Primo 590',
     *             'quantity' => 2,
     *             'unitName' => 'Stück',
     *             'unitPrice' => [
     *                 'currency' => 'EUR',
     *                 'netAmount' => 13.4,
     *                 'taxRatePercentage' => 19,
     *             ],
     *         ],
     *     ],
     *     'totalPrice' => [
     *         'currency' => 'EUR',
     *     ],
     *     'taxConditions' => [
     *         'taxType' => 'net',
     *     ],
     *     'title' => 'Credit Note Title',
     *     'introduction' => 'Introduction text for the credit note',
     *     'remark' => 'Closing remarks for the credit note',
     * ];
     * 
     * $response = $lexofficeCreditNoteManager->create($creditNoteData);
     * ```
     *
     * ### Return Value:
     * - `'success'`: Indicates the success of the request.
     * - `'data'`: Contains the created credit note details on success.
     * - `'error'`: Error message on failure.
     *
     * @param array $data The payload for creating the credit note.
     * @param bool $finalize Optional parameter to finalize the note. Defaults to false.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function create(array $data, bool $finalize = false)
    {
        try {
            $url = 'credit-notes';
            if ($finalize) {
                $url .= '?finalize=true';
            }

            $response = $this->client->post($url, [
                'json' => $data,
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
     * Pursue a sales voucher to a credit note in the LexOffice API.
     *
     * This method creates a credit note that references a preceding sales voucher.
     * The optional query parameter `finalize` can be set to true to finalize the
     * credit note during creation.
     *
     * ### Usage Example:
     * ```php
     * $precedingSalesVoucherId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $creditNoteData = [
     *     'voucherDate' => '2023-02-22T00:00:00.000+01:00',
     *     'address' => [
     *         'name' => 'Bike & Ride GmbH & Co. KG',
     *         'street' => 'Musterstraße 42',
     *         'city' => 'Freiburg',
     *         'zip' => '79112',
     *         'countryCode' => 'DE',
     *     ],
     *     'lineItems' => [
     *         [
     *             'type' => 'custom',
     *             'name' => 'Abus Kabelschloss Primo 590',
     *             'quantity' => 2,
     *             'unitName' => 'Stück',
     *             'unitPrice' => [
     *                 'currency' => 'EUR',
     *                 'netAmount' => 13.4,
     *                 'taxRatePercentage' => 19,
     *             ],
     *         ],
     *     ],
     *     'totalPrice' => [
     *         'currency' => 'EUR',
     *     ],
     *     'taxConditions' => [
     *         'taxType' => 'net',
     *     ],
     * ];
     * 
     * $response = $lexofficeCreditNoteManager->pursueToCreditNote($precedingSalesVoucherId, $creditNoteData, true);
     * ```
     *
     * ### Return Value:
     * - `'success'`: Boolean indicating the success of the request.
     * - `'data'`: Contains the created credit note's details on success.
     * - `'error'`: Error message on failure.
     *
     * @param string $precedingSalesVoucherId The ID of the preceding sales voucher.
     * @param array $data The payload for the credit note.
     * @param bool $finalize Optional parameter to finalize the credit note. Defaults to false.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function pursueToCreditNote(string $precedingSalesVoucherId, array $data, bool $finalize = false)
    {
        try {
            $url = "credit-notes?precedingSalesVoucherId={$precedingSalesVoucherId}";
            if ($finalize) {
                $url .= '&finalize=true';
            }

            $response = $this->client->post($url, [
                'json' => $data,
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
     * Retrieve a credit note from the LexOffice API by its ID.
     *
     * This method fetches a specific credit note based on the provided credit note ID.
     *
     * ### Usage Example:
     * ```php
     * $creditNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $response = $lexofficeCreditNoteManager->find($creditNoteId);
     * if ($response['success']) {
     *     print_r($response['data']);
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * ### Return Value:
     * - `'success'`: Boolean indicating the success of the request.
     * - `'data'`: Contains the credit note details on success.
     * - `'error'`: Error message on failure.
     *
     * @param string $creditNoteId The ID of the credit note to retrieve.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function find(string $creditNoteId)
    {
        try {
            // Make the GET request to fetch the credit note
            $response = $this->client->get("credit-notes/{$creditNoteId}");

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
     * Render a credit note document (PDF) in the LexOffice API.
     *
     * This method triggers the rendering of a PDF for the specified credit note and
     * returns the `documentFileId`, which can be used to download the PDF using the Files Endpoint.
     *
     * ### Usage Example:
     * ```php
     * $creditNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $response = $lexofficeCreditNoteManager->renderDocument($creditNoteId);
     * if ($response['success']) {
     *     echo 'Document File ID: ' . $response['data']['documentFileId'];
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * ### Return Value:
     * - `'success'`: Boolean indicating the success of the request.
     * - `'data'`: Contains the `documentFileId` on success.
     * - `'error'`: Error message on failure.
     *
     * @param string $creditNoteId The ID of the credit note to render.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function renderDocument(string $creditNoteId)
    {
        try {
            // Make the GET request to trigger PDF rendering
            $response = $this->client->get("credit-notes/{$creditNoteId}/document");

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
     * Generate a view deeplink for a credit note.
     *
     * This method generates a URL to view the credit note directly.
     *
     * ### Usage Example:
     * ```php
     * $creditNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $viewUrl = $lexofficeCreditNoteManager->getViewDeeplink($creditNoteId);
     * echo "View Credit Note URL: " . $viewUrl;
     * ```
     *
     * @param string $creditNoteId The ID of the credit note.
     * @return string The URL to view the credit note.
     */
    public function getViewDeeplink(string $creditNoteId): string
    {
        return config('lexoffice.base_uri') . "permalink/credit-notes/view/{$creditNoteId}";
    }

    /**
     * Generate an edit deeplink for a credit note.
     *
     * This method generates a URL to edit the credit note directly.
     *
     * ### Usage Example:
     * ```php
     * $creditNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $editUrl = $lexofficeCreditNoteManager->getEditDeeplink($creditNoteId);
     * echo "Edit Credit Note URL: " . $editUrl;
     * ```
     *
     * @param string $creditNoteId The ID of the credit note.
     * @return string The URL to edit the credit note.
     */
    public function getEditDeeplink(string $creditNoteId): string
    {
        return config('lexoffice.base_uri') . "permalink/credit-notes/edit/{$creditNoteId}";
    }
}
