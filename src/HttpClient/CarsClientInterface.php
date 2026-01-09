<?php

declare(strict_types=1);

namespace App\HttpClient;

interface CarsClientInterface
{
    public function getBrands(): array;
}
