<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Controller\Adminhtml\Label;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\CatalogRule\Model\RuleFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Serialize\SerializerInterface;
use Rmg\ProductLabel\Api\Data\ProductLabelInterface;
use Rmg\ProductLabel\Api\ProductLabelRepositoryInterface;
use Rmg\ProductLabel\Model\ProductLabelFactory;

class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Rmg_ProductLabel::label';

    /**
     * @var ProductLabelRepositoryInterface
     */
    private $productLabelRepository;
    /**
     * @var ProductLabelFactory
     */
    private $productLabelFactory;
    /**
     * @var RuleFactory
     */
    private $ruleFactory;
    /**
     * @var SerializerInterface
     */
    private $serialize;

    /**
     * @param Action\Context $context
     * @param ProductLabelRepositoryInterface $productLabelRepository
     * @param ProductLabelFactory $productLabelFactory
     * @param RuleFactory $ruleFactory
     * @param SerializerInterface $serialize
     */
    public function __construct(
        Action\Context $context,
        ProductLabelRepositoryInterface $productLabelRepository,
        ProductLabelFactory $productLabelFactory,
        RuleFactory $ruleFactory,
        SerializerInterface $serialize
    ) {
        parent::__construct($context);
        $this->productLabelRepository = $productLabelRepository;
        $this->productLabelFactory = $productLabelFactory;
        $this->ruleFactory = $ruleFactory;
        $this->serialize = $serialize;
    }

    /**
     * Execute action based on request and return result
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $data = $this->getRequest()->getParams();
        if ($data) {
            try {
                if (!empty($data[ProductLabelInterface::ENTITY_ID])) {
                    $model = $this->productLabelRepository->get($data[ProductLabelInterface::ENTITY_ID]);
                } else {
                    $model = $this->productLabelFactory->create();
                    unset($data[ProductLabelInterface::ENTITY_ID]);
                }
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This label no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($this->prepareData($data));

            try {
                $this->productLabelRepository->save($model);
                $this->messageManager->addSuccessMessage(__('Product Label saved successfully.'));
                return $this->processReturn($model, $data, $resultRedirect);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the label.'));
            }

            return $resultRedirect->setPath(
                '*/*/edit',
                ['id' => $data[ProductLabelInterface::ENTITY_ID]]
            );
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareData(array $data): array
    {
        if (!empty($data['rule']['conditions'])) {
            $rule = $this->ruleFactory->create();
            $rule->loadPost($data['rule']);

            $data['conditions_serialized'] = $this->serialize->serialize($rule->getConditions()->asArray());
        }

        return $data;
    }

    /**
     * @param ProductLabelInterface $model
     * @param array $data
     * @param Redirect $resultRedirect
     *
     * @return mixed
     */
    private function processReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/grid/');
        }
        return $resultRedirect;
    }
}
