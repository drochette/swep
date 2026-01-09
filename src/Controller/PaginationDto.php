<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationDto
{
    public function __construct(
        #[Assert\Valid()]
        public ?FilterDto $filters = null,
        #[Assert\Type('integer')]
        public int $page = 1,
        #[Assert\Type('integer')]
        #[Assert\LessThanOrEqual('100')]
        public int $limit = 10,
    ) {
    }
}
