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
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\GraphQl\Model\Query\ContextInterface;
use Lof\Formbuilder\Api\FormbuilderRepositoryInterface;

class Form implements ResolverInterface
{

    /**
     * @var GetCustomer
     */
    private $getCustomer;

    /**
     * @var FormbuilderRepositoryInterface
     */
    private $formbuilder;

    /**
     * construct
     *
     * @param FormbuilderRepositoryInterface $formbuilderRepository
     * @param GetCustomer $getCustomer
     */
    public function __construct(
        FormbuilderRepositoryInterface $formbuilderRepository,
        GetCustomer $getCustomer
    )
    {
        $this->formbuilder = $formbuilderRepository;
        $this->getCustomer = $getCustomer;
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

        if (empty($args['form_id'])) {
            throw new GraphQlInputException(__('"form_id" value should be specified'));
        }

        $customerGroupId = 0;
        /** @var ContextInterface $context */
        if ($context->getExtensionAttributes()->getIsCustomer()) {
            $customer = $this->getCustomer->execute($context);
            $customerGroupId = $customer->getGroupId();
        }
        $store = $context->getExtensionAttributes()->getStore();
        return $this->formbuilder->getAvailableFormById((int)$args['form_id'], $customerGroupId, $store->getId());
    }
}
