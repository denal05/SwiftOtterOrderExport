<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Action;

use Magento\Framework\Exception\NoSuchEntityException;

class PushDetailsToWebservice
{
    public function execute(int $orderId, array $orderDetails)
    {
        if (rand(1, 100) < 10) {
            throw new NoSuchEntityException( __('This order was malformed.') );
        }
    }
}
