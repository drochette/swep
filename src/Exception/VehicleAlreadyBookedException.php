<?php

declare(strict_types=1);

namespace App\Exception;

final class VehicleAlreadyBookedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('vehicle.already.booked');
    }
}
