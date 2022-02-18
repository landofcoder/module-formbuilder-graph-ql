<?php
/**
 * Copyright © Landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\FormbuilderGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Lof\Formbuilder\Api\FormbuilderRepositoryInterface;

class Message implements ResolverInterface
{
    /**
     * @var FormbuilderRepositoryInterface
     */
    private $formbuilder;

    /**
     * construct
     *
     * @param FormbuilderRepositoryInterface $formbuilderRepository
     */
    public function __construct(
        FormbuilderRepositoryInterface $formbuilderRepository
    )
    {
        $this->formbuilder = $formbuilderRepository;
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
        if (empty($args['message_id'])) {
            throw new GraphQlInputException(__('"message_id" value should be specified'));
        }
        $customer_id = $context->getUserId();
        return $this->formbuilder->getMyMessageById((int)$customer_id, (int)$args['message_id']);
    }
}
