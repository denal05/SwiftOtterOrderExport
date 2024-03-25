<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Action;

use GuzzleHttp\Client;
use http\Exception\InvalidArgumentException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class PushDetailsToWebservice
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function execute(int $orderId, array $orderDetails)
    {
        try {
            return true;

            // Use GuzzleHttp (http://docs.guzzlephp.org/en/stable/) to send the data to our webservice.
            $client = new Client();
            $response = $client->post('https://swiftotter.com', [
                'json' => $orderDetails
            ]);

            $body = (string)$response->getBody();

            if ($response->getStatusCode() !== 200) {
                throw new InvalidArgumentException( __('There was a problem: ') . $body);
            }
        } catch (\Exception $exception) {
            $this->logger->critical($exception->getMessage(), [
                'order_id' => $orderId,
                'details' => $orderDetails
            ]);

            throw $exception;
        }
    }
}
