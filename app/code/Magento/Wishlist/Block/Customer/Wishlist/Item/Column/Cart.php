<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Wishlist\Block\Customer\Wishlist\Item\Column;

use Magento\Catalog\Controller\Adminhtml\Product\Initialization\StockDataFilter;

/**
 * Wishlist block customer item cart column
 *
 * @api
 * @since 100.0.2
 */
class Cart extends \Magento\Wishlist\Block\Customer\Wishlist\Item\Column
{
    /**
     * @var \Magento\Catalog\Block\Product\View
     */
    protected $productView;

    /**
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     * @param \Magento\Catalog\Block\Product\View $productView
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Block\Product\View $productView = null,
        array $data = []
    ) {
        $this->productView = $productView ?:
                \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Catalog\Block\Product\View::class);
        parent::__construct($context, $httpContext, $data);
    }
    /**
     * Returns qty to show visually to user
     *
     * @param \Magento\Wishlist\Model\Item $item
     * @return float
     */
    public function getAddToCartQty(\Magento\Wishlist\Model\Item $item)
    {
        $qty = $item->getQty();
        $qty = $qty < $this->productView->getProductDefaultQty($this->getProductItem())
                ? $this->productView->getProductDefaultQty($this->getProductItem()) : $qty ;
        return $qty ? $qty : 1;
    }

    /**
     * Return product for current item
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProductItem()
    {
        return $this->getItem()->getProduct();
    }

    /**
     * Get min and max qty for wishlist form.
     *
     * @return array
     */
    public function getMinMaxQty()
    {
        $stockItem = $this->stockRegistry->getStockItem(
            $this->getItem()->getProduct()->getId(),
            $this->getItem()->getProduct()->getStore()->getWebsiteId()
        );

        $params = [];

        $params['minAllowed'] = (float)$stockItem->getMinSaleQty();
        if ($stockItem->getMaxSaleQty()) {
            $params['maxAllowed'] = (float)$stockItem->getMaxSaleQty();
        } else {
            $params['maxAllowed'] = (float)StockDataFilter::MAX_QTY_VALUE;
        }

        return $params;
    }
}
