<?php
/**
 * Copyright © landofcoder.com All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\FormbuilderGraphQl\Model\Resolver\Form;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;

/**
 * Retrieves the sort fields data
 */
class SortFields implements ResolverInterface
{
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $sortFieldsOptions = [
            ['label' => "form_id", 'value' => "form_id"],
            ['label' => "creation_time", 'value' => "creation_time"],
            ['label' => "identifier", 'value' => "identifier"],
            ['label' => "tags", 'value' => "tags"]
        ];

        $data = [
            'default' => "creation_time",
            'options' => $sortFieldsOptions,
        ];

        return $data;
    }
}
