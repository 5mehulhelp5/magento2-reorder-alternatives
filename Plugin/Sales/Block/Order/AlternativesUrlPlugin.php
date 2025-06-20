<?php
declare(strict_types=1);

namespace Elgentos\ReorderAlternatives\Plugin\Sales\Block\Order;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Sales\Block\Order\History;

/**
 * Interceptor for @see \Magento\Sales\Block\Order\History
 */
class AlternativesUrlPlugin
{
    /**
     * Intercepted method getReorderUrl.
     *
     * @param History  $subject
     * @param callable $proceed
     * @param object   $order
     *
     * @return string
     * @see \Magento\Sales\Block\Order\History::getReorderUrl
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetReorderUrl(History $subject, callable $proceed, object $order): string
    {
        $itemCollection = $order->getItemsCollection();
        $nonSalableProducts = [];

        foreach ($itemCollection as $item) {
            if (!$item->getProduct()->isSalable()) {
                $nonSalableProducts[] = $item->getProductId();
            }
        }

        if (empty($nonSalableProducts)) {
            return $proceed($order);
        }

        return $subject->getUrl('sales/order/alternatives', [
            'orderId' => $order->getId(),
            'nonSalableProducts' => implode(',', $nonSalableProducts)
        ]);
    }
}
