<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Action;

use Denal05\SwiftOtterOrderExport\Api\DataCollectorInterface;
use Denal05\SwiftOtterOrderExport\Model\HeaderData;
use Magento\Sales\Api\OrderRepositoryInterface;

class TransformOrderToArray
{
    private OrderRepositoryInterface $orderRepository;

    /** @var DataCollectorInterface[] */
    private $dataCollectors;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        array $dataCollectors
    ) {
        $this->orderRepository = $orderRepository;
        $this->dataCollectors = $dataCollectors;
    }

    public function execute(
        int $orderId,
        HeaderData $headerData
    ) {
        $order = $this->orderRepository->get($orderId);
        $output = [];

        foreach($this->dataCollectors as $collector) {
            if(!$collector instanceof DataCollectorInterface) {
                // throw new \Exception('Collectors must implement DataCollectorInterface');
                continue;
            }

            $output = array_merge($output, $collector->collect($order, $headerData));
        }

        return $output;
    }
}
