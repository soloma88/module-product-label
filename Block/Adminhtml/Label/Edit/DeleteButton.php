<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Block\Adminhtml\Label\Edit;

class DeleteButton extends GenericButton
{
    /**
     * Retrieve button-specified settings
     *
     * @return array
     */
    public function getButtonData(): array
    {
        $data = [];
        if ($this->getLabelId()) {
            $data = [
                'label' => __('Delete Label'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * URL to send delete requests to.
     *
     * @return string
     */
    public function getDeleteUrl(): string
    {
        return $this->getUrl('*/*/delete', ['entity_id' => $this->getLabelId()]);
    }
}
