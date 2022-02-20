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

class SubmitForm implements ResolverInterface
{

    /**
     * @var SubmitFormRepositoryInterface
     */
    private $submitFormRepository;

    /**
     * construct
     *
     * @param SubmitFormRepositoryInterface $submitFormRepository
     */
    public function __construct(
        SubmitFormRepositoryInterface $submitFormRepository
    )
    {
        $this->submitFormRepository = $submitFormRepository;
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
        if (empty($args['input'])) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }

        $customerId = $context->getUserId();
        $store = $context->getExtensionAttributes()->getStore();

        return $this->submitFormRepository->submitForm((int)$customerId, $args['input'], $store->getId());
    }
}
