<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Api;

use Denal05\SwiftOtterOrderExport\Model\HeaderData as HeaderDataModel;
use Magento\Sales\Api\Data\OrderInterface;

interface DataCollectorInterface
{
    public function collect(
        OrderInterface $order,
        HeaderDataModel $headerData,
    ): array;
}
