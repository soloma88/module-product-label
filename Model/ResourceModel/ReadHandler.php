<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model\ResourceModel;

use Magento\Framework\EntityManager\Operation\AttributeInterface;

class ReadHandler implements AttributeInterface
{
    public function execute($entityType, $entityData, $arguments = [])
    {
        return $entityData;
    }
}
