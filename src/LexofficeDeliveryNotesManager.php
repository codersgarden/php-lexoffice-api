<?php

namespace CodersGarden\PhpLexofficeApi;

use Codersgarden\PhpLexofficeApi\LexofficeBase;
use GuzzleHttp\Exception\RequestException;

class LexofficeDeliveryNotesManager extends LexofficeBase
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
     * Retrieve a delivery note from the LexOffice API by its ID.
     *
     * This method fetches a specific delivery note based on the provided delivery note ID.
     *
     * ### Usage Example:
     * ```php
     * $deliveryNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $response = $lexofficeDeliveryNotesManager->find($deliveryNoteId);
     * if ($response['success']) {
     *     print_r($response['data']);
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * ### Return Value:
     * - `'success'`: Boolean indicating the success of the request.
     * - `'data'`: Contains the delivery note details on success.
     * - `'error'`: Error message on failure.
     *
     * @param string $deliveryNoteId The ID of the delivery note to retrieve.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function find(string $deliveryNoteId)
    {
        try {
            // Make the GET request to fetch the delivery note
            $response = $this->client->get("delivery-notes/{$deliveryNoteId}");

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
     * Pursue a sales voucher to a delivery note in the LexOffice API.
     *
     * This method creates a delivery note that references a preceding sales voucher.
     * The optional query parameter `precedingSalesVoucherId` must be set to the ID
     * of the sales voucher to be pursued.
     *
     * ### Usage Example:
     * ```php
     * $precedingSalesVoucherId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $deliveryNoteData = [
     *     'voucherDate' => '2023-02-22T00:00:00.000+01:00',
     *     'address' => [
     *         'name' => 'Bike & Ride GmbH & Co. KG',
     *         'street' => 'Musterstraße 42',
     *         'city' => 'Freiburg',
     *         'zip' => '79112',
     *         'countryCode' => 'DE',
     *     ],
     * ];
     * 
     * $response = $lexofficeDeliveryNotesManager->pursueToDeliveryNote($deliveryNoteData, $precedingSalesVoucherId);
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
     * @param string $precedingSalesVoucherId The ID of the preceding sales voucher.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function pursueToDeliveryNote(string $precedingSalesVoucherId,array $data = [])
    {
        try {
            $url = "delivery-notes?precedingSalesVoucherId={$precedingSalesVoucherId}";

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
     * Render a delivery note document (PDF) in the LexOffice API.
     *
     * This method triggers the rendering of a PDF for the specified delivery note and
     * returns the `documentFileId`, which can be used to download the PDF using the Files Endpoint.
     *
     * ### Usage Example:
     * ```php
     * $deliveryNoteId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $response = $lexofficeDeliveryNotesManager->renderDocument($deliveryNoteId);
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
     * @param string $deliveryNoteId The ID of the delivery note to render.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function renderDocument(string $deliveryNoteId)
    {
        try {
            // Make the GET request to trigger PDF rendering
            $response = $this->client->get("delivery-notes/{$deliveryNoteId}/document");

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
     * Generate a view deeplink for a delivery note.
     *
     * @param string $deliveryNoteId The ID of the delivery note.
     * @return string The URL to view the delivery note.
     */
    public function getViewDeeplink(string $deliveryNoteId): string
    {
        return config('lexoffice.base_uri')."permalink/delivery-notes/view/{$deliveryNoteId}";
    }

    /**
     * Generate an edit deeplink for a delivery note.
     *
     * @param string $deliveryNoteId The ID of the delivery note.
     * @return string The URL to edit the delivery note.
     */
    public function getEditDeeplink(string $deliveryNoteId): string
    {
        return config('lexoffice.base_uri')."permalink/delivery-notes/edit/{$deliveryNoteId}";
    }
}
