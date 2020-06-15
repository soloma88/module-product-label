<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model\Label;

use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Rmg\ProductLabel\Model\ProductLabelRepository;
use Rmg\ProductLabel\Model\ProductLabel;
use Rmg\ProductLabel\Model\ResourceModel\ProductLabel\Collection;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var ImageUploader
     */
    private $imageUploader;
    /**
     * @var Mime
     */
    private $mime;
    /**
     * @var ProductLabelRepository
     */
    private $productLabelRepository;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ProductLabelRepository $productLabelRepository
     * @param RequestInterface $request
     * @param Collection $collection
     * @param Filesystem $filesystem
     * @param ImageUploader $imageUploader
     * @param Mime $mime
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ProductLabelRepository $productLabelRepository,
        RequestInterface $request,
        Collection $collection,
        Filesystem $filesystem,
        ImageUploader $imageUploader,
        Mime $mime,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->filesystem = $filesystem;
        $this->imageUploader = $imageUploader;
        $this->mime = $mime;
        $this->productLabelRepository = $productLabelRepository;
        $this->request = $request;
        $this->collection = $collection;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $labelId = $this->request->getParam($this->requestFieldName);
        if (!$labelId) {
            return [];
        }

        try {
            $label = $this->productLabelRepository->get($labelId);
        } catch (NoSuchEntityException $e) {
            return [];
        }

        /** @var ProductLabel $label */
        $labelData = $label->getData();
        if ($labelData['image']) {
            $imageName = $labelData['image'];
            /**  @TODO refactor */
            $directory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
            $mediaPath = $directory->getAbsolutePath();
            $relativePath = $this->imageUploader->getFilePath($this->imageUploader->getBasePath(), $imageName);
            $imagePath = $mediaPath . $relativePath;
            if (!$directory->isFile($imagePath)) {
                $labelData['image'] = [];
                $this->loadedData[$label->getId()] = $labelData;
                return $this->loadedData;
            }
            /** Collect required data for image preview */
            $image[0]['name'] = $imageName;
            $image[0]['url']  = DIRECTORY_SEPARATOR . DirectoryList::MEDIA . DIRECTORY_SEPARATOR . $relativePath;
            $image[0]['size'] = filesize($imagePath);
            $image[0]['type'] = $this->mime->getMimeType($imagePath);
            /** set data for image preview */
            $labelData['image'] = $image;
        }
        $this->loadedData[$label->getId()] = $labelData;

        return $this->loadedData;
    }
}
