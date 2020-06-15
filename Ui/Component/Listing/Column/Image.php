<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

namespace Rmg\ProductLabel\Ui\Component\Listing\Column;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Image extends Column
{
    const FOLDER_NAME = 'productlabel';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Image constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$fieldName . '_src'] = $this->getMediaUrl($item['image']);
                $item[$fieldName . '_orig_src'] = $this->getMediaUrl($item['image']);
                $item[$fieldName . '_alt'] = $item['name'];
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'productlabel/label/edit',
                    [
                        'image' => $item['image'],
                        'store' => $this->context->getRequestParam('store')
                    ]
                );
            }
        }

        return $dataSource;
    }

    /**
     * @param $file
     * @return string
     * @throws NoSuchEntityException
     */
    private function getMediaUrl($file): string
    {
        $file = ltrim(str_replace('\\', '/', $file), '/');
        return $this->storeManager->getStore()
                    ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . self::FOLDER_NAME . DIRECTORY_SEPARATOR . $file;
    }
}
