<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model;

use Magento\Framework\Model\AbstractModel;
use Rmg\ProductLabel\Api\Data\ProductLabelInterface;

class ProductLabel extends AbstractModel implements ProductLabelInterface
{
    public function _construct()
    {
        $this->_init(ResourceModel\ProductLabel::class);
    }

    public function setIsActive(bool $isActive)
    {
        $this->setData(self::IS_ACTIVE, $isActive);
    }

    public function setStores(array $stores)
    {
        $this->setData(self::STORES, $stores);
    }

    public function getStores()
    {
        return $this->_getData(self::STORES);
    }

    public function getImage()
    {
        return $this->_getData(self::IMAGE);
    }

    public function getName(): string
    {
        return $this->_getData(self::NAME);
    }

    public function setName(string $name)
    {
        $this->setData(self::NAME, $name);
    }

    public function setImage($image)
    {
        $this->setData(self::IMAGE, $image);
    }

    public function getIsActive(): bool
    {
        return (bool) $this->_getData(self::IS_ACTIVE);
    }

    public function getEntityId(): ?string
    {
        return $this->_getData(self::ENTITY_ID);
    }

    public function setConditionsSerialized(string $conditions)
    {
        $this->setData(self::CONDITIONS_SERIALIZED, $conditions);
    }

    public function getConditionsSerialized(): ?string
    {
        return $this->_getData(self::CONDITIONS_SERIALIZED);
    }
}
