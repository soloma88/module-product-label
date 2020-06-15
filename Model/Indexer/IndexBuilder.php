<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model\Indexer;

use Magento\CatalogRule\Model\Rule;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Rmg\ProductLabel\Model\ProductLabelRepository;

/**
 * TODO: this is only skeleton of indexer.
 * It is not working for now and some things are copied from default magento rule indexer
 */
class IndexBuilder
{
    public const INDEX_TABLE_NAME = 'product_label_index';

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var AdapterInterface
     */
    private $connection;
    /**
     * @var ProductLabelRepository
     */
    private $productLabelRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $criteriaBuilder;

    public function __construct(
        ResourceConnection $resource,
        ProductLabelRepository $productLabelRepository,
        SearchCriteriaBuilder $criteriaBuilder
    ) {
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->productLabelRepository = $productLabelRepository;
        $this->criteriaBuilder = $criteriaBuilder;
    }

    /**
     * Reindex by ids. Template method
     *
     * @param array $ids
     * @return void
     */
    protected function doReindexByIds($ids)
    {
        $this->cleanProductIndex($ids);

        /** @var Rule[] $activeRules */
        $activeLabels = $this->getActiveLabels();
        foreach ($activeLabels as $rule) {
            $rule->setProductsFilter($ids);
            $this->reindexRuleProduct->execute($rule, $this->batchCount);
        }

        $this->reindexRuleGroupWebsite->execute();
    }

    public function getActiveLabels(): array
    {
        $criteria = $this->criteriaBuilder->addFilter('is_active', 1)->create();

        return $this->productLabelRepository->getList($criteria)->getItems();
    }

    /**
     * Clean product index
     *
     * @param array $productIds
     * @return void
     */
    private function cleanProductIndex(array $productIds): void
    {
        $where = ['product_id IN (?)' => $productIds];
        $this->connection->delete($this->getTable(self::INDEX_TABLE_NAME), $where);
    }

    /**
     * @param $tableName
     *
     * @return string
     */
    private function getTable($tableName): string
    {
        return $this->resource->getTableName($tableName);
    }
}
