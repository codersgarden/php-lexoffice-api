<?php

namespace CodersGarden\PhpLexofficeApi;

use GuzzleHttp\Exception\RequestException;

class LexofficeCreditNotesManager extends LexofficeBase
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new delivery note in the LexOffice API.
     *
     * This method allows creating a delivery note with the required details,
     * such as address, line items, total price, and tax conditions. Delivery
     * notes are always created in draft mode unless specified otherwise using
     * the `finalize` parameter.
     *
     * ### Usage Example:
     * ```php
     * $deliveryNoteData = [
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
     *             ]
     *         ]
     *     ],
     *     'totalPrice' => [
     *         'currency' => 'EUR',
     *     ],
     *     'taxConditions' => [
     *         'taxType' => 'net',
     *     ],
     *     'title' => 'Delivery Note Title',
     *     'introduction' => 'Introduction text for the delivery note',
     *     'remark' => 'Closing remarks for the delivery note',
     * ];
     * 
     * $response = $lexofficeDeliveryNotesManager->create($deliveryNoteData);
     * if ($response['success']) {
     *     echo 'Delivery Note Created: ' . $response['data']['id'];
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * ### Return Value:
     * - `'success'`: Boolean indicating the success of the request.
     * - `'data'`: Contains the created delivery note's details on success.
     * - `'error'`: Error message on failure.
     *
     * @param array $data The payload for creating the delivery note.
     * @param bool $finalize Optional parameter to finalize the note. Defaults to false.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function create(array $data, bool $finalize = false)
    {
        try {
            // Append the `finalize` query parameter if set to true
            $url = 'delivery-notes';
            if ($finalize) {
                $url .= '?finalize=true';
            }

            // Make the API request
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
     * Pursue a sales voucher to a credit note.
     *
     * This method allows creating a credit note by pursuing an existing sales voucher
     * in the LexOffice API. The `precedingSalesVoucherId` must be provided to reference
     * the sales voucher being pursued.
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
     * $response = $lexofficeCreditNotesManager->pursueToCreditNote($creditNoteData, 'precedingSalesVoucherId', true);
     * if ($response['success']) {
     *     echo 'Credit Note Created: ' . $response['data']['id'];
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * ### Return Value:
     * - `'success'`: Boolean indicating the success of the request.
     * - `'data'`: Contains the created credit note's details on success.
     * - `'error'`: Error message on failure.
     *
     * @param array $data The payload for creating the credit note.
     * @param string $precedingSalesVoucherId The ID of the sales voucher to pursue.
     * @param bool $finalize Optional parameter to finalize the credit note. Defaults to false.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function pursueToCreditNote(array $data, string $precedingSalesVoucherId, bool $finalize = false)
    {
        try {
            // Construct the URL with query parameters
            $url = 'credit-notes?precedingSalesVoucherId=' . $precedingSalesVoucherId;
            if ($finalize) {
                $url .= '&finalize=true';
            }

            // Make the API request
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
     * Retrieve a credit note by its ID.
     *
     * This method fetches a credit note from the LexOffice API using the given credit note ID.
     *
     * ### Usage Example:
     * ```php
     * $creditNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $response = $lexofficeCreditNotesManager->find($creditNoteId);
     * if ($response['success']) {
     *     print_r($response['data']);
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * ### Return Value:
     * - `'success'`: Boolean indicating the success of the request.
     * - `'data'`: Contains the retrieved credit note's details on success.
     * - `'error'`: Error message on failure.
     *
     * @param string $id The ID of the credit note to retrieve.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function find(string $id)
    {
        try {
            // Make the API GET request
            $response = $this->client->get("credit-notes/{$id}");

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
     * Render a credit note document to retrieve its PDF documentFileId.
     *
     * This method triggers the rendering of a credit note's PDF document file
     * in the LexOffice API. The `documentFileId` returned can be used to
     * download the PDF document via the Files endpoint.
     *
     * ### Usage Example:
     * ```php
     * $creditNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $response = $lexofficeCreditNotesManager->renderDocument($creditNoteId);
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
     * @param string $id The ID of the credit note for which to render the PDF document.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function renderDocument(string $id)
    {
        try {
            // Make the API GET request to render the document
            $response = $this->client->get("credit-notes/{$id}/document");

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
     * Generate a deeplink for a credit note.
     *
     * This method generates a deeplink for viewing or editing a credit note
     * in LexOffice based on the provided `id`.
     *
     * ### Usage Example:
     * ```php
     * $creditNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $viewLink = $lexofficeCreditNotesManager->generateDeeplink($creditNoteId, 'view');
     * echo 'View Link: ' . $viewLink;
     *
     * $editLink = $lexofficeCreditNotesManager->generateDeeplink($creditNoteId, 'edit');
     * echo 'Edit Link: ' . $editLink;
     * ```
     *
     * @param string $id The ID of the credit note.
     * @param string $type The type of link to generate ('view' or 'edit').
     * @return string The generated deeplink URL.
     */
    public function generateDeeplink(string $id, string $type = 'view'): string
    {
        if (!in_array($type, ['view', 'edit'])) {
            throw new \InvalidArgumentException("Invalid link type. Allowed types are 'view' or 'edit'.");
        }

        return "{config('lexoffice.base_uri')}/permalink/credit-notes/{$type}/{$id}";
    }
}
