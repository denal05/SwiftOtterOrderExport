<?php
declare(strict_types=1);

namespace Denal05\SwiftOtterOrderExport\Collector;

use Denal05\SwiftOtterOrderExport\Api\DataCollectorInterface;
use Denal05\SwiftOtterOrderExport\Model\HeaderData as HeaderDataModel;
use Magento\Backend\Model\Search\Order;
use Magento\Bundle\Test\Fixture\OrderItem;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;

class ItemData implements DataCollectorInterface
{
    /** @var string[] */
    private $allowedProductTypes;
    private ProductCollectionFactory $productCollectionFactory;

    public function __construct(
        array                  $allowedProductTypes,
        ProductCollectionFactory $productCollectionFactory
    ) {
        $this->allowedProductTypes = $allowedProductTypes;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    public function collect(OrderInterface $order, HeaderDataModel $headerData,): array
    {
        $output = [];

        $items = $order->getItems();

        // We have two ways to filter products by product type:
        // 1) Using array_filter with a callable
        $items = array_filter($items, function(OrderItemInterface $orderItem) {
            return in_array(
                $this->getProductTypeFor($orderItem->getProductId()),
                $this->allowedProductTypes
            );
        });

        // 2) Using in_array to check for the product type within a foreach
        ///** @var OrderItemInterface $item */
        //foreach ($items as $item) {
        //    //if(!in_array($item->getProductType(), $this->allowedProductTypes)) {
        //    //    continue;
        //    //}
        //
        //    $output[] = [
        //        'sku'           => $item->getSku(), // $this->productResource->getSkuById($item->getProductId()),
        //        'qty'           => $item->getQtyOrdered(),
        //        'item_price'    => $item->getBasePrice(),
        //        'item_cost'     => $item->getBaseCost(),
        //        'total'         => $item->getBaseRowTotal()
        //    ];
        //}

        return array_map(function(OrderItemInterface $item) {
            return [
                'sku'           => $item->getSku(), // $this->productResource->getSkuById($item->getProductId()),
                'qty'           => $item->getQtyOrdered(),
                'item_price'    => $item->getBasePrice(),
                'item_cost'     => $item->getBaseCost(),
                'total'         => $item->getBaseRowTotal()
            ];
        }, $items);
    }

    private function getProductTypeFor(int $productId): string
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['eq' => $productId]);

        // @TODO Set a breakpoint inside the __toString method and observer the SQL query.
        // echo (string) $collection->getSelect()->__toString();

        /** @var Product $product */
        $product = $collection->getFirstItem();

        if(!$product->getId()) {
            throw new NoSuchEntityException(__('This product doesn\'t exist.'));
        }

        return (string) $product->getTypeId();
    }
}
