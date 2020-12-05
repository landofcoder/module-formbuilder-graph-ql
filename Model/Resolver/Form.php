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
use Lof\Formbuilder\Api\FormbuilderRepositoryInterface;

class Form implements ResolverInterface
{


    /**
     * @var FormbuilderRepositoryInterface
     */
    private $formbuilder;

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
        return $this->formbuilder->getFormById($args['form_id'])['0']['data'];
    }
}
