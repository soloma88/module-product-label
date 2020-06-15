<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rmg\ProductLabel\Api\Data\ProductLabelSearchResultsInterface;
use Rmg\ProductLabel\Model\ProductLabel;

interface ProductLabelRepositoryInterface
{
    /**
     * Get Label
     *
     * @param $labelId
     *
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function get($labelId);

    /**
     * Create or update a data
     *
     * @param ProductLabel $object
     */
    public function save(ProductLabel $object);

    /**
     * Delete label.
     *
     * @param ProductLabel $object
     */
    public function delete(ProductLabel $object);

    /**
     * @param int $labelId
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($labelId);

    /**
     * Load label data collection by given search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return ProductLabelSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
