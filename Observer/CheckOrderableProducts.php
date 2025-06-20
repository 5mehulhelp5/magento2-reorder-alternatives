<?php

declare(strict_types=1);

namespace Elgentos\ReorderAlternatives\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Response\Http as ResponseHttp;

class CheckOrderableProducts implements ObserverInterface
{
    private OrderRepositoryInterface $orderRepository;
    private CollectionFactory $productCollectionFactory;
    private ResponseHttp $response;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CollectionFactory $productCollectionFactory,
        ResponseHttp $response
    ) {
        $this->orderRepository = $orderRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->response = $response;
    }

    public function execute(Observer $observer): void
    {
        /** @var Action $action */
        $action = $observer->getEvent()->getData('controller_action');
        $orderId = (int)$action->getRequest()->getParam('order_id');


    }
}
