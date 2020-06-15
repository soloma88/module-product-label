<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model\ResourceModel;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class ProductLabel extends AbstractDb
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(
        Context $context,
        EntityManager $entityManager,
        ImageUploader $imageUploader,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->entityManager = $entityManager;
        $this->imageUploader = $imageUploader;
    }

    protected function _construct()
    {
        $this->_init('product_label_entity', 'entity_id');
    }

    protected function _beforeSave(AbstractModel $object): ProductLabel
    {
        $images = $object->getData('image');
        if (is_array($images)) {
            $image = array_shift($images);
            if (isset($image['file'])) {
                $this->imageUploader->moveFileFromTmp($image['file']);
            }
            $object->setData('image', $image['name']);
        }

        return parent::_beforeSave($object);
    }

    public function updateLabelToStoresRelations($labelId, array $storeIds): void
    {
        $select = $this->getConnection()->select()
            ->from('product_label_store')
            ->where('label_id = ?', $labelId);
        $relations = $this->getConnection()->fetchAll($select);
        $usedStores = array_column($relations, 'store_id');
        $relationsToDelete = array_diff($usedStores, $storeIds);
        $relationsToAdd = array_diff($storeIds, $usedStores);

        if (!empty($relationsToDelete)) {
            $this->deleteStoreRelations($labelId, $relationsToDelete);
        }

        if (!empty($relationsToAdd)) {
            $this->addStoreRelations($labelId, $relationsToAdd);
        }
    }

    private function deleteStoreRelations($labelId, array $storeIds): void
    {
        $this->getConnection()->delete(
            'product_label_store',
            $this->getConnection()->quoteInto('store_id' . ' IN (?) AND ', $storeIds)
            . $this->getConnection()->quoteInto('label_id' . ' = ?', $labelId)
        );
    }

    private function addStoreRelations($labelId, $storeIds): void
    {
        $data = [];
        foreach ($storeIds as $storeId) {
            $data[] = [
                'label_id' => $labelId,
                'store_id' => $storeId
            ];
        }
        $this->getConnection()->insertMultiple('product_label_store', $data);
    }

    /**
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object): ProductLabel
    {
        $this->beforeSave($object);
        $this->entityManager->save($object);

        return $this;
    }
}
