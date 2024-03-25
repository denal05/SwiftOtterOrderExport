<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport;

use Denal05\SwiftOtterOrderExport\Action\PushDetailsToWebservice;
use Denal05\SwiftOtterOrderExport\Action\TransformOrderToArray;
use Denal05\SwiftOtterOrderExport\Model\HeaderData;
use Magento\Framework\Exception\NoSuchEntityException;
//use Magento\Webapi\Controller\Rest\RequestValidator;
use Psr\Log\LoggerInterface;

class Orchestrator
{
    private TransformOrderToArray $orderToArray;
    //private RequestValidator $requestValidator;
    private PushDetailsToWebservice $pushDetailsToWebservice;
    private LoggerInterface $logger;

    public function __construct(
        TransformOrderToArray $orderToArray,
        //RequestValidator $requestValidator,
        PushDetailsToWebservice $pushDetailsToWebservice,
        LoggerInterface $logger
    ) {
        $this->orderToArray = $orderToArray;
        //$this->requestValidator = $requestValidator;
        $this->pushDetailsToWebservice = $pushDetailsToWebservice;
        $this->logger = $logger;
    }

    public function run(
        int $orderId,
        HeaderData $headerData
    ): array {
        // throw new NoSuchEntityException(__('There was an error: no such entity'));

        $results = [
            'success' => false,
            'error' => null
        ];

//        if (!$this->requestValidator->isValid($orderId)) {
//            $results['error'] = (string) __('The order ID provided was invalid.');
//            return $results;
//        }

        $orderDetails = $this->orderToArray->execute($orderId, $headerData);

        try {
            $this->pushDetailsToWebservice->execute($orderId, $orderDetails);
        } catch (NoSuchEntityException $exception) {
            $results['error'] = (string) $exception->getMessage(); // NoSuchEntityException
            $results['success'] = false;
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage(), [
                'trace' => $exception->getTraceAsString()
            ]);
            $results['error'] = (string) $exception->getMessage(); // general Exception
            $results['success'] = false;
        }
        return $results;
    }
}
