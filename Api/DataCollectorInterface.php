<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Api;

use Denal05\SwiftOtterOrderExport\Model\HeaderData;
use Magento\Sales\Api\Data\OrderInterface;

interface DataCollectorInterface
{
    public function collect(
        OrderInterface $order,
        HeaderData $headerData,
    ): array;
}
