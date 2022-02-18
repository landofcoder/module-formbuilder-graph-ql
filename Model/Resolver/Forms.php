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
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Lof\Formbuilder\Api\FormbuilderRepositoryInterface;
use Lof\Formbuilder\Model\Form;

class Forms implements ResolverInterface
{

    /**
     * @var GetCustomer
     */
    private $getCustomer;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FormbuilderRepositoryInterface
     */
    private $formbuilder;

    /**
     * @param FormbuilderRepositoryInterface $formbuilderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param GetCustomer $getCustomer
     */
    public function __construct(
        FormbuilderRepositoryInterface $formbuilderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        GetCustomer $getCustomer
    )
    {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
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
        if ($args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }
        if ($args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }
        $customerGroupId = 0;
        /** @var ContextInterface $context */
        if ($context->getExtensionAttributes()->getIsCustomer()) {
            $customer = $this->getCustomer->execute($context);
            $customerGroupId = $customer->getGroupId();
        }
        $store = $context->getExtensionAttributes()->getStore();
        $args["filter"]["status"] = ["eq" => Form::STATUS_ENABLED];
        $searchCriteria = $this->searchCriteriaBuilder->build( 'lof_formbuilder_form', $args );
        $searchCriteria->setCurrentPage( $args['currentPage'] );
        $searchCriteria->setPageSize( $args['pageSize'] );
        $searchResult = $this->formbuilder->getList( $searchCriteria, $customerGroupId, $store->getId() );
        $totalPages = $args['pageSize'] ? ((int)ceil($searchResult->getTotalCount() / $args['pageSize'])) : 0;

        return [
            'total_count' => $searchResult->getTotalCount(),
            'items'       => $searchResult->getItems(),
            'page_info' => [
                'page_size' => $args['pageSize'],
                'current_page' => $args['currentPage'],
                'total_pages' => $totalPages
            ]
        ];
    }
}
