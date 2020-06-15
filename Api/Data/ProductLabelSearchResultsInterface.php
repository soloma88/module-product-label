<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ProductLabelSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get items list.
     *
     * @return ProductLabelInterface[]
     */
    public function getItems();

    /**
     * Set items list.
     *
     * @param ProductLabelInterface[] $items
     *
     * @return ProductLabelSearchResultsInterface
     */
    public function setItems(array $items);
}
