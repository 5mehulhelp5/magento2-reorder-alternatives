<?php

declare(strict_types=1);

namespace Elgentos\ReorderAlternatives\Controller\Order;

use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Controller\Result\RedirectFactory;

class AlternativeReorder extends Action
{
    private Cart $cart;
    private ProductRepositoryInterface $productRepository;

    protected $resultRedirectFactory;

    public function __construct(
        Context $context,
        Cart $cart,
        ProductRepositoryInterface $productRepository,
        RedirectFactory $resultRedirectFactory
    ) {
        parent::__construct($context);
        $this->cart = $cart;
        $this->productRepository = $productRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function execute()
    {
        $selectedProducts = $this->getRequest()->getParam('product', []);
        foreach ($selectedProducts as $productIds) {
            if (str_contains($productIds, '-')) {
                [$originalProductId, $productId] = explode('-',
                    $productIds);
            } else {
                $productId = $productIds;
            }
            try {
                $product = $this->productRepository->getById($productId);
                $this->cart->addProduct($product, 1); // Assume quantity 1
            } catch (NoSuchEntityException $e) {
                // Log or handle entity not found
            }
        }

        $this->cart->save();

        return $this->resultRedirectFactory->create()->setPath('checkout/cart');
    }
}
