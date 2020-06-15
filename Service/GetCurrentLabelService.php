<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Service;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Rmg\ProductLabel\Model\ProductLabel;
use Rmg\ProductLabel\Model\ProductLabelRepository;

class GetCurrentLabelService
{
    /**
     * @var ProductLabelRepository
     */
    private $productLabelRepository;
    /**
     * @var RequestInterface
     */
    private $request;

    private $currentLabel;

    public function __construct(
        ProductLabelRepository $productLabelRepository,
        RequestInterface $request
    ) {
        $this->productLabelRepository = $productLabelRepository;
        $this->request = $request;
    }

    /**
     * @return ProductLabel|null
     */
    public function execute(): ?ProductLabel
    {
        if (!$this->currentLabel) {
            $labelId = $this->request->getParam('id');
            try {
                $this->currentLabel = $this->productLabelRepository->get($labelId);
            } catch (NoSuchEntityException $e) {
                return null;
            }
        }

        return $this->currentLabel;
    }
}
