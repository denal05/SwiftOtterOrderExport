<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Collector;

use Denal05\SwiftOtterOrderExport\Api\DataCollectorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Config\Scope;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Denal05\SwiftOtterOrderExport\Model\HeaderData as HeaderDataModel;
use Magento\Sales\Api\OrderAddressRepositoryInterface;

class HeaderData implements DataCollectorInterface
{
    private ScopeConfigInterface $scopeConfig;
    private OrderAddressRepositoryInterface $orderAddressRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        OrderAddressRepositoryInterface $orderAddressRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->orderAddressRepository = $orderAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    #[\Override] public function collect(
        OrderInterface $order,
        HeaderDataModel $headerDataModel
    ): array
    {
        $output = [
            'password'  =>  $this->scopeConfig->getValue('swiftotterorderexport/order_export/password'),
            'id'        =>  $order->getIncrementId(),
            'currency'  =>  $order->getBaseCurrencyCode(),

            'customer_notes'    =>  $order->getExtensionAttributes()->getBoldOrderComment(),
            'merchant_notes'    =>  $headerDataModel->getMerchantNotes() ,
            'discount'          =>  $order->getBaseDiscountAmount(),
            'total'             =>  $order->getBaseGrandTotal()
        ];

        $address = $this->getShippingAddress($order);
        if($address) {
            $output['shipping'] = [
                'name'      =>  $address->getFirstname() . ' ' . $address->getLastname(),
                'street'    =>  $address->getStreet(),
                'city'      =>  $address->getCity(),
                'state'     =>  $address->getRegionCode(),
                'postcode'  =>  $address->getPostcode(),
                'country'   =>  $address->getCountryId(),
                'amount'    =>  $order->getBaseShippingAmount(),
                'method'    =>  $order->getShippingDescription(),
                'ship_on'   =>  $headerDataModel->getShipDate()->format('Y-m-d')
            ];
        }

        return $output;
    }

    private function getShippingAddress(OrderInterface $order): ?OrderAddressInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('parent_id', $order->getEntityId(), 'eq')
            ->addFilter('address_type', 'shipping', 'eq')
            ->create();

        $addresses = $this->orderAddressRepository->getList($searchCriteria)->getItems();

        return count($addresses) ? reset($addresses) : null;
    }
}
