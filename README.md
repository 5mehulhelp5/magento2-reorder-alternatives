# Elgentos_ReorderAlternatives
The Elgentos_ReorderAlternatives Magento 2 module is designed to enhance the reorder functionality by providing alternative products suggestions. This extension allows merchants to define alternatives for any product, increasing the likelihood of successful reorders even if the original product is unavailable.

## Features
- Define alternative products for any product in the catalog.
- When reordering from My Orders, shows alternatives when there are alternatives configured for out-of-stock products.
- Adds a product relation link type (Alternatives) to manage alternative relationships between products.
- Integrates seamlessly with Magento's sales and catalog modules.

## Screenshot

![Backend alternatives configuration](https://github.com/user-attachments/assets/01fd5cbe-5952-49e2-9549-f696fb6e933d)

## Installation

```
composer require elgentos/magento2-reorder-alternatives
bin/magento setup:upgrade
```
