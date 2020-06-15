<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model\ResourceModel;

use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\AttributeInterface;

class SaveHandler implements AttributeInterface
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;
    /**
     * @var ProductLabel
     */
    private $labelResource;

    public function __construct(MetadataPool $metadataPool, ProductLabel $productLabel)
    {
        $this->metadataPool = $metadataPool;
        $this->labelResource = $productLabel;
    }

    public function execute($entityType, $entityData, $arguments = [])
    {
        $linkField = $this->metadataPool->getMetadata($entityType)->getLinkField();
        if (isset($entityData['stores'])) {
            $storeIds = $entityData['stores'];
            $this->labelResource->updateLabelToStoresRelations($entityData[$linkField], $storeIds);
        }
        return $entityData;
    }
}
