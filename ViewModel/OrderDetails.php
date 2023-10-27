<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class OrderDetails implements ArgumentInterface
{
    const ADMIN_RESOURCE = 'Denal05_SwiftOtterOrderExport::OrderExport';

    /** @var ScopeConfigInterface */
    private ScopeConfigInterface $scopeConfig;
    /** @var FormKey */
    private FormKey $formKey;
    /** @var UrlInterface */
    private UrlInterface $urlBuilder;
    /** @var RequestInterface */
    private RequestInterface $request;
    /** @var AuthorizationInterface */
    private AuthorizationInterface $authorization;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param FormKey $formKey
     * @param UrlInterface $urlInterface
     * @param RequestInterface $request
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        FormKey $formKey,
        UrlInterface $urlInterface,
        RequestInterface $request,
        AuthorizationInterface $authorization,
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->formKey = $formKey;
        $this->urlBuilder = $urlInterface;
        $this->request = $request;
        $this->authorization = $authorization;
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        return $this->scopeConfig->isSetFlag('swiftotterorderexport/order_export/enabled')
            && $this->authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * @return string
     */
    public function getButtonMessage(): string
    {
        return (string) __('Send Order to Fulfillment');
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'sending_message' => __('Sending...'),
            'original_message' => $this->getButtonMessage(),
            'upload_url' => $this->urlBuilder->getUrl(
                'order_export/export/run',
                [
                    'order_id' => (int) $this->request->getParam('order_id')
                ]
            ),
            'form_key' => $this->formKey->getFormKey()
        ];

    }
}
