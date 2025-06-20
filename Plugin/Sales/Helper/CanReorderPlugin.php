<?php
declare(strict_types=1);

namespace Elgentos\ReorderAlternatives\Plugin\Sales\Helper;

use Magento\Sales\Helper\Reorder;

/**
 * Interceptor for @see \Magento\Sales\Helper\Reorder
 */
class CanReorderPlugin
{
    /**
     * @param Reorder  $subject
     * @param callable $proceed
     * @param int      $orderId
     *
     * @return bool
     *
     * This method now always returns true because we want to show alternatives
     */
    public function aroundCanReorder(
        Reorder $subject,
        callable $proceed,
        int $orderId
    ): bool {
        return true;
    }
}
