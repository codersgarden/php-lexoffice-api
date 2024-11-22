<?php

namespace Codersgarden\PhpLexofficeApi;

use GuzzleHttp\Exception\RequestException;

class LexofficeOrderConfirmationManager extends LexofficeBase
{
    /**
     * Create a new instance of the LexofficeOrderConfirmationManager.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create an Order Confirmation in LexOffice.
     *
     * This method creates a new order confirmation in draft mode with the provided data.
     *
     * ### Required Fields:
     * - `voucherDate`: string (ISO 8601 date, e.g., "2023-02-22T00:00:00.000+01:00").
     * - `address`: array (Recipient's address details).
     * - `lineItems`: array (List of line items including name, type, and unit price).
     * - `totalPrice`: array (Total price details including currency).
     * - `taxConditions`: array (Tax type details such as `net`, `gross`, or `vatfree`).
     * - `shippingConditions`: array (Shipping details such as shipping date and type).
     *
     * ### Optional Fields:
     * - `title`, `introduction`, `remark`, `deliveryTerms`, `paymentConditions`.
     *
     * @param array $orderData The data for the order confirmation.
     * @return array The response data or error details.
     */
    public function create(array $orderData)
    {
        try {
            $response = $this->client->post('order-confirmations', ['json' => $orderData]);

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
     * Retrieve an Order Confirmation by ID.
     *
     * Fetches details of a specific order confirmation using its unique identifier.
     *
     * @param string $orderId The ID of the order confirmation.
     * @return array The response data or error details.
     */
    public function find(string $orderId)
    {
        try {
            $response = $this->client->get("order-confirmations/{$orderId}");

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
     * Render an Order Confirmation Document as PDF.
     *
     * Generates the PDF for the specified order confirmation and retrieves its `documentFileId`.
     *
     * @param string $orderId The ID of the order confirmation.
     * @return array The documentFileId or error details.
     */
    public function renderDocument(string $orderId)
    {
        try {
            $response = $this->client->get("order-confirmations/{$orderId}/document");

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
     * Pursue a Sales Voucher to an Order Confirmation.
     *
     * Creates a new order confirmation based on a preceding sales voucher.
     *
     * @param string $precedingSalesVoucherId The ID of the preceding sales voucher.
     * @param array $orderData The additional data for the new order confirmation.
     * @return array The response data or error details.
     */
    public function pursue(string $precedingSalesVoucherId, array $orderData)
    {
        try {
            $response = $this->client->post("order-confirmations?precedingSalesVoucherId={$precedingSalesVoucherId}", [
                'json' => $orderData,
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
     * Get Deeplink to an Order Confirmation.
     *
     * Generates the view or edit deeplink URL for the specified order confirmation.
     *
     * @param string $orderId The ID of the order confirmation.
     * @param string $type The type of deeplink to generate: 'view' or 'edit'.
     * @return string The deeplink URL.
     */
    public function getDeeplink(string $orderId, string $type = 'view')
    {
        $baseUrl = config('lexoffice.api_base_url');
        $deeplinkTypes = ['view', 'edit'];

        if (!in_array($type, $deeplinkTypes)) {
            throw new \InvalidArgumentException("Invalid deeplink type. Allowed values: 'view', 'edit'.");
        }

        return "{$baseUrl}/permalink/order-confirmations/{$type}/{$orderId}";
    }
}
