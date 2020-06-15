<?php
declare(strict_types=1);

namespace Rmg\ProductLabel\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SimpleDataObjectConverter;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Rmg\ProductLabel\Api\Data\ProductLabelInterface;
use Rmg\ProductLabel\Api\Data\ProductLabelSearchResultsInterface;
use Rmg\ProductLabel\Api\Data\ProductLabelSearchResultsInterfaceFactory;
use Rmg\ProductLabel\Api\ProductLabelRepositoryInterface;
use Rmg\ProductLabel\Model\ResourceModel\ProductLabel as ProductLabelResource;
use Rmg\ProductLabel\Model\ResourceModel\ProductLabel\CollectionFactory;

class ProductLabelRepository implements ProductLabelRepositoryInterface
{
    /**
     * @var ProductLabelFactory
     */
    protected $labelFactory;

    /**
     * @var ProductLabelResource
     */
    protected $labelResource;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var ProductLabelSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @param ProductLabelFactory $labelFactory
     * @param ProductLabelResource $labelResource
     * @param CollectionFactory $collectionFactory
     * @param ProductLabelSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ProductLabelFactory $labelFactory,
        ProductLabelResource $labelResource,
        CollectionFactory $collectionFactory,
        ProductLabelSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->labelFactory = $labelFactory;
        $this->labelResource = $labelResource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @param $labelId
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function get($labelId): ProductLabel
    {
        $label = $this->labelFactory->create();
        $this->labelResource->load($label, $labelId);
        if (!$label->getId()) {
            throw NoSuchEntityException::singleField(
                SimpleDataObjectConverter::snakeCaseToCamelCase(ProductLabelInterface::ENTITY_ID),
                $labelId
            );
        }

        return $label;
    }

    /**
     * @param ProductLabel $object
     *
     * @return ProductLabel
     * @throws CouldNotSaveException
     */
    public function save(ProductLabel $object): ProductLabel
    {
        try {
            $this->labelResource->save($object);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the label: %1', $exception->getMessage()),
                $exception
            );
        }

        return $object;
    }

    /**
     * @param ProductLabel $object
     *
     * @throws CouldNotDeleteException
     */
    public function delete(ProductLabel $object)
    {
        try {
            $this->labelResource->delete($object);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the label: %1', $exception->getMessage()),
                $exception
            );
        }
    }

    /**
     * @param int $labelId
     *
     * @throws CouldNotDeleteException
     */
    public function deleteById($labelId)
    {
        try {
            $this->labelResource->delete($this->get($labelId));
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the label: %1', $exception->getMessage()),
                $exception
            );
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return ProductLabelSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }
}
