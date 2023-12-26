<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Controller\Adminhtml\Export;

use Denal05\SwiftOtterOrderExport\Model\HeaderData;
use Denal05\SwiftOtterOrderExport\Model\HeaderDataFactory;
use Denal05\SwiftOtterOrderExport\Orchestrator;
use Magento\Backend\App\Action as AdminAction;
use Magento\Backend\App\Action\Context as ActionContext;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Run extends AdminAction implements HttpPostActionInterface
{
    private JsonFactory $jsonFactory;
    private HeaderDataFactory $headerDataFactory;
    private Orchestrator $orchestrator;

    public function __construct(
        HeaderDataFactory $headerData,
        Orchestrator $orchestrator,
        JsonFactory $jsonFactory,
        ActionContext $context,
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->headerDataFactory = $headerData;
        $this->orchestrator = $orchestrator;
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

        try {
            $this->orchestrator->run(
                $this->getRequest()->getParam('order_id'),
                $headerData
            );
        } catch(NoSuchEntityException $exception) {

        }

        return $this->jsonFactory->create();
    }
}
