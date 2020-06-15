<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Controller\Adminhtml\Label;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Rmg_ProductLabel::label';

    /**
     * Execute action based on request and return result
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Rmg_ProductLabel::label');
        $resultPage->getConfig()->getTitle()->prepend(__('Labels'));
        $resultPage->getConfig()->getTitle()->prepend($id ? __('Edit Label') : __('New Label'));

        return $resultPage;
    }
}
