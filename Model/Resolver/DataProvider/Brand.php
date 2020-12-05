<?php
/**
 * Copyright © Landofcoder All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\FormbuilderGraphQl\Model\Resolver\DataProvider;

use Lof\FormbuilderGraphQl\Api\FormbuilderRepositoryInterface;

class Brand
{
    /**
     * @var FormbuilderRepositoryInterface
     */
    private $formbuilderRepository;

    public function __construct(
        FormbuilderRepositoryInterface $formbuilderRepository
    )
    {
        $this->formbuilderRepository = $formbuilderRepository;
    }

    public function getBrand($brandId)
    {
        return $this->formbuilderRepository->get($brandId);
    }

}
