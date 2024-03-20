<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport;

use Denal05\SwiftOtterOrderExport\Action\TransformOrderToArray;
use Denal05\SwiftOtterOrderExport\Model\HeaderData;
use Magento\Framework\Exception\NoSuchEntityException;

class Orchestrator
{
    private TransformOrderToArray $orderToArray;

    public function __construct(
        TransformOrderToArray $orderToArray
    ) {
        $this->orderToArray = $orderToArray;
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

        $orderDetails = $this->orderToArray->execute($orderId, $headerData);

        return $results;
    }
}
