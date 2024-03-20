<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Collector;

use Denal05\SwiftOtterOrderExport\Api\DataCollectorInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Denal05\SwiftOtterOrderExport\Model\HeaderData as HeaderDataModel;

class HeaderData implements DataCollectorInterface
{

    #[\Override] public function collect(
        OrderInterface $order,
        HeaderDataModel $headerDataModel
    ): array
    {
        // TODO: Implement collect() method.

        return [
            'order_id' => $order->getIncrementId(),
            'ship_date' => $headerDataModel->getShipDate()->format('Y-m-d')
        ];
    }
}
