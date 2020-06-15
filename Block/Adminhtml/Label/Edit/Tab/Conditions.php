<?php
/**
 * Copyright (c) Rmg Media, LLC. All rights reserved.
 */

declare(strict_types=1);

namespace Rmg\ProductLabel\Block\Adminhtml\Label\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset;
use Magento\CatalogRule\Api\Data\RuleInterface;
use Magento\CatalogRule\Model\RuleFactory;
use Magento\Framework\Data\Form as DataForm;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Rule\Block\Conditions as RuleConditions;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Rmg\ProductLabel\Api\Data\ProductLabelInterface;
use Rmg\ProductLabel\Service\GetCurrentLabelService;

class Conditions extends Generic implements TabInterface
{
    /**
     * @var Fieldset
     */
    protected $rendererFieldset;

    /**
     * @var RuleConditions
     */
    protected $conditions;

    /**
     * @var RuleFactory
     */
    private $ruleFactory;
    /**
     * @var GetCurrentLabelService
     */
    private $getCurrentLabelService;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param RuleConditions $conditions
     * @param Fieldset $rendererFieldset
     * @param RuleFactory $ruleFactory
     * @param GetCurrentLabelService $getCurrentLabelService
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        RuleConditions $conditions,
        Fieldset $rendererFieldset,
        RuleFactory $ruleFactory,
        GetCurrentLabelService $getCurrentLabelService,
        array $data = []
    ) {
        $this->rendererFieldset = $rendererFieldset;
        $this->conditions = $conditions;
        $this->ruleFactory = $ruleFactory;
        $this->getCurrentLabelService = $getCurrentLabelService;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare content for tab
     *
     * @return Phrase
     * @codeCoverageIgnore
     */
    public function getTabLabel(): Phrase
    {
        return __('Conditions');
    }

    /**
     * Prepare title for tab
     *
     * @return Phrase
     * @codeCoverageIgnore
     */
    public function getTabTitle(): Phrase
    {
        return __('Conditions');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return bool
     */
    public function canShowTab(): bool
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return false;
    }

    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass(): ?string
    {
        return null;
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl(): ?string
    {
        return null;
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isAjaxLoaded(): bool
    {
        return false;
    }

    /**
     * @return Form
     */
    protected function _prepareForm()
    {
        $form = $this->addTabToForm();
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @param RuleInterface $model
     * @param string $fieldsetId
     * @param string $formName
     * @return DataForm
     * @throws LocalizedException
     */
    protected function addTabToForm($fieldsetId = 'conditions_fieldset', $formName = 'product_label_form')
    {
        /** @var DataForm $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');
        $ruleModel = $this->ruleFactory->create();

        /** @var ProductLabelInterface $currentLabel */
        $currentLabel = $this->getCurrentLabelService->execute();

        if ($currentLabel && $currentLabel->getConditionsSerialized()) {
            $ruleModel->setConditions([]);
            $ruleModel->setConditionsSerialized($currentLabel->getConditionsSerialized());
            $ruleModel->getConditions()->setJsFormObject('rule_conditions_fieldset');
        }

        $newChildUrl = $this->getUrl(
            'catalog_rule/promo_catalog/newConditionHtml/form/rule_conditions_fieldset',
            ['form_namespace' => $formName]
        );

        $renderer = $this->rendererFieldset->setTemplate('Magento_CatalogRule::promo/fieldset.phtml')
            ->setNewChildUrl($newChildUrl);

        $fieldset = $form->addFieldset(
            $fieldsetId,
            ['legend' => __('Conditions (don\'t add conditions if rule is applied to all products)')]
        )->setRenderer($renderer);

        $fieldset->addField(
            'conditions',
            'text',
            [
                'name' => 'conditions',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'required' => true,
                'data-form-part' => $formName
            ]
        )
            ->setRule($ruleModel)
            ->setRenderer($this->conditions);

        $form->setValues($ruleModel->getData());

        return $form;
    }
}
