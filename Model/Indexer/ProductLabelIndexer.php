<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model\Indexer;

use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;

class ProductLabelIndexer implements MviewActionInterface, IndexerActionInterface
{
    public const INDEXER_ID = 'product_label_index';

    public function execute($ids)
    {
        // TODO: Implement execute() method.
    }

    public function executeRow($id)
    {
        // TODO: Implement executeRow() method.
    }

    public function executeFull()
    {
        // TODO: Implement executeFull() method.
    }

    public function executeList(array $ids)
    {
        // TODO: Implement executeList() method.
    }
}
