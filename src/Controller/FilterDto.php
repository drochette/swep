<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Validator\Constraints as Assert;

class FilterDto
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $label = null,
    ) {
    }
}
