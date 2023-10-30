<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Controller\Adminhtml\Export;

use Denal05\SwiftOtterOrderExport\Model\HeaderData;
use Denal05\SwiftOtterOrderExport\Model\HeaderDataFactory;
use Magento\Backend\App\Action as AdminAction;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Run extends AdminAction implements HttpPostActionInterface
{
    private JsonFactory $jsonFactory;
    private HeaderDataFactory $headerDataFactory;

    public function __construct(
        HeaderDataFactory $headerData,
        JsonFactory $jsonFactory,
        ActionContext $context,
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->headerDataFactory = $headerData;
    }

    public function execute()
    {
        $headerData = $this->headerDataFactory->create();
        $headerData->setShipDate(
            new \DateTime(
                $this->getRequest()->getParam(HeaderData::SHIP_DATE)
            )
        );
        $headerData->setMerchantNotes(
            htmlspecialchars(
                $this->getRequest()->getParam(HeaderData::MERCHANT_NOTES)
            )
        );

        return $this->jsonFactory->create();
    }
}
