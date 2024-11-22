<?php

namespace CodersGarden\PhpLexofficeApi;

use Codersgarden\PhpLexofficeApi\LexofficeBase;
use GuzzleHttp\Exception\RequestException;

class LexofficeInvoiceManager extends LexofficeBase
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new invoice in the LexOffice API.
     *
     * This method creates a new invoice with the specified details, such as
     * address, line items, total price, tax conditions, payment conditions, and shipping conditions.
     * By default, invoices are created in draft mode. To finalize an invoice, set the `finalize` parameter to true.
     *
     * ### Usage Example:
     * ```php
     * $invoiceData = [
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
     *             'name' => 'Energieriegel Testpaket',
     *             'quantity' => 1,
     *             'unitName' => 'Stück',
     *             'unitPrice' => [
     *                 'currency' => 'EUR',
     *                 'netAmount' => 5,
     *                 'taxRatePercentage' => 0,
     *             ],
     *             'discountPercentage' => 0,
     *         ],
     *     ],
     *     'totalPrice' => ['currency' => 'EUR'],
     *     'taxConditions' => ['taxType' => 'net'],
     *     'paymentConditions' => [
     *         'paymentTermLabel' => '10 Tage - 3 %, 30 Tage netto',
     *         'paymentTermDuration' => 30,
     *         'paymentDiscountConditions' => [
     *             'discountPercentage' => 3,
     *             'discountRange' => 10,
     *         ],
     *     ],
     *     'shippingConditions' => [
     *         'shippingDate' => '2023-04-22T00:00:00.000+02:00',
     *         'shippingType' => 'delivery',
     *     ],
     *     'title' => 'Invoice Title',
     *     'introduction' => 'Invoice introduction text',
     *     'remark' => 'Thank you for your purchase!',
     * ];
     * 
     * $response = $lexofficeInvoiceManager->create($invoiceData, true); // Pass true to finalize the invoice
     * ```
     *
     * @param array $data The payload for creating the invoice.
     * @param bool $finalize Optional parameter to finalize the invoice. Defaults to false.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function create(array $data, bool $finalize = false)
    {
        try {
            $url = 'invoices';
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
     * Retrieve an invoice from the LexOffice API by its ID.
     *
     * This method fetches the details of a specific invoice based on the provided invoice ID.
     *
     * ### Usage Example:
     * ```php
     * $invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $response = $lexofficeInvoiceManager->find($invoiceId);
     * if ($response['success']) {
     *     print_r($response['data']);
     * } else {
     *     echo 'Error: ' . $response['error'];
     * }
     * ```
     *
     * @param string $invoiceId The ID of the invoice to retrieve.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function find(string $invoiceId)
    {
        try {
            $response = $this->client->get("invoices/{$invoiceId}");

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
     * Pursue a sales voucher to an invoice in the LexOffice API.
     *
     * This method creates an invoice that references a preceding sales voucher. The optional query
     * parameter `finalize` can be set to true to finalize the invoice during creation.
     *
     * ### Usage Example:
     * ```php
     * $precedingSalesVoucherId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $invoiceData = [
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
     * $response = $lexofficeInvoiceManager->pursueToInvoice($precedingSalesVoucherId, $invoiceData, true);
     * ```
     *
     * @param string $precedingSalesVoucherId The ID of the preceding sales voucher.
     * @param array $data The payload for the invoice.
     * @param bool $finalize Optional parameter to finalize the invoice. Defaults to false.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function pursueToInvoice(string $precedingSalesVoucherId, array $data, bool $finalize = false)
    {
        try {
            $url = "invoices?precedingSalesVoucherId={$precedingSalesVoucherId}";
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
     * Render an invoice document (PDF) in the LexOffice API.
     *
     * This method triggers the rendering of a PDF for the specified invoice and
     * returns the `documentFileId`, which can be used to download the PDF using the Files Endpoint.
     *
     * ### Usage Example:
     * ```php
     * $invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $response = $lexofficeInvoiceManager->renderDocument($invoiceId);
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
     * @param string $invoiceId The ID of the invoice to render.
     * @return array Response array with 'success', 'data', or 'error' details.
     */
    public function renderDocument(string $invoiceId)
    {
        try {
            // Make the GET request to trigger PDF rendering
            $response = $this->client->get("invoices/{$invoiceId}/document");

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
     * Generate a view deeplink for an invoice.
     *
     * This method generates a URL to view the invoice directly.
     *
     * ### Usage Example:
     * ```php
     * $invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $viewUrl = $lexofficeInvoiceManager->getViewDeeplink($invoiceId);
     * echo "View Invoice URL: " . $viewUrl;
     * ```
     *
     * @param string $invoiceId The ID of the invoice.
     * @return string The URL to view the invoice.
     */
    public function getViewDeeplink(string $invoiceId): string
    {
        return config('lexoffice.base_uri') . "permalink/invoices/view/{$invoiceId}";
    }

    /**
     * Generate an edit deeplink for an invoice.
     *
     * This method generates a URL to edit the invoice directly.
     *
     * ### Usage Example:
     * ```php
     * $invoiceId = 'e9066f04-8cc7-4616-93f8-ac9ecc8479c8';
     * $editUrl = $lexofficeInvoiceManager->getEditDeeplink($invoiceId);
     * echo "Edit Invoice URL: " . $editUrl;
     * ```
     *
     * @param string $invoiceId The ID of the invoice.
     * @return string The URL to edit the invoice.
     */
    public function getEditDeeplink(string $invoiceId): string
    {
        return config('lexoffice.base_uri') . "permalink/invoices/edit/{$invoiceId}";
    }
}
