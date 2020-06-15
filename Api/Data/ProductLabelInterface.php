<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Api\Data;

interface ProductLabelInterface
{
    public const NAME = 'name';
    public const ENTITY_ID = 'entity_id';
    public const IMAGE = 'image';
    public const STORES = 'stores';
    public const IS_ACTIVE = 'is_active';
    public const CONDITIONS_SERIALIZED = 'conditions_serialized';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return mixed
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getEntityId(): ?string;

    /**
     * @return mixed
     */
    public function getImage();

    /**
     * @return void
     */
    public function setImage($image);

    /**
     * @return bool
     */
    public function getIsActive(): bool;

    /**
     * @param bool $isActive
     * @return mixed
     */
    public function setIsActive(bool $isActive);

    /**
     * @return string
     */
    public function getStores();

    /**
     * @param array $stores
     * @return mixed
     */
    public function setStores(array $stores);

    /**
     * @return string
     */
    public function getConditionsSerialized(): ?string;

    /**
     * @param string $conditions
     * @return mixed
     */
    public function setConditionsSerialized(string $conditions);
}
