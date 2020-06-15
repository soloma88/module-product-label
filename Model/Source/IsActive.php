<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    private const ENABLED = '1';
    private const DISABLED = '0';

    /**
     * {@inheritDoc}
     */
    public function toOptionArray(): array
    {
        return [
            [
                'label' => __('Enabled'),
                'value' => self::ENABLED

            ],
            [
                'label' => __('Disabled'),
                'value' => self::DISABLED
            ]
        ];
    }
}
