<?php

declare(strict_types=1);

namespace App\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class NhtsaApiClient implements CarsClientInterface
{
    public function __construct(
        private HttpClientInterface $nhtsaClient,
    ) {
    }

    /**
     * @return BrandDto[]
     */
    public function getBrands(int $year = 2024): array
    {
        $response = $this->nhtsaClient->request('GET', 'SafetyRatings/modelyear/'.$year);

        $brands = array_map(fn ($brand) => new BrandDto($brand['Make']), $response->toArray()['Results']);

        return $brands;
    }
}
