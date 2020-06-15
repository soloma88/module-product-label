<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model\ResourceModel\ProductLabel;

use Dotdigitalgroup\Email\Model\Connector\Product;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Rmg\ProductLabel\Model\ProductLabel;
use Rmg\ProductLabel\Model\ResourceModel\ProductLabel as ProductLabelResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ProductLabel::class, ProductLabelResource::class);
    }
}
