<?php
/**
 * Copyright © Landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\FormbuilderGraphQl\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Lof\Formbuilder\Api\SubmitFormRepositoryInterface;
use Lof\Formbuilder\Api\Data\SubmitFormFieldInterfaceFactory;
use Lof\Formbuilder\Api\Data\SubmitFormInterfaceFactory;
use Lof\Formbuilder\Api\Data\SubmitFormInterface;

class SubmitForm implements ResolverInterface
{

    /**
     * @var SubmitFormRepositoryInterface
     */
    private $submitFormRepository;

    /**
     * @var SubmitFormFieldInterfaceFactory
     */
    private $submitFormFieldFactory;

    /**
     * @var SubmitFormInterfaceFactory
     */
    private $submitFormFactory;

    /**
     * construct
     *
     * @param SubmitFormRepositoryInterface $submitFormRepository
     * @param SubmitFormFieldInterfaceFactory $submitFormFieldFactory
     * @param SubmitFormInterfaceFactory $submitFormFactory
     */
    public function __construct(
        SubmitFormRepositoryInterface $submitFormRepository,
        SubmitFormFieldInterfaceFactory $submitFormFieldFactory,
        SubmitFormInterfaceFactory $submitFormFactory
    )
    {
        $this->submitFormRepository = $submitFormRepository;
        $this->submitFormFieldFactory = $submitFormFieldFactory;
        $this->submitFormFactory = $submitFormFactory;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        /** @phpstan-ignore-next-line */
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }
        if (empty($args['input'] || empty($args["input"]["form_id"])) || empty($args["input"]["fields"])) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }

        $submitFormData = $this->formatSubmitFormData($args['input']);

        if (!$submitFormData->getFields()) {
            throw new GraphQlInputException(__('"fields" value should be specified'));
        }

        $customerId = $context->getUserId();
        $store = $context->getExtensionAttributes()->getStore();

        return $this->submitFormRepository->submitForm((int)$customerId, $submitFormData, $store->getId());
    }

    /**
     * format submit form data
     *
     * @param mixed|array $input
     * @return SubmitFormInterface
     */
    protected function formatSubmitFormData($input = []): SubmitFormInterface
    {
        $submitFormData = $this->submitFormFactory->create();
        $submitFormData->setFormId((int)$input["form_id"]);
        $submitFormData->setProductId(isset($input["product_id"]) ? (int)$input["product_id"] : 0);
        $submitFormData->setCaptcha(isset($input["captcha"]) ? $input["captcha"] : "");

        $fields = [];
        foreach ($input["fields"] as $_item) {
            if (!isset($_item["cid"]) || empty($_item["cid"])) {
                continue;
            }
            $formFieldData = $this->submitFormFieldFactory->create();
            $formFieldData->setCid($_item["cid"]);
            $formFieldData->setFieldName(isset($_item["field_name"]) ? $_item["field_name"] : "");
            $formFieldData->setValue(isset($_item["value"]) ? $_item["value"] : "");

            $fields[] = $formFieldData;
        }

        $submitFormData->setFields($fields);

        return $submitFormData;
    }
}
